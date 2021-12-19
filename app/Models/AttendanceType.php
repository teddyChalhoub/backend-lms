<?php

namespace App\Models;

use App\Models\AttendanceRecord;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceType extends Model
{
    use HasFactory;

    protected $fillable=[
        'name'
    ];

    public function attendanceRecord()
    {
        return $this->hasMany(AttendanceRecord::class);
    }
}
