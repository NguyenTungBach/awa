<?php
/**
 * Created by VeHo.
 * Year: 2022-07-28
 */

namespace Repository;

use App\Models\DayOff;
use App\Models\Driver;
use App\Models\DriverCourse;
use App\Models\Grade;
use App\Models\User;
use App\Repositories\Contracts\DayOffRepositoryInterface;
use App\Repositories\Contracts\DriverRepositoryInterface;
use Helper\Common;
use Helper\ResponseService;
use Illuminate\Http\Response;
use Repository\BaseRepository;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;


class DriverRepository extends BaseRepository implements DriverRepositoryInterface, DayOffRepositoryInterface
{

    public function __construct(Application $app, DayOffRepositoryInterface $repositoryDayOff)
    {
        parent::__construct($app);
        $this->repositoryDayOff = $repositoryDayOff;
    }

    /**
     * Instantiate model
     *
     * @return string
     */

    public function model()
    {
        return Driver::class;
    }

    /** get list driver
     * @param $request
     * @return array|mixed|null
     */
    public function listDriver($request)
    {
        $sortby = isset($request['sortby']) ? $request['sortby'] : null;
        $field = isset($request['field']) ? $request['field'] : null;
        $checkValidateParamIndex = $this->checkValidateParamIndex($sortby, $field);
        if ($checkValidateParamIndex['status'] != 'success') {
            return ResponseService::responseData($checkValidateParamIndex['code'], $checkValidateParamIndex['status'], $checkValidateParamIndex['message']);
        }
        $listDriverOn = Driver::select('id', 'driver_code', 'driver_name', 'status', 'start_date', 'end_date', 'created_at', 'updated_at')->where(Driver::DRIVER_STATUS,'on');
        if ($sortby && $field) {
            if($field =='driver_code'){
                $listDriverOn = $listDriverOn->orderByRaw("CONVERT(driver_code, SIGNED) $sortby");
            }else{
                $listDriverOn = $listDriverOn->orderBy($field, $sortby);
            }
        }
        $listDriverOn = $listDriverOn->get()->toArray();
        $listDriverOff = Driver::select('id', 'driver_code', 'driver_name', 'status', 'start_date', 'end_date', 'created_at', 'updated_at')->where(Driver::DRIVER_STATUS,'off');
        if ($sortby && $field) {
            if($field =='driver_code'){
                $listDriverOff = $listDriverOff->orderByRaw("CONVERT(driver_code, SIGNED) $sortby");
            }else{
                $listDriverOff = $listDriverOff->orderBy($field, $sortby);
            }
        }
        $listDriverOff = $listDriverOff->orderBy('driver_code')->get()->toArray();
        $listDriver = array_merge($listDriverOn,$listDriverOff);
        return ResponseService::responseData(Response::HTTP_OK, 'success', 'success', $listDriver);
    }

    /**
     * @param array $attributes
     * $attributes = ['flag' => "",driver_code => "",driver_name=> "" , start_date =>"",end_date=> "",
     * birth_day = >"",working_day=>"",
     * day_of_week=>"",working_time=>"",note=>"note"];
     * @return mixed|void
     */

    public function editOrCreate(array $attributes)
    {
        $flag = $attributes['flag'];
        $driverCode = $attributes['driver_code'];
        $driverName = $attributes['driver_name'];
        $grade = $attributes['grade'];
        $startDate = $attributes['start_date'];
        $birthDay = $attributes['birth_day'];
        $endDate = $attributes['end_date'] ?? null;
        $workingDay = $attributes['working_day'] ?? null;
        $dayOfWeek = $attributes['day_of_week'] ?? null;
        $note = $attributes['note'] ?? null;

        $status = strtotime($attributes['end_date']) && strtotime($attributes['end_date']) < strtotime(date('Y-m-d')) ? Driver::DRIVER_STATUS_OFF: Driver::DRIVER_STATUS_WORK;

        $model = Driver::updateOrCreate(
            ['driver_code' => $driverCode],
            [
                'flag' => $flag,
                'driver_code' => $driverCode,
                'driver_name' => $driverName,
                'grade' => $grade,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'birth_day' => $birthDay,
                'working_day' => $workingDay,
                'day_of_week' => $dayOfWeek,
                'note' => $note,
                'status' => $status
            ]
        );
        if (!$model) {
            return ResponseService::responseData(CODE_CREATE_FAILED, 'error', trans('errors.data.create'));
        }
        Grade::updateOrCreate([
            'date' => date('Y-m-d'),
            'driver_code' => $model->driver_code,
        ],[
            'date' => date('Y-m-d'),
            'driver_code' => $model->driver_code,
            'grade' => $model->grade
        ]);

        return ResponseService::responseData(\Illuminate\Http\Response::HTTP_OK, 'success', 'success', $model);
    }

