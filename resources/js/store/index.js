import Vue from 'vue';
import Vuex from 'vuex';

import app from './modules/app';
import loading from './modules/loading';
import login from './modules/login';
import permissions from './modules/permissions';
import listShift from './modules/listShift';
import listDayoff from './modules/listDayoff';
import listSchedule from './modules/listSchedule';
import listDriver from './modules/listDriver';
import listCoursePattern from './modules/listCoursePattern';
import handleAI from './modules/handleAI';
import calendar from './modules/calendar';
import user from './modules/user';
import course from './modules/course';
import listCash from './modules/listCashOut';

import getters from './getters';

Vue.use(Vuex);

const modules = {
	app,
	loading,
	login,
	permissions,
	listShift,
	listDayoff,
	listSchedule,
	listDriver,
	listCoursePattern,
	handleAI,
	calendar,
	user,
	course,
	listCash,
};

const store = new Vuex.Store({
	modules,
	getters,
});

export default store;
