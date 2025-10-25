<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TicketController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'admin') {
            // Admin sees all tickets with user info
            $tickets = Ticket::with('user')->get();
        } else {
            // Customer sees only their own tickets
            $tickets = $user->tickets()->with('user')->get();
        }

        return response()->json($tickets);
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'subject' => 'required|string',
            'description' => 'required|string',
            'category' => 'nullable|string',
            'priority' => 'nullable|in:low,medium,high',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf,docx',
        ]);

        if ($request->hasFile('attachment')) {
            $validated['attachment'] = $request->file('attachment')->store('attachments', 'public');
        }

        $validated['user_id'] = Auth::id();
        $ticket = Ticket::create($validated);

        return response()->json($ticket, 201);
    }

    public function show($id)
    {
        $ticket = Ticket::findOrFail($id);
        $user = Auth::user();

        if ($user->role != 'admin' && $ticket->user_id != $user->id) {
            return response()->json(['error' => 'Forbidden'], 403);
        }

        return response()->json($ticket);
    }

    public function update(Request $request, $id)
    {
        $ticket = Ticket::findOrFail($id);
        $user = Auth::user();

        if ($user->role != 'admin' && $ticket->user_id != $user->id) {
            return response()->json(['error' => 'Forbidden'], 403);
        }

        $validated = $request->validate([
            'subject' => 'sometimes|string',
            'description' => 'sometimes|string',
            'category' => 'nullable|string',
            'priority' => 'nullable|in:low,medium,high',
            'status' => 'nullable|in:open,in_progress,resolved,closed',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf,docx',
        ]);

        if ($request->hasFile('attachment')) {
            // Delete old file if exists
            if ($ticket->attachment) {
                Storage::disk('public')->delete($ticket->attachment);
            }
            $validated['attachment'] = $request->file('attachment')->store('attachments', 'public');
        }

        $ticket->update($validated);

        return response()->json($ticket);
    }

    public function destroy($id)
    {
        $ticket = Ticket::findOrFail($id);
        $user = Auth::user();

        if ($user->role != 'admin' && $ticket->user_id != $user->id) {
            return response()->json(['error' => 'Forbidden'], 403);
        }

        if ($ticket->attachment) {
            Storage::disk('public')->delete($ticket->attachment);
        }

        $ticket->delete();
        return response()->json(['message' => 'Ticket deleted']);
    }
}
