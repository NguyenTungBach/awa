<!-- eslint-disable vue/valid-v-for -->
<template>
    <b-col>
        <div class="list-shift">
            <div class="list-shift__header">
                <b-row>
                    <b-col>
                        <div class="zone-left">
                            <div class="zone-title">
                                <span class="title-page">
                                    {{ $t("LIST_SHIFT.TITLE_COURSE_BASE") }}
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
                                <template
                                    v-if="selectWeekMonth === CONSTANT.LIST_SHIFT.WEEK"
                                >
                                    <template v-for="(date, idx) in pickerWeek.listDate">
                                        <b-th
                                            :key="`date-${idx}`"
                                            class="th-show-date"
                                        >
                                            <div>
                                                {{ date.date }} ({{ getTextDay(date.text) }})
                                            </div>
                                        </b-th>
                                    </template>
                                </template>
                                <template
                                    v-if="selectWeekMonth === CONSTANT.LIST_SHIFT.MONTH"
                                >
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
                                </template>
                            </b-tr>
                            <b-tr>
                                <b-th class="th-employee-number">
                                    {{ $t("LIST_SHIFT.TABLE_COURSE_COURSE_ID") }}
                                </b-th>
                                <b-th class="th-flag">
                                    {{ $t('LIST_SHIFT.TABLE_COURSE_COURSE_GROUP') }}
                                </b-th>
                                <b-th class="th-full-name">
                                    {{ $t("LIST_SHIFT.TABLE_COURSE_COURSE_NAME") }}
                                </b-th>
                                <template v-if="selectWeekMonth === CONSTANT.LIST_SHIFT.WEEK">
                                    <template v-for="(date, idx) in pickerWeek.listDate">
                                        <b-th :key="`date-${idx + 1}`">
                                            <div v-if="listCalendar.length">
                                                {{ listCalendar[idx] || '' }}
                                            </div>
                                        </b-th>
                                    </template>
                                </template>
                                <template v-if="selectWeekMonth === CONSTANT.LIST_SHIFT.MONTH">
                                    <template v-for="date in pickerYearMonth.numberDate">
                                        <b-th :key="`date-${date}`">
                                            <span>
                                                {{ listCalendar[date - 1] || '' }}
                                            </span>
                                        </b-th>
                                    </template>
                                </template>
                            </b-tr>
                        </b-thead>
                        <b-tbody v-if="listTableCourse.length > 0">
                            <template
                                v-for="(course, idx) in listTableCourse"
                            >
                                <tr :key="`course-no-${idx + 1}`">
                                    <td class="td-employee-number">
                                        {{ course.course_code }}
                                    </td>
                                    <b-td class="td-flag">
                                        {{ course.group ? course.group : '-' }}
                                    </b-td>
                                    <td class="td-full-name text-center">
                                        {{ course.course_name }}
                                    </td>
                                    <template
                                        v-if="selectWeekMonth === CONSTANT.LIST_SHIFT.WEEK"
                                    >
                                        <template v-for="(date, idxDate) in pickerWeek.listDate">
                                            <NodeCourseBase
                                                :key-render="idxDate"
                                                :idx-component="idxDate + 1"
                                                :idx-course="idx"
                                                :item="course.shift_list[idxDate]"
                                                :course-code="course.course_code"
                                                :course-name="course.course_name"
                                                :is-edit="true"
                                                :start-date="course.start_date"
                                                :end-date="course.end_date"
                                                @clickNode="clickNode"
                                            />
                                        </template>
                                    </template>
                                    <template v-if="selectWeekMonth === CONSTANT.LIST_SHIFT.MONTH">
                                        <template v-for="(date, idxDate) in pickerYearMonth.numberDate">
                                            <NodeCourseBase
                                                :key-render="idxDate"
                                                :idx-component="idxDate + 1"
                                                :idx-course="idx"
                                                :item="course.shift_list[idxDate]"
                                                :course-code="course.course_code"
                                                :course-name="course.course_name"
                                                :is-edit="true"
                                                :start-date="course.start_date"
                                                :end-date="course.end_date"
                                                @clickNode="clickNode"
                                            />
                                        </template>
                                    </template>
                                </tr>
                            </template>
                        </b-tbody>
                    </b-table-simple>
                </div>
            </div>
        </div>

        <b-modal
            id="modal-edit"
            v-model="modalEdit"
            body-class="modal-edit-node"
            hide-footer
            :title="$t('LIST_SHIFT.TITLE_EDIT_COURSE_BASE')"
            no-close-on-esc
            no-close-on-backdrop
            static
            @close="handleCloseModal"
        >
            <div class="zone-select-driver">
                <b-form-group v-slot="{ ariaDescribedby }">
                    <b-form-radio-group
                        id="select-day-off"
                        v-model="selectEdit"
                        :options="listNodeEdit"
                        :aria-describedby="ariaDescribedby"
                        name="select-day-off"
                        stacked
                    />
                </b-form-group>
            </div>
            <div class="zone-save">
                <b-button
                    block
                    pill
                    class="btn-save"
                    :disabled="selectEdit === null"
                    @click="handleSaveModal()"
                >
                    {{ $t('APP.BUTTON_SAVE') }}
                </b-button>
            </div>
        </b-modal>
    </b-col>
