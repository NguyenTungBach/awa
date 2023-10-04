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
use Illuminate\Support\Facades\Log;

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

    public function getAll($input)
    {
        $input['order_by'] = Arr::get($input, 'order_by', 'id');
        $input['sort'] = Arr::get($input, 'sort', 'desc');

        $data = [];
        $users = User::orderBy($input['order_by'], $input['sort']);
        $users = $users->get();
        foreach ($users as $key => $value) {
            $data[$key]['id'] = $value->id;
            $data[$key]['user_code'] = $value->user_code;
            $data[$key]['user_name'] = $value->user_name;
            $data[$key]['role'] = __('users.role_lang.' . $value->role);
        }
        $result = $data;

        return $result ?? [];
    }

    public function getDetail($id)
    {
        $result = UserRepository::find($id);

        return $result;
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

    public function create($attributes)
    {
        //Kiểm tra user_code này đã từng bị xóa hay chưa. Nếu có thì dùng luôn
        $checkDriverByUserCode = User::where('user_code',$attributes['user_code'])->whereNotNull('deleted_at')->withoutGlobalScopes()->first();
        if ($checkDriverByUserCode){
            $checkDriverByUserCode->user_name = $attributes['user_name'];
            $checkDriverByUserCode->password = Hash::make($attributes['password']);
            $checkDriverByUserCode->role = $attributes['role'];
            $checkDriverByUserCode->status = 1;
            $checkDriverByUserCode->created_at = Carbon::now();
            $checkDriverByUserCode->updated_at = null;
            $checkDriverByUserCode->deleted_at = null;
            $checkDriverByUserCode->save();
            $user = $attributes;
        } else{
            $user = User::create([
                'user_code' => $attributes['user_code'],
                'user_name' => $attributes['user_name'],
                'password' => Hash::make($attributes['password']),
                'role' => $attributes['role'],
            ]);
        }
        return $user;
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

    public function updateUser($request, $id)
    {
        if(!empty($request['password'])) {
            $request['password'] = Hash::make($request['password']);
        }
        $result = UserRepository::update($request, $id);

        return $result;
    }

    public function destroy($id)
    {
        if(auth()->user()->id == $id) {
            return false;
        }
        $result = UserRepository::find($id)->delete();

        return $result;
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
