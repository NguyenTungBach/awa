<template>
    <b-col>
        <b-container>
            <div class="page-list-course">
                <div class="page-list-course__header">
                    <b-row>
                        <b-col>
                            <div class="zone-title text-left">
                                <span class="title-page">
                                    {{ $t('LIST_COURSE.TITLE_LIST_COURSE') }}
                                </span>
                            </div>
                        </b-col>
                        <b-col>
                            <div class="zone-control text-right">
                                <b-button
                                    pill
                                    class="btn-control"
                                    @click="onClickSignUp()"
                                >
                                    {{ $t('APP.BUTTON_SIGN_UP') }}
                                </b-button>
                            </div>
                        </b-col>
                    </b-row>
                    <LineGray />
                </div>
                <div class="page-list-course__body">
                    <div class="zone-table">
                        <b-table-simple
                            bordered
                            no-border-collapse
                        >
                            <b-thead>
                                <b-tr>
                                    <b-th
                                        class="th-sort th-id th-course-id text-center"
                                        :rowspan="2"
                                        @click="onSortTable('course_code')"
                                    >
                                        <b-row class="row-course-id ">
                                            {{ $t('LIST_COURSE.TABLE_COURSE_ID') }}
                                            <b-col class="icon-sorts">
                                                <div class="text-right">
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
                                                </div>
                                            </b-col>
                                        </b-row>
                                    </b-th>
                                    <b-th
                                        class="th-sort th-name th-course-name"
                                        :colspan="3"
                                        @click="onSortTable('course_name')"
                                    >
                                        <b-row >
                                            <b-col >
                                                {{ $t('LIST_COURSE.TABLE_COURSE_NAME') }}
                                            </b-col>
                                            <b-col>
                                                <div class="text-right">
                                                    <i
                                                        v-if="sortTable.sortBy === 'course_name' && sortTable.sortType === true"
                                                        class="fad fa-sort-up icon-sort"
                                                    />
                                                    <i
                                                        v-else-if="sortTable.sortBy === 'course_name' && sortTable.sortType === false"
                                                        class="fad fa-sort-down icon-sort"
                                                    />
                                                    <i
                                                        v-else
                                                        class="fa-solid fa-sort icon-sort-default"
                                                    />
                                                </div>
                                            </b-col>
                                        </b-row>
                                    </b-th>
                                    <b-th
                                        class="th-sort th-closing-day"
                                        :rowspan="2"
                                        @click="onSortTable('closing_day')"
                                    >
                                        <b-row >
                                            <b-col >
                                                {{ $t('LIST_COURSE.TABLE_OPERATIONAL_INFORMATION') }}
                                            </b-col>
                                            <b-col>
                                                <div class="text-right">
                                                    <i
                                                        v-if="sortTable.sortBy === 'closing_day' && sortTable.sortType === true"
                                                        class="fad fa-sort-up icon-sort"
                                                    />
                                                    <i
                                                        v-else-if="sortTable.sortBy === 'closing_day' && sortTable.sortType === false"
                                                        class="fad fa-sort-down icon-sort"
                                                    />
                                                    <i
                                                        v-else
                                                        class="fa-solid fa-sort icon-sort-default"
                                                    />
                                                </div>
                                            </b-col>
                                        </b-row>
                                    </b-th>
                                    <b-th
                                        :rowspan="2"
                                        class="text-center th-control"
                                    >
                                        {{ $t('LIST_COURSE.TABLE_DETAIL') }}
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
                                <template v-for="(course, idx) in listCourse">
                                    <b-tr :key="`course-number-${idx + 1}`" :class="[genereateClassRowByStatus(course.status)]">
                                        <b-td class="td-course-id">
                                            {{ course.course_code }}
                                        </b-td>
                                        <b-td class="td-course-name">
                                            <!-- {{ course.course_name }} -->
                                            ABC company
                                        </b-td>
                                        <b-td :colspan="3" class="text-center td-control">
                                            <!-- {{ course.start_time }} -->
                                            25 日
                                        </b-td>
                                        
                                        <b-td class="text-center td-control">
                                            <i
                                                class="fas fa-eye"
                                                @click="onClickDetail(course)"
                                            />
                                        </b-td>
                                        <b-td class="text-center td-control">
                                            <i
                                                class="fas fa-trash-alt"
                                                @click="onClickDelete(course)"
                                            />
                                        </b-td>
                                    </b-tr>
                                </template>
                            </b-tbody>
                        </b-table-simple>
                        <template v-if="listCourse.length === 0">
                            <div class="text-center">
                                {{ $t('APP.TABLE_NO_DATA') }}
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </b-container>

        <b-modal
            id="modal-delete"
            ref="modal-delete"
            v-model="modalDelete"
            body-class="modal-detail"
            static
            :title="$t('APP.TITLE_MODAL_CONFIRM')"
            :cancel-title="$t('APP.TEXT_CANCEL')"
            :ok-title="$t('APP.TEXT_CONFIRM')"
            @hidden="resetModal"
            @ok="handleOk"
        >
            <div class="text-center">
                <span>{{ $t('LIST_COURSE.TEXT_CONFIRM_DELETE') }}</span>
            </div>
        </b-modal>
    </b-col>
