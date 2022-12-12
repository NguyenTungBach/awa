<?php


namespace App\Repositories;

use App\Http\Requests\UserRequest;
use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Carbon\Carbon;
use Helper\Common;
use Helper\ResponseService;
use Illuminate\Foundation\Application;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Repository\BaseRepository;
use Spatie\Permission\Models\Role;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    public function __construct(Application $app)
    {
        parent::__construct($app);
    }

    public function model()
    {
        return User::class;
    }

    public function getAll()
    {
        // return User::with('roles')->find($id);
    }

    public function getOne($id)
    {
        $user = $this->model->find($id);
        if (!$user) return ResponseService::responseData(Response::HTTP_METHOD_NOT_ALLOWED, 'error', trans('errors.data_not_found'));
        return ResponseService::responseData(Response::HTTP_OK, 'success', 'success', $user);
    }

    public function getOneByCode($userCode)
    {
        return $this->model->find($userCode);
    }

    public function getPagination(UserRequest $request)
    {
        $sortby = isset($request['sortby']) ? $request['sortby'] : null;
        $field = isset($request['field']) ? $request['field'] : null;
        $checkValidateParamIndex = $this->checkValidateParamIndex($sortby, $field);
        if ($checkValidateParamIndex['status'] != 'success') {
            return ResponseService::responseData($checkValidateParamIndex['code'], $checkValidateParamIndex['status'], $checkValidateParamIndex['message']);
        }
        $listUser = User::query();
        if ($sortby && $field) {
            $listUser = $listUser->orderBy($field, $sortby);
        }
        $listUser = $listUser->get();
        return ResponseService::responseData(Response::HTTP_OK, 'success', 'success', $listUser);
    }

    /**
     * @param array $attributes
     * $attributes = ['user_code','user_name','password','role']
     * @return array|mixed|null
     */
    public function create(array $attributes)
    {
        $user_name = $attributes['user_name'];
//        $user_code = $attributes['user_code'];
        $user_code = Common::convertDataCodeInput($attributes['user_code'],4);
        $password = $attributes['password'];
        $role = $attributes['role'];
        $user = parent::createUser($user_code, $user_name, $password, $role);
        return ResponseService::responseData($user['code'], $user['status'], $user['message'], $user['data']);
    }

    public function saveTokenFCM($token, $token_old, $isLogOut = false)
    {
        $messaging = app('firebase.messaging');
        $user_code = auth()->user()->user_code;

        $department_id = auth()->user()->department_id;
        if ($isLogOut) {
            $messaging->unsubscribeFromAllTopics(['izumi_chat_topic_' . $department_id, 'izumi_notice_topic'], [$token]);
            UserFcmToken::where('token', $token)->where('user_code', $user_code)->delete();
            return true;
        }

        try {
            $resut = $messaging->subscribeToTopics(['izumi_chat_topic_' . $department_id, 'izumi_notice_topic'], [$token]);
            $checkSub = $resut['izumi_notice_topic'][$token];
            if ($checkSub !== "OK") {
                return [];
            }
            if ($token_old) {
                $userFcmToken = UserFcmToken::where('token', $token_old)->where('user_code', $user_code)->first();
                if ($userFcmToken) {
                    $userFcmToken->token = $token;
                    $userFcmToken->save();
                }
                $messaging->unsubscribeFromAllTopics(['izumi_chat_topic_' . $department_id, 'izumi_notice_topic'], [$token_old]);
            } else {
                $userFcmToken = UserFcmToken::where('token', $token)->where('user_code', $user_code)->first();
                if (!$userFcmToken) {
                    $userFcmToken = UserFcmToken::create([
                        "user_code" => $user_code,
                        "token" => $token
                    ]);
                }
            }
            return $userFcmToken;
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * @param array $request
     * $request = ['user_name','password','role'];
     * @param $id
     * @return array|mixed|null
     */
    public function update(array $request, $id)
    {
        $user_name = $request['user_name'];
        $password = isset($request['password'])?$request['password']:null;
        $role = $request['role'];
        $user = parent::updateUser($id, $user_name, $password, $role);
        return ResponseService::responseData($user['code'], $user['status'], $user['message'], $user['data']);
    }

    public function destroy($id)
    {
        $userLogin = \Illuminate\Support\Facades\Auth::user();
        if ($userLogin->id == $id)return ResponseService::responseData(Response::HTTP_METHOD_NOT_ALLOWED, 'error', trans('errors.data.me'));
        $user = $this->model->find($id);
        if (!$user) return ResponseService::responseData(Response::HTTP_METHOD_NOT_ALLOWED, 'error', trans('errors.data_not_found'));
        $user->delete();
        return ResponseService::responseData(Response::HTTP_OK, 'success', trans('messages.mes.delete_success'));
    }

    public function syncUser($listUser)
    {
        $departments = Department::pluck('name', 'id')->toArray();
        foreach ($listUser as $userRq) {
            $user_name = Arr::get($userRq, 'user_name');
            $user_code = Arr::get($userRq, 'user_code');
            $password = Arr::get($userRq, 'password');
            $department_id = Arr::get($userRq, 'department_id');
            $deleted_at = Arr::get($userRq, 'deleted_at', null);
            if ($deleted_at) {
                $deleted_at = Carbon::parse($deleted_at);
            }

            $user = UserSync::where('user_code', $user_code)->first();
            if ($user) {
                $user->user_name = $user_name;
                $user->password = $password;
                $user->department_id = $department_id;
                $user->deleted_at = $deleted_at;
                $user->save();
            } else {
                $user = UserSync::create([
                    'user_code' => $user_code,
                    'department_id' => $department_id,
                    'user_name' => $user_name,
                    'password' => $password,
                    'deleted_at' => $deleted_at,
                ]);
            }
            //auto map group chat user
            if ($user) {
                if (array_key_exists($department_id, $departments)) {
                    $name_group = $departments[$department_id];
                    $groupChat = GroupChat::where('name', $name_group)->first();
                    if ($groupChat) {
                        $groupChat->group_chat_user()->firstOrCreate([
                            'user_id' => $user->id,
                        ]);
                    }
                }
            }
        }
        return true;
    }

    private function checkValidateParamIndex($sortby, $field)
    {
        $arrayList = ['user_code', 'user_name', 'role'];
        $arraySortby = ['asc', 'desc'];
        if ($field && !in_array($field, $arrayList)) return ResponseService::responseData(Response::HTTP_UNPROCESSABLE_ENTITY, 'error', trans('errors.sort_by.index', $arrayList));
        if ($sortby && !in_array($sortby, $arraySortby)) return ResponseService::responseData(Response::HTTP_UNPROCESSABLE_ENTITY, 'error', trans('errors.sort_by.index', $arraySortby));
        return ResponseService::responseData(Response::HTTP_OK, 'success', '');
    }
}
