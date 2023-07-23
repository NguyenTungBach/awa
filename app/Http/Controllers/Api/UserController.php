<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\UserRequest;
use App\Http\Resources\BaseResource;
use App\Http\Resources\UserResource;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Kreait\Firebase\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Arr;
use Helper\ResponseService;

class UserController extends BaseController
{
    protected $repository;
    protected $userRepository;

    public function __construct(UserRepository $repository)
    {
        //$this->middleware(['role_or_permission:' . ROLE_HEADQUARTER]);
        $this->repository = $repository;
    }

    /**
     * @OA\Get(
     *   path="/api/user",
     *   tags={"User"},
     *   summary="List user",
     *   operationId="user_index",
     *   @OA\Response(
     *     response=200,
     *     description="response success",
     *     @OA\MediaType(
     *       mediaType="application/json",
     *        example={"code": 200,"data": {{"id": 1,"user_name": "Nguyen Tien Nam","user_code": 111111,"roles": {"headquater","admin"},"department_id": 1},{"id": 2,"user_name": "Vu Duck Viet","user_code": 222222,"roles": {"headquater"},"department_id": 1}}}
     *     )
     *   ),
     *  @OA\Parameter(
     *     name="field",
     *     description = "user_code,user_name,role",
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
     *     type="string",
     *     ),
     *     ),
     *  @OA\Response(
     *     response=401,
     *     description="Unauthorized",
     *     @OA\MediaType(
     *      mediaType="application/json",
     *      example={"code":401,"message":"Wrong account or password"}
     *     )
     *   ),
     *   @OA\Response(
     *     response=403,
     *     description="Deny access",
     *     @OA\MediaType(
     *      mediaType="application/json",
     *      example={"code":403,"message":"Từ chối quyền truy cập"}
     *     )
     *   ),
     *   security={{"auth": {}}},
     * )
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(UserRequest $request)
    {
        try {
            $result = $this->repository->getAll($request->all());

            return $this->responseJson(Response::HTTP_OK, UserResource::collection($result), LIST_SUCCESS);
        } catch (\Exception $exception) {

            return $this->responseJsonError(Response::HTTP_INTERNAL_SERVER_ERROR, LIST_ERROR, $exception->getMessage());
        }
    }

    /**
     * @OA\Post(
     *   path="/api/user",
     *   tags={"User"},
     *   summary="Add create",
     *   operationId="user_create",
     * @OA\RequestBody(
     *       description="role = 'admin|driver'",
     *       @OA\MediaType(
     *          mediaType="application/json",
     *          example={"user_name": "Admin","user_code": "111111","password": "abc12345678","role": "admin"},
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
     *     description="",
     *     @OA\MediaType(
     *      mediaType="application/json",
     *      example={"code":200,"data":{{"id":1,
     *     "role":"admin",
     *     "name":"Nguyen",
     *     "id":"1",
     *     "created_at":1570031021}}}
     *     )
     *   ),
     *   security={{"auth": {}}},
     * )
     * @param UserRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function store(UserRequest $request)
    {
        try {
            $result = $this->repository->create($request->all());

            return $this->responseJson(Response::HTTP_OK, new UserResource($result), CREATE_SUCCESS);
        } catch (\Exception $exception) {

            return $this->responseJsonError(Response::HTTP_INTERNAL_SERVER_ERROR, CREATE_ERROR, $exception->getMessage());
        }
    }

    /**
     * @OA\Get(
     *   path="/api/user/{id}",
     *   tags={"User"},
     *   summary="User detail",
     *   operationId="user_show",
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
     *     description="Submit request successfully",
     *     @OA\MediaType(
     *       mediaType="application/json",
     *     example={"code": 200,"data": {"id": 1,"user_name": "Nguyen","user_code": 111111,"role": "admin"}}
     *     )
     *   ),
     *   @OA\Response(
     *     response=401,
     *     description="Unauthorized",
     *     @OA\MediaType(
     *      mediaType="application/json",
     *      example={"code":401,"message":"Not login"}
     *     )
     *   ),
     *   @OA\Response(
     *     response=403,
     *     description="Deny access",
     *     @OA\MediaType(
     *      mediaType="application/json",
     *      example={"code":403,"message":"Access deny permission"}
     *     )
     *   ),
     *   security={{"auth": {}}},
     * )
     * Display a listing of the resource.
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */

    public function show($id)
    {
        try {
            $result = $this->repository->getDetail($id);

            return $this->responseJson(Response::HTTP_OK, new UserResource($result), SUCCESS);
        } catch (\Exception $exception) {

            return $this->responseJsonError(Response::HTTP_NOT_FOUND, ERROR, $exception->getMessage());
        }
    }

