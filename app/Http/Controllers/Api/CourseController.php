<?php
/**
 * Created by VeHo.
 * Year: 2022-07-28
 */

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CourseRequest;
use App\Repositories\Contracts\CourseRepositoryInterface;
use App\Http\Resources\BaseResource;
use App\Http\Resources\CourseResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CourseController extends Controller
{

    /**
     * var Repository
     */
    protected $repository;

    public function __construct(CourseRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @OA\Get(
     *   path="/api/course",
     *   tags={"Course"},
     *   summary="List Course",
     *   operationId="course_index",
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
     *     description = "course_code,course_name",
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
    public function index(CourseRequest $request)
    {
        $course = $this->repository->listCourse($request);
        if ($course['status'] != 'success') {
            return $this->responseJsonError($course['code'], $course['message']);
        }
        return $this->responseJson($course['code'], isset($course['data'])?$course['data']:null);
    }

    /**
     * @OA\Post(
     *   path="/api/course",
     *   tags={"Course"},
     *   summary="Add new Course",
     *   operationId="course_create",
     *   @OA\RequestBody(
     *       description="flag = 'yes|no|null' ",
     *       @OA\MediaType(
     *          mediaType="application/json",
     *          example={"flag": "yes","course_code": "1111","course_name": "sontest 2","start_time": "06:06","end_time": "06:15","break_time": "06:20","start_date": "2022-08-25","end_date": "","note":"","group" : "AA","point" : "number"},
     *          @OA\Schema(
     *            required={"user_name"},
     *            @OA\Property(
     *              property="name",
     *              format="string",
     *            ),
     *          )
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
    public function store(CourseRequest $request)
    {
        try {
            $createCourse = $this->repository->create($request->all());
            if ($createCourse['status'] != 'success') {
                return $this->responseJsonError($createCourse['code'], $createCourse['message'], $createCourse['message']);
            }
            return $this->responseJson($createCourse['code'], new BaseResource($createCourse['data']), $createCourse['message']);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @OA\Get(
     *   path="/api/course/{id}",
     *   tags={"Course"},
     *   summary="Detail Course",
     *   operationId="course_show",
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
            $course = $this->repository->getOne($id);
            if ($course['status'] != 'success') return $this->responseJsonError($course['code'], $course['message'], $course['message']);
            return $this->responseJson($course['code'], new CourseResource($course['data']));
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @OA\Put(
     *   path="/api/course/{id}",
     *   tags={"Course"},
     *   summary="Update Course",
     *   operationId="course_update",
     *  @OA\Parameter(
     *     name="id",
     *     in="path",
     *     required=true,
     *     @OA\Schema(
     *      type="string",
     *     ),
     *   ),
     *   @OA\RequestBody(
     *       description="flag = 'yes|no|null' ",
     *       @OA\MediaType(
     *          mediaType="application/json",
     *          example={"flag": "yes", "course_name": "sontest 2","start_time": "06:06","end_time": "06:15","break_time": "06:20","start_date": "2022-08-25","end_date": "","note":"","group" : "AA","point" : "number"},
     *          @OA\Schema(
     *            required={"user_name"},
     *            @OA\Property(
     *              property="name",
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
    public function update(CourseRequest $request, $id)
    {
        $updateCourse = $this->repository->update($request->all(), $id);
        if ($updateCourse['status']!='success'){
            return $this->responseJsonError($updateCourse['code'],$updateCourse['message'],$updateCourse['message']);
        }
        return $this->responseJson(CODE_SUCCESS, new BaseResource($updateCourse['data']),$updateCourse['message']);
    }

    /**
     * @OA\Delete(
     *   path="/api/course/{id}",
     *   tags={"Course"},
     *   summary="Delete Course",
     *   operationId="course_delete",
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
     * */
    public function destroy($id)
    {
        $course = $this->repository->delete($id);
        if ($course['status']!='success'){
            return $this->responseJsonError($course['code'],$course['message'],$course['message']);
        }
        return $this->responseJson($course['code'], null,$course['message']);
    }
}
