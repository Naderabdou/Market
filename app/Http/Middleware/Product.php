<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Product
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        if (auth()->user('sanctum')->type != 'admin') {
            if (auth()->user('sanctum')->type != 'market' && auth()->user('sanctum')->status != 'unblock') {
                return response()->apiError('You are not allowed', 1, 401);
            }
          //  return response()->apiError('You are not allowed', 1, 401);
        }



        return $next($request);
    }
}
