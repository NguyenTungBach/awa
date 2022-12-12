const loopCallback = (array, callback) => {
	if (!array || !callback) {
		return null;
	}

	return array.map(item => callback(item));
};

const splitStr2Array = (string, separator) => {
	if (!string) {
		return [];
	}

	return string.split(separator);
};

const getValueShortDayByText = (text) => {
	if (!text) {
		return null;
	}

	const LIBRAY = {
		mon: 1,
		tue: 2,
		wed: 3,
		thu: 4,
		fri: 5,
		sat: 6,
		sun: 7,
	};

	return LIBRAY[text];
};

export {
	loopCallback,
	splitStr2Array,
	getValueShortDayByText
};
