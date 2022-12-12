import store from '@/store';
import router from '@/router';
import ListDayOff from '@/pages/ShiftManagement/ListDayOff/index';
import { mount, createLocalVue } from '@vue/test-utils';
import data from './data';
import NodeDayOff from '@/components/NodeDayOff';

describe('TEST COMPONENT DAY OFF LIST', () => {
	test('Check render page', () => {
		const localVue = createLocalVue();
		const wrapper = mount(ListDayOff, {
			localVue,
			store,
			router,
		});

		const PAGE = wrapper.find('.page-day-off');
		expect(PAGE.exists()).toBe(true);

		wrapper.destroy();
	});

	test('Check render title page', () => {
		const localVue = createLocalVue();
		const wrapper = mount(ListDayOff, {
			localVue,
			store,
			router,
		});

		const TITLE = wrapper.find('.title-page');
		expect(TITLE.text()).toBe('DAY_OFF.TITLE_LIST_DAY_OFF');

		wrapper.destroy();
	});

	test('Check render button edit', () => {
		const localVue = createLocalVue();
		const wrapper = mount(ListDayOff, {
			localVue,
			store,
			router,
		});

		const BUTTON = wrapper.find('.btn-edit');
		expect(BUTTON.exists()).toBe(true);
		expect(BUTTON.text()).toEqual('APP.BUTTON_EDIT');

		wrapper.destroy();
	});

	test('Check click button edit', async() => {
		const goToDayOffEdit = jest.fn();

		const localVue = createLocalVue();
		const wrapper = mount(ListDayOff, {
			localVue,
			store,
			router,
			methods: {
				goToDayOffEdit,
			},
		});

		const BUTTON = wrapper.find('.btn-edit');
		await BUTTON.trigger('click');
		expect(goToDayOffEdit).toHaveBeenCalled();

		wrapper.destroy();
	});

	test('Check render table header', () => {
		const localVue = createLocalVue();
		const wrapper = mount(ListDayOff, {
			localVue,
			store,
			router,
			data() {
				return {
					listCalendar: data.listCalendar,
				};
			},
		});

		const TABLE = wrapper.find('.zone-table table');
		expect(TABLE.exists()).toBe(true);

		const LIST_HEADER = TABLE.findAll('th');

		const CONST_LIST_HEADER = [
			'',
			'DAY_OFF.TABLE_DATE_EMPLOYEE_NUMBER',
			'DAY_OFF.TABLE_FLAG',
			'DAY_OFF.TABLE_FULL_NAME',
		];

		expect(LIST_HEADER.length).toBe(4);
		for (let i = 0; i < LIST_HEADER.length; i++) {
			expect(LIST_HEADER.at(i).text()).toEqual(CONST_LIST_HEADER[i]);
		}

		wrapper.destroy();
	});

	test('Check render body table', () => {
		const localVue = createLocalVue();
		const wrapper = mount(ListDayOff, {
			localVue,
			store,
			router,
			data() {
				return {
					listCalendar: data.listCalendar,
					ListDayOff: data.ListDayOff,
					numberDate: 0,
				};
			},
		});

		store.dispatch('app/setPickerYearMonth', data.pickerYearMonth)
			.then(() => {
				const TABLE = wrapper.find('.zone-table table');
				expect(TABLE.exists()).toBe(true);

				const LIST_BODY = TABLE.findAll('tbody tr');
				expect(LIST_BODY.length).toBe(5);
			});

		wrapper.destroy();
	});

	test('Check sort by driver_code (Sort up)', async() => {
		const onSortTable = jest.fn();

		const localVue = createLocalVue();
		const wrapper = mount(ListDayOff, {
			localVue,
			store,
			router,
			data() {
				return {
					listCalendar: data.listCalendar,
					ListDayOff: data.ListDayOff,
					numberDate: 0,
				};
			},
			methods: {
				onSortTable,
			},
		});

		store.dispatch('app/setPickerYearMonth', data.pickerYearMonth)
			.then(async() => {
				const BUTTON = wrapper.find('.th-number-employee fad fa-sort-up icon-sort');
				await BUTTON.trigger('click');
				expect(onSortTable('driver_code')).toHaveBeenCalled();

				const CONST_SORT_TABLE = {
					sortBy: 'driver_code',
					sortType: true,
				};

				const SORT_TABLE = wrapper.vm.sortTable;
				expect(SORT_TABLE.exists()).toBe(true);

				expect(SORT_TABLE.sortBy).toEqual(CONST_SORT_TABLE.sortBy);
				expect(SORT_TABLE.sortType).toEqual(CONST_SORT_TABLE.sortType);
			});

		wrapper.destroy();
	});

	test('Check sort by driver_code (Sort dowm)', async() => {
		const onSortTable = jest.fn();

		const localVue = createLocalVue();
		const wrapper = mount(ListDayOff, {
			localVue,
			store,
			router,
			data() {
				return {
					listCalendar: data.listCalendar,
					ListDayOff: data.ListDayOff,
					numberDate: 0,
				};
			},
			methods: {
				onSortTable,
			},
		});

		store.dispatch('app/setPickerYearMonth', data.pickerYearMonth)
			.then(async() => {
				const BUTTON = wrapper.find('.th-number-employee fad fa-sort-down icon-sort');
				await BUTTON.trigger('click');
				expect(onSortTable('driver_code')).toHaveBeenCalled();

				const CONST_SORT_TABLE = {
					sortBy: 'driver_code',
					sortType: false,
				};

				const SORT_TABLE = wrapper.vm.sortTable;
				expect(SORT_TABLE.exists()).toBe(true);

				expect(SORT_TABLE.sortBy).toEqual(CONST_SORT_TABLE.sortBy);
				expect(SORT_TABLE.sortType).toEqual(CONST_SORT_TABLE.sortType);
			});

		wrapper.destroy();
	});

	test('Check sort by driver_code (Sort default)', async() => {
		const onSortTable = jest.fn();

		const localVue = createLocalVue();
		const wrapper = mount(ListDayOff, {
			localVue,
			store,
			router,
			data() {
				return {
					listCalendar: data.listCalendar,
					ListDayOff: data.ListDayOff,
					numberDate: 0,
				};
			},
			methods: {
				onSortTable,
			},
		});

		store.dispatch('app/setPickerYearMonth', data.pickerYearMonth)
			.then(async() => {
				const BUTTON = wrapper.find('.th-number-employee fa-solid fa-sort icon-sort-default');
				await BUTTON.trigger('click');
				expect(onSortTable('driver_code')).toHaveBeenCalled();

				const CONST_SORT_TABLE = {
					sortBy: 'test_driver_code',
					sortType: 'test_type',
				};

				const SORT_TABLE = wrapper.vm.sortTable;
				expect(SORT_TABLE.exists()).toBe(true);

				expect(SORT_TABLE.sortBy).toEqual(CONST_SORT_TABLE.sortBy);
				expect(SORT_TABLE.sortType).toEqual(CONST_SORT_TABLE.sortType);
			});

		wrapper.destroy();
	});

	test('Check sort by flag (Sort up)', async() => {
		const onSortTable = jest.fn();

		const localVue = createLocalVue();
		const wrapper = mount(ListDayOff, {
			localVue,
			store,
			router,
			data() {
				return {
					listCalendar: data.listCalendar,
					ListDayOff: data.ListDayOff,
					numberDate: 0,
				};
			},
			methods: {
				onSortTable,
			},
		});

		store.dispatch('app/setPickerYearMonth', data.pickerYearMonth)
			.then(async() => {
				const BUTTON = wrapper.find('.th-number-employee fad fa-sort-up icon-sort');
				await BUTTON.trigger('click');
				expect(onSortTable('flag')).toHaveBeenCalled();

				const CONST_SORT_TABLE = {
					sortBy: 'flag',
					sortType: true,
				};

				const SORT_TABLE = wrapper.vm.sortTable;
				expect(SORT_TABLE.exists()).toBe(true);

				expect(SORT_TABLE.sortBy).toEqual(CONST_SORT_TABLE.sortBy);
				expect(SORT_TABLE.sortType).toEqual(CONST_SORT_TABLE.sortType);
			});

		wrapper.destroy();
	});

	test('Check sort by flag (Sort dowm)', async() => {
		const onSortTable = jest.fn();

		const localVue = createLocalVue();
		const wrapper = mount(ListDayOff, {
			localVue,
			store,
			router,
			data() {
				return {
					listCalendar: data.listCalendar,
					ListDayOff: data.ListDayOff,
					numberDate: 0,
				};
			},
			methods: {
				onSortTable,
			},
		});

		store.dispatch('app/setPickerYearMonth', data.pickerYearMonth)
			.then(async() => {
				const BUTTON = wrapper.find('.th-number-employee fad fa-sort-down icon-sort');
				await BUTTON.trigger('click');
				expect(onSortTable('flag')).toHaveBeenCalled();

				const CONST_SORT_TABLE = {
					sortBy: 'flag',
					sortType: false,
				};

				const SORT_TABLE = wrapper.vm.sortTable;
				expect(SORT_TABLE.exists()).toBe(true);

				expect(SORT_TABLE.sortBy).toEqual(CONST_SORT_TABLE.sortBy);
				expect(SORT_TABLE.sortType).toEqual(CONST_SORT_TABLE.sortType);
			});

		wrapper.destroy();
	});

	test('Check sort by flag (Sort default)', async() => {
		const onSortTable = jest.fn();

		const localVue = createLocalVue();
		const wrapper = mount(ListDayOff, {
			localVue,
			store,
			router,
			data() {
				return {
					listCalendar: data.listCalendar,
					ListDayOff: data.ListDayOff,
					numberDate: 0,
				};
			},
			methods: {
				onSortTable,
			},
		});

		store.dispatch('app/setPickerYearMonth', data.pickerYearMonth)
			.then(async() => {
				const BUTTON = wrapper.find('.th-number-employee fa-solid fa-sort icon-sort-default');
				await BUTTON.trigger('click');
				expect(onSortTable('flag')).toHaveBeenCalled();

				const CONST_SORT_TABLE = {
					sortBy: 'test_flag',
					sortType: 'test_type',
				};

				const SORT_TABLE = wrapper.vm.sortTable;
				expect(SORT_TABLE.exists()).toBe(true);

				expect(SORT_TABLE.sortBy).toEqual(CONST_SORT_TABLE.sortBy);
				expect(SORT_TABLE.sortType).toEqual(CONST_SORT_TABLE.sortType);
			});

		wrapper.destroy();
	});

	test('Check prop NodeDayOff', async() => {
		const localVue = createLocalVue();
		const wrapper = mount(NodeDayOff, {
			localVue,
			store,
			router,
		});

		const CONST_DATE_EDIT = '';
		const CONST_DRIVER_CODE = '';
		const CONST_IS_EDIT = false;
		const CONST_ITEM_PROPS = {
			'driver_code': '0004',
			'date': '2022-09-26',
			'type': 'D-3',
			'color': '#EDD1A7',
			'status': 'off',
			'lunar_jp': {
				'id': 269,
				'date': '2022-09-26',
				'week': '月',
				'rokuyou': '先負',
				'holiday': null,
			},
		};

		await wrapper.setProps({
			dateEdit: CONST_DATE_EDIT,
			driverCode: CONST_DRIVER_CODE,
			isEdit: CONST_IS_EDIT,
			item: CONST_ITEM_PROPS,
		});

		const DATE_EDIT = wrapper.vm.dateEdit;
		const DRIVER_CODE = wrapper.vm.driverCode;
		const IS_EDIT = wrapper.vm.isEdit;
		const ITEM_PROPS = wrapper.vm.item;

		// console.log(wrapper.vm.item);
		expect(DATE_EDIT).toEqual(CONST_DATE_EDIT);
		expect(DRIVER_CODE).toEqual(CONST_DRIVER_CODE);
		expect(IS_EDIT).toEqual(CONST_IS_EDIT);
		expect(ITEM_PROPS).toEqual(CONST_ITEM_PROPS);

		wrapper.destroy();
	});
});
