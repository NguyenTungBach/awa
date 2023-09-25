import store from '@/store';
import router from '@/router';
import ListCourse from '@/pages/DataManagement/ListCustomer/index';
import { mount, createLocalVue } from '@vue/test-utils';

describe('TEST COMPONENT LIST COURSE', () => {
	test('Check render page', () => {
		const localVue = createLocalVue();
		const wrapper = mount(ListCourse, {
			localVue,
			store,
			router,
		});

		const PAGE = wrapper.find('.page-list-course');
		expect(PAGE.exists()).toBe(true);

		wrapper.destroy();
	});

	test('Check render title page', () => {
		const localVue = createLocalVue();
		const wrapper = mount(ListCourse, {
			localVue,
			store,
			router,
		});

		const TITLE = wrapper.find('.title-page');
		expect(TITLE.text()).toEqual('LIST_COURSE.TITLE_LIST_COURSE');

		wrapper.destroy();
	});

	test('Check render button sign-up', () => {
		const localVue = createLocalVue();
		const wrapper = mount(ListCourse, {
			localVue,
			store,
			router,
		});

		const BUTTON = wrapper.find('div:nth-child(2) > div > button');
		expect(BUTTON.exists()).toBe(true);
		expect(BUTTON.text()).toEqual('APP.BUTTON_SIGN_UP');

		wrapper.destroy();
	});

	test('Check click button sign-up', async() => {
		const onClickSignUp = jest.fn();

		const localVue = createLocalVue();
		const wrapper = mount(ListCourse, {
			localVue,
			store,
			router,
			methods: {
				onClickSignUp,
			},
		});

		const BUTTON = wrapper.find('div:nth-child(2) > div > button');
		await BUTTON.trigger('click');
		expect(onClickSignUp).toHaveBeenCalled();

		wrapper.destroy();
	});

	test('Check render table header', () => {
		const localVue = createLocalVue();
		const wrapper = mount(ListCourse, {
			localVue,
			store,
			router,
		});

		const TABLE = wrapper.find('.zone-table table');
		expect(TABLE.exists()).toBe(true);

		const LIST_HEADER = TABLE.findAll('th');

		const CONST_LIST_HEADER = [
			'LIST_COURSE.TABLE_COURSE_ID',
			'LIST_COURSE.TABLE_COURSE_NAME',
			'LIST_COURSE.TABLE_OPERATIONAL_INFORMATION',
			'LIST_COURSE.TABLE_DETAIL',
			'LIST_COURSE.TABLE_DELETE',
			// 'LIST_COURSE.TABLE_START_TIME',
			// 'LIST_COURSE.TABLE_CLOSING_TIME',
			// 'LIST_COURSE.TABLE_BREAK_TIME',
		];

		expect(LIST_HEADER.length).toBe(5);
		for (let i = 0; i < LIST_HEADER.length; i++) {
			expect(LIST_HEADER.at(i).text()).toEqual(CONST_LIST_HEADER[i]);
		}

		wrapper.destroy();
	});

	test('Check render body table', () => {
		const localVue = createLocalVue();
		const wrapper = mount(ListCourse, {
			localVue,
			store,
			router,
			data() {
				return {
					listCourse: [
						{
							'id': 2,
							'flag': 'no',
							'course_code': '0002',
							'course_name': 'son test 2',
							'start_time': '6:000',
							'end_time': '6:06',
							'break_time': '00:00',
							'start_date': '2022-08-25',
							'end_date': '2022-08-29',
							'status': 'on',
							'note': 'son test 2',
							'created_at': 1661748677,
							'updated_at': 1661748677,
							'deleted_at': null,
						},
						{
							'id': 4,
							'flag': 'yes',
							'course_code': '0004',
							'course_name': 'son test 4',
							'start_time': '6:00',
							'end_time': '6:06',
							'break_time': '00:00',
							'start_date': '2022-08-25',
							'end_date': '2022-08-29',
							'status': 'on',
							'note': 'son test 4',
							'created_at': 1661748677,
							'updated_at': 1661748677,
							'deleted_at': null,
						},
					],
				};
			},
		});

		const TABLE = wrapper.find('.zone-table table');
		expect(TABLE.exists()).toBe(true);

		const LIST_BODY = TABLE.findAll('tbody tr');
		expect(LIST_BODY.length).toBe(2);

		wrapper.destroy();
	});

	test('Check function sort table', async() => {
		const onSortTable = jest.fn();

		const localVue = createLocalVue();
		const wrapper = mount(ListCourse, {
			localVue,
			store,
			router,
			methods: {
				onSortTable,
			},
		});

		const TABLE = wrapper.find('.zone-table table');
		const LIST_HEADER = TABLE.findAll('th');

		await LIST_HEADER.at(0).trigger('click');
		expect(onSortTable).toHaveBeenCalled();

		await LIST_HEADER.at(1).trigger('click');
		expect(onSortTable).toHaveBeenCalled();

		wrapper.destroy();
	});

	test('Check render button detail', () => {
		const localVue = createLocalVue();
		const wrapper = mount(ListCourse, {
			localVue,
			store,
			router,
			data() {
				return {
					listCourse: [
						{
							'id': 2,
							'flag': 'no',
							'course_code': '0002',
							'course_name': 'son test 2',
							'start_time': '6:000',
							'end_time': '6:06',
							'break_time': '00:00',
							'start_date': '2022-08-25',
							'end_date': '2022-08-29',
							'status': 'on',
							'note': 'son test 2',
							'created_at': 1661748677,
							'updated_at': 1661748677,
							'deleted_at': null,
						},
						{
							'id': 4,
							'flag': 'yes',
							'course_code': '0004',
							'course_name': 'son test 4',
							'start_time': '6:00',
							'end_time': '6:06',
							'break_time': '00:00',
							'start_date': '2022-08-25',
							'end_date': '2022-08-29',
							'status': 'on',
							'note': 'son test 4',
							'created_at': 1661748677,
							'updated_at': 1661748677,
							'deleted_at': null,
						},
					],
				};
			},
		});

		const TABLE = wrapper.find('.zone-table table');
		const LIST_BODY = TABLE.findAll('tbody tr');

		for (let i = 0; i < LIST_BODY.length; i++) {
			const LIST_TD = LIST_BODY.at(i).findAll('td');

			const DETAIL = LIST_TD.at(3).find('i.fa-eye');

			expect(DETAIL.exists()).toBe(true);
		}

		wrapper.destroy();
	});

	test('Check click button detail', async() => {
		const onClickDetail = jest.fn();
		const localVue = createLocalVue();
		const wrapper = mount(ListCourse, {
			localVue,
			store,
			router,
			data() {
				return {
					listCourse: [
						{
							'id': 2,
							'flag': 'no',
							'course_code': '0002',
							'course_name': 'son test 2',
							'start_time': '6:000',
							'end_time': '6:06',
							'break_time': '00:00',
							'start_date': '2022-08-25',
							'end_date': '2022-08-29',
							'status': 'on',
							'note': 'son test 2',
							'created_at': 1661748677,
							'updated_at': 1661748677,
							'deleted_at': null,
						},
						{
							'id': 4,
							'flag': 'yes',
							'course_code': '0004',
							'course_name': 'son test 4',
							'start_time': '6:00',
							'end_time': '6:06',
							'break_time': '00:00',
							'start_date': '2022-08-25',
							'end_date': '2022-08-29',
							'status': 'on',
							'note': 'son test 4',
							'created_at': 1661748677,
							'updated_at': 1661748677,
							'deleted_at': null,
						},
					],
				};
			},
			methods: {
				onClickDetail,
			},
		});

		const TABLE = wrapper.find('.zone-table table');
		const LIST_BODY = TABLE.findAll('tbody tr');

		for (let i = 0; i < LIST_BODY.length; i++) {
			const LIST_TD = LIST_BODY.at(i).findAll('td');

			const DETAIL = LIST_TD.at(3).find('i.fa-eye');

			expect(DETAIL.exists()).toBe(true);

			await DETAIL.trigger('click');

			expect(onClickDetail).toHaveBeenCalled();
		}

		wrapper.destroy();
	});

	test('Check render button delete', () => {
		const localVue = createLocalVue();
		const wrapper = mount(ListCourse, {
			localVue,
			store,
			router,
			data() {
				return {
					listCourse: [
						{
							'id': 2,
							'flag': 'no',
							'course_code': '0002',
							'course_name': 'son test 2',
							'start_time': '6:000',
							'end_time': '6:06',
							'break_time': '00:00',
							'start_date': '2022-08-25',
							'end_date': '2022-08-29',
							'status': 'on',
							'note': 'son test 2',
							'created_at': 1661748677,
							'updated_at': 1661748677,
							'deleted_at': null,
						},
						{
							'id': 4,
							'flag': 'yes',
							'course_code': '0004',
							'course_name': 'son test 4',
							'start_time': '6:00',
							'end_time': '6:06',
							'break_time': '00:00',
							'start_date': '2022-08-25',
							'end_date': '2022-08-29',
							'status': 'on',
							'note': 'son test 4',
							'created_at': 1661748677,
							'updated_at': 1661748677,
							'deleted_at': null,
						},
					],
				};
			},
		});

		const TABLE = wrapper.find('.zone-table table');
		const LIST_BODY = TABLE.findAll('tbody tr');

		for (let i = 0; i < LIST_BODY.length; i++) {
			const LIST_TD = LIST_BODY.at(i).findAll('td');

			const DETAIL = LIST_TD.at(4).find('i.fa-trash-alt');

			expect(DETAIL.exists()).toBe(true);
		}

		wrapper.destroy();
	});

	test('Check click button delete', async() => {
		const onClickDelete = jest.fn();

		const localVue = createLocalVue();
		const wrapper = mount(ListCourse, {
			localVue,
			store,
			router,
			data() {
				return {
					listCourse: [
						{
							'id': 2,
							'flag': 'no',
							'course_code': '0002',
							'course_name': 'son test 2',
							'start_time': '6:00',
							'end_time': '6:06',
							'break_time': '00:00',
							'start_date': '2022-08-25',
							'end_date': '2022-08-29',
							'status': 'on',
							'note': 'son test 2',
							'created_at': 1661748677,
							'updated_at': 1661748677,
							'deleted_at': null,
						},
						{
							'id': 4,
							'flag': 'yes',
							'course_code': '0004',
							'course_name': 'son test 4',
							'start_time': '6:00',
							'end_time': '6:06',
							'break_time': '00:00',
							'start_date': '2022-08-25',
							'end_date': '2022-08-29',
							'status': 'on',
							'note': 'son test 4',
							'created_at': 1661748677,
							'updated_at': 1661748677,
							'deleted_at': null,
						},
					],
				};
			},
			methods: {
				onClickDelete,
			},
		});

		const TABLE = wrapper.find('.zone-table table');
		const LIST_BODY = TABLE.findAll('tbody tr');

		for (let i = 0; i < LIST_BODY.length; i++) {
			const LIST_TD = LIST_BODY.at(i).findAll('td');

			const DETAIL = LIST_TD.at(4).find('i.fa-trash-alt');

			expect(DETAIL.exists()).toBe(true);

			await DETAIL.trigger('click');

			expect(onClickDelete).toHaveBeenCalled();
		}

		wrapper.destroy();
	});
});
