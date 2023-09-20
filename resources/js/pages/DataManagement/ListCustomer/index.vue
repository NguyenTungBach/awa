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
                                    @click="onClickCreate()"
                                >
                                    {{ $t('APP.BUTTON_CREATE') }}
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
                                        @click="onSortTable('customer_code')"
                                    >
                                        <b-row class="row-course-id ">
                                            {{ $t('LIST_COURSE.TABLE_COURSE_ID') }}
                                            <b-col class="icon-sorts">
                                                <div class="text-right">
                                                    <i
                                                        v-if="sortTable.sortBy === 'customer_code' && sortTable.sortType === true"
                                                        class="fad fa-sort-up icon-sort"
                                                    />
                                                    <i
                                                        v-else-if="sortTable.sortBy === 'customer_code' && sortTable.sortType === false"
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
                                        @click="onSortTable('customer_name')"
                                    >
                                        <b-row>
                                            <b-col>
                                                {{ $t('LIST_COURSE.TABLE_COURSE_NAME') }}
                                            </b-col>
                                            <b-col>
                                                <div class="text-right">
                                                    <i
                                                        v-if="sortTable.sortBy === 'customer_name' && sortTable.sortType === true"
                                                        class="fad fa-sort-up icon-sort"
                                                    />
                                                    <i
                                                        v-else-if="sortTable.sortBy === 'customer_name' && sortTable.sortType === false"
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
                                        @click="onSortTable('closing_date')"
                                    >
                                        <b-row>
                                            <b-col>
                                                {{ $t('LIST_COURSE.TABLE_OPERATIONAL_INFORMATION') }}
                                            </b-col>
                                            <b-col>
                                                <div class="text-right">
                                                    <i
                                                        v-if="sortTable.sortBy === 'closing_date' && sortTable.sortType === true"
                                                        class="fad fa-sort-up icon-sort"
                                                    />
                                                    <i
                                                        v-else-if="sortTable.sortBy === 'closing_date' && sortTable.sortType === false"
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
                                            {{ course.customer_code }}
                                        </b-td>
                                        <b-td class="td-course-name">
                                            {{ course.customer_name }}
                                        </b-td>
                                        <b-td :colspan="3" class="text-center td-control">
                                            {{ course.closing_date }}
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
	name: 'ListCustomer',
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
				customer_code: '',
				customer_name: '',
				closing_date: '',
				status: '',
				note: '',
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
					order_by: this.sortTable.sortBy,
					sort_by: this.sortTable.sortType,
				};

				PARAMS = cleanObject(PARAMS);

				if (PARAMS.order_by) {
					PARAMS.sort_by = PARAMS.sort_by ? 'asc' : 'desc';
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
				case 'customer_code':
					if (this.sortTable.sortBy === 'customer_code') {
						if (this.sortTable.sortType) {
							this.sortTable.sortType = !this.sortTable.sortType;
						} else {
							this.sortTable.sortType = true;
						}
					} else {
						this.sortTable.sortBy = 'customer_code';
						this.sortTable.sortType = true;
					}

					break;

				case 'customer_name':
					if (this.sortTable.sortBy === 'customer_name') {
						if (this.sortTable.sortType) {
							this.sortTable.sortType = !this.sortTable.sortType;
						} else {
							this.sortTable.sortType = true;
						}
					} else {
						this.sortTable.sortBy = 'customer_name';
						this.sortTable.sortType = true;
					}

					break;

				case 'closing_date':
					if (this.sortTable.sortBy === 'closing_date') {
						if (this.sortTable.sortType) {
							this.sortTable.sortType = !this.sortTable.sortType;
						} else {
							this.sortTable.sortType = true;
						}
					} else {
						this.sortTable.sortBy = 'closing_date';
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
				name: 'CustomerDetail',
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
				customer_code: '',
				customer_name: '',
				closing_date: '',
				status: '',
				note: '',
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

		onClickCreate() {
			this.$router.push({
				name: 'CustomerCreate',
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
