import CONSTANT from '@/const';

function hasRole(role, access = [CONSTANT.LIST_USER.ROLE_SYSTEM_ADMINISTRATOR]) {
	return access.includes(role);
}

export {
	hasRole
};
