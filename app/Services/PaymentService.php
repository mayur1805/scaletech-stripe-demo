<?php
namespace App\Services;

use Stripe\Stripe;
use Stripe\PaymentIntent;  
use App\Models\Payment; 
use App\Models\StripeWebhookLog;
use Stripe\Exception\ApiErrorException;
use Stripe\Exception\SignatureVerificationException;
use Stripe\Webhook;
use App\Helpers\Helper;

class PaymentService
{
    use Helper;

    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }
    
    public function createPayment($data){
        try {
            //Create payment intent for checkout
            $paymentIntent = PaymentIntent::create([
                'amount' => $data['amount'] * 100,
                'currency' => 'usd',
                'description' => 'Laravel Stripe Payment',
            ]);

            if($paymentIntent){
                //If payment intent created properly add records
                $payment = Payment::create([
                    "payment_intent_id" => $paymentIntent->id,
                    "amount" => $paymentIntent->amount / 100,
                    "currency" => $paymentIntent->currency,
                    "payment_method" => $paymentIntent->payment_method,
                ]);
                
                if($payment){
                    $intent = $paymentIntent->client_secret;

                    $paymentData = [
                        "intent" => $intent,
                        "amount" => $data['amount']
                    ];
                    return $this->successResponse("Payment initialized successfully.", $paymentData);
                }else{
                    return $this->errorResponse("An error occurred while saving the payment details.");
                }
            }else{
                return  $this->errorResponse("Failed to initialize payment. Please try again later.");
            }
        } catch (ApiErrorException $e) {
            return $this->errorResponse("Something went wrong.");
        }
    }

    public function handleWebhook($requestParam)
    {
        $webhookSecret = config('services.stripe.webhook_secret');
        $payload = $requestParam->getContent();
        $sigHeader = $requestParam->header('Stripe-Signature');
    
        try {
            // Verify the webhook signature
            $event = Webhook::constructEvent($payload, $sigHeader, $webhookSecret);

            //Get the message as per the event occur
            $paymentData = $event->data->object ?? [];

            if(!empty($paymentData)){
                $payment = Payment::where('payment_intent_id', $paymentData->id)
                        ->first();

                if(!empty($payment)){
                    $amount = $paymentData->amount / 100; // Payment in cent so need to convert 
                    $type = $event->type;
                    $currency = $paymentData?->currency;
                    $paymentIntentId = $paymentData->id;
                    $status = $paymentData?->status;
                    
                    if(in_array($type, ['payment_intent.succeeded', 'payment_intent.payment_failed'])){
                        $payment->status =  $type == 'payment_intent.payment_failed' ? "failed" : "success";
                        $payment->save();
                    }
                    
                    //Save payment webhook log
                    StripeWebhookLog::create([
                        'payment_intent_id' => $paymentIntentId,
                        'type' => $type,
                        'payload' => $payload,
                        'amount'  => $amount,
                        'currency' => $currency,
                        'status' => $status
                    ]);
                    

                    return $this->successResponse($this->getWebHookEventMessage($event), []);
                }

                return $this->errorResponse("Payment intent detail not found.");
          }else{
            return $this->errorResponse("Payment Intent does not created.");   
          }
        } catch (SignatureVerificationException $e) {
            return  $this->errorResponse('Invalid signature');
        }
    }

    public function getWebHookEventMessage($event)
    {
        $message = '';
        switch ($event->type) {
            case 'payment_intent.canceled':
                $message = "Payment intent has been canceled.";
                break;
            case 'payment_intent.created':
                $message = "A new payment intent has been created.";
                break;
            case 'payment_intent.payment_failed':
                $message = "Payment attempt has failed.";
                break;
            case 'payment_intent.succeeded':
                $message = "Payment has been successfully completed.";
                break;
            default:
                $message = "Received unknown event type: " . $event->type;
        }

        return $message;
    }
}
