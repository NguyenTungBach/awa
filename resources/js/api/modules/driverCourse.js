import request from '../config';

export function getListDriverCourse(url, params) {
	return request.getRequest(url, params);
}

export function postListDriverCourse(url, data) {
	return request.postRequest(url, data);
}
