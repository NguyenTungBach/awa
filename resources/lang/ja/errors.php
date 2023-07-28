<?php
return [
    'page_not_found' => '結果が見つかりませんでした。',
    'accessory_name_of_tonnage_is_exist' => '部品名は既に存在しています',
    'not_change_tonnage_accessory_referenced_to_maintenance_cost' => 'この部品は実績登録済みため、(t)数は編集できません',
    'name_accessory_not_change' => '部品名は編集できません',
    'permission' => 'この機能を利用する権限がありません',
    'data_not_found' => 'データが存在しません。',
    'calender.get_data_index_60' => 'Data can only be obtained for no more than 60 days .',
    'field.index' => 'Field must be in driver_code,driver_name,status .',
    'sort_by.index' => 'Sort by needs to pass the field in and sort by must be asc,desc .',
    'data.create' => 'Data cannot be saved .',
    'data.update' => 'Data cannot be update .',
    'data.me' => 'You cannot delete your own information .',
    'driver.workingday' => 'Fixed number of days off and days able to work is not valid. The total number of days off and working days must not be more than 7.',
    'driver.enddate' => 'The day off from work must not be less than the date of starting work.',
    'time.Invalid_time' => '有効な時間を入力してください .',
    'data_not_found.status' => 'Data does not exist or has updated status deleted. Please check',
    'data_already_extists' => 'Data already exists. Please check',
    'dayoff.date' => 'Data can only be edited no more than one year from the current date',
    'shift.date' => 'The duration should not exceed 4 weeks and must begin on Saturday and end on Friday.',
    'shift.date_view' => 'The invalid time get data. Please check.',
    'shift.check' => 'The data already exists , if you agree the data will be replaced. please check again.',
    'shift.check.course' => '送迎配送にチェックを入れていないコースは時間を編集することができません。',
    'shift.check.24' => '1日最大拘束時間は24hを超えることができません。',
    'shift.check.only' => '休日又は待機を選択した場合は他のコースを選択することができません。',
    'shift.check.okie' => '選択されたシフトは既に作成されています。上書きしてシフトを作成しますか？',
    'shift.check.time.cell' => '終業時間は始業時間より後の時間を選択してください。',
    'shift.check.create' => '他のシフトが生成されています。しばらくお待ちください。',
    'shift.driver_empty' => 'Empty Drivers data',
    'shift.course_empty' => 'Empty Courses data',
    'not_found' => 'not found',
    'driver_can_not_delete' => 'driver id :driver_id, driver_name :driver_name have course_id :course_id, course_name :course_name can not delete',
    'driver_course_id_not_found' => 'driver course id :id not found',
    'unique' => ':attribute already exists',
    'has_been_assigned' => ':attribute has been assigned',
    'end_date_retirement' => ':attribute already in retirement :end_date',
    'unlike_ship_date' => ':attribute not unlike ship date :ship_date',
    'final_closing_histories' => ':attribute already in final closing',
    'duplicate_course_id_and_date' => 'duplicate course_id :course_id and date :date ',
    'duplicate_driver_id_and_course_id' => 'duplicate driver :driver_id and course_id :course_id ',
    'duplicate_id_shift' => 'duplicate shift update id :id',
];
