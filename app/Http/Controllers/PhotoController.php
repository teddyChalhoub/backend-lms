<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Student;
use App\Models\Photo;
use App\Services\PhotoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class PhotoController extends Controller
{
    protected $photoService;

    public function __construct()
    {
        $this->photoService= new PhotoService();
    }

    public function addedPhoto(Request $request){

        $inputs = $request->all();

        if(!empty($inputs)){

            if($inputs['admin_id']){

                $admin = Admin::find($inputs['admin_id']);
                $photo = $admin->photo()->create([
                   'url' => $this->photoService->addFile($request)
                ]);

                return response()->json([
                    "status"=>200,
                    'success'=>true,
                    'message'=>'Photo Added successfully',
                    'data'=> $photo
                ]);

            }

            if($inputs['student_id']){

               $student = Student::find($inputs['student_id']);
               $student->photo()->create([
                   'url' => $this->photoService->addFile($request)
               ]);
               return response()->json([
                   "status"=>200,
                   'success'=>true,
                   'message'=>'Photo Added successfully',
                   'data'=> $student
               ]);

            }
        }else{
            return response()->json([
                "status"=>404,
                'success'=>false,
                'message'=>'Value should be provided',
                'data'=> ''
            ]);
        }

        return null;
    }

    public function deletePhoto($id){

        $photo = Photo::find($id);


        if($photo){

            if(File::exists(public_path($photo->url))){

                if(File::delete(public_path($photo->url))){

                    $photo->delete();

                    return response()->json([
                        "status"=>200,
                    'success' => true,
                    'message' => 'Photo deleted successfully',
                    ]);

                }else{
                    return response()->json([
                        "status"=>404,
                        'success' => false,
                        'message' => 'Photo wasn\'t deleted successfully',
                    ]);
                }

            }else {

                return response()->json([
                    "status"=>404,
                    'success' => false,
                    'message' => 'Photo doesn\'t exist',
                ]);
            }
        }else{
            return response()->json([
                "status"=>404,
                'success'=>false,
                'message'=>'Photo doesn\'t exist',
            ]);
        }
    }
}
