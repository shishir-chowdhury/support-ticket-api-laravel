<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json([
        'status' => 'ok',
        'message' => 'Support Ticket API is running',
        'timestamp' => now()->toIso8601String(),
    ]);
});
