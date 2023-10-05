import request from '../config';

function getListPractical(url, params) {
	return request.getRequest(url, params);
}

function getListShift(url, params = null) {
	return request.getRequest(url, params);
}

function postUpdateCellShift(url, data) {
	return request.postRequest(url, data);
}

function postUpdateCourseBase(url, data) {
	return request.postRequest(url, data);
}

function getCheckDataResult(url, params) {
	return request.getRequest(url, params);
}

function postAddListShift(url, data) {
	return request.postRequest(url, data);
}

function getMessageResponseAi(url, params = null) {
	return request.getRequest(url, params);
}

function getListMessageResponseAI(url, params = null) {
	return request.getRequest(url, params);
}

function editShift(url, params) {
	return request.postRequest(url, params);
}

function postClosingDate(url, data) {
	return request.postRequest(url, data);
}

function putShift(url, data) {
	return request.postRequest(url, data);
}

function getDataUpdate(url, data) {
	return request.getRequest(url, data);
}

function CheckFinalClosingDate(url, data) {
	return request.getRequest(url, data);
}

function CheckTemporory(url, data) {
	return request.postRequest(url, data);
}

function CheckButtonTemporary(url, data) {
	return request.getRequest(url, data);
}

function CheckButtonFinalClosing(url, data) {
	return request.getRequest(url, data);
}

export {
	getListPractical,
	getListShift,
	postUpdateCellShift,
	postUpdateCourseBase,
	getCheckDataResult,
	postAddListShift,
	postClosingDate,
	getMessageResponseAi,
	getListMessageResponseAI,
	editShift,
	putShift,
	getDataUpdate,
	CheckFinalClosingDate,
	CheckTemporory,
	CheckButtonTemporary,
	CheckButtonFinalClosing
};
