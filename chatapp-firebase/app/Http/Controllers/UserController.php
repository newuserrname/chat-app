<?php

namespace App\Http\Controllers;

use App\Models\ConversationStamp;
use App\Models\User;
use Google\Cloud\Firestore\FieldValue;
use Google\Cloud\Firestore\FirestoreClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Ramsey\Uuid\Uuid;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function chatWithProvider($id)
    {
        $receiverId = User::where('id', $id)->first();
        $currentId = Auth::id();

        // Tạo một instance của FirestoreClient để tương tác với Firestore
        $firestore = new FirestoreClient([
            'projectId' => 'laravel-chat-app-firebase'
        ]);

        // Tạo một collection mới để lưu trữ các conversation
        $conversation = $firestore->collection('conversation');

        // Kiểm tra tồn tại của provider_id & seeker_id trong conversation
        $existingConversation = $conversation
            ->where('seeker_id', '=', $currentId)
            ->where('provider_id', '=', $receiverId->id)
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
                'seeker_id' => $currentId,
                'provider_id' => $receiverId->id,
            ]);

            // Lấy Id document vừa tạo
            $documentID = $newConversation->id();
        }

        return view('chat', [
            'receiverId' => $receiverId,
            'currentId' => $currentId,
            'conversationId' => $documentID
        ]);
    }

    public function getUserById($id) {

        $currentId = $id;

        $firestore = new FirestoreClient([
            'projectId' => 'laravel-chat-app-firebase'
        ]);
        $conversationReference = $firestore->collection('conversation');

        $queryProvider = $conversationReference->where('provider_id', '=', $currentId);
        $querySeeker = $conversationReference->where('seeker_id', '=', $currentId);

        $conversation = [];

        $documentProvider = $queryProvider->documents();
        foreach ($documentProvider as $document)
        {
            $conversation[] = $document->data();
        }

        $documentSeeker = $querySeeker->documents();
        foreach ($documentSeeker as $document)
        {
            $existingConversation = array_filter($conversation, function ($c) use ($document) {
                return $c['id'] == $document->id();
            });

            if (empty($existingConversation)) {
                $conversation[] = $document->data();
            }
        }

        $count = count($conversation);

        $providerId = [];
        foreach ($conversation as $result)
        {
            $provider = $result['provider_id'];
            $providerId[] = $provider;
        }
        $listchat = User::whereIn('id', $providerId)->pluck('name', 'id');

        return response()->json([
            'conversations' => $conversation,
            'count' => $count,
            'listchat' => $listchat
        ]);
    }

    public function getMessages($currentId, $receiverId) {

        $currentId = $currentId;
        $receiverId = $receiverId;

        $firestore = new FirestoreClient([
            'projectId' => 'laravel-chat-app-firebase'
        ]);

        $conversationMessage = $firestore->collection('conversation_message');
        $conversation = $firestore->collection('conversation');

        $query1 = $conversation->where('provider_id', '=', $receiverId)
            ->where('seeker_id', '=', $currentId);
        $query2 = $conversation->where('provider_id', '=', $currentId)
            ->where('seeker_id', '=', $receiverId);

        $querySnapshot1 = $query1->documents();
        $querySnapshot2 = $query2->documents();

        $conversations = $querySnapshot1;

        foreach ($querySnapshot2 as $documentSnapshot) {
            $conversations[] = $documentSnapshot;
        }

        $conversationId = null;
        foreach ($conversations as $document) {
            $conversationId = $document->data()['id'];
        }

        if ($conversationId) {
            $query3 = $conversationMessage->where('conversation_id', '=', $conversationId)
                ->orderBy('created_at');
            $querySnapshot3 = $query3->documents();

            $result = [];
            foreach ($querySnapshot3 as $value) {
                $documentData = $value->data();
                $documentData['id'] = $value->id(); // lấy id của document
                $result[] = $documentData;
            }

            if (count($result) > 0) {
                return response()->json([$result, $currentId, $receiverId]);
            } else {
                return response()->json(['message' => 'No message']);
            }
        } else {
            return response()->json(['message' => 'No conversation']);
        }
    }

    public function sendMessage(Request $request) {

        $currentId = $request->input('currentId');
        $receiverId = $request->input('receiverId');
        $message = $request->input('message');

        $firestore = new FirestoreClient([
            'projectId' => 'laravel-chat-app-firebase'
        ]);

        $conversationMessage = $firestore->collection('conversation_message');
        $conversation = $firestore->collection('conversation');

        $conversations = $conversation->where('provider_id', '=', $receiverId)
            ->where('seeker_id', '=', $currentId)
            ->documents();

        if ($conversations->isEmpty()) {
            $conversations = $conversation->where('provider_id', '=', $currentId)
                ->where('seeker_id', '=', $receiverId)
                ->documents();
        }

        // Get conversation ID
        $conversationId = null;
        foreach ($conversations as $document) {
            $conversationId = $document->data()['id'];
            break;
        }

        // Set message type
        $role = User::where('id', $currentId)->value('role');
        $type = $role == 2 ? 1 : ($role == 3 ? 0 : 3);

        // Create new document and set its data
        $newDocReference = $conversationMessage->add([
            'conversation_id' => $conversationId,
            'created_at' => FieldValue::serverTimestamp(),
            'deleted_at' => 0,
            'file_attach' => null,
            'message' => $message,
            'provider_seen' => 0,
            'seeker_seen' => 0,
            'stamp' => null,
            'type' => $type,
        ]);
    // Try to update document with ID field
        try {
            $newDocReference->id();
            return response()->json(['success' => 'success']);
        } catch (\Throwable $error) {
            return response()->json(['error' => $error->getMessage()], 500);
        }
    }

    public function sendFileAttach(Request $request) {

        $currentId = $request->input('currentId');
        $receiverId = $request->input('receiverId');
        $attachment = $request->validate([
            'attachment' => 'required|file|mimes:jpeg,png,jpg,gif|max:25000',
        ]);

        $firestore = new FirestoreClient([
            'projectId' => 'laravel-chat-app-firebase'
        ]);

        $conversationMessage = $firestore->collection('conversation_message');
        $conversation = $firestore->collection('conversation');

        $conversations = $conversation->where('provider_id', '=', $receiverId)
            ->where('seeker_id', '=', $currentId)
            ->documents();

        if ($conversations->isEmpty()) {
            $conversations = $conversation->where('provider_id', '=', $currentId)
                ->where('seeker_id', '=', $receiverId)
                ->documents();
        }

        // Get conversation ID
        $conversationId = null;
        foreach ($conversations as $document) {
            $conversationId = $document->data()['id'];
            break;
        }

        /*update path save s3 /chat/conversation_id */
        $imageName = time().'.'.$request->file('attachment')->extension();
        $path = Storage::disk('attach-chat')->put("chat/{$conversationId}", $request->file('attachment'));
        $path = Storage::disk('attach-chat')->url($path);
        $file_attach = "https://girlsmeee.s3.amazonaws.com" . $path;

        // Set message type
        $role = User::where('id', $currentId)->value('role');
        $type = $role == 2 ? 1 : ($role == 3 ? 0 : 3);

        // Create new document and set its data
        $newDocReference = $conversationMessage->add([
            'conversation_id' => $conversationId,
            'created_at' => FieldValue::serverTimestamp(),
            'deleted_at' => 0,
            'file_attach' => $file_attach,
            'message' => null,
            'provider_seen' => 0,
            'seeker_seen' => 0,
            'stamp' => null,
            'type' => $type,
        ]);
        // Try to update document with ID field
        try {
            $newDocReference->id();
            return response()->json(['success' => 'success']);
        } catch (\Throwable $error) {
            return response()->json(['error' => $error->getMessage()], 500);
        }
    }

    public function sendStampv2(Request $request) {

        $currentId = $request->input('currentId');
        $receiverId = $request->input('receiverId');
        $selectedStampv2 = $request->input('stampV2');

        $firestore = new FirestoreClient([
            'projectId' => 'laravel-chat-app-firebase'
        ]);

        $conversationMessage = $firestore->collection('conversation_message');
        $conversation = $firestore->collection('conversation');

        $conversations = $conversation->where('provider_id', '=', $receiverId)
            ->where('seeker_id', '=', $currentId)
            ->documents();

        if ($conversations->isEmpty()) {
            $conversations = $conversation->where('provider_id', '=', $currentId)
                ->where('seeker_id', '=', $receiverId)
                ->documents();
        }

        // Get conversation ID
        $conversationId = null;
        foreach ($conversations as $document) {
            $conversationId = $document->data()['id'];
            break;
        }

        // Set message type
        $role = User::where('id', $currentId)->value('role');
        $type = $role == 2 ? 1 : ($role == 3 ? 0 : 3);

        // Create new document and set its data
        $newDocReference = $conversationMessage->add([
            'conversation_id' => $conversationId,
            'created_at' => FieldValue::serverTimestamp(),
            'deleted_at' => 0,
            'file_attach' => null,
            'message' => null,
            'provider_seen' => 0,
            'seeker_seen' => 0,
            'stamp' => $selectedStampv2,
            'type' => $type,
        ]);
        // Try to update document with ID field
        try {
            $newDocReference->id();
            return response()->json(['success' => 'success']);
        } catch (\Throwable $error) {
            return response()->json(['error' => $error->getMessage()], 500);
        }
    }

}
