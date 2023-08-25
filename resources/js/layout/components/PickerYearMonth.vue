<template>
    <div class="picker-month-year">
        <div
            class="picker-month-year__back"
            @click="onClickBack()"
        >
            <i class="fas fa-angle-left" />
        </div>
        <div class="picker-month-year__time">
            {{ textFull }}
        </div>
        <div
            class="picker-month-year__next"
            @click="onClickNext()"
        >
            <i class="fas fa-angle-right" />
        </div>
    </div>
</template>

<script>
import { setLoading } from '@/utils/handleLoading';
const URL_API = {
	setupData: '/calendar/setup-data',
};
import { postSetupDataCalendar } from '@/api/modules/calendar';
import { convertMonth, convertYear, getFullText } from '@/utils/convertTime';
import { hasRole } from '@/utils/hasRole';

export default {
	name: 'PickerYearMonth',
	props: {
		initMonth: {
			type: Number,
			default: null,
			validate: (value) => {
				return value >= 1 && value <= 12;
			},
		},

		initYear: {
			type: Number,
			default: null,
			validate: (value) => {
				return value >= 1;
			},
		},
	},

	data() {
		return {
			isTime: {
				month: this.initData().month || this.initMonth,
				year: this.initData().year || this.initYear,
			},

			textMonth: '',
			textYear: '',
			textFull: '',
		};
	},

	computed: {
		lang() {
			return this.$store.getters.language;
		},

		clickBack() {
			return this.$store.getters.clickBackCalendarMonth;
		},

		clickNext() {
			return this.$store.getters.clickNextCalendarMonth;
		},

		currentRouter() {
			return this.$route;
		},
	},

	watch: {
		isTime: {
			async handler() {
				const d = new Date(this.isTime.year, this.isTime.month, 0);

				const DATA = {
					month: this.isTime.month,
					year: this.isTime.year,
					textMonth: convertMonth(this.isTime.month, this.lang),
					textYear: convertYear(this.isTime.year, this.lang),
					textFull: getFullText(this.isTime.month, this.isTime.year, this.lang),
					numberDate: d.getDate(),
				};

				this.storePickerYearMonth(DATA);
				this.getText();
			},

			deep: true,
		},

		clickBack() {
			this.onClickBack();
		},

		clickNext() {
			this.onClickNext();
		},
	},

	async created() {
		let DATA;

		const OLD_YEAR_MONTH = this.$store.getters.pickerYearMonth;

		if (OLD_YEAR_MONTH) {
			DATA = OLD_YEAR_MONTH;
		} else {
			const d = new Date(this.isTime.year, this.isTime.month, 0);

			DATA = {
				month: this.isTime.month,
				year: this.isTime.year,
				textMonth: convertMonth(this.isTime.month, this.lang),
				textYear: convertYear(this.isTime.year, this.lang),
				textFull: getFullText(this.isTime.month, this.isTime.year, this.lang),
				numberDate: d.getDate(),
			};
		}

		await this.handleSetupDataCalendar(this.isTime.year);

		this.storePickerYearMonth(DATA);
		this.getText();

		this.resetIndexPickerWeek();
	},

	methods: {
		initData() {
			const OLD_YEAR_MONTH = this.$store.getters.pickerYearMonth;

			let year;
			let month;

			if (OLD_YEAR_MONTH) {
				year = OLD_YEAR_MONTH.year;
				month = OLD_YEAR_MONTH.month;
			} else {
				const d = new Date();

				month = d.getMonth() + 1;
				year = d.getFullYear();
			}

			return {
				month,
				year,
			};
		},

		getText() {
			this.textMonth = convertMonth(this.isTime.month, this.lang);
			this.textYear = convertYear(this.isTime.year, this.lang);
			this.textFull = getFullText(this.isTime.month, this.isTime.year, this.lang);
		},

		resetIndexPickerWeek() {
			const PAGE_SAVE = ['ListShift'];

			if (!(PAGE_SAVE.includes(this.currentRouter.name))) {
				this.$store.dispatch('calendar/setIndexCalendarWeekListShift', null);
			}
		},

		async onClickBack() {
			this.resetIndexPickerWeek();

			if (this.isTime.month > 1) {
				this.isTime.month = this.isTime.month - 1;
			} else {
				setLoading(true);
				await this.handleSetupDataCalendar(this.isTime.year - 1);
				await this.handleSetupDataCalendar(this.isTime.year - 2);
				setLoading(false);

				this.isTime.month = 12;
				this.isTime.year = this.isTime.year - 1;
			}
		},

		async onClickNext() {
			this.resetIndexPickerWeek();

			if (this.isTime.month < 12) {
				this.isTime.month = this.isTime.month + 1;
			} else {
				setLoading(true);
				await this.handleSetupDataCalendar(this.isTime.year + 1);
				await this.handleSetupDataCalendar(this.isTime.year + 2);
				setLoading(false);

				this.isTime.month = 1;
				this.isTime.year = this.isTime.year + 1;
			}
		},

		storePickerYearMonth(DATA) {
			this.$store.dispatch('app/setPickerYearMonth', DATA);
		},

		async handleSetupDataCalendar(year) {
			const ROLE = this.$store.getters.profile.role;

			if (hasRole(ROLE)) {
				try {
					const BODY = {
						password: 'veho1234567890',
						targetyyyy: year,
					};

					await postSetupDataCalendar(URL_API.setupData, BODY);
				} catch {
					console.log('ERROR : Setup data calendar');
				}
			}
		},
	},
};
</script>

<style lang="scss" scoped>
    @import '@/scss/variables.scss';

    .picker-month-year {
        display: inline-flex;

        &__back,
        &__time,
        &__next {
            height: 32px;
            background-color: $white;

            display: flex;
            justify-content: center;
            align-items: center;

            font-size: 17px;
            color: $mine-shaft;
        }

        &__back,
        &__next {
            cursor: pointer;
            width: 50px;
            border-radius: 50%;

            &:hover {
                i {
                    color: $main;
                }
            }
        }

        &__back {
            margin-right: 5px;
        }

        &__next {
            margin-left: 5px;
        }

        &__time {
            width: 100%;
            min-width: 130px;
            border-radius: 10px;
            font-weight: bold;
            color: $mine-shaft;
        }

    }
	@media (max-width: 900px) {
			.picker-month-year {
			&__back,
			&__time,
			&__next {
				height: 32px;
				background-color: $wild-sand;

				display: flex;
				justify-content: center;
				align-items: center;

				font-size: 17px;
				color: $mine-shaft;
			}

		}
	}
</style>
