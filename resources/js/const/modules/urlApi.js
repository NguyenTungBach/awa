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
const GET_ONE_DRIVER = '/driver';
const POST_DRIVER = '/driver';
const PUT_DRIVER = '/driver';
const DELETE_DRIVER = '/driver';

// Driver Course API
const GET_DRIVER_COURSE = '/driver-course/list';
const POST_DRIVER_COURSE = '/driver-course';

// Course API
const GET_LIST_COURSE = '/course';
const POST_COURSE = '/course';
const GET_COURSE = '/course';
const PUT_COURSE = '/course';
const DELETE_COURSE = '/course';

// CoursePatern API
const GET_LIST_COURSE_PATERN = '/course-pattern';
const POST_COURSE_PATTERN = '/course-pattern/updates';

// Course Schedule API
const GET_LIST_COURSE_SCHEDULE = '/course-schedule';
const POST_LIST_COURSE_SCHEDULE = '/course-schedule/updates';

// const POST_COURSE_SCHEDULE = '/course-pattern/updates';
const GET_EXPORT_COURSE_SCHEDULE = '/course-schedule/export-data';
const POST_IMPORT_COURSE_SCHEDULE = '/course-schedule/import';

// Day-off API
const GET_LIST_DAY_OFF = '/day-off';
const POST_DAY_OFF = '/day-off';

// Shift API
// /api/shift?date=2022-05&start_date=2022-04-30&end_date=2022-05-06&type=week
const GET_LIST_PRACTICAL = '/parctical-performance';
const GET_LIST_SHIFT_TABLE = '/shift';
const GET_EXPORT_SHIFT_EXCEL_ONLY_WEEK = '/shift/export-to-excel';
const GET_EXPORT_SHIFT_PDF_ONLY_WEEK = '/shift/export-to-pdf';
const GET_EXPORT_PRACTICAL_PERFORMANCE_EXCEL = '/parctical-performance/export-to-excel';
const GET_EXPORT_SHIFT_FILE = '/shift/grade-tab-to-excel';
const GET_EXPORT_PRACTICAL_PERFORMANCE_PDF = '/parctical-performance/export-to-pdf';
const POST_UPDATE_CELL_SHIFT = '/shift/detail-cell';
const POST_UPDATE_COURSE_BASE = '/shift/edits';
const POST_CHECK_DATA_RESULT = '/shift/check-data-result';
const POST_ADD_LIST_SHIFT = '/shift';
const GET_MESSAGE_RESPONSE_AI = '/shift/get-message-response-ai';

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
	GET_LIST_COURSE_SCHEDULE,
	POST_LIST_COURSE_SCHEDULE,
	GET_EXPORT_COURSE_SCHEDULE,
	POST_IMPORT_COURSE_SCHEDULE,

	// Day-off API
	GET_LIST_DAY_OFF,
	POST_DAY_OFF,

	// Shift API
	GET_LIST_PRACTICAL,
	GET_LIST_SHIFT_TABLE,
	GET_EXPORT_SHIFT_EXCEL_ONLY_WEEK,
	GET_EXPORT_SHIFT_PDF_ONLY_WEEK,
	GET_EXPORT_PRACTICAL_PERFORMANCE_EXCEL,
	GET_EXPORT_SHIFT_FILE,
	GET_EXPORT_PRACTICAL_PERFORMANCE_PDF,
	POST_UPDATE_CELL_SHIFT,
	POST_UPDATE_COURSE_BASE,
	POST_CHECK_DATA_RESULT,
	POST_ADD_LIST_SHIFT,
	GET_MESSAGE_RESPONSE_AI,
};
