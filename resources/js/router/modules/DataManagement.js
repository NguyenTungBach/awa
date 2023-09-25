import Layout from '@/layout';

const DataManagement = {
	path: '/data-management',
	name: 'DataManagement',
	meta: {
		title: 'ROUTER.DATA_MANAGEMENT',
		roles: ['admin'],
	},
	component: Layout,
	children: [
		{
			path: 'list-driver',
			name: 'ListDriver',
			meta: {
				title: 'ROUTER.LIST_DRIVER',
				roles: ['admin'],
			},
			component: () => import(/* webpackChunkName: "List Driver" */ '@/pages/DataManagement/ListDriver/index.vue'),
		},
		{
			path: 'list-driver-create',
			name: 'ListDriverCreate',
			meta: {
				title: 'ROUTER.LIST_DRIVER',
				roles: ['admin'],
			},
			hidden: true,
			component: () => import(/* webpackChunkName: "List Driver Create" */ '@/pages/DataManagement/ListDriver/create.vue'),
		},
		{
			path: 'driver-course-edit/:id',
			name: 'DriverCourseEdit',
			meta: {
				title: 'ROUTER.LIST_DRIVER',
				roles: ['admin'],
			},
			hidden: true,
			component: () => import(/* webpackChunkName: "Driver Course Edit" */ '@/pages/DataManagement/ListDriver/DriverCourseEdit.vue'),
		},
		{
			path: 'list-driver-detail/:id',
			name: 'ListDriverDetail',
			meta: {
				title: 'ROUTER.LIST_DRIVER',
				roles: ['admin'],
			},
			hidden: true,
			component: () => import(/* webpackChunkName: "Driver Detail" */ '@/pages/DataManagement/ListDriver/detail.vue'),
		},
		{
			path: 'list-driver-edit/:id',
			name: 'ListDriverEdit',
			meta: {
				title: 'ROUTER.LIST_DRIVER',
				roles: ['admin'],
			},
			hidden: true,
			component: () => import(/* webpackChunkName: "Driver Edit" */ '@/pages/DataManagement/ListDriver/edit.vue'),
		},
		{
			path: 'customer',
			name: 'LayoutCustomer',
			meta: {
				title: 'ROUTER.LIST_COURSE',
				roles: ['admin'],
			},
			component: () => import(/* webpackChunkName: "Layout Course" */ '@/pages/DataManagement/LayoutCustomer/index.vue'),
			redirect: { name: 'ListCustomerIndex' },
			children: [
				{
					path: 'index',
					name: 'ListCustomerIndex',
					meta: {
						title: 'ROUTER.LIST_COURSE',
						roles: ['admin'],
					},
					component: () => import(/* webpackChunkName: "List Course" */ '@/pages/DataManagement/ListCustomer/index.vue'),
					hidden: true,
				},
				{
					path: 'create',
					name: 'CustomerCreate',
					meta: {
						title: 'ROUTER.LIST_COURSE',
						roles: ['admin'],
					},
					component: () => import(/* webpackChunkName: "Course Create" */ '@/pages/DataManagement/ListCustomer/create.vue'),
					hidden: true,
				},
				{
					path: 'detail/:id',
					name: 'CustomerDetail',
					meta: {
						title: 'ROUTER.LIST_COURSE',
						roles: ['admin'],
					},
					component: () => import(/* webpackChunkName: "Course Detail" */ '@/pages/DataManagement/ListCustomer/detail.vue'),
					hidden: true,
				},
				{
					path: 'edit/:id',
					name: 'CustomerEdit',
					meta: {
						title: 'ROUTER.LIST_COURSE',
						roles: ['admin'],
					},
					component: () => import(/* webpackChunkName: "Course Edit" */ '@/pages/DataManagement/ListCustomer/edit.vue'),
					hidden: true,
				},

			],
		},
		{
			path: 'list-user',
			name: 'LayoutUser',
			meta: {
				title: 'ROUTER.LIST_USER',
				roles: ['admin'],
			},
			component: () => import(/* webpackChunkName: "Layout User" */ '@/pages/DataManagement/LayoutUser/index.vue'),
			redirect: { name: 'ListUser' },
			children: [
				{
					path: 'list',
					name: 'ListUser',
					meta: {
						title: 'ROUTER.LIST_USER',
						roles: ['admin'],
					},
					component: () => import(/* webpackChunkName: "List User" */ '@/pages/DataManagement/ListUser/index.vue'),
					hidden: true,
				},
				{
					path: 'create',
					name: 'UserCreate',
					meta: {
						title: 'ROUTER.LIST_USER',
						roles: ['admin'],
					},
					component: () => import(/* webpackChunkName: "User Create" */ '@/pages/DataManagement/ListUser/create.vue'),
					hidden: true,
				},
				{
					path: 'detail/:id',
					name: 'UserDetail',
					meta: {
						title: 'ROUTER.LIST_USER',
						roles: ['admin'],
					},
					component: () => import(/* webpackChunkName: "User Detail" */ '@/pages/DataManagement/ListUser/detail.vue'),
					hidden: true,
				},
				{
					path: 'edit/:id',
					name: 'UserEdit',
					meta: {
						title: 'ROUTER.LIST_USER',
						roles: ['admin'],
					},
					component: () => import(/* webpackChunkName: "User Edit" */ '@/pages/DataManagement/ListUser/edit.vue'),
					hidden: true,
				},
			],
		},
	],
};

export default DataManagement;
