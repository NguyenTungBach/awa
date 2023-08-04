<?php
/**
 * Created by VeHo.
 * Year: 2023-08-01
 */

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CashInRequest;
use App\Repositories\Contracts\CashInRepositoryInterface;
use App\Http\Resources\BaseResource;
use App\Http\Resources\CashInResource;
use Helper\ResponseService;
use Illuminate\Http\Request;

class CashInController extends Controller
{

     /**
     * var Repository
     */
    protected $repository;

    public function __construct(CashInRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @OA\Get(
     *   path="/api/cash-in",
     *   tags={"CashIn"},
     *   summary="List CashIn",
     *   operationId="cash_in_index",
     *   @OA\Response(
     *     response=200,
     *     description="Send request success",
     *     @OA\MediaType(
     *      mediaType="application/json",
     *      example={"code":200,"data":{{"id": 1,"name": "..........."}}}
     *     )
     *   ),
     *  @OA\Parameter(
     *     name="customer_id",
     *     description = "customer_id",
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
    public function index(CashInRequest $request)
    {
        return $this->repository->listCashIn($request);
    }

    /**
     * @OA\Post(
     *   path="/api/cash-in",
     *   tags={"CashIn"},
     *   summary="Add new CashIn",
     *   operationId="cash_in_create",
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
    public function store(CashInRequest $request)
    {
        try {
            $request->merge(['status' => 1]);
            return $this->repository->create($request->all());
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @OA\Get(
     *   path="/api/cash-in/{id}",
     *   tags={"CashIn"},
     *   summary="Detail CashIn",
     *   operationId="cash_in_show",
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
            $data = $this->repository->with("customer")->find($id);
            switch ($data->customer->closing_date){
                case 1:
                    $data->customer->closing_dateName = trans("customers.closing_date_lang.1");
                    break;
                case 2:
                    $data->customer->closing_dateName = trans("customers.closing_date_lang.2");
                    break;
                case 3:
                    $data->customer->closing_dateName = trans("customers.closing_date_lang.3");
                    break;
                case 4:
                    $data->customer->closing_dateName = trans("customers.closing_date_lang.4");
                    break;
            }
            return $this->responseJson(200, new BaseResource($data));
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @OA\Post(
     *   path="/api/cash-in/{id}",
     *   tags={"CashIn"},
     *   summary="Update CashIn",
     *   operationId="cash_in_update",
     *   @OA\Parameter(
     *     name="id",
     *     in="path",
     *     required=true,
     *     @OA\Schema(
     *      type="string",
     *     ),
     *   ),
     *   @OA\RequestBody(
     *       @OA\MediaType(
     *          mediaType="application/json",
     *          example={"name":"string"},
     *          @OA\Schema(
     *            required={"name"},
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
    public function update(CashInRequest $request, $id)
    {
        $attributes = $request->except([]);
        return $this->repository->update($attributes, $id);
    }

    /**
     * @OA\Delete(
     *   path="/api/cash-in/{id}",
     *   tags={"CashIn"},
     *   summary="Delete CashIn",
     *   operationId="cash_in_delete",
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
        return $this->repository->delete($id);
    }
}
