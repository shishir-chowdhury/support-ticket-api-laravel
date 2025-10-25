<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use PHPOpenSourceSaver\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller
{
    public function register(Request $r)
    {
        $r->validate([
            'name'=>'required|string',
            'email'=>'required|email|unique:users',
            'password'=>'required|min:6',
            'role'=>'required|in:admin,customer'
        ]);

        $user = User::create([
            'name'=>$r->name,
            'email'=>$r->email,
            'password'=>$r->password,
            'role'=>$r->role
        ]);

        $token = JWTAuth::fromUser($user);

        return response()->json(['user'=>$user,'token'=>$token], 201);
    }

    public function login(Request $r)
    {
        $credentials = $r->only(['email','password']);
        try {
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['error'=>'Invalid credentials'], 401);
            }
        } catch (JWTException $e) {
            return response()->json(['error'=>'Could not create token'], 500);
        }
        return response()->json(['token'=>$token,'user'=>auth()->user()]);
    }

    public function me()
    {
        return response()->json(auth()->user());
    }

    public function logout()
    {
        JWTAuth::invalidate(JWTAuth::getToken());
        return response()->json(['message'=>'Logged out']);
    }
}
