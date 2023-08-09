import Notification from '../notification';
import i18n from '@/lang';

export default {
	success() {
		Notification.success(i18n.t('MESSAGE_APP.CASH_OUT_CREATE_SUCCESS'));
	},
	successCashIn() {
		Notification.success(i18n.t('MESSAGE_APP.CASH_IN_CREATE_SUCCESS'));
	},
	update() {
		Notification.success(i18n.t('MESSAGE_APP.CASH_OUT_UPDATE_SCUCCESS'));
	},
	updateCashIn() {
		Notification.success(i18n.t('MESSAGE_APP.CASH_IN_UPDATE_SCUCCESS'));
	},
	delete() {
		Notification.success(i18n.t('MESSAGE_APP.CASH_OUT_DELETE_SUCCESS'));
	},
	deleteCashIn() {
		Notification.success(i18n.t('MESSAGE_APP.CASH_IN_DELETE_SUCCESS'));
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
