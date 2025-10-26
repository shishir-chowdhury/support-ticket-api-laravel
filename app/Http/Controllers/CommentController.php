<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\Ticket;
use Illuminate\Support\Facades\Auth;
use App\Events\MessageSent;

class CommentController extends Controller
{
    public function index($ticket_id)
    {
        $ticket = Ticket::findOrFail($ticket_id);
        $user = Auth::user();

        if ($user->role !== 'admin' && $ticket->user_id !== $user->id) {
            return response()->json(['error' => 'Forbidden'], 403);
        }

        $comments = Comment::with('user')->where('ticket_id', $ticket_id)->get();
        return response()->json($comments);
    }

//    public function store(Request $request, $ticket_id)
//    {
//        $ticket = Ticket::findOrFail($ticket_id);
//        $user = Auth::user();
//
//        if ($user->role !== 'admin' && $ticket->user_id !== $user->id) {
//            return response()->json(['error' => 'Forbidden'], 403);
//        }
//
//        $validated = $request->validate(['message' => 'required|string']);
//        $comment = Comment::create([
//            'ticket_id' => $ticket_id,
//            'user_id' => $user->id,
//            'message' => $validated['message'],
//        ]);
//
//        return response()->json($comment, 201);
//    }


    public function store(Request $request, $ticket_id)
    {
        $ticket = Ticket::findOrFail($ticket_id);
        $user = Auth::user();

        if ($user->role !== 'admin' && $ticket->user_id !== $user->id) {
            return response()->json(['error' => 'Forbidden'], 403);
        }

        $validated = $request->validate([
            'message' => 'required|string',
        ]);

        $comment = Comment::create([
            'ticket_id' => $ticket_id,
            'user_id' => $user->id,
            'message' => $validated['message'],
        ]);

        $comment->load('user');

        \Log::info('About to broadcast MessageSent', [
            'comment_id' => $comment->id,
            'ticket_id' => $ticket_id,
            'channel' => 'private-ticket.' . $ticket_id
        ]);

        try {
            broadcast(new MessageSent($comment));
            \Log::info('Broadcast successful');
        } catch (\Exception $e) {
            \Log::error('Broadcast failed: ' . $e->getMessage());
        }

        return response()->json($comment, 201);
    }
}
