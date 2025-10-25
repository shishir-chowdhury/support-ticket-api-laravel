<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $fillable = [
        'user_id', 'subject', 'description', 'category', 'priority', 'attachment', 'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
