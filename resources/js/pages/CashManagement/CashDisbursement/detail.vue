<template>
    <b-col>
        <b-container>
            <div class="page-cashDisbursement-detail">
                <div class="page-cashDisbursement-detail__header">
                    <b-row>
                        <b-col>
                            <div class="zone-title">
                                <span class="title-page">
                                    {{ $t('LIST_CASH.TITLE_CASH_DISBURSEMENT_DETAIL') }}
                                </span>
                            </div>
                        </b-col>
                    </b-row>
                    <LineGray />
                </div>
                <div class="page-cashDisbursement-detail__body">
                    <div class="body-control">
                        <b-row>
                            <b-col>
                                <div class="zone-control text-right">
                                    <b-button
                                        pill
                                        class="btn-return"
                                        @click="onClickReturn()"
                                    >
                                        {{ $t('APP.BUTTON_RETURN') }}
                                    </b-button>
                                    <b-button
                                        pill
                                        class="btn-edit btn-color-active"
                                        @click="onClickCreate()"
                                    >
                                        {{ $t('APP.BUTTON_EDIT') }}
                                    </b-button>
                                </div>
                            </b-col>
                        </b-row>
                    </div>
                    <div class="body-form">
                        <b-row>
                            <b-col
                                :cols="12"
                                :sm="12"
                                :md="12"
                                :lg="4"
                                :xl="4"
                            >
                                <div class="zone-avatar">
                                    <img :src="require('@/assets/images/Cash_icon.png')" alt="Avatar course">
                                </div>
                            </b-col>
                            <b-col
                                :cols="12"
                                :sm="12"
                                :md="12"
                                :lg="8"
                                :xl="8"
                            >
                                <div class="zone-form">
                                    <div class="zone-form__header">
                                        <TitlePathForm>
                                            {{ $t('LIST_CASH.FORM_CASH_DISBURSEMENT_BASIC_INFORMATION') }}
                                        </TitlePathForm>
                                    </div>
                                    <div class="zone-form__body">
                                        <b-row>
                                            <b-col>
                                                <div class="item-form">
                                                    <DetailForm
                                                        :label="$t('LIST_CASH.TABLE_CASH_DISBURSEMENT_ID')"
                                                        :value="isForm.course_id"
                                                    />
                                                </div>
                                            </b-col>
                                        </b-row>

                                        <b-row>
                                            <b-col>
                                                <div class="item-form">
                                                    <DetailForm
                                                        :label="$t('LIST_CASH.TABLE_CASH_DISBURSEMENT_NAME')"
                                                        :value="isForm.course_name"
                                                    />
                                                </div>
                                            </b-col>
                                        </b-row>

                                        <b-row>
                                            <b-col>
                                                <div class="item-form">
                                                    <DetailForm
                                                        :label="$t('LIST_CASH.TABLE_CASH_DISBURSEMENT_CURRENT_MONTH_BALANCE')"
                                                        :value="isForm.course_current_month_balance"
                                                    />
                                                </div>
                                            </b-col>
                                        </b-row>
                                        <b-row>
                                            <b-col>
                                                <div class="item-form">
                                                    <DetailForm
                                                        :label="$t('LIST_CASH.TABLE_CASH_DISBURSEMENT_BALANCE_AT_END_OF_PREVIOUS_MONTH')"
                                                        :value="isForm.course_balance_at_end_of_previous_month"
                                                    />
                                                </div>
                                            </b-col>
                                        </b-row>
                                        <b-row>
                                            <b-col>
                                                <div class="item-form">
                                                    <DetailForm
                                                        :label="$t('LIST_CASH.TABLE_CASH_DISBURSEMENT_ACCOUNTS_RECEIVABLE')"
                                                        :value="isForm.course_accounts_receivable"
                                                    />
                                                </div>
                                            </b-col>
                                        </b-row>
                                        <b-row>
                                            <b-col>
                                                <div class="item-form">
                                                    <DetailForm
                                                        :label="$t('LIST_CASH.TABLE_CASH_DISBURSEMENT_TOTAL_ACCOUNTS_RECEIVABLE')"
                                                        :value="isForm.course_total_account_receivable"
                                                    />
                                                </div>
                                            </b-col>
                                        </b-row>
                                    </div>
                                    <div class="zone-form__footer">
                                        <TitlePathForm>
                                            {{ $t('LIST_CASH.CASH_DISBURSEMENT_DEPOSIT_INFORMATION') }}
                                        </TitlePathForm>
                                    </div>
                                </div>
                            </b-col>
                            <b-col
                                :cols="12"
                                :sm="12"
                                :md="12"
                                :lg="8"
                                :xl="8"
                                class="ml-auto"
                            >
                                <div class="table_detail_cash">
                                    <b-table-simple>
                                        <b-thead>
                                            <b-tr>
                                                <b-th
                                                    class="th-sort"
                                                    :colspan="1"
                                                >
                                                    {{ $t('LIST_CASH.TABLE_CASH_DISBURSEMENT_NO') }}
                                                </b-th>
                                                <b-th
                                                    class="th-sort"
                                                    :colspan="2"
                                                >
                                                    {{ $t('LIST_CASH.TABLE_CASH_DISBURSEMENT_DATE') }}
                                                </b-th>
                                                <b-th
                                                    class="th-sort"
                                                    :colspan="2"
                                                >
                                                    {{ $t('LIST_CASH.TABLE_CASH_DISBURSEMENT_DEPOSIT_AMOUNT') }}
                                                </b-th>
                                                <b-th
                                                    class="th-sort"
                                                    :colspan="2"
                                                >
                                                    {{ $t('LIST_CASH.TABLE_CASH_DISBURSEMENT_PAYMENT_METHOD') }}
                                                </b-th>
                                                <b-th
                                                    class="th-remarks"
                                                    :colspan="3"
                                                >
                                                    {{ $t('LIST_CASH.TABLE_CASH_DISBURSEMENT_REMARKS') }}
                                                </b-th>
                                                <b-th
                                                    class="th-sort"
                                                    :colspan="1"
                                                >
                                                    {{ $t('LIST_CASH.TABLE_EDIT') }}
                                                </b-th>
                                                <b-th
                                                    class="th-sort"
                                                    :colspan="1"
                                                >
                                                    {{ $t('LIST_CASH.TABLE_DELETE') }}
                                                </b-th>
                                            </b-tr>
                                        </b-thead>
                                        <b-tbody>
                                            <template v-for="(course, idx) in listCashDeital">
                                                <b-tr :key="`item-cash-${idx + 1}`">
                                                    <b-td class="td-cash-id" :colspan="1">
                                                        {{ course.no }}
                                                    </b-td>
                                                    <b-td class="td-cash-name" :colspan="2">
                                                        {{ course.date }}
                                                    </b-td>
                                                    <b-td class="td-cash-balance" :colspan="2">
                                                        {{ course.deposit_amount }}
                                                    </b-td>
                                                    <b-td class="td-cash-account-receiable" :colspan="2">
                                                        {{ course.payment_method }}
                                                    </b-td>
                                                    <b-td class="td-cash-total-account-receiable" :colspan="3">
                                                        {{ course.remarks }}
                                                    </b-td>
                                                    <b-td class="td-cash-edit td-control" :colspan="1">
                                                        <i class="fas fa-pen" @click="onClickEdit(course.id)" />
                                                    </b-td>
                                                    <b-td class="td-cash-delete td-control" :colspan="1">
                                                        <i
                                                            class="fas fa-trash-alt"
                                                            @click="onClickShowModalDelete()"
                                                        />
                                                    </b-td>
                                                </b-tr>
                                            </template>
                                            <b-tr>
                                                <b-th class="total-cash" :colspan="4">
                                                    {{ $t('LIST_CASH.TABLE_CASH_DISBURSEMENT_TOTAL') }}
                                                </b-th>
                                                <b-td>
                                                    {{ isForm.total }}
                                                </b-td>
                                            </b-tr>
                                        </b-tbody>
                                    </b-table-simple>
                                </div>
                            </b-col>
                        </b-row>
                    </div>
                </div>
                <b-modal
                    id="modal-delete"
                    v-model="showModalDelete"
                    body-class="modal-delete"
                    hide-header
                    hide-footer
                    no-close-on-esc
                    no-close-on-backdrop
                    static
                    @close="handleCloseModalDelete()"
                >
                    <div class="text-center">
                        <h5 class="font-weight-bolde">
                            {{ $t('LIST_CASH.MESSAGE_DELETE') }}
                        </h5>
                    </div>
                    <div class="text-center">
                        <b-button
                            pill
                            @click="handleCloseModalDelete()"
                        >
                            キャンセル
                        </b-button>
                        <b-button
                            pill
                            class="mr-2 btn-color-active-import"
                        >
                            OK
                        </b-button>
                    </div>
                </b-modal>
            </div>
        </b-container>
    </b-col>
