<?php

namespace App\Http\Middleware;

use Closure;

use Auth;

class UserIsMember
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
        if (Auth::check())
        {
            $user = Auth::user();
            if ($user->type == 'member')
            {
                return $next($request);
            }
        }
        
        return redirect('login');
    }
}
