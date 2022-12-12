import { validateFormatYYYYMMDD } from './validate';

export function convertMonth(month, lang = 'ja') {
	const DICTIONARY = {
		en: {
			1: 'January',
			2: 'February',
			3: 'March',
			4: 'April',
			5: 'May',
			6: 'June',
			7: 'July',
			8: 'August',
			9: 'September',
			10: 'October',
			11: 'November',
			12: 'December',
		},
		ja: {
			1: '1月',
			2: '2月',
			3: '3月',
			4: '4月',
			5: '5月',
			6: '6月',
			7: '7月',
			8: '8月',
			9: '9月',
			10: '10月',
			11: '11月',
			12: '12月',
		},
	};

	if (month >= 1 && month <= 12) {
		if (Object.keys(DICTIONARY).includes(lang)) {
			return DICTIONARY[lang][month];
		}
	}

	return '';
}

export function convertYear(year, lang = 'ja') {
	if (year) {
		if (['en', 'ja'].includes(lang)) {
			switch (lang) {
				case 'en':
					return `${year}`;

				case 'ja':
					return `${year}年`;
			}
		}
	}

	return '';
}

export function getFullText(month, year, lang = 'ja') {
	if (month && year) {
		switch (lang) {
			case 'en':
				return `${convertMonth(month, lang)} | ${convertYear(year, lang)}`;

			case 'ja':
				return `${convertYear(year, lang)} | ${convertMonth(month, lang)}`;
		}
	}

	return '';
}

export function getTextDay(date, lang = 'ja') {
	const DICTIONARY = {
		en: {
			1: 'Monday',
			2: 'Tuesday',
			3: 'Wednesday',
			4: 'Thursday',
			5: 'Friday',
			6: 'Saturday',
			0: 'Sunday',
		},
		ja: {
			1: '月',
			2: '火',
			3: '水',
			4: '木',
			5: '金',
			6: '土',
			0: '日',
		},
	};

	const d = new Date(date);
	const day = d.getDay();

	return DICTIONARY[lang][day];
}

export function getTextCodeDay(day, lang = 'ja') {
	const DICTIONARY = {
		en: {
			1: 'Monday',
			2: 'Tuesday',
			3: 'Wednesday',
			4: 'Thursday',
			5: 'Friday',
			6: 'Saturday',
			7: 'Sunday',
		},
		ja: {
			1: '月',
			2: '火',
			3: '水',
			4: '木',
			5: '金',
			6: '土',
			7: '日',
		},
	};

	return DICTIONARY[lang][day];
}

export function getTextShortDay(code, lang = 'ja') {
	const DICTIONARY = {
		en: {
			1: 'Mon',
			2: 'Tue',
			3: 'Wed',
			4: 'Thu',
			5: 'Fri',
			6: 'Sat',
			0: 'Sun',
		},
		ja: {
			1: '月',
			2: '火',
			3: '水',
			4: '木',
			5: '金',
			6: '土',
			0: '日',
		},
	};

	return DICTIONARY[lang][code];
}

export function getNumberDate(date) {
	const d = new Date(date.year, date.month, 0);

	return d.getDate();
}

export function getTextDayInWeek(date) {
	const d = new Date(date.year, date.month, date.day);

	return d.getDay();
}

export function getTextShortDayByCodeDay(code) {
	if (!code) {
		return null;
	}

	const LIBRAY = {
		1: 'mon',
		2: 'tue',
		3: 'wed',
		4: 'thu',
		5: 'fri',
		6: 'sat',
		7: 'sun',
	};

	return LIBRAY[code];
}

export function getTextDayByCodeDay(code) {
	if (!code) {
		return null;
	}

	const LIBRAY = {
		1: 'Monday',
		2: 'Tuesday',
		3: 'Wednesday',
		4: 'Thursday',
		5: 'Friday',
		6: 'Saturday',
		7: 'Sunday',
	};

	return LIBRAY[code];
}

