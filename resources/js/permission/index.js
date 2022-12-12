import Vue from 'vue';
import i18n from '@/lang';
import store from '@/store';
import router, { resetRouter } from '@/router';
import { getToken } from '@/utils/handleToken';

const whiteList = ['/login'];

router.beforeEach(async(to, from, next) => {
	const TOKEN = getToken();

	if (TOKEN) {
		if (to.path === '/login') {
			const LIST_SHIFT_PAGE = '/shift-management/list-shift-edit';
			const LIST_DAY_OFF_PAGE = '/shift-management/list-day-off-edit';
			const LIST_SCHEDULE_PAGE = '/shift-management/list-schedule-edit';
			const USER_EDIT_PAGE = 'UserEdit';
			const DRIVER_COURSE_EDIT_PAGE = 'DriverCourseEdit';
			const DRIVER_EDIT_PAGE = 'ListDriverEdit';
			const COURSE_EDIT_PAGE = 'CourseEdit';
			const LIST_COURSE_PATTERN = 'EditListCoursePattern';

			const LIST_UPDATE_LIST_SHIFT = store.getters.listUpdateShift;
			const LIST_UPDATE_LIST_DAYOFF = store.getters.listUpdateDayoff;
			const LIST_UPDATE_LIST_SCHEDULE = store.getters.listUpdateSchedule;
			const EDIT_USER = store.getters.warningNotSaveUser;
			const EDIT_DRIVER = store.getters.warningNotSaveDriver;
			const EDIT_COURSE = store.getters.warningNotSaveCourse;
			const EDIT_LIST_COURSE_PATTERN = store.getters.warningNotSaveCoursePattern;

			if (
				(from.path === LIST_SHIFT_PAGE && LIST_UPDATE_LIST_SHIFT.length > 0) ||
                (from.path === LIST_DAY_OFF_PAGE && LIST_UPDATE_LIST_DAYOFF.length > 0) ||
                (from.path === LIST_SCHEDULE_PAGE && LIST_UPDATE_LIST_SCHEDULE.length > 0) ||
                (from.name === USER_EDIT_PAGE && EDIT_USER) ||
                (from.name === DRIVER_COURSE_EDIT_PAGE && EDIT_DRIVER) ||
                (from.name === DRIVER_EDIT_PAGE && EDIT_DRIVER) ||
                (from.name === COURSE_EDIT_PAGE && EDIT_COURSE) ||
                (from.name === LIST_COURSE_PATTERN && EDIT_LIST_COURSE_PATTERN)
			) {
				const vm = new Vue();

				vm.$bvModal.msgBoxConfirm(i18n.t('ROOT_APP_ACTION_LOAD.MESSAGE'),
					{
						title: i18n.t('ROOT_APP_ACTION_LOAD.TITLE'),
						centered: true,
						okTitle: i18n.t('ROOT_APP_ACTION_LOAD.CONTINUE'),
						cancelTitle: i18n.t('ROOT_APP_ACTION_LOAD.RETURN'),
					})
					.then((val) => {
						if (val) {
							if (from.path === LIST_SHIFT_PAGE) {
								store.dispatch('listShift/setListUpdate', []);
							}

							if (from.path === LIST_DAY_OFF_PAGE) {
								store.dispatch('listDayoff/setListUpdate', []);
							}

							if (from.path === LIST_SCHEDULE_PAGE) {
								store.dispatch('listSchedule/setListUpdate', []);
							}

							if (from.name === USER_EDIT_PAGE) {
								store.dispatch('user/setWarningNotSave', false);
							}

							if (from.name === DRIVER_COURSE_EDIT_PAGE) {
								store.dispatch('listDriver/setWarningNotSave', false);
							}

							if (from.name === DRIVER_EDIT_PAGE) {
								store.dispatch('listDriver/setWarningNotSave', false);
							}

							if (from.name === COURSE_EDIT_PAGE) {
								store.dispatch('course/setWarningNotSave', false);
							}

							if (from.name === LIST_COURSE_PATTERN) {
								store.dispatch('listCoursePattern/setWarningNotSave', false);
							}

							next();
						}
					});
			} else {
				next({ path: '/' });
			}
		} else {
			const LIST_SHIFT_PAGE = '/shift-management/list-shift-edit';
			const LIST_DAY_OFF_PAGE = '/shift-management/list-day-off-edit';
			const LIST_DAY_COURSE_BASE_PAGE = '/shift-management/list-course-base-edit';
			const LIST_SCHEDULE_PAGE = '/shift-management/list-schedule-edit';
			const USER_EDIT_PAGE = 'UserEdit';
			const DRIVER_COURSE_EDIT_PAGE = 'DriverCourseEdit';
			const DRIVER_EDIT_PAGE = 'ListDriverEdit';
			const COURSE_EDIT_PAGE = 'CourseEdit';
			const LIST_COURSE_PATTERN = 'EditListCoursePattern';

			const LIST_UPDATE_LIST_SHIFT = store.getters.listUpdateShift;
			const LIST_UPDATE_LIST_DAYOFF = store.getters.listUpdateDayoff;
			const LIST_UPDATE_COURSE_BASE = store.getters.listUpdateShiftCourseBase;
			const LIST_UPDATE_LIST_SCHEDULE = store.getters.listUpdateSchedule;
			const EDIT_USER = store.getters.warningNotSaveUser;
			const EDIT_DRIVER = store.getters.warningNotSaveDriver;
			const EDIT_COURSE = store.getters.warningNotSaveCourse;
			const EDIT_LIST_COURSE_PATTERN = store.getters.warningNotSaveCoursePattern;

			if (
				(from.path === LIST_SHIFT_PAGE && LIST_UPDATE_LIST_SHIFT.length > 0) ||
                (from.path === LIST_DAY_OFF_PAGE && LIST_UPDATE_LIST_DAYOFF.length > 0) ||
				(from.path === LIST_DAY_COURSE_BASE_PAGE && LIST_UPDATE_COURSE_BASE.length > 0) ||
                (from.path === LIST_SCHEDULE_PAGE && LIST_UPDATE_LIST_SCHEDULE.length > 0) ||
                (from.name === USER_EDIT_PAGE && EDIT_USER) ||
                (from.name === DRIVER_COURSE_EDIT_PAGE && EDIT_DRIVER) ||
                (from.name === DRIVER_EDIT_PAGE && EDIT_DRIVER) ||
                (from.name === COURSE_EDIT_PAGE && EDIT_COURSE) ||
                (from.name === LIST_COURSE_PATTERN && EDIT_LIST_COURSE_PATTERN)
			) {
				const vm = new Vue();

				vm.$bvModal.msgBoxConfirm(i18n.t('ROOT_APP_ACTION_LOAD.MESSAGE'),
					{
						title: i18n.t('ROOT_APP_ACTION_LOAD.TITLE'),
						centered: true,
						okTitle: i18n.t('ROOT_APP_ACTION_LOAD.CONTINUE'),
						cancelTitle: i18n.t('ROOT_APP_ACTION_LOAD.RETURN'),
					})
					.then((val) => {
						if (val) {
							if (from.path === LIST_SHIFT_PAGE) {
								store.dispatch('listShift/setListUpdate', []);
							}

							if (from.path === LIST_DAY_OFF_PAGE) {
								store.dispatch('listDayoff/setListUpdate', []);
							}

							if (from.path === LIST_DAY_COURSE_BASE_PAGE) {
								store.dispatch('listShift/setListUpdate', []);
							}

							if (from.path === LIST_SCHEDULE_PAGE) {
								store.dispatch('listSchedule/setListUpdate', []);
							}

							if (from.name === USER_EDIT_PAGE) {
								store.dispatch('user/setWarningNotSave', false);
							}

							if (from.name === DRIVER_COURSE_EDIT_PAGE) {
								store.dispatch('listDriver/setWarningNotSave', false);
							}

							if (from.name === DRIVER_EDIT_PAGE) {
								store.dispatch('listDriver/setWarningNotSave', false);
							}

							if (from.name === COURSE_EDIT_PAGE) {
								store.dispatch('course/setWarningNotSave', false);
							}

							if (from.name === LIST_COURSE_PATTERN) {
								store.dispatch('listCoursePattern/setWarningNotSave', false);
							}

							next();
						}
					});
			} else {
				next();
			}
		}
	} else {
		resetRouter();

		if (whiteList.indexOf(to.matched[0] ? to.matched[0].path : '') !== -1) {
			next();
		} else {
			next(`/login`);
		}
	}
});
