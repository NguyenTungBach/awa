<template>
    <b-col>
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
            </div>

            <LineGray />

            <div class="page-schedule__body">
                <div class="zone-control">
                    <b-row>
                        <b-col>
                            <div class="text-right">
                                <b-button
                                    pill
                                    class="btn-edit"
                                    @click="goToListShiftEdit()"
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
                                    class="th-course-id"
                                    @click="onSortTable('course_code')"
                                >
                                    {{ $t('LIST_SCHEDULE.TABLE_COURSE_ID') }}
                                    <i
                                        v-if="sortTable.sortBy === 'course_code' && sortTable.sortType === true"
                                        class="fad fa-sort-up icon-sort"
                                    />
                                    <i
                                        v-else-if="sortTable.sortBy === 'course_code' && sortTable.sortType === false"
                                        class="fad fa-sort-down icon-sort"
                                    />
                                    <i
                                        v-else
                                        class="fa-solid fa-sort icon-sort-default"
                                    />
                                </b-th>
                                <b-th
                                    class="th-course-group-code"
                                    @click="onSortTable('group_code')"
                                >
                                    {{ $t('LIST_SCHEDULE.TABLE_COURSE_GROUP_CODE') }}
                                    <i
                                        v-if="sortTable.sortBy === 'group_code' && sortTable.sortType === true"
                                        class="fad fa-sort-up icon-sort"
                                    />
                                    <i
                                        v-else-if="sortTable.sortBy === 'group_code' && sortTable.sortType === false"
                                        class="fad fa-sort-down icon-sort"
                                    />
                                    <i
                                        v-else
                                        class="fa-solid fa-sort icon-sort-default"
                                    />
                                </b-th>
                                <b-th class="th-course-name">
                                    {{ $t('LIST_SCHEDULE.TABLE_COURSE_NAME') }}
                                </b-th>
                                <template v-for="date in numberDate">
                                    <b-th :key="`date-${date}`">
                                        <span>{{ listCalendar[date - 1] || '' }}</span>
                                    </b-th>
                                </template>
                            </b-tr>
                        </b-thead>

                        <b-tbody>
                            <template v-for="(emp, idx) in listCourseSchedule">
                                <b-tr :key="`b-tr-${idx + 1}`">
                                    <b-td class="td-course-id">
                                        {{ emp.course_code }}
                                    </b-td>

                                    <b-td v-if="emp.group !== null" class="td-course-group-code">
                                        {{ emp.group }}
                                    </b-td>

                                    <b-td v-else class="td-course-group-code">
                                        {{ '-' }}
                                    </b-td>

                                    <b-td class="td-course-name text-left">
                                        {{ emp.course_name }}
                                    </b-td>
                                    <template v-for="(col, idxCourse) in numberDate">

                                        <NodeSchedule
                                            :key="`td-course-${idxCourse}`"
                                            :idx-item="idxCourse"
                                            :item="emp.course_schedules[col - 1]"
                                            :emp-data="emp"
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
                    配車情報データを取り込む
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

        <b-modal
            id="modal-import-faild"
            v-model="showModalImportFaild"
            body-class="modal-import-faild"
            centered
            hide-header
            hide-footer
            no-close-on-esc
            no-close-on-backdrop
            static
            @close="handleCloseModalImportFaild"
        >
            <div>
                <p class="text-center text-danger font-weight-bold">
                    {{ $t('MESSAGE_APP.SCHEDULE_IMPORT_FAIL', { listCourse: listCourseImportFaild.join(', ') }) }}
                </p>
            </div>

            <div class="text-center">
                <b-button
                    pill
                    @click="onClickCloseModalImportFaild()"
                >
                    {{ $t('APP.BUTTON_RETURN') }}
                </b-button>
            </div>
        </b-modal>
    </b-col>
</template>

<script>
import CONSTANT from '@/const';
import LineGray from '@/components/LineGray';
import { Obj2Param } from '@/utils/Obj2Param';
import { getToken } from '@/utils/handleToken';
import { setLoading } from '@/utils/handleLoading';
import { cleanObject } from '@/utils/handleObject';
import { format2Digit } from '@/utils/generateTime';
import NodeSchedule from '@/components/NodeSchedule';
import { getCalendar } from '@/api/modules/calendar';
import { getListSchedule, postImportFile } from '@/api/modules/courseSchedule';
import { getNumberDate, getTextDay } from '@/utils/convertTime';
import { validateSizeFile, validateFileCSV } from '@/utils/validate';
import TOAST_SCHEDULE_SHIFT from '@/toast/modules/scheduleShift';

