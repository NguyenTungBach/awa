import store from '@/store';
import router from '@/router';
import DriverEdit from '@/pages/DataManagement/ListDriver/edit';
import { mount, createLocalVue } from '@vue/test-utils';
import { validateDriver } from '@/utils/validateCRUD';

describe('TEST COMPONENT DRIVER EDIT', () => {
	test('Check render page', () => {
		const localVue = createLocalVue();
		const wrapper = mount(DriverEdit, {
			localVue,
			store,
			router,
		});

		const PAGE = wrapper.find('.page-create-driver');
		expect(PAGE.exists()).toBe(true);

		wrapper.destroy();
	});

	test('Check render title page', () => {
		const localVue = createLocalVue();
		const wrapper = mount(DriverEdit, {
			localVue,
			store,
			router,
		});

		const TITLE = wrapper.find('.title-page');
		expect(TITLE.text()).toBe('EDIT_DRIVER.TITLE_EDIT_DRIVER');

		wrapper.destroy();
	});

	test('Check render button return', () => {
		const localVue = createLocalVue();
		const wrapper = mount(DriverEdit, {
			localVue,
			store,
			router,
		});

		const BUTTON = wrapper.find('.btn-return');
		expect(BUTTON.exists()).toBe(true);
		expect(BUTTON.text()).toEqual('APP.BUTTON_RETURN');

		wrapper.destroy();
	});

	test('Check click button return', async() => {
		const onClickReturn = jest.fn();

		const localVue = createLocalVue();
		const wrapper = mount(DriverEdit, {
			localVue,
			store,
			router,
			methods: {
				onClickReturn,
			},
		});

		const BUTTON = wrapper.find('.btn-return');
		await BUTTON.trigger('click');
		expect(onClickReturn).toHaveBeenCalled();

		wrapper.destroy();
	});

	test('Check render button save', () => {
		const localVue = createLocalVue();
		const wrapper = mount(DriverEdit, {
			localVue,
			store,
			router,
		});

		const BUTTON = wrapper.find('.btn-save');
		expect(BUTTON.exists()).toBe(true);
		expect(BUTTON.text()).toEqual('APP.BUTTON_SAVE');

		wrapper.destroy();
	});

	test('Check click button save', async() => {
		const onClickSaveDriver = jest.fn();

		const localVue = createLocalVue();
		const wrapper = mount(DriverEdit, {
			localVue,
			store,
			router,
			methods: {
				onClickSaveDriver,
			},
		});

		const BUTTON = wrapper.find('.btn-save');
		await BUTTON.trigger('click');
		expect(onClickSaveDriver).toHaveBeenCalled();

		wrapper.destroy();
	});

	test('Check render form', () => {
		const localVue = createLocalVue();
		const wrapper = mount(DriverEdit, {
			localVue,
			store,
			router,
		});

		const TITLE = wrapper.find('.title-path-form');
		expect(TITLE.exists()).toBe(true);
		expect(TITLE.text()).toEqual('CREATE_DRIVER.FORM_PATH_BASIC_INFORMATION');

		wrapper.destroy();
	});

	test('Check function validate edit', () => {
		const localVue = createLocalVue();
		const wrapper = mount(DriverEdit, {
			localVue,
			store,
			router,
		});

		wrapper.destroy();
	});

	test('Check function validate edit', () => {
		expect(validateDriver({})).toStrictEqual({ 'message': 'MESSAGE_APP.DRIVER_MANAGEMENT_VALIDATE_REQUIRED', 'status': false });
		expect(validateDriver({
			driver_code: '123',
		})).toStrictEqual({ 'message': 'MESSAGE_APP.DRIVER_MANAGEMENT_VALIDATE_REQUIRED', 'status': false });
		expect(validateDriver({
			driver_code: '123',
			driver_name: 'ABC',
		})).toStrictEqual({ 'message': 'MESSAGE_APP.DRIVER_MANAGEMENT_VALIDATE_REQUIRED', 'status': false });
		expect(validateDriver({
			driver_code: '123',
			driver_name: 'ABC',
			start_date: '2022-01-01',
		})).toStrictEqual({ 'message': 'MESSAGE_APP.DRIVER_MANAGEMENT_VALIDATE_REQUIRED', 'status': false });
		expect(validateDriver({
			driver_code: '123',
			driver_name: 'ABC',
			start_date: '2022-01-01',
			birth_day: '2000-01-01',
		})).toStrictEqual({ 'message': 'MESSAGE_APP.DRIVER_MANAGEMENT_VALIDATE_REQUIRED', 'status': false });
		expect(validateDriver({
			driver_code: '123',
			driver_name: 'ABC',
			start_date: '2022-01-01',
			birth_day: '2000-01-01',
			working_day: '2',
		})).toStrictEqual({ 'message': null, 'status': true });
		expect(validateDriver({
			driver_code: '123.1',
			driver_name: 'ABC',
			start_date: '2022-01-01',
			birth_day: '2000-01-01',
			working_day: '2',
		})).toStrictEqual({ 'message': 'MESSAGE_APP.DRIVER_MANAGEMENT_VALIDATE_DRIVER_CODE', 'status': false });
		expect(validateDriver({
			driver_code: '1231',
			driver_name: '123',
			start_date: '2022-01-01',
			birth_day: '2000-01-01',
			working_day: '2',
		})).toStrictEqual({ 'message': null, 'status': true });
		expect(validateDriver({
			driver_code: '1231',
			driver_name: '123123123123123123123123123123123123123',
			start_date: '2022-01-01',
			birth_day: '2000-01-01',
			working_day: '2',
		})).toStrictEqual({ 'message': 'MESSAGE_APP.DRIVER_MANAGEMENT_VALIDATE_DRIVER_NAME', 'status': false });
		expect(validateDriver({
			driver_code: '1231',
			driver_name: '123123123123',
			start_date: '2022-01-01',
			birth_day: '2000-01-01',
			working_day: '',
		})).toStrictEqual({ 'message': 'MESSAGE_APP.DRIVER_MANAGEMENT_VALIDATE_REQUIRED', 'status': false });
	});

	test('Check validate fullname', () => {
		expect(validateDriver({})).toStrictEqual({ 'message': 'MESSAGE_APP.DRIVER_MANAGEMENT_VALIDATE_REQUIRED', 'status': false });
		expect(validateDriver({
			driver_code: '123',
		})).toStrictEqual({ 'message': 'MESSAGE_APP.DRIVER_MANAGEMENT_VALIDATE_REQUIRED', 'status': false });
		expect(validateDriver({
			driver_code: '123',
			driver_name: 'ABC',
		})).toStrictEqual({ 'message': 'MESSAGE_APP.DRIVER_MANAGEMENT_VALIDATE_REQUIRED', 'status': false });
		expect(validateDriver({
			driver_code: '123',
			driver_name: 'ABC',
			start_date: '2022-01-01',
		})).toStrictEqual({ 'message': 'MESSAGE_APP.DRIVER_MANAGEMENT_VALIDATE_REQUIRED', 'status': false });
		expect(validateDriver({
			driver_code: '123',
			driver_name: 'ABC',
			start_date: '2022-01-01',
			birth_day: '2000-01-01',
		})).toStrictEqual({ 'message': 'MESSAGE_APP.DRIVER_MANAGEMENT_VALIDATE_REQUIRED', 'status': false });
		expect(validateDriver({
			driver_code: '123',
			driver_name: 'ABC',
			start_date: '2022-01-01',
			birth_day: '2000-01-01',
			working_day: '2',
		})).toStrictEqual({ 'message': null, 'status': true });
		expect(validateDriver({
			driver_code: '123.1',
			driver_name: 'ABC',
			start_date: '2022-01-01',
			birth_day: '2000-01-01',
			working_day: '2',
		})).toStrictEqual({ 'message': 'MESSAGE_APP.DRIVER_MANAGEMENT_VALIDATE_DRIVER_CODE', 'status': false });
		expect(validateDriver({
			driver_code: '1231',
			driver_name: '123',
			start_date: '2022-01-01',
			birth_day: '2000-01-01',
			working_day: '2',
		})).toStrictEqual({ 'message': null, 'status': true });
		expect(validateDriver({
			driver_code: '1231',
			driver_name: '123123123123123123123123123123123123123',
			start_date: '2022-01-01',
			birth_day: '2000-01-01',
			working_day: '2',
		})).toStrictEqual({ 'message': 'MESSAGE_APP.DRIVER_MANAGEMENT_VALIDATE_DRIVER_NAME', 'status': false });
		expect(validateDriver({
			driver_code: '1231',
			driver_name: '123123123123',
			start_date: '2022-01-01',
			birth_day: '2000-01-01',
			working_day: '',
		})).toStrictEqual({ 'message': 'MESSAGE_APP.DRIVER_MANAGEMENT_VALIDATE_REQUIRED', 'status': false });
	});

	test('Check validate hiredate', () => {
		expect(validateDriver({})).toStrictEqual({ 'message': 'MESSAGE_APP.DRIVER_MANAGEMENT_VALIDATE_REQUIRED', 'status': false });
		expect(validateDriver({
			driver_code: '123',
		})).toStrictEqual({ 'message': 'MESSAGE_APP.DRIVER_MANAGEMENT_VALIDATE_REQUIRED', 'status': false });
		expect(validateDriver({
			driver_code: '123',
			driver_name: 'ABC',
		})).toStrictEqual({ 'message': 'MESSAGE_APP.DRIVER_MANAGEMENT_VALIDATE_REQUIRED', 'status': false });
		expect(validateDriver({
			driver_code: '123',
			driver_name: 'ABC',
			start_date: '2022-01-01',
		})).toStrictEqual({ 'message': 'MESSAGE_APP.DRIVER_MANAGEMENT_VALIDATE_REQUIRED', 'status': false });
		expect(validateDriver({
			driver_code: '123',
			driver_name: 'ABC',
			start_date: '2022-01-01',
			birth_day: '2000-01-01',
		})).toStrictEqual({ 'message': 'MESSAGE_APP.DRIVER_MANAGEMENT_VALIDATE_REQUIRED', 'status': false });
		expect(validateDriver({
			driver_code: '123',
			driver_name: 'ABC',
			start_date: '2022-01-01',
			birth_day: '2000-01-01',
			working_day: '2',
		})).toStrictEqual({ 'message': null, 'status': true });
		expect(validateDriver({
			driver_code: '123.1',
			driver_name: 'ABC',
			start_date: '2022-01-01',
			birth_day: '2000-01-01',
			working_day: '2',
		})).toStrictEqual({ 'message': 'MESSAGE_APP.DRIVER_MANAGEMENT_VALIDATE_DRIVER_CODE', 'status': false });
		expect(validateDriver({
			driver_code: '1231',
			driver_name: '123',
			start_date: '2022-01-01',
			birth_day: '2000-01-01',
			working_day: '2',
		})).toStrictEqual({ 'message': null, 'status': true });
		expect(validateDriver({
			driver_code: '1231',
			driver_name: '123123123123123123123123123123123123123',
			start_date: '2022-01-01',
			birth_day: '2000-01-01',
			working_day: '2',
		})).toStrictEqual({ 'message': 'MESSAGE_APP.DRIVER_MANAGEMENT_VALIDATE_DRIVER_NAME', 'status': false });
		expect(validateDriver({
			driver_code: '1231',
			driver_name: '123123123123',
			start_date: '2022-01-01',
			birth_day: '2000-01-01',
			working_day: '',
		})).toStrictEqual({ 'message': 'MESSAGE_APP.DRIVER_MANAGEMENT_VALIDATE_REQUIRED', 'status': false });
	});

	test('Check validate date of birth', () => {
		expect(validateDriver({})).toStrictEqual({ 'message': 'MESSAGE_APP.DRIVER_MANAGEMENT_VALIDATE_REQUIRED', 'status': false });
		expect(validateDriver({
			driver_code: '123',
		})).toStrictEqual({ 'message': 'MESSAGE_APP.DRIVER_MANAGEMENT_VALIDATE_REQUIRED', 'status': false });
		expect(validateDriver({
			driver_code: '123',
			driver_name: 'ABC',
		})).toStrictEqual({ 'message': 'MESSAGE_APP.DRIVER_MANAGEMENT_VALIDATE_REQUIRED', 'status': false });
		expect(validateDriver({
			driver_code: '123',
			driver_name: 'ABC',
			start_date: '2022-01-01',
		})).toStrictEqual({ 'message': 'MESSAGE_APP.DRIVER_MANAGEMENT_VALIDATE_REQUIRED', 'status': false });
		expect(validateDriver({
			driver_code: '123',
			driver_name: 'ABC',
			start_date: '2022-01-01',
			birth_day: '2000-01-01',
		})).toStrictEqual({ 'message': 'MESSAGE_APP.DRIVER_MANAGEMENT_VALIDATE_REQUIRED', 'status': false });
		expect(validateDriver({
			driver_code: '123',
			driver_name: 'ABC',
			start_date: '2022-01-01',
			birth_day: '2000-01-01',
			working_day: '2',
		})).toStrictEqual({ 'message': null, 'status': true });
		expect(validateDriver({
			driver_code: '123.1',
			driver_name: 'ABC',
			start_date: '2022-01-01',
			birth_day: '2000-01-01',
			working_day: '2',
		})).toStrictEqual({ 'message': 'MESSAGE_APP.DRIVER_MANAGEMENT_VALIDATE_DRIVER_CODE', 'status': false });
		expect(validateDriver({
			driver_code: '1231',
			driver_name: '123',
			start_date: '2022-01-01',
			birth_day: '2000-01-01',
			working_day: '2',
		})).toStrictEqual({ 'message': null, 'status': true });
		expect(validateDriver({
			driver_code: '1231',
			driver_name: '123123123123123123123123123123123123123',
			start_date: '2022-01-01',
			birth_day: '2000-01-01',
			working_day: '2',
		})).toStrictEqual({ 'message': 'MESSAGE_APP.DRIVER_MANAGEMENT_VALIDATE_DRIVER_NAME', 'status': false });
		expect(validateDriver({
			driver_code: '1231',
			driver_name: '123123123123',
			start_date: '2022-01-01',
			birth_day: '2000-01-01',
			working_day: '',
		})).toStrictEqual({ 'message': 'MESSAGE_APP.DRIVER_MANAGEMENT_VALIDATE_REQUIRED', 'status': false });
	});

	test('Check validate available days', () => {
		expect(validateDriver({})).toStrictEqual({ 'message': 'MESSAGE_APP.DRIVER_MANAGEMENT_VALIDATE_REQUIRED', 'status': false });
		expect(validateDriver({
			driver_code: '123',
		})).toStrictEqual({ 'message': 'MESSAGE_APP.DRIVER_MANAGEMENT_VALIDATE_REQUIRED', 'status': false });
		expect(validateDriver({
			driver_code: '123',
			driver_name: 'ABC',
		})).toStrictEqual({ 'message': 'MESSAGE_APP.DRIVER_MANAGEMENT_VALIDATE_REQUIRED', 'status': false });
		expect(validateDriver({
			driver_code: '123',
			driver_name: 'ABC',
			start_date: '2022-01-01',
		})).toStrictEqual({ 'message': 'MESSAGE_APP.DRIVER_MANAGEMENT_VALIDATE_REQUIRED', 'status': false });
		expect(validateDriver({
			driver_code: '123',
			driver_name: 'ABC',
			start_date: '2022-01-01',
			birth_day: '2000-01-01',
		})).toStrictEqual({ 'message': 'MESSAGE_APP.DRIVER_MANAGEMENT_VALIDATE_REQUIRED', 'status': false });
		expect(validateDriver({
			driver_code: '123',
			driver_name: 'ABC',
			start_date: '2022-01-01',
			birth_day: '2000-01-01',
			working_day: '2',
		})).toStrictEqual({ 'message': null, 'status': true });
		expect(validateDriver({
			driver_code: '123.1',
			driver_name: 'ABC',
			start_date: '2022-01-01',
			birth_day: '2000-01-01',
			working_day: '2',
		})).toStrictEqual({ 'message': 'MESSAGE_APP.DRIVER_MANAGEMENT_VALIDATE_DRIVER_CODE', 'status': false });
		expect(validateDriver({
			driver_code: '1231',
			driver_name: '123',
			start_date: '2022-01-01',
			birth_day: '2000-01-01',
			working_day: '2',
		})).toStrictEqual({ 'message': null, 'status': true });
		expect(validateDriver({
			driver_code: '1231',
			driver_name: '123123123123123123123123123123123123123',
			start_date: '2022-01-01',
			birth_day: '2000-01-01',
			working_day: '2',
		})).toStrictEqual({ 'message': 'MESSAGE_APP.DRIVER_MANAGEMENT_VALIDATE_DRIVER_NAME', 'status': false });
		expect(validateDriver({
			driver_code: '1231',
			driver_name: '123123123123',
			start_date: '2022-01-01',
			birth_day: '2000-01-01',
			working_day: '',
		})).toStrictEqual({ 'message': 'MESSAGE_APP.DRIVER_MANAGEMENT_VALIDATE_REQUIRED', 'status': false });
	});
});
