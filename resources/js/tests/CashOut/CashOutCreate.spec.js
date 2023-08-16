import store from '@/store';
import router from '@/router';
import CashOutCreate from '@/pages/CashManagement/CashDisbursement/create';
import { mount, createLocalVue } from '@vue/test-utils';

describe('TEST COMPONENT CASH-OUT CREATE', () => {
	test('Check render page', () => {
		const localVue = createLocalVue();
		const wrapper = mount(CashOutCreate, {
			localVue,
			store,
			router,
		});

		const PAGE = wrapper.find('.page-cashDisbursement-create');
		expect(PAGE.exists()).toBe(true);

		wrapper.destroy();
	});

	test('Check render title page', () => {
		const localVue = createLocalVue();
		const wrapper = mount(CashOutCreate, {
			localVue,
			store,
			router,
		});

		const TITLE = wrapper.find('.title-page');
		expect(TITLE.text()).toEqual('LIST_CASH.TITLE_CASH_DISBURSEMENT_CREATE');

		wrapper.destroy();
	});

	test('Check render click button create', async() => {
		const handleOnClickSave = jest.fn();

		const localVue = createLocalVue();
		const wrapper = mount(CashOutCreate, {
			localVue,
			store,
			router,
			methods: {
				handleOnClickSave,
			},
		});

		const BUTTON_ADD = wrapper.find('.btn-save');
		expect(BUTTON_ADD.exists()).toBe(true);

		await BUTTON_ADD.trigger('click');

		expect(handleOnClickSave).toHaveBeenCalled();

		wrapper.destroy();
	});

	test('Check click button return', async() => {
		const onClickReturn = jest.fn();

		const localVue = createLocalVue();
		const wrapper = mount(CashOutCreate, {
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
		const localVue = createLocalVue();
		const wrapper = mount(CashOutCreate, {
			localVue,
			store,
			router,
		});
		const ZONE_FORM = wrapper.find('.body-form');
		expect(ZONE_FORM.exists()).toBe(true);

		const LIST_ITEM_FORM = wrapper.findAll('.item-form');
		expect(LIST_ITEM_FORM.length).toEqual(4);
		const FORM_Note = wrapper.find('#input-notes');
		expect(FORM_Note.exists()).toBe(true);

		wrapper.destroy();
	});
});
