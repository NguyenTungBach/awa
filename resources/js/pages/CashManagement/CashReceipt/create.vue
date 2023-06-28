<template>
    <b-col>
        <b-container>
            <div class="page-cash-create">
                <div class="page-cash-create__header">
                    <b-row>
                        <b-col>
                            <div class="zone-title">
                                <span class="title-page">
                                    {{ $t('LIST_CASH.TITLE_CASH_CREATE') }}
                                </span>
                            </div>
                        </b-col>
                    </b-row>
                    <LineGray />
                </div>
                <div class="page-cash-create__body">
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
                                        class="btn-color-active btn-save"
                                    >
                                        {{ $t('APP.BUTTON_SAVE') }}
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
                                    <img
                                        :src="require('@/assets/images/Cash_icon.png')"
                                        alt="Avatar course"
                                    >
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
                                    <!-- <div class="zone-form__header">
                                        <TitlePathForm>
                                            {{ $t('CUSTOMER_CREATE.FORM_BASIC_INFORMATION') }}
                                        </TitlePathForm>
                                    </div> -->
                                    <div class="zone-form__body">
                                        <div class="item-form">
                                            <b-row>
                                                <b-col
                                                    :cols="12"
                                                    :sm="12"
                                                    :md="12"
                                                    :lg="12"
                                                    :xl="12"
                                                >
                                                    <label for="input-payment-day">
                                                        {{ $t('LIST_CASH.PAYMENT_DAY') }}
                                                        <span class="text-danger">
                                                            *
                                                        </span>
                                                    </label>
                                                    <b-input-group class="mb-3">
                                                        <b-form-input
                                                            id="input-payment-day"
                                                            v-model="isForm.payment_day"
                                                            type="text"
                                                            autocomplete="off"
                                                        />
                                                        <b-input-group-append>
                                                            <b-form-datepicker
                                                                v-model="isForm.payment_day"
                                                                button-only
                                                                right
                                                                :locale="language"
                                                                aria-controls="input-payment-day"
                                                                hide-header
                                                                :is-r-t-l="false"
                                                                :label-help="$t('APP.LABLE_HELP_CALENDAR')"
                                                                :min="isForm.dateOfBirth"
                                                                :max="isForm.retirementDate"
                                                            />
                                                        </b-input-group-append>
                                                    </b-input-group>
                                                </b-col>
                                            </b-row>
                                        </div>
                                        <div class="item-form">
                                            <b-row>
                                                <b-col>
                                                    <label for="input-deposit-day">
                                                        {{ $t('LIST_CASH.TABLE_DEPOSIT_AMOUNT') }}
                                                        <span class="text-danger">
                                                            *
                                                        </span>
                                                    </label>
                                                    <b-form-input
                                                        id="input-deposit-day"
                                                        v-model="isForm.character"
                                                    />
                                                </b-col>
                                                <b-col
                                                    :cols="12"
                                                    :sm="12"
                                                    :md="12"
                                                    :lg="12"
                                                    :xl="1"
                                                    style="bottom: -41px;"
                                                >
                                                    <span class="text-closing-day">円</span>
                                                </b-col>
                                            </b-row>
                                        </div>
                                        <div class="item-form">
                                            <b-row>
                                                <b-col
                                                    :cols="12"
                                                    :sm="12"
                                                    :md="12"
                                                    :lg="12"
                                                    :xl="12"
                                                >
                                                    <div class="item-form">
                                                        <label for="input-payment-method">
                                                            {{ $t('LIST_CASH.TABLE_PAYMENT_METHOD') }}
                                                        </label>
                                                        <span class="text-danger">
                                                            *
                                                        </span>
                                                        <b-input-group>
                                                            <b-form-select
                                                                id="input-payment-method"
                                                                v-model="isForm.exclusive"
                                                                :options="optionsClosingDay"
                                                            />
                                                        </b-input-group>
                                                    </div>
                                                </b-col>
                                            </b-row>
                                        </div>
                                    </div>
                                </div>
                            </b-col>
                        </b-row>
                        <b-row>
                            <b-col
                                :cols="12"
                                :sm="12"
                                :md="12"
                                :lg="8"
                                :xl="8"
                                class="ml-auto"
                            >
                                <label for="input-notes">
                                    {{ $t('LIST_CASH.NOTE_CASH_DISBURSEMENT') }}
                                </label>
                                <b-form-textarea
                                    id="input-notes"
                                    v-model="isForm.note"
                                    rows="6"
                                    max-rows="12"
                                />
                            </b-col>
                        </b-row>
                    </div>
                </div>
            </div>
        </b-container>
    </b-col>
</template>
<script>
import LineGray from '@/components/LineGray';
// import TitlePathForm from '@/components/TitlePathForm';

export default {
	name: 'CashCreate',
	components: {
		LineGray,
		// TitlePathForm,
	},

	data() {
		return {
			isForm: {
				payment_day: '',
				dateOfBirth: '',
				retirementDate: '',
				exclusive: '',
				note: '',
			},

			optionsClosingDay: [
				{
					value: 1,
					text: '入金方法',
				},
				{
					value: 2,
					text: '方法',
				},
			],
		};
	},

	computed: {
		language() {
			return this.$store.getters.language;
		},
	},

	methods: {
		onClickReturn() {
			this.$router.push({ name: 'ListCashDisbursement' });
		},
	},
};
</script>
<style lang="scss" scoped>
    @import '@/scss/variables';

    .page-cash-create {
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
                            margin-bottom: 10px;
                            font-size: 18px;
                        }
                        .text-closing-day {
                            font-family: 'Inter';
                            font-style: normal;
                            font-weight: 400;
                            font-size: 24px;
                            line-height: 29px;
                        }
                        .select-multiple {
                            width: 100%;
                        }
                    }
                }
            }
        }
    }

</style>
