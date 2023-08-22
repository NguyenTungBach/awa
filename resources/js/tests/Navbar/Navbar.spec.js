import store from '@/store';
import router from '@/router';
import Navbar from '@/layout/components/Navbar';
import { mount, createLocalVue } from '@vue/test-utils';
import PickerYearMonth from '@/layout/components/PickerYearMonth';

describe('TEST COMPONENT MENU', () => {
	test('Check render navbar', () => {
		const localVue = createLocalVue();
		const wrapper = mount(Navbar, {
			localVue,
			store,
			router,
		});

		const NAVBAR = wrapper.find('.zone-navbar');
		expect(NAVBAR.exists()).toBe(true);

		wrapper.destroy();
	});

	test('Check render logo', () => {
		const localVue = createLocalVue();
		const wrapper = mount(Navbar, {
			localVue,
			store,
			router,
		});

		const NAVBAR = wrapper.find('.zone-navbar');
		const LOGO = NAVBAR.find('div.show-logo > img');

		expect(LOGO.exists()).toEqual(true);

		wrapper.destroy();
	});

	test('Check render menu', () => {
		const localVue = createLocalVue();
		const wrapper = mount(Navbar, {
			localVue,
			store,
			router,
		});

		const NAVBAR = wrapper.find('.zone-navbar');

		const MENU_LV1 = NAVBAR.find('.item-modules');

		expect(MENU_LV1.exists()).toBe(true);

		wrapper.destroy();
	});

	test('Check render component picker year month', () => {
		const localVue = createLocalVue();
		const wrapper = mount(Navbar, {
			localVue,
			store,
			router,
		});

		const NAVBAR = wrapper.find('.zone-navbar');

		const MENU_RIGTH = NAVBAR.find('.show-menu-right');
		const ZONE_PICKER_YEAR_MONTH = MENU_RIGTH.find('.picker-month-year');
		expect(ZONE_PICKER_YEAR_MONTH.exists()).toBe(true);

		wrapper.destroy();
	});

	test('Check click next and back in component year month', async() => {
		const onClickBack = jest.fn();
		const onClickNext = jest.fn();

		const localVue = createLocalVue();
		const wrapper = mount(PickerYearMonth, {
			localVue,
			store,
			router,
			methods: {
				onClickBack,
				onClickNext,
			},
		});

		const PICKER_YEAR_MONTH = wrapper.find('.picker-month-year');
		expect(PICKER_YEAR_MONTH.exists()).toBe(true);

		const BACK_BUTTON = PICKER_YEAR_MONTH.find('.picker-month-year__back');
		const NEXT_BUTTON = PICKER_YEAR_MONTH.find('.picker-month-year__next');

		expect(BACK_BUTTON.exists()).toBe(true);
		expect(NEXT_BUTTON.exists()).toBe(true);

		await BACK_BUTTON.trigger('click');
		expect(onClickBack).toHaveBeenCalled();

		await NEXT_BUTTON.trigger('click');
		expect(onClickNext).toHaveBeenCalled();

		wrapper.destroy();
	});

	test('Check render profile', () => {
		const localVue = createLocalVue();
		const wrapper = mount(Navbar, {
			localVue,
			store,
			router,
		});

		const NAVBAR = wrapper.find('.zone-navbar');
		const MENU_RIGTH = NAVBAR.find('.show-menu-right');

		const PROFILE = MENU_RIGTH.find('.show-profile');
		expect(PROFILE.exists()).toBe(true);

		const ICON_PROFILE = PROFILE.find('.icon-profile');
		expect(ICON_PROFILE.exists()).toBe(true);

		const USERNAME = PROFILE.find('.username');
		expect(USERNAME.exists()).toBe(true);

		const ICON_DROPDOWN = PROFILE.find('.icon-dropdown');
		expect(ICON_DROPDOWN.exists()).toBe(true);

		wrapper.destroy();
	});

	test('Check render dropdown logout', () => {
		const localVue = createLocalVue();
		const wrapper = mount(Navbar, {
			localVue,
			store,
			router,
		});

		const NAVBAR = wrapper.find('.zone-navbar');
		const MENU_RIGTH = NAVBAR.find('.show-menu-right');

		const PROFILE = MENU_RIGTH.find('.show-profile');
		expect(PROFILE.exists()).toBe(true);

		const LOGOUT = PROFILE.find('.menu-profile');
		expect(LOGOUT.exists()).toBe(true);

		wrapper.destroy();
	});

	test('Check click logout', async() => {
		const onClickLogout = jest.fn();

		const localVue = createLocalVue();
		const wrapper = mount(Navbar, {
			localVue,
			store,
			router,
			methods: {
				onClickLogout,
			},
		});

		const NAVBAR = wrapper.find('.zone-navbar');
		const MENU_RIGTH = NAVBAR.find('.show-menu-right');
		const PROFILE = MENU_RIGTH.find('.show-profile');
		const LOGOUT = PROFILE.find('.menu-profile');

		const BUTTON_LOGOUT = LOGOUT.find('li');
		expect(BUTTON_LOGOUT.exists()).toBe(true);

		await BUTTON_LOGOUT.trigger('click');
		expect(onClickLogout).toHaveBeenCalled();

		wrapper.destroy();
	});
});
