<template>
    <b-col>
        <b-container>
            <div class="page-create-driver">
                <div class="page-create-driver__header">
                    <b-row>
                        <b-col>
                            <div class="zone-title text-left">
                                <span
                                    v-show="isTab === 'BASIC'"
                                    class="title-page"
                                >
                                    {{ $t('CREATE_DRIVER.TITLE_CREATE_DRIVER') }}
                                </span>
                                <!-- <span
                                    v-show="isTab === 'COURSE'"
                                    class="title-page"
                                >
                                    {{ $t('CREATE_DRIVER.TITLE_EMPLOYEE_DETAILS') }}
                                </span> -->
                            </div>
                        </b-col>
                    </b-row>
                </div>
                <LineGray />
                <div class="page-create-driver__body">
                    <div class="zone-content">
                        <b-row class="mt-10 mb-10">
                            <b-col
                                :cols="12"
                                :sm="12"
                                :md="12"
                                :lg="12"
                                :xl="12"
                            >
                                <div
                                    v-show="isTab === 'BASIC'"
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
                                <div
                                    v-show="isTab === 'COURSE'"
                                    class="zone-control text-right"
                                >
                                    <b-button
                                        pill
                                        class="btn-edit"
                                        @click="onClickEditCourse()"
                                    >
                                        {{ $t('APP.BUTTON_EDIT') }}
                                    </b-button>
                                </div>
                            </b-col>
                        </b-row>
                        <b-tabs id="zone-tab" v-model="tabIndex">
                            <b-tab
                                :title="$t('CREATE_DRIVER.TAB_TITLE_BASIC_INFORMATION')"
                                :active="isTab === 'BASIC'"
                                class="title-tab"
                                @click="onClickTab('BASIC')"
                            >
                                <div class="tab-content">
                                    <b-row>
                                        <b-col
                                            :cols="12"
                                            :sm="12"
                                            :md="12"
                                            :lg="4"
                                            :xl="4"
                                        >
                                            <div class="zone-avatar text-center">
                                                <img
                                                    class="avatar-driver"
                                                    :src="require('@/assets/images/driver_icon.png')"
                                                    alt="Avatar Driver"
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
                                                {{ $t('CREATE_DRIVER.FORM_PATH_BASIC_INFORMATION') }}
                                            </TitlePathForm>
                                            <div class="item-form">
                                                <label for="input-empolyee-number">
                                                    {{ $t('CREATE_DRIVER.TYPE_DRIVER') }}
                                                    <span class="text-danger">
                                                        *
                                                    </span>
                                                </label>
                                                <b-form-group v-slot="{ ariaDescribedby }">
                                                    <b-form-radio-group
                                                        id="select-type-driver"
                                                        v-model="isForm.typeDriver"
                                                        :aria-describedby="ariaDescribedby"
                                                        name="select-type-driver"
                                                    >
                                                        <b-form-radio
                                                            v-for="typeDriver in isForm.optionsTypeDriver"
                                                            :key="typeDriver.value"
                                                            :value="typeDriver.value"
                                                        >
                                                            {{ $t(typeDriver.text) }}
                                                        </b-form-radio>
                                                    </b-form-radio-group>
                                                </b-form-group>
                                            </div>
                                            <div class="item-form">
                                                <b-row>
                                                    <b-col>
                                                        <label for="input-empolyee-number">
                                                            {{ $t('CREATE_DRIVER.EMPLOYEE_NUMBER') }}
                                                            <span class="text-danger">
                                                                *
                                                            </span>
                                                        </label>
                                                        <b-form-input
                                                            id="input-empolyee-number"
                                                            v-model="isForm.employeeNumber"
                                                            type="number"
                                                            @keydown.native="validInputNumber"
                                                        />
                                                    </b-col>
                                                </b-row>
                                            </div>
                                            <div class="item-form">
                                                <b-row>
                                                    <b-col>
                                                        <label for="input-fullname">
                                                            {{ $t('CREATE_DRIVER.FULL_NAME') }}
                                                            <span class="text-danger">
                                                                *
                                                            </span>
                                                        </label>
                                                        <b-form-input
                                                            id="input-fullname"
                                                            v-model="isForm.fullname"
                                                        />
                                                    </b-col>
                                                </b-row>
                                            </div>
                                            <div class="item-form">
                                                <b-row>
                                                    <b-col
                                                        :cols="12"
                                                        :sm="12"
                                                        :md="12"
                                                        :lg="6"
                                                        :xl="6"
                                                    >
                                                        <label for="input-date-hire-date">
                                                            {{ $t('CREATE_DRIVER.HIRE_DATE') }}
                                                            <span class="text-danger">
                                                                *
                                                            </span>
                                                        </label>
                                                        <b-input-group class="mb-3">
                                                            <b-form-input
                                                                id="input-date-hire-date"
                                                                v-model="isForm.hireDate"
                                                                type="text"
                                                                autocomplete="off"
                                                            />
                                                            <b-input-group-append>
                                                                <b-form-datepicker
                                                                    v-model="isForm.hireDate"
                                                                    button-only
                                                                    right
                                                                    :locale="language"
                                                                    aria-controls="input-date-hire-date"
                                                                    hide-header
                                                                    :is-r-t-l="false"
                                                                    :label-help="$t('APP.LABLE_HELP_CALENDAR')"
                                                                    :min="isForm.dateOfBirth"
                                                                    :max="isForm.retirementDate"
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
                                                            {{ $t('CREATE_DRIVER.DATE_OF_BIRTH') }}
                                                            <span class="text-danger">
                                                                *
                                                            </span>
                                                        </label>
                                                        <b-input-group class="mb-3">
                                                            <b-form-input
                                                                id="input-date-date-of-birth"
                                                                v-model="isForm.dateOfBirth"
                                                                type="text"
                                                                autocomplete="off"
                                                            />
                                                            <b-input-group-append>
                                                                <b-form-datepicker
                                                                    v-model="isForm.dateOfBirth"
                                                                    button-only
                                                                    right
                                                                    :locale="language"
                                                                    aria-controls="input-date-date-of-birth"
                                                                    hide-header
                                                                    :is-r-t-l="false"
                                                                    :label-help="$t('APP.LABLE_HELP_CALENDAR')"
                                                                    :max="isForm.hireDate"
                                                                />
                                                            </b-input-group-append>
                                                        </b-input-group>
                                                    </b-col>
                                                </b-row>

                                                <!-- <b-row>
                                                    <b-col>
                                                        <label for="input-grade">
                                                            {{ $t('CREATE_DRIVER.GRADE') }}
                                                            <span class="text-danger">
                                                                *
                                                            </span>
                                                        </label>
                                                        <b-form-input
                                                            id="input-grade"
                                                            v-model="isForm.grade"
                                                            type="number"
                                                            @keydown.native="validInputNumber"
                                                        />
                                                        <div v-show="isForm.grade" class="format-price">
                                                            {{ formatNumber(isForm.grade) }}
                                                        </div>
                                                    </b-col>
                                                </b-row> -->
                                            </div>
                                            <TitlePathForm>
                                                {{ $t('CREATE_DRIVER.FORM_PATH_WORKING_CONDITIONS') }}
                                            </TitlePathForm>
                                            <b-row>
                                                <b-col
                                                    :cols="12"
                                                    :sm="12"
                                                    :md="12"
                                                    :lg="12"
                                                    :xl="12"
                                                >
                                                    <div class="item-form">
                                                        <label for="input-available-days">
                                                            {{ $t('CREATE_DRIVER.AVAILABLE_DAYS') }}
                                                            <span class="text-danger">
                                                                *
                                                            </span>
                                                        </label>
                                                        <b-input-group :append="$t('CREATE_DRIVER.DAY')">
                                                            <b-form-select
                                                                id="input-available-days"
                                                                v-model="isForm.availableDays"
                                                                :options="isForm.optionsAvailableDays"
                                                            />
                                                        </b-input-group>
                                                    </div>
                                                </b-col>
                                                <!-- <b-col
                                                    :cols="12"
                                                    :sm="12"
                                                    :md="12"
                                                    :lg="8"
                                                    :xl="8"
                                                >
                                                    <div
                                                        class="item-form"
                                                        style="height: 70px;"
                                                    >
                                                        <label for="input-fixed-holidays">
                                                            {{ $t('CREATE_DRIVER.FIXED_HOLIDAYS') }}
                                                        </label>
                                                        <b-form-group
                                                            v-slot="{ ariaDescribedby }"
                                                            :key="reRender"
                                                        >
                                                            <b-form-checkbox-group
                                                                id="input-fixed-holidays"
                                                                v-model="isForm.seletedDateInWeek"
                                                                :options="isForm.optionsDateInWeek"
                                                                :aria-describedby="ariaDescribedby"
                                                                name="input-fixed-holidays"
                                                            />
                                                        </b-form-group>
                                                    </div>
                                                </b-col> -->
                                            </b-row>
                                            <TitlePathForm>
                                                {{ $t('CREATE_DRIVER.FORM_PATH_RETIREMENT_DATE') }}
                                            </TitlePathForm>
                                            <b-row>
                                                <b-col
                                                    :cols="12"
                                                    :sm="12"
                                                    :md="12"
                                                    :lg="6"
                                                    :xl="6"
                                                >
                                                    <div class="item-form">
                                                        <b-input-group class="mb-3">
                                                            <b-form-input
                                                                id="input-retirement-date"
                                                                v-model="isForm.retirementDate"
                                                                type="text"
                                                                autocomplete="off"
                                                            />
                                                            <b-input-group-append>
                                                                <b-form-datepicker
                                                                    v-model="isForm.retirementDate"
                                                                    button-only
                                                                    right
                                                                    :locale="language"
                                                                    aria-controls="input-retirement-date"
                                                                    hide-header
                                                                    :is-r-t-l="false"
                                                                    :label-help="$t('APP.LABLE_HELP_CALENDAR')"
                                                                    :min="isForm.hireDate"
                                                                    reset-button
                                                                    :reset-value="''"
                                                                    :label-reset-button="$t('APP.TEXT_RESET')"
                                                                />
                                                            </b-input-group-append>
                                                        </b-input-group>
                                                    </div>
                                                </b-col>
                                            </b-row>

                                            <b-row>
                                                <b-col>
                                                    <div class="item-form">
                                                        <label for="input-notes">
                                                            {{ $t('CREATE_DRIVER.NOTES') }}
                                                        </label>
                                                        <b-form-textarea
                                                            id="input-notes"
                                                            v-model="isForm.notes"
                                                            rows="6"
                                                            max-rows="12"
                                                        />
                                                    </div>
                                                </b-col>
                                            </b-row>
                                        </b-col>
                                    </b-row>
                                </div>
                            </b-tab>
                            
                        </b-tabs>
                    </div>
                </div>
            </div>
        </b-container>
    </b-col>
