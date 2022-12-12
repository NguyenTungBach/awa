import Cookies from 'js-cookie';
import CONSTANT from '@/const';
import { getLanguage } from '@/lang/helper/getLang';
import { convertMonth, convertYear, getFullText } from './convertTime';

function getValue(key) {
	if (key) {
		const VALUE = Cookies.get(key);

		if (VALUE) {
			return JSON.parse(VALUE);
		}
	}

	if (key === CONSTANT['COOKIES']['TOSHIN_PICKER_YEAR_MONTH']) {
		const lang = getLanguage();
		const d = new Date();

		const month = d.getMonth() + 1;
		const year = d.getFullYear();

		const getDate = new Date(year, month, 0);
		const date = getDate.getDate();

		const textMonth = convertMonth(month, lang);
		const textYear = convertYear(year, lang);
		const textFull = getFullText(month, year, lang);

		return {
			month,
			year,
			numberDate: date,
			textMonth,
			textYear,
			textFull,
		};
	}

	return null;
}

export {
	getValue
};
