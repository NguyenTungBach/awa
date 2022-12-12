const state = {
	tabIndex: 0,
	warningNotSave: false,
};

const mutations = {
	SET_INDEX_TAB: (state, index) => {
		state.tabIndex = index;
	},
	SET_WARNING_NOT_SAVE: (state, status) => {
		state.warningNotSave = status;
	},
};

const actions = {
	setIndexTab({ commit }, index) {
		commit('SET_INDEX_TAB', index);
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
