import request from '../config';

export function getList(url, params) {
	return request.getRequest(url, params);
}

export function postCoursePattern(url, data) {
	return request.postRequest(url, data);
}
