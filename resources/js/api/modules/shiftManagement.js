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

export {
	getListPractical,
	getListShift,
	postUpdateCellShift,
	postUpdateCourseBase,
	getCheckDataResult,
	postAddListShift,
	getMessageResponseAi,
	getListMessageResponseAI,
	editShift
};
