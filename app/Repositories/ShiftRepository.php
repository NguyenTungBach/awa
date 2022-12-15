<?php
/**
 * Created by VeHo.
 * Year: 2022-07-29
 */

namespace Repository;

use App\Exports\AIExportCourse;
use App\Exports\AIExportCoursePattern;
use App\Exports\AIExportDayOff;
use App\Exports\AIExportDriver;
use App\Exports\AIExportDriverCourse;
use App\Exports\AIExportSchedule;
use App\Exports\ShiftGrade;
use App\Http\Requests\ShiftRequest;
use App\Exports\ShiftExport;
use App\Jobs\ConectionAI;
use App\Models\Calendar;
use App\Models\Course;
use App\Models\CourseSchedule;
use App\Models\DayOff;
use App\Models\Driver;
use App\Models\DriverCourse;
use App\Models\ResultAI;
use App\Models\Shift;
use App\Repositories\Contracts\ShiftRepositoryInterface;
use Carbon\Carbon;
use Helper\Common;
use Helper\ResponseService;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use \Mpdf\Mpdf as PDF;

use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mime\Address;
use App\Mail\ExportFilesForAI;
use Illuminate\Support\Facades\Mail;

class ShiftRepository extends BaseRepository implements ShiftRepositoryInterface
{

    protected $totalMonth = 12;
    protected $file;

    protected $monthConfig = [
        'sun' => 0,
        'mon' => 1,
        'tue' => 2,
        'wed' => 3,
        'thu' => 4,
        'fri' => 5,
        'sat' => 6,
    ];

    public function __construct(Application $app, FileRepository $file)
    {
        parent::__construct($app);
        $this->file = $file;

    }

    /**
     * Instantiate model
     *
     * @param Shift $model
     */

    public function model()
    {
        return Shift::class;
    }
    public function list(ShiftRequest $request)
    {
        $startDate = $request['start_date'] ?? '';
        $endDate = $request['end_date'] ?? '';
        $tab3 = $request['tab3'] ?? '';
        $display = $request['display'] ?? '';
        $sortBy = $request['sortby'] ?? '';
        $field = $request['field'] ?? '';
        if (!$display) {
            $list = $this->getListDriver($startDate, $endDate, $field, $sortBy);
            $items = $this->getDataForDriverFromAI($list->toArray(), $startDate, $endDate, $tab3);
        } else {
            $list = $this->getCourses($startDate, $endDate, false, $field, $sortBy);
            $items = $this->getDataForCourseFromAI($list->toArray(), $startDate, $endDate);
        }
        return ResponseService::responseData(Response::HTTP_OK, 'success', 'success', $items);
    }

    private function getDataForDriverFromAI($drivers, $startDate, $endDate, $tab = '')
    {
        $courseList = $this->getListCourses($startDate, $endDate);
        $courses = $courseList->keyBy('course_code')->toArray();
        foreach ($drivers as &$driver) {
            $listDay = [];
            $dataAI = $driver['result_a_i'];
            for($d = $startDate; strtotime($d) <= strtotime($endDate); $d = Carbon::parse($d)->addDay()->toDateString()) {
                $listDay[$d] = [
                    'date' => $d,
                    'value' => [],
                    'color' => COLOR_WORK
                ];
                if ($tab) {
                        $listDay[$d]['value'] = 0;
                }
                if (strtotime($d) < strtotime($driver['start_date']) || (strtotime($driver['end_date']) && strtotime($d) > strtotime($driver['end_date']))) {
                    $listDay[$d]['color'] = COLOR_OFF;
                }
            }
            if ($dataAI) {
                foreach ($dataAI as $item) {
                    $date = $item['date'];
                    $dtInt = strtotime($date);

                    if ($dtInt < strtotime($driver['start_date']) || (strtotime($driver['end_date']) && $dtInt > strtotime($driver['end_date']))) {
                        continue;
                    }
                    $resultCodeFromAI = $item['result_ai'];
                    $typeText = Common::getTextType($resultCodeFromAI);
                    if ($tab) {
                        $lastGrade = 0;
                        $lastPoint = 0;
                        if (isset($courses[$typeText])) {
                            $course = $courses[$typeText];
                            if ($course['points']) {
                                foreach ($course['points'] as $point) {
                                    if (strtotime($point['date']) <= strtotime($date)) {
                                        $lastPoint = $point['point'];
                                        break;
                                    }
                                }
                            } else {
                                $lastPoint = $course['point'];
                            }
                        }
                        if (!$driver['grades']) {
                            $lastGrade = $driver['grade'];
                        } else {
                            foreach($driver['grades'] as $grade) {
                                if (strtotime($grade['date']) <= strtotime($date)) {
                                    $lastGrade = $grade['grade'];
                                    break;
                                }
                            }
                        }
                        $listDay[$date]['value'] += $lastGrade * $lastPoint;
                    } else {
                        $listDay[$date]['value'][] = [
                            "type" => $resultCodeFromAI,
                            "name" => isset($courses[$typeText]) ? $courses[$typeText]['course_name'] : $typeText,
                            "course_status" => isset($courses[$typeText]) ? $courses[$typeText]['status'] : '',
                            "start_time" => $item['start_time'],
                            "end_time" => $item['end_time'],
                            "break_time" => $item['break_time']
                        ];
                    }
                    // $listDay[$date]['value'][] = $paramValue;

                    if (in_array($resultCodeFromAI, DAY_OFF_CODE)) {
                        $listDay[$date]['color'] = Common::getColorType($resultCodeFromAI);
                    }
                }
            }
            $resultList = [];
            foreach ($listDay as $item) {
                if (is_array($item['value']) && count($item['value']) == 1) {
                    $course = $courses[$item['value'][0]['type']] ?? '';
                    if ($course && strtotime($course['end_date']) && strtotime($course['end_date']) < strtotime($item['date'])) {
                        $item['color'] = '#C6C6C6';
                    }
                }
                $resultList[] = $item;
            }

            $driver['shift_list'] = $resultList;
            $driver['course_driver'] = $this->getDriverCouseInListtAI($courses,$driver['driver_course']);
            unset($driver['day_off'], $driver['result_a_i'],$driver['driver_course']);
        }
        return $drivers;
    }

