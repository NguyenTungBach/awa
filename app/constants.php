<?php

define('DEFAULT_ZERO_PAD', 5);
define('DEFAULT_STR_ZERO', '0');

define('CODE_SUCCESS', 200);
define('CODE_CREATE_FAILED', 201);
define('CODE_DELETE_FAILED', 202);
define('CODE_MULTI_STATUS', 207);
define('CODE_NO_ACCESS', 403);
define('CODE_NOT_FOUND', 404);
define('CODE_ERROR_SERVER', 500);
define('CODE_UNAUTHORIZED', 401);

define('IMAGE', 'upload/image');

define("TEMP_PASS", '123456789');


define("PATH_UPLOAD_FILE", 'upload');


define("PERMISSION_ACCESSORIES_CREATE", 'accessories_create');
define("PERMISSION_ACCESSORIES_DELETE", 'accessories_delete');
define("PERMISSION_ACCESSORIES_EDIT", 'accessories_edit');
define("PERMISSION_ACCESSORIES_LIST", 'accessories_list');

define("PERMISSION_MAINTENANCE_CREATE", 'maintenance_create');
define("PERMISSION_MAINTENANCE_DELETE", 'maintenance_delete');
define("PERMISSION_MAINTENANCE_DETAIL", 'maintenance_detail');
define("PERMISSION_MAINTENANCE_EDIT", 'maintenance_edit');
define("PERMISSION_MAINTENANCE_LIST", 'maintenance_list');

define("PERMISSION_MAINTENANCE_RESULT_CREATE", 'maintenance_result_create');
define("PERMISSION_MAINTENANCE_RESULT_DELETE", 'maintenance_result_delete');
define("PERMISSION_MAINTENANCE_RESULT_DETAIL", 'maintenance_result_detail');
define("PERMISSION_MAINTENANCE_RESULT_EDIT", 'maintenance_result_edit');
define("PERMISSION_MAINTENANCE_RESULT_LIST", 'maintenance_result_list');

define("PERMISSION_MAINTENANCE_SCHEDULE_AND_RESULTS_EXPORT", 'maintenance_schedule_and_results_export');
define("PERMISSION_MAINTENANCE_SCHEDULE_AND_RESULTS_LIST", 'maintenance_schedule_and_results_list');

define("PERMISSION_MAINTENANCE_SCHEDULE_CREATE", 'maintenance_schedule_create');
define("PERMISSION_MAINTENANCE_SCHEDULE_DELETE", 'maintenance_schedule_delete');
define("PERMISSION_MAINTENANCE_SCHEDULE_DETAIL", 'maintenance_schedule_detail');
define("PERMISSION_MAINTENANCE_SCHEDULE_EDIT", 'maintenance_schedule_edit');
define("PERMISSION_MAINTENANCE_SCHEDULE_LIST", 'maintenance_schedule_list');

define("PERMISSION_USER_CREATE", 'user_create');
define("PERMISSION_USER_DELETE", 'user_delete');
define("PERMISSION_USER_EDIT", 'user_edit');
define("PERMISSION_USER_LIST", 'user_list');

define("PERMISSION_VEHICLE_CREATE", 'vehicle_create');
define("PERMISSION_VEHICLE_DELETE", 'vehicle_delete');
define("PERMISSION_VEHICLE_DETAIL", 'vehicle_detail');
define("PERMISSION_VEHICLE_EDIT", 'vehicle_edit');
define("PERMISSION_VEHICLE_LIST", 'vehicle_list');


//"1. 3 month | 2. 12 month | 3.  accessory change | 4. other"

define("TYPE_CHECKBOX", 1);
define("TYPE_RADIO", 2);
define("TYPE_TEXT", 3);
define(
    "FILE_MIMETYPES",
    [
        "avi" => "video/x-msvideo",
        "csv" => "text/csv",
        "doc" => "application/msword",
        "docx" => "application/vnd.openxmlformats-officedocument.wordprocessingml.document",
        "gif" => "image/gif",
        "jpe" => "image/jpeg",
        "jpeg" => "image/jpeg",
        "jpg" => "image/jpeg",
        "mp3" => "audio/mpeg",
        "mp4" => "video/mp4",
        "pdf" => "application/pdf",
        "png" => "image/png",
        "ppt" => "application/vnd.ms-powerpoint",
        "pptm" => "application/vnd.ms-powerpoint.presentation.macroEnabled.12",
        "pptx" => "application/vnd.openxmlformats-officedocument.presentationml.presentation",
        "rar" => "application/octet-stream",
        "txt" => "text/plain",
        "xls" => "application/vnd.ms-excel",
        "xlsx" => "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
        "zip" => "application/x-zip-compressed"
    ]
);

