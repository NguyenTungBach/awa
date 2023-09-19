<template>
    <b-col>
        <b-container class="container">
            <div class="page-schedule">
                <div class="page-schedule__header">
                    <b-row>
                        <b-col>
                            <div class="zone-title">
                                <span class="title-page">
                                    {{ $t('DETAIL_SCHEDULE.TITLE_DETAIL_SCHEDULE') }}
                                </span>
                            </div>
                        </b-col>
                    </b-row>
                </div>

                <LineGray />

                <div
                    class="zone-control text-right"
                >
                    <b-button
                        pill
                        class="btn-return"
                        @click="onClickReturn()"
                    >
                        {{ $t('APP.BUTTON_RETURN') }}
                    </b-button>
                    <b-button
                        pill
                        class="btn-save"
                        @click="onClickEdit()"
                    >
                        {{ $t('APP.BUTTON_EDIT') }}
                    </b-button>
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
                                    :src="require('@/assets/images/course_icon.png')"
                                    alt="Avatar schedule"
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
                            <TitlePathForm>
                                {{ $t('DETAIL_SCHEDULE.BASIC_INFORMATION') }}
                            </TitlePathForm>
                            <div class="item-form">
                                <b-row>
                                    <b-col>
                                        <DetailForm
                                            :label="$t('DETAIL_SCHEDULE.SHIP_DATE')"
                                            :value="isForm.ship_date"
                                        />
                                    </b-col>
                                </b-row>
                            </div>
                            <div class="item-form">
                                <b-row>
                                    <b-col>
                                        <DetailForm
                                            :label="$t('DETAIL_SCHEDULE.COURSE_NAME')"
                                            :value="isForm.course_name"
                                        />
                                    </b-col>
                                </b-row>
                            </div>
                            <div class="item-form">
                                <b-row>
                                    <b-col>
                                        <DetailForm
                                            :label="$t('DETAIL_SCHEDULE.VIHICLE_NUMBER')"
                                            :value="isForm.vehicle_number"
                                        />
                                    </b-col>
                                </b-row>
                            </div>
                            <div>
                                <b-row>
                                    <b-col
                                        :cols="12"
                                        :sm="12"
                                        :md="12"
                                        :lg="6"
                                        :xl="6"
                                        class="item-form"
                                    >
                                        <DetailForm
                                            :label="$t('DETAIL_SCHEDULE.START_TIME')"
                                            :value="isForm.start_time"
                                        />
                                    </b-col>
                                    <b-col
                                        :cols="12"
                                        :sm="12"
                                        :md="12"
                                        :lg="6"
                                        :xl="6"
                                        class="item-form"
                                    >
                                        <DetailForm
                                            :label="$t('DETAIL_SCHEDULE.END_TIME')"
                                            :value="isForm.end_time"
                                        />
                                    </b-col>
                                    <b-col
                                        :cols="12"
                                        :sm="12"
                                        :md="12"
                                        :lg="6"
                                        :xl="6"
                                        class="item-form"
                                    >
                                        <DetailForm
                                            :label="$t('DETAIL_SCHEDULE.BREAK_TIME')"
                                            :value="isForm.break_time"
                                        />
                                    </b-col>
                                </b-row>
                                <b-row class="item-form">
                                    <b-col>
                                        <DetailForm
                                            :label="$t('DETAIL_SCHEDULE.CUSTUM_NAME')"
                                            :value="isForm.customer_name"
                                        />
                                    </b-col>
                                </b-row>
                                <b-row class="item-form">
                                    <b-col>
                                        <DetailForm
                                            :label="$t('DETAIL_SCHEDULE.DEPATURE_PLACE')"
                                            :value="isForm.departure_place"
                                        />
                                    </b-col>
                                </b-row>
                                <b-row class="item-form">
                                    <b-col>
                                        <DetailForm
                                            :label="$t('DETAIL_SCHEDULE.ARRIVAL_PLACE')"
                                            :value="isForm.arrival_place"
                                        />
                                    </b-col>
                                </b-row>
                            </div>
                            <TitlePathForm class="item-not">
                                {{ $t('DETAIL_SCHEDULE.FEE_INFORMATION') }}
                            </TitlePathForm>
                            <div>
                                <b-row class="item-form">
                                    <b-col>
                                        <DetailForm
                                            :label="$t('DETAIL_SCHEDULE.ITEM_NAME')"
                                            :value="isForm.item_name"
                                        />
                                    </b-col>
                                </b-row>
                                <b-row class="item-form">
                                    <b-col>
                                        <DetailForm
                                            :label="$t('DETAIL_SCHEDULE.QUANTITY')"
                                            :value="isForm.quantity"
                                        />
                                    </b-col>
                                </b-row>
                                <b-row class="item-form">
                                    <b-col>
                                        <DetailForm
                                            :label="$t('DETAIL_SCHEDULE.UNIT_PRICE')"
                                            :value="isForm.unit_price"
                                        />
                                    </b-col>
                                </b-row>
                                <b-row class="item-form">
                                    <b-col>
                                        <DetailForm
                                            :label="$t('DETAIL_SCHEDULE.WEIGHT')"
                                            :value="isForm.weight"
                                        />
                                    </b-col>
                                </b-row>
                                <b-row class="item-form">
                                    <b-col>
                                        <DetailForm
                                            :label="$t('DETAIL_SCHEDULE.FREIGHT_COST')"
                                            :value="isForm.freight_cost"
                                        />
                                    </b-col>
                                </b-row>
                                <b-row class="item-form">
                                    <b-col>
                                        <DetailForm
                                            :label="$t('DETAIL_SCHEDULE.COOPERATING_COMPANY_PAYMENT_AMOUNT')"
                                            :value="isForm.payment_amount"
                                        />
                                    </b-col>
                                </b-row>
                                <b-row class="item-form">
                                    <b-col>
                                        <DetailForm
                                            :label="$t('DETAIL_SCHEDULE.HIGHT_WAY')"
                                            :value="isForm.hight_way"
                                        />
                                    </b-col>
                                </b-row>
                                <b-row class="item-form">
                                    <b-col>
                                        <DetailForm
                                            :label="$t('DETAIL_SCHEDULE.COMMISSION')"
                                            :value="isForm.commission"
                                        />
                                    </b-col>
                                </b-row>
                                <b-row class="item-form">
                                    <b-col>
                                        <DetailForm
                                            :label="$t('DETAIL_SCHEDULE.MEAL_SUBSIDY_AMOUNT')"
                                            :value="isForm.meal_fee"
                                        />
                                    </b-col>
                                </b-row>
                                <!-- <b-row class="item-form">
                                    <b-col>
                                        <DetailForm
                                            :label="$t('DETAIL_SCHEDULE.BONUS_AMOUNT')"
                                        />
                                    </b-col>
                                </b-row> -->
                            </div>

                            <TitlePathForm class="item-not">
                                {{ $t('DETAIL_SCHEDULE.NOTE') }}
                            </TitlePathForm>

                            <div class="item-form">
                                <b-col
                                    :cols="12"
                                    :sm="12"
                                    :md="12"
                                    :lg="12"
                                    :xl="12"
                                    class="note-detail"
                                >
                                    <p>{{ isForm.note ? isForm.note : '-' }}</p>
                                </b-col>
                            </div>

                        </b-col>
                    </b-row>

                </div>

            </div>
        </b-container>
    </b-col>