    private function getDriverCouseInListtAI($courseList,$driverCourses): array
    {
        $dataDriverCourse = [];
        foreach ($driverCourses as $driverCourse){
            if (!isset($courseList[$driverCourse['course_code']])) {
                DriverCourse::where('course_code', $driverCourse['course_code'])->delete();
                continue;
            }
            $infomationCourse = $courseList[$driverCourse['course_code']];
            $dataDriverCourse[] = [
                "id" => $driverCourse['id'],
                "driver_code" => $driverCourse['driver_code'],
                "course_code" => $driverCourse['course_code'],
                "course_flag" => $infomationCourse['flag'],
                "course_name" => $infomationCourse['course_name'],
                "course_status" => $infomationCourse['status'],
                "start_time" => $infomationCourse['start_time'],
                "end_time" => $infomationCourse['end_time'],
                "break_time" => $infomationCourse['break_time'],
                "is_checked" => $driverCourse['is_checked'],
            ];
        }
        return $dataDriverCourse;
    }

    private function getDataForCourseFromAI($courses, $startDate, $endDate, $addListDriverCanRun = true)
    {
        $driverObjs = $this->getListDriver($startDate, $endDate);
        $daysOff = $driverObjs->mapWithKeys(function ($item, $key) {
            $arr = $item->dayOff->toArray();
            if (!$arr) {
                return [$item->driver_code => []];
            } else {
                $data = [];
                foreach ($arr as $dOff){
                    $data[$dOff['date']] = $dOff['type'];
                }
            }
            return [$item->driver_code => $data];
        });
        $drivers = $driverObjs->keyBy('driver_code')->toArray();

        $schedules = collect($courses)->mapWithKeys(function ($item, $key) {
            $arr = $item['course_schedules'];
            if (!$arr) {
                return [$item['course_code'] => []];
            } else {
                $data = [];
                foreach ($arr as $sch){
                    $data[$sch['schedule_date']] = $sch['status'] ?? 0;
                }
                return [$item['course_code'] => $data];
            }
        });

        foreach ($courses as &$course) {
            $listDay = [];
            $dataAI = $course['result_a_i'];
            for($d = $startDate; strtotime($d) <= strtotime($endDate); $d = Carbon::parse($d)->addDay()->toDateString()) {
                $listDay[$d] = [
                    'date' => $d,
                    'driver' => '',
                    'status' => 'off',
                    'color' => COLOR_OFF,
                ];
                if (!$schedules[$course['course_code']] || !isset($schedules[$course['course_code']][$d]) || $schedules[$course['course_code']][$d] != 'off') {
                    $listDay[$d]['status'] =  'on';
                    $listDay[$d]['color'] = COLOR_WORK;
                }
                if ($addListDriverCanRun) {
                    $listDay[$d]['listDriverCanRun'] = [];
                    foreach ($daysOff as $dr_code => $dr) {
                        if (!$dr || !isset($dr[$d])) {
                           $listDay[$d]['listDriverCanRun'][] = [
                                'code' => $dr_code,
                                'name' => $drivers[$dr_code]['driver_name']
                            ];
                            continue;
                        }
                        if (!in_array($dr[$d], DAY_OFF_CODE)) {
                            $listDay[$d]['listDriverCanRun'][] = [
                                'code' => $dr_code,
                                'name' => $drivers[$dr_code]['driver_name']
                            ];
                        }
                    }
                }
                if (strtotime($d) < strtotime($course['start_date']) || (strtotime($course['end_date']) && strtotime($d) > strtotime($course['end_date']))) {
                    $listDay[$d]['color'] = COLOR_OFF;
                    $listDay[$d]['status'] =  'off';
                }
            }
            if ($dataAI) {
                foreach ($dataAI as $item) {
                    $date = $item['date'];
                    $dtInt = strtotime($date);

                    if ($dtInt < strtotime($course['start_date']) || (strtotime($course['end_date']) && $dtInt > strtotime($course['end_date']))) {
                        continue;
                    }

                    $listDay[$date]['driver'] = isset($drivers[$item['driver_code']]) ? $drivers[$item['driver_code']]['driver_name'] : $item['driver_code'];
                    if (isset($schedules[$item['result_ai']]) && isset($schedules[$item['result_ai']][$date]) && $schedules[$item['result_ai']][$date] == 'on') {
                        $listDay[$date]['status'] =  'on';
                        $listDay[$date]['color'] = COLOR_WORK;
                    }
                }
            }
            $resultList = [];
            foreach ($listDay as $item) {
                $driver = $drivers[$item['driver']] ?? '';
                if ($driver && strtotime($driver['end_date']) && strtotime($driver['end_date']) < strtotime($item['date'])) {
                    $item['color'] = '#C6C6C6';
                }
                if (!isset($item['status'])) {
                    continue;
                }
                if ($item['status'] == 'off') {
                    $item['listDriverCanRun'] = [];
                }
                $resultList[] = $item;
            }
            unset($course['result_a_i'], $course['course_schedules']);
            $course['shift_list'] = $resultList;
        }
        return $courses;
    }

