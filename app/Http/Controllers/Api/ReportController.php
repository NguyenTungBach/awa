<?php
/**
 * Created by VeHo.
 * Year: 2022-07-29
 */

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReportRequest;
use App\Models\Report;
use App\Repositories\Contracts\ReportRepositoryInterface;
use App\Http\Resources\BaseResource;
use App\Http\Resources\ReportResource;
use Illuminate\Http\Request;

class ReportController extends Controller
{

     /**
     * var Repository
     */
    protected $repository;

    public function __construct(ReportRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @OA\Get(
     *   path="/api/parctical-performance",
     *   tags={"Report"},
     *   summary="List Report",
     *   operationId="report_index",
     *   @OA\Response(
     *     response=200,
     *     description="Send request success",
     *     @OA\MediaType(
     *      mediaType="application/json",
     *      example={"code":200,"data":{{"id": 1,"name": "..........."}}}
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="status_view",
     *     description="month or fix time, default is month",
     *     in="query",
     *     @OA\Schema(
     *      type="string",
     *     ),
     *   ),
     *   @OA\Parameter(
     *     name="view_date",
     *     description="ex: 2022-10",
     *     in="query",
     *     @OA\Schema(
     *      type="date",
     *     ),
     *   ),
     *   @OA\Parameter(
     *     name="sortby_code",
     *     description="asc | desc",
     *     in="query",
     *     @OA\Schema(
     *      type="string",
     *     ),
     *   ),
     *   @OA\Parameter(
     *     name="sortby_driver_type",
     *     description="asc | desc",
     *     in="query",
     *     @OA\Schema(
     *      type="string",
     *     ),
     *   ),
     *   @OA\Parameter(
     *     name="sync",
     *     description="Sync data from AI",
     *     in="query",
     *     @OA\Schema(
     *      type="string",
     *     ),
     *   ),
     *   security={{"auth": {}}},
     * )
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(ReportRequest $request)
    {
        $list = $this->repository->getList($request);
        if ($list['status'] != 'success') {
            return $this->responseJsonError($list['code'], $list['message']);
        }
        if (!isset($list['data'])) {
            $list['data'] = [];
            return $this->responseJson($list['code'], $list['data']);
        }

        return $this->responseJson($list['code'], ReportResource::collection($list['data']));
    }
    /**
     * @OA\Get(
     *   path="/api/parctical-performance/export-to-excel",
     *   tags={"Report"},
     *   summary="Export list performance to excel",
     *   operationId="performance_export",
     *   @OA\Parameter(
     *     name="sortby_code",
     *     in="query",
     *     required=false,
     *     @OA\Schema(
     *      type="string",
     *     ),
     *   ),
     *   @OA\Parameter(
     *     name="sortby_driver_type",
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
     *   @OA\Parameter(
     *     name="status_view",
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
        $sortByCode = isset($request['sortby_code']) ? strtolower($request['sortby_code']):'';
        $sortByDriverType = isset($request['sortby_driver_type']) ? strtolower($request['sortby_driver_type']): '';
        $viewDate = isset($request['view_date']) && strtotime($request['view_date']) ? strtotime($request['view_date']):strtotime(date('Y-m'));

        return $this->repository->downloadFileExported($viewDate, $sortByCode, $sortByDriverType);

    }

}
