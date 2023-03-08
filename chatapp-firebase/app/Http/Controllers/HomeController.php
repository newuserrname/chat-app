<?php

namespace App\Http\Controllers;

use App\Models\ConversationStamp;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Google\Cloud\Firestore\FirestoreClient;
use Ramsey\Uuid\Uuid;

class HomeController extends Controller
{

    public function index()
    {
        $provider = User::where('role', 2)->get();
        return view('home', compact('provider'));
    }

    public function providerIndex()
    {
        $currentId = Auth::id();
        return view('provider_home', [
            'currentId' => $currentId,
        ]);
    }

    public function profile($id)
    {
        $user = User::find($id);
        return view('profile', compact('user'));
    }

    public function conversation_stamp()
    {
        $stamp = ConversationStamp::all();

        return response()->json($stamp);
    }
}
