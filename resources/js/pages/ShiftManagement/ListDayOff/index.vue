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
                                    class="btn-edit"
                                    @click="goToDayOffEdit()"
                                >
                                    <span>{{ $t('APP.BUTTON_EDIT') }}</span>
                                </b-button>
                            </div>
                        </b-col>
                    </b-row>
                </div>

                <div
                    v-if="listCalendar.length"
                    class="zone-table"
                >
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
                                            :item="driver.day_off[col - 1]"
                                            :list-course="listCourse"
                                        />
                                    </template>
                                </b-tr>
                            </template>
                        </b-tbody>
                    </b-table-simple>
                </div>
            </div>
        </div>
    </b-col>
</template>

<script>
import CONSTANT from '@/const';
import LineGray from '@/components/LineGray';
import NodeDayOff from '@/components/NodeDayOff';
import { setLoading } from '@/utils/handleLoading';
import { cleanObject } from '@/utils/handleObject';
import { format2Digit } from '@/utils/generateTime';
import { getCalendar } from '@/api/modules/calendar';
import { getListDayOff } from '@/api/modules/dayoff';
import { getNumberDate, getTextDay } from '@/utils/convertTime';
import { convertValueToText } from '@/utils/handleSelect';
import { getList } from '@/api/modules/courseManagement';

export default {
	name: 'ListDayOff',
	components: {
		LineGray,
		NodeDayOff,
	},

	data() {
		return {
			getTextDay,

			CONSTANT,

			numberDate: 0,

			listCalendar: [],
			listCourse: [],

			sortTable: {
				sortBy: '',
				sortType: null,
			},

			listDayOff: [],
			reRender: 0,
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
		async pickerYearMonth(newValue, oldValue) {
			if (JSON.stringify(oldValue) !== JSON.stringify(newValue)) {
				setLoading(true);

				this.numberDate = getNumberDate(this.pickerYearMonth);
				await this.handleGetListCalendar();
				await this.handleGetListDayOff();

				setLoading(false);
			}
		},

		sortTable: {
			handler: async function() {
				setLoading(true);
				await this.handleGetListDayOff();
				setLoading(false);
			},

			deep: true,
		},
	},

	mounted() {
		this.initData();
	},

	methods: {
		convertValueToText,

		async initData() {
			this.numberDate = getNumberDate(this.pickerYearMonth);

			setLoading(true);

			await this.handleGetListCourse();
			await this.handleGetListCalendar();
			await this.handleGetListDayOff();

			setLoading(false);
		},

		async handleGetListCourse() {
			const COURSE_DEFAULT = [
				{
					course_code: CONSTANT.LIST_DAY_OFF.TYPE_DAY_OFF_DEFAULT,
					course_name: this.$t(CONSTANT.LIST_DAY_OFF.TEXT_DAY_OFF_DEFAULT),
				},
			];

			try {
				const { code, data } = await getList(CONSTANT.URL_API.GET_LIST_COURSE);

				if (code === 200) {
					if (Array.isArray(data)) {
						this.listCourse = [...COURSE_DEFAULT, ...data];
					} else {
						this.listCourse = [...COURSE_DEFAULT];
					}
				} else {
					this.listCourse = [...COURSE_DEFAULT];
				}
			} catch (err) {
				this.listCourse = [...COURSE_DEFAULT];
				console.log(err);
			}
		},

		async handleGetListCalendar() {
			try {
				this.listCalendar.length = 0;

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
					PARAMS.sortby = PARAMS.sortby ? 'asc' : 'desc';
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
				setLoading(false);
			}
		},

		reloadTable() {
			this.reRender++;
		},

		goToDayOffEdit() {
			this.$router.push({
				name: 'ListDayOffEdit',
			});
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

                .btn-edit {
                    background-color: $main;
                    color: $white;
                    font-weight: 600;

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

                                i {
                                    margin-left: 5px;
                                }
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
</style>
