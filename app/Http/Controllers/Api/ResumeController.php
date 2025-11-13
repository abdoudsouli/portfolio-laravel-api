<?php

namespace App\Http\Controllers\Api;

use App\Models\Resume;
use Illuminate\Http\Request;
use App\Services\ResponseApi;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class ResumeController extends Controller
{
    public function index()
    {
    try {
     $resume = Resume::with('user')->get();
     return ResponseApi::data(null,'resume',$resume);
    } catch (\Exception $e) {
       return ResponseApi::error('Error : '.$e.getMessage(),500);
    }
    }


    public function store(Request $request)
    {
        try {
            $user=$request->user();
            $validator = Validator::make(
            $request->all(),
            [
            'start_date'=>
            [
                'required',
                'regex:/^[0-9]{4}$/',
                'integer',
                'between:2004,' . date('Y') + 1
            ],
            'end_date'=>
            [
             'required',
             'regex:/^[0-9]{4}$/',
             'integer',
             'between:2004,'.date('Y')+1,
             'after_or_equal:start_date'
            ],
            'title'=>'required|max:225',
            'company'=>'required|max:225|unique:resumes',
            'description'=>'required|max:1500',
            ]
            );

            if($validator->fails()){
            return ResponseApi::error($validator->errors(),401);
            }
            $add_resume = Resume::create(
                [
                    'user_id'=>$user->id,
                    'start_date'=>$request->start_date,
                    'end_date'=>$request->end_date,
                    'title'=>$request->title,
                    'company'=>$request->company,
                    'description'=>$request->description
                ]
                );

            return ResponseApi::success('resume Added Successfully');


        } catch (\Exception $e) {
            return ResponseApi::error('Error : '.$e->getMessage(),500);
        }
    }

    public function show(string $id)
    {
       try {
      $resume = Resume::with('user')->find($id);
      if($resume){
       return ResponseApi::data(null,'resume',$resume);
      }
      return ResponseApi::error('no data found !',404);
       } catch (\Exception $e) {
      return ResponseApi::error('Error : '.$e->getMessage(),401);
       }
    }

    public function update(Request $request, string $id)
    {
      try {
        $user = $request->user();
        $request->merge(['id'=>$id]);
        $validator = Validator::make(
            $request->all(),
            [
            'id'=>'required|integer|exists:resumes,id',
             'start_date'=>
             [
                'required',
                'regex:/^[0-9]{4}$/',
                'integer',
                'between:2004,'.date('Y')+1
             ],
             'end_date'=>
             [
              'required',
              'regex:/^[0-9]{4}$/',
              'integer',
              'between:2004,'.date('Y')+1,
              'after_or_equal:start_date'
             ],
             'title'=>'required|max:225',
             'company'=>'required|max:225|unique:resumes,company,'.$id,
             'description'=>'required|max:1500',
            ]
        );

       if($validator->fails()){
            return ResponseApi::error($validator->errors(),401);
        }
        //test id_user equal auth user

        $test_user = Resume::where('id',$request->id)->where('user_id',$user->id)->first();
        if(!$test_user){
         return ResponseApi::error('You Dont have permistion to update this resume',401);
        }
        $test_user->update(
            [
                    'start_date'=>$request->start_date,
                    'end_date'=>$request->end_date,
                    'title'=>$request->title,
                    'company'=>$request->company,
                    'description'=>$request->description
            ]);
        return ResponseApi::data(
            'Resume updated successfully',
            'resume',
            $test_user->load('user')
        );

      } catch (\Exception $e) {
         return ResponseApi::error('Error : '.$e->getMessage(),401);
      }
    }

    public function destroy(string $id)
    {
    try {

        $delete = Resume::find($id);
        if($delete){
        $delete->delete();
        return ResponseApi::success('Resume deleted successfully');
        }
        return ResponseApi::error('Resume not found',401);

    } catch (\Exception $e) {
    return ResponseApi::error('Error : '.$e->getMessage(),401);
    }
    }
}
