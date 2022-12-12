<?php
/**
 * Created by VeHo.
 * Year: 2022-07-29
 */

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CalendarRequest;
use App\Repositories\Contracts\CalendarRepositoryInterface;
use App\Http\Resources\BaseResource;
use App\Http\Resources\CalendarResource;
use Facade\FlareClient\Http\Response;
use Helper\ResponseService;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    protected $password = 'veho1234567890';

     /**
     * var Repository
     */
    protected $repository;

    public function __construct(CalendarRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @OA\Get(
     *   path="/api/calendar/index",
     *   tags={"Calendar"},
     *   summary="List Calendar",
     *   operationId="Calendar_index",
     *   @OA\Response(
     *     response=200,
     *     description="Send request success",
     *     @OA\MediaType(
     *      mediaType="application/json",
     *      example={"code":200,"data":{{"id": 1,"name": "..........."}}}
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="start_date",
     *     in="query",
     *     required=true,
     *     @OA\Schema(
     *      type="date",
     *     ),
     *   ),
     *   @OA\Parameter(
     *     name="end_date",
     *     in="query",
     *     required=true,
     *     @OA\Schema(
     *      type="date",
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
    public function index(CalendarRequest $request)
    {
        $data = $this->repository->index($request);
        if ($data['status']!='success'){
            return ResponseService::responseData($data['code'],$data['status'],$data['message']);
        }
        return $this->responseJson($data['code'], new CalendarResource($data['data']));
    }

    /**
     * @OA\Post(
     *   path="/api/calendar/setup-data",
     *   tags={"Calendar"},
     *   summary="Add new Calendar",
     *   operationId="Calendar_create",
     *   @OA\RequestBody(
     *       description="targetyyyy = '2022'",
     *       @OA\MediaType(
     *          mediaType="application/json",
     *          example={"password":"string","targetyyyy": "string"},
     *          @OA\Schema(
     *            required={"password","targetyyyy"},
     *           @OA\Property(
     *              property="password",
     *              format="string",
     *            ),
     *           @OA\Property(
     *              property="targetyyyy",
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
    public function store(CalendarRequest $request){
        $checkPassword = $this->checkPassword($request);
        if ($checkPassword['status'] != 'success'){
            return $this->responseJson($checkPassword['code'], null,null,$checkPassword['message']);
        }
        try {
            $data = $this->repository->store($request->all());
            return $this->responseJson($data['code'], null,$data['message']);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @OA\Get(
     *   path="/api/calendar/{id}",
     *   tags={"Calendar"},
     *   summary="Detail Calendar",
     *   operationId="Calendar_show",
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
            $department = $this->repository->find($id);
            return $this->responseJson(200, new BaseResource($department));
        } catch (\Exception $e) {
            throw $e;
        }
    }
//
//    /**
//     * @OA\Post(
//     *   path="/api/Calendar/{id}",
//     *   tags={"Calendar"},
//     *   summary="Update Calendar",
//     *   operationId="Calendar_update",
//     *   @OA\Parameter(
//     *     name="id",
//     *     in="path",
//     *     required=true,
//     *     @OA\Schema(
//     *      type="string",
//     *     ),
//     *   ),
//     *   @OA\RequestBody(
//     *       @OA\MediaType(
//     *          mediaType="application/json",
//     *          example={"name":"string"},
//     *          @OA\Schema(
//     *            required={"name"},
//     *            @OA\Property(
//     *              property="name",
//     *              format="string",
//     *            ),
//     *         )
//     *      )
//     *   ),
//     *   @OA\Response(
//     *     response=200,
//     *     description="Send request success",
//     *     @OA\MediaType(
//     *      mediaType="application/json",
//     *      example={"code":200,"data":{"id": 1,"name":  "............."}}
//     *     ),
//     *   ),
//     *   @OA\Response(
//     *     response=403,
//     *     description="Access Deny permission",
//     *     @OA\MediaType(
//     *      mediaType="application/json",
//     *      example={"code":403,"message":"Access Deny permission"}
//     *     ),
//     *   ),
//     *   security={{"auth": {}}},
//     * )
//     * Display a listing of the resource.
//     *
//     * @return \Illuminate\Http\JsonResponse
//     */
//    public function update(CalendarRequest $request, $id)
//    {
//        $attributes = $request->except([]);
//        $data = $this->repository->update($attributes, $id);
//        return $this->responseJson(200, new BaseResource($data));
//    }

    /**
     * @OA\Delete(
     *   path="/api/Calendar/{id}",
     *   tags={"Calendar"},
     *   summary="Delete Calendar",
     *   operationId="Calendar_delete",
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
    public function destroy(CalendarRequest $request)
    {
        $checkPassword = $this->checkPassword($request);
        if ($checkPassword['status'] != 'success'){
            return $this->responseJson($checkPassword['code'], null,null,$checkPassword['message']);
        }

        $this->repository->delete($request);
        return $this->responseJson(200, null, trans('messages.mes.delete_success'));
    }
    // check password
    private function checkPassword($request){
        if ($request->password != $this->password){
            return ResponseService::responseData(\Illuminate\Http\Response::HTTP_FORBIDDEN,'error','errors.permission');
        }
        return ResponseService::responseData(\Illuminate\Http\Response::HTTP_OK,'success');
    }
}
