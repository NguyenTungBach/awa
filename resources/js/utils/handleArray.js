function removeDirtyItemInArray(arr, dirty = [null, undefined, ''], key = null) {
	const results = arr.filter(element => {
		return !dirty.includes(key ? element[key] : element);
	});

	return results;
}

export {
	removeDirtyItemInArray
};
