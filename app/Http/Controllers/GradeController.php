<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddClassRequest;
use App\Http\Requests\UpdateClassRequest;
use App\Models\Grade;
use App\Models\Section;
use App\Models\Student;
use Illuminate\Http\Request;

class GradeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $grade = Grade::with('student')->paginate(5);

        if ($grade) {
            return response()->json([
                "status"=>200,
                "success" => true,
                "message" => "List of classes:",
                "data" => $grade
            ]);
        }
    }

    public function getGradeById($grade_Id)
    {
        $grade = Grade::find($grade_Id);

        if ($grade) {
            return response()->json([
                "status"=>200,
                "success" => true,
                "message" => "Class:",
                "data" => $grade
            ]);
        } else {
            return response()->json([
                "status"=>404,
                "success" => false,
                "message" => "Class with this id does not exist!",
            ]);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(AddClassRequest $request)
    {
        // $request->validate([
        //     'name' => 'required',
        // ]);
        // dd($request);
// dd('jj');
        $inputs = $request->validated();
// dd('hh');
        $grade = new Grade();
        $grade->name = $inputs['name'];

        $grade->save();

        if ($grade) {
            return response()->json([
                "status"=>200,
                "success" => true,
                "message" => "Class has been added successfully!",
                "data" => $grade
            ]);
        } else {
            return response()->json([
                "status"=>404,
                "success" => false,
                "message" => "Class creation failed!",
            ]);
        }
    }




    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse   
     */
    public function update(UpdateClassRequest $request, $id)
    {
        $inputs = $request->validated();

        $grade = Grade::where('id', $id)->first();
        if ($grade) {
            // $grade->name = $request->name;
            $grade->update($inputs);
            // $grade->save();

            return response()->json([
                "status"=>200,
                "success" => true,
                "message" => "Class has been updated!",
                "data" => $grade
            ]);
        } else {
            return response()->json([
                "status"=>404,
                "success" => false,
                "message" => "Class id does not exist!"
            ]);
        }
    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Grade  $grade
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($grade_Id)
    {
        $grade = Grade::find($grade_Id);

        if ($grade) {
            if (!$grade->section()->exists()) {
                $grade->delete();
                return response()->json([
                    "status"=>200,
                    "success" => true,
                    "message" => "Deleted!",
                    "data" => $grade,
                ]);
            } else {
                return response()->json([
                    "status"=>400,
                    "success" => false,
                    "message" => "Class cannot be deleted!"
                ]);
            }
        } else {
            return response()->json([
                "status"=>404,
                "success" => false,
                "message" => "Class doesn't exist!"
            ]);
        }

    }
}
