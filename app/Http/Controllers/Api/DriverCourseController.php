<?php
/**
 * Created by VeHo.
 * Year: 2022-07-29
 */

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\DriverCourseRequest;
use App\Http\Resources\UserResource;
use App\Models\Course;
use App\Models\Customer;
use App\Models\Driver;
use App\Models\DriverCourse;
use App\Repositories\Contracts\DriverCourseRepositoryInterface;
use App\Http\Resources\BaseResource;
use App\Http\Resources\DriverCourseResource;
use Helper\ResponseService;
use Http\Client\Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class DriverCourseController extends Controller
{

    /**
     * var Repository
     */
    protected $repository;

    public function __construct(DriverCourseRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @OA\Get(
     *   path="/api/driver-course",
     *   tags={"DriverCourse"},
     *   summary="List DriverCourse",
     *   operationId="driver_course_index",
     *   @OA\Response(
     *     response=200,
     *     description="Send request success",
     *     @OA\MediaType(
     *      mediaType="application/json",
     *      example={"code":200,"data":{{"id": 1,"name": "..........."}}}
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="field",
     *     description = "drivers.driver_code,drivers.type,drivers.driver_name",
     *     example = "drivers.driver_code",
     *     in="path",
     *     required=true,
     *     @OA\Schema(
     *      type="string",
     *     ),
     *   ),
     *   @OA\Parameter(
     *     name="sortby",
     *     description = "asc,desc",
     *     example = "desc",
     *     in="path",
     *     required=true,
     *     @OA\Schema(
     *      type="string",
     *     ),
     *   ),
     *   @OA\Parameter(
     *     name="month_year",
     *     description = "Y-m",
     *     example = "2023-07",
     *     in="path",
     *     required=true,
     *     @OA\Schema(
     *      type="string",
     *     ),
     *   ),
     *   @OA\Parameter(
     *     name="closing_date",
     *     description = "24,25",
     *     example = "25",
     *     in="path",
     *     @OA\Schema(
     *      type="integer",
     *     ),
     *   ),
     *   @OA\Response(
     *     response=401,
     *     description="Login false",
     *     @OA\MediaType(
     *      mediaType="application/json",
     *      example={"code":401,"message":"Username or password invalid"}
     *     )
     *   ),
     *   security={{"auth": {}}},
     * )
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(DriverCourseRequest $request)
    {
        $field = isset($request['field']) ? $request['field'] : null;
        $sortby = isset($request['sortby']) ? $request['sortby'] : null;

        $arraySortby = ['asc', 'desc'];

        if (!$field && $sortby){
            return ResponseService::responseData(Response::HTTP_UNPROCESSABLE_ENTITY, 'error', trans('errors.sort_by.index', $arraySortby));
        }

        $datas = $this->repository->getAll($request);
        return ResponseService::responseData(Response::HTTP_OK, 'success', 'success', $datas);;
    }

//    /**
//     * @OA\Post(
//     *   path="/api/driver-course",
//     *   tags={"DriverCourse"},
//     *   summary="Add new DriverCourse",
//     *   operationId="driver_course_create",
//     *   @OA\RequestBody(
//     *       @OA\MediaType(
//     *          mediaType="application/json",
//     *          example={
//     *              "driver_id": "1",
//     *              "items" : {
//     *                  {
//     *                      "course_id": 4,
//     *                      "date": "2023-07-25",
//     *                      "start_time": "08:00",
//     *                      "end_time": "18:00",
//     *                      "break_time": "12:00"
//     *                  },
//     *                  {
//     *                      "course_id": 2,
//     *                      "date": "2023-07-25",
//     *                      "start_time": "08:00",
//     *                      "end_time": "18:00",
//     *                      "break_time": "12:00"
//     *                  },
//     *              }
//     *          },
//     *          @OA\Schema(
//     *            required={"driver_id","items"},
//     *            @OA\Property(
//     *              property="driver_id",
//     *              type="integer",
//     *            ),
//     *            @OA\Property(
//     *              property="items",
//     *              type="array",
//     *              @OA\Items(
//     *                  required={"course_id","date","start_time","end_time","break_time"},
//     *                  @OA\Property(property="course_id", type="interger" ),
//     *                  @OA\Property(property="date", type="string",example="Y-m-d" ),
//     *                  @OA\Property(property="start_time", type="string",description="H:i" ),
//     *                  @OA\Property(property="end_time", type="string",description="H:i" ),
//     *                  @OA\Property(property="break_time", type="string",description="H:i" ),
//     *               )
//     *            ),
//     *         )
//     *      )
//     *   ),
//     *     @OA\Response(
//     *     response=200,
//     *     description="Send request success",
//     *     @OA\MediaType(
//     *      mediaType="application/json",
//     *      example={"code":200,"data":{"id": 1,"name": "......"}}
//     *     )
//     *   ),
//     *   security={{"auth": {}}},
//     * )
//     * @return \Illuminate\Http\JsonResponse
//     * @throws \Exception
//     */
//    public function store(DriverCourseRequest $request)
//    {
//        try {
//            $request->merge(['status' => 1]);
//            return $this->repository->create($request->all());
//        } catch (\Exception $e) {
//            throw $e;
//        }
//    }

    /**
     * @OA\Get(
     *   path="/api/driver-course/{driver_id}",
     *   tags={"DriverCourse"},
     *   summary="Detail DriverCourse",
     *   operationId="driver_course_detail",
     *   @OA\Response(
     *     response=200,
     *     description="Send request success",
     *     @OA\MediaType(
     *      mediaType="application/json",
     *      example={"code":200,"data":{{"id": 1,"name": "..........."}}}
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="date",
     *     description = "Y-m-d",
     *     example = "2023-07-23",
     *     in="path",
     *     required=true,
     *     @OA\Schema(
     *      type="string",
     *     ),
     *   ),
     *   @OA\Response(
     *     response=401,
     *     description="Login false",
     *     @OA\MediaType(
     *      mediaType="application/json",
     *      example={"code":401,"message":"Username or password invalid"}
     *     )
     *   ),
     *   security={{"auth": {}}},
     * )
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($driver_id,DriverCourseRequest $request)
    {
        return $this->repository->getDetalDriverCourse($driver_id,$request);
    }

    /**
     * @OA\Post(
     *   path="/api/driver-course/update-course",
     *   tags={"DriverCourse"},
     *   summary="Update DriverCourse",
     *   operationId="driver_course_update",
     *   @OA\Response(
     *     response=200,
     *     description="Send request success",
     *     @OA\MediaType(
     *      mediaType="application/json",
     *          example={
     *              "items" : {
     *                  {
     *                      "id": 1,
     *                      "driver_id": 1,
     *                      "course_id": 6,
     *                      "date": "2023-07-28",
     *                      "start_time": "08:00",
     *                      "end_time": "18:00",
     *                      "break_time": "12:00"
     *                  },
     *                  {
     *                      "driver_id": 2,
     *                      "course_id": 7,
     *                      "date": "2023-07-24",
     *                      "start_time": "08:00",
     *                      "end_time": "18:00",
     *                      "break_time": "12:00"
     *                  },
     *              }
     *          },
     *          @OA\Schema(
     *            required={"items"},
     *            @OA\Property(
     *              property="items",
     *              type="array",
     *              @OA\Items(
     *                  required={"course_id","driver_id","course_id","start_time","end_time","break_time"},
     *                  @OA\Property(property="id", type="interger" ),
     *                  @OA\Property(property="driver_id", type="interger" ),
     *                  @OA\Property(property="course_id", type="interger" ),
     *                  @OA\Property(property="date", type="string",example="Y-m-d" ),
     *                  @OA\Property(property="start_time", type="string",description="H:i" ),
     *                  @OA\Property(property="end_time", type="string",description="H:i" ),
     *                  @OA\Property(property="break_time", type="string",description="H:i" ),
     *               )
     *            ),
     *         )
     *     )
     *   ),
     *   @OA\Response(
     *     response=401,
     *     description="Login false",
     *     @OA\MediaType(
     *      mediaType="application/json",
     *      example={"code":401,"message":"Username or password invalid"}
     *     )
     *   ),
     *   security={{"auth": {}}},
     * )
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update_course(DriverCourseRequest $request)
    {
        return $this->repository->update_course($request->all());
    }


    /**
     * @OA\Get(
     *   path="/driver-course/total-extra-cost",
     *   tags={"DriverCourse"},
     *   summary="Total extra cost DriverCourse",
     *   operationId="driver_course_total_extra_cost",
     *   @OA\Response(
     *     response=200,
     *     description="Send request success",
     *     @OA\MediaType(
     *      mediaType="application/json",
     *      example={"code":200,"data":{{"id": 1,"name": "..........."}}}
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="closing_date",
     *     description = "24,25",
     *     example = "25",
     *     in="path",
     *     required=true,
     *     @OA\Schema(
     *      type="integer",
     *     ),
     *   ),
     *   @OA\Parameter(
     *     name="month_year",
     *     description = "Y-m",
     *     example = "2023-07",
     *     in="path",
     *     required=true,
     *     @OA\Schema(
     *      type="string",
     *     ),
     *   ),
     *   @OA\Response(
     *     response=401,
     *     description="Login false",
     *     @OA\MediaType(
     *      mediaType="application/json",
     *      example={"code":401,"message":"Username or password invalid"}
     *     )
     *   ),
     *   security={{"auth": {}}},
     * )
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function total_extra_cost(DriverCourseRequest $request)
    {
        $datas = $this->repository->totalOfExtraCost($request);
        return ResponseService::responseData(Response::HTTP_OK, 'success', 'success', $datas);
    }

    /**
     * @OA\Get(
     *   path="/driver-course/export-shift",
     *   tags={"DriverCourse"},
     *   summary="Export_shift DriverCourse",
     *   operationId="driver_course_export_shift",
     *   @OA\Response(
     *     response=200,
     *     description="Send request success",
     *     @OA\MediaType(
     *      mediaType="application/json",
     *      example={"code":200,"data":{{"id": 1,"name": "..........."}}}
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="closing_date",
     *     description = "24,25",
     *     example = "25",
     *     in="path",
     *     required=true,
     *     @OA\Schema(
     *      type="integer",
     *     ),
     *   ),
     *   @OA\Parameter(
     *     name="month_year",
     *     description = "Y-m",
     *     example = "2023-07",
     *     in="path",
     *     required=true,
     *     @OA\Schema(
     *      type="string",
     *     ),
     *   ),
     *   @OA\Parameter(
     *     name="field",
     *     description = "drivers.driver_code,drivers.type,drivers.driver_name",
     *     example = "drivers.driver_code",
     *     in="path",
     *     @OA\Schema(
     *      type="string",
     *     ),
     *   ),
     *   @OA\Parameter(
     *     name="sortby",
     *     description = "asc,desc",
     *     example = "desc",
     *     in="path",
     *     required=true,
     *     @OA\Schema(
     *      type="string",
     *     ),
     *   ),
     *   @OA\Response(
     *     response=401,
     *     description="Login false",
     *     @OA\MediaType(
     *      mediaType="application/json",
     *      example={"code":401,"message":"Username or password invalid"}
     *     )
     *   ),
     *   security={{"auth": {}}},
     * )
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function export_shift(DriverCourseRequest $request)
    {
        $this->repository->export_shift($request);
        return $this->responseJson(200, null, trans('messages.mes.export_success'));
    }

    /**
     * @OA\Get(
     *   path="/api/driver-course/get-all-express-charge",
     *   tags={"DriverCourse"},
     *   summary="List Express Charge",
     *   operationId="driver_course_express_charge",
     *   @OA\Response(
     *     response=200,
     *     description="Send request success",
     *     @OA\MediaType(
     *      mediaType="application/json",
     *      example={"code":200,"data":{{"id": 1,"name": "..........."}}}
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="month_year",
     *     description = "Y-m",
     *     example = "2023-07",
     *     in="path",
     *     required=true,
     *     @OA\Schema(
     *      type="string",
     *     ),
     *   ),
     *   @OA\Parameter(
     *     name="field",
     *     description = "customers.customer_code,customers.closing_date,customers.customer_name",
     *     example = "customers.customer_code",
     *     in="path",
     *     @OA\Schema(
     *      type="string",
     *     ),
     *   ),
     *   @OA\Parameter(
     *     name="sortby",
     *     description = "asc,desc",
     *     example = "desc",
     *     in="path",
     *     required=true,
     *     @OA\Schema(
     *      type="string",
     *     ),
     *   ),
     *   @OA\Response(
     *     response=401,
     *     description="Login false",
     *     @OA\MediaType(
     *      mediaType="application/json",
     *      example={"code":401,"message":"Username or password invalid"}
     *     )
     *   ),
     *   security={{"auth": {}}},
     * )
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function get_all_express_charge(DriverCourseRequest $request)
    {
        $field = isset($request['field']) ? $request['field'] : null;
        $sortby = isset($request['sortby']) ? $request['sortby'] : null;

        $arraySortby = ['asc', 'desc'];

        if (!$field && $sortby){
            return ResponseService::responseData(Response::HTTP_UNPROCESSABLE_ENTITY, 'error', trans('errors.sort_by.index', $arraySortby));
        }

        $datas = $this->repository->getAllExpressCharge($request);
        return ResponseService::responseData(Response::HTTP_OK, 'success', 'success', $datas);;
    }

    /**
     * @OA\Get(
     *   path="/driver-course/total-express-charge-cost",
     *   tags={"DriverCourse"},
     *   summary="Total express charge cost DriverCourse",
     *   operationId="driver_course_total_express_charge_cost",
     *   @OA\Response(
     *     response=200,
     *     description="Send request success",
     *     @OA\MediaType(
     *      mediaType="application/json",
     *      example={"code":200,"data":{{"id": 1,"name": "..........."}}}
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="month_year",
     *     description = "Y-m",
     *     example = "2023-07",
     *     in="path",
     *     required=true,
     *     @OA\Schema(
     *      type="string",
     *     ),
     *   ),
     *   @OA\Parameter(
     *     name="field",
     *     description = "customers.customer_code,customers.closing_date,customers.customer_name",
     *     example = "customers.customer_code",
     *     in="path",
     *     @OA\Schema(
     *      type="string",
     *     ),
     *   ),
     *   @OA\Parameter(
     *     name="sortby",
     *     description = "asc,desc",
     *     example = "desc",
     *     in="path",
     *     required=true,
     *     @OA\Schema(
     *      type="string",
     *     ),
     *   ),
     *   @OA\Response(
     *     response=401,
     *     description="Login false",
     *     @OA\MediaType(
     *      mediaType="application/json",
     *      example={"code":401,"message":"Username or password invalid"}
     *     )
     *   ),
     *   security={{"auth": {}}},
     * )
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function total_express_charge_cost(DriverCourseRequest $request)
    {
        $datas = $this->repository->totalOfExpressChargeCost($request);
        return ResponseService::responseData(Response::HTTP_OK, 'success', 'success', $datas);
    }

    /**
     * @OA\Get(
     *   path="/driver-course/export-shift-express-charge",
     *   tags={"DriverCourse"},
     *   summary="Export_shift_express_charge DriverCourse",
     *   operationId="driver_course_export_shift_express_charge",
     *   @OA\Response(
     *     response=200,
     *     description="Send request success",
     *     @OA\MediaType(
     *      mediaType="application/json",
     *      example={"code":200,"data":{{"id": 1,"name": "..........."}}}
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="month_year",
     *     description = "Y-m",
     *     example = "2023-07",
     *     in="path",
     *     required=true,
     *     @OA\Schema(
     *      type="string",
     *     ),
     *   ),
     *   @OA\Parameter(
     *     name="field",
     *     description = "customers.customer_code,customers.closing_date,customers.customer_name",
     *     example = "customers.customer_code",
     *     in="path",
     *     @OA\Schema(
     *      type="string",
     *     ),
     *   ),
     *   @OA\Parameter(
     *     name="sortby",
     *     description = "asc,desc",
     *     example = "desc",
     *     in="path",
     *     required=true,
     *     @OA\Schema(
     *      type="string",
     *     ),
     *   ),
     *   @OA\Response(
     *     response=401,
     *     description="Login false",
     *     @OA\MediaType(
     *      mediaType="application/json",
     *      example={"code":401,"message":"Username or password invalid"}
     *     )
     *   ),
     *   security={{"auth": {}}},
     * )
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function export_shift_express_charge(DriverCourseRequest $request)
    {
        $this->repository->export_shift_express_charge($request);
        return $this->responseJson(200, null, trans('messages.mes.export_success'));
    }

    /**
     * @OA\Delete(
     *   path="/driver-course/{id}",
     *   tags={"DriverCourse"},
     *   summary="Delete DriverCourse",
     *   operationId="driver_course_delete",
     *   @OA\Parameter(
     *      name="id",
     *      in="path",
     *      required=true,
     *     @OA\Schema(
     *      type="string",
     *     ),
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Send request success",
     *     @OA\MediaType(
     *      mediaType="application/json",
     *      example={"code":200,"data":"Send request success"}
     *     )
     *   ),
     *   security={{"auth": {}}},
     * )
     * @param int $id
     * @return int
     * @throws \Exception
     */
    public function destroy($id)
    {
        return $this->repository->delete($id);
    }

    /**
     * @OA\Get(
     *   path="/api/driver-course/salesList",
     *   tags={"DriverCourse"},
     *   summary="Sales List",
     *   operationId="sales_list_index",
     *   @OA\Response(
     *     response=200,
     *     description="Send request success",
     *     @OA\MediaType(
     *      mediaType="application/json",
     *      example={"code":200,"data":{{"id": 1,"name": "..........."}}}
     *     )
     *   ),
     *  @OA\Parameter(
     *     name="field",
     *     description = "customers.customer_code,customers.type,customers.customer_name",
     *     in="path",
     *     required=true,
     *     @OA\Schema(
     *     type="string",
     *     ),
     *     ),
     *  @OA\Parameter(
     *     name="sortby",
     *     description = "asc,desc",
     *     in="path",
     *     required=true,
     *     @OA\Schema(
     *     type="string",
     *     ),
     *     ),
     *  @OA\Parameter(
     *     name="month_year",
     *     description = "Y-m",
     *     in="path",
     *     required=true,
     *     @OA\Schema(
     *     type="string",
     *     ),
     *     ),
     *   @OA\Response(
     *     response=401,
     *     description="Login false",
     *     @OA\MediaType(
     *      mediaType="application/json",
     *      example={"code":401,"message":"Username or password invalid"}
     *     )
     *   ),
     *   security={{"auth": {}}},
     * )
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function salesList(DriverCourseRequest $request)
    {
        $data = $this->repository->salesList($request);
        return ResponseService::responseJson(200, new BaseResource($data));
    }

    /**
     * @OA\Get(
     *   path="/api/driver-course/export-sales-list",
     *   tags={"DriverCourse"},
     *   summary="Export Sales List",
     *   operationId="export_sales_list",
     *   @OA\Response(
     *     response=200,
     *     description="Send request success",
     *     @OA\MediaType(
     *      mediaType="application/json",
     *      example={"code":200,"data":{{"id": 1,"name": "..........."}}}
     *     )
     *   ),
     *  @OA\Parameter(
     *     name="field",
     *     description = "customers.customer_code,customers.type,customers.customer_name",
     *     in="path",
     *     required=true,
     *     @OA\Schema(
     *     type="string",
     *     ),
     *     ),
     *  @OA\Parameter(
     *     name="sortby",
     *     description = "asc,desc",
     *     in="path",
     *     required=true,
     *     @OA\Schema(
     *     type="string",
     *     ),
     *     ),
     *  @OA\Parameter(
     *     name="month_year",
     *     description = "Y-m",
     *     in="path",
     *     required=true,
     *     @OA\Schema(
     *     type="string",
     *     ),
     *     ),
     *   @OA\Response(
     *     response=401,
     *     description="Login false",
     *     @OA\MediaType(
     *      mediaType="application/json",
     *      example={"code":401,"message":"Username or password invalid"}
     *     )
     *   ),
     *   security={{"auth": {}}},
     * )
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function exportSalesList(DriverCourseRequest $request)
    {
        $this->repository->exportSaleList($request);
        return $this->responseJson(200, null, trans('messages.mes.export_success'));
    }

    /**
     * @OA\Get(
     *   path="/api/driver-course/sales-detail/{id}",
     *   tags={"DriverCourse"},
     *   summary="Sales Detail",
     *   operationId="sales_detail_index",
     *   @OA\Response(
     *     response=200,
     *     description="Send request success",
     *     @OA\MediaType(
     *      mediaType="application/json",
     *      example={"code":200,"data":{{"id": 1,"name": "..........."}}}
     *     )
     *   ),
     *  @OA\Parameter(
     *     name="month_year",
     *     description = "Y-m",
     *     in="path",
     *     required=true,
     *     @OA\Schema(
     *     type="string",
     *     ),
     *     ),
     *   @OA\Response(
     *     response=401,
     *     description="Login false",
     *     @OA\MediaType(
     *      mediaType="application/json",
     *      example={"code":401,"message":"Username or password invalid"}
     *     )
     *   ),
     *   security={{"auth": {}}},
     * )
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function salesDetail(DriverCourseRequest $request,$id)
    {
        // Kiểm tra customer có tồn tại không
        $customer = Customer::find($id);
        if ($customer == null){
            return $this->responseJsonError(Response::HTTP_UNPROCESSABLE_ENTITY, trans('errors.attribute_not_found',[
                "attribute"=> "customer",
                "id"=> $id
            ]));
        }

        $data = $this->repository->saleDetailByClosingDate($request,$id);
        return ResponseService::responseJson(200, new BaseResource($data));
    }

    /**
     * @OA\Get(
     *   path="/api/driver-course/export-sale-detail-pdf/{id}",
     *   tags={"DriverCourse"},
     *   summary="Sales Detail PDF",
     *   operationId="sales_detail_PDF",
     *   @OA\Response(
     *     response=200,
     *     description="Send request success",
     *     @OA\MediaType(
     *      mediaType="application/json",
     *      example={"code":200,"data":{{"id": 1,"name": "..........."}}}
     *     )
     *   ),
     *  @OA\Parameter(
     *     name="month_year",
     *     description = "Y-m",
     *     in="path",
     *     required=true,
     *     @OA\Schema(
     *     type="string",
     *     ),
     *     ),
     *   @OA\Response(
     *     response=401,
     *     description="Login false",
     *     @OA\MediaType(
     *      mediaType="application/json",
     *      example={"code":401,"message":"Username or password invalid"}
     *     )
     *   ),
     *   security={{"auth": {}}},
     * )
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function exportSalesDetailPDF(DriverCourseRequest $request,$id)
    {
        return $this->repository->exportSalesDetailPDF($request,$id);
    }
}
