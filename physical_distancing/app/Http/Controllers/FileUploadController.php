<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FileUploadController extends Controller
{
    public function formSubmit(Request $request)
    {
        
        $fileName = str_replace(" ", "-", $request->fileName).'-'.time().'.'.$request->file->getClientOriginalExtension();
        $request->file->move(public_path('upload'), $fileName);
        return response()->json(['success'=>'You have successfully upload file.', 'fileUploaded' => public_path('upload').'\\'.$fileName]);
    }

    public function getFilesList() 
    {

        $files = opendir(public_path('upload'));
        $result = [];
        if($files){
           while (($fileName = readdir($files)) !== FALSE) {
            if($fileName != '.' && $fileName != '..'){
                $result[] = ['fileName' => $fileName, 'fileLoc' => public_path('upload')];
            }
           
           }
        }
        return response()->json($result);
    }
}
