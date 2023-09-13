<template>
    <b-col>
        <div class="list-shift">
            <div class="list-shift__header">
                <b-row>
                    <b-col>
                        <div class="zone-left">
                            <div class="zone-title">
                                <span
                                    v-if="(selectTable === CONSTANT.LIST_SHIFT.COURSE_BASE_TABLE)"
                                    class="title-page"
                                >
                                    {{ $t("LIST_SHIFT.BUTTON_COURSE_BASE") }}
                                </span>
                                <span
                                    v-else
                                    class="title-page"
                                >
                                    {{ $t("LIST_SHIFT.TITLE_LIST_SHIFT") }}
                                </span>
                            </div>
                        </div>
                    </b-col>
                </b-row>
            </div>
            <LineGray />
            <div class="list-shift__control">
                <b-row>
                    <b-col>
                        <div class="text-right">
                            <b-button
                                pill
                                class="btn-return"
                                @click="goToListShift()"
                            >
                                {{ $t("LIST_SHIFT.BUTTON_RETURN") }}
                            </b-button>
                            <b-button
                                pill
                                class="btn-save btn-color-active"
                                @click="onClickSave()"
                            >
                                {{ $t("LIST_SHIFT.BUTTON_SAVE") }}
                            </b-button>
                        </div>
                    </b-col>
                </b-row>
            </div>
            <div class="list-shift__table">
                <div class="zone-table">
                    <b-table-simple
                        v-show="selectTable === CONSTANT.LIST_SHIFT.SHIFT_TABLE"
                        :key="reRenderTable"
                        bordered
                        no-border-collapse
                    >
                        <b-thead>
                            <b-tr>
                                <b-th
                                    :colspan="3"
                                    class="fix-header"
                                />
                                <template v-for="date in pickerYearMonth.numberDate">
                                    <b-th
                                        :key="`date-${date}`"
                                        class="th-show-date"
                                    >
                                        <div>
                                            {{ date }} ({{ getTextDay(`${pickerYearMonth.year}-${pickerYearMonth.month}-${date}`) }})
                                        </div>
                                    </b-th>
                                </template>
                            </b-tr>
                            <b-tr>
                                <b-th class="th-employee-number">
                                    {{ $t("LIST_SHIFT.TABLE_DATE_EMPLOYEE_NUMBER") }}
                                </b-th>
                                <b-th class="th-type-employee">
                                    {{ $t('LIST_SHIFT.TABLE_DRIVER_TYPE') }}
                                </b-th>
                                <b-th class="th-full-name">
                                    {{ $t("LIST_SHIFT.TABLE_FULL_NAME") }}
                                </b-th>
                                <template v-for="date in pickerYearMonth.numberDate">
                                    <b-th :key="`date-${date}`">
                                        <span>
                                            {{ listCalendar[date - 1] }}
                                        </span>
                                    </b-th>
                                </template>
                            </b-tr>
                        </b-thead>
                        <b-tbody>
                            <template
                                v-for="(emp, idx) in listShift"
                            >
                                <b-tr :key="`emp-no-${idx + 1}`">
                                    <b-td class="td-employee-number">
                                        {{ emp.driver_code }}
                                    </b-td>

                                    <b-td class="td-type-employee">
                                        {{ $t(convertValueToText(optionsTypeDriver, emp.type)) }}
                                    </b-td>

                                    <b-td class="td-full-name text-center">
                                        {{ emp.driver_name }}
                                    </b-td>
                                    <template v-for="(date, idxDate) in pickerYearMonth.numberDate">
                                        <template v-if="emp.dataShift">
                                            <NodeListShift
                                                :key="`date-${date}-${idxDate}`"
                                                :idx-component="idxDate + 1"
                                                :date="date"
                                                :check-table="CONSTANT.LIST_SHIFT.SHIFT_TABLE"
                                                :data-node="emp.dataShift.data_by_date[idxDate]"
                                                :emp-data="emp"
                                                :is-edit="true"
                                                :driver-code="emp.driver_code"
                                                :driver-name="emp.driver_name"
                                            />
                                        </template>

                                        <NodeListShift
                                            v-else
                                            :key="`dateNull-${date}-${idxDate}`"
                                            :idx-component="idxDate + 1"
                                            :check-table="CONSTANT.LIST_SHIFT.SHIFT_TABLE"
                                            :date="date"
                                            :is-edit="true"
                                            :data-node="emp.dataShift"
                                            :emp-data="emp"
                                            :driver-code="emp.driver_code"
                                            :driver-name="emp.driver_name"
                                        />
                                    </template>
                                </b-tr>
                            </template>
                        </b-tbody>
                    </b-table-simple>
                </div>
            </div>
        </div>
        <b-modal
            id="modal-detail"
            v-model="modalDetail"
            body-class="modal-detail-node"
            hide-header
            hide-footer
            static
        >
            <template #default>
                <div class="detail-node">
                    <div
                        v-for="(item, idx) in nodeEmit.listShift"
                        :key="`item-detail-node-${idx}`"
                        class="item-node"
                    >
                        <span v-if="item.course" class="type-node">
                            {{ item.course.course_name }}
                        </span>
                        <b-row v-if="!CONSTANT.LIST_SHIFT.LIST_VALUE_DAY_OFF.includes(item.course_id) && !CONSTANT.LIST_SHIFT.LIST_VALUE_HALF_DAY_OFF.includes(item.course_id)">

                            <b-col>
                                <div class="item-time">
                                    <span>始業時間: {{ item.start_time }}</span>
                                </div>
                            </b-col>

                        </b-row>
                        <b-row v-if="!CONSTANT.LIST_SHIFT.LIST_VALUE_DAY_OFF.includes(item.course_id) && !CONSTANT.LIST_SHIFT.LIST_VALUE_HALF_DAY_OFF.includes(item.course_id)">
                            <b-col>
                                <div class="item-time">
                                    <span>終業時間: {{ item.end_time }}</span>
                                </div>
                            </b-col>
                        </b-row>
                        <b-row v-if="!CONSTANT.LIST_SHIFT.LIST_VALUE_DAY_OFF.includes(item.course_id) && !CONSTANT.LIST_SHIFT.LIST_VALUE_HALF_DAY_OFF.includes(item.course_id)">
                            <b-col>
                                <div class="item-time">
                                    <span>休憩時間: {{ convertBreakTimeNumberToTime(item.break_time) }}</span>
                                </div>
                            </b-col>
                        </b-row>
                    </div>
                </div>
                <div class="modal-detail-node-control">
                    <b-button
                        pill
                        @click="onClickCancelDetail()"
                    >
                        {{ $t('APP.TEXT_CLOSE') }}
                    </b-button>
                    <b-button
                        pill
                        class="btn-change"
                        @click="onClickChangeNode"
                    >
                        {{ $t("LIST_SHIFT.BUTTON_EDIT") }}
                    </b-button>
                </div>
            </template>
        </b-modal>
        <b-modal
            id="modal-edit"
            v-model="modalEdit"
            body-class="modal-edit-node"
            scrollable
            hide-header
            hide-footer
            hide-header-close
            no-close-on-esc
            no-close-on-backdrop
            static
        >
            <template #default>
                <!-- <div class="edit-node">
                    <span>シフトを選択</span>
                </div> -->
                <div class="detail-node">
                    <div
                        v-for="(item, idx) in nodeEmit.listShift"
                        :key="`item-detail-node-${idx}`"
                        class="item-node"
                    >
                        <span v-if="!CONSTANT.LIST_SHIFT.LIST_VALUE_DAY_OFF.includes(item.course_id) && !CONSTANT.LIST_SHIFT.LIST_VALUE_HALF_DAY_OFF.includes(item.course_id)" class="type-node">
                            {{ item.course.course_name }}
                        </span>
                        <b-row v-if="!CONSTANT.LIST_SHIFT.LIST_VALUE_DAY_OFF.includes(item.course_id) && !CONSTANT.LIST_SHIFT.LIST_VALUE_HALF_DAY_OFF.includes(item.course_id)">

                            <b-col>
                                <div class="item-time">
                                    <span>始業時間: {{ item.start_time }}</span>
                                </div>
                            </b-col>

                        </b-row>
                        <b-row v-if="!CONSTANT.LIST_SHIFT.LIST_VALUE_DAY_OFF.includes(item.course_id) && !CONSTANT.LIST_SHIFT.LIST_VALUE_HALF_DAY_OFF.includes(item.course_id)">
                            <b-col>
                                <div class="item-time">
                                    <span>終業時間: {{ item.end_time }}</span>
                                </div>
                            </b-col>
                        </b-row>
                        <b-row v-if="!CONSTANT.LIST_SHIFT.LIST_VALUE_DAY_OFF.includes(item.course_id) && !CONSTANT.LIST_SHIFT.LIST_VALUE_HALF_DAY_OFF.includes(item.course_id)">
                            <b-col>
                                <div class="item-time">
                                    <span>休憩時間: {{ convertBreakTimeNumberToTime(item.break_time) }}</span>
                                </div>
                            </b-col>
                        </b-row>
                    </div>
                </div>
                <EditNodeListShift
                    :list-select="listNodeEdit"
                    :half-day-off="halfDayOff"
                    :list-course="listCourse"
                    :list-selected="listNodeEditSelected"
                    @add="onAddNode"
                    @edit="onEditNode"
                    @remove="onRemoveNode"
                    @dayoff="onSelectedDayOff"
                    @change="onChangeNode"
                />
                <div class="edit-control">
                    <b-button
                        pill
                        @click="onClickCancelEdit()"
                    >
                        {{ $t('APP.TEXT_CANCEL') }}
                    </b-button>
                    <b-button
                        pill
                        class="btn-save"
                        @click="onClickSaveNode"
                    >
                        {{ $t("LIST_SHIFT.BUTTON_OK") }}
                    </b-button>
                </div>
            </template>
        </b-modal>
    </b-col>