    public function create(array $attributes)
    {
        sleep(10);
        $file = \App\Models\File::where('status', 'on')->first();
        $queue = DB::table('jobs')->first();
        if ($file || $queue) {
            return ResponseService::responseData(\Illuminate\Http\Response::HTTP_UNPROCESSABLE_ENTITY, 'error', trans('errors.shift.check.create'));
        }
        $date = $attributes['date'];
        $startDate = $attributes['start_date'] ?? '';
        if (!strtotime($startDate)) {
            $startDate = strtotime($date) > strtotime(date('Y-m'))? Carbon::parse($date)->startOfMonth()->toDateString() : date('Y-m-d') ;
        }

        $endDate = Carbon::parse($startDate)->endOfMonth()->toDateString();
        // tiến hành lấy dữ liệu theo forrmart
        $driverObjects = $this->getListDriver($startDate, $endDate, '', '', true);
        if (!$driverObjects) {
            return ResponseService::responseData(\Illuminate\Http\Response::HTTP_UNPROCESSABLE_ENTITY, 'error', trans('errors.shift.driver_empty'));
        }
        $courseObjects = $this->getListCourses($startDate, $endDate);
        if (!count($courseObjects)) {
            return ResponseService::responseData(\Illuminate\Http\Response::HTTP_UNPROCESSABLE_ENTITY, 'error', trans('errors.shift.course_empty'));
        }
        $drivers = $driverObjects->toArray();
        $courses = $courseObjects->toArray();

        $disk = 'storage_input';

        //get data worked days from start month
        $startOfMonth = Carbon::parse($startDate)->startOfMonth();
        $toDate = Carbon::parse($startDate)->subDay();
        $shiftData = [];
        if (!Carbon::parse($startDate)->isSameDay($startOfMonth)){
            $shiftData = $this->getDataCalledAI($startOfMonth, $toDate);
        }
        $storage = Storage::disk($disk);

        foreach($storage->allFiles() as $file) {
            $storage->delete($file);
        }
        // Xuất excel va luu vao thu muc input
        Excel::store(new AIExportDriver($drivers, $shiftData), 'crew.csv', $disk, \Maatwebsite\Excel\Excel::CSV);
        Excel::store(new AIExportCourse($courses), 'course.csv', $disk, \Maatwebsite\Excel\Excel::CSV);
        Excel::store(new AIExportDriverCourse($courses, $drivers), 'crew_course.csv', $disk, \Maatwebsite\Excel\Excel::CSV);
        Excel::store(new AIExportSchedule($courses, Carbon::parse($startDate)->startOfMonth()->toDateString(), $endDate), 'schedule.csv', $disk, \Maatwebsite\Excel\Excel::CSV);
        Excel::store(new AIExportCoursePattern($courses), 'course_pattern.csv', $disk, \Maatwebsite\Excel\Excel::CSV);
        $drivers = $this->getDataFromAI($drivers, $startDate);
        Excel::store(new AIExportDayOff($drivers, $startDate, $endDate), 'day-off.csv', $disk, \Maatwebsite\Excel\Excel::CSV);

        // tien hanh day du lieu sag ben AI bang queue
        $files = Storage::disk($disk)->allFiles();
        foreach ($files as $file) {
            $content = Storage::disk($disk)->get($file);
            $string = str_replace('"', "", $content);
            Storage::disk($disk)->put($file, $string);
        }
        if (count($files) != 6) {
            return ResponseService::responseData(Response::HTTP_EXPECTATION_FAILED, 'error', 'file khong du', null);
        }

        $queue = ConectionAI::dispatch($startDate, $endDate);

        $result = $this->sendEmails();

        return ResponseService::responseData(Response::HTTP_OK, 'success', 'success');
    }

    private function getDataFromAI($drivers, $startDate)
    {
        $year = Carbon::parse($startDate)->year;
        $month = Carbon::parse($startDate)->month;
        $daysInMonth = Carbon::parse($startDate)->daysInMonth;

        foreach ($drivers as &$driver) {
            $daysOfMonth = [];
            $dataAI = $driver['result_a_i'];
            $dayOffs = $driver['day_off'];
            for($d = 1; $d <= $daysInMonth; $d ++) {//Carbon::parse($startDate)->day
                $date = Carbon::create($year, $month, $d)->toDateString();
                $daysOfMonth[$date] = [];
            }
            if ($dataAI) {
                foreach ($dataAI as $item) {
                    $daysOfMonth[$item['date']][] = Common::getTextType($item['result_ai']);
                }
            }
            if ($dayOffs) {
                foreach ($dayOffs as $item) {
                    $typeText = Common::getTextType($item['type']);
                    $daysOfMonth[$item['date']][] = $typeText == 'D-5' ? str_replace(',', ' & ', $item['has_codes']) : $typeText;
                }
            }
            $driver['daysOfMonth'] = $daysOfMonth;
        }
        return $drivers;
    }

