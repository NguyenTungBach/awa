import Cookies from 'js-cookie';
import { getLanguage } from '@/lang/helper/getLang';
import CONSTANT from '@/const';
import { getValue } from '@/utils/handleCookies';

const state = {
	language: getLanguage(),
	pickerYearMonth: getValue(CONSTANT['COOKIES']['TOSHIN_PICKER_YEAR_MONTH']),
	pickerWeek: getValue(CONSTANT['COOKIES']['TOSHIN_PICKER_WEEK']),
};

const mutations = {
	SET_LANGUAGE: (state, language) => {
		state.language = language;
		Cookies.set(CONSTANT['COOKIES']['LANGUAGE'], language);
	},
	SET_PICKER_YEAR_MONTH: (state, pickerYearMonth) => {
		state.pickerYearMonth = pickerYearMonth;
		Cookies.set(CONSTANT['COOKIES']['TOSHIN_PICKER_YEAR_MONTH'], pickerYearMonth);
	},
	SET_PICKER_WEEK: (state, pickerWeek) => {
		state.pickerWeek = pickerWeek;
		Cookies.set(CONSTANT['COOKIES']['TOSHIN_PICKER_WEEK'], pickerWeek);
	},
};

const actions = {
	setLanguage({ commit }, language) {
		commit('SET_LANGUAGE', language);
	},
	setPickerYearMonth({ commit }, pickerYearMonth) {
		commit('SET_PICKER_YEAR_MONTH', pickerYearMonth);
	},
	setPickerWeek({ commit }, pickerWeek) {
		commit('SET_PICKER_WEEK', pickerWeek);
	},
};

export default {
	namespaced: true,
	state,
	mutations,
	actions,
};
