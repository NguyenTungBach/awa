import store from '@/store';
import router from '@/router';
import UserEdit from '@/pages/DataManagement/ListUser/edit';
import { mount, createLocalVue } from '@vue/test-utils';
import { validUsername, validateRole, validPassword } from '@/utils/validate';

describe('TEST COMPONENT USER EDIT', () => {
	test('Check render page', () => {
		const localVue = createLocalVue();
		const wrapper = mount(UserEdit, {
			localVue,
			store,
			router,
		});

		const PAGE = wrapper.find('.page-edit-user');
		expect(PAGE.exists()).toBe(true);

		wrapper.destroy();
	});

	test('Check render title page', () => {
		const localVue = createLocalVue();
		const wrapper = mount(UserEdit, {
			localVue,
			store,
			router,
		});

		const TITLE = wrapper.find('.title-page');
		expect(TITLE.text()).toEqual('EDIT_USER.TITLE_EDIT_USER');

		wrapper.destroy();
	});

	test('Check render button return', () => {
		const localVue = createLocalVue();
		const wrapper = mount(UserEdit, {
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
		const wrapper = mount(UserEdit, {
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

	test('Check render button save', () => {
		const localVue = createLocalVue();
		const wrapper = mount(UserEdit, {
			localVue,
			store,
			router,
		});

		const ZONE_CONTROL = wrapper.find('.zone-control');
		const BUTTON_SAVE = ZONE_CONTROL.find('.btn-save');

		expect(BUTTON_SAVE.exists()).toBe(true);
		expect(BUTTON_SAVE.text()).toEqual('APP.BUTTON_SAVE');

		wrapper.destroy();
	});

	test('Check click button save', async() => {
		const onClickSave = jest.fn();

		const localVue = createLocalVue();
		const wrapper = mount(UserEdit, {
			localVue,
			store,
			router,
			methods: {
				onClickSave,
			},
		});

		const ZONE_CONTROL = wrapper.find('.zone-control');
		const BUTTON_SAVE = ZONE_CONTROL.find('.btn-save');

		await BUTTON_SAVE.trigger('click');

		expect(onClickSave).toHaveBeenCalled();

		wrapper.destroy();
	});

	test('Check render form', () => {
		const localVue = createLocalVue();
		const wrapper = mount(UserEdit, {
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
				label: 'EDIT_USER.USER_ID',
				id: '#input-user-id',
			},
			{
				label: 'EDIT_USER.USERNAME',
				id: '#input-user-name',
			},
			{
				label: 'EDIT_USER.USER_AUTHORITY',
				id: '#input-user-authority',
			},
			{
				label: 'EDIT_USER.PASSWORD',
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

			if (idx === 0) {
				expect(INPUT.attributes('disabled')).toEqual('disabled');
			}

			idx++;
		}

		wrapper.destroy();
	});

	test('Check validate username', () => {
		expect(validUsername()).toBe(false);
		expect(validUsername(null)).toBe(false);
		expect(validUsername('')).toBe(false);
		expect(validUsername('V')).toBe(true);
		expect(validUsername('Vu')).toBe(true);
		expect(validUsername('VuDuc')).toBe(true);
		expect(validUsername('VuDucViet')).toBe(true);
	});

	test('Check validate user authority', () => {
		expect(validateRole()).toBe(false);
		expect(validateRole(null)).toBe(false);
		expect(validateRole('')).toBe(false);
		expect(validateRole('user')).toBe(false);
		expect(validateRole('role')).toBe(false);
		expect(validateRole('supperadmin')).toBe(false);
		expect(validateRole('admin')).toBe(true);
		expect(validateRole('driver')).toBe(true);
	});

	test('Check validate password', () => {
		expect(validPassword()).toBe(false);
		expect(validPassword(null)).toBe(false);
		expect(validPassword('1')).toBe(false);
		expect(validPassword('12')).toBe(false);
		expect(validPassword('123')).toBe(false);
		expect(validPassword('1234')).toBe(false);
		expect(validPassword('12345')).toBe(false);
		expect(validPassword('123456')).toBe(false);
		expect(validPassword('1234567')).toBe(false);
		expect(validPassword('12345678')).toBe(true);
		expect(validPassword('123456789')).toBe(true);
		expect(validPassword('1234567890')).toBe(true);
		expect(validPassword('12345678901')).toBe(true);
		expect(validPassword('123456789012')).toBe(true);
		expect(validPassword('1234567890123')).toBe(true);
		expect(validPassword('12345678901234')).toBe(true);
		expect(validPassword('123456789012345')).toBe(true);
		expect(validPassword('1234567890123456')).toBe(true);
		expect(validPassword('12345678901234567')).toBe(false);
	});
});
