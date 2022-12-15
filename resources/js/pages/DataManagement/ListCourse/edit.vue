<template>
    <b-col>
        <b-container>
            <div class="page-course-edit">
                <div class="page-course-edit__header">
                    <b-row>
                        <b-col>
                            <div class="zone-title">
                                <span class="title-page">
                                    {{ $t('CUSTOMER_EDIT.TITLE_CUSTOMER_EDIT') }}
                                </span>
                            </div>
                        </b-col>
                    </b-row>
                    <LineGray />
                </div>
                <div class="page-course-edit__body">
                    <div class="body-control zone-content">
                        <b-row>
                            <b-col>
                                <div class="zone-control text-right">
                                    <b-button
                                        pill
                                        class="btn-return"
                                        @click="onClickReturn()"
                                    >
                                        {{ $t('APP.BUTTON_RETURN') }}
                                    </b-button>
                                    <b-button
                                        pill
                                        class="btn-save btn-color-active"
                                        @click="onClickSaveCourse()"
                                    >
                                        {{ $t('APP.BUTTON_SAVE') }}
                                    </b-button>
                                </div>
                            </b-col>
                        </b-row>
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
                                        alt="Avatar course"
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
                                    <div class="zone-form__header">
                                        <TitlePathForm>
                                            {{ $t('CUSTOMER_CREATE.FORM_BASIC_INFORMATION') }}
                                        </TitlePathForm>
                                    </div>

                                    <div class="zone-form__body">

                                        <div class="item-form">
                                            <label for="input-course-id">
                                                {{ $t('CUSTOMER_CREATE.COURSE_ID') }}
                                                <span class="text-danger">
                                                    *
                                                </span>
                                            </label>
                                            <b-form-input
                                                id="input-course-id"
                                                v-model="isForm.course_id"
                                                type="text"
                                                onpaste="return false;"
                                                ondrop="return false;"
                                                autocomplete="off"
                                                disabled
                                                @keydown.native="validInputCourseCode"
                                            />
                                        </div>

                                        <div class="item-form">
                                            <label for="input-course-name">
                                                {{ $t('CUSTOMER_CREATE.COURSE_NAME') }}
                                                <span class="text-danger">
                                                    *
                                                </span>
                                            </label>
                                            <b-form-input
                                                id="input-course-name"
                                                v-model="isForm.course_name"
                                            />
                                        </div>

                                        <b-row>
                                            <b-col
                                                :cols="12"
                                                :sm="12"
                                                :md="12"
                                                :lg="12"
                                                :xl="11"
                                            >
                                                <div class="item-form">
                                                    <label for="input-group-code">
                                                        {{ $t('CUSTOMER_CREATE.CLOSING_DAY') }}
                                                    </label>
                                                    <span class="text-danger">
                                                        *
                                                    </span>
                                                    <b-form-select
                                                        id="customer-closing-day"
                                                        v-model="isForm.exclusive"
                                                        :options="optionsClosingDay"
                                                        :disabled="isLoading"
                                                    />
                                                </div>
                                            </b-col>
                                            <b-col
                                                :cols="12"
                                                :sm="12"
                                                :md="12"
                                                :lg="12"
                                                :xl="1"
                                                style="bottom: -41px;"
                                            >
                                                <span class="text-closing-day">日</span>
                                            </b-col>
                                        </b-row>

                                        <b-row>
                                            <b-col
                                                :cols="12"
                                                :sm="12"
                                                :md="12"
                                                :lg="12"
                                                :xl="12"
                                            >
                                                <div class="item-form">
                                                    <label for="input-course-id">
                                                        {{ $t('CUSTOMER_CREATE.CLIENT_MANAGER') }}
                                                        <span class="text-danger">
                                                            *
                                                        </span>
                                                    </label>
                                                    <b-input-group>
                                                        <b-form-input
                                                            id="input-course-name"
                                                            v-model="isForm.customer_manager"
                                                        />
                                                    </b-input-group>
                                                </div>
                                            </b-col>
                                        </b-row>

                                        <b-row>
                                            <b-col
                                                :cols="12"
                                                :sm="12"
                                                :md="12"
                                                :lg="12"
                                                :xl="12"
                                            >
                                                <div class="item-form">
                                                    <label for="input-course-id">
                                                        {{ $t('CUSTOMER_CREATE.ADDRESS_OF_CLIENT') }}
                                                        <span class="text-danger">
                                                            *
                                                        </span>
                                                    </label>
                                                    <b-input-group>
                                                        <b-form-input
                                                            id="input-course-name"
                                                            v-model="isForm.customer_address"
                                                        />
                                                    </b-input-group>
                                                </div>
                                            </b-col>
                                        </b-row>

                                        <b-row>
                                            <b-col
                                                :cols="12"
                                                :sm="12"
                                                :md="12"
                                                :lg="12"
                                                :xl="12"
                                            >
                                                <div class="item-form">
                                                    <label for="input-course-id">
                                                        {{ $t('CUSTOMER_CREATE.CLIENT_EMAIL') }}
                                                        <span class="text-danger">
                                                            *
                                                        </span>
                                                    </label>
                                                    <b-input-group>
                                                        <b-form-input
                                                            id="input-course-name"
                                                            v-model="isForm.customer_email"
                                                        />
                                                    </b-input-group>
                                                </div>
                                            </b-col>
                                        </b-row>
                                    </div>
                                </div>
                            </b-col>
                        </b-row>
                        <b-row>
                            <b-col
                                :cols="12"
                                :sm="12"
                                :md="12"
                                :lg="12"
                                :xl="12"
                            >
                                <label for="input-notes">
                                    {{ $t('CUSTOMER_CREATE.NOTE') }}
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
            </div>
        </b-container>
    </b-col>
