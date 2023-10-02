// Auth API
const POST_LOGIN = '/auth/login';

// Calendar API
const GET_CALENDAR = '/calendar/index';

// User API
const GET_LIST_USER = '/user';
const GET_ONE_USER = '/user';
const POST_USER = '/user';
const PUT_USER = '/user';
const DELETE_USER = '/user';

// Driver API
const GET_LIST_DRIVER = '/driver';
const GET_LIST_DRIVER_VALIDATE = '/driver/driver-for-course';
const GET_ONE_DRIVER = '/driver';
const POST_DRIVER = '/driver';
const PUT_DRIVER = '/driver';
const DELETE_DRIVER = '/driver';

// Driver Course API
const GET_DRIVER_COURSE = '/driver-course/list';
const POST_DRIVER_COURSE = '/driver-course';

// Course API
const GET_LIST_COURSE = '/customer';
const POST_COURSE = '/customer';
const GET_COURSE = '/customer';
const PUT_COURSE = '/customer';
const DELETE_COURSE = '/customer';

// CoursePatern API
const GET_LIST_COURSE_PATERN = '/course-pattern';
const POST_COURSE_PATTERN = '/course-pattern/updates';

// Course Schedule API
const GET_COURSE_SHIFT = '/course/course-shift';
const GET_LIST_COURSE_SCHEDULE = '/course';
const DELETE_COURSE_SCHEDULE = '/course';
const POST_COURSE_SCHEDULE = '/course';
const GET_DETAIL_COURSE_SCHEDULE = '/course';
const DELETE_COURSE_SCHEDULE_MANY = '/course/delete-many';
const POST_LIST_COURSE_SCHEDULE = '/course-schedule/updates';
const POST_EXPORT_COURSE_SCHEDULE = '/course/export';
const PUT_COURSE_SCHEDULE = '/course';

// const POST_COURSE_SCHEDULE = '/course-pattern/updates';
const GET_EXPORT_COURSE_SCHEDULE = '/course-schedule/export-data';
const POST_IMPORT_COURSE_SCHEDULE = '/course/import';

// Day-off API
const GET_LIST_DAY_OFF = '/day-off';
const POST_DAY_OFF = '/day-off';

// Shift API
// /api/shift?date=2022-05&start_date=2022-04-30&end_date=2022-05-06&type=week
const GET_LIST_PRACTICAL = '/parctical-performance';
const GET_LIST_SHIFT_TABLE = '/driver-course';
const GET_SALE_LIST = '/driver-course/sales-list';
const GET_LIST_HIGHT_WAY = '/driver-course/get-all-express-charge';
const GET_LIST_PAYMENT = '/payment';
const GET_TOTAL_EXTRA_COST = '/driver-course/total-extra-cost';
const GET_EXPORT_SALE_LIST = '/driver-course/export-sales-list';
const GET_EXPORT_SHIFT_EXCEL_ONLY_WEEK = '/driver-course/export-shift';
const GET_EXPORT_SHIFT_PDF_ONLY_WEEK = '/shift/export-to-pdf';
const GET_EXPORT_HIGHT_WAY = '/driver-course/export-shift-express-charge';
const GET_EXPORT_SHIFT_FILE = '/shift/grade-tab-to-excel';
const GET_EXPORT_PAYMENT = '/payment/export';
const GET_EXPORT_EXPENSE = '/driver-course/export-driver-meal-shift';
const GET_EXPORT_SALE_LIST_PDF = '/driver-course/export-sale-detail-pdf';
const GET_EXPORT_PRACTICAL_PERFORMANCE_PDF = '/parctical-performance/export-to-pdf';
const POST_UPDATE_CELL_SHIFT = '/driver-course/update-course';
const GET_DATA_UPDATE = '/driver-course/detail-edit-shift';
const POST_UPDATE_COURSE_BASE = '/shift/edits';
const POST_CHECK_DATA_RESULT = '/shift/check-data-result';
const POST_ADD_LIST_SHIFT = '/shift';
const POST_CLOSING_DATE = '/final-closing';
const GET_DETAIL_SHIFT = '/driver-course';
const UPDATE_SHIFT = '/driver-course/update-course';
const GET_MESSAGE_RESPONSE_AI = '/shift/get-message-response-ai';
const POST_TEMPORORY = '/temporary-closing';
const GET_CHECK_BUTTON_TEMPORARY = '/temporary-closing/check-temporary';

