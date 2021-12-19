<?php

namespace App\Http\Controllers;

use App\Models\AttendanceRecord;
use Illuminate\Http\Request;

class AttendanceRecordController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {

        if(isset($request["attendance_id"]) && $request["attendance_id"] != ""){
            $attendanceRecords = AttendanceRecord::
                with("section","attendance","student","attendanceType")
                ->where("attendance_id",$request["attendance_id"])
                ->get();
        }else{
            $attendanceRecords = AttendanceRecord::all();

        }


        if(!$attendanceRecords->isEmpty()){
            return response()->json([
                "status"=>200,
                'success'=> true,
                'message'=> "Attendance records had been fetched successfully",
                'data'=> $attendanceRecords

            ]);
        }else {
            return response()->json([
                "status"=>404,
                'success' => false,
                'message' => "No attendance records available",
                'data' => $attendanceRecords

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

        $attendanceRecord = new AttendanceRecord();
        $attendanceRecord->fill($inputs);
        $attendanceRecord->save();

        $attendanceRecord = AttendanceRecord::
        with("section","attendance","student","attendanceType")
            ->where("id",$attendanceRecord->id)
            ->first();

        return response()->json([
            "status"=>200,
            'success'=> true,
            'message'=> "Attendance record had been added successfully",
            'data'=> $attendanceRecord

        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\AttendanceRecord  $attendanceRecord
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, AttendanceRecord $attendanceRecord)
    {
        $inputs = $request->all();
        if($attendanceRecord){
            $attendanceRecord->fill($inputs);
            $attendanceRecord->save();

            $attendanceRecord = AttendanceRecord::
            with("section","attendance","student","attendanceType")
                ->where("id",$attendanceRecord->id)
                ->first();

            return response()->json([
                "status"=>200,
                'success'=> true,
                'message'=> "Attendance records had been updated successfully",
                'data'=> $attendanceRecord

            ]);
        }else{
            return response()->json([
                "status"=>404,
                'success'=> false,
                'message'=> "Attendance records not available",
                'data'=> $attendanceRecord

            ]);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\AttendanceRecord  $attendanceRecord
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(AttendanceRecord $attendanceRecord)
    {
        if($attendanceRecord){
            $attendanceRecord->delete();

            return response()->json([
                "status"=>200,
                'success'=> true,
                'message'=> "Attendance record had been deleted successfully",
                'data'=> $attendanceRecord

            ]);
        }else{
            return response()->json([
                "status"=>404,
                'success'=> false,
                'message'=> "Attendance record doesn't exist",
                'data'=> $attendanceRecord

            ]);
        }
    }
}
