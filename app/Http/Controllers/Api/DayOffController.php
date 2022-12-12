<?php
/**
 * Created by VeHo.
 * Year: 2022-08-04
 */

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\DayOffRequest;
use App\Http\Resources\DriverResource;
use App\Http\Resources\UserResource;
use App\Repositories\Contracts\DayOffRepositoryInterface;
use App\Http\Resources\BaseResource;
use App\Http\Resources\DayOffResource;
use Illuminate\Http\Request;

class DayOffController extends Controller
{

     /**
     * var Repository
     */
    protected $repository;

    public function __construct(DayOffRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @OA\Get(
     *   path="/api/day-off",
     *   tags={"DayOff"},
     *   summary="List DayOff",
     *   operationId="day_off_index",
     *   @OA\Response(
     *     response=200,
     *     description="Send request success",
     *     @OA\MediaType(
     *      mediaType="application/json",
     *      example={"code":200,"data":{{"id": 1,"name": "..........."}}}
     *     )
     *   ),
     *   @OA\Parameter(
     *      description="'Y-m'   '(2022-08)'",
     *     name="date",
     *     in="query",
     *     @OA\Schema(
     *      type="date",
     *     ),
     *   ),
     *  @OA\Parameter(
     *     name="field",
     *     description = "driver_code,flag",
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
    public function index(DayOffRequest $request)
    {
        $dayOff = $this->repository->getPagination($request);
        if ($dayOff['status']!='success'){
            return  $this->responseJsonError($dayOff['code'],$dayOff['message']);
        }
        return $this->responseJson($dayOff['code'], isset($dayOff['data'])?$dayOff['data']:[]);
    }

    /**
     * @OA\Post(
     *   path="/api/day-off",
     *   tags={"DayOff"},
     *   summary="Add new DayOff",
     *   operationId="day_off_create",
     * @OA\RequestBody(
     *       description="type = 'D-1|D-2|D-3|D-4|D-5'",
     *       @OA\MediaType(
     *          mediaType="application/json",
     *          example={"date":"2022-09","day_off": {{"driver_code": "00001","date_off": "2022-09-09","type": "D-3"},{"driver_code": "00001","date_off": "2022-09-10","type": "D-3"}}},
     *          @OA\Schema(
     *            required={"user_name"},
     *            @OA\Property(
     *              property="name",
     *              format="string",
     *            ),
     *         )
     *      )
     *   ),
     *     @OA\Response(
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
    public function store(DayOffRequest $request)
    {
        $createDayOff = $this->repository->create($request->all());
        if ($createDayOff['status'] != 'success') {
            return $this->responseJsonError($createDayOff['code'], $createDayOff['message'], $createDayOff['message']);
        }
        return $this->responseJson($createDayOff['code'], isset($createDayOff['data'])?new DayOffResource($createDayOff['data']):null, $createDayOff['message']);
    }

    /**
     * @OA\Get(
     *   path="/api/day-off/{id}",
     *   tags={"DayOff"},
     *   summary="Detail DayOff",
     *   operationId="day_off_show",
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
//    public function show($id)
//    {
//        try {
//            $department = $this->repository->find($id);
//            return $this->responseJson(200, new BaseResource($department));
//        } catch (\Exception $e) {
//            throw $e;
//        }
//    }

    /**
     * @OA\Post(
     *   path="/api/day-off/{id}",
     *   tags={"DayOff"},
     *   summary="Update DayOff",
     *   operationId="day_off_update",
     *   @OA\Parameter(
     *     name="id",
     *     in="path",
     *     required=true,
     *     @OA\Schema(
     *      type="string",
     *     ),
     *   ),
     *   @OA\RequestBody(
     *       @OA\MediaType(
     *          mediaType="application/json",
     *          example={"name":"string"},
     *          @OA\Schema(
     *            required={"name"},
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
//    public function update(DayOffRequest $request, $id)
//    {
//        $attributes = $request->except([]);
//        $data = $this->repository->update($attributes, $id);
//        return $this->responseJson(200, new BaseResource($data));
//    }

    /**
     * @OA\Delete(
     *   path="/api/day-off/{id}",
     *   tags={"DayOff"},
     *   summary="Delete DayOff",
     *   operationId="day_off_delete",
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
//    public function destroy($id)
//    {
//        $this->repository->delete($id);
//        return $this->responseJson(200, null, trans('messages.mes.delete_success'));
//    }
}
