const state = {
	showModalMessage: false,
	typeMessage: '',
	textMessage: '',
};

const mutations = {
	SET_MODAL_MESSAGE: (state, status) => {
		state.showModalMessage = status;
	},
	SET_TYPE_MESSAGE: (state, type) => {
		state.typeMessage = type;
	},
	SET_TEXT_MESSAGE: (state, text) => {
		state.textMessage = text;
	},
};

const actions = {
	setModalMessage({ commit }, status) {
		if ([true, false].includes(status)) {
			commit('SET_MODAL_MESSAGE', status);
		} else {
			commit('SET_MODAL_MESSAGE', true);
		}
	},
	setTypeMessage({ commit }, type) {
		commit('SET_TYPE_MESSAGE', type);
	},
	setTextMessage({ commit }, text) {
		commit('SET_TEXT_MESSAGE', text);
	},
};

export default {
	namespaced: true,
	state,
	mutations,
	actions,
};
