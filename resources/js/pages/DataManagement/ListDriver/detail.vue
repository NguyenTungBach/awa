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
                                    {{ $t('DETAIL_DRIVER.TITLE_DETAIL_DRIVER') }}
                                </span>
                                <span
                                    v-show="isTab === 'COURSE'"
                                    class="title-page"
                                >
                                    {{ $t('DETAIL_DRIVER.TITLE_DETAIL_DRIVER') }}
                                </span>
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
                                        class="btn-edit"
                                        @click="onClickEdit()"
                                    >
                                        {{ $t('APP.BUTTON_EDIT') }}
                                    </b-button>
                                </div>
                                <div
                                    v-show="isTab === 'COURSE'"
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
                                        class="btn-edit"
                                        @click="onClickEditCourse()"
                                    >
                                        {{ $t('APP.BUTTON_EDIT') }}
                                    </b-button>
                                </div>
                            </b-col>
                        </b-row>
                        <b-tabs v-model="tabIndex">
                            <b-tab
                                :title="$t('CREATE_DRIVER.TAB_TITLE_BASIC_INFORMATION')"
                                :active="isTab === 'BASIC'"
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
                                                {{ $t('DETAIL_DRIVER.FORM_PATH_BASIC_INFORMATION') }}
                                            </TitlePathForm>

                                            <div class="item-form">
                                                <p>{{ $t(isForm.typeDriver) }}</p>
                                            </div>

                                            <div class="item-form">
                                                <b-row>
                                                    <b-col>
                                                        <DetailForm
                                                            :label="$t('CREATE_DRIVER.EMPLOYEE_NUMBER')"
                                                            :value="isForm.employeeNumber"
                                                        />
                                                    </b-col>
                                                </b-row>
                                            </div>
                                            <div class="item-form">
                                                <b-row>
                                                    <b-col>
                                                        <DetailForm
                                                            :label="$t('CREATE_DRIVER.FULL_NAME')"
                                                            :value="isForm.fullname"
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
                                                        :lg="12"
                                                        :xl="12"
                                                    >
                                                        <DetailForm
                                                            :label="$t('CREATE_DRIVER.HIRE_DATE')"
                                                            :value="isForm.hireDate"
                                                        />
                                                    </b-col>
                                                    <!-- <b-col
                                                        :cols="12"
                                                        :sm="12"
                                                        :md="12"
                                                        :lg="6"
                                                        :xl="6"
                                                    >
                                                        <DetailForm
                                                            :label="$t('CREATE_DRIVER.DATE_OF_BIRTH')"
                                                            :value="isForm.dateOfBirth"
                                                        />
                                                    </b-col> -->
                                                </b-row>

                                                <!-- <div class="item-form">
                                                    <b-row>
                                                        <b-col>
                                                            <DetailForm
                                                                :label="$t('CREATE_DRIVER.GRADE')"
                                                                :value="isForm.grade"
                                                            />
                                                        </b-col>
                                                    </b-row>
                                                </div> -->
                                            </div>
                                            <div class="item-form">
                                                <b-row>
                                                    <b-col>
                                                        <DetailForm
                                                            :label="$t('CREATE_DRIVER.CHARACTER')"
                                                            :value="isForm.character"
                                                        />
                                                    </b-col>
                                                </b-row>
                                            </div>

                                            <!-- <TitlePathForm :add-class="['margin-title-form']">
                                                {{ $t('CREATE_DRIVER.FORM_PATH_WORKING_CONDITIONS') }}
                                            </TitlePathForm>

                                            <b-row>
                                                <b-col>
                                                    <div class="item-form">
                                                        <DetailForm
                                                            :label="$t('CREATE_DRIVER.AVAILABLE_DAYS')"
                                                            :value="isForm.availableDays ? `${isForm.availableDays}日` : ''"
                                                        />
                                                    </div>
                                                </b-col>
                                            </b-row> -->
                                            <!-- <b-row>
                                                <b-col>
                                                    <div class="item-form">
                                                        <DetailForm
                                                            :label="$t('CREATE_DRIVER.FIXED_HOLIDAYS')"
                                                            :value="getTextLoop(isForm.seletedDateInWeek, getTextCodeDay, ', ', language)"
                                                        />
                                                    </div>
                                                </b-col>
                                            </b-row> -->

                                            <TitlePathForm :add-class="['margin-title-form']">
                                                {{ $t('CREATE_DRIVER.FORM_PATH_RETIREMENT_DATE') }}
                                            </TitlePathForm>

                                            <b-row>
                                                <b-col>
                                                    <div class="item-form">
                                                        <p>{{ isForm.retirementDate ? isForm.retirementDate : '-' }}</p>
                                                    </div>
                                                </b-col>
                                            </b-row>

                                            <TitlePathForm :add-class="['margin-title-form']">
                                                {{ $t('DETAIL_DRIVER.NOTES') }}
                                            </TitlePathForm>

                                            <b-row>
                                                <b-col>
                                                    <b-row>
                                                        <b-col>
                                                            <div class="item-form">
                                                                <p>{{ isForm.notes ? isForm.notes : '-' }}</p>
                                                            </div>
                                                        </b-col>
                                                    </b-row>
                                                </b-col>
                                            </b-row>
                                        </b-col>
                                    </b-row>
                                </div>
                            </b-tab>
                            <!-- <b-tab
                                :title="$t('CREATE_DRIVER.TAB_TITLE_COURSE_INFORMATION')"
                                :active="isTab === 'COURSE'"
                                :disabled="isDisableTabCourse"
                                @click="onClickTab('COURSE')"
                            >
                                <div class="tab-content">
                                    <div class="zone-table">
                                        <b-table
                                            :fields="fieldsListDriverCourse"
                                            :items="listDriverCourse"
                                            bordered
                                            show-empty
                                            no-border-collapse
                                        >
                                            <template #cell(exclusive)="exclusive">
                                                <b-form-checkbox
                                                    v-model="exclusive.item.is_checked"
                                                    style="margin-top: 4px;"
                                                    value="yes"
                                                    unchecked-value="no"
                                                    disabled
                                                />
                                            </template>

                                            <template #empty>
                                                <div class="text-center">
                                                    {{ $t('APP.TABLE_NO_DATA') }}
                                                </div>
                                            </template>
                                        </b-table>
                                    </div>
                                </div>
                            </b-tab> -->
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
import { getDriver } from '@/api/modules/driver';
import { getListDriverCourse } from '@/api/modules/driverCourse';
import DetailForm from '@/components/DetailForm';
import { setLoading } from '@/utils/handleLoading';
import { YmdtoStringYmd, getTextLoop, getTextCodeDay } from '@/utils/convertTime';
import TitlePathForm from '@/components/TitlePathForm';
import { convertValueToText } from '@/utils/handleSelect';
import { loopCallback, splitStr2Array, getValueShortDayByText } from '@/utils/helper';
import { formatNumber } from '@/utils/formatNumber';

