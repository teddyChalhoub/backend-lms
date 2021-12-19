<?php

namespace App\Models;

use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Grade;
use App\Models\Attendance;


class Section extends Model
{
    use HasFactory;

    protected $table = "sections";

    protected $fillable = [
        'max_students', 'name','grade_id'
    ];

    public function student()
    {
        return $this->hasMany(Student::class);
    }

    public function grade()
    {
        return $this->belongsTo(Grade::class,'grade_id','id');
    }

    public function attendance()
    {
        return $this->hasMany(Attendance::class);
    }

}
