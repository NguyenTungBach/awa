import store from '@/store';
import router from '@/router';
import { mount, createLocalVue } from '@vue/test-utils';
import ShiftEdit from '@/pages/ShiftManagement/ListShift/edit';
import { convertBreakTimeNumberToTime } from '@/utils/convertTime';

const DATA = {
	start: {
		year: 2022,
		month: 10,
		date: 1,
		text: '2022/10/01',
	},
	end: {
		year: 2022,
		month: 10,
		date: 7,
		text: '2022/10/07',
	},
	listDate: [
		{
			year: 2022,
			month: 10,
			date: 1,
			text: '2022-10-01',
		},
		{
			year: 2022,
			month: 10,
			date: 2,
			text: '2022-10-02',
		},
		{
			year: 2022,
			month: 10,
			date: 3,
			text: '2022-10-03',
		},
		{
			year: 2022,
			month: 10,
			date: 4,
			text: '2022-10-04',
		},
		{
			year: 2022,
			month: 10,
			date: 5,
			text: '2022-10-05',
		},
		{
			year: 2022,
			month: 10,
			date: 6,
			text: '2022-10-06',
		},
		{
			year: 2022,
			month: 10,
			date: 7,
			text: '2022-10-07',
		},
	],
};

describe('TEST COMPONENT EDIT LIST SHIFT', () => {
	test('Check render page', () => {
		const localVue = createLocalVue();
		const wrapper = mount(ShiftEdit, {
			localVue,
			store,
			router,
			computed: {
				pickerWeek() {
					return DATA;
				},
			},
		});

		const PAGE = wrapper.find('.list-shift');
		expect(PAGE.exists()).toBe(true);

		wrapper.destroy();
	});

	test('Check render title page', () => {
		const localVue = createLocalVue();
		const wrapper = mount(ShiftEdit, {
			localVue,
			store,
			router,
			computed: {
				pickerWeek() {
					return DATA;
				},
			},
		});

		const TITLE = wrapper.find('.title-page');
		expect(TITLE.text()).toEqual('LIST_SHIFT.TITLE_LIST_SHIFT');

		wrapper.destroy();
	});

	test('Check render button control', () => {
		const localVue = createLocalVue();
		const wrapper = mount(ShiftEdit, {
			localVue,
			store,
			router,
			computed: {
				pickerWeek() {
					return DATA;
				},
			},
		});

		const ZONE_CONTROL = wrapper.find('.list-shift__control');
		expect(ZONE_CONTROL.exists()).toBe(true);

		const BUTTON_RETURN = ZONE_CONTROL.find('.btn-return');
		const BUTTON_SAVE = ZONE_CONTROL.find('.btn-save');

		expect(BUTTON_RETURN.exists()).toBe(true);
		expect(BUTTON_SAVE.exists()).toBe(true);

		wrapper.destroy();
	});

	test('Check click button control', async() => {
		const goToListShift = jest.fn();
		const onClickSave = jest.fn();

		const localVue = createLocalVue();
		const wrapper = mount(ShiftEdit, {
			localVue,
			store,
			router,
			computed: {
				pickerWeek() {
					return DATA;
				},
			},
			methods: {
				goToListShift,
				onClickSave,
			},
		});

		const ZONE_CONTROL = wrapper.find('.list-shift__control');
		expect(ZONE_CONTROL.exists()).toBe(true);

		const BUTTON_RETURN = ZONE_CONTROL.find('.btn-return');
		const BUTTON_SAVE = ZONE_CONTROL.find('.btn-save');

		await BUTTON_RETURN.trigger('click');
		expect(goToListShift).toHaveBeenCalled();

		await BUTTON_SAVE.trigger('click');
		expect(onClickSave).toHaveBeenCalled();

		wrapper.destroy();
	});

	test('Check render table', () => {
		const localVue = createLocalVue();
		const wrapper = mount(ShiftEdit, {
			localVue,
			store,
			router,
			computed: {
				pickerWeek() {
					return DATA;
				},
			},
		});

		const TABLE = wrapper.find('.list-shift__table');
		expect(TABLE.exists()).toBe(true);

		wrapper.destroy();
	});

	test('Check render modal detail', () => {
		const localVue = createLocalVue();
		const wrapper = mount(ShiftEdit, {
			localVue,
			store,
			router,
			computed: {
				pickerWeek() {
					return DATA;
				},
			},
		});

		const MODAL = wrapper.find('#modal-detail');
		expect(MODAL.exists()).toBe(true);

		wrapper.destroy();
	});

	test('Check render modal edit', () => {
		const localVue = createLocalVue();
		const wrapper = mount(ShiftEdit, {
			localVue,
			store,
			router,
			computed: {
				pickerWeek() {
					return DATA;
				},
			},
		});

		const MODAL = wrapper.find('#modal-edit');
		expect(MODAL.exists()).toBe(true);

		wrapper.destroy();
	});

	test('Check call function when hook created', () => {
		const initData = jest.fn();
		const createdEmit = jest.fn();

		const localVue = createLocalVue();
		const wrapper = mount(ShiftEdit, {
			localVue,
			store,
			router,
			computed: {
				pickerWeek() {
					return DATA;
				},
			},
			methods: {
				initData,
				createdEmit,
			},
		});

		expect(initData).toHaveBeenCalled();
		expect(createdEmit).toHaveBeenCalled();

		wrapper.destroy();
	});

	test('Check function render table', () => {
		const localVue = createLocalVue();
		const wrapper = mount(ShiftEdit, {
			localVue,
			store,
			router,
			computed: {
				pickerWeek() {
					return DATA;
				},
			},
		});

		wrapper.vm.handleReRenderTable();

		expect(wrapper.vm.reRenderTable).toEqual(2);

		wrapper.destroy();
	});

	test('Check function convertBreakTimeNumberToTime', () => {
		expect(convertBreakTimeNumberToTime(null)).toEqual('00:00');
		expect(convertBreakTimeNumberToTime('1.25')).toEqual('01:15');
		expect(convertBreakTimeNumberToTime('1.50')).toEqual('01:30');
		expect(convertBreakTimeNumberToTime('1.75')).toEqual('01:45');
		expect(convertBreakTimeNumberToTime('23.75')).toEqual('23:45');
		expect(convertBreakTimeNumberToTime('0.00')).toEqual('00:00');
	});
});
