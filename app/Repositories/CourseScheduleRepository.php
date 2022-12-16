<?php
/**
 * Created by VeHo.
 * Year: 2022-07-29
 */

namespace Repository;

use App\Exports\CourseScheduleExport;
use App\Imports\CourseScheduleImport;
use App\Models\Calendar;
use App\Models\Course;
use App\Models\CourseSchedule;
use App\Repositories\Contracts\CourseScheduleRepositoryInterface;
use Helper\ResponseService;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Repository\BaseRepository;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Auth;

class CourseScheduleRepository extends BaseRepository implements CourseScheduleRepositoryInterface
{

    public function __construct(Application $app)
    {
        parent::__construct($app);

    }

    /**
       * Instantiate model
       *
       * @param CourseSchedule $model
       */

    public function model()
    {
        return CourseSchedule::class;
    }

    public function findOne($id)
    {
        $item = CourseSchedule::find($id);
        if ($item && $item->status !== '') {
            return ResponseService::responseData(\Illuminate\Http\Response::HTTP_OK, 'success', 'success', $item);
        } else {
            return ResponseService::responseData(\Illuminate\Http\Response::HTTP_METHOD_NOT_ALLOWED, 'error', trans('errors.data_not_found'));
        }
    }

    public function getList($request = [])
    {
        $field = $request['field'] ?? '';
        $sortBy = $request['sortby'] ?? '';
        $viewDate = isset($request['view_date']) && strtotime($request['view_date']) ? strtotime($request['view_date']):strtotime(date('Y-m-d'));
        $onEdit = isset($request['edit']);

        $query = Course::select('id', 'flag', 'course_code', 'course_name', 'group', 'start_date', 'end_date')
            ->with(['courseSchedules' => function ($q) use ($viewDate) {
                $q->select(['id', 'course_code', 'schedule_date', 'status', 'lunar_jp'])
                    ->whereYear('schedule_date', date('Y', $viewDate))
                    ->whereMonth('schedule_date', date('m', $viewDate))
                    ;
            }])
            ->where('flag', Course::COURSE_FLAG_NO)
            ->orWhere('flag', Course::COURSE_FLAG_NULL)
            ->where(function($q) use ($viewDate) {
                $q->Where('end_date', null)
                    ->whereRaw('LAST_DAY(end_date) >= ?', [date('Y-m-d', $viewDate)]);
            });

        if ($field && $sortBy) {
            $query->orderBy($field, $sortBy);
        }
        $items = $query->get()->toArray();

        if (!$items) {
            return ResponseService::responseData(\Illuminate\Http\Response::HTTP_OK, 'success', 'success', null);
        }
        $dateObj = \Carbon\Carbon::parse(date('Y-m-d', $viewDate));
        $result = [];
        foreach ($items as $item) {
            $checkStartDate = $onEdit && !strtotime($item['end_date']) && strtotime(date('Y-m-d') . ' + 12 months') < strtotime($dateObj->toDateString());
            $checkEndDate = strtotime($item['end_date']) && strtotime(date('Y-m', strtotime($item['end_date'] . ' + 1 months'))) <= strtotime($dateObj->toDateString());

            if ($checkEndDate) {
                continue;
            }
            $lunarJps = Calendar::whereYear('date', date('Y', $viewDate))
                                    ->whereMonth('date', date('m',$viewDate))
                                    ->pluck('week', 'date')->all();

            $schedules = [];
            for ($i = 1; $i <= $dateObj->daysInMonth; $i++) {
                $year = $dateObj->year;
                $month = $dateObj->month;
                $dateInMonth = Carbon::create($year, $month, $i)->toDateString();
                $schedules[$i] = [
                    'id' => '',
                    'course_code' => $item['course_code'],
                    'schedule_date' => $dateInMonth,
                    'status' => '',
                    'lunar_jp' => $lunarJps[$dateInMonth] ?? ''
                ];

                foreach($item['course_schedules'] as $schedule) {
                    if ($schedule['schedule_date'] == $dateInMonth) {
                        $schedules[$i] = $schedule;
                        break;
                    }
                }
            }
            $item['course_schedules'] = $schedules;
            $result[] = $item;
        }

        return ResponseService::responseData(\Illuminate\Http\Response::HTTP_OK, 'success', 'success', $result);
    }

