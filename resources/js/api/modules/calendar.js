import request from '../config';

export function postSetupDataCalendar(url, data) {
	return request.postRequest(url, data);
}

export function getCalendar(url, params) {
	return request.getRequest(url, params);
}
