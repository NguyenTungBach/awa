import request from '../config';

export function postLogin(url, data) {
	return request.postRequest(url, data);
}
