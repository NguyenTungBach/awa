<template>
    <b-col>
        <b-container class="container">
            <div class="page-schedule">
                <div class="page-schedule__header">
                    <b-row>
                        <b-col>
                            <div class="zone-title">
                                <span class="title-page">
                                    {{ $t('CREATE_SCHEDULE.TITLE_CREATE_SCHEDULE') }}
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
                        @click="onClickSave()"
                    >
                        {{ $t('APP.BUTTON_SAVE') }}
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
                            <div class="zone-form">
                                <TitlePathForm>
                                    {{ $t('CREATE_SCHEDULE.BASIC_INFORMATION') }}
                                </TitlePathForm>
                                <b-col
                                    :cols="12"
                                    :sm="12"
                                    :md="12"
                                    :lg="6"
                                    :xl="12"
                                    class="date"
                                >
                                    <label for="input-date-date-of-birth">
                                        {{ $t('CREATE_SCHEDULE.SHIP_DATE') }}
                                        <span class="text-danger">
                                            *
                                        </span>
                                    </label>
                                    <b-input-group class="mb-3">
                                        <b-form-input
                                            id="input-date-date-of-birth"
                                            v-model="isForm.ship_date"
                                            type="text"
                                        />
                                        <b-input-group-append>
                                            <b-form-datepicker
                                                v-model="isForm.ship_date"
                                                button-only
                                                right
                                            />
                                        </b-input-group-append>
                                    </b-input-group>
                                </b-col>
                                <b-col
                                    :cols="12"
                                    :sm="12"
                                    :md="12"
                                    :lg="6"
                                    :xl="12"
                                    class="date"
                                >
                                    <label for="input-course-name">
                                        {{ $t('CREATE_SCHEDULE.COURSE_NAME') }}
                                        <span class="text-danger">
                                            *
                                        </span>
                                    </label>
                                    <b-input-group class="mb-3">
                                        <b-form-input
                                            id="input-course-name"
                                            v-model="isForm.course_name"
                                            type="text"
                                        />
                                    </b-input-group>
                                </b-col>
                                <b-row>
                                    <b-col
                                        :cols="12"
                                        :sm="12"
                                        :md="12"
                                        :lg="6"
                                        :xl="6"
                                    >
                                        <label for="input-start-time">
                                            {{ $t('CREATE_SCHEDULE.START_TIME') }}
                                            <span class="text-danger">
                                                *
                                            </span>
                                        </label>
                                        <b-input-group class="mb-3">
                                            <div class="item-form">
                                                <SelectMultiple
                                                    :options="isForm.listTime"
                                                    :value="isForm.start_time"
                                                    :formatter="formatter"
                                                    @select="getSelectStartTime"
                                                />
                                            </div>
                                        </b-input-group>
                                    </b-col>
                                    <b-col
                                        :cols="12"
                                        :sm="12"
                                        :md="12"
                                        :lg="6"
                                        :xl="6"
                                    >
                                        <label for="input-end-time">
                                            {{ $t('CREATE_SCHEDULE.END_TIME') }}
                                            <span class="text-danger">
                                                *
                                            </span>
                                        </label>
                                        <b-input-group class="mb-3">
                                            <div class="item-form">
                                                <SelectMultiple
                                                    :options="isForm.listTime"
                                                    :value="isForm.end_time"
                                                    :formatter="formatter"
                                                    @select="getSelectEndTime"
                                                />
                                            </div>
                                        </b-input-group>
                                    </b-col>
                                    <b-col
                                        :cols="12"
                                        :sm="12"
                                        :md="12"
                                        :lg="6"
                                        :xl="6"
                                    >
                                        <label for="input-break-time">
                                            {{ $t('CREATE_SCHEDULE.BREAK_TIME') }}
                                            <span class="text-danger">
                                                *
                                            </span>
                                        </label>
                                        <b-input-group class="mb-3">
                                            <div class="item-form">
                                                <SelectMultiple
                                                    :options="isForm.listTime"
                                                    :value="isForm.break_time"
                                                    :formatter="formatter"
                                                    @select="getSelectBreakTime"
                                                />
                                            </div>
                                        </b-input-group>
                                    </b-col>
                                </b-row>
                                <b-col
                                    :cols="12"
                                    :sm="12"
                                    :md="12"
                                    :lg="6"
                                    :xl="12"
                                    class="date"
                                >
                                    <label for="input-customer-name">
                                        {{ $t('CREATE_SCHEDULE.CUSTUM_NAME') }}
                                        <span class="text-danger">
                                            *
                                        </span>
                                    </label>
                                    <b-input-group class="mb-3">
                                        <b-form-select
                                            id="input-customer-name"
                                            v-model="isForm.customer_name"
                                            :options="isForm.optionListCustomer"
                                        />

                                    </b-input-group>
                                </b-col>
                                <b-col
                                    :cols="12"
                                    :sm="12"
                                    :md="12"
                                    :lg="6"
                                    :xl="12"
                                    class="date"
                                >
                                    <label for="input-depature-place">
                                        {{ $t('CREATE_SCHEDULE.DEPATURE_PLACE') }}
                                        <span class="text-danger">
                                            *
                                        </span>
                                    </label>
                                    <b-input-group class="mb-3">
                                        <b-form-input
                                            id="input-depature-place"
                                            v-model="isForm.departure_place"
                                            type="text"
                                        />

                                    </b-input-group>
                                </b-col>
                                <b-col
                                    :cols="12"
                                    :sm="12"
                                    :md="12"
                                    :lg="6"
                                    :xl="12"
                                    class="date"
                                >
                                    <label for="input-arrival_place">
                                        {{ $t('CREATE_SCHEDULE.ARRIVAL_PLACE') }}
                                        <span class="text-danger">
                                            *
                                        </span>
                                    </label>
                                    <b-input-group class="mb-3">
                                        <b-form-input
                                            id="input-arrival_place"
                                            v-model="isForm.arrival_place"
                                            type="text"
                                        />

                                    </b-input-group>
                                </b-col>
                                <TitlePathForm>
                                    {{ $t('CREATE_SCHEDULE.FEE_INFORMATION') }}
                                </TitlePathForm>
                                <b-col
                                    :cols="12"
                                    :sm="12"
                                    :md="12"
                                    :lg="6"
                                    :xl="12"
                                    class="date"
                                >
                                    <label for="input-freight-cost">
                                        {{ $t('CREATE_SCHEDULE.FREIGHT_COST') }}
                                        <span class="text-danger">
                                            *
                                        </span>
                                    </label>
                                    <b-input-group class="mb-3">
                                        <b-form-input
                                            id="input-freight-cost"
                                            v-model="isForm.freight_cost"
                                            type="number"
                                        />
                                        <span class="freight-cost"> 円 </span>
                                    </b-input-group>

                                </b-col>
                                <b-col
                                    :cols="12"
                                    :sm="12"
                                    :md="12"
                                    :lg="6"
                                    :xl="12"
                                    class="date"
                                >
                                    <label for="input-cooperating-company-payment-amount">
                                        {{ $t('CREATE_SCHEDULE.COOPERATING_COMPANY_PAYMENT_AMOUNT') }}
                                        <!-- <span class="text-danger">
                                            *
                                        </span> -->
                                    </label>
                                    <b-input-group class="mb-3">
                                        <b-form-input
                                            id="input-cooperating-company-payment-amount"
                                            v-model="isForm.payment_amount"
                                            type="number"
                                        />
                                        <span class="freight-cost"> 円 </span>
                                    </b-input-group>

                                </b-col>
                                <b-col
                                    :cols="12"
                                    :sm="12"
                                    :md="12"
                                    :lg="6"
                                    :xl="12"
                                    class="date"
                                >
                                    <label for="input-hight-way">
                                        {{ $t('CREATE_SCHEDULE.HIGHT_WAY') }}
                                        <!-- <span class="text-danger">
                                            *
                                        </span> -->
                                    </label>
                                    <b-input-group class="mb-3">
                                        <b-form-input
                                            id="input-hight-way"
                                            v-model="isForm.hight_way"
                                            type="number"
                                        />
                                        <span class="freight-cost"> 円 </span>
                                    </b-input-group>

                                </b-col>
                                <b-col
                                    :cols="12"
                                    :sm="12"
                                    :md="12"
                                    :lg="6"
                                    :xl="12"
                                    class="date"
                                >
                                    <label for="input-expenses">
                                        {{ $t('CREATE_SCHEDULE.EXPENSES') }}
                                        <!-- <span class="text-danger">
                                            *
                                        </span> -->
                                    </label>
                                    <b-input-group class="mb-3">
                                        <b-form-input
                                            id="input-expenses"
                                            v-model="isForm.expenses"
                                            type="number"
                                        />
                                        <span class="freight-cost"> 円 </span>
                                    </b-input-group>

                                </b-col>
                                <!-- <b-col
                                    :cols="12"
                                    :sm="12"
                                    :md="12"
                                    :lg="6"
                                    :xl="12"
                                    class="date"
                                >
                                    <label for="input-bunus-targer">
                                        {{ $t('CREATE_SCHEDULE.BONUS_TARGET') }}
                                        <span class="text-danger">
                                            *
                                        </span>
                                    </label>
                                    <b-input-group class="mb-3">
                                        <b-form-select
                                            v-model="bunus_target" :options="bunusTargetOptions"
                                            :text-field="'text'"
                                            :value-field="'rate'" size="xl"
                                        />
                                    </b-input-group>

                                </b-col> -->
                                <b-col
                                    :cols="12"
                                    :sm="12"
                                    :md="12"
                                    :lg="6"
                                    :xl="12"
                                    class="date"
                                >
                                    <label for="input-bunus-amount">
                                        {{ $t('CREATE_SCHEDULE.BONUS_AMOUNT') }}
                                        <!-- <span class="text-danger">
                                            *
                                        </span> -->
                                    </label>
                                    <b-input-group class="mb-3">
                                        <b-form-input
                                            id="input-bunus-amount"
                                            v-model="isForm.bonus_amount"
                                            type="number"
                                        />
                                        <span class="freight-cost"> 円 </span>
                                    </b-input-group>

                                </b-col>
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
                                {{ $t('CREATE_SCHEDULE.NOTE') }}
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
        </b-container>
    </b-col>
