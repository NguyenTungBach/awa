<?php
/**
 * Created by VeHo.
 * Year: 2022-07-29
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Shift extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'shifts';

    public static $timeConfigAI = [
        'S-1' => [
            'start_time' => '09:00',
            'end_time' => '18:00',
            "break_time" => 0
        ],
        'D-1' => [
            'start_time' => '09:00',
            'end_time' => '18:00',
            "break_time" => 0
        ],
        'D-2' => [
            'start_time' => '09:00',
            'end_time' => '18:00',
            "break_time" => 0
        ],
        'D-3' => [
            'start_time' => '09:00',
            'end_time' => '18:00',
            "break_time" => 0
        ],
        'D-4' => [
            'start_time' => '09:00',
            'end_time' => '18:00',
            "break_time" => 0
        ],
    ];
    protected $fillable = [];

    protected $dates = ['deleted_at'];

    protected $casts = [
        'data' => 'array'
    ];

}
