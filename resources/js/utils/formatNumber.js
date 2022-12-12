function formatNumberCode(string, maxlength = 4) {
	const number = parseInt(string);

	const re = /^([0-9]\d*)$/;

	if (re.test(number)) {
		return String(number).padStart(maxlength, '0');
	}

	return number;
}

function formatNumber(number) {
	if (number) {
		number = BigInt(number);
		return String(number).replace(/(.)(?=(\d{3})+$)/g, '$1,');
	}

	return '0';
}

export {
	formatNumberCode,
	formatNumber
};
