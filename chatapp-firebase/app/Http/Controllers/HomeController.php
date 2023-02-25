<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Google\Cloud\Firestore\FirestoreClient;
use Ramsey\Uuid\Uuid;

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

    public function chatWithProvider($id)
    {
        $provider = User::where('id', $id)->first();
        $seeker = Auth::id();

        // Tạo một instance của FirestoreClient để tương tác với Firestore
        $firestore = new FirestoreClient([
            'projectId' => 'laravel-chat-app-firebase'
        ]);

        // Tạo một collection mới để lưu trữ các conversation
        $conversation = $firestore->collection('conversation');

        // Kiểm tra tồn tại của provider_id & seeker_id trong conversation
        $existingConversation = $conversation
            ->where('seeker_id', '=', $seeker)
            ->where('provider_id', '=', $provider->id)
            ->limit(1)
            ->documents();

        if (!$existingConversation->isEmpty()) {
            // Lấy id conversation đã tồn tại (nhưng không có provider_id & seeker_id trong đó)
            foreach ($existingConversation->rows() as $document) {
                $documentID = $document->id();
            }
        } else {
            // Tạo một document mới trong collection conversations để lưu trữ thông tin của cuộc trò chuyện
            $newConversation = $conversation->add([
                'id' => Uuid::uuid4()->toString(),
                'seeker_id' => $seeker,
                'provider_id' => $provider->id,
            ]);

            // Lấy Id document vừa tạo
            $documentID = $newConversation->id();
        }

        return view('chat', [
            'provider' => $provider,
            'seeker' => $seeker,
            'documentId' => $documentID
        ]);
    }
}
