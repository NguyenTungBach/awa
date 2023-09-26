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
		USER_MANAGEMENT_VALIDATE_USER_CODE: 'ユーザーIDは半角数字15桁以内で入力してください。',
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
		SCHEDULE_MANAGEMENT_CREATE_SUCCESS: 'コースを成功に導く',
		SCHEDULE_MANAGEMENT_UPDATE_SUCCESS: 'コースを更新しました',
		SCHEDULE_MANAGEMENT_DELETE_SUCCESS: 'コース削除成功',
		DAY_OFF_UPDATE_SUCCESS: 'シフト生成情報を更新しました。',
		DAY_OFF_VALIDATE_SELECT_TYPE_DAY: '休日の種類が選択されていません',
		COURSE_SCHEDULE_UPDATE_SUCCESS: '保存しました',
		SCHEDULE_UPDATE_SUCCESS: '保存しました。',
		SCHEDULE_CREATE_SUCCESS: 'コーススケジュールの作成が成功しました',
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
		// CASH MANAGEMENT
		CASH_OUT_DELETE_SUCCESS: 'キャッシュアウトの削除成功',
		CASH_OUT_CREATE_SUCCESS: 'キャッシュアウトの成功を生み出す',
		CASH_OUT_UPDATE_SCUCCESS: 'キャッシュピットの更新成功',

		CASH_IN_CREATE_SUCCESS: '成功して現金を生み出す',
		CASH_IN_DELETE_SUCCESS: '現金の削除に成功しました',
		CASH_IN_UPDATE_SCUCCESS: 'キャッシュの更新が成功しました',
	},
	APP: {
		PLEASE_WAIT: 'しばらくお待ちください...',
		LOADING: 'Loading...',
		PLEASE_SELECT: '選択してください',
		LABLE_HELP_CALENDAR: 'カーソルキーを使用してカレンダーの日付をナビゲートする',
		BUTTON_SIGN_UP: '新規登録',
		BUTTON_BULK_DELETE: '一括削除',
		BUTTON_RETURN: '戻る',
		BUTTON_BACK: '戻る',
		BUTTON_EDIT: '編集',
		BUTTON_SAVE: '保存',
		BUTTON_CREATE: '作成',
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
		LIST_SCHEDULE: '運行情報',
		DATA_MANAGEMENT: 'データ管理',
		LIST_DRIVER: '従業員情報',
		LIST_COURSE: '荷主情報',
		LIST_COURSE_PATTERN: 'コース組み合わせ表',
		LIST_USER: 'ユーザー情報',
		CASH_MANAGEMENT: '入出金管理',
		LIST_CASH_RECEIPT: '入金情報',
		LIST_CASH_DISBURSEMENT: '出金情報',
	},
	LAYOUT: {
		LOGOUT: 'ログアウト',
	},
	LOGIN: {
		TITLE_LOGIN: 'ログイン',
		PLACEHOLDER_USER_ID: 'Crew ID',
		PLACEHOLDER_USER_PASSWORD: 'パスワード',
		BUTTON_LOGIN: 'ログイン',
	},
	LIST_SHIFT: {
		TITLE_EDIT_COURSE_BASE: 'Crewを選択',
		TITLE_LIST_SHIFT: ' シフト表',
		// TITLE_LIST_SHIFT_PRACTICAL_RECORD_TABLE: 'シフト表',
		TITLE_LIST_SHIFT_PRACTICAL_RECORD_TABLE: '実務実績表',
		TABLE_SALARY: '売上金額表',
		BUTTON_WEEK: '週',
		BUTTON_MONTH: '月',
		BUTTON_SHIFT_CREATION: 'シフト生成',
		BUTTON_SELECT_CLOSING_DATE: '締日選択',
		BUTTON_ATMTC: 'ATMTC連携',
		BUTTON_DOWNLOAD_EXCEL: 'Excel出力',
		BUTTON_DOWNLOAD_PDF: 'PDF出力',
		BUTTON_SHIFT_TABLE: ' シフト表',
		TITLE_EXPENSE: '経費表',
		HIGHT_WAY_FEE: '高速代金表',
		EXPENSE_LIST: '経費表',
		PAYMENT_TABLE: '支払代金表',
		// BUTTON_SHIFT_TABLE: 'シフト表<br />Crewベース',
		// BUTTON_COURSE_BASE: 'シフト表コースベース',
		BUTTON_COURSE_BASE: 'シフト表<br />コースベース',
		TITLE_COURSE_BASE: 'シフト表',
		BUTTON_PRACTICAL_ACHIEVEMENTS_MONTHLY: '実務実績表',
		BUTTON_PRACTICAL_PERFORMANCE_BY_CLOSING_DATE: '実務実績締日別',
		BUTTON_TABLE_SALES: '売上金額表',
		BUTTON_HIGHT_WAY_FEE: '高速代金表',
		BUTTON_EXPENSE: '経費表',
		BUTTON_PAYMENT: '支払代金表',
		BUTTON_RETURN: '戻る',
		BUTTON_EDIT: '編集',
		BUTTON_SAVE: '保存',
		BUTTON_CHANGE: '変更',
		BUTTON_OK: 'OK',
		BUTTON_TEMPORARY: '仮締め',
		BUTTON_FINAL_CLOSING_DATE: '本締め',
		TABLE_DATE_EMPLOYEE_NUMBER: '社員番号',
		TABLE_FULL_NAME: '氏名',
		STAND_BY: '待機',
		INTERNAL_BUSINESS: '社内業務',
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
		TABLE_EXPENSE_ID: '社員番号',
		TABLE_EXPENSE_TYPE: '社員区分',
		TABLE_EXPENSE_NAME: '氏名',
		SELECT_HOLIDAY: '公休',
		SELECT_FIXED_DAY_OFF: '固定休',
		SELECT_DAY_OFF_REQUEST: '希望休',
		SELECT_PAID: '有給休暇',
		LABEL_START_TIME: '始業時間: ',
		LABEL_CLOSING_TIME: '終業時間: ',
		LABEL_BREAK_TIME: '休憩時間: ',

		HALF_DAY_OF: '半休',
		FREE: '手間',

		TABLE_DRIVER_CODE: '荷主番号',
		TABLE_CUSTOMER_ID: '荷主ID',
		TABLE_DRIVER_TYPE: '締日',
		TABLE_DUA_DATE_CUSTOMER: '締日',
		TABLE_DRIVER_NAME: '荷主名',
		TABLE_CUSTOMER_NAME: '荷主名',
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
		TABLE_TOTAL: '食事補助・歩合締日別合計',
		TABLE_HIGHT_WAY_FREE_CUSTOMER_ID: '荷主ID',
		TABLE_HIGHT_WAY_DUE_DATE: '締日',
		TABLE_HIGHT_WAY_CUSTOMER_NAME: '荷主名',
		TABLE_HIGHT_WAY_MONTHLY_AMOUNT: '月別合計',
		TABLE_TOTAL_EXPENSE: ' 締日別合計',
		TABLE_PAYMENT_COMPANY_ID: '協力会社ID',
		TABLE_PAYMENT_DUE_DATE: '締日',
		TABLE_COMPANY_NAME: '協力会社名',
		TABLE_VEHICLE_NUMBER: '車両番号',
		TABLE_PAYMENT_MONTHLY_AMOUNT: '月別合計',

		TITLE_LIST_COURSE: 'シフト表',
		TABLE_FLAG: '社員区分',
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

		SALES_TOTAL: '合計',
		SALES_MONTH: '月別合計',
		SALES_CLOSING_DATE: ' 締日別合計',
		SALES_INVOICE: ' 請求書',
		TABLE_COURSE_COURSE_ID: 'コース ID',
		TABLE_COURSE_COURSE_GROUP: 'グループ',
		TABLE_COURSE_COURSE_NAME: 'コース名',
		TOTAL_FARE_NAME: '運賃合計',
		CONSUMPTION_TAX: '消費税',
		TOTAL: '合計',
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
		TITLE_LIST_SCHEDULE: '運行情報',
		TITLE_COURSE_RATE_RANGE_START_TIME: '運行日 検索範囲指定',
		TITLE_CUSTOMER_NAME: '荷主名',
		TITLE_DRIVER_NAME: '従業員名',
		BUTTON_RESET: 'リセット',
		BUTTON_SEARCH: '検索',
		TABLE_COURSE_DATE: '日付',
		TABLE_COURSE_NAME: '運行名',
		TABLE_DRIVER_NAME: '従業員名',
		TABLE_CUSTUM_NAME: '荷主名',
		TABLE_DEPATURE_PLACE: '発地',
		TABLE_FREIGHT_COST: '運賃',
		TABLE_ARRIVAL_PLACE: '着地',
		TABLE_FILL: 'フィルタ',
		TABLE_COURSE_GROUP_CODE: 'グループ',
		TABLE_TITLE_DETAIL: '詳細',
		TABLE_TITLE_DELETE: '削除',
	},

	CREATE_SCHEDULE: {
		TITLE_CREATE_SCHEDULE: '運行情報新規登録',
		FEE_INFORMATION: '料金情報',
		BASIC_INFORMATION: '基本情報',
		COOPERATING_COMPANY_PAYMENT_AMOUNT: '協力会社支払金額',
		HIGHT_WAY: '高速道路・フェリー料金',
		EXPENSES: '歩合',
		BONUS_TARGET: 'ボーナス対象者',
		BONUS_AMOUNT: '食事補助金額',
		SHIP_DATE: '運行日',
		COURSE_NAME: '運行名',
		DRIVER_NAME: '従業員名',
		VIHICLE_NUMBER: '車両番号',
		START_TIME: '始業時間',
		END_TIME: ' 終業時間',
		BREAK_TIME: '休憩時間',
		CUSTUM_NAME: '荷主名',
		DEPATURE_PLACE: '発地',
		ARRIVAL_PLACE: '着地',
		FREIGHT_COST: '運賃',
		QUANTITY: '数量',
		ITEM_NAME: '品名',
		UNIT_PRICE: '単価',
		WEIGHT: '重量',
		NOTE: 'メモ:',
		BUTTON_RETURN: 'キャンセル',
		BUTTON_SAVE: '保存',
	},
	DETAIL_SCHEDULE: {
		TITLE_DETAIL_SCHEDULE: '運行情報詳細',
		FEE_INFORMATION: '料金情報',
		BASIC_INFORMATION: '基本情報',
		COOPERATING_COMPANY_PAYMENT_AMOUNT: '協力会社支払金額',
		HIGHT_WAY: '高速道路・フェリー料金',
		COMMISSION: '歩合',
		MEAL_SUBSIDY_AMOUNT: '食事補助金額',
		// BONUS_AMOUNT: 'ボーナス金額',
		SHIP_DATE: '運行日',
		COURSE_NAME: '従業員名',
		VIHICLE_NUMBER: '車両番号',
		START_TIME: '始業時間',
		END_TIME: ' 終業時間',
		BREAK_TIME: '休憩時間',
		CUSTUM_NAME: '荷主名',
		DEPATURE_PLACE: '発地',
		ARRIVAL_PLACE: '着地',
		FREIGHT_COST: '運賃',
		ITEM_NAME: '品名',
		QUANTITY: '数量',
		UNIT_PRICE: '単価',
		WEIGHT: '重量',
		NOTE: 'メモ:',
	},

	EDIT_SCHEDULE: {
		TITLE_EDIT_SCHEDULE: '運行情報編集',
	},
	FILTER: {
		TITLE: 'フィルタ',
	},

	LIST_DRIVER: {
		TITLE_LIST_DRIVER: '従業員情報',
		BUTTON_SIGN_UP: '新規登録',
		TABLE_TITLE_EMPLOYEE_NUMBER: '社員番号',
		TABLE_TITLE_FULL_NAME: '氏名',
		TABLE_TITLE_TYPE_NAME: '社員区分',
		TABLE_TITLE_ENROLLMENT_STATUS: '在籍状況',
		TABLE_TITLE_DETAIL: '詳細',
		TABLE_TITLE_DELETE: '削除',
		ENROLLMENT_STATUS_RETIRED: '退職済み',
		ENROLLMENT_STATUS_ENROLLED: '在籍',
		TEXT_CONFIRM_DELETE: '本当に削除しますか？',
		TEXT_CONFIRM: '確認',
		TEXT_CANCEL: 'キャンセル',
	},
	CREATE_DRIVER: {
		TITLE_CREATE_DRIVER: '従業員新規登録',
		TITLE_EMPLOYEE_DETAILS: '従業員詳細',
		TITLE_EDIT_DRIVER: '従業員編集',
		TAB_TITLE_BASIC_INFORMATION: '基本情報',
		TAB_TITLE_COURSE_INFORMATION: 'コース情報',
		FORM_PATH_BASIC_INFORMATION: '基本情報',
		FORM_PATH_WORKING_CONDITIONS: '労働条件',
		FORM_PATH_RETIREMENT_DATE: '退職日',
		DIRECTOR: '主任',
		FUNERAL_ONLY_DRIVER: '葬儀のみドライバー',
		TYPE_EMPLOYEE: '社員区分',
		EMPLOYEE_NUMBER: '社員番号',
		FULL_NAME: '氏名',
		CHARACTER: '車両番号',
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
		ASSOCIATE_COMPANY: '協力会社',
	},
	DETAIL_DRIVER: {
		TITLE_DETAIL_DRIVER: '従業員詳細',
		TITLE_EMPLOYEE_DETAILS: '従業員詳細',
		TAB_TITLE_BASIC_INFORMATION: '基本情報',
		TAB_TITLE_COURSE_INFORMATION: 'コース情報',
		FORM_PATH_BASIC_INFORMATION: '基本情報',
		FORM_PATH_WORKING_CONDITIONS: '労働条件',
		FORM_PATH_RETIREMENT_DATE: '退職日',
		DIRECTOR: '主任',
		FUNERAL_ONLY_DRIVER: '葬儀のみドライバー',
		EMPLOYEE_NUMBER: 'Crew番号',
		FULL_NAME: '氏名',
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
		TITLE_EDIT_DRIVER: '従業員編集',
		TITLE_EMPLOYEE_DETAILS: '従業員詳細',
		TAB_TITLE_BASIC_INFORMATION: '基本情報',
		TAB_TITLE_COURSE_INFORMATION: 'コース情報',
		FORM_PATH_BASIC_INFORMATION: '基本情報',
		FORM_PATH_WORKING_CONDITIONS: '労働条件',
		FORM_PATH_RETIREMENT_DATE: '退職日',
		DIRECTOR: '主任',
		FUNERAL_ONLY_DRIVER: '葬儀のみドライバー',
		EMPLOYEE_NUMBER: 'Crew番号',
		FULL_NAME: '氏名',
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
	// LIST_COURSE: {
	// 	TITLE_LIST_COURSE: 'コース情報',
	// 	TABLE_COURSE_ID: 'コースID',
	// 	TABLE_COURSE_NAME: 'コース名',
	// 	TABLE_OPERATIONAL_INFORMATION: '運行情報',
	// 	TABLE_START_TIME: '始業時間',
	// 	TABLE_CLOSING_TIME: '終業時間',
	// 	TABLE_BREAK_TIME: '休憩時間',
	// 	TABLE_DETAIL: '詳細',
	// 	TABLE_DELETE: '削除',
	// 	TEXT_CONFIRM_DELETE: '本当に削除しますか？',
	// 	TEXT_CONFIRM: '確認',
	// 	TEXT_CANCEL: 'キャンセル',
	// },
	LIST_COURSE: {
		TITLE_LIST_COURSE: '荷主情報',
		TABLE_COURSE_ID: '荷主ID',
		TABLE_COURSE_NAME: '荷主名',
		TABLE_OPERATIONAL_INFORMATION: '締日',
		TABLE_DETAIL: '詳細',
		TABLE_DELETE: '削除',
		TEXT_CONFIRM_DELETE: '本当に削除しますか？',
		TEXT_CONFIRM: '確認',
		TEXT_CANCEL: 'キャンセル',
	},
	// COURSE_CREATE: {
	// 	TITLE_COURSE_CREATE: 'コース新規登録',
	// 	FORM_BASIC_INFORMATION: '基本情報',
	// 	SHUTTLE_DELIVERY: '送迎配送',
	// 	EASY_TO_DISTINGUISH: '便区分',
	// 	COURSE_ID: 'コースID',
	// 	COURSE_NAME: 'コース名',
	// 	EXCLUSIVE: ' ',
	// 	COURSE_TYPE: 'グループ',
	// 	START_TIME: '始業時間',
	// 	END_TIME: '終業時間',
	// 	BREAK_TIME: '休憩時間',
	// 	FATIGUE: 'ポイント',
	// 	START_DATE: '開始日',
	// 	END_DATE: '終了日',
	// 	NOTE: 'メモ:',
	// 	GROUP_CODE: 'グループ',
	// 	CHECKBOX_SPOT_FLIGHT: 'スポット便',
	// 	CHECKBOX_SHORT_FLIGHT: 'ショート便',
	// },

	CUSTOMER_CREATE: {
		TITLE_CUSTOMER_CREATE: '荷主新規登録',
		POST_CODE: '郵便番号',
		SALE_TAX: '消費税',
		FORM_BASIC_INFORMATION: '荷主情報',
		COURSE_ID: '荷主ID',
		COURSE_NAME: '荷主名',
		CLOSING_DAY: '締日',
		CLIENT_MANAGER: '担当者',
		ADDRESS_OF_CLIENT: '住所',
		CLIENT_EMAIL: '請求書送付連絡先',
		CLIENT_PHONE: '請求書送付連絡先',
		NOTE: 'メモ:',
		TAX: '消費税:',
		TAX_OPTION: {
			TAX_INCLUDE: '内税',
			TAX_EXCLUDE: '外税',
		},
	},
	CUSTOMER_DETAIL: {
		TITLE_CUSTOMER_DETAIL: '荷主詳細',
	},
	CUSTOMER_EDIT: {
		TITLE_CUSTOMER_EDIT: '荷主編集',
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
		ROLE_DRIVER: 'ドライバー',
		ROLE_SYSTEM_ADMINISTRATOR: 'システム管理者',
		TEXT_CONFIRM_DELETE: '本当に削除しますか？',
		TEXT_CONFIRM: '確認',
		TEXT_CANCEL: 'キャンセル',
	},
	LIST_CASH: {
		TITLE_LIST_CASH: '入金情報 一覧',
		TABLE_CASH_ID: '荷主ID',
		TABLE_CASH_NAME: '荷主名',
		TABLE_CASH_BALANCE_AT_END_OF_PREVIOUS_MONTH: '前月末残高',
		TABLE_CASH_ACCOUNTS_RECEIVABLE: '当月売掛金',
		TABLE_TOTAL_ACCOUNTS_RECEIVABLE: '売掛金合計',
		TABLE_MONTHLY_DEPOSIT_AMOUNT: '当月入金金額',
		TABLE_CURRENT_MONTH_BALANCE: '当月残高',
		TABLE_DETAIL: '詳細',
		BUTTON_RETURN: 'キャンセル ',
		BUTTON_SAVE: '登録',
		BUTTON_KEEP: '保存',

		TITLE_CASH_DETAIL: '入金情報 詳細',
		FORM_BASIC_INFORMATION: '荷主情報',
		DEPOSIT_INFORMATION: '入金情報',

		TABLE_NO: 'No',
		TABLE_DATE: '日付',
		TABLE_DEPOSIT_AMOUNT: '入金金額',
		TABLE_PAYMENT_METHOD: '入金方法',
		TABLE_REMARKS: '備考',
		TABLE_TOTAL: '当月入金金額合計',

		TITLE_CASH_CREATE: '入金情報 登録',
		TITLE_CASH_EDIT: '入金情報 編集',
		PAYMENT_DAY: '入金日',

		TITLE_LIST_CASH_DISBURSEMENT: '出金情報 一覧',
		TABLE_CASH_DISBURSEMENT_ID: '協力会社ID',
		TABLE_CASH_DISBURSEMENT_NAME: '協力会社名',
		TABLE_CASH_DISBURSEMENT_BALANCE_AT_END_OF_PREVIOUS_MONTH: '前月末残高',
		TABLE_CASH_DISBURSEMENT_ACCOUNTS_RECEIVABLE: '当月買掛金',
		TABLE_CASH_DISBURSEMENT_TOTAL_ACCOUNTS_RECEIVABLE: '買掛金合計',
		TABLE_CASH_DISBURSEMENT_MONTHLY_DEPOSIT_AMOUNT: '当月出金金額',
		TABLE_CASH_DISBURSEMENT_CURRENT_MONTH_BALANCE: '当月残高',

		FORM_CASH_DISBURSEMENT_BASIC_INFORMATION: '協力会社情報',
		CASH_DISBURSEMENT_DEPOSIT_INFORMATION: '出金情報',
		TITLE_CASH_DISBURSEMENT_DETAIL: '出金情報 詳細',
		TABLE_CASH_DISBURSEMENT_NO: 'No.',
		TABLE_CASH_DISBURSEMENT_DATE: '日付',
		TABLE_CASH_DISBURSEMENT_DEPOSIT_AMOUNT: '出金金額',
		TABLE_CASH_DISBURSEMENT_PAYMENT_METHOD: '出金方法',
		TABLE_CASH_DISBURSEMENT_REMARKS: '備考',
		TABLE_CASH_DISBURSEMENT_TOTAL: '当月出金金額',
		TABLE_EDIT: '編集',
		TABLE_DELETE: '削除',
		MESSAGE_DELETE: 'このデータを削除してもよろしいですか？',

		TITLE_CASH_DISBURSEMENT_CREATE: '出金情報 登録',
		CASH_DISBURSEMENT_PAYMENT_DAY: '出金日',
		NOTE_CASH_DISBURSEMENT: '備考',
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
