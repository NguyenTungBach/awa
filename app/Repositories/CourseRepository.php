<?php
/**
 * Created by VeHo.
 * Year: 2022-07-28
 */

namespace Repository;

use App\Models\Course;
use App\Repositories\Contracts\CourseRepositoryInterface;
use Helper\Common;
use Helper\ResponseService;
use Illuminate\Http\Response;
use Repository\BaseRepository;
use Illuminate\Foundation\Application;
use Carbon\Carbon;
use Illuminate\Support\Arr;

class CourseRepository extends BaseRepository implements CourseRepositoryInterface
{
    public function __construct(Application $app)
    {
        parent::__construct($app);
    }

    /**
     * Instantiate model
     *
     * @param Course $model
     */

    public function model()
    {
        return Course::class;
    }

    public function createCourse($input)
    {
        $input['associate_company_fee'] = empty($input['associate_company_fee']) ? 0 : Arr::get($input, 'associate_company_fee', 0);
        $input['expressway_fee'] = empty($input['expressway_fee']) ? 0 : Arr::get($input, 'expressway_fee', 0);
        $input['commission'] = empty($input['commission']) ? 0 : Arr::get($input, 'commission', 0);
        $input['meal_fee'] = empty($input['meal_fee']) ? 0 : Arr::get($input, 'meal_fee', 0);
        $input['note'] = Arr::get($input, 'note', NULL);

        $course = Course::create([
            'customer_id' => $input['customer_id'],
            'course_name' => $input['course_name'],
            'ship_date' => $input['ship_date'],
            'start_date' => $input['start_date'],
            'end_date' => $input['end_date'],
            'break_time' => $input['break_time'],
            'departure_place' => $input['departure_place'],
            'arrival_place' => $input['arrival_place'],
            'ship_fee' => $input['ship_fee'],
            'associate_company_fee' => $input['associate_company_fee'],
            'expressway_fee' => $input['expressway_fee'],
            'commission' => $input['commission'],
            'meal_fee' => $input['meal_fee'],
            'note' => $input['note'],
        ]);

        return $course;
    }

    public function getAll($input)
    {
        $input['start_date_ship'] = Arr::get($input, 'start_date_ship', NULL);
        $input['end_date_ship'] = Arr::get($input, 'end_date_ship', NULL);
        $input['customer_id'] = Arr::get($input, 'customer_id', NULL);
        $input['order_by'] = Arr::get($input, 'order_by', 'id');
        $input['sort_by'] = Arr::get($input, 'sort_by', 'desc');

        $data = [];
        $courses = Course::with('customer');
        if (!empty($input['start_date_ship']) && !empty($input['end_date_ship'])) {
            $courses = $courses->whereBetween('ship_date', [$input['start_date_ship'], $input['end_date_ship']]);
        }
        if (empty($input['start_date_ship']) && !empty($input['end_date_ship'])) {
            $courses = $courses->where('ship_date', '<=',$input['end_date_ship']);
        }
        if (!empty($input['start_date_ship']) && empty($input['end_date_ship'])) {
            $courses = $courses->where('ship_date', '>=', $input['start_date_ship']);
        }
        if (!empty($input['customer_id'])) {
            $courses = $courses->where('customer_id', $input['customer_id']);
        }

        $courses = $courses->orderBy($input['order_by'], $input['sort_by']);
        $courses = $courses->get();
        foreach ($courses as $key => $value) {
            $data[$key]['id'] = $value->id;
            $data[$key]['ship_date'] = $value->ship_date;
            $data[$key]['course_name'] = $value->course_name;
            $data[$key]['customer_name'] = empty($value->customer) ? '' : $value->customer->customer_name;
            $data[$key]['departure_place'] = $value->departure_place;
            $data[$key]['arrival_place'] = $value->arrival_place;
            $data[$key]['ship_fee'] = $value->ship_fee;
        }
        $result = $data;

        return $result ?? [];
    }

    public function getDetail($id)
    {
        $result = CourseRepository::find($id);
        $result['ship_date'] = date('Y年m月d日', strtotime($result->start_date));
        $result['start_date'] = date('H:i', strtotime($result->start_date));
        $result['end_date'] = date('H:i', strtotime($result->end_date));
        $result['break_time'] = date('H:i', strtotime($result->break_time));
        $result['ship_fee'] = number_format($result->ship_fee, 0, '.', ',');
        $result['associate_company_fee'] = number_format($result->associate_company_fee, 0, '.', ',');
        $result['expressway_fee'] = number_format($result->expressway_fee, 0, '.', ',');
        $result['commission'] = number_format($result->commission, 0, '.', ',');
        $result['meal_fee'] = number_format($result->meal_fee, 0, '.', ',');

        return $result;
    }

    public function updateCourse($input, $id)
    {
        $result = CourseRepository::update($input, $id);

        return $result;
    }
}
