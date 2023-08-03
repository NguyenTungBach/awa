<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\CashOutStatisticalRequest;
use App\Repositories\Contracts\CashOutStatisticalRepositoryInterface;
use App\Http\Resources\BaseResource;
use Illuminate\Http\Response;
use App\Http\Resources\CashOutStatisticalResource;
use App\Exports\CashOutStatisticalExport;
use Maatwebsite\Excel\Facades\Excel;

class CashOutStatisticalController extends Controller
{
    /**
     * var Repository
     */
    protected $repository;

    public function __construct(CashOutStatisticalRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(CashOutStatisticalRequest $request)
    {
        try {
            $result = $this->repository->getAllCashOutStatisticalByDriver($request->all());

            return $this->responseJson(Response::HTTP_OK, CashOutStatisticalResource::collection($result), LIST_SUCCESS);
        } catch (\Exception $exception) {

            return $this->responseJsonError(Response::HTTP_INTERNAL_SERVER_ERROR, LIST_ERROR, $exception->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(CashOutStatisticalRequest $request, $id)
    {
        try {
            $input['driver_id'] = request()->route('driver_cash_out_statistical');
            $input['month_line'] = request()->get('month_line');
            $result = $this->repository->getDetailCashOutStatistical($input);
            if (empty($result)) {
                return $this->responseJsonError(Response::HTTP_NOT_FOUND, ERROR, 'NOT FOUND');
            }

            return $this->responseJson(Response::HTTP_OK, new CashOutStatisticalResource($result), SUCCESS);
        } catch (\Exception $exception) {

            return $this->responseJsonError(Response::HTTP_NOT_FOUND, ERROR, $exception->getMessage());
        }
    }

    public function export(CashOutStatisticalRequest $request)
    {
        try {
            $input = $request->all();

            return Excel::download(new CashOutStatisticalExport($input), '運行情報一覧.xlsx');
        } catch (\Exception $exception) {

            return $this->responseJsonError(Response::HTTP_INTERNAL_SERVER_ERROR, ERROR, $exception->getMessage());
        }
    }
}
