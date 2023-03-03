<?php

namespace App\Http\Controllers;

use App\Models\User;
use Google\Cloud\Firestore\FirestoreClient;
use Google\Type\DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use function PHPUnit\Framework\assertJson;
use function PHPUnit\Framework\isJson;

class UserController extends Controller
{
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
        // Lấy ra id conversaion theo provider_id và seeker_id
        foreach ($conversations as $document) {
            $conversationId = $document->data()['id'];
        }

        // lấy tin nhắn
        $query3 = $conversationMessage->where('conversation_id', '=', $conversationId)
        ->orderBy('created_at');
        $querySnapshot3 = $query3->documents();

        foreach ($querySnapshot3 as $value) {
            $result[] = $value->data();
        }

        return response()->json($result);
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

    // Set timezone and created_at
        $timezone = new \DateTimeZone('Asia/Ho_Chi_Minh');
        $createdAt = (new \DateTimeImmutable('now', $timezone))->getTimestamp();

    // Set message type
        $role = User::where('id', $currentId)->value('role');
        $type = $role == 2 ? 1 : ($role == 3 ? 0 : 3);

    // Create new document and set its data
        $newDocReference = $conversationMessage->add([
            'attachment' => null,
            'conversation_id' => $conversationId,
            'created_at' => $createdAt,
            'deleted_at' => 0,
            'message' => $message,
            'provider_seen' => 0,
            'seeker_seen' => 0,
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
