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
use App\Models\Driver;
use App\Models\DriverCourse;
use App\Repositories\Contracts\DriverCourseRepositoryInterface;
use App\Http\Resources\BaseResource;
use App\Http\Resources\DriverCourseResource;
use Http\Client\Exception;
use Illuminate\Http\Request;
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
     *   path="/api/driver-course/list/{id}",
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
     *     name="id",
     *     description = "id = driver_id",
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
    public function index($id)
    {
        $driverCourse = $this->repository->getPagination($id);
        if ($driverCourse['status'] != 'success') {
            return $this->responseJsonError($driverCourse['code'], $driverCourse['message']);
        }
        return $this->responseJson($driverCourse['code'], isset($driverCourse['data']) ? $driverCourse['data'] : null);
    }

    /**
     * @OA\Post(
     *   path="/api/driver-course",
     *   tags={"DriverCourse"},
     *   summary="Add Update new DriverCourse",
     *   operationId="driver_course_create",
     *   @OA\RequestBody(
     *       @OA\MediaType(
     *          mediaType="application/json",
     *          example={
     *              "driver_id": "1",
     *              "items" : {
     *                  {
     *                      "course_id": 4,
     *                      "date": "2023-07-25",
     *                      "start_time": "08:00",
     *                      "end_time": "18:00",
     *                      "break_time": "12:00"
     *                  },
     *                  {
     *                      "course_id": 2,
     *                      "date": "2023-07-25",
     *                      "start_time": "08:00",
     *                      "end_time": "18:00",
     *                      "break_time": "12:00"
     *                  },
     *              }
     *          },
     *          @OA\Schema(
     *            required={"driver_id","items"},
     *            @OA\Property(
     *              property="driver_id",
     *              type="integer",
     *            ),
     *            @OA\Property(
     *              property="items",
     *              type="array",
     *              @OA\Items(
     *                  required={"course_id","date","start_time","end_time","break_time"},
     *                  @OA\Property(property="course_id", type="string" ),
     *                  @OA\Property(property="date", type="string",example="Y-m-d" ),
     *                  @OA\Property(property="start_time", type="string",description="H:i" ),
     *                  @OA\Property(property="end_time", type="string",description="H:i" ),
     *                  @OA\Property(property="break_time", type="string",description="H:i" ),
     *               )
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
    public function store(DriverCourseRequest $request)
    {
        try {
            $request->merge(['status' => 1]);
            return $this->repository->create($request->all());
        } catch (\Exception $e) {
            throw $e;
        }
    }

    private function checkUnique($code, $isChecked, $driverCode)
    {
        if ($isChecked == 'no') {
            return false;
        }
        $hasCheck = DriverCourse::where('driver_code', '<>', $driverCode)
                                ->where('course_code', $code)
                                ->where('is_checked', $isChecked)
                                ->first();
        return $hasCheck;
    }

}
