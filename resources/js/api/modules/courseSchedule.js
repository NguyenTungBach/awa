import request from '../config';

export function getListSchedule(url, params) {
	return request.getRequest(url, params);
}

export function postListSchedule(url, params) {
	return request.postRequest(url, params);
}

export function postImportFile(url, data, params) {
	return request.postRequest(url, data, params);
}
