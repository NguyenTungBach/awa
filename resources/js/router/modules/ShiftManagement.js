import Layout from '@/layout';

const ShiftManagement = {
	path: '/shift-management',
	name: 'ShiftManagement',
	meta: {
		title: 'ROUTER.SHIFT_MANAGEMENT',
		roles: ['admin', 'driver'],
	},
	component: Layout,
	children: [
		{
			path: 'list-shift',
			name: 'ListShift',
			meta: {
				title: 'ROUTER.LIST_SHIFT',
				roles: ['admin', 'driver'],
			},
			component: () => import(/* webpackChunkName: "List Shift" */ '@/pages/ShiftManagement/ListShift/index.vue'),
		},
		{
			path: 'list-shift-edit',
			name: 'ListShiftEdit',
			meta: {
				title: 'ROUTER.LIST_SHIFT',
				roles: ['admin'],
			},
			hidden: true,
			component: () => import(/* webpackChunkName: "List Shift Edit" */ '@/pages/ShiftManagement/ListShift/edit.vue'),
		},
		{
			path: 'list-course-base-edit',
			name: 'ListCourseBaseEdit',
			meta: {
				title: 'ROUTER.LIST_SHIFT',
				roles: ['admin'],
			},
			hidden: true,
			component: () => import(/* webpackChunkName: "List Shift Edit" */ '@/pages/ShiftManagement/ListShift/CourseBase/edit.vue'),
		},
		{
			path: 'list-day-off',
			name: 'ListDayOff',
			meta: {
				title: 'ROUTER.LIST_DAY_OFF',
				roles: ['admin'],
			},
			component: () => import(/* webpackChunkName: "List Day Off" */ '@/pages/ShiftManagement/ListDayOff/index.vue'),
		},
		{
			path: 'list-day-off-edit',
			name: 'ListDayOffEdit',
			meta: {
				title: 'ROUTER.LIST_DAY_OFF',
				roles: ['admin'],
			},
			hidden: true,
			component: () => import(/* webpackChunkName: "List Day Off Edit" */ '@/pages/ShiftManagement/ListDayOff/edit.vue'),
		},
		{
			path: 'list-schedule',
			name: 'ListSchedule',
			meta: {
				title: 'ROUTER.LIST_SCHEDULE',
				roles: ['admin'],
			},
			component: () => import(/* webpackChunkName: "List Schedule" */ '@/pages/ShiftManagement/ListSchedule/index.vue'),
		},
		{
			path: 'list-schedule-edit',
			name: 'ListScheduleEdit',
			meta: {
				title: 'ROUTER.LIST_SCHEDULE',
				roles: ['admin'],
			},
			hidden: true,
			component: () => import(/* webpackChunkName: "List Schedule Edit" */ '@/pages/ShiftManagement/ListSchedule/edit.vue'),
		},
	],
};

export default ShiftManagement;