    private function getDataCalledAI($from, $to) {
        $result = ReportRepository::syncData($from, $to);
        if (isset($result['empty'])) {
            $result = [];
        }
        return $result;
    }

    public function sendEmails()
    {
        $toList = [
            'takaba.veho@gmail.com',
            'anhngo1501@gmail.com',
            'nguyenvn099@gmail.com',
        ];
        foreach ($toList as $emailAddress) {
            $email = (new ExportFilesForAI([]))->to($emailAddress);
            try {
                Mail::send($email);
            } catch (TransportExceptionInterface $e) {
                // some error prevented the email sending; display an
                // error message or try to resend the message
                dd('error sending email: ' . $e);
            }
        }
        return $this->responseJson(200, null, 'send mail success');

    }

    /**
     * @param ShiftRequest $request
     * @return array|mixed|void|null
     */
    public function getMessageAI(ShiftRequest $request)
    {
        $status = $request->status;
        switch ($status) {
            case 'new':
                return $this->getMessageAINew($request);
            case 'update':
                return $this->getMessageAIUpdate($request);
            case 'detail':
                return $this->getMessageAIDetail($request);
            default:
                return $this->getMessageAIList($request);
        }
    }

    private function getMessageAIDetail(ShiftRequest $request)
    {
        $data = [];
        $id = $request->id;
        $file = \App\Models\File::find($id);
        if (!$file) {
            return ResponseService::responseData(Response::HTTP_UNPROCESSABLE_ENTITY, 'error', 'error', $data);
        }
        $arrayTime = explode(',', $file->date_time);
        $data = [
            'id' => $file->id,
            'status' => $file->status,
            'message' => json_decode($file->note),
            'start_date' => $arrayTime[0],
            'end_date' => $arrayTime[1],
            'created_at' => Carbon::parse($file->created_at)->toDateTimeString(),
        ];
        return ResponseService::responseData(Response::HTTP_OK, 'success', 'success', $data);
    }

    private function getMessageAIList(ShiftRequest $request)
    {
        $result = [];
        $dataConvert = [];
        $files = \App\Models\File::orderByDesc('id')->whereIn('status', ['check','error', 'success'])->where(['type' => 'ai', 'file_name' => 'course.csv'])->paginate(15);
        foreach ($files as $keyFile => $valueFile) {
            $arrayTime = explode(',', $valueFile->date_time);
            $dataConvert[] = [
                'id' => $valueFile->id,
                'status' => $valueFile->status,
                'message' => json_decode($valueFile->note),
                'start_date' => $arrayTime[0],
                'end_date' => $arrayTime[1],
                'created_at' => Carbon::parse($valueFile->created_at)->toDateTimeString(),
            ];
        }
        $result['result'] = $dataConvert;
        $result['pagination'] = [
            'display' => (int)$files->count(),
            'total_records' => (int)$files->total(),
            'per_page' => (int)$files->perPage(),
            'current_page' => (int)$files->currentPage(),
            'total_pages' => (int)$files->lastPage(),
        ];
        return ResponseService::responseData(Response::HTTP_OK, 'success', 'success', $result);
    }

    private function getMessageAINew(ShiftRequest $request)
    {
        $data = [];
        $file = \App\Models\File::orderByDesc('id')->where(['flag' => 'no'])->limit(6)->get()->toArray();
        if (count($file) == 0) {
            return ResponseService::responseData(Response::HTTP_OK, 'nothing', 'nothing', $data);
        } else {
            $arrayTime = explode(',', $file[0]['date_time']);
            $data = [
                'status' => $file[0]['status'],
                'message' => json_decode($file[0]['note']),
                'start_date' => $arrayTime[0],
                'end_date' => $arrayTime[1],
            ];
            return ResponseService::responseData(Response::HTTP_OK, $file[0]['status'], $file[0]['status'], $data);
        }
    }

    private function getMessageAIUpdate(ShiftRequest $request)
    {
        $flag = 'yes';
        \App\Models\File::where('flag', 'no')->update([
            'flag' => $flag
        ]);
        return ResponseService::responseData(Response::HTTP_OK, 'success', '', null);
    }

    /**
     * @param ShiftRequest $request
     * @return array|mixed|null
     */
    public function checkDataResult(ShiftRequest $request)
    {
        $date = $request->date;
        $result = ResultAI::whereYear('date', '=', date('Y', strtotime($date)))->whereMonth('date', '=', date('m', strtotime($date)))->count();
        $data = [];
        if ($result > 0) {
            $data['type'] = 'success';
            $data['message'] = trans('errors.shift.check.okie');
        } else {
            $data['type'] = 'error';
            $data['message'] = 'error';
        }
        return ResponseService::responseData(Response::HTTP_OK, 'success', 'success', $data);
    }

    /**
     * @param ShiftRequest $request
     * @return array|mixed|null
     */
    public function detailCell(ShiftRequest $request)
    {
        $driverCode = $request->driver_code;
        $date = $request->date;
        $drivers = Driver::where(Driver::DRIVER_CODE, $driverCode)->first();
        if (!$drivers) {
            return ResponseService::responseData(Response::HTTP_UNPROCESSABLE_ENTITY, 'error', trans('errors.data_not_found'));
        }
        $dataDetailConvert = [
            'id' => $drivers->id,
            'driver_code' => $drivers->driver_code,
            'flag' => $drivers->flag,
            'driver_name' => $drivers->driver_name,
            'start_date' => $drivers->start_date,
            'end_date' => $drivers->end_date,
            'status' => $drivers->status,
            'shift_list' => $this->dataShiftAdminValue($drivers, $date, $date),
        ];
        return ResponseService::responseData(Response::HTTP_OK, 'success', 'success', $dataDetailConvert);
    }

