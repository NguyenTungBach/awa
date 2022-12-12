import Cookies from 'js-cookie';
import CONSTANT from '@/const';

function getIndexCalendarWeekListShift() {
	const index = Cookies.get(CONSTANT['COOKIES']['INDEX_CALENDAR_WEEK_LIST_SHIFT']);

	if (index) {
		return parseInt(index);
	}

	return null;
}

const state = {
	clickBack: 0,
	clickNext: 0,

	indexCalendarWeekListShift: getIndexCalendarWeekListShift(),
};

const mutations = {
	SET_CLICK_BACK: (state) => {
		state.clickBack = state.clickBack + 1;
	},
	SET_CLICK_NEXT: (state) => {
		state.clickNext = state.clickNext + 1;
	},
	SET_INDEX_CALENDAR_WEEK_LIST_SHIFT: (state, index) => {
		state.indexCalendarWeekListShift = index;
	},
};

const actions = {
	setClickBackCalendarMonth({ commit }) {
		commit('SET_CLICK_BACK');
	},
	setClickNextCalendarMonth({ commit }) {
		commit('SET_CLICK_NEXT');
	},
	setIndexCalendarWeekListShift({ commit }, index) {
		commit('SET_INDEX_CALENDAR_WEEK_LIST_SHIFT', index);
		Cookies.set(CONSTANT['COOKIES']['INDEX_CALENDAR_WEEK_LIST_SHIFT'], index);
	},
};

export default {
	namespaced: true,
	state,
	mutations,
	actions,
};
