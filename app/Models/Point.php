<?php
/**
 * Created by VeHo.
 * Year: 2022-07-29
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Point extends Model
{
    use HasFactory;

    protected $table = 'course_point';

    protected $fillable = ['date', 'course_code', 'point'];

    protected $dates = [
        //'deleted_at'
    ];

    protected $casts = [
        'data' => 'array'
    ];

}
