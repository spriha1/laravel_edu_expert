@extends('layouts.master')
@section('sidenav_content')
@include('layouts.admin_sidenav')
@endsection
@section('content')
<div class="content-wrapper">
    <br><br>
    <div class="col-md-6">
        <!-- Horizontal Form -->
        <div class="box box-info">
            <form action="/post_stripe_payment" method="post" id="payment-form">
                @csrf
                <div class="form-row">
                    <label for="card-element">
                    Credit or debit card
                    </label>
                    <div id="card-element">
                    <!-- A Stripe Element will be inserted here. -->
                    </div>
                    <input type="hidden" name="amount" id="amount" value={{ $amount }}>
                    <input type="hidden" name="currency" id="currency" value={{ $currency }}>
                    <!-- Used to display Element errors. -->
                    <div id="card-errors" role="alert"></div>
                </div>
                <button>Submit Payment</button>
            </form>
        </div>
    </div>
</div>
@endsection
@section('footer')
    @include('layouts.footer')
    <script src="{{ mix('/js/stripe.js') }}"></script>
@endsection
