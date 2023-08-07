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
		// number = BigInt(number);
		return String(number).replace(/(.)(?=(\d{3})+$)/g, '$1,');
	}

	return '0';
}

function formartPhoneNumber(number) {
	const digits = number.replace(/\D/g, '');
	if (digits.length === 11) {
		return `${digits.slice(0, 3)}-${digits.slice(3, 7)}-${digits.slice(7)}`;
	}
}

export {
	formatNumberCode,
	formatNumber,
	formartPhoneNumber
};
