import store from '@/store';
import router from '@/router';
import CashInDetail from '@/pages/CashManagement/CashReceipt/detail';
import { mount, createLocalVue } from '@vue/test-utils';

describe('TEST COMPONENT CASH-IN DETAIL', () => {
	test('Check render page', () => {
		const localVue = createLocalVue();
		const wrapper = mount(CashInDetail, {
			localVue,
			store,
			router,
		});

		const PAGE = wrapper.find('.page-cash-detail__body');
		expect(PAGE.exists()).toBe(true);

		wrapper.destroy();
	});

	test('Check render title page', () => {
		const localVue = createLocalVue();
		const wrapper = mount(CashInDetail, {
			localVue,
			store,
			router,
		});

		const TITLE = wrapper.find('.title-page');
		expect(TITLE.text()).toEqual('LIST_CASH.TITLE_CASH_DETAIL');

		wrapper.destroy();
	});

	test('Check render click button create', async() => {
		const onClickExport = jest.fn();

		const localVue = createLocalVue();
		const wrapper = mount(CashInDetail, {
			localVue,
			store,
			router,
			methods: {
				onClickExport,
			},
		});

		const BUTTON_ADD = wrapper.find('.btn-edit');
		expect(BUTTON_ADD.exists()).toBe(true);

		await BUTTON_ADD.trigger('click');

		expect(onClickExport).toHaveBeenCalled();

		wrapper.destroy();
	});

	test('Check click button return', async() => {
		const onClickReturn = jest.fn();

		const localVue = createLocalVue();
		const wrapper = mount(CashInDetail, {
			localVue,
			store,
			router,
			methods: {
				onClickReturn,
			},
		});

		const ZONE_CONTROL = wrapper.find('.zone-control');
		const BUTTON_RETURN = ZONE_CONTROL.find('.btn-return');

		await BUTTON_RETURN.trigger('click');

		expect(onClickReturn).toHaveBeenCalled();

		wrapper.destroy();
	});

	test('Check render form', () => {
		const DATA = [
			{
				'id': 3,
				'customer_id': 1,
				'cash_in': '2500.00',
				'payment_method': 1,
				'payment_date': '2023-08-11',
				'note': null,
				'status': 1,
				'created_at': '2023-08-03T02:05:44.000000Z',
				'updated_at': '2023-08-03T02:05:44.000000Z',
				'deleted_at': null,
				'customer': {
					'id': 1,
					'customer_code': '0001',
					'customer_name': 'Customer 01',
					'closing_date': 1,
					'person_charge': 'Person charge 01',
					'post_code': '123-4567',
					'address': 'Address 01',
					'phone': '01212341234',
					'status': null,
					'closing_dateName': '15日',
				},
			},
			{
				'id': 4,
				'customer_id': 1,
				'cash_in': '100.00',
				'payment_method': 1,
				'payment_date': '2023-08-14',
				'note': null,
				'status': 1,
				'created_at': '2023-08-04T03:11:16.000000Z',
				'updated_at': '2023-08-04T03:11:16.000000Z',
				'deleted_at': null,
				'customer': {
					'id': 1,
					'customer_code': '0001',
					'customer_name': 'Customer 01',
					'closing_date': 1,
					'person_charge': 'Person charge 01',
					'post_code': '123-4567',
					'address': 'Address 01',
					'phone': '01212341234',
					'status': null,
					'closing_dateName': '15日',
				},
			},
		];

		const localVue = createLocalVue();
		const wrapper = mount(CashInDetail, {
			localVue,
			store,
			router,
			data() {
				return {
					listCashDeital: DATA,
				};
			},
		});
		// Total
		const ZONE_FORM = wrapper.find('.zone-form');
		const FORM_BODY = ZONE_FORM.find('.zone-form__body');

		const LIST_ITEM_FORM = FORM_BODY.findAll('.item-form');
		expect(LIST_ITEM_FORM.length).toEqual(6);

		const CONST_FORM = [
			'LIST_CASH.TABLE_CASH_ID',
			'LIST_CASH.TABLE_CASH_NAME',
			'LIST_CASH.TABLE_CURRENT_MONTH_BALANCE',
			'LIST_CASH.TABLE_CASH_BALANCE_AT_END_OF_PREVIOUS_MONTH',
			'LIST_CASH.TABLE_CASH_ACCOUNTS_RECEIVABLE',
			'LIST_CASH.TABLE_TOTAL_ACCOUNTS_RECEIVABLE',
		];

		let idx = 0;
		const len = LIST_ITEM_FORM.length;

		while (idx < len) {
			// Tìm theo từng dòng
			const ITEM = LIST_ITEM_FORM.at(idx);

			const LABEL = ITEM.find('.text-label');
			expect(LABEL.text()).toEqual(CONST_FORM[idx]);

			// const INPUT = ITEM.find(CONST_FORM[idx].id);
			const INPUT = ITEM.find('.text-right');
			expect(INPUT.exists()).toBe(true);

			// expect(INPUT.attributes('disabled')).toEqual('disabled');

			idx++;
		}

		// Table header
		const TABLE = wrapper.find('.table_detail_cash');

		const THEAD = TABLE.find('thead');
		expect(THEAD.exists()).toBe(true);

		// Kiểm tra các trường
		const LIST_TH = THEAD.findAll('th');
		expect(LIST_TH.length).toEqual(7);

		expect(LIST_TH.at(0).text()).toEqual('LIST_CASH.TABLE_NO');
		expect(LIST_TH.at(1).text()).toEqual('LIST_CASH.TABLE_DATE');
		expect(LIST_TH.at(2).text()).toEqual('LIST_CASH.TABLE_DEPOSIT_AMOUNT');
		expect(LIST_TH.at(3).text()).toEqual('LIST_CASH.TABLE_PAYMENT_METHOD');
		expect(LIST_TH.at(4).text()).toEqual('LIST_CASH.TABLE_REMARKS');
		expect(LIST_TH.at(5).text()).toEqual('LIST_CASH.TABLE_EDIT');
		expect(LIST_TH.at(6).text()).toEqual('LIST_CASH.TABLE_DELETE');

		// Table Body
		const TBODY = TABLE.find('tbody');
		expect(TBODY.exists()).toBe(true);

		const ROWS = TBODY.findAll('tr');

		const len2 = DATA.length;
		let idx2 = 0;

		// eslint-disable-next-line no-unmodified-loop-condition
		while (idx2 < len2) {
			const ROW = ROWS.at(idx2);

			const COLUMNS = ROW.findAll('td');

			const COLUMN_NO = COLUMNS.at(0);
			expect(COLUMN_NO.text()).toEqual((idx2 + 1).toString());
			const COLUMN_DATE = COLUMNS.at(1);
			expect(COLUMN_DATE.text()).toEqual(DATA[idx2].payment_date);
			const COLUMN_Cash_in = COLUMNS.at(2);
			expect(COLUMN_Cash_in.text()).toEqual(Number(DATA[idx2].cash_in).toString());
			const COLUMN_Payment_method = COLUMNS.at(3);
			expect(COLUMN_Payment_method.text()).toEqual(DATA[idx2].payment_method === 1 ? '銀行振込' : '振込');
			const COLUMN_Remark = COLUMNS.at(4);
			expect(COLUMN_Remark.text()).toEqual(DATA[idx2].note ?? '');
			idx2++;
		}

		wrapper.destroy();
	});

	test('Check click button edit delete', async() => {
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
		const onClickEdit = jest.fn();
		const onClickShowModalDelete = jest.fn();

		const localVue = createLocalVue();
		const wrapper = mount(CashInDetail, {
			localVue,
			store,
			router,
			data() {
				return {
					listCash: DATA,
				};
			},
			methods: {
				onClickEdit,
				onClickShowModalDelete,
			},
		});

		const TABLE = wrapper.find('.table_detail_cash');
		const TBODY = TABLE.find('tbody');

		const ROWS = TBODY.findAll('tr');

		const len = DATA.length;
		let idx = 0;

		while (idx < len) {
			const ROW = ROWS.at(idx);

			const COLUMNS = ROW.findAll('td');

			// edit
			const COLUMN_EDIT = COLUMNS.at(5);
			const BUTTON_EDIT = COLUMN_EDIT.find('i');
			expect(BUTTON_EDIT.exists()).toBe(true);

			await BUTTON_EDIT.trigger('click');

			expect(onClickEdit).toHaveBeenCalled();

			// delete
			const COLUMN_DELETE = COLUMNS.at(6);
			const BUTTON_DELETE = COLUMN_DELETE.find('i');
			expect(BUTTON_DELETE.exists()).toBe(true);

			await BUTTON_DELETE.trigger('click');

			expect(onClickShowModalDelete).toHaveBeenCalled();

			idx++;
		}

		wrapper.destroy();
	});
});
