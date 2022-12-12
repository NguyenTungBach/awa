const state = {
	listUpdate: [],
};

const mutations = {
	SET_LIST_UPDATE: (state, data) => {
		state.listUpdate = data;
	},
};

const actions = {
	setListUpdate({ commit }, data) {
		commit('SET_LIST_UPDATE', data);
	},
};

export default {
	namespaced: true,
	state,
	mutations,
	actions,
};
