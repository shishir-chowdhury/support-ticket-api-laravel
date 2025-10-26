<?php

namespace App\Events;

use App\Models\Comment;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcast
{
    use SerializesModels;

    public $comment;

    public function __construct(Comment $comment)
    {
        $this->comment = $comment->load('user');
    }

    public function broadcastOn()
    {
        return new PrivateChannel('ticket.' . $this->comment->ticket_id);
    }

    public function broadcastWith()
    {
        return ['comment' => $this->comment];
    }

    public function broadcastAs()
    {
        return 'MessageSent';
    }
}
