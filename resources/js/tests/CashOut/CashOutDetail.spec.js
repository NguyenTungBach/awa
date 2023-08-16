import store from '@/store';
import router from '@/router';
import CashOutDetail from '@/pages/CashManagement/CashDisbursement/detail';
import { mount, createLocalVue } from '@vue/test-utils';

describe('TEST COMPONENT CASH-OUT DETAIL', () => {
	test('Check render page', () => {
		const localVue = createLocalVue();
		const wrapper = mount(CashOutDetail, {
			localVue,
			store,
			router,
		});

		const PAGE = wrapper.find('.page-cashDisbursement-detail__body');
		expect(PAGE.exists()).toBe(true);

		wrapper.destroy();
	});

	test('Check render title page', () => {
		const localVue = createLocalVue();
		const wrapper = mount(CashOutDetail, {
			localVue,
			store,
			router,
		});

		const TITLE = wrapper.find('.title-page');
		expect(TITLE.text()).toEqual('LIST_CASH.TITLE_CASH_DISBURSEMENT_DETAIL');

		wrapper.destroy();
	});

	test('Check render click button create', async() => {
		const onClickCreate = jest.fn();

		const localVue = createLocalVue();
		const wrapper = mount(CashOutDetail, {
			localVue,
			store,
			router,
			methods: {
				onClickCreate,
			},
		});

		const BUTTON_ADD = wrapper.find('.btn-edit');
		expect(BUTTON_ADD.exists()).toBe(true);

		await BUTTON_ADD.trigger('click');

		expect(onClickCreate).toHaveBeenCalled();

		wrapper.destroy();
	});

	test('Check click button return', async() => {
		const onClickReturn = jest.fn();

		const localVue = createLocalVue();
		const wrapper = mount(CashOutDetail, {
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
				'id': 1,
				'driver_id': 4,
				'payment_date': '2023/08/16',
				'cash_out': '1000.00',
				'payment_method': '銀行振込',
				'note': '',
			},
		];

		const localVue = createLocalVue();
		const wrapper = mount(CashOutDetail, {
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
			'LIST_CASH.TABLE_CASH_DISBURSEMENT_ID',
			'LIST_CASH.TABLE_CASH_DISBURSEMENT_NAME',
			'LIST_CASH.TABLE_CASH_DISBURSEMENT_CURRENT_MONTH_BALANCE',
			'LIST_CASH.TABLE_CASH_DISBURSEMENT_BALANCE_AT_END_OF_PREVIOUS_MONTH',
			'LIST_CASH.TABLE_CASH_DISBURSEMENT_ACCOUNTS_RECEIVABLE',
			'LIST_CASH.TABLE_CASH_DISBURSEMENT_TOTAL_ACCOUNTS_RECEIVABLE',
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

		expect(LIST_TH.at(0).text()).toEqual('LIST_CASH.TABLE_CASH_DISBURSEMENT_NO');
		expect(LIST_TH.at(1).text()).toEqual('LIST_CASH.TABLE_CASH_DISBURSEMENT_DATE');
		expect(LIST_TH.at(2).text()).toEqual('LIST_CASH.TABLE_CASH_DISBURSEMENT_DEPOSIT_AMOUNT');
		expect(LIST_TH.at(3).text()).toEqual('LIST_CASH.TABLE_CASH_DISBURSEMENT_PAYMENT_METHOD');
		expect(LIST_TH.at(4).text()).toEqual('LIST_CASH.TABLE_CASH_DISBURSEMENT_REMARKS');
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
			expect(COLUMN_Cash_in.text()).toEqual(Number(DATA[idx2].cash_out).toString());
			const COLUMN_Payment_method = COLUMNS.at(3);
			expect(COLUMN_Payment_method.text()).toEqual(DATA[idx2].payment_method);
			const COLUMN_Remark = COLUMNS.at(4);
			expect(COLUMN_Remark.text()).toEqual(DATA[idx2].note ?? '');
			idx2++;
		}

		wrapper.destroy();
	});

	test('Check click button edit delete', async() => {
		const DATA = [
			{
				'id': 1,
				'driver_id': 4,
				'payment_date': '2023/08/16',
				'cash_out': '1000.00',
				'payment_method': '銀行振込',
				'note': '',
			},
		];
		const onClickEdit = jest.fn();
		const onClickShowModalDelete = jest.fn();

		const localVue = createLocalVue();
		const wrapper = mount(CashOutDetail, {
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
