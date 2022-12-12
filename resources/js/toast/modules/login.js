import Notification from '../notification';
import i18n from '@/lang';

export default {
	success() {
		Notification.success(i18n.t('MESSAGE_APP.LOGIN_SUCCESS'));
	},
	validate(message) {
		Notification.warning(i18n.t(message));
	},
	exception() {
		Notification.warning(i18n.t('MESSAGE_APP.EXCEPTION'));
	},
	server(message) {
		Notification.error(message);
	},
};
