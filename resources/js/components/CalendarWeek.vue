<template>
    <div>
        <div class="calendar-week">
            <div class="calendar-week-body">
                <div
                    :class="['btn-back']"
                    @click="onClickBack"
                >
                    <i class="fas fa-angle-left" />
                </div>
                <div class="content">
                    <div class="time-start">
                        <div class="year">
                            <span>{{ selectedYMD.start.year }}年</span>
                        </div>
                        <div class="month-date">
                            <span>{{ format2Digit(selectedYMD.start.month) }}月{{ format2Digit(selectedYMD.start.date) }}日(月)</span>
                        </div>
                    </div>
                    <div class="splint">
                        <i class="far fa-minus" />
                    </div>
                    <div class="time-end">
                        <div class="year">
                            <span>{{ selectedYMD.end.year }}年</span>
                        </div>
                        <div class="month-date">
                            <span>{{ format2Digit(selectedYMD.end.month) }}月{{ format2Digit(selectedYMD.end.date) }}日(日)</span>
                        </div>
                    </div>
                </div>
                <div
                    id="popover-calendar"
                    class="btn-calendar"
                >
                    <i class="far fa-calendar-week" />
                </div>

                <div
                    :class="['btn-next']"
                    @click="onClickNext"
                >
                    <i class="fas fa-angle-right" />
                </div>
            </div>
        </div>
        <b-popover
            ref="popoverCalendar"
            target="popover-calendar"
            triggers="hover"
            placement="bottom"
        >
            <b-calendar
                v-model="value"
                :locale="lang"
                :date-disabled-fn="dateDisabled"
                :start-weekday="1"
                :date-format-options="{ month: '2-digit', day: '2-digit' }"
                hide-header
                no-key-nav
                :is-r-t-l="false"
                :label-help="$t('APP.LABLE_HELP_CALENDAR')"
                :initial-date="initialDate"
                no-highlight-today
                @selected="onSelect"
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
        </b-popover>
    </div>
</template>

