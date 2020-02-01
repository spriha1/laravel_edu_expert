<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Currency as Currency;
use App\User as User;
use Exception;

class CurrencyController extends Controller
{
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
        $new           = $request->input('new_cur');
        $old           = $request->input('old_cur');
        $amount        = $request->input('amount');
        $req_url       = 'https://api.exchangerate-api.com/v4/latest/'.$old;
        $response_json = file_get_contents($req_url);
        if (false !== $response_json) {
            try {
                $response_object = json_decode($response_json);
                $new_amount      = ($amount * $response_object->rates->$new);
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

    public function fetch_currency(Request $request)
    {
        try {
            $new = User::join('currencies', 'users.currency_id', '=', 'currencies.id')
                ->where('users.id', $request->input('user_id'))
                ->select('code')
                ->first();

            $old = User::join('user_types', 'users.user_type_id', '=', 'user_types.id')
                ->join('currencies', 'users.currency_id', '=', 'currencies.id')
                ->where('user_type', 'Admin')
                ->select('code')
                ->first();
                
            $response = array('new' => $new['code'], 'old' => $old['code']);
            return(json_encode($response));
        }
        
        catch(Exception $e) {
            Log::error($e->getMessage());
        }

    }
}
