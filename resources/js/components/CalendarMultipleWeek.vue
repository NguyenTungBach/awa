<template>
    <div class="component-calendar-multiple-week">
        <div class="calendar-multiple-week">
            <b-row>
                <b-col cols="2">
                    <div class="clear-height center-all text-center icon-delete">
                        <i class="far fa-trash-alt" @click="resetData()" />
                    </div>
                </b-col>
                <b-col cols="8">
                    <b-row>
                        <b-col cols="5">
                            <div class="center-all show-year-month-date">
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
                            <div class="center-all show-year-month-date show-year-month-date-right">
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
                    <div :id="idCalendar" class="clear-height center-all text-center icon-calendar">
                        <i class="fas fa-calendar-week" />
                    </div>
                </b-col>
            </b-row>
        </div>

        <b-popover
            :target="idCalendar"
            triggers="hover"
            placement="bottom"
            custom-class="popover-calendar-multiple-week"
        >
            <b-row>
                <b-col cols="6">
                    <b-calendar
                        v-model="selectedTime.start"
                        hide-header
                        :date-format-options="configCalendar.format"
                        :label-help="$t('APP.LABLE_HELP_CALENDAR')"
                        :start-weekday="1"
                        :locale="lang"
                        :min="configCalendar.min"
                        :max="configCalendar.max"
                        :date-disabled-fn="dateDisabledStart"
                    >
                        <template #nav-this-month>
                            <i class="fas fa-calendar-day" />
                        </template>

                        <template #nav-next-year>
                            <i class="fas fa-chevron-double-right" />
                        </template>

                        <template #nav-prev-year>
                            <i class="fas fa-chevron-double-left" />
                        </template>

                        <template #nav-next-month>
                            <i class="fas fa-chevron-right" />
                        </template>

                        <template #nav-prev-month>
                            <i class="fas fa-chevron-left" />
                        </template>
                    </b-calendar>
                </b-col>

                <b-col cols="6">
                    <b-calendar
                        v-model="selectedTime.end"
                        hide-header
                        :date-format-options="configCalendar.format"
                        :label-help="$t('APP.LABLE_HELP_CALENDAR')"
                        :start-weekday="1"
                        :locale="lang"
                        :min="configCalendar.min"
                        :max="configCalendar.max"
                        :date-disabled-fn="dateDisabledEnd"
                    >
                        <template #nav-this-month>
                            <i class="fas fa-calendar-day" />
                        </template>

                        <template #nav-next-year>
                            <i class="fas fa-chevron-double-right" />
                        </template>

                        <template #nav-prev-year>
                            <i class="fas fa-chevron-double-left" />
                        </template>

                        <template #nav-next-month>
                            <i class="fas fa-chevron-right" />
                        </template>

                        <template #nav-prev-month>
                            <i class="fas fa-chevron-left" />
                        </template>
                    </b-calendar>
                </b-col>
            </b-row>
        </b-popover>
    </div>
</template>

<script>
import { getYMD, getTextCodeDay } from '@/utils/convertTime';

export default {
	name: 'CalendarMultipleWeek',
	props: {
		idCalendar: {
			type: String,
			required: false,
			default: 'calendar-multiple-week',
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
			},
		};
	},

	computed: {
		lang() {
			return this.$store.getters.language;
		},

		isSelectedStartEnd() {
			return this.selectedTime.start && this.selectedTime.end;
		},

		isSelectedStart() {
			return this.selectedTime.start;
		},

		isSelectedEnd() {
			return this.selectedTime.end;
		},
	},

	watch: {
		isSelectedStart() {
			if (this.selectedTime.start) {
				this.configCalendar.min = this.selectedTime.start;
				this.configCalendar.max = this.getTimeWith4Week('start', this.selectedTime.start);

				if (this.selectedTime.end) {
					const endTime = new Date(this.selectedTime.end).getTime();
					const minTime = new Date(this.configCalendar.min).getTime();
					const maxTime = new Date(this.configCalendar.max).getTime();

					if (endTime > maxTime || endTime < minTime) {
						this.selectedTime.end = null;
					}

					if (this.selectedTime.start && this.selectedTime.end) {
						const startTime = new Date(this.selectedTime.start).getTime();
						const endTime = new Date(this.selectedTime.end).getTime();

						if (startTime > endTime) {
							this.selectedTime.end = null;
						}
					}
				}
			}

			this.emitValue();
		},

		isSelectedEnd() {
			if (this.selectedTime.end) {
				this.configCalendar.min = this.getTimeWith4Week('end', this.selectedTime.end);
				this.configCalendar.max = this.selectedTime.end;

				if (this.selectedTime.start) {
					const startTime = new Date(this.selectedTime.start).getTime();
					const minTime = new Date(this.configCalendar.min).getTime();
					const maxTime = new Date(this.configCalendar.max).getTime();

					if (startTime > maxTime || startTime < minTime) {
						this.selectedTime.start = null;
					}

					if (this.selectedTime.start && this.selectedTime.end) {
						const startTime = new Date(this.selectedTime.start).getTime();
						const endTime = new Date(this.selectedTime.end).getTime();

						if (startTime > endTime) {
							this.selectedTime.start = null;
						}
					}
				}
			}

			this.emitValue();
		},
	},

	methods: {
		getYMD,
		getTextCodeDay,
		format2Digit(number) {
			if (number <= 0) {
				return '00';
			}

			return number > 9 ? '' + number : '0' + number;
		},

		dateDisabledStart(ymd, date) {
			const weekday = date.getDay();

			return weekday !== 6;
		},

		dateDisabledEnd(ymd, date) {
			const weekday = date.getDay();

			return weekday !== 5;
		},

		emitValue() {
			const CALENDAR = {
				start: this.selectedTime.start,
				end: this.selectedTime.end,
			};

			this.$emit('change', CALENDAR);
		},

		resetData() {
			const CALENDAR = {
				start: null,
				end: null,
			};

			const CONFIG_CALENDAR = {
				format: { month: '2-digit', day: '2-digit' },
				min: null,
				max: null,
			};

			this.selectedTime = CALENDAR;
			this.configCalendar = CONFIG_CALENDAR;
		},

		getTimeWith4Week(type, time) {
			const _time = new Date(time);
			const time2Miniseconds = _time.getTime();

			let timeNew = null;

			if (type === 'start') {
				timeNew = time2Miniseconds + ((24 * 60 * 60 * 1000) * 27);
			}

			if (type === 'end') {
				timeNew = time2Miniseconds - ((24 * 60 * 60 * 1000) * 27);
			}

			const d = new Date(timeNew);

			const year = d.getFullYear();
			const month = this.format2Digit(d.getMonth() + 1);
			const day = this.format2Digit(d.getDate());

			return `${year}-${month}-${day}`;
		},
	},
};
</script>

<style lang="scss" scoped>
@import '@/scss/variables';

.component-calendar-multiple-week {
    display: flex;
    justify-content: center;
}

.calendar-multiple-week {
    min-width: 400px;
    max-width: 400px;
    height: 40px;

    background-color: $white;

    border: 1px solid $main;
    border-radius: 40px;

    overflow: hidden;

    .clear-height {
        height: 40px;
    }

    .center-all {
        line-height: 40px;
    }

    .icon-left {
        text-align: left;
    }

    .icon-right {
        text-align: right;
    }

    .base-icon {
        color: $main;

        &:hover {
            cursor: pointer;

            background-color: $main;
            color: $white;
        }
    }

    .icon-back {
        text-align: center;

    }

    .icon-next {
        text-align: center;
    }

    .icon-split {
        color: $main;

        font-size: 20px;
    }

    .icon-calendar {
        color: $main;

        font-size: 20px;

        padding-right: 20px;
    }

    .icon-delete {
        color: $punch;

        font-size: 20px;

        padding-left: 10px;

        cursor: pointer;
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
}

.date-selected {
    background-color: $main;
    color: $white;
}

.control-disabled {
    cursor: not-allowed !important;
    background-color: $white !important;
    color: $main !important;
}
</style>
