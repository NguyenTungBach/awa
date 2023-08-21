import store from '@/store';
import router from '@/router';
import ListShift from '@/pages/ShiftManagement/ListShift/index';
import { mount, createLocalVue } from '@vue/test-utils';
describe('TEST COMPONENT LIST SHIFT PAYMENT', () => {
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
					selectTable: 'CONSTANT.LIST_SHIFT.PAYMENT_TABLE',
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
		const BUTTON_EXPRESS_CHANGE = wrapper.find('div > div:nth-child(1) > div > div > button:nth-child(4)');
		expect(BUTTON_EXPRESS_CHANGE.exists()).toEqual(true);
		await BUTTON_EXPRESS_CHANGE.trigger('click');

		// expect(wrapper).toMatchSnapshot();
		const TITLE = wrapper.find('.title-page');
		expect(TITLE.text()).toEqual('LIST_SHIFT.PAYMENT_TABLE');
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
					selectTable: 'CONSTANT.LIST_SHIFT.PAYMENT_TABLE',
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
		const BUTTON_SALES = wrapper.find('div > div:nth-child(1) > div > div > button:nth-child(4)');
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

	test('Check render body', async() => {
		const DATA = [
			{
				'id': 4,
				'driver_code': '0004',
				'driver_name': 'Associate company',
				'closing_date': '25日',
				'total_payable_day': [
					{
						'date': '2023-08-01',
						'payment': 0,
					},
					{
						'date': '2023-08-02',
						'payment': 0,
					},
					{
						'date': '2023-08-03',
						'payment': 0,
					},
					{
						'date': '2023-08-04',
						'payment': 0,
					},
					{
						'date': '2023-08-05',
						'payment': 0,
					},
					{
						'date': '2023-08-06',
						'payment': 0,
					},
					{
						'date': '2023-08-07',
						'payment': 0,
					},
					{
						'date': '2023-08-08',
						'payment': 0,
					},
					{
						'date': '2023-08-09',
						'payment': 0,
					},
					{
						'date': '2023-08-10',
						'payment': 0,
					},
					{
						'date': '2023-08-11',
						'payment': 0,
					},
					{
						'date': '2023-08-12',
						'payment': 0,
					},
					{
						'date': '2023-08-13',
						'payment': 0,
					},
					{
						'date': '2023-08-14',
						'payment': 40,
					},
					{
						'date': '2023-08-15',
						'payment': 0,
					},
					{
						'date': '2023-08-16',
						'payment': 0,
					},
					{
						'date': '2023-08-17',
						'payment': 0,
					},
					{
						'date': '2023-08-18',
						'payment': 0,
					},
					{
						'date': '2023-08-19',
						'payment': 0,
					},
					{
						'date': '2023-08-20',
						'payment': 0,
					},
					{
						'date': '2023-08-21',
						'payment': 0,
					},
					{
						'date': '2023-08-22',
						'payment': 0,
					},
					{
						'date': '2023-08-23',
						'payment': 0,
					},
					{
						'date': '2023-08-24',
						'payment': 0,
					},
					{
						'date': '2023-08-25',
						'payment': 0,
					},
					{
						'date': '2023-08-26',
						'payment': 0,
					},
					{
						'date': '2023-08-27',
						'payment': 0,
					},
					{
						'date': '2023-08-28',
						'payment': 0,
					},
					{
						'date': '2023-08-29',
						'payment': 0,
					},
					{
						'date': '2023-08-30',
						'payment': 0,
					},
					{
						'date': '2023-08-31',
						'payment': 0,
					},
				],
				'payable_this_month': 40,
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
					selectTable: 'CONSTANT.LIST_SHIFT.PAYMENT_TABLE',
					ListPayment: DATA,
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
		const BUTTON_SALES = wrapper.find('div > div:nth-child(1) > div > div > button:nth-child(4)');
		expect(BUTTON_SALES.exists()).toEqual(true);
		await BUTTON_SALES.trigger('click');

		const ZONE_TABLE = wrapper.find('.zone-table');
		const TABLE = ZONE_TABLE.find('.table-payment');

		// Kiểm tra HEAD table
		const THEAD = TABLE.find('thead');
		expect(THEAD.exists()).toBe(true);

		const TR = THEAD.findAll('tr');
		expect(TR.length).toBe(2);

		const TR_TH = TR.at(1).findAll('th');
		expect(TR_TH.exists()).toBe(true);

		// Kiểm tra tên 3 cột
		expect(TR_TH.at(0).text()).toEqual('LIST_SHIFT.TABLE_PAYMENT_COMPANY_ID');
		expect(TR_TH.at(1).text()).toEqual('LIST_SHIFT.TABLE_PAYMENT_DUE_DATE');
		expect(TR_TH.at(2).text()).toEqual('LIST_SHIFT.TABLE_COMPANY_NAME');

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
				'id': 4,
				'driver_code': '0004',
				'driver_name': 'Associate company',
				'closing_date': '25日',
				'total_payable_day': [
					{
						'date': '2023-08-01',
						'payment': 0,
					},
					{
						'date': '2023-08-02',
						'payment': 0,
					},
					{
						'date': '2023-08-03',
						'payment': 0,
					},
					{
						'date': '2023-08-04',
						'payment': 0,
					},
					{
						'date': '2023-08-05',
						'payment': 0,
					},
					{
						'date': '2023-08-06',
						'payment': 0,
					},
					{
						'date': '2023-08-07',
						'payment': 0,
					},
					{
						'date': '2023-08-08',
						'payment': 0,
					},
					{
						'date': '2023-08-09',
						'payment': 0,
					},
					{
						'date': '2023-08-10',
						'payment': 0,
					},
					{
						'date': '2023-08-11',
						'payment': 0,
					},
					{
						'date': '2023-08-12',
						'payment': 0,
					},
					{
						'date': '2023-08-13',
						'payment': 0,
					},
					{
						'date': '2023-08-14',
						'payment': 40,
					},
					{
						'date': '2023-08-15',
						'payment': 0,
					},
					{
						'date': '2023-08-16',
						'payment': 0,
					},
					{
						'date': '2023-08-17',
						'payment': 0,
					},
					{
						'date': '2023-08-18',
						'payment': 0,
					},
					{
						'date': '2023-08-19',
						'payment': 0,
					},
					{
						'date': '2023-08-20',
						'payment': 0,
					},
					{
						'date': '2023-08-21',
						'payment': 0,
					},
					{
						'date': '2023-08-22',
						'payment': 0,
					},
					{
						'date': '2023-08-23',
						'payment': 0,
					},
					{
						'date': '2023-08-24',
						'payment': 0,
					},
					{
						'date': '2023-08-25',
						'payment': 0,
					},
					{
						'date': '2023-08-26',
						'payment': 0,
					},
					{
						'date': '2023-08-27',
						'payment': 0,
					},
					{
						'date': '2023-08-28',
						'payment': 0,
					},
					{
						'date': '2023-08-29',
						'payment': 0,
					},
					{
						'date': '2023-08-30',
						'payment': 0,
					},
					{
						'date': '2023-08-31',
						'payment': 0,
					},
				],
				'payable_this_month': 40,
			},
		];
		const localVue = createLocalVue();
		const wrapper = mount(ListShift, {
			localVue,
			store,
			router,
			data() {
				return {
					ListPayment: DATA,
					selectTable: 'CONSTANT.LIST_SHIFT.PAYMENT_TABLE',
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
		const BUTTON_SALES = wrapper.find('div > div:nth-child(1) > div > div > button:nth-child(4)');
		expect(BUTTON_SALES.exists()).toEqual(true);
		await BUTTON_SALES.trigger('click');

		const ZONE_TABLE = wrapper.find('.zone-table');
		const TABLE = ZONE_TABLE.find('.table-payment');

		// Kiểm tra HEAD table
		const THEAD = TABLE.find('thead');
		expect(THEAD.exists()).toBe(true);

		const TR = THEAD.findAll('tr');
		expect(TR.length).toBe(2);

		const TR_TH = TR.at(1).findAll('th');
		expect(TR_TH.exists()).toBe(true);

		// Kiểm tra tên 3 cột
		expect(TR_TH.at(0).text()).toEqual('LIST_SHIFT.TABLE_PAYMENT_COMPANY_ID');
		expect(TR_TH.at(1).text()).toEqual('LIST_SHIFT.TABLE_PAYMENT_DUE_DATE');
		expect(TR_TH.at(2).text()).toEqual('LIST_SHIFT.TABLE_COMPANY_NAME');

		// Kiểm tra tên Data đổ ra
		const ROWS = TABLE.findAll('tbody tr');
		// expect(wrapper).toMatchSnapshot();
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
			idx2++;
		}

		wrapper.destroy();
	});
});
