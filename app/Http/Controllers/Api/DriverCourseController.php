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
     *                      "course_code": "00001",
     *                      "is_checked": "no"
     *
     *                  },
     *                  {
     *                      "course_code": "00002",
     *                      "is_checked": "no"
     *
     *                  }
     *              }
     *          },
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
    public function store(Request $request)
    {
        $data = $request->json()->all();


        $validator = Validator::make($data, [
            'driver_id' => 'required|numeric',
            'items.*.course_code' => 'required',
            'items.*.is_checked' => 'nullable|in:yes,no'
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();//dd($validator->errors()->all());
            return $this->responseJsonError(422, $errors);
        }

        $theDriver = Driver::where('id', $data['driver_id'])->first();

        if (!$theDriver) {
            return $this->responseJsonError(422, "not found for id [" . $data['driver_id'] . "]");
        }

        if ($data['items']) {
            $codes = array_column($data['items'], 'course_code');

            $validCodes = Course::whereIn('course_code', $codes)->get();
            $validCourses = $validCodes->keyBy('course_code')->toArray();

            if (count(array_unique($codes)) != count($validCourses)) {
                return $this->responseJsonError(422, "some courses was not found");
            } elseif (count($codes) != count($validCourses)) {
                return $this->responseJsonError(422, "some courses was duplicated");
            }

            $resultCheck = [];
            foreach ($data['items'] as $item) {
                if ($result = $this->checkUnique($item['course_code'], $item['is_checked'], $theDriver->driver_code)) {
                    $resultCheck[] = "this course " . $item['course_code'] . " has checked by Driver has code[" . $result->driver_code . "]";
                }
            }

            if ($resultCheck) {
                return $this->responseJsonError(422, "このコースは、既に他のドライバーに専属チェックが付いています。", "some course have been checked", $resultCheck);
            }

        }

        $createDriverCourse = $this->repository->updateData($data, $theDriver);

        if ($createDriverCourse['status']!='success'){
            return $this->responseJsonError($createDriverCourse['code'], $createDriverCourse['message'], $createDriverCourse['message'],$createDriverCourse);
        }
        return $this->responseJson($createDriverCourse['code'],isset($createDriverCourse['data']) ? $createDriverCourse['data'] : null);
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
