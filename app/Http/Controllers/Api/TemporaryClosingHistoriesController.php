<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\TemporaryClosingHistoriesRequest;
use App\Repositories\Contracts\TemporaryClosingHistoriesRepositoryInterface;
use App\Http\Resources\BaseResource;
use App\Http\Resources\TemporaryClosingHistoriesResource;
use Illuminate\Http\Response;
use Carbon\Carbon;

class TemporaryClosingHistoriesController extends Controller
{
    /**
     * var Repository
     */
    protected $repository;

    public function __construct(TemporaryClosingHistoriesRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(TemporaryClosingHistoriesRequest $request)
    {
        try {
            $result = $this->repository->getAll($request->all());

            return $this->responseJson(Response::HTTP_OK, TemporaryClosingHistoriesResource::collection($result), LIST_SUCCESS);
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
    public function store(TemporaryClosingHistoriesRequest $request)
    {
        try {
            $request['date'] = Carbon::now()->format('Y-m-d');
            $result = $this->repository->createTemporaryClosing($request->all());
            if (empty($result)) {
                return $this->responseJsonError(Response::HTTP_NOT_FOUND, CREATE_ERROR, 'NOT FOUND DRIVER IN DRIVER COURSE');
            }

            return $this->responseJson(Response::HTTP_OK, new TemporaryClosingHistoriesResource($result), CREATE_SUCCESS);
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
            $result = $this->repository->getDetail($id);

            return $this->responseJson(Response::HTTP_OK, new TemporaryClosingHistoriesResource($result), SUCCESS);
        } catch (\Exception $exception) {

            return $this->responseJsonError(Response::HTTP_NOT_FOUND, ERROR, $exception->getMessage());
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
        $result =  $this->repository->deleteTemporaryClosing($id);
        if ($result) {
            return $this->responseJson(Response::HTTP_OK, $result, DELETE_SUCCESS);
        }

        return $this->responseJsonError(Response::HTTP_METHOD_NOT_ALLOWED, DELETE_ERROR);
    }
}
