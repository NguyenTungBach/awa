<?php
/**
 * Created by VeHo.
 * Year: 2022-07-29
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class File extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'files';

    const  FILE_STATUS_ON = 'on';
    const  FILE_STATUS_CHECK = 'check';
    const  FILE_STATUS_ERROR = 'error';
    const  FILE_STATUS_SUCCESS = 'success';
    const  FILE_STATUS_OFF = 'off';

    public static $configMessage = [
        "1" => "休日管理情報に読込エラーがあります。",
        "2" => "休日管理情報に社員番号の列が存在しません。",
        "3" => "休日管理情報で列名が日付形式(YYYY/MM/DD)形式になっていません。",
        "4" => "従業員情報に読込エラーがあります。",
        "5" => "従業員情報に社員番号の列が存在しません。",
        "6" => "コース情報に読込エラーがあります。",
        "7" => "コース情報にコースID、路線名・コース名の列が存在しません。",
        "8" => "コース情報で始業時間の列が存在しない or 始業時間の列がHH:MM:SS形式になっていません。",
        "9" => "コース情報で終業時間の列が存在しない or 終業時間の列がHH:MM:SS形式になっていません。",
        "10" => "コース情報で休憩時間の列が存在しない or 数値形式(何時間か)になっていません。",
        "11" => "コース情報に葬儀送迎が存在しません。",
        "12" => "コース組み合わせ表に読込エラーがあります。",
        "13" => "コース組み合わせ表にコースIDの列が存在しません。",
        "14" => "配車情報に読込エラーがあります。",
        "15" => "配車情報にコースIDの列が存在しません。",
        "16" => "配車情報でデータに'○'が含まれていない、もしくは他の文字列が入っています。",
        "17" => "配車情報でコースIDを除く列名が日付形式(YYYY/MM/DD)形式になっていません。",
        "18" => "疲労度に読込エラーがあります。",
        "19" => "疲労度に社員番号の列が存在しません。",
        "20" => "休日管理情報で社員番号が数値になっていません。",
        "21" => "従業員情報で社員番号が数値になっていません。",
        "22" => "疲労度で社員番号が数値になっていません。",
        "23" => "コース情報でコースIDが数値になっていません。",
        "24" => "コース組み合わせ表でコースIDが数値になっていません。",
        "25" => "配車情報でコースIDが数値になっていません。",
        "26" => "疲労度のコースIDが数値になっていません。",
        "27" => "休日管理情報と従業員情報に共通の社員番号が存在しません。",
        "28" => "休日管理情報と配車情報に共通の配車日が存在しません。",
        "29" => "コース情報、コース組み合わせ表、配車情報に共通のコースIDが存在しません。",
        "30" => "勤務できる社員番号が存在しません。",
        "31" => "配車日の中に欠損している日が存在します。",
        "32" => "配車日が7日未満です。",
        "33" => "休日管理情報に固定休、希望休、有給休暇、公休以外の文字が存在しています。",
        "34" => "従業員情報に15時間以上NGの列が存在しない、もしくは○以外の文字が存在しています。",
        "35" => "従業員情報に残業40時間以上NGの列が存在しない、もしくは○以外の文字が存在しています。",
        "36" => "従業員情報に葬儀のみの列が存在しない、もしくは○以外の文字が存在します。",
        "37" => "従業員情報に主任の列が存在しない、もしくは○以外の文字が存在します。",
        "38" => "コース組み合わせ表で始業終業時間的に組めないコースが存在しています。",
        "39" => "疲労度が数値で入っていません。",
        "40" => "路線・コース名に安祥線、北部線、東部線のいずれかがありません。",
        "41" => "路線・コース名に刈谷A、刈谷B、刈谷Cのいずれかがありません。",
        "42" => "路線・コース名に西尾A、西尾B、西尾Cのいずれかがありません。",
        "43" => "路線・コース名に東岡崎線出社便、東岡崎線退社便、東岡崎線残業退社便のいずれかがありません。",
        "44" => "路線・コース名にJR岡崎線出社便、JR岡崎線退社便、JR岡崎線残業退社便のいずれかがありません。",
        "45" => "路線・コース名に新安城線出社便、新安城線退社便(平日残業あり)、新安城線退社便(土曜残業なし)、新安城線退社便(土曜残業あり)のいずれかがありません。",
        "46" => "コース組み合わせ表データに○,×が含まれていない、もしくは他の文字列が入っています。",
        "47" => "モデル作成タイムアウトエラーです。",
        "48" => "モデル生成時エラーです"
    ];

    protected $fillable = [
        'file_name',
        'file_code',
        'type',
        'date_time',
        'path',
        'model',
        'status',
        'note',
    ];

    protected $dates = ['deleted_at'];

    protected $casts = [
        'data' => 'array'
    ];

}
