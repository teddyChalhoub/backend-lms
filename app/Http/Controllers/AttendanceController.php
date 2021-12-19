<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $attendance = Attendance::with('attendanceRecords.student',
            'attendanceRecords.attendanceType',
            'section.student.attendanceRecord.attendanceType')->get();

        if(!$attendance->isEmpty()){
            return response()->json([
                "status"=>200,
                'success'=> true,
                'message'=> "Attendance had been fetched successfully",
                'data'=> $attendance

            ]);
        }else{
            return response()->json([
                "status"=>404,
                'success'=> false,
                'message'=> "No attendance available",
                'data'=> $attendance

            ]);
        }
    }

    public function show(Request $request,$id)
    {

        if(isset($request["student_search"]) && $request["student_search"] != ""){

            $attendance = Attendance::where("id",$id)->first();
            $attendance = $attendance->section()->first();

            $attendance =$attendance->student()
                ->with("attendanceRecord.attendanceType")->where(function ($q) use($request,$attendance){
                $q->where([["section_id",$attendance->id],["firstname",'like','%'.$request["student_search"].'%']])
                    ->orWhere("lastname",'like','%'.$request["student_search"].'%');
            })->get();

        }else{
;
            $attendance = Attendance::where("id",$id)->first();
            $attendance = $attendance->section()->first();

            $attendance =$attendance->student()
                ->with("attendanceRecord.attendanceType")->where(function ($q) use($request,$attendance){
                    $q->where("section_id",$attendance->id);
                })->get();
        }


        if($attendance){
            return response()->json([
                "status"=>200,
                'success'=> true,
                'message'=> "Attendance had been fetched successfully",
                'data'=> $attendance

            ]);
        }else{
            return response()->json([
                "status"=>404,
                'success'=> false,
                'message'=> "No attendance available",
                'data'=> $attendance

            ]);
        }
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $inputs = $request->all();
        $addWeek = new Carbon($request->date);
        $inputs['date'] = $addWeek->toDateString();

        $attendance = new Attendance();
        $attendance->fill($inputs);
        $attendance->save();

        $attendance = Attendance::with('attendanceRecords.student',
            'attendanceRecords.attendanceType','section.student')
            ->where('id',$attendance->id)->first();

         return response()->json([
             "status"=>200,
            'success'=> true,
            'message'=> "Attendance had been added successfully",
            'data'=> $attendance

        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Attendance  $attendance
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Attendance $attendance)
    {
        $inputs = $request->all();
        $addWeek = new Carbon($request->date);
        $inputs['date'] = $addWeek->toDateString();

        $attendance =Attendance::where('id',$attendance->id)->first();
        $attendance->update($inputs);

        $attendance = Attendance::with('attendanceRecords.student',
            'attendanceRecords.attendanceType','section.student')
            ->where('id',$attendance->id)->first();

        return response()->json([
            "status"=>200,
            'success'=> true,
            'message'=> "Attendance had been updated successfully",
            'data'=> $attendance

        ]);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Attendance  $attendance
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Attendance $attendance)
    {
        $attendance->delete();

        return response()->json([
            "status"=>200,
            'success'=> true,
            'message'=> "Attendance had been deleted successfully",
            'data'=> $attendance

        ]);

    }
}
