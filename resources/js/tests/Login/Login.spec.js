import store from '@/store';
import router from '@/router';
import Login from '@/pages/Login/index';
import { mount, createLocalVue } from '@vue/test-utils';
import { validateUserID, validPassword } from '@/utils/validate';

describe('TEST COMPONENT LOGIN', () => {
	test('Check render page login', () => {
		const localVue = createLocalVue();
		const wrapper = mount(Login, {
			localVue,
			store,
			router,
		});

		const PAGE = wrapper.find('.login-page');
		expect(PAGE.exists()).toBe(true);

		wrapper.destroy();
	});

	test('Check render title login', () => {
		const localVue = createLocalVue();
		const wrapper = mount(Login, {
			localVue,
			store,
			router,
		});

		const PAGE_LOGIN = wrapper.find('.login-page');
		const TITLE = PAGE_LOGIN.find('.title-login');

		expect(TITLE.exists()).toBe(true);
		expect(TITLE.text()).toBe('LOGIN.TITLE_LOGIN');

		wrapper.destroy();
	});

	test('Check render input user_id and password', () => {
		const localVue = createLocalVue();
		const wrapper = mount(Login, {
			localVue,
			store,
			router,
		});

		const PAGE_LOGIN = wrapper.find('.login-page');

		const INPUT_USER_ID = PAGE_LOGIN.find('.input-user-id');
		const INPUT_PASSWORD = PAGE_LOGIN.find('.input-password');

		expect(INPUT_USER_ID.exists()).toBe(true);
		expect(INPUT_PASSWORD.exists()).toBe(true);

		const ICON_USER_ID = INPUT_USER_ID.find('.fa-user-alt');
		const ICON_PASSWORD = INPUT_PASSWORD.find('.fa-key');

		expect(ICON_USER_ID.exists()).toBe(true);
		expect(ICON_PASSWORD.exists()).toBe(true);

		const USER_ID = INPUT_USER_ID.find('input');
		const PASSWORD = INPUT_PASSWORD.find('input');

		expect(USER_ID.attributes('placeholder')).toEqual('LOGIN.PLACEHOLDER_USER_ID');
		expect(PASSWORD.attributes('placeholder')).toEqual('LOGIN.PLACEHOLDER_USER_PASSWORD');

		const TYPE_INPUT_PASSWORD = PASSWORD.attributes('type');
		expect(TYPE_INPUT_PASSWORD).toEqual('password');

		wrapper.destroy();
	});

	test('Check render button login', () => {
		const localVue = createLocalVue();
		const wrapper = mount(Login, {
			localVue,
			store,
			router,
		});

		const PAGE_LOGIN = wrapper.find('.login-page');
		const BUTTON_LOGIN = PAGE_LOGIN.find('.btn-submit');

		expect(BUTTON_LOGIN.exists()).toBe(true);

		const TEXT_BUTTON_LOGIN = BUTTON_LOGIN.find('span').text();
		expect(TEXT_BUTTON_LOGIN).toBe('LOGIN.BUTTON_LOGIN');

		wrapper.destroy();
	});

	test('Check click button login', async() => {
		const clickLogin = jest.fn();

		const localVue = createLocalVue();
		const wrapper = mount(Login, {
			localVue,
			store,
			router,
			methods: {
				clickLogin,
			},
		});

		const PAGE_LOGIN = wrapper.find('.login-page');
		const BUTTON_LOGIN = PAGE_LOGIN.find('.btn-submit');

		expect(BUTTON_LOGIN.exists()).toBe(true);
		await BUTTON_LOGIN.trigger('click');
		expect(clickLogin).toHaveBeenCalled();

		wrapper.destroy();
	});

	test('Check function validate user_id', () => {
		expect(validateUserID('')).toBe(false);
		expect(validateUserID('!@#!@#!@#')).toBe(false);
		expect(validateUserID('user_id')).toBe(false);
		expect(validateUserID('  ')).toBe(false);
		expect(validateUserID(' 1234 ')).toBe(false);
		expect(validateUserID('1a3a')).toBe(false);

		expect(validateUserID('1')).toBe(true);
		expect(validateUserID('12')).toBe(true);
		expect(validateUserID('123')).toBe(true);
		expect(validateUserID('1234')).toBe(true);
	});

	test('Check function validate password', () => {
		expect(validPassword('')).toBe(false);
		expect(validPassword('           ')).toBe(false);
		expect(validPassword('password or 1 = 1')).toBe(false);
		expect(validPassword('12345678901234567')).toBe(false);
		expect(validPassword('12345678 123')).toBe(false);

		expect(validPassword('12345678')).toBe(true);
		expect(validPassword('12a34a5a6a78')).toBe(true);
		expect(validPassword('1234567890123456')).toBe(true);
	});
});
