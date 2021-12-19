<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Grade;
use App\Models\Section;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function searchStudent(Request $request,$id){

        $search = $request->search;

        $attendance = Attendance::find($id)->first();

        $section = $attendance->section()->first();

        $students = $section->student()->where(function($q) use($search){
            $q->where('firstname', 'LIKE', '%'.$search.'%')
                ->orWhere('lastname', 'LIKE', '%'.$search.'%');
        })->paginate(10);

        if(!$students->isEmpty()){
            return response()->json([
                "status"=>200,
                'success' => true,
                'message'=>'Students has been filtered',
                'data'=> $students
            ]);
        }else{
            return response()->json([
                "status"=>404,
                'success' => false,
                'message'=>'Students with the specific search aren\'t available',
                'data'=> $students
            ]);
        }
    }

    public function getStudentPerClass($id){

        $classes = Grade::find($id)->first();
        $students = $classes->student()->paginate(10);

        if(!$students->isEmpty()){
            return response()->json([
                "status"=>200,
                'success' => true,
                'message'=>'Students has been filtered per class',
                'data'=> $students
            ]);
        }else{
            return response()->json([
                "status"=>404,
                'success' => false,
                'message'=>'Students with the specific filter aren\'t available',
                'data'=> $students
            ]);
        }
    }

    public function getStudentPerSection($id){

        $section = Section::find($id)->first();
        $students = $section->student()->paginate(10);

        if(!$students->isEmpty()){
            return response()->json([
                "status"=>200,
                'success' => true,
                'message'=>'Students has been filtered per section',
                'data'=> $students
            ]);
        }else{
            return response()->json([
                "status"=>404,
                'success' => false,
                'message'=>'Students with the specific filter aren\'t available',
                'data'=> $students
            ]);
        }
    }
}
