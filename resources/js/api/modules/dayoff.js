import request from '../config';

export function getListDayOff(url, params = null) {
	return request.getRequest(url, params);
}

export function postListDayOff(url, body) {
	return request.postRequest(url, body);
}
