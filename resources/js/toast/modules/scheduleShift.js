import Notification from '../notification';
import i18n from '@/lang';

export default {
	importSuccess() {
		Notification.success(i18n.t('MESSAGE_APP.SCHEDULE_IMPORT_SUCCESS'));
	},
	validate(message) {
		Notification.warning(i18n.t(message));
	},
	update() {
		Notification.success(i18n.t('MESSAGE_APP.COURSE_SCHEDULE_UPDATE_SUCCESS'));
	},
	delete() {
		Notification.success(i18n.t('MESSAGE_APP.COURSE_MANAGEMENT_DELETE_SUCCESS'));
	},
	warning(message) {
		Notification.warning(i18n.t(message));
	},
	exception() {
		Notification.warning(i18n.t('MESSAGE_APP.EXCEPTION'));
	},
	server(message) {
		Notification.error(message);
	},
	closingDate(message) {
		Notification.success(message);
	},
};
