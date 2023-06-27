import CONSTANT from '@/const';
import { convertBreakTimeNumberToTime } from '@/utils/convertTime';
import { validateStartEndBreakTimeListShift } from '@/utils/validateTime';

function checkRequiredEditShift(listUpdate = []) {
	if (listUpdate.length === 0) {
		return {
			status: false,
			message: 'MESSAGE_APP.LIST_SHIFT_VALIDATE_REQUIRED',
		};
	}

	const len = listUpdate.length;
	let idx = 0;

	while (idx < len) {
		const ITEM = listUpdate[idx];

		const LIST_DAY_OFF = CONSTANT.LIST_SHIFT.LIST_VALUE_DAY_OFF;

		if (!ITEM.type) {
			return {
				status: false,
				message: 'MESSAGE_APP.LIST_SHIFT_VALIDATE_REQUIRED_TYPE',
			};
		}

		if (!LIST_DAY_OFF.includes(ITEM.type)) {
			if (ITEM.type !== 'H-0') {
				if ((ITEM.start_time).includes(null) || (ITEM.end_time).includes(null) || (ITEM.break_time).includes(null)) {
					return {
						status: false,
						message: 'MESSAGE_APP.LIST_SHIFT_VALIDATE_REQUIRED_TIME',
					};
				}

				const FLAG = ITEM.course.flag;
				if (FLAG === 'yes') {
					const START_TIME = ITEM.start_time;
					const END_TIME = ITEM.end_time;
					const BREAK_TIME = ITEM.break_time;

					if ((START_TIME).includes(null) || (END_TIME).includes(null) || (BREAK_TIME).includes(null)) {
						return {
							status: false,
							message: 'MESSAGE_APP.LIST_SHIFT_VALIDATE_REQUIRED_TIME',
						};
					}
				} else {
					const START_TIME = ITEM.course.start_time;
					const END_TIME = ITEM.course.end_time;
					const BREAK_TIME = convertBreakTimeNumberToTime(ITEM.course.break_time);

					if (START_TIME === null || END_TIME === null || BREAK_TIME === null) {
						return {
							status: false,
							message: 'MESSAGE_APP.LIST_SHIFT_VALIDATE_REQUIRED_TIME',
						};
					}
				}
			}
			// if ((ITEM.start_time).includes(null) || (ITEM.end_time).includes(null) || (ITEM.break_time).includes(null)) {
			// 	return {
			// 		status: false,
			// 		message: 'MESSAGE_APP.LIST_SHIFT_VALIDATE_REQUIRED_TIME',
			// 	};
			// }

			// const FLAG = ITEM.course.flag;
			// if (FLAG === 'yes') {
			// 	const START_TIME = ITEM.start_time;
			// 	const END_TIME = ITEM.end_time;
			// 	const BREAK_TIME = ITEM.break_time;

			// 	if ((START_TIME).includes(null) || (END_TIME).includes(null) || (BREAK_TIME).includes(null)) {
			// 		return {
			// 			status: false,
			// 			message: 'MESSAGE_APP.LIST_SHIFT_VALIDATE_REQUIRED_TIME',
			// 		};
			// 	}
			// } else {
			// 	const START_TIME = ITEM.course.start_time;
			// 	const END_TIME = ITEM.course.end_time;
			// 	const BREAK_TIME = convertBreakTimeNumberToTime(ITEM.course.break_time);

			// 	if (START_TIME === null || END_TIME === null || BREAK_TIME === null) {
			// 		return {
			// 			status: false,
			// 			message: 'MESSAGE_APP.LIST_SHIFT_VALIDATE_REQUIRED_TIME',
			// 		};
			// 	}
			// }
		}

		idx++;
	}

	return {
		status: true,
		message: null,
	};
}

