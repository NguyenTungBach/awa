import store from '@/store';
import router from '@/router';
import DriverList from '@/pages/DataManagement/ListDriver/index';
import { mount, createLocalVue } from '@vue/test-utils';

describe('TEST COMPONENT DRIVER LIST', () => {
	test('Check render page', () => {
		const localVue = createLocalVue();
		const wrapper = mount(DriverList, {
			localVue,
			store,
			router,
		});

		const PAGE = wrapper.find('.list-driver');
		expect(PAGE.exists()).toBe(true);

		wrapper.destroy();
	});

	test('Check render title page', () => {
		const localVue = createLocalVue();
		const wrapper = mount(DriverList, {
			localVue,
			store,
			router,
		});

		const TITLE = wrapper.find('.title-page');
		expect(TITLE.text()).toBe('LIST_DRIVER.TITLE_LIST_DRIVER');

		wrapper.destroy();
	});

	test('Check render button sign-up', () => {
		const localVue = createLocalVue();
		const wrapper = mount(DriverList, {
			localVue,
			store,
			router,
		});

		const BUTTON = wrapper.find('div:nth-child(2) > div > button');
		expect(BUTTON.exists()).toBe(true);
		expect(BUTTON.text()).toEqual('LIST_DRIVER.BUTTON_SIGN_UP');

		wrapper.destroy();
	});

	test('Check click button sign-up', async() => {
		const goToCreateDriver = jest.fn();

		const localVue = createLocalVue();
		const wrapper = mount(DriverList, {
			localVue,
			store,
			router,
			methods: {
				goToCreateDriver,
			},
		});

		const BUTTON = wrapper.find('div:nth-child(2) > div > button');
		await BUTTON.trigger('click');
		expect(goToCreateDriver).toHaveBeenCalled();

		wrapper.destroy();
	});

	test('Check render header table', () => {
		const localVue = createLocalVue();
		const wrapper = mount(DriverList, {
			localVue,
			store,
			router,
		});

		// const CONST_HEADER = [
		// 	{ key: 'driver_code', label: 'LIST_DRIVER.TABLE_TITLE_EMPLOYEE_NUMBER', sortable: true, thClass: 'base-th th-employee-number' },
		// 	{ key: 'driver_name', label: 'LIST_DRIVER.TABLE_TITLE_FULL_NAME', sortable: true, thClass: 'base-th' },
		// 	{ key: 'status', label: 'LIST_DRIVER.TABLE_TITLE_ENROLLMENT_STATUS', sortable: true, thClass: 'base-th text-center th-enrollment', tdClass: 'text-center' },
		// 	{ key: 'detail', label: 'LIST_DRIVER.TABLE_TITLE_DETAIL', sortable: false, thClass: 'text-center base-th base-th-control', tdClass: 'text-center base-td-control' },
		// 	{ key: 'delete', label: 'LIST_DRIVER.TABLE_TITLE_DELETE', sortable: false, thClass: 'text-center base-th base-th-control', tdClass: 'text-center base-td-control' },
		// ];

		const CONST_HEADER = [
			{ key: 'driver_code', label: 'LIST_DRIVER.TABLE_TITLE_EMPLOYEE_NUMBER', sortable: true, thClass: 'base-th th-employee-number' },
			{ key: 'typeName', label: 'LIST_DRIVER.TABLE_TITLE_TYPE_NAME', sortable: true, thClass: 'base-th th-type-name' },
			{ key: 'driver_name', label: 'LIST_DRIVER.TABLE_TITLE_FULL_NAME', sortable: true, thClass: 'base-th th-driver-name' },
			{ key: 'status', label: 'LIST_DRIVER.TABLE_TITLE_ENROLLMENT_STATUS', sortable: true, thClass: 'base-th text-center th-enrollment', tdClass: 'text-center' },
			{ key: 'detail', label: 'LIST_DRIVER.TABLE_TITLE_DETAIL', sortable: false, thClass: 'text-center base-th base-th-control', tdClass: 'text-center base-td-control' },
			{ key: 'delete', label: 'LIST_DRIVER.TABLE_TITLE_DELETE', sortable: false, thClass: 'text-center base-th base-th-control', tdClass: 'text-center base-td-control' },
		];

		const HEADER = wrapper.vm.fields;

		let idx = 0;
		const len = HEADER.length;

		while (idx < len) {
			expect(HEADER[idx].key).toEqual(CONST_HEADER[idx].key);
			expect(HEADER[idx].label).toEqual(CONST_HEADER[idx].label);
			expect(HEADER[idx].sortable).toEqual(CONST_HEADER[idx].sortable);
			expect(HEADER[idx].thClass).toEqual(CONST_HEADER[idx].thClass);
			expect(HEADER[idx].tdClass).toEqual(CONST_HEADER[idx].tdClass);

			idx++;
		}

		wrapper.destroy();
	});

	test('Check render body table', () => {
		const DATA = [
			{
				'id': 1,
				'driver_code': '0001',
				'driver_name': 'tuan minh',
				'typeName': 'leader',
				'status': 'on',
				'start_date': '2022-08-15',
				'end_date': null,
				'created_at': 1660729751,
				'updated_at': 1660729751,
			},
			{
				'id': 2,
				'driver_code': '0002',
				'driver_name': 'veho',
				'typeName': 'full-day',
				'status': 'on',
				'start_date': '2022-08-15',
				'end_date': null,
				'created_at': 1660729751,
				'updated_at': 1660729751,
			},
			{
				'id': 3,
				'driver_code': '0003',
				'driver_name': 'veho',
				'typeName': 'haft-day',
				'status': 'off',
				'start_date': '2022-08-15',
				'end_date': '2022-09-15',
				'created_at': 1660729751,
				'updated_at': 1660729751,
			},
		];

		const localVue = createLocalVue();
		const wrapper = mount(DriverList, {
			localVue,
			store,
			router,
			data() {
				return {
					items: DATA,
				};
			},
		});
		jest.spyOn(wrapper.vm, 'initData');

		const ZONE_TABLE = wrapper.find('.zone-table');
		const TABLE = ZONE_TABLE.find('table');
		const BODY = TABLE.find('tbody');

		const ROWS = BODY.findAll('tr');

		const len = DATA.length;
		let idx = 0;

		while (idx < len) {
			const ROW = ROWS.at(idx);

			const COLUMNS = ROW.findAll('td');

			const COLUMN_USER_CODE = COLUMNS.at(0);
			expect(COLUMN_USER_CODE.text()).toEqual(DATA[idx].driver_code);
			const COLUMN_TYPE_NAME = COLUMNS.at(1);
			expect(COLUMN_TYPE_NAME.text()).toEqual(DATA[idx].typeName);
			const COLUMN_USER_NAME = COLUMNS.at(2);
			expect(COLUMN_USER_NAME.text()).toEqual(DATA[idx].driver_name);
			// const COLUMN_USER_AUTHORITY = COLUMNS.at(2);
			//
			// if (DATA[idx].status === 'on') {
			// 	expect(COLUMN_USER_AUTHORITY.text()).toEqual('LIST_DRIVER.ENROLLMENT_STATUS_RETIRED');
			// } else {
			// 	expect(COLUMN_USER_AUTHORITY.text()).toEqual('LIST_DRIVER.ENROLLMENT_STATUS_ENROLLED');
			// }

			idx++;
		}

		wrapper.destroy();
	});

	test('Check function check flag to change color row', () => {
		const localVue = createLocalVue();
		const wrapper = mount(DriverList, {
			localVue,
			store,
			router,
		});

		// expect(wrapper.vm.rowClass()).toEqual('');
		// expect(wrapper.vm.rowClass('veho')).toEqual('');
		const today = new Date();
		expect(wrapper.vm.rowClass({
			'id': 3,
			'driver_code': '0003',
			'driver_name': 'veho',
			'typeName': 'haft-day',
			'status': 'on',
			'start_date': '2022-08-15',
			'end_date': null,
			'created_at': 1660729751,
			'updated_at': 1660729751,
		})).toEqual('');
		expect(wrapper.vm.rowClass({
			'id': 3,
			'driver_code': '0003',
			'driver_name': 'veho',
			'typeName': 'haft-day',
			'status': 'off',
			'start_date': '2022-08-15',
			'end_date': today,
			'created_at': 1660729751,
			'updated_at': 1660729751,
		})).toEqual('employee-retired');

		wrapper.destroy();
	});

	test('Check function sort table', async() => {
		const handleSort = jest.fn();

		const localVue = createLocalVue();
		const wrapper = mount(DriverList, {
			localVue,
			store,
			router,
			methods: {
				handleSort,
			},
		});

		const HEADER_TABLE = wrapper.find('.zone-table thead');

		const LIST_TH = HEADER_TABLE.findAll('th');

		await LIST_TH.at(0).trigger('click');
		expect(handleSort).toHaveBeenCalled();

		await LIST_TH.at(1).trigger('click');
		expect(handleSort).toHaveBeenCalled();

		await LIST_TH.at(2).trigger('click');
		expect(handleSort).toHaveBeenCalled();

		wrapper.destroy();
	});

	test('Check render button detail', () => {
		const today = new Date();
		const DATA = [
			{
				'id': 1,
				'driver_code': '0001',
				'driver_name': 'tuan minh',
				'typeName': 'leader',
				'status': 'on',
				'start_date': '2022-08-15',
				'end_date': null,
				'created_at': 1660729751,
				'updated_at': 1660729751,
			},
			{
				'id': 2,
				'driver_code': '0002',
				'driver_name': 'veho',
				'typeName': 'full-day',
				'status': 'on',
				'start_date': '2022-08-15',
				'end_date': null,
				'created_at': 1660729751,
				'updated_at': 1660729751,
			},
			{
				'id': 3,
				'driver_code': '0003',
				'driver_name': 'veho',
				'typeName': 'haft-day',
				'status': 'off',
				'start_date': '2022-08-15',
				'end_date': today,
				'created_at': 1660729751,
				'updated_at': 1660729751,
			},
		];

		const localVue = createLocalVue();
		const wrapper = mount(DriverList, {
			localVue,
			store,
			router,
			data() {
				return {
					items: DATA,
				};
			},
		});

		const ZONE_TABLE = wrapper.find('.zone-table');
		const TABLE = ZONE_TABLE.find('table');
		const BODY = TABLE.find('tbody');

		const ROWS = BODY.findAll('tr');

		const len = DATA.length;
		let idx = 0;

		while (idx < len) {
			const ROW = ROWS.at(idx);

			const COLUMNS = ROW.findAll('td');

			const COLUMN_DETAIL = COLUMNS.at(4);
			expect(COLUMN_DETAIL.find('i').exists()).toBe(true);

			idx++;
		}

		wrapper.destroy();
	});

	test('Check click button detail', async() => {
		const DATA = [
			{
				'id': 1,
				'driver_code': '0001',
				'driver_name': 'tuan minh',
				'typeName': 'leader',
				'status': 'on',
				'start_date': '2022-08-15',
				'end_date': null,
				'created_at': 1660729751,
				'updated_at': 1660729751,
			},
			{
				'id': 2,
				'driver_code': '0002',
				'driver_name': 'veho',
				'typeName': 'full-day',
				'status': 'on',
				'start_date': '2022-08-15',
				'end_date': null,
				'created_at': 1660729751,
				'updated_at': 1660729751,
			},
			{
				'id': 3,
				'driver_code': '0003',
				'driver_name': 'veho',
				'typeName': 'haft-day',
				'status': 'off',
				'start_date': '2022-08-15',
				'end_date': '2022-09-15',
				'created_at': 1660729751,
				'updated_at': 1660729751,
			},
		];

		const goToDetail = jest.fn();

		const localVue = createLocalVue();
		const wrapper = mount(DriverList, {
			localVue,
			store,
			router,
			data() {
				return {
					items: DATA,
				};
			},
			methods: {
				goToDetail,
			},
		});

		const ZONE_TABLE = wrapper.find('.zone-table');
		const TABLE = ZONE_TABLE.find('table');
		const BODY = TABLE.find('tbody');

		const ROWS = BODY.findAll('tr');

		const len = DATA.length;
		let idx = 0;

		while (idx < len) {
			const ROW = ROWS.at(idx);

			const COLUMNS = ROW.findAll('td');

			const COLUMN_DETAIL = COLUMNS.at(4);
			const BUTTON_DETAIL = COLUMN_DETAIL.find('i');
			expect(BUTTON_DETAIL.exists()).toBe(true);

			await BUTTON_DETAIL.trigger('click');

			expect(goToDetail).toHaveBeenCalled();

			idx++;
		}

		// store.dispatch('login/saveProfile', {
		// 	'id': 1,
		// 	'user_code': '1122',
		// 	'user_name': 'Super Admin',
		// 	'role': 'admin',
		// }).then(async() => {
		// 	store.dispatch('permissions/generateRoutes', { roles: ['admin'], permissions: [] })
		// 		.then(async(res) => {
		// 			addRoutes(res);
		//
		// 			const ZONE_TABLE = wrapper.find('.zone-table');
		// 			const TABLE = ZONE_TABLE.find('table');
		// 			const BODY = TABLE.find('tbody');
		//
		// 			const ROWS = BODY.findAll('tr');
		//
		// 			const len = DATA.length;
		// 			let idx = 0;
		//
		// 			while (idx < len) {
		// 				const ROW = ROWS.at(idx);
		//
		// 				const COLUMNS = ROW.findAll('td');
		//
		// 				const COLUMN_DETAIL = COLUMNS.at(3);
		// 				await COLUMN_DETAIL.find('i').trigger('click');
		// 				expect(goToDetail).toHaveBeenCalled();
		//
		// 				idx++;
		// 			}
		//
		// 			wrapper.destroy();
		// 		})
		// 		.catch(() => {
		// 			wrapper.destroy();
		// 		});
		// }).catch(() => {
		// 	wrapper.destroy();
		// });
	});

	test('Check render button delete', () => {
		const DATA = [
			{
				'id': 1,
				'driver_code': '0001',
				'driver_name': 'tuan minh',
				'typeName': 'leader',
				'status': 'on',
				'start_date': '2022-08-15',
				'end_date': null,
				'created_at': 1660729751,
				'updated_at': 1660729751,
			},
			{
				'id': 2,
				'driver_code': '0002',
				'driver_name': 'veho',
				'typeName': 'full-day',
				'status': 'on',
				'start_date': '2022-08-15',
				'end_date': null,
				'created_at': 1660729751,
				'updated_at': 1660729751,
			},
			{
				'id': 3,
				'driver_code': '0003',
				'driver_name': 'veho',
				'typeName': 'haft-day',
				'status': 'off',
				'start_date': '2022-08-15',
				'end_date': '2022-09-15',
				'created_at': 1660729751,
				'updated_at': 1660729751,
			},
		];

		const localVue = createLocalVue();
		const wrapper = mount(DriverList, {
			localVue,
			store,
			router,
			data() {
				return {
					items: DATA,
				};
			},
		});

		const ZONE_TABLE = wrapper.find('.zone-table');
		const TABLE = ZONE_TABLE.find('table');
		const BODY = TABLE.find('tbody');

		const ROWS = BODY.findAll('tr');

		const len = DATA.length;
		let idx = 0;

		while (idx < len) {
			const ROW = ROWS.at(idx);

			const COLUMNS = ROW.findAll('td');

			const COLUMN_DELETE = COLUMNS.at(5);
			expect(COLUMN_DELETE.find('i').exists()).toBe(true);

			idx++;
		}

		wrapper.destroy();
	});

	test('Check click button delete', async() => {
		const DATA = [
			{
				'id': 1,
				'driver_code': '0001',
				'driver_name': 'tuan minh',
				'typeName': 'leader',
				'status': 'on',
				'start_date': '2022-08-15',
				'end_date': null,
				'created_at': 1660729751,
				'updated_at': 1660729751,
			},
			{
				'id': 2,
				'driver_code': '0002',
				'driver_name': 'veho',
				'typeName': 'full-day',
				'status': 'on',
				'start_date': '2022-08-15',
				'end_date': null,
				'created_at': 1660729751,
				'updated_at': 1660729751,
			},
			{
				'id': 3,
				'driver_code': '0003',
				'driver_name': 'veho',
				'typeName': 'haft-day',
				'status': 'off',
				'start_date': '2022-08-15',
				'end_date': '2022-09-15',
				'created_at': 1660729751,
				'updated_at': 1660729751,
			},
		];

		const handleDelete = jest.fn();

		const localVue = createLocalVue();
		const wrapper = mount(DriverList, {
			localVue,
			store,
			router,
			data() {
				return {
					items: DATA,
				};
			},
			methods: {
				handleDelete,
			},
		});

		const ZONE_TABLE = wrapper.find('.zone-table');
		const TABLE = ZONE_TABLE.find('table');
		const BODY = TABLE.find('tbody');

		const ROWS = BODY.findAll('tr');

		const len = DATA.length;
		let idx = 0;

		while (idx < len) {
			const ROW = ROWS.at(idx);

			const COLUMNS = ROW.findAll('td');

			const COLUMN_DELETE = COLUMNS.at(5);
			await COLUMN_DELETE.find('i').trigger('click');
			expect(handleDelete).toHaveBeenCalled();

			idx++;
		}

		wrapper.destroy();
	});
});
