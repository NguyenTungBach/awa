export default {
	TOAST: {
		SUCCESS: '成功',
		WARNING: '警告',
		DANGER: 'エラー',
	},
	MESSAGE_APP: {
		EXCEPTION: 'システムエラーが発生しました。',
		TOKEN_EXPIRE: 'トークンの有効期限が切れました。再度ログインしてください。',
		LOGIN_SUCCESS: 'ログインしました。',
		LOGIN_REQUIRED: 'ユーザーIDとパスワードを入力してください。',
		LOGIN_VALIDATE_USER_ID: 'ユーザーIDは半角数字4桁で入力してください',
		LOGIN_VALIDATE_PASSWORD: 'パスワードは8文字以上16文字以内の半角英数字で入力してください。',
		LOGOUT_SUCCESS: 'ログアウトしました。',
		USER_MANAGEMENT_CREATE_SUCCESS: 'ユーザーを作成しました。',
		USER_MANAGEMENT_UPDATE_SUCCESS: 'ユーザーを更新しました。',
		USER_MANAGEMENT_DELETE_SUCCESS: 'ユーザーを削除しました。',
		USER_MANAGEMENT_VALIDATE_REQUIRED: '入力または選択されていない項目があります。',
		USER_MANAGEMENT_VALIDATE_USER_CODE: 'ユーザーIDは半角数字4桁で入力してください。',
		USER_MANAGEMENT_VALIDATE_USER_NAME: 'ユーザー名は20桁以内で入力してください。',
		USER_MANAGEMENT_VALIDATE_USER_PASSWORD: 'パスワードは8文字以上16文字以内の半角英数字で入力してください。',
		USER_MANAGEMENT_VALIDATE_USER_ROLE: 'ユーザー権限を入力してください。',
		DRIVER_MANAGEMENT_CREATE_SUCCESS: 'ドライバーを作成しました。',
		DRIVER_MANAGEMENT_UPDATE_SUCCESS: 'ドライバーを更新しました。',
		DRIVER_MANAGEMENT_DELETE_SUCCESS: 'ドライバーを削除しました。',
		DRIVER_MANAGEMENT_CLICK_TAB_COURSE: '入力されていない項目があります。',
		DRIVER_MANAGEMENT_VALIDATE_REQUIRED: '入力されていない項目があります。',
		DRIVER_MANAGEMENT_VALIDATE_DRIVER_CODE: 'Crew番号は半角数字15桁以内で入力してください。',
		DRIVER_MANAGEMENT_VALIDATE_DRIVER_NAME: '氏名は20文字以内で入力してください。',
		DRIVER_COURSE_MANAGEMENT_CREATE_SUCCESS: 'ドライバー走行可能コースを作成しました。',
		DRIVER_COURSE_MANAGEMENT_UPDATE_SUCCESS: 'ドライバー走行可能コースを更新しました。',
		DRIVER_COURSE_MANAGEMENT_DELETE_SUCCESS: 'ドライバー走行可能コースを削除しました。',
		DRIVER_MANAGEMENT_VALIDATE_GRADE: '等級は半角英数字10桁以内で入力してください。',
		COURSE_MANAGEMENT_CREATE_SUCCESS: 'コースを作成しました。',
		COURSE_MANAGEMENT_UPDATE_SUCCESS: 'コースを更新しました。',
		COURSE_MANAGEMENT_DELETE_SUCCESS: 'コースを削除しました。',
		COURSE_MANAGEMENT_VALIDATE_REQUIRED: '入力されていない項目があります。',
		COURSE_MANAGEMENT_VALIDATE_GROUP: 'グループ項目はアルファベット両方を選択してください。',
		COURSE_MANAGEMENT_VALIDATE_COURSE_CODE: 'コースIDは半角英数字15桁以内で入力してください。',
		COURSE_MANAGEMENT_VALIDATE_COURSE_NAME: 'コース名は30文字以内で入力してください。',
		COURSE_MANAGEMENT_VALIDATE_START_TIME_GREATER_THAN_END_TIME: '開始時刻は終了時刻より前に設定してください。',
		COURSE_MANAGEMENT_VALIDATE_START_TIME_EQUALS_END_TIME: '開始時刻は終了時刻と同じに設定してください。',
		COURSE_MANAGEMENT_VALIDATE_BREAK_TIME_GREATER_THAN_COURSE_TIME: '休憩時間が長すぎます。',
		COURSE_MANAGEMENT_VALIDATE_POINT: 'ポイントは半角数字及び小数点のみ入力可能です。',
		COURSE_MANAGEMENT_VALIDATE_START_DATE: '開始日の形式が正しくありません。',
		COURSE_MANAGEMENT_VALIDATE_END_DATE: '終了日の形式が正しくありません。',
		COURSE_MANAGEMENT_VALIDATE_START_END_DATE: '開始日と終了日の形式が正しくありません。',
		COURSE_MANAGEMENT_VALIDATE_NOTE: 'メモは1000文字以内で入力してください。',
		COURSE_PATTERN_VALIDATE_LIST_UPDATE_EMPTY: '保存しました。',
		DAY_OFF_UPDATE_SUCCESS: 'シフト生成情報を更新しました。',
		DAY_OFF_VALIDATE_SELECT_TYPE_DAY: '休日の種類が選択されていません',
		SCHEDULE_UPDATE_SUCCESS: '保存しました。',
		SCHEDULE_VALIDATE_FILE_REQUIRED: 'ファイルを選択してください。',
		SCHEDULE_VALIDATE_FILE_TYPE: ' ファイルの形式が正しくありません。',
		SCHEDULE_VALIDATE_FILE_SIZE: 'ファイルの容量が大きすぎます。',
		SCHEDULE_IMPORT_SUCCESS: '取り込みに成功しました。',
		SCHEDULE_IMPORT_FAIL: 'コースID {listCourse} はインポートできませんでした。',
		LIST_SHIFT_UPDATE_SUCCESS: 'シフト表を更新しました。',
		LIST_SHIFT_VALIDATE_LIST_UPDATE_EMPTY: '更新するデータがありません。',
		LIST_SHIFT_VALIDATE_SELECTED_DATE: '開始曜日と終了曜日を選択してください。',
		LIST_SHIFT_VALIDATE_REQUIRED: '入力されていない項目があります。',
		LIST_SHIFT_VALIDATE_REQUIRED_TYPE: '入力されていない項目があります。',
		LIST_SHIFT_VALIDATE_REQUIRED_TIME: '入力されていない項目があります。',
		LIST_SHIFT_VALIDATE_DUPLICATE_DATE_COURSE: '選択された項目が重複しています。',
		LIST_SHIFT_VALIDATE_TIME_START_END_COURSE: '終業時間は始業時間より後の時間を選択してください。',
		LIST_SHIFT_VALIDATE_TIME_BREAK_COURSE: '休憩時間が長すぎます。',
		LIST_SHIFT_VALIDATE_DUPLICATE_TIME_COURSE: 'この時間は既に登録されています。',
	},
	APP: {
		PLEASE_WAIT: 'しばらくお待ちください...',
		LOADING: 'Loading...',
		PLEASE_SELECT: '選択してください',
		LABLE_HELP_CALENDAR: 'カーソルキーを使用してカレンダーの日付をナビゲートする',
		BUTTON_SIGN_UP: '新規登録',
		BUTTON_RETURN: '戻る',
		BUTTON_EDIT: '編集',
		BUTTON_SAVE: '保存',
		BUTTON_CHANGE: '変更',
		BUTTON_OK: 'OK',
		BUTTON_GO_TO_HOME: 'ホームに戻る',
		TABLE_NO_DATA: 'データなし',
		TITLE_MODAL_CONFIRM: '確認',
		TEXT_CONFIRM: '確認',
		TEXT_CANCEL: 'キャンセル',
		TEXT_CLOSE: '閉じる',
		TEXT_RESET: '削除',
		TEXT_CALENDAR_PLACEHOLDER: 'データなし',
	},
	ROOT_APP_ACTION_LOAD: {
		TITLE: '確認してください',
		MESSAGE: 'このページから移動しますか？入力したデータは保存されません。',
		RETURN: 'このページに留まる',
		CONTINUE: 'このページから移動する',
	},
	ROUTER: {
		DEV: 'Dev',
		PAGE_NOT_FOUND: 'ページが見つかりません',
		LOGIN: 'ログイン',
		SHIFT_MANAGEMENT: 'シフト管理',
		LIST_SHIFT: 'シフト表',
		LIST_DAY_OFF: 'シフト希望表',
		LIST_SCHEDULE: '配送予定表',
		DATA_MANAGEMENT: 'データ管理',
		LIST_DRIVER: 'Crew情報',
		LIST_COURSE: 'コース情報',
		LIST_COURSE_PATTERN: 'コース組み合わせ表',
		LIST_USER: 'ユーザー情報',
	},
	LAYOUT: {
		LOGOUT: 'ログアウト',
	},
	LOGIN: {
		TITLE_LOGIN: 'AIシフト生成システム',
		PLACEHOLDER_USER_ID: 'Crew ID',
		PLACEHOLDER_USER_PASSWORD: 'パスワード',
		BUTTON_LOGIN: 'ログイン',
	},
	LIST_SHIFT: {
		TITLE_EDIT_COURSE_BASE: 'Crewを選択',
		TITLE_LIST_SHIFT: ' シフト表',
		// TITLE_LIST_SHIFT_PRACTICAL_RECORD_TABLE: 'シフト表',
		TITLE_LIST_SHIFT_PRACTICAL_RECORD_TABLE: '実務実績表',
		TABLE_SALARY: '人件費表',
		BUTTON_WEEK: '週',
		BUTTON_MONTH: '月',
		BUTTON_SHIFT_CREATION: 'シフト生成',
		BUTTON_VIEW_LOG: '実行履歴',
		BUTTON_ATMTC: 'ATMTC連携',
		BUTTON_DOWNLOAD_EXCEL: 'Excel出力',
		BUTTON_DOWNLOAD_PDF: 'PDF出力',
		// BUTTON_SHIFT_TABLE: 'シフト表Crewベース',
		BUTTON_SHIFT_TABLE: 'シフト表<br />Crewベース',
		// BUTTON_COURSE_BASE: 'シフト表コースベース',
		BUTTON_COURSE_BASE: 'シフト表<br />コースベース',
		TITLE_COURSE_BASE: 'シフト表',
		BUTTON_PRACTICAL_ACHIEVEMENTS_MONTHLY: '実務実績表',
		BUTTON_PRACTICAL_PERFORMANCE_BY_CLOSING_DATE: '実務実績締日別',
		BUTTON_TABLE_SALARY: '人件費表',
		BUTTON_RETURN: '戻る',
		BUTTON_EDIT: '編集',
		BUTTON_SAVE: '保存',
		BUTTON_CHANGE: '変更',
		BUTTON_OK: 'OK',
		TABLE_DATE_EMPLOYEE_NUMBER: 'Crew番号',
		TABLE_FULL_NAME: 'Crew名',
		TABLE_DATE_HOLIDAY: '公休',
		TABLE_DATE_FIXED_DAY_OFF: '固定休',
		TABLE_DATE_DAY_OFF_REQUEST: '希望休',
		TABLE_DATE_PAID: '有給休暇',
		TABLE_DATE_LEADER_CHIEF: '社内業務',
		TABLE_DATE_WAIT: '待機',
		TABLE_DATE_WAIT_BETWEEN_TASK: '待機時間',
		SELECT_WAIT: '待機',
		SELECT_WAIT_BETWEEN_TASK: '待機時間',
		SELECT_LEADER_CHIEF: '社内業務',
		SELECT_HOLIDAY: '公休',
		SELECT_FIXED_DAY_OFF: '固定休',
		SELECT_DAY_OFF_REQUEST: '希望休',
		SELECT_PAID: '有給休暇',
		LABEL_START_TIME: '始業時間: ',
		LABEL_CLOSING_TIME: '終業時間: ',
		LABEL_BREAK_TIME: '休憩時間: ',

		TABLE_DRIVER_CODE: 'Crew番号',
		TABLE_DRIVER_TYPE: 'Crew区分',
		TABLE_DRIVER_NAME: 'Crew名',
		TABLE_NUMBER_OF_PAID_HOLIDAYS: '有給休暇数',
		TABLE_TOTAL_TIME: '勤務可能日数',
		TABLE_DRIVING_TIME: '勤務日数',
		TABLE_OVER_TIME: '休日数',
		TABLE_WORKING_DAYS: '希望休数',
		TABLE_DAY_OFF: '拘束時間',
		TABLE_PAID_HOLIDAYS: '実質乗車時間',
		TABLE_ONE_DAY_MAX_TOTAL_TIME: '休憩時間',
		TABLE_ONE_DAY_MAX_DRIVING_TIME: '残業時間',
		TABLE_FIFTEEN_HOURS_OVER_WORKING_DAYS: 'ポイント',

		TITLE_LIST_COURSE: 'シフト表',
		TABLE_FLAG: 'Crew区分',
		MODAL_CONFIRM_AI: '{start_year}年{start_month}月{start_date}日(土)〜{end_year}年{end_month}月{end_date}日(金)のシフトは既に作成されています。上書きしてシフトを作成しますか？',
		MODAL_CONFIRM_AI_CANCEL: 'キャンセル',
		MODAL_CONFIRM_AI_OK: 'シフトを生成する',
		MODAL_TITLE_VIEW_LOG: '実行履歴',
		TABLE_TITLE_NO: 'No',
		TABLE_EXECUTION_DATE_AND_TIME: '実行日時',
		TABLE_START_TIME: '開始年月日',
		TABLE_END_TIME: '終了年月日',
		TABLE_STATUS: 'ステータス',
		TABLE_MESSAGE: 'メッセージ',
		MODAL_TITLE_DETAIL_VIEW_LOG: '実行履歴詳細',
		TEXT_TOTAL_MESSAGE: '合計メッセージ: {total}',
		MESSAGE_AI_SUCCESS: 'シフト表生成が完了しました。',
		MESSAGE_AI_ERROR: 'シフト表生成に失敗しました。実行履歴でエラー内容を確認してください。',

		SALARY_TOTAL: '合計',
		TABLE_COURSE_COURSE_ID: 'コース ID',
		TABLE_COURSE_COURSE_GROUP: 'グループ',
		TABLE_COURSE_COURSE_NAME: 'コース名',
	},
	DAY_OFF: {
		TITLE_LIST_DAY_OFF: 'シフト希望表',
		TABLE_DATE_EMPLOYEE_NUMBER: 'Crew番号',
		TABLE_FLAG: 'Crew区分',
		TABLE_FULL_NAME: 'Crew名',
		TABLE_DATE_FIXED_DAY_OFF: '固定休',
		TABLE_DATE_DAY_OFF_REQUEST: '希望休',
		TABLE_DATE_PAID: '有給休暇',
		TABLE_DEFAULT: '-',
		MODAL_CHANGE_STATUS_DATE: '選択変更',
		SELECT_TYPE_HOLIDAY: '休日',
		SELECT_TYPE_WORK: 'コース名',
	},
	LIST_SCHEDULE: {
		TITLE_LIST_SCHEDULE: '配送予定表',
		TABLE_COURSE_ID: 'コースID',
		TABLE_COURSE_NAME: 'コース名',
		TABLE_COURSE_GROUP_CODE: 'グループ',
	},
	LIST_DRIVER: {
		TITLE_LIST_DRIVER: 'Crew情報',
		BUTTON_SIGN_UP: '新規登録',
		TABLE_TITLE_EMPLOYEE_NUMBER: 'Crew番号',
		TABLE_TITLE_FULL_NAME: 'Crew名',
		TABLE_TITLE_ENROLLMENT_STATUS: '在籍状況',
		TABLE_TITLE_DETAIL: '詳細',
		TABLE_TITLE_DELETE: '削除',
		ENROLLMENT_STATUS_RETIRED: '在籍',
		ENROLLMENT_STATUS_ENROLLED: '退職済み',
		TEXT_CONFIRM_DELETE: '本当に削除しますか？',
		TEXT_CONFIRM: '確認',
		TEXT_CANCEL: 'キャンセル',
	},
	CREATE_DRIVER: {
		TITLE_CREATE_DRIVER: 'Crew新規登録',
		TITLE_EMPLOYEE_DETAILS: 'Crew詳細',
		TITLE_EDIT_DRIVER: 'Crew編集',
		TAB_TITLE_BASIC_INFORMATION: '基本情報',
		TAB_TITLE_COURSE_INFORMATION: 'コース情報',
		FORM_PATH_BASIC_INFORMATION: '基本情報',
		FORM_PATH_WORKING_CONDITIONS: '労働条件',
		FORM_PATH_RETIREMENT_DATE: '退職日',
		DIRECTOR: '主任',
		FUNERAL_ONLY_DRIVER: '葬儀のみドライバー',
		TYPE_DRIVER: 'Crew区分',
		EMPLOYEE_NUMBER: 'Crew番号',
		FULL_NAME: 'Crew名',
		HIRE_DATE: '入社日',
		DATE_OF_BIRTH: '生年月日',
		GRADE: '等級',
		AVAILABLE_DAYS: '勤務可能日数',
		DAY: '日',
		FIXED_HOLIDAYS: '固定休曜日',
		MONDAY: '月',
		TUESDAY: '火',
		WEDNESDAY: '水',
		THURSDAY: '木',
		FRIDAY: '金',
		SATURDAY: '土',
		SUNDAY: '日',
		WORKING_TIME: '労働時間:',
		WORKING_TIME_2: '労働時間',
		NO_MORE_THAN_15_HOURS_OF_WORK_PER_DAY: '一日労働時間15時間以上NG',
		NO_MORE_THAN_40_HOURS_OF_OVERTIME_PER_MONTH: '月残業40時間以上NG',
		NOTES: 'メモ:',
		TABLE_RUNABLE_COURSE_ID: '走行可能コースID',
		TABLE_UNABLE_COURSE_NAME: '走行可能コース名',
		TABLE_EXCLUSIVE: '専属',
		TABLE_FATIGUE: 'ポイント',
		TABLE_DELETE: '削除',
		LEADER: '管理職',
		FULL_TIME: '正社員',
		PART_TIME: '契約社員',
	},
	DETAIL_DRIVER: {
		TITLE_DETAIL_DRIVER: 'Crew詳細',
		TITLE_EMPLOYEE_DETAILS: 'Crew詳細',
		TAB_TITLE_BASIC_INFORMATION: '基本情報',
		TAB_TITLE_COURSE_INFORMATION: 'コース情報',
		FORM_PATH_BASIC_INFORMATION: '基本情報',
		FORM_PATH_WORKING_CONDITIONS: '労働条件',
		FORM_PATH_RETIREMENT_DATE: '退職日',
		DIRECTOR: '主任',
		FUNERAL_ONLY_DRIVER: '葬儀のみドライバー',
		EMPLOYEE_NUMBER: 'Crew番号',
		FULL_NAME: 'Crew名',
		HIRE_DATE: '入社日',
		DATE_OF_BIRTH: '生年月日',
		GRADE: '等級',
		AVAILABLE_DAYS: '勤務可能日数',
		DAY: '日',
		FIXED_HOLIDAYS: '固定休曜日',
		MONDAY: '月',
		TUESDAY: '火',
		WEDNESDAY: '水',
		THURSDAY: '木',
		FRIDAY: '金',
		SATURDAY: '土',
		SUNDAY: '日',
		WORKING_TIME: '労働時間:',
		WORKING_TIME_2: '労働時間',
		NO_MORE_THAN_15_HOURS_OF_WORK_PER_DAY: '一日労働時間15時間以上NG',
		NO_MORE_THAN_40_HOURS_OF_OVERTIME_PER_MONTH: '月残業40時間以上NG',
		NOTES: 'メモ',
		TABLE_RUNABLE_COURSE_ID: '走行可能コースID',
		TABLE_UNABLE_COURSE_NAME: '走行可能コース名',
		TABLE_EXCLUSIVE: '専属',
		TABLE_FATIGUE: 'ポイント',
		TABLE_DELETE: '削除',
	},
	EDIT_DRIVER: {
		TITLE_EDIT_DRIVER: 'Crew編集',
		TITLE_EMPLOYEE_DETAILS: 'Crew詳細',
		TAB_TITLE_BASIC_INFORMATION: '基本情報',
		TAB_TITLE_COURSE_INFORMATION: 'コース情報',
		FORM_PATH_BASIC_INFORMATION: '基本情報',
		FORM_PATH_WORKING_CONDITIONS: '労働条件',
		FORM_PATH_RETIREMENT_DATE: '退職日',
		DIRECTOR: '主任',
		FUNERAL_ONLY_DRIVER: '葬儀のみドライバー',
		EMPLOYEE_NUMBER: 'Crew番号',
		FULL_NAME: 'Crew名',
		HIRE_DATE: '入社日',
		DATE_OF_BIRTH: '生年月日',
		GRADE: '等級',
		AVAILABLE_DAYS: '勤務可能日数',
		DAY: '日',
		FIXED_HOLIDAYS: '固定休曜日',
		MONDAY: '月',
		TUESDAY: '火',
		WEDNESDAY: '水',
		THURSDAY: '木',
		FRIDAY: '金',
		SATURDAY: '土',
		SUNDAY: '日',
		WORKING_TIME: '労働時間:',
		WORKING_TIME_2: '労働時間',
		NO_MORE_THAN_15_HOURS_OF_WORK_PER_DAY: '一日労働時間15時間以上NG',
		NO_MORE_THAN_40_HOURS_OF_OVERTIME_PER_MONTH: '月残業40時間以上NG',
		NOTES: 'メモ:',
		TABLE_RUNABLE_COURSE_ID: '走行可能コースID',
		TABLE_UNABLE_COURSE_NAME: '走行可能コース名',
		TABLE_EXCLUSIVE: '専属',
		TABLE_FATIGUE: 'ポイント',
		TABLE_DELETE: '削除',
		RUNABLE_COURSE_NAME: '走行可能コース名',
		FATIGUE: 'ポイント',
	},
	LIST_COURSE: {
		TITLE_LIST_COURSE: 'コース情報',
		TABLE_COURSE_ID: 'コースID',
		TABLE_COURSE_NAME: 'コース名',
		TABLE_OPERATIONAL_INFORMATION: '運行情報',
		TABLE_START_TIME: '始業時間',
		TABLE_CLOSING_TIME: '終業時間',
		TABLE_BREAK_TIME: '休憩時間',
		TABLE_DETAIL: '詳細',
		TABLE_DELETE: '削除',
		TEXT_CONFIRM_DELETE: '本当に削除しますか？',
		TEXT_CONFIRM: '確認',
		TEXT_CANCEL: 'キャンセル',
	},
	COURSE_CREATE: {
		TITLE_COURSE_CREATE: 'コース新規登録',
		FORM_BASIC_INFORMATION: '基本情報',
		SHUTTLE_DELIVERY: '送迎配送',
		EASY_TO_DISTINGUISH: '便区分',
		COURSE_ID: 'コースID',
		COURSE_NAME: 'コース名',
		EXCLUSIVE: '専任',
		COURSE_TYPE: 'グループ',
		START_TIME: '始業時間',
		END_TIME: '終業時間',
		BREAK_TIME: '休憩時間',
		FATIGUE: 'ポイント',
		START_DATE: '開始日',
		END_DATE: '終了日',
		NOTE: 'メモ:',
		GROUP_CODE: 'グループ',
		CHECKBOX_SPOT_FLIGHT: 'スポット便',
		CHECKBOX_SHORT_FLIGHT: 'ショート便',
	},
	COURSE_DETAIL: {
		TITLE_COURSE_DETAIL: 'コース詳細',
		FORM_BASIC_INFORMATION: '基本情報',
		SHUTTLE_DELIVERY: '送迎配送',
		COURSE_ID: 'コースID',
		COURSE_NAME: 'コース名',
		COURSE_TYPE: 'グループ',
		START_TIME: '始業時間',
		END_TIME: '終業時間',
		BREAK_TIME: '休憩時間',
		FATIGUE: 'ポイント',
		START_DATE: '開始日',
		END_DATE: '終了日',
		NOTE: 'メモ:',
		NOTE_DETAIL: 'メモ',
		GROUP_CODE: 'グループ',
	},
	COURSE_EDIT: {
		TITLE_COURSE_EDIT: 'コース編集',
		FORM_BASIC_INFORMATION: '基本情報',
		SHUTTLE_DELIVERY: '送迎配送',
		COURSE_ID: 'コースID',
		COURSE_NAME: 'コース名',
		COURSE_TYPE: 'グループ',
		START_TIME: '始業時間',
		END_TIME: '終業時間',
		BREAK_TIME: '休憩時間',
		FATIGUE: 'ポイント',
		START_DATE: '開始日',
		END_DATE: '終了日',
		NOTE: 'メモ:',
		GROUP_CODE: 'グループ',
	},
	LIST_COURSE_PATTERN: {
		TITLE_LIST_COURSE_PATTERN: 'コース組み合わせ表',
		COURSE_ID: 'コースID',
		COURSE_NAME: 'コース名',
	},
	EDIT_COURSE_PATTERN: {
		TITLE_EDIT_COURSE_PATTERN: 'コース組み合わせ表',
		COURSE_ID: 'コースID',
		COURSE_NAME: 'コース名',
	},
	COURSE_PATTERN: {
		MODAL_CHANGE_COURSE_PATTERN: 'コース組み合わせ表',
	},
	LIST_USER: {
		TITLE_LIST_USER: 'ユーザー情報',
		USER_ID: 'ユーザーID',
		USER_NAME: 'ユーザー名',
		USER_AUTHORITY: 'ユーザー権限',
		DETAIL: '詳細',
		DELETE: '削除',
		ROLE_DRIVER: 'Crew',
		ROLE_SYSTEM_ADMINISTRATOR: 'システム管理者',
		TEXT_CONFIRM_DELETE: '本当に削除しますか？',
		TEXT_CONFIRM: '確認',
		TEXT_CANCEL: 'キャンセル',
	},
	CREATE_USER: {
		TITLE_CREATE_USER: 'ユーザー新規登録',
		USER_INFORMATION: 'ユーザー情報',
		USER_ID: 'ユーザーID',
		USERNAME: 'ユーザー名',
		USER_AUTHORITY: 'ユーザー権限',
		PASSWORD: 'パスワード',
	},
	DETAIL_USER: {
		TITLE_DETAIL_USER: 'ユーザー詳細',
		USER_INFORMATION: 'ユーザー情報',
		USER_ID: 'ユーザーID',
		USERNAME: 'ユーザー名',
		USER_AUTHORITY: 'ユーザー権限',
		PASSWORD: 'パスワード',
	},
	EDIT_USER: {
		TITLE_EDIT_USER: 'ユーザー編集',
		USER_INFORMATION: 'ユーザー情報',
		USER_ID: 'ユーザーID',
		USERNAME: 'ユーザー名',
		USER_AUTHORITY: 'ユーザー権限',
		PASSWORD: 'パスワード',
	},
	MESSAGE_RESPONSE_AI: {
		TITLE_MODAL: 'AIの結果',
		// MESSAGE_NO_AI_TO_RUN: "Please call Kiet san",
		MESSAGE_NO_AI_TO_RUN: 'Something went wrong, please contact admin',
		BUTTON_OK: '閉じる',
		TEXT_STATUS_DEFAULT: '',
		TEXT_STATUS_ERROR: 'エラーがあります。',
		TEXT_STATUS_SUCCESS: '成功しました。',
		TEXT_STATUS_PROCESS: 'プロセス',
		TEXT_STATUS_CHECK: '組み込めないコースがあります。',
	},
	STATUS: {
		on: 'Process',
		error: 'Error',
		success: 'Success',
		check: 'Check',
	},
};
