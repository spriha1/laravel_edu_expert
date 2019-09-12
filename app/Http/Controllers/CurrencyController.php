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

    /**
    * 
    * @method update_currency() 
    * 
    * @param Request object
    * Desc : This method updates the select_status of the chosen currency to 1
    */

    public function update_currency(Request $request)
    {
    	$new = $request->input('currency');

    	$this->currency->where('select_status', 1)
        ->update(['select_status' => 0]);

    	$this->currency->where('code', $new)
        ->update(['select_status' => 1]);
    }

    /**
    * 
    * @method convert_currency() 
    * 
    * @param Request object
    * @return float 
    * Desc : This method performs currency conversion and returns the converted amount
    */

    public function convert_currency(Request $request)
    {
    	$new    = $request->input('new_cur');
    	$old    = $request->input('old_cur');
    	$amount = $request->input('amount');

		$req_url = 'https://api.exchangerate-api.com/v4/latest/'.$old;
		$response_json = file_get_contents($req_url);
		
		if (false !== $response_json) {

			try {

				$response_object = json_decode($response_json);

				$new_amount = ($amount * $response_object->rates->$new);
				return $new_amount;

			}
			catch(Exception $e) {
                Log::error($e->getMessage());
			}

		}
    }

    /**
    * 
    * @method fetch_currency() 
    * 
    * @param void
    * @return json 
    * Desc : This method fetches and returns the chosen currency
    */

    public function fetch_currency()
    {
    	$new = $this->currency->where('select_status', 1)->first();

    	$response = array("new"=>$new['code']);

    	return(json_encode($response));
    }
}
