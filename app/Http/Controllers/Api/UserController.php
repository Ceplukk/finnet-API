<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use PhpParser\Node\Stmt\Else_;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class UserController extends Controller
{
    public function getAllUser()
    {
        $user = UserResource::collection(User::all());
        return response()->json([
            "status"        =>      "success",
            "message"       =>      "List User.",
            "data"          =>      $user
        ]);
    }
    // get user
    public function getUser()
    {
        $user = JWTAuth::user();
        $transform = new UserResource($user);
        return response()->json(["user" => $transform], 200);
    }

    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'old_password'      =>      'required',
            'new_password'      =>      'required|min:3|max:255',
            'confirm_password'  =>      'required|same:new_password',
        ]);

        if ($validator->fails()) {
            return response()->json([
                "message"   =>  "Validation fails",
                "error"     =>  $validator->errors()
            ]);
        }

        $user = $request->user();
        if (Hash::check($request->old_password, $user->password)) {

            $user->update([
                'password' => Hash::make($request->new_password)
            ]);

            return response()->json([
                "message"    =>    "Password Successfuly updated",
            ]);
        } else {
            return response()->json([
                'message'   =>  "Old Password doesn't matched"
            ]);
        }
    }

    // change email
    public function changeEmail(Request $request)
    {
        $request->validate([
            'email'     =>     'required | email | unique:users',
        ]);

        $user = $request->user();
        $user->update([
            'email'      =>     $request->email
        ]);

        return response()->json([
            "status"    =>      'true',
            "message"   =>      "Success... user updated!",
        ], 200);
    }


    public function updateUser(Request $request)
    {
        $validator = Validator::make($request->all() , [
            'profile_image'      =>      'nullable | image',
            'name'               =>      'required | max:255',

        ]);
        
        try {
            if( $validator->fails() ){
                $errors = $validator->errors()->all();
                return response()->json([
                    'status'    => 'false',
                    'messege'   =>  $errors
                ]);
                
            }else{
                $user = User::find($request->user()->id);
                $user->name = $request->name;
                if($request->file('profile_image')){
                    $file_name = time() . '.' . $request->profile_image->extension();
                    $request->profile_image->move(public_path('images'),$file_name);
                    $path = "public/images/$file_name";
                    $user->profile_image = $path;

                }
                $transform = new UserResource($user);
                $user->update();
                return response()->json([
                    'status'    =>      'true',
                    'message'   =>      'Success.. profile updated!',
                    'data'      =>      $transform
                ]); 
            }
        }catch (\Throwable $th) {
            return response()->json([
                'status'    =>  'false',
                'message'   =>  $th->getMessage()
            ]);
        }
    }
}