</template>

<script>
import CONSTANT from '@/const';
import LineGray from '@/components/LineGray';
import { setLoading } from '@/utils/handleLoading';
import { format2Digit } from '@/utils/generateTime';
import { getCalendar } from '@/api/modules/calendar';
import NodeListShift from '@/components/NodeListShift';
import EditNodeListShift from '@/components/EditNodeListShift';
import { getTextDayInWeek, getTextDay } from '@/utils/convertTime';
import { getListShift, putShift, getDataUpdate } from '@/api/modules/shiftManagement';
import { convertValueToText } from '@/utils/handleSelect';
import { cleanObject } from '@/utils/handleObject';
// import { convertValueWhenNull } from '@/utils/handleListShift';
import { convertBreakTimeNumberToTime, convertTextToSelectTime, formatArray2Time, convertTimeForDetail } from '@/utils/convertTime';
// import { getList } from '@/api/modules/courseManagement';
import Notification from '@/toast/notification';
// import { validateEditShift } from './helper/validateEditShift';

export default {
	name: 'ListShift',
	components: {
		LineGray,
		NodeListShift,
		EditNodeListShift,
	},

	data() {
		return {
			CONSTANT,
			halfDayOff: [],
			selectWeekMonth: this.$store.getters.weekOrMonthListShift || CONSTANT.LIST_SHIFT.WEEK,

			selectTable: this.$store.getters.tableListShift || CONSTANT.LIST_SHIFT.SHIFT_TABLE,

			modalDetail: false,
			modalEdit: false,

			listNodeEdit: [],
			listNodeEditSelected: [],
			listCalendar: [],

			listShift: [],

			nodeEmit: {},

			listCourse: [],

			listUpdate: [],

			reRenderTable: 1,
			courseDisabled: [],
			listDataUpdate: [],
		};
	},

	computed: {
		pickerYearMonth() {
			return this.$store.getters.pickerYearMonth;
		},

		pickerWeek() {
			return this.$store.getters.pickerWeek;
		},

		language() {
			return this.$store.getters.language;
		},

		optionsTypeDriver() {
			return CONSTANT.LIST_DRIVER.LIST_FLAG;
		},

		role() {
			return this.$store.getters.profile.role;
		},
	},

	watch: {
		async pickerYearMonth() {
			if (this.selectWeekMonth === CONSTANT.LIST_SHIFT.MONTH) {
				await this.handleGetListCalendar();
			}
		},

		listUpdate: {
			handler: function() {
				this.setListUpdate(this.listUpdate);
			},

			deep: true,
		},
	},

	created() {
		this.initData();
		this.createdEmit();
	},

	destroyed() {
		this.destroyEmit();
	},

	methods: {
		convertValueToText,
		convertBreakTimeNumberToTime,
		async initData() {
			this.listNodeEdit = CONSTANT.LIST_SHIFT.LIST_DAY_OFF;
			this.halfDayOff = CONSTANT.LIST_SHIFT.HALF_DAY_OF;

			setLoading(true);

			await this.handleGetListCalendar();
			await this.handleGetListShift();
			await this.handleGetDataUpdate();

			setLoading(false);
		},

		handleChangeToMonth(index) {
			return index < 10 ? `0${index}` : `${index}`;
		},

		handleChangeFromMonthtoIndex(index) {
			return index < 10 ? index.slice(-1) : index;
		},

		handleGetAllCourseWork(idx = null) {
			const result = [];

			if (idx >= 0) {
				const len = this.listShift.length;
				let idxDriver = 0;

				while (idxDriver < len) {
					const arrValue = this.listShift[idxDriver].dataShift.data_by_date[idx];
					// console.log(`loop: ${idxDriver} - ${idx}`);

					// const lenValue = arrValue.length;
					// let idxValue = 0;

					// while (idxValue < lenValue) {
					// 	const TYPE = arrValue[idxValue].course_ids;
					// 	if (!(CONSTANT.LIST_SHIFT.LIST_DAY_OFF).includes(TYPE)) {
					// 		result.push(TYPE);
					// 	}

					// 	idxValue++;
					// }
					const TYPE = Number(arrValue.course_ids);
					if (!(CONSTANT.LIST_SHIFT.LIST_DAY_OFF).includes(TYPE)) {
						result.push(TYPE);
					}

					idxDriver++;
				}
			}

			// console.log(result);

			return result;
		},

		async detailShift(idDriverCourse, date) {
			try {
				const PARAMS = {};
				this.nodeEmit = {};
				PARAMS.date = date;
				const { code, data } = await getListShift(`${CONSTANT.URL_API.GET_DETAIL_SHIFT}/${idDriverCourse}`, PARAMS);
				if (code === 200) {
					this.nodeEmit = data;
					console.log('node Emit', this.nodeEmit);
				}
				console.log('node Emit aaa', this.nodeEmit);
			} catch (error) {
				console.log(error);
			}
		},

		async createdEmit() {
			this.$bus.on('LIST_SHITF_CLICK_NODE', async(data) => {
				this.listNodeEditSelected = [];
				this.listNodeEdit = CONSTANT.LIST_SHIFT.LIST_DAY_OFF;
				// await this.handleGetListCourse(data.dateDriver);
				await this.detailShift(data.id, data.dateDriver);

				// const DATE_CHECK = data.dateDriver;
				// const lenListUpdate = this.listUpdate.length;
				// let idxUpdate = 0;
				// const listCheck = [];

				// if (lenListUpdate > 0) {
				// 	while (idxUpdate < lenListUpdate) {
				// 		if (this.listUpdate[idxUpdate].date_edit === DATE_CHECK) {
				// 			listCheck.push(this.listUpdate[idxUpdate]);
				// 		}

				// 		idxUpdate++;
				// 	}
				// }

				// const listCourseTypeCheck = [];
				// let TYPE_CHECK = '';
				// idxUpdate = 0;
				// let idx = 0;
				// if (listCheck.length > 0) {
				// 	while (idxUpdate < listCheck.length) {
				// 		while (idx < listCheck[idxUpdate].shift_list_update.length) {
				// 			TYPE_CHECK = listCheck[idxUpdate].shift_list_update[idx].type;
				// 			listCourseTypeCheck.push(TYPE_CHECK);

				// 			idx++;
				// 		}

				// 		idx = 0;
				// 		idxUpdate++;
				// 	}

				// 	TYPE_CHECK = '';
				// }

				// TYPE_CHECK = '';
				// idxUpdate = 0;
				// idx = 0;
				// let INDEX_DATE_CHECK = -1;
				// if (this.listShift.length > 0) {
				// 	while (idx < this.listShift[0].shift_list.length) {
				// 		if (this.listShift[0].shift_list[idx].date === DATE_CHECK) {
				// 			INDEX_DATE_CHECK = idx;
				// 			break;
				// 		}

				// 		idx++;
				// 	}

				// 	idx = 0;
				// 	if (INDEX_DATE_CHECK !== -1) {
				// 		while (idxUpdate < this.listShift.length) {
				// 			if (this.listShift[idxUpdate].shift_list[INDEX_DATE_CHECK].value.length > 0) {
				// 				while (idx < this.listShift[idxUpdate].shift_list[INDEX_DATE_CHECK].value.length) {
				// 					TYPE_CHECK = this.listShift[idxUpdate].shift_list[INDEX_DATE_CHECK].value[idx].type;
				// 					listCourseTypeCheck.push(TYPE_CHECK);

				// 					idx++;
				// 				}
				// 			}

				// 			idx = 0;
				// 			idxUpdate++;
				// 		}
				// 	}
				// }

				// check course choosed

				this.listNodeEditSelected.length = 0;

				const OLD_SELECTED = this.nodeEmit.listShift || [];

				const lenOldSelected = OLD_SELECTED.length;
				let idxOldSelected = 0;
				console.log('this nodeEdit', OLD_SELECTED);

				while (idxOldSelected < lenOldSelected) {
					const OLD_DATA = {
						name: null,
						type: null,
						start_time: [null, null],
						end_time: [null, null],
						break_time: [null, null],
					};

					OLD_DATA.name = OLD_SELECTED[idxOldSelected].course.course_name;
					OLD_DATA.type = OLD_SELECTED[idxOldSelected].course_id;
					OLD_DATA.start_time = convertTextToSelectTime(convertTimeForDetail(OLD_SELECTED[idxOldSelected].start_time));
					OLD_DATA.end_time = convertTextToSelectTime(convertTimeForDetail(OLD_SELECTED[idxOldSelected].end_time));
					OLD_DATA.break_time = convertTextToSelectTime(convertTimeForDetail(OLD_SELECTED[idxOldSelected].break_time));
					console.log('dữ liệu chuẩn bị push:', OLD_DATA.start_time);
					this.listNodeEditSelected.push(OLD_DATA);

					idxOldSelected++;
				}
				console.log('datalisNote:', this.listNodeEditSelected);

				const lenListSelected = this.listNodeEditSelected.length;
				let idxListSelected = 0;

				while (idxListSelected < lenListSelected) {
					const SELECTED = this.listNodeEditSelected[idxListSelected];

					const SELECTED_TYPE = SELECTED.type;
					const FIND_SELECTED = this.listCourse.find((item) => item.value === SELECTED_TYPE);

					if (FIND_SELECTED) {
						const DATA_COURSE = {
							flag: FIND_SELECTED.flag,
							start_time: FIND_SELECTED.start_time,
							end_time: FIND_SELECTED.end_time,
							break_time: FIND_SELECTED.break_time,
						};

						this.listNodeEditSelected[idxListSelected].course = DATA_COURSE;
					} else {
						if (SELECTED_TYPE === CONSTANT.LIST_SHIFT.DATE_WAIT_BETWEEN_TASK) {
							this.listNodeEditSelected[idxListSelected].course = {
								// flag: 'yes',
								start_time: formatArray2Time(this.listNodeEditSelected[idxListSelected].start_time),
								end_time: formatArray2Time(this.listNodeEditSelected[idxListSelected].end_time),
								break_time: formatArray2Time(this.listNodeEditSelected[idxListSelected].break_time),
							};
						} else if (SELECTED_TYPE === CONSTANT.LIST_SHIFT.DATE_LEADER_CHIEF) {
							this.listNodeEditSelected[idxListSelected].course = {
								// flag: 'yes',
								start_time: formatArray2Time(this.listNodeEditSelected[idxListSelected].start_time),
								end_time: formatArray2Time(this.listNodeEditSelected[idxListSelected].end_time),
								break_time: formatArray2Time(this.listNodeEditSelected[idxListSelected].break_time),
							};
						} else {
							this.listNodeEditSelected[idxListSelected].course = {
								// flag: null,
								start_time: null,
								end_time: null,
								break_time: null,
							};
						}
					}

					idxListSelected++;
				}

				console.log('list course - disable', this.listCourse);
				const COURSE_DISABLED = this.handleGetAllCourseWork(data.index - 1);
				this.courseDisabled = COURSE_DISABLED;
				console.log('course - disable', COURSE_DISABLED);

				const lenCourse = this.listCourse.length;
				let idxCourse = 0;

				while (idxCourse < lenCourse) {
					if (COURSE_DISABLED.includes(this.listCourse[idxCourse].value)) {
						this.listCourse[idxCourse].disabled = true;
					} else {
						this.listCourse[idxCourse].disabled = false;
					}

					idxCourse++;
				}

				// console.log(this.listCourse);

				// if (this.listNodeEditSelected.length > 0) {
				// 	this.modalDetail = true;
				// } else {
				// 	this.modalEdit = true;
				// }
				if (this.nodeEmit.listShift.length !== 0) {
					this.modalDetail = true;
				} else {
					this.modalEdit = true;
				}
			});
		},

		destroyEmit() {
			this.$bus.off('LIST_SHITF_CLICK_NODE');
		},

		// async handleGetListCourse(dateDriver) {
		// 	// const LABOUR = {
		// 	// 	value: 'L-0',
		// 	// 	text: this.$t('LIST_SHIFT.LABOUR'),
		// 	// 	flag: 'yes',
		// 	// 	status: 'on',
		// 	// 	start_time: '',
		// 	// 	end_time: '',
		// 	// 	break_time: '',
		// 	// 	disabled: false,
		// 	// };
		// 	try {
		// 		const param = {
		// 			date: dateDriver,
		// 		};
		// 		const { code, data } = await getList(CONSTANT.URL_API.GET_COURSE_SHIFT, param);
		// 		if (code === 200) {
		// 			this.listCourse = [];

		// 			// this.listCourse.push(LABOUR);
		// 			const len = data.length;
		// 			let idx = 0;

		// 			while (idx < len) {
		// 				if (data[idx].driver_id !== null) {
		// 					this.listCourse.push({
		// 						value: data[idx].id,
		// 						text: data[idx].course_name,
		// 						// status: data[idx].status,
		// 						id: idx,
		// 						start_time: data[idx].start_date,
		// 						end_time: data[idx].end_date,
		// 						break_time: data[idx].break_time,
		// 						disabled: true,
		// 					});
		// 				} else {
		// 					this.listCourse.push({
		// 						value: data[idx].id,
		// 						text: data[idx].course_name,
		// 						// status: data[idx].status,
		// 						id: idx,
		// 						start_time: data[idx].start_date,
		// 						end_time: data[idx].end_date,
		// 						break_time: data[idx].break_time,
		// 						disabled: false,
		// 					});
		// 				}

		// 				idx++;
		// 			}
		// 			console.log('list course detail', this.listCourse);
		// 		} else {
		// 			this.listCourse.length = 0;
		// 		}
		// 	} catch (error) {
		// 		console.log(error);
		// 	}
		// },

		async handleGetListCalendar() {
			try {
				this.listCalendar = [];

				let START_DATE = '';
				let END_DATE = '';

				// if (this.selectWeekMonth === CONSTANT.LIST_SHIFT.WEEK) {
				// 	START_DATE = this.pickerWeek.listDate[0].text;
				// 	END_DATE =
				//             this.pickerWeek.listDate[this.pickerWeek.listDate.length - 1].text;
				// }

				START_DATE = `${this.pickerYearMonth.year}-${format2Digit(this.pickerYearMonth.month)}-01`;
				END_DATE = `${this.pickerYearMonth.year}-${format2Digit(this.pickerYearMonth.month)}-${this.pickerYearMonth.numberDate}`;
				// if ([CONSTANT.LIST_SHIFT.SHIFT_TABLE, CONSTANT.LIST_SHIFT.COURSE_BASE_TABLE].includes(this.selectTable)) {
				// 	if (this.selectWeekMonth === CONSTANT.LIST_SHIFT.WEEK) {
				// 		START_DATE = this.pickerWeek.listDate[0].text;
				// 		END_DATE = this.pickerWeek.listDate[this.pickerWeek.listDate.length - 1].text;
				// 	}

				// 	if (this.selectWeekMonth === CONSTANT.LIST_SHIFT.MONTH) {
				// 		START_DATE = `${this.pickerYearMonth.year}-${format2Digit(this.pickerYearMonth.month)}-01`;
				// 		END_DATE = `${this.pickerYearMonth.year}-${format2Digit(this.pickerYearMonth.month)}-${this.pickerYearMonth.numberDate}`;
				// 	}
				// }

				// if (this.selectTable === CONSTANT.LIST_SHIFT.SALES_AMOUNT_TABLE) {
				// 	START_DATE = `${this.pickerYearMonth.year}-${format2Digit(this.pickerYearMonth.month)}-01`;
				// 	END_DATE = `${this.pickerYearMonth.year}-${format2Digit(this.pickerYearMonth.month)}-${this.pickerYearMonth.numberDate}`;
				// }

				const CALENDAR = await getCalendar(CONSTANT.URL_API.GET_CALENDAR, {
					start_date: START_DATE,
					end_date: END_DATE,
				});

				if (CALENDAR.code === 200) {
					const len = CALENDAR.data.length;
					let idx = 0;

					while (idx < len) {
						this.listCalendar.push(CALENDAR.data[idx].rokuyou);

						idx++;
					}
				}
			} catch {
				setLoading(false);
			}
		},

		async handleGetListShift() {
			try {
				setLoading(true);
				this.listShift.length = 0;

				let PARAMS = {};
				const YEAR = this.pickerYearMonth.year;
				const MONTH = this.pickerYearMonth.month;

				const YEAR_MONTH = `${YEAR}-${format2Digit(MONTH)}`;

				PARAMS.month_year = YEAR_MONTH;

				PARAMS = cleanObject(PARAMS);

				const { code, data } = await getListShift(CONSTANT.URL_API.GET_LIST_SHIFT_TABLE, PARAMS);

				if (code === 200) {
					this.listShift = data;
				}
				setLoading(false);
			} catch (error) {
				setLoading(false);
				console.log(error);
			}
		},

		getTextDayInWeek,
		getTextDay,
		goToListShift() {
			this.$router.push({ name: 'ListShift' });
		},

		onClickChangeNode() {
			this.modalDetail = false;

			this.modalEdit = true;
		},

		onAddNode() {
			this.listNodeEditSelected.push({
				type: null,
				start_time: [null, null],
				end_time: [null, null],
				break_time: [null, null],
				course: {
					// flag: null,
					start_time: null,
					end_time: null,
					break_time: null,
				},
			});
		},

		onEditNode(data) {
			const INDEX = data.index;
			const KEY = data.key;
			const VALUE = data.value;

			this.listNodeEditSelected[INDEX][KEY] = VALUE;
		},

		onRemoveNode(itemEdit, idxEdit) {
			console.log('remove', this.listUpdate);
			console.log('list selected', itemEdit);
			this.listUpdate.forEach((item) => {
				item.listShift = (item.listShift).filter(value => value.course_id !== itemEdit.type);
			});
			console.log('dữ liệu sau khi xóa', this.listUpdate);
			this.listNodeEditSelected.splice(idxEdit, 1);
		},

		onClickSaveNode() {
			const FILTER_LIST_SELECTED = this.handleFilterListSelected(this.listNodeEditSelected, [null]);

			// const VALIDATE = validateEditShift(FILTER_LIST_SELECTED);

			// if (VALIDATE.status) {
			// 	this.modalEdit = false;

			// 	const DRIVER_CODE = this.nodeEmit.driverCode;
			// 	const INDEX_OF_DRIVER = this.findIndexOfDriverCode(this.listShift, DRIVER_CODE);

			// 	const TYPE_TABLE = this.$store.getters.weekOrMonthListShift;

			// 	let INDEX_CELL_OF_DRIVER = -1;

			// 	if (TYPE_TABLE === 'MONTH') {
			// 		INDEX_CELL_OF_DRIVER = this.nodeEmit.date - 1;
			// 	} else {
			// 		INDEX_CELL_OF_DRIVER = this.nodeEmit.index - 1;
			// 	}

			// 	const DATA_UPDATE = FILTER_LIST_SELECTED;

			// 	this.listShift = this.handleUpdateTable(this.listShift, INDEX_OF_DRIVER, INDEX_CELL_OF_DRIVER, DATA_UPDATE);
			// 	console.log('listShift:', this.listShift);

			// 	const INIT_DATA = this.handleinitObjectUpdate(this.nodeEmit, FILTER_LIST_SELECTED);
			// 	this.listUpdate = this.handleUpdateListUpdate(this.listUpdate, INIT_DATA);

			// 	this.listNodeEditSelected.length = 0;
			// } else {
			// 	Notification.warning(this.$t(VALIDATE.message));
			// }
			// this.modalEdit = false;
			console.log('list node:', FILTER_LIST_SELECTED);

			const DRIVER_CODE = Number(this.nodeEmit.driver_id);
			const INDEX_OF_DRIVER = this.findIndexOfDriverCode(this.listShift, DRIVER_CODE);

			// const TYPE_TABLE = this.$store.getters.weekOrMonthListShift;
			// console.log('month:', TYPE_TABLE);

			// let INDEX_CELL_OF_DRIVER = -1;

			// if (TYPE_TABLE === 'MONTH') {
			// 	INDEX_CELL_OF_DRIVER = Number(this.handleChangeFromMonthtoIndex((this.nodeEmit.date).slice(-2))) - 1;
			// } else {
			// 	INDEX_CELL_OF_DRIVER = -1;
			// }
			let INDEX_CELL_OF_DRIVER = -1;
			INDEX_CELL_OF_DRIVER = Number(this.handleChangeFromMonthtoIndex((this.nodeEmit.date).slice(-2))) - 1;
			console.log('listShift:', INDEX_CELL_OF_DRIVER);
			console.log('index:', DRIVER_CODE);

			const DATA_UPDATE = FILTER_LIST_SELECTED;

			this.listShift = this.handleUpdateTable(this.listShift, INDEX_OF_DRIVER, INDEX_CELL_OF_DRIVER, DATA_UPDATE);

			const INIT_DATA = this.handleinitObjectUpdate(this.nodeEmit, FILTER_LIST_SELECTED);
			// this.handleinitObjectUpdate(this.nodeEmit, FILTER_LIST_SELECTED);
			console.log('init data', INIT_DATA);
			console.log('data update:', this.listUpdate);
			this.listUpdate = this.handleUpdateListUpdate(this.listUpdate, INIT_DATA);
			this.listNodeEditSelected.length = 0;
			this.modalEdit = false;
		},

		handleUpdateTable(listShift, idxOfDriver, idxCellOfDriver, dataUpdate) {
			if (dataUpdate.length > 0) {
				const LIST_TYPE_SELECTED = dataUpdate.map((item) => item.type);
				console.log('list type selected', LIST_TYPE_SELECTED);

				const LIST_DAY_OFF = LIST_TYPE_SELECTED.filter((item) => (CONSTANT.LIST_SHIFT.LIST_VALUE_DAY_OFF).includes(item));
				console.log('day off', LIST_DAY_OFF);

				if (LIST_DAY_OFF.length > 0) {
					// console.log(listShift[idxOfDriver].shift_list);

					listShift[idxOfDriver].dataShift.data_by_date[idxCellOfDriver].course_names_color = this.role === CONSTANT.ROLE.ADMIN ? CONSTANT.LIST_SHIFT.MAP_TYPE_COLOR_DAY_OFF[LIST_DAY_OFF[0]] : CONSTANT.LIST_SHIFT.COLOR_HOLIDAY;
					const listdataUpdate = this.generateListValueDayOff(dataUpdate);
					var updateCourseNameDayOff = '';
					listdataUpdate.forEach(item => {
						if (item.name) {
							updateCourseNameDayOff += `${item.name} `;
						}
					});
					listShift[idxOfDriver].dataShift.data_by_date[idxCellOfDriver].course_names += ` ${updateCourseNameDayOff}`;
				} else {
					listShift[idxOfDriver].dataShift.data_by_date[idxCellOfDriver].course_names_color = CONSTANT.LIST_SHIFT.COLOR_WORKING_DAY;
					const listdataUpdate = this.generateListValueWork(dataUpdate);
					console.log('LIST_TYPE_SELECTED: ', listdataUpdate);
					var updateCourseName = '';
					listdataUpdate.forEach(item => {
						if (item.name) {
							updateCourseName += `${item.name} `;
						} else {
							updateCourseName = '';
						}
					});
					listShift[idxOfDriver].dataShift.data_by_date[idxCellOfDriver].course_names = updateCourseName;
				}
			} else {
				listShift[idxOfDriver].dataShift.data_by_date[idxCellOfDriver].course_names = '';
			}

			this.handleReRenderTable();

			return listShift;
		},

		generateListValueDayOff(dataUpdate) {
			// console.log(dataUpdate);

			const result = [];

			const len = dataUpdate.length;
			let idx = 0;

			while (idx < len) {
				result.push({
					type: dataUpdate[idx].type,
					name: this.role === CONSTANT.ROLE.ADMIN ? this.$t(CONSTANT.LIST_SHIFT.MAP_TYPE_TEXT_DAY_OFF[dataUpdate[idx].type]) : this.$t(CONSTANT.LIST_SHIFT.TABLE_DATE_HOLIDAY),
					course_status: null,
					start_time: '09:00',
					end_time: '18:00',
					break_time: '0.00',
				});

				idx++;
			}

			return result;
		},

		generateListValueWork(dataUpdate) {
			const result = [];

			console.log('dataLen', dataUpdate);
			const len = dataUpdate.length;
			let idx = 0;

			while (idx < len) {
				if (dataUpdate[idx].type === 7) {
					result.push({
						type: 7,
						name: this.$t(CONSTANT.LIST_SHIFT.TEXT_HALF_DAY_OF),
					});
				} else if (dataUpdate[idx].type === CONSTANT.LIST_SHIFT.DATE_WAIT_BETWEEN_TASK) {
					result.push({
						type: dataUpdate[idx].type,
						name: this.$t(CONSTANT.LIST_SHIFT.TEXT_DATE_WAIT_BETWEEN_TASK),
						course_status: null,
						start_time: formatArray2Time(dataUpdate[idx].start_time),
						end_time: formatArray2Time(dataUpdate[idx].end_time),
						break_time: formatArray2Time(dataUpdate[idx].break_time),
					});
				} else if (dataUpdate[idx].type === CONSTANT.LIST_SHIFT.DATE_LEADER_CHIEF) {
					result.push({
						type: dataUpdate[idx].type,
						name: this.$t(CONSTANT.LIST_SHIFT.TEXT_DATE_LEADER_CHIEF),
						course_status: null,
						start_time: formatArray2Time(dataUpdate[idx].start_time),
						end_time: formatArray2Time(dataUpdate[idx].end_time),
						break_time: formatArray2Time(dataUpdate[idx].break_time),
					});
				} else if ((CONSTANT.LIST_SHIFT.LIST_VALUE_SPECIAL_DAY).includes(dataUpdate[idx].type)) {
					result.push({
						type: dataUpdate[idx].type,
						name: this.$t(CONSTANT.LIST_SHIFT.MAP_TYPE_TEXT_DAY_OFF[dataUpdate[idx].type]),
						// course_status: this.listCourse[COURSE].status,
						start_time: formatArray2Time(dataUpdate[idx].start_time),
						end_time: formatArray2Time(dataUpdate[idx].end_time),
						break_time: formatArray2Time(dataUpdate[idx].break_time),
					});
				} else {
					const COURSE = this.listCourse.findIndex((item) => item.value === dataUpdate[idx].type);
					console.log('check', COURSE);

					if (COURSE !== -1) {
						result.push({
							type: dataUpdate[idx].type,
							name: this.listCourse[COURSE].text,
							// course_status: this.listCourse[COURSE].status,
							start_time: formatArray2Time(dataUpdate[idx].start_time),
							end_time: formatArray2Time(dataUpdate[idx].end_time),
							break_time: formatArray2Time(dataUpdate[idx].break_time),
						});
					} else {
						result.push({
							type: null,
							name: null,
							course_status: null,
							start_time: null,
							end_time: null,
							break_time: null,
						});
					}
				}

				idx++;
			}

			return result;
		},

		handleReRenderTable() {
			this.reRenderTable += 1;
		},

		findIndexOfDriverCode(list = [], driverID = '') {
			const len = list.length;
			let idx = 0;

			while (idx < len) {
				if (list[idx].driver_id === driverID) {
					return idx;
				}

				idx++;
			}

			return -1;
		},

		async handleGetDataUpdate() {
			try {
				setLoading(true);
				const params = {};
				const YEAR = this.pickerYearMonth.year;
				const MONTH = this.pickerYearMonth.month;
				const YEAR_MONTH = `${YEAR}-${format2Digit(MONTH)}`;
				params.month_year = YEAR_MONTH;
				const URL = CONSTANT.URL_API.GET_DATA_UPDATE;
				const DATA = await getDataUpdate(URL, params);
				if (DATA.code === 200) {
					DATA.data.items.forEach((value) => {
						this.listUpdate.push(value);
					});
				}
				setLoading(false);
			} catch {
				setLoading(false);
			}
		},

		async onClickSave() {
			try {
				if (this.listUpdate.length > 0) {
					const DATA = this.handleInitDataUpdate(this.listUpdate);
					console.log('data', DATA);
					const { code } = await putShift(CONSTANT.URL_API.POST_UPDATE_CELL_SHIFT, DATA);

					if (code === 200) {
						Notification.success(this.$t('MESSAGE_APP.LIST_SHIFT_UPDATE_SUCCESS'));
					}

					this.listUpdate.length = 0;

					this.goToListShift();
				} else {
					Notification.success(this.$t('MESSAGE_APP.LIST_SHIFT_UPDATE_SUCCESS'));
					this.goToListShift();
				}
			} catch (error) {
				this.listUpdate.length = 0;
				this.goToListShift();
				console.log(error);
			}
		},

		handleInitDataUpdate(listUpdate) {
			const YEAR = this.pickerYearMonth.year;
			const MONTH = this.pickerYearMonth.month;
			const YEAR_MONTH = `${YEAR}-${format2Digit(MONTH)}`;
			if (listUpdate.length > 0) {
				return {
					month_year: YEAR_MONTH,
					items: this.listUpdate,
				};
			} else {
				return {
					month_year: null,
					items: [],
				};
			}
		},

		handleinitObjectUpdate(otherInfo, listSelected) {
			const INIT_UPDATE = {
				driver_id: Number(otherInfo.driver_id),
				listShift: [],
			};

			if (listSelected.length > 0) {
				listSelected.forEach((item) => {
					INIT_UPDATE.listShift.push({
						course_id: item.type,
						date: otherInfo.date,
						start_time: formatArray2Time(item.start_time),
						break_time: formatArray2Time(item.break_time),
						end_time: formatArray2Time(item.end_time),
					});
					// if ((CONSTANT.LIST_SHIFT.LIST_VALUE_SPECIAL_DAY).includes(item.type)) {
					// 	INIT_UPDATE.listShift.push({
					// 		course_id: item.type,
					// 		date: otherInfo.date,
					// 		start_time: formatArray2Time(item.start_time),
					// 		break_time: formatArray2Time(item.break_time),
					// 		end_time: formatArray2Time(item.end_time),
					// 	});
					// } else if ((CONSTANT.LIST_SHIFT.LIST_VALUE_DAY_OFF).includes(item.type)) {
					// 	INIT_UPDATE.listShift.push({
					// 		course_id: item.type,
					// 		date: otherInfo.date,
					// 		start_time: formatArray2Time(item.start_time),
					// 		break_time: formatArray2Time(item.break_time),
					// 		end_time: formatArray2Time(item.end_time),
					// 	});
					// } else {
					// 	INIT_UPDATE.listShift.push({
					// 		course_id: item.type,
					// 		date: otherInfo.date,
					// 		start_time: formatArray2Time(item.start_time),
					// 		break_time: formatArray2Time(item.break_time),
					// 		end_time: formatArray2Time(item.end_time),
					// 	});
					// }
				});
			}

			return INIT_UPDATE;
		},

		handleUpdateListUpdate(listUpdate, updateDayOff) {
			if (listUpdate.length > 0) {
				const IS_EXIT = this.handleCheckUpdateWithDataExit(listUpdate, updateDayOff);
				const DRIVER_CODE = updateDayOff.driver_id;
				const CHECK_ID = {
					findID: false,
					getIndex: null,
				};
				listUpdate.forEach((value, idx) => {
					if (value.driver_id === DRIVER_CODE) {
						CHECK_ID.findID = true;
						CHECK_ID.getIndex = idx;
					}
				});
				if (IS_EXIT.status) {
					listUpdate[CHECK_ID.getIndex].listShift.forEach((value) => {
						updateDayOff.listShift.forEach((item) => {
							if (value.course_id === item.course_id) {
								updateDayOff.listShift = updateDayOff.listShift.filter(data => data.course_id !== item.course_id);
							}
						});
					});
					const set = new Set([...listUpdate[CHECK_ID.getIndex].listShift, ...updateDayOff.listShift]);
					listUpdate[CHECK_ID.getIndex].listShift = Array.from(set);
					console.log('bbbbbbb', listUpdate);
				} else if (CHECK_ID.findID) {
					// updateDayOff.listShift.forEach((item) => {
					// 	listUpdate[CHECK_ID.getIndex].listShift.push(item);
					// });
					listUpdate[IS_EXIT.index] = updateDayOff;
				} else {
					listUpdate.push(updateDayOff);
				}
			} else {
				listUpdate.push(updateDayOff);
			}

			return listUpdate;
		},

		handleCheckUpdateWithDataExit(listUpdate, updateDayOff) {
			const IS_EXIT = {
				status: false,
				index: null,
			};

			const DATE_EDIT = updateDayOff.listShift.length > 0 ? updateDayOff.listShift[0].date : '';
			const DRIVER_CODE = updateDayOff.driver_id;

			// const len = listUpdate.length;
			// let idx = 0;

			// while (idx < len) {
			// 	listUpdate[idx].listShift.forEach((items) => {
			// 		if (items.date === DATE_EDIT && listUpdate[idx].driver_id === DRIVER_CODE) {
			// 			IS_EXIT.status = true;
			// 			IS_EXIT.index = idx;
			// 		}
			// 	});
			// 	// if (listUpdate[idx].date === DATE_EDIT && listUpdate[idx].driver_id === DRIVER_CODE) {
			// 	// 	IS_EXIT.status = true;
			// 	// 	IS_EXIT.index = idx;

			// 	// 	break;
			// 	// }

			// 	idx++;
			// }
			listUpdate.forEach((value, idx) => {
				value.listShift.forEach((items) => {
					if (items.date === DATE_EDIT && value.driver_id === DRIVER_CODE) {
						IS_EXIT.status = true;
						IS_EXIT.index = idx;
					}
				});
			});

			return IS_EXIT;
		},

		handleFilterListSelected(listUpdate, listFilter = [null]) {
			const FILTER_LIST_SELECTED = listUpdate.filter((item) => {
				return !listFilter.includes(item.type);
			});

			return FILTER_LIST_SELECTED;
		},

		setListUpdate(list) {
			this.$store.dispatch('listShift/setListUpdate', list);
		},

		onSelectedDayOff(status) {
			console.log('status', status);
			if (this.isCheckNullListSelected(this.listNodeEditSelected)) {
				this.setDisabledListEdit(false, false);
			} else {
				this.setDisabledListEdit(false, false);
				// if (status) {
				// 	this.setDisabledListEdit(false, true);
				// } else {
				// 	this.setDisabledListEdit(true, false);
				// }
			}
		},

		isCheckNullListSelected(listSelected) {
			const len = listSelected.length;
			let idx = 0;

			let total = 0;

			while (idx < len) {
				if (listSelected[idx].type === null) {
					total = total + 1;
				}

				idx++;
			}

			return total === len;
		},

		setDisabledListEdit(valueDayoff, valueCourse) {
			const lenDayOff = this.listNodeEdit.length;
			let idxDayOff = 0;

			while (idxDayOff < lenDayOff) {
				if (([null, ...CONSTANT.LIST_SHIFT.LIST_VALUE_DAY_OFF]).includes(this.listNodeEdit[idxDayOff].value)) {
					this.listNodeEdit[idxDayOff].disabled = valueDayoff;
				}

				idxDayOff++;
			}

			const lenCourse = this.listCourse.length;
			let idxCourse = 0;

			while (idxCourse < lenCourse) {
				if (valueCourse) {
					this.listCourse[idxCourse].disabled = valueCourse;
				} else {
					this.listCourse[idxCourse].disabled = (this.courseDisabled).includes(this.listCourse[idxCourse].value);
				}

				idxCourse++;
			}

			const lenNotDayOff = this.listNodeEdit.length;
			let idxNotDayOff = 0;

			while (idxNotDayOff < lenNotDayOff) {
				if (!CONSTANT.LIST_SHIFT.LIST_VALUE_DAY_OFF.includes(this.listNodeEdit[idxNotDayOff].value)) {
					this.listNodeEdit[idxNotDayOff].disabled = valueCourse;
				}

				idxNotDayOff++;
			}
		},

		onClickCancelDetail() {
			this.modalDetail = false;
		},

		onClickCancelEdit() {
			this.modalEdit = false;

			this.listNodeEditSelected.length = 0;
		},

		onChangeNode(data) {
			const value = data.value;
			console.log('change:', data);

			if (value) {
				const idxChange = data.index;
				const COURSE = this.listCourse.find((course) => course.value === value);

				if (COURSE) {
					if ((CONSTANT.LIST_SHIFT.LIST_VALUE_DAY_OFF).includes(value)) {
						this.listNodeEditSelected[idxChange].name = this.$t(CONSTANT.LIST_SHIFT.MAP_TYPE_TEXT_DAY_OFF[value]);
						this.listNodeEditSelected[idxChange].start_time = ['09', '00'];
						this.listNodeEditSelected[idxChange].end_time = ['18', '00'];
						this.listNodeEditSelected[idxChange].break_time = ['00', '00'];
						this.listNodeEditSelected[idxChange].course = {
							start_time: null,
							end_time: null,
							break_time: null,
						};
						this.listNodeEditSelected[idxChange].course_status = null;
					} else {
						this.listNodeEditSelected[idxChange].name = COURSE.course_name;
						this.listNodeEditSelected[idxChange].start_time = convertTextToSelectTime(convertTimeForDetail(COURSE.start_time));
						this.listNodeEditSelected[idxChange].end_time = convertTextToSelectTime(convertTimeForDetail(COURSE.end_time));
						this.listNodeEditSelected[idxChange].break_time = convertTextToSelectTime(convertTimeForDetail(COURSE.break_time));
						this.listNodeEditSelected[idxChange].course = {
							start_time: COURSE.start_time,
							end_time: COURSE.end_time,
							break_time: COURSE.break_time,
						};
						this.listNodeEditSelected[idxChange].course_status = COURSE.status;
					}
				} else {
					console.log('list not:', this.listNodeEditSelected);
					if ((CONSTANT.LIST_SHIFT.LIST_VALUE_DAY_OFF).includes(value)) {
						this.listNodeEditSelected[idxChange].name = this.$t(CONSTANT.LIST_SHIFT.MAP_TYPE_TEXT_DAY_OFF[value]);
						this.listNodeEditSelected[idxChange].start_time = ['09', '00'];
						this.listNodeEditSelected[idxChange].end_time = ['18', '00'];
						this.listNodeEditSelected[idxChange].break_time = ['00', '00'];
						this.listNodeEditSelected[idxChange].course = {
							start_time: null,
							end_time: null,
							break_time: null,
						};
						this.listNodeEditSelected[idxChange].course_status = null;
					} else {
						if (value === CONSTANT.LIST_SHIFT.DATE_LEADER_CHIEF) {
							this.listNodeEditSelected[idxChange].name = this.$t(CONSTANT.LIST_SHIFT.MAP_TYPE_TEXT_DAY_OFF[CONSTANT.LIST_SHIFT.DATE_LEADER_CHIEF]);
							this.listNodeEditSelected[idxChange].start_time = [null, null];
							this.listNodeEditSelected[idxChange].end_time = [null, null];
							this.listNodeEditSelected[idxChange].break_time = [null, null];
							this.listNodeEditSelected[idxChange].course = {
								start_time: null,
								end_time: null,
								break_time: null,
							};
							this.listNodeEditSelected[idxChange].course_status = null;
						} else if (value === CONSTANT.LIST_SHIFT.DATE_WAIT_BETWEEN_TASK) {
							this.listNodeEditSelected[idxChange].name = this.$t(CONSTANT.LIST_SHIFT.MAP_TYPE_TEXT_DAY_OFF[CONSTANT.LIST_SHIFT.DATE_WAIT_BETWEEN_TASK]);
							this.listNodeEditSelected[idxChange].start_time = [null, null];
							this.listNodeEditSelected[idxChange].end_time = [null, null];
							this.listNodeEditSelected[idxChange].break_time = [null, null];
							this.listNodeEditSelected[idxChange].course = {
								start_time: null,
								end_time: null,
								break_time: null,
							};
							this.listNodeEditSelected[idxChange].course_status = null;
						}
					}
				}
			}
		},
	},
};
</script>

