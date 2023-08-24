<template>
    <b-col>
        <b-container>
            <div class="page-list-cashCiept">
                <div class="page-list-cashCiept_header">
                    <b-row>
                        <b-col>
                            <div class="zone-title text-left">
                                <span class="title-page">
                                    {{ $t('LIST_CASH.TITLE_LIST_CASH') }}
                                </span>
                            </div>
                        </b-col>
                    </b-row>
                    <LineGray />
                </div>
                <div
                    class="zone-right"
                >
                    <div class="item-function btn-excel" @click="onClickExport()">
                        <div class="show-icon">
                            <i class="fas fa-file-excel" />
                        </div>
                        <div class="show-text">
                            <span>{{ $t("LIST_SHIFT.BUTTON_DOWNLOAD_EXCEL") }}</span>
                        </div>
                    </div>
                </div>
                <div class="page-list-cashCiept__body">
                    <div class="zone-table">
                        <b-table-simple
                            bordered
                            no-border-collapse
                            class="table-cash-in"
                        >
                            <b-thead>
                                <b-tr>
                                    <b-th
                                        class="th-sort th-id th-course-id"
                                        :rowspan="2"
                                        @click="onSortTable('customer_code')"
                                    >
                                        <div class="row-cashCiept-id th-col">
                                            <span>
                                                {{ $t('LIST_CASH.TABLE_CASH_ID') }}
                                            </span>
                                            <div class="icon-sorts text-right">
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
                                        </div>
                                    </b-th>
                                    <b-th
                                        class="th-sort th-name th-course-name"
                                        :colspan="3"
                                        @click="onSortTable('customer_name')"
                                    >
                                        <div class="th-col">
                                            <span>
                                                {{ $t('LIST_CASH.TABLE_CASH_NAME') }}
                                            </span>
                                            <div class="icon-sorts text-right">
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
                                        </div>
                                    </b-th>
                                    <b-th
                                        class="th-sort th-name"
                                        :colspan="3"
                                        @click="onSortTable('balance_previous_month')"
                                    >
                                        <div class="th-col">
                                            <span>
                                                {{ $t('LIST_CASH.TABLE_CASH_BALANCE_AT_END_OF_PREVIOUS_MONTH') }}
                                            </span>
                                            <div class="icon-sorts text-right">
                                                <i
                                                    v-if="sortTable.sortBy === 'balance_previous_month' && sortTable.sortType === true"
                                                    class="fad fa-sort-up icon-sort"
                                                />
                                                <i
                                                    v-else-if="sortTable.sortBy === 'balance_previous_month' && sortTable.sortType === false"
                                                    class="fad fa-sort-down icon-sort"
                                                />
                                                <i
                                                    v-else
                                                    class="fa-solid fa-sort icon-sort-default"
                                                />
                                            </div>
                                        </div>
                                    </b-th>
                                    <b-th
                                        class="th-sort th-name"
                                        :colspan="3"
                                        @click="onSortTable('receivable_this_month')"
                                    >
                                        <div class="th-col">
                                            <span>
                                                {{ $t('LIST_CASH.TABLE_CASH_ACCOUNTS_RECEIVABLE') }}
                                            </span>
                                            <div class="icon-sorts text-right">
                                                <i
                                                    v-if="sortTable.sortBy === 'receivable_this_month' && sortTable.sortType === true"
                                                    class="fad fa-sort-up icon-sort"
                                                />
                                                <i
                                                    v-else-if="sortTable.sortBy === 'receivable_this_month' && sortTable.sortType === false"
                                                    class="fad fa-sort-down icon-sort"
                                                />
                                                <i
                                                    v-else
                                                    class="fa-solid fa-sort icon-sort-default"
                                                />
                                            </div>
                                        </div>
                                    </b-th>
                                    <b-th
                                        class="th-sort th-name"
                                        :colspan="3"
                                        @click="onSortTable('total_account_receivable')"
                                    >
                                        <div class="th-col">
                                            <span>
                                                {{ $t('LIST_CASH.TABLE_TOTAL_ACCOUNTS_RECEIVABLE') }}
                                            </span>
                                            <div class="icon-sorts text-right">
                                                <i
                                                    v-if="sortTable.sortBy === 'total_account_receivable' && sortTable.sortType === true"
                                                    class="fad fa-sort-up icon-sort"
                                                />
                                                <i
                                                    v-else-if="sortTable.sortBy === 'total_account_receivable' && sortTable.sortType === false"
                                                    class="fad fa-sort-down icon-sort"
                                                />
                                                <i
                                                    v-else
                                                    class="fa-solid fa-sort icon-sort-default"
                                                />
                                            </div>
                                        </div>
                                    </b-th>
                                    <b-th
                                        class="th-sort th-name"
                                        :colspan="3"
                                        @click="onSortTable('total_cash_in_of_current_month')"
                                    >
                                        <div class="th-col">
                                            <span>
                                                {{ $t('LIST_CASH.TABLE_MONTHLY_DEPOSIT_AMOUNT') }}
                                            </span>
                                            <div class="icon-sorts text-right">
                                                <i
                                                    v-if="sortTable.sortBy === 'total_cash_in_of_current_month' && sortTable.sortType === true"
                                                    class="fad fa-sort-up icon-sort"
                                                />
                                                <i
                                                    v-else-if="sortTable.sortBy === 'total_cash_in_of_current_month' && sortTable.sortType === false"
                                                    class="fad fa-sort-down icon-sort"
                                                />
                                                <i
                                                    v-else
                                                    class="fa-solid fa-sort icon-sort-default"
                                                />
                                            </div>
                                        </div>
                                    </b-th>
                                    <b-th
                                        class="th-sort th-name"
                                        :colspan="3"
                                        @click="onSortTable('total_cash_in_current')"
                                    >
                                        <div class="th-col">
                                            <span>
                                                {{ $t('LIST_CASH.TABLE_CURRENT_MONTH_BALANCE') }}
                                            </span>
                                            <div class="icon-sorts text-right">
                                                <i
                                                    v-if="sortTable.sortBy === 'total_cash_in_current' && sortTable.sortType === true"
                                                    class="fad fa-sort-up icon-sort"
                                                />
                                                <i
                                                    v-else-if="sortTable.sortBy === 'total_cash_in_current' && sortTable.sortType === false"
                                                    class="fad fa-sort-down icon-sort"
                                                />
                                                <i
                                                    v-else
                                                    class="fa-solid fa-sort icon-sort-default"
                                                />
                                            </div>
                                        </div>
                                    </b-th>
                                    <b-th
                                        class="th-sort th-name th-detail"
                                        :colspan="2"
                                    >
                                        <div class="th-col">
                                            <span>
                                                {{ $t('LIST_CASH.TABLE_DETAIL') }}
                                            </span>
                                        </div>
                                    </b-th>
                                </b-tr>
                            </b-thead>
                            <b-tbody>
                                <template v-for="(course, idx) in listCash">
                                    <b-tr :key="`item-cash-${idx + 1}`">
                                        <b-td class="td-cash-id">
                                            {{ course.customer_code }}
                                        </b-td>
                                        <b-td class="td-cash-name" :colspan="3">
                                            {{ course.customer_name }}
                                        </b-td>
                                        <b-td class="td-cash-balance" :colspan="3">
                                            {{ Number(course.balance_previous_month) }}
                                        </b-td>
                                        <b-td class="td-cash-account-receiable" :colspan="3">
                                            {{ Number(course.receivable_this_month) }}
                                        </b-td>
                                        <b-td class="td-cash-total-account-receiable" :colspan="3">
                                            {{ Number(course.total_account_receivable) }}
                                        </b-td>
                                        <b-td class="td-cash-month-deposit-amount" :colspan="3">
                                            {{ Number(course.total_cash_in_of_current_month) }}
                                        </b-td>
                                        <b-td class="td-cash-current-month-balance" :colspan="3">
                                            {{ Number(course.total_cash_in_current) }}
                                        </b-td>
                                        <b-td class="text-center td-control" :colspan="2">
                                            <i
                                                class="fas fa-eye"
                                                @click="onClickDetail(course.customer_id)"
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
    </b-col>