    /**
     * @param ShiftRequest $request
     * @return array|mixed|null
     */
    public function updateCell(ShiftRequest $request)
    {
        try {
            $date = $request->date;
            $shiftList = $request->shift_list;
            foreach ($shiftList as $cell) {
                $dateEdit = $cell['date_edit'];
                $driverCode = $cell['driver_code'];

                $driver = Driver::where(Driver::DRIVER_CODE, $driverCode)->first();
                if (!$driver) {
                    return ResponseService::responseData(Response::HTTP_EXPECTATION_FAILED, 'error', trans('errors.shift.check'));
                }
                $collectionShiftList = collect($cell['shift_list_update'])->sortBy('start_time');
                $checkTypeInShipList = $this->checkTypeInShipList($collectionShiftList, $date, $dateEdit);
                if ($checkTypeInShipList['status'] != 'success') {
                    return ResponseService::responseData($checkTypeInShipList['code'], $checkTypeInShipList['status'], $checkTypeInShipList['message']);
                }
                ResultAI::where('driver_code', $driver->driver_code)->whereDate('date', $dateEdit)->delete();
                foreach ($collectionShiftList as $keyCollectionShiftList => $valueCollectionShiftList) {
                    $result = ResultAI::createResultAI($driverCode, null, $dateEdit, null, null, $valueCollectionShiftList['type'], $valueCollectionShiftList['start_time'],
                        $valueCollectionShiftList['end_time'], $valueCollectionShiftList['break_time']);
                    if ($result['status'] != 'success') {
                        return ResponseService::responseData($result['code'], $result['status'], $result['message']);
                    }
                }
                $dataDetailConvert[] = [
                    'id' => $driver->id,
                    'driver_code' => $driver->driver_code,
                    'flag' => $driver->flag,
                    'driver_name' => $driver->driver_name,
                    'start_date' => $driver->start_date,
                    'end_date' => $driver->end_date,
                    'status' => $driver->status,
                    'shift_list' => $this->dataShiftAdminValue($driver, $dateEdit, $dateEdit),
                ];
            }
            return ResponseService::responseData(Response::HTTP_OK, 'success', 'success', $dataDetailConvert);
        } catch (\Exception $exception) {
            Log::error("updateCell " . $exception);
            return ResponseService::responseData(Response::HTTP_EXPECTATION_FAILED, 'error', trans('errors.shift.check'));
        }
    }

    public function editCells($request)
    {
        $date = $request['date'];
        $codes = array_column($request['items'], 'course_code');
        $listCourses = Course::whereIn('course_code', $codes)->get();
        if (count(array_unique($codes)) != $listCourses->count()) {
            return ResponseService::responseData(Response::HTTP_NOT_FOUND, ERROR, trans('errors.shift.check'));
        }
        $driverCodes = array_column($request['items'], 'driver_code');
        $listDrivers = Driver::whereIn('driver_code', $driverCodes)->get();
        $courses = $listCourses->keyBy('course_code')->toArray();
        $drivers = $listDrivers->keyBy('driver_code')->toArray();

        $data = [];
        foreach ($request['items'] as $item) {
            $code = $item['course_code'];
            $day = $item['day'];
            $driverCode = $item['driver'];
            $course = $courses[$code];
            if (!$driverCode) {
                ResultAI::whereDate('date', $day)->where('result_ai', $code)->delete();
                continue;
            }
            if (isset($drivers[$driverCode])) {
                continue;
            }

            $model = ResultAI::updateOrCreate(
                [
                    'date' => $day,
                    'result_ai' => $code
                ],
                [
                    'driver_code' => $driverCode,
                    'date' => $day,
                    'result_ai' => $code,
                    'start_time' => $course['start_time'],
                    'end_time' => $course['end_time'],
                    'break_time' => $course['break_time']
                ]
            );

            if (!$model) {
                return ResponseService::responseData(CODE_CREATE_FAILED, 'error', trans('errors.data.create'));
            }
            $data[] = $model;
        }
        return ResponseService::responseData(Response::HTTP_OK, 'success', 'success', $data ?? '');
    }

    private function checkTypeInShipList($collectionShiftList, $date, $dateEdit)
    {
        $checkTimeUpdateShift = $this->checkTimeUpdateShift($date, $dateEdit);
        if ($checkTimeUpdateShift['status'] != 'success') {
            return ResponseService::responseData($checkTimeUpdateShift['code'], $checkTimeUpdateShift['status'], $checkTimeUpdateShift['message']);
        }
        $checkDataShiftList = $this->checkDataShiftList($collectionShiftList);
        if ($checkDataShiftList['status'] != 'success') {
            return ResponseService::responseData($checkDataShiftList['code'], $checkDataShiftList['status'], $checkDataShiftList['message']);
        }
        return ResponseService::responseData(Response::HTTP_OK, 'success', 'success');

    }

