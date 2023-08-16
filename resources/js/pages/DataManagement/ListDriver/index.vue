<template>
    <b-col>
        <b-container>
            <div class="list-driver">
                <div class="list-driver__header">
                    <b-row>
                        <b-col>
                            <div class="zone-title text-left">
                                <span class="title-page">
                                    {{ $t('LIST_DRIVER.TITLE_LIST_DRIVER') }}
                                </span>
                            </div>
                        </b-col>
                        <b-col>
                            <div class="zone-btn-sign-up text-right">
                                <b-button
                                    pill
                                    class="btn-control"
                                    @click="goToCreateDriver()"
                                >
                                    {{ $t('LIST_DRIVER.BUTTON_SIGN_UP') }}
                                </b-button>
                            </div>
                        </b-col>
                    </b-row>
                </div>
                <LineGray />
                <div class="list-driver__body">
                    <div class="zone-table table-list-driver">
                        <b-table
                            :items="items"
                            :fields="fields"
                            :tbody-tr-class="rowClass"
                            bordered
                            no-sort-reset
                            no-local-sorting
                            show-empty
                            @sort-changed="handleSort"
                        >
                            <template #cell(driver_code)="scope">
                                {{ formatNumberCode(scope.item.driver_code) }}
                            </template>

                            <template #cell(status)="scope">
                                {{ getTextEnrollmentStatus(scope.item.end_date) }}
                            </template>

                            <template #cell(detail)="scope">
                                <i
                                    class="fas fa-eye"
                                    @click="goToDetail(scope.item)"
                                />
                            </template>

                            <template #cell(delete)="scope">
                                <i
                                    class="fas fa-trash-alt"
                                    @click="handleDelete(scope.item)"
                                />
                            </template>

                            <template #empty>
                                <div class="text-center">
                                    {{ $t('APP.TABLE_NO_DATA') }}
                                </div>
                            </template>
                        </b-table>
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
                <span>{{ $t('LIST_DRIVER.TEXT_CONFIRM_DELETE') }}</span>
            </div>
        </b-modal>
    </b-col>
</template>

<script>
import CONSTANT from '@/const';
import LineGray from '@/components/LineGray';
import { setLoading } from '@/utils/handleLoading';
import { cleanObject } from '@/utils/handleObject';
import { getList, deleteDriver } from '@/api/modules/driver';
import TOAST_DRIVER from '@/toast/modules/driverManagement';
import { formatNumberCode } from '@/utils/formatNumber';

