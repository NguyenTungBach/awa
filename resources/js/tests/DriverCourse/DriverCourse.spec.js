import store from '@/store';
import router from '@/router';
import DriverCourse from '@/components/TableEditDriverCourse';
import { mount, createLocalVue } from '@vue/test-utils';

describe('TEST COMPONENT DRIVER COURSE', () => {
	test('Check render page', () => {
		const localVue = createLocalVue();
		const wrapper = mount(DriverCourse, {
			localVue,
			store,
			router,
		});

		const PAGE = wrapper.find('.table-edit-driver-course');
		expect(PAGE.exists()).toBe(true);

		wrapper.destroy();
	});

	test('Check render table', () => {
		const localVue = createLocalVue();
		const wrapper = mount(DriverCourse, {
			localVue,
			store,
			router,
		});

		const TABLE = wrapper.find('table');

		const THEAD = TABLE.find('thead');
		expect(THEAD.exists()).toBe(true);

		const LIST_TH = THEAD.findAll('th');
		expect(LIST_TH.length).toEqual(2);

		expect(LIST_TH.at(0).text()).toEqual('');
		expect(LIST_TH.at(1).text()).toEqual('EDIT_DRIVER.RUNABLE_COURSE_NAME');

		const TBODY = TABLE.find('tbody');
		expect(TBODY.exists()).toBe(true);

		console.log(TABLE.html());

		wrapper.destroy();
	});

	test('Check render button add', async() => {
		const onClickAdd = jest.fn();

		const localVue = createLocalVue();
		const wrapper = mount(DriverCourse, {
			localVue,
			store,
			router,
			methods: {
				onClickAdd,
			},
		});

		const BUTTON_ADD = wrapper.find('.zone-btn-add button');
		expect(BUTTON_ADD.exists()).toBe(true);

		await BUTTON_ADD.trigger('click');

		expect(onClickAdd).toHaveBeenCalled();

		wrapper.destroy();
	});
});
