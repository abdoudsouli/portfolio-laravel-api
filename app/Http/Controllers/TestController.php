<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TestController extends Controller
{
    public function index(){
        return view('forntend.index',['name'=>'dsouli']);
    }

    public function getid(Request $request,$id){

    $request->merge(['id' => $id]);

    $validator = Validator::make($request->all(), [
        'id' => 'required|numeric',
    ], [
        'id.required' => 'الثمن ضروري',
        'id.numeric'  => 'الثمن خاصو يكون رقم صحيح',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'errors' => $validator->errors()
        ], 422);
    }

        return response()->json([
            'success'=>true,
            'id'=>$id
        ]);
    }
}
