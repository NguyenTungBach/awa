const state = {
	list: [],
	warningNotSave: false,
};

const mutations = {
	SET_LIST: (state, data) => {
		state.items = data;
	},
	SET_WARNING_NOT_SAVE: (state, status) => {
		state.warningNotSave = status;
	},
};

const actions = {
	setList({ commit }, data) {
		commit('SET_LIST', data);
	},
	setWarningNotSave({ commit }, status) {
		commit('SET_WARNING_NOT_SAVE', status);
	},
};

export default {
	namespaced: true,
	state,
	mutations,
	actions,
};
