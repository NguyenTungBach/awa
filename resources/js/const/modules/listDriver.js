const ENROLLMENT_STATUS_RETIRED = 'on';
const ENROLLMENT_STATUS_ENROLLED = 'off';

const TEXT_ENROLLMENT_STATUS_RETIRED = 'LIST_DRIVER.ENROLLMENT_STATUS_RETIRED';
const TEXT_ENROLLMENT_STATUS_ENROLLED = 'LIST_DRIVER.ENROLLMENT_STATUS_ENROLLED';

const LIST_FLAG = [
	{
		value: 1,
		text: '管理職',
	},
	{
		value: 2,
		text: '正社員',
	},
	{
		value: 3,
		text: '契約社員',
	},
	{
		value: 4,
		text: '協力会社',
	},
];

const LIST_DATE = [
	{
		value: 1,
		text: '15日',
	},
	{
		value: 2,
		text: '20日',
	},
	{
		value: 3,
		text: '25日',
	},
	{
		value: 4,
		text: '末日',
	},
];

const LIST_WORKING_TIME = [
	{
		value: 'daily',
		text: 'CREATE_DRIVER.NO_MORE_THAN_15_HOURS_OF_WORK_PER_DAY',
	},
	{
		value: 'month',
		text: 'CREATE_DRIVER.NO_MORE_THAN_40_HOURS_OF_OVERTIME_PER_MONTH',
	},
];

export default {
	ENROLLMENT_STATUS_RETIRED,
	ENROLLMENT_STATUS_ENROLLED,
	TEXT_ENROLLMENT_STATUS_RETIRED,
	TEXT_ENROLLMENT_STATUS_ENROLLED,

	LIST_FLAG,
	LIST_DATE,
	LIST_WORKING_TIME,
};
