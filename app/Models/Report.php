<?php
/**
 * Created by VeHo.
 * Year: 2022-07-29
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Report extends Model
{
    use HasFactory;

    protected $table = 'reports';

    protected $fillable = [

    ];

    protected $dates = [
        //'deleted_at'
    ];

    protected $casts = [
        'data' => 'array'
    ];

}
