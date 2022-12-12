const state = {
	isTable: null,
	isWeekOrMonth: '',

	listUpdate: [],

	reLoadTable: 0,
};

const mutations = {
	SET_TABLE: (state, isTable) => {
		state.isTable = isTable;
	},
	SET_IS_WEEK_OR_MONTH: (state, isWeekOrMonth) => {
		state.isWeekOrMonth = isWeekOrMonth;
	},
	SET_LIST_UPDATE: (state, listUpdate) => {
		state.listUpdate = listUpdate;
	},
	SET_RELOAD_TABLE: (state) => {
		state.reLoadTable = state.reLoadTable + 1;
	},
};

const actions = {
	setTable({ commit }, isTable) {
		commit('SET_TABLE', isTable);
	},
	setIsWeekOrMonth({ commit }, isWeekOrMonth) {
		commit('SET_IS_WEEK_OR_MONTH', isWeekOrMonth);
	},
	setListUpdate({ commit }, listUpdate) {
		commit('SET_LIST_UPDATE', listUpdate);
	},
	setReloadTable({ commit }) {
		commit('SET_RELOAD_TABLE');
	},
};

export default {
	namespaced: true,
	state,
	mutations,
	actions,
};
