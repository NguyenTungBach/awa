<template>
    <b-col>
        <b-container class="container">
            <div class="page-schedule">
                <div class="page-schedule__header">
                    <b-row>
                        <b-col>
                            <div class="zone-title">
                                <span class="title-page">
                                    {{ $t('LIST_SCHEDULE.TITLE_LIST_SCHEDULE') }}
                                </span>
                            </div>
                        </b-col>
                        <b-col>
                            <div class="zone-item">
                                <!-- <div class="zone-title">
                                    <span
                                        class="title-bulk-delete"
                                    >
                                        {{ $t('APP.BUTTON_BULK_DELETE') }}
                                    </span>
                                </div> -->
                                <div class="zone-title">
                                    <span
                                        class="title-edit"
                                        @click="goToCreateSchedule()"
                                    >
                                        {{ $t('APP.BUTTON_SIGN_UP') }}
                                    </span>
                                </div>
                            </div>
                        </b-col>
                    </b-row>
                    <LineGray />
                </div>
            </div>

            <div class="page-schedule">
                <div class="page-schedule__header">
                    <b-row>
                        <!-- <b-col>
                            <div class="zone-title">
                                <div class="zone-title__left" />
                                <div class="customselect">
                                    <select>
                                        <option>{{ $t('LIST_SCHEDULE.TABLE_FILL') }} </option>
                                        <option>2001</option>
                                        <option>2002</option>
                                    </select>
                                </div>
                                <div class="zone-title__right" />
                            </div>
                        </b-col> -->
                        <b-col>
                            <div class="zone-item">
                                <div
                                    class="item-function"
                                    @click="handleClickImport()"
                                >
                                    <div class="show-icon">
                                        <i class="fas fa-file-spreadsheet" />
                                    </div>
                                    <div class="show-text">
                                        <span>Excel取り込み</span>
                                    </div>
                                </div>
                                <div class="item-function" @click="onClickExport()">
                                    <div class="show-icon">
                                        <i class="fas fa-file-excel" />
                                    </div>
                                    <div class="show-text">
                                        Excel出力
                                    </div>
                                </div>
                            </div>
                        </b-col>
                    </b-row>
                    <HeaderFilter>
                        <template #zone-filter>
                            <div class="page-schedule__filter">
                                <b-row>
                                    <b-col
                                        :cols="12"
                                        :sm="12"
                                        :md="12"
                                        :lg="6"
                                        :xl="6"
                                    >
                                        <div class="course-rate-range">
                                            <label for="input-course-date-range">
                                                {{ $t('LIST_SCHEDULE.TITLE_COURSE_RATE_RANGE_START_TIME') }}
                                            </label>
                                        </div>
                                        <div class="time-course-rate-range">
                                            <b-input-group class="input-time-start">
                                                <b-form-input
                                                    type="text"
                                                />
                                                <b-input-group-append>
                                                    <b-form-timepicker
                                                        button-only
                                                        right
                                                    />
                                                </b-input-group-append>
                                            </b-input-group>
                                            <div class="from-to">
                                                <span> ~ </span>
                                            </div>
                                            <b-input-group class="input-time-end">
                                                <b-form-input
                                                    type="text"
                                                />
                                                <b-input-group-append>
                                                    <b-form-timepicker
                                                        button-only
                                                        right
                                                    />
                                                </b-input-group-append>
                                            </b-input-group>
                                        </div>
                                    </b-col>
                                    <b-col
                                        :cols="12"
                                        :sm="12"
                                        :md="12"
                                        :lg="4"
                                        :xl="4"
                                    >
                                        <div class="customer-name">
                                            <label for="input-customer-name">
                                                {{ $t('LIST_SCHEDULE.TITLE_CUSTOMER_NAME') }}
                                            </label>
                                        </div>
                                        <div class="select-option">
                                            <b-form-select v-model="customerName" :options="listNameCustomer" />
                                        </div>
                                    </b-col>
                                    <b-col
                                        :cols="12"
                                        :sm="12"
                                        :md="12"
                                        :lg="2"
                                        :xl="2"
                                        class="option_button"
                                    >
                                        <div class="group-button">
                                            <b-button
                                                pill
                                                class="btn-reset"
                                            >
                                                {{ $t('LIST_SCHEDULE.BUTTON_RESET') }}
                                            </b-button>
                                            <b-button
                                                pill
                                                class="btn-search"
                                            >
                                                {{ $t('LIST_SCHEDULE.BUTTON_SEARCH') }}
                                            </b-button>
                                        </div>
                                    </b-col>
                                </b-row>
                            </div>
                        </template>
                    </HeaderFilter>
                </div>
                <div class="page-schedule__body">
                    <div class="zone-table">
                        <b-table-simple
                            bordered
                            no-border-collapse
                        >
                            <b-thead class="zone-table__head">
                                <b-tr>
                                    <b-th class="th-control">
                                        <b-form-checkbox
                                            id="checkbox-1"
                                            v-model="checked_all"
                                            name="checkbox-1"
                                            class="text-center"
                                            @change="checkboxAll"
                                        />
                                    </b-th>
                                    <b-th
                                        :rowspan="2"
                                        class="row-date"
                                    >
                                        <b-row class="row-course-id ">
                                            {{ $t('LIST_SCHEDULE.TABLE_COURSE_DATE') }}
                                            <b-col class="icon-sorts">
                                                <div class="text-right">
                                                    <i
                                                        class="fad fa-sort-up icon-sort"
                                                    />
                                                </div>
                                            </b-col>
                                        </b-row>
                                    </b-th>
                                    <b-th
                                        :rowspan="2"
                                        class="row-couse-name"
                                    >
                                        <b-row class="row-course-id ">
                                            {{ $t('LIST_SCHEDULE.TABLE_COURSE_NAME') }}
                                            <b-col class="icon-sorts">
                                                <div class="text-right">
                                                    <i
                                                        class="fad fa-sort-up icon-sort"
                                                    />
                                                </div>
                                            </b-col>
                                        </b-row>
                                    </b-th>
                                    <b-th
                                        :rowspan="2"
                                    >
                                        <b-row class="row-course-id">
                                            {{ $t('LIST_SCHEDULE.TABLE_CUSTUM_NAME') }}
                                            <b-col class="icon-sorts">
                                                <div class="text-right">
                                                    <i
                                                        class="fad fa-sort-up icon-sort"
                                                    />
                                                </div>
                                            </b-col>
                                        </b-row>
                                    </b-th>
                                    <b-th>
                                        <b-row class="row-course-id">
                                            {{ $t('LIST_SCHEDULE.TABLE_DEPATURE_PLACE') }}
                                            <b-col class="icon-sorts">
                                                <div class="text-right">
                                                    <i
                                                        class="fad fa-sort-up icon-sort"
                                                    />
                                                </div>
                                            </b-col>
                                        </b-row>
                                    </b-th>
                                    <b-th>
                                        <b-row class="row-course-id">
                                            {{ $t('LIST_SCHEDULE.TABLE_ARRIVAL_PLACE') }}
                                            <b-col class="icon-sorts">
                                                <div class="text-right">
                                                    <i
                                                        class="fad fa-sort-up icon-sort"
                                                    />
                                                </div>
                                            </b-col>
                                        </b-row>
                                    </b-th>
                                    <b-th
                                        :rowspan="2"
                                        class="row-freight-cost"
                                    >
                                        <b-row class="row-course-id ">
                                            {{ $t('LIST_SCHEDULE.TABLE_FREIGHT_COST') }}
                                            <b-col class="icon-sorts">
                                                <div class="text-right">
                                                    <i
                                                        class="fad fa-sort-up icon-sort"
                                                    />
                                                </div>
                                            </b-col>
                                        </b-row>
                                    </b-th>
                                    <b-th
                                        :rowspan="2"
                                        class="text-center th-control"
                                    >
                                        {{ $t('LIST_SCHEDULE.TABLE_TITLE_DETAIL') }}
                                    </b-th>
                                    <b-th
                                        :rowspan="2"
                                        class="text-center th-control"
                                    >
                                        {{ $t('LIST_COURSE.TABLE_DELETE') }}
                                    </b-th>
                                </b-tr>
                            </b-thead>
                            <b-tbody>
                                <template v-for="(schedule, idx) in listSchedule">
                                    <b-tr :key="`schedule-number-${idx + 1}`">
                                        <b-td>
                                            <b-form-checkbox
                                                :checked="handleShowCheckbox(schedule.id)"
                                                class="text-center td-control"
                                            />
                                        </b-td>
                                        <b-td class="text-center">
                                            {{ schedule.date }}
                                        </b-td>
                                        <b-td class="text-center">
                                            {{ schedule.courseName }}
                                        </b-td>
                                        <b-td class="text-center">
                                            {{ schedule.customerName }}
                                        </b-td>
                                        <b-td class="text-center">
                                            {{ schedule.depaturePlace }}
                                        </b-td>
                                        <b-td class="text-center">
                                            {{ schedule.arrivalPlace }}
                                        </b-td>
                                        <b-td class="text-center">
                                            {{ schedule.freightCost }}
                                        </b-td>
                                        <b-td class="text-center td-control">
                                            <i
                                                class="fas fa-eye"
                                                @click="onClickDetail()"
                                            />
                                        </b-td>
                                        <b-td class="text-center td-control">
                                            <i
                                                class="fas fa-trash-alt"
                                                @click="onClickDelete()"
                                            />
                                        </b-td>
                                    </b-tr>
                                </template>
                            </b-tbody>

                        </b-table-simple>
                    </div>
                </div>
            </div>
        </b-container>
        <b-modal
            id="modal-import"
            v-model="showModalImport"
            body-class="modal-import"
            hide-header
            hide-footer
            no-close-on-esc
            no-close-on-backdrop
            static
            @close="handleCloseModalImport"
        >
            <div class="text-center">
                <h5 class="font-weight-bold">
                    運行情報データを取り込む
                </h5>
            </div>
            <div class="body-item">
                <b-row>
                    <b-col cols="3">
                        <span class="lable-show-file-name">ファイル</span>
                    </b-col>

                    <b-col cols="9">
                        <b-form-file
                            id="import-file"
                            ref="importFile"
                            v-model="fileImport"
                            plain
                            type="file"
                            name="import-file"
                            accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel"
                        />

                        <div class="show-file-name" @click="onClickImportFile()">
                            <span v-if="!fileImport">ファイルを選択</span>
                            <span v-else>{{ fileImport.name }}</span>
                        </div>
                    </b-col>
                </b-row>
            </div>
            <div v-show="messageValidateImport" class="text-center message-error-import-file">
                <span class="text-danger font-weight-bold">{{ messageValidateImport }}</span>
            </div>
            <div class="text-center">
                <b-button
                    pill
                    class="mr-2"
                    @click="handleCloseModalImport()"
                >
                    キャンセル
                </b-button>

                <b-button
                    pill
                    class="btn-color-active-import"
                    @click="handleSaveModalImport()"
                >
                    取り込み
                </b-button>
            </div>
        </b-modal>
    </b-col>

