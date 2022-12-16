<?php
/**
 * Created by VeHo.
 * Year: 2022-07-29
 */

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ShiftRequest;
use App\Repositories\Contracts\ShiftRepositoryInterface;
use App\Http\Resources\BaseResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ShiftController extends Controller
{

    /**
     * var Repository
     */
    protected $repository;

    public function __construct(ShiftRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @OA\Get(
     *   path="/api/shift",
     *   tags={"Shift"},
     *   summary="List Shift",
     *   operationId="shift_index",
     *   @OA\Response(
     *     response=200,
     *     description="Send request success",
     *     @OA\MediaType(
     *      mediaType="application/json",
     *      example={"code":200,"data":{{"id": 1,"name": "..........."}}}
     *     )
     *   ),
     *    @OA\Parameter(
     *     description="'Y-m'   '(2022-08)'",
     *     name="date",
     *     in="query",
     *     @OA\Schema(
     *      type="string",
     *     ),
     *   ),
     *   @OA\Parameter(
     *     description="'Y-m-d'   '(2022-08-01)'",
     *     name="start_date",
     *     in="query",
     *     @OA\Schema(
     *      type="string",
     *     ),
     *   ),
     *   @OA\Parameter(
     *     description="'Y-m-d'   '(2022-08-11)'",
     *     name="end_date",
     *     in="query",
     *     @OA\Schema(
     *      type="string",
     *     ),
     *   ),
     *   @OA\Parameter(
     *     description="'month|week'",
     *     name="type",
     *     in="query",
     *     @OA\Schema(
     *      type="string",
     *     ),
     *   ),
     *      @OA\Parameter(
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
     *       type="string",
     *     ),
     *   ),
     *   @OA\Parameter(
     *     name="tab3",
     *     description = "1|true  required when display tab grade",
     *     in="query",
     *     @OA\Schema(
     *       type="string",
     *     ),
     *   ),
     *   @OA\Parameter(
     *     name="display",
     *     description = "1|true",
     *     in="query",
     *     @OA\Schema(
     *       type="string",
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
    public function index(ShiftRequest $request)
    {
        $shift = $this->repository->list($request);
        if ($shift['status'] != 'success') {
            return $this->responseJsonError($shift['code'], $shift['message']);
        }
        return $this->responseJson($shift['code'], isset($shift['data'])?$shift['data']:null);
    }

    /**
     * @OA\Get(
     *   path="/api/shift/detail-cell",
     *   tags={"Shift"},
     *   summary="List Shift",
     *   operationId="shift_detail",
     *   @OA\Response(
     *     response=200,
     *     description="Send request success",
     *     @OA\MediaType(
     *      mediaType="application/json",
     *      example={"code":200,"data":{{"id": 1,"name": "..........."}}}
     *     )
     *   ),
     *    @OA\Parameter(
     *     name="driver_code",
     *     in="query",
     *     @OA\Schema(
     *      type="string",
     *     ),
     *   ),
     *   @OA\Parameter(
     *     description="'Y-m-d'   '(2022-08-01)'",
     *     name="date",
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
    public function detailCell(ShiftRequest $request)
    {
        $shift = $this->repository->detailCell($request);
        if ($shift['status'] != 'success') {
            return $this->responseJsonError($shift['code'], $shift['message']);
        }
        return $this->responseJson($shift['code'], $shift['data']);
    }

    /**
     * @OA\Post(
     *   path="/api/shift",
     *   tags={"Shift"},
     *   summary="Add new Shift",
     *   operationId="shift_create_ai",
     * @OA\RequestBody(
     *       @OA\MediaType(
     *          mediaType="application/json",
     *          example={"date":"2022-09","start_date" : "2022-09-24","end_date"  : "2022-09-30"},
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
     *      example={"code":200,"data":{"id": 1,"name": "......"}}
     *     )
     *   ),
     *   security={{"auth": {}}},
     * )
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function store(ShiftRequest $request)
    {
        $createShift = $this->repository->create($request->all());
        if ($createShift['status'] != 'success') {
            return $this->responseJsonError($createShift['code'], $createShift['message'], $createShift['message']);
        }
        return $this->responseJson($createShift['code'],null, $createShift['message']);
    }

    /**
     * @OA\Post(
     *   path="/api/shift/detail-cell",
     *   tags={"Shift"},
     *   summary="Add new Shift",
     *   operationId="shift_create",
     * @OA\RequestBody(
     *       @OA\MediaType(
     *          mediaType="application/json",
     *          example={"date" : "2022-09","shift_list" : {{"date_edit" : "2022-09-01","driver_code" : "0144","shift_list_update":{{"type":"00001","start_time" : "06:15","end_time" : "19:45","break_time" : "1.50"},{"type":"S-2","start_time" : "20:00","end_time" : "20:45","break_time" : "1.50"}}},{"date_edit" : "2022-09-01","driver_code" : "0144","shift_list_update":{{"type":"00001","start_time" : "06:15","end_time" : "19:45","break_time" : "1.50"},{"type":"S-2","start_time" : "20:00","end_time" : "20:45","break_time" : "1.50"}}}}},
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
     *      example={"code":200,"data":{"id": 1,"name": "......"}}
     *     )
     *   ),
     *   security={{"auth": {}}},
     * )
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function updateCell(ShiftRequest $request)
    {
        $createShift = $this->repository->updateCell($request);
        if ($createShift['status'] != 'success') {
            return $this->responseJsonError($createShift['code'], $createShift['message'], $createShift['message']);
        }
        return $this->responseJson($createShift['code'], $createShift['data'], $createShift['message']);
    }

    /**
     * @OA\Post(
     *   path="/api/shift/edits",
     *   tags={"Shift"},
     *   summary="update Shift days on tab courses",
     *   operationId="shift_editAI",
     * @OA\RequestBody(
     *       @OA\MediaType(
     *          mediaType="application/json",
     *          example=
     *          {
     *              "date": "2022-09",
     *              "items": {
     *                      {
     *                          "course_code": "00001",
     *                          "day": "2022-12-01",
     *                          "driver":"0001"
     *                      }, {
     *                          "course_code": "00001",
     *                          "day": "2022-12-02",
     *                          "driver":"0001"
     *                      }
     *               }
     *          },
     *          @OA\Schema(
     *            required={"user_name"},
     *            @OA\Property(
     *              property="name",
     *              format="string",
     *            ),
     *         ),
     *      ),
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
    public function editAI(ShiftRequest $request)
    {
        $result = $this->repository->editCells($request->all());
        if ($result['status'] != 'success') {
            return $this->responseJsonError($result['code'], $result['message'], $result['message']);
        }
        return $this->responseJson($result['code'], $result['data'] ?? '', $result['message']);
    }

    /**
     * @OA\Get(
     *   path="/api/shift/check-data-result",
     *   tags={"Shift"},
     *   summary="check Data Shift",
     *   operationId="shift_check",
     * @OA\RequestBody(
     *       @OA\MediaType(
     *          mediaType="application/json",
     *          example={"date":"2022-09","start_date" : "2022-09-24","end_date"  : "2022-09-30" , "type" : "month|week"},
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
     *      example={"code":200,"data":{"id": 1,"name": "......"}}
     *     )
     *   ),
     *   security={{"auth": {}}},
     * )
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function checkDataResult(ShiftRequest $request)
    {
        $createShift = $this->repository->checkDataResult($request);
        if ($createShift['status'] != 'success') {
            return $this->responseJsonError($createShift['code'], $createShift['message'], $createShift['message']);
        }
        return $this->responseJson($createShift['code'], $createShift['data'], $createShift['message']);
    }

    /**
     * @OA\Get(
     *   path="/api/shift/export-to-excel",
     *   tags={"Shift"},
     *   summary="Export shift to excel",
     *   operationId="shift_export_to_excel",
     *   @OA\Parameter(
     *     name="date",
     *     in="query",
     *     required=true,
     *     @OA\Schema(
     *      type="string",
     *     ),
     *   ),
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

    public function exportToExcel(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'date' => 'required|date|date_format:Y-m',
            'field' => 'nullable|in:driver_code,flag',
            'sortby' => 'nullable|in:asc,desc',
        ]);
        if ($validator->fails()) {
            return $this->responseJsonError(422, $validator->errors());
        }
        $request['field'] = $request['field'] ?? '';
        $request['sortby'] = $request['sortby'] ?? '';
        $request['type'] = 'default-tab';

        return $this->repository->downloadFile($request);
    }

    /**
     * @OA\Get(
     *   path="/api/shift/grade-tab-to-file",
     *   tags={"Shift"},
     *   summary="Export Grade tab to file",
     *   operationId="grade_tab_to_file",
     *   @OA\Parameter(
     *     name="date",
     *     in="query",
     *     required=true,
     *     @OA\Schema(
     *      type="string",
     *     ),
     *   ),
     *   @OA\Parameter(
     *     name="type",
     *     in="query",
     *     required=true,
     *     example = "grade-tab",
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
     *     name="field",
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
    public function download(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'date' => 'required|date|date_format:Y-m',
                'type' => 'required',
                'sortby' => 'nullable|in:asc,desc',
                'field' => 'nullable',
            ]);
        if ($validator->fails()) {
            return $this->responseJsonError(422, $validator->errors());
        }

        return $this->repository->downloadFile($request->all());
    }
    /**
     * @OA\Get(
     *   path="/api/shift/get-message-response-ai",
     *   tags={"Shift"},
     *   summary="List Shift",
     *   operationId="shift_get_message",
     *   @OA\Response(
     *     response=200,
     *     description="Send request success",
     *     @OA\MediaType(
     *      mediaType="application/json",
     *      example={"code":200,"data":{{"id": 1,"name": "..........."}}}
     *     )
     *   ),
     *    @OA\Parameter(
     *     name="status",
     *     description = "list,new,update,detail",
     *     in="query",
     *     required=false,
     *     @OA\Schema(
     *     type="string",
     *     ),
     *     ),
     *     @OA\Parameter(
     *     name="page",
     *     description = "1",
     *     in="query",
     *     required=false,
     *     @OA\Schema(
     *     type="string",
     *     ),
     *     ),
     *     @OA\Parameter(
     *     name="id",
     *     description = "1",
     *     in="query",
     *     required=false,
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
    public function getMessageAI(ShiftRequest $request)
    {
        return $this->repository->getMessageAI($request);

    }
}