</template>
<script>
import LineGray from '@/components/LineGray';
import { format2Digit } from '@/utils/generateTime';
import { getCashReciept } from '@/api/modules/cashDisbursement';
import { cleanObject } from '@/utils/handleObject';
import { setLoading } from '@/utils/handleLoading';
import { getToken } from '@/utils/handleToken';
import CONSTANT from '@/const';
import TOAST_CASH_MANAGEMENT from '@/toast/modules/cashManagement';
import axios from 'axios';

export default {
	name: 'ListCash',
	components: {
		LineGray,
	},

	data() {
		return {
			sortTable: {
				sortBy: '',
				sortType: null,
			},

			listCash: [
				{
					id: 1,
					name: '荷主名',
					balance: '200,000',
					account_receivable: '300,000',
					total_account_receivable: '100',
					monthly_deposit_amount: '150,00',
					current_month_balance: '110,00',
				},
				{
					id: 2,
					name: '荷主名',
					balance: '222,022',
					account_receivable: '333,00',
					total_account_receivable: '111,00',
					monthly_deposit_amount: '222,22',
					current_month_balance: '123,11',
				},
				{
					id: 3,
					name: '荷主名',
					balance: '666,12',
					account_receivable: '122,22',
					total_account_receivable: '144,33',
					monthly_deposit_amount: '155,55',
					current_month_balance: '123,12',
				},
				{
					id: 4,
					name: '荷主名',
					balance: '321,21',
					account_receivable: '522,12',
					total_account_receivable: '632,32',
					monthly_deposit_amount: '123,32',
					current_month_balance: '155,55',
				},
			],
		};
	},

	computed: {
		pickerYearMonth() {
			return this.$store.getters.pickerYearMonth;
		},
	},

	watch: {
		sortTable: {
			handler: function() {
				this.handleGetCashReciept();
			},

			deep: true,
		},
	},

	created() {
		this.initData();
	},

	methods: {
		onClickDetail(scopeId) {
			this.$router.push({ name: 'ListCashReceiptDetail', params: { id: scopeId }});
		},

		initData() {
			this.handleGetCashReciept();
		},

		async handleGetCashReciept() {
			try {
				setLoading(true);
				let PARAMS = {
					field: this.sortTable.sortBy,
					sortby: this.sortTable.sortType,
				};
				if (PARAMS.field) {
					PARAMS.sortby = PARAMS.sortby ? 'desc' : 'asc';
				}
				const YEAR = this.pickerYearMonth.year;
				const MONTH = this.pickerYearMonth.month;

				const YEAR_MONTH = `${YEAR}-${format2Digit(MONTH)}`;
				PARAMS.month_year = YEAR_MONTH;
				PARAMS = cleanObject(PARAMS);
				const URL = CONSTANT.URL_API.GET_LIST_CASH_RECIEPT;
				const response = await getCashReciept(URL, PARAMS);
				if (response.code === 200) {
					this.listCash = response.data;
				} else {
					this.listCash = [];
				}
				setLoading(false);
			} catch (error) {
				console.log(error);
			}
		},

		async onClickExport() {
			try {
				setLoading(true);
				let params = {
					field: this.sortTable.sortBy,
					sortby: this.sortTable.sortType,
				};
				const YEAR = this.pickerYearMonth.year;
				const MONTH = this.pickerYearMonth.month;
				const YEAR_MONTH = `${YEAR}-${format2Digit(MONTH)}`;
				params.month_year = YEAR_MONTH;
				params = cleanObject(params);
				const URL = `/api${CONSTANT.URL_API.EXPORT_EXCEL_CASH_RECIEPT}`;
				await axios.get(URL, {
					params: params,
					responseType: 'blob',
					headers: {
						'Accept-Language': this.$store.getters.language,
						'Authorization': getToken(),
						'accept': 'application/json',
					},
				}).then((response) => {
					const url = window.URL.createObjectURL(new Blob([response.data]));
					const link = document.createElement('a');
					link.href = url;
					link.setAttribute('download', 'download.xlsx');
					document.body.appendChild(link);
					link.click();
				}).catch((error) => {
					TOAST_CASH_MANAGEMENT.warning(error.massage);
				});
				setLoading(false);
			} catch (error) {
				console.log(error);
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
				case 'balance_previous_month':
					if (this.sortTable.sortBy === 'balance_previous_month') {
						if (this.sortTable.sortType) {
							this.sortTable.sortType = !this.sortTable.sortType;
						} else {
							this.sortTable.sortType = true;
						}
					} else {
						this.sortTable.sortBy = 'balance_previous_month';
						this.sortTable.sortType = true;
					}
					break;
				case 'receivable_this_month':
					if (this.sortTable.sortBy === 'receivable_this_month') {
						if (this.sortTable.sortType) {
							this.sortTable.sortType = !this.sortTable.sortType;
						} else {
							this.sortTable.sortType = true;
						}
					} else {
						this.sortTable.sortBy = 'receivable_this_month';
						this.sortTable.sortType = true;
					}
					break;
				case 'total_account_receivable':
					if (this.sortTable.sortBy === 'total_account_receivable') {
						if (this.sortTable.sortType) {
							this.sortTable.sortType = !this.sortTable.sortType;
						} else {
							this.sortTable.sortType = true;
						}
					} else {
						this.sortTable.sortBy = 'total_account_receivable';
						this.sortTable.sortType = true;
					}
					break;
				case 'total_cash_in_of_current_month':
					if (this.sortTable.sortBy === 'total_cash_in_of_current_month') {
						if (this.sortTable.sortType) {
							this.sortTable.sortType = !this.sortTable.sortType;
						} else {
							this.sortTable.sortType = true;
						}
					} else {
						this.sortTable.sortBy = 'total_cash_in_of_current_month';
						this.sortTable.sortType = true;
					}
					break;
				case 'total_cash_in_current':
					if (this.sortTable.sortBy === 'total_cash_in_current') {
						if (this.sortTable.sortType) {
							this.sortTable.sortType = !this.sortTable.sortType;
						} else {
							this.sortTable.sortType = true;
						}
					} else {
						this.sortTable.sortBy = 'total_cash_in_current';
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

    .page-list-cashCiept {
        .zone-right {
            display: flex;
            justify-content: flex-end;
            .item-function {
                padding: 10px 20px;
                justify-content: flex-end;

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

        .page-list-cashCiept__body {
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

                                .th-col {
                                    display: flex;
                                    align-items: center;
                                    justify-content: space-between;

                                    span {
                                        white-space: nowrap;
                                    }
                                }

                                th.th-sort {
                                    cursor: pointer;

                                    .icon-sorts {
                                        margin-left: 15px;

                                        i.icon-sort-default {
                                            color: $white;
                                            opacity: 0.7;
                                        }
                                    }
                                }

                                th.th-time {
                                    width: 100px;
                                }

                                th.th-control {
                                    width: 15px;
                                }

                                th.th-detail {
                                    min-width: 30px;
                                }

                                th.th-course-name {
                                    min-width: 250px;
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