<style lang="scss" scoped>
	@import "@/scss/variables";

	.list-shift {
		&__header {
			.zone-left {
				display: flex;
				height: 100%;
				line-height: 48px;

				.zone-title {
					justify-content: left;
					vertical-align: middle;

					.title-page {
						font-size: 25px;
						font-weight: bold;
					}
				}

				.zone-select-week-month {
					margin: 0 20px;

					.btn-select-week-month {
						background-color: $white;
						border-color: $main;
						color: $main;
						font-weight: 600;
					}

					.btn-select-week-month.active {
						background-color: $main;
						color: $white;
					}
				}
			}

			.zone-right {
				display: flex;
				justify-content: flex-end;

				.item-function {
					padding: 0 20px;

					cursor: pointer;

					.show-icon {
						i {
							font-size: 25px;
							color: $dusty-gray;

							display: flex;
							align-items: center;
							justify-content: center;

							margin-bottom: 5px;
						}
					}

					.show-text {
						text-align: center;
						font-weight: bold;
						color: $dusty-gray;
						font-size: 12px;
					}

					&:hover {
						.show-icon {
							i {
								color: $di-serria;

								display: flex;
								align-items: center;
								justify-content: center;

								margin-bottom: 5px;
							}
						}

						.show-text {
							text-align: center;
							font-weight: bold;
							color: $di-serria;
							font-size: 12px;
						}
					}
				}
			}
		}

		&__control {
			margin-bottom: 10px;

			.control-button-group {
				background-color: $wild-sand;
				color: $sirocco;

				font-size: 12px;
				min-width: 120px;

				span {
					font-weight: bold;
				}
			}

			.control-button-group.active {
				background-color: $main;
				color: $white;
			}

			.btn-control {
				line-height: 50px;

				.btn-return {
					color: $white;
					font-weight: 600;

					border-color: transparent;

					&:hover {
						opacity: 0.8;
					}
				}

				.btn-save {
					background-color: $main;
					color: $white;
					font-weight: 600;

					border-color: transparent;

					&:hover {
						opacity: 0.8;
					}
				}
			}
		}

		&__table {
			.zone-table {
				height: calc(100vh - 240px);
				overflow: auto;

				table {
					thead {
						tr {
							th {
                                position: sticky;
                                z-index: 9;

								div {
									display: flex;
									align-items: center;
									justify-content: center;
								}

								background-color: $main;
								color: $white;
								text-align: center;
                                vertical-align: middle;
								min-width: 100px;
								padding: 25px 0;

                                top: 0;
							}

                            th.fix-header {
                                position: sticky;
                                z-index: 10;
                                top: 0;
                                left: 0;
                            }

							th.th-show-date {
								padding: 5px 0;
							}

							th.total-shift {
								min-width: 150px;
							}

							th.th-employee-number {
                                position: sticky;
                                z-index: 10;
                                top: 0;
                                left: 0;

								min-width: 150px;
							}

							th.th-type-employee {
                                position: sticky;
                                top: 37px;
                                z-index: 10;
                                left: 150px;

                                min-width: 180px;

                                cursor: pointer;
                            }

							th.th-full-name {
                                position: sticky;
                                z-index: 10;
                                top: 0;
                                left: 330px;

                                min-width: 240px;

                                cursor: pointer;
							}
						}

                        tr:nth-child(2) {
                            position: sticky;
                            z-index: 10;
                            top: 50px;
                        }
					}

					tbody {
						tr {
							td {
								text-align: center;
								padding: 0;

								min-width: 150px;
							}

							td.td-employee-number,
							td.td-type-employee,
							td.td-full-name {
								background-color: $sub-main;

								font-weight: bold;

								vertical-align: middle;

                                padding: 10px;
							}

							td.td-type-employee {
                                position: sticky;
                                top: 0;
                                z-index: 8;
                                left: 150px;
                            }

							td.td-total-shift {
								min-width: 150px;
							}

                            td.td-employee-number {
                                position: sticky;
                                z-index: 9;
								left: 150px;
                                top: 0;
                                left: 0;
                            }

                            td.td-full-name {
                                position: sticky;
                                z-index: 9;
                                top: 0;
								left: 330px;
                            }
						}
					}
				}
			}
		}
	}

	.modal-detail-node {
		.item-node {
			text-align: center;
			span.type-node {
				font-size: 20px;
				font-weight: bold;
				text-decoration: underline;
			}

			.item-time {
				margin: 5px 0;
			}

			margin-bottom: 20px;
		}

		.modal-detail-node-control {
			text-align: center;

			.btn-change {
				min-width: 100px;
				background-color: $main;
				color: $white;
				font-weight: 600;

				border-color: transparent;

				&:hover {
					opacity: 0.8;
				}
			}
		}
	}

	.modal-edit-node {
		padding: 10px;

		.item-node {
			text-align: center;
			span.type-node {
				font-size: 20px;
				font-weight: bold;
				text-decoration: underline;
			}

			.item-time {
				margin: 5px 0;
			}

			margin-bottom: 20px;
		}

		.edit-node {
			text-align: center;

			span {
				font-size: 20px;
				font-weight: bold;
			}
		}

		.edit-control {
			margin-top: 30px;

			text-align: center;

			.btn-save {
				min-width: 100px;
				background-color: $main;
				color: $white;
				font-weight: 600;

				border-color: transparent;

				&:hover {
					opacity: 0.8;
				}
			}
		}
	}
</style>
