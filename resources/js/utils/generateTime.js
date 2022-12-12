import i18n from '@/lang';

export function format2Digit(val) {
	return val < 10 ? `0${val}` : `${val}`;
}

export function generateTimeSelect(step = 5) {
	const list = [
		{
			value: null,
			text: i18n.t('APP.PLEASE_SELECT'),
		},
	];

	const len = 24;
	let idx = 0;

	while (idx < len) {
		for (let min = 0; min < 60; min += step) {
			list.push({
				value: `${format2Digit(idx)}:${format2Digit(min)}`,
				text: `${format2Digit(idx)}:${format2Digit(min)}`,
			});
		}

		idx++;
	}

	return list;
}
