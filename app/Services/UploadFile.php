<?php

namespace App\Services;
class UploadFile {

    public function upload_img($file,$path){
     try {

         $new_name = time().'.'.$file->extension();
         $file->storeAs($path,$new_name,'public');
         return $path.'/'.$new_name;

     } catch (\Exception $e) {
       return false;
     }
    }

}