export default {
	name: 'ListSchedule',
	components: {
		LineGray,
		NodeSchedule,
	},

	data() {
		return {
			sortTable: {
				sortBy: {},
				sortType: null,
			},

			listCourseSchedule: [],
			getTextDay,

			CONSTANT,

			numberDate: 0,

			listCalendar: [],

			reRender: 0,

			listUpdate: [],

			showModalImport: false,

			fileImport: null,
			messageValidateImport: '',

			showModalImportFaild: false,
			listCourseImportFaild: [],
		};
	},

	computed: {
		pickerYearMonth() {
			return this.$store.getters.pickerYearMonth;
		},
	},

	watch: {
		async pickerYearMonth(newValue, oldValue) {
			if (JSON.stringify(oldValue) !== JSON.stringify(newValue)) {
				setLoading(true);

				this.numberDate = getNumberDate(this.pickerYearMonth);
				await this.handleGetListCalendar();
				await this.handleGetListSchedule();

				setLoading(false);
			}
		},

		fileImport: {
			handler: function() {
				if (this.fileImport) {
					const TYPE = this.fileImport.type;
					const SIZE = this.fileImport.size;

					if (validateFileCSV(TYPE)) {
						if (validateSizeFile(SIZE, 3.00)) {
							this.messageValidateImport = '';
						} else {
							this.messageValidateImport = this.$t('MESSAGE_APP.SCHEDULE_VALIDATE_FILE_SIZE');
						}
					} else {
						this.messageValidateImport = this.$t('MESSAGE_APP.SCHEDULE_VALIDATE_FILE_TYPE');
					}
				} else {
					this.messageValidateImport = this.$t('MESSAGE_APP.SCHEDULE_VALIDATE_FILE_REQUIRED');
				}
			},

			deep: true,
		},

		sortTable: {
			handler: function() {
				this.handleGetListSchedule();
			},

			deep: true,
		},
	},

	mounted() {
		this.initData();
	},

	methods: {
		async initData() {
			this.numberDate = getNumberDate(this.pickerYearMonth);

			setLoading(true);

			await this.handleGetListCalendar();
			await this.handleGetListSchedule();

			setLoading(false);
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

		async handleGetListSchedule() {
			try {
				this.listCourseSchedule.length = 0;

				const sort = {
					field: this.sortTable.sortBy,
					sortby: this.sortTable.sortType,
				};

				let PARAMS = {};

				if (sort.field === 'course_code') {
					PARAMS.sorttype_id = sort.sortby ? 'asc' : 'desc';
				}

				if (sort.field === 'group_code') {
					PARAMS.sorttype_group = sort.sortby ? 'asc' : 'desc';
				}

				const YEAR = this.pickerYearMonth.year || null;
				const MONTH = this.pickerYearMonth.month || null;

				const YEAR_MONTH = `${YEAR}-${MONTH < 10 ? `0${MONTH}` : `${MONTH}`}`;

				PARAMS.view_date = YEAR_MONTH || null;

				PARAMS = cleanObject(PARAMS);

				const { code, data } = await getListSchedule(CONSTANT.URL_API.GET_LIST_COURSE_SCHEDULE, PARAMS);

				if (code === 200) {
					this.listCourseSchedule = data;
					this.reloadTable();
					setLoading(false);
				}
			} catch (error) {
				console.log(error);
				setLoading(false);
			}
		},

		reloadTable() {
			this.reRender++;
		},

		onSortTable(col) {
			switch (col) {
				case 'course_code':
					if (this.sortTable.sortBy === 'course_code') {
						if (this.sortTable.sortType) {
							this.sortTable.sortType = !this.sortTable.sortType;
						} else {
							this.sortTable.sortType = true;
						}
					} else {
						this.sortTable.sortBy = 'course_code';
						this.sortTable.sortType = true;
					}

					break;

				case 'group_code':
					if (this.sortTable.sortBy === 'group_code') {
						if (this.sortTable.sortType) {
							this.sortTable.sortType = !this.sortTable.sortType;
						} else {
							this.sortTable.sortType = true;
						}
					} else {
						this.sortTable.sortBy = 'group_code';
						this.sortTable.sortType = true;
					}

					break;

				default:
					console.log('Handle sort table faild with ', col);

					break;
			}
		},

		goToListShiftEdit() {
			this.$router.push({
				name: 'ListScheduleEdit',
			});
		},

		onClickExport() {
			const sort = {
				field: this.sortTable.sortBy,
				sortby: this.sortTable.sortType,
			};

			let PARAMS = {};

			if (sort.field === 'course_code') {
				PARAMS.sorttype_id = sort.sortby ? 'asc' : 'desc';
			}

			if (sort.field === 'group_code') {
				PARAMS.sorttype_group = sort.sortby ? 'asc' : 'desc';
			}

			const YEAR = this.pickerYearMonth.year || null;
			const MONTH = this.pickerYearMonth.month || null;

			const YEAR_MONTH = `${YEAR}-${MONTH < 10 ? `0${MONTH}` : `${MONTH}`}`;

			PARAMS.view_date = YEAR_MONTH || null;

			PARAMS = cleanObject(PARAMS);

			const URL = `/api${CONSTANT.URL_API.GET_EXPORT_COURSE_SCHEDULE}?${Obj2Param(PARAMS)}`;

			let FILE_DOWNLOAD;

			fetch(URL, {
				headers: {
					'Accept-Language': this.$store.getters.language,
					'Authorization': getToken(),
					'accept': 'application/json',
				},
			}).then(async(res) => {
				let filename = decodeURI(res.headers.get('content-disposition').split(`filename*=utf-8''`)[1]) || `配車表_{${YEAR_MONTH}}`;
				filename = filename.replaceAll('"', '');
				await res.blob().then((res) => {
					FILE_DOWNLOAD = res;
				});
				const fileURL = window.URL.createObjectURL(FILE_DOWNLOAD);
				const fileLink = document.createElement('a');

				fileLink.href = fileURL;
				fileLink.setAttribute('download', filename);
				document.body.appendChild(fileLink);

				fileLink.click();
			})
				.catch((err) => {
					console.log(err);
				});
		},

		handleClickImport() {
			this.showModalImport = true;
		},

		handleCloseModalImport() {
			this.showModalImport = false;
			this.resetImportFile();
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

		handleCloseModalImportFaild() {
			this.listCourseImportFaild.length = 0;
		},

		onClickCloseModalImportFaild() {
			this.showModalImportFaild = false;
			this.handleCloseModalImportFaild();
		},
	},
};
</script>

<style lang="scss" scoped>
    @import '@/scss/variables';

    .page-schedule {
        &__header {
            .zone-title {
                .title-page {
                    font-size: 25px;
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
                height: calc(100vh - 220px);
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

                            th.th-date {
                                padding: 5px 0;
                                position: sticky;
                                top: 0;
                                z-index: 9;
                            }

                            th.th-course-id {
                                position: sticky;
                                top: 37px;
                                z-index: 10;
                                left: 0;

                                min-width: 120px;
                                max-width: 120px;

                                cursor: pointer;
                            }

                            th.th-course-group-code {
                                position: sticky;
                                top: 37px;
                                z-index: 10;
                                left: 120px;

                                min-width: 120px;
                                max-width: 120px;

                                cursor: pointer;
                            }

                            th.th-course-name {
                                position: sticky;
                                top: 37px;
                                z-index: 10;
                                left: 240px;

                                min-width: 300px;
                                max-width: 300px;
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
                                min-width: 80px;
                            }

                            td.td-course-name {
                                width: 180px;
                            }

                            td.td-course-id,
                            td.td-course-group-code,
                            td.td-course-name {
                                background-color: $sub-main;
                                font-weight: bold;
                                vertical-align: middle;

                                min-width: 120px;
                            }

                            td.td-course-id {
                                position: sticky;
                                top: 0;
                                left: 0;
                                z-index: 8;
                            }

                            td.td-course-group-code {
                                position: sticky;
                                top: 0;
                                z-index: 8;
                                left: 120px;
                            }

                            td.td-course-name {
                                position: sticky;
                                top: 0;
                                left: 240px;
                                z-index: 8;
                            }
                        }
                    }
                }
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
