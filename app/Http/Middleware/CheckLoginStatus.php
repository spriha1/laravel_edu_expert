<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\User;

class CheckLoginStatus
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
        if (Auth::check()) {
            $user_type = User::join('user_types', 'users.user_type_id', '=', 'user_types.id')
            ->where('users.id', Auth::id())
            ->select('user_types.user_type')->get();
            foreach ($user_type as $value) {
                if ($value->user_type === 'Admin') {
                    return redirect('/admin_dashboard');
                }
                else if ($value->user_type === 'Teacher') {
                    return redirect('/teacher_dashboard');
                }
                else if ($value->user_type === 'Student') {
                    return redirect('/student_dashboard');
                }
            }
        }
        
        return $next($request);
    }
}
