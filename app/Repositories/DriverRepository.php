<?php
/**
 * Created by VeHo.
 * Year: 2022-07-28
 */

namespace Repository;

use App\Http\Resources\BaseResource;
use App\Models\Course;
use App\Models\Customer;
use App\Models\Driver;
use App\Models\DriverCourse;
use App\Models\User;
use App\Repositories\Contracts\DriverRepositoryInterface;
use Helper\Common;
use Helper\ResponseService;
use Illuminate\Http\Response;
use Repository\BaseRepository;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;


class DriverRepository extends BaseRepository implements DriverRepositoryInterface
{

    public function __construct(Application $app)
    {
        parent::__construct($app);
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
//        $field = isset($request['field']) ? $request['field'] : null;
//        $checkValidateParamIndex = $this->checkValidateParamIndex($sortby, $field);
//        if ($checkValidateParamIndex['status'] != 'success') {
//            return ResponseService::responseData($checkValidateParamIndex['code'], $checkValidateParamIndex['status'], $checkValidateParamIndex['message']);
//        }
//        $listDriverOn = Driver::select('id', 'driver_code', 'driver_name', 'status', 'start_date', 'end_date', 'created_at', 'updated_at')->where(Driver::DRIVER_STATUS,'on');
//        if ($sortby && $field) {
//            if($field =='driver_code'){
//                $listDriverOn = $listDriverOn->orderByRaw("CONVERT(driver_code, SIGNED) $sortby");
//            }else{
//                $listDriverOn = $listDriverOn->orderBy($field, $sortby);
//            }
//        }
//        $listDriverOn = $listDriverOn->get()->toArray();
//        $listDriverOff = Driver::select('id', 'driver_code', 'driver_name', 'status', 'start_date', 'end_date', 'created_at', 'updated_at')->where(Driver::DRIVER_STATUS,'off');
//        if ($sortby && $field) {
//            if($field =='driver_code'){
//                $listDriverOff = $listDriverOff->orderByRaw("CONVERT(driver_code, SIGNED) $sortby");
//            }else{
//                $listDriverOff = $listDriverOff->orderBy($field, $sortby);
//            }
//        }
//        $listDriverOff = $listDriverOff->orderBy('driver_code')->get()->toArray();
//        $listDriver = array_merge($listDriverOn,$listDriverOff);
        $field = isset($request['field']) ? $request['field'] : null;
        $sortby = isset($request['sortby']) ? $request['sortby'] : null;

        $arrayList = ['driver_code', 'driver_name', 'type'];
        $arraySortby = ['asc', 'desc'];

        if ($field && !in_array($field, $arrayList)) {
            return ResponseService::responseData(Response::HTTP_UNPROCESSABLE_ENTITY, 'error', trans('errors.field.index', $arrayList));
        }
        if ($sortby && !in_array($sortby, $arraySortby)){
            return ResponseService::responseData(Response::HTTP_UNPROCESSABLE_ENTITY, 'error', trans('errors.sort_by.index', $arraySortby));
        }
        if (!$field && $sortby){
            return ResponseService::responseData(Response::HTTP_UNPROCESSABLE_ENTITY, 'error', trans('errors.sort_by.index', $arraySortby));
        }

        $listDriverNotRetirement = $this->model->query()
            ->whereNull('end_date')
            ->whereNull('deleted_at')
            ->SortByForDriver($request)
            ->get();

        $listDriverRetirement = $this->model->query()
            ->whereNotNull('end_date')
            ->whereNull('deleted_at')
            ->get();

        $data = [];
        if (count($listDriverRetirement) != 0){
            $data = $listDriverNotRetirement->concat($listDriverRetirement)->filter(function ($driver) {
                $driver->checkEnd_date = $driver->end_date !== null;
                if ($driver->end_date !== null){
                    $driver->end_date = explode(" ",$driver->end_date)[0];
                }
                switch ($driver->type){
                    case 1:
                        $driver->typeName = trans('drivers.type.1');
                        break;
                    case 2:
                        $driver->typeName = trans('drivers.type.2');
                        break;
                    case 3:
                        $driver->typeName = trans('drivers.type.3');
                        break;
                    case 4:
                        $driver->typeName = trans('drivers.type.4');
                        break;
                }
                return $driver;
            });
        } else{
            $data = $listDriverNotRetirement->filter(function ($driver) {
                $driver->checkEnd_date = $driver->end_date !== null;
                if ($driver->end_date !== null){
                    $driver->end_date = explode(" ",$driver->end_date)[0];
                }
                switch ($driver->type){
                    case 1:
                        $driver->typeName = trans('drivers.type.1');
                        break;
                    case 2:
                        $driver->typeName = trans('drivers.type.2');
                        break;
                    case 3:
                        $driver->typeName = trans('drivers.type.3');
                        break;
                    case 4:
                        $driver->typeName = trans('drivers.type.4');
                        break;
                }
                return $driver;
            });
        }

        return ResponseService::responseJson(200, new BaseResource($data));
    }


    /**
     * @param $id
     * @return array|mixed|null
     */
    public function destroy($id)
    {
        try {
            $driver = $this->model->find($id);
            // Kiểm tra xem driver có tồn tại không
            if (!$driver) return ResponseService::responseJsonError(Response::HTTP_UNPROCESSABLE_ENTITY, trans('errors.data_not_found'));
            // Kiểm tra xem driver này đã có chuyến giao hàng nào chưa nếu có thì không được xóa
            $driver_courses = DriverCourse::where("driver_id",$driver->id)->first();
            if ($driver_courses != null){
                $course = Course::find($driver_courses->course_id);
                return ResponseService::responseJsonError(Response::HTTP_UNPROCESSABLE_ENTITY,
                    trans("errors.driver_can_not_delete" ,[
                        "driver_id"=> $driver->id,
                        "driver_name"=> $driver->driver_name,
                        "course_id"=> $course->id,
                        "course_name"=> $course->course_name,
                    ]));
            } else{
                $driver->delete();
            }
        }catch (\Exception $exception){
            return ResponseService::responseJsonError(Response::HTTP_METHOD_NOT_ALLOWED, $exception->getMessage(), trans('errors.data_not_found'));
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
