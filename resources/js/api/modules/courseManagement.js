import request from '../config';

export function getList(url, params) {
	return request.getRequest(url, params);
}

export function postCourse(url, data) {
	return request.postRequest(url, data);
}

export function getCourse(url, params) {
	return request.getRequest(url, params);
}

export function putCourse(url, data) {
	return request.putRequest(url, data);
}

export function deleteCourse(url, params) {
	return request.deleteRequest(url, params);
}
