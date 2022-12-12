import request from '../config';

export function getList(url, params) {
	return request.getRequest(url, params);
}

export function postDriver(url, data) {
	return request.postRequest(url, data);
}

export function getDriver(url, params) {
	return request.getRequest(url, params);
}

export function putDriver(url, data) {
	return request.putRequest(url, data);
}

export function deleteDriver(url, params) {
	return request.deleteRequest(url, params);
}
