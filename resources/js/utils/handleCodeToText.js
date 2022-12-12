import CONSTANT from '@/const';
import i18n from '@/lang';

function getRoleName(role = '') {
	if (!role) {
		return '';
	}

	switch (role) {
		case CONSTANT.LIST_USER.ROLE_DRIVER:
			return i18n.t(CONSTANT.LIST_USER.TEXT_ROLE_DRIVER);

		case CONSTANT.LIST_USER.ROLE_SYSTEM_ADMINISTRATOR:
			return i18n.t(CONSTANT.LIST_USER.TEXT_ROLE_SYSTEM_ADMINISTRATOR);

		default:
			return role;
	}
}

export {
	getRoleName
};