</template>

<script>
import CONSTANT from '@/const';
import LineGray from '@/components/LineGray';
import { setLoading } from '@/utils/handleLoading';
import { format2Digit } from '@/utils/generateTime';
import { getCalendar } from '@/api/modules/calendar';
// import NodeListShift from '@/components/NodeListShift';
// import EditNodeCourseBase from '@/components/EditNodeCourseBase';
import NodeCourseBase from '@/components/NodeCourseBase';
import { getTextDayInWeek, getTextDay } from '@/utils/convertTime';
import { getListShift, postUpdateCourseBase } from '@/api/modules/shiftManagement';
// import { convertValueToText } from '@/utils/handleSelect';
// import { convertValueWhenNull } from '@/utils/handleListShift';
// import { convertBreakTimeNumberToTime, convertTextToSelectTime, getYearMonthFromDate, convertTimeCourse, formatArray2Time } from '@/utils/convertTime';
// import { getList } from '@/api/modules/courseManagement';
import Notification from '@/toast/notification';
// import { validateEditShift } from '../helper/validateEditShift';

export default {
	name: 'ListShift',
	components: {
		LineGray,
		// NodeListShift,
		// EditNodeCourseBase,
		NodeCourseBase,
	},

	data() {
		return {
			CONSTANT,
			selectEdit: '',
			selectWeekMonth: this.$store.getters.weekOrMonthListShift || CONSTANT.LIST_SHIFT.WEEK,

			selectTable: this.$store.getters.tableListShift || CONSTANT.LIST_SHIFT.SHIFT_TABLE,

			modalDetail: false,
			modalEdit: false,

			listNodeEdit: [],
			nodeEdit: [],
			listNodeEditSelected: [],
			listCalendar: [],

			listShift: [],

			nodeEmit: {
				index: '',
				date: '',
				driverCode: '',
				driverName: '',
				dataNode: '',
			},

			listCourse: [],

			listUpdate: [],

			listTableCourse: [],

			reRenderTable: 0,
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
		// this.createdEmit();
	},

	// destroyed() {
	// 	this.destroyEmit();
	// },

	methods: {
		handleCloseModal() {
			this.selectEdit = '';
			this.nodeEdit = [];
			// this.modalEdit = false;
			// this.handleModal = {
			// 	driverCode: '',
			// 	date: '',
			// };
		},

		handleSaveModal() {
			// console.log('call save model');
			const INDEX_COURSE = this.findCourseInListCourseTable(this.nodeEdit.course_code, this.listTableCourse);
			const IDX_ITEM = this.nodeEdit.idx_component;
			// const LIST_DRIVER_CAN_RUN = this.nodeEdit.item.listDriverCanRun;
			const LIST_DRIVER_CAN_RUN = this.listNodeEdit;
			const LIST_DRIVER = [];
			let i = 0;

			for (i = 0; i < LIST_DRIVER_CAN_RUN.length; i++) {
				LIST_DRIVER.push(LIST_DRIVER_CAN_RUN[i].value);
			}

			const DRIVER_CODE = this.selectEdit;
			let DRIVER_NAME = '';

			// console.log('INDEX_COURSE: ', INDEX_COURSE, ' IDX_ITEM: ', IDX_ITEM, ' LIST_DRIVER: ', LIST_DRIVER, ' DRIVER: ', DRIVER_CODE, ' list can run: ', LIST_DRIVER_CAN_RUN);

			if (INDEX_COURSE !== -1 && IDX_ITEM >= 1 && LIST_DRIVER.includes(DRIVER_CODE)) {
				if (this.listTableCourse[INDEX_COURSE].shift_list[IDX_ITEM].driver !== DRIVER_CODE) {
					// console.log('driver : ', this.listTableCourse[INDEX_COURSE].shift_list[IDX_ITEM].driver);
					i = 0;
					// console.log('i: ', i);
					for (i = 0; i < LIST_DRIVER_CAN_RUN.length; i++) {
						if (LIST_DRIVER_CAN_RUN[i].value === DRIVER_CODE) {
							DRIVER_NAME = LIST_DRIVER_CAN_RUN[i].text;
						}
					}
					// console.log('driver NAME 1 : ', DRIVER_NAME);
					this.listTableCourse = this.handleOverwriteData(this.listTableCourse, INDEX_COURSE, IDX_ITEM, DRIVER_NAME);
					const NEW_DRIVER_OF_COURSE = this.handleInitObjectUpdate(this.nodeEdit.course_code, this.nodeEdit.item.date, DRIVER_CODE);
					this.handleUpdateListUpdate(NEW_DRIVER_OF_COURSE, this.listUpdate);
				}
			}

			this.handleCloseModal();
			this.modalEdit = false;
		},

		handleOverwriteData(listCourseTable, idxCourse, idxItem, driverName) {
			if (listCourseTable[idxCourse].shift_list[idxItem - 1].driver !== driverName) {
				listCourseTable[idxCourse].shift_list[idxItem - 1].driver = driverName;
			}

			this.handleReRenderTable();

			return listCourseTable;
		},

		handleInitObjectUpdate(courseCode, date, driverCode) {
			let NEW_DRIVER_OF_COURSE = [];
			if (driverCode !== '-1') {
				NEW_DRIVER_OF_COURSE = {
					course_code: courseCode,
					day: date,
					driver: driverCode,
				};
			} else {
				NEW_DRIVER_OF_COURSE = {
					course_code: courseCode,
					day: date,
					driver: '',
				};
			}

			return NEW_DRIVER_OF_COURSE;
		},

		handleUpdateListUpdate(newDriverOfCourse, listUpdate) {
			const len = listUpdate.length;
			let idx = 0;

			let isDuplicate = {
				status: false,
				idx: null,
			};

			while (idx < len) {
				if (listUpdate[idx].day === newDriverOfCourse.day && listUpdate[idx].course_code === newDriverOfCourse.course_code) {
					isDuplicate = {
						status: true,
						idx,
					};
				}

				idx++;
			}

			if (isDuplicate.status) {
				listUpdate[isDuplicate.idx].driver = newDriverOfCourse.driver;
			} else {
				listUpdate.push(newDriverOfCourse);
			}
			console.log('call handleUpdateListUpdate: ', listUpdate);
		},

		findCourseInListCourseTable(courseCode, listCourseTable) {
			const len = listCourseTable.length;
			let idx = 0;

			while (idx < len) {
				if (listCourseTable[idx].course_code === courseCode) {
					return idx;
				}

				idx++;
			}

			return -1;
		},

		async onClickSave() {
			try {
				if (this.listUpdate.length > 0) {
					const DATA = {
						date: `${this.pickerYearMonth.year}-${this.pickerYearMonth.month < 10 ? `0${this.pickerYearMonth.month}` : `${this.pickerYearMonth.month}`}`,
						items: this.listUpdate,
					};

					const { code } = await postUpdateCourseBase(CONSTANT.URL_API.POST_UPDATE_COURSE_BASE, DATA);

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

		async initData() {
			setLoading(true);

			await this.handleGetListCalendar();
			await this.handleGetTableCourse();

			setLoading(false);
		},

		clickNode(data) {
			this.modalEdit = true;
			const list = data.item.listDriverCanRun;
			// code === -1 if driver === null
			const listData = [
				{
					value: '-1',
					text: '-',
				},
			];

			const updatedData = list.map((v) => ({ value: v.code, text: v.name }));

			let i = 0;
			for (i = 0; i < updatedData.length; i++) {
				listData.push(updatedData[i]);
			}
			// const listData = data.listDriverCanRun;
			this.listNodeEdit = listData;
			this.nodeEdit = data;
			console.log('node edit: ', this.nodeEdit);
		},

		async handleGetListCalendar() {
			try {
				this.listCalendar = [];

				let START_DATE = '';
				let END_DATE = '';

				if (this.selectWeekMonth === CONSTANT.LIST_SHIFT.WEEK) {
					START_DATE = this.pickerWeek.listDate[0].text;
					END_DATE =
                            this.pickerWeek.listDate[this.pickerWeek.listDate.length - 1].text;
				}

				if (this.selectWeekMonth === CONSTANT.LIST_SHIFT.MONTH) {
					START_DATE = `${this.pickerYearMonth.year}-${format2Digit(
						this.pickerYearMonth.month
					)}-01`;
					END_DATE = `${this.pickerYearMonth.year}-${format2Digit(
						this.pickerYearMonth.month
					)}-${this.pickerYearMonth.numberDate}`;
				}

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

		async handleGetTableCourse() {
			// console.log('get list course table');
			try {
				this.listTableCourse.length = 0;

				const PARAMS = {
					display: 1,
				};

				if (this.selectWeekMonth === CONSTANT.LIST_SHIFT.WEEK) {
					const START = this.pickerWeek.start;
					const END = this.pickerWeek.end;

					PARAMS.start_date = `${START.year}-${format2Digit(START.month)}-${format2Digit(START.date)}`;
					PARAMS.end_date = `${END.year}-${format2Digit(END.month)}-${format2Digit(END.date)}`;
					PARAMS.type = 'week';

					const YEAR = this.pickerYearMonth.year;
					const MONTH = this.pickerYearMonth.month;

					const YEAR_MONTH = `${YEAR}-${format2Digit(MONTH)}`;

					PARAMS.date = YEAR_MONTH;
				}

				if (this.selectWeekMonth === CONSTANT.LIST_SHIFT.MONTH) {
					const YEAR = this.pickerYearMonth.year;
					const MONTH = this.pickerYearMonth.month;

					const START = `${YEAR}-${format2Digit(MONTH)}-01`;
					const END = `${YEAR}-${format2Digit(MONTH)}-${format2Digit(this.pickerYearMonth.numberDate)}`;

					PARAMS.start_date = START;
					PARAMS.end_date = END;
					PARAMS.type = 'month';

					const YEAR_MONTH = `${YEAR}-${format2Digit(MONTH)}`;

					PARAMS.date = YEAR_MONTH;
				}

				const { code, data } = await getListShift(CONSTANT.URL_API.GET_LIST_SHIFT_TABLE, PARAMS);

				console.log(data);

				if (code === 200) {
					this.listTableCourse = data;
				} else {
					this.listTableCourse = [];
				}
			} catch (error) {
				console.log(error);
			}
		},

		getTextDayInWeek,
		getTextDay,
		goToListShift() {
			this.$router.push({ name: 'ListShift' });
		},

		handleReRenderTable() {
			console.log('re render table');
			this.reRenderTable += 1;
		},

		setListUpdate(list) {
			this.$store.dispatch('listShift/setListUpdate', list);
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
			height: calc(100vh - 235px);
			overflow-y: auto;

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

								padding: 5px 0;

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

							th.th-employee-number {
                                position: sticky;
                                z-index: 10;
                                top: 0;
                                left: 0;

                                width: 150px;
							}

                            th.th-flag {
                                position: sticky;
                                z-index: 10;
                                top: 0;
                                left: 200px;

								width: 180px;
                            }

							th.th-full-name {
                                position: sticky;
                                z-index: 10;
                                top: 0;
                                left: 400px;

								width: 240px;
							}
						}

                        tr:nth-child(2) {
                            position: sticky;
                            z-index: 10;
                            top: 37px;
                        }
					}

					tbody {
						tr {
							td {
								text-align: center;
								padding: 0;

								min-width: 150px;
                                min-height: 80px;
							}

							td.td-employee-number,
                            td.td-flag,
							td.td-full-name {
								background-color: $sub-main;

								font-weight: bold;

								vertical-align: middle;

                                padding: 5px;

                                min-width: 200px;
							}

                            td.td-employee-number {
                                position: sticky;
                                z-index: 9;
                                top: 0;
                                left: 0;
                            }

                            td.td-flag {
                                position: sticky;
                                z-index: 9;
                                top: 0;
                                left: 200px;

                                width: 200px;
                            }

                            td.td-full-name {
                                position: sticky;
                                z-index: 9;
                                top: 0;
                                left: 400px;
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

	// .modal-edit-node {
	// 	padding: 10px;

	// 	.edit-node {
	// 		text-align: center;

	// 		span {
	// 			font-size: 20px;
	// 			font-weight: bold;
	// 		}
	// 	}

	// 	.edit-control {
	// 		margin-top: 30px;

	// 		text-align: center;

	// 		.btn-save {
	// 			min-width: 100px;
	// 			background-color: $main;
	// 			color: $white;
	// 			font-weight: 600;

	// 			border-color: transparent;

	// 			&:hover {
	// 				opacity: 0.8;
	// 			}
	// 		}
	// 	}
	// }

	.modal-edit-node {
		.zone-select-driver {
			overflow-y: scroll;
			max-height: 50vh;
		}
        .zone-save {
            margin-top: 20px;

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

</style>
