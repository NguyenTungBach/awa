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
use Illuminate\Support\Facades\Log;

class ResultAI extends Model
{
    use HasFactory;

    protected $table = 'result_ais';

   protected $fillable = ['date', 'driver_code', 'result_ai', 'start_time', 'end_time', 'break_time'];

    protected $dates = ['deleted_at'];

    protected $casts = [
        'data' => 'array'
    ];


    public function createResultAI($driverCode, $courseCode = null, $date = null, $status = null, $type = null, $dataAI = null, $startTime = null, $endTime = null, $breakTime = null, $orderNumber = null, $color = null)
    {
        try {
            $resultAI = new ResultAI();
            $resultAI->driver_code = $driverCode;
            $resultAI->course_code = $courseCode;
            $resultAI->date = $date;
            $resultAI->status = $status;
            $resultAI->type = $type;
            $resultAI->result_ai = $dataAI;
            $resultAI->start_time = $startTime;
            $resultAI->end_time = $endTime;
            $resultAI->break_time = $breakTime;
            $resultAI->order_number = $orderNumber;
            $resultAI->color = $color;
            $r = $resultAI->save();

            if (!$r) return ResponseService::responseData(CODE_CREATE_FAILED, 'error', trans('errors.data.update'));
            return ResponseService::responseData(\Illuminate\Http\Response::HTTP_OK, 'success', 'success', $resultAI);
        } catch (\Exception $exception) {
            Log::error('AI create' . $exception);
            return ResponseService::responseData(CODE_CREATE_FAILED, 'error', trans('errors.data.create'));
        }

    }

}