</template>

<script>
import CONSTANT from '@/const';
import LineGray from '@/components/LineGray';
import TitlePathForm from '@/components/TitlePathForm';
import { setLoading } from '@/utils/handleLoading';
// import { format2Digit } from '@/utils/generateTime';
// import NodeSchedule from '@/components/NodeSchedule';
// import { cleanObject } from '@/utils/handleObject';
// import { getCalendar } from '@/api/modules/calendar';
// import { getNumberDate, getTextDay } from '@/utils/convertTime';
// import TOAST_SCHEDULE_MANAGEMENT from '@/toast/modules/scheduleManagement';
import { getDetail } from '@/api/modules/courseSchedule';
// import { validateSizeFile, validateFileCSV } from '@/utils/validate';
// import TOAST_SCHEDULE_SHIFT from '@/toast/modules/scheduleShift';
import DetailForm from '@/components/DetailForm';
export default {
	name: 'ListSchedule',
	components: {
		LineGray,
		// NodeSchedule,
		TitlePathForm,
		DetailForm,
	},

	data() {
		return {
			isForm: {
				customer_id: '',
				ship_date: '',
				course_name: '',
				vehicle_number: '',
				start_time: '',
				end_time: '',
				break_time: '',
				customer_name: '',
				departure_place: '',
				arrival_place: '',
				item_name: '',
				quantity: '',
				unit_price: '',
				weight: '',
				freight_cost: '',
				payment_amount: '',
				hight_way: '',
				commission: '',
				meal_fee: '',
				note: '',
			},
		};
	},

	computed: {

	},

	watch: {

	},

	created() {
		this.initData();
	},

	methods: {
		onClickReturn() {
			this.$router.push({ name: 'ListSchedule' });
		},

		onClickEdit(){
			this.$router.push({ name: 'ListScheduleEdit' });
		},

		async initData() {
			this.isForm.customer_id = this.$route.params.id || null;
			await this.handleGetCourseShedule();
		},

		async handleGetCourseShedule() {
			try {
				if (this.isForm.customer_id) {
					setLoading(true);
					const customer = await getDetail(`${CONSTANT.URL_API.GET_DETAIL_COURSE_SCHEDULE}/${this.isForm.customer_id}`);

					if (customer.code === 200) {
						const DATA = customer.data;
						// const convertDate = `${(DATA.ship_date).slice(0, 4)}-${(DATA.ship_date).slice(5, 7)}-${(DATA.ship_date).slice(8, 10)}`;
						this.isForm.ship_date = DATA.ship_date;
						this.isForm.course_name = DATA.course_name;
						this.isForm.vehicle_number = DATA.vehicle_number;
						this.isForm.customer_name = DATA.customer_name;
						this.isForm.start_time = DATA.start_date;
						this.isForm.end_time = DATA.end_date;
						this.isForm.break_time = DATA.break_time;
						this.isForm.departure_place = DATA.departure_place;
						this.isForm.arrival_place = DATA.arrival_place;
						this.isForm.item_name = DATA.item_name;
						this.isForm.quantity = DATA.quantity ? Number(DATA.quantity).toLocaleString() : '';
						this.isForm.unit_price = DATA.price ? Number(DATA.price).toLocaleString() + ' 円' : '';
						this.isForm.weight = DATA.weight ? Number(DATA.weight).toLocaleString() + ' kg' : '';
						this.isForm.freight_cost = DATA.ship_fee ? Number(DATA.ship_fee).toLocaleString() + ' 円' : '';
						this.isForm.payment_amount = DATA.associate_company_fee ? Number(DATA.associate_company_fee).toLocaleString() + ' 円' : '';
						this.isForm.hight_way = DATA.expressway_fee ? Number(DATA.expressway_fee).toLocaleString() + ' 円' : '';
						this.isForm.commission = DATA.commission ? Number(DATA.commission).toLocaleString() + ' 円' : '';
						this.isForm.meal_fee = DATA.meal_fee ? Number(DATA.meal_fee).toLocaleString() + ' 円' : '';
						this.isForm.note = DATA.note;
					}
					setLoading(false);
				}
			} catch {
				setLoading(false);
			}
		},

	},

};
</script>

<style lang="scss" scoped>
    @import '@/scss/variables';

    .page-schedule {
            .zone-control {
                margin-bottom: 10px;

                .btn-return,
                .btn-save {
                    &:hover {
                        opacity: 0.8;
                    }

                    border-color: transparent;
                }
                .btn-return{
                    background-color: $gray;
                }
                .btn-save {
                    background-color: $main;
                    color: $white;
                    font-weight: 600;
                }
            }
            .body-form {
                border: 1px solid $geyser;
                margin-top: 15px;
                padding: 20px;
                font-size: 18px;
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
                .item-form{
                    margin-top: 40px;

                    .note-detail {
                        max-width: 100%;
                        overflow: hidden;
                        word-wrap: break-word;
                    }
                }
                .item-not{
                    margin: 40px 0;
                }
            }
    }
</style>
