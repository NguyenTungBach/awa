import store from '@/store';
import router from '@/router';
import CashInList from '@/pages/CashManagement/CashReceipt';
import { mount, createLocalVue } from '@vue/test-utils';

describe('TEST COMPONENT CASH-IN LIST', () => {
	test('Check render page', () => {
		const localVue = createLocalVue();
		const wrapper = mount(CashInList, {
			localVue,
			store,
			router,
		});

		const PAGE = wrapper.find('.table-cash-in');
		expect(PAGE.exists()).toBe(true);

		wrapper.destroy();
	});

	test('Check render header table', () => {
		const localVue = createLocalVue();
		const wrapper = mount(CashInList, {
			localVue,
			store,
			router,
		});

		const TABLE = wrapper.find('table');

		const THEAD = TABLE.find('thead');
		expect(THEAD.exists()).toBe(true);

		// Kiểm tra các trường
		const LIST_TH = THEAD.findAll('th');
		expect(LIST_TH.length).toEqual(8);

		expect(LIST_TH.at(0).text()).toEqual('LIST_CASH.TABLE_CASH_ID');
		expect(LIST_TH.at(1).text()).toEqual('LIST_CASH.TABLE_CASH_NAME');
		expect(LIST_TH.at(2).text()).toEqual('LIST_CASH.TABLE_CASH_BALANCE_AT_END_OF_PREVIOUS_MONTH');
		expect(LIST_TH.at(3).text()).toEqual('LIST_CASH.TABLE_CASH_ACCOUNTS_RECEIVABLE');
		expect(LIST_TH.at(4).text()).toEqual('LIST_CASH.TABLE_TOTAL_ACCOUNTS_RECEIVABLE');
		expect(LIST_TH.at(5).text()).toEqual('LIST_CASH.TABLE_MONTHLY_DEPOSIT_AMOUNT');
		expect(LIST_TH.at(6).text()).toEqual('LIST_CASH.TABLE_CURRENT_MONTH_BALANCE');
		expect(LIST_TH.at(7).text()).toEqual('LIST_CASH.TABLE_DETAIL');

		const TBODY = TABLE.find('tbody');
		expect(TBODY.exists()).toBe(true);

		console.log(TABLE.html());

		wrapper.destroy();
	});

	test('Check render body table', () => {
		const DATA = [
			{
				'customer_id': 1,
				'customer_code': '0001',
				'customer_name': 'Customer 01',
				'month_line': '2023-08',
				'balance_previous_month': '0.00',
				'receivable_this_month': '5000.00',
				'total_account_receivable': '5000.00',
				'total_cash_in_of_current_month': '3000.00',
				'total_cash_in_current': '2000.00',
			},
			{
				'customer_id': 2,
				'customer_code': '0002',
				'customer_name': 'Customer 02',
				'month_line': '2023-08',
				'balance_previous_month': '0.00',
				'receivable_this_month': '66000.00',
				'total_account_receivable': '66000.00',
				'total_cash_in_of_current_month': '0.00',
				'total_cash_in_current': '66000.00',
			},
		];

		const localVue = createLocalVue();
		const wrapper = mount(CashInList, {
			localVue,
			store,
			router,
			data() {
				return {
					listCash: DATA,
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

			const COLUMN_CUSTOMER_CODE = COLUMNS.at(0);
			expect(COLUMN_CUSTOMER_CODE.text()).toEqual(DATA[idx].customer_code);
			const COLUMN_CUSTOMER_NAME = COLUMNS.at(1);
			expect(COLUMN_CUSTOMER_NAME.text()).toEqual(DATA[idx].customer_name);
			const COLUMN_Balance_Previous_Month = COLUMNS.at(2);
			expect(COLUMN_Balance_Previous_Month.text()).toEqual(DATA[idx].balance_previous_month);
			const COLUMN_Receivable_this_month = COLUMNS.at(3);
			expect(COLUMN_Receivable_this_month.text()).toEqual(DATA[idx].receivable_this_month);
			const COLUMN_Total_account_receivable = COLUMNS.at(4);
			expect(COLUMN_Total_account_receivable.text()).toEqual(DATA[idx].total_account_receivable);
			const COLUMN_Total_cash_in_of_current_month = COLUMNS.at(5);
			expect(COLUMN_Total_cash_in_of_current_month.text()).toEqual(DATA[idx].total_cash_in_of_current_month);
			const COLUMN_Total_cash_in_current = COLUMNS.at(6);
			expect(COLUMN_Total_cash_in_current.text()).toEqual(DATA[idx].total_cash_in_current);
			idx++;
		}

		wrapper.destroy();
	});

	test('Check render button download excel', async() => {
		const onClickExport = jest.fn();

		const localVue = createLocalVue();
		const wrapper = mount(CashInList, {
			localVue,
			store,
			router,
			methods: {
				onClickExport,
			},
		});

		const BUTTON_ADD = wrapper.find('.btn-excel');
		expect(BUTTON_ADD.exists()).toBe(true);

		await BUTTON_ADD.trigger('click');

		expect(onClickExport).toHaveBeenCalled();

		wrapper.destroy();
	});

	test('Check render button download excel', async() => {
		const onClickExport = jest.fn();

		const localVue = createLocalVue();
		const wrapper = mount(CashInList, {
			localVue,
			store,
			router,
			methods: {
				onClickExport,
			},
		});

		const BUTTON_ADD = wrapper.find('.btn-excel');
		expect(BUTTON_ADD.exists()).toBe(true);

		await BUTTON_ADD.trigger('click');

		expect(onClickExport).toHaveBeenCalled();

		wrapper.destroy();
	});

	test('Check render button detail', () => {
		const DATA = [
			{
				'customer_id': 1,
				'customer_code': '0001',
				'customer_name': 'Customer 01',
				'month_line': '2023-08',
				'balance_previous_month': '0.00',
				'receivable_this_month': '5000.00',
				'total_account_receivable': '5000.00',
				'total_cash_in_of_current_month': '3000.00',
				'total_cash_in_current': '2000.00',
			},
			{
				'customer_id': 2,
				'customer_code': '0002',
				'customer_name': 'Customer 02',
				'month_line': '2023-08',
				'balance_previous_month': '0.00',
				'receivable_this_month': '66000.00',
				'total_account_receivable': '66000.00',
				'total_cash_in_of_current_month': '0.00',
				'total_cash_in_current': '66000.00',
			},
		];

		const localVue = createLocalVue();
		const wrapper = mount(CashInList, {
			localVue,
			store,
			router,
			data() {
				return {
					listCash: DATA,
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

			const COLUMN_DETAIL = COLUMNS.at(7);
			expect(COLUMN_DETAIL.find('i').exists()).toBe(true);

			idx++;
		}

		wrapper.destroy();
	});

	test('Check click button detail', async() => {
		const DATA = [
			{
				'customer_id': 1,
				'customer_code': '0001',
				'customer_name': 'Customer 01',
				'month_line': '2023-08',
				'balance_previous_month': '0.00',
				'receivable_this_month': '5000.00',
				'total_account_receivable': '5000.00',
				'total_cash_in_of_current_month': '3000.00',
				'total_cash_in_current': '2000.00',
			},
			{
				'customer_id': 2,
				'customer_code': '0002',
				'customer_name': 'Customer 02',
				'month_line': '2023-08',
				'balance_previous_month': '0.00',
				'receivable_this_month': '66000.00',
				'total_account_receivable': '66000.00',
				'total_cash_in_of_current_month': '0.00',
				'total_cash_in_current': '66000.00',
			},
		];
		const onClickDetail = jest.fn();
		const localVue = createLocalVue();
		const wrapper = mount(CashInList, {
			localVue,
			store,
			router,
			data() {
				return {
					listCash: DATA,
				};
			},
			methods: {
				onClickDetail,
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

			const COLUMN_DETAIL = COLUMNS.at(7);
			const BUTTON_DETAIL = COLUMN_DETAIL.find('i');
			expect(BUTTON_DETAIL.exists()).toBe(true);

			await BUTTON_DETAIL.trigger('click');

			expect(onClickDetail).toHaveBeenCalled();

			idx++;
		}

		wrapper.destroy();
	});
});
