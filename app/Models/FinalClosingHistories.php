<?php
/**
 * Created by VeHo.
 * Year: 2023-07-25
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FinalClosingHistories extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'final_closing_histories';

    protected $fillable = [
        "date",
        "month_year",
        "type",
        "status",
    ];

    protected $dates = ['deleted_at'];

    protected $casts = [
        'data' => 'array'
    ];

}