<script>
export default {
	name: 'CalendarWeek',
	data() {
		return {
			value: null,
			compareTime: {
				start: null,
				end: null,
			},

			selectedYMD: {
				start: {
					year: null,
					month: null,
					date: null,
					text: '',
				},

				end: {
					year: null,
					month: null,
					date: null,
					text: '',
				},

				listDate: [],
			},

			listWeekSelect: [],
			indexSelected: null,
			initialDate: null,
		};
	},

	computed: {
		lang() {
			return this.$store.getters.language;
		},

		pickerYearMonth() {
			return this.$store.getters.pickerYearMonth;
		},

		selectedValue() {
			return this.value;
		},

		minTime() {
			if (this.listWeekSelect.length) {
				return this.listWeekSelect[0].start.text;
			}

			return null;
		},

		maxTime() {
			if (this.listWeekSelect.length) {
				return this.listWeekSelect[this.listWeekSelect.length - 1].end.text;
			}

			return null;
		},

		selectedYMDStart() {
			return this.selectedYMD.start;
		},

		indexCalendarWeekListShift() {
			return this.$store.getters.indexCalendarWeekListShift;
		},
	},

	watch: {
		pickerYearMonth: {
			handler() {
				this.initData();
			},

			deep: true,
		},

		selectedValue() {
			this.handleSelectStartEndDateWhenYMDChange();
		},

		selectedYMDStart: {
			handler() {
				this.selectedYMD.listDate = this.generateListDate();
				this.$emit('value', this.selectedYMD);
			},

			deep: true,
		},

		indexSelected: {
			handler() {
				this.$store.dispatch('calendar/setIndexCalendarWeekListShift', this.indexSelected);
			},

			deep: true,
		},
	},

	created() {
		this.initData();
	},

	methods: {
		generateListDate() {
			const result = [];

			let tmp = new Date(this.selectedYMD.start.text);
			const end = new Date(this.selectedYMD.end.text);

			while (tmp.getTime() <= end.getTime()) {
				result.push({
					year: tmp.getFullYear(),
					month: tmp.getMonth() + 1,
					date: tmp.getDate(),
					text: `${tmp.getFullYear()}-${this.format2Digit(tmp.getMonth() + 1)}-${this.format2Digit(tmp.getDate())}`,
				});

				tmp = new Date(tmp.getTime() + 1000 * 60 * 60 * 24);
			}

			return result;
		},

		async onClickBack() {
			if (this.indexSelected === 0) {
				await this.$store.dispatch('calendar/setClickBackCalendarMonth')
					.then(() => {
						this.initData('back');
					});
			} else {
				if (this.indexSelected >= 1) {
					this.indexSelected = this.indexSelected - 1;
					this.selectedYMD = this.listWeekSelect[this.indexSelected];

					this.value = `${this.selectedYMD.start.year}-${this.selectedYMD.start.month}-${this.selectedYMD.start.date}`;
				}
			}
		},

		async onClickNext() {
			if (this.indexSelected === this.listWeekSelect.length - 1) {
				await this.$store.dispatch('calendar/setClickNextCalendarMonth')
					.then(() => {
						this.initData('next');
					});
			} else {
				if (this.indexSelected < this.listWeekSelect.length - 1) {
					this.indexSelected = this.indexSelected + 1;
					this.selectedYMD = this.listWeekSelect[this.indexSelected];

					this.value = `${this.selectedYMD.start.year}-${this.selectedYMD.start.month}-${this.selectedYMD.start.date}`;
				}
			}
		},

		initData(event) {
			const TIME_PICKER = this.$store.getters.pickerYearMonth;
			this.initialDate = `${this.format2Digit(TIME_PICKER.year)}-${this.format2Digit(TIME_PICKER.month)}-01`;

			const COMPARE_TIME = this.compareTimeNowAndTimePicker();
			this.compareTime = COMPARE_TIME;

			if (COMPARE_TIME.year === 0 && COMPARE_TIME.month === 0) {
				this.processDataWithSameTime();
				this.handleSelectStartEndDate(event);
			} else {
				this.processDataWithOtherTime();
				this.handleSelectStartEndDate(event);
			}

			this.selectedYMD.listDate = this.generateListDate();
			this.value = `${this.selectedYMD.start.year}-${this.selectedYMD.start.month}-${this.selectedYMD.start.date}`;

			this.$emit('value', this.selectedYMD);
		},

		processDataWithSameTime() {
			const TIME_NOW = this.getDateNow();

			this.listWeekSelect = this.getWeekSelectWithDate(TIME_NOW);
		},

		processDataWithOtherTime() {
			const TIME_PICKER = this.$store.getters.pickerYearMonth;
			const GET_TIME_PICKER = this.getDatePicker(`${TIME_PICKER.year}/${TIME_PICKER.month}/01`);

			this.listWeekSelect = this.getWeekSelectWithDate(GET_TIME_PICKER);
		},

		handleSelectStartEndDate(event) {
			if (event === 'back') {
				this.selectedYMD = this.listWeekSelect[this.listWeekSelect.length - 2];
				this.indexSelected = this.listWeekSelect.length - 2;

				return;
			}

			if (event === 'next') {
				this.selectedYMD = this.listWeekSelect[1];
				this.indexSelected = 1;

				return;
			}

			if (this.indexCalendarWeekListShift) {
				const ITEM = this.listWeekSelect[this.indexCalendarWeekListShift];

				this.selectedYMD = ITEM;
				this.indexSelected = this.indexCalendarWeekListShift;

				return;
			}

			if (this.compareTime.year === 0 && this.compareTime.month === 0) {
				const TIME_NOW = this.getDateNow();
				const GET_DATE_TIME_NOW = new Date(`${TIME_NOW.year}/${TIME_NOW.month}/${TIME_NOW.date}`);
				const NOW = GET_DATE_TIME_NOW.getTime();

				const len = this.listWeekSelect.length;
				let idx = 0;

				while (idx < len) {
					const ITEM = this.listWeekSelect[idx];

					let START = new Date(ITEM.start.text);
					let END = new Date(ITEM.end.text);

					START = START.getTime();
					END = END.getTime();

					if (NOW >= START && NOW <= END) {
						this.selectedYMD = ITEM;
						this.indexSelected = idx;

						break;
					}

					idx++;
				}
			} else {
				if (this.compareTime.year === 0) {
					if (this.compareTime.month === -1) {
						this.selectedYMD = this.listWeekSelect[this.listWeekSelect.length - 1];
						this.indexSelected = this.listWeekSelect.length - 1;
					}

					if (this.compareTime.month === 1) {
						this.selectedYMD = this.listWeekSelect[0];
						this.indexSelected = 0;
					}
				}

				if (this.compareTime.year === -1) {
					this.selectedYMD = this.listWeekSelect[this.listWeekSelect.length - 1];
					this.indexSelected = this.listWeekSelect.length - 1;
				}

				if (this.compareTime.year === 1) {
					this.selectedYMD = this.listWeekSelect[0];
					this.indexSelected = 0;
				}
			}
		},

		handleSelectStartEndDateWhenYMDChange() {
			const GET_DATE_TIME_NOW = new Date(this.value);
			const NOW = GET_DATE_TIME_NOW.getTime();

			const len = this.listWeekSelect.length;
			let idx = 0;

			while (idx < len) {
				const ITEM = this.listWeekSelect[idx];

				let START = new Date(ITEM.start.text);
				let END = new Date(ITEM.end.text);

				START = START.getTime();
				END = END.getTime();

				if (NOW >= START && NOW <= END) {
					this.selectedYMD = ITEM;
					this.indexSelected = idx;

					break;
				}

				idx++;
			}
		},

		compareTimeNowAndTimePicker() {
			const TIME_NOW = new Date();
			const TIME_PICKER = this.$store.getters.pickerYearMonth;

			const year = TIME_NOW.getFullYear();
			const month = TIME_NOW.getMonth() + 1;

			const COMPARE = {
				year: null,
				month: null,
			};

			if (year > TIME_PICKER.year) {
				COMPARE.year = 1;
			}

			if (year === TIME_PICKER.year) {
				COMPARE.year = 0;

				if (month > TIME_PICKER.month) {
					COMPARE.month = -1;
				}

				if (month === TIME_PICKER.month) {
					COMPARE.month = 0;
				}

				if (month < TIME_PICKER.month) {
					COMPARE.month = 1;
				}
			}

			if (year < TIME_PICKER.year) {
				COMPARE.year = -1;
			}

			return COMPARE;
		},

		formatYMD(year, month, date) {
			if (year && month && date) {
				return `${year}/${month}/${date}`;
			}

			return '';
		},

		getDatePicker(date) {
			const d = new Date(date);

			return {
				year: d.getFullYear(),
				month: d.getMonth() + 1,
				date: d.getDate(),
				day: d.getDay(),
			};
		},

		getDateNow() {
			const d = new Date();

			return {
				year: d.getFullYear(),
				month: d.getMonth() + 1,
				date: d.getDate(),
				day: d.getDay(),
			};
		},

		getWeekSelectWithDate(date) {
			return this.getAllWeekCanSelect(date);
		},

		getAllWeekCanSelect(date) {
			const NUMBER_DATE = this.getNumberDate(date);
			let idx = 1;

			const LIST_WEEK = [];

			while (idx <= NUMBER_DATE) {
				const d = new Date(date.year, date.month - 1, idx);

				const day = d.getDay();

				if (day === 1) {
					// if (idx - 7 >= 0 && (idx + 6 <= NUMBER_DATE)) {
					// if (idx - 5 >= 0 && (idx + 6 <= NUMBER_DATE)) {
					if (idx + 6 <= NUMBER_DATE) {
						LIST_WEEK.push({
							start: {
								year: date.year,
								month: date.month,
								date: idx,
								text: `${date.year}/${this.format2Digit(date.month)}/${this.format2Digit(idx)}`,
							},
							end: {
								year: date.year,
								month: date.month,
								date: idx + 6,
								text: `${date.year}/${this.format2Digit(date.month)}/${this.format2Digit(idx + 6)}`,
							},
						});
					}
				}

				idx++;
			}

			const START = LIST_WEEK[0];

			if (START.start.date > 1) {
				const LAST_SATURDAY_OF_MONTH = this.getWeekEndOfBeforeMonth(date);

				LIST_WEEK.unshift({
					start: LAST_SATURDAY_OF_MONTH,
					end: {
						year: START.start.year,
						month: START.start.month,
						date: START.start.date - 1,
						text: `${START.start.year}/${this.format2Digit(START.start.month)}/${this.format2Digit(START.start.date - 1)}`,
					},
				});
			}

			const END = LIST_WEEK[LIST_WEEK.length - 1];

			if (END.end.date < NUMBER_DATE) {
				const NEXT_SATURDAY_OF_MONTH = this.getWeekStartOfNextMonth(date);

				LIST_WEEK.push({
					start: {
						year: END.end.year,
						month: END.end.month,
						date: END.end.date + 1,
						text: `${END.end.year}/${this.format2Digit(END.end.month)}/${this.format2Digit(END.end.date + 1)}`,
					},
					end: NEXT_SATURDAY_OF_MONTH,
				});
			}

			return LIST_WEEK;
		},

		getWeekEndOfBeforeMonth(date) {
			let NUMBER_DATE;
			let NEW_DATE;

			if (date.month === 1) {
				NEW_DATE = {
					year: date.year - 1,
					month: 12,
					date: 1,
				};

				NUMBER_DATE = this.getNumberDate(NEW_DATE);
			} else {
				NEW_DATE = {
					year: date.year,
					month: date.month - 1,
					date: 1,
				};

				NUMBER_DATE = this.getNumberDate(NEW_DATE);
			}

			let LAST_SATURDAY_OF_MONTH = null;
			let idx = 1;

			while (idx <= NUMBER_DATE) {
				const d = new Date(NEW_DATE.year, NEW_DATE.month - 1, idx);

				const day = d.getDay();

				if (day === 1) {
					LAST_SATURDAY_OF_MONTH = {
						year: NEW_DATE.year,
						month: NEW_DATE.month,
						date: idx,
						text: `${NEW_DATE.year}/${this.format2Digit(NEW_DATE.month)}/${this.format2Digit(idx)}`,
					};
				}

				idx++;
			}

			return LAST_SATURDAY_OF_MONTH;
		},

		getWeekStartOfNextMonth(date) {
			let NUMBER_DATE;
			let NEW_DATE;

			if (date.month === 12) {
				NEW_DATE = {
					year: date.year + 1,
					month: 1,
					date: 1,
				};

				NUMBER_DATE = this.getNumberDate(NEW_DATE);
			} else {
				NEW_DATE = {
					year: date.year,
					month: date.month + 1,
					date: 1,
				};

				NUMBER_DATE = this.getNumberDate(NEW_DATE);
			}

			let FIRST_FRIDAY_OF_MONTH = null;
			let idx = 1;

			while (idx <= NUMBER_DATE) {
				const d = new Date(NEW_DATE.year, NEW_DATE.month - 1, idx);

				const day = d.getDay();

				if (day === 0) {
					FIRST_FRIDAY_OF_MONTH = {
						year: NEW_DATE.year,
						month: NEW_DATE.month,
						date: idx,
						text: `${NEW_DATE.year}/${this.format2Digit(NEW_DATE.month)}/${this.format2Digit(idx)}`,
					};

					break;
				}

				idx++;
			}

			return FIRST_FRIDAY_OF_MONTH;
		},

		getNumberDate(date) {
			const d = new Date(date.year, date.month, 0);

			return d.getDate();
		},

		dateDisabled(ymd, date) {
			let ymdCalendar = new Date(ymd);
			let minDate = new Date(this.minTime);
			let maxDate = new Date(this.maxTime);

			ymdCalendar = ymdCalendar.getTime();
			minDate = minDate.getTime();
			maxDate = maxDate.getTime();

			const len = this.listWeekSelect.length;
			let idx = 0;

			while (idx < len) {
				let DATE_CHECK = new Date(this.listWeekSelect[idx].start.text);
				DATE_CHECK = DATE_CHECK.getTime();

				if (ymdCalendar === DATE_CHECK) {
					return false;
				}

				idx++;
			}

			if (ymdCalendar < minDate || ymdCalendar > maxDate) {
				return true;
			}

			const weekday = date.getDay();

			return [0, 2, 3, 4, 5, 6, 7].includes(weekday);
		},

		onSelect() {
			this.$refs.popoverCalendar.$emit('close');
		},

		format2Digit(number) {
			if (number <= 0) {
				return '00';
			}

			return number > 9 ? '' + number : '0' + number;
		},

		getDay(day) {
			const d = new Date(day);

			return d.getDay();
		},
	},
};
</script>