export function convertTimeCourse(time) {
	if (!time) {
		return '0.00';
	}

	const timeSplit = time.split(':');

	if (Array.isArray(timeSplit)) {
		if (timeSplit) {
			const hour = parseInt(timeSplit[0]);
			const minute = parseInt(timeSplit[1]);
			if (minute === 0) {
				return hour + '.00';
			} else {
				const totalMinute = (hour * 60) + minute;
				const dataNumber = totalMinute / 60;
				return dataNumber.toFixed(2);
			}
		}
	}

	return '0.00';
}

export function convertBreakTimeNumberToTime(number) {
	if (!number) {
		return '00:00';
	}

	number = parseFloat(number);
	number = number.toFixed(2);

	const numberSplit = number.split('.');

	if (Array.isArray(numberSplit)) {
		if (numberSplit.length === 2) {
			let hour = parseInt(numberSplit[0]);
			hour = hour > 10 ? `${hour}` : `0${hour}`;

			const minute = numberSplit[1];
			let min = '00';

			switch (minute) {
				case '00': {
					min = '00';

					break;
				}

				case '25': {
					min = '15';

					break;
				}

				case '50': {
					min = '30';

					break;
				}

				case '75': {
					min = '45';

					break;
				}
			}

			return `${hour}:${min}`;
		}
	}

	return '00:00';
}

export function YmdtoStringYmd(ymd) {
	if (validateFormatYYYYMMDD(ymd)) {
		const splitYmd = ymd.split('-');

		if (Array.isArray(splitYmd) && splitYmd.length === 3) {
			const year = splitYmd[0];
			const month = splitYmd[1];
			const day = splitYmd[2];

			return `${year}年${month}月${day}日`;
		}
	}

	return '';
}

export function getTextLoop(array, handle, char = ' ', lang = 'ja') {
	const _array = array.map((item) => handle(item, lang));

	return _array.join(char);
}

export function convertTimeToSelect(time) {
	if (!time) {
		return [null, null];
	}

	const timeSplit = time.split(':');

	if (Array.isArray(timeSplit)) {
		if (timeSplit.length === 2) {
			return [timeSplit[0], timeSplit[1]];
		}
	}

	return [null, null];
}

export function convertStingToSelect(string) {
	if (!string) {
		return [null, null];
	}

	const timeSplit = string.split('');

	if (Array.isArray(timeSplit)) {
		if (timeSplit.length === 2) {
			return [timeSplit[0], timeSplit[1]];
		}
	}

	return [null, null];
}

export function convertTextToSelectTime(string) {
	if (!string) {
		return [null, null];
	}

	const timeSplit = string.split(':');

	if (Array.isArray(timeSplit)) {
		if (timeSplit.length === 2) {
			const _hour = parseInt(timeSplit[0]);
			const _minute = parseInt(timeSplit[1]);

			return [_hour < 10 ? `0${_hour}` : `${_hour}`, _minute < 10 ? `0${_minute}` : `${_minute}`];
		}
	}

	return [null, null];
}

export function getYearMonthFromDate(date) {
	const d = new Date(date);

	const year = d.getFullYear();
	const month = d.getMonth() + 1;

	return `${year}-${month < 10 ? `0${month}` : `${month}`}`;
}

export function formatArray2Time(array) {
	if (Array.isArray(array)) {
		if (array.length === 2) {
			const hour = array[0];
			const minute = array[1];

			return `${hour}:${minute}`;
		}
	}

	return null;
}

export function getYMD(textDate) {
	const d = new Date(textDate);

	const year = d.getFullYear();
	const month = d.getMonth() + 1;
	const date = d.getDate();
	const day = d.getDay();

	return {
		year,
		month,
		date,
		day,
	};
}

export function getTextYMDOfHeader(headerPicker) {
	if (headerPicker) {
		return `${headerPicker.year}/${headerPicker.month}/${headerPicker.numberDate}`;
	}

	return null;
}

export function getStartDateOfYMDHeader(headerPicker) {
	if (headerPicker) {
		return `${headerPicker.year}/${headerPicker.month}/1`;
	}

	return null;
}
