export default {
	TOAST: {
		SUCCESS: 'Success',
		WARNING: 'Warning',
		DANGER: 'Error',
	},
	MESSAGE_APP: {
		EXCEPTION: 'System error',

		TOKEN_EXPIRE: 'Token expire',

		// LOGIN
		LOGIN_SUCCESS: 'Login success',

		LOGIN_REQUIRED: 'Please enter full user id and password',

		LOGIN_VALIDATE_USER_ID: 'User ID must be 4 digits',
		LOGIN_VALIDATE_PASSWORD: 'Password must be 8 to 16 characters',

		// LOGOUT
		LOGOUT_SUCCESS: 'Logout success',

		// USER MANAGEMENT
		USER_MANAGEMENT_CREATE_SUCCESS: 'Create user success',
		USER_MANAGEMENT_UPDATE_SUCCESS: 'Update user success',
		USER_MANAGEMENT_DELETE_SUCCESS: 'Delete user success',

		USER_MANAGEMENT_VALIDATE_REQUIRED: 'Some items have not been entered or selected.',
		USER_MANAGEMENT_VALIDATE_USER_CODE: 'User code must be 1 to 4 digits',
		USER_MANAGEMENT_VALIDATE_USER_NAME: 'User name must be 1 to 20 characters',
		USER_MANAGEMENT_VALIDATE_USER_PASSWORD: 'Password must be 8 to 16 characters',
		USER_MANAGEMENT_VALIDATE_USER_ROLE: 'Role must be selected',

		// DRIVER MANAGEMENT
		DRIVER_MANAGEMENT_CREATE_SUCCESS: 'Create driver success',
		DRIVER_MANAGEMENT_UPDATE_SUCCESS: 'Update driver success',
		DRIVER_MANAGEMENT_DELETE_SUCCESS: 'Delete driver success',

		DRIVER_MANAGEMENT_CLICK_TAB_COURSE: 'Some items have not been entered.',
		DRIVER_MANAGEMENT_VALIDATE_REQUIRED: 'Some items have not been entered or selected.',
		DRIVER_MANAGEMENT_VALIDATE_DRIVER_CODE: 'Driver code must be 1 to 15 digits',
		DRIVER_MANAGEMENT_VALIDATE_DRIVER_NAME: 'Driver name must be 1 to 20 characters',
		DRIVER_MANAGEMENT_VALIDATE_GRADE: 'Grade must be 1 to 10 digits',

		// DRIVER COURSE MANAGEMENT
		DRIVER_COURSE_MANAGEMENT_CREATE_SUCCESS: 'Create driver course success',
		DRIVER_COURSE_MANAGEMENT_UPDATE_SUCCESS: 'Update driver course success',
		DRIVER_COURSE_MANAGEMENT_DELETE_SUCCESS: 'Delete driver course success',

		// COURSE MANAGEMENT
		SCHEDULE_MANAGEMENT_CREATE_SUCCESS: 'Create customer success',
		SCHEDULE_MANAGEMENT_UPDATE_SUCCESS: 'Update customer success',
		SCHEDULE_MANAGEMENT_DELETE_SUCCESS: 'Delete customer success',

		// CUSTOMER MANAGEMENT
		COURSE_MANAGEMENT_CREATE_SUCCESS: 'Create customer success',
		COURSE_MANAGEMENT_UPDATE_SUCCESS: 'Update customer success',
		COURSE_MANAGEMENT_DELETE_SUCCESS: 'Delete customer success',

		COURSE_MANAGEMENT_VALIDATE_REQUIRED: 'Some items have not been entered or selected.',
		COURSE_MANAGEMENT_VALIDATE_COURSE_CODE: 'Course code must be 1 to 15 digits',
		COURSE_MANAGEMENT_VALIDATE_GROUP: 'Group course must have 2 characters',
		COURSE_MANAGEMENT_VALIDATE_COURSE_NAME: 'Course name must be 1 to 30 characters',
		COURSE_MANAGEMENT_VALIDATE_START_TIME_GREATER_THAN_END_TIME: 'Start time must be greater than end time',
		COURSE_MANAGEMENT_VALIDATE_START_TIME_EQUALS_END_TIME: 'Start time must not be equal to end time',
		COURSE_MANAGEMENT_VALIDATE_BREAK_TIME_GREATER_THAN_COURSE_TIME: 'Break time must be less than course time',
		COURSE_MANAGEMENT_VALIDATE_POINT: 'You have not entered the correct format of the point course',
		COURSE_MANAGEMENT_VALIDATE_START_DATE: 'Start date is not in the correct format',
		COURSE_MANAGEMENT_VALIDATE_END_DATE: 'End date is not in the correct format',
		COURSE_MANAGEMENT_VALIDATE_START_END_DATE: 'Start date must be less than end date',
		COURSE_MANAGEMENT_VALIDATE_NOTE: 'Note must be 1 to 1000 characters',

		// COURSE PATTERN
		COURSE_PATTERN_VALIDATE_LIST_UPDATE_EMPTY: 'Update course pattern success',

		// DAY-OFF
		DAY_OFF_VALIDATE_SELECT_TYPE_DAY: 'You have not selected the type of holiday',
		DAY_OFF_UPDATE_SUCCESS: 'Update day-off success',

		// SCHEDULE
		COURSE_SCHEDULE_UPDATE_SUCCESS: 'Update Course schedule success',
		SCHEDULE_UPDATE_SUCCESS: 'Update schedule success',
		SCHEDULE_CREATE_SUCCESS: 'Create course schedule success',
		SCHEDULE_VALIDATE_FILE_REQUIRED: 'Please select file',
		SCHEDULE_VALIDATE_FILE_TYPE: 'File type must be .csv',
		SCHEDULE_VALIDATE_FILE_SIZE: 'File size must be less than 3MB',
		SCHEDULE_IMPORT_SUCCESS: 'Import success',
		SCHEDULE_IMPORT_FAIL: 'Course ID {listCourse} could not be imported.',

		// LIST SHIFT
		LIST_SHIFT_UPDATE_SUCCESS: 'Update list shift success',
		LIST_SHIFT_VALIDATE_LIST_UPDATE_EMPTY: 'List update empty',

		LIST_SHIFT_VALIDATE_SELECTED_DATE: 'You must select start date and end date',

		LIST_SHIFT_VALIDATE_REQUIRED: 'Some items have not been entered or selected',
		LIST_SHIFT_VALIDATE_REQUIRED_TYPE: 'You must select type',
		LIST_SHIFT_VALIDATE_REQUIRED_TIME: 'You must select time',
		LIST_SHIFT_VALIDATE_DUPLICATE_DATE_COURSE: 'You have selected the same ingredients',
		LIST_SHIFT_VALIDATE_TIME_START_END_COURSE: 'Time of selection component overlaps',
		LIST_SHIFT_VALIDATE_TIME_BREAK_COURSE: 'Break time of selection component overlaps',
		LIST_SHIFT_VALIDATE_DUPLICATE_TIME_COURSE: 'The time of the overlap',

		// CASH MANAGEMENT
		CASH_OUT_DELETE_SUCCESS: 'Delete cash out success',
		CASH_OUT_CREATE_SUCCESS: 'Create cash out success',
		CASH_OUT_UPDATE_SCUCCESS: 'Update cash pit success',

		CASH_IN_CREATE_SUCCESS: 'Create cash in success',
		CASH_IN_DELETE_SUCCESS: 'Delete cash in success',
		CASH_IN_UPDATE_SCUCCESS: 'Update cash in success',
	},
	APP: {
		PLEASE_WAIT: 'Please wait...',
		LOADING: 'Loading...',

		PLEASE_SELECT: 'Please select',
		LABLE_HELP_CALENDAR: 'Use cursor keys to navigate calendar dates',

		BUTTON_SIGN_UP: 'Sign up',
		BUTTON_BULK_DELETE: 'Bulk delete',
		BUTTON_RETURN: 'Return',
		BUTTON_EDIT: 'Edit',
		BUTTON_CREATE: 'Create',
		BUTTON_SAVE: 'Save',
		BUTTON_CHANGE: 'Change',
		BUTTON_OK: 'OK',
		BUTTON_GO_TO_HOME: 'Go to home',

		TABLE_NO_DATA: 'No data',

		TITLE_MODAL_CONFIRM: 'Confirm',

		TEXT_CONFIRM: 'Confirm',
		TEXT_CANCEL: 'Cancel',
		TEXT_CLOSE: 'Close',

		TEXT_RESET: 'Reset',

		TEXT_CALENDAR_PLACEHOLDER: 'No data',
	},
	ROOT_APP_ACTION_LOAD: {
		TITLE: 'Please confirm',
		MESSAGE: 'The latest update data has not yet been saved. All updated data will be lost if you proceed. continue?',
		RETURN: 'Return',
		CONTINUE: 'Continue',
	},
	ROUTER: {
		DEV: 'Dev',
		PAGE_NOT_FOUND: 'Page Not Found',
		LOGIN: 'Login',

		SHIFT_MANAGEMENT: 'Shift Management',
		LIST_SHIFT: 'Lift Shift',
		LIST_DAY_OFF: 'List Day Off',
		LIST_SCHEDULE: 'List Schedule',

		DATA_MANAGEMENT: 'Data Management',
		LIST_DRIVER: 'List Driver',
		LIST_COURSE: 'List Customer',
		LIST_COURSE_PATTERN: 'List Course Pattern',
		LIST_USER: 'List User',

		CASH_MANAGEMENT: 'Cash Management',
		LIST_CASH_RECEIPT: 'Cash Receipt',
		LIST_CASH_DISBURSEMENT: 'Cash Disbursement',
	},
	LAYOUT: {
		LOGOUT: 'Logout',
	},
	LOGIN: {
		TITLE_LOGIN: 'LOGIN',
		PLACEHOLDER_USER_ID: 'Crew ID',
		PLACEHOLDER_USER_PASSWORD: 'Password',
		BUTTON_LOGIN: 'Login',
	},
	LIST_SHIFT: {
		TITLE_EDIT_COURSE_BASE: 'Change driver run',
		TITLE_LIST_SHIFT: 'List Shift',
		TITLE_LIST_SHIFT_PRACTICAL_RECORD_TABLE: 'List Express Change',
		TABLE_SALARY: 'Sales amount table',
		BUTTON_WEEK: 'Week',
		BUTTON_MONTH: 'Month',

		BUTTON_SHIFT_CREATION: 'Shift creation',
		BUTTON_SELECT_CLOSING_DATE: 'Select closing date',
		BUTTON_ATMTC: 'ATMTC',
		BUTTON_DOWNLOAD_EXCEL: 'Download Excel',
		BUTTON_DOWNLOAD_PDF: 'Download PDF',
		HIGHT_WAY_FEE: 'List Hight Way Fee',
		PAYMENT_TABLE: 'List Payment',

		BUTTON_SHIFT_TABLE: 'Shift table',
		BUTTON_HIGHT_WAY_FEE: 'Hight way fee table',
		BUTTON_PAYMENT: 'Payment table',
		BUTTON_COURSE_BASE: 'Course base',
		TITLE_COURSE_BASE: 'Course base',
		BUTTON_PRACTICAL_ACHIEVEMENTS_MONTHLY: 'Practical achievements monthly',
		BUTTON_PRACTICAL_PERFORMANCE_BY_CLOSING_DATE: 'Practical performance by closing date',
		BUTTON_TABLE_SALES: 'Sales amount table',

		BUTTON_RETURN: 'Return',
		BUTTON_EDIT: 'Edit',
		BUTTON_SAVE: 'Save',
		BUTTON_CHANGE: 'Change',
		BUTTON_OK: 'OK',
		BUTTON_TEMPORARY: 'Temporary',
		BUTTON_FINAL_CLOSING_DATE: 'Final',

		TABLE_DATE_EMPLOYEE_NUMBER: 'Employee number',
		TABLE_FULL_NAME: 'Full name',
		STAND_BY: 'Stand-by',
		INTERNAL_BUSINESS: 'Internal business',
		TABLE_DATE_HOLIDAY: 'Holiday',
		TABLE_DATE_FIXED_DAY_OFF: 'Fixed day off',
		TABLE_DATE_DAY_OFF_REQUEST: 'Day off request',
		TABLE_DATE_PAID: 'Paid',
		TABLE_DATE_LEADER_CHIEF: 'Leader/Chief',
		TABLE_DATE_WAIT: 'Wait',
		TABLE_DATE_WAIT_BETWEEN_TASK: 'Wait between task',
		TABLE_TOTAL: 'Total by date of meal assistance and commission closing',
		TABLE_HIGHT_WAY_FREE_CUSTOMER_ID: 'Customer ID',
		TABLE_HIGHT_WAY_DUE_DATE: 'Due date',
		TABLE_HIGHT_WAY_CUSTOMER_NAME: 'Customer name',
		TABLE_HIGHT_WAY_MONTHLY_AMOUNT: 'Monthly amount',
		HALF_DAY_OF: 'Half Day Off',
		TABLE_PAYMENT_COMPANY_ID: 'Cooperate company ID',
		TABLE_PAYMENT_DUE_DATE: 'Due date',
		TABLE_COMPANY_NAME: 'Cooperate company name',
		TABLE_PAYMENT_MONTHLY_AMOUNT: 'Monthly amount',
		FREE: 'Free',

		SELECT_WAIT: 'Wait',
		SELECT_WAIT_BETWEEN_TASK: 'Wait between task',
		SELECT_LEADER_CHIEF: 'Leader/Chief',
		SELECT_HOLIDAY: 'Holiday',
		SELECT_FIXED_DAY_OFF: 'Fixed day off',
		SELECT_DAY_OFF_REQUEST: 'Day off request',
		SELECT_PAID: 'Paid',

		LABEL_START_TIME: 'Start time: ',
		LABEL_CLOSING_TIME: 'Closing time: ',
		LABEL_BREAK_TIME: 'Break time: ',

		TABLE_DRIVER_CODE: 'Customer ID',
		TABLE_CUSTOMER_ID: 'Customer ID',
		TABLE_DRIVER_TYPE: 'Due date',
		TABLE_DUA_DATE_CUSTOMER: 'Due date',
		TABLE_DRIVER_NAME: 'Customer name',
		TABLE_CUSTOMER_NAME: 'Customer name',
		TABLE_NUMBER_OF_PAID_HOLIDAYS: 'Number of paid holidays',
		TABLE_TOTAL_TIME: 'Days can work',
		TABLE_DRIVING_TIME: 'Working days',
		TABLE_OVER_TIME: 'Days off',
		TABLE_WORKING_DAYS: 'Days off request',
		TABLE_DAY_OFF: 'Total time',
		TABLE_PAID_HOLIDAYS: 'Driving time',
		TABLE_ONE_DAY_MAX_TOTAL_TIME: 'Breakl time',
		TABLE_ONE_DAY_MAX_DRIVING_TIME: 'Over time',
		TABLE_FIFTEEN_HOURS_OVER_WORKING_DAYS: 'Point',

		TITLE_LIST_COURSE: 'List Course',
		TABLE_FLAG: 'Flag',

		MODAL_CONFIRM_AI: '{start_year}年{start_month}月{start_date}日(土)〜{end_year}年{end_month}月{end_date}日(金) Shifts have already been created. Do you want to overwrite and create a shift?',
		MODAL_CONFIRM_AI_CANCEL: 'Cancel',
		MODAL_CONFIRM_AI_OK: 'Continue',

		MODAL_TITLE_VIEW_LOG: 'View log AI',
		TABLE_TITLE_NO: 'No',
		TABLE_EXECUTION_DATE_AND_TIME: 'Execution date and time',
		TABLE_START_TIME: 'Start time',
		TABLE_END_TIME: 'End time',
		TABLE_STATUS: 'Status',
		TABLE_MESSAGE: 'Message',

		MODAL_TITLE_DETAIL_VIEW_LOG: 'Detail log AI',
		TEXT_TOTAL_MESSAGE: 'Total message: {total}',

		MESSAGE_AI_SUCCESS: 'Shift table generation completed',
		MESSAGE_AI_ERROR: 'Failed to generate shift table. <br />Check the error details in the execution history.',

		SALES_TOTAL: 'Total',
		SALES_MONTH: 'Month ',
		SALES_CLOSING_DATE: 'Closing date ',
		SALES_INVOICE: 'Invoice ',
		TABLE_COURSE_COURSE_ID: 'Course ID',
		TABLE_COURSE_COURSE_GROUP: 'Group',
		TABLE_COURSE_COURSE_NAME: 'Course name',

	},
	DAY_OFF: {
		TITLE_LIST_DAY_OFF: 'Holiday request information',

		TABLE_DATE_EMPLOYEE_NUMBER: 'Employee number',
		TABLE_FLAG: 'Flag',
		TABLE_FULL_NAME: 'Full name',

		TABLE_DATE_FIXED_DAY_OFF: 'Fixed day off',
		TABLE_DATE_DAY_OFF_REQUEST: 'Day off request',
		TABLE_DATE_PAID: 'Paid',
		TABLE_DEFAULT: '-',

		MODAL_CHANGE_STATUS_DATE: 'Change status date',

		SELECT_TYPE_HOLIDAY: 'Holiday',
		SELECT_TYPE_WORK: 'Work',
	},
	LIST_SCHEDULE: {
		TITLE_LIST_SCHEDULE: 'List Schedule',
		TITLE_COURSE_RATE_RANGE_START_TIME: 'Course date range',
		TITLE_CUSTOMER_NAME: 'Customer name',
		BUTTON_RESET: 'Reset',
		BUTTON_SEARCH: 'Search',
		TABLE_COURSE_ID: 'Course ID',
		TABLE_COURSE_DATE: 'Date',
		TABLE_COURSE_NAME: 'Course name',
		TABLE_CUSTUM_NAME: 'Customer name',
		TABLE_DEPATURE_PLACE: 'Depature place',
		TABLE_FREIGHT_COST: 'Freight cost',
		TABLE_ARRIVAL_PLACE: 'Arrival place',
		TABLE_FILL: 'Fill',
		TABLE_TITLE_DETAIL: 'Detail',
		TABLE_COURSE_GROUP_CODE: 'グループ',
	},
	CREATE_SCHEDULE: {
		FEE_INFORMATION: 'Fee information',
		TITLE_CREATE_SCHEDULE: 'Create schedule',
		BASIC_INFORMATION: 'Basic information',
		COOPERATING_COMPANY_PAYMENT_AMOUNT: 'cooperating company payment amount',
		HIGHT_WAY: 'Expressway/ferry fee',
		EXPENSES: 'Commission',
		BONUS_TARGET: 'bonus target',
		BONUS_AMOUNT: 'Meal subsidy amount',
		SHIP_DATE: 'Ship date',
		COURSE_NAME: 'Name',
		START_TIME: 'Start time',
		END_TIME: ' End time',
		BREAK_TIME: 'Break time',
		CUSTUM_NAME: 'Custum name',
		DEPATURE_PLACE: 'Depature place',
		ARRIVAL_PLACE: 'Arrival place',
		FREIGHT_COST: 'Freight cost',
		NOTE: 'Note:',
	},
	DETAIL_SCHEDULE: {
		TITLE_DETAIL_SCHEDULE: 'Service information details',
		FEE_INFORMATION: 'Fee information',
		BASIC_INFORMATION: 'Basic information',
		COOPERATING_COMPANY_PAYMENT_AMOUNT: 'cooperating company payment amount',
		HIGHT_WAY: 'Expressway / ferry fee',
		COMMISSION: 'commission',
		MEAL_SUBSIDY_AMOUNT: 'Meal subsidy amount',
		// BONUS_AMOUNT: 'bonus amount',
		SHIP_DATE: 'Ship date',
		COURSE_NAME: 'Name',
		START_TIME: 'Start time',
		END_TIME: ' End time',
		BREAK_TIME: 'Break time',
		CUSTUM_NAME: 'Custum name',
		DEPATURE_PLACE: 'Depature place',
		ARRIVAL_PLACE: 'Arrival place',
		FREIGHT_COST: 'Freight cost',
		NOTE: 'Note:',
	},
	EDIT_SCHEDULE: {
		TITLE_EDIT_SCHEDULE: 'Operation information editing',
	},
	FILTER: {
		TITLE: 'filter',
	},
	LIST_DRIVER: {
		TITLE_LIST_DRIVER: 'List Driver',

		BUTTON_SIGN_UP: 'Sign up',
		TABLE_TITLE_EMPLOYEE_NUMBER: 'Crew code',
		TABLE_TITLE_FULL_NAME: 'Crew name',
		TABLE_TITLE_TYPE_NAME: 'Employee classification',
		TABLE_TITLE_ENROLLMENT_STATUS: 'Enrollment status',
		TABLE_TITLE_DETAIL: 'Detail',
		TABLE_TITLE_DELETE: 'Delete',

		ENROLLMENT_STATUS_RETIRED: 'Retired',
		ENROLLMENT_STATUS_ENROLLED: 'Enrolled',

		TEXT_CONFIRM_DELETE: 'Are you sure you want to delete this driver?',
		TEXT_CONFIRM: 'Confirm',
		TEXT_CANCEL: 'Cancel',
	},
	CREATE_DRIVER: {
		TITLE_CREATE_DRIVER: 'Create Driver',
		TITLE_EMPLOYEE_DETAILS: 'Employee details',

		TITLE_EDIT_DRIVER: 'Driver Edit',

		TAB_TITLE_BASIC_INFORMATION: 'Basic information',
		TAB_TITLE_COURSE_INFORMATION: 'Course information',

		FORM_PATH_BASIC_INFORMATION: 'Basic information',
		FORM_PATH_WORKING_CONDITIONS: 'Working conditions',
		FORM_PATH_RETIREMENT_DATE: 'Retirement date',

		DIRECTOR: 'Director',
		FUNERAL_ONLY_DRIVER: 'Funeral only driver',

		TYPE_EMPLOYEE: 'Employee classification',
		EMPLOYEE_NUMBER: 'Crew code',
		FULL_NAME: 'Crew name',
		CHARACTER: 'create Character',
		HIRE_DATE: 'Hire date',
		DATE_OF_BIRTH: 'Date of birth',
		GRADE: 'Grade',

		AVAILABLE_DAYS: 'Available days',
		DAY: 'Day',

		FIXED_HOLIDAYS: 'Fixed holidays',
		MONDAY: 'Monday',
		TUESDAY: 'Tuesday',
		WEDNESDAY: 'Wednesday',
		THURSDAY: 'Thursday',
		FRIDAY: 'Friday',
		SATURDAY: 'Saturday',
		SUNDAY: 'Sunday',

		WORKING_TIME: 'Working time:',
		WORKING_TIME_2: 'Working time',
		NO_MORE_THAN_15_HOURS_OF_WORK_PER_DAY: 'No more than 15 hours of work per day',
		NO_MORE_THAN_40_HOURS_OF_OVERTIME_PER_MONTH: 'No more than 40 hours of overtime per month',

		NOTES: 'Notes:',

		TABLE_RUNABLE_COURSE_ID: 'Runable course ID',
		TABLE_UNABLE_COURSE_NAME: 'Runable course name',
		TABLE_EXCLUSIVE: 'Exclusive',
		TABLE_FATIGUE: 'Point',
		TABLE_DELETE: 'Delete',

		LEADER: 'Leader',
		FULL_TIME: 'Full-time',
		PART_TIME: 'Part-time',
		ASSOCIATE_COMPANY: 'Associate company',
	},
	DETAIL_DRIVER: {
		TITLE_DETAIL_DRIVER: 'Detail Driver',
		TITLE_EMPLOYEE_DETAILS: 'Employee details',

		TAB_TITLE_BASIC_INFORMATION: 'Basic information',
		TAB_TITLE_COURSE_INFORMATION: 'Course information',

		FORM_PATH_BASIC_INFORMATION: 'Basic information',
		FORM_PATH_WORKING_CONDITIONS: 'Working conditions',
		FORM_PATH_RETIREMENT_DATE: 'Retirement date',

		DIRECTOR: 'Director',
		FUNERAL_ONLY_DRIVER: 'Funeral only driver',

		EMPLOYEE_NUMBER: 'Crew code',
		FULL_NAME: 'Crew name',
		HIRE_DATE: 'Hire date',
		DATE_OF_BIRTH: 'Date of birth',

		AVAILABLE_DAYS: 'Available days',
		DAY: 'Day',

		FIXED_HOLIDAYS: 'Fixed holidays',
		MONDAY: 'Monday',
		TUESDAY: 'Tuesday',
		WEDNESDAY: 'Wednesday',
		THURSDAY: 'Thursday',
		FRIDAY: 'Friday',
		SATURDAY: 'Saturday',
		SUNDAY: 'Sunday',

		WORKING_TIME: 'Working time:',
		WORKING_TIME_2: 'Working time',
		NO_MORE_THAN_15_HOURS_OF_WORK_PER_DAY: 'No more than 15 hours of work per day',
		NO_MORE_THAN_40_HOURS_OF_OVERTIME_PER_MONTH: 'No more than 40 hours of overtime per month',

		NOTES: 'Notes',

		TABLE_RUNABLE_COURSE_ID: 'Runable course ID',
		TABLE_UNABLE_COURSE_NAME: 'Runable course name',
		TABLE_EXCLUSIVE: 'Exclusive',
		TABLE_FATIGUE: 'Point',
		TABLE_DELETE: 'Delete',
	},
	EDIT_DRIVER: {
		TITLE_EDIT_DRIVER: 'Edit Driver',
		TITLE_EMPLOYEE_DETAILS: 'Employee details',

		TAB_TITLE_BASIC_INFORMATION: 'Basic information',
		TAB_TITLE_COURSE_INFORMATION: 'Course information',

		FORM_PATH_BASIC_INFORMATION: 'Basic information',
		FORM_PATH_WORKING_CONDITIONS: 'Working conditions',
		FORM_PATH_RETIREMENT_DATE: 'Retirement date',

		DIRECTOR: 'Director',
		FUNERAL_ONLY_DRIVER: 'Funeral only driver',

		EMPLOYEE_NUMBER: 'Crew code',
		FULL_NAME: 'Crew name',
		HIRE_DATE: 'Hire date',
		DATE_OF_BIRTH: 'Date of birth',

		AVAILABLE_DAYS: 'Available days',
		DAY: 'Day',

		FIXED_HOLIDAYS: 'Fixed holidays',
		MONDAY: 'Monday',
		TUESDAY: 'Tuesday',
		WEDNESDAY: 'Wednesday',
		THURSDAY: 'Thursday',
		FRIDAY: 'Friday',
		SATURDAY: 'Saturday',
		SUNDAY: 'Sunday',

		WORKING_TIME: 'Working time:',
		WORKING_TIME_2: 'Working time',
		NO_MORE_THAN_15_HOURS_OF_WORK_PER_DAY: 'No more than 15 hours of work per day',
		NO_MORE_THAN_40_HOURS_OF_OVERTIME_PER_MONTH: 'No more than 40 hours of overtime per month',

		NOTES: 'Notes:',

		TABLE_RUNABLE_COURSE_ID: 'Runable course ID',
		TABLE_UNABLE_COURSE_NAME: 'Runable course name',
		TABLE_EXCLUSIVE: 'Exclusive',
		TABLE_FATIGUE: 'Point',
		TABLE_DELETE: 'Delete',

		RUNABLE_COURSE_NAME: 'Runable course name',
		FATIGUE: 'Point',
	},
	// LIST_COURSE: {
	// 	TITLE_LIST_COURSE: 'List Course',

	// 	TABLE_COURSE_ID: 'Course ID',
	// 	TABLE_COURSE_NAME: 'Course name',
	// 	TABLE_OPERATIONAL_INFORMATION: 'Operational information',
	// 	TABLE_START_TIME: 'Start time',
	// 	TABLE_CLOSING_TIME: 'Closing time',
	// 	TABLE_BREAK_TIME: 'Break time',
	// 	TABLE_DETAIL: 'Detail',
	// 	TABLE_DELETE: 'Delete',

	// 	TEXT_CONFIRM_DELETE: 'Are you sure you want to delete this course?',
	// 	TEXT_CONFIRM: 'Confirm',
	// 	TEXT_CANCEL: 'Cancel',
	// },

	LIST_COURSE: {
		TITLE_LIST_COURSE: 'List Customer',
		TABLE_COURSE_ID: 'Customer ID',
		TABLE_COURSE_NAME: 'Customer name',
		TABLE_OPERATIONAL_INFORMATION: 'Closing day',
		TABLE_DETAIL: 'Detail',
		TABLE_DELETE: 'Delete',

		TEXT_CONFIRM_DELETE: 'Are you sure you want to delete this course?',
		TEXT_CONFIRM: 'Confirm',
		TEXT_CANCEL: 'Cancel',
	},
	// COURSE_CREATE: {
	// 	TITLE_COURSE_CREATE: 'Create Course',

	// 	FORM_BASIC_INFORMATION: 'Basic information',
	// 	SHUTTLE_DELIVERY: 'Shuttle delivery',
	// 	EASY_TO_DISTINGUISH: 'Easy to distinguish',
	// 	COURSE_ID: 'Course ID',
	// 	COURSE_NAME: 'Course Name',
	// 	EXCLUSIVE: ' ',
	// 	COURSE_TYPE: 'Course type',
	// 	START_TIME: 'Start time',
	// 	END_TIME: 'End time',
	// 	BREAK_TIME: 'Break time',
	// 	GROUP_CODE: 'Group code',
	// 	FATIGUE: 'Point',
	// 	START_DATE: 'Start date',
	// 	END_DATE: 'End date',
	// 	NOTE: 'Note',

	// 	CHECKBOX_SPOT_FLIGHT: 'Spot flight',
	// 	CHECKBOX_SHORT_FLIGHT: 'Short flight',
	// },

	CUSTOMER_CREATE: {
		TITLE_CUSTOMER_CREATE: 'Create Customer',
		POST_CODE: 'Post Code',
		FORM_BASIC_INFORMATION: 'Basic information',
		COURSE_ID: 'Customer ID',
		COURSE_NAME: 'Customer name',
		CLOSING_DAY: 'Closing day',
		CLIENT_MANAGER: 'Person_charge',
		ADDRESS_OF_CLIENT: 'Address of client',
		CLIENT_EMAIL: 'client e-mail',
		CLIENT_PHONE: 'Client Phone Number',
		NOTE: 'Note',
	},
	CUSTOMER_DETAIL: {
		TITLE_CUSTOMER_DETAIL: 'Detail Customer',
	},
	CUSTOMER_EDIT: {
		TITLE_CUSTOMER_EDIT: 'Edit Customer',
	},
	LIST_COURSE_PATTERN: {
		TITLE_LIST_COURSE_PATTERN: 'List Course Pattern',

		COURSE_ID: 'Course ID',
		COURSE_NAME: 'Course Name',
	},
	EDIT_COURSE_PATTERN: {
		TITLE_EDIT_COURSE_PATTERN: 'Edit Course Pattern',

		COURSE_ID: 'Course ID',
		COURSE_NAME: 'Course Name',
	},
	COURSE_PATTERN: {
		MODAL_CHANGE_COURSE_PATTERN: 'Change Course Pattern',
	},
	LIST_USER: {
		TITLE_LIST_USER: 'List User',

		USER_ID: 'User ID',
		USER_NAME: 'User Name',
		USER_AUTHORITY: 'User authority',
		DETAIL: 'Detail',
		DELETE: 'Delete',

		ROLE_DRIVER: 'Crew',
		ROLE_SYSTEM_ADMINISTRATOR: 'System Administrator',

		TEXT_CONFIRM_DELETE: 'Are you sure you want to delete this user?',
		TEXT_CONFIRM: 'Confirm',
		TEXT_CANCEL: 'Cancel',
	},
	LIST_CASH: {
		TITLE_LIST_CASH: 'Deposit information list',
		TABLE_CASH_ID: 'Shipper ID',
		TABLE_CASH_NAME: 'Shipper Name',
		TABLE_CASH_BALANCE_AT_END_OF_PREVIOUS_MONTH: 'balance at end of previous month',
		TABLE_CASH_ACCOUNTS_RECEIVABLE: 'Accounts receivable',
		TABLE_TOTAL_ACCOUNTS_RECEIVABLE: 'Total accounts receivable',
		TABLE_MONTHLY_DEPOSIT_AMOUNT: 'Monthly deposit amount',
		TABLE_CURRENT_MONTH_BALANCE: 'current month balance',
		TABLE_DETAIL: 'Detail',
		BUTTON_RETURN: 'Return',
		BUTTON_KEEP: 'Keep',

		TITLE_CASH_DETAIL: 'Deposit Information Details',
		FORM_BASIC_INFORMATION: 'Shipper information',
		DEPOSIT_INFORMATION: 'Deposit information',

		TABLE_NO: 'No',
		TABLE_DATE: 'Date',
		TABLE_DEPOSIT_AMOUNT: 'Deposit Amount',
		TABLE_PAYMENT_METHOD: 'Payment Method',
		TABLE_REMARKS: 'Remarks',
		TABLE_TOTAL: 'Total deposit amount for the current month',

		TITLE_CASH_CREATE: 'Deposit information Registration',

		PAYMENT_DAY: 'Payment Day',

		TITLE_LIST_CASH_DISBURSEMENT: 'Withdrawal information list',
		TABLE_CASH_DISBURSEMENT_ID: 'Subcontractor ID',
		TABLE_CASH_DISBURSEMENT_NAME: 'Cooperating company name',
		TABLE_CASH_DISBURSEMENT_BALANCE_AT_END_OF_PREVIOUS_MONTH: 'Balance at end of previous month',
		TABLE_CASH_DISBURSEMENT_ACCOUNTS_RECEIVABLE: 'Accounts payable for the current month',
		TABLE_CASH_DISBURSEMENT_TOTAL_ACCOUNTS_RECEIVABLE: 'Total payable',
		TABLE_CASH_DISBURSEMENT_MONTHLY_DEPOSIT_AMOUNT: 'Withdrawal amount for the current month',
		TABLE_CASH_DISBURSEMENT_CURRENT_MONTH_BALANCE: 'Current month balance',

		FORM_CASH_DISBURSEMENT_BASIC_INFORMATION: 'Cooperating company information',
		CASH_DISBURSEMENT_DEPOSIT_INFORMATION: 'Withdrawal information',
		TITLE_CASH_DISBURSEMENT_DETAIL: 'Withdrawal Information Details',
		TABLE_CASH_DISBURSEMENT_NO: 'No.',
		TABLE_CASH_DISBURSEMENT_DATE: 'Date',
		TABLE_CASH_DISBURSEMENT_DEPOSIT_AMOUNT: 'Withdrawal amount',
		TABLE_CASH_DISBURSEMENT_PAYMENT_METHOD: 'Withdrawal method',
		TABLE_CASH_DISBURSEMENT_REMARKS: 'Remarks',
		TABLE_CASH_DISBURSEMENT_TOTAL: 'Withdrawal amount for the current month',
		TABLE_EDIT: 'Edit',
		TABLE_DELETE: 'Delete',
		MESSAGE_DELETE: 'Are you sure you want to delete this data?',

		TITLE_CASH_DISBURSEMENT_CREATE: 'Withdrawal information registration',
		CASH_DISBURSEMENT_PAYMENT_DAY: 'Withdrawal date',
		NOTE_CASH_DISBURSEMENT: 'remarks',
	},
	CREATE_USER: {
		TITLE_CREATE_USER: 'Create User',

		USER_INFORMATION: 'User information',
		USER_ID: 'User ID',
		USERNAME: 'Username',
		USER_AUTHORITY: 'User authority',
		PASSWORD: 'Password',
	},
	DETAIL_USER: {
		TITLE_DETAIL_USER: 'Detail User',

		USER_INFORMATION: 'User information',
		USER_ID: 'User ID',
		USERNAME: 'Username',
		USER_AUTHORITY: 'User authority',
		PASSWORD: 'Password',
	},
	EDIT_USER: {
		TITLE_EDIT_USER: 'Edit User',

		USER_INFORMATION: 'User information',
		USER_ID: 'User ID',
		USERNAME: 'Username',
		USER_AUTHORITY: 'User authority',
		PASSWORD: 'Password',
	},

	MESSAGE_RESPONSE_AI: {
		TITLE_MODAL: 'Message response AI',
		// MESSAGE_NO_AI_TO_RUN: 'Please call Kiet san',
		MESSAGE_NO_AI_TO_RUN: 'Something went wrong, please contact admin',
		BUTTON_OK: 'OK',

		TEXT_STATUS_DEFAULT: '',
		TEXT_STATUS_ERROR: 'There is an error',
		TEXT_STATUS_SUCCESS: 'Succeeded',
		TEXT_STATUS_PROCESS: 'Process',
		TEXT_STATUS_CHECK: 'Check',
	},

	STATUS: {
		on: 'Process',
		error: 'Error',
		success: 'Success',
		check: 'Check',
	},
};
