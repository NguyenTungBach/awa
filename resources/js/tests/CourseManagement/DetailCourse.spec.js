import store from '@/store';
import router from '@/router';
import CourseDetail from '@/pages/DataManagement/ListCourse/detail';
import { mount, createLocalVue } from '@vue/test-utils';

describe('TEST COMPONENT COURSE DETAIL', () => {
	test('Check render page', () => {
		const localVue = createLocalVue();
		const wrapper = mount(CourseDetail, {
			localVue,
			store,
			router,
		});

		const PAGE = wrapper.find('.page-course-detail');
		expect(PAGE.exists()).toBe(true);

		wrapper.destroy();
	});

	test('Check render title page', () => {
		const localVue = createLocalVue();
		const wrapper = mount(CourseDetail, {
			localVue,
			store,
			router,
		});

		const TITLE = wrapper.find('.title-page');
		expect(TITLE.text()).toBe('COURSE_DETAIL.TITLE_COURSE_DETAIL');

		wrapper.destroy();
	});

	test('Check render button return', () => {
		const localVue = createLocalVue();
		const wrapper = mount(CourseDetail, {
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
		const wrapper = mount(CourseDetail, {
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

	test('Check render button edit', () => {
		const localVue = createLocalVue();
		const wrapper = mount(CourseDetail, {
			localVue,
			store,
			router,
		});

		const BUTTON = wrapper.find('.btn-edit');
		expect(BUTTON.exists()).toBe(true);
		expect(BUTTON.text()).toEqual('APP.BUTTON_EDIT');

		wrapper.destroy();
	});

	test('Check click button edit', async() => {
		const onClickEdit = jest.fn();

		const localVue = createLocalVue();
		const wrapper = mount(CourseDetail, {
			localVue,
			store,
			router,
			methods: {
				onClickEdit,
			},
		});

		const BUTTON = wrapper.find('.btn-edit');
		await BUTTON.trigger('click');
		expect(onClickEdit).toHaveBeenCalled();

		wrapper.destroy();
	});

	test('Check render form detail', () => {
		const localVue = createLocalVue();
		const wrapper = mount(CourseDetail, {
			localVue,
			store,
			router,
		});

		const TITLE = wrapper.find('.title-path-form');
		expect(TITLE.exists()).toBe(true);
		expect(TITLE.text()).toEqual('COURSE_CREATE.FORM_BASIC_INFORMATION');

		wrapper.destroy();
	});
});