</template>

<script>
import CONSTANT from '@/const';
import LineGray from '@/components/LineGray';
import TitlePathForm from '@/components/TitlePathForm';
import { setLoading } from '@/utils/handleLoading';
import SelectMultiple from '@/components/SelectMultiple';
// import { formatArray2Time } from '@/utils/convertTime';
// import { format2Digit } from '@/utils/generateTime';
// import NodeSchedule from '@/components/NodeSchedule';
// import { cleanObject } from '@/utils/handleObject';
// import { getCalendar } from '@/api/modules/calendar';
// import { getNumberDate, getTextDay } from '@/utils/convertTime';
import TOAST_SCHEDULE_MANAGEMENT from '@/toast/modules/courseManagement';
// import { getListSchedule, postImportFile, postListSchedule } from '@/api/modules/courseSchedule';
// import { validateSizeFile, validateFileCSV } from '@/utils/validate';
// import TOAST_SCHEDULE_SHIFT from '@/toast/modules/scheduleShift';
// import { convertTimeToSelect, convertBreakTimeNumberToTime } from '@/utils/convertTime';
// import { validateCourse } from '@/utils/validateCRUD';
import { postCourse } from '@/api/modules/courseSchedule';
import { getList } from '@/api/modules/courseManagement';

export default {
	name: 'ListSchedule',
	components: {
		LineGray,
		// NodeSchedule,
		TitlePathForm,
		SelectMultiple,
	},

	data() {
		return {
			bunus_target: '',
			bunusTargetOptions: [
				{ value: 'a', text: 'ボーナス対象者' },
				{ value: 'b', text: 'ボーナス対象者' },
				{ value: 'c', text: 'ボーナス対象者' },
			],

			isForm: {
				customer_id: '',
				course_name: '',
				ship_date: '',
				start_time: [null, null],
				end_time: [null, null],
				break_time: [null, null],
				customer_name: null,
				departure_place: '',
				arrival_place: '',
				freight_cost: '',
				payment_amount: '',
				hight_way: '',
				expenses: '',
				bonus_amount: '',
				note: '',
				optionListCustomer: [],
				listTime: [],
			},
		};
	},

	computed: {

	},

	watch: {

	},

	created() {
		this.initDate();
	},

	methods: {
		genereateOptionTime(min = 0, max = 23) {
			const result = [];

			for (let i = min; i <= max; i++) {
				result.push({
					value: i < 10 ? `0${i}` : `${i}`,
					text: i < 10 ? `0${i}` : `${i}`,
					disabled: false,
				});
			}

			return [result,
				[
					{
						value: '00',
						text: '00',
					},
					{
						value: '15',
						text: '15',
					},
					{
						value: '30',
						text: '30',
					},
					{
						value: '45',
						text: '45',
					},
				],
			];
		},

		formatter(arr) {
			for (let idx = 0; idx < arr.length; idx++) {
				if (!arr[idx]) {
					return arr.join('');
				}
			}

			return arr.join(':');
		},

		getSelectStartTime(select) {
			this.isForm.start_time = select;
		},

		getSelectEndTime(select) {
			this.isForm.end_time = select;
		},

		getSelectBreakTime(select) {
			this.isForm.break_time = select;
		},

		onClickReturn() {
			this.$router.push({ name: 'ListSchedule' });
		},

		async initDate() {
			await this.handleGetCustomer();
			this.isForm.listTime = this.genereateOptionTime();
		},

		async handleGetCustomer() {
			try {
				setLoading(true);
				const params = {};
				const LIST = await getList(CONSTANT.URL_API.GET_LIST_COURSE, params);
				if (LIST.code === 200) {
					this.isForm.optionListCustomer = [];
					LIST.data.forEach(item => {
						this.isForm.optionListCustomer.push({
							value: item.id,
							text: item.customer_name,
						});
					});
				} else {
					this.isForm.optionListCustomer = [];
				}
				setLoading(false);
			} catch {
				setLoading(false);
			}
		},

		async onClickSave() {
			console.log('isform:', this.isForm);
			setLoading(true);
			const course = {
				customer_id: this.isForm.customer_name,
				course_name: this.isForm.course_name,
				ship_date: this.isForm.ship_date,
				start_date: this.formatter(this.isForm.start_time),
				end_date: this.formatter(this.isForm.end_time),
				break_time: this.formatter(this.isForm.break_time) ? this.formatter(this.isForm.break_time) : '0.00',
				departure_place: this.isForm.departure_place,
				arrival_place: this.isForm.arrival_place,
				ship_fee: this.isForm.freight_cost,
				associate_company_fee: this.isForm.payment_amount,
				expressway_fee: this.isForm.hight_way,
				commission: this.isForm.expenses,
				meal_fee: this.isForm.bonus_amount,
				note: this.isForm.note,
			};
			// const VALIDATE = validateCourse(course);
			// console.log('data', VALIDATE);
			// if (VALIDATE.status) {
			try {
				const new_course = await postCourse(CONSTANT.URL_API.POST_COURSE_SCHEDULE, course);
				if (new_course.code === 200) {
					TOAST_SCHEDULE_MANAGEMENT.success();
					this.$router.push({ name: 'ListSchedule' });
					setLoading(false);
				}
			} catch (error) {
				console.log(error);
				setLoading(false);
			}
			// } else {
			// 	setLoading(false);
			// 	TOAST_SCHEDULE_MANAGEMENT.validate(VALIDATE.message);
			// }
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
                margin-top: 10px;
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

                .zone-form {
                    .item-form {
                    margin-bottom: 10px;
                    font-size: 18px;
                    width: 100%;
                    }

                    .select-multiple {
                        width: 100%;
                    }
                    .date{
                        padding: 0;
                    }
                    .freight-cost{
                        margin-left: 10px;
                        margin-top: 6px;
                    }
                }
            }
    }
</style>