define("LIST_MIMES", array_keys(FILE_MIMETYPES));
define("LIST_MIMETYPES", array_values(FILE_MIMETYPES));
define("UPLOAD_MAX_FILE_SIZE", 8388608);

define("ROLE_CREW", "crew");
define("ROLE_CLERKS", "clerks");
define("ROLE_TL", "tl");
define("ROLE_ACCOUNTING", "accounting");
define("ROLE_DX_GENERAL_AFFAIR", "dx_general_affair");
define("ROLE_PERSONNEL_LABOR", "personnel_labor");
define("ROLE_HEADQUARTER", "headquarter");
define("ROLE_AM_SM", "am_sm");
define("ROLE_DIRECTOR", "director");
define("ROLE_ACCOUNTANT_DIRECTION", "accountant_direction");
define("ROLE_PERSONNEL_LABOR_DIRECTION", "personnel_labor_direction");
define("ROLE_DX_MANAGER", "dx_manager");

define("MONDAY",'mon');
define("TUESDAY",'tue');
define("WEDNESDAY",'wed');
define("THURSDAY",'thu');
define("FRIDAY",'fri');
define("SATURDAY",'sat');
define("SUNDAY",'sun');

const IS_HOLIDAY = 'D-1';
const IS_FIX_DAY_OFF = 'D-2';
const IS_DAY_OFF_REQUEST = 'D-3';
const IS_DAY_OFF_PAID = 'D-4';
const IS_LEADER = 'R';
const IS_WAIT = 'S-1';
const IS_WAIT_BETWEEN_TASKS = 'S-2';
const DAY_OFF_CODE = [IS_HOLIDAY, IS_FIX_DAY_OFF, IS_DAY_OFF_REQUEST, IS_DAY_OFF_PAID];
const WAIT_CODE = [IS_WAIT, IS_WAIT_BETWEEN_TASKS, IS_LEADER];
const SORT_BY = ['desc', 'asc'];

const JP_HOLIDAY = '公休';
const JP_FIX_DAY_OFF = '固定休';
const JP_DAY_OFF_REQUEST = '希望休';
const JP_DAY_OFF_PAID = '有給休暇';
const JP_LEADER = '社内業務';
const JP_WAIT = '待機';
const JP_WAIT_BETWEEN_TASKS = '待機時間';
const ERROR = 'error';
const SUCCESS = 'success';
const CREATE_SUCCESS = 'Create success';
const LIST_SUCCESS = 'List success';
const UPDATE_SUCCESS = 'Update success';
const DELETE_SUCCESS = 'Delete success';
const CREATE_ERROR = 'Create error';
const LIST_ERROR = 'List error';
const UPDATE_ERROR = 'Update error';
const DELETE_ERROR = 'Delete error';

define("COLOR_HOLIDAY",'#EAE7AC');
define("COLOR_FIXED_DAY_OFF",'#FFE5CD');
define("COLOR_DAY_OFF_REQUEST",'#FFFCE2');
define("COLOR_PAID",'#FFD4D4');
define("COLOR_WORK",'#FFFFFF');
define("COLOR_OFF",'#EBEBEB');

define('URL_LOCAL_OUTPUT','F:/output');
define('URL_DEV_OUTPUT','/var/www/gac/output');
define('URL_STAGING_OUTPUT','/var/www/gac-staging/output');
define('URL_PRODUCTION_OUTPUT','/var/www/gac-production/output');

define('URL_LOCAL_INPUT','F:/input');
define('URL_DEV_INPUT','/var/www/gac/input');
define('URL_STAGING_INPUT','/var/www/gac-staging/input');
define('URL_PRODUCTION_INPUT','/var/www/gac-production/input');

define('PATH_LOCAL_AI','F:/program_ver002_07.py');
define('PATH_URL_DEV_AI','/var/www/gac/program_ver1.0.1.py');
define('PATH_URL_STAGING_AI','/var/www/gac-staging/program_ver002_07.py');
define('PATH_URL_PRODUCTION_AI','/var/www/gac-production/program_ver002_07.py');


define('PATH_ENVIRONMENT_LOCAL_AI','');
define('PATH_ENVIRONMENT_URL_DEV_AI','/var/www/toshin-dev/pythonenv/bin/python ');
define('PATH_ENVIRONMENT_URL_STAGING_AI','/var/www/toshin-staging/pythonenv/bin/python ');
define('PATH_ENVIRONMENT_URL_PRODUCTION_AI','/var/www/toshin-production/pythonenv/bin/python ');









