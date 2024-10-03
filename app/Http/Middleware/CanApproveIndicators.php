<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class CanApproveIndicators
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(Auth::check() && in_array(Auth::user()->access_level_id, [User::ROLE_EXEC, User::ROLE_PD, User::ROLE_AH])){
            return $next($request);
        }

        return redirect(route('dashboard'))->with('error', 'You do not have access to this page.');
    }
}
