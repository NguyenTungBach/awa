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
     * @OA\Get(
     *   path="/api/temporary-closing",
     *   tags={"Temporary"},
     *   summary="List Temporary",
     *   operationId="temporary_closing_index",
     *   @OA\Response(
     *     response=200,
     *     description="Send request success",
     *     @OA\MediaType(
     *      mediaType="application/json",
     *      example={"code":200,"data":{{"id": 1,"name": "..........."}}}
     *     )
     *   ),
     *  @OA\Parameter(
     *     name="date",
     *     description = "Y-m-d",
     *     in="path",
     *     required=true,
     *     @OA\Schema(
     *     type="string",
     *     ),
     *     ),
     *  @OA\Parameter(
     *     name="month_year",
     *     description = "Y-m",
     *     in="path",
     *     required=true,
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
     * @OA\Post(
     *   path="/api/temporary-closing",
     *   tags={"Temporary"},
     *   summary="Create Temporary",
     *   operationId="temporary_closing_create",
     *   @OA\RequestBody(
     *       @OA\MediaType(
     *          mediaType="application/json",
     *          example={"date": "2023-09-29","month_year": "2023-09"},
     *          @OA\Schema(
     *            required={"date","month_year"},
     *            @OA\Property(
     *              property="date",
     *              format="string",
     *              description="Y-m-d"
     *            ),
     *            @OA\Property(
     *              property="month_year",
     *              format="string",
     *              description="Y-m"
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
     * @OA\Get(
     *   path="/api/temporary-closing/{id}",
     *   tags={"Temporary"},
     *   summary="Detail Temporary",
     *   operationId="temporary_closing_show",
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

            return $this->responseJson(Response::HTTP_OK, new TemporaryClosingHistoriesResource($result), SUCCESS);
        } catch (\Exception $exception) {

            return $this->responseJsonError(Response::HTTP_NOT_FOUND, ERROR, $exception->getMessage());
        }
    }

    /**
     * @OA\Delete(
     *   path="/api/temporary-closing/{id}",
     *   tags={"Temporary"},
     *   summary="Delete Temporary",
     *   operationId="temporary_closing_delete",
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
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
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
