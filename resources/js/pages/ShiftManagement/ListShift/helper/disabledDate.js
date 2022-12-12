function handleDisabledDate(startDate, endDate, date) {
	const _starDate = new Date(startDate);
	const _endDate = new Date(endDate);
	const _date = new Date(date);

	if (startDate && endDate) {
		return _date.getTime() > (_starDate.getTime() - (24 * 60 * 60 * 1000)) && _date.getTime() < (_endDate.getTime() + (24 * 60 * 60 * 1000));
	}

	if (startDate && !endDate) {
		return _date.getTime() > (_starDate.getTime() - (24 * 60 * 60 * 1000));
	}

	if (!startDate && endDate) {
		return _date.getTime() < (_endDate.getTime() + 24 * 60 * 60 * 1000);
	}

	return false;
}

export {
	handleDisabledDate
};
