<?php

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Log;

Broadcast::channel('ticket.{id}', function ($user, $id) {
    Log::info('Channel auth', [
        'user' => $user ? $user->id : null,
        'ticket_id' => $id,
        'role' => $user ? $user->role : null
    ]);

    $ticket = \App\Models\Ticket::find($id);
    return $ticket && ($user->role === 'admin' || $ticket->user_id === $user->id);
});
