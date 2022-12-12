<?php
/**
 * Created by VeHo.
 * Year: 2022-08-04
 */

namespace Repository;

use App\Http\Requests\DayOffRequest;
use App\Models\Calendar;
use App\Models\Course;
use App\Models\DayOff;
use App\Models\Driver;
use App\Repositories\Contracts\DayOffRepositoryInterface;
use Carbon\Carbon;
use Helper\Common;
use Helper\ResponseService;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Repository\BaseRepository;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Auth;

class DayOffRepository extends BaseRepository implements DayOffRepositoryInterface
{

    protected $totalMonth = 12;

    protected $monthConfig = [
        'sun' => 0,
        'mon' => 1,
        'tue' => 2,
        'wed' => 3,
        'thu' => 4,
        'fri' => 5,
        'sat' => 6,
    ];

    public function __construct(Application $app)
    {
        parent::__construct($app);
    }

    /**
     * Instantiate model
     *
     * @param DayOff $model
     */

    public function model()
    {
        return DayOff::class;
    }

    /**
     * @param DayOffRequest $request
     * @return array|mixed|null
     */
    public function getPagination(DayOffRequest $request)
    {
        $date = $request->date;
        if (!$date) {
            $date = Carbon::now()->format('Y-m');
        }
        $sortby = isset($request['sortby']) ? $request['sortby'] : null;
        $field = isset($request['field']) ? $request['field'] : null;
        $listDayOff = $this->getListDriver(Carbon::parse($date)->startOfMonth()->toDateString(), Carbon::parse($date)->endOfMonth()->toDateString(), $field, $sortby);

        if (count($listDayOff) == 0) {
            return ResponseService::responseData(Response::HTTP_OK, 'success', 'success', []);
        }
        $drivers = $this->getDataDayOff($listDayOff->toArray(), Carbon::parse($date)->startOfMonth()->toDateString());

        return ResponseService::responseData(Response::HTTP_OK, 'success', 'success', $drivers);
    }

    private function getListDriver($startDate, $endDate, $orderByField = '', $typeSort = '')
    {
        $query = Driver::select('id','flag', 'driver_code', 'driver_name', 'start_date', 'end_date', 'status', 'day_of_week')
            ->SelectRaw('IF( status = "off" OR end_date < ? OR end_date < ? , "yes", "no") AS highlight', [
                date('Y-m-d'),
                $startDate
            ])
            ->with([
                'dayOff' => function ($q) use ($startDate, $endDate) {
                    $q->whereDate(DayOff::DAY_OFF_DATE, '>=', $startDate)
                        ->whereDate(DayOff::DAY_OFF_DATE, '<=', $endDate);
                },

            ])
            // ->where('status', Driver::DRIVER_STATUS_WORK)
            ->where('start_date', '<=', $endDate)
            ->where('flag', '<>', 'lead')
            ->where(function($q) use ($startDate) {
                $q->where('end_date', null)
                    ->orWhereRaw('LAST_DAY(end_date) >= ?', [$startDate]);
            })
            ->orderBy('highlight');
        if ($orderByField) {
            if ($typeSort) {
                $query->orderBy($orderByField, $typeSort);
            } else {
                $query->orderBy($orderByField);
            }

        }

        return $query->orderBy('id')->get();
    }

