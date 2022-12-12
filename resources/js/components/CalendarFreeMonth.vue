<template>
    <div class="component-calendar-month">
        <div class="calendar-month">
            <b-row>
                <b-col cols="2">
                    <div class="clear-height center-all text-center btn-remove">
                        <i
                            class="fa-regular fa-trash-can"
                            @click="onClickRemove"
                        />
                    </div>
                </b-col>

                <b-col cols="8">
                    <b-row>
                        <b-col cols="5">
                            <div class="center-all">
                                <template v-if="selectedTime.start">
                                    <div class="show-year">
                                        {{ getYMD(selectedTime.start).year }}年
                                    </div>
                                    <div class="show-month-date">
                                        {{ format2Digit(getYMD(selectedTime.start).month) }}月{{ format2Digit(getYMD(selectedTime.start).date) }}日({{ getTextCodeDay(getYMD(selectedTime.start).day) }})
                                    </div>
                                </template>
                                <template v-else>
                                    <div class="show-year">
                                        {{ $t('APP.TEXT_CALENDAR_PLACEHOLDER') }}
                                    </div>
                                    <div class="show-month-date">
                                        {{ $t('APP.TEXT_CALENDAR_PLACEHOLDER') }}
                                    </div>
                                </template>
                            </div>
                        </b-col>

                        <b-col cols="2">
                            <div class="clear-height center-all icon-split text-center">
                                <i class="fas fa-minus" />
                            </div>
                        </b-col>

                        <b-col cols="5">
                            <div class="center-all">
                                <template v-if="selectedTime.end">
                                    <div class="show-year">
                                        {{ getYMD(selectedTime.end).year }}年
                                    </div>
                                    <div class="show-month-date">
                                        {{ format2Digit(getYMD(selectedTime.end).month) }}月{{ format2Digit(getYMD(selectedTime.end).date) }}日({{ getTextCodeDay(getYMD(selectedTime.end).day) }})
                                    </div>
                                </template>
                                <template v-else>
                                    <div class="show-year">
                                        {{ $t('APP.TEXT_CALENDAR_PLACEHOLDER') }}
                                    </div>
                                    <div class="show-month-date">
                                        {{ $t('APP.TEXT_CALENDAR_PLACEHOLDER') }}
                                    </div>
                                </template>
                            </div>
                        </b-col>
                    </b-row>
                </b-col>

                <b-col cols="2">
                    <div class="clear-height center-all text-center btn-calendar">
                        <i
                            :id="idCalendar"
                            class="fa-solid fa-calendar-day"
                        />
                    </div>
                </b-col>
            </b-row>
        </div>

        <b-popover
            :target="idCalendar"
            triggers="hover"
            placement="bottom"
            custom-class="popover-calendar-free-month"
        >
            <div class="picker-calendar-month">
                <b-row>
                    <b-col>
                        <b-calendar
                            v-model="selectedTime.start"
                            hide-header
                            :date-format-options="configCalendar.format"
                            :label-help="$t('APP.LABLE_HELP_CALENDAR')"
                            :start-weekday="1"
                            :locale="lang"
                            :min="selectedTime.start"
                            :max="selectedTime.end"
                            no-key-nav
                            :initial-date="configCalendar.initialDate"
                            :date-disabled-fn="handleDisabled"
                            no-highlight-today
                        />
                    </b-col>

                    <b-col>
                        <b-calendar
                            v-model="selectedTime.end"
                            hide-header
                            :date-format-options="configCalendar.format"
                            :label-help="$t('APP.LABLE_HELP_CALENDAR')"
                            :start-weekday="1"
                            :locale="lang"
                            :min="selectedTime.start"
                            :max="selectedTime.end"
                            no-key-nav
                            :initial-date="configCalendar.initialDate"
                            :date-disabled-fn="handleDisabled"
                            no-highlight-today
                        />
                    </b-col>
                </b-row>
            </div>
        </b-popover>
    </div>
