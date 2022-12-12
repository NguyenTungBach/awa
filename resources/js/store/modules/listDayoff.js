const state = {
	listUpdate: [],
};

const mutations = {
	SET_LIST_UPDATE: (state, listUpdate) => {
		state.listUpdate = listUpdate;
	},
};

const actions = {
	setListUpdate({ commit }, listUpdate) {
		commit('SET_LIST_UPDATE', listUpdate);
	},
};

export default {
	namespaced: true,
	state,
	mutations,
	actions,
};
