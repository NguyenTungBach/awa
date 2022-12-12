import Vue from 'vue';
import VueRouter from 'vue-router';

Vue.use(VueRouter);

import PageNotFound from './modules/PageNotFound';
import Login from './modules/Login';
import ShiftManagement from './modules/ShiftManagement';
import DataManagement from './modules/DataManagement';
import Dev from './modules/Dev';

export const constantRoutes = [
	Login,
	Dev,
];

export const asyncRoutes = [
	ShiftManagement,
	DataManagement,
	{
		path: '/',
		name: 'Dashboard',
		meta: {
			roles: ['admin', 'driver'],
		},
		redirect: { name: 'ListShift' },
		hidden: true,
	},
	PageNotFound,
	{
		path: '*',
		hidden: true,
		redirect: { name: 'PageNotFound' },
	},
];

const createRouter = () => new VueRouter({
	mode: 'history',
	scrollBehavior: () => ({ y: 0 }),
	routes: constantRoutes,
});

const router = createRouter();

export function resetRouter() {
	const newRouter = createRouter();
	router.matcher = newRouter.matcher;
}

export default router;
