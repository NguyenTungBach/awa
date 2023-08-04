<?php

namespace Repository;

use App\Models\Driver;
use App\Models\DriverCourse;
use App\Repositories\Contracts\PaymentRepositoryInterface;
use Helper\Common;
use Helper\ResponseService;
use Illuminate\Http\Response;
use Repository\BaseRepository;
use Illuminate\Foundation\Application;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Builder;

class PaymentRepository extends BaseRepository implements PaymentRepositoryInterface
{
    public function __construct(Application $app)
    {
        parent::__construct($app);
    }

    /**
     * Instantiate model
     *
     * @param Driver $model
     */

    public function model()
    {
        return Driver::class;
    }

    public function getAll($input)
    {
        $input['order_by'] = Arr::get($input, 'order_by', 'drivers.id');
        $input['sort_by'] = Arr::get($input, 'sort_by', 'desc');
        $input['month_year'] = Arr::get($input, 'month_year', Carbon::now()->format('Y-m'));
        
        $startOfMonth = Carbon::createFromDate($input['month_year'])->startOfMonth()->format('Y-m-d');
        $endOfMonth = Carbon::createFromDate($input['month_year'])->endOfMonth()->format('Y-m-d');
        $monthYear = $input['month_year'];

        // 2 dieu kien de fillter month
        // 1: driverCourses.date thuoc input['month_year']: whereBetween('driverCourses.date', [$startOfMonth, $endOfMonth])
        // 2: course.ship_date thuoc input['month_year']: whereBetween('course.ship_date', [$startOfMonth, $endOfMonth])

        $drivers = Driver::where('type', 4)
                    ->with(['driverCourses' => function ($query) use($startOfMonth, $endOfMonth) {
                        $query->whereBetween('date', [$startOfMonth, $endOfMonth]);
                        $query->join('courses', 'driver_courses.course_id', '=', 'courses.id');
                        $query->whereBetween('courses.ship_date', [$startOfMonth, $endOfMonth]);
                        // dd($query->toSql());
                    }]);
                    // ->with(['cashOutStatistical' => function ($query) use($monthYear) {
                    //     $query->where('month_line', $monthYear);
                    // }]);

        $drivers = $drivers->get();

        $data = [];
        foreach ($drivers as $key => $value) {
            // drivers
            $data[$key]['id'] = $value->id;
            $data[$key]['type'] = $value->type;
            $data[$key]['driver_code'] = $value->driver_code;
            $data[$key]['driver_name'] = $value->driver_name;
            // driver_courses
            // $data[$key]['driver_course'] = $value->driverCourses;
            $sum = 0;
            if ($value->driverCourses->isEmpty()) {
                // empty
                $data[$key]['course_id'] = 0;
                $data[$key]['course_name'] = 0;
                $data[$key]['date'] = 0;
                $data[$key]['ship_date'] = 0;
                $data[$key]['associate_company_fee'] = 0;

            } else {
                // not empty
                foreach ($value->driverCourses as $k => $item) {
                    if (isset($item['associate_company_fee'])) {
                        $sum += $item['associate_company_fee'];
                    }
                    $data[$key]['course_id'] = $item->course_id;
                    $data[$key]['course_name'] = $item->course_name;
                    $data[$key]['date'] = $item->date;
                    $data[$key]['ship_date'] = $item->ship_date;
                    $data[$key]['associate_company_fee'] = $item->associate_company_fee;
                }
            }
            $data[$key]['payable_this_month'] = $sum;
        }

        $result = $data;

        return $result;
    }
}
