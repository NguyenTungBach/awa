<?php
/**
 * Created by VeHo.
 * Year: 2023-07-25
 */

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\FinalClosingHistoriesRequest;
use App\Repositories\Contracts\FinalClosingHistoriesRepositoryInterface;
use App\Http\Resources\BaseResource;
use App\Http\Resources\FinalClosingHistoriesResource;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Http\Response;

class FinalClosingHistoriesController extends Controller
{
    /**
     * var Repository
     */
    protected $repository;

    public function __construct(FinalClosingHistoriesRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @OA\Get(
     *   path="/api/final-closing",
     *   tags={"FinalClosingHistories"},
     *   summary="List FinalClosingHistories",
     *   operationId="final_closing_histories_index",
     *   @OA\Response(
     *     response=200,
     *     description="Send request success",
     *     @OA\MediaType(
     *      mediaType="application/json",
     *      example={"code":200,"data":{{"id": 1,"name": "..........."}}}
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="page",
     *     in="query",
     *     @OA\Schema(
     *      type="integer",
     *     ),
     *   ),
     *   @OA\Parameter(
     *     name="per_page",
     *     in="query",
     *     @OA\Schema(
     *      type="integer",
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
    public function index(FinalClosingHistoriesRequest $request)
    {
        try {
            $result = $this->repository->getAll($request->all());

            return $this->responseJson(Response::HTTP_OK, FinalClosingHistoriesResource::collection($result), LIST_SUCCESS);
        } catch (\Exception $exception) {

            return $this->responseJsonError(Response::HTTP_INTERNAL_SERVER_ERROR, LIST_ERROR, $exception->getMessage());
        }
    }

    /**
     * @OA\Post(
     *   path="/api/final-closing",
     *   tags={"FinalClosingHistories"},
     *   summary="Add new FinalClosingHistories",
     *   operationId="final_closing_histories_create",
     *   @OA\Parameter(name="name", in="query", required=true,
     *     @OA\Schema(type="string"),
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
    public function store(FinalClosingHistoriesRequest $request)
    {
        try {
            $request['date'] = Carbon::now()->format('Y-m-d');
            $result = $this->repository->createFinalClosing($request->all());
            if (empty($result)) {
                return $this->responseJsonError(Response::HTTP_NOT_FOUND, CREATE_ERROR, 'NOT FOUND DRIVER IN DRIVER COURSE');
            }

            return $this->responseJson(Response::HTTP_OK, new FinalClosingHistoriesResource($result), CREATE_SUCCESS);
        } catch (\Exception $exception) {

            return $this->responseJsonError(Response::HTTP_INTERNAL_SERVER_ERROR, CREATE_ERROR, $exception->getMessage());
        }
    }

    /**
     * @OA\Get(
     *   path="/api/final-closing/{id}",
     *   tags={"FinalClosingHistories"},
     *   summary="Detail FinalClosingHistories",
     *   operationId="final_closing_histories_show",
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

            return $this->responseJson(Response::HTTP_OK, new FinalClosingHistoriesResource($result), SUCCESS);
        } catch (\Exception $exception) {

            return $this->responseJsonError(Response::HTTP_NOT_FOUND, ERROR, $exception->getMessage());
        }
    }

    /**
     * @OA\Delete(
     *   path="/api/final-closing/{id}",
     *   tags={"FinalClosingHistories"},
     *   summary="Delete FinalClosingHistories",
     *   operationId="final_closing_histories_delete",
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
        $result =  $this->repository->deleteFinalClosing($id);
        if ($result) {
            return $this->responseJson(Response::HTTP_OK, $result, DELETE_SUCCESS);
        }

        return $this->responseJsonError(Response::HTTP_METHOD_NOT_ALLOWED, DELETE_ERROR);
    }

    /**
     * @OA\Get(
     *   path="/api/final-closing/check-final-closing",
     *   tags={"FinalClosingHistories"},
     *   summary="Check FinalClosingHistories",
     *   operationId="check_final_closing_histories",
     *   @OA\Parameter(
     *     name="month_year",
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
    public function checkFinalClosing(FinalClosingHistoriesRequest $request)
    {
        return $this->repository->checkFinalClosing($request);
    }
}
