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

    public function upload_file($file, $folder)
        {
           try {
             $extension = $file->getClientOriginalExtension();
            $filename = uniqid() . '_' . time() . '.' . $extension;
            $file->storeAs('public/' . $folder, $filename);
            return $filename;
           } catch (\Exception $e) {
              return false;
           }
        }

}
