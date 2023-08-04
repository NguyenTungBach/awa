const getters = {
	loading: state => state.loading.overlay,
	paddingShift: state => state.loading.paddingShift,

	language: state => state.app.language,
	pickerYearMonth: state => state.app.pickerYearMonth,
	pickerWeek: state => state.app.pickerWeek,
	permissionRoutes: state => state.permissions.routes,
	addRoutes: state => state.permissions.addRoutes,
	token: state => state.login.token,
	profile: state => state.login.profile,

	tableListShift: state => state.listShift.isTable,
	weekOrMonthListShift: state => state.listShift.isWeekOrMonth,
	reloadTableListShift: state => state.listShift.reLoadTable,

	tabIndexDriver: state => state.listDriver.tabIndex,

	listUpdateShift: state => state.listShift.listUpdate,
	listUpdateDayoff: state => state.listDayoff.listUpdate,
	listUpdateSchedule: state => state.listSchedule.listUpdate,
	listUpdateShiftCourseBase: state => state.listShift.listUpdate,
	listCoursePattern: state => state.listCoursePattern.list,

	showModalResponseAI: state => state.handleAI.showModalMessage,
	typeMessage: state => state.handleAI.typeMessage,
	textMessge: state => state.handleAI.textMessage,

	clickBackCalendarMonth: state => state.calendar.clickBack,
	clickNextCalendarMonth: state => state.calendar.clickNext,
	indexCalendarWeekListShift: state => state.calendar.indexCalendarWeekListShift,

	warningNotSaveUser: state => state.user.warningNotSave,
	warningNotSaveDriver: state => state.listDriver.warningNotSave,
	warningNotSaveCourse: state => state.course.warningNotSave,
	warningNotSaveCoursePattern: state => state.listCoursePattern.warningNotSave,
	idRouter: state => state.listCash.idRouter,
};

export default getters;
