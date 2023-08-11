import {
	validateUserID,
	validUsername,
	validPassword,
	validateRole,
	validString,
	validateCourseCode,
	validateFormatYYYYMMDD,
	validateCrewCode,
	// validateGrade,
} from '@/utils/validate';
import {
	validateStartEndBreakTime,
} from '@/utils/validateTime';

function validateUser(user, list = ['user_code', 'user_name', 'password', 'role']) {
	if (
		(!user.user_code && list.includes('user_code')) ||
        (!user.user_name && list.includes('user_name')) ||
        (!user.password && list.includes('password')) ||
        (!user.role && list.includes('role'))
	) {
		return {
			status: false,
			message: 'MESSAGE_APP.USER_MANAGEMENT_VALIDATE_REQUIRED',
		};
	}

	if (!validateUserID(user.user_code) && list.includes('user_code')) {
		return {
			status: false,
			message: 'MESSAGE_APP.USER_MANAGEMENT_VALIDATE_USER_CODE',
		};
	}

	if (!validUsername(user.user_name) && !(user.user_name.length >= 1 && user.user_name.length <= 20) && list.includes('user_name')) {
		return {
			status: false,
			message: 'MESSAGE_APP.USER_MANAGEMENT_VALIDATE_USER_NAME',
		};
	}

	if (!validPassword(user.password) && list.includes('password')) {
		return {
			status: false,
			message: 'MESSAGE_APP.USER_MANAGEMENT_VALIDATE_USER_PASSWORD',
		};
	}

	if (!validateRole(user.role) && list.includes('role')) {
		return {
			status: false,
			message: 'MESSAGE_APP.USER_MANAGEMENT_VALIDATE_USER_ROLE',
		};
	}

	return {
		status: true,
		message: null,
	};
}

function validateDriver(driver, list = ['type', 'driver_code', 'driver_name', 'start_date', 'end_date', 'birth_day', 'car', 'working_day', 'day_of_week', 'working_time', 'note']) {
// function validateDriver(driver, list = ['flag', 'driver_code', 'driver_name', 'start_date', 'end_date', 'birth_day', 'grade', 'working_day', 'working_time', 'note']) {
	if (
		!driver.driver_code && list.includes('driver_code') ||
        !driver.driver_name && list.includes('driver_name') ||
        !driver.start_date && list.includes('start_date') ||
        !driver.type && list.includes('type') ||
        !driver.car && list.includes('car')
	) {
		return {
			status: false,
			message: 'MESSAGE_APP.DRIVER_MANAGEMENT_VALIDATE_REQUIRED',
		};
	}

	if (!validateCrewCode(driver.driver_code) && list.includes('driver_code')) {
		return {
			status: false,
			message: 'MESSAGE_APP.DRIVER_MANAGEMENT_VALIDATE_DRIVER_CODE',
		};
	}

	// if (!validateGrade(driver.grade) && list.includes('grade')) {
	// 	return {
	// 		status: false,
	// 		message: 'MESSAGE_APP.DRIVER_MANAGEMENT_VALIDATE_GRADE',
	// 	};
	// }

	if (!validUsername(driver.driver_name) && !(driver.driver_name.length >= 1 && driver.driver_name.length <= 20) && list.includes('driver_name')) {
		return {
			status: false,
			message: 'MESSAGE_APP.DRIVER_MANAGEMENT_VALIDATE_DRIVER_NAME',
		};
	}

	if (!validUsername(driver.driver_name) || !(driver.driver_name.length >= 1 && driver.driver_name.length <= 20) && list.includes('driver_name')) {
		return {
			status: false,
			message: 'MESSAGE_APP.DRIVER_MANAGEMENT_VALIDATE_DRIVER_NAME',
		};
	}

	return {
		status: true,
		message: null,
	};
}

function validateCourse(course, list = ['course_code', 'group', 'course_name', 'start_time', 'end_time', 'break_time', 'point', 'start_date', 'end_date', 'note']) {
	if (
		!course.course_code && list.includes('course_code') ||
        !course.course_name && list.includes('course_name') ||
        !course.start_time && list.includes('start_time') ||
        !course.end_time && list.includes('end_time') ||
        !course.break_time && list.includes('break_time') ||
        !course.point && list.includes('point') ||
        !course.start_date && list.includes('start_date')
	) {
		return {
			status: false,
			message: 'MESSAGE_APP.COURSE_MANAGEMENT_VALIDATE_REQUIRED',
		};
	}

	if (!validateCourseCode(course.course_code) && list.includes('course_code')) {
		return {
			status: false,
			message: 'MESSAGE_APP.COURSE_MANAGEMENT_VALIDATE_COURSE_CODE',
		};
	}

	if (course.group) {
		if (course.group.length !== 2) {
			return {
				status: false,
				message: 'MESSAGE_APP.COURSE_MANAGEMENT_VALIDATE_GROUP',
			};
		}
	}

	if (!validString(course.course_name) && !(course.course_name.length >= 1 && course.course_name.length <= 30) && list.includes('course_name')) {
		return {
			status: false,
			message: 'MESSAGE_APP.COURSE_MANAGEMENT_VALIDATE_COURSE_NAME',
		};
	}

	if (list.includes('start_time') && list.includes('end_time') && list.includes('break_time')) {
		const VALIDATE = validateStartEndBreakTime(course.start_time, course.end_time, course.break_time);

		if (!VALIDATE.status) {
			return {
				status: false,
				message: VALIDATE.message,
			};
		}
	}

	if (!validateFormatYYYYMMDD(course.start_date) && list.includes('start_date')) {
		return {
			status: false,
			message: 'MESSAGE_APP.COURSE_MANAGEMENT_VALIDATE_START_DATE',
		};
	}

	if (!validateFormatYYYYMMDD(course.end_date) && list.includes('end_date') && course.end_date) {
		return {
			status: false,
			message: 'MESSAGE_APP.COURSE_MANAGEMENT_VALIDATE_END_DATE',
		};
	}

	if (course.start_date && course.end_date && list.includes('start_date') && list.includes('end_date')) {
		const START = new Date(course.start_date);
		const END = new Date(course.end_date);

		const _START = START.getTime();
		const _END = END.getTime();

		if (_START > _END) {
			return {
				status: false,
				message: 'MESSAGE_APP.COURSE_MANAGEMENT_VALIDATE_START_END_DATE',
			};
		}
	}

	if (list.includes('point') && isNaN((course.point + ''))) {
		return {
			status: false,
			message: 'MESSAGE_APP.COURSE_MANAGEMENT_VALIDATE_POINT',
		};
	}

	if (course.note && !validString(course.note) && !(course.note.length >= 1 && course.note.length <= 1000) && list.includes('note')) {
		return {
			status: false,
			message: 'MESSAGE_APP.COURSE_MANAGEMENT_VALIDATE_NOTE',
		};
	}

	return {
		status: true,
		message: null,
	};
}

export {
	validateUser,
	validateDriver,
	validateCourse
};
