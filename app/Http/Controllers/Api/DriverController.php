<?php
/**
 * Created by VeHo.
 * Year: 2022-07-28
 */

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\DriverRequest;
use App\Http\Resources\UserResource;
use App\Repositories\Contracts\DriverRepositoryInterface;
use App\Http\Resources\BaseResource;
use App\Http\Resources\DriverResource;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class DriverController extends Controller
{

    /**
     * var Repository
     */
    protected $repository;

    public function __construct(DriverRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @OA\Get(
     *   path="/api/driver",
     *   tags={"Driver"},
     *   summary="List Driver",
     *   operationId="driver_index",
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
     *     description = "driver_code,driver_name,status",
     *     in="query",
     *     required=false,
     *     @OA\Schema(
     *     type="string",
     *     ),
     *     ),
     *   @OA\Parameter(
     *     name="sortby",
     *     description = "asc,desc",
     *     in="query",
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
    public function index(DriverRequest $request)
    {
        $driver = $this->repository->listDriver($request);
        if ($driver['status'] != 'success') {
            return $this->responseJsonError($driver['code'], $driver['message']);
        }
        return $this->responseJson($driver['code'], isset($driver['data'])?$driver['data']:null);
    }

    /**
     * @OA\Post(
     *   path="/api/driver",
     *   tags={"Driver"},
     *   summary="Add new Driver",
     *   operationId="driver_create",
     *     * @OA\RequestBody(
     *       description="flag = 'lead|full|part' , day_of_week = ['mon' => 'Monday','tue'=>'Tuesday','wed'=> 'Wednesday','thu'=>'Thursday','fri'=>'Friday','sat'=>'Saturday ','sun'=>'Sunday'],working_time = ['daily' => 'over_15h_day','month'=>'over_ot_40h_month']",
     *       @OA\MediaType(
     *          mediaType="application/json",
     *          example={"flag": "lead|full|part","driver_code": "1111","driver_name": "tuanminh","start_date": "2022-08-15","end_date": "","brith_day" : "2020-12-03","working_day" : "2","day_of_week" : "mon,tue,wed"
     *     , "working_time": "daily,month" ,"note":""},
     *          @OA\Schema(
     *            required={"user_name"},
     *            @OA\Property(
     *              property="name",
     *              format="string",
     *            ),
     *         )
     *      )
     *   ),
     *
     *   @OA\Response(
     *     response=200,
     *     description="Send request success",
     *     @OA\MediaType(
     *      mediaType="application/json",
     *      example={"code":200,"data":{"id": 1,"name": "......"}}
     *     )
     *   ),
     *   security={{"auth": {}}},
     * )
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function store(DriverRequest $request)
    {
        $createDriver = $this->repository->create($request->all());
        if ($createDriver['status'] != 'success') {
            return $this->responseJsonError($createDriver['code'], $createDriver['message'], $createDriver['message']);
        }
        return $this->responseJson($createDriver['code'], new DriverResource($createDriver['data']), $createDriver['message']);
    }

    /**
     * @OA\Get(
     *   path="/api/driver/{id}",
     *   tags={"Driver"},
     *   summary="Detail Driver",
     *   operationId="driver_show",
     *   @OA\Parameter(
     *     name="id",
     *     in="path",
     *     required=true,
     *     @OA\Schema(
     *      type="string",
     *     ),
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Send request success",
     *     @OA\MediaType(
     *      mediaType="application/json",
     *      example={"code":200,"data":{"id": 1,"name":"......"}}
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
    public function show($id)
    {
        try {
            $driver = $this->repository->getOne($id);
            if ($driver['status'] != 'success') return $this->responseJsonError($driver['code'], $driver['message'], $driver['message']);
            return $this->responseJson($driver['code'], new DriverResource($driver['data']));
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @OA\Put (
     *   path="/api/driver/{id}",
     *   tags={"Driver"},
     *   summary="Update Driver",
     *   operationId="driver_update",
     *   @OA\Parameter(
     *     name="id",
     *     in="path",
     *     required=true,
     *     @OA\Schema(
     *      type="string",
     *     ),
     *   ),
     *     * @OA\RequestBody(
     *       description="flag = 'lead|full|part' , day_of_week = ['mon' => 'Monday','tue'=>'Tuesday','wed'=> 'Wednesday','thu'=>'Thursday','fri'=>'Friday','sat'=>'Saturday ','sun'=>'Sunday'],working_time = ['daily' => 'over_15h_day','month'=>'over_ot_40h_month']",
     *       @OA\MediaType(
     *          mediaType="application/json",
     *          example={"flag":"lead|full|part","driver_name": "tuanminh","start_date": "2022-08-15","end_date": "","brith_day" : "2020-12-03","working_day" : "2","day_of_week" : "mon,tue,wed"
     *     , "working_time": "daily,month" ,"note":""},
     *          @OA\Schema(
     *            required={"user_name"},
     *            @OA\Property(
     *              property="name",
     *              format="string",
     *            ),
     *         )
     *      )
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Send request success",
     *     @OA\MediaType(
     *      mediaType="application/json",
     *      example={"code":200,"data":{"id": 1,"name":  "............."}}
     *     ),
     *   ),
     *   @OA\Response(
     *     response=403,
     *     description="Access Deny permission",
     *     @OA\MediaType(
     *      mediaType="application/json",
     *      example={"code":403,"message":"Access Deny permission"}
     *     ),
     *   ),
     *   security={{"auth": {}}},
     * )
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(DriverRequest $request, $id)
    {
        $r = ['id' => $id];
        $validator = Validator::make($r, ['id' => 'required|exists:drivers,id',]);
        if ($validator->fails()) {
            return $this->responseJsonError(Response::HTTP_UNPROCESSABLE_ENTITY, $validator->messages());
        }
        $driver = $this->repository->update($request->all(), $id);
        if ($driver['status'] != 'success') {
            return $this->responseJsonError($driver['code'], $driver['message'], $driver['message']);
        }
        return $this->responseJson(CODE_SUCCESS, new UserResource($driver['data']), $driver['message']);
    }

    /**
     * @OA\Delete(
     *   path="/api/driver/{id}",
     *   tags={"Driver"},
     *   summary="Delete Driver",
     *   operationId="driver_delete",
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
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $driver = $this->repository->destroy($id);
        if ($driver['status']!='success'){
            return $this->responseJsonError($driver['code'],$driver['message'],$driver['message']);
        }
        return $this->responseJson($driver['code'], null,$driver['message']);
    }
}
