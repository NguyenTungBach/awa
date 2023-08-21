import store from '@/store';
import router from '@/router';
import ListShift from '@/pages/ShiftManagement/ListShift/index';
import { mount, createLocalVue } from '@vue/test-utils';
describe('TEST COMPONENT LIST SHIFT Sales', () => {
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

	test('Check render title page', async() => {
		const localVue = createLocalVue();
		const wrapper = mount(ListShift, {
			localVue,
			store,
			router,
			methods: {
				initData: jest.fn(),
				// activeSelectTable: jest.fn(),
				// onClickSelectTable: jest.fn(),
				handleGetListCalendar: jest.fn(),
				handleGetListShift: jest.fn(),
			},
			data() {
				return {
					selectTable: 'CONSTANT.LIST_SHIFT.SALES_AMOUNT_TABLE',
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
		const BUTTON_EXPRESS_CHANGE = wrapper.find('div > div:nth-child(1) > div > div > button:nth-child(3)');
		expect(BUTTON_EXPRESS_CHANGE.exists()).toEqual(true);
		await BUTTON_EXPRESS_CHANGE.trigger('click');

		// expect(wrapper).toMatchSnapshot();
		const TITLE = wrapper.find('.title-page');
		expect(TITLE.text()).toEqual('LIST_SHIFT.TABLE_SALARY');
		wrapper.destroy();
	});

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
		const BUTTON_SALES = wrapper.find('div > div:nth-child(1) > div > div > button:nth-child(3)');
		expect(BUTTON_SALES.exists()).toEqual(true);
		await BUTTON_SALES.trigger('click');
		// expect(wrapper.vm.onClickSelectTable).toHaveBeenCalled();

		// const TITLE = wrapper.find('.title-page');
		// expect(TITLE.text()).toEqual('LIST_SHIFT.TITLE_LIST_SHIFT_PRACTICAL_RECORD_TABLE');

		// console.log('akjgkaj: ', wrapper.vm.selectTable);
		// await wrapper.setData( {selectTable: 'CONSTANT.LIST_SHIFT.HIGHT_WAY_FEE'} );
		// expect(wrapper.vm.selectTable).toBe('CONSTANT.LIST_SHIFT.HIGHT_WAY_FEE');

		const BUTTON_WEEK = wrapper.find('.btn-excel');
		await BUTTON_WEEK.trigger('click');
		expect(onExportExcel).toHaveBeenCalled();

		// await BUTTON.trigger('click');
		// expect(onExportExcel).toHaveBeenCalled();

		wrapper.destroy();
	});

	test('Check click button closing final', async() => {
		const onExportExcel = jest.fn();
		const onClickSelectTable = jest.fn();
		const initData = jest.fn();
		const handleGetListCalendar = jest.fn();
		const handleGetListShift = jest.fn();
		const turnOnButtonFinal = jest.fn();
		const handleClosingDate = jest.fn();
		const handleChangeBackgroundTEM = jest.fn();
		const handleChangeBackgroundFinal = jest.fn();
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
				turnOnButtonFinal,
				handleClosingDate,
				handleChangeBackgroundTEM,
				handleChangeBackgroundFinal,
				// handleGetListCalendar,
				// handleGetListShift,
				// onClickSelectWeekMonth,
			},
			data() {
				return {
					selectTable: 'CONSTANT.LIST_SHIFT.SHIFT_TABLE',
					disableFinal: false,
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
		const BUTTON_SALES = wrapper.find('div > div:nth-child(1) > div > div > button:nth-child(3)');
		expect(BUTTON_SALES.exists()).toEqual(true);
		await BUTTON_SALES.trigger('click');
		// expect(wrapper.vm.onClickSelectTable).toHaveBeenCalled();

		// const TITLE = wrapper.find('.title-page');
		// expect(TITLE.text()).toEqual('LIST_SHIFT.TITLE_LIST_SHIFT_PRACTICAL_RECORD_TABLE');

		// console.log('akjgkaj: ', wrapper.vm.selectTable);
		// await wrapper.setData( {selectTable: 'CONSTANT.LIST_SHIFT.HIGHT_WAY_FEE'} );
		// expect(wrapper.vm.selectTable).toBe('CONSTANT.LIST_SHIFT.HIGHT_WAY_FEE');

		const BUTTON_temporary = wrapper.find('.btn-temporary');
		await BUTTON_temporary.trigger('click');
		expect(turnOnButtonFinal).toHaveBeenCalled();

		const BUTTON_final = wrapper.find('.btn-final');
		await BUTTON_final.trigger('click');
		expect(handleClosingDate).toHaveBeenCalled();

		// await BUTTON.trigger('click');
		// expect(onExportExcel).toHaveBeenCalled();

		wrapper.destroy();
	});

	test('Check render body', async() => {
		const DATA = [
			{
				'customer_id': 1,
				'customer_code': '0001',
				'closing_date': 1,
				'closing_dateName': '15日',
				'customer_name': 'Customer 01',
				'date_ship_fee': [
					{
						'courses_customer_id': 1,
						'courses_ship_fee': '',
						'date': '2023-08-01',
					},
					{
						'courses_customer_id': 1,
						'courses_ship_fee': '',
						'date': '2023-08-02',
					},
					{
						'courses_customer_id': 1,
						'courses_ship_fee': '',
						'date': '2023-08-03',
					},
					{
						'courses_customer_id': 1,
						'courses_ship_fee': '',
						'date': '2023-08-04',
					},
					{
						'courses_customer_id': 1,
						'courses_ship_fee': '',
						'date': '2023-08-05',
					},
					{
						'courses_customer_id': 1,
						'courses_ship_fee': '',
						'date': '2023-08-06',
					},
					{
						'courses_customer_id': 1,
						'courses_ship_fee': '',
						'date': '2023-08-07',
					},
					{
						'courses_customer_id': 1,
						'courses_ship_fee': '',
						'date': '2023-08-08',
					},
					{
						'courses_customer_id': 1,
						'courses_ship_fee': '',
						'date': '2023-08-09',
					},
					{
						'courses_customer_id': 1,
						'courses_ship_fee': '',
						'date': '2023-08-10',
					},
					{
						'courses_customer_id': 1,
						'courses_ship_fee': '5000.00',
						'date': '2023-08-11',
					},
					{
						'courses_customer_id': 1,
						'courses_ship_fee': '',
						'date': '2023-08-12',
					},
					{
						'courses_customer_id': 1,
						'courses_ship_fee': '',
						'date': '2023-08-13',
					},
					{
						'courses_customer_id': 1,
						'courses_ship_fee': '',
						'date': '2023-08-14',
					},
					{
						'courses_customer_id': 1,
						'courses_ship_fee': '',
						'date': '2023-08-15',
					},
					{
						'courses_customer_id': 1,
						'courses_ship_fee': '',
						'date': '2023-08-16',
					},
					{
						'courses_customer_id': 1,
						'courses_ship_fee': '',
						'date': '2023-08-17',
					},
					{
						'courses_customer_id': 1,
						'courses_ship_fee': '',
						'date': '2023-08-18',
					},
					{
						'courses_customer_id': 1,
						'courses_ship_fee': '',
						'date': '2023-08-19',
					},
					{
						'courses_customer_id': 1,
						'courses_ship_fee': '',
						'date': '2023-08-20',
					},
					{
						'courses_customer_id': 1,
						'courses_ship_fee': '',
						'date': '2023-08-21',
					},
					{
						'courses_customer_id': 1,
						'courses_ship_fee': '',
						'date': '2023-08-22',
					},
					{
						'courses_customer_id': 1,
						'courses_ship_fee': '',
						'date': '2023-08-23',
					},
					{
						'courses_customer_id': 1,
						'courses_ship_fee': '',
						'date': '2023-08-24',
					},
					{
						'courses_customer_id': 1,
						'courses_ship_fee': '',
						'date': '2023-08-25',
					},
					{
						'courses_customer_id': 1,
						'courses_ship_fee': '',
						'date': '2023-08-26',
					},
					{
						'courses_customer_id': 1,
						'courses_ship_fee': '',
						'date': '2023-08-27',
					},
					{
						'courses_customer_id': 1,
						'courses_ship_fee': '',
						'date': '2023-08-28',
					},
					{
						'courses_customer_id': 1,
						'courses_ship_fee': '',
						'date': '2023-08-29',
					},
					{
						'courses_customer_id': 1,
						'courses_ship_fee': '',
						'date': '2023-08-30',
					},
					{
						'courses_customer_id': 1,
						'courses_ship_fee': '',
						'date': '2023-08-31',
					},
				],
				'total_ship_fee_by_closing_date': '5000.00',
				'total_ship_fee_by_month': '5000.00',
			},
		];
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
					selectTable: 'CONSTANT.LIST_SHIFT.SALES_AMOUNT_TABLE',
					listHighWay: DATA,
					listSaleAmount: DATA,
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
		const BUTTON_SALES = wrapper.find('div > div:nth-child(1) > div > div > button:nth-child(3)');
		expect(BUTTON_SALES.exists()).toEqual(true);
		await BUTTON_SALES.trigger('click');

		const ZONE_TABLE = wrapper.find('.zone-table');
		const TABLE = ZONE_TABLE.find('.table-salary');

		// Kiểm tra HEAD table
		const THEAD = TABLE.find('thead');
		expect(THEAD.exists()).toBe(true);

		const TR = THEAD.findAll('tr');
		expect(TR.length).toBe(2);

		const TR_TH = TR.at(1).findAll('th');
		expect(TR_TH.exists()).toBe(true);

		// Kiểm tra tên 3 cột
		expect(TR_TH.at(0).text()).toEqual('LIST_SHIFT.TABLE_CUSTOMER_ID');
		expect(TR_TH.at(1).text()).toEqual('LIST_SHIFT.TABLE_DUA_DATE_CUSTOMER');
		expect(TR_TH.at(2).text()).toEqual('LIST_SHIFT.TABLE_CUSTOMER_NAME');

		// expect(wrapper.vm.onClickSelectTable).toHaveBeenCalled();

		// const TITLE = wrapper.find('.title-page');
		// expect(TITLE.text()).toEqual('LIST_SHIFT.TITLE_LIST_SHIFT_PRACTICAL_RECORD_TABLE');

		// console.log('akjgkaj: ', wrapper.vm.selectTable);
		// await wrapper.setData( {selectTable: 'CONSTANT.LIST_SHIFT.HIGHT_WAY_FEE'} );
		// expect(wrapper.vm.selectTable).toBe('CONSTANT.LIST_SHIFT.HIGHT_WAY_FEE');

		// expect(wrapper).toMatchSnapshot();

		const BUTTON_WEEK = wrapper.find('.btn-excel');
		await BUTTON_WEEK.trigger('click');
		expect(onExportExcel).toHaveBeenCalled();

		// await BUTTON.trigger('click');
		// expect(onExportExcel).toHaveBeenCalled();

		wrapper.destroy();
	});

	test('Check render body table', async() => {
		const DATA = [
			{
				'customer_id': 1,
				'customer_code': '0001',
				'closing_date': 1,
				'closing_dateName': '15日',
				'customer_name': 'Customer 01',
				'date_ship_fee': [
					{
						'courses_customer_id': 1,
						'courses_ship_fee': '',
						'date': '2023-08-01',
					},
					{
						'courses_customer_id': 1,
						'courses_ship_fee': '',
						'date': '2023-08-02',
					},
					{
						'courses_customer_id': 1,
						'courses_ship_fee': '',
						'date': '2023-08-03',
					},
					{
						'courses_customer_id': 1,
						'courses_ship_fee': '',
						'date': '2023-08-04',
					},
					{
						'courses_customer_id': 1,
						'courses_ship_fee': '',
						'date': '2023-08-05',
					},
					{
						'courses_customer_id': 1,
						'courses_ship_fee': '',
						'date': '2023-08-06',
					},
					{
						'courses_customer_id': 1,
						'courses_ship_fee': '',
						'date': '2023-08-07',
					},
					{
						'courses_customer_id': 1,
						'courses_ship_fee': '',
						'date': '2023-08-08',
					},
					{
						'courses_customer_id': 1,
						'courses_ship_fee': '',
						'date': '2023-08-09',
					},
					{
						'courses_customer_id': 1,
						'courses_ship_fee': '',
						'date': '2023-08-10',
					},
					{
						'courses_customer_id': 1,
						'courses_ship_fee': '5000.00',
						'date': '2023-08-11',
					},
					{
						'courses_customer_id': 1,
						'courses_ship_fee': '',
						'date': '2023-08-12',
					},
					{
						'courses_customer_id': 1,
						'courses_ship_fee': '',
						'date': '2023-08-13',
					},
					{
						'courses_customer_id': 1,
						'courses_ship_fee': '',
						'date': '2023-08-14',
					},
					{
						'courses_customer_id': 1,
						'courses_ship_fee': '',
						'date': '2023-08-15',
					},
					{
						'courses_customer_id': 1,
						'courses_ship_fee': '',
						'date': '2023-08-16',
					},
					{
						'courses_customer_id': 1,
						'courses_ship_fee': '',
						'date': '2023-08-17',
					},
					{
						'courses_customer_id': 1,
						'courses_ship_fee': '',
						'date': '2023-08-18',
					},
					{
						'courses_customer_id': 1,
						'courses_ship_fee': '',
						'date': '2023-08-19',
					},
					{
						'courses_customer_id': 1,
						'courses_ship_fee': '',
						'date': '2023-08-20',
					},
					{
						'courses_customer_id': 1,
						'courses_ship_fee': '',
						'date': '2023-08-21',
					},
					{
						'courses_customer_id': 1,
						'courses_ship_fee': '',
						'date': '2023-08-22',
					},
					{
						'courses_customer_id': 1,
						'courses_ship_fee': '',
						'date': '2023-08-23',
					},
					{
						'courses_customer_id': 1,
						'courses_ship_fee': '',
						'date': '2023-08-24',
					},
					{
						'courses_customer_id': 1,
						'courses_ship_fee': '',
						'date': '2023-08-25',
					},
					{
						'courses_customer_id': 1,
						'courses_ship_fee': '',
						'date': '2023-08-26',
					},
					{
						'courses_customer_id': 1,
						'courses_ship_fee': '',
						'date': '2023-08-27',
					},
					{
						'courses_customer_id': 1,
						'courses_ship_fee': '',
						'date': '2023-08-28',
					},
					{
						'courses_customer_id': 1,
						'courses_ship_fee': '',
						'date': '2023-08-29',
					},
					{
						'courses_customer_id': 1,
						'courses_ship_fee': '',
						'date': '2023-08-30',
					},
					{
						'courses_customer_id': 1,
						'courses_ship_fee': '',
						'date': '2023-08-31',
					},
				],
				'total_ship_fee_by_closing_date': '5000.00',
				'total_ship_fee_by_month': '5000.00',
			},
		];
		const handleExportPDF = jest.fn();
		const localVue = createLocalVue();
		const wrapper = mount(ListShift, {
			localVue,
			store,
			router,
			methods: {
				handleExportPDF,
				// handleGetListCalendar,
				// handleGetListShift,
				// onClickSelectWeekMonth,
			},
			data() {
				return {
					listSaleAmount: DATA,
					// handleGetListCalendar: jest.fn(),
					initData: jest.fn(),
				};
			},
			computed: {
				role() {
					return 'admin';
				},
			},
		});

		// jest.spyOn(wrapper.vm, 'handleGetListCalendar');

		const BUTTON_LIST = wrapper.find('.list-shift__control');
		expect(BUTTON_LIST.exists()).toEqual(true);
		const BUTTON_SALES = wrapper.find('div > div:nth-child(1) > div > div > button:nth-child(3)');
		expect(BUTTON_SALES.exists()).toEqual(true);
		await BUTTON_SALES.trigger('click');

		const ZONE_TABLE = wrapper.find('.zone-table');
		const TABLE = ZONE_TABLE.find('.table-salary');

		// Kiểm tra HEAD table
		const THEAD = TABLE.find('thead');
		expect(THEAD.exists()).toBe(true);

		const TR = THEAD.findAll('tr');
		expect(TR.length).toBe(2);

		const TR_TH = TR.at(1).findAll('th');
		expect(TR_TH.exists()).toBe(true);

		// Kiểm tra tên 3 cột
		expect(TR_TH.at(0).text()).toEqual('LIST_SHIFT.TABLE_CUSTOMER_ID');
		expect(TR_TH.at(1).text()).toEqual('LIST_SHIFT.TABLE_DUA_DATE_CUSTOMER');
		expect(TR_TH.at(2).text()).toEqual('LIST_SHIFT.TABLE_CUSTOMER_NAME');

		// Kiểm tra tên Data đổ ra
		const ROWS = TABLE.findAll('tbody tr');
		expect(ROWS.length).toBe(2);

		const len2 = DATA.length;
		let idx2 = 0;

		// kiểm tra 3 cột render đầu tiên
		while (idx2 < len2) {
			const ROW = ROWS.at(idx2);

			const COLUMNS = ROW.findAll('td');

			const COLUMN_driver_code = COLUMNS.at(0);
			expect(COLUMN_driver_code.text()).toEqual(DATA[idx2].customer_code);
			const COLUMN_CUSTOMER_NAME = COLUMNS.at(1);
			expect(COLUMN_CUSTOMER_NAME.text()).toEqual(DATA[idx2].closing_dateName);
			const COLUMN_Driver_name = COLUMNS.at(2);
			expect(COLUMN_Driver_name.text()).toEqual(DATA[idx2].customer_name);

			// Kiểm tra button pdf có tồn tại và click được không
			const BUTTON_PDF = ROW.find('.img-pdf img');
			expect(BUTTON_PDF.exists()).toEqual(true);
			// expect(wrapper).toMatchSnapshot();
			await BUTTON_PDF.trigger('click');
			expect(handleExportPDF).toHaveBeenCalled();

			idx2++;
		}

		wrapper.destroy();
	});
});