</template>

<script>
import {
	getYMD,
	getTextCodeDay,
	getTextShortDay,
	getNumberDate,
} from '@/utils/convertTime';

export default {
	name: 'CalendarFreeMonth',
	props: {
		idCalendar: {
			type: String,
			required: false,
			default: 'calendar-free-month',
		},
	},

	data() {
		return {
			selectedTime: {
				start: null,
				end: null,
			},

			configCalendar: {
				format: { month: '2-digit', day: '2-digit' },
				min: null,
				max: null,
				initialDate: null,
			},

			disabledCalendar: false,
		};
	},

	computed: {
		lang() {
			return this.$store.getters.language;
		},

		pickerYearMonth() {
			return this.$store.getters.pickerYearMonth;
		},
	},

	watch: {
		pickerYearMonth: {
			handler: function() {
				this.handleInitalDate();
				this.onClickRemove();
			},

			deep: true,
		},

		selectedTime: {
			handler: function() {
				this.$emit('change', this.selectedTime);
			},

			deep: true,
		},
	},

	created() {
		this.initData();
	},

	methods: {
		getYMD,
		getTextCodeDay,
		getTextShortDay,
		format2Digit(number) {
			if (number <= 0) {
				return '00';
			}

			return number > 9 ? '' + number : '0' + number;
		},

		initData() {
			this.handleInitalDate();
		},

		onClickRemove() {
			this.selectedTime = {
				start: null,
				end: null,
			};
		},

		handleInitalDate() {
			const PICKER = this.$store.getters.pickerYearMonth;
			const GET_NUMBER_DATE = getNumberDate({ year: PICKER.year, month: PICKER.month });

			this.configCalendar.min = `${this.format2Digit(PICKER.year)}-${this.format2Digit(PICKER.month)}-01`;
			this.configCalendar.initialDate = `${this.format2Digit(PICKER.year)}-${this.format2Digit(PICKER.month)}-01`;
			this.configCalendar.max = `${this.format2Digit(PICKER.year)}-${this.format2Digit(PICKER.month)}-${this.format2Digit(GET_NUMBER_DATE)}`;
		},

		handleDisabled(ymd, date) {
			const PICKER = this.$store.getters.pickerYearMonth;
			const GET_NUMBER_DATE = getNumberDate({ year: PICKER.year, month: PICKER.month });

			const min = new Date(PICKER.year, PICKER.month - 1, 1, 0, 0, 0, 0);
			const max = new Date(PICKER.year, PICKER.month - 1, GET_NUMBER_DATE, 0, 0, 0, 0);

			const _min = min.getTime();
			const _max = max.getTime();

			const _date = date.getTime();

			return !(_date >= _min && _date <= _max);
		},
	},
};
</script>

<style lang="scss" scoped>
@import '@/scss/variables';

.component-calendar-month {
    display: flex;
    align-content: center;
    align-items: center;
    justify-content: center;

    .calendar-month {
        min-width: 400px;
        max-width: 400px;
        height: 40px;
        border: 1px solid $main;
        border-radius: 40px;
        overflow: hidden;

        .clear-height {
            height: 40px;
        }

        .center-all {
            line-height: 40px;
        }

        .btn-remove {
            font-size: 20px;
            padding-left: 20px;

            i {
                color: $punch;
                cursor: pointer;
            }
        }

        .icon-split {
            color: $main;
            font-size: 20px;
            font-weight: bold;
        }

        .show-year {
            line-height: 15px;

            font-size: 10px;

            font-weight: bold;
        }

        .show-month-date {
            line-height: 25px;

            font-size: 12px;

            font-weight: bold;
        }

        .btn-calendar {
            font-size: 20px;
            padding-right: 20px;

            i {
                color: $main;
                cursor: pointer;
            }
        }
    }
}

.picker-calendar-month {
    ::v-deep .b-calendar-nav {
        visibility: hidden;
        height: 0;
    }
}
</style>
