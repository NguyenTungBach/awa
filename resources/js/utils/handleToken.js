import CONSTANT from '@/const';
import Cookies from 'js-cookie';

export function getToken() {
	const TOKEN = Cookies.get(CONSTANT.COOKIES.TOSHIN_TOKEN);

	if (TOKEN) {
		return TOKEN;
	}

	return '';
}

export function getProfile() {
	const PROFILE = Cookies.get(CONSTANT.COOKIES.TOSHIN_PROFILE);

	if (PROFILE) {
		return JSON.parse(PROFILE);
	}

	return {};
}
