<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Currency;
Use Illuminate\Support\Facades\Log;
use Exception;

class CurrencyController extends Controller
{
    protected $currency;

    public function __construct()
    {
        $this->currency = new Currency;
    }

    public function update_currency(Request $request)
    {
    	$new = $request->input('currency');

    	$this->currency->where('select_status', 1)->update(['select_status' => 0]);

    	// $this->currency->where('select_status', 1)->update(['select_status' => -1]);

    	$this->currency->where('code', $new)->update(['select_status' => 1]);
    }

    public function convert_currency(Request $request)
    {
    	$new = $request->input('new_cur');
    	$old = $request->input('old_cur');
    	$amount = $request->input('amount');

		$req_url = 'https://api.exchangerate-api.com/v4/latest/'.$old;
		$response_json = file_get_contents($req_url);
		// Continuing if we got a result
		if(false !== $response_json) {

			// Try/catch for json_decode operation
			try {

			// Decoding
				$response_object = json_decode($response_json);

				// YOUR APPLICATION CODE HERE, e.g.
				// $base_price = $amount; // Your price in USD
				$new_amount = ($amount * $response_object->rates->$new);
				return $new_amount;

			}
			catch(Exception $e) {
			// Handle JSON parse error...
			}

		}
    }

    public function fetch_currency()
    {
    	// $old = $this->currency->where('select_status', -1)->first();

    	$new = $this->currency->where('select_status', 1)->first();

    	// $response = array("old"=>$old['code'],"new"=>$new['code']);

    	$response = array("new"=>$new['code']);

    	return(json_encode($response));
    }
}
