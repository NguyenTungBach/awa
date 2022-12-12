<?php
/**
 * Created by VeHo.
 * Year: 2022-07-29
 */

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CoursePatternRequest;
use App\Repositories\Contracts\CoursePatternRepositoryInterface;
use App\Http\Resources\BaseResource;
use App\Http\Resources\CoursePatternResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CoursePatternController extends Controller
{

     /**
     * var Repository
     */
    protected $repository;

    public function __construct(CoursePatternRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @OA\Get(
     *   path="/api/course-pattern",
     *   tags={"CoursePattern"},
     *   summary="List CoursePattern",
     *   operationId="course_pattern_index",
     *   @OA\Response(
     *     response=200,
     *     description="Send request success",
     *     @OA\MediaType(
     *      mediaType="application/json",
     *      example={"code":200,"data":{{"id": 1,"name": "..........."}}}
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
     *     security={{"auth": {}}},
     * )
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(CoursePatternRequest $request)
    {
        $list = $this->repository->getList($request);
        if ($list['status'] !== 'success') {
            return $this->responseJsonError($list['code'], $list['message']);
        }
        return $this->responseJson(200, BaseResource::collection($list['data']));

    }

    /**
     * @OA\Get(
     *   path="/api/course-pattern/{id}",
     *   tags={"CoursePattern"},
     *   summary="Detail CoursePattern",
     *   operationId="course_pattern_show",
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
     *     response=405,
     *     description="Data does not exist",
     *     @OA\MediaType(
     *      mediaType="application/json",
     *      example={"code":405,"message":"Data does not exist"}
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
            $model = $this->repository->findOne($id);
            if ($model['status'] == 'error') {
                return $this->responseJsonError($model['code'], $model['message']);
            }
            return $this->responseJson(200, new BaseResource($model['data']));
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @OA\Put(
     *   path="/api/course-pattern/{id}",
     *   tags={"CoursePattern"},
     *   summary="Update CoursePattern",
     *   operationId="course_pattern_update",
     *   @OA\Parameter(
     *     name="id",
     *     in="path",
     *     required=true,
     *     @OA\Schema(
     *      type="string",
     *     ),
     *   ),
     *   @OA\RequestBody(
     *       description="status = 'yes|no' ",
     *       @OA\MediaType(
     *          mediaType="application/json",
     *          example={"status": "yes"},
     *          @OA\Schema(
     *            required={"status"},
     *            @OA\Property(
     *              property="status",
     *              format="string",
     *            ),
     *          )
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
     *     response=405,
     *     description="Access Deny permission",
     *     @OA\MediaType(
     *      mediaType="application/json",
     *      example={"code":405,"message":"Access Deny permission"}
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
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:no,yes'
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return $this->responseJsonError(422, $errors);
        }
        $model = $this->repository->findOne($id);
        if ($model['status'] == 'error') {
            return $this->responseJsonError($model['code'], $model['message']);
        }
        $attributes = $request->all();
        $attributes['status'] = $attributes['status'];
        $attributes['id'] = $id;
        $data = $this->repository->changeStatus($attributes);
        if ($data['status'] == 'error') {
            return $this->responseJsonError($data['code'], $data['message']);
        }
        return $this->responseJson(200, new CoursePatternResource($data['data']), $data['message']);

    }


    /**
     * @OA\post(
     *   path="/api/course-pattern/updates",
     *   tags={"CoursePattern"},
     *   summary="Update many CoursePattern",
     *   operationId="course_pattern_updates",
     *   @OA\RequestBody(
     *       description="id = 2, status = 'yes|no' ",
     *       @OA\MediaType(
     *          mediaType="application/json",
     *          example={
     *          "items": {
     *                   {
     *                          "id": 1,
     *                          "status":"no"
     *                   },
     *                  {
     *                          "id": 2,
     *                          "status":"yes"
     *                   }
     *                 }
     *          },
     *          @OA\Schema(
     *            required={"status"},
     *            @OA\Property(
     *              property="status",
     *              format="string",
     *            ),
     *          )
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
     *     response=405,
     *     description="Access Deny permission",
     *     @OA\MediaType(
     *      mediaType="application/json",
     *      example={"code":405,"message":"Access Deny permission"}
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
    public function updateMany(Request $request)
    {
        $data = $request->json()->all();
        $validator = Validator::make($data, [
            'items.*.id' => 'required|numeric',
            'items.*.status' => 'required|in:no,yes'
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();//dd($validator->errors()->all());
            return $this->responseJsonError(422, $errors);
        }
        $items = $request->all() ?? [];
        $data = $this->repository->changeMany($items['items']);
        if ($data['status'] == 'error') {
            return $this->responseJsonError($data['code'], $data['message']);
        }
        return $this->responseJson(200, new CoursePatternResource($data['data']), $data['message']);

    }
}
