const WEEK = 'WEEK';
const MONTH = 'MONTH';
const SHIFT_TABLE = 'SHIFT_TABLE';
const COURSE_BASE_TABLE = 'COURSE_BASE_TABLE';
const PRACTICAL_ACHIEVEMENTS_MONTHLY = 'PRACTICAL_ACHIEVEMENTS_MONTHLY';
const PRACTICAL_PERFORMANCE_BY_CLOSING_DATE = 'PRACTICAL_PERFORMANCE_BY_CLOSING_DATE';
const SALES_AMOUNT_TABLE = 'SALES AMOUNT TABLE';
const HIGHT_WAY_FEE = 'HIGHT AWAY TABLE';
const PAYMENT_TABLE = 'PAYMENT TABLE';

const DATE_HOLIDAY = 4;
const DATE_FIXED_DAY_OFF = 3;
const DATE_DAY_OFF_REQUEST = 5;
const DATE_PAID = 6;
const DATE_WORKING_DAY = 8;
const DATE_LEADER_CHIEF = 2;
const DATE_WAIT = 1;
const DATE_WAIT_BETWEEN_TASK = 9;
const DATE_HAFT_DAY_OFF = 7;

const COLOR_HOLIDAY = '#EAE7AC';
const COLOR_FIXED_DAY_OFF = '#FFE5CD';
const COLOR_DAY_OFF_REQUEST = '#EDD1A7';
const COLOR_PAID = '#EEAAAA';
const COLOR_WORKING_DAY = '#FFFFFF';
const COLOR_HAFT_DAY_OFF = '#FFF1E4';

const TEXT_DATE_HOLIDAY = 'LIST_SHIFT.TABLE_DATE_HOLIDAY';
const TEXT_DATE_FIXED_DAY_OFF = 'LIST_SHIFT.TABLE_DATE_FIXED_DAY_OFF';
const TEXT_DATE_DAY_OFF_REQUEST = 'LIST_SHIFT.TABLE_DATE_DAY_OFF_REQUEST';
const TEXT_DATE_PAID = 'LIST_SHIFT.TABLE_DATE_PAID';
const TEXT_DATE_LEADER_CHIEF = 'LIST_SHIFT.TABLE_DATE_LEADER_CHIEF';
const TEXT_DATE_WAIT = 'LIST_SHIFT.TABLE_DATE_WAIT';
const TEXT_DATE_WAIT_BETWEEN_TASK = 'LIST_SHIFT.TABLE_DATE_WAIT_BETWEEN_TASK';
const TEXT_FREE = 'LIST_SHIFT.FREE';
// const TEXT_INTERNAL_BUSINESS = 'LIST_SHIFT.INTERNAL_BUSINESS';
// const WAIT = 'LIST_SHIFT.WAIT';

const TEXT_HALF_DAY_OF = 'LIST_SHIFT.HALF_DAY_OF';

const MAP_TYPE_COLOR_DAY_OFF = {
	[DATE_HOLIDAY]: COLOR_HOLIDAY,
	[DATE_FIXED_DAY_OFF]: COLOR_FIXED_DAY_OFF,
	[DATE_DAY_OFF_REQUEST]: COLOR_DAY_OFF_REQUEST,
	[DATE_PAID]: COLOR_PAID,
	[DATE_WORKING_DAY]: COLOR_WORKING_DAY,
	[DATE_LEADER_CHIEF]: COLOR_WORKING_DAY,
	[DATE_WAIT]: COLOR_WORKING_DAY,
	[DATE_WAIT_BETWEEN_TASK]: COLOR_WORKING_DAY,
	[DATE_HAFT_DAY_OFF]: COLOR_HAFT_DAY_OFF,
};

