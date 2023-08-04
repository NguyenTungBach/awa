import Notification from '../notification';
import i18n from '@/lang';

export default {
	success() {
		Notification.success(i18n.t('MESSAGE_APP.COURSE_MANAGEMENT_CREATE_SUCCESS'));
	},
	update() {
		Notification.success(i18n.t('MESSAGE_APP.COURSE_MANAGEMENT_UPDATE_SUCCESS'));
	},
	delete() {
		Notification.success(i18n.t('MESSAGE_APP.CASH_OUT_DELETE_SUCCESS'));
	},
	validate(message) {
		Notification.warning(i18n.t(message));
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
};
