import store from '@/store';
import router from '@/router';
import UserDetail from '@/pages/DataManagement/ListUser/detail';
import { mount, createLocalVue } from '@vue/test-utils';

describe('TEST COMPONENT USER DETAIL', () => {
	test('Check render page', () => {
		const localVue = createLocalVue();
		const wrapper = mount(UserDetail, {
			localVue,
			store,
			router,
		});

		const PAGE = wrapper.find('.page-detail-user');
		expect(PAGE.exists()).toBe(true);

		wrapper.destroy();
	});

	test('Check render title page', () => {
		const localVue = createLocalVue();
		const wrapper = mount(UserDetail, {
			localVue,
			store,
			router,
		});

		const TITLE = wrapper.find('.title-page');
		expect(TITLE.text()).toEqual('DETAIL_USER.TITLE_DETAIL_USER');

		wrapper.destroy();
	});

	test('Check render button return', () => {
		const localVue = createLocalVue();
		const wrapper = mount(UserDetail, {
			localVue,
			store,
			router,
		});

		const ZONE_CONTROL = wrapper.find('.zone-control');
		const BUTTON_RETURN = ZONE_CONTROL.find('.btn-return');

		expect(BUTTON_RETURN.exists()).toBe(true);
		expect(BUTTON_RETURN.text()).toEqual('APP.BUTTON_RETURN');

		wrapper.destroy();
	});

	test('Check click button return', async() => {
		const onClickReturn = jest.fn();

		const localVue = createLocalVue();
		const wrapper = mount(UserDetail, {
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

	test('Check render button edit', () => {
		const localVue = createLocalVue();
		const wrapper = mount(UserDetail, {
			localVue,
			store,
			router,
		});

		const ZONE_CONTROL = wrapper.find('.zone-control');
		const BUTTON_EDIT = ZONE_CONTROL.find('.btn-edit');

		expect(BUTTON_EDIT.exists()).toBe(true);
		expect(BUTTON_EDIT.text()).toEqual('APP.BUTTON_EDIT');

		wrapper.destroy();
	});

	test('Check click button edit', async() => {
		const onClickEdit = jest.fn();

		const localVue = createLocalVue();
		const wrapper = mount(UserDetail, {
			localVue,
			store,
			router,
			methods: {
				onClickEdit,
			},
		});

		const ZONE_CONTROL = wrapper.find('.zone-control');
		const BUTTON_EDIT = ZONE_CONTROL.find('.btn-edit');

		await BUTTON_EDIT.trigger('click');

		expect(onClickEdit).toHaveBeenCalled();

		wrapper.destroy();
	});

	test('Check render form', () => {
		const localVue = createLocalVue();
		const wrapper = mount(UserDetail, {
			localVue,
			store,
			router,
		});

		const ZONE_FORM = wrapper.find('.zone-form');
		const FORM_BODY = ZONE_FORM.find('.form-body');

		const AVATAR = FORM_BODY.find('.avatar-user');
		expect(AVATAR.exists()).toBe(true);

		const LIST_ITEM_FORM = FORM_BODY.findAll('.item-form');
		expect(LIST_ITEM_FORM.length).toEqual(4);

		const CONST_FORM = [
			{
				label: 'DETAIL_USER.USER_ID',
				id: '#input-user-id',
			},
			{
				label: 'DETAIL_USER.USERNAME',
				id: '#input-user-name',
			},
			{
				label: 'DETAIL_USER.USER_AUTHORITY',
				id: '#input-user-authority',
			},
			{
				label: 'DETAIL_USER.PASSWORD',
				id: '#input-user-password',
			},
		];

		let idx = 0;
		const len = LIST_ITEM_FORM.length;

		while (idx < len) {
			const ITEM = LIST_ITEM_FORM.at(idx);

			const LABEL = ITEM.find('label');
			expect(LABEL.text()).toEqual(CONST_FORM[idx].label);

			const INPUT = ITEM.find(CONST_FORM[idx].id);
			expect(INPUT.exists()).toBe(true);

			expect(INPUT.attributes('disabled')).toEqual('disabled');

			idx++;
		}

		wrapper.destroy();
	});
});