export default {
	name: 'ListDriver',
	components: {
		LineGray,
	},

	data() {
		return {
			CONSTANT,

			items: [],

			sortTable: {
				sortBy: '',
				sortType: '',
			},

			modalDelete: false,

			driverHandle: {
				id: null,
				driver_code: '',
				driver_name: '',
				status: '',
				start_date: '',
				end_date: null,
				created_at: null,
				updated_at: null,
			},

			currentDate: null,
		};
	},

	computed: {
		fields() {
			return [
				{ key: 'driver_code', label: this.$t('LIST_DRIVER.TABLE_TITLE_EMPLOYEE_NUMBER'), sortable: true, thClass: 'base-th th-employee-number' },
				{ key: 'typeName', label: this.$t('LIST_DRIVER.TABLE_TITLE_TYPE_NAME'), sortable: true, thClass: 'base-th th-type-name' },
				{ key: 'driver_name', label: this.$t('LIST_DRIVER.TABLE_TITLE_FULL_NAME'), sortable: true, thClass: 'base-th th-driver-name' },
				{ key: 'status', label: this.$t('LIST_DRIVER.TABLE_TITLE_ENROLLMENT_STATUS'), sortable: true, thClass: 'base-th text-center th-enrollment', tdClass: 'text-center' },
				{ key: 'detail', label: this.$t('LIST_DRIVER.TABLE_TITLE_DETAIL'), sortable: false, thClass: 'text-center base-th base-th-control', tdClass: 'text-center base-td-control' },
				{ key: 'delete', label: this.$t('LIST_DRIVER.TABLE_TITLE_DELETE'), sortable: false, thClass: 'text-center base-th base-th-control', tdClass: 'text-center base-td-control' },
			];
		},

		pickerYearMonth() {
			return this.$store.getters.pickerYearMonth;
		},
	},

	watch: {
		sortTable: {
			handler() {
				this.handleGetList();
			},

			deep: true,
		},
	},

	created() {
		this.$store.dispatch('listDriver/setIndexTab', 0);
		this.initData();
	},

	mounted() {
		this.getCurrentDate();
	},

	methods: {
		formatNumberCode,

		async initData() {
			await this.handleGetList();
		},

		getCurrentDate() {
			const today = new Date();
			// Định dạng ngày thành chuỗi yyyy-MM-dd để sử dụng trong input type="date"
			this.currentDate = today.toISOString().slice(0, 10);
		},

		async handleGetList() {
			try {
				this.items.length = 0;

				setLoading(true);

				let PARAMS = {
					field: this.sortTable.sortBy,
					sortby: this.sortTable.sortType,
				};

				PARAMS = cleanObject(PARAMS);

				const LIST_DRIVER = await getList(CONSTANT.URL_API.GET_LIST_DRIVER, PARAMS);

				if (LIST_DRIVER.code === 200) {
					this.items = LIST_DRIVER.data;
				} else {
					this.items.length = 0;
				}

				setLoading(false);
			} catch (error) {
				console.log(error);
				setLoading(false);
			}
		},

		// handleStyle(end_date) {
		// 	const today = new Date();
		// 	const endDate = new Date(end_date);
		// 	if (today === endDate) {
		// 		return 'background: #dbdbdb';
		// 	} else if (end_date === null) {
		// 		return '';
		// 	}
		// },

		getTextEnrollmentStatus(end_date) {
			const today = new Date();
			const endDate = end_date !== null ? new Date(end_date) : null;
			if (end_date === null) {
				return this.$t(CONSTANT.LIST_DRIVER.TEXT_ENROLLMENT_STATUS_ENROLLED);
			} else if (today >= endDate) {
				return this.$t(CONSTANT.LIST_DRIVER.TEXT_ENROLLMENT_STATUS_RETIRED);
			} else {
				return this.$t(CONSTANT.LIST_DRIVER.TEXT_ENROLLMENT_STATUS_ENROLLED);
			}
			// switch (end_date) {
			// 	case end_date !== null:
			// 		return this.$t(CONSTANT.LIST_DRIVER.TEXT_ENROLLMENT_STATUS_RETIRED);
			// 	case end_date:
			// 		return this.$t(CONSTANT.LIST_DRIVER.TEXT_ENROLLMENT_STATUS_ENROLLED);
			// 	default:
			// 		return '';
			// }
		},

		rowClass(item) {
			console.log('addd', item);
			const today = new Date();
			const endDate = item.end_date === null ? null : new Date(item.end_date);
			console.log('end date:', endDate);
			if (item.end_date === null) {
				return '';
			} else if (today >= endDate) {
				return 'employee-retired';
			} else {
				return '';
			}
		},

		goToCreateDriver() {
			this.$router.push({ name: 'ListDriverCreate' });
		},

		goToDetail(scope) {
			this.$router.push({ name: 'ListDriverDetail', params: { id: scope.id }});
		},

		async handleDelete(scope) {
			this.driverHandle = scope;

			this.modalDelete = true;
		},

		async handleOk() {
			try {
				setLoading(true);

				const DELETE_DRIVER = await deleteDriver(`${CONSTANT.URL_API.DELETE_DRIVER}/${this.driverHandle.id}`);

				this.modalDelete = false;

				if (DELETE_DRIVER.code === 200) {
					TOAST_DRIVER.delete();
					await this.handleGetList();
				}

				setLoading(false);
			} catch (error) {
				this.modalDelete = false;
				setLoading(false);
			}
		},

		resetModal() {
			this.driverHandle = {
				id: null,
				driver_code: '',
				driver_name: '',
				status: '',
				start_date: '',
				end_date: null,
				created_at: null,
				updated_at: null,
			};
		},

		handleSort(ctx) {
			this.sortTable = {
				sortBy: ctx.sortBy,
				sortType: ctx.sortDesc ? 'asc' : 'desc',
			};
		},
	},
};
</script>

<style lang="scss" scoped>
    @import '@/scss/variables';

    .list-driver {
        &__header {
            .zone-title {
                .title-page {
                    font-size: 25px;
                }
            }

            .zone-btn-sign-up {
                .btn-sign-up {
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

        &__body {
            ::v-deep .zone-table {
                height: calc(100vh - 170px);
                overflow-y: auto;

                th {
                    position: sticky !important;
                    top: 0;
                }

                .th-employee-number {
                    width: 150px;
                }

                .th-enrollment {
                    width: 200px;
                }

                .base-th {
                    background-color: $main;
                    color: $white;
                }

                .base-th-control {
                    width: 130px;
                }

                .base-td-control {
                    i {
                        color: $dusty-gray;
                        font-size: 24;

                        cursor: pointer;
                    }
                }

                .employee-retired {
                    background-color: $swiss-coffee;
                }

                td {
                    vertical-align: middle;
                }
            }
        }
    }
</style>
