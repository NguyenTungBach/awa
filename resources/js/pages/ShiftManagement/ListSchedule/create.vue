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
                                            type="text"
                                        />
                                        <b-input-group-append>
                                            <b-form-datepicker
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
                                    <label for="input-date-date-of-birth">
                                        {{ $t('CREATE_SCHEDULE.COURSE_NAME') }}
                                        <span class="text-danger">
                                            *
                                        </span>
                                    </label>
                                    <b-input-group class="mb-3">
                                        <b-form-input
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
                                        <label for="input-date-date-of-birth">
                                            {{ $t('CREATE_SCHEDULE.START_TIME') }}
                                            <span class="text-danger">
                                                *
                                            </span>
                                        </label>
                                        <b-input-group class="mb-3">
                                            <b-form-input
                                                type="text"
                                            />
                                            <b-input-group-append>
                                                <b-form-timepicker
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
                                        :xl="6"
                                    >
                                        <label for="input-date-date-of-birth">
                                            {{ $t('CREATE_SCHEDULE.END_TIME') }}
                                            <span class="text-danger">
                                                *
                                            </span>
                                        </label>
                                        <b-input-group class="mb-3">
                                            <b-form-input
                                                type="text"
                                            />
                                            <b-input-group-append>
                                                <b-form-timepicker
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
                                        :xl="6"
                                    >
                                        <label for="input-date-date-of-birth">
                                            {{ $t('CREATE_SCHEDULE.BREAK_TIME') }}
                                            <span class="text-danger">
                                                *
                                            </span>
                                        </label>
                                        <b-input-group class="mb-3">
                                            <b-form-input
                                                type="text"
                                            />
                                            <b-input-group-append>
                                                <b-form-timepicker
                                                    button-only
                                                    right
                                                />
                                            </b-input-group-append>
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
                                    <label for="input-date-date-of-birth">
                                        {{ $t('CREATE_SCHEDULE.CUSTUM_NAME') }}
                                        <span class="text-danger">
                                            *
                                        </span>
                                    </label>
                                    <b-input-group class="mb-3">
                                        <b-form-input
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
                                    <label for="input-date-date-of-birth">
                                        {{ $t('CREATE_SCHEDULE.DEPATURE_PLACE') }}
                                        <span class="text-danger">
                                            *
                                        </span>
                                    </label>
                                    <b-input-group class="mb-3">
                                        <b-form-input
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
                                    <label for="input-date-date-of-birth">
                                        {{ $t('CREATE_SCHEDULE.ARRIVAL_PLACE') }}
                                        <span class="text-danger">
                                            *
                                        </span>
                                    </label>
                                    <b-input-group class="mb-3">
                                        <b-form-input
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
                                    <label for="input-date-date-of-birth">
                                        {{ $t('CREATE_SCHEDULE.FREIGHT_COST') }}
                                        <span class="text-danger">
                                            *
                                        </span>
                                    </label>
                                    <b-input-group class="mb-3">
                                        <b-form-input
                                            type="text"
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
                                        <span class="text-danger">
                                            *
                                        </span>
                                    </label>
                                    <b-input-group class="mb-3">
                                        <b-form-input
                                            type="text"
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
                                        <span class="text-danger">
                                            *
                                        </span>
                                    </label>
                                    <b-input-group class="mb-3">
                                        <b-form-input
                                            type="text"
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
                                        <span class="text-danger">
                                            *
                                        </span>
                                    </label>
                                    <b-input-group class="mb-3">
                                        <b-form-input
                                            type="text"
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
                                        <span class="text-danger">
                                            *
                                        </span>
                                    </label>
                                    <b-input-group class="mb-3">
                                        <b-form-input
                                            type="text"
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
// import CONSTANT from '@/const';
import LineGray from '@/components/LineGray';
import TitlePathForm from '@/components/TitlePathForm';
// import { setLoading } from '@/utils/handleLoading';
// import { format2Digit } from '@/utils/generateTime';
// import NodeSchedule from '@/components/NodeSchedule';
// import { cleanObject } from '@/utils/handleObject';
// import { getCalendar } from '@/api/modules/calendar';
// import { getNumberDate, getTextDay } from '@/utils/convertTime';
// import TOAST_SCHEDULE_MANAGEMENT from '@/toast/modules/scheduleManagement';
// import { getListSchedule, postImportFile, postListSchedule } from '@/api/modules/courseSchedule';
// import { validateSizeFile, validateFileCSV } from '@/utils/validate';
// import TOAST_SCHEDULE_SHIFT from '@/toast/modules/scheduleShift';

export default {
	name: 'ListSchedule',
	components: {
		LineGray,
		// NodeSchedule,
		TitlePathForm,
	},

	data() {
		return {
			bunus_target: '',
			bunusTargetOptions: [
				{ value: 'a', text: 'ボーナス対象者' },
				{ value: 'b', text: 'ボーナス対象者' },
				{ value: 'c', text: 'ボーナス対象者' },
			],
		};
	},

	computed: {

	},

	watch: {

	},

	created() {

	},

	methods: {
		onClickReturn() {
			this.$router.push({ name: 'ListSchedule' });
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
                    &__body {
                        .item-form {
                            margin-bottom: 10px;
                            font-size: 18px;
                        }

                        .select-multiple {
                            width: 100%;
                        }
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
