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

class CoursePattern extends Model
{
    use HasFactory;

    const TABLE_NAME = 'course_patterns';

    protected $table = self::TABLE_NAME;

    //const status
    const COURSE_PATTERN_STATUS_YES = 'yes';
    const COURSE_PATTERN_STATUS_NO = 'no';
    const COURSE_PATTERN_STATUS_NULL = 'duplicate';

    // const table
    const COURSE_PATTERN_PARENT_CODE = 'course_parent_code';
    const COURSE_PATTERN_CHILD_CODE = 'course_child_code';
    const COURSE_PATTERN_STATUS = "status";

    protected $fillable = [
        self::COURSE_PATTERN_PARENT_CODE,
        self::COURSE_PATTERN_CHILD_CODE,
        self::COURSE_PATTERN_STATUS
    ];


    protected $dates = [
        //'deleted_at'
    ];

    protected $casts = [
        'data' => 'array'
    ];

    protected static function updateStatus($request)
    {
        $pCode = $request[self::COURSE_PATTERN_PARENT_CODE];
        $cCode = $request[self::COURSE_PATTERN_CHILD_CODE];
        $status = $request[self::COURSE_PATTERN_STATUS];
        if ($pCode === $cCode) {
            $status = self::COURSE_PATTERN_STATUS_NULL;
        }
        $affected = CoursePattern::where(function ($query) use ($pCode, $cCode) {
                $query->where(self::COURSE_PATTERN_PARENT_CODE, $pCode)
                    ->where(self::COURSE_PATTERN_CHILD_CODE, $cCode);
            })
            ->orWhere(function ($query) use ($pCode, $cCode) {
                $query->where(self::COURSE_PATTERN_PARENT_CODE, $cCode)
                    ->where(self::COURSE_PATTERN_CHILD_CODE, $pCode);
            })
            ->update([
                self::COURSE_PATTERN_STATUS => $status,
                'updated_at' => \Carbon\Carbon::now()
            ]);
        if ($affected) {
            return ResponseService::responseData(\Illuminate\Http\Response::HTTP_OK, 'success', 'success', $affected);
        } else {
            return ResponseService::responseData(CODE_CREATE_FAILED, 'error', trans('errors.data.update'));
        }
    }

    protected static function deleteItem($code)
    {
        $affected = CoursePattern::where(self::COURSE_PATTERN_PARENT_CODE, $code)
            ->orWhere(self::COURSE_PATTERN_CHILD_CODE, $code)
            ->delete();
    }


}
