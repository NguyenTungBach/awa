import Layout from '@/layout';
const MenuMobile = {
	path: '/mobile',
	name: 'MenuMobile',
	meta: {
		// title: 'ROUTER.CASH_MANAGEMENT',
		roles: ['admin'],
	},
	// component: Layout,
	// component: () => import(/* webpackChunkName: "Menu" */ '@/pages/MenuMobile/index.vue'),
	hidden: true,
	component: Layout,
	redirect: { name: 'Menu' },
	children: [
		{
			path: 'menu',
			name: 'Menu',
			component: () => import(/* webpackChunkName: "DevIndex" */ '@/pages/MenuMobile/index.vue'),
		},
	],
};

export default MenuMobile;
