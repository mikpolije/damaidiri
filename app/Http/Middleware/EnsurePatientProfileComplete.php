<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsurePatientProfileComplete
{
    public function handle(Request $request, Closure $next): Response
    {
        $check_user = auth()->user();
        if ($check_user->roles->first()->name === 'patient' && $check_user->profile == null) {
            return redirect()->route('profile.my-profile');
        }

        return $next($request);
    }
}