// Cash Disbusement
const GET_LIST_CASH_DISBUSEMENT = '/driver-cash-out-statistical';
const GET_DETAIL_CASH_DISBURSEMENT = '/driver-cash-out-statistical';
const GET_LIST_CASH_OUT = '/driver';
const DELETE_CASH_OUT = '/driver';
const POST_CASH_OUT = '/driver';
const PUT_CASH_OUT = '/driver';
const GET_DETAIL_CASH_OUT = '/driver';
const EXPORT_EXCEL_CASH_OUT = '/driver-cash-out-statistical/export';

// Cash Reciept
const GET_LIST_CASH_RECIEPT = '/cash-in-statical';
const EXPORT_EXCEL_CASH_RECIEPT = '/cash-in-statical/export-cash-in-statical';
const GET_DETAIL_CASH_RECIEPT = '/cash-in-statical';
const GET_LIST_CASH_IN = '/cash-in';
const DELETE_CASH_IN = '/cash-in';
const GET_DETAIL_CASH_IN = '/cash-in';
const PUST_CASH_IN = '/cash-in';
const POST_CASH_IN = '/cash-in';

export default {
	// Auth API
	POST_LOGIN,

	// Calendar API
	GET_CALENDAR,

	// User API
	GET_LIST_USER,
	GET_ONE_USER,
	POST_USER,
	PUT_USER,
	DELETE_USER,

	// Driver API
	GET_LIST_DRIVER,
	GET_LIST_DRIVER_VALIDATE,
	GET_ONE_DRIVER,
	POST_DRIVER,
	PUT_DRIVER,
	DELETE_DRIVER,

	// Driver Course API
	GET_DRIVER_COURSE,
	POST_DRIVER_COURSE,

	// Course API
	GET_LIST_COURSE,
	POST_COURSE,
	GET_COURSE,
	PUT_COURSE,
	DELETE_COURSE,

	// CoursePatern API
	GET_LIST_COURSE_PATERN,
	POST_COURSE_PATTERN,

	// Course Schedule API
	GET_COURSE_SHIFT,
	GET_LIST_COURSE_SCHEDULE,
	POST_COURSE_SCHEDULE,
	DELETE_COURSE_SCHEDULE,
	POST_LIST_COURSE_SCHEDULE,
	GET_EXPORT_COURSE_SCHEDULE,
	GET_DETAIL_COURSE_SCHEDULE,
	DELETE_COURSE_SCHEDULE_MANY,
	POST_IMPORT_COURSE_SCHEDULE,
	POST_EXPORT_COURSE_SCHEDULE,
	PUT_COURSE_SCHEDULE,

	// Day-off API
	GET_LIST_DAY_OFF,
	POST_DAY_OFF,

	// Shift API
	GET_LIST_PRACTICAL,
	UPDATE_SHIFT,
	GET_LIST_SHIFT_TABLE,
	GET_LIST_HIGHT_WAY,
	GET_LIST_PAYMENT,
	GET_SALE_LIST,
	GET_EXPORT_SALE_LIST,
	GET_TOTAL_EXTRA_COST,
	GET_EXPORT_SHIFT_EXCEL_ONLY_WEEK,
	GET_EXPORT_SHIFT_PDF_ONLY_WEEK,
	GET_EXPORT_HIGHT_WAY,
	GET_EXPORT_SHIFT_FILE,
	GET_EXPORT_PRACTICAL_PERFORMANCE_PDF,
	GET_EXPORT_SALE_LIST_PDF,
	GET_EXPORT_PAYMENT,
	GET_EXPORT_EXPENSE,
	POST_UPDATE_CELL_SHIFT,
	GET_DATA_UPDATE,
	POST_UPDATE_COURSE_BASE,
	POST_CHECK_DATA_RESULT,
	POST_ADD_LIST_SHIFT,
	GET_DETAIL_SHIFT,
	GET_MESSAGE_RESPONSE_AI,
	POST_CLOSING_DATE,
	POST_TEMPORORY,
	GET_CHECK_BUTTON_TEMPORARY,

	// Cash Disbusement
	GET_LIST_CASH_DISBUSEMENT,
	GET_DETAIL_CASH_DISBURSEMENT,
	GET_LIST_CASH_OUT,
	DELETE_CASH_OUT,
	POST_CASH_OUT,
	PUT_CASH_OUT,
	GET_DETAIL_CASH_OUT,
	EXPORT_EXCEL_CASH_OUT,

	// Cash Reciept
	GET_LIST_CASH_RECIEPT,
	EXPORT_EXCEL_CASH_RECIEPT,
	GET_DETAIL_CASH_RECIEPT,
	GET_LIST_CASH_IN,
	DELETE_CASH_IN,
	GET_DETAIL_CASH_IN,
	PUST_CASH_IN,
	POST_CASH_IN,
};
