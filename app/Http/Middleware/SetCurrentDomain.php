<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetCurrentDomain
{
    public function handle(Request $request, Closure $next): Response
    {
        $currentDomain = $request->getHost();
        $request->merge(['current_domain' => $currentDomain]);
        
        view()->share('currentDomain', $currentDomain);
        
        return $next($request);
    }
}