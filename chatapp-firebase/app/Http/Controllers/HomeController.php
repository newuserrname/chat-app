<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $provider = User::where('role', 2)->get();
        return view('home', compact('provider'));
    }

    public function profile($id)
    {
        $user = User::find($id);
        return view('profile', compact('user'));
    }

    public function chat()
    {
        return view('chat');
    }
}
