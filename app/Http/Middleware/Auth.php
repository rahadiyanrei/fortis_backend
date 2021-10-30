<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Sentinel;
class Auth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Sentinel::getUser();
        if(!$user){
            // $getURI = $request->getRequestUri();
            // $uriExplode = explode('/', $getURI);
            return redirect('auth')->with('toast_error','Please Sign in first to continue!');
        }
        if ($user->status === 0) {
            Sentinel::logout();
            return redirect('auth')->with('toast_error','Your account has been banned!');
        }
        if ($request->route()) {
            $namePath = $request->route()->getName();
            if ($namePath === 'admin' && $user->id !== 1) {
                return redirect('dashboard')->with('toast_error','You are not eligible for admin module!');
            } 
        }

        return $next($request);
    }
}
