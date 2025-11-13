<?php
namespace App\Services;

class ResponseApi{

    static function success($msg){
        return response()->json(
            [
                'success'=>true,
                'message'=>$msg
            ],200
        );
    }

   static function error($msg,$code){
        return response()->json(
            [
                'success'=>false,
                'errors'=>$msg
            ],$code
        );
    }

      static function data($msg,$key,$data){
        return response()->json(
            [
                'success'=>true,
                'message'=>$msg,
                $key=>$data
            ],200
        );
    }

}
