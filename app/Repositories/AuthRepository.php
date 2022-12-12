<?php

namespace Repository;

use App\Repositories\Contracts\AuthRepositoryInterface;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Helper\ResponseService;
use Illuminate\Http\Response;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;

class AuthRepository  implements AuthRepositoryInterface
{
    public function __construct()
    {
    }

    /**
     *
     * Handle action login of user.
     *
     * @param LoginRequest $request
     * @param null $guard
     * @return array
     */
    public function doLogin(LoginRequest $request, $guard = null): array{
        $input = $request->only('user_code', 'password');
        $attempt = JWTAuth::attempt($input);
        if ($attempt) {
            $user = User::where('user_code', $request->user_code)->first(['id', 'user_code', 'user_name','role']);
            $data['user'] = $user;
            $data['attempt'] = $attempt;
            return ResponseService::responseData(Response::HTTP_OK,'success','Success',$data);
        } else {
            return ResponseService::responseData(Response::HTTP_NOT_FOUND,trans('auth.failed'),'Errors');
        }
    }
    /**
     * @param array $params
     * @return bool|void
     */
}
