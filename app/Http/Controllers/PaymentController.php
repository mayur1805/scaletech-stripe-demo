<?php
namespace App\Http\Controllers;

use App\Http\Requests\PaymentRequest;
use Illuminate\Support\Facades\Session;
use App\Services\PaymentService;
    
class PaymentController extends Controller
{
    protected $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }
    /**
     * This is for payment view page
     */
    public function show()
    {
        return view('payment.initiate');
    }

    /**
     * This function is for handling payment checkout 
     * @param App\Http\Requests\PaymentRequest $request
     */
    public function checkout(PaymentRequest $request)
    {
        //Payment service call for create payment
        $response = $this->paymentService->createPayment($request->all());

        if($response['status'] == true){
            //Go to the checkout page with payment intent and amount
            return view('payment.checkout', $response['data']);
        }
        Session::flash("error", $response['message']);

        return redirect()->route('payment.form');
    }
}