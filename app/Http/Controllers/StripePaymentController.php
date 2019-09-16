<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Exception;

class StripePaymentController extends Controller
{
    public function stripe(Request $request)
    {
        return view('stripe_payment', [
            'amount'   => $request->input('amount'),
            'currency' => $request->input('currency')
        ]);
    }

    public function post_stripe(Request $request)
    {
        // Set your secret key: remember to change this to your live secret key in production
        // See your keys here: https://dashboard.stripe.com/account/apikeys
        \Stripe\Stripe::setApiKey('sk_test_e5fmzx4vnU7xDvy5fwtX1FeC00eyLRkRmP');
        // Token is created using Checkout or Elements!
        // Get the payment token ID submitted by the form:
        $token    = $request->input('stripeToken');
        $amount   = $request->input('amount') * 100;
        $currency = $request->input('currency');
        try {
            \Stripe\Transfer::create([
                "amount" => $amount,
                "currency" => $currency,
                "destination" => "acct_1FH6ajLlnY5NTgkq"
            ]);
            // $charge = \Stripe\Charge::create([
            //     'amount'      => $amount,
            //     'currency'    => $currency,
            //     'description' => 'Example charge',
            //     'source'      => $token,
            // ]);
        }
        
        catch (Exception $e) {
            Log::error($e->getMessage());
            return $e->getMessage();
        }

        return($charge->status);
    }


    public function connect(){

        \Stripe\Stripe::setApiKey('sk_test_e5fmzx4vnU7xDvy5fwtX1FeC00eyLRkRmP');

        // Account Id of account to be connected
        $code = 'ac_FovKflisVsrD3O9APhsscw1kdI7va3w3';
        $stripe_sk = 'sk_test_e5fmzx4vnU7xDvy5fwtX1FeC00eyLRkRmP';
        $stripe_sk_client_id = '';

        $req_url = 'https://connect.stripe.com/oauth/token';

        $fields = array(
        'client_secret' => urlencode('sk_test_e5fmzx4vnU7xDvy5fwtX1FeC00eyLRkRmP'),
        'code' => urlencode($code),
        'grant_type' => urlencode('authorization_code')
        );

        $fields_string = '';

        //url-ify the data for the POST
        foreach($fields as $key=>$value) { 
        $fields_string .= $key.'='.$value.'&'; 
        }

        rtrim($fields_string, '&');

        //open connection
        $ch = curl_init();

        //set the url, number of POST vars, POST data
        curl_setopt($ch,CURLOPT_POST, count($fields));
        curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch,CURLOPT_URL, $req_url);

        //execute post
        $result = curl_exec($ch);
        $http_info = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        echo "<pre>";
        print_r($result);

        //close connection
        curl_close($ch);

        //https://connect.stripe.com/oauth/authorize?response_type=code&client_id=ca_FosjP95EizSgRhMQXj5cZsX3oB8LWomi&scope=read_write

    }

    public function success(){
        echo "Finally. Its done!";
    }

}
