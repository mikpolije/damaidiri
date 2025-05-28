<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsurePsychologistProfileComplete
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $check_user = auth()->user();
        if ($check_user->roles->first()->name === 'psychologist' && $check_user->psychologist == null) {
            return redirect()->route('profile.my-profile-psychologist');
        }

        return $next($request);
    }
}
