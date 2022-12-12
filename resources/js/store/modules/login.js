import CONSTANT from '@/const';
import Cookies from 'js-cookie';

import { getToken, getProfile } from '@/utils/handleToken';

const state = {
	token: getToken(),
	profile: getProfile(),
};

const mutations = {
	SET_TOKEN(state, token) {
		state.token = token;
		Cookies.set(CONSTANT.COOKIES.TOSHIN_TOKEN, token);
	},
	SET_PROFILE(state, profile) {
		state.profile = profile;
		Cookies.set(CONSTANT.COOKIES.TOSHIN_PROFILE, profile);
	},
	SET_LOGOUT(state) {
		state.token = '';
		state.profile = {};
		Cookies.remove(CONSTANT.COOKIES.TOSHIN_TOKEN);
		Cookies.remove(CONSTANT.COOKIES.TOSHIN_PROFILE);
		Cookies.remove(CONSTANT.COOKIES.TOSHIN_PICKER_YEAR_MONTH);
		Cookies.remove(CONSTANT.COOKIES.TOSHIN_PICKER_WEEK);
	},
};

const actions = {
	saveToken({ commit }, token) {
		commit('SET_TOKEN', token);
	},
	saveProfile({ commit }, profile) {
		commit('SET_PROFILE', profile);
	},
	saveLogout({ commit }) {
		commit('SET_LOGOUT');
	},
};

export default {
	namespaced: true,
	state,
	mutations,
	actions,
};
