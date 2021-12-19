<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Attendance;
use App\Models\Student;
use App\Models\AttendanceType;

class AttendanceRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id','attendance_id','attendance_type_id'
    ];

    public function section()
    {
        return $this->belongsTo(Section::class,'section_id','id');
    }

    public function attendance()
    {
        return $this->belongsTo(Attendance::class,'attendance_id','id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class,'student_id','id');
    }

    public function attendanceType()
    {
        return $this->belongsTo(AttendanceType::class,'attendance_type_id','id');
    }
}
