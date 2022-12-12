function cleanObject(obj, list = [null, undefined, '']) {
	for (var propName in obj) {
		if (list.includes(obj[propName])) {
			delete obj[propName];
		}
	}

	return obj;
}

export {
	cleanObject
};
