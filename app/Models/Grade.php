<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Student;
use App\Models\Section;

class Grade extends Model
{
    use HasFactory;

    protected $table ="grades";

    protected $fillable = [
        'name'
    ];
    
    public function student()
    {
        return $this->hasMany(Student::class);
    }

    public function section()
    {
        return $this->hasMany(Section::class);
    }
}
