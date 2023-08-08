const state = {
	testText: "vuex thử nghiệm",
};

const mutations = {
	SET_TEST_TEXT: (state, status) => {
		state.testText = status;
	},
};

const actions = {
	setChangeVuex({ commit }, status) {
		commit('SET_TEST_TEXT', status);
	},
};

export default {
	namespaced: true,
	state,
	mutations,
	actions,
};