</template>

<script>
import CONSTANT from '@/const';
import LineGray from '@/components/LineGray';
import HeaderFilter from '@/components/HeaderFilter';
// import { Obj2Param } from '@/utils/Obj2Param';
// import { getToken } from '@/utils/handleToken';
import { setLoading } from '@/utils/handleLoading';
// import { cleanObject } from '@/utils/handleObject';
// import { format2Digit } from '@/utils/generateTime';
// import NodeSchedule from '@/components/NodeSchedule';
// import { getCalendar } from '@/api/modules/calendar';
import { postImportFile } from '@/api/modules/courseSchedule';
// import { getNumberDate, getTextDay } from '@/utils/convertTime';
// import { validateSizeFile, validateFileCSV } from '@/utils/validate';
import TOAST_SCHEDULE_SHIFT from '@/toast/modules/scheduleShift';
// import HeaderFilterVue from '../../../components/HeaderFilter.vue';

export default {
	name: 'ListSchedule',
	components: {
		LineGray,
		HeaderFilter,
		// NodeSchedule,
	},

	data() {
		return {
			customerName: null,
			listNameCustomer: [
				{
					value: 1,
					text: '荷主名',
				},
				{
					value: 2,
					text: '荷主名',
				},
			],

			selected: null,
			options: [
				{ value: null, text: 'Please select an option' },
				{ value: 'a', text: 'This is First option' },
				{ value: 'b', text: 'Selected Option' },
				{ value: { C: '3PO' }, text: 'This is an option with object value' },
				{ value: 'd', text: 'This one is disabled', disabled: true },
			],

			showModalImport: false,
			checked_all: false,
			checkselec: [],
			file: null,
			listSchedule: [
				{
					id: 1,
					date: '2022/12/05',
					courseName: 'テストコース2',
					customerName: 'B商事',
					depaturePlace: '徳島港',
					arrivalPlace: '高松港',
					freightCost: '500000',
				},
				{
					id: 2,
					date: '2022/12/25',
					courseName: 'テストコース2',
					customerName: 'E商事',
					depaturePlace: 'B商事',
					arrivalPlace: '高松港',
					freightCost: '650000',
				},
			],

			fileImport: null,
			messageValidateImport: null,
			showModalImportFaild: false,
			listCourseImportFaild: [],
		};
	},

	methods: {
		checkboxAll(){
			if (this.checked_all) {
				this.checkselec = [];

				const len = this.listSchedule.length;
				let idx = 0;

				while (idx < len) {
					this.checkselec.push(this.listSchedule[idx].id);

					idx++;
				}
			} else {
				this.checkselec = [];
			}
		},

		handleShowCheckbox(id) {
			return this.checkselec.includes(id);
		},

		goToCreateSchedule() {
			this.$router.push({ name: 'ListScheduleCreate' });
		},

		onClickDetail(){
			this.$router.push({ name: 'ListScheduleDetail' });
		},

		handleClickImport(){
			this.showModalImport = true;
		},

		async handleSaveModalImport() {
			try {
				setLoading(true);

				if (!this.messageValidateImport) {
					this.showModalImport = false;

					const URL = CONSTANT.URL_API.POST_IMPORT_COURSE_SCHEDULE;

					const YEAR = this.pickerYearMonth.year || null;
					const MONTH = this.pickerYearMonth.month || null;

					const YEAR_MONTH = `${YEAR}-${MONTH < 10 ? `0${MONTH}` : `${MONTH}`}`;
					const PARAMS = {
						for_date: YEAR_MONTH,
					};

					const DATA = new FormData();
					DATA.append('file', this.fileImport);

					const { code } = await postImportFile(URL, DATA, PARAMS);

					if (code === 200) {
						this.fileImport = null;

						TOAST_SCHEDULE_SHIFT.importSuccess();
						this.resetImportFile();
						await this.initData();
					}
				}

				setLoading(false);
			} catch (error) {
				const ERROR_CODE = error.response.data.code;
				const DATA_ERROR = error.response.data.data_error;
				const ERROR_CONTENT = error.response.data.message_content;

				this.fileImport = null;

				if (ERROR_CODE === 422 && ERROR_CONTENT === 'codes not imported') {
					await this.initData();

					this.listCourseImportFaild = DATA_ERROR;
					this.showModalImportFaild = true;
				}

				setLoading(false);
			}
		},

		onClickImportFile() {
			this.$refs.importFile.$el.click();
		},

		resetImportFile() {
			this.$refs.importFile.reset();
		},

		handleCloseModalImport() {
			this.showModalImport = false;
			this.resetImportFile();
		},
	},

};
</script>

