function convertValueToText(arr, value, keyValue = 'value', keyText = 'text') {
	let idx = 0;
	const len = arr.length;

	while (idx < len) {
		if (arr[idx][keyValue] === value) {
			return arr[idx][keyText];
		}

		idx++;
	}

	return '';
}

function convertArrValueToArrText(arr, arrValue, keyValue = 'value', keyText = 'text') {
	let idx = 0;
	const len = arrValue.length;

	const result = [];

	while (idx < len) {
		const item = convertValueToText(arr, arrValue[idx], keyValue, keyText);

		console.log(arrValue[idx]);

		result.push(item);

		idx++;
	}

	return result;
}

export {
	convertValueToText,
	convertArrValueToArrText
};