</template>

<script>
import CONSTANT from '@/const';
import LineGray from '@/components/LineGray';
import { setLoading } from '@/utils/handleLoading';
import TitlePathForm from '@/components/TitlePathForm';
import { getCourse, putCourse } from '@/api/modules/courseManagement';
import { convertTimeCourse } from '@/utils/convertTime';
import { convertBreakTimeNumberToTime, convertTimeToSelect, convertStingToSelect } from '@/utils/convertTime';
import { validateCourse } from '@/utils/validateCRUD';
import TOAST_COURSE_MANAGEMENT from '@/toast/modules/courseManagement';
// import SelectMultiple from '@/components/SelectMultiple';
import { validInputFloat, validInputCourseCode } from '@/utils/handleInput';

export default {
	name: 'CourseEdit',
	components: {
		LineGray,
		TitlePathForm,
		// SelectMultiple,
	},

	data() {
		return {
            optionsClosingDay: [
                {
                    value: -1,
                    text: '末日',
                },
                {
                    value: 1,
                    text: 1,
                },
                {
                    value: 2,
                    text: 2,
                },
                {
                    value: 3,
                    text: 3,
                },
                {
                    value: 4,
                    text: 4,
                },
                {
                    value: 5,
                    text: 5,
                },
                {
                    value: 6,
                    text: 6,
                },
                {
                    value: 7,
                    text: 7,
                },
                {
                    value: 8,
                    text: 8,
                },
                {
                    value: 9,
                    text: 9,
                },
                {
                    value: 10,
                    text: 10,
                },
                {
                    value: 11,
                    text: 11,
                },
                {
                    value: 12,
                    text: 12,
                },
                {
                    value: 13,
                    text: 13,
                },
                {
                    value: 14,
                    text: 14,
                },
                {
                    value: 15,
                    text: 15,
                },
                {
                    value: 16,
                    text: 16,
                },
                {
                    value: 17,
                    text: 17,
                },
                {
                    value: 18,
                    text: 18,
                },
                {
                    value: 19,
                    text: 19,
                },
                {
                    value: 20,
                    text: 20,
                },
                {
                    value: 21,
                    text: 21,
                },
                {
                    value: 22,
                    text: 22,
                },
                {
                    value: 23,
                    text: 23,
                },
                {
                    value: 24,
                    text: 24,
                },
                {
                    value: 25,
                    text: 25,
                },
                {
                    value: 26,
                    text: 26,
                },
                {
                    value: 27,
                    text: 27,
                },
                {
                    value: 28,
                    text: 28,
                },
                {
                    value: 29,
                    text: 29,
                },
                {
                    value: 30,
                    text: 30,
                },
            ],

			optionsAZ: [],
			idCourse: null,

			isForm: {
				flag: 'no',
				pot: 'no',
				course_id: '',
				group: [null, null],
				course_name: '',
                // 
				customer_manager: '',
				customer_address: '',
				customer_email: '',
                //
				start_time: [null, null],
				end_time: [null, null],
				break_time: [null, null],
				point: null,
				start_date: '',
				end_date: '',
				note: '',
				owner: {
					driver_code: '',
					course_code: '',
					driver: {
						driver_code: '',
						driver_name: '',
					},
				},

				listTime: [],
				listFatigue: [],
			},

			inited: false,
		};
	},

	computed: {
		language() {
			return this.$store.getters.language;
		},

		changeForm() {
			return this.isForm;
		},
	},

	watch: {
		changeForm: {
			handler: function() {
				if (this.inited) {
					this.$store.dispatch('course/setWarningNotSave', true);
				}
			},

			deep: true,
		},
	},

	created() {
		this.initData();
		this.optionsAZ = this.genereateOptionAZ();
	},

	methods: {
		validInputFloat,
		validInputCourseCode,

		async initData() {
			this.inited = false;
			setLoading(true);
			this.idCourse = this.$route.params.id || null;
			this.isForm.listTime = this.genereateOptionTime();
			this.isForm.listFatigue = this.generateListFatigue(1, 5);
			await this.handleGetCourse();
			this.inited = true;

			setLoading(false);
		},

		genereateOptionTime(min = 0, max = 31) {
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

		generateListFatigue(min = 1, max = 5) {
			let idx = min;

			const result = [];

			while (idx <= max) {
				result.push({
					value: idx,
					text: `${idx}`,
				});

				idx++;
			}

			return result;
		},

		initBody() {
			return {
				flag: this.isForm.flag,
				pot: this.isForm.pot,
				course_code: this.isForm.course_id,
				group: this.isForm.group ? (this.isForm.group).join('') : '',
				course_name: this.isForm.course_name ? this.isForm.course_name.trim() : '',
				start_time: this.formatter(this.isForm.start_time),
				end_time: this.formatter(this.isForm.end_time),
				break_time: this.formatter(this.isForm.break_time) ? this.formatter(this.isForm.break_time) : '0.00',
				point: parseFloat(this.isForm.point) || null,
				start_date: this.isForm.start_date,
				end_date: this.isForm.end_date,
				note: this.isForm.note ? this.isForm.note.trim() : '',
			};
		},

		// goToList() {
			// this.$router.push({ name: 'ListCourseIndex' });
		// },

		goToDetail() {
			// this.$router.push({ name: 'ListCourseIndex' });
            this.$router.push({ name: 'CourseDetail', params: { id: this.idCourse }});
		},

		async handleGetCourse() {
			try {
				setLoading(true);
				const COURSE = await getCourse(`${CONSTANT.URL_API.GET_COURSE}/${this.idCourse}`);

				if (COURSE.code === 200) {
					const OWER = {
						driver_code: '',
						course_code: '',
						driver: {
							driver_code: '',
							driver_name: '',
						},
					};
					const DATA = COURSE.data;

					this.isForm.flag = DATA.flag || 'no';
					this.isForm.pot = DATA.pot || 'no';
					this.isForm.course_id = DATA.course_code;
					this.isForm.group = convertStingToSelect(DATA.group);
					this.isForm.course_name = DATA.course_name;
					this.isForm.start_time = convertTimeToSelect(DATA.start_time);
					this.isForm.end_time = convertTimeToSelect(DATA.end_time);
					this.isForm.break_time = convertTimeToSelect(convertBreakTimeNumberToTime(DATA.break_time));
					this.isForm.point = DATA.point;
					this.isForm.start_date = DATA.start_date;
					this.isForm.end_date = DATA.end_date;
					this.isForm.note = DATA.note;
					this.isForm.owner = DATA.owner ? DATA.owner : OWER;

					setLoading(false);
				}
			} catch (error) {
				console.log(error);
				setLoading(false);
			}
		},

		async onClickSaveCourse() {
			const BODY = this.initBody();
			const VALIDATE = validateCourse(BODY);

			if (VALIDATE.status) {
				try {
					await this.$store.dispatch('course/setWarningNotSave', false)
						.then(async() => {
							setLoading(true);

							BODY.break_time = convertTimeCourse(BODY.break_time);

							const COURSE = await putCourse(`${CONSTANT.URL_API.PUT_COURSE}/${this.idCourse}`, BODY);

							if (COURSE.code === 200) {
								// this.goToList();
                                this.goToDetail();
								TOAST_COURSE_MANAGEMENT.success();
							}

							setLoading(false);
						});
				} catch {
					setLoading(false);
				}
			} else {
				TOAST_COURSE_MANAGEMENT.validate(VALIDATE.message);
			}
		},

		onClickReturn() {
			this.$router.push({ name: 'CourseDetail', params: { id: this.idCourse }});
		},

		onClickEdit() {
			this.$router.push({ name: 'CourseEdit', params: { id: this.idCourse }});
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

		getSelectValueAZ(select) {
			this.isForm.group = select;
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
	},
};
</script>

<style lang="scss" scoped>
    @import '@/scss/variables';

    .page-course-edit {
        &__body {
            .body-form {
                border: 1px solid $geyser;
                margin-top: 10px;
                padding: 20px;

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
                        
                        .text-closing-day {
                            font-family: 'Inter';
                            font-style: normal;
                            font-weight: 400;
                            font-size: 24px;
                            line-height: 29px;
                        }

                        .select-multiple {
                            width: 100%;
                        }
                    }
                }
            }
        }
    }
</style>
