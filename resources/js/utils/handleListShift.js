function convertValueWhenNull(shift) {
	const len = shift.length;
	let idx = 0;

	while (idx < len) {
		const listShift = shift[idx].shift_list;
		const lenListShift = listShift.length;
		let idxListShift = 0;

		while (idxListShift < lenListShift) {
			if (listShift[idxListShift].value === null) {
				shift[idx].shift_list[idxListShift].value = [];
			}

			idxListShift++;
		}

		idx++;
	}

	return shift;
}

function convertStatusToText(status) {
	const LIBRAY = {
		'on': 'MESSAGE_RESPONSE_AI.TEXT_STATUS_PROCESS',
		'success': 'MESSAGE_RESPONSE_AI.TEXT_STATUS_SUCCESS',
		'error': 'MESSAGE_RESPONSE_AI.TEXT_STATUS_ERROR',
		'check': 'MESSAGE_RESPONSE_AI.TEXT_STATUS_CHECK',
	};

	return LIBRAY[status] || 'MESSAGE_RESPONSE_AI.TEXT_STATUS_DEFAULT';
}

function convertStatusToMessage(status) {
	const LIBRAY = {
		'success': 'LIST_SHIFT.MESSAGE_AI_SUCCESS',
		'error': 'LIST_SHIFT.MESSAGE_AI_ERROR',
	};

	return LIBRAY[status] || '';
}

function converStatusToTextStatus(status) {
	const LIBRAY = {
		'success': 'STATUS.success',
		'error': 'STATUS.error',
	};

	return LIBRAY[status] || '';
}

export {
	convertValueWhenNull,
	convertStatusToText,
	convertStatusToMessage,
	converStatusToTextStatus
};
