<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validateData = $request->validate([
            'name'      =>      'required | max:255 | min:3',
            'email'     =>      'required | email | unique:users',
            'company'   =>      'required | max:255 | min:3',
            'password'  =>      'required | max:255 | min:3',
        ]);

        // encripsi password \\
        $validateData["password"] = Hash::make($validateData["password"]);

        User::create($validateData);
        $user = User::where('email', $request->email)->first();
        $transform = new UserResource($user);

        return response()->json([
            'message'       =>      'success , Berhasil menambahkan user baru',
            'user'          =>      $transform, 200
        ]);
    }

    public function login(Request $request)
    {
        $creds = $request->only(['email', 'password']);

        // $token auth()->attempt($creds);
        if (!$token = Auth::claims(['foo' => 'bar'])->attempt($creds)) {
            return response()->json(['error' => 'Incorrect email/password'], 401);
        }

        $cookie = Cookie('jwt', $token);

        return $this->respondWithToken($token)->withCookie($cookie);
    }

    public function logout(Request $request)
    {
        auth()->logout();

        return response()->json([
            "status"    =>  "true",
            "messege"   =>  "berhasil logout"
        ]);
    }

    public function refresh()
    {
        return $this->respondWithToken(Auth::refresh());
    }

    protected function respondWithToken($token)
    {
         return response()->json([
            "status"        =>      "True",
            "massege"       =>      "Success , Login Berhasil....",
            'access_token'  =>      $token,
            'token_type'    =>      'bearer',
            'expires_in'    =>      Auth::factory()->getTTL() * 60
        ]);
    }

}
