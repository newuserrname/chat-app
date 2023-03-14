<?php

namespace App\Http\Controllers;

use App\Models\ConversationStamp;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
        $avatar = DB::table('provider')->where('id', $currentId)->first();
        return view('provider_home', [
            'currentId' => $currentId,
            /*'$avatar' => $avatar['profile_url'],*/
        ]);
    }

    public function profile($id)
    {
        $user = User::find($id);
        $avatar = DB::table('provider')->where('id', $id)->first();
        return view('profile', compact('user', 'avatar'));
    }
}
