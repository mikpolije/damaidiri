<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

class RedirectController extends Controller
{
    public function redirect() : RedirectResponse
    {
        $checkRole = Auth::user()->roles()->pluck('name')[0];

        if ($checkRole == 'superadmin') {
            return redirect()->route('dashboard.superadmin');
        } elseif ($checkRole == 'admin') {
            return redirect()->route('dashboard.admin');
        } elseif ($checkRole == 'patient') {
            return redirect()->route('dashboard.patient');
        } elseif ($checkRole == 'psychologist') {
            return redirect()->route('dashboard.psychologist');
        } else {
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            
            return redirect('/');
        }
    }
}
