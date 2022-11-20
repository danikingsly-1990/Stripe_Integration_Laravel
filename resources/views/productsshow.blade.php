@extends('layouts.app')

@section('styles')
<style>
    .StripeElement {
        box-sizing: border-box;
        height: 40px;
        padding: 10px 12px;
        border: 1px solid transparent;
        border-radius: 4px;
        background-color: white;
        box-shadow: 0 1px 3px 0 #e6ebf1;
        -webkit-transition: box-shadow 150ms ease;
        transition: box-shadow 150ms ease;
    }
    .StripeElement--focus {
        box-shadow: 0 1px 3px 0 #cfd7df;
    }
    .StripeElement--invalid {
        border-color: #fa755a;
    }
    .StripeElement--webkit-autofill {
        background-color: #fefde5 !important;
    }
</style>
@endsection

@section('content')
<style>
    #cover-spin {
        position:fixed;
        width:100%;
        left:0;
        right:0;
        top:0;
        bottom:0;
        background-color: rgba(255,255,255,0.7);
        z-index:9999;
        display:none;
    }

    @-webkit-keyframes spin {
        from {
            -webkit-transform:rotate(0deg);
        }
        to {
            -webkit-transform:rotate(360deg);
        }
    }

    @keyframes spin {
        from {
            transform:rotate(0deg);
        }
        to {
            transform:rotate(360deg);
        }
    }

    #cover-spin::after {
        content:'';
        display:block;
        position:absolute;
        left:48%;
        top:40%;
        width:40px;
        height:40px;
        border-style:solid;
        border-color:black;
        border-top-color:transparent;
        border-width: 4px;
        border-radius:50%;
        -webkit-animation: spin .8s linear infinite;
        animation: spin .8s linear infinite;
    }
</style>
<div id="cover-spin"></div>
<script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
@if(session('message'))
<div class="alert alert-success" role="alert">{{ session('message') }}</div>
@endif
@if(session('error'))
<div class="alert alert-danger" role="alert">{{ session('error') }}</div>
@endif
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Product Detailed View</div>

                <div class="card-body">
                    <ul class="list-group list-group-unbordered">
                                        <li class="list-group-item">
                                            <b> Name: </b> {{isset($getData->Name)?$getData->Name:''}}
                                        </li>
                                        <li class="list-group-item">
                                            <b> Price: </b> {{isset($getData->Price)?$getData->Price:''}}
                                        </li>
                                        <li class="list-group-item">
                                            <b> Description: </b>  {{isset($getData->Description)?$getData->Description:''}}
                                        </li>
                                        <li class="list-group-item">
                                            <b> Status: </b> {{isset($getData->Status)?$getData->Status:''}}
                                        </li>
                                    </ul>
                    <form method="POST" action="{{ route('products.purchase', $getData->id) }}" class="card-form mt-3 mb-3">
                        @csrf
                        <input type="hidden" name="payment_method" class="payment-method">
                        <input class="StripeElement mb-3" name="card_holder_name" placeholder="Card holder name" required>
                        <div class="col-lg-4 col-md-6">
                            <div id="card-element"></div>
                        </div>
                        <div id="card-errors" role="alert" style="color:red;"></div>
                        <div class="form-group mt-3">
                            <button type="submit" class="btn btn-primary pay">
                                Buy Now
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://js.stripe.com/v3/"></script>
<script>
let stripe = Stripe("{{ env('STRIPE_KEY') }}")
let elements = stripe.elements()
let style = {
    base: {
        color: '#32325d',
        fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
        fontSmoothing: 'antialiased',
        fontSize: '16px',
        '::placeholder': {
            color: '#aab7c4'
        }
    },
    invalid: {
        color: '#fa755a',
        iconColor: '#fa755a'
    }
}
let card = elements.create('card', {style: style})
card.mount('#card-element')
let paymentMethod = null
$('.card-form').on('submit', function (e) {
    $('button.pay').attr('disabled', true)
    if (paymentMethod) {
        return true
    }
    stripe.confirmCardSetup(
            "{{ $intent->client_secret }}",
            {
                payment_method: {
                    card: card,
                    billing_details: {name: $('.card_holder_name').val()}
                }
            }
    ).then(function (result) {
        if (result.error) {
            $('#card-errors').text(result.error.message)
            $('button.pay').removeAttr('disabled')
        } else {
            paymentMethod = result.setupIntent.payment_method
            $('.payment-method').val(paymentMethod)
            $('#cover-spin').show(0)
            $('.card-form').submit()
        }
    })
    return false
})
</script>
@endsection