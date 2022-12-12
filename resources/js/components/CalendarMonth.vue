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
        >
            <div class="picker-calendar-month">
                <b-calendar
                    v-model="selectedTime.start"
                    hide-header
                    :date-format-options="configCalendar.format"
                    :label-help="$t('APP.LABLE_HELP_CALENDAR')"
                    :start-weekday="1"
                    :locale="lang"
                    :min="configCalendar.min"
                    :max="configCalendar.max"
                    no-key-nav
                    :date-disabled-fn="disabledPastDay"
                    :initial-date="configCalendar.initialDate"
                    no-highlight-today
                />
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
	name: 'CalendarMonth',
	props: {
		idCalendar: {
			type: String,
			required: false,
			default: 'calendar-month',
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
				this.selectedTime.start = null;
				this.handleSetEndDate();
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
			this.handleSetEndDate();
		},

		onClickRemove() {
			this.selectedTime = {
				start: null,
				end: null,
			};

			this.handleSetEndDate();
		},

		handleInitalDate() {
			const PICKER = this.$store.getters.pickerYearMonth;

			this.configCalendar.initialDate = `${this.format2Digit(PICKER.year)}-${this.format2Digit(PICKER.month)}-01`;
		},

		disabledPastDay(ymd, date) {
			this.configCalendar.min = `${this.pickerYearMonth.year}-${this.pickerYearMonth.month}-1`;
			const ymdNow = new Date();
			const now = new Date(ymdNow.getFullYear(), ymdNow.getMonth(), ymdNow.getDate(), 0, 0, 0, 0);
			const _now = now.getTime();
			const _date = date.getTime();

			const ymdEndMonth = new Date(this.pickerYearMonth.year, this.pickerYearMonth.month - 1, this.pickerYearMonth.numberDate, 0, 0, 0, 0);
			const _ymdEndMonth = ymdEndMonth.getTime();

			if (_now > _ymdEndMonth) {
				return true;
			} else {
				this.configCalendar.max = `${ymdEndMonth.getFullYear()}-${ymdEndMonth.getMonth() + 1}-${getNumberDate({ year: ymdEndMonth.getFullYear(), month: ymdEndMonth.getMonth() + 1 })}`;
			}

			return _date < _now;
		},

		handleSetEndDate() {
			const ymdNow = new Date();
			const now = new Date(ymdNow.getFullYear(), ymdNow.getMonth(), ymdNow.getDate(), 0, 0, 0, 0);
			const _now = now.getTime();

			const ymdEndMonth = `${this.pickerYearMonth.year}-${this.pickerYearMonth.month}-${this.pickerYearMonth.numberDate}`;
			const _ymdEndMonth = new Date(this.pickerYearMonth.year, this.pickerYearMonth.month - 1, this.pickerYearMonth.numberDate, 0, 0, 0, 0);

			if (_now > _ymdEndMonth.getTime()) {
				this.selectedTime.end = null;
			} else {
				this.selectedTime.end = ymdEndMonth;
			}
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
