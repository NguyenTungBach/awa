<?php
/**
 * Created by VeHo.
 * Year: 2022-07-28
 */

namespace Repository;

use App\Http\Resources\BaseResource;
use App\Http\Resources\DriverResource;
use App\Models\Course;
use App\Models\Customer;
use App\Models\Driver;
use App\Models\DriverCourse;
use App\Models\FinalClosingHistories;
use App\Models\User;
use App\Repositories\Contracts\DriverRepositoryInterface;
use Helper\Common;
use Helper\ResponseService;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
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

        $arrayList = ['driver_code', 'driver_name', 'typeName'];
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

        $now = Carbon::now()->format("Y-m-d");

        // Lấy ra tất cả các driver không nghỉ và những driver được báo nghỉ nhưng chưa đến
        $listDriverNotRetirement = Driver::query()
            ->whereNull('end_date') // chưa nghỉ
            ->orWhere("end_date",">",$now) // nhỏ hơn ngày nghỉ tức là vẫn trong tương lai và chưa đến
            ->SortByForDriver($request)
            ->get();

        // Lấy ra tất cả các driver đã nghỉ hoặc qua rồi
        $listDriverRetirement = Driver::query()
            ->whereNotNull('end_date')
            ->where("end_date","<=",$now) // nằm trong hoặc lớn hơn ngày nghỉ tức là đến ngày hoặc đã qua ngày nghỉ
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
    public function driver_for_course($request){
        // Lấy ra tất cả các driver không nghỉ và những driver được báo nghỉ nhưng chưa đến
        $data = Driver::query()
            ->whereNull('end_date') // chưa nghỉ
            ->orWhere("end_date",">",$request->ship_date) // nhỏ hơn ngày nghỉ tức là vẫn trong tương lai và chưa đến
            ->orderBy("created_at")
            ->get();

        return ResponseService::responseJson(200, new BaseResource($data));
    }

    public function createDriver($request)
    {
        // Kiểm tra trường hợp nếu start_date = null, thì kiểm tra created_at
        if ($request->start_date == null && $request->end_date !=null){
            // Nếu ngày end_date mà < ngày tạo thì không cho, tức là end_date là quá khứ
            $parse_created_at = Carbon::now();
            $parse_end_date = Carbon::parse($request->end_date);
            if ($parse_end_date->lt($parse_created_at)){
                return ResponseService::responseJsonError(Response::HTTP_METHOD_NOT_ALLOWED, trans('drivers.end_date_not_before_now'));
            }
        }

        //Kiểm tra driver_code này đã từng bị xóa hay chưa. Nếu có thì dùng luôn
        $checkDriverByDriverCode = Driver::where('driver_code',$request->driver_code)->whereNotNull('deleted_at')->withoutGlobalScopes()->first();
        if ($checkDriverByDriverCode){
            $checkDriverByDriverCode->type = $request->type;
            $checkDriverByDriverCode->driver_name = $request->driver_name;
            $checkDriverByDriverCode->start_date = $request->start_date;
            $checkDriverByDriverCode->end_date = $request->end_date;
            $checkDriverByDriverCode->car = $request->car;
            $checkDriverByDriverCode->note = $request->note;
            $checkDriverByDriverCode->status = $request->status;
            $checkDriverByDriverCode->created_at = Carbon::now();
            $checkDriverByDriverCode->updated_at = null;
            $checkDriverByDriverCode->deleted_at = null;
            $checkDriverByDriverCode->save();
            $data = $request->all();
        } else {
            $data = parent::create($request->all());
        }
        return $this->responseJson(200, new DriverResource($data)); // TODO: Change the autogenerated stub
    }

    public function updateDriver($request, $id)
    {
        $driver = Driver::find($id);
        $driver_start_date = Carbon::parse($driver->start_date)->format("Y-m-d");
        DB::beginTransaction();
        if ($request->start_date != $driver_start_date) {
            // lấy ra năm tháng cho start_date, trường hợp nếu start date null thì tìm theo closing date
            $getMonthYear = Carbon::parse($request->start_date == '' ? $request->start_date : $driver->created_at)->format('Y-m');
            // check final_closing
//            $final_closing = FinalClosingHistories::where('month_year', $getMonthYear);
//            if ($final_closing) {
//                return ResponseService::responseJsonError(Response::HTTP_METHOD_NOT_ALLOWED, trans('errors.final_closing_histories', ['attribute' => 'start_date']), trans('errors.final_closing_histories', ['attribute' => 'start_date']));
//            }
            // kiểm tra xem driver này đã có chuyến hàng nào chưa
            $driver_courses = DriverCourse::where("driver_id",$driver->id)->first();
            if ($driver_courses != null){
                return ResponseService::responseJsonError(Response::HTTP_UNPROCESSABLE_ENTITY,
                    trans("errors.driver_can_not_change_attribute_ship" ,[
                        "attribute"=> "start_date",
                    ]));
            }
        }

        // Kiểm tra trường hợp nếu start_date = null, thì kiểm tra created_at
        if ($request->start_date == null && $request->end_date !=null){
            // Nếu ngày end_date mà < ngày tạo thì không cho, tức là end_date là quá khứ
            $parse_created_at = Carbon::now();
            $parse_end_date = Carbon::parse($request->end_date);
            if ($parse_end_date->lt($parse_created_at)){
                return ResponseService::responseJsonError(Response::HTTP_METHOD_NOT_ALLOWED, trans('drivers.end_date_not_before_now'));
            }
        }
        try {
            $data = parent::update($request->except([]), $id);
            DB::commit();
        } catch (\Exception $ex){
            DB::rollBack();
        }
        return $this->responseJson(200, new BaseResource($data));
//        return parent::update($attributes, $id); // TODO: Change the autogenerated stub
    }

    public function listDriverIdsByFinal($input) {
        $driverIds = Driver::whereRaw('IF(start_date IS NOT NULL, DATE_FORMAT(start_date, "%Y-%m") <= ?, DATE_FORMAT(created_at, "%Y-%m") <= ?)', [$input['month_year'], $input['month_year']])
                    ->where(function ($query) use ($input) {
                        $query->whereNull('end_date')
                            ->orWhereRaw('DATE_FORMAT(end_date, "%Y-%m") >= ?', [$input['month_year']]);
                    })->get()->pluck('id')->toArray();

        return $driverIds;
    }
}
