@extends('layouts.layout')

@section('title', 'Stripe Payment')
@section('css')
<link href="{{ asset('css/checkout.css') }}" rel="stylesheet">
@endsection
        
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card p-4">
                <h3 class="text-center mb-3">Enter Amount</h3>
                @if(Session::has('error'))
                <div class="bg-red-100 text-red-800 p-4 rounded-md mb-4">
                    {{ Session::get('error')  }}
                </div>
            @endif
                <form action="{{ route('payment.checkout') }}" id="paymentForm" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="amount" class="form-label">Amount</label>
                        <div class="form-group">
                            <input type="number" class="form-control clearfix @error('amount') is-invalid @enderror" id="amount" name="amount" placeholder="Enter amount" required>
                            @error('amount')
                                <p class="invalid-feedback">{{$message}}</p>
                            @enderror
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection      

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/jquery.validate.min.js"></script>
    <script src={{ asset('js/stripeFromValidation.js') }}></script>
@endsection