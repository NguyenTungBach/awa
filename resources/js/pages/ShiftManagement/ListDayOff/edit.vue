<template>
    <b-col>
        <div class="page-day-off">
            <div class="page-day-off__header">
                <b-row>
                    <b-col>
                        <div class="zone-title">
                            <span class="title-page">
                                {{ $t('DAY_OFF.TITLE_LIST_DAY_OFF') }}
                            </span>
                        </div>
                    </b-col>
                </b-row>
            </div>

            <LineGray />

            <div class="page-day-off__body">
                <div class="zone-control">
                    <b-row>
                        <b-col>
                            <div class="text-right">
                                <b-button
                                    pill
                                    class="btn-return"
                                    @click="goToDayOffIndex()"
                                >
                                    <span>{{ $t('APP.BUTTON_RETURN') }}</span>
                                </b-button>

                                <b-button
                                    pill
                                    class="btn-save"
                                    @click="onClickSave()"
                                >
                                    <span>{{ $t('APP.BUTTON_SAVE') }}</span>
                                </b-button>
                            </div>
                        </b-col>
                    </b-row>
                </div>

                <div class="zone-table">
                    <b-table-simple
                        :key="reRender"
                        bordered
                        no-border-collapse
                    >
                        <b-thead>
                            <b-tr>
                                <b-th
                                    :colspan="3"
                                    class="fix-header"
                                />
                                <template v-for="date in numberDate">
                                    <b-th
                                        :key="`date-${date}`"
                                        class="th-date"
                                    >
                                        <div>
                                            {{ date }} ({{ getTextDay(`${pickerYearMonth.year}/${pickerYearMonth.month}/${date}`) }})
                                        </div>
                                    </b-th>
                                </template>
                            </b-tr>
                            <b-tr>
                                <b-th
                                    class="th-number-employee"
                                    @click="onSortTable('driver_code')"
                                >
                                    {{ $t('DAY_OFF.TABLE_DATE_EMPLOYEE_NUMBER') }}
                                    <i
                                        v-if="sortTable.sortBy === 'driver_code' && sortTable.sortType === true"
                                        class="fad fa-sort-up icon-sort"
                                    />
                                    <i
                                        v-else-if="sortTable.sortBy === 'driver_code' && sortTable.sortType === false"
                                        class="fad fa-sort-down icon-sort"
                                    />
                                    <i
                                        v-else
                                        class="fa-solid fa-sort icon-sort-default"
                                    />
                                </b-th>
                                <b-th
                                    class="th-type-employee"
                                    @click="onSortTable('flag')"
                                >
                                    {{ $t('DAY_OFF.TABLE_FLAG') }}
                                    <i
                                        v-if="sortTable.sortBy === 'flag' && sortTable.sortType === true"
                                        class="fad fa-sort-up icon-sort"
                                    />
                                    <i
                                        v-else-if="sortTable.sortBy === 'flag' && sortTable.sortType === false"
                                        class="fad fa-sort-down icon-sort"
                                    />
                                    <i
                                        v-else
                                        class="fa-solid fa-sort icon-sort-default"
                                    />
                                </b-th>

                                <b-th class="th-employee-name">
                                    {{ $t('DAY_OFF.TABLE_FULL_NAME') }}
                                </b-th>
                                <template v-for="date in numberDate">
                                    <b-th :key="`date-${date}`">
                                        <span>{{ listCalendar[date - 1] || '' }}</span>
                                    </b-th>
                                </template>
                            </b-tr>
                        </b-thead>

                        <b-tbody>
                            <template v-for="(driver, idxDriver) in listDayOff">
                                <b-tr :key="`row-driver-${idxDriver + 1}`">
                                    <b-td class="td-number-employee">
                                        {{ driver.driver_code }}
                                    </b-td>

                                    <b-td class="td-type-employee">
                                        {{ $t(convertValueToText(optionsTypeDriver, driver.flag)) }}
                                    </b-td>

                                    <b-td class="td-employee-name text-center">
                                        {{ driver.driver_name }}
                                    </b-td>

                                    <template v-for="col in numberDate">
                                        <NodeDayOff
                                            :key="`node-${col}`"
                                            :is-edit="true"
                                            :id-driver="driver.id"
                                            :driver-code="driver.driver_code"
                                            :date-edit="col"
                                            :item="driver.day_off[col - 1]"
                                            :list-course="listAllCourse"
                                            @clickNode="handleClickNode"
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
            id="modal-edit"
            v-model="showModal"
            body-class="modal-edit-node"
            hide-footer
            :title="$t('DAY_OFF.MODAL_CHANGE_STATUS_DATE')"
            no-close-on-esc
            no-close-on-backdrop
            static
            @close="handleCloseModal"
        >
            <div class="zone-select-day-off">
                <b-form-select v-model="selectWorkOrDayoff" :options="optionsWorkOrDayoff">
                    <template #first>
                        <b-form-select-option :value="null">
                            {{ $t('APP.PLEASE_SELECT') }}
                        </b-form-select-option>
                    </template>
                </b-form-select>

                <div v-if="selectWorkOrDayoff === 'dayoff'" class="mt-2">
                    <b-form-radio-group
                        id="select-day-off"
                        v-model="selectedDayOff"
                        :options="listTypeDayOff"
                        name="select-day-off"
                        stacked
                    />
                </div>

                <div v-if="selectWorkOrDayoff === 'work'" class="mt-2">
                    <div class="display-list-course">
                        <b-form-checkbox-group
                            id="select-course"
                            v-model="selectedCourse"
                            :options="listCourse"
                            name="select-course"
                            stacked
                            :text-field="'course_name'"
                            :value-field="'course_code'"
                        />
                    </div>
                </div>
            </div>
            <div class="zone-save">
                <b-button
                    block
                    pill
                    class="btn-save"
                    :disabled="selectedDayOff === null"
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
import TOAST_DAY_OFF from '@/toast/modules/dayOff';
import LineGray from '@/components/LineGray';
import NodeDayOff from '@/components/NodeDayOff';
import { setLoading } from '@/utils/handleLoading';
import { format2Digit } from '@/utils/generateTime';
import { getCalendar } from '@/api/modules/calendar';
import { convertValueToText } from '@/utils/handleSelect';
import { getNumberDate, getTextDay } from '@/utils/convertTime';
import { getListDayOff, postListDayOff } from '@/api/modules/dayoff';
import { getListDriverCourse } from '@/api/modules/driverCourse';
import { getList } from '@/api/modules/courseManagement';
import { cleanObject } from '@/utils/handleObject';

