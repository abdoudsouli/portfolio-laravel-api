<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Services\UploadFile;
use Illuminate\Http\Request;
use App\Services\ResponseApi;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
   public function index(Request $request){
        try {

        $user=$request->user();
        $profil = User::select('id','name','date_birth','address','phone','about_me','email','role','avatar')->where('id',$user->id)->first();
        if ($profil->avatar !== null) {
            $profil->avatar = 'https://domain.com/img-profil/'.$profil->avatar;
        }
        return ResponseApi::data(null,'profil',$profil);
        } catch (\Exception $e) {
        return ResponseApi::error('Error :'.$e->getMessage(),500);
        }
   }


   public function change_password(Request $request){
       try {
        $user = $request->user();

        $validator = Validator::make(
            $request->all(),
            [
                'old_password'=>'required|min:6|max:25',
                'new_password'=>'required|min:6|confirmed'
            ]
        );

        if($validator->fails()){
           return ResponseApi::error($validator->errors(),401);
        }

        $get_user = User::where('id',$user->id)->first();
        $old_password_check = Hash::check($request->old_password, $get_user->password);

        if(!$old_password_check){
            return ResponseApi::error('Current password is incorrect',401);
        }

        $get_user->update([
            'password'=>Hash::make($request->new_password)
         ]);

         return ResponseApi::success('Password successfully updated');

       } catch (\Exception $e) {
        return ResponseApi::error('Error :'.$e->getMessage(),500);
       }
   }

   public function update_profil(Request $request){
    try {

    $user = $request->user();

    $validator = Validator::make(
     $request->all(),
     [
        'name'=>'required|max:225',
        'date_birth'=>'required|date_format:Y-m-d',
        'address'=>'required|max:225',
        'phone'=>
        [
            'required',
            'regex:/^\+[0-9]{1,4}[0-9]{8,10}$/',
        ],
        'about_me'=>'nullable|max:225'
    ],
    [
        'phone.regex'=>'Phone Fromat is incorecct ex: +1xxxxxxx'
    ]
    );

    if($validator->fails()){
        return ResponseApi::error($validator->errors(),401);
    }

    $update_profil = User::where('id',$user->id)->first();
    if (!$update_profil) {
     return ResponseApi::error('User not found',404);
    }

    $update_profil->update(
        [
          'name'=>strtoupper($request->name),
          'date_birth'=>$request->date_birth,
          'address'=>$request->address,
          'phone'=>$request->phone,
          'about_me'=>$request->about_me
        ]
        );


    return ResponseApi::success('profil info updated successfully');

    } catch (\Exception $e) {
     return ResponseApi::error('Error :'.$e->getMessage(),500);
    }
   }

   public function avatar(Request $request){
    try {

    $user  = $request->user();
    $uploadfile = new UploadFile;

    $useravatar = User::find($user->id);

    if (!$useravatar) {
     return ResponseApi::error('User not Found',401);
    }

    $validator = Validator::make(
    $request->all(),
    [
        'avatar'=>'required|image|mimes:jpeg,jpg,png|max:2048'
    ]
    );

    if ($validator->fails()) {
      return responseApi::error($validator->errors(),401);
    }

    $newnameavatar = $uploadfile->upload_img($request->avatar,'avatar');
    $useravatar->update([
     'avatar'=>$newnameavatar
    ]);

     return ResponseApi::success('Avatar uploaded successfully');
    } catch (\Exception $e) {
        return ResponseApi::error('Error : '.$e->getMessage(),500);
    }

   }

}
