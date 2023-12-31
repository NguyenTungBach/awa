import request from '../config';

export function getListSchedule(url, params) {
	return request.getRequest(url, params);
}

export function postListSchedule(url, params) {
	return request.postRequest(url, params);
}

export function postCourse(url, params) {
	return request.postRequest(url, params);
}

export function postImportFile(url, data, params) {
	return request.postRequest(url, data, params);
}

export function deleteCourse(url, params) {
	return request.deleteRequest(url, params);
}

export function getDetail(url, params) {
	return request.getRequest(url, params);
}

export function getAllDelete(url, params) {
	return request.deleteRequest(url, params);
}

export function postExportExcel(url, params) {
	return request.postRequest(url, params);
}

export function editCourse(url, params) {
	return request.putRequest(url, params);
}
