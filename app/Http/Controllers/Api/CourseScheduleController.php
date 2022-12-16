<?php
/**
 * Created by VeHo.
 * Year: 2022-07-29
 */

namespace App\Http\Controllers\Api;

use App\Exports\CourseScheduleExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\CourseScheduleRequest;
use App\Imports\CourseScheduleImport;
use App\Repositories\Contracts\CourseScheduleRepositoryInterface;
use App\Http\Resources\BaseResource;
use App\Http\Resources\CourseScheduleResource;
use Helper\ResponseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class CourseScheduleController extends Controller
{

     /**
     * var Repository
     */
    protected $repository;

    public function __construct(CourseScheduleRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @OA\Get(
     *   path="/api/course-schedule",
     *   tags={"CourseSchedule"},
     *   summary="List CourseSchedule",
     *   operationId="course_schedule_index",
     *   @OA\Response(
     *     response=200,
     *     description="Send request success",
     *     @OA\MediaType(
     *      mediaType="application/json",
     *      example={"code":200,"data":{{"id": 1,"name": "..........."}}}
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="view_date",
     *     in="query",
     *     @OA\Schema(
     *      type="string",
     *     ),
     *   ),
     *   @OA\Parameter(
     *     name="field",
     *     in="query",
     *     @OA\Schema(
     *      type="string",
     *     ),
     *   ),
     *   @OA\Parameter(
     *     name="sortby",
     *     in="query",
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
    public function index(CourseScheduleRequest $request)
    {
        $list = $this->repository->getList($request);
        if ($list['status'] != 'success') {
            return $this->responseJsonError($list['code'], $list['message']);
        }
        if (!isset($list['data'])) {
            $list['data'] = [];
        }

        return $this->responseJson($list['code'], CourseScheduleResource::collection($list['data']));
    }

    /**
     * @OA\Get(
     *   path="/api/course-schedule/{id}",
     *   tags={"CourseSchedule"},
     *   summary="Detail CourseSchedule",
     *   operationId="course_schedule_show",
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
    public function show(Request $request, $id)
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
     * @OA\post(
     *   path="/api/course-schedule/updates",
     *   tags={"CourseSchedule"},
     *   summary="Update many CourseSchedule",
     *   operationId="course_schedule_updates",
     *   @OA\RequestBody(
     *       description="id = 2, status = 'on|off' ",
     *       @OA\MediaType(
     *          mediaType="application/json",
     *          example={
     *          "items": {
     *                   {
     *                          "id": 1,
     *                          "status":"on"
     *                   },
     *                  {
     *                          "id": 2,
     *                          "status":"off"
     *                   }
     *                 }
     *          },
     *          @OA\Schema(
     *            required={"id", "status"},
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
        if (!$data || !$data['items'] || empty($data['items'])) {
            return $this->responseJson(200, null, "0 row updated");
        }
        $validator = Validator::make($data, [
            'items.*.course_id' => 'required_if:items.*.id,null|numeric',
            'items.*.schedule_date' => 'required_if:items.*.id,null|date',
            'items.*.status' => 'required|in:on,off'
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();//dd($validator->errors()->all());
            return $this->responseJsonError(422, $errors);
        }
        $items = $data ?? [];
        if (!isset($items['items'])) {
            return $this->responseJsonError(422, "data need to update is empty");
        }
        $data = $this->repository->changeMany($items['items']);
        if ($data['status'] == 'error') {
            return $this->responseJsonError($data['code'], $data['message']);
        }
        return $this->responseJson(200, new CourseScheduleResource($data['data']), $data['message']);

    }
    /**
     * @OA\Post(
     *   path="/api/course-schedule/import",
     *   tags={"CourseSchedule"},
     *   summary="Import CourseSchedule",
     *   operationId="course_schedule_import",
     *   description="import data 1 month.",
     *   @OA\Parameter(
     *     name="for_date",
     *     in="query",
     *     required=false,
     *     @OA\Schema(
     *      type="string",
     *     ),
     *   ),
     *     @OA\RequestBody(
     *          required=true,
     *          @OA\MediaType(
     *              mediaType="multipart/form-data",
     *              @OA\Schema(
     *                  @OA\Property(property="file", type="string", format="binary"),
     *              )
     *          )
     *      ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\JsonContent(
     *                  type="object",
     *                  @OA\Property(
     *                      property="message",
     *                      type="string",
     *                      example="import success"
     *                  ),
     *              ),
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="data not found",
     *         @OA\JsonContent(
     *              type="object",
     *              @OA\Property(
     *                  property="message",
     *                  type="string",
     *                  example="data not found."
     *              ),
     *         ),
     *     ),
     *   security={{"auth": {}}},
     * )
     */
    public function import(Request $request)
    {
        $validator = Validator::make($request->all(),
        [
            'file'          => 'required|file|max:3000|mimes:xlsx, csv, xls',
            'for_date'      => 'sometimes|date|date_format:Y-m'
        ]);//'file'=> 'required|mimes:xlsx, csv, xls'
        if ($validator->fails()) {
            return $this->responseJsonError(422, $validator->errors());
        }
        $result = $this->repository->importFileUpload($request);

        if ($result['status'] == 'error') {
            return ResponseService::responseJsonError($result['code'], $result['message'], $result['message_content'],null, $result['data'] ?? '');
        }
        return $this->responseJson(200, $result['message'], new BaseResource($result['data']));

    }
    /**
     * @OA\Get(
     *   path="/api/course-schedule/export-data",
     *   tags={"CourseSchedule"},
     *   summary="Export CourseSchedule",
     *   operationId="course_schedule_export",
     *   @OA\Parameter(
     *     name="field",
     *     in="query",
     *     required=false,
     *     @OA\Schema(
     *      type="string",
     *     ),
     *   ),
     *   @OA\Parameter(
     *     name="sortby",
     *     in="query",
     *     required=false,
     *     @OA\Schema(
     *      type="string",
     *     ),
     *   ),
     *   @OA\Parameter(
     *     name="view_date",
     *     in="query",
     *     required=false,
     *     @OA\Schema(
     *      type="string",
     *     ),
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="export file success",
     *     @OA\MediaType(
     *      mediaType="application/json",
     *      example={"code":200,"data":{"id": 1,"name":"......"}}
     *     )
     *   ),
     *   security={{"auth": {}}},
     * )
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function export(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'view_date' => 'sometimes|date|date_format:Y-m',
            'field' => 'nullable|in:course_code,flag',
            'sortby' => 'nullable|in:asc,desc'
        ]);
        if ($validator->fails()) {
            return $this->responseJsonError(422, $validator->errors());
        }
        $field = $request['field'] ?? '';
        $sortBy = $request['sortby'] ?? '';
        $viewDate = isset($request['view_date']) && strtotime($request['view_date']) ? strtotime($request['view_date']):strtotime(date('Y-m'));
        $newFile = isset($request['new-file']) ?? false;
        return $this->repository->downloadFileExported($viewDate, $field, $sortBy, $newFile);
    }
}
