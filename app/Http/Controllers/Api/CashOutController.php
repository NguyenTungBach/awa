<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\CashOutRequest;
use App\Http\Resources\CashOutResource;
use App\Http\Resources\BaseResource;
use Illuminate\Http\Response;
use App\Repositories\Contracts\CashOutRepositoryInterface;

class CashOutController extends Controller
{
    /**
     * var Repository
     */
    protected $repository;

    public function __construct(CashOutRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(CashOutRequest $request)
    {
        try {
            $request['driver_id'] = $request->route('driver');
            $result = $this->repository->getAllCashOutByDriver($request->all());

            return $this->responseJson(Response::HTTP_OK, CashOutResource::collection($result), LIST_SUCCESS);
        } catch (\Exception $exception) {

            return $this->responseJsonError(Response::HTTP_INTERNAL_SERVER_ERROR, LIST_ERROR, $exception->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CashOutRequest $request)
    {
        try {
            $request['driver_id'] = $request->route('driver');
            $result = $this->repository->createCashOutByDriver($request->all());

            return $this->responseJson(Response::HTTP_OK, new CashOutResource($result), CREATE_SUCCESS);
        } catch (\Exception $exception) {

            return $this->responseJsonError(Response::HTTP_INTERNAL_SERVER_ERROR, CREATE_ERROR, $exception->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $input['driver_id'] = request()->route('driver');
            $input['cash_out_id'] = request()->route('cash_out');
            $result = $this->repository->getDetailCashOutByDriver($input);
            if (empty($result)) {
                return $this->responseJsonError(Response::HTTP_NOT_FOUND, ERROR, 'NOT FOUND');
            }

            return $this->responseJson(Response::HTTP_OK, new CashOutResource($result), SUCCESS);
        } catch (\Exception $exception) {

            return $this->responseJsonError(Response::HTTP_NOT_FOUND, ERROR, $exception->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CashOutRequest $request, $id)
    {
        try {
            $request['driver_id'] = request()->route('driver');
            $request['cash_out_id'] = request()->route('cash_out');
            $result = $this->repository->updateCashOutByDriver($request->all());
            if (empty($result)) {
                return $this->responseJsonError(Response::HTTP_NOT_FOUND, ERROR, 'NOT FOUND');
            }

            return $this->responseJson(Response::HTTP_OK, $result, UPDATE_SUCCESS);
        } catch (\Exception $exception) {

            return $this->responseJsonError(Response::HTTP_INTERNAL_SERVER_ERROR, UPDATE_ERROR, $exception->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $input['driver_id'] = request()->route('driver');
            $input['cash_out_id'] = request()->route('cash_out');
            $result = $this->repository->deleteCashOutByDriver($input);
            if (empty($result)) {
                return $this->responseJsonError(Response::HTTP_NOT_FOUND, ERROR, 'NOT FOUND');
            }

            return $this->responseJson(Response::HTTP_OK, $result, DELETE_SUCCESS);
        } catch (\Exception $exception) {

            return $this->responseJsonError(Response::HTTP_INTERNAL_SERVER_ERROR, DELETE_ERROR, $exception->getMessage());
        }
    }
}