    private function checkTimeUpdateShift($date, $dateEdit)
    {
        $now = Carbon::parse($dateEdit);
        $weekStartDate = $now->startOfWeek()->format('Y-m');
        $weekEndDate = $now->endOfWeek()->format('Y-m');
        if (strtotime($date) == strtotime($weekStartDate) || strtotime($date) == strtotime($weekEndDate)) {
            return ResponseService::responseData(Response::HTTP_OK, 'success', 'success');
        }
        return ResponseService::responseData(Response::HTTP_EXPECTATION_FAILED, 'error', trans('errors.shift.check'));
    }

    private function checkDataShiftList($collectionShiftList)
    {
        $arrayError = [];
        $totalTimeInday = 0;
        $timeDoubleInlistShift = [];
        if (count($collectionShiftList) == 1) {
//            $totalTimeInday = $this->getTotalTimeInShift($collectionShiftList[0]);
//            if ($totalTimeInday > 31) return ResponseService::responseData(Response::HTTP_EXPECTATION_FAILED, 'error', trans('errors.shift.check.24'));
        } else {
            foreach ($collectionShiftList as $keyShiftList => $valueShiftList) {
                if ($keyShiftList + 1 == $collectionShiftList->count()) {
                    break;
                }
                // check trong ngày nếu type thuộc danh sách thì chỉ có thể có 1 type duy nhất
                if (in_array($valueShiftList['type'], array_keys(Shift::$timeConfigAI))) {
                    return ResponseService::responseData(Response::HTTP_EXPECTATION_FAILED, 'error', trans('errors.shift.check.only'));
                }
                // check thời gian với những mã code course thuộc lits thì không được sửa thời gian
                if (!in_array($valueShiftList['type'], array_merge(DAY_OFF_CODE, WAIT_CODE))) {
                    $course = Course::where(Course::COURSE_CODE, $valueShiftList['type'])->where(Course::COURSE_STATUS, 'on')->first();
                    if (!$course) {
                        return ResponseService::responseData(Response::HTTP_EXPECTATION_FAILED, 'error', trans('errors.shift.check'));
                    }
                    if ($course && $course->flag == null) {
                        if ($course->start_time != $valueShiftList['start_time'] || $course->end_time != $valueShiftList['end_time'] || $course->break_time != $valueShiftList['break_time']) {
                            return ResponseService::responseData(Response::HTTP_EXPECTATION_FAILED, 'error', trans('errors.shift.check.course'));
                        }
                    }
                }
//                $totalTimeInday += $this->getTotalTimeInShift($valueShiftList);
                $endtimeFirst = $valueShiftList['end_time'];
                $starttimeNext = $collectionShiftList[$keyShiftList + 1]['start_time'];
                if (strtotime($endtimeFirst) > strtotime($starttimeNext)) {
                    return ResponseService::responseData(Response::HTTP_EXPECTATION_FAILED, 'error', trans('errors.shift.check.time.cell'));
                }

            }
//            if ($totalTimeInday > 31) return ResponseService::responseData(Response::HTTP_EXPECTATION_FAILED, 'error', trans('errors.shift.check.24'));
        }
        return ResponseService::responseData(Response::HTTP_OK, 'success', 'success');
    }

    private function getTotalTimeInShift($valueShiftList)
    {
        $startTimes = Carbon::parse($valueShiftList['start_time']);
        $endTimes = Carbon::parse($valueShiftList['end_time']);
        $breakTimesHours = $valueShiftList['break_time'];
        $minsWork = ($endTimes->diffInMinutes($startTimes, true) / 60) + $breakTimesHours;
        return $minsWork;
    }

    private function dataShiftAdminValue($valueShift, $startDate, $endDate)
    {
        $dataShiftList = [];
        for ($date = $startDate; strtotime($date) <= strtotime($endDate); $date = date('Y-m-d', strtotime('+ 1 day', strtotime($date)))) {
            $resultAIForDate = ResultAI::whereDate('date', $date)->where('driver_code', $valueShift['driver_code'])->get()->toArray();
            if (count($resultAIForDate) == 0) {
                $dataShiftList[] = [
                    'date' => $date,
                    'value' => null,
                    'color' => COLOR_WORK,
                ];
            } else {
                $value = $this->convertValueAIForShiftList($resultAIForDate);
                $dayInfo = [
                    'date' => $date,
                    'value' => $value,
                    'color' => $this->checkColorShiftList($value, $valueShift, $date),
                ];
                if ($dayInfo['value'] && count($dayInfo['value']) == 1) {
                    $courseName = $dayInfo['value'][0]['name'];
                    $courseInfo = Course::where('course_name', $courseName)->first();
                    if ($courseInfo && $courseInfo->end_date && strtotime($courseInfo->end_date) < strtotime($dayInfo['date'])) {
                        $dayInfo['color'] = COLOR_OFF;
                    }
                }
                $dataShiftList[] = $dayInfo;
            }
        }

        return $dataShiftList;
    }

