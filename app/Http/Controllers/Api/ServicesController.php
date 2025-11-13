<?php

namespace App\Http\Controllers\api;

use App\Models\Services;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class ServicesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Services::select('id','name','icon','created_at','updated_at')->get();
        if($data->isempty()){
            return response()->json(
                [
                    'success'=>false,
                    'errors'=>'No data fond!'
                ],422
                );
        }
        return response()->json(
            [
                'success'=>true,
                 'data'=>$data
            ]
            );
    }

    public function store(Request $request)
    {

        $validator = Validator::make(
        $request->all(),
        [
        'name'=>'required|string|unique:services|between:6,255',
        'icon'=>'required',
        ],
        [
        'name.required'=>'the name is required.!',
        'name.string'=>'the name is incorrect.!',
       'name.unique'=>'The name of service has already been taken.!',
        'name.between'=>'the name has ben between 6 and 255 caracter.!',
        'icon.required'=>'icon is required.!',
        ]
        );


        if($validator->fails()){
        return response()->json([
        'success'=>false,
        'errors'=>$validator->errors()
        ],422);
        }

        $services = Services::create($request->all());

        return response()->json([
            'message'=>'the services has created successfuly',
            'data'=>$services
        ]);

    }

    public function show(string $id)
    {
      $data = Services::select('name','icon','id')->where('id',$id)->get();

      if ($data->isEmpty()) {
        return response()->json([
        'success'=>false,
        'errors'=>'no services fond data is empty!'
       ]
    ,422
       );
      }

      return response()->json([
        'success'=>true,
        'message'=>'data fond',
        'data'=>$data
      ]);
    }

    public function update(Request $request, string $id)
    {

        // dart f validation : |unique:services,name,'.$id,
        //--------------me-------------
        /*


         $get_services = Services::select('name')->where('id',$id)->first();
         if($get_services->name != $request->name){
           $test_service_name = Services::select('name')->where('name',$request->name)->first();
           if($test_service_name){
             return response()->json([
                'success'=>false,
                'errors'=>'The name '.$request->name.' has already been taken.!'
             ]);
           }
         }*/

        //----------------chat gbt-----------

        /*
        $service = Services::findOrFail($id);
    // Check if name changed
    if ($service->name !== $request->name) {
        $exists = Services::where('name', $request->name)->exists();

        if ($exists) {
            return response()->json([
                'success' => false,
                'errors' => 'The name "' . $request->name . '" has already been taken!',
            ], 400);
        }
    }
        */




        $request->merge(['id'=>$id]);
        $validator = Validator::make(
        $request->all(),
        [
        'id'=>'required|exists:services,id',
        'name'=>'nullable|string|between:6,255|unique:services,name,'.$id,
        'icon'=>'required',
        ],
        [
        'id.required'=>'Services selected id is required.!',
        'id.exists'=>'Services id is invalid.!',
        'name.required'=>'the name is required.!',
        'name.string'=>'the name is incorrect.!',
        'name.between'=>'the name has ben between 6 and 255 caracter.!',
        'name.unique'=>'The name '.$request->name.' has already been taken.!',
        'icon.required'=>'icon is required.!',

        ]
        );

        if($validator->fails()){
            return response()->json([
                'success'=>false,
                'errors'=>$validator->errors()
            ],422);
        }


     $service = Services::findOrFail($request->id);
     $data = $service->update([
        'name'=>$request->name,
        'icon'=>$request->icon
     ]);

           return response()->json([
                'success'=>true,
                'message'=>'update has ben successfuly.'
            ]);
    }

    public function destroy(string $id)
    {

       $services = Services::find($id);

       if(!$services){
            return response()->json([
                'success'=>false,
                'errors'=>'Services not found!!'
            ],422);
       }

       $services->delete();
        return response()->json([
        'success'=>true,
        'message'=>'service has ben deleted successfuly.'
    ]);
    }
}
