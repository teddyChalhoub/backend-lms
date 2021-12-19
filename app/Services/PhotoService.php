<?php

namespace App\Services;
use Illuminate\Support\Facades\File;

class PhotoService{

    public function __construct(){

    }

    public function addFile($request): ?string
    {
        if ($image = $request->file('picture')) {
            $destinationPath = 'image/';
            $profileImage = $image->getATime() . "." . $image->getClientOriginalExtension();
            $image->move($destinationPath, $profileImage);
            return $destinationPath . $profileImage;
        }
        return  null;
    }

    public function deleteFile(String $photoUrl){
        if(File::exists(public_path($photoUrl))){
            return File::delete(public_path($photoUrl));
        }
    }
}