    public function create(array $attributes)
    {
        $driver = $this->editOrCreate($attributes);
        if ($driver['data']->day_of_week) {
            $driverDayOff = $this->repositoryDayOff->createDayOff($driver['data']);
        }
        return ResponseService::responseData($driver['code'], $driver['status'], $driver['message'], $driver['data']);
    }

    /**
     * @param array $attributes
     * $attributes = ['flag' => "",driver_name=> "" , start_date =>"",end_date=> "",
     * birth_day = >"",working_day=>"",
     * day_of_week=>"",working_time=>"",note=>"note"];
     * @return mixed|void
     */
    public function update(array $attributes, $id)
    {
        $driver = Driver::where('id', $id)->first();
        if (!$driver) return ResponseService::responseData(\Illuminate\Http\Response::HTTP_UNPROCESSABLE_ENTITY, 'error', trans('errors.data_not_found'));
        $attributes['driver_code'] = $driver->driver_code;
        $updateDriver = $this->editOrCreate($attributes);
        if ($updateDriver['data']->status == Driver::DRIVER_STATUS_WORK){
            $driverDayOff = $this->repositoryDayOff->updateDayOff($updateDriver['data']);
        }
        return ResponseService::responseData($updateDriver['code'], $updateDriver['status'], $updateDriver['message'], $updateDriver['data']);

    }

    /** get detail driver
     * @param $id
     * @return array|mixed|null
     */
    public function getOne($id)
    {
        $driver = $this->model->find($id);
        if (!$driver) return ResponseService::responseData(Response::HTTP_METHOD_NOT_ALLOWED, 'error', trans('errors.data_not_found'));
        return ResponseService::responseData(Response::HTTP_OK, 'success', 'success', $driver);
    }

    /**
     * @param $id
     * @return array|mixed|null
     */
    public function destroy($id)
    {
        try {
            $driver = $this->model->find($id);
            if (!$driver) return ResponseService::responseData(Response::HTTP_METHOD_NOT_ALLOWED, 'error', trans('errors.data_not_found'));
            // delete dayoff
            DayOff::where('driver_code',$driver->driver_code)->delete();
            // delete driver course
            DriverCourse::where('driver_code',$driver->driver_code)->delete();

            Grade::where('driver_code', $driver->driver_code)->delete();

            $driver->delete();
        }catch (\Exception $exception){
            return ResponseService::responseData(Response::HTTP_METHOD_NOT_ALLOWED, 'error', trans('errors.data_not_found'));
        }
//        $endDate = Carbon::now()->toDateString();
//        if ($driver->end_date) {
//            return ResponseService::responseData(Response::HTTP_OK, 'success', trans('messages.mes.delete_success'));
//        }
//        $checkValidateParamUpdate = $this->checkValidateParamUpdate($driver, null, null, $endDate);
//        if ($checkValidateParamUpdate['status'] != 'success') {
//            return ResponseService::responseData($checkValidateParamUpdate['code'], $checkValidateParamUpdate['status'], $checkValidateParamUpdate['message']);
//        }
//        $updateDriver = Driver::updateDriver($driver, null, null, null, null, $endDate);
        return ResponseService::responseData(Response::HTTP_OK, 'success', trans('messages.mes.delete_success'));
    }


    private function checkValidateParamIndex($sortby, $field)
    {
        $arrayList = ['driver_code', 'driver_name', 'status'];
        $arraySortby = ['asc', 'desc'];
        if ($field && !in_array($field, $arrayList)) return ResponseService::responseData(Response::HTTP_UNPROCESSABLE_ENTITY, 'error', trans('errors.sort_by.index', $arrayList));
        if ($sortby && !in_array($sortby, $arraySortby)) return ResponseService::responseData(Response::HTTP_UNPROCESSABLE_ENTITY, 'error', trans('errors.sort_by.index', $arraySortby));
        return ResponseService::responseData(Response::HTTP_OK, 'success', '');
    }

//    private function checkUpdateDayOff(Driver $driver, $dayOfWeek, $endDate)
//    {
//        $flagCheck = true;
//        $dayOfWeekInDriver = $driver->day_of_week;
//        if (strcmp($dayOfWeek, $dayOfWeekInDriver) != 0) {
//            $flagCheck = false;
//            return $flagCheck;
//        }
//        return $flagCheck;
//    }


}