function checkDuplicateEditShift(listUpdate = []) {
	const LIST_DAY_OFF = [
		CONSTANT.LIST_SHIFT.DATE_LEADER_CHIEF,
		CONSTANT.LIST_SHIFT.DATE_WAIT_BETWEEN_TASK,
		...CONSTANT.LIST_SHIFT.LIST_VALUE_DAY_OFF,
	];

	const listUpdateNotDayOff = listUpdate.filter(item => !LIST_DAY_OFF.includes(item.type));

	const len = listUpdateNotDayOff.length;
	let idx = 0;

	while (idx < len) {
		const isDuplicate = listUpdateNotDayOff.filter(item => item.type === listUpdateNotDayOff[idx].type).length > 1;

		if (isDuplicate) {
			return {
				status: false,
				message: 'MESSAGE_APP.LIST_SHIFT_VALIDATE_DUPLICATE_DATE_COURSE',
			};
		}

		idx++;
	}

	return {
		status: true,
		message: null,
	};
}

function checkTimeEditShift(listUpdate = []) {
	const len = listUpdate.length;
	let idx = 0;

	console.log('check time: ', listUpdate);

	let isPass = 0;

	while (idx < len) {
		const ITEM = listUpdate[idx];
		const FLAG = ITEM.course.flag;
		const TYPE = ITEM.type;
		console.log('get type: ', TYPE);

		if (TYPE !== 'H-0') {
			console.log('check type: ', TYPE);
			if (FLAG === 'yes') {
				const START_TIME = (ITEM.start_time).join(':');
				const END_TIME = (ITEM.end_time).join(':');
				const BREAK_TIME = (ITEM.break_time).join(':');

				const VALIDATE = validateStartEndBreakTimeListShift(START_TIME, END_TIME, BREAK_TIME);

				if (VALIDATE.status === false) {
					return VALIDATE;
				}

				if (VALIDATE.status) {
					isPass = isPass + 1;
				}
			} else {
				if ((CONSTANT.LIST_SHIFT.LIST_VALUE_DAY_OFF).includes(ITEM.type)) {
					const START_TIME = (ITEM.start_time).join(':');
					const END_TIME = (ITEM.end_time).join(':');
					const BREAK_TIME = (ITEM.break_time).join(':');

					const VALIDATE = validateStartEndBreakTimeListShift(START_TIME, END_TIME, BREAK_TIME);

					if (VALIDATE.status === false) {
						return VALIDATE;
					}

					if (VALIDATE.status) {
						isPass = isPass + 1;
					}
				} else {
					const START_TIME = ITEM.course.start_time;
					const END_TIME = ITEM.course.end_time;
					const BREAK_TIME = ITEM.course.break_time;

					const VALIDATE = validateStartEndBreakTimeListShift(START_TIME, END_TIME, BREAK_TIME);

					if (VALIDATE.status === false) {
						return VALIDATE;
					}

					if (VALIDATE.status) {
						isPass = isPass + 1;
					}
				}
			}
		} else {
			isPass = isPass + 1;
		}

		// if (FLAG === 'yes') {
		// 	const START_TIME = (ITEM.start_time).join(':');
		// 	const END_TIME = (ITEM.end_time).join(':');
		// 	const BREAK_TIME = (ITEM.break_time).join(':');

		// 	const VALIDATE = validateStartEndBreakTimeListShift(START_TIME, END_TIME, BREAK_TIME);

		// 	if (VALIDATE.status === false) {
		// 		return VALIDATE;
		// 	}

		// 	if (VALIDATE.status) {
		// 		isPass = isPass + 1;
		// 	}
		// } else {
		// 	if ((CONSTANT.LIST_SHIFT.LIST_VALUE_DAY_OFF).includes(ITEM.type)) {
		// 		const START_TIME = (ITEM.start_time).join(':');
		// 		const END_TIME = (ITEM.end_time).join(':');
		// 		const BREAK_TIME = (ITEM.break_time).join(':');

		// 		const VALIDATE = validateStartEndBreakTimeListShift(START_TIME, END_TIME, BREAK_TIME);

		// 		if (VALIDATE.status === false) {
		// 			return VALIDATE;
		// 		}

		// 		if (VALIDATE.status) {
		// 			isPass = isPass + 1;
		// 		}
		// 	} else {
		// 		const START_TIME = ITEM.course.start_time;
		// 		const END_TIME = ITEM.course.end_time;
		// 		const BREAK_TIME = ITEM.course.break_time;

		// 		const VALIDATE = validateStartEndBreakTimeListShift(START_TIME, END_TIME, BREAK_TIME);

		// 		if (VALIDATE.status === false) {
		// 			return VALIDATE;
		// 		}

		// 		if (VALIDATE.status) {
		// 			isPass = isPass + 1;
		// 		}
		// 	}
		// }

		idx++;
	}

	if (isPass === len) {
		return {
			status: true,
			message: null,
		};
	} else {
		return {
			status: false,
			message: 'MESSAGE_APP.LIST_SHIFT_VALIDATE_TIME_START_END_COURSE',
		};
	}
}

