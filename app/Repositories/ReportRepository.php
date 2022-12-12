<?php
/**
 * Created by VeHo.
 * Year: 2022-07-29
 */

namespace Repository;

use App\Exports\ReportExport;
use App\Models\Course;
use App\Models\Driver;
use App\Models\Report;
use App\Models\ResultAI;
use App\Repositories\Contracts\ReportRepositoryInterface;
use Carbon\Carbon;
use Helper\ResponseService;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Repository\BaseRepository;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Auth;
use \Mpdf\Mpdf as PDF;

class ReportRepository extends BaseRepository implements ReportRepositoryInterface
{

     public function __construct(Application $app)
     {
         parent::__construct($app);

     }

    /**
       * Instantiate model
       *
       * @param Report $model
       */

    public function model()
    {
        return Report::class;
    }
    public static function getListCourses($startDate, $endDate)
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
                'points' => function ($q) use ($endDate) {
                    $q->whereDate('date', '<=', $endDate)->orderByDesc('date');
                }
            ])
            ->where('start_date', '<=', $endDate)
            ->orderBy('highlight');
        return $query->get();
    }

    public static function syncData($startDate, $endDate, $sortByDriverCode = '', $sortByDriverType = '')
    {
        $listDriver = self::getListDriver($startDate, $endDate, $sortByDriverCode, $sortByDriverType);

        if (!$listDriver) {
            return ['empty' => 'Drivers not found'];
        }
        $drivers = $listDriver->keyBy('driver_code')->toArray();

        $listCourse = self::getListCourses($startDate->toDateString(), $endDate->toDateString());
        $courses = $listCourse->keyBy('course_code')->toArray();

        $data = ResultAI::whereDate('date', '>=', $startDate->toDateString())
            ->whereDate('date', '<=', $endDate->toDateString())
            ->get()->toArray();

        if (!$data) {
            // return ['empty' => 'result from AI not found'];
        }
        $items = [];
        //filter data available
        foreach ($data as $d) {
            $c = $d['driver_code'];
            $statusWork = $d['result_ai'];
            $dt = strtotime($d['date']);
            $startTime = $d['start_time'];
            $endTime = $d['end_time'];
            if (!isset($drivers[$c]))
                continue;
            $existDriver = $drivers[$c];
            if ($dt < strtotime($existDriver['start_date']) || (strtotime($existDriver['end_date']) && $dt > strtotime($existDriver['end_date'])) ) {
                continue;
            }
            if (!isset($items[$c])) {
                $items[$c] = [
                    'working_days' => [],
                    'days_off' => 0,
                    'days_off_paid' => 0,
                    'days_off_request' => 0,
                    'total_time' => 0,
                    'driving_time' => [],
                    'break_time' => [],
                    'over_time' => 0,
                    'point' => 0.00
                ];
            }
            $totalTime = 0;
            $daysOff = 0;
            $daysOffRequest = 0;
            $daysOffPaid = 0;
            $breakTime = 0;

            if (!in_array($statusWork, DAY_OFF_CODE)) {
                if (!in_array($statusWork, WAIT_CODE)) {
                    if (isset($courses[$statusWork]) && $dt >= strtotime($courses[$statusWork]['start_date']) && (!$courses[$statusWork]['end_date'] || $dt <= strtotime($courses[$statusWork]['end_date']))) {
                        $totalTime = self::hoursToDecimal($endTime) - self::hoursToDecimal($startTime);

                        $breakTime +=  $d['break_time'];

                        $course = $courses[$statusWork];
                        $lastPoint = 0;

                        if ($course['points']) {
                            foreach ($course['points'] as $point) {
                                if (strtotime($point['date']) <= strtotime($dt)) {
                                    $lastPoint = $point['point'];
                                    break;
                                }
                            }
                        } else {
                            $lastPoint = $course['point'];
                        }

                        $items[$c]['point'] += $lastPoint;

                        if (!isset($items[$c]['driving_time'][$dt])) {
                            $items[$c]['driving_time'][$dt] = $totalTime;
                        } else {
                            $items[$c]['driving_time'][$dt] += $totalTime;
                        }
                    } else {
                        continue;
                    }
                } else {
                    $totalTime = self::hoursToDecimal($endTime) - self::hoursToDecimal($startTime);
                    $breakTime +=  $d['break_time'];
                }
                if (!isset($items[$c]['working_days'][$dt])) {
                    $items[$c]['working_days'][$dt] = $totalTime;
                } else {
                    $items[$c]['working_days'][$dt] += $totalTime;
                }

                if (!isset($items[$c]['break_time'][$dt])) {
                    $items[$c]['break_time'][$dt] = $breakTime;
                } else {
                    $items[$c]['break_time'][$dt] += $breakTime;
                }
            } else {
                $daysOff = 1;
                if ($statusWork == IS_DAY_OFF_REQUEST) {
                    $daysOffRequest = 1;
                }

                if ($statusWork == IS_DAY_OFF_PAID) {
                    $daysOffPaid = 1;
                }
            }
            $items[$c]['total_time'] += $totalTime;
            $items[$c]['days_off'] += $daysOff;
            $items[$c]['days_off_request'] += $daysOffRequest;
            $items[$c]['days_off_paid'] += $daysOffPaid;
        }

        foreach ($items as $c => &$item) {
            ksort($item['working_days'], 1);
            $item['point'] = !empty($item['point']) ? max($item['driving_time']): 0;
            $item['driving_time'] = !empty($item['driving_time']) ? array_sum($item['driving_time']): 0;
            $item['break_time'] = !empty($item['break_time']) ? array_sum($item['break_time']): 0;
            $item['working_days'] = count($item['working_days']);
            $item['over_time'] = ($item['total_time'] - $item['break_time'] - $item['working_days'] * 8) > 0 ? $item['total_time'] - $item['break_time'] - $item['working_days'] * 8: 0;
            //set float
            $item['break_time'] = number_format($item['break_time'], 2);
            $item['total_time'] = number_format($item['total_time'], 2);
            $item['driving_time'] = number_format($item['driving_time'], 2);
            $item['over_time'] = number_format($item['over_time'], 2);
            $item['point'] = number_format($item['point'], 2);
        }
        // end filter
        foreach (array_keys($drivers) as $code) {
            $data = $items[$code] ?? [];
            if ($data) {
                 $drivers[$code]['reports'] = $data;
            }
        }

        return $drivers;
    }

    public static function hoursToDecimal($timeInHours) {
        $times = explode(':', $timeInHours);
        return $times[0] + ($times[1]/60);
    }

    public static function getListDriver($startDate, $endDate, $sortByCode = '', $sortByDriverType = '')
    {
        if ($sortByCode && !in_array($sortByCode, ['asc', 'desc'])) {
            $sortByCode = '';
        }
        if ($sortByDriverType && !in_array($sortByDriverType, ['asc', 'desc'])) {
            $sortByDriverType = '';
        }
        $query = Driver::select('id', 'driver_code', 'driver_name', 'flag', 'status', 'start_date', 'end_date', 'day_of_week')
            ->SelectRaw('IF( status = "off" OR end_date < ? OR end_date < ? , "yes", "no") AS highlight, IF( flag = "lead", 1, 0) AS flag_sorted', [
                date('Y-m-d'),
                $startDate->toDateString()
            ])

            ->where('start_date', '<=', $endDate->toDateString())
            ->where(function($q) use ($startDate) {
                $q->where('end_date', null)
                    ->orWhereRaw('LAST_DAY(end_date) >= ?', [$startDate->toDateString()]);
            })
            ->orderBy('highlight')
            ->orderBy('flag_sorted');

        if($sortByCode) {
            $query->orderBy('driver_code', $sortByCode);
        }

        if($sortByDriverType) {
            $query->orderBy('flag', $sortByDriverType);
        }
        return $query->orderBy('id')->get();
    }

    public function getList($request = [])
    {
        $sortByCode = isset($request['sortby_code']) ? strtolower($request['sortby_code']) : '';
        $sortByDriverType = isset($request['sortby_driver_type']) ? strtolower($request['sortby_driver_type']): '';
        $viewDate = isset($request['view_date']) && strtotime($request['view_date']) ? strtotime($request['view_date']):strtotime(date('Y-m'));

        $startDate = \Carbon\Carbon::parse(date('Y-m-d', $viewDate))->startOfMonth();
        $endDate = \Carbon\Carbon::parse(date('Y-m-d', $viewDate))->endOfMonth();
        $items = self::syncData($startDate, $endDate, $sortByCode, $sortByDriverType);
        if (isset($items['empty'])) {
            return ResponseService::responseData(CODE_SUCCESS, 'success', 'success', null);
        }
        if (!is_array($items)) {
            $items = $items->toArray();
        }
        if (!$items) {
            return ResponseService::responseData(\Illuminate\Http\Response::HTTP_OK, 'success', 'success', null);
        }

        foreach ($items as &$item) {
            if (!isset($item['reports'])) {
                $item['reports'] = [];
            }
            if (empty($item['reports'])) {
                $item['reports'] = [
                    'working_days' => 0,
                    'days_off' => 0,
                    'days_off_paid' => 0,
                    'days_off_request' => 0,
                    'total_time' => "0.00",
                    'driving_time' => "0.00",
                    'break_time' => "0.00",
                    'over_time' => "0.00",
                    'point' => "0.00"
                ];

            }
        }

        return ResponseService::responseData(\Illuminate\Http\Response::HTTP_OK, 'success', 'success', $items);
    }

    private function countInMonth($startDate = null, $endDate = null, $weekday = [])
    {
        if (!$weekday || !is_array($weekday)) {
            return 0;
        }
        if (!$startDate || !strtotime($startDate))
        {
            $startDate = \Carbon\Carbon::now()->startOfMonth();
        } else {
            $startDate = Carbon::parse($startDate);
        }

        if (!$endDate || !strtotime($endDate))
        {
            $endDate = Carbon::parse($startDate)->endOfMonth();
        } else {
            $endDate = Carbon::parse($endDate);
        }

        if (strtotime($endDate->toDateString()) < strtotime($startDate->toDateString())) {
            $endDate = Carbon::parse($startDate)->endOfMonth();
        }
        $endDate = $endDate->addDay();
        return $startDate->diffInDaysFiltered(function ($date) use ($weekday) {
            $days = true;
            if (in_array('mon', $weekday)) {
                $days = $days && !$date->isMonday();
            }
            if (in_array('tue', $weekday)) {
                $days = $days && !$date->isTuesday();
            }
            if (in_array('wed', $weekday)) {
                $days = $days && !$date->isWednesday();
            }
            if (in_array('thu', $weekday)) {
                $days = $days && !$date->isThursday();
            }
            if (in_array('fri', $weekday)) {
                $days = $days && !$date->isFriday();
            }
            if (in_array('sat', $weekday)) {
                $days = $days && !$date->isSaturday();
            }
            if (in_array('sun', $weekday)) {
                $days = $days && !$date->isSunday();
            }
            return $days;
        }, $endDate);
    }

    public function downloadFileExported($viewDate, $sortByCode, $sortByDriverType, $fileType = 'xlsx')
    {
        $fileName = '実務実績月別_{' . date('Y', $viewDate) . '_' . date('m', $viewDate) . '}.' . $fileType;
        $statusView = null;
        $headers['Content-Type'] = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
        $writeType = \Maatwebsite\Excel\Excel::XLSX;
        $requestParams = [
            'view_date' => date('Y-m', $viewDate),
            'sortby_code' => $sortByCode,
            'sortby_driver_type' => $sortByDriverType
        ];

        $list = self::getList($requestParams, 'yes');

        if (!isset($list['data'])) {
            $list['data'] = [];
        }

        $items = $list['data'];
        if ($fileType == 'pdf' && 1 == 2) {
            $document = new PDF( [
                'mode' => 'utf-8',
                // 'format' => 'A4',
                // 'format' => [290 , 250],
                'format' => 'A4-L',
                'margin_header' => '3',
                'margin_top' => '25',
                'margin_bottom' => '20',
                'margin_footer' => '2',
                'defaultPageNumStyle' => '1',
                'pagenumSuffix' => '/',
                'fontDir' => public_path('fonts/'),
                'fontdata' => [ // lowercase letters only in font key
                    'inter' => [
                        'R' => 'rounded-mgenplus-1c-regular.ttf',
                    ]
                ]
            ]);
            $htmlHeader = '
                    <table id="pdf-header" width="100%">
                        <tr> <td colspan="10">株式会社グローバルエアカーゴ</td> </tr>
                        <tr>
                            <td colspan="10" class="border-bottom-0" style="border-bottom: 1px solid #000;" >
                                <div style="display: block; width: 100%;">
                                    <table style="width: 100%; padding: 0; margin: 0 ">
                                        <tr style="padding: 0; margin: 0">
                                            <td style="width: 18%;padding-left: -3px; font-size: 20px" >月別 実務実績表</td>
                                            <td colspan="11">' . date("Y", $viewDate)  . '年' . date("n", $viewDate) . '月</td>
                                        </tr>
                                    </table>
                                </div>
                            </td>

                       </tr>
                    </table>';


            $document->setFooter('{PAGENO}{nbpg}');

            $document->SetHTMLHeader($htmlHeader);
            $document->WriteHTML(view('exports.report-pdf', [
                'items' => $items,
                'viewDate' => $viewDate,
                'statusView' => $statusView
            ]));
            $document->Output($fileName, 'D');
        }
        return (new ReportExport($items, $viewDate, $statusView))->download($fileName, $writeType, $headers);
        // $filePath = Storage::path($fullPath);
        // return Response::download($filePath, $fileName, $headers);
    }

}
