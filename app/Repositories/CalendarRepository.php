<?php
/**
 * Created by VeHo.
 * Year: 2022-07-29
 */

namespace Repository;

use App\Models\Calendar;
use App\Repositories\Contracts\CalendarRepositoryInterface;
use Helper\ResponseService;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\DB;
use Repository\BaseRepository;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;


class CalendarRepository extends BaseRepository implements CalendarRepositoryInterface
{

    public function __construct(Application $app)
    {
        parent::__construct($app);

    }

    /**
     * Instantiate model
     *
     * @param Calendar $model
     */

    public function model()
    {
        return Calendar::class;
    }

    public function index($request)
    {
        $startDate = $request['start_date'];
        $endDate = $request['end_date'];
        $checkDateGetData = $this->checkDateGetData($startDate, $endDate);
        if ($checkDateGetData['status'] != 'success') {
            return ResponseService::responseData($checkDateGetData['code'], $checkDateGetData['status'], $checkDateGetData['message']);
        }
        $calendar = Calendar::whereDate(Calendar::CALENDAR_DATE, '>=', $startDate)
            ->whereDate(Calendar::CALENDAR_DATE, '<=', $endDate)
            ->get();

        return ResponseService::responseData(\Illuminate\Http\Response::HTTP_OK, 'success', 'success', $calendar);
    }

    public function indexGetData($startDate,$endDate)
    {
        $checkDateGetData = $this->checkDateGetData($startDate, $endDate);
        if ($checkDateGetData['status'] != 'success') {
            return ResponseService::responseData($checkDateGetData['code'], $checkDateGetData['status'], $checkDateGetData['message']);
        }
        $calendar = Calendar::whereDate(Calendar::CALENDAR_DATE, '>=', $startDate)
            ->whereDate(Calendar::CALENDAR_DATE, '<=', $endDate)
            ->get();

        return $calendar;
    }

    /** create calendar
     * @param $request
     * @return array|mixed|null
     */
    public function store($request)
    {
        $checkYear = $this->checkYearData($request);
        if ($checkYear['status'] != 'success') {
            return ResponseService::responseData($checkYear['code'], 'error', $checkYear['message']);
        }
        $dataParam = [
            'mode' => 'm',
            'cnt' => 12,
            'targetyyyy' => $request['targetyyyy'],
            'targetmm' => 1,
            'targetdd' => 31
        ];
        $api = 'https://koyomi.zingsystem.com/api/';
        $dataCURL = $this->getCURL($api, $dataParam);
        if (!$dataCURL) {
            return ResponseService::responseData(\Illuminate\Http\Response::HTTP_NOT_FOUND, 'error', 'errors.data_not_found');
        }
        $dataCalendarUpdate = $dataCURL['data']['datelist'];
        foreach ($dataCalendarUpdate as $key => $valueDataCURL) {
            $week = !empty($valueDataCURL['week']) ? $valueDataCURL['week'] : null;
            $rokuyou = !empty($valueDataCURL['rokuyou']) ? $valueDataCURL['rokuyou'] : null;
            $holiday = !empty($valueDataCURL['holiday']) ? $valueDataCURL['holiday'] : null;
            $calendar = Calendar::create($key, $week, $rokuyou, $holiday);
            if ($calendar['status'] != 'success') {
                Calendar::whereYear(Calendar::CALENDAR_DATE, $request['targetyyyy'])->delete();
                return ResponseService::responseData($calendar['code'], $calendar['status'], $calendar['message']);

            }
        }
        return ResponseService::responseData(\Illuminate\Http\Response::HTTP_OK, 'success');
    }

    /** delete : param with year, delete all : all
     * @param $request
     * @return array|int|mixed|null
     */
    public function delete($request)
    {
        $code = $request['code'];
        if ($code == 'all') {
            Calendar::query()->truncate();
            $calender = Calendar::first();
            if ($calender) {
                DB::table('calendars')->truncate();
            }
            $calender = Calendar::first();
            if ($calender) return ResponseService::responseData(\Illuminate\Http\Response::HTTP_NOT_FOUND, 'error', 'error 2');
        } else {
            Calendar::whereYear(Calendar::CALENDAR_DATE, $request['code'])->delete();
            $calender = Calendar::whereYear(Calendar::CALENDAR_DATE, $request['code'])->first();
            if ($calender) return ResponseService::responseData(\Illuminate\Http\Response::HTTP_NOT_FOUND, 'error', 'error 2');
        }
        return ResponseService::responseData(\Illuminate\Http\Response::HTTP_OK, 'success');
    }


    //call api get data
    private function getCURL($api, $params)
    {
        $ch = curl_init($api);
        curl_setopt($ch, CURLOPT_URL, $api);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, TRUE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));

        $result = curl_exec($ch);

        curl_close($ch);
        $data = json_decode($result, true);
        return ResponseService::responseData(\Illuminate\Http\Response::HTTP_OK, 'success', 'success', $data);
    }

    //check year get data
    private function checkYearData($request)
    {
        $targetyyyy = $request['targetyyyy'];
        $calender = Calendar::whereYear(Calendar::CALENDAR_DATE, $targetyyyy)->first();
        if ($calender) {
            return ResponseService::responseData(\Illuminate\Http\Response::HTTP_NOT_FOUND, 'error', 'error1');
        }
        return ResponseService::responseData(\Illuminate\Http\Response::HTTP_OK, 'success');
    }

    private function checkDateGetData($startDate, $endDate)
    {
        $diff = Carbon::parse($endDate)->diffInDays(Carbon::parse($startDate));
        if ($diff > 60) {
            return ResponseService::responseData(\Illuminate\Http\Response::HTTP_NOT_FOUND, 'error', trans('errors.calender.get_data_index_60'));
        }
        return ResponseService::responseData(\Illuminate\Http\Response::HTTP_OK, 'success');
    }

}