</template>

<script>
import CONSTANT from '@/const';
import LineGray from '@/components/LineGray';
import { postDriver } from '@/api/modules/driver';
import { validInputNumber } from '@/utils/handleInput';
import { setLoading } from '@/utils/handleLoading';
import { validateDriver } from '@/utils/validateCRUD';
import TitlePathForm from '@/components/TitlePathForm';
import TOAST_DRIVER from '@/toast/modules/driverManagement';
import { getTextShortDayByCodeDay } from '@/utils/convertTime';
import { formatNumber } from '@/utils/formatNumber';

export default {
	name: 'DriverCreate',
	components: {
		LineGray,
		TitlePathForm,
	},

	data() {
		return {
			CONSTANT,

			tabIndex: this.$store.getters.tabIndexDriver,
			isTab: 'BASIC',
			isDisableTabCourse: true,

			isForm: {
				typeDriver: null,
				employeeNumber: '',
				fullname: '',
				hireDate: '',
				dateOfBirth: '',
				grade: null,
				availableDays: null,
				seletedDateInWeek: [],
				retirementDate: '',
				notes: '',

				optionsTypeDriver: CONSTANT.LIST_DRIVER.LIST_FLAG,
				optionsAvailableDays: [],
				optionsDateInWeek: [],
				optionsWorkingTime: [],
			},

			listDriverCourse: [],

			reRender: 0,
		};
	},

	computed: {
		language() {
			return this.$store.getters.language;
		},

		fieldsListDriverCourse() {
			return [
				{ key: 'runable_course_id', label: this.$t('CREATE_DRIVER.TABLE_RUNABLE_COURSE_ID'), sortable: false, thClass: 'base-th th-course-id' },
				{ key: 'runable_course_name', label: this.$t('CREATE_DRIVER.TABLE_UNABLE_COURSE_NAME'), sortable: false, thClass: 'base-th' },
			];
		},

		selectedAvailableDays() {
			return this.isForm.availableDays;
		},

		selectedDateInWeek() {
			return this.isForm.seletedDateInWeek;
		},
	},

	watch: {
		tabIndex() {
			this.$store.dispatch('listDriver/setIndexTab', this.tabIndex);
		},

		isForm: {
			handler() {
				const seletedDateInWeek = JSON.parse(JSON.stringify(this.isForm.seletedDateInWeek));

				const DRIVER = {
					flag: this.isForm.typeDriver,
					driver_code: this.isForm.employeeNumber,
					driver_name: this.isForm.fullname,
					start_date: this.isForm.hireDate,
					end_date: this.isForm.retirementDate,
					birth_day: this.isForm.dateOfBirth,
					working_day: this.isForm.availableDays,
					day_of_week: this.handleDayOfWeek(seletedDateInWeek),
					note: this.isForm.notes,
				};

				const VALIDATE = validateDriver(DRIVER);

				this.isDisableTabCourse = !VALIDATE.status;
			},

			deep: true,
		},

		selectedAvailableDays() {
			// const DAY_WORK = this.isForm.availableDays ? parseInt(this.isForm.availableDays) : 0;
			// const DAY_OFF = this.isForm.seletedDateInWeek.length;

			// const TOTAL = DAY_WORK + DAY_OFF;

			// if (TOTAL > 7) {
			// 	this.isForm.seletedDateInWeek.length = 0;

			// 	const len = this.isForm.optionsDateInWeek.length;
			// 	let idx = 0;

			// 	while (idx < len) {
			// 		this.isForm.optionsDateInWeek[idx].disabled = false;

			// 		idx++;
			// 	}
			// } else if (TOTAL === 7) {
			// 	const len = this.isForm.optionsDateInWeek.length;
			// 	let idx = 0;

			// 	while (idx < len) {
			// 		if (!(this.isForm.seletedDateInWeek.includes(this.isForm.optionsDateInWeek[idx].value))) {
			// 			this.isForm.optionsDateInWeek[idx].disabled = true;
			// 		}

			// 		idx++;
			// 	}
			// } else {
			// 	const len = this.isForm.optionsDateInWeek.length;
			// 	let idx = 0;

			// 	while (idx < len) {
			// 		this.isForm.optionsDateInWeek[idx].disabled = false;

			// 		idx++;
			// 	}
			// }

			// this.reRender += 1;
		},

		selectedDateInWeek() {
			const DAY_WORK = this.isForm.availableDays ? parseInt(this.isForm.availableDays) : 0;
			const DAY_OFF = this.isForm.seletedDateInWeek.length;

			const TOTAL = DAY_WORK + DAY_OFF;

			if (TOTAL === 7) {
				const len = this.isForm.optionsDateInWeek.length;
				let idx = 0;

				while (idx < len) {
					if (!(this.isForm.seletedDateInWeek.includes(this.isForm.optionsDateInWeek[idx].value))) {
						this.isForm.optionsDateInWeek[idx].disabled = true;
					}

					idx++;
				}
			} else if (TOTAL < 7) {
				const len = this.isForm.optionsDateInWeek.length;
				let idx = 0;

				while (idx < len) {
					this.isForm.optionsDateInWeek[idx].disabled = false;

					idx++;
				}
			}

			this.reRender += 1;
		},
	},

	created() {
		this.$store.dispatch('listDriver/setIndexTab', 0);
		this.initData();
	},

	methods: {
		validInputNumber,
		formatNumber,

		initData() {
			this.generateOptionsAvailableDays();
			this.generateOptionDateInWeek();
			this.generateWorkingTime();
		},

		onClickReturn() {
			this.$router.push({ name: 'ListDriver' });
		},

		async onClickSave() {
			setLoading(true);

			const seletedDateInWeek = JSON.parse(JSON.stringify(this.isForm.seletedDateInWeek));

			const DRIVER = {
				flag: this.isForm.typeDriver,
				driver_code: this.isForm.employeeNumber,
				driver_name: this.isForm.fullname,
				start_date: this.isForm.hireDate,
				end_date: this.isForm.retirementDate,
				birth_day: this.isForm.dateOfBirth,
				grade: parseInt(this.isForm.grade),
				working_day: this.isForm.availableDays,
				day_of_week: this.handleDayOfWeek(seletedDateInWeek.sort()),
				note: this.isForm.notes,
			};

			const VALIDATE = validateDriver(DRIVER);

			if (VALIDATE.status) {
				try {
					const NEW_DRIVER = await postDriver(CONSTANT.URL_API.POST_DRIVER, DRIVER);

					if (NEW_DRIVER.code === 200) {
						TOAST_DRIVER.success();

						this.$router.push({ name: 'ListDriver' });
					}

					setLoading(false);
				} catch (error) {
					console.log(error);
					setLoading(false);
				}
			} else {
				setLoading(false);
				TOAST_DRIVER.validate(VALIDATE.message);
			}
		},

		handleFlag(list = []) {
			if (list.length > 0) {
				return list.join(',');
			}

			return null;
		},

		handleDayOfWeek(list = []) {
			const result = [];
			if (list.length > 0) {
				for (let day = 0; day < list.length; day++) {
					result.push(getTextShortDayByCodeDay(list[day]));
				}

				return result.length > 0 ? result.join(',') : null;
			}

			return '';
		},

		handleWorkingTime(list = []) {
			if (list.length > 0) {
				return list.join(',');
			}

			return '';
		},

		onClickEditCourse() {
			this.$router.push({ name: 'DriverCourseEdit', params: { id: 1 }});
		},

		generateOptionsAvailableDays() {
			const options = [
				{
					value: null,
					text: this.$t('APP.PLEASE_SELECT'),
				},
			];

			for (let i = 1; i <= 5; i++) {
				options.push({
					value: `${i}`,
					text: `${i}`,
				});
			}

			this.isForm.optionsAvailableDays = options;
		},

		generateOptionDateInWeek() {
			const LIBRARY = {
				1: 'MONDAY',
				2: 'TUESDAY',
				3: 'WEDNESDAY',
				4: 'THURSDAY',
				5: 'FRIDAY',
				6: 'SATURDAY',
				7: 'SUNDAY',
			};

			const options = [];

			for (let i = 1; i <= 7; i++) {
				options.push({
					value: i,
					text: this.$t(`CREATE_DRIVER.${LIBRARY[i]}`),
					disabled: false,
				});
			}

			this.isForm.optionsDateInWeek = options;
		},

		generateWorkingTime() {
			const options = CONSTANT.LIST_DRIVER.LIST_WORKING_TIME;

			this.isForm.optionsWorkingTime = options;
		},

		onClickTab(tab) {
			if (tab === 'COURSE' && this.isDisableTabCourse) {
				TOAST_DRIVER.warning('MESSAGE_APP.DRIVER_MANAGEMENT_CLICK_TAB_COURSE');

				return;
			}

			if (['BASIC', 'COURSE'].includes(tab)) {
				this.isTab = tab;
			} else {
				this.isTab = 'BASIC';
			}
		},

		handleDelete(scope) {

		},
	},
};
</script>

