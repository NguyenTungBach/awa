import store from '@/store';
import router from '@/router';
import ListShift from '@/pages/ShiftManagement/ListShift/index';
import { mount, createLocalVue } from '@vue/test-utils';
import CONSTANT from '@/const';
import { convertValueToText } from '@/utils/handleSelect';

describe('TEST COMPONENT LIST SHIFT', () => {
	test('Check render page', () => {
		const localVue = createLocalVue();
		const handleGetListCalendar = jest.fn();
		const handleGetListShift = jest.fn();
		// const initData = jest.fn();
		const wrapper = mount(ListShift, {
			localVue,
			store,
			router,
			methods: {
				handleGetListCalendar,
				handleGetListShift,
				// initData,
			},
		});

		const PAGE = wrapper.find('.list-shift');
		expect(PAGE.exists()).toBe(true);

		wrapper.destroy();
	});

	test('Check render title page', () => {
		const localVue = createLocalVue();
		const handleGetListCalendar = jest.fn();
		const handleGetListShift = jest.fn();
		const wrapper = mount(ListShift, {
			localVue,
			store,
			router,
			methods: {
				handleGetListCalendar,
				handleGetListShift,
			},
			data() {
				return {
					selectTable: 'CONSTANT.LIST_SHIFT.HIGHT_WAY_FEE',
					// handleGetListCalendar: jest.fn(),
				};
			},
		});

		const TITLE = wrapper.find('.title-page');
		expect(TITLE.text()).toEqual('LIST_SHIFT.TITLE_LIST_SHIFT_PRACTICAL_RECORD_TABLE');

		wrapper.destroy();
	});

	// test('Check render button choose week or month', () => {
	// 	const localVue = createLocalVue();
	// 	const wrapper = mount(ListShift, {
	// 		localVue,
	// 		store,
	// 		router,
	// 	});
	//
	// 	const BUTTON_WEEK = wrapper.find('.btn-list-shift-week');
	// 	expect(BUTTON_WEEK.exists()).toBe(true);
	// 	expect(BUTTON_WEEK.text()).toEqual('LIST_SHIFT.BUTTON_MONTH');
	//
	// 	const BUTTON_MONTH = wrapper.find('.btn-list-shift-month');
	// 	expect(BUTTON_MONTH.exists()).toBe(true);
	// 	expect(BUTTON_MONTH.text()).toEqual('LIST_SHIFT.BUTTON_WEEK');
	//
	// 	wrapper.destroy();
	// });

	// test('Check click button choose week or month', async() => {
	// 	const onClickSelectWeekMonth = jest.fn();
	// 	const localVue = createLocalVue();
	// 	const wrapper = mount(ListShift, {
	// 		localVue,
	// 		store,
	// 		router,
	// 		methods: {
	// 			onClickSelectWeekMonth,
	// 		},
	// 	});
	//
	// 	const BUTTON_WEEK = wrapper.find('.btn-list-shift-week');
	// 	await BUTTON_WEEK.trigger('click');
	// 	expect(onClickSelectWeekMonth).toHaveBeenCalled();
	//
	// 	const BUTTON_MONTH = wrapper.find('.btn-list-shift-month');
	// 	await BUTTON_MONTH.trigger('click');
	// 	expect(onClickSelectWeekMonth).toHaveBeenCalled();
	//
	// 	wrapper.destroy();
	// });

	// test('Check render button AI', () => {
	// 	const localVue = createLocalVue();
	// 	const wrapper = mount(ListShift, {
	// 		localVue,
	// 		store,
	// 		router,
	// 	});
	//
	// 	const CONTROL = wrapper.find('.zone-right');
	// 	expect(CONTROL.exists()).toBe(true);
	// 	const BUTTON = CONTROL.find('.show-text');
	// 	expect(BUTTON.exists()).toBe(true);
	// 	const TEXT_BUTTON = BUTTON.find('span');
	// 	expect(TEXT_BUTTON.text()).toEqual('LIST_SHIFT.BUTTON_DOWNLOAD_EXCEL');
	//
	// 	wrapper.destroy();
	// });
	//
	// test('Check click button AI', async() => {
	// 	const onExportExcel = jest.fn();
	// 	const localVue = createLocalVue();
	// 	const wrapper = mount(ListShift, {
	// 		localVue,
	// 		store,
	// 		router,
	// 		methods: {
	// 			onExportExcel,
	// 		},
	// 	});
	//
	// 	// const BUTTON = wrapper.find('.fa-robot');
	// 	const CONTROL = wrapper.find('.zone-right');
	// 	expect(CONTROL.exists()).toBe(true);
	// 	const BUTTON = CONTROL.find('.show-text');
	// 	expect(BUTTON.exists()).toBe(true);
	// 	await BUTTON.trigger('click');
	// 	expect(onExportExcel).toHaveBeenCalled();
	//
	// 	wrapper.destroy();
	// });

	// test('Check render button Excel', () => {
	// 	const localVue = createLocalVue();
	// 	const wrapper = mount(ListShift, {
	// 		localVue,
	// 		store,
	// 		router,
	// 	});
	//
	// 	const BUTTON = wrapper.find('.btn-excel');
	// 	expect(BUTTON.exists()).toBe(true);
	// 	expect(BUTTON.text()).toEqual('LIST_SHIFT.BUTTON_DOWNLOAD_EXCEL');
	//
	// 	wrapper.destroy();
	// });

	test('Check click button Excel', async() => {
		const onExportExcel = jest.fn();
		const onClickSelectTable = jest.fn();
		const initData = jest.fn();
		const handleGetListCalendar = jest.fn();
		const handleGetListShift = jest.fn();
		const localVue = createLocalVue();
		const wrapper = mount(ListShift, {
			localVue,
			store,
			router,
			methods: {
				onExportExcel,
				initData,
				onClickSelectTable,
				handleGetListCalendar,
				handleGetListShift,
				// handleGetListCalendar,
				// handleGetListShift,
				// onClickSelectWeekMonth,
			},
			data() {
				return {
					selectTable: 'CONSTANT.LIST_SHIFT.SHIFT_TABLE',
					// handleGetListCalendar: jest.fn(),
				};
			},
			computed: {
				role() {
					return 'admin';
				},
			},
		});

		const BUTTON_LIST = wrapper.find('.list-shift__control');
		expect(BUTTON_LIST.exists()).toEqual(true);
		expect(wrapper).toMatchSnapshot();
		const BUTTON_EXPRESS_CHANGE = wrapper.find('div > div:nth-child(1) > div > div > button:nth-child(2)');
		expect(BUTTON_EXPRESS_CHANGE.exists()).toEqual(true);
		await BUTTON_EXPRESS_CHANGE.trigger('click');
		// expect(wrapper.vm.onClickSelectTable).toHaveBeenCalled();

		// const TITLE = wrapper.find('.title-page');
		// expect(TITLE.text()).toEqual('LIST_SHIFT.TITLE_LIST_SHIFT_PRACTICAL_RECORD_TABLE');

		// console.log('akjgkaj: ', wrapper.vm.selectTable);
		// await wrapper.setData( {selectTable: 'CONSTANT.LIST_SHIFT.HIGHT_WAY_FEE'} );
		// expect(wrapper.vm.selectTable).toBe('CONSTANT.LIST_SHIFT.HIGHT_WAY_FEE');

		expect(wrapper).toMatchSnapshot();

		const BUTTON_WEEK = wrapper.find('.btn-excel');
		await BUTTON_WEEK.trigger('click');
		expect(onExportExcel).toHaveBeenCalled();

		// await BUTTON.trigger('click');
		// expect(onExportExcel).toHaveBeenCalled();

		wrapper.destroy();
	});

	// test('Check render button PDF', () => {
	// 	const localVue = createLocalVue();
	// 	const wrapper = mount(ListShift, {
	// 		localVue,
	// 		store,
	// 		router,
	// 	});

	// 	const CONTROL = wrapper.find('.zone-right');
	// 	expect(CONTROL.exists()).toBe(true);
	// 	const BUTTON = CONTROL.find('.show-text');
	// 	expect(BUTTON.exists()).toBe(true);
	// 	const TEXT_BUTTON = BUTTON.find('span');
	// 	expect(TEXT_BUTTON.text()).toEqual('LIST_SHIFT.BUTTON_DOWNLOAD_EXCEL');

	// 	wrapper.destroy();
	// });

	// test('Check click button PDF', async() => {
	// 	const onExportPDF = jest.fn();
	// 	const localVue = createLocalVue();
	// 	const wrapper = mount(ListShift, {
	// 		localVue,
	// 		store,
	// 		router,
	// 		methods: {
	// 			onExportPDF,
	// 		},
	// 	});

	// 	// const BUTTON_WEEK = wrapper.find('.btn-pdf');
	// 	// await BUTTON_WEEK.trigger('click');
	// 	// expect(onExportPDF).toHaveBeenCalled();
	// 	const CONTROL = wrapper.find('.zone-right');
	// 	expect(CONTROL.exists()).toBe(true);
	// 	const BUTTON = CONTROL.find('.show-text');
	// 	expect(BUTTON.exists()).toBe(true);
	// 	await BUTTON.trigger('click');
	// 	expect(onExportPDF).toHaveBeenCalled();

	// 	wrapper.destroy();
	// });

	test('Check click button choose table', async() => {
		const onExportExcel = jest.fn();
		const handleGetListCalendar = jest.fn();
		const handleGetListShift = jest.fn();
		const localVue = createLocalVue();
		const wrapper = mount(ListShift, {
			localVue,
			store,
			router,
			methods: {
				onExportExcel,
				handleGetListCalendar,
				handleGetListShift,
			},
		});

		const BUTTON_WEEK = wrapper.find('.btn-excel');
		await BUTTON_WEEK.trigger('click');
		expect(onExportExcel).toHaveBeenCalled();

		wrapper.destroy();
	});

	// test('Check render table title date', () => {
	// 	const localVue = createLocalVue();
	// 	const wrapper = mount(ListShift, {
	// 		localVue,
	// 		store,
	// 		router,
	// 	});
	//
	// 	const TABLE = wrapper.find('.zone-table table');
	// 	expect(TABLE.exists()).toBe(true);
	//
	// 	const LIST_HEADER = TABLE.findAll('th');
	//
	// 	// const CONST_LIST_HEADER = [
	// 	// 	'LIST_SHIFT.TABLE_DATE_EMPLOYEE_NUMBER',
	// 	// 	'LIST_SHIFT.TABLE_FLAG',
	// 	// 	'LIST_SHIFT.TABLE_FULL_NAME',
	// 	// ];
	//
	// 	expect(LIST_HEADER.length).toBe(67);
	// 	// for (let i = 0; i < LIST_HEADER.length; i++) {
	// 	// 	expect(LIST_HEADER.at(i).text()).toEqual(CONST_LIST_HEADER[i]);
	// 	// }
	//
	// 	wrapper.destroy();
	// });

	test('Check render body table', () => {
		const DATA = [
			{
				'driver_code': '0004',
				'driver_id': 4,
				'driver_name': 'Associate company',
				'type': 4,
				'typeName': 'associate company',
				'dataShift': {
					'driver_id': 4,
					'data_by_date': [
						{
							'driver_id': 4,
							'date': '2023-08-01',
							'course_ids': '',
							'course_names': '',
							'course_names_color': '',
						},
						{
							'driver_id': 4,
							'date': '2023-08-02',
							'course_ids': '',
							'course_names': '',
							'course_names_color': '',
						},
						{
							'driver_id': 4,
							'date': '2023-08-03',
							'course_ids': '',
							'course_names': '',
							'course_names_color': '',
						},
						{
							'driver_id': 4,
							'date': '2023-08-04',
							'course_ids': '',
							'course_names': '',
							'course_names_color': '',
						},
						{
							'driver_id': 4,
							'date': '2023-08-05',
							'course_ids': '',
							'course_names': '',
							'course_names_color': '',
						},
						{
							'driver_id': 4,
							'date': '2023-08-06',
							'course_ids': '',
							'course_names': '',
							'course_names_color': '',
						},
						{
							'driver_id': 4,
							'date': '2023-08-07',
							'course_ids': '',
							'course_names': '',
							'course_names_color': '',
						},
						{
							'driver_id': 4,
							'date': '2023-08-08',
							'course_ids': '',
							'course_names': '',
							'course_names_color': '',
						},
						{
							'driver_id': 4,
							'date': '2023-08-09',
							'course_ids': '',
							'course_names': '',
							'course_names_color': '',
						},
						{
							'driver_id': 4,
							'date': '2023-08-10',
							'course_ids': '',
							'course_names': '',
							'course_names_color': '',
						},
						{
							'driver_id': 4,
							'date': '2023-08-11',
							'course_ids': '',
							'course_names': '',
							'course_names_color': '',
						},
						{
							'driver_id': 4,
							'date': '2023-08-12',
							'course_ids': '',
							'course_names': '',
							'course_names_color': '',
						},
						{
							'driver_id': 4,
							'date': '2023-08-13',
							'course_ids': '',
							'course_names': '',
							'course_names_color': '',
						},
						{
							'driver_id': 4,
							'date': '2023-08-14',
							'course_ids': '11',
							'course_names': 'Course name 4',
							'course_names_color': '',
						},
						{
							'driver_id': 4,
							'date': '2023-08-15',
							'course_ids': '',
							'course_names': '',
							'course_names_color': '',
						},
						{
							'driver_id': 4,
							'date': '2023-08-16',
							'course_ids': '',
							'course_names': '',
							'course_names_color': '',
						},
						{
							'driver_id': 4,
							'date': '2023-08-17',
							'course_ids': '',
							'course_names': '',
							'course_names_color': '',
						},
						{
							'driver_id': 4,
							'date': '2023-08-18',
							'course_ids': '',
							'course_names': '',
							'course_names_color': '',
						},
						{
							'driver_id': 4,
							'date': '2023-08-19',
							'course_ids': '',
							'course_names': '',
							'course_names_color': '',
						},
						{
							'driver_id': 4,
							'date': '2023-08-20',
							'course_ids': '',
							'course_names': '',
							'course_names_color': '',
						},
						{
							'driver_id': 4,
							'date': '2023-08-21',
							'course_ids': '',
							'course_names': '',
							'course_names_color': '',
						},
						{
							'driver_id': 4,
							'date': '2023-08-22',
							'course_ids': '',
							'course_names': '',
							'course_names_color': '',
						},
						{
							'driver_id': 4,
							'date': '2023-08-23',
							'course_ids': '',
							'course_names': '',
							'course_names_color': '',
						},
						{
							'driver_id': 4,
							'date': '2023-08-24',
							'course_ids': '',
							'course_names': '',
							'course_names_color': '',
						},
						{
							'driver_id': 4,
							'date': '2023-08-25',
							'course_ids': '',
							'course_names': '',
							'course_names_color': '',
						},
						{
							'driver_id': 4,
							'date': '2023-08-26',
							'course_ids': '',
							'course_names': '',
							'course_names_color': '',
						},
						{
							'driver_id': 4,
							'date': '2023-08-27',
							'course_ids': '',
							'course_names': '',
							'course_names_color': '',
						},
						{
							'driver_id': 4,
							'date': '2023-08-28',
							'course_ids': '',
							'course_names': '',
							'course_names_color': '',
						},
						{
							'driver_id': 4,
							'date': '2023-08-29',
							'course_ids': '',
							'course_names': '',
							'course_names_color': '',
						},
						{
							'driver_id': 4,
							'date': '2023-08-30',
							'course_ids': '',
							'course_names': '',
							'course_names_color': '',
						},
						{
							'driver_id': 4,
							'date': '2023-08-31',
							'course_ids': '',
							'course_names': '',
							'course_names_color': '',
						},
					],
				},
				'total_money': '80.00',
			},
		];
		const localVue = createLocalVue();
		const wrapper = mount(ListShift, {
			localVue,
			store,
			router,
			data() {
				return {
					listShift: DATA,
					// handleGetListCalendar: jest.fn(),
					initData: jest.fn(),
				};
			},
		});

		// jest.spyOn(wrapper.vm, 'handleGetListCalendar');

		const ZONE_TABLE = wrapper.find('.zone-table');
		const TABLE = ZONE_TABLE.find('.shift-table');

		// Kiểm tra HEAD table
		const THEAD = TABLE.find('thead');
		expect(THEAD.exists()).toBe(true);

		const TR = THEAD.findAll('tr');
		expect(TR.length).toBe(2);

		const TR_TH = TR.at(1).findAll('th');
		expect(TR_TH.exists()).toBe(true);

		// Kiểm tra tên 3 cột
		expect(TR_TH.at(0).text()).toEqual('LIST_SHIFT.TABLE_DATE_EMPLOYEE_NUMBER');
		expect(TR_TH.at(1).text()).toEqual('LIST_SHIFT.TABLE_FLAG');
		expect(TR_TH.at(2).text()).toEqual('LIST_SHIFT.TABLE_FULL_NAME');

		// Kiểm tra tên Data đổ ra
		const ROWS = TABLE.findAll('tbody tr');
		expect(ROWS.length).toBe(1);

		const len2 = DATA.length;
		let idx2 = 0;

		// kiểm tra 3 cột render đầu tiên
		while (idx2 < len2) {
			const ROW = ROWS.at(idx2);

			const COLUMNS = ROW.findAll('td');

			const COLUMN_driver_code = COLUMNS.at(0);
			expect(COLUMN_driver_code.text()).toEqual(DATA[idx2].driver_code);
			const COLUMN_CUSTOMER_NAME = COLUMNS.at(1);
			expect(COLUMN_CUSTOMER_NAME.text()).toEqual(convertValueToText(CONSTANT.LIST_DRIVER.LIST_FLAG, DATA[idx2].type));
			const COLUMN_Driver_name = COLUMNS.at(2);
			expect(COLUMN_Driver_name.text()).toEqual(DATA[idx2].driver_name);
			idx2++;
		}

		wrapper.destroy();
	});
});
