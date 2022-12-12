import store from '@/store';
import router from '@/router';
import EditCoursePattern from '@/pages/DataManagement/ListCoursePattern/edit';
import { mount, createLocalVue } from '@vue/test-utils';

describe('TEST COMPONENT EDIT COURSE PATTERN', () => {
	test('Check render page', () => {
		const localVue = createLocalVue();
		const wrapper = mount(EditCoursePattern, {
			localVue,
			store,
			router,
		});

		const PAGE = wrapper.find('.page-edit-course-pattern');
		expect(PAGE.exists()).toBe(true);

		wrapper.destroy();
	});

	test('Check render title page', () => {
		const localVue = createLocalVue();
		const wrapper = mount(EditCoursePattern, {
			localVue,
			store,
			router,
		});

		const TITLE = wrapper.find('.title-page');
		expect(TITLE.text()).toEqual('EDIT_COURSE_PATTERN.TITLE_EDIT_COURSE_PATTERN');

		wrapper.destroy();
	});

	test('Check render button return', () => {
		const localVue = createLocalVue();
		const wrapper = mount(EditCoursePattern, {
			localVue,
			store,
			router,
		});

		const BUTTON = wrapper.find('.btn-return');
		expect(BUTTON.exists()).toBe(true);
		expect(BUTTON.text()).toEqual('APP.BUTTON_RETURN');

		wrapper.destroy();
	});
	test('Check render button save', () => {
		const localVue = createLocalVue();
		const wrapper = mount(EditCoursePattern, {
			localVue,
			store,
			router,
		});

		const BUTTON = wrapper.find('.btn-save');
		expect(BUTTON.exists()).toBe(true);
		expect(BUTTON.text()).toEqual('APP.BUTTON_SAVE');

		wrapper.destroy();
	});

	test('Check render table header', () => {
		const localVue = createLocalVue();
		const wrapper = mount(EditCoursePattern, {
			localVue,
			store,
			router,
		});

		const TABLE = wrapper.find('.zone-table table');
		expect(TABLE.exists()).toBe(true);

		const LIST_HEADER = TABLE.findAll('th');

		const CONST_LIST_HEADER = [
			'EDIT_COURSE_PATTERN.COURSE_ID',
			'EDIT_COURSE_PATTERN.COURSE_NAME',
		];

		expect(LIST_HEADER.length).toBe(2);
		for (let i = 0; i < LIST_HEADER.length; i++) {
			expect(LIST_HEADER.at(i).text()).toEqual(CONST_LIST_HEADER[i]);
		}

		wrapper.destroy();
	});

	test('Check render body table edit', () => {
		const localVue = createLocalVue();
		const wrapper = mount(EditCoursePattern, {
			localVue,
			store,
			router,
			data() {
				return {
					listCoursePatern: [
						{
							'id': 2,
							'course_code': '0002',
							'course_name': 'son test 2',
							'status': 'on',
							'course_patterns': [
								{
									'id': 1,
									'course_parent_code': '0002',
									'course_child_code': '0002',
									'status': 0,
									'created_at': '2022-09-12T04:08:58.000000Z',
									'updated_at': '2022-09-12T04:08:58.000000Z',
								},
								{
									'id': 2,
									'course_parent_code': '0002',
									'course_child_code': '0005',
									'status': 0,
									'created_at': '2022-09-12T04:08:58.000000Z',
									'updated_at': '2022-09-12T04:08:58.000000Z',
								},
								{
									'id': 4,
									'course_parent_code': '0002',
									'course_child_code': '0007',
									'status': 0,
									'created_at': '2022-09-12T04:08:58.000000Z',
									'updated_at': '2022-09-12T04:08:58.000000Z',
								},
								{
									'id': 6,
									'course_parent_code': '0002',
									'course_child_code': '0009',
									'status': 0,
									'created_at': '2022-09-12T04:08:58.000000Z',
									'updated_at': '2022-09-12T04:08:58.000000Z',
								},
								{
									'id': 8,
									'course_parent_code': '0002',
									'course_child_code': '00100',
									'status': 0,
									'created_at': '2022-09-12T04:08:58.000000Z',
									'updated_at': '2022-09-12T04:08:58.000000Z',
								},
							],
						},
						{
							'id': 5,
							'course_code': '0005',
							'course_name': 'son test 5',
							'status': 'on',
							'course_patterns': [
								{
									'id': 3,
									'course_parent_code': '0005',
									'course_child_code': '0002',
									'status': 0,
									'created_at': '2022-09-12T04:08:58.000000Z',
									'updated_at': '2022-09-12T04:08:58.000000Z',
								},
								{
									'id': 10,
									'course_parent_code': '0005',
									'course_child_code': '0005',
									'status': 0,
									'created_at': '2022-09-12T04:08:58.000000Z',
									'updated_at': '2022-09-12T04:08:58.000000Z',
								},
								{
									'id': 11,
									'course_parent_code': '0005',
									'course_child_code': '0007',
									'status': 0,
									'created_at': '2022-09-12T04:08:58.000000Z',
									'updated_at': '2022-09-12T04:08:58.000000Z',
								},
								{
									'id': 13,
									'course_parent_code': '0005',
									'course_child_code': '0009',
									'status': 0,
									'created_at': '2022-09-12T04:08:58.000000Z',
									'updated_at': '2022-09-12T04:08:58.000000Z',
								},
								{
									'id': 15,
									'course_parent_code': '0005',
									'course_child_code': '00100',
									'status': 0,
									'created_at': '2022-09-12T04:08:58.000000Z',
									'updated_at': '2022-09-12T04:08:58.000000Z',
								},
							],
						},
						{
							'id': 7,
							'course_code': '0007',
							'course_name': 'son test 7',
							'status': 'on',
							'course_patterns': [
								{
									'id': 5,
									'course_parent_code': '0007',
									'course_child_code': '0002',
									'status': 0,
									'created_at': '2022-09-12T04:08:58.000000Z',
									'updated_at': '2022-09-12T04:08:58.000000Z',
								},
								{
									'id': 12,
									'course_parent_code': '0007',
									'course_child_code': '0005',
									'status': 0,
									'created_at': '2022-09-12T04:08:58.000000Z',
									'updated_at': '2022-09-12T04:08:58.000000Z',
								},
								{
									'id': 19,
									'course_parent_code': '0007',
									'course_child_code': '0007',
									'status': 0,
									'created_at': '2022-09-12T04:08:58.000000Z',
									'updated_at': '2022-09-12T04:08:58.000000Z',
								},
								{
									'id': 20,
									'course_parent_code': '0007',
									'course_child_code': '0009',
									'status': 0,
									'created_at': '2022-09-12T04:08:58.000000Z',
									'updated_at': '2022-09-12T04:08:58.000000Z',
								},
								{
									'id': 22,
									'course_parent_code': '0007',
									'course_child_code': '00100',
									'status': 0,
									'created_at': '2022-09-12T04:08:58.000000Z',
									'updated_at': '2022-09-12T04:08:58.000000Z',
								},
							],
						},
						{
							'id': 9,
							'course_code': '0009',
							'course_name': 'son test 9',
							'status': 'off',
							'course_patterns': [
								{
									'id': 7,
									'course_parent_code': '0009',
									'course_child_code': '0002',
									'status': 0,
									'created_at': '2022-09-12T04:08:58.000000Z',
									'updated_at': '2022-09-12T04:08:58.000000Z',
								},
								{
									'id': 14,
									'course_parent_code': '0009',
									'course_child_code': '0005',
									'status': 0,
									'created_at': '2022-09-12T04:08:58.000000Z',
									'updated_at': '2022-09-12T04:08:58.000000Z',
								},
								{
									'id': 21,
									'course_parent_code': '0009',
									'course_child_code': '0007',
									'status': 0,
									'created_at': '2022-09-12T04:08:58.000000Z',
									'updated_at': '2022-09-12T04:08:58.000000Z',
								},
								{
									'id': 28,
									'course_parent_code': '0009',
									'course_child_code': '0009',
									'status': 0,
									'created_at': '2022-09-12T04:08:58.000000Z',
									'updated_at': '2022-09-12T04:08:58.000000Z',
								},
								{
									'id': 29,
									'course_parent_code': '0009',
									'course_child_code': '00100',
									'status': 0,
									'created_at': '2022-09-12T04:08:58.000000Z',
									'updated_at': '2022-09-12T04:08:58.000000Z',
								},
							],
						},
						{
							'id': 10,
							'course_code': '00100',
							'course_name': 'son test 00100',
							'status': 'off',
							'course_patterns': [
								{
									'id': 9,
									'course_parent_code': '00100',
									'course_child_code': '0002',
									'status': 0,
									'created_at': '2022-09-12T04:08:58.000000Z',
									'updated_at': '2022-09-12T04:08:58.000000Z',
								},
								{
									'id': 16,
									'course_parent_code': '00100',
									'course_child_code': '0005',
									'status': 0,
									'created_at': '2022-09-12T04:08:58.000000Z',
									'updated_at': '2022-09-12T04:08:58.000000Z',
								},
								{
									'id': 23,
									'course_parent_code': '00100',
									'course_child_code': '0007',
									'status': 0,
									'created_at': '2022-09-12T04:08:58.000000Z',
									'updated_at': '2022-09-12T04:08:58.000000Z',
								},
								{
									'id': 30,
									'course_parent_code': '00100',
									'course_child_code': '0009',
									'status': 0,
									'created_at': '2022-09-12T04:08:58.000000Z',
									'updated_at': '2022-09-12T04:08:58.000000Z',
								},
								{
									'id': 37,
									'course_parent_code': '00100',
									'course_child_code': '00100',
									'status': 0,
									'created_at': '2022-09-12T04:08:58.000000Z',
									'updated_at': '2022-09-12T04:08:58.000000Z',
								},
							],
						},
					],
				};
			},
		});

		const TABLE = wrapper.find('.zone-table table');
		expect(TABLE.exists()).toBe(true);

		const LIST_BODY = TABLE.findAll('tbody tr');
		expect(LIST_BODY.length).toBe(5);

		wrapper.destroy();
	});

	test('Check click button return', async() => {
		const onClickReturn = jest.fn();

		const localVue = createLocalVue();
		const wrapper = mount(EditCoursePattern, {
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

	test('Check click button save', async() => {
		const onClickSaveCoursePattern = jest.fn();

		const localVue = createLocalVue();
		const wrapper = mount(EditCoursePattern, {
			localVue,
			store,
			router,
			methods: {
				onClickSaveCoursePattern,
			},
		});

		const BUTTON = wrapper.find('.btn-save');
		await BUTTON.trigger('click');
		expect(onClickSaveCoursePattern).toHaveBeenCalled();

		wrapper.destroy();
	});

	test('Check function edit save', () => {
		const localVue = createLocalVue();
		const wrapper = mount(EditCoursePattern, {
			localVue,
			store,
			router,
		});

		wrapper.destroy();
	});
});
