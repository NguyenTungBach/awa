import store from '@/store';
import router from '@/router';
import CashOutList from '@/pages/CashManagement/CashDisbursement/index';
import { mount, createLocalVue } from '@vue/test-utils';

describe('TEST COMPONENT CASH-OUT LIST', () => {
	test('Check render page', () => {
		const localVue = createLocalVue();
		const wrapper = mount(CashOutList, {
			localVue,
			store,
			router,
		});

		const PAGE = wrapper.find('.page-list-cashDisbursement');
		expect(PAGE.exists()).toBe(true);

		wrapper.destroy();
	});

	test('Check render header table', () => {
		const localVue = createLocalVue();
		const wrapper = mount(CashOutList, {
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

		expect(LIST_TH.at(0).text()).toEqual('LIST_CASH.TABLE_CASH_DISBURSEMENT_ID');
		expect(LIST_TH.at(1).text()).toEqual('LIST_CASH.TABLE_CASH_DISBURSEMENT_NAME');
		expect(LIST_TH.at(2).text()).toEqual('LIST_CASH.TABLE_CASH_DISBURSEMENT_BALANCE_AT_END_OF_PREVIOUS_MONTH');
		expect(LIST_TH.at(3).text()).toEqual('LIST_CASH.TABLE_CASH_DISBURSEMENT_ACCOUNTS_RECEIVABLE');
		expect(LIST_TH.at(4).text()).toEqual('LIST_CASH.TABLE_CASH_DISBURSEMENT_TOTAL_ACCOUNTS_RECEIVABLE');
		expect(LIST_TH.at(5).text()).toEqual('LIST_CASH.TABLE_CASH_DISBURSEMENT_MONTHLY_DEPOSIT_AMOUNT');
		expect(LIST_TH.at(6).text()).toEqual('LIST_CASH.TABLE_CASH_DISBURSEMENT_CURRENT_MONTH_BALANCE');
		expect(LIST_TH.at(7).text()).toEqual('LIST_CASH.TABLE_DETAIL');

		const TBODY = TABLE.find('tbody');
		expect(TBODY.exists()).toBe(true);

		// console.log(TABLE.html());

		wrapper.destroy();
	});

	test('Check render body table', () => {
		const DATA = [
			{
				'id': 4,
				'type': 4,
				'driver_code': '0004',
				'driver_name': 'Associate company',
				'month_line': '2023-08',
				'balance_previous_month': '0.00',
				'payable_this_month': '40.00',
				'total_payable': 40,
				'total_cash_out_current': '0.00',
				'balance_current': 40,
			},
		];

		const localVue = createLocalVue();
		const wrapper = mount(CashOutList, {
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

			const COLUMN_DRIVER_CODE = COLUMNS.at(0);
			expect(COLUMN_DRIVER_CODE.text()).toEqual(DATA[idx].driver_code);
			const COLUMN_DRIVER_NAME = COLUMNS.at(1);
			expect(COLUMN_DRIVER_NAME.text()).toEqual(DATA[idx].driver_name);
			const COLUMN_Balance_Previous_Month = COLUMNS.at(2);
			expect(COLUMN_Balance_Previous_Month.text()).toEqual(DATA[idx].balance_previous_month);
			const COLUMN_Payable_this_month = COLUMNS.at(3);
			expect(COLUMN_Payable_this_month.text()).toEqual(DATA[idx].payable_this_month);
			const COLUMN_Total_payable = COLUMNS.at(4);
			expect(COLUMN_Total_payable.text()).toEqual(DATA[idx].total_payable.toString());
			const COLUMN_Total_cash_out_current = COLUMNS.at(5);
			expect(COLUMN_Total_cash_out_current.text()).toEqual(DATA[idx].total_cash_out_current);
			const COLUMN_Balance_current = COLUMNS.at(6);
			expect(COLUMN_Balance_current.text()).toEqual(DATA[idx].balance_current.toString());
			idx++;
		}

		wrapper.destroy();
	});

	test('Check render button download excel', async() => {
		const onClickExport = jest.fn();

		const localVue = createLocalVue();
		const wrapper = mount(CashOutList, {
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
		const wrapper = mount(CashOutList, {
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
				'id': 4,
				'type': 4,
				'driver_code': '0004',
				'driver_name': 'Associate company',
				'month_line': '2023-08',
				'balance_previous_month': '0.00',
				'payable_this_month': '40.00',
				'total_payable': 40,
				'total_cash_out_current': '0.00',
				'balance_current': 40,
			},
		];

		const localVue = createLocalVue();
		const wrapper = mount(CashOutList, {
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
});
