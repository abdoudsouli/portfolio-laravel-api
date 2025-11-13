<?php

namespace App\Http\Controllers\Api;

use App\Models\Projects;
use App\Models\Project_img;
use Illuminate\Http\Request;
use App\Services\ResponseApi;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;


class ProjectController extends Controller
{

    public function index()
    {
       $projects = Projects::with(['imgs','user'])->get();
       if($projects->isempty()){
        return ResponseApi::error('No project found',404);
       }
       return ResponseApi::data(null,'projects',$projects);
    }

    public function meproject(Request $request){
        $user = $request->user();
            $projects = Projects::with(['imgs','user'])
            ->where('user_id',$user->id)
            ->get();
       if($projects->isempty()){
        return ResponseApi::error('No project found',404);
       }
      return ResponseApi::data(null,'projects',$projects);
    }

    public function store(Request $request){
  try {

        $user = $request->user();
        $validator = Validator::make(
        $request->all(),
        [
        'name' => 'required|string|max:255|unique:projects,name',
        'profile' => 'required|string',
        'type' => 'required|string',
        'path' => 'required',
        'path.*' => 'string',
        ],
        [
        'name.required' => 'this name is incorrect',
        'name.string' => 'this name is incorrect ,be string',
        'name.max' => 'this name is incorrect ,max 255',
        'name.unique' => 'this name ('.$request->name.') is already taken,max 255',
        'profile.required' => 'the profil is incorrect',
        'profile.string' => 'the profil is incorrect be string',
        'type.required' => 'the profil is incorrect ',
        'type.string' => 'the profil is incorrect be string',
        'path.required' => 'imges is incorrect',
        'path.string' => 'imges is incorrect be string',
        ]
        );

        if($validator->fails()){
          return  ResponseApi::error($validator->errors(),401);
        }
         $project = Projects::create(
        [
            'name'=>$request->name,
            'profile'=>$request->profile,
            'type'=>$request->type,
            'user_id'=>$user->id,
        ]);

        $project_img = Project_img::create(
            [
            'path'=>$request->path,
           'project_id'=>$project->id,
            ]);

    return ResponseApi::data('Project created successfully!','project',$project->load(['imgs','user']));

    } catch (\Exception $e) {
        return ResponseApi::error('Error: could not create project! : '.$e->getMessage(),500);
    }
    }

    public function show(string $id)
    {
       try {

       $projects = Projects::with(['imgs','user'])->where('id',$id)->get();
       if($projects->isempty()){
        return ResponseApi::error('No project found',404);
       }
       return ResponseApi::data(null,'projects',$projects);

       } catch (\Exception $e) {
        return ResponseApi::error('Error: could not create project! : '.$e->getMessage(),500);
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
        'id' => 'required|numeric|exists:projects,id',
        'name' => 'required|string|max:255|unique:projects,name,'.$id,
        'profile' => 'required|string',
        'type' => 'required|string',
        'path' => 'required',
        'path.*' => 'string',
        ],
        [
        'id.required' => 'the project id is incorrect',
        'id.numeric' => 'the project is incorrect',
        'id.exiset' => 'the project dosant existe',
        'name.required' => 'this name is incorrect',
        'name.string' => 'this name is incorrect ,be string',
        'name.max' => 'this name is incorrect ,max 255',
        'name.unique' => 'this name ('.$request->name.') is already taken,max 255',
        'profile.required' => 'the profil is incorrect',
        'profile.string' => 'the profil is incorrect be string',
        'type.required' => 'the profil is incorrect ',
        'type.string' => 'the profil is incorrect be string',
        'path.required' => 'imges is incorrect',
        'path.string' => 'imges is incorrect be string',
        ]
        );

        if($validator->fails()){
            return ResponseApi::error($validator->errors(),401);
        }

        $test_is_myproject = Projects::where('id',$request->id)
        ->where('user_id',$user->id)
        ->first();

        if(!$test_is_myproject){
            return ResponseApi::error('You are not allowed to update this project or it does not exist.',404);
        }

        $test_is_myproject->update(
            [
            'name'=>$request->name,
            'profile'=>$request->profile,
            'type'=>$request->type,
            ]
            );

       $test_is_myproject_img = Project_img::where('project_id',$test_is_myproject->id)
        ->first();

       if($test_is_myproject_img){
            $test_is_myproject_img->update(
            [
            'path'=>$request->path,
            ]
            );
       }

            return ResponseApi::data(
            'Project updated successfully!',
            'project',
            $test_is_myproject->load('imgs','user')
            );

        } catch (\Exception $e) {
           return ResponseApi::error('Error : could not update project!: '.$e->getMessage(),500);
        }

    }
    public function destroy(string $id)
    {
        try {
          $deleteProject = Projects::findOrfail($id);
          if($deleteProject){
            $deleteProject->delete();
           return ResponseApi::success('Project deleted successfully.!');
          }
           return ResponseApi::error('Error : could not delete project! code:254',500);
        } catch (\Exception $e) {
          return ResponseApi::error('Error : could not delete project!: '.$e->getMessage(),500);
        }
    }

    public function projectdeleted(){
        try {
        $projectdeleted = Projects::onlyTrashed()->with('imgs','user')->get();
        if($projectdeleted->isempty()){
        return ResponseApi::error('no data found!!',404);
        }
        return ResponseApi::data(null,'projectdeleted',$projectdeleted);
        } catch (Exception $e) {
          return ResponseApi::error('Error : could not delete project!:'.$e->getMessage(),500);
        }
    }

    public function projectdeletedrestore(Request $request){
         try {
$validator = Validator::make(
$request->all(),
[
'deletedId'=>
[
'required',
'integer',
Rule::exists('projects', 'id')->whereNotNull('deleted_at')
]
],
[
'deletedId.required'=>'Refronce Project is required',
'deletedId.integer'=>'Refronce Project is incoreect',
'deletedId.exists'=>'Project is not found'
]
);

if($validator->fails()){
return ResponseApi::error($validator->errors(),401);
}
        $projectdeletedrestore = Projects::onlyTrashed()->find($request->deletedId);
        if($projectdeletedrestore){
         $projectdeletedrestore->restore();
        return ResponseApi::success('Project restored successfully');
        }else{
         return ResponseApi::error('no data found!!',404);
        }
        } catch (Exception $e) {
          return ResponseApi::error('Error : could not delete project!:'.$e->getMessage(),500);
        }
    }
}
