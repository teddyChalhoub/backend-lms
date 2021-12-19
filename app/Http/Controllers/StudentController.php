<?php

namespace App\Http\Controllers;

use App\Models\AttendanceRecord;
use App\Models\Student;
use App\Models\Section;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Requests\StoreStudentRequest;
use App\Http\Requests\UpdateStudentRequest;
use App\Services\PhotoService;

class StudentController extends Controller
{
    protected $photoService;

    public function __construct()
    {
        $this->photoService = new PhotoService();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $students = Student::with('section','grade','photo')->paginate(5);
        return response()->json([
            "status"=>200,
            "success"=> true,
            "message" => "Student has been fetched",
            "data" => $students
        ]);
    }

    public function getAllStudents()
    {
        $students = Student::with('section','grade','photo')->get();

        return response()->json([
            "status"=>200,
            "success"=> true,
            "message" => "Student has been fetched",
            "data" => $students
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreStudentRequest $storeRequest)
    {

        $inputs = $storeRequest->validated();
        $student = new Student();
        $section = Section::where('id',$inputs['section_id'])->first();
        $student->firstname = $inputs['firstname'];
        $student->lastname = $inputs['lastname'];
        $student->email = $inputs['email'];
        $student->phone = $inputs['phone'];
        $student->grade_id = $inputs['grade_id'];
        $student->section_id = $inputs['section_id'];

        // dd($section->student()->count());

        if($section->student()->count() >= $section->max_students){

            return response()->json([
                "status"=>400,
                "success"=> false,
                "message" => "Maximum number of students allowed for this section has been reached",
                "data"=>$student
            ]);

        }
        $student->save();

        $student = Student::with('section','grade','photo')
            ->where("id",$student->id)->first();

        if ($storeRequest->file('picture') != null) {
            $student->photo()->create([
                'url' => $this->photoService->addFile($storeRequest),
            ]);
        }

        $student = $this->autoIncrementStudentId($student->id - 1, $student->id);
        return response()->json([
            "status"=>200,
            "success"=> true,
            "message" => "Student has been added",
            "data"=> $student
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $students = Student::with('attendanceRecord', 'section.grade', 'photo')
            ->where('id', $id)
            ->get();

        if ($students) {
            return response()->json([
                "status"=>200,
                "success"=> true,
                "message" => "Student has been fetched",
                "data" => $students
            ]);
        }else{
            return response()->json([
                "status"=>404,
                "success"=> false,
                "message" => "Student doesn't exist",
                "data" => $students
            ]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateStudentRequest $storeRequest, $id)
    {
        $inputs = $storeRequest->validated();
        $student = Student::with('section','grade','photo')->where('id', $id)->first();
        $student->update($inputs);


        return response()->json([
            "status"=>200,
            "success" => true,
            "message" => "information has been updated",
            "data"=>$student
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        //    $student = Student::where('id', $id)->delete();

        // return response()->json([
        //     "success" => true,
        //     "message" => "Deleted !"], 200);

        $student = Student::find($id);

        if ($student) {

            //            $this->deleteFile($admin->picture);
            if ($student->photo != null && $this->photoService->deleteFile($student->photo->url)) {
                $student->delete();
                $student->photo()->delete();
                return response()->json([
                    "status"=>200,
                    'success'=> true,
                    'message'=> $student->name.'Student has been deleted successfully',
                    'data'=>$student
                ]);
            } else {
                $student->delete();
                return response()->json([
                    "status"=>200,
                    'success'=> true,
                    'message'=> $student->name.'Student has been deleted successfully',
                    'data'=>$student
                ]);
            }
        } else {
            return response()->json([
                "status"=>404,
                'success'=> false,
                'message'=> "Student doesn't exist",
                'data'=>$student
            ]);
        }
    }

    public function autoIncrementStudentId(int $latestStudentId, int $newStudentId)
    {
        $latestStud = Student::find($latestStudentId);
        $newStud = Student::find($newStudentId);
        if ($latestStud) {
            $newStud->student_id = $latestStud->student_id + 1;
            $newStud->save();
        }

        return $newStud;
    }

    public function filtration($filter, int $id){
        if ($filter === 'grade'){
            $students = Student::with('section','grade','photo')
            ->where('grade_id', $id)->get();
            if($students->isEmpty()){
              return  response()->json([
                "status"=>404,
                "success"=> false,
                "message" => "No students for this class",
                "data" => $students
            ]);
            }
            return response()->json([
                "status"=>200,
                "success"=> true,
                "message" => "Student has been filtered by class",
                "data" => $students
            ]);

        }elseif ($filter === 'section'){
            $students = Student::with('section','grade','photo')
            ->where('section_id', $id)->get();
            if($students->isEmpty()){
                return  response()->json([
                    "status"=>404,
                  "success"=> false,
                  "message" => "No students for this section",
                  "data" => $students
              ]);
              }
            return response()->json([
                "status"=>200,
                "success"=> true,
                "message" => "Students has been filtered by section",
                "data" => $students
            ]);

        }else{
            $students = Student::with('section','grade','photo')
            ->where('student_id', $id)->get();
            if($students->isEmpty()){
                return  response()->json([
                    "status"=>404,
                  "success"=> false,
                  "message" => "Student is not registered",
                  "data" => $students
              ],200);
              }
            return response()->json([
                "status"=>200,
                "success"=> true,
                "message" => "Student has been searched by id",
                "data" => $students
            ],200);
        }
    }

    public function totalAttendanceReport(Request $request)
    {

        $attendanceRecords = AttendanceRecord::count();

        $from = new Carbon($request->from);
        $from = $from->toDateString();

        $to = new Carbon($request->to);
        $to = $to->toDateString();

        $present = AttendanceRecord::with(
            'attendance',
            'attendanceType:id,name'
        )->whereHas('attendance', function ($q) use ($from, $to) {
            $q->whereBetween('date', [$from, $to]);
        })->whereHas('attendanceType', function ($q) use ($request) {
            $q->where('name', 'Present');
        })->count();

        $present = round(($present / $attendanceRecords) * 100, 2);

        $absent = AttendanceRecord::with(
            'attendance',
            'attendanceType:id,name'
        )->whereHas('attendance', function ($q) use ($from, $to) {
            $q->whereBetween('date', [$from, $to]);
        })->whereHas('attendanceType', function ($q) use ($request) {
            $q->where('name', 'Absent');
        })->count();

        $absent = round(($absent / $attendanceRecords) * 100, 2);

        $late = AttendanceRecord::with(
            'attendance',
            'attendanceType:id,name'
        )->whereHas('attendance', function ($q) use ($from, $to) {
            $q->whereBetween('date', [$from, $to]);
        })->whereHas('attendanceType', function ($q) use ($request) {
            $q->where('name', 'Late');
        })->count();

        $late = round(($late / $attendanceRecords) * 100, 2);

        return response()->json([
            'success' => true,
            'message' => 'Record created successfully',
            'data' => [
                ['record' => $absent, "name" => "Absent"],
                ['record' =>  $present, "name" => "Present"],
                ['record' => $late, "name" => "Late"]
            ]
        ]);
    }

    public function studentAttendanceReport(Request $request, $id)
    {

        $student = Student::find($id);
        $attendanceRecordPerStud = $student->attendanceRecord()->count();


        //        $attendanceRecordPerStud = AttendanceRecord::count();

        $from = new Carbon($request->from);
        $from = $from->toDateString();

        $to = new Carbon($request->to);
        $to = $to->toDateString();

        if ($student && $attendanceRecordPerStud > 0) {


            $present = $student->attendanceRecord()
                ->with('attendance:id,date', 'attendanceType:id,name')
                ->whereHas('attendance', function ($q) use ($from, $to) {
                    $q->whereBetween('date', [$from, $to]);
                })->whereHas('attendanceType', function ($q) use ($request) {
                    $q->where('name', 'Present');
                })->count();

            $present = round(($present / $attendanceRecordPerStud) * 100, 2);

            $absent = $student->attendanceRecord()
                ->with('attendance:id,date', 'attendanceType:id,name')
                ->whereHas('attendance', function ($q) use ($from, $to) {
                    $q->whereBetween('date', [$from, $to]);
                })->whereHas('attendanceType', function ($q) use ($request) {
                    $q->where('name', 'Absent');
                })->count();

            $absent = round(($absent / $attendanceRecordPerStud) * 100, 2);

            $late = $student->attendanceRecord()
                ->with('attendance:id,date', 'attendanceType:id,name')
                ->whereHas('attendance', function ($q) use ($from, $to) {
                    $q->whereBetween('date', [$from, $to]);
                })->whereHas('attendanceType', function ($q) use ($request) {
                    $q->where('name', 'Late');
                })->count();

            $late = round(($late / $attendanceRecordPerStud) * 100, 2);

            return response()->json([
                "status"=>200,
                'success' => true,
                'message' => 'Record retrieved successfully',
                'data' => [
                    ['record' => $absent, "name" => "Absent"],
                    ['record' =>  $present, "name" => "Present"],
                    ['record' => $late, "name" => "Late"]
                ]
            ]);
        } else {

            return response()->json([
                "status"=>404,
                'success' => false,
                'message' => 'Data  not found',
                'data' => []
            ]);
        }
    }
}
