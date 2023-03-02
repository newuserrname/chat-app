<?php

namespace App\Http\Controllers;

use App\Models\User;
use Google\Cloud\Firestore\FirestoreClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use function PHPUnit\Framework\assertJson;
use function PHPUnit\Framework\isJson;

class UserController extends Controller
{
    public function getUserById($id)
    {
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

    public function getMessages($currentId, $receiverId)
    {
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

        $query3 = $conversationMessage->where('conversation_id', '=', $conversationId);
        $querySnapshot3 = $query3->documents();

        foreach ($querySnapshot3 as $value) {
            $result[] = $value->data();
        }

        return response()->json($result);
    }
}
