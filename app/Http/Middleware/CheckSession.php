<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckSession
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        //dd(auth()->user()->session_id, session('login_token'));
        if (auth()->check() && auth()->user()->session_id !== session('login_token')) {
            auth()->logout();
            session()->flush();
            return redirect('/administrator/login')
                ->with('message', 'Your session has expired because you logged in from another device.');
        }

        return $next($request);
    }
}
