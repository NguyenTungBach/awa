<template>
    <b-col>
        <b-container>
            <div class="page-course-detail">
                <div class="page-course-detail__header">
                    <b-row>
                        <b-col>
                            <div class="zone-title">
                                <span class="title-page">
                                    {{ $t('CUSTOMER_DETAIL.TITLE_CUSTOMER_DETAIL') }}
                                </span>
                            </div>
                        </b-col>
                    </b-row>
                    <LineGray />
                </div>
                <div class="page-course-detail__body">
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
                                        @click="onClickEdit()"
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
                                    <img
                                        :src="require('@/assets/images/customer_icon.png')"
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
                                    <div class="zone-form__header">
                                        <TitlePathForm>
                                            {{ $t('CUSTOMER_CREATE.FORM_BASIC_INFORMATION') }}
                                        </TitlePathForm>
                                    </div>
                                    <div class="zone-form__body">
                                        <!-- <div class="item-form">
                                            <DetailForm
                                                :label="$t('CUSTOMER_CREATE.EASY_TO_DISTINGUISH')"
                                                :value="formatFlagAndPot(isForm.flag, isForm.pot)"
                                            />
                                        </div> -->

                                        <b-row>
                                            <b-col>
                                                <div class="item-form">
                                                    <DetailForm
                                                        :label="$t('CUSTOMER_CREATE.COURSE_ID')"
                                                        :value="isForm.customer_id"
                                                    />
                                                </div>
                                            </b-col>
                                        </b-row>

                                        <b-row>
                                            <b-col>
                                                <div class="item-form">
                                                    <DetailForm
                                                        :label="$t('CUSTOMER_CREATE.COURSE_NAME')"
                                                        :value="isForm.customer_name"
                                                    />
                                                </div>
                                            </b-col>
                                        </b-row>

                                        <b-row>
                                            <b-col>
                                                <div class="item-form">
                                                    <DetailForm
                                                        :label="$t('CUSTOMER_CREATE.CLOSING_DAY')"
                                                        :value="isForm.closing_date"
                                                    />
                                                </div>
                                            </b-col>
                                        </b-row>

                                        <!-- bach them-->
                                        <!--                                        <b-row>-->
                                        <!--                                            <b-col>-->
                                        <!--                                                <div class="item-form">-->
                                        <!--                                                    <DetailForm-->
                                        <!--                                                        :label="$t('CUSTOMER_CREATE.TAX')"-->
                                        <!--                                                        :value="isForm.tax"-->
                                        <!--                                                    />-->
                                        <!--                                                </div>-->
                                        <!--                                            </b-col>-->
                                        <!--                                        </b-row>-->
                                        <!-- bach them-->

                                        <!-- <b-row>
                                            <b-col>
                                                <div class="item-form">
                                                    <DetailForm
                                                        :label="$t('CUSTOMER_CREATE.SALE_TAX')"
                                                        :value="isForm.saleTax"
                                                    />
                                                </div>
                                            </b-col>
                                        </b-row> -->

                                        <b-row>
                                            <b-col>
                                                <div class="item-form">
                                                    <DetailForm
                                                        :label="$t('CUSTOMER_CREATE.CLIENT_MANAGER')"
                                                        :value="isForm.client_manager"
                                                    />
                                                </div>
                                            </b-col>
                                        </b-row>

                                        <b-row>
                                            <b-col>
                                                <div class="item-form">
                                                    <DetailForm
                                                        :label="$t('CUSTOMER_CREATE.POST_CODE')"
                                                        :value="isForm.post_code"
                                                    />
                                                </div>
                                            </b-col>
                                        </b-row>

                                        <b-row>
                                            <b-col>
                                                <div class="item-form">
                                                    <DetailForm
                                                        :label="$t('CUSTOMER_CREATE.ADDRESS_OF_CLIENT')"
                                                        :value="isForm.address"
                                                    />
                                                </div>
                                            </b-col>
                                        </b-row>

                                        <b-row>
                                            <b-col>
                                                <div class="item-form">
                                                    <DetailForm
                                                        :label="$t('CUSTOMER_CREATE.CLIENT_PHONE')"
                                                        :value="isForm.client_phone"
                                                    />
                                                </div>
                                            </b-col>
                                        </b-row>

                                        <div class="zone-form__header">
                                            <TitlePathForm>
                                                {{ $t('CUSTOMER_CREATE.NOTE') }}
                                            </TitlePathForm>
                                        </div>
                                        <div class="item-form">
                                            <b-col
                                                :cols="12"
                                                :sm="12"
                                                :md="12"
                                                :lg="12"
                                                :xl="12"
                                            >
                                                <p>{{ isForm.note ? isForm.note : '-' }}</p>
                                            </b-col>
                                        </div>
                                    </div>
                                </div>
                            </b-col>
                        </b-row>
                    </div>
                </div>
            </div>
        </b-container>
    </b-col>