    private function getDataDayOff($drivers, $startDate)
    {
        $year = Carbon::parse($startDate)->year;
        $month = Carbon::parse($startDate)->month;
        $daysInMonth = Carbon::parse($startDate)->daysInMonth;
        $calendars = Common::Calendars();

        foreach ($drivers as &$driver) {
            $daysOfMonth = [];
            $dayOffs = $driver['day_off'];
            for($d = 1; $d <= $daysInMonth; $d ++) {
                $date = Carbon::create($year, $month, $d)->toDateString();
                $dtInt = strtotime($date);
                $daysOfMonth[$dtInt] = [
                    "driver_code" => $driver['driver_code'],
                    "date" => $date,
                    "type" => "D-5",
                    "has_codes" => "",
                    "color" => COLOR_WORK,
                    'status' => 'on',
                    'lunar_jp' => $calendars[$date] ?? []
                ];
                if ($dtInt < strtotime($driver['start_date']) || (strtotime($driver['end_date']) && $dtInt > strtotime($driver['end_date']))) {
                    $daysOfMonth[$dtInt]['type'] = '';
                    $daysOfMonth[$dtInt]['color'] = COLOR_OFF;
                    $daysOfMonth[$dtInt]['status'] = 'off';
                }
            }

            if ($dayOffs) {
                foreach ($dayOffs as $item) {
                    $dtInt = strtotime($item['date']);

                    if ($dtInt < strtotime($driver['start_date']) || (strtotime($driver['end_date']) && $dtInt > strtotime($driver['end_date']))) {
                        continue;
                    }
                    $typeText = Common::getTextType($item['type']);
                    $daysOfMonth[$dtInt]['type'] = $item['type'];
                    $daysOfMonth[$dtInt]['has_codes'] = $typeText == 'D-5' ? $item['has_codes']: '';

                    if (in_array($item['type'], DAY_OFF_CODE)) {
                        $daysOfMonth[$dtInt]['color'] = Common::getColorType($item['type']);
                    }
                }
            }
            $daysResult = [];
            if ($daysOfMonth) {
                foreach ($daysOfMonth as $day) {
                    $daysResult[] = $day;
                }
            }
            $driver['day_off'] = $daysResult;
        }
        return $drivers;
    }

    /** create DayOff -> create Driver
     * @param Driver $driver
     */
    public function createDayOff(Driver $driver, $flag = null)
    {
        if(!$flag){
            $startDate = $driver->start_date;
            $startDateFake = Carbon::parse(now())->toDateString();
            $fakeEndDate = Carbon::parse($startDateFake)->addMonth(12);
            $endDate = Carbon::create($fakeEndDate->year, $fakeEndDate->month, Carbon::parse($fakeEndDate)->daysInMonth)->toDateString();
        } elseif ($flag && $flag == 'update'){
            $startDate = Carbon::parse(now())->toDateString();
            $fakeEndDate = Carbon::parse($startDate)->addMonth(12);
            $endDate = Carbon::create($fakeEndDate->year, $fakeEndDate->month, Carbon::parse($fakeEndDate)->daysInMonth)->toDateString();
        } elseif ($flag && $flag == 'cronUpdate') {
            $timeUpdate = Carbon::now()->addMonth(12);
            $startDate = Carbon::create($timeUpdate->year, $timeUpdate->month, 1)->toDateString();
            $endDate = Carbon::create($timeUpdate->year, $timeUpdate->month, $timeUpdate->daysInMonth)->toDateString();
        }
        $arrayConvertDayOfWeekForDriver = $this->convertDayOfWeekForDriver($driver);
        $this->insertDataDayOff($driver, $startDate, $endDate, $arrayConvertDayOfWeekForDriver);
    }

    // inser DB
    private function insertDataDayOff($driver, $startDate, $endDate, $arrayConvertDayOfWeekForDriver)
    {
        $calendars = Common::Calendars($startDate, $endDate);
        for ($date = $startDate; strtotime($date) <= strtotime($endDate); $date = date('Y-m-d', strtotime('+ 1 day', strtotime($date)))) {
            $day = Carbon::parse($date)->dayOfWeek;
            if (in_array($day, $arrayConvertDayOfWeekForDriver)) {
                $type = DayOff::DAY_OFF_TYPE_FIXED_DAY_OFF;
                $color = COLOR_FIXED_DAY_OFF;
                $lunarJp = $calendars[$date] ?? [];
                $createDayOff = DayOff::createDayOff($driver, $date, $type, $color, $lunarJp);
                if ($createDayOff['status'] != 'success') {
                    Log::error('create day off error' . now() . $driver->id);
                }
            }
        }
    }

    // xóa toàn bộ data từ ngày cập nhật mới bắt đầu từ ngày cập nhật
    public function updateDayOff(Driver $driver)
    {
        $date = Carbon::parse(now())->toDateString();
        $deleteDayOff = DayOff::where(DayOff::DAY_OFF_DRIVER_CODE,$driver->driver_code)->whereDate('date','>=',$date)->delete();
        if ($driver->day_of_week){
            $this->createDayOff($driver, 'update');
        }
    }

