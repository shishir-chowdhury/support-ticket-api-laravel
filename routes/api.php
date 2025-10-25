<?php
use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\TicketController;

Route::get('/health', function(){
    try {
        DB::connection()->getPdo();
        return response()->json(['db'=>'ok']);
    } catch (\Exception $e) {
        return response()->json(['db'=>'fail', 'error'=>$e->getMessage()]);
    }
});
Route::post('auth/register', [AuthController::class,'register']);
Route::post('auth/login', [AuthController::class,'login']);

Route::middleware('auth:api')->group(function(){
    Route::get('auth/me', [AuthController::class,'me']);
    Route::post('auth/logout', [AuthController::class,'logout']);
});

Route::middleware('auth:api')->group(function () {
    Route::apiResource('tickets', TicketController::class);
});

