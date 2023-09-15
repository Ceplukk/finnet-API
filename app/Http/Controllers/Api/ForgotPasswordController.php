<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rules\Password as RulesPassword;

class ForgotPasswordController extends Controller
{
    public function forgot(Request $request)
    {
        $credentials = $request->validate(['email'   =>  'required|email']);

        $status = Password::sendResetLink($credentials);

        if ( $status == Password::RESET_LINK_SENT) {
            return[
                'status' => __($status)
            ];
        }
        throw ValidationException::withMessages([
            'email' => [trans($status)]
        ]);
    }

    public function reset(Request $request)
    {
        $request->validate([
            'email' =>  'required|email',
            'password' =>  ['required' , 'confirmed'],
            'token' =>  'required|string|'
        ]);

        $status = Password::reset(
            $request->only('email' , 'password' , 'password_confirmation' , 'token'),
            function ($user) use ($request) {
                $user->forceFill([
                    'password' => Hash::make($request->password),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        if($status == Password::PASSWORD_RESET) {
            return response()->json([
                "message"   =>  "password reset succesfuly"
            ]);
        }

        return response([
            "message"   =>  __($status)
        ],500);

    }
}
