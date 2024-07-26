<?php

// app/Http/Middleware/CheckUserStatus.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckUserStatus
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
            $user = Auth::user();
            $customer = $user->customer; // Assuming a relationship is defined in the User model

            if ($customer->status === 'Deactivated') {
                Auth::logout();
                return redirect('/failed')->with('error', 'Your account has been deactivated.');
            }
        }

        return $next($request);
    }
}