    /**
     * @param array $attributes
     * @return array|mixed|null
     */
    public function create(array $attributes)
    {
        $date = $attributes['date'];
        $startDate = Carbon::parse($date)->startOfMonth()->toDateString();
        $endDate = Carbon::parse($date)->endOfMonth()->toDateString();
        $days = $attributes['day_off'];
        $updateDayOffData = [];
        $calendars = Common::Calendars($startDate, $endDate);

        foreach ($days as $day) {
            $driverId = $day['driver_code'];
            $type = $day['type'];
            $dayDate = $day['date_off'];
            $color = Common::getColorType($type);
            $driver = Driver::where(Driver::DRIVER_CODE, $driverId)->first();
            if (!$driver) {
                return ResponseService::responseData(\Illuminate\Http\Response::HTTP_UNPROCESSABLE_ENTITY, 'error', trans('errors.data_not_found'));
            }
            if ($driver->status != 'on') continue;

            $dayOff = DayOff::where(DayOff::DAY_OFF_DRIVER_CODE, $driver->driver_code)
                ->whereDate(DayOff::DAY_OFF_DATE, $dayDate)->first();

            $lunarJp = $calendars[$dayDate] ?? [];
            // nếu tồn tại ngày nghỉ và ngày nghỉ có type != d5 và type mới khác hiện tại thì cập nhật
            if ($dayOff && $dayOff->type != $type && $type != DayOff::DAY_OFF_TYPE_WORK) {
                $updateDayOff = DayOff::updateDayOff($dayOff, '', $type, $color);
                if ($updateDayOff['status'] != 'success') {
                    return ResponseService::responseData($updateDayOff['code'], $updateDayOff['status'], $updateDayOff['message']);
                }
                $updateDayOffData[] = $updateDayOff['data'];
            } elseif ($type == DayOff::DAY_OFF_TYPE_WORK) {
                $hasCodes = str_replace(' ', '', $day['has_codes']);
                if ($hasCodes && $courses = Course::whereIn('course_code', explode(',', $hasCodes))) {
                    if ($courses->count() == count(explode(',', $hasCodes))) {
                        if ($dayOff) {
                            $dayOff->type = $type;
                            $dayOff->has_codes = $hasCodes;
                            $dayOff->color = $color;
                            $dayOff->save();

                            $updateDayOffData[] = $dayOff;
                        } else {
                            $updateDayOff = DayOff::createDayOff($driver, $dayDate, $type, $color, $lunarJp, $hasCodes);
                            if ($updateDayOff['status'] != 'success') {
                                return ResponseService::responseData($updateDayOff['code'], $updateDayOff['status'], $updateDayOff['message']);
                            }
                            $updateDayOffData[] = $updateDayOff['data'];
                        }
                    } else {
                        return ResponseService::responseData(\Illuminate\Http\Response::HTTP_UNPROCESSABLE_ENTITY, 'error', trans('errors.data_not_found'), '', 'some course code are not found');
                    }

                } elseif ($dayOff) {
                    $dayOff->delete();
                }
            } elseif (!$dayOff && $type != DayOff::DAY_OFF_TYPE_WORK) {
                $updateDayOff = DayOff::createDayOff($driver, $dayDate, $type, $color, $lunarJp);
                if ($updateDayOff['status'] != 'success') {
                    return ResponseService::responseData($updateDayOff['code'], $updateDayOff['status'], $updateDayOff['message']);
                }
                $updateDayOffData[] = $updateDayOff['data'];
            }
        }
        return ResponseService::responseData(Response::HTTP_OK, 'success', 'success', $updateDayOffData);
    }

    private function convertDayOfWeekForDriver(Driver $driver)
    {
        $arrayDayOfWeekConvert = [];
        if ($driver->day_of_week) {
            $dayOfWeek = explode(',', $driver->day_of_week);
            foreach ($dayOfWeek as $day) {
                $arrayDayOfWeekConvert[] = $this->monthConfig[$day];
            }
        }
        return $arrayDayOfWeekConvert;
    }
}