    public function changeMany($items = [])
    {
        if (!is_array($items))
            $items = json_decode($items);
        if (empty($items)) {
            return ResponseService::responseData(\Illuminate\Http\Response::HTTP_METHOD_NOT_ALLOWED, 'error', trans('errors.data_not_found'));
        }
        foreach ($items as $item) {

            if (!isset($item['id']) && isset($item['course_id'])) {
                $course = Course::where('id', $item['course_id'])->first();

                if (!$course) {
                    return ResponseService::responseData(\Illuminate\Http\Response::HTTP_METHOD_NOT_ALLOWED, 'error', trans('errors.data_not_found'));
                }
                $lunarJps = Calendar::whereYear('date', date('Y', strtotime($item['schedule_date'])))
                                    ->whereMonth('date', date('m', strtotime($item['schedule_date'])))
                                    ->pluck('week', 'date')->all();
                $checkSchedule = CourseSchedule::where('course_code', $course['course_code'])
                    ->where('schedule_date', $item['schedule_date'])
                    ->first();
                if (!$checkSchedule) {
                    $result = CourseSchedule::create([
                        'course_code' => $course['course_code'],
                        'course_name' => $course['course_name'],
                        'schedule_date' => $item['schedule_date'],
                        'status' => $item['status'],
                        'lunar_jp' => $lunarJps[$item['schedule_date']] ?? ''
                    ]);
                }
            } elseif (isset($item['id'])) {
                $model = CourseSchedule::find($item['id']);
                if (!$model) {
                    return ResponseService::responseData(\Illuminate\Http\Response::HTTP_METHOD_NOT_ALLOWED, 'error', trans('errors.data_not_found'));
                }
                $affected = CourseSchedule::whereId($model->id)->update([
                    'status' => $item['status'],
                    'updated_at' =>  \Carbon\Carbon::now()
                ]);
            }
        }
        return $this->getList();
    }

    public function exportData($viewDate, $sortById, $sortByGroup)
    {
        Storage::deleteDirectory('/CourseSchedule');
        $path = "CourseSchedule/$viewDate" . "/" . $sortById . "_" . $sortByGroup . '/';
        $fileName = '配車表_{' . date('Y', $viewDate) . '_' . date('m', $viewDate) . '}.xlsx';
        $fullPath = $path . $fileName;
        $result = (new CourseScheduleExport($sortById, $sortByGroup, $viewDate))->store($fullPath);
        if ($result && !Storage::exists($fullPath)) {
            dd('not found ' . Storage::path($fullPath));
        }

        $filePath = Storage::path($fullPath);

        return Storage::get($fullPath) ? $filePath : '';
    }

    public function downloadFileExported($viewDate, $sortById, $sortByGroup, $newFile = false)
    {
        $path = "CourseSchedule/$viewDate" . "/" . $sortById . "_" . $sortByGroup . '/';
        $fileName = '配車表_{' . date('Y', $viewDate) . '_' . date('m', $viewDate) . '}.xlsx';
        $fullPath = $path . $fileName;
        if ($newFile && Storage::exists($fullPath)) {
            Storage::deleteDirectory($path);
        }

        $filePath = $this->exportData($viewDate, $sortById, $sortByGroup);

        // if (!Storage::exists($fullPath)) {
        //     $filePath = $this->exportData($viewDate, $sortById, $sortByGroup);
        // }\
        $headers = [
            'Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ];

        return Response::download($filePath, $fileName, $headers);

        // return Excel::download(new CourseScheduleExport($sortById, $sortByGroup), 'CourseSchedule.xlsx');
        // $contents = Storage::get($fullPath);
        // return response($contents, 200, $headers);//->status()->download($filePath, $fileName, $headers);
    }



    public function importFileUpload($request)
    {
        // $file = 'CourseSchedule\1661958000\asc_asc/配車表_{2022_09}.xlsx';
        $file = $request->file('file');
        $forDate = isset($request['for_date']) && strtotime($request['for_date']) ? strtotime($request['for_date']): strtotime(date('Y-m'));
        $ImportBeforeSave = true;

        $import = new CourseScheduleImport($forDate, $ImportBeforeSave);

        Excel::import($import, $file);

        $dataErrors = $import->dataErrors;

        if (!$ImportBeforeSave && $import->dataImported) {
            return ResponseService::responseData(\Illuminate\Http\Response::HTTP_OK, 'success', 'import success', ['inValidCourses' => $dataErrors, 'validCourses' => $import->dataImported]);
        }
        if ($dataErrors) {
            if (is_array($dataErrors)) {
                return ResponseService::responseData(\Illuminate\Http\Response::HTTP_UNPROCESSABLE_ENTITY, 'error', implode(', ', $dataErrors) . ' は取り込めませんでした。', $dataErrors, 'codes not imported');
            } else {
                return ResponseService::responseData(\Illuminate\Http\Response::HTTP_UNPROCESSABLE_ENTITY, 'error', $dataErrors, null, 'error format');
            }

        }
        $items = $this->getList([
            'view_date' => date('Y-m', $forDate)
        ]);

        return ResponseService::responseData(\Illuminate\Http\Response::HTTP_OK, 'success', 'import success', $items['data']);
    }


}
