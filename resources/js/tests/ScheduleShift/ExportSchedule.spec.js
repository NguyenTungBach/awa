import store from '@/store';
import router from '@/router';
import { mount, createLocalVue } from '@vue/test-utils';
import ScheduleShift from '@/pages/ShiftManagement/ListSchedule/index';

describe('TEST COMPONENT EXPORT SCHEDULE', () => {
	test('Check render button export', () => {
		const localVue = createLocalVue();
		const wrapper = mount(ScheduleShift, {
			localVue,
			store,
			router,
		});

		const ZONE_EXPORT = wrapper.find('.item-function');
		expect(ZONE_EXPORT.exists()).toBe(true);

		const ICON_EXPORT = wrapper.find('.show-icon');
		expect(ICON_EXPORT.exists()).toBe(true);
		expect(ICON_EXPORT.find('.fas').exists()).toBe(true);
		expect(ICON_EXPORT.find('.fa-file-excel').exists()).toBe(true);

		const TEXT_EXPORT = wrapper.find('.show-text');
		expect(TEXT_EXPORT.exists()).toBe(true);
		expect(TEXT_EXPORT.text()).toBe('Excel出力');

		wrapper.destroy();
	});

	test('Check click button export', async() => {
		const onClickExport = jest.fn();

		const localVue = createLocalVue();
		const wrapper = mount(ScheduleShift, {
			localVue,
			store,
			router,
			methods: {
				onClickExport,
			},
		});

		const ZONE_EXPORT = wrapper.find('.item-function');
		expect(ZONE_EXPORT.exists()).toBe(true);

		await ZONE_EXPORT.trigger('click');

		expect(onClickExport).toHaveBeenCalled();

		wrapper.destroy();
	});
});
