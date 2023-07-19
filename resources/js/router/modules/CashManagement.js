import Layout from '@/layout';

const CashManagement = {
	path: '/cash-management',
	name: 'CashManagement',
	meta: {
		title: 'ROUTER.CASH_MANAGEMENT',
		roles: ['admin'],
	},
	component: Layout,
	children: [
		{
			path: 'list-cash-receipt',
			name: 'ListCashReceipt',
			meta: {
				title: 'ROUTER.LIST_CASH_RECEIPT',
				roles: ['admin'],
			},
			component: () => import(/* webpackChunkName: "List Shift" */ '@/pages/CashManagement/CashReceipt/index.vue'),
		},
		{
			path: 'list-cash-receipt-create',
			name: 'ListCashReceiptCreate',
			meta: {
				title: 'ROUTER.LIST_CASH_RECEIPT',
				roles: ['admin'],
			},
			hidden: true,
			component: () => import(/* webpackChunkName: "List Shift Edit" */ '@/pages/CashManagement/CashReceipt/create.vue'),
		},
		{
			path: 'list-cash-receipt-detail/:id',
			name: 'ListCashReceiptDetail',
			meta: {
				title: 'ROUTER.LIST_CASH_RECEIPT',
				roles: ['admin'],
			},
			hidden: true,
			component: () => import(/* webpackChunkName: "List Shift Edit" */ '@/pages/CashManagement/CashReceipt/detail.vue'),
		},
		{
			path: 'list-cash-receipt-edit/:id',
			name: 'ListCashReceiptEdit',
			meta: {
				title: 'ROUTER.LIST_CASH_RECEIPT',
				roles: ['admin'],
			},
			hidden: true,
			component: () => import(/* webpackChunkName: "List Shift Edit" */ '@/pages/CashManagement/CashReceipt/edit.vue'),
		},
		// {
		// 	path: 'list-cash-receipt-edit',
		// 	name: 'ListCashReceiptEdit',
		// 	meta: {
		// 		title: 'ROUTER.LIST_CASH_RECEIPT',
		// 		roles: ['admin'],
		// 	},
		// 	hidden: true,
		// 	component: () => import(/* webpackChunkName: "List Shift Edit" */ '@/pages/CashManagement/CashReceipt/edit.vue'),
		// },
		{
			path: 'list-cash-disbursement',
			name: 'ListCashDisbursement',
			meta: {
				title: 'ROUTER.LIST_CASH_DISBURSEMENT',
				roles: ['admin'],
			},
			component: () => import(/* webpackChunkName: "List Schedule" */ '@/pages/CashManagement/CashDisbursement/index.vue'),
		},
		{
			path: 'list-cash-disbursement-create',
			name: 'ListCashDisbursementCreate',
			meta: {
				title: 'ROUTER.LIST_CASH_DISBURSEMENT',
				roles: ['admin'],
			},
			hidden: true,
			component: () => import(/* webpackChunkName: "List Schedule Edit" */ '@/pages/CashManagement/CashDisbursement/create.vue'),
		},
		{
			path: 'list-cash-disbursement-detail/:id',
			name: 'ListCashDisbursementDetail',
			meta: {
				title: 'ROUTER.LIST_CASH_DISBURSEMENT',
				roles: ['admin'],
			},
			hidden: true,
			component: () => import(/* webpackChunkName: "List Schedule Edit" */ '@/pages/CashManagement/CashDisbursement/detail.vue'),
		},
		{
			path: 'list-cash-disbursement-edit/:id',
			name: 'ListCashDisbursementEdit',
			meta: {
				title: 'ROUTER.LIST_CASH_DISBURSEMENT',
				roles: ['admin'],
			},
			hidden: true,
			component: () => import(/* webpackChunkName: "List Schedule Edit" */ '@/pages/CashManagement/CashDisbursement/edit.vue'),
		},
	],
};

export default CashManagement;
