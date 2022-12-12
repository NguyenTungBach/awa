<?php
/**
 * Created by VeHo.
 * Year: 2022-07-29
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    use HasFactory;

    protected $table = 'driver_grade';

    protected $fillable = ['date', 'driver_code', 'grade'];

    protected $dates = [
        //'deleted_at'
    ];

    protected $casts = [
        'data' => 'array'
    ];

}
