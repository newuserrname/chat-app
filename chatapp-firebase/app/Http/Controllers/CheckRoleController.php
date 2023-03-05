<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class CheckRoleController extends Controller
{
    public function login()
    {
        return view('auth.login');
    }

    public function loginPost(Request $request)
    {
        if (Auth::attempt(['email' => $request->input('email'), 'password' => $request->input('password')])) {
            $user = Auth::user();
            if ($user->role == 3) {
                return redirect('/home');
            } elseif ($user->role == 2) {
                return redirect('/home-provider');
            }
        }
        return redirect('/');
    }
}
