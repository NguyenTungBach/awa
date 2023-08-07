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
use Carbon\Carbon;
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
        return $this->repository->listDriver($request);
    }

    /**
     * @OA\Post(
     *   path="/api/driver",
     *   tags={"Driver"},
     *   summary="Add new Driver",
     *   operationId="driver_create",
     *   @OA\RequestBody(
     *       @OA\MediaType(
     *          mediaType="application/json",
     *          example={"type": 1,"driver_code": "abc123","driver_name": "Bach","car": "Lambo","start_date": "2022-08-20","note": "thoi roi ta da xa nhau"},
     *          @OA\Schema(
     *            required={"type","driver_code","car","driver_name","start_date"},
     *            @OA\Property(
     *              property="type",
     *              format="integer",
     *              description="type = {'1' => 'manager','2'=>'full-time','3'=> 'part-time','4'=>'associate company'}",
     *            ),
     *            @OA\Property(
     *              property="driver_code",
     *              format="string",
     *              description="15 character"
     *            ),
     *            @OA\Property(
     *              property="driver_name",
     *              format="string",
     *              description="20 character"
     *            ),
     *            @OA\Property(
     *              property="car",
     *              format="string",
     *            ),
     *            @OA\Property(
     *              property="start_date",
     *              format="string",
     *              description="Y-m-d"
     *            ),
     *            @OA\Property(
     *              property="end_date",
     *              format="string",
     *              description="Y-m-d"
     *            ),
     *            @OA\Property(
     *              property="note",
     *              format="string",
     *              description="1000 character"
     *            ),
     *         )
     *      )
     *   ),
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
        try {
            $request->merge(['status' => 1]);
            $data = $this->repository->create($request->all());
            return $this->responseJson(200, new DriverResource($data));
        } catch (\Exception $e) {
            throw $e;
        }
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
            $data = $this->repository->find($id);
            if ($data != null){
                $data->checkEnd_date = $data->end_date !== null;
                if ($data->end_date !== null){
                    $data->end_date = explode(" ",$data->end_date);
                }
                switch ($data->type){
                    case 1:
                        $data->typeName = trans('drivers.type.1');
                        break;
                    case 2:
                        $data->typeName = trans('drivers.type.2');
                        break;
                    case 3:
                        $data->typeName = trans('drivers.type.3');
                        break;
                    case 4:
                        $data->typeName = trans('drivers.type.4');
                        break;
                }
            }
            $data->start_date = Carbon::parse(explode(" ",$data->start_date)[0])->format('Y年m月d日');
            return $this->responseJson(200, new BaseResource($data));
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
     *   @OA\RequestBody(
     *       @OA\MediaType(
     *          mediaType="application/json",
     *          example={"type": 1,"driver_code": "abc123","driver_name": "Bach","car": "Lambo","start_date": "2022-08-20","note": "thoi roi ta da xa nhau"},
     *          @OA\Schema(
     *            required={"type","car","driver_name","start_date"},
     *            @OA\Property(
     *              property="type",
     *              format="integer",
     *              description="type = {'1' => 'manager','2'=>'full-time','3'=> 'part-time','4'=>'associate company'}",
     *            ),
     *            @OA\Property(
     *              property="driver_name",
     *              format="string",
     *              description="20 character"
     *            ),
     *            @OA\Property(
     *              property="car",
     *              format="string",
     *            ),
     *            @OA\Property(
     *              property="start_date",
     *              format="string",
     *              description="Y-m-d"
     *            ),
     *            @OA\Property(
     *              property="end_date",
     *              format="string",
     *              description="Y-m-d"
     *            ),
     *            @OA\Property(
     *              property="note",
     *              format="string",
     *              description="1000 character"
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
        $attributes = $request->except([]);
        $data = $this->repository->update($attributes, $id);
        return $this->responseJson(200, new BaseResource($data));
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
        return $this->repository->destroy($id);
    }
}
