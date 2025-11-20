<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Services\ResponseApi;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{

    public function register(Request $request)
    {

    $validatour = Validator::make(
    $request->all(),
    [
     'name' => 'required',
            'date_birth' => 'required|date_format:Y-m-d',
            'address' => 'required',
            'phone' => 'required',
            'about_me' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
    ],
    [
      'date_birth.required'=>'Date birth is required',
      'date_birth.date_format'=>'Date birth fromat is incorrect ex:Y-m-d',
      'address.required'=>'Address is required',
      'phone.required'=>'Phone is required',
      'about_me.required'=>'About me is required',
      'email.required'=>'E-mail address is required',
      'email.email'=>'E-mail is incorrect fromat ex : email@email.com',
      'email.unique'=>'E-mail address is alrady taken!',
      'password.required'=>'Password is required',
      'password.min'=>'Password has ben a min 6 caracter!',
    ]
      );

       if($validatour->fails()){
       return response()->json(
        [
            'success'=>false,
            'errors'=>$validatour->errors()
        ],422
    );
       }
$request->name = strtoupper($request->name);
        $user = User::create([
            'name' => $request->name,
            'date_birth' => $request->date_birth,
            'address' => $request->address,
            'phone' => $request->phone,
            'about_me' => $request->about_me,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        //$token = $user->createToken('api_token')->plainTextToken;

        return response()->json([
            'success'=>true,
            'message'=>'registeration successfuly',
            //'data'=>$user
        ]);
    }

    public function login(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'email'=>'required|email',
                'password'=>'required|min:6'
            ],
            [
                'email.required'=>'E-mail address is required',
                 'email.email'=>'E-mail address is incorrect ex : email@email.com',
                  'password.required'=>'Password is required',
                   'password.min'=>'Password has ben a min 6 caracter!',
            ]
        );

        if($validator->fails()){
            return ResponseApi::error($validator->errors(),401);
        };

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return ResponseApi::error('Email address or password is incorrect!',401);
        }
         $passwordtest = Hash::check($request->password, $user->password);
         if (!$passwordtest) {
            return ResponseApi::error('Email address or password is incorrect!',401);
        }
        if(!$user->hasVerifiedEmail()){
         return ResponseApi::error('Authentication failed please verify your email address',401);
        }

        // Delete old tokens if you want (optional)
        $user->tokens()->delete();

        // Create new token
        $token = $user->createToken('api_token')->plainTextToken;

        return ResponseApi::data(null,'token',$token);
    }

    public function logout(Request $request){
    $request->user()->tokens()->delete();
    return ResponseApi::success('Logged out from all devices');
    }
}
