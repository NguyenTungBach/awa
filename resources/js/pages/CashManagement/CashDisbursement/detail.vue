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
                                        {{ $t('APP.BUTTON_CREATE') }}
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
                                                        :value="isForm.driver_code"
                                                    />
                                                </div>
                                            </b-col>
                                        </b-row>

                                        <b-row>
                                            <b-col>
                                                <div class="item-form">
                                                    <DetailForm
                                                        :label="$t('LIST_CASH.TABLE_CASH_DISBURSEMENT_NAME')"
                                                        :value="isForm.driver_name"
                                                    />
                                                </div>
                                            </b-col>
                                        </b-row>

                                        <b-row>
                                            <b-col>
                                                <div class="item-form">
                                                    <DetailForm
                                                        :label="$t('LIST_CASH.TABLE_CASH_DISBURSEMENT_CURRENT_MONTH_BALANCE')"
                                                        :value="isForm.balance_current"
                                                    />
                                                </div>
                                            </b-col>
                                        </b-row>
                                        <b-row>
                                            <b-col>
                                                <div class="item-form">
                                                    <DetailForm
                                                        :label="$t('LIST_CASH.TABLE_CASH_DISBURSEMENT_BALANCE_AT_END_OF_PREVIOUS_MONTH')"
                                                        :value="isForm.balance_previous_month"
                                                    />
                                                </div>
                                            </b-col>
                                        </b-row>
                                        <b-row>
                                            <b-col>
                                                <div class="item-form">
                                                    <DetailForm
                                                        :label="$t('LIST_CASH.TABLE_CASH_DISBURSEMENT_ACCOUNTS_RECEIVABLE')"
                                                        :value="isForm.payable_this_month"
                                                    />
                                                </div>
                                            </b-col>
                                        </b-row>
                                        <b-row>
                                            <b-col>
                                                <div class="item-form">
                                                    <DetailForm
                                                        :label="$t('LIST_CASH.TABLE_CASH_DISBURSEMENT_TOTAL_ACCOUNTS_RECEIVABLE')"
                                                        :value="isForm.total_payable"
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
                                                        {{ idx + 1 }}
                                                    </b-td>
                                                    <b-td class="td-cash-name" :colspan="2">
                                                        {{ course.payment_date }}
                                                    </b-td>
                                                    <b-td class="td-cash-balance" :colspan="2">
                                                        {{ Number(course.cash_out) }}
                                                    </b-td>
                                                    <b-td class="td-cash-account-receiable" :colspan="2">
                                                        {{ course.payment_method }}
                                                    </b-td>
                                                    <b-td class="td-cash-total-account-receiable" :colspan="3">
                                                        {{ course.note }}
                                                    </b-td>
                                                    <b-td class="td-cash-edit td-control" :colspan="1">
                                                        <i class="fas fa-pen" @click="onClickEdit(course.id)" />
                                                    </b-td>
                                                    <b-td class="td-cash-delete td-control" :colspan="1">
                                                        <i
                                                            class="fas fa-trash-alt"
                                                            @click="onClickShowModalDelete(course.id)"
                                                        />
                                                    </b-td>
                                                </b-tr>
                                            </template>
                                            <b-tr>
                                                <b-th class="total-cash" :colspan="4">
                                                    {{ $t('LIST_CASH.TABLE_CASH_DISBURSEMENT_TOTAL') }}
                                                </b-th>
                                                <b-td class="total-cash-out">
                                                    {{ totalCashOut }}
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
                            @click="handleOK()"
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
import CONSTANT from '@/const';
import { setLoading } from '@/utils/handleLoading';
import { getDetailDisbursement, getListCashOut, deleteCashOut } from '@/api/modules/cashDisbursement';
// import { cleanObject } from '@/utils/handleObject';
import TOAST_CASH_MANAGEMENT from '@/toast/modules/cashManagement';
import { format2Digit } from '@/utils/generateTime';
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
				driver_code: '',
				driver_name: '',
				balance_current: '',
				balance_previous_month: '',
				payable_this_month: '',
				total_payable: '',
			},

			totalCashOut: '',
			scopeID: '',

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

	computed: {
		pickerYearMonth() {
			return this.$store.getters.pickerYearMonth;
		},
	},

	watch: {
		pickerYearMonth() {
			this.initData();
		},
	},

	created() {
		this.initData();
	},

	methods: {
		async initData() {
			this.idCash = this.$route.params.id || null;
			setLoading(true);
			await this.handleGetlistCashOut();
			await this.handleGetDetailDisbursement();
			setLoading(false);
		},

		onClickReturn() {
			this.$router.push({ name: 'ListCashDisbursement' });
		},

		onClickCreate() {
			this.$router.push({ name: 'ListCashDisbursementCreate', params: { id: this.idCash }});
		},

		onClickEdit(idCashOut) {
			this.$router.push({ name: 'ListCashDisbursementEdit', params: { driverID: this.idCash, cashOutID: idCashOut }});
		},

		handleCloseModalDelete() {
			this.showModalDelete = false;
		},

		onClickShowModalDelete(scope) {
			this.scopeID = scope;
			this.showModalDelete = true;
		},

		async handleOK() {
			try {
				this.showModalDelete = false;
				if (this.scopeID) {
					setLoading(true);
					const URL = `${CONSTANT.URL_API.DELETE_CASH_OUT}/${this.idCash}/cash-out/${this.scopeID}`;
					const DELETE_CASH_OUT = await deleteCashOut(URL);
					if (DELETE_CASH_OUT.code === 200) {
						TOAST_CASH_MANAGEMENT.delete();
						await this.handleGetlistCashOut();
						await this.handleGetDetailDisbursement();
					}
					setLoading(false);
				}
			} catch (error) {
				console.log(error);
			}
		},

		async handleGetDetailDisbursement() {
			try {
				if (this.idCash) {
					const PARAMS = {};
					const YEAR = this.pickerYearMonth.year;
					const MONTH = this.pickerYearMonth.month;

					const YEAR_MONTH = `${YEAR}-${format2Digit(MONTH)}`;

					PARAMS.month_line = YEAR_MONTH;
					const URL = `${CONSTANT.URL_API.GET_DETAIL_CASH_DISBURSEMENT}/${this.idCash}`;
					const response = await getDetailDisbursement(URL, PARAMS);
					if (response.code === 200) {
						const DATA = response.data;
						this.isForm.driver_code = DATA.driver_code;
						this.isForm.driver_name = DATA.driver_name;
						this.isForm.balance_current = DATA.balance_current ? Number(DATA.balance_current) + '円' : '';
						this.isForm.balance_previous_month = DATA.balance_previous_month ? Number(DATA.balance_previous_month) + '円' : '';
						this.isForm.payable_this_month = DATA.payable_this_month ? Number(DATA.payable_this_month) + '円' : '';
						this.isForm.total_payable = DATA.total_payable ? Number(DATA.total_payable) + '円' : '';
					} else {
						TOAST_CASH_MANAGEMENT.warning(response.message_content);
					}
				}
			} catch (error) {
				console.log(error);
			}
		},

		async handleGetlistCashOut() {
			try {
				const URL = `${CONSTANT.URL_API.GET_LIST_CASH_OUT}/${this.idCash}/cash-out`;
				const params = {};
				const response = await getListCashOut(URL, params);
				if (response.code === 200) {
					this.listCashDeital = response.data.data_list_cash_out.list_cash_out;
					this.totalCashOut = response.data.total_cash_out_month;
				} else {
					TOAST_CASH_MANAGEMENT.warning(response.message_content);
				}
			} catch (error) {
				console.log(error);
			}
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
                    }
                    td {
                        border: 1px solid rgb(211, 211, 211);
                    }
                    .th-remarks {
                        min-width: 170px;
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
                    td.td-cash-balance {
                       text-align: end;
                    }
                    td.total-cash-out {
                        text-align: end;
                    }
                }
            }
        }
        .font-weight-bolde{
            margin: 30px 0;
        }
    }
</style>