    private function checkColorShiftList($valueShift, $valueDriver, $date)
    {
        $arrCourse = [];
        $color = COLOR_WORK;
        $statusDriverForStatus = $this->checkStatusDriverWithDate($valueDriver, $date);
        if ($statusDriverForStatus == 'off') {
            return COLOR_OFF;
        }
        foreach ($valueShift as $keyValueShift => $value) {
            if (in_array($value['type'], DAY_OFF_CODE)) {
                if (Auth::user()->role == 'admin') {
                    $color = Common::getColorType($value['type']);
                } else {
                    $color = COLOR_HOLIDAY;
                }
            }
            if (!in_array($value['type'], array_merge(DAY_OFF_CODE, WAIT_CODE))) {
                $arrCourse[] = $value['course_status'];
            }
        }
        $uniqueArrayCourse = array_unique($arrCourse);
        if (count($uniqueArrayCourse) == 1 && $uniqueArrayCourse[0] != 'on') {
            $color = COLOR_WORK;
        }
        return $color;
    }

    // tí nữa check lại danh sách những người đã bị xóa
    private function checkStatusDriverWithDate($valueDayOff, $dateInMonth)
    {
        $startDate = $valueDayOff['start_date'];
        $endDate = $valueDayOff['end_date'];
        if (strtotime($dateInMonth) < strtotime($startDate)) return 'off';
        if (!$endDate && (strtotime($dateInMonth) >= strtotime($startDate))) return 'on';
        if ($endDate && (strtotime($dateInMonth) >= strtotime($startDate) && strtotime($dateInMonth) <= strtotime($endDate))) return 'on';
        if ($endDate && strtotime($dateInMonth) > strtotime($endDate)) return 'off';
        return 'off';
    }

    // check những thằng 2 tháng bị xóa
    private function convertValueAIForShiftList($resultAIForDate)
    {
        $dataResultAIForDate = [];
        foreach ($resultAIForDate as $keyResultAIForDate => $valueResultAIForDate) {
            $dataResultAIForDate[] = [
                'type' => $valueResultAIForDate['result_ai'],
                'name' => $this->getNameForTypeResultAI($valueResultAIForDate['result_ai']),
                'course_status' => $this->getDataCourseInResponseAI($valueResultAIForDate['result_ai'], 'status'),
                'start_time' => $valueResultAIForDate['start_time'],
                'end_time' => $valueResultAIForDate['end_time'],
                'break_time' => $valueResultAIForDate['break_time'],
            ];
        }
        $dataResultAIForDate = collect($dataResultAIForDate)->sortBy('start_time');
        return $dataResultAIForDate;
    }

    private function getNameForTypeResultAI($type)
    {
        $text = Common::getTextType($type);
        if (!in_array($type, DAY_OFF_CODE) && !in_array($type, WAIT_CODE)) {
            $theCourse = Course::where('course_code', $type)->first();
            if ($theCourse) {
                return $theCourse->course_name;
            }
        }
        return $text;
    }

    private function getDataCourseInResponseAI($type, $name)
    {
        if (in_array($type, array_merge(DAY_OFF_CODE, WAIT_CODE))) {
            return null;
        }
        $course = Course::where(Course::COURSE_CODE, $type)->first();
        if ($course) {
            $result = $course->$name;
            return $result;
        }
        return null;
    }

    private function getListDriver($startDate, $endDate, $orderByField = '', $typeSort = '', $getForAIExport = false)
    {
        $query = Driver::select('*')
            ->SelectRaw('IF( status = "off" OR end_date < ? OR end_date < ? , "yes", "no") AS highlight, IF( flag = "lead", 1, 0) AS flag_sorted', [
                date('Y-m-d'),
                $startDate
            ])
            ->with([
                'resultAI' => function ($q) use ($startDate, $endDate, $getForAIExport) {
                    if ($getForAIExport) {
                        $fromDate = Carbon::parse($startDate)->startOfMonth()->toDateString();
                        $toDate = Carbon::parse($startDate)->subDay()->toDateString();
                    } else {
                        $fromDate = $startDate;
                        $toDate = $endDate;
                    }
                    $q
                        ->select('*')
                        ->whereDate('date', '>=', $fromDate)
                        ->whereDate('date', '<=', $toDate);

                },
                'dayOff' => function ($q) use ($startDate, $endDate) {
                    $q->whereDate(DayOff::DAY_OFF_DATE, '>=', $startDate)
                        ->whereDate(DayOff::DAY_OFF_DATE, '<=', $endDate);
                },
                'driverCourse',
                'grades' => function ($q) use ($startDate, $endDate) {
                    $q->whereDate('date', '<=', $endDate)->orderByDesc('date');
                }
            ])
            // ->where('status', Driver::DRIVER_STATUS_WORK)
            ->where('start_date', '<=', $endDate)
        ;
        if ($getForAIExport) {
            $query->where('flag', '<>', 'lead');
        }
        $query->where(function($q) use ($startDate) {
                $q->where('end_date', null)
                    ->orWhereRaw('LAST_DAY(end_date) >= ?', [$startDate]);
            })
            ->orderBy('highlight')
            ->orderBy('flag_sorted')
        ;
        if ($orderByField && $typeSort) {
            $query->orderBy($orderByField, $typeSort);

        }

        return $query->orderBy('id')->get();
    }