const MAP_TYPE_TEXT_DAY_OFF = {
	[DATE_HOLIDAY]: TEXT_DATE_HOLIDAY,
	[DATE_FIXED_DAY_OFF]: TEXT_FREE,
	[DATE_DAY_OFF_REQUEST]: TEXT_DATE_DAY_OFF_REQUEST,
	[DATE_PAID]: TEXT_DATE_PAID,
	[DATE_LEADER_CHIEF]: TEXT_DATE_LEADER_CHIEF,
	[DATE_WAIT]: TEXT_DATE_WAIT,
	[DATE_WAIT_BETWEEN_TASK]: TEXT_DATE_WAIT_BETWEEN_TASK,
};

const WAINT_AI_CREATE_SHIFT_TABLE = '';

const LIST_DAY_OFF = [
	{
		value: 1,
		text: TEXT_DATE_WAIT,
		disabled: false,
	},
	{
		value: 2,
		text: TEXT_DATE_LEADER_CHIEF,
		disabled: false,
	},
	{
		value: 3,
		text: TEXT_FREE,
		disabled: false,
	},
	// {
	// 	value: 'D-2',
	// 	text: TEXT_DATE_FIXED_DAY_OFF,
	// 	disabled: false,
	// },
	{
		value: 4,
		text: TEXT_DATE_HOLIDAY,
		disabled: false,
	},
	{
		value: 5,
		text: TEXT_DATE_DAY_OFF_REQUEST,
		disabled: false,
	},
	{
		value: 6,
		text: TEXT_DATE_PAID,
		disabled: false,
	},
	// {
	// 	value: 'R',
	// 	text: 'LIST_SHIFT.SELECT_LEADER_CHIEF',
	// 	disabled: false,
	// },
	// {
	// 	value: 'S-1',
	// 	text: 'LIST_SHIFT.SELECT_WAIT',
	// 	disabled: false,
	// },
	// {
	// 	value: 'S-2',
	// 	text: 'LIST_SHIFT.SELECT_WAIT_BETWEEN_TASK',
	// 	disabled: false,
	// },
];

// const LIST_VALUE_DAY_OFF = ['D-1', 'D-2', 'D-3', 'D-4', 'S-1'];
const LIST_VALUE_DAY_OFF = [4, 5, 6, 7];

const LIST_VALUE_SPECIAL_DAY = [1, 2, 3];

const HALF_DAY_OF = [
	{
		value: 7,
		text: TEXT_HALF_DAY_OF,
		disabled: false,
	},
];
const LIST_VALUE_HALF_DAY_OFF = [7];

export default {
	WEEK,
	MONTH,
	SHIFT_TABLE,
	COURSE_BASE_TABLE,
	PRACTICAL_ACHIEVEMENTS_MONTHLY,
	PRACTICAL_PERFORMANCE_BY_CLOSING_DATE,
	SALES_AMOUNT_TABLE,
	HIGHT_WAY_FEE,
	PAYMENT_TABLE,

	DATE_WORKING_DAY,
	DATE_HOLIDAY,
	DATE_FIXED_DAY_OFF,
	DATE_DAY_OFF_REQUEST,
	DATE_PAID,
	DATE_LEADER_CHIEF,
	DATE_WAIT,
	DATE_WAIT_BETWEEN_TASK,

	COLOR_WORKING_DAY,
	COLOR_HOLIDAY,
	COLOR_FIXED_DAY_OFF,
	COLOR_DAY_OFF_REQUEST,
	COLOR_PAID,

	MAP_TYPE_COLOR_DAY_OFF,
	MAP_TYPE_TEXT_DAY_OFF,

	TEXT_DATE_HOLIDAY,
	TEXT_DATE_FIXED_DAY_OFF,
	TEXT_DATE_DAY_OFF_REQUEST,
	TEXT_DATE_PAID,
	TEXT_DATE_LEADER_CHIEF,
	TEXT_DATE_WAIT,
	TEXT_DATE_WAIT_BETWEEN_TASK,
	TEXT_HALF_DAY_OF,

	WAINT_AI_CREATE_SHIFT_TABLE,

	HALF_DAY_OF,

	LIST_DAY_OFF,
	LIST_VALUE_DAY_OFF,
	LIST_VALUE_HALF_DAY_OFF,
	LIST_VALUE_SPECIAL_DAY,
};
