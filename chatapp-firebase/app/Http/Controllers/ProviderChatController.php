<?php

namespace App\Http\Controllers;

use App\Models\ConversationStamp;
use App\Models\User;
use Google\Cloud\Firestore\FieldValue;
use Google\Cloud\Firestore\FirestoreClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Ramsey\Uuid\Uuid;

class ProviderChatController extends Controller
{
    public const MAXIMUM_LAST_MESSAGE_CHARACTER = 10;

    public function chatWithSeeker($id = null)
    {
        if ($id === null) {
            die();
        }
        $receiverId = User::where('id', $id)->first();
        $currentId = Auth::id();

        $firestore = new FirestoreClient([
            'projectId' => 'chat-realtime-firebase-1eaac'
        ]);

        $conversation = $firestore->collection('conversation');

        $existingConversation = $conversation
            ->where('seeker_id', '=', $receiverId->id)
            ->where('provider_id', '=', $currentId)
            ->limit(1)
            ->documents();

        if (!$existingConversation->isEmpty()) {
            foreach ($existingConversation->rows() as $document) {
                $documentID = $document->id();
            }
        } else {
            $newConversation = $conversation->add([
                'id' => Uuid::uuid4()->toString(),
                'seeker_id' => $receiverId->id,
                'provider_id' => $currentId,
            ]);

            $documentID = $newConversation->id();
        }

        return view('conversation.chat_provider', [
            'receiverId' => $receiverId,
            'currentId' => $currentId,
            'conversationId' => $documentID
        ]);
    }

    public function chatMobileWithSeeker($id = null)
    {

        $receiverId = User::where('id', $id)->first();
        $currentId = Auth::id();

        $firestore = new FirestoreClient([
            'projectId' => 'chat-realtime-firebase-1eaac'
        ]);

        $conversation = $firestore->collection('conversation');

        $existingConversation = $conversation
            ->where('seeker_id', '=', $receiverId->id)
            ->where('provider_id', '=', $currentId)
            ->limit(1)
            ->documents();

        if (!$existingConversation->isEmpty()) {
            foreach ($existingConversation->rows() as $document) {
                $documentID = $document->id();
            }
        } else {
            $newConversation = $conversation->add([
                'id' => Uuid::uuid4()->toString(),
                'seeker_id' => $receiverId->id,
                'provider_id' => $currentId,
            ]);

            $documentID = $newConversation->id();
        }

        return view('conversation.chat_mobile_provider', [
            'receiverId' => $receiverId,
            'currentId' => $currentId,
            'conversationId' => $documentID
        ]);
    }

    public function getChatList()
    {

        $currentId = Auth::id();

        $firestore = new FirestoreClient([
            'projectId' => 'chat-realtime-firebase-1eaac'
        ]);
        $conversationRef = $firestore->collection('conversation');


        $querySeeker = $conversationRef->where('provider_id', '=', $currentId);

        $conversation = [];
        $lastConversationId = null;

        $documentSeeker = $querySeeker->documents();
        foreach ($documentSeeker as $document) {
            $receiverId = $document->get('seeker_id');
            $lastConversationId = $document->get('id');

            $receiver = DB::table('users')
                ->join('seeker', 'seeker.id', '=', 'users.id')
                ->where('users.id', $receiverId)
                ->first();

            if ($receiver) {
                $existingConversation = array_filter($conversation, function ($c) use ($document) {
                    return $c['id'] == $document->id();
                });

                if (empty($existingConversation)) {
                    $conversationMessageRef = $firestore->collection('conversation_message');
                    $queryLastMessageSeeker = $conversationMessageRef
                        ->where('conversation_id', '=', $lastConversationId)
                        ->orderBy('created_at', 'DESC');

                    $lastDocument = $queryLastMessageSeeker->documents();
                    $lastMessage = '';
                    foreach ($lastDocument as $document) {
                        $lastMessage = $document->get('message');
                        $lastMessage = mb_substr($lastMessage, 0, self::MAXIMUM_LAST_MESSAGE_CHARACTER, 'UTF-8');
                        break;
                    }

                    $conversation[] = [
                        'id' => $document->id(),
                        'name' => $receiver->name,
                        'seeker_id' => $receiverId,
                        'last_message' => $lastMessage,
                        'avatar' => $receiver->profile_url,
                    ];
                }
            }
        }
        if (empty($conversation)) {
            $response = [
                'message' => 'No conversation',
            ];
        } else {
            $response = [
                'message' => 'success',
                'conversation' => $conversation,
            ];
        }
        return json_encode($response);
    }

    public function getMessages($receiverId)
    {
        $currentId = Auth::id();

        $firestore = new FirestoreClient([
            'projectId' => 'chat-realtime-firebase-1eaac'
        ]);

        $conversationMessage = $firestore->collection('conversation_message');
        $conversation = $firestore->collection('conversation');

        $query = $conversation->where('seeker_id', '=', $receiverId)
            ->where('provider_id', '=', $currentId);

        $querySnapshot = $query->documents();

        $conversations = [];

        foreach ($querySnapshot as $documentSnapshot) {
            $conversations[] = $documentSnapshot;
        }

        $conversationId = null;
        foreach ($conversations as $document) {
            if ($document->data()['provider_id'] == $currentId && $document->data()['seeker_id'] == $receiverId) {
                $conversationId = $document->data()['id'];
                break;
            }
        }

        if ($conversationId) {
            $query3 = $conversationMessage->where('conversation_id', '=', $conversationId)
//                ->where('is_deleted', '==', false)
                ->orderBy('created_at');
            $querySnapshot3 = $query3->documents();

            $result = [];
            foreach ($querySnapshot3 as $value) {
                $documentData = $value->data();
                $documentData['id'] = $value->id();
                $result[] = $documentData;
            }

            if (count($result) > 0) {
                return response()->json([$result, $currentId, $receiverId]);
            } else {
                return response()->json(null);
            }
        } else {
            return response()->json(null);
        }
    }