<style lang="scss" scoped>
    @import '@/scss/variables';

    .page-create-driver {
        &__header {
            .zone-title {
                .title-page {
                    font-size: 25px;
                }
            }
        }

        &__body {
            .zone-content {
                .zone-control {

                    .btn-return,
                    .btn-save,
                    .btn-edit {
                        &:hover {
                            opacity: 0.8;
                        }
                    }

                    .btn-save,
                    .btn-edit {
                        background-color: $main;
                        color: $white;

                        border-color: transparent;
                    }
                }
                .tab-content {
                    .zone-avatar {
                        display: flex;
                        height: 100%;
                        justify-content: center;
                        align-items: center;

                        .avatar-driver {
                            height: 270px;
                        }
                    }

                    border-left: 1px solid $geyser;
                    border-bottom: 1px solid $geyser;
                    border-right: 1px solid $geyser;

                    padding: 10px 20px;

                    .item-form {
                        margin: 10px 0;

                        font-size: 18px;
                    }

                    ::v-deep .zone-table {
                        height: calc(100vh - 250px);
                        overflow-y: auto;

                        th {
                            position: sticky !important;
                            top: 0;
                            z-index: 10;
                        }

                        .base-th {
                            background-color: $main;
                            color: $white;
                        }

                        .base-th-control {
                            width: 90px;
                        }

                        .base-td-control {
                            i {
                                color: $dusty-gray;
                                font-size: 24;

                                cursor: pointer;
                            }
                        }

                        td {
                            z-index: 3;
                        }
                    }
                }
            }
        }
    }

    ::v-deep .th-course-id {
        width: 180px;
    }

    .format-price {
        margin-top: 10px;
        font-size: 14px;
    }
</style>
