<?php
/**
 * Created by VeHo.
 * Year: 2022-07-29
 */

namespace App\Models;

use Helper\ResponseService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class DriverCourse extends Model
{
    use HasFactory;


    protected $table = 'driver_courses';
    protected $dates = ['deleted_at'];

    protected $fillable = [
        "driver_id",
        "course_id",
        "start_time",
        "end_time",
        "break_time",
        "date",
        "status",
        "created_at",
        "updated_at",
    ];

    protected $casts = [
        'data' => 'array'
    ];



    /*  Relationships  */
    public function driver(){
        return $this->belongsTo(Driver::class,'driver_code','driver_code');
    }
    public function course(){
        return $this->belongsTo(Course::class,'course_code','course_code');
    }

}
