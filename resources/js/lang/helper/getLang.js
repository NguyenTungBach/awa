import Cookies from 'js-cookie';
import CONSTANT from '@/const';

/**
 * Function get Current Language in Cookies
 * @returns Current Language (String)
 */
export function getLanguage() {
	const chooseLanguage = Cookies.get(CONSTANT['COOKIES']['LANGUAGE']);

	if (chooseLanguage) {
		return chooseLanguage;
	}

	const LANG_DEFAULT = process.env.MIX_LARAVEL_LANG || 'ja';

	Cookies.set(CONSTANT['COOKIES']['LANGUAGE'], LANG_DEFAULT);

	return LANG_DEFAULT;
}
