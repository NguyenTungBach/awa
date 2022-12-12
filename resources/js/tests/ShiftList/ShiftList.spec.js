import store from '@/store';
import router from '@/router';
import ListShift from '@/pages/ShiftManagement/ListShift/index';
import { mount, createLocalVue } from '@vue/test-utils';

describe('TEST COMPONENT LIST SHIFT', () => {
	test('Check render page', () => {
		const localVue = createLocalVue();
		const wrapper = mount(ListShift, {
			localVue,
			store,
			router,
		});

		const PAGE = wrapper.find('.list-shift');
		expect(PAGE.exists()).toBe(true);

		wrapper.destroy();
	});

	test('Check render title page', () => {
		const localVue = createLocalVue();
		const wrapper = mount(ListShift, {
			localVue,
			store,
			router,
		});

		const TITLE = wrapper.find('.title-page');
		expect(TITLE.text()).toEqual('LIST_SHIFT.TITLE_LIST_SHIFT');

		wrapper.destroy();
	});

	test('Check render button choose week or month', () => {
		const localVue = createLocalVue();
		const wrapper = mount(ListShift, {
			localVue,
			store,
			router,
		});

		const BUTTON_WEEK = wrapper.find('.btn-list-shift-week');
		expect(BUTTON_WEEK.exists()).toBe(true);
		expect(BUTTON_WEEK.text()).toEqual('LIST_SHIFT.BUTTON_MONTH');

		const BUTTON_MONTH = wrapper.find('.btn-list-shift-month');
		expect(BUTTON_MONTH.exists()).toBe(true);
		expect(BUTTON_MONTH.text()).toEqual('LIST_SHIFT.BUTTON_WEEK');

		wrapper.destroy();
	});

	test('Check click button choose week or month', async() => {
		const onClickSelectWeekMonth = jest.fn();
		const localVue = createLocalVue();
		const wrapper = mount(ListShift, {
			localVue,
			store,
			router,
			methods: {
				onClickSelectWeekMonth,
			},
		});

		const BUTTON_WEEK = wrapper.find('.btn-list-shift-week');
		await BUTTON_WEEK.trigger('click');
		expect(onClickSelectWeekMonth).toHaveBeenCalled();

		const BUTTON_MONTH = wrapper.find('.btn-list-shift-month');
		await BUTTON_MONTH.trigger('click');
		expect(onClickSelectWeekMonth).toHaveBeenCalled();

		wrapper.destroy();
	});

	test('Check render component picker week', () => {
		const localVue = createLocalVue();
		const wrapper = mount(ListShift, {
			localVue,
			store,
			router,
		});

		wrapper.destroy();
	});

	test('Check render button AI', () => {
		const localVue = createLocalVue();
		const wrapper = mount(ListShift, {
			localVue,
			store,
			router,
		});

		const CONTROL = wrapper.find('.zone-right');
		expect(CONTROL.exists()).toBe(true);
		const BUTTON = CONTROL.find('.show-text');
		expect(BUTTON.exists()).toBe(true);
		const TEXT_BUTTON = BUTTON.find('span');
		expect(TEXT_BUTTON.text()).toEqual('LIST_SHIFT.BUTTON_DOWNLOAD_EXCEL');

		wrapper.destroy();
	});

	test('Check click button AI', async() => {
		const onExportExcel = jest.fn();
		const localVue = createLocalVue();
		const wrapper = mount(ListShift, {
			localVue,
			store,
			router,
			methods: {
				onExportExcel,
			},
		});

		// const BUTTON = wrapper.find('.fa-robot');
		const CONTROL = wrapper.find('.zone-right');
		expect(CONTROL.exists()).toBe(true);
		const BUTTON = CONTROL.find('.show-text');
		expect(BUTTON.exists()).toBe(true);
		await BUTTON.trigger('click');
		expect(onExportExcel).toHaveBeenCalled();

		wrapper.destroy();
	});

	test('Check render button Excel', () => {
		const localVue = createLocalVue();
		const wrapper = mount(ListShift, {
			localVue,
			store,
			router,
		});

		const BUTTON = wrapper.find('.btn-excel');
		expect(BUTTON.exists()).toBe(true);
		expect(BUTTON.text()).toEqual('LIST_SHIFT.BUTTON_DOWNLOAD_EXCEL');

		wrapper.destroy();
	});

	test('Check click button Excel', async() => {
		const onExportExcel = jest.fn();
		const localVue = createLocalVue();
		const wrapper = mount(ListShift, {
			localVue,
			store,
			router,
			methods: {
				onExportExcel,
			},
		});

		const BUTTON_WEEK = wrapper.find('.btn-excel');
		await BUTTON_WEEK.trigger('click');
		expect(onExportExcel).toHaveBeenCalled();

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

	test('Check render button choose table', () => {
		const localVue = createLocalVue();
		const wrapper = mount(ListShift, {
			localVue,
			store,
			router,
		});

		const BUTTON = wrapper.find('.btn-excel');
		expect(BUTTON.exists()).toBe(true);
		expect(BUTTON.text()).toEqual('LIST_SHIFT.BUTTON_DOWNLOAD_EXCEL');

		wrapper.destroy();
	});

	test('Check click button choose table', async() => {
		const onExportExcel = jest.fn();
		const localVue = createLocalVue();
		const wrapper = mount(ListShift, {
			localVue,
			store,
			router,
			methods: {
				onExportExcel,
			},
		});

		const BUTTON_WEEK = wrapper.find('.btn-excel');
		await BUTTON_WEEK.trigger('click');
		expect(onExportExcel).toHaveBeenCalled();

		wrapper.destroy();
	});

	test('Check render table title date', () => {
		const localVue = createLocalVue();
		const wrapper = mount(ListShift, {
			localVue,
			store,
			router,
		});

		const TABLE = wrapper.find('.zone-table table');
		expect(TABLE.exists()).toBe(true);

		const LIST_HEADER = TABLE.findAll('th');

		const CONST_LIST_HEADER = [
			'LIST_SHIFT.TABLE_DATE_EMPLOYEE_NUMBER',
			'LIST_SHIFT.TABLE_FLAG',
			'LIST_SHIFT.TABLE_FULL_NAME',
		];

		expect(LIST_HEADER.length).toBe(66);
		// for (let i = 0; i < LIST_HEADER.length; i++) {
		// 	expect(LIST_HEADER.at(i).text()).toEqual(CONST_LIST_HEADER[i]);
		// }

		wrapper.destroy();
	});

	test('Check render body table', () => {
		const localVue = createLocalVue();
		const wrapper = mount(ListShift, {
			localVue,
			store,
			router,
			data() {
				return {
					listShift: [
						[
							{
								'id': 1,
								'driver_code': '0144',
								'flag': 'full',
								'driver_name': 'Pikachu',
								'start_date': '2021-06-25',
								'end_date': null,
								'status': 'on',
								'shift_list': [
									{
										'date': '2022-10-01',
										'value': null,
										'color': '#FFFFFF',
									},
									{
										'date': '2022-10-02',
										'value': null,
										'color': '#FFFFFF',
									},
									{
										'date': '2022-10-03',
										'value': null,
										'color': '#FFFFFF',
									},
									{
										'date': '2022-10-04',
										'value': null,
										'color': '#FFFFFF',
									},
									{
										'date': '2022-10-05',
										'value': null,
										'color': '#FFFFFF',
									},
									{
										'date': '2022-10-06',
										'value': null,
										'color': '#FFFFFF',
									},
									{
										'date': '2022-10-07',
										'value': null,
										'color': '#FFFFFF',
									},
								],
							},
							{
								'id': 2,
								'driver_code': '0145',
								'flag': 'full',
								'driver_name': 'Pikachu',
								'start_date': '2021-06-25',
								'end_date': null,
								'status': 'on',
								'shift_list': [
									{
										'date': '2022-10-01',
										'value': null,
										'color': '#FFFFFF',
									},
									{
										'date': '2022-10-02',
										'value': null,
										'color': '#FFFFFF',
									},
									{
										'date': '2022-10-03',
										'value': null,
										'color': '#FFFFFF',
									},
									{
										'date': '2022-10-04',
										'value': null,
										'color': '#FFFFFF',
									},
									{
										'date': '2022-10-05',
										'value': null,
										'color': '#FFFFFF',
									},
									{
										'date': '2022-10-06',
										'value': null,
										'color': '#FFFFFF',
									},
									{
										'date': '2022-10-07',
										'value': null,
										'color': '#FFFFFF',
									},
								],
							},
							{
								'id': 3,
								'driver_code': '0146',
								'flag': 'full',
								'driver_name': 'Pikachu',
								'start_date': '2021-06-25',
								'end_date': null,
								'status': 'on',
								'shift_list': [
									{
										'date': '2022-10-01',
										'value': null,
										'color': '#FFFFFF',
									},
									{
										'date': '2022-10-02',
										'value': null,
										'color': '#FFFFFF',
									},
									{
										'date': '2022-10-03',
										'value': null,
										'color': '#FFFFFF',
									},
									{
										'date': '2022-10-04',
										'value': null,
										'color': '#FFFFFF',
									},
									{
										'date': '2022-10-05',
										'value': null,
										'color': '#FFFFFF',
									},
									{
										'date': '2022-10-06',
										'value': null,
										'color': '#FFFFFF',
									},
									{
										'date': '2022-10-07',
										'value': null,
										'color': '#FFFFFF',
									},
								],
							},
							{
								'id': 4,
								'driver_code': '0147',
								'flag': 'full',
								'driver_name': 'Pikachu',
								'start_date': '2021-06-25',
								'end_date': null,
								'status': 'on',
								'shift_list': [
									{
										'date': '2022-10-01',
										'value': null,
										'color': '#FFFFFF',
									},
									{
										'date': '2022-10-02',
										'value': null,
										'color': '#FFFFFF',
									},
									{
										'date': '2022-10-03',
										'value': null,
										'color': '#FFFFFF',
									},
									{
										'date': '2022-10-04',
										'value': null,
										'color': '#FFFFFF',
									},
									{
										'date': '2022-10-05',
										'value': null,
										'color': '#FFFFFF',
									},
									{
										'date': '2022-10-06',
										'value': null,
										'color': '#FFFFFF',
									},
									{
										'date': '2022-10-07',
										'value': null,
										'color': '#FFFFFF',
									},
								],
							},
							{
								'id': 5,
								'driver_code': '0148',
								'flag': 'full',
								'driver_name': 'Pikachu',
								'start_date': '2021-06-25',
								'end_date': null,
								'status': 'on',
								'shift_list': [
									{
										'date': '2022-10-01',
										'value': null,
										'color': '#FFFFFF',
									},
									{
										'date': '2022-10-02',
										'value': null,
										'color': '#FFFFFF',
									},
									{
										'date': '2022-10-03',
										'value': null,
										'color': '#FFFFFF',
									},
									{
										'date': '2022-10-04',
										'value': null,
										'color': '#FFFFFF',
									},
									{
										'date': '2022-10-05',
										'value': null,
										'color': '#FFFFFF',
									},
									{
										'date': '2022-10-06',
										'value': null,
										'color': '#FFFFFF',
									},
									{
										'date': '2022-10-07',
										'value': null,
										'color': '#FFFFFF',
									},
								],
							},
							{
								'id': 6,
								'driver_code': '0149',
								'flag': 'full',
								'driver_name': 'Pikachu',
								'start_date': '2021-06-25',
								'end_date': null,
								'status': 'on',
								'shift_list': [
									{
										'date': '2022-10-01',
										'value': null,
										'color': '#FFFFFF',
									},
									{
										'date': '2022-10-02',
										'value': null,
										'color': '#FFFFFF',
									},
									{
										'date': '2022-10-03',
										'value': null,
										'color': '#FFFFFF',
									},
									{
										'date': '2022-10-04',
										'value': null,
										'color': '#FFFFFF',
									},
									{
										'date': '2022-10-05',
										'value': null,
										'color': '#FFFFFF',
									},
									{
										'date': '2022-10-06',
										'value': null,
										'color': '#FFFFFF',
									},
									{
										'date': '2022-10-07',
										'value': null,
										'color': '#FFFFFF',
									},
								],
							},
							{
								'id': 7,
								'driver_code': '0150',
								'flag': 'full',
								'driver_name': 'Pikachu',
								'start_date': '2021-06-25',
								'end_date': null,
								'status': 'on',
								'shift_list': [
									{
										'date': '2022-10-01',
										'value': null,
										'color': '#FFFFFF',
									},
									{
										'date': '2022-10-02',
										'value': null,
										'color': '#FFFFFF',
									},
									{
										'date': '2022-10-03',
										'value': null,
										'color': '#FFFFFF',
									},
									{
										'date': '2022-10-04',
										'value': null,
										'color': '#FFFFFF',
									},
									{
										'date': '2022-10-05',
										'value': null,
										'color': '#FFFFFF',
									},
									{
										'date': '2022-10-06',
										'value': null,
										'color': '#FFFFFF',
									},
									{
										'date': '2022-10-07',
										'value': null,
										'color': '#FFFFFF',
									},
								],
							},
							{
								'id': 8,
								'driver_code': '0151',
								'flag': 'full',
								'driver_name': 'Pikachu',
								'start_date': '2021-06-25',
								'end_date': null,
								'status': 'on',
								'shift_list': [
									{
										'date': '2022-10-01',
										'value': null,
										'color': '#FFFFFF',
									},
									{
										'date': '2022-10-02',
										'value': null,
										'color': '#FFFFFF',
									},
									{
										'date': '2022-10-03',
										'value': null,
										'color': '#FFFFFF',
									},
									{
										'date': '2022-10-04',
										'value': null,
										'color': '#FFFFFF',
									},
									{
										'date': '2022-10-05',
										'value': null,
										'color': '#FFFFFF',
									},
									{
										'date': '2022-10-06',
										'value': null,
										'color': '#FFFFFF',
									},
									{
										'date': '2022-10-07',
										'value': null,
										'color': '#FFFFFF',
									},
								],
							},
							{
								'id': 9,
								'driver_code': '0152',
								'flag': 'full',
								'driver_name': 'Pikachu',
								'start_date': '2021-06-25',
								'end_date': null,
								'status': 'on',
								'shift_list': [
									{
										'date': '2022-10-01',
										'value': null,
										'color': '#FFFFFF',
									},
									{
										'date': '2022-10-02',
										'value': null,
										'color': '#FFFFFF',
									},
									{
										'date': '2022-10-03',
										'value': null,
										'color': '#FFFFFF',
									},
									{
										'date': '2022-10-04',
										'value': null,
										'color': '#FFFFFF',
									},
									{
										'date': '2022-10-05',
										'value': null,
										'color': '#FFFFFF',
									},
									{
										'date': '2022-10-06',
										'value': null,
										'color': '#FFFFFF',
									},
									{
										'date': '2022-10-07',
										'value': null,
										'color': '#FFFFFF',
									},
								],
							},
							{
								'id': 10,
								'driver_code': '0153',
								'flag': 'full',
								'driver_name': 'Pikachu',
								'start_date': '2021-06-25',
								'end_date': null,
								'status': 'on',
								'shift_list': [
									{
										'date': '2022-10-01',
										'value': null,
										'color': '#FFFFFF',
									},
									{
										'date': '2022-10-02',
										'value': null,
										'color': '#FFFFFF',
									},
									{
										'date': '2022-10-03',
										'value': null,
										'color': '#FFFFFF',
									},
									{
										'date': '2022-10-04',
										'value': null,
										'color': '#FFFFFF',
									},
									{
										'date': '2022-10-05',
										'value': null,
										'color': '#FFFFFF',
									},
									{
										'date': '2022-10-06',
										'value': null,
										'color': '#FFFFFF',
									},
									{
										'date': '2022-10-07',
										'value': null,
										'color': '#FFFFFF',
									},
								],
							},
							{
								'id': 11,
								'driver_code': '0154',
								'flag': 'full',
								'driver_name': 'Pikachu',
								'start_date': '2021-06-25',
								'end_date': null,
								'status': 'on',
								'shift_list': [
									{
										'date': '2022-10-01',
										'value': null,
										'color': '#FFFFFF',
									},
									{
										'date': '2022-10-02',
										'value': null,
										'color': '#FFFFFF',
									},
									{
										'date': '2022-10-03',
										'value': null,
										'color': '#FFFFFF',
									},
									{
										'date': '2022-10-04',
										'value': null,
										'color': '#FFFFFF',
									},
									{
										'date': '2022-10-05',
										'value': null,
										'color': '#FFFFFF',
									},
									{
										'date': '2022-10-06',
										'value': null,
										'color': '#FFFFFF',
									},
									{
										'date': '2022-10-07',
										'value': null,
										'color': '#FFFFFF',
									},
								],
							},
							{
								'id': 12,
								'driver_code': '0155',
								'flag': 'full',
								'driver_name': 'Pikachu',
								'start_date': '2021-06-25',
								'end_date': null,
								'status': 'on',
								'shift_list': [
									{
										'date': '2022-10-01',
										'value': null,
										'color': '#FFFFFF',
									},
									{
										'date': '2022-10-02',
										'value': null,
										'color': '#FFFFFF',
									},
									{
										'date': '2022-10-03',
										'value': null,
										'color': '#FFFFFF',
									},
									{
										'date': '2022-10-04',
										'value': null,
										'color': '#FFFFFF',
									},
									{
										'date': '2022-10-05',
										'value': null,
										'color': '#FFFFFF',
									},
									{
										'date': '2022-10-06',
										'value': null,
										'color': '#FFFFFF',
									},
									{
										'date': '2022-10-07',
										'value': null,
										'color': '#FFFFFF',
									},
								],
							},
							{
								'id': 13,
								'driver_code': '0156',
								'flag': 'lead',
								'driver_name': 'Pikachu',
								'start_date': '2021-06-25',
								'end_date': null,
								'status': 'on',
								'shift_list': [
									{
										'date': '2022-10-01',
										'value': null,
										'color': '#FFFFFF',
									},
									{
										'date': '2022-10-02',
										'value': null,
										'color': '#FFFFFF',
									},
									{
										'date': '2022-10-03',
										'value': null,
										'color': '#FFFFFF',
									},
									{
										'date': '2022-10-04',
										'value': null,
										'color': '#FFFFFF',
									},
									{
										'date': '2022-10-05',
										'value': null,
										'color': '#FFFFFF',
									},
									{
										'date': '2022-10-06',
										'value': null,
										'color': '#FFFFFF',
									},
									{
										'date': '2022-10-07',
										'value': null,
										'color': '#FFFFFF',
									},
								],
							},
							{
								'id': 14,
								'driver_code': '0157',
								'flag': 'full',
								'driver_name': 'Pikachu',
								'start_date': '2021-06-25',
								'end_date': null,
								'status': 'on',
								'shift_list': [
									{
										'date': '2022-10-01',
										'value': null,
										'color': '#FFFFFF',
									},
									{
										'date': '2022-10-02',
										'value': null,
										'color': '#FFFFFF',
									},
									{
										'date': '2022-10-03',
										'value': null,
										'color': '#FFFFFF',
									},
									{
										'date': '2022-10-04',
										'value': null,
										'color': '#FFFFFF',
									},
									{
										'date': '2022-10-05',
										'value': null,
										'color': '#FFFFFF',
									},
									{
										'date': '2022-10-06',
										'value': null,
										'color': '#FFFFFF',
									},
									{
										'date': '2022-10-07',
										'value': null,
										'color': '#FFFFFF',
									},
								],
							},
							{
								'id': 15,
								'driver_code': '0158',
								'flag': 'full',
								'driver_name': 'Pikachu',
								'start_date': '2021-06-25',
								'end_date': null,
								'status': 'on',
								'shift_list': [
									{
										'date': '2022-10-01',
										'value': null,
										'color': '#FFFFFF',
									},
									{
										'date': '2022-10-02',
										'value': null,
										'color': '#FFFFFF',
									},
									{
										'date': '2022-10-03',
										'value': null,
										'color': '#FFFFFF',
									},
									{
										'date': '2022-10-04',
										'value': null,
										'color': '#FFFFFF',
									},
									{
										'date': '2022-10-05',
										'value': null,
										'color': '#FFFFFF',
									},
									{
										'date': '2022-10-06',
										'value': null,
										'color': '#FFFFFF',
									},
									{
										'date': '2022-10-07',
										'value': null,
										'color': '#FFFFFF',
									},
								],
							},
							{
								'id': 16,
								'driver_code': '0159',
								'flag': 'full',
								'driver_name': 'Pikachu',
								'start_date': '2021-06-25',
								'end_date': null,
								'status': 'on',
								'shift_list': [
									{
										'date': '2022-10-01',
										'value': null,
										'color': '#FFFFFF',
									},
									{
										'date': '2022-10-02',
										'value': null,
										'color': '#FFFFFF',
									},
									{
										'date': '2022-10-03',
										'value': null,
										'color': '#FFFFFF',
									},
									{
										'date': '2022-10-04',
										'value': null,
										'color': '#FFFFFF',
									},
									{
										'date': '2022-10-05',
										'value': null,
										'color': '#FFFFFF',
									},
									{
										'date': '2022-10-06',
										'value': null,
										'color': '#FFFFFF',
									},
									{
										'date': '2022-10-07',
										'value': null,
										'color': '#FFFFFF',
									},
								],
							},
							{
								'id': 17,
								'driver_code': '0160',
								'flag': 'lead',
								'driver_name': 'Pikachu',
								'start_date': '2021-06-25',
								'end_date': null,
								'status': 'on',
								'shift_list': [
									{
										'date': '2022-10-01',
										'value': null,
										'color': '#FFFFFF',
									},
									{
										'date': '2022-10-02',
										'value': null,
										'color': '#FFFFFF',
									},
									{
										'date': '2022-10-03',
										'value': null,
										'color': '#FFFFFF',
									},
									{
										'date': '2022-10-04',
										'value': null,
										'color': '#FFFFFF',
									},
									{
										'date': '2022-10-05',
										'value': null,
										'color': '#FFFFFF',
									},
									{
										'date': '2022-10-06',
										'value': null,
										'color': '#FFFFFF',
									},
									{
										'date': '2022-10-07',
										'value': null,
										'color': '#FFFFFF',
									},
								],
							},
							{
								'id': 18,
								'driver_code': '0161',
								'flag': 'full',
								'driver_name': 'Pikachu',
								'start_date': '2021-06-25',
								'end_date': null,
								'status': 'on',
								'shift_list': [
									{
										'date': '2022-10-01',
										'value': null,
										'color': '#FFFFFF',
									},
									{
										'date': '2022-10-02',
										'value': null,
										'color': '#FFFFFF',
									},
									{
										'date': '2022-10-03',
										'value': null,
										'color': '#FFFFFF',
									},
									{
										'date': '2022-10-04',
										'value': null,
										'color': '#FFFFFF',
									},
									{
										'date': '2022-10-05',
										'value': null,
										'color': '#FFFFFF',
									},
									{
										'date': '2022-10-06',
										'value': null,
										'color': '#FFFFFF',
									},
									{
										'date': '2022-10-07',
										'value': null,
										'color': '#FFFFFF',
									},
								],
							},
							{
								'id': 19,
								'driver_code': '0162',
								'flag': 'full',
								'driver_name': 'Pikachu',
								'start_date': '2021-06-25',
								'end_date': null,
								'status': 'on',
								'shift_list': [
									{
										'date': '2022-10-01',
										'value': null,
										'color': '#FFFFFF',
									},
									{
										'date': '2022-10-02',
										'value': null,
										'color': '#FFFFFF',
									},
									{
										'date': '2022-10-03',
										'value': null,
										'color': '#FFFFFF',
									},
									{
										'date': '2022-10-04',
										'value': null,
										'color': '#FFFFFF',
									},
									{
										'date': '2022-10-05',
										'value': null,
										'color': '#FFFFFF',
									},
									{
										'date': '2022-10-06',
										'value': null,
										'color': '#FFFFFF',
									},
									{
										'date': '2022-10-07',
										'value': null,
										'color': '#FFFFFF',
									},
								],
							},
							{
								'id': 20,
								'driver_code': '0163',
								'flag': 'full',
								'driver_name': 'Pikachu',
								'start_date': '2021-06-25',
								'end_date': null,
								'status': 'on',
								'shift_list': [
									{
										'date': '2022-10-01',
										'value': null,
										'color': '#FFFFFF',
									},
									{
										'date': '2022-10-02',
										'value': null,
										'color': '#FFFFFF',
									},
									{
										'date': '2022-10-03',
										'value': null,
										'color': '#FFFFFF',
									},
									{
										'date': '2022-10-04',
										'value': null,
										'color': '#FFFFFF',
									},
									{
										'date': '2022-10-05',
										'value': null,
										'color': '#FFFFFF',
									},
									{
										'date': '2022-10-06',
										'value': null,
										'color': '#FFFFFF',
									},
									{
										'date': '2022-10-07',
										'value': null,
										'color': '#FFFFFF',
									},
								],
							},
							{
								'id': 21,
								'driver_code': '0164',
								'flag': 'full',
								'driver_name': 'Pikachu',
								'start_date': '2021-06-25',
								'end_date': null,
								'status': 'on',
								'shift_list': [
									{
										'date': '2022-10-01',
										'value': null,
										'color': '#FFFFFF',
									},
									{
										'date': '2022-10-02',
										'value': null,
										'color': '#FFFFFF',
									},
									{
										'date': '2022-10-03',
										'value': null,
										'color': '#FFFFFF',
									},
									{
										'date': '2022-10-04',
										'value': null,
										'color': '#FFFFFF',
									},
									{
										'date': '2022-10-05',
										'value': null,
										'color': '#FFFFFF',
									},
									{
										'date': '2022-10-06',
										'value': null,
										'color': '#FFFFFF',
									},
									{
										'date': '2022-10-07',
										'value': null,
										'color': '#FFFFFF',
									},
								],
							},
						],
					],
				};
			},
		});

		const TABLE = wrapper.find('.zone-table table');
		expect(TABLE.exists()).toBe(true);

		const LIST_BODY = TABLE.findAll('tbody tr');
		expect(LIST_BODY.length).toBe(0);

		wrapper.destroy();
	});

	test('Check render cell', () => {
		const localVue = createLocalVue();
		const wrapper = mount(ListShift, {
			localVue,
			store,
			router,
		});

		wrapper.destroy();
	});
});
