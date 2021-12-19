<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
 use App\Models\Grade;
 use App\Models\Section;
use App\Models\Photo;
use App\Models\AttendanceRecord;

class Student extends Model
{
    use HasFactory;
    protected $fillable = [
        'firstname',
        'lastname',
        'email',
        'phone',
        'grade_id',
        'section_id'
    ];
    public function grade(){
        return $this->belongsTo(Grade::class,'grade_id','id');
    }

    public function section(){
        return $this->belongsTo(Section::class,'section_id','id');
    }
    public function photo(){
        return $this->morphOne(Photo::class, 'photoable');
    }

    public function attendanceRecord()
    {
        return $this->hasMany(AttendanceRecord::class);
    }

}
//attendaceRecords to be added later;