</template>
<script>
import LineGray from '@/components/LineGray';
import DetailForm from '@/components/DetailForm';
import TitlePathForm from '@/components/TitlePathForm';
export default {
	name: 'CashDetail',
	components: {
		LineGray,
		TitlePathForm,
		DetailForm,
	},

	data() {
		return {
			idCash: null,
			showModalDelete: false,
			// idCashOut: null,
			isForm: {
				course_id: '',
				course_name: '',
				course_current_month_balance: '',
				course_balance_at_end_of_previous_month: '',
				course_accounts_receivable: '',
				course_total_account_receivable: '',
				total: '122.55',
			},

			listCashDeital: [
				{
					no: 1,
					id: 1,
					date: '22/1/2019',
					deposit_amount: '122,33',
					payment_method: '233,22',
					remarks: '331,31',
				},
				{
					no: 2,
					id: 2,
					date: '15/3/2020',
					deposit_amount: '155,33',
					payment_method: '144,22',
					remarks: '331,31',
				},
			],
		};
	},

	methods: {
		onClickReturn() {
			this.$router.push({ name: 'ListCashDisbursement' });
		},

		onClickCreate() {
			this.$router.push({ name: 'ListCashDisbursementCreate', params: { id: this.idCash }});
		},

		onClickEdit(idCashOut) {
			this.$router.push({ name: 'ListCashDisbursementEdit', params: { id: idCashOut }});
		},

		handleCloseModalDelete() {
			this.showModalDelete = false;
		},

		onClickShowModalDelete() {
			this.showModalDelete = true;
		},
	},
};
</script>
<style lang="scss" scoped>
    @import '@/scss/variables';

    .page-cashDisbursement-detail {
        &__body {
            .body-form {
                border: 1px solid $geyser;
                margin-top: 10px;
                padding: 20px;

                .zone-avatar {
                    height: 100%;

                    display: flex;
                    justify-content: center;
                    align-items: center;
                    vertical-align: middle;

                    img {
                        height: 270px;
                    }
                }

                .zone-form {
                    &__body {
                        .item-form {
                            margin: 20px 0;
                            font-size: 18px;
                        }
                    }
                    .zone-form__footer {
                        margin-top: 40px;
                    }
                }
                .table_detail_cash {
                    margin-top: 40px;
                    th {
                        border: 1px solid rgb(211, 211, 211);
                        // border-left: 1px solid gray;
                    }
                    td {
                        border: 1px solid rgb(211, 211, 211);
                        // border-left: 1px solid gray;
                    }
                    .th-remarks {
                        min-width: 200px;
                        text-align: center;
                        background-color: #FFF4E4;
                    }
                    .th-sort {
                        text-align: center;
                        background-color: #FFF4E4;
                    }
                    .total-cash {
                        text-align: center;
                    }
                    td.td-control {
                        text-align: center;
                        i {
                            color: $dusty-gray;
                            font-size: 18px;
                            cursor: pointer;
                        }
                    }
                }
            }
        }
        .font-weight-bolde{
            margin: 30px 0;
        }
    }
</style>
