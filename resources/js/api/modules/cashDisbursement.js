import request from '../config';

export function getCashDisbursement(url, data) {
	return request.getRequest(url, data);
}
