import CONSTANT from '@/const';

function validEmail(email) {
	const re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;

	return re.test(email);
}

function validString(string) {
	const re = /^[\S ]*$/;

	return re.test(string);
}

function validateUserID(userID) {
	if (userID <= 0) {
		return false;
	}

	const re = /^\d{1,4}$/;

	return re.test(userID);
}

function validateCrewCode(crew_code) {
	if (crew_code <= 0) {
		return false;
	}

	const re = /^\d{1,15}$/;

	return re.test(crew_code);
}

function validateGrade(grade) {
	if (grade <= 0) {
		return false;
	}

	const re = /^\d{1,10}$/;

	return re.test(grade);
}

function validateCourseCode(courseCode) {
	if (!courseCode) {
		return false;
	}

	const re = /^\S{1,15}$/;

	return re.test(courseCode);
}

function validUsername(username) {
	if (!username) {
		return false;
	}

	const re = /^[\S ]*$/;

	return re.test(username);
}

function validPassword(password) {
	if (!password) {
		return false;
	}

	const re = /^\S{8,16}$/;

	return re.test(password);
}

function validateRole(role) {
	const LIST_ROLE = [
		CONSTANT.LIST_USER.ROLE_DRIVER,
		CONSTANT.LIST_USER.ROLE_SYSTEM_ADMINISTRATOR,
	];

	return LIST_ROLE.includes(role);
}

function validEmptyOrWhiteSpace(string) {
	const re = /^\s*$/;

	return re.test(string);
}

function validateFormatYYYYMMDD(date) {
	const re = /^\d{4}-\d{2}-\d{2}$/;

	return re.test(date);
}

function validateSizeFile(size, maxFileSize) {
	let size2Mb = size / 1024 / 1024;
	size2Mb.toFixed(2);

	size2Mb = parseFloat(size2Mb);

	return size2Mb <= maxFileSize;
}

function validateFileCSV(type) {
	const LIST_TYPE = [
		'application/excel',
		'application/vnd.ms-excel',
		'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
		'.xlsx',
		'.xls',
	];

	return LIST_TYPE.includes(type);
}

export {
	validEmail,
	validString,
	validateRole,
	validUsername,
	validPassword,
	validateUserID,
	validateCrewCode,
	validateCourseCode,
	validateFormatYYYYMMDD,
	validEmptyOrWhiteSpace,
	validateSizeFile,
	validateFileCSV,
	validateGrade
};
