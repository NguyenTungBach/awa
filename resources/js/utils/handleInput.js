function validInputNumber(e) {
	const key = e.keyCode || e.which;

	const LIST_VALIDATE = [69, 187, 189, 190];

	if (LIST_VALIDATE.includes(key)) {
		e.preventDefault();

		return false;
	}
}

function validInputFloat(e) {
	const key = e.keyCode || e.which;

	const LIST_VALIDATE = [69, 187, 189];

	if (LIST_VALIDATE.includes(key)) {
		e.preventDefault();

		return false;
	}
}

function validInputCourseCode(e) {
	const key = e.keyCode || e.which;
	const shiftKey = e.shiftKey;

	if ([9, 189].includes(key) && shiftKey === false) {
		return true;
	}

	if (shiftKey) {
		e.preventDefault();

		return false;
	}

	const LIST_VALIDATE = [16, 192, 188, 190, 191, 186, 222, 219, 221, 187];

	if (LIST_VALIDATE.includes(key)) {
		e.preventDefault();

		return false;
	}
}

export {
	validInputNumber,
	validInputFloat,
	validInputCourseCode
};
