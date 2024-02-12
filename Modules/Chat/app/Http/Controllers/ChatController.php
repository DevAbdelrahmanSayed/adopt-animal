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

        $message = Chat::create([
            'sender_id' => $user->id,
            'receiver_id' => $receiver->id,
            'message' => $request->message,
        ]);

        broadcast(new MessageEvent($user, $message, now(), $receiver))->toOthers();

        return ApiResponse::sendResponse(201, 'Receiver messages retrieved successfully');

    }
}
