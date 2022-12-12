import store from '@/store';
import router from '@/router';
import ListDayOffEdit from '@/pages/ShiftManagement/ListDayOff/edit';
import { mount, createLocalVue } from '@vue/test-utils';
import data from './data';
import NodeDayOff from '@/components/NodeDayOff';

describe('TEST COMPONENT DAY OFF LIST', () => {
	test('Check render page', () => {
		const localVue = createLocalVue();
		const wrapper = mount(ListDayOffEdit, {
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
		const wrapper = mount(ListDayOffEdit, {
			localVue,
			store,
			router,
		});

		const TITLE = wrapper.find('.title-page');
		expect(TITLE.text()).toBe('DAY_OFF.TITLE_LIST_DAY_OFF');

		wrapper.destroy();
	});

	test('Check render button return', () => {
		const localVue = createLocalVue();
		const wrapper = mount(ListDayOffEdit, {
			localVue,
			store,
			router,
		});

		const BUTTON = wrapper.find('.btn-return');
		expect(BUTTON.exists()).toBe(true);
		expect(BUTTON.text()).toEqual('APP.BUTTON_RETURN');

		wrapper.destroy();
	});

	test('Check click button return', async() => {
		const goToDayOffIndex = jest.fn();

		const localVue = createLocalVue();
		const wrapper = mount(ListDayOffEdit, {
			localVue,
			store,
			router,
			methods: {
				goToDayOffIndex,
			},
		});

		const BUTTON = wrapper.find('.btn-return');
		await BUTTON.trigger('click');
		expect(goToDayOffIndex).toHaveBeenCalled();

		wrapper.destroy();
	});

	test('Check render button save', () => {
		const localVue = createLocalVue();
		const wrapper = mount(ListDayOffEdit, {
			localVue,
			store,
			router,
		});

		const BUTTON = wrapper.find('.btn-save');
		expect(BUTTON.exists()).toBe(true);
		expect(BUTTON.text()).toEqual('APP.BUTTON_SAVE');

		wrapper.destroy();
	});

	test('Check click button save', async() => {
		const onClickSave = jest.fn();

		const localVue = createLocalVue();
		const wrapper = mount(ListDayOffEdit, {
			localVue,
			store,
			router,
			methods: {
				onClickSave,
			},
		});

		const BUTTON = wrapper.find('.btn-save');
		await BUTTON.trigger('click');
		expect(onClickSave).toHaveBeenCalled();

		wrapper.destroy();
	});

	test('Check render header table', () => {
		const localVue = createLocalVue();
		const wrapper = mount(ListDayOffEdit, {
			localVue,
			store,
			router,
			data() {
				return {
					listCalendar: data.listCalendar,
				};
			},
		});

		const DRIVER_CODE_HEADER = wrapper.find('.th-number-employee');
		expect(DRIVER_CODE_HEADER.exists()).toBe(true);

		const FLAG_HEADER = wrapper.find('.th-type-employee');
		expect(FLAG_HEADER.exists()).toBe(true);

		const NAME_HEADER = wrapper.find('.th-employee-name');
		expect(NAME_HEADER.exists()).toBe(true);

		const CONST_DRIVER_CODE_HEADER = 'DAY_OFF.TABLE_DATE_EMPLOYEE_NUMBER';
		const CONST_FLAG_HEADER = 'DAY_OFF.TABLE_FLAG';
		const CONST_NAME_HEADER = 'DAY_OFF.TABLE_FULL_NAME';

		expect(DRIVER_CODE_HEADER.text()).toEqual(CONST_DRIVER_CODE_HEADER);
		expect(FLAG_HEADER.text()).toEqual(CONST_FLAG_HEADER);
		expect(NAME_HEADER.text()).toEqual(CONST_NAME_HEADER);

		wrapper.destroy();
	});

	test('Check render body table', () => {
		const localVue = createLocalVue();
		const wrapper = mount(ListDayOffEdit, {
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
		const wrapper = mount(ListDayOffEdit, {
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
		const wrapper = mount(ListDayOffEdit, {
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
		const wrapper = mount(ListDayOffEdit, {
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
		const wrapper = mount(ListDayOffEdit, {
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
		const wrapper = mount(ListDayOffEdit, {
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
		const wrapper = mount(ListDayOffEdit, {
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

		const CONST_DATE_EDIT = '2022-09-26';
		const CONST_DRIVER_CODE = '0004';
		const CONST_IS_EDIT = true;
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

	test('Check render model edit', () => {
		const localVue = createLocalVue();
		const wrapper = mount(ListDayOffEdit, {
			localVue,
			store,
			router,
		});

		const PAGE = wrapper.find('.modal-edit-node');
		expect(PAGE.exists()).toBe(true);

		wrapper.destroy();
	});

	test('Check render title model', () => {
		const localVue = createLocalVue();
		const wrapper = mount(ListDayOffEdit, {
			localVue,
			store,
			router,
		});

		const TITLE = wrapper.find('.modal-title');
		expect(TITLE.text()).toBe('DAY_OFF.MODAL_CHANGE_STATUS_DATE');

		wrapper.destroy();
	});

	test('Check render body model', () => {
		const localVue = createLocalVue();
		const wrapper = mount(ListDayOffEdit, {
			localVue,
			store,
			router,
			data() {
				return {
					listTypeDayOff: [
						{
							value: 'D-5',
							text: 'DAY_OFF.TABLE_DEFAULT',
						},
						{
							value: 'D-2',
							text: 'DAY_OFF.TABLE_DATE_FIXED_DAY_OFF',
						},
						{
							value: 'D-3',
							text: 'DAY_OFF.TABLE_DATE_DAY_OFF_REQUEST',
						},
						{
							value: 'D-4',
							text: 'DAY_OFF.TABLE_DATE_PAID',
						},
					],
					showModal: true,
				};
			},
		});

		const CONST_SELECT_DAY_OFF = [
			'DAY_OFF.TABLE_DEFAULT',
			'DAY_OFF.TABLE_DATE_FIXED_DAY_OFF',
			'DAY_OFF.TABLE_DATE_DAY_OFF_REQUEST',
			'DAY_OFF.TABLE_DATE_PAID',
		];

		const SELECT_DAY_OFF = wrapper.find('.zone-select-day-off');
		expect(SELECT_DAY_OFF.exists()).toBe(true);

		const LIST_SELECT_DAY_OFF = SELECT_DAY_OFF.findAll('.custom-radio');
		expect(LIST_SELECT_DAY_OFF.length).toBe(4);

		console.log(wrapper.html());
		console.log(SELECT_DAY_OFF);

		let idx = 0;
		const len = LIST_SELECT_DAY_OFF.length;

		while (idx < len) {
			expect(LIST_SELECT_DAY_OFF.at(idx).find('label').text()).toEqual(CONST_SELECT_DAY_OFF[idx]);

			idx++;
		}

		wrapper.destroy();
	});

	test('Check render button model save', () => {
		const localVue = createLocalVue();
		const wrapper = mount(ListDayOffEdit, {
			localVue,
			store,
			router,
		});

		const BUTTON = wrapper.find('.zone-save .btn-save');
		expect(BUTTON.exists()).toBe(true);
		expect(BUTTON.text()).toEqual('APP.BUTTON_SAVE');

		wrapper.destroy();
	});

	test('Check click button model save', async() => {
		const handleSaveModal = jest.fn();

		const localVue = createLocalVue();
		const wrapper = mount(ListDayOffEdit, {
			localVue,
			store,
			router,
			methods: {
				handleSaveModal,
			},
		});

		const BUTTON = wrapper.find('.zone-save .btn-save');
		await BUTTON.trigger('click');
		expect(handleSaveModal).toHaveBeenCalled();

		wrapper.destroy();
	});
});
