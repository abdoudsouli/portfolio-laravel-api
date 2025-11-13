<?php

namespace App\Http\Controllers\Api;

use App\Models\Skills;
use Illuminate\Http\Request;
use App\Services\ResponseApi;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class SkillsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $data = Skills::select()->get();

        return ResponseApi::data(null,'Skills',$data);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $validator = Validator::make(
            $request->all(),
            [
             'name'=>'required|between:2,255|unique:skills',
             'level'=>'required|numeric|between:0,100'
            ],
            [
                'name.required'=>'the name is incorecct!!',
                'name.between'=>'the name has been between 2 and 250 caracters',
                'name.unique'=>'the name of skills has already been taken!!',
                'level.required'=>'the level is required',
                'level.float'=>'the level is incorecct example : 1.5 - 55 - 99.99',
                'level.float'=>'the level is incorecct between : 0 and 99.99',
            ]
        );

        if($validator->fails()){
            return ResponseApi::error($validator->errors(),401);
        }

        $skills = Skills::create([
            'name'=>$request->name,
            'level'=>$request->level
        ]);

        return ResponseApi::success('Skill Added successfully');
    }


    public function show(string $id)
    {
            $data = Skills::select('id','name','level')->where('id',$id)->get();

      if ($data->isEmpty()) {
     return ResponseApi::error('no services fond data is empty!',401);
      }
      return ResponseApi::data(null,'skills',$data);
    }

    public function update(Request $request, string $id)
    {
            $request->merge(['id'=>$id]);
            $validator = Validator::make(
            $request->all(),
            [
             'id'=>'required|exists:skills,id',
             'name'=>'required|between:2,255|unique:skills,name,'.$id,
             'level'=>'required|numeric|between:0,100'
            ],
            [
                'id.required'=>'Services selected id is required.!',
                'id.exists'=>'Services id is invalid.!',
                'name.required'=>'the name is incorecct!!',
                'name.between'=>'the name has been between 2 and 250 caracters',
                'name.unique'=>'the name '.$request->name.' has already been taken.!',
                'level.required'=>'the level is required',
                'level.float'=>'the level is incorecct example : 1.5 - 55 - 99.99',
                'level.float'=>'the level is incorecct between : 0 and 99.99',
            ]
        );

        if($validator->fails()){
            return ResponseApi::error($validator->errors(),401);
        }

       $skill = Skills::withTrashed()->find($request->id);

       if(!$skill){
  return ResponseApi::error('Skill not found',404);
       }

    if ($skill->trashed()) {
          return ResponseApi::error('This Skill is deleted. You cannot update it.',404);
    }

        $data = $skill->update([
            'name'=>$request->name,
            'level'=>$request->level
        ]);
        return ResponseApi::data('Skill updated successfully','skills',$data);
    }

    public function destroy(string $id)
    {
     $skill = Skills::findOrfail($id);
     $delete = $skill->delete();
       return ResponseApi::success('Skill deleted successfully');
    }
}
