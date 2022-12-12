function Obj2Param(obj) {
	var param = '';

	for (var key in obj) {
		param += key + '=' + obj[key] + '&';
	}

	return param.substring(0, param.length - 1);
}

export {
	Obj2Param
};
