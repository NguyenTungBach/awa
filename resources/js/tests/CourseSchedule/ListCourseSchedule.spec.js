import store from '@/store';
import router from '@/router';
import ListCourseSchedule from '@/pages/ShiftManagement/ListSchedule/index';
import { mount, createLocalVue } from '@vue/test-utils';

describe('TEST COMPONENT LIST COURSE SCHEDULE', () => {
	test('Check render page', () => {
		const localVue = createLocalVue();
		const wrapper = mount(ListCourseSchedule, {
			localVue,
			store,
			router,
		});

		const PAGE = wrapper.find('.page-schedule');
		expect(PAGE.exists()).toBe(true);

		wrapper.destroy();
	});

	test('Check render title page', () => {
		const localVue = createLocalVue();
		const wrapper = mount(ListCourseSchedule, {
			localVue,
			store,
			router,
		});

		const TITLE = wrapper.find('.title-page');
		expect(TITLE.text()).toEqual('LIST_SCHEDULE.TITLE_LIST_SCHEDULE');

		wrapper.destroy();
	});

	test('Check render button edit', () => {
		const localVue = createLocalVue();
		const wrapper = mount(ListCourseSchedule, {
			localVue,
			store,
			router,
		});

		const BUTTON = wrapper.find('.btn-edit');
		expect(BUTTON.exists()).toBe(true);
		expect(BUTTON.text()).toEqual('APP.BUTTON_EDIT');

		wrapper.destroy();
	});

	test('Check render table header', () => {
		const localVue = createLocalVue();
		const wrapper = mount(ListCourseSchedule, {
			localVue,
			store,
			router,
		});

		const TABLE = wrapper.find('.zone-table table');
		expect(TABLE.exists()).toBe(true);

		const LIST_HEADER = TABLE.findAll('th');

		const CONST_LIST_HEADER = [
			'LIST_SCHEDULE.TABLE_COURSE_ID',
			'LIST_SCHEDULE.TABLE_COURSE_GROUP_CODE',
			'LIST_SCHEDULE.TABLE_COURSE_NAME',
		];

		expect(LIST_HEADER.length).toBe(3);
		for (let i = 0; i < LIST_HEADER.length; i++) {
			expect(LIST_HEADER.at(i).text()).toEqual(CONST_LIST_HEADER[i]);
		}

		wrapper.destroy();
	});

	test('Check render body table', () => {
		const localVue = createLocalVue();
		const wrapper = mount(ListCourseSchedule, {
			localVue,
			store,
			router,
			data() {
				return {
					listCourseSchedule: [
						[
							{
								'id': 2,
								'course_code': '0002',
								'course_name': 'son test 2',
								'group': 'AB',
								'start_date': '2022-08-25',
								'end_date': '2022-08-29',
								'course_schedules': [
									{
										'id': '',
										'course_code': '0002',
										'schedule_date': '2022-09-01',
										'status': '',
										'lunar_jp': '',
									},
									{
										'id': '',
										'course_code': '0002',
										'schedule_date': '2022-09-02',
										'status': '',
										'lunar_jp': '',
									},
									{
										'id': '',
										'course_code': '0002',
										'schedule_date': '2022-09-03',
										'status': '',
										'lunar_jp': '',
									},
									{
										'id': '',
										'course_code': '0002',
										'schedule_date': '2022-09-04',
										'status': '',
										'lunar_jp': '',
									},
									{
										'id': '',
										'course_code': '0002',
										'schedule_date': '2022-09-05',
										'status': '',
										'lunar_jp': '',
									},
									{
										'id': '',
										'course_code': '0002',
										'schedule_date': '2022-09-06',
										'status': '',
										'lunar_jp': '',
									},
									{
										'id': '',
										'course_code': '0002',
										'schedule_date': '2022-09-07',
										'status': '',
										'lunar_jp': '',
									},
									{
										'id': '',
										'course_code': '0002',
										'schedule_date': '2022-09-08',
										'status': '',
										'lunar_jp': '',
									},
									{
										'id': '',
										'course_code': '0002',
										'schedule_date': '2022-09-09',
										'status': '',
										'lunar_jp': '',
									},
									{
										'id': '',
										'course_code': '0002',
										'schedule_date': '2022-09-10',
										'status': '',
										'lunar_jp': '',
									},
									{
										'id': '',
										'course_code': '0002',
										'schedule_date': '2022-09-11',
										'status': '',
										'lunar_jp': '',
									},
									{
										'id': '',
										'course_code': '0002',
										'schedule_date': '2022-09-12',
										'status': '',
										'lunar_jp': '',
									},
									{
										'id': '',
										'course_code': '0002',
										'schedule_date': '2022-09-13',
										'status': '',
										'lunar_jp': '',
									},
									{
										'id': '',
										'course_code': '0002',
										'schedule_date': '2022-09-14',
										'status': '',
										'lunar_jp': '',
									},
									{
										'id': '',
										'course_code': '0002',
										'schedule_date': '2022-09-15',
										'status': '',
										'lunar_jp': '',
									},
									{
										'id': '',
										'course_code': '0002',
										'schedule_date': '2022-09-16',
										'status': '',
										'lunar_jp': '',
									},
									{
										'id': '',
										'course_code': '0002',
										'schedule_date': '2022-09-17',
										'status': '',
										'lunar_jp': '',
									},
									{
										'id': '',
										'course_code': '0002',
										'schedule_date': '2022-09-18',
										'status': '',
										'lunar_jp': '',
									},
									{
										'id': '',
										'course_code': '0002',
										'schedule_date': '2022-09-19',
										'status': '',
										'lunar_jp': '',
									},
									{
										'id': '',
										'course_code': '0002',
										'schedule_date': '2022-09-20',
										'status': '',
										'lunar_jp': '',
									},
									{
										'id': '',
										'course_code': '0002',
										'schedule_date': '2022-09-21',
										'status': '',
										'lunar_jp': '',
									},
									{
										'id': '',
										'course_code': '0002',
										'schedule_date': '2022-09-22',
										'status': '',
										'lunar_jp': '',
									},
									{
										'id': '',
										'course_code': '0002',
										'schedule_date': '2022-09-23',
										'status': '',
										'lunar_jp': '',
									},
									{
										'id': '',
										'course_code': '0002',
										'schedule_date': '2022-09-24',
										'status': '',
										'lunar_jp': '',
									},
									{
										'id': '',
										'course_code': '0002',
										'schedule_date': '2022-09-25',
										'status': '',
										'lunar_jp': '',
									},
									{
										'id': '',
										'course_code': '0002',
										'schedule_date': '2022-09-26',
										'status': '',
										'lunar_jp': '',
									},
									{
										'id': '',
										'course_code': '0002',
										'schedule_date': '2022-09-27',
										'status': '',
										'lunar_jp': '',
									},
									{
										'id': '',
										'course_code': '0002',
										'schedule_date': '2022-09-28',
										'status': '',
										'lunar_jp': '',
									},
									{
										'id': '',
										'course_code': '0002',
										'schedule_date': '2022-09-29',
										'status': '',
										'lunar_jp': '',
									},
									{
										'id': '',
										'course_code': '0002',
										'schedule_date': '2022-09-30',
										'status': '',
										'lunar_jp': '',
									},
								],
							},
							{
								'id': 5,
								'course_code': 'axNc',
								'course_name': 'test',
								'group': null,
								'start_date': '2022-09-22',
								'end_date': '',
								'course_schedules': [
									{
										'id': 1,
										'course_code': 'axNc',
										'schedule_date': '2022-09-01',
										'status': 'on',
										'lunar_jp': 'a',
									},
									{
										'id': 2,
										'course_code': 'axNc',
										'schedule_date': '2022-09-02',
										'status': 'on',
										'lunar_jp': 'a',
									},
									{
										'id': 3,
										'course_code': 'axNc',
										'schedule_date': '2022-09-03',
										'status': 'on',
										'lunar_jp': 'a',
									},
									{
										'id': 4,
										'course_code': 'axNc',
										'schedule_date': '2022-09-04',
										'status': 'on',
										'lunar_jp': 'a',
									},
									{
										'id': 5,
										'course_code': 'axNc',
										'schedule_date': '2022-09-05',
										'status': 'on',
										'lunar_jp': 'a',
									},
									{
										'id': 6,
										'course_code': 'axNc',
										'schedule_date': '2022-09-06',
										'status': 'on',
										'lunar_jp': 'a',
									},
									{
										'id': 7,
										'course_code': 'axNc',
										'schedule_date': '2022-09-07',
										'status': 'on',
										'lunar_jp': 'a',
									},
									{
										'id': 8,
										'course_code': 'axNc',
										'schedule_date': '2022-09-08',
										'status': 'on',
										'lunar_jp': 'a',
									},
									{
										'id': 9,
										'course_code': 'axNc',
										'schedule_date': '2022-09-09',
										'status': 'on',
										'lunar_jp': 'a',
									},
									{
										'id': 10,
										'course_code': 'axNc',
										'schedule_date': '2022-09-10',
										'status': 'on',
										'lunar_jp': 'a',
									},
									{
										'id': 11,
										'course_code': 'axNc',
										'schedule_date': '2022-09-11',
										'status': 'on',
										'lunar_jp': 'a',
									},
									{
										'id': 12,
										'course_code': 'axNc',
										'schedule_date': '2022-09-12',
										'status': 'on',
										'lunar_jp': 'a',
									},
									{
										'id': 13,
										'course_code': 'axNc',
										'schedule_date': '2022-09-13',
										'status': 'on',
										'lunar_jp': 'a',
									},
									{
										'id': 14,
										'course_code': 'axNc',
										'schedule_date': '2022-09-14',
										'status': 'on',
										'lunar_jp': 'a',
									},
									{
										'id': 15,
										'course_code': 'axNc',
										'schedule_date': '2022-09-15',
										'status': 'on',
										'lunar_jp': 'a',
									},
									{
										'id': 16,
										'course_code': 'axNc',
										'schedule_date': '2022-09-16',
										'status': 'on',
										'lunar_jp': 'a',
									},
									{
										'id': 17,
										'course_code': 'axNc',
										'schedule_date': '2022-09-17',
										'status': 'on',
										'lunar_jp': 'a',
									},
									{
										'id': 18,
										'course_code': 'axNc',
										'schedule_date': '2022-09-18',
										'status': 'on',
										'lunar_jp': 'a',
									},
									{
										'id': 19,
										'course_code': 'axNc',
										'schedule_date': '2022-09-19',
										'status': 'on',
										'lunar_jp': 'a',
									},
									{
										'id': 20,
										'course_code': 'axNc',
										'schedule_date': '2022-09-20',
										'status': 'on',
										'lunar_jp': 'a',
									},
									{
										'id': 21,
										'course_code': 'axNc',
										'schedule_date': '2022-09-21',
										'status': 'on',
										'lunar_jp': 'a',
									},
									{
										'id': 22,
										'course_code': 'axNc',
										'schedule_date': '2022-09-22',
										'status': 'on',
										'lunar_jp': 'a',
									},
									{
										'id': 23,
										'course_code': 'axNc',
										'schedule_date': '2022-09-23',
										'status': 'on',
										'lunar_jp': 'a',
									},
									{
										'id': 24,
										'course_code': 'axNc',
										'schedule_date': '2022-09-24',
										'status': 'on',
										'lunar_jp': 'a',
									},
									{
										'id': 25,
										'course_code': 'axNc',
										'schedule_date': '2022-09-25',
										'status': 'on',
										'lunar_jp': 'a',
									},
									{
										'id': 26,
										'course_code': 'axNc',
										'schedule_date': '2022-09-26',
										'status': 'on',
										'lunar_jp': 'a',
									},
									{
										'id': 27,
										'course_code': 'axNc',
										'schedule_date': '2022-09-27',
										'status': 'on',
										'lunar_jp': 'a',
									},
									{
										'id': 28,
										'course_code': 'axNc',
										'schedule_date': '2022-09-28',
										'status': 'on',
										'lunar_jp': 'a',
									},
									{
										'id': 29,
										'course_code': 'axNc',
										'schedule_date': '2022-09-29',
										'status': 'on',
										'lunar_jp': 'a',
									},
									{
										'id': 30,
										'course_code': 'axNc',
										'schedule_date': '2022-09-30',
										'status': 'on',
										'lunar_jp': 'a',
									},
								],
							},
						],
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

	test('Check click button edit', async() => {
		const goToListShiftEdit = jest.fn();

		const localVue = createLocalVue();
		const wrapper = mount(ListCourseSchedule, {
			localVue,
			store,
			router,
			methods: {
				goToListShiftEdit,
			},
		});

		const BUTTON = wrapper.find('.btn-edit');
		await BUTTON.trigger('click');
		expect(goToListShiftEdit).toHaveBeenCalled();

		wrapper.destroy();
	});
});
