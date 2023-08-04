const state = {
	idRouter: 0,
};

const mutations = {
	SET_ID_ROUTER: (state, index) => {
		state.idRouter = index;
	},
};

const actions = {
	setIdRouter({ commit }, index) {
		commit('SET_ID_ROUTER', index);
	},
};

export default {
	namespaced: true,
	state,
	mutations,
	actions,
};
