@extends('layouts.layout')

@section('title', 'Stripe Payment')
@section('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
<link href="{{ asset('css/checkout.css') }}" rel="stylesheet">
@endsection
        
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-6">
            <div class="card shadow p-6">
                <div id="payment-container" class="text-center">
                    <div class="text-dark mb-4">
                        <p class="fs-4 fw-semibold">Your Total Amount is {{ $amount }} USD</p>
                    </div>
                    <div class="card shadow p-4">
                        <form action="{{ route('payment.checkout') }}" method="post" id="payment-form">
                            @csrf
                            <div class="mb-3">
                                <label for="card-element" class="form-label fw-medium">Enter your credit card information</label>
                                <div class="border rounded p-3" id="card-element"></div>
                                <div class="text-danger mt-2" id="card-errors" role="alert"></div>
                                <input type="hidden" name="plan" value="" />
                            </div>
                            <div class="text-end">
                                <button id="card-button" class="btn btn-dark px-4 py-2" type="submit" data-secret="{{ $intent }}">
                                    Pay
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="text-dark mb-4">
                    
                </div>
                <div id="success-container" class="d-none alert alert-success mt-4 text-center">
                    <h2 class="fs-4 fw-semibold">Payment Successful!</h2>
                    <p class="mt-2">Thank you for your payment. Your transaction has been processed successfully.</p>
                    <a href="{{ route('payment.form') }}" class="btn btn-success">Redirect to payment page</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection      

@section('scripts')
<script src="https://js.stripe.com/v3/"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const stripe = Stripe('{{ env('STRIPE_KEY') }}', { locale: 'en' });
    const elements = stripe.elements();
    const cardElement = elements.create('card');
    const cardButton = document.getElementById('card-button');
    const clientSecret = cardButton.dataset.secret;
    cardElement.mount('#card-element');
    cardElement.addEventListener('change', function(event) {
        var displayError = document.getElementById('card-errors');
        displayError.textContent = event.error ? event.error.message : '';
    });
    var form = document.getElementById('payment-form');
    form.addEventListener('submit', function(event) {
        event.preventDefault();
        stripe.handleCardPayment(clientSecret, cardElement, {
            payment_method_data: {}
        }).then(function(result) {
            if (result.error) {
                document.getElementById('card-errors').textContent = result.error.message;
            } else {
                document.getElementById('payment-container').classList.add('d-none');
                document.getElementById('success-container').classList.remove('d-none');
            }
        });
    });
</script>
@endsection