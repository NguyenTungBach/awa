import store from '@/store';
import router from '@/router';
import UserList from '@/pages/DataManagement/ListUser/index';
import { mount, createLocalVue } from '@vue/test-utils';
import i18n from '@/lang';
import CONSTANT from '@/const';

describe('TEST COMPONENT USER LIST', () => {
	test('Check render page', () => {
		const localVue = createLocalVue();
		const wrapper = mount(UserList, {
			localVue,
			store,
			router,
		});

		const PAGE = wrapper.find('.list-user');
		expect(PAGE.exists()).toBe(true);

		wrapper.destroy();
	});

	test('Check render title page', () => {
		const localVue = createLocalVue();
		const wrapper = mount(UserList, {
			localVue,
			store,
			router,
		});

		const TITLE = wrapper.find('.title-page');
		expect(TITLE.text()).toBe('LIST_USER.TITLE_LIST_USER');

		wrapper.destroy();
	});

	test('Check render button sign-up', () => {
		const localVue = createLocalVue();
		const wrapper = mount(UserList, {
			localVue,
			store,
			router,
		});

		const BUTTON = wrapper.find('div:nth-child(2) > div > button');
		expect(BUTTON.exists()).toBe(true);
		expect(BUTTON.text()).toEqual('APP.BUTTON_SIGN_UP');

		wrapper.destroy();
	});

	test('Check click button sign-up', async() => {
		const goToCreate = jest.fn();

		const localVue = createLocalVue();
		const wrapper = mount(UserList, {
			localVue,
			store,
			router,
			methods: {
				goToCreate,
			},
		});

		const BUTTON = wrapper.find('div:nth-child(2) > div > button');
		await BUTTON.trigger('click');
		expect(goToCreate).toHaveBeenCalled();

		wrapper.destroy();
	});

	test('Check render header table', () => {
		const localVue = createLocalVue();
		const wrapper = mount(UserList, {
			localVue,
			store,
			router,
		});

		const CONST_HEADER = [
			{ key: 'user_code', label: 'LIST_USER.USER_ID', sortable: true, thClass: 'base-th' },
			{ key: 'user_name', label: 'LIST_USER.USER_NAME', sortable: true, thClass: 'base-th' },
			{ key: 'role', label: 'LIST_USER.USER_AUTHORITY', sortable: true, thClass: 'base-th' },
			{ key: 'detail', label: 'LIST_USER.DETAIL', sortable: false, thClass: 'text-center base-th base-th-control', tdClass: 'text-center base-td-control' },
			{ key: 'delete', label: 'LIST_USER.DELETE', sortable: false, thClass: 'text-center base-th base-th-control', tdClass: 'text-center base-td-control' },
		];

		// Kiểm tra trường
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
				'user_code': '1122',
				'user_name': 'Super Admin',
				'role': 'admin',
				'jwt_active': null,
				'remember_token': null,
				'created_at': 1660556001,
				'updated_at': 1660556001,
			},
			{
				'id': 2,
				'user_code': '2233',
				'user_name': 'Member Drive',
				'role': 'driver',
				'jwt_active': null,
				'remember_token': null,
				'created_at': 1660556001,
				'updated_at': 1660556001,
			},
		];

		const localVue = createLocalVue();
		const wrapper = mount(UserList, {
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

			const COLUMN_USER_CODE = COLUMNS.at(0);
			expect(COLUMN_USER_CODE.text()).toEqual(DATA[idx].user_code);
			const COLUMN_USER_NAME = COLUMNS.at(1);
			expect(COLUMN_USER_NAME.text()).toEqual(DATA[idx].user_name);
			const COLUMN_USER_AUTHORITY = COLUMNS.at(2);

			if (DATA[idx].role === 'admin') {
				expect(COLUMN_USER_AUTHORITY.text()).toEqual(i18n.t(CONSTANT.LIST_USER.TEXT_ROLE_SYSTEM_ADMINISTRATOR));
			} else {
				expect(COLUMN_USER_AUTHORITY.text()).toEqual(i18n.t(CONSTANT.LIST_USER.TEXT_ROLE_DRIVER));
			}

			idx++;
		}

		wrapper.destroy();
	});

	test('Check render button detail', () => {
		const DATA = [
			{
				'id': 1,
				'user_code': '1122',
				'user_name': 'Super Admin',
				'role': 'admin',
				'jwt_active': null,
				'remember_token': null,
				'created_at': 1660556001,
				'updated_at': 1660556001,
			},
			{
				'id': 2,
				'user_code': '2233',
				'user_name': 'Member Drive',
				'role': 'driver',
				'jwt_active': null,
				'remember_token': null,
				'created_at': 1660556001,
				'updated_at': 1660556001,
			},
		];

		const localVue = createLocalVue();
		const wrapper = mount(UserList, {
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

			const COLUMN_DETAIL = COLUMNS.at(3);
			expect(COLUMN_DETAIL.find('i').exists()).toBe(true);

			idx++;
		}

		wrapper.destroy();
	});

	test('Check render button delete', () => {
		const DATA = [
			{
				'id': 1,
				'user_code': '1122',
				'user_name': 'Super Admin',
				'role': 'admin',
				'jwt_active': null,
				'remember_token': null,
				'created_at': 1660556001,
				'updated_at': 1660556001,
			},
			{
				'id': 2,
				'user_code': '2233',
				'user_name': 'Member Drive',
				'role': 'driver',
				'jwt_active': null,
				'remember_token': null,
				'created_at': 1660556001,
				'updated_at': 1660556001,
			},
		];

		const localVue = createLocalVue();
		const wrapper = mount(UserList, {
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

			const COLUMN_DELETE = COLUMNS.at(4);
			expect(COLUMN_DELETE.find('i').exists()).toBe(true);

			idx++;
		}

		wrapper.destroy();
	});

	test('Check click button detail', async() => {
		const DATA = [
			{
				'id': 1,
				'user_code': '1122',
				'user_name': 'Super Admin',
				'role': 'admin',
				'jwt_active': null,
				'remember_token': null,
				'created_at': 1660556001,
				'updated_at': 1660556001,
			},
			{
				'id': 2,
				'user_code': '2233',
				'user_name': 'Member Drive',
				'role': 'driver',
				'jwt_active': null,
				'remember_token': null,
				'created_at': 1660556001,
				'updated_at': 1660556001,
			},
		];

		const goToDetail = jest.fn();

		const localVue = createLocalVue();
		const wrapper = mount(UserList, {
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

			const COLUMN_DETAIL = COLUMNS.at(3);
			const BUTTON_DETAIL = COLUMN_DETAIL.find('i');
			expect(BUTTON_DETAIL.exists()).toBe(true);

			await BUTTON_DETAIL.trigger('click');

			expect(goToDetail).toHaveBeenCalled();

			idx++;
		}

		wrapper.destroy();
	});

	test('Check click button delete', async() => {
		const DATA = [
			{
				'id': 1,
				'user_code': '1122',
				'user_name': 'Super Admin',
				'role': 'admin',
				'jwt_active': null,
				'remember_token': null,
				'created_at': 1660556001,
				'updated_at': 1660556001,
			},
			{
				'id': 2,
				'user_code': '2233',
				'user_name': 'Member Drive',
				'role': 'driver',
				'jwt_active': null,
				'remember_token': null,
				'created_at': 1660556001,
				'updated_at': 1660556001,
			},
		];

		const handleDelete = jest.fn();

		const localVue = createLocalVue();
		const wrapper = mount(UserList, {
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

			const COLUMN_DELETE = COLUMNS.at(4);
			expect(COLUMN_DELETE.find('i').exists()).toBe(true);
			const BUTTON_DELETE = COLUMN_DELETE.find('i');
			expect(BUTTON_DELETE.exists()).toBe(true);

			await BUTTON_DELETE.trigger('click');

			expect(handleDelete).toHaveBeenCalled();

			idx++;
		}

		wrapper.destroy();
	});
});
