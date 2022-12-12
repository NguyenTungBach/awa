import store from '@/store';
import router from '@/router';
import { mount, createLocalVue } from '@vue/test-utils';
import ShiftList from '@/pages/ShiftManagement/ListShift/index';
import { convertValueToText } from '@/utils/handleSelect';

describe('TEST COMPONENT PARCTICAL PERFORMANCE', () => {
	test('Check render page', () => {
		const localVue = createLocalVue();
		const wrapper = mount(ShiftList, {
			localVue,
			store,
			router,
		});

		const PAGE = wrapper.find('.list-shift');
		expect(PAGE.exists()).toBe(true);

		wrapper.destroy();
	});

	test('Check render button choose table', () => {
		const localVue = createLocalVue();
		const wrapper = mount(ShiftList, {
			localVue,
			store,
			router,
		});

		const CONTROL = wrapper.find('.list-shift__control');
		expect(CONTROL.exists()).toBe(true);

		const BUTTON = CONTROL.find('button');
		expect(BUTTON.exists()).toBe(true);

		wrapper.destroy();
	});

	test('Check render table', () => {
		const localVue = createLocalVue();
		const wrapper = mount(ShiftList, {
			localVue,
			store,
			router,
			data() {
				return {
					selectTable: 'PRACTICAL_ACHIEVEMENTS_MONTHLY',
				};
			},
		});

		const TABLE = wrapper.find('.list-shift__table .zone-table table.table-practical-achievements-monthly');
		expect(TABLE.exists()).toBe(true);

		const THEAD = TABLE.find('thead');
		expect(THEAD.exists()).toBe(true);

		const LIST_TH = THEAD.findAll('th');
		expect(LIST_TH.length).toBe(12);

		const LIST_HEADER_TEXT = [
			'LIST_SHIFT.TABLE_DRIVER_CODE',
			'LIST_SHIFT.TABLE_DRIVER_TYPE',
			'LIST_SHIFT.TABLE_DRIVER_NAME',
			'LIST_SHIFT.TABLE_TOTAL_TIME',
			'LIST_SHIFT.TABLE_DRIVING_TIME',
			'LIST_SHIFT.TABLE_OVER_TIME',
			'LIST_SHIFT.TABLE_WORKING_DAYS',
			'LIST_SHIFT.TABLE_DAY_OFF',
			'LIST_SHIFT.TABLE_PAID_HOLIDAYS',
			'LIST_SHIFT.TABLE_ONE_DAY_MAX_TOTAL_TIME',
			'LIST_SHIFT.TABLE_ONE_DAY_MAX_DRIVING_TIME',
			'LIST_SHIFT.TABLE_FIFTEEN_HOURS_OVER_WORKING_DAYS',
		];

		for (let th = 0; th < LIST_TH.length; th++) {
			expect(LIST_TH.at(th).text()).toBe(LIST_HEADER_TEXT[th]);
		}

		const TBODY = TABLE.find('tbody');
		expect(TBODY.exists()).toBe(true);

		wrapper.destroy();
	});

	test('Check function convert driver type', () => {
		const localVue = createLocalVue();
		const wrapper = mount(ShiftList, {
			localVue,
			store,
			router,
		});

		const LIST_DRIVER_TYPE = [
			{
				value: 'lead',
				text: 'CREATE_DRIVER.LEADER',
			},
			{
				value: 'full',
				text: 'CREATE_DRIVER.FULL_TIME',
			},
			{
				value: 'part',
				text: 'CREATE_DRIVER.PART_TIME',
			},
		];

		expect(convertValueToText(LIST_DRIVER_TYPE, 'lead')).toBe('CREATE_DRIVER.LEADER');
		expect(convertValueToText(LIST_DRIVER_TYPE, 'full')).toBe('CREATE_DRIVER.FULL_TIME');
		expect(convertValueToText(LIST_DRIVER_TYPE, 'part')).toBe('CREATE_DRIVER.PART_TIME');
		expect(convertValueToText(LIST_DRIVER_TYPE, '')).toBe('');
		expect(convertValueToText(LIST_DRIVER_TYPE, null)).toBe('');

		wrapper.destroy();
	});
});
