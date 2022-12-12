<template>
    <div class="dev">
        <b-col>
            <div class="dev__language">
                <h2>Languages</h2>
            </div>
        </b-col>

        <b-col>
            <b-row class="mt-2">
                <b-col>
                    <div :class="{ 'dev__btn-lang': true, 'dev__choose-lang': language === 'en' }">
                        <b-button @click="setLanguage('en')">
                            English
                        </b-button>
                    </div>
                </b-col>

                <b-col>
                    <div :class="{ 'dev__btn-lang': true, 'dev__choose-lang': language === 'ja' }">
                        <b-button @click="setLanguage('ja')">
                            Japanese
                        </b-button>
                    </div>
                </b-col>
            </b-row>

            <b-row class="mt-2">
                <b-col>
                    <p>Select Time</p>
                    <SelectMultiple
                        :options="options"
                        :value="[null, null]"
                        :formatter="formatter"
                        @select="getSelectValue"
                    />
                </b-col>

                <b-col>
                    <p>Select Group</p>
                    <SelectMultiple
                        :options="optionsAZ"
                        :value="[null, null]"
                        :formatter="formatterAZ"
                        @select="getSelectValueAZ"
                    />
                </b-col>
            </b-row>

            <b-row class="mt-2">
                <b-col>
                    <CalendarMultipleWeek />
                </b-col>
            </b-row>

            <b-row class="mt-2">
                <b-col>
                    <CalendarMonth />
                </b-col>
            </b-row>

            <b-row class="mt-2">
                <b-col>
                    <CalendarFreeMonth @change="getValue" />
                </b-col>
            </b-row>
        </b-col>
    </div>
</template>

<script>
import Toast from '@/toast/notification';

import SelectMultiple from '@/components/SelectMultiple';
import CalendarMultipleWeek from '@/components/CalendarMultipleWeek';
import CalendarMonth from '@/components/CalendarMonth';
import CalendarFreeMonth from '@/components/CalendarFreeMonth';

export default {
	name: 'PageDev',
	components: {
		SelectMultiple,
		CalendarMultipleWeek,
		CalendarMonth,
		CalendarFreeMonth,
	},

	data() {
		return {
			options: [],
			select: [],
			selectAZ: [],
			listAZ: [
				'A',
				'B',
				'C',
				'D',
				'E',
				'F',
				'G',
				'H',
				'I',
				'J',
				'K',
				'L',
				'M',
				'N',
				'O',
				'P',
				'Q',
				'R',
				'S',
				'T',
				'U',
				'V',
				'W',
				'X',
				'Y',
				'Z',
			],

			optionsAZ: [],
		};
	},

	computed: {
		language() {
			return this.$store.getters.language;
		},
	},

	created() {
		this.options = this.genereateOption();
		this.optionsAZ = this.genereateOptionAZ();
	},

	methods: {
		getValue(time) {
			console.log(time);
		},

		genereateOption(min = 1, max = 24) {
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

		genereateOptionAZ() {
			const len = this.listAZ.length;

			let idx = 0;

			const result = [];

			while (idx < len) {
				result.push({
					value: this.listAZ[idx],
					text: this.listAZ[idx],
					disabled: false,
				});

				idx++;
			}

			return [result, result];
		},

		formatter(arr) {
			for (let idx = 0; idx < arr.length; idx++) {
				if (!arr[idx]) {
					return arr.join('');
				}
			}

			return arr.join(':');
		},

		formatterAZ(arr) {
			return arr.join('');
		},

		getSelectValue(select) {
			this.select = select;
		},

		getSelectValueAZ(select) {
			this.selectAZ = select;
		},

		setLanguage(lang) {
			this.$store.dispatch('app/setLanguage', lang)
				.then(() => {
					this.$i18n.locale = lang;

					if (lang === 'en') {
						Toast.success('Change language successfully');
					} else {
						Toast.success('言語を正常に変更');
					}
				})
				.catch(() => {
					if (lang === 'en') {
						Toast.error('Language change failed');
					} else {
						Toast.error('言語の変更に失敗しました');
					}
				});
		},
	},
};
</script>

<style lang="scss" scoped>
    @import '@/scss/variables';

    .dev {
        &__language {
            text-align: center;
        }

        &__btn-lang {
            text-align: center;

            button {
                min-width: 150px;
                border: none;

                &:active {
                    background-color: $main !important;
                }
            }
        }

        &__choose-lang {
            button {
                background-color: $main;
            }
        }
    }
  </style>

