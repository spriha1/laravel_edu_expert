<?php

namespace App\Http\Middleware;

use Closure;
use App\UserType;
use Illuminate\Support\Facades\Auth;

class CheckStudent
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $results = UserType::where('user_type', 'Student')->select('id')->get();
        foreach($results as $result)
        {
            if (Auth::user()->user_type_id != $result->id) {
                return redirect()->back();
            }
        }
        return $next($request);
    }
}
