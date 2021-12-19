<?php

namespace App\Models;

use App\Models\AttendanceRecord;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Section;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable =[
      'date','section_id'
    ];

    public function section()
    {
        return $this->belongsTo(Section::class,'section_id','id');
    }

    public function attendanceRecords()
    {
        return $this->hasMany(AttendanceRecord::class);
    }


}
