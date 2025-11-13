<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
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
            return response()->json(
                [
                    'success'=>false,
                    'errors'=>$validator->errors()
                ]
                ,
                422
                );
        };

        $user = User::where('email', $request->email)->first();
        $passwordtest = Hash::check($request->password, $user->password);
        if (!$user || !$passwordtest) {
            return response()->json(
                [
                    'message' =>'Email address or password is incorrect!'
                    ]
                , 401);
        }

        // Delete old tokens if you want (optional)
        $user->tokens()->delete();

        // Create new token
        $token = $user->createToken('api_token')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token,
        ]);
    }


    public function logout(Request $request){
/*
// حذف التوكن الحالي فقط
هاد الكود كيعني:

المستخدم لي دير login دابا، نحيد ليه فقط الـ token ديالو الحالي (يعني يخرج من هاد الجهاز فقط)

$request->user()->currentAccessToken()->delete();

return response()->json([
'success' => true,
'message' => 'Logged out successfully'
]);
*/

 $request->user()->tokens()->delete();

    return response()->json([
        'success' => true,
        'message' => 'Logged out from all devices'
    ]);

    }
}