    public function sendMessage(Request $request)
    {

        $currentId = Auth::id();
        $receiverId = $request->input('receiverId');
        $message = $request->input('message');

        $firestore = new FirestoreClient([
            'projectId' => 'chat-realtime-firebase-1eaac'
        ]);

        $conversationMessage = $firestore->collection('conversation_message');
        $conversation = $firestore->collection('conversation');

        $conversations = $conversation->where('seeker_id', '=', $receiverId)
            ->where('provider_id', '=', $currentId)
            ->documents();

        $conversationId = null;
        foreach ($conversations as $document) {
            $conversationId = $document->data()['id'];
            break;
        }

        $newDocReference = $conversationMessage->add([
            'conversation_id' => $conversationId,
            'created_at' => FieldValue::serverTimestamp(),
            'file_attach' => null,
            'message' => $message,
            'is_deleted' => false,
            'is_provider_seen' => false,
            'is_seeker_seen' => false,
            'stamp' => null,
            'type' => 0,
        ]);

        try {
            $newDocReference->id();
            return response()->json(['success' => 'success']);
        } catch (\Throwable $error) {
            return response()->json(['error' => $error->getMessage()], 500);
        }
    }

    public function sendFileAttach(Request $request)
    {

        $currentId = $request->input('currentId');
        $receiverId = $request->input('receiverId');
        $attachment = $request->validate([
            'attachment' => 'required|file|mimes:jpeg,png,jpg,gif|max:25000',
        ]);

        $firestore = new FirestoreClient([
            'projectId' => 'chat-realtime-firebase-1eaac'
        ]);

        $conversationMessage = $firestore->collection('conversation_message');
        $conversation = $firestore->collection('conversation');

        $conversations = $conversation->where('seeker_id', '=', $receiverId)
            ->where('provider_id', '=', $currentId)
            ->documents();

        if ($conversations->isEmpty()) {
            $conversations = $conversation->where('seeker_id', '=', $currentId)
                ->where('provider_id', '=', $receiverId)
                ->documents();
        }

        $conversationId = null;
        foreach ($conversations as $document) {
            $conversationId = $document->data()['id'];
            break;
        }

        $imageName = time() . '.' . $request->file('attachment')->extension();
        $path = Storage::disk('attach-chat')->put("chat/{$conversationId}", $request->file('attachment'));
        $path = Storage::disk('attach-chat')->url($path);
        $file_attach = "https://girlsmeee.s3.amazonaws.com" . $path;

        $role = User::where('id', $currentId)->value('role');

        $newDocReference = $conversationMessage->add([
            'conversation_id' => $conversationId,
            'created_at' => FieldValue::serverTimestamp(),
            'file_attach' => $file_attach,
            'message' => null,
            'is_deleted' => false,
            'is_provider_seen' => false,
            'is_seeker_seen' => false,
            'stamp' => null,
            'type' => 0,
            'user_id' => $currentId,
        ]);
        try {
            $newDocReference->id();
            return response()->json(['success' => 'success']);
        } catch (\Throwable $error) {
            return response()->json(['error' => $error->getMessage()], 500);
        }
    }

    public function sendStampv2(Request $request)
    {

        $currentId = $request->input('currentId');
        $receiverId = $request->input('receiverId');
        $selectedStampv2 = $request->input('stampV2');

        $firestore = new FirestoreClient([
            'projectId' => 'chat-realtime-firebase-1eaac'
        ]);

        $conversationMessage = $firestore->collection('conversation_message');
        $conversation = $firestore->collection('conversation');

        $conversations = $conversation->where('seeker_id', '=', $receiverId)
            ->where('provider_id', '=', $currentId)
            ->documents();

        if ($conversations->isEmpty()) {
            $conversations = $conversation->where('seeker_id', '=', $currentId)
                ->where('provider_id', '=', $receiverId)
                ->documents();
        }

        $conversationId = null;
        foreach ($conversations as $document) {
            $conversationId = $document->data()['id'];
            break;
        }

        $role = User::where('id', $currentId)->value('role');

        $newDocReference = $conversationMessage->add([
            'conversation_id' => $conversationId,
            'created_at' => FieldValue::serverTimestamp(),
            'file_attach' => null,
            'message' => null,
            'is_deleted_at' => false,
            'is_provider_seen' => false,
            'is_seeker_seen' => false,
            'stamp' => $selectedStampv2,
            'type' => 0,
        ]);
        try {
            $newDocReference->id();
            return response()->json(['success' => 'success']);
        } catch (\Throwable $error) {
            return response()->json(['error' => $error->getMessage()], 500);
        }
    }

    public function conversation_stamp()
    {
        $stamp = ConversationStamp::all();

        return response()->json($stamp);
    }

}
