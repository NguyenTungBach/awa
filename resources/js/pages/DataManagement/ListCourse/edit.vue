<template>
    <b-col>
        <b-container>
            <div class="page-course-edit">
                <div class="page-course-edit__header">
                    <b-row>
                        <b-col>
                            <div class="zone-title">
                                <span class="title-page">
                                    {{ $t('COURSE_EDIT.TITLE_COURSE_EDIT') }}
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
                                            {{ $t('COURSE_EDIT.FORM_BASIC_INFORMATION') }}
                                        </TitlePathForm>
                                    </div>

                                    <div class="zone-form__body">
                                        <div class="item-form">
                                            <label for="input-course-easy-to-distinguish">
                                                {{ $t('COURSE_CREATE.EASY_TO_DISTINGUISH') }}
                                            </label>
                                            <b-row>
                                                <b-col>
                                                    <b-form-checkbox
                                                        id="input-course-flag"
                                                        v-model="isForm.flag"
                                                        value="yes"
                                                        unchecked-value="no"
                                                    >
                                                        {{ $t('COURSE_CREATE.CHECKBOX_SPOT_FLIGHT') }}
                                                    </b-form-checkbox>
                                                </b-col>
                                                <b-col>
                                                    <b-form-checkbox
                                                        id="input-course-pot"
                                                        v-model="isForm.pot"
                                                        value="yes"
                                                        unchecked-value="no"
                                                    >
                                                        {{ $t('COURSE_CREATE.CHECKBOX_SHORT_FLIGHT') }}
                                                    </b-form-checkbox>
                                                </b-col>
                                            </b-row>
                                        </div>

                                        <div class="item-form">
                                            <label for="input-course-id">
                                                {{ $t('COURSE_EDIT.COURSE_ID') }}
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
                                                {{ $t('COURSE_EDIT.COURSE_NAME') }}
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
                                                :lg="6"
                                                :xl="6"
                                            >
                                                <div class="item-form">
                                                    <label for="input-exclusive">
                                                        {{ $t('COURSE_CREATE.EXCLUSIVE') }}
                                                    </label>
                                                    <b-form-input
                                                        id="input-exclusive"
                                                        v-model="isForm.owner.driver.driver_name"
                                                        disabled
                                                    />
                                                </div>
                                            </b-col>

                                            <b-col
                                                :cols="12"
                                                :sm="12"
                                                :md="12"
                                                :lg="6"
                                                :xl="6"
                                            >
                                                <div class="item-form">
                                                    <label for="input-group-code">
                                                        {{ $t('COURSE_CREATE.GROUP_CODE') }}
                                                    </label>
                                                    <b-input-group>
                                                        <SelectMultiple
                                                            :options="optionsAZ"
                                                            :value="isForm.group"
                                                            :formatter="formatterAZ"
                                                            @select="getSelectValueAZ"
                                                        />
                                                    </b-input-group>
                                                </div>
                                            </b-col>
                                        </b-row>

                                        <div class="item-form">
                                            <b-row>
                                                <b-col
                                                    :cols="12"
                                                    :sm="12"
                                                    :md="12"
                                                    :lg="6"
                                                    :xl="6"
                                                >
                                                    <div class="item-form">
                                                        <label for="input-start-time">
                                                            {{ $t('COURSE_EDIT.START_TIME') }}
                                                            <span class="text-danger">
                                                                *
                                                            </span>
                                                        </label>
                                                        <SelectMultiple
                                                            :options="isForm.listTime"
                                                            :value="isForm.start_time"
                                                            :formatter="formatter"
                                                            @select="getSelectStartTime"
                                                        />
                                                    </div>
                                                </b-col>
                                                <b-col
                                                    :cols="12"
                                                    :sm="12"
                                                    :md="12"
                                                    :lg="6"
                                                    :xl="6"
                                                >
                                                    <div class="item-form">
                                                        <label for="input-end-time">
                                                            {{ $t('COURSE_EDIT.END_TIME') }}
                                                            <span class="text-danger">
                                                                *
                                                            </span>
                                                        </label>
                                                        <SelectMultiple
                                                            :options="isForm.listTime"
                                                            :value="isForm.end_time"
                                                            :formatter="formatter"
                                                            @select="getSelectEndTime"
                                                        />
                                                    </div>
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
                                                    <div class="item-form">
                                                        <label for="input-break_time">
                                                            {{ $t('COURSE_EDIT.BREAK_TIME') }}
                                                            <span class="text-danger">
                                                                *
                                                            </span>
                                                        </label>
                                                        <SelectMultiple
                                                            :options="isForm.listTime"
                                                            :value="isForm.break_time"
                                                            :formatter="formatter"
                                                            @select="getSelectBreakTime"
                                                        />
                                                    </div>
                                                </b-col>

                                                <b-col
                                                    :cols="12"
                                                    :sm="12"
                                                    :md="12"
                                                    :lg="6"
                                                    :xl="6"
                                                >
                                                    <div class="item-form">
                                                        <label for="input-fatigue">
                                                            {{ $t('COURSE_CREATE.FATIGUE') }}
                                                            <span class="text-danger">
                                                                *
                                                            </span>
                                                        </label>
                                                        <b-form-input
                                                            id="input-fatigue"
                                                            v-model="isForm.point"
                                                            type="number"
                                                            @keydown.native="validInputFloat"
                                                        />
                                                    </div>
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
                                                    <div class="item-form">
                                                        <label for="input-start-date">
                                                            {{ $t('COURSE_EDIT.START_DATE') }}
                                                            <span class="text-danger">
                                                                *
                                                            </span>
                                                        </label>
                                                        <b-input-group class="mb-3">
                                                            <b-form-input
                                                                id="input-start-date"
                                                                v-model="isForm.start_date"
                                                                type="text"
                                                                autocomplete="off"
                                                            />
                                                            <b-input-group-append>
                                                                <b-form-datepicker
                                                                    v-model="isForm.start_date"
                                                                    button-only
                                                                    right
                                                                    :locale="language"
                                                                    aria-controls="input-start-date"
                                                                    hide-header
                                                                    :is-r-t-l="false"
                                                                    :label-help="$t('APP.LABLE_HELP_CALENDAR')"
                                                                />
                                                            </b-input-group-append>
                                                        </b-input-group>
                                                    </div>
                                                </b-col>
                                                <b-col
                                                    :cols="12"
                                                    :sm="12"
                                                    :md="12"
                                                    :lg="6"
                                                    :xl="6"
                                                >
                                                    <div class="item-form">
                                                        <label for="input-end-date">
                                                            {{ $t('COURSE_EDIT.END_DATE') }}
                                                        </label>
                                                        <b-input-group class="mb-3">
                                                            <b-form-input
                                                                id="input-end-date"
                                                                v-model="isForm.end_date"
                                                                type="text"
                                                                autocomplete="off"
                                                            />
                                                            <b-input-group-append>
                                                                <b-form-datepicker
                                                                    v-model="isForm.end_date"
                                                                    button-only
                                                                    right
                                                                    :locale="language"
                                                                    aria-controls="input-end-date"
                                                                    hide-header
                                                                    :is-r-t-l="false"
                                                                    :label-help="$t('APP.LABLE_HELP_CALENDAR')"
                                                                />
                                                            </b-input-group-append>
                                                        </b-input-group>
                                                    </div>
                                                </b-col>
                                            </b-row>
                                        </div>
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
                                    {{ $t('COURSE_EDIT.NOTE') }}
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
import SelectMultiple from '@/components/SelectMultiple';
import { validInputFloat, validInputCourseCode } from '@/utils/handleInput';

export default {
	name: 'CourseEdit',
	components: {
		LineGray,
		TitlePathForm,
		SelectMultiple,
	},

	data() {
		return {
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
			idCourse: null,

			isForm: {
				flag: 'no',
				pot: 'no',
				course_id: '',
				group: [null, null],
				course_name: '',
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

                        .select-multiple {
                            width: 100%;
                        }
                    }
                }
            }
        }
    }
</style>