</template>

<script>
import CONSTANT from '@/const';
import LineGray from '@/components/LineGray';
import { cleanObject } from '@/utils/handleObject';
import { setLoading } from '@/utils/handleLoading';
import { getList, deleteCourse } from '@/api/modules/courseManagement';
import TOAST_COURSE_MANAGEMENT from '@/toast/modules/courseManagement';

export default {
	name: 'ListCourse',
	components: {
		LineGray,
	},

	data() {
		return {
			sortTable: {
				sortBy: '',
				sortType: null,
			},

			listCourse: [],

			modalDelete: false,

			courseHandle: {
				id: null,
				flag: '',
				course_code: '',
				course_name: '',
				start_time: '',
				end_time: '',
				break_time: '',
				start_date: '',
				end_date: '',
				status: '',
				note: '',
				created_at: null,
				updated_at: null,
				deleted_at: null,
			},
		};
	},

	watch: {
		sortTable: {
			handler: function() {
				this.handleGetList();
			},

			deep: true,
		},
	},

	created() {
		this.initData();
	},

	methods: {
		async initData() {
			await this.handleGetList();
		},

		async handleGetList() {
			try {
				setLoading(true);

				let PARAMS = {
					field: this.sortTable.sortBy,
					sortby: this.sortTable.sortType,
				};

				PARAMS = cleanObject(PARAMS);

				if (PARAMS.field) {
					PARAMS.sortby = PARAMS.sortby ? 'asc' : 'desc';
				}

				const LIST = await getList(CONSTANT.URL_API.GET_LIST_COURSE, PARAMS);

				if (LIST.code === 200) {
					this.listCourse = LIST.data;
				} else {
					this.listCourse = [];
				}

				setLoading(false);
			} catch {
				setLoading(false);
			}
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

				case 'course_name':
					if (this.sortTable.sortBy === 'course_name') {
						if (this.sortTable.sortType) {
							this.sortTable.sortType = !this.sortTable.sortType;
						} else {
							this.sortTable.sortType = true;
						}
					} else {
						this.sortTable.sortBy = 'course_name';
						this.sortTable.sortType = true;
					}

					break;

				case 'closing_day':
					if (this.sortTable.sortBy === 'closing_day') {
						if (this.sortTable.sortType) {
							this.sortTable.sortType = !this.sortTable.sortType;
						} else {
							this.sortTable.sortType = true;
						}
					} else {
						this.sortTable.sortBy = 'closing_day';
						this.sortTable.sortType = true;
					}

					break;

				default:
					console.log('Handle sort table faild');

					break;
			}
		},

		onClickDetail(scope) {
			this.$router.push({
				name: 'CourseDetail',
				params: {
					id: scope.id,
				},
			});
		},

		onClickDelete(scope) {
			this.courseHandle = scope;

			this.modalDelete = true;
		},

		resetModal() {
			this.courseHandle = {
				id: null,
				flag: null,
				course_code: null,
				course_name: null,
				start_time: null,
				end_time: null,
				break_time: null,
				start_date: null,
				end_date: null,
				status: null,
				note: null,
				created_at: null,
				updated_at: null,
				deleted_at: null,
			};
		},

		async handleOk() {
			try {
				this.modalDelete = false;
				if (this.courseHandle.id) {
					setLoading(true);

					const DELETE_COURSE = await deleteCourse(`${CONSTANT.URL_API.DELETE_COURSE}/${this.courseHandle.id}`);

					if (DELETE_COURSE.code === 200) {
						await this.handleGetList();
						TOAST_COURSE_MANAGEMENT.delete();
					}

					setLoading(false);
				}
			} catch {
				setLoading(false);
			}
		},

		onClickSignUp() {
			this.$router.push({
				name: 'CourseCreate',
			});
		},

		genereateClassRowByStatus(status) {
			if (!status) {
				return '';
			}

			if (status === 'off') {
				return 'flag';
			}

			return '';
		},
	},
};
</script>

<style lang="scss" scoped>
    @import '@/scss/variables';

    .page-list-course {

        .page-list-course__body {
            .zone-table {
                height: calc(100vh - 165px);
                overflow-y: auto;

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

                                .row.row-course-id {
                                 
                                    display: flex;
                                    flex-wrap: wrap;
                                    margin-right: -15px;
                                    margin-left: -6px;
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
    }
</style>
