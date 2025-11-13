<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
   public function index(){
     return User::get();
   }

   public function getbyid(Request $request , $id){

    $request->merge(['id'=>$id]);

    $validator = Validator::make(
     $request->all(),
     [
        //digits_between أرقام between min and max
        //min:4|max:8' for alidate character length (string), use min and max instead.

        'id'=>'required|numeric|digits_between:1,225'
     ],
     [
        'id.required' => 'uesr not fond!! code:285',
        'id.numeric' =>'uesr not fond!! code:254',
        'id.max' =>'uesr not fond!! code:542',
     ]
    );


    // if have a errors
    if($validator->fails()){
        return response()->json(
            //errors message
            [
           'seccess'=>false,
            'errors'=>$validator->errors()
        ]
        ,
        422//code error
    );
    }


    $data = User::where('id',$request->id)->get();

    if($data->isEmpty()){
        return response()->json(
        //errors message
        [
        'seccess'=>false,
        'errors'=>'no user fond data is empty!'
        ]
        ,
        422//code error
    );
    }

    return response()->json(
        [
            'success'=>true,
            'data'=>$data
        ]
        );

   }
}
