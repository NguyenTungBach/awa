<template>
    <b-col>
        <b-container>
            <div class="page-cash-detail">
                <div class="page-cash-detail__header">
                    <b-row>
                        <b-col>
                            <div class="zone-title">
                                <span class="title-page">
                                    {{ $t('LIST_CASH.TITLE_CASH_DETAIL') }}
                                </span>
                            </div>
                        </b-col>
                    </b-row>
                    <LineGray />
                </div>
                <div class="page-cash-detail__body">
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
                                            {{ $t('LIST_CASH.FORM_BASIC_INFORMATION') }}
                                        </TitlePathForm>
                                    </div>
                                    <div class="zone-form__body">
                                        <b-row>
                                            <b-col>
                                                <div class="item-form">
                                                    <DetailForm
                                                        :label="$t('LIST_CASH.TABLE_CASH_ID')"
                                                        :value="isForm.customer_code"
                                                    />
                                                </div>
                                            </b-col>
                                        </b-row>

                                        <b-row>
                                            <b-col>
                                                <div class="item-form">
                                                    <DetailForm
                                                        :label="$t('LIST_CASH.TABLE_CASH_NAME')"
                                                        :value="isForm.customer_name"
                                                    />
                                                </div>
                                            </b-col>
                                        </b-row>

                                        <b-row>
                                            <b-col>
                                                <div class="item-form">
                                                    <DetailForm
                                                        :label="$t('LIST_CASH.TABLE_CURRENT_MONTH_BALANCE')"
                                                        :value="isForm.total_cash_in_current"
                                                    />
                                                </div>
                                            </b-col>
                                        </b-row>
                                        <b-row>
                                            <b-col>
                                                <div class="item-form">
                                                    <DetailForm
                                                        :label="$t('LIST_CASH.TABLE_CASH_BALANCE_AT_END_OF_PREVIOUS_MONTH')"
                                                        :value="isForm.balance_previous_month"
                                                    />
                                                </div>
                                            </b-col>
                                        </b-row>
                                        <b-row>
                                            <b-col>
                                                <div class="item-form">
                                                    <DetailForm
                                                        :label="$t('LIST_CASH.TABLE_CASH_ACCOUNTS_RECEIVABLE')"
                                                        :value="isForm.receivable_this_month"
                                                    />
                                                </div>
                                            </b-col>
                                        </b-row>
                                        <b-row>
                                            <b-col>
                                                <div class="item-form">
                                                    <DetailForm
                                                        :label="$t('LIST_CASH.TABLE_TOTAL_ACCOUNTS_RECEIVABLE')"
                                                        :value="isForm.total_account_receivable"
                                                    />
                                                </div>
                                            </b-col>
                                        </b-row>
                                    </div>
                                    <div class="zone-form__footer">
                                        <TitlePathForm>
                                            {{ $t('LIST_CASH.DEPOSIT_INFORMATION') }}
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
                                                    {{ $t('LIST_CASH.TABLE_NO') }}
                                                </b-th>
                                                <b-th
                                                    class="th-sort"
                                                    :colspan="2"
                                                >
                                                    {{ $t('LIST_CASH.TABLE_DATE') }}
                                                </b-th>
                                                <b-th
                                                    class="th-sort"
                                                    :colspan="2"
                                                >
                                                    {{ $t('LIST_CASH.TABLE_DEPOSIT_AMOUNT') }}
                                                </b-th>
                                                <b-th
                                                    class="th-sort"
                                                    :colspan="2"
                                                >
                                                    {{ $t('LIST_CASH.TABLE_PAYMENT_METHOD') }}
                                                </b-th>
                                                <b-th
                                                    class="th-remarks"
                                                    :colspan="3"
                                                >
                                                    {{ $t('LIST_CASH.TABLE_REMARKS') }}
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
                                                        {{ Number(course.cash_in) }}
                                                    </b-td>
                                                    <b-td class="td-payment_method" :colspan="2">
                                                        {{ handlePaymentMethod(course.payment_method) }}
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
                                                    {{ $t('LIST_CASH.TABLE_TOTAL') }}
                                                </b-th>
                                                <b-td class="total-cash-in">
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
import DetailForm from '@/components/DetailForm';
import TitlePathForm from '@/components/TitlePathForm';
import { setLoading } from '@/utils/handleLoading';
import CONSTANT from '@/const';
import TOAST_CASH_MANAGEMENT from '@/toast/modules/cashManagement';
import { getDetailCashReciept, getCashIn, deleteCashIn } from '@/api/modules/cashDisbursement';
import { format2Digit } from '@/utils/generateTime';
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
			// idCashIn: null,
			isForm: {
				customer_code: '',
				customer_name: '',
				total_cash_in_current: '',
				balance_previous_month: '',
				receivable_this_month: '',
				total_account_receivable: '',
				total: '122.55',
			},

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
			await this.handleGetListCashIn();
			await this.handleGetDetailReciept();
		},

		handlePaymentMethod(cashIn) {
			return cashIn === 1 ? '銀行振込' : '振込';
		},

		onClickReturn() {
			this.$router.push({ name: 'ListCashReceipt' });
		},

		onClickCreate() {
			this.$router.push({ name: 'ListCashReceiptCreate', params: { id: this.idCash }});
		},

		onClickEdit(idCashIn) {
			this.$router.push({ name: 'ListCashReceiptEdit', params: { id: idCashIn }});
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
					const URL = `${CONSTANT.URL_API.DELETE_CASH_IN}/${this.scopeID}`;
					const Delete_cash_in = await deleteCashIn(URL);
					if (Delete_cash_in.code === 200) {
						TOAST_CASH_MANAGEMENT.deleteCashIn();
						await this.handleGetListCashIn();
						await this.handleGetDetailReciept();
					}
					setLoading(false);
				}
			} catch (error) {
				console.log(error);
			}
		},

		async handleGetDetailReciept() {
			try {
				if (this.idCash) {
					setLoading(true);
					const PARAMS = {};
					const YEAR = this.pickerYearMonth.year;
					const MONTH = this.pickerYearMonth.month;
					const YEAR_MONTH = `${YEAR}-${format2Digit(MONTH)}`;
					PARAMS.month_year = YEAR_MONTH;
					const URL = `${CONSTANT.URL_API.GET_DETAIL_CASH_RECIEPT}/${this.idCash}`;
					const response = await getDetailCashReciept(URL, PARAMS);
					if (response.code === 200) {
						const DATA = response.data;
						this.isForm.customer_code = DATA.customer_code;
						this.isForm.customer_name = DATA.customer_name;
						this.isForm.total_cash_in_current = DATA.total_cash_in_current ? Number(DATA.total_cash_in_current) + '円' : '';
						this.isForm.balance_previous_month = DATA.balance_previous_month ? Number(DATA.balance_previous_month) + '円' : '';
						this.isForm.receivable_this_month = DATA.receivable_this_month ? Number(DATA.receivable_this_month) + '円' : '';
						this.isForm.total_account_receivable = DATA.total_account_receivable ? Number(DATA.total_account_receivable) + '円' : '';
					} else {
						TOAST_CASH_MANAGEMENT.warning(response.message_content);
					}
					setLoading(false);
				}
			} catch (error) {
				console.log(error);
			}
		},

		async handleGetListCashIn() {
			try {
				setLoading(true);
				const URL = `${CONSTANT.URL_API.GET_LIST_CASH_IN}`;
				const params = {
					customer_id: this.idCash,
				};
				const YEAR = this.pickerYearMonth.year;
				const MONTH = this.pickerYearMonth.month;
				const YEAR_MONTH = `${YEAR}-${format2Digit(MONTH)}`;
				params.month_year = YEAR_MONTH;
				const response = await getCashIn(URL, params);
				if (response.code === 200) {
					this.listCashDeital = response.data.list_cash_in;
					this.isForm.total = response.data.total_cash_in.total_cash_in ? Number(response.data.total_cash_in.total_cash_in) : ''; // Number(response.data.total_cash_in.total_cash_in).toLocaleString()
				} else {
					this.listCashDeital = [];
					TOAST_CASH_MANAGEMENT.warning(response.message_content);
				}
				setLoading(false);
			} catch (error) {
				console.log(error);
			}
		},
	},
};
</script>
<style lang="scss" scoped>
    @import '@/scss/variables';

    .page-cash-detail {
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
                    td.td-cash-balance {
                       text-align: end;
                    }
                    td.total-cash-in {
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
