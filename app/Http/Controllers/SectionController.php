<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddSectionRequest;
use App\Http\Requests\UpdateSectionRequest;
use App\Models\Section;
use Illuminate\Http\Request;
use App\Models\Grade;
class SectionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $sections = Section::with('student','attendance',
            'attendance.attendanceRecords.student',
            'attendance.attendanceRecords.attendanceType')->paginate(5);

        if (!$sections->isEmpty()) {
            return response()->json([
                "status"=>200,
                "success" => true,
                "message" => "List of sections:",
                "data" => $sections
            ]);
        } else {
            return response()->json([
                "status"=>404,
                "success" => false,
                "message" => "No section available",
                "data" => $sections
            ]);
        }
    }

    public function show($id){
        $sections = Section::with('student.attendanceRecord.attendanceType')
            ->where('id',$id)
            ->first();

        if($sections){
            return response()->json([
                "status"=>200,
                "success" => true,
                "message" => "Section has been fetched successfully",
                "data" => $sections
            ]);
        }else{
            return response()->json([
                "status"=>404,
                "success" => false,
                "message" => "Section doesn't exist",
                "data" => $sections
            ]);
        }
    }


    public function getSectionById($section_Id)
    {
        $section = Section::find($section_Id);

        if ($section) {
            return response()->json([
                "status"=>200,
                "success" => true,
                "message" => "Section:",
                "data" => $section
            ]);
        } else {
            return response()->json([
                "status"=>404,
                "success" => false,
                "message" => "Section with this id does not exist!",
            ]);
        }
    }


    public function getSectionByGrade($id){

        $grade = Grade::where("id",$id)->first();
        if($grade){
            $sections = $grade->section()->get();

            if(!$sections->isEmpty())
            {
                return response()->json([
                    "status"=>200,
                    "success" => true,
                    "message" => "Sections related to this class fetched successfully",
                    "data" => $sections
                ]);
            }
            else{
                return response()->json([
                    "status"=>404,
                    "success" => false,
                    "message" => "Section for this class does not exist",
                ]);

            }
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(AddSectionRequest $request)
    {
        // $request->validate([
        //     'grade_id' => 'required',
        //     'name' => 'required',
        //     'max_students' => 'required',
        // ]);

        $inputs = $request->validated();

        $section = new Section();
        $section->grade_id =    $inputs["grade_id"];
        $section->name =    $inputs["name"];
        $section->max_students =    $inputs["max_students"];

        $section->save();

        if ($section) {
            return response()->json([
                "status"=>200,
                "success" => true,
                "message" => "Section has been added successfully!",
                "data" => $section
            ]);
        } else {
            return response()->json([
                "status"=>404,
                "success" => false,
                "message" => "Section creation failed!",
            ]);
        }
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Section $section
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateSectionRequest $request, $id)
    {
        // $request->validate([
        //     'grade_id' => 'required',
        //     'name' => 'required',
        //     'max_students' => 'required',
        // ]);
        $inputs = $request->validated();
        $section = Section::where('id', $id)->first();

        if ($section) {
            // $section->grade_id = $request->grade_id;
            // $section->name = $request->name;
            // $section->max_students = $request->max_students;
            $section->fill($inputs);
            $section->save();

            return response()->json([
                "status"=>200,
                "success" => true,
                "message" => "Section has been updated!",
                "data" => $section
            ]);
        } else {
            return response()->json([
                "status"=>404,
                "success" => false,
                "message" => "Section id does not exist!"
            ]);
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Section $section
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($section_Id)
    {
        $section = Section::find($section_Id);

        if ($section) {
            if (!$section->student()->exists()) {
                $section->delete();
                return response()->json([
                    "status"=>200,
                    "success" => true,
                    "message" => "Deleted!",
                    "data" => $section,
                ]);
            } else {
                return response()->json([
                    "status"=>400,
                    "success" => false,
                    "message" => "Section cannot be deleted!"
                ]);
            }
        } else {
            return response()->json([
                "status"=>404,
                "success" => false,
                "message" => "Section doesn't exist!"
            ]);
        }
    }
}
