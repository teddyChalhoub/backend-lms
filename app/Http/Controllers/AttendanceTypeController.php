<?php

namespace App\Http\Controllers;

use App\Models\AttendanceType;
use Illuminate\Http\Request;

class AttendanceTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $attendanceType = AttendanceType::all();
        if(!$attendanceType->isEmpty()){
            return response()->json([
                "status"=>200,
                'success'=> true,
                'message'=> "Attendance type had been fetched successfully",
                'data'=> $attendanceType

            ]);
        }else{
            return response()->json([
                "status"=>404,
                'success'=> false,
                'message'=> "No attendance type available",
                'data'=> $attendanceType

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
        $attendanceType = new AttendanceType();

        if(!empty($inputs)){

            $attendanceType->fill($inputs);
            $attendanceType->save();

            return response()->json([
                "status"=>200,
                'success'=> true,
                'message'=> "Attendance type had been added successfully",
                'data'=> $attendanceType

            ]);
        }else{
            return response()->json([
                "status"=>404,
                'success'=> false,
                'message'=> "Name should be provided",
                'data'=> $attendanceType

            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\AttendanceType  $attendanceType
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, AttendanceType $attendanceType)
    {
        $inputs = $request->all();
        if($attendanceType){
            $attendanceType->fill($inputs);
            $attendanceType->save();

            return response()->json([
                "status"=>200,
                'success'=> true,
                'message'=> "Attendance type had been updated successfully",
                'data'=> $attendanceType

            ]);
        }else{
            return response()->json([
                "status"=>404,
                'success'=> false,
                'message'=> "Attendance type doesn't exist",
                'data'=> $attendanceType

            ]);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\AttendanceType  $attendanceType
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(AttendanceType $attendanceType)
    {
        if($attendanceType){

            $attendanceType->delete();
            return response()->json([
                "status"=>200,
                'success' => true,
                'message' => "Attendance type had been deleted successfully",
                'data' => $attendanceType

            ]);

        }else{
            return response()->json([
                "status"=>404,
                'success'=> false,
                'message'=> "Attendance type doesn't exist",
                'data'=> $attendanceType

            ]);
        }
    }
}