function validateDuplicateTime(listUpdate = []) {
	const len = listUpdate.length;
	let idx = 0;
	let idxCompare = 0;

	while (idxCompare < len - 1) {
		idxCompare = idx + 1;

		const END = listUpdate[idx];
		const START = listUpdate[idxCompare];

		if (END.type !== 'H-0' && START.type !== 'H-0') {
			let END_TIME = null;
			let START_TIME = null;

			if (END.course.flag === 'yes') {
				END_TIME = (END.end_time).join(':');
			} else {
				END_TIME = END.course.end_time;
			}

			if (START.course.flag === 'yes') {
				START_TIME = (START.start_time).join(':');
			} else {
				START_TIME = START.course.start_time;
			}

			const _endTime = getTime(END_TIME);
			const _startTime = getTime(START_TIME);

			if (_startTime.hour < _endTime.hour) {
				return {
					status: false,
					message: 'MESSAGE_APP.LIST_SHIFT_VALIDATE_DUPLICATE_TIME_COURSE',
				};
			} else if (_startTime.hour === _endTime.hour) {
				if (_startTime.minute < _endTime.minute) {
					return {
						status: false,
						message: 'MESSAGE_APP.LIST_SHIFT_VALIDATE_DUPLICATE_TIME_COURSE',
					};
				}
			}
		}
		// let END_TIME = null;
		// let START_TIME = null;

		// if (END.course.flag === 'yes') {
		// 	END_TIME = (END.end_time).join(':');
		// } else {
		// 	END_TIME = END.course.end_time;
		// }

		// if (START.course.flag === 'yes') {
		// 	START_TIME = (START.start_time).join(':');
		// } else {
		// 	START_TIME = START.course.start_time;
		// }

		// const _endTime = getTime(END_TIME);
		// const _startTime = getTime(START_TIME);

		// if (_startTime.hour < _endTime.hour) {
		// 	return {
		// 		status: false,
		// 		message: 'MESSAGE_APP.LIST_SHIFT_VALIDATE_DUPLICATE_TIME_COURSE',
		// 	};
		// } else if (_startTime.hour === _endTime.hour) {
		// 	if (_startTime.minute < _endTime.minute) {
		// 		return {
		// 			status: false,
		// 			message: 'MESSAGE_APP.LIST_SHIFT_VALIDATE_DUPLICATE_TIME_COURSE',
		// 		};
		// 	}
		// }

		idx++;
	}

	return {
		status: true,
		message: null,
	};
}

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

function validateEditShift(listUpdate = []) {
	const validRequired = checkRequiredEditShift(listUpdate);

	console.log('list   : ', listUpdate);
	// if ()

	if (validRequired.status) {
		const validDuplicate = checkDuplicateEditShift(listUpdate);

		if (validDuplicate.status) {
			const validTime = checkTimeEditShift(listUpdate);

			if (validTime.status) {
				const validDuplicateTime = validateDuplicateTime(listUpdate);

				if (validDuplicateTime.status) {
					return {
						status: true,
						message: null,
					};
				} else {
					return validDuplicateTime;
				}
			} else {
				return validTime;
			}
		} else {
			return validDuplicate;
		}
	} else {
		return validRequired;
	}
}

export {
	validateEditShift
};
