<?php

use App\Http\Controllers\PhotoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AttendanceRecordController;
use App\Http\Controllers\AttendanceTypeController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SectionController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware(['assign.guard:admins'])->post("/login", [AuthController::class, "login"]);


Route::group(['middleware' => ['assign.guard:api', 'jwt.verify']], function () {
    Route::apiResource("/attendance", AttendanceController::class);
//    Route::get("/attendance/{id}", [AttendanceController::class,"attendanceById"]);
    Route::apiResource("/attendanceRecord", AttendanceRecordController::class);
    Route::apiResource("/attendanceType", AttendanceTypeController::class);
// delete a class
    Route::delete('/grade/delete/{grade_Id}', 'App\Http\Controllers\GradeController@destroy');

// delete a section
    Route::delete('/section/delete/{section_Id}', 'App\Http\Controllers\SectionController@destroy');
// get all classess
    Route::get('/grades', 'App\Http\Controllers\GradeController@index');

// get class by id
    Route::get('/grade/{grade_Id}', 'App\Http\Controllers\GradeController@getGradeById');

// create a class
    Route::post('/grade/create', 'App\Http\Controllers\GradeController@create');

// update a class
    Route::put('/grade/update/{id}', 'App\Http\Controllers\GradeController@update');

// get all sections
    Route::get('/sections', 'App\Http\Controllers\SectionController@index');

// get section by id
    Route::get('/section/{section_Id}', 'App\Http\Controllers\SectionController@getSectionById');

// create a section
    Route::post('/section/create', 'App\Http\Controllers\SectionController@create');

// update a section
    Route::put('/section/update/{id}', 'App\Http\Controllers\SectionController@update');

// get section by id
//Route::get('/section/{section_Id}', 'App\Http\Controllers\SectionController@getSectionById');

    Route::resource('/students', 'App\Http\Controllers\StudentController');
// get students in a specific class
    Route::get('/grade/students/{filter}/{id}',[StudentController::class, 'filtration']);
    Route::get('/allStudents',[StudentController::class, 'getAllStudents']);

    Route::post("/report", [StudentController::class,'totalAttendanceReport']);
    Route::post("/report/{id}", [StudentController::class,'studentAttendanceReport']);
//get sections for a class
    Route::get("/getSectionByGrade/{id}", [SectionController::class,'getSectionByGrade']);
// update a section
    Route::get('/section/{id}', ['App\Http\Controllers\SectionController','show']);
    Route::delete("/delete/photo/{id}", [PhotoController::class, 'deletePhoto']);
    Route::post("/add/photo", [PhotoController::class, 'addedPhoto']);

    Route::apiResource("/admin", AdminController::class);
    Route::post("/searchStudent/{id}", [SearchController::class, 'searchStudent']);
    Route::post("/searchStudentPerClass/{id}", [SearchController::class, 'getStudentPerClass']);
    Route::post("/searchStudentPerSection/{id}", [SearchController::class, 'getStudentPerSection']);



    Route::middleware(['assign.guard:admins'])->post("/logout", [AuthController::class, "logout"]);
});


