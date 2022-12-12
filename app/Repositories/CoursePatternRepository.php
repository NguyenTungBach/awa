<?php
/**
 * Created by VeHo.
 * Year: 2022-07-29
 */

namespace Repository;

use App\Models\Course;
use App\Models\CoursePattern;
use App\Repositories\Contracts\CoursePatternRepositoryInterface;
use Helper\ResponseService;
use Repository\BaseRepository;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Auth;

class CoursePatternRepository extends BaseRepository implements CoursePatternRepositoryInterface
{

     public function __construct(Application $app)
     {
         parent::__construct($app);

     }

    /**
       * Instantiate model
       *
       * @param CoursePattern $model
       */

    public function model()
    {
        return CoursePattern::class;
    }

    public function getList($request = '')
    {
        $this->makeData();
        $list = Course::select('id', 'course_code', 'course_name', 'status', 'start_date', 'end_date')
            ->with([
                'coursePatterns'
            ])
            ->where(function ($query) {
                $query
                    ->where('status', Course::COURSE_STATUS_WORK)
                    ->orWhereRaw('LAST_DAY(end_date) >= ?', [\Carbon\Carbon::now()->toDateString()]);
            })
            ->get();
        $result = $list->keyBy('course_code')->toArray();
        $codes = array_keys($result);
        $items = [];
        foreach ($result as $item) {
            $coursePattern = [];
            foreach ($codes as $code) {
                foreach ($item['course_patterns'] as $course) {
                    if ($course['course_child_code'] == $code) {
                        $coursePattern[] = $course;
                        break;
                    }
                }
            }
            $item['course_patterns'] = $coursePattern;
            $items[] = $item;
        }

        return ResponseService::responseData(\Illuminate\Http\Response::HTTP_OK, 'success', 'success', $items);
    }

    public static function makeData($courseModel = null)
    {
        if (!$courseModel) {
            $courseItems = Course::where(function ($query) {
                return $query
                    ->where('status', Course::COURSE_STATUS_WORK);
                    // ->orWhereRaw('LAST_DAY(end_date + interval 1 MONTH) >= ?', [\Carbon\Carbon::now()->toDateString()]);
            })->pluck('course_code');
            $coursePatternItems = CoursePattern::pluck('course_parent_code');
            $diffCodes = array_diff($courseItems->all(), array_unique($coursePatternItems->all()));
            if (!empty($diffCodes)) {
                foreach ($diffCodes as $d_code) {
                    $data = [];
                    foreach ($courseItems->all() as $code) {
                        if ($d_code == $code) {
                            $data[] = [
                                CoursePattern::COURSE_PATTERN_PARENT_CODE => $code,
                                CoursePattern::COURSE_PATTERN_CHILD_CODE => $code,
                                CoursePattern::COURSE_PATTERN_STATUS => CoursePattern::COURSE_PATTERN_STATUS_NULL,
                                "created_at" => \Carbon\Carbon::now(),
                                "updated_at" => \Carbon\Carbon::now(),
                            ];
                        } else {
                            $data[] = [
                                CoursePattern::COURSE_PATTERN_PARENT_CODE => $d_code,
                                CoursePattern::COURSE_PATTERN_CHILD_CODE => $code,
                                CoursePattern::COURSE_PATTERN_STATUS => CoursePattern::COURSE_PATTERN_STATUS_NO,
                                "created_at" => \Carbon\Carbon::now(),
                                "updated_at" => \Carbon\Carbon::now(),
                            ];
                            $data[] = [
                                CoursePattern::COURSE_PATTERN_PARENT_CODE => $code,
                                CoursePattern::COURSE_PATTERN_CHILD_CODE => $d_code,
                                CoursePattern::COURSE_PATTERN_STATUS => CoursePattern::COURSE_PATTERN_STATUS_NO,
                                "created_at" => \Carbon\Carbon::now(),
                                "updated_at" => \Carbon\Carbon::now(),
                            ];
                        }
                    }
                    if (!empty($data)) {
                        CoursePattern::upsert(
                            $data,
                            [CoursePattern::COURSE_PATTERN_PARENT_CODE, CoursePattern::COURSE_PATTERN_CHILD_CODE], [CoursePattern::COURSE_PATTERN_STATUS]);
                    }
                }
            }
            $diffCodesDeleted = array_diff(array_unique($coursePatternItems->all()), $courseItems->all());
            if ($diffCodesDeleted) {
                foreach ($diffCodesDeleted as $key => $code) {
                    CoursePattern::deleteItem($code);
                }
            }
        } else {
            if ($courseModel->flag == Course::COURSE_FLAG_YES) {
                // CoursePattern::deleteItem($courseModel->course_code);
            }

        }
    }

    public function findOne($id)
    {
        $item = CoursePattern::find($id);
        if ($item && $item->status !== CoursePattern::COURSE_PATTERN_STATUS_NULL) {
            return ResponseService::responseData(\Illuminate\Http\Response::HTTP_OK, 'success', 'success', $item);
        } else {
            return ResponseService::responseData(\Illuminate\Http\Response::HTTP_METHOD_NOT_ALLOWED, 'error', trans('errors.data_not_found'));
        }
    }

    public static function changeStatus($request)
    {
        $model = CoursePattern::find($request['id']);
        if (!$model) {
            return ResponseService::responseData(\Illuminate\Http\Response::HTTP_METHOD_NOT_ALLOWED, 'error', trans('errors.data_not_found'));
        }
        $data = CoursePattern::updateStatus([
            'course_parent_code' => $model->course_parent_code,
            'course_child_code' => $model->course_child_code,
            'status' => $request['status']
        ]);

        if ($data['status'] !== 'error') {
            $model->status = $request['status'];
            return ResponseService::responseData(\Illuminate\Http\Response::HTTP_OK, 'success', 'success', $model);
        } else {
            return ResponseService::responseData(\Illuminate\Http\Response::HTTP_METHOD_NOT_ALLOWED, 'error', trans('errors.data_update_fail'));
        }
    }

    public function changeMany($items = [])
    {
        if (!is_array($items))
            $items = json_decode($items);
        if (empty($items)) {
            return ResponseService::responseData(\Illuminate\Http\Response::HTTP_METHOD_NOT_ALLOWED, 'error', trans('errors.data_not_found'));
        }
        foreach ($items as $item) {
            $model = CoursePattern::find($item['id']);
            if (!$model) {
                return ResponseService::responseData(\Illuminate\Http\Response::HTTP_METHOD_NOT_ALLOWED, 'error', trans('errors.data_not_found'));
            }
            if ($model->status == 'duplicate')
                continue;
            CoursePattern::updateStatus([
                'course_parent_code' => $model->course_parent_code,
                'course_child_code' => $model->course_child_code,
                'status' => $item['status']
            ]);
        }
        return $this->getList();
    }


}