<style lang="scss" scoped>
@import '@/scss/variables';

.calendar-week {
    min-width: 280px;
    height: 38px;

    background-color: $white;

    border-radius: 38px;

    border: 1px solid $main;

    overflow: hidden;

    .calendar-week-body {
        display: flex;

        line-height: 38px;

        .btn-back,
        .btn-next {
            cursor: pointer;

            display: flex;
            justify-content: center;
            align-items: center;

            width: 40px;

            i {
                font-size: 20px;
                color: $main;
            }

            &:hover {
                background-color: $main;

                i {
                    color: $white;
                }
            }
        }

        .btn-back.disabled,
        .btn-next.disabled {
            cursor: not-allowed;

            opacity: 0.8;
        }

        .btn-calendar {
            cursor: pointer;

            width: 40px;

            display: flex;
            justify-content: center;
            align-items: center;

            i {
                font-size: 20px;
                color: $main;
            }
        }

        .content {
            display: flex;

            .time-start,
            .time-end {
                padding: 0 20px;
                min-width: 140px;

                .year {
                    height: 10px;
                    line-height: 10px;

                    span {
                        font-size: 10px;
                        color: $mine-shaft;
                        font-weight: bold;
                    }
                }

                .month-date {
                    height: 28px;
                    line-height: 28px;

                    span {
                        font-size: 14px;
                        color: $mine-shaft;
                        font-weight: bold;
                    }
                }
            }

            .splint{
                width: 20px;
                text-align: center;
                color: $main;
            }
        }
    }
}
</style>
