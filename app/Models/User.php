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

    protected $fillable = [
        'user_code',
        'user_name',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'jwt_active',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */

    public function getJWTIdentifier()
    {
        // return $this->getAttribute('user_code');
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
}