export default {
	name: 'ListDayOffEdit',
	components: {
		LineGray,
		NodeDayOff,
	},

	data() {
		return {
			getTextDay,

			CONSTANT,

			numberDate: 0,

			showModal: false,

			selectWorkOrDayoff: null,
			optionsWorkOrDayoff: [
				{
					value: 'dayoff',
					text: this.$t('DAY_OFF.SELECT_TYPE_HOLIDAY'),
				},
				{
					value: 'work',
					text: this.$t('DAY_OFF.SELECT_TYPE_WORK'),
				},
				{
					value: 'default',
					text: '-',
				},
			],

			selectedDayOff: CONSTANT.LIST_DAY_OFF.TYPE_DAY_OFF_DEFAULT,
			listTypeDayOff: [
				{
					value: CONSTANT.LIST_DAY_OFF.TYPE_DAY_OFF_FIXED_DAY_OFF,
					text: this.$t(CONSTANT.LIST_DAY_OFF.TEXT_DAY_OFF_FIXED_DAY_OFF),
				},
				{
					value: CONSTANT.LIST_DAY_OFF.TYPE_DAY_OFF_DAY_OFF_REQUEST,
					text: this.$t(CONSTANT.LIST_DAY_OFF.TEXT_DAY_OFF_DAY_OFF_REQUEST),
				},
				{
					value: CONSTANT.LIST_DAY_OFF.TYPE_DAY_OFF_PAID,
					text: this.$t(CONSTANT.LIST_DAY_OFF.TEXT_DAY_OFF_PAID),
				},
			],

			selectedCourse: [],
			listCourse: [],
			listAllCourse: [],

			sortTable: {
				sortBy: '',
				sortType: null,
			},

			listCalendar: [],

			listDayOff: [],
			reRender: 0,

			handleModal: {
				idDriverHandle: -1,
				driverCode: '',
				date: '',
			},

			listUpdate: [],
		};
	},

	computed: {
		pickerYearMonth() {
			return this.$store.getters.pickerYearMonth;
		},

		optionsTypeDriver() {
			return CONSTANT.LIST_DRIVER.LIST_FLAG;
		},
	},

	watch: {
		async pickerYearMonth() {
			setLoading(true);

			this.numberDate = getNumberDate(this.pickerYearMonth);
			await this.handleGetListCalendar();
			await this.handleGetListDayOff();

			setLoading(false);
		},

		sortTable: {
			handler: async function() {
				setLoading(true);
				await this.handleGetListDayOff();
				setLoading(false);
			},

			deep: true,
		},

		listUpdate: {
			handler: function() {
				this.$store.dispatch('listDayoff/setListUpdate', this.listUpdate);
			},

			deep: true,
		},
	},

	created() {
		this.initData();
	},

	methods: {
		convertValueToText,

		async initData() {
			this.numberDate = getNumberDate(this.pickerYearMonth);

			setLoading(true);

			await this.handleGetAllCourse();
			await this.handleGetListCalendar();
			await this.handleGetListDayOff();

			setLoading(false);
		},

		async handleGetAllCourse() {
			try {
				const { code, data } = await getList(CONSTANT.URL_API.GET_LIST_COURSE);

				if (code === 200) {
					this.listAllCourse = data;
				}
			} catch (err) {
				this.listAllCourse = [];
				console.log(err);
			}
		},

		async handleGetListCourse(idDriver) {
			try {
				const { code, data } = await getListDriverCourse(`${CONSTANT.URL_API.GET_DRIVER_COURSE}/${idDriver}`);

				if (code === 200) {
					this.listCourse = data;
				} else {
					this.listCourse = [];
				}
			} catch (err) {
				this.listCourse = [];
				console.log(err);
			}
		},

		async handleGetListCalendar() {
			try {
				this.listCalendar = [];

				const START_DATE = `${this.pickerYearMonth.year}-${format2Digit(this.pickerYearMonth.month)}-01`;
				const END_DATE = `${this.pickerYearMonth.year}-${format2Digit(this.pickerYearMonth.month)}-${this.pickerYearMonth.numberDate}`;

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

		async handleGetListDayOff() {
			try {
				this.listDayOff.length = 0;

				let PARAMS = {
					field: this.sortTable.sortBy,
					sortby: this.sortTable.sortType,
				};

				PARAMS = cleanObject(PARAMS);

				if (PARAMS.field) {
					PARAMS.sortby = PARAMS.sortby ? 'desc' : 'asc';
				}

				const YEAR = this.pickerYearMonth.year || null;
				const MONTH = this.pickerYearMonth.month || null;

				const YEAR_MONTH = `${YEAR}-${MONTH < 10 ? `0${MONTH}` : `${MONTH}`}`;

				PARAMS.date = YEAR_MONTH || null;

				const { code, data } = await getListDayOff(CONSTANT.URL_API.GET_LIST_DAY_OFF, PARAMS);

				if (code === 200) {
					this.listDayOff = data;
					this.reloadTable();
				}
			} catch (error) {
				console.log(error);
			}
		},

		goToDayOffIndex() {
			this.$router.push({
				name: 'ListDayOff',
			});
		},

		handleCloseModal() {
			this.selectWorkOrDayoff = null;
			this.selectedDayOff = '';
			this.selectedCourse = [];
			this.handleModal = {
				idDriver: -1,
				driverCode: '',
				date: '',
			};
		},

		splitCoure(course) {
			if (course) {
				return course.split(',');
			}

			return [];
		},

		async handleClickNode(data) {
			if (data.item.type === CONSTANT.LIST_DAY_OFF.TYPE_DAY_OFF_DEFAULT) {
				if (data.item.has_codes) {
					this.selectWorkOrDayoff = 'work';
					this.selectedCourse = data.item.has_codes ? this.splitCoure(data.item.has_codes) : [];
				} else {
					this.selectWorkOrDayoff = 'default';
				}
			} else {
				this.selectWorkOrDayoff = 'dayoff';
				this.selectedDayOff = data.item.type || null;
			}

			this.handleModal = {
				idDriver: data.idDriver,
				driverCode: data.driverCode,
				date: data.date,
			};

			await this.handleGetListCourse(data.idDriver);

			this.showModal = true;
		},

		handleSaveModal() {
			if (this.selectWorkOrDayoff !== null) {
				const INDEX_DRIVER = this.findDriverInListDayoff(this.handleModal.driverCode, this.listDayOff);
				const DATE = this.handleModal.date;
				let STATUS = null;

				if (this.selectWorkOrDayoff === 'work') {
					STATUS = this.selectedCourse.join(',');

				}

				if (this.selectWorkOrDayoff === 'dayoff') {
					STATUS = this.selectedDayOff;
				}

				if (this.selectWorkOrDayoff === 'default') {
					STATUS = '';
				}

				if (INDEX_DRIVER !== -1 && DATE >= 1) {
					this.listDayOff = this.handleOverwriteData(this.listDayOff, INDEX_DRIVER, DATE, STATUS);
					const NEW_DAY_OFF = this.handleInitObjectUpdate(this.handleModal.driverCode, DATE, STATUS);
					this.handleUpdateListUpdate(NEW_DAY_OFF, this.listUpdate);
				}

				this.handleCloseModal();
				this.showModal = false;
			} else {
				TOAST_DAY_OFF.validate('MESSAGE_APP.DAY_OFF_VALIDATE_SELECT_TYPE_DAY');
			}
		},

		handleOverwriteData(listDayoff, idxDriver, date, status) {
			console.log('selectww', this.selectWorkOrDayoff)
			if (this.selectWorkOrDayoff === 'work') {
				listDayoff[idxDriver].day_off[date - 1].type = CONSTANT.LIST_DAY_OFF.TYPE_DAY_OFF_DEFAULT;

			}

			if (this.selectWorkOrDayoff === 'dayoff') {
				listDayoff[idxDriver].day_off[date - 1].type = status;
				console.log('status',status);
			}

			if (this.selectWorkOrDayoff === 'default') {
				listDayoff[idxDriver].day_off[date - 1].type = CONSTANT.LIST_DAY_OFF.TYPE_DAY_OFF_DEFAULT;
				listDayoff[idxDriver].day_off[date - 1].has_codes = '';
				console.log('listDayoff[idxDriver].day_off[date - 1].type',listDayoff[idxDriver].day_off[date - 1].has_codes);
			}

			if (this.selectWorkOrDayoff === 'work') {
				if (status) {
					listDayoff[idxDriver].day_off[date - 1].has_codes = status;
				} else {
					listDayoff[idxDriver].day_off[date - 1].has_codes = '';
				}
			}

			if (this.selectWorkOrDayoff === 'dayoff') {
				listDayoff[idxDriver].day_off[date - 1].has_codes = '';
			}

			this.reloadTable();

			return listDayoff;
		},

		handleInitObjectUpdate(driverCode, date, status) {
			let has_codes = '';

			if (this.selectWorkOrDayoff === 'work') {
				has_codes = this.selectedCourse.join(',');
			}

			if (this.selectWorkOrDayoff === 'dayoff') {
				has_codes = '';
			}

			if (this.selectWorkOrDayoff === 'default') {
				has_codes = '';
			}

			const NEW_DAY_OFF = {
				driver_code: driverCode,
				date_off: this.handleInitYMDbyDate(date),
				has_codes,
			};

			if (this.selectWorkOrDayoff === 'work') {
				NEW_DAY_OFF.type = CONSTANT.LIST_DAY_OFF.TYPE_DAY_OFF_DEFAULT;
			}

			if (this.selectWorkOrDayoff === 'dayoff') {
				NEW_DAY_OFF.type = status;
			}

			if (this.selectWorkOrDayoff === 'default') {
				NEW_DAY_OFF.type = CONSTANT.LIST_DAY_OFF.TYPE_DAY_OFF_DEFAULT;
			}

			return NEW_DAY_OFF;
		},

		handleUpdateListUpdate(newDayOff, listUpdate) {
			const len = listUpdate.length;
			let idx = 0;

			let isDuplicate = {
				status: false,
				idx: null,
			};

			while (idx < len) {
				if (listUpdate[idx].date_off === newDayOff.date_off && listUpdate[idx].driver_code === newDayOff.driver_code) {
					isDuplicate = {
						status: true,
						idx,
					};
				}

				idx++;
			}

			if (isDuplicate.status) {
				listUpdate[isDuplicate.idx].type = newDayOff.type;
				listUpdate[isDuplicate.idx].has_codes = newDayOff.has_codes;
			} else {
				listUpdate.push(newDayOff);
			}
		},

		handleInitYMDbyDate(date) {
			return `${this.pickerYearMonth.year}-${this.pickerYearMonth.month < 10 ? `0${this.pickerYearMonth.month}` : `${this.pickerYearMonth.month}`}-${date < 10 ? `0${date}` : `${date}`}`;
		},

		reloadTable() {
			this.reRender++;
		},

		findDriverInListDayoff(driverCode, listDayoff) {
			const len = listDayoff.length;
			let idx = 0;

			while (idx < len) {
				if (listDayoff[idx].driver_code === driverCode) {
					return idx;
				}

				idx++;
			}

			return -1;
		},

		onSortTable(col) {
			switch (col) {
				case 'driver_code':
					if (this.sortTable.sortBy === 'driver_code') {
						if (this.sortTable.sortType) {
							this.sortTable.sortType = !this.sortTable.sortType;
						} else {
							this.sortTable.sortType = true;
						}
					} else {
						this.sortTable.sortBy = 'driver_code';
						this.sortTable.sortType = true;
					}

					break;

				case 'flag':
					if (this.sortTable.sortBy === 'flag') {
						if (this.sortTable.sortType) {
							this.sortTable.sortType = !this.sortTable.sortType;
						} else {
							this.sortTable.sortType = true;
						}
					} else {
						this.sortTable.sortBy = 'flag';
						this.sortTable.sortType = true;
					}

					break;

				default:
					console.log('Handle sort table faild');

					break;
			}
		},

		async onClickSave() {
			try {
				setLoading(true);

				if (this.listUpdate.length > 0) {
					const BODY = {
						date: `${this.pickerYearMonth.year}-${this.pickerYearMonth.month < 10 ? `0${this.pickerYearMonth.month}` : `${this.pickerYearMonth.month}`}`,
						day_off: this.listUpdate,
					};

					const { code } = await postListDayOff(CONSTANT.URL_API.POST_DAY_OFF, BODY);

					if (code === 200) {
						this.listDayOff.length = 0;
						this.listUpdate.length = 0;

						this.$router.push({ name: 'ListDayOff' });

						TOAST_DAY_OFF.update();
					}
				} else {
					this.$router.push({ name: 'ListDayOff' });

					TOAST_DAY_OFF.update();
				}

				setLoading(false);
			} catch (error) {
				console.log(error);
				setLoading(false);
			}
		},
	},
};
</script>

<style lang="scss" scoped>
    @import '@/scss/variables';

    .page-day-off {
        &__header {
            .zone-title {
                .title-page {
                    font-size: 25px;
                }
            }
        }

        &__body {
            .zone-control {
                margin-bottom: 10px;

                .btn-save {
                    background-color: $main;
                    color: $white;
                    font-weight: 600;
                }

                .btn-return,
                .btn-save {
                    border-color: transparent;

                    &:hover {
                        opacity: 0.8;
                    }
                }
            }

            .zone-table {
                height: calc(100vh - 210px);
                overflow-y: auto;

                table {
                    thead {
                        tr {
                            th {
                                position: sticky;
                                top: 0;
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
                            }

                            th.fix-header {
                                position: sticky;
                                top: 0;
                                z-index: 10;
                                left: 0;
                            }

                            th.th-number-employee {
                                position: sticky;
                                top: 37px;
                                z-index: 10;
                                left: 0;

                                min-width: 130px;
                                max-width: 130px;

                                cursor: pointer;
                            }

                            th.th-type-employee {
                                position: sticky;
                                top: 37px;
                                z-index: 10;
                                left: 130px;

                                min-width: 180px;
                                max-width: 180px;

                                cursor: pointer;
                            }

                            th.th-employee-name {
                                position: sticky;
                                top: 37px;
                                z-index: 10;
                                left: 310px;

                                min-width: 230px;
                                max-width: 230px;
                            }

                        }

                        tr:nth-child(2) {
                            position: sticky;
                            top: 37px;
                            z-index: 9;
                        }
                    }

                    tbody {
                        tr {
                            td {
                                text-align: center;
                                padding: 0;
                            }

                            td.td-employee-name {
                                width: 180px;
                            }

                            td.td-number-employee,
                            td.td-type-employee,
                            td.td-employee-name {
                                background-color: $sub-main;
                                font-weight: bold;
                                vertical-align: middle;

                                min-width: 180px;
                            }

                            td.td-number-employee {
                                position: sticky;
                                top: 0;
                                z-index: 8;
                                left: 0;

                                min-width: 130px;
                                max-width: 130px;
                            }

                            td.td-type-employee {
                                position: sticky;
                                top: 0;
                                z-index: 8;
                                left: 130px;
                            }

                            td.td-employee-name {
                                position: sticky;
                                top: 0;
                                z-index: 8;
                                left: 310px;
                            }

                        }
                    }
                }
            }
        }
    }

    .modal-edit-node {
        .display-list-course {
            height: 250px;
            overflow: auto;
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
