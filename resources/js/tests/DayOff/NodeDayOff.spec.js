import store from '@/store';
import router from '@/router';
import NodeDayOff from '@/components/NodeDayOff';
import { mount, createLocalVue } from '@vue/test-utils';

describe('TEST COMPONENT DAY OFF LIST', () => {
	test('Check render node', () => {
		const localVue = createLocalVue();
		const wrapper = mount(NodeDayOff, {
			localVue,
			store,
			router,
		});

		const NODE = wrapper.find('.base-node');
		expect(NODE.exists()).toBe(true);

		wrapper.destroy();
	});

	test('Check process class fixed day off', async() => {
		const localVue = createLocalVue();
		const wrapper = mount(NodeDayOff, {
			localVue,
			store,
			router,
		});

		await wrapper.setProps({ item: {
			'driver_code': '0004',
			'date': '2022-09-26',
			'type': 'D-2',
			'color': '#EDD1A7',
			'status': 'on',
			'lunar_jp': {
				'id': 269,
				'date': '2022-09-26',
				'week': '月',
				'rokuyou': '先負',
				'holiday': null,
			},
		}});

		expect(wrapper.vm.processClass('D-2')).toEqual('node-fixed-day-off');

		wrapper.destroy();
	});

	test('Check process class request', async() => {
		const localVue = createLocalVue();
		const wrapper = mount(NodeDayOff, {
			localVue,
			store,
			router,
		});

		await wrapper.setProps({ item: {
			'driver_code': '0004',
			'date': '2022-09-26',
			'type': 'D-3',
			'color': '#EDD1A7',
			'status': 'on',
			'lunar_jp': {
				'id': 269,
				'date': '2022-09-26',
				'week': '月',
				'rokuyou': '先負',
				'holiday': null,
			},
		}});

		expect(wrapper.vm.processClass('D-3')).toEqual('node-day-off-request');

		wrapper.destroy();
	});

	test('Check process class day of paid', async() => {
		const localVue = createLocalVue();
		const wrapper = mount(NodeDayOff, {
			localVue,
			store,
			router,
		});

		await wrapper.setProps({ item: {
			'driver_code': '0004',
			'date': '2022-09-26',
			'type': 'D-4',
			'color': '#EDD1A7',
			'status': 'on',
			'lunar_jp': {
				'id': 269,
				'date': '2022-09-26',
				'week': '月',
				'rokuyou': '先負',
				'holiday': null,
			},
		}});

		expect(wrapper.vm.processClass('D-4')).toEqual('node-paid');

		wrapper.destroy();
	});

	test('Check process class day of default', async() => {
		const localVue = createLocalVue();
		const wrapper = mount(NodeDayOff, {
			localVue,
			store,
			router,
		});

		await wrapper.setProps({ item: {
			'driver_code': '0004',
			'date': '2022-09-26',
			'type': 'D-5',
			'color': '#EDD1A7',
			'status': 'on',
			'lunar_jp': {
				'id': 269,
				'date': '2022-09-26',
				'week': '月',
				'rokuyou': '先負',
				'holiday': null,
			},
		}});

		expect(wrapper.vm.processClass('D-5')).toEqual('node-default');

		wrapper.destroy();
	});

	test('Check process class default', async() => {
		const localVue = createLocalVue();
		const wrapper = mount(NodeDayOff, {
			localVue,
			store,
			router,
		});

		await wrapper.setProps({ item: {
			'driver_code': '0004',
			'date': '2022-09-26',
			'type': 'D-5',
			'color': '#EDD1A7',
			'status': 'off',
			'lunar_jp': {
				'id': 269,
				'date': '2022-09-26',
				'week': '月',
				'rokuyou': '先負',
				'holiday': null,
			},
		}});

		expect(wrapper.vm.processClass('')).toEqual('');

		wrapper.destroy();
	});

	test('Check process text fixed day off', async() => {
		const localVue = createLocalVue();
		const wrapper = mount(NodeDayOff, {
			localVue,
			store,
			router,
		});

		await wrapper.setProps({ item: {
			'driver_code': '0004',
			'date': '2022-09-26',
			'type': 'D-2',
			'color': '#EDD1A7',
			'status': 'on',
			'lunar_jp': {
				'id': 269,
				'date': '2022-09-26',
				'week': '月',
				'rokuyou': '先負',
				'holiday': null,
			},
		}});

		expect(wrapper.vm.processText('D-2')).toEqual('DAY_OFF.TABLE_DATE_FIXED_DAY_OFF');

		wrapper.destroy();
	});

	test('Check process text request', async() => {
		const localVue = createLocalVue();
		const wrapper = mount(NodeDayOff, {
			localVue,
			store,
			router,
		});

		await wrapper.setProps({ item: {
			'driver_code': '0004',
			'date': '2022-09-26',
			'type': 'D-5',
			'color': '#EDD1A7',
			'status': 'off',
			'lunar_jp': {
				'id': 269,
				'date': '2022-09-26',
				'week': '月',
				'rokuyou': '先負',
				'holiday': null,
			},
		}});

		expect(wrapper.vm.processText('D-3')).toEqual('DAY_OFF.TABLE_DATE_DAY_OFF_REQUEST');

		wrapper.destroy();
	});

	test('Check process text day of paid', async() => {
		const localVue = createLocalVue();
		const wrapper = mount(NodeDayOff, {
			localVue,
			store,
			router,
		});

		await wrapper.setProps({ item: {
			'driver_code': '0004',
			'date': '2022-09-26',
			'type': 'D-5',
			'color': '#EDD1A7',
			'status': 'off',
			'lunar_jp': {
				'id': 269,
				'date': '2022-09-26',
				'week': '月',
				'rokuyou': '先負',
				'holiday': null,
			},
		}});

		expect(wrapper.vm.processText('D-4')).toEqual('DAY_OFF.TABLE_DATE_PAID');

		wrapper.destroy();
	});

	test('Check process text day of default', async() => {
		const localVue = createLocalVue();
		const wrapper = mount(NodeDayOff, {
			localVue,
			store,
			router,
		});

		await wrapper.setProps({ item: {
			'driver_code': '0004',
			'date': '2022-09-26',
			'type': 'D-5',
			'color': '#EDD1A7',
			'status': 'off',
			'lunar_jp': {
				'id': 269,
				'date': '2022-09-26',
				'week': '月',
				'rokuyou': '先負',
				'holiday': null,
			},
		}});

		expect(wrapper.vm.processText('D-5')).toEqual('DAY_OFF.TABLE_DEFAULT');

		wrapper.destroy();
	});

	test('Check process text default', async() => {
		const localVue = createLocalVue();
		const wrapper = mount(NodeDayOff, {
			localVue,
			store,
			router,
		});

		await wrapper.setProps({ item: {
			'driver_code': '0004',
			'date': '2022-09-26',
			'type': 'D-5',
			'color': '#EDD1A7',
			'status': 'off',
			'lunar_jp': {
				'id': 269,
				'date': '2022-09-26',
				'week': '月',
				'rokuyou': '先負',
				'holiday': null,
			},
		}});

		expect(wrapper.vm.processText('')).toEqual('');

		wrapper.destroy();
	});

	test('Check click node', async() => {
		const onClickNode = jest.fn();

		const localVue = createLocalVue();
		const wrapper = mount(NodeDayOff, {
			localVue,
			store,
			router,
			methods: {
				onClickNode,
			},
		});

		const CONST_DATE_EDIT = '2022-09-26';
		const CONST_DRIVER_CODE = '0004';
		const CONST_IS_EDIT = true;
		const CONST_ITEM_PROPS = {
			'driver_code': '0004',
			'date': '2022-09-26',
			'type': 'D-3',
			'color': '#EDD1A7',
			'status': 'on',
			'lunar_jp': {
				'id': 269,
				'date': '2022-09-26',
				'week': '月',
				'rokuyou': '先負',
				'holiday': null,
			},
		};

		await wrapper.setProps({
			dateEdit: CONST_DATE_EDIT,
			driverCode: CONST_DRIVER_CODE,
			isEdit: CONST_IS_EDIT,
			item: CONST_ITEM_PROPS,
		});

		const BUTTON = wrapper.find('.base-node');
		await BUTTON.trigger('click');
		expect(onClickNode).toHaveBeenCalled();

		const DATA = {
			item: CONST_ITEM_PROPS,
			driverCode: CONST_DRIVER_CODE,
			date: CONST_DATE_EDIT,
		};

		wrapper.vm.$emit('clickNode');
		wrapper.vm.$emit('clickNode', DATA);

		await wrapper.vm.$nextTick();

		expect(wrapper.emitted().clickNode).toBeTruthy();

		expect(wrapper.emitted().clickNode.length).toBe(2);

		expect(wrapper.emitted().clickNode[1]).toEqual([DATA]);

		wrapper.destroy();
	});

	test('Check genarate by status off', async() => {
		const generate = jest.fn();

		const localVue = createLocalVue();
		const wrapper = mount(NodeDayOff, {
			localVue,
			store,
			router,
			data() {
				return {
					dateNode: {
						nodeType: null,
						nodeText: '',
						nodeClass: '',
					},
				};
			},
			methods: {
				generate,
			},
		});

		const CONST_DATE_EDIT = '2022-09-26';
		const CONST_DRIVER_CODE = '0004';
		const CONST_IS_EDIT = true;
		const CONST_ITEM_PROPS = {
			'driver_code': '0004',
			'date': '2022-09-26',
			'type': 'D-3',
			'color': '#EDD1A7',
			'status': 'off',
			'lunar_jp': {
				'id': 269,
				'date': '2022-09-26',
				'week': '月',
				'rokuyou': '先負',
				'holiday': null,
			},
		};
		const CONST_LIST_UPDATE = '';

		await wrapper.setProps({
			dateEdit: CONST_DATE_EDIT,
			driverCode: CONST_DRIVER_CODE,
			isEdit: CONST_IS_EDIT,
			item: CONST_ITEM_PROPS,
		});

		store.dispatch('listDayoff/setListUpdate', CONST_LIST_UPDATE)
			.then(async() => {
				expect(generate).toHaveBeenCalled();

				const DATE_NODE = wrapper.vm.dateNode;
				expect(DATE_NODE.exists()).toBe(true);

				expect(DATE_NODE.nodeType).toEqual('DISABLE');
				expect(DATE_NODE.nodeClass).toHaveBeenCalled('node-disable');
				expect(DATE_NODE.nodeText).toHaveBeenCalled('');
			});

		wrapper.destroy();
	});

	test('Check genarate by status on', async() => {
		const generate = jest.fn();

		const localVue = createLocalVue();
		const wrapper = mount(NodeDayOff, {
			localVue,
			store,
			router,
			data() {
				return {
					dateNode: {
						nodeType: null,
						nodeText: '',
						nodeClass: '',
					},
				};
			},
			methods: {
				generate,
			},
		});

		const CONST_DATE_EDIT = '2022-09-26';
		const CONST_DRIVER_CODE = '0004';
		const CONST_IS_EDIT = true;
		const CONST_ITEM_PROPS = {
			'driver_code': '0004',
			'date': '2022-09-26',
			'type': 'D-3',
			'color': '#EDD1A7',
			'status': 'on',
			'lunar_jp': {
				'id': 269,
				'date': '2022-09-26',
				'week': '月',
				'rokuyou': '先負',
				'holiday': null,
			},
		};
		const CONST_LIST_UPDATE = {
			'driver_code': '0003',
			'date_off': '2022-09-05',
			'type': 'D-4',
		};

		await wrapper.setProps({
			dateEdit: CONST_DATE_EDIT,
			driverCode: CONST_DRIVER_CODE,
			isEdit: CONST_IS_EDIT,
			item: CONST_ITEM_PROPS,
		});

		store.dispatch('listDayoff/setListUpdate', CONST_LIST_UPDATE)
			.then(async() => {
				expect(generate).toHaveBeenCalled();

				const DATE_NODE = wrapper.vm.dateNode;
				expect(DATE_NODE.exists()).toBe(true);

				expect(DATE_NODE.nodeType).toEqual('D-3');
				expect(DATE_NODE.nodeClass).toHaveBeenCalled('node-day-off-request');
				expect(DATE_NODE.nodeText).toHaveBeenCalled('DAY_OFF.TABLE_DATE_DAY_OFF_REQUEST');
			});

		wrapper.destroy();
	});

	test('Check genarate by status on and updated', async() => {
		const generate = jest.fn();

		const localVue = createLocalVue();
		const wrapper = mount(NodeDayOff, {
			localVue,
			store,
			router,
			data() {
				return {
					dateNode: {
						nodeType: null,
						nodeText: '',
						nodeClass: '',
					},
				};
			},
			methods: {
				generate,
			},
		});

		const CONST_DATE_EDIT = '2022-09-26';
		const CONST_DRIVER_CODE = '0004';
		const CONST_IS_EDIT = true;
		const CONST_ITEM_PROPS = {
			'driver_code': '0004',
			'date': '2022-09-26',
			'type': 'D-3',
			'color': '#EDD1A7',
			'status': 'on',
			'lunar_jp': {
				'id': 269,
				'date': '2022-09-26',
				'week': '月',
				'rokuyou': '先負',
				'holiday': null,
			},
		};
		const CONST_LIST_UPDATE = {
			'driver_code': '0004',
			'date_off': '2022-09-26',
			'type': 'D-4',
		};

		await wrapper.setProps({
			dateEdit: CONST_DATE_EDIT,
			driverCode: CONST_DRIVER_CODE,
			isEdit: CONST_IS_EDIT,
			item: CONST_ITEM_PROPS,
		});

		store.dispatch('listDayoff/setListUpdate', CONST_LIST_UPDATE)
			.then(async() => {
				expect(generate).toHaveBeenCalled();

				const DATE_NODE = wrapper.vm.dateNode;
				expect(DATE_NODE.exists()).toBe(true);

				expect(DATE_NODE.nodeType).toEqual('D-4');
				expect(DATE_NODE.nodeClass).toHaveBeenCalled('node-paid');
				expect(DATE_NODE.nodeText).toHaveBeenCalled('DAY_OFF.TABLE_DATE_PAID');
			});

		wrapper.destroy();
	});
});
