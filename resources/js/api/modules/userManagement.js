import request from '../config';

function getList(url, params) {
	return request.getRequest(url, params);
}

function postUser(url, data) {
	return request.postRequest(url, data);
}

function getUser(url, params) {
	return request.getRequest(url, params);
}

function putUser(url, data) {
	return request.putRequest(url, data);
}

function deleteUser(url, params) {
	return request.deleteRequest(url, params);
}

export {
	getList,
	postUser,
	getUser,
	putUser,
	deleteUser
};
