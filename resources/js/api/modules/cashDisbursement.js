import request from '../config';

export function getCashDisbursement(url, data) {
	return request.getRequest(url, data);
}

export function getDetailDisbursement(url, data) {
	return request.getRequest(url, data);
}

export function getListCashOut(url, data) {
	return request.getRequest(url, data);
}

export function deleteCashOut(url, params) {
	return request.deleteRequest(url, params);
}

export function postCashOut(url, params) {
	return request.postRequest(url, params);
}

export function updateCashOut(url, params) {
	return request.putRequest(url, params);
}
