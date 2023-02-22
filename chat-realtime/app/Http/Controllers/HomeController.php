<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;


class HomeController extends Controller
{
    public function index() {
        $users = User::where('id', '!=', Auth::id())->get();
        $this->data['users'] = $users;
        
        return view('dashboard', $this->data); 
    }
}
