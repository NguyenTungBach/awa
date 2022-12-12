const state = {
	warningNotSave: false,
};

const mutations = {
	SET_WARNING_NOT_SAVE: (state, status) => {
		state.warningNotSave = status;
	},
};

const actions = {
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
