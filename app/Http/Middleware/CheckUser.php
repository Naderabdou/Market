<?php

namespace App\Http\Middleware;

use App\Models\User;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use function Symfony\Component\String\u;

class CheckUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        $user=  User::where('phone', $request->phone)->wherein('type',['user','driver','market'])->first();


        if ($user){
        if ($user->status == 'block'){
            return response()->apiError('Your account has blocked', 1, 401);

       }
        if ($user->expiry_date <= Carbon::now()){

            return response()->apiError('Your account has expired', 1, 401);}

        }

        return $next($request);
    }
}
