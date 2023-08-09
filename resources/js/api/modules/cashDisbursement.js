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

export function putCashOut(url, params) {
	return request.putRequest(url, params);
}

// Cash Reciept
export function getCashReciept(url, data) {
	return request.getRequest(url, data);
}

export function getDetailCashReciept(url, data) {
	return request.getRequest(url, data);
}

export function getCashIn(url, data) {
	return request.getRequest(url, data);
}

export function deleteCashIn(url, data) {
	return request.deleteRequest(url, data);
}

export function getDetailCashIn(url, data) {
	return request.getRequest(url, data);
}

export function pustCashIn(url, data) {
	return request.putRequest(url, data);
}

export function postCashIn(url, data) {
	return request.postRequest(url, data);
}
