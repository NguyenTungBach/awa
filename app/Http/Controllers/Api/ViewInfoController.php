<?php

/**
 * Created by VeHo.
 * Year: 2022-04-08
 */

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\Notices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\MessageView;

class ViewInfoController extends Controller
{

    /**
     * var Repository
     */
    protected $repository;

    public function __construct()
    {
    }

//    /**
//     * @OA\Post(
//     *   path="/api/mobile/view-notice-and-message",
//     *   tags={"
// * Mobile-App view notice and message"},
//     *   summary="view count left notice and message",
//     *   operationId="notices_message_left",
//     *   @OA\Response(
//     *     response=200,
//     *     description="Send request success",
//     *     @OA\MediaType(
//     *      mediaType="application/json",
//     *      example={"code":200,"message":"i18n.key.updated.success"}
//     *     ),
//     *   ),
//     *   @OA\Response(
//     *     response=403,
//     *     description="Access Deny permission",
//     *     @OA\MediaType(
//     *      mediaType="application/json",
//     *      example={"code":403,"message":"Access Deny permission"}
//     *     ),
//     *   ),
//     *   security={{"auth": {}}},
//     * )
//     * Display a listing of the resource.
//     *
//     * @return mixed
//     */
//    public function viewCount(Request $request)
//    {
//        $user_id = $request->user()->id;
//        $unreadMessage = 0;
//        $notice_viewers = DB::table('notice_viewers')
//            ->whereJsonContains('data_viewer', $user_id)
//            ->pluck('notice_id', 'notice_id')->toArray();
//        $data = Notices::whereNotNull('public_date')->where('is_draft', 1)->whereNotIn('id', $notice_viewers)->count();
//        $unreadMessageId = MessageView::where('user_id', $user_id)->first();
//        if ($unreadMessageId) {
//            $unreadMessage = Message::where('id', '>', $unreadMessageId->message_id)->where('group_id', $request->user()->department_id)->count();
//        }
//        return $this->responseJson(200, [
//            "unread_notices" => $data,
//            "unread_messages" => $unreadMessage
//        ]);
//    }
}
