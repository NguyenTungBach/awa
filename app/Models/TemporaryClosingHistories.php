<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TemporaryClosingHistories extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'temporary_closing_histories';

    protected $fillable = [
        'date',
        'month_year',
        'status',
    ];

    protected $dates = ['deleted_at'];

    protected $casts = [
        'data' => 'array'
    ];
}