    /**
     * @OA\Put(
     *   path="/api/user/{id}",
     *   tags={"User"},
     *   summary="Update user",
     *   operationId="user_update",
     * @OA\Parameter(
     *     name="id",
     *     in="path",
     *     required=false,
     *     @OA\Schema(
     *      type="integer",
     *     ),
     *   ),
     * @OA\RequestBody(
     *      description="role = 'admin|driver'",
     *      description="password = 'null|446nbbbd'",
     *       @OA\MediaType(
     *          mediaType="application/json",
     *          example={"user_name": "Nguyen","password": "111111","role": "admin"},
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
     *     description="success",
     *     @OA\MediaType(
     *      mediaType="application/json",
     *       example={"code":200,"message": "i18n"}
     *     )
     *   ),
     *   @OA\Response(
     *     response=401,
     *     description="Unauthorized",
     *     @OA\MediaType(
     *      mediaType="application/json",
     *      example={"code":401,"message":"Chưa đăng nhập"}
     *     )
     *   ),
     *   @OA\Response(
     *     response=403,
     *     description="Từ chối quyền truy cập",
     *     @OA\MediaType(
     *      mediaType="application/json",
     *      example={"code":403,"message":"Từ chối quyền truy cập"}
     *     )
     *   ),
     *   security={{"auth": {}}},
     * )
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(UserRequest $request, $id)
    {
        try {
            $user = $this->repository->find($id);
            $result = $this->repository->update($request->all(), $id);

            return $this->responseJson(Response::HTTP_OK, $result, UPDATE_SUCCESS);
        } catch (\Exception $exception) {

            return $this->responseJsonError(Response::HTTP_INTERNAL_SERVER_ERROR, UPDATE_ERROR, $exception->getMessage());
        }
    }

    /**
     * @OA\Delete(
     *   path="/api/user/{id}",
     *   tags={"User"},
     *   summary="Delete ..............",
     *   operationId="user_delete",
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
     *     description="Send request Success",
     *     @OA\MediaType(
     *      mediaType="application/json",
     *      example={"code":200,"data":"Send request Success"}
     *     )
     *   ),
     *   security={{"auth": {}}},
     * )
     * */
    public function destroy($id)
    {
        $result =  $this->repository->destroy($id);
        if ($result) {
            return $this->responseJson(Response::HTTP_OK, $result, DELETE_SUCCESS);
        }

        return $this->responseJsonError(Response::HTTP_METHOD_NOT_ALLOWED, DELETE_ERROR);
    }

    /**
     * @OA\Post(
     *   path="/api/sync/user",
     *   tags={"User"},
     *   summary="sync user",
     *   operationId="user_sync_update",
     *   @OA\RequestBody(
     *       @OA\MediaType(
     *          mediaType="application/json",
     *          example={"user_name": "Nguyen Tien Nam","user_code": 111111,"password": null,"department_id": 1, "deleted_at": 1},
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
     *     description="success",
     *     @OA\MediaType(
     *      mediaType="application/json",
     *       example={"code":200,"message": "i18n"}
     *     )
     *   ),
     *   @OA\Response(
     *     response=401,
     *     description="Unauthorized",
     *     @OA\MediaType(
     *      mediaType="application/json",
     *      example={"code":401,"message":"Chưa đăng nhập"}
     *     )
     *   ),
     *   @OA\Response(
     *     response=403,
     *     description="Từ chối quyền truy cập",
     *     @OA\MediaType(
     *      mediaType="application/json",
     *      example={"code":403,"message":"Từ chối quyền truy cập"}
     *     )
     *   ),
     * )
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function sync(UserRequest $request)
    {
        try {
            $this->repository->syncUser($request->all());
            return $this->responseJson(CODE_SUCCESS, null, trans('messages.mes.update_success'));
        } catch (\Exception $e) {
            return $this->responseJson(CODE_CREATE_FAILED, null, trans('messages.mes.update_error'));
        }
    }

    /**
     * @OA\Post(
     *   path="/api/user/save-token-fcm",
     *   tags={"User"},
     *   summary="save token fcm for user",
     *   operationId="user_register_token_fcm",
     *   @OA\RequestBody(
     *       @OA\MediaType(
     *          mediaType="multipart/form-data",
     *          @OA\Schema(
     *            required={"token"},
     *            @OA\Property(
     *              property="token",
     *              format="string",
     *              description="token fcm",
     *              example="xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx",
     *            ),
     *            @OA\Property(
     *              property="token_old",
     *              format="string",
     *              description="token old fcm",
     *              example="xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx",
     *            ),
     *            @OA\Property(
     *              property="isLogOut",
     *              format="boolean",
     *              description="Is user log out to app",
     *              example="false",
     *            ),
     *         )
     *      )
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="",
     *     @OA\MediaType(
     *      mediaType="application/json",
     *      example={"code":200,"data":{"access_token":"","profile":{"id":1,
     *     "role":null,
     *     "name":null,
     *     "id":"example@gmail.com",
     *     "created_at":1570031021}}}
     *     )
     *   ),
     *   security={{"auth": {}}},
     * )
     * @param UserRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function saveTokenFCM(UserRequest $request)
    {
        if ($this->repository->saveTokenFCM($request->get('token'), $request->get('token_old'), $request->get('isLogOut'))) {
            return $this->responseJson(CODE_SUCCESS, null, trans('messages.mes.create_success'));
        } else {
            return $this->responseJson(CODE_CREATE_FAILED, null, trans('messages.mes.create_error'));
        }
    }
}
