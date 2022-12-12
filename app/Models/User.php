<?php

namespace App\Models;

use Facade\FlareClient\Http\Response;
use Helper\ResponseService;
use Illuminate\Auth\Authenticatable as AuthenticableTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use PHPUnit\Exception;
use Tymon\JWTAuth\Contracts\JWTSubject;
use DateTimeInterface;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Facades\Log;


class User extends Authenticatable implements JWTSubject
{
    use HasFactory;
    use Notifiable;
    use AuthenticableTrait;
    use SoftDeletes;
    use HasRoles;

    protected $primaryKey = 'id';

    protected $table = 'users';
    protected $guard_name = 'api';
    public $timestamps = true;
    protected $dates = ['deleted_at'];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    const USER_ROLE_ADMIN = 'admin';
    const USER_ROLE_DRIVER = 'driver';


    const USER_CODE = 'user_code';
    const USER_NAME = 'user_name';
    const USER_PASSWORD = 'password';
    const USER_ROLE = 'role';

    protected $fillable = [self::USER_CODE, self::USER_NAME, self::USER_PASSWORD, self::USER_ROLE];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'deleted_at',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
//    protected $casts = [
//        'email_verified_at' => 'datetime',
//        'images' => 'json',
//    ];


    public function getJWTIdentifier()
    {
//        return $this->getAttribute('user_code');
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [
            'guard' => 'api'
        ];
    }

    public function createUser($user_code, $user_name, $password, $role)
    {
        $checkValidateCreateUser = self::checkValidateCreateUser($user_code, $user_name, $password, $role);
        if ($checkValidateCreateUser['status'] != 'success') {
            return ResponseService::responseData($checkValidateCreateUser['code'], $checkValidateCreateUser['status'], $checkValidateCreateUser['message']);
        }
        try {
            $user = new User();
            $user->user_code = $user_code;
            $user->user_name = $user_name;
            $user->password = Hash::make($password);
            $user->role = $role;
            $r = $user->save();
            if (!$r) return ResponseService::responseData(CODE_CREATE_FAILED, 'error', trans('errors.data.create'));
            return ResponseService::responseData(\Illuminate\Http\Response::HTTP_OK, 'success', 'success', $user);
        } catch (\Exception $exception) {
            Log::error('users create' . $exception);
            return ResponseService::responseData(CODE_CREATE_FAILED, 'error', trans('errors.data.create'));
        }
    }

    public function updateUser($id, $user_name = null, $password = null, $role = null)
    {
        $checkValidateUpdateUser = self::checkValidateUpdateUser($id, $user_name, $password, $role);
        if ($checkValidateUpdateUser['status'] != 'success') {
            return ResponseService::responseData($checkValidateUpdateUser['code'], $checkValidateUpdateUser['status'], $checkValidateUpdateUser['message']);
        }
        try {
            $user = $checkValidateUpdateUser['data'];
            if ($user_name) $user->user_name = $user_name;
            if ($password) $user->password = Hash::make($password);
            if ($role) $user->role = $role;
            $r = $user->save();
            if (!$r) return ResponseService::responseData(CODE_CREATE_FAILED, 'error', trans('errors.data.update'));
            return ResponseService::responseData(\Illuminate\Http\Response::HTTP_OK, 'success', 'success', $user);
        } catch (\Exception $exception) {
            Log::error('users update' . $exception);
            return ResponseService::responseData(CODE_CREATE_FAILED, 'error', trans('errors.data.update'));
        }
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    // check base key validate
    private function checkValidateCreateUser($user_code, $user_name, $password, $role)
    {
        if (!$user_code && !$user_name && !$password && !$role) {
            return ResponseService::responseData(\Illuminate\Http\Response::HTTP_UNPROCESSABLE_ENTITY, 'error', '');
        }
        $checkUserCode = User::where('user_code', $user_code)->first();
        if ($checkUserCode) return ResponseService::responseData(\Illuminate\Http\Response::HTTP_UNPROCESSABLE_ENTITY, 'error', 'user_error1');
        $checkRole = self::checkRole($role);
        if ($checkRole['status'] != 'success') {
            return ResponseService::responseData($checkRole['code'], $checkRole['status'], $checkRole['message']);
        }
        return ResponseService::responseData(\Illuminate\Http\Response::HTTP_OK, 'success');
    }
    //check validate update
    private function checkValidateUpdateUser($id, $user_name, $password, $role)
    {
        if (!$user_name && !$id && !$role) {
            return ResponseService::responseData(\Illuminate\Http\Response::HTTP_UNPROCESSABLE_ENTITY, 'error', '');
        }
        $user = User::find($id);
        if (!$user) return ResponseService::responseData(\Illuminate\Http\Response::HTTP_UNPROCESSABLE_ENTITY, 'error', '');
        if ($role) {
            $checkRole = self::checkRole($role);
            if ($checkRole['status'] != 'success') {
                return ResponseService::responseData($checkRole['code'], $checkRole['status'], $checkRole['message']);
            }
        }
        return ResponseService::responseData(\Illuminate\Http\Response::HTTP_OK, 'success', '', $user);
    }
    // check role
    private function checkRole($role)
    {
        if (!in_array($role, [self::USER_ROLE_DRIVER, self::USER_ROLE_ADMIN])) return ResponseService::responseData(\Illuminate\Http\Response::HTTP_UNPROCESSABLE_ENTITY, 'error', 'user_error2');
        return ResponseService::responseData(\Illuminate\Http\Response::HTTP_OK, 'success');
    }


}
