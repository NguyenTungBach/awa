<template>
    <b-col>
        <b-container>
            <div class="page-list-cashDisbursement">
                <div class="page-list-cashDisbursement_header">
                    <b-row>
                        <b-col>
                            <div class="zone-title text-left">
                                <span class="title-page">
                                    {{ $t('LIST_CASH.TITLE_LIST_CASH_DISBURSEMENT') }}
                                </span>
                            </div>
                        </b-col>
                    </b-row>
                    <LineGray />
                </div>
                <div class="page-list-cashDisbursement__body">
                    <div class="zone-table">
                        <b-table-simple
                            bordered
                            no-border-collapse
                        >
                            <b-thead>
                                <b-tr>
                                    <b-th
                                        class="th-sort th-id th-course-id"
                                        :rowspan="2"
                                    >
                                        <b-row class="row-cashDisbursement-id">
                                            <b-col>
                                                {{ $t('LIST_CASH.TABLE_CASH_DISBURSEMENT_ID') }}
                                            </b-col>
                                            <b-col class="icon-sorts">
                                                <div class="text-right">
                                                    <i
                                                        v-if="sortTable.sortBy === 'cash_code' && sortTable.sortType === true"
                                                        class="fad fa-sort-up icon-sort"
                                                    />
                                                    <i
                                                        v-else-if="sortTable.sortBy === 'cash_code' && sortTable.sortType === false"
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
                                    >
                                        <b-row>
                                            <b-col>
                                                {{ $t('LIST_CASH.TABLE_CASH_DISBURSEMENT_NAME') }}
                                            </b-col>
                                            <b-col>
                                                <div class="text-right">
                                                    <i
                                                        v-if="sortTable.sortBy === 'cash_name' && sortTable.sortType === true"
                                                        class="fad fa-sort-up icon-sort"
                                                    />
                                                    <i
                                                        v-else-if="sortTable.sortBy === 'cash_name' && sortTable.sortType === false"
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
                                    >
                                        <b-row>
                                            <b-col>
                                                {{ $t('LIST_CASH.TABLE_CASH_DISBURSEMENT_BALANCE_AT_END_OF_PREVIOUS_MONTH') }}
                                            </b-col>
                                            <b-col>
                                                <div class="text-right">
                                                    <i
                                                        v-if="sortTable.sortBy === 'cash_banlance' && sortTable.sortType === true"
                                                        class="fad fa-sort-up icon-sort"
                                                    />
                                                    <i
                                                        v-else-if="sortTable.sortBy === 'cash_banlance' && sortTable.sortType === false"
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
                                    >
                                        <b-row>
                                            <b-col>
                                                {{ $t('LIST_CASH.TABLE_CASH_DISBURSEMENT_ACCOUNTS_RECEIVABLE') }}
                                            </b-col>
                                            <b-col>
                                                <div class="text-right">
                                                    <i
                                                        v-if="sortTable.sortBy === 'cash_accounts_receivable' && sortTable.sortType === true"
                                                        class="fad fa-sort-up icon-sort"
                                                    />
                                                    <i
                                                        v-else-if="sortTable.sortBy === 'cash_accounts_receivable' && sortTable.sortType === false"
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
                                    >
                                        <b-row>
                                            <b-col>
                                                {{ $t('LIST_CASH.TABLE_CASH_DISBURSEMENT_TOTAL_ACCOUNTS_RECEIVABLE') }}
                                            </b-col>
                                            <b-col>
                                                <div class="text-right">
                                                    <i
                                                        v-if="sortTable.sortBy === 'cash_total_accounts_receivable' && sortTable.sortType === true"
                                                        class="fad fa-sort-up icon-sort"
                                                    />
                                                    <i
                                                        v-else-if="sortTable.sortBy === 'cash_total_accounts_receivable' && sortTable.sortType === false"
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
                                    >
                                        <b-row>
                                            <b-col>
                                                {{ $t('LIST_CASH.TABLE_CASH_DISBURSEMENT_MONTHLY_DEPOSIT_AMOUNT') }}
                                            </b-col>
                                            <b-col>
                                                <div class="text-right">
                                                    <i
                                                        v-if="sortTable.sortBy === 'cash_monthly_deposit_amount' && sortTable.sortType === true"
                                                        class="fad fa-sort-up icon-sort"
                                                    />
                                                    <i
                                                        v-else-if="sortTable.sortBy === 'cash_monthly_deposit_amount' && sortTable.sortType === false"
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
                                    >
                                        <b-row>
                                            <b-col>
                                                {{ $t('LIST_CASH.TABLE_CASH_DISBURSEMENT_CURRENT_MONTH_BALANCE') }}
                                            </b-col>
                                            <b-col>
                                                <div class="text-right">
                                                    <i
                                                        v-if="sortTable.sortBy === 'cash_current_month_balance' && sortTable.sortType === true"
                                                        class="fad fa-sort-up icon-sort"
                                                    />
                                                    <i
                                                        v-else-if="sortTable.sortBy === 'cash_current_month_balance' && sortTable.sortType === false"
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
                                        :colspan="2"
                                    >
                                        <b-row>
                                            <b-col>
                                                {{ $t('LIST_CASH.TABLE_DETAIL') }}
                                            </b-col>
                                        </b-row>
                                    </b-th>
                                </b-tr>
                            </b-thead>
                            <b-tbody>
                                <template v-for="(course, idx) in listCash">
                                    <b-tr :key="`item-cash-${idx + 1}`">
                                        <b-td class="td-cash-id">
                                            {{ course.id }}
                                        </b-td>
                                        <b-td class="td-cash-name" :colspan="3">
                                            {{ course.name }}
                                        </b-td>
                                        <b-td class="td-cash-balance" :colspan="3">
                                            {{ course.balance }}
                                        </b-td>
                                        <b-td class="td-cash-account-receiable" :colspan="3">
                                            {{ course.account_receivable }}
                                        </b-td>
                                        <b-td class="td-cash-total-account-receiable" :colspan="3">
                                            {{ course.total_account_receivable }}
                                        </b-td>
                                        <b-td class="td-cash-month-deposit-amount" :colspan="3">
                                            {{ course.monthly_deposit_amount }}
                                        </b-td>
                                        <b-td class="td-cash-current-month-balance" :colspan="3">
                                            {{ course.current_month_balance }}
                                        </b-td>
                                        <b-td class="text-center td-control" :colspan="2">
                                            <i
                                                class="fas fa-eye"
                                                @click="onClickDetail(course.id)"
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
export default {
	name: 'ListCashDisbursement',
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

	methods: {
		onClickDetail(scope) {
			this.$router.push({
				name: 'ListCashDisbursementDetail',
				param: {
					id: scope.id,
				},
			});
		},
	},
};
</script>
<style lang="scss" scoped>
    @import '@/scss/variables';

    .page-list-cashDisbursement {

        .page-list-cashDisbursement__body {
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

                                th.th-sort {
                                    cursor: pointer;

                                    i.icon-sort-default {
                                        color: $white;
                                        opacity: 0.7;
                                    }
                                }

                                th.th-id {
                                    min-width: 100px;
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