export default {
	name: 'DetailDriver',
	components: {
		LineGray,
		TitlePathForm,
		DetailForm,
	},

	data() {
		return {
			CONSTANT,

			idDriver: null,

			tabIndex: this.$store.getters.tabIndexDriver,
			isTab: this.$store.getters.tabIndexDriver === 1 ? 'COURSE' : 'BASIC',
			isDisableTabCourse: false,

			isForm: {
				typeDriver: null,
				employeeNumber: '',
				fullname: '',
				character: '',
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
		};
	},

	computed: {
		language() {
			return this.$store.getters.language;
		},

		fieldsListDriverCourse() {
			return [
				{ key: 'course_code', label: this.$t('CREATE_DRIVER.TABLE_RUNABLE_COURSE_ID'), sortable: false, thClass: 'base-th th-course-id' },
				{ key: 'course_name', label: this.$t('CREATE_DRIVER.TABLE_UNABLE_COURSE_NAME'), sortable: false, thClass: 'base-th' },
				{ key: 'exclusive', label: this.$t('CREATE_DRIVER.TABLE_EXCLUSIVE'), sortable: false, thClass: 'base-th th-exclusive text-center', tdClass: 'text-center' },
			];
		},
	},

	watch: {
		tabIndex() {
			this.$store.dispatch('listDriver/setIndexTab', this.tabIndex);
		},
	},

	created() {
		this.initData();
	},

	methods: {
		convertValueToText,
		getTextLoop,
		getTextCodeDay,

		async initData() {
			setLoading(true);

			this.idDriver = this.$route.params.id;

			this.generateOptionsAvailableDays();
			this.generateOptionDateInWeek();
			this.generateWorkingTime();

			await this.handleGetDriver();
			await this.handleGetDriverCourse();

			setLoading(false);
		},

		async handleGetDriver() {
			try {
				const URL = `${CONSTANT.URL_API.GET_ONE_DRIVER}/${this.idDriver}`;

				const DRIVER = await getDriver(URL);

				if (DRIVER.code === 200) {
					const DATA = DRIVER.data;

					this.isForm.typeDriver = convertValueToText(this.isForm.optionsTypeDriver, DATA.flag);
					this.isForm.employeeNumber = DATA.driver_code;
					this.isForm.fullname = DATA.driver_name;
					this.isForm.hireDate = YmdtoStringYmd(DATA.start_date);
					this.isForm.dateOfBirth = YmdtoStringYmd(DATA.birth_day);
					this.isForm.grade = formatNumber(DATA.grade);
					this.isForm.availableDays = DATA.working_day;
					this.isForm.seletedDateInWeek = (loopCallback(splitStr2Array(DATA.day_of_week, ','), getValueShortDayByText)).sort();

					this.isForm.retirementDate = DATA.end_date;
					this.isForm.notes = DATA.note;
				}
			} catch (error) {
				console.log(error);
				setLoading(false);
			}
		},

		async handleGetDriverCourse() {
			try {
				const URL = `${CONSTANT.URL_API.GET_DRIVER_COURSE}/${this.idDriver}`;

				const { code, data } = await getListDriverCourse(URL, null);

				if (code === 200) {
					if (data) {
						this.listDriverCourse = data;
					} else {
						this.listDriverCourse.length = 0;
					}
				}
			} catch {
				setLoading(false);
			}
		},

		onClickReturn() {
			this.$router.push({ name: 'ListDriver' });
		},

		onClickEdit() {
			this.$router.push({ name: 'ListDriverEdit', params: { id: this.idDriver }});
		},

		onClickEditCourse() {
			this.$router.push({ name: 'DriverCourseEdit', params: { id: this.idDriver }});
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
					value: i,
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
				});
			}

			this.isForm.optionsDateInWeek = options;
		},

		generateWorkingTime() {
			const options = CONSTANT.LIST_DRIVER.LIST_WORKING_TIME;

			this.isForm.optionsWorkingTime = options;
		},

		onClickTab(tab) {
			if (['BASIC', 'COURSE'].includes(tab)) {
				this.isTab = tab;
			} else {
				this.isTab = 'BASIC';
			}
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
                        margin: 15px 0;

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

                        .th-exclusive {
                            width: 80px;
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

    .margin-title-form {
        margin-top: 20px;
    }

    ::v-deep .th-course-id {
        width: 180px;
    }
</style>
