import store from '@/store';
import router from '@/router';
import CreateCourse from '@/pages/DataManagement/ListCourse/create';
import { mount, createLocalVue } from '@vue/test-utils';
import { validateCourseCode, validString } from '@/utils/validate';

describe('TEST COMPONENT CREATE COURSE', () => {
	test('Check render page', () => {
		const localVue = createLocalVue();
		const wrapper = mount(CreateCourse, {
			localVue,
			store,
			router,
		});

		const PAGE = wrapper.find('.page-course-create');
		expect(PAGE.exists()).toBe(true);

		wrapper.destroy();
	});

	test('Check render title page', () => {
		const localVue = createLocalVue();
		const wrapper = mount(CreateCourse, {
			localVue,
			store,
			router,
		});

		const TITLE = wrapper.find('.title-page');
		expect(TITLE.text()).toEqual('COURSE_CREATE.TITLE_COURSE_CREATE');

		wrapper.destroy();
	});

	test('Check render button return', () => {
		const localVue = createLocalVue();
		const wrapper = mount(CreateCourse, {
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
		const wrapper = mount(CreateCourse, {
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
		const wrapper = mount(CreateCourse, {
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
		const onClickSave = jest.fn();

		const localVue = createLocalVue();
		const wrapper = mount(CreateCourse, {
			localVue,
			store,
			router,
			methods: {
				onClickSave,
			},
		});

		const BUTTON = wrapper.find('.btn-save');

		await BUTTON.trigger('click');

		expect(onClickSave).toHaveBeenCalled();

		wrapper.destroy();
	});

	test('Check render form', () => {
		const localVue = createLocalVue();
		const wrapper = mount(CreateCourse, {
			localVue,
			store,
			router,
		});

		const ZONE_FORM = wrapper.find('.body-form');
		expect(ZONE_FORM.exists()).toBe(true);

		const LIST_ITEM_FORM = wrapper.findAll('.item-form');
		expect(LIST_ITEM_FORM.length).toEqual(11);

		wrapper.destroy();
	});

	test('Check validate course id', () => {
		const localVue = createLocalVue();
		const wrapper = mount(CreateCourse, {
			localVue,
			store,
			router,
		});

		expect(validateCourseCode()).toBe(false);
		expect(validateCourseCode(null)).toBe(false);
		expect(validateCourseCode(undefined)).toBe(false);
		expect(validateCourseCode('')).toBe(false);
		expect(validateCourseCode('CourseCode')).toBe(false);
		expect(validateCourseCode('ab1')).toBe(false);
		expect(validateCourseCode('123')).toBe(true);
		expect(validateCourseCode(123)).toBe(true);

		wrapper.destroy();
	});

	test('Check validate course name', () => {
		const localVue = createLocalVue();
		const wrapper = mount(CreateCourse, {
			localVue,
			store,
			router,
		});

		expect(validString('Course name')).toBe(true);
		expect(validString('Course')).toBe(true);
		expect(validString('Course 123')).toBe(true);
		expect(validString('123')).toBe(true);

		wrapper.destroy();
	});

	test('Check validate start time', () => {
		const localVue = createLocalVue();
		const wrapper = mount(CreateCourse, {
			localVue,
			store,
			router,
		});

		wrapper.destroy();
	});

	test('Check validate end time', () => {
		const localVue = createLocalVue();
		const wrapper = mount(CreateCourse, {
			localVue,
			store,
			router,
		});

		wrapper.destroy();
	});

	test('Check validate break time', () => {
		const localVue = createLocalVue();
		const wrapper = mount(CreateCourse, {
			localVue,
			store,
			router,
		});

		wrapper.destroy();
	});

	test('Check valiadte start date', () => {
		const localVue = createLocalVue();
		const wrapper = mount(CreateCourse, {
			localVue,
			store,
			router,
		});

		wrapper.destroy();
	});
});