<style lang="scss" scoped>
    @import '@/scss/variables';
    .container{
        max-width: 1500px !important;
    }

    .page-schedule {
        &__header {
            .zone-title {
                margin-top: 25px;
                display: flex;
                .title-page {
                    font-size: 25px;
                }
                &__left {
                background: $gray;
                width: 60px;
                height: 2px;
                margin: 17px 0;
                margin-right: 10px;
                }
                &__right {
                background: $gray;
                width: 60px;
                height: 2px;
                margin: 17px 0;
                margin-left: 10px;
                }
                .customselect {
                    overflow: hidden;

                    border: none;
                }

                .customselect select {
                    border:none;
                    -webkit-appearance: none;
                    outline: none;
                    font-size: 20px;
                    }
            }

            .zone-item {
                display: flex;
                justify-content: flex-end;

                .item-function {
                    cursor: pointer;

                    text-align: center;

                    margin: 0 10px;

                    .show-icon {
                        i {
                            font-size: 25px;
                            color: $dusty-gray;

                            align-items: revert;
                            justify-content: space-evenly;

                            margin-bottom: 5px;
                        }
                    }

                    .show-text {
                        text-align: center;
                        font-weight: bold;
                        color: $dusty-gray;
                        font-size: 12px;
                    }
                }
                .title-edit{
                    background-color: $main-header;
                    font-size: 18px;
                    border-radius: 30px;
                    color: $white;
                    padding: 6px 10px;
                    &:hover {
                            opacity: 0.8;
                            cursor: pointer;
                        }
                }
                .title-bulk-delete{
                    background-color: $punch;
                    font-size: 18px;
                    border-radius: 30px;
                    color: $white;
                    padding: 6px 10px;
                    &:hover {
                            opacity: 0.8;
                            cursor: pointer;
                        }
                }
            }

        }
        &__filter{
            margin: 30px 0 10px 20px;
            .time-course-rate-range{
                display: flex;
                .input-time-start{
                    margin: 0 5px;
                }
                .input-time-end{
                    margin: 0 5px;
                }
                .from-to{
                    display: flex;
                    align-items: center;
                    font-size: 25px;
                }
            }
            .course-rate-range {
                margin-left: 5px;
            }
            .option_button {
                margin-block-start: auto;
                .group-button {
                    .btn-reset,
                    .btn-search {
                        &:hover {
                            opacity: 0.8;
                        }

                        border-color: transparent;
                    }
                    .btn-reset{
                        background-color: $gray;
                        margin: 0 5px;
                    }
                    .btn-search {
                        margin: 0 5px;
                        background-color: $main;
                        color: $white;
                        font-weight: 600;
                    }
                }
            }

        }
        &__body{
            .zone-table{
            margin-top: 25px;
           ::v-deep {
                    table {
                        thead {
                            tr {
                                position: sticky;
                                top: 0;
                                z-index: 8;

                                th {
                                    vertical-align: middle;

                                    min-width: 65px;
                                    height: 41px;
                                    background-color: $main;
                                    color: $white;
                                }
                                th.row-date {
                                   width: 65px;
                                }
                                th.row-freight-cost {
                                    width: 150px;
                                }
                                .row.row-course-id {
                                    display: flex;
                                    flex-wrap: wrap;
                                    margin: auto;
                                    margin-right: -15px;

                                }

                                th.th-id {
                                     width: 150px;
                                }

                                th.th-sort {
                                    cursor: pointer;

                                    i.icon-sort-default {
                                        color: $white;
                                        opacity: 0.7;
                                    }
                                }

                                th.th-time {
                                    width: 100px;
                                }

                                th.th-control {
                                    width: 15px;
                                }
                            }

                            tr:nth-child(2) {
                                position: sticky;
                                top: 49.5px;
                                z-index: 9;
                            }
                        }

                        tbody {
                            tr {

                                td {
                                    vertical-align: middle;
                                }

                                td.td-control {
                                    i {
                                        color: $dusty-gray;
                                        font-size: 24;

                                        cursor: pointer;
                                    }
                                }
                            }

                            tr.flag {
                                background-color: $swiss-coffee;
                            }
                        }
                    }
                }
        }
        }
        &__modal {

            .btn-file{
                border-radius: 40px;
            }
        }
    }
    .modal-import {
        .body-item {
            margin-top: 40px;
            margin-bottom: 40px;

            .lable-show-file-name {
                line-height: 50px;
            }

            .show-file-name {
                width: 100%;
                min-height: 50px;
                border: 1px solid $black;

                padding: 10px 0;

                text-align: center;

                span {
                    color: #C0C0C0;
                }

                cursor: pointer;
            }

            #import-file {
                display: none;
            }
        }

        .message-error-import-file {
            margin-bottom: 30px;
        }
    }
</style>