</template>

<script>
import CONSTANT from '@/const';
import LineGray from '@/components/LineGray';
import DetailForm from '@/components/DetailForm';
import { setLoading } from '@/utils/handleLoading';
import TitlePathForm from '@/components/TitlePathForm';
// import { generateTimeSelect } from '@/utils/generateTime';
import { getCourse } from '@/api/modules/courseManagement';
// import { convertBreakTimeNumberToTime } from '@/utils/convertTime';
import { convertValueToText } from '@/utils/handleSelect';
import { formartPhoneNumber } from '@/utils/formatNumber';

export default {
	name: 'CustomerDetail',
	components: {
		LineGray,
		TitlePathForm,
		DetailForm,
	},

	data() {
		return {
			idCourse: null,

			isForm: {
				customer_id: '',
				customer_name: '',
				closing_date: '',
				// tax: '', // bach them
				client_manager: '',
				post_code: '',
				address: '',
				client_phone: '',
				note: '',
			},
		};
	},

	computed: {
		language() {
			return this.$store.getters.language;
		},
	},

	created() {
		this.initData();
	},

	methods: {
		convertValueToText,

		initData() {
			this.idCourse = this.$route.params.id || null;
			// this.isForm.listTime = generateTimeSelect(15);
			// this.isForm.listFatigue = this.generateListFatigue(1, 5);
			this.handleGetCourse();
		},

		// generateListFatigue(min = 1, max = 5) {
		// 	let idx = min;

		// 	const result = [];

		// 	while (idx <= max) {
		// 		result.push({
		// 			value: idx,
		// 			text: `${idx}`,
		// 		});

		// 		idx++;
		// 	}

		// 	return result;
		// },

		async handleGetCourse() {
			try {
				if (this.idCourse) {
					setLoading(true);
					const COURSE = await getCourse(`${CONSTANT.URL_API.GET_COURSE}/${this.idCourse}`);

					if (COURSE.code === 200) {
						const DATA = COURSE.data;

						this.isForm.customer_id = DATA.customer_code;
						this.isForm.customer_name = DATA.customer_name;
						// this.isForm.end_time = convertValueToText(this.isForm.listTime, DATA.end_time);
						// this.isForm.break_time = convertValueToText(this.isForm.listTime, convertBreakTimeNumberToTime(DATA.break_time));
						this.isForm.closing_date = DATA.closing_date;
						// this.isForm.tax = DATA.tax; // bach them
						this.isForm.client_manager = DATA.person_charge;
						this.isForm.post_code = DATA.post_code;
						this.isForm.note = DATA.note;
						this.isForm.client_phone = formartPhoneNumber(DATA.phone);
						this.isForm.address = DATA.address;

						setLoading(false);
					}
				}
			} catch {
				setLoading(false);
			}
		},

		onClickReturn() {
			this.$router.push({ name: 'ListCustomerIndex' });
		},

		onClickEdit() {
			this.$router.push({ name: 'CustomerEdit', params: { id: this.idCourse }});
		},

		// formatFlagAndPot(flag, pot) {
		// 	if (flag === 'yes' && pot === 'yes') {
		// 		return `${this.$t('COURSE_CREATE.CHECKBOX_SPOT_FLIGHT')}, ${this.$t('COURSE_CREATE.CHECKBOX_SHORT_FLIGHT')}`;
		// 	}

		// 	if (flag === 'yes') {
		// 		return `${this.$t('COURSE_CREATE.CHECKBOX_SPOT_FLIGHT')}`;
		// 	}

		// 	if (pot === 'yes') {
		// 		return `${this.$t('COURSE_CREATE.CHECKBOX_SHORT_FLIGHT')}`;
		// 	}
		// },
	},
};
</script>

<style lang="scss" scoped>
    @import '@/scss/variables';

    .page-course-detail {
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
                }
            }
        }
    }
</style>
