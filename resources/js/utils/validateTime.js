function getTime(time) {
	if (!time) {
		return {
			hour: 0,
			minute: 0,
		};
	}

	const [hour, minute] = time.split(':');

	return {
		hour: parseInt(hour),
		minute: parseInt(minute),
	};
}

function validateStartEndBreakTime(startTime, endTime, breakTime) {
	const { hour: startHour, minute: startMinute } = getTime(startTime);
	const { hour: endHour, minute: endMinute } = getTime(endTime);
	const { hour: breakHour, minute: breakMinute } = getTime(breakTime);

	if (startHour > endHour) {
		return {
			status: false,
			message: 'MESSAGE_APP.COURSE_MANAGEMENT_VALIDATE_START_TIME_GREATER_THAN_END_TIME',
		};
	}

	if (startHour === endHour && startHour > endHour) {
		return {
			status: false,
			message: 'MESSAGE_APP.COURSE_MANAGEMENT_VALIDATE_START_TIME_GREATER_THAN_END_TIME',
		};
	}

	if (startHour === endHour && endMinute === startMinute) {
		return {
			status: false,
			message: 'MESSAGE_APP.COURSE_MANAGEMENT_VALIDATE_START_TIME_EQUALS_END_TIME',
		};
	}

	const totalMinStart = startHour * 60 + startMinute;
	const totalMinEnd = endHour * 60 + endMinute;
	const totalMinBreak = breakHour * 60 + breakMinute;

	if (totalMinBreak > (totalMinEnd - totalMinStart)) {
		return {
			status: false,
			message: 'MESSAGE_APP.COURSE_MANAGEMENT_VALIDATE_BREAK_TIME_GREATER_THAN_COURSE_TIME',
		};
	}

	return {
		status: true,
		message: null,
	};
}

function validateStartEndBreakTimeListShift(startTime, endTime, breakTime) {
	const { hour: startHour, minute: startMinute } = getTime(startTime);
	const { hour: endHour, minute: endMinute } = getTime(endTime);
	const { hour: breakHour, minute: breakMinute } = getTime(breakTime);

	if (startHour > endHour) {
		return {
			status: false,
			message: 'MESSAGE_APP.LIST_SHIFT_VALIDATE_TIME_START_END_COURSE',
		};
	}

	if (startHour === endHour && startHour > endHour) {
		return {
			status: false,
			message: 'MESSAGE_APP.LIST_SHIFT_VALIDATE_TIME_START_END_COURSE',
		};
	}

	if (startHour === endHour && endMinute === startMinute) {
		return {
			status: false,
			message: 'MESSAGE_APP.LIST_SHIFT_VALIDATE_TIME_START_END_COURSE',
		};
	}

	const totalMinStart = startHour * 60 + startMinute;
	const totalMinEnd = endHour * 60 + endMinute;
	const totalMinBreak = breakHour * 60 + breakMinute;

	if (totalMinBreak > (totalMinEnd - totalMinStart)) {
		return {
			status: false,
			message: 'MESSAGE_APP.LIST_SHIFT_VALIDATE_TIME_BREAK_COURSE',
		};
	}

	return {
		status: true,
		message: null,
	};
}

export {
	validateStartEndBreakTime,
	validateStartEndBreakTimeListShift
};
