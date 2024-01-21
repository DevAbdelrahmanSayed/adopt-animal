<?php

namespace Modules\Chat\app\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Chat\app\Events\MessageEvent;
use Modules\Chat\app\Models\Chat;
use Modules\User\app\Models\User;

class ChatController extends Controller
{
    public function sendMessage(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'message' => 'required|string',
        ]);
        $user = Auth::user();
        $receiver = User::findOrFail($request->receiver_id);

        // Create the new message
        $message = Chat::create([
            'sender_id' => $user->id,
            'receiver_id' => $receiver->id,
            'message' => $request->message,
        ]);

        // Broadcast the new message
        broadcast(new MessageEvent($user, $message, $message->created_at, $receiver))->toOthers();

        return ApiResponse::sendResponse(201, 'Message created successfully', [
            'messages' => $message
        ]);
    }

    public function getMessages(Request $request)
    {
        $user = Auth::user();

        // Retrieve messages received by the authenticated user from the sender
        $receiverMessages = Chat::where('receiver_id', $user->id)
            ->orderBy('created_at', 'asc')
            ->get();

        return ApiResponse::sendResponse(200, 'Receiver messages retrieved successfully', ['messages' => $receiverMessages]);
    }

}
