import store from '@/store';
import router from '@/router';
import CourseEdit from '@/pages/DataManagement/ListCourse/edit';
import { mount, createLocalVue } from '@vue/test-utils';
import { validateCourse } from '@/utils/validateCRUD';

describe('TEST COMPONENT COURSE EDIT', () => {
	test('Check render page', () => {
		const localVue = createLocalVue();
		const wrapper = mount(CourseEdit, {
			localVue,
			store,
			router,
		});

		const PAGE = wrapper.find('.page-course-edit');
		expect(PAGE.exists()).toBe(true);

		wrapper.destroy();
	});

	test('Check render title page', () => {
		const localVue = createLocalVue();
		const wrapper = mount(CourseEdit, {
			localVue,
			store,
			router,
		});

		const TITLE = wrapper.find('.title-page');
		expect(TITLE.text()).toBe('COURSE_EDIT.TITLE_COURSE_EDIT');

		wrapper.destroy();
	});

	test('Check render button return', () => {
		const localVue = createLocalVue();
		const wrapper = mount(CourseEdit, {
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
		const wrapper = mount(CourseEdit, {
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
		const wrapper = mount(CourseEdit, {
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
		const onClickSaveCourse = jest.fn();

		const localVue = createLocalVue();
		const wrapper = mount(CourseEdit, {
			localVue,
			store,
			router,
			methods: {
				onClickSaveCourse,
			},
		});

		const BUTTON = wrapper.find('.btn-save');
		await BUTTON.trigger('click');
		expect(onClickSaveCourse).toHaveBeenCalled();

		wrapper.destroy();
	});

	test('Check render form', () => {
		const localVue = createLocalVue();
		const wrapper = mount(CourseEdit, {
			localVue,
			store,
			router,
		});

		const TITLE = wrapper.find('.title-path-form');
		expect(TITLE.exists()).toBe(true);
		expect(TITLE.text()).toEqual('COURSE_EDIT.FORM_BASIC_INFORMATION');

		wrapper.destroy();
	});

	test('Check function validate', () => {
		expect(validateCourse({})).toStrictEqual({ 'message': 'MESSAGE_APP.COURSE_MANAGEMENT_VALIDATE_REQUIRED', 'status': false });
		expect(validateCourse({
			course_code: '123',
			course_name: 'ABC ABC ABC ABC ABC ABC ABC ABC ABC',
		})).toStrictEqual({ 'message': 'MESSAGE_APP.COURSE_MANAGEMENT_VALIDATE_REQUIRED', 'status': false });
	});
});
