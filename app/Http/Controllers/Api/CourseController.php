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
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Route;
use App\Exports\CourseExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\CourseImport;
use Maatwebsite\Excel\Validators\ValidationException;
use Illuminate\Validation\ValidationException as Validation;
use Maatwebsite\Excel\HeadingRowImport;
use Maatwebsite\Excel\Validators\Failure;

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
        try {
            $result = $this->repository->getAll($request->all());

            return $this->responseJson(Response::HTTP_OK, CourseResource::collection($result), LIST_SUCCESS);
        } catch (\Exception $exception) {

            return $this->responseJsonError(Response::HTTP_INTERNAL_SERVER_ERROR, LIST_ERROR, $exception->getMessage());
        }
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
            $result = $this->repository->createCourse($request->all());
            if (empty($result)) {
                return $this->responseJsonError(Response::HTTP_BAD_REQUEST, CREATE_ERROR, CREATE_ERROR);
            }
            if ($result['code'] != 200) {
                return $this->responseJsonError($result['code'], $result['message'], CREATE_ERROR);
            }

            return $this->responseJson(Response::HTTP_OK, new CourseResource($result['data']), CREATE_SUCCESS);
        } catch (\Exception $exception) {

            return $this->responseJsonError(Response::HTTP_INTERNAL_SERVER_ERROR, CREATE_ERROR, $exception->getMessage());
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
            $result = $this->repository->getDetail($id);

            return $this->responseJson(Response::HTTP_OK, new CourseResource($result), SUCCESS);
        } catch (\Exception $exception) {

            return $this->responseJsonError(Response::HTTP_NOT_FOUND, ERROR, $exception->getMessage());
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
        try {
            $result = $this->repository->updateCourse($request->all(), $id);
            if (empty($result)) {
                return $this->responseJsonError(Response::HTTP_BAD_REQUEST, UPDATE_ERROR, 'CAN NOT UPDATE BECAUSE FINAL CLOSING ALREADY EXISTS');
            }

            return $this->responseJson(Response::HTTP_OK, $result, UPDATE_SUCCESS);
        } catch (\Exception $exception) {

            return $this->responseJsonError(Response::HTTP_INTERNAL_SERVER_ERROR, UPDATE_ERROR, $exception->getMessage());
        }
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
        try {
            $result = $this->repository->deleteCourse($id);
            if (empty($result)) {
                return $this->responseJsonError(Response::HTTP_BAD_REQUEST, DELETE_ERROR, DELETE_ERROR);
            }

            return $this->responseJson(Response::HTTP_OK, $result, DELETE_SUCCESS);
        } catch (\Exception $exception) {

            return $this->responseJsonError(Response::HTTP_INTERNAL_SERVER_ERROR, DELETE_ERROR, $exception->getMessage());
        }
    }

    public function export(CourseRequest $request)
    {
        try {
            $input = $request->all();

            return Excel::download(new CourseExport($input), '運行情報一覧.xlsx');
        } catch (\Exception $exception) {

            return $this->responseJsonError(Response::HTTP_INTERNAL_SERVER_ERROR, ERROR, $exception->getMessage());
        }
    }

    public function deleteMultiple(CourseRequest $request)
    {
        try {
            $arrId = $request->course_ids;
            $result = $this->repository->destroyCourse($arrId);
            if (empty($result)) {
                return $this->responseJsonError(Response::HTTP_BAD_REQUEST, DELETE_ERROR, DELETE_ERROR);
            }
            if ($result['code'] != 200) {
                return $this->responseJsonError($result['code'], $result['message'], $result['message_content']);
            }

            return $this->responseJson(Response::HTTP_OK, DELETE_SUCCESS, DELETE_SUCCESS);
        } catch (\Exception $exception) {

            return $this->responseJsonError(Response::HTTP_INTERNAL_SERVER_ERROR, DELETE_ERROR, $exception->getMessage());
        }
    }

    public function import(CourseRequest $request)
    {
        try {
            $file = $request->file;
            // validate header before read content data
            $diff = array_diff(config('courses.header'), collect((new HeadingRowImport)->toArray($file))->flatten()->all());
            if (!empty($diff)) {
                throw Validation::withMessages([
                    __('validation.custom.csv.headings.matched', ['names' => implode(config('courses.comma'), $diff)]),
                ]);
            }
            // read data
            $courseImport = new CourseImport;
            Excel::import($courseImport, $file);
            if(!empty($courseImport->data) && is_array($courseImport->data) && count($courseImport->data)) {
                return $this->responseJsonError(Response::HTTP_UNPROCESSABLE_ENTITY, ERROR, $courseImport->data);
            }

            return $this->responseJson(200, [], trans('messages.mes.create_success'));
        } catch (ValidationException $exception) {
            return $this->responseJsonError(
                Response::HTTP_UNPROCESSABLE_ENTITY,
                ERROR,
                collect($exception->failures())
                ->map(fn(Failure $failure) => str_replace(':row', $failure->row(), $failure->errors()[0]))->all()
            );
        }
    }

    /**
     * @OA\GET(
     *   path="/api/course/course-shift",
     *   tags={"Course"},
     *   summary="List Course Shift",
     *   operationId="course_shift",
     *   @OA\Parameter(
     *     name="date",
     *     description = "Y-m-d",
     *     example = "2023-08-01",
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
     *      example={"code":200,"data":"Send request success"}
     *     )
     *   ),
     *   security={{"auth": {}}},
     * )
     * */
    public function listCourseShift(CourseRequest $request)
    {
        $data = $this->repository->listCourseShift($request);

        return $this->responseJson(200, new BaseResource($data));
    }
}
