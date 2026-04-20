<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        \Illuminate\Support\Facades\Log::info('SetLocale executed. Session locale: ' . session()->get('locale') . '. App Locale before: ' . app()->getLocale());
        if (session()->has('locale')) {
            app()->setLocale(session()->get('locale'));
        } else {
            app()->setLocale('fr'); // Default to French
        }
        \Illuminate\Support\Facades\Log::info('App Locale after: ' . app()->getLocale());
        
        return $next($request);
    }
}