    private function getListCourses($startDate, $endDate, $forSchedule = false)
    {
        if (!strtotime($startDate) || !strtotime($endDate)) {
            return null;
        }

        $query = Course::select('*')
            ->SelectRaw('IF( status = "off" OR end_date < ? OR end_date < ? , "yes", "no") AS highlight', [
                date('Y-m-d'),
                $startDate
            ])
            ->with([
                'courseSchedules' => function ($q) use ($endDate) {
                    $q->select(['id', 'course_code', 'schedule_date', 'status', 'lunar_jp'])
                        ->whereYear('schedule_date', date('Y', strtotime($endDate)))
                        ->whereMonth('schedule_date', date('m', strtotime($endDate)))
                        ->orderBy('course_code')
                        ->orderBy('schedule_date')
                        ;
                },
                'coursePatterns' => function ($q) {
                    $q->orderBy('course_parent_code')
                        ->orderBy('course_child_code');
                },
                'owner',
                'points' => function ($q) use ($endDate) {
                    $q->whereDate('date', '<=', $endDate)->orderByDesc('date');
                }
            ])
        ;

        if ($forSchedule) {
            $query->where('flag', Course::COURSE_FLAG_NO);
        }
        $query
            ->where('start_date', '<=', $endDate)
            ->orderBy('highlight');
        return $query->orderBy('id')->get();
    }

    private function getCourses($startDate, $endDate, $forSchedule = false, $field = '', $sortBy = '')
    {
        if (!strtotime($startDate) || !strtotime($endDate)) {
            return null;
        }
        $query = Course::select('*')
            ->SelectRaw('IF( status = "off" OR end_date < ? OR end_date < ? , "yes", "no") AS highlight', [
                date('Y-m-d'),
                $startDate
            ]);
        $withs = [
                'courseSchedules' => function ($q) use ($endDate) {
                    $q->select(['id', 'course_code', 'schedule_date', 'status', 'lunar_jp'])
                        ->whereYear('schedule_date', date('Y', strtotime($endDate)))
                        ->whereMonth('schedule_date', date('m', strtotime($endDate)))
                        ->orderBy('course_code')
                        ->orderBy('schedule_date')
                        ;
                },
                'resultAI' => function ($q) use ($endDate) {
                    $q->select(['id', 'course_code', 'driver_code', 'date', 'result_ai'])
                        ->whereYear('date', date('Y', strtotime($endDate)))
                        ->whereMonth('date', date('m', strtotime($endDate)))
                        ->orderBy('date');
                }
            ];
        $query->with($withs);

        if ($forSchedule) {
            $query->where('flag', Course::COURSE_FLAG_NO);
        }
        $query
            ->where('start_date', '<=', $endDate)
            ->where(function($q) use ($startDate) {
                 $q->where('end_date', null)
                    ->orWhereRaw('LAST_DAY(end_date) >= ?', [$startDate]);
            })
            ->orderBy('highlight');
        if ($field && $sortBy) {
            $query->orderBy($field, $sortBy);
        }
        return $query->orderBy('id')->get();
    }

    public function xlsx($file)
    {
        $spreadsheet = (new Xlsx())->load($file);
        $data = [];
        for ($i = 0; $i < $spreadsheet->getSheetCount(); $i++) {
            $sheet = $spreadsheet->getSheet($i);
            $array = $sheet->toArray();
            $data[] = $array;
        }
        return $data;
    }

    public function downloadFile($request = [], $fileType = 'xlsx')
    {
        $date = $request['date'];
        $startDate = Carbon::parse($date)->startOfMonth()->toDateString();
        $endDate = Carbon::parse($date)->endOfMonth()->toDateString();
        $type = $request['type'];
        $field = $request['field'] ?? '';
        $sortBy = $request['sortby'] ?? '';

        $headers['Content-Type'] = FILE_MIMETYPES[$fileType];

        switch ($fileType) {
            case 'xlsx':
                $writeType = \Maatwebsite\Excel\Excel::XLSX;
                break;
            case 'csv':
                $writeType = \Maatwebsite\Excel\Excel::CSV;
                break;
            case 'pdf':
                $writeType = \Maatwebsite\Excel\Excel::MPDF;
                break;
            default:
                return ResponseService::responseData(Response::HTTP_UNPROCESSABLE_ENTITY, ERROR, ERROR);
        }

        $params = [
            'date' => $date,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'field' => $field,
            'sortby' => $sortBy
        ];

        switch ($type) {
            case 'grade-tab':
                $fileName = '人件費表_{' . date('Y', strtotime($startDate)) . '_' . date('m', strtotime($startDate)) . '}.' . $fileType;
                $params['tab3'] = true;
                break;
            case 'default-tab':
                $fileName = 'シフト表_{' . date('Ymd', strtotime($startDate)) . '-' . date('Ymd', strtotime($endDate)) . '}.' . $fileType;
                break;
            default:
                return ResponseService::responseData(Response::HTTP_UNPROCESSABLE_ENTITY, ERROR, ERROR);
        }

        $list = self::list(new ShiftRequest($params));

        if ($list['status'] != 'success') {
            return $list;
        }

        if (!isset($list['data'])) {
            $list['data'] = [];
        }

        $items = $list['data'];

        $calendars = Common::Calendars($startDate, $endDate);

        switch ($type) {
            case 'default-tab':
                $data = [
                    'startDate' => $startDate,
                    'endDate' => $endDate,
                    'calendars' => $calendars
                ];
                $export = new ShiftExport($items, $data);
            break;
            case 'grade-tab':
                $data = [
                    'startDate' => $startDate,
                    'endDate' => $endDate,
                    'calendars' => $calendars
                ];
                $export = new ShiftGrade($items, $data);
            break;

            default:
                return ResponseService::responseData(Response::HTTP_UNPROCESSABLE_ENTITY, ERROR, ERROR);
        }
        return $export->download($fileName, $writeType, $headers);
    }
}

