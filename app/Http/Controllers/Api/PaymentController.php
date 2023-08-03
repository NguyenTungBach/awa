<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\PaymentRequest;
use App\Repositories\Contracts\PaymentRepositoryInterface;
use App\Http\Resources\PaymentResource;
use Illuminate\Http\Response;

class PaymentController extends Controller
{
    /**
     * var Repository
     */
    protected $repository;

    public function __construct(PaymentRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(PaymentRequest $request)
    {
        try {
            $result = $this->repository->getAll($request->all());

            return $this->responseJson(Response::HTTP_OK, PaymentResource::collection($result), LIST_SUCCESS);
        } catch (\Exception $exception) {

            return $this->responseJsonError(Response::HTTP_INTERNAL_SERVER_ERROR, LIST_ERROR, $exception->getMessage());
        }
    }
}
