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
                                        :src="require('@/assets/images/customer_icon.png')"
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
                                            <!-- <b-form-input
                                                id="input-course-id"
                                                v-model="isForm.course_id"
                                                type="text"
                                                onpaste="return false;"
                                                ondrop="return false;"
                                                autocomplete="off"
                                                disabled
                                                @keydown.native="validInputCourseCode"
                                            /> -->
                                            <b-form-input
                                                id="input-course-id"
                                                v-model="isForm.customer_id"
                                                type="text"
                                                onpaste="return false;"
                                                ondrop="return false;"
                                                autocomplete="off"
                                                disabled
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
                                                v-model="isForm.customer_name"
                                            />
                                        </div>

                                        <b-row>
                                            <b-col
                                                :cols="12"
                                                :sm="12"
                                                :md="12"
                                                :lg="12"
                                                :xl="12"
                                            >
                                                <div class="item-form">
                                                    <label for="customer-closing-day">
                                                        {{ $t('CUSTOMER_CREATE.CLOSING_DAY') }}
                                                    </label>
                                                    <span class="text-danger">
                                                        *
                                                    </span>
                                                    <b-input-group :append="$t('CREATE_DRIVER.DAY')">
                                                        <b-form-select
                                                            id="customer-closing-day"
                                                            v-model="isForm.exclusive"
                                                            :options="optionsClosingDay"
                                                        />
                                                    </b-input-group>
                                                </div>
                                            </b-col>
                                        </b-row>
                                        <!-- <b-row>
                                            <b-col
                                                :cols="12"
                                                :sm="12"
                                                :md="12"
                                                :lg="12"
                                                :xl="12"
                                            >
                                                <div class="item-form">
                                                    <label for="customer-sales-tax">
                                                        {{ $t('CUSTOMER_CREATE.SALE_TAX') }}
                                                    </label>
                                                    <span class="text-danger">
                                                        *
                                                    </span>
                                                    <b-input-group>
                                                        <b-form-select
                                                            id="customer-sales-tax"
                                                            v-model="isForm.saleTax"
                                                            :options="optionsSaleTax"
                                                        />
                                                    </b-input-group>
                                                </div>
                                            </b-col>
                                        </b-row> -->

                                        <b-row>
                                            <b-col
                                                :cols="12"
                                                :sm="12"
                                                :md="12"
                                                :lg="12"
                                                :xl="12"
                                            >
                                                <div class="item-form">
                                                    <label for="customer-tax">
                                                        {{ $t('CUSTOMER_CREATE.TAX') }}
                                                    </label>
                                                    <span class="text-danger">
                                                        *
                                                    </span>
                                                    <b-input-group>
                                                        <b-form-select
                                                            id="customer-tax"
                                                            v-model="isForm.tax"
                                                            :options="optionsTax"
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
                                                    <label for="input-course-clineManager">
                                                        {{ $t('CUSTOMER_CREATE.CLIENT_MANAGER') }}
                                                        <span class="text-danger">
                                                            *
                                                        </span>
                                                    </label>
                                                    <b-input-group>
                                                        <b-form-input
                                                            id="input-course-clineManager"
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
                                                :lg="4"
                                                :xl="4"
                                            >
                                                <label for="input-course-id">
                                                    {{ $t('CUSTOMER_CREATE.POST_CODE') }}
                                                    <span class="text-danger">
                                                        *
                                                    </span>
                                                </label>
                                                <div class="item-form-postCode">
                                                    <b-form-input
                                                        id="postCode-first" v-model="postalCode_first"
                                                        type="text"
                                                        onkeypress="if ( isNaN( String.fromCharCode(event.keyCode) )) return false;"
                                                        maxlength="3"
                                                    />
                                                    <span class="postCode">-</span>
                                                    <b-form-input
                                                        id="postCode-second" v-model="postalCode_last"
                                                        type="text"
                                                        onkeypress="if ( isNaN( String.fromCharCode(event.keyCode) )) return false;"
                                                        maxlength="4"
                                                    />
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
                                                    <label for="input-course-addressOfClient">
                                                        {{ $t('CUSTOMER_CREATE.ADDRESS_OF_CLIENT') }}
                                                        <span class="text-danger">
                                                            *
                                                        </span>
                                                    </label>
                                                    <b-input-group>
                                                        <b-form-input
                                                            id="input-course-addressOfClient"
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
                                                    <label for="input-course-phone">
                                                        {{ $t('CUSTOMER_CREATE.CLIENT_PHONE') }}
                                                        <span class="text-danger">
                                                            *
                                                        </span>
                                                    </label>
                                                    <b-input-group>
                                                        <b-form-input
                                                            id="input-course-phone"
                                                            v-model="isForm.customer_phone"
                                                            type="text"
                                                            maxlength="13"
                                                            onkeypress="if ( isNaN( String.fromCharCode(event.keyCode) )) return false;"
                                                            @keyup="autoFormartPhoneNumber(isForm.customer_phone)"
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
// import { convertTimeCourse } from '@/utils/convertTime';
// import { convertBreakTimeNumberToTime, convertTimeToSelect, convertStingToSelect } from '@/utils/convertTime';
// import { validateCourse } from '@/utils/validateCRUD';
import TOAST_COURSE_MANAGEMENT from '@/toast/modules/courseManagement';
// import SelectMultiple from '@/components/SelectMultiple';
import { validInputFloat, validInputCourseCode } from '@/utils/handleInput';
import { formartPhoneNumber } from '@/utils/formatNumber';
import i18n from '@/lang';

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
					value: 1,
					text: '15日',
				},
				{
					value: 2,
					text: '20日',
				},
				{
					value: 3,
					text: '25日',
				},
				{
					value: 4,
					text: '末日',
				},
			],

			optionsSaleTax: [
				{
					value: 1,
					text: '内税',
				},
				{
					value: 2,
					text: '外税',
				},
			],

			optionsAZ: [],
			idCourse: null,

			isForm: {
				customer_id: '',
				customer_name: '',
				customer_postCode: '',
				//
				customer_manager: '',
				customer_address: '',
				customer_phone: '',
				exclusive: '',
				tax: '',
				//
				note: '',
			},

			inited: false,

			// Bach them
			postalCode_first: '',
			postalCode_last: '',
			optionsTax: [
				{
					value: 1,
					text: i18n.t('CUSTOMER_CREATE.TAX_OPTION.TAX_INCLUDE'),
				},
				{
					value: 2,
					text: i18n.t('CUSTOMER_CREATE.TAX_OPTION.TAX_EXCLUDE'),
				},
			],
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
		// this.optionsAZ = this.genereateOptionAZ();
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
				customer_code: this.isForm.customer_id,
				customer_name: this.isForm.customer_name ? this.isForm.customer_name.trim() : '',
				closing_date: this.isForm.exclusive,
				tax: this.isForm.tax, // bach them
				person_charge: this.isForm.customer_manager ? this.isForm.customer_manager.trim() : '',
				post_code: this.postalCode_first + '-' + this.postalCode_last,
				address: this.isForm.customer_address ? this.isForm.customer_address.trim() : '',
				phone: this.isForm.customer_phone,
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
					const DATA = COURSE.data;
					this.optionsClosingDay.forEach(item => {
						if (item.text === DATA.closing_date) {
							this.isForm.exclusive = item.value;
						}
					});
					// bach them start
					this.optionsTax.forEach(item => {
						if (item.value === DATA.tax_value) {
							this.isForm.tax = item.value;
						}
					});
					// bach them end
					this.isForm.customer_id = DATA.customer_code;
					this.isForm.customer_name = DATA.customer_name;
					this.isForm.customer_manager = DATA.person_charge;
					this.postalCode_first = DATA.post_code.split('-')[0];
					this.postalCode_last = DATA.post_code.split('-')[1];
					this.isForm.customer_address = DATA.address;
					this.isForm.customer_phone = formartPhoneNumber(DATA.phone);
					this.isForm.note = DATA.note;
					console.log('form:', this.isForm);
					setLoading(false);
				}
			} catch (error) {
				console.log(error);
				setLoading(false);
			}
		},

		// onClickSaveCourse() {
		//     this.goToDetail();
		//     TOAST_COURSE_MANAGEMENT.success();
		// },
		async onClickSaveCourse() {
			const BODY = this.initBody();
			// const VALIDATE = validateCourse(BODY);

			// if (VALIDATE.status) {
			try {
				await this.$store.dispatch('course/setWarningNotSave', false)
					.then(async() => {
						setLoading(true);

						// BODY.break_time = convertTimeCourse(BODY.break_time);

						const COURSE = await putCourse(`${CONSTANT.URL_API.PUT_COURSE}/${this.idCourse}`, BODY);

						if (COURSE.code === 200) {
							// this.goToList();
							this.goToDetail();
							TOAST_COURSE_MANAGEMENT.update();
						}

						setLoading(false);
					});
			} catch {
				setLoading(false);
			}
			// } else {
			// 	TOAST_COURSE_MANAGEMENT.validate(VALIDATE.message);
			// }
		},

		onClickReturn() {
			this.$router.push({ name: 'CourseDetail', params: { id: this.idCourse }});
		},

		onClickEdit() {
			this.$router.push({ name: 'CourseEdit', params: { id: this.idCourse }});
		},

		// Bách thêm tự động thêm dấu gạch cho điện thoại
		autoFormartPhoneNumber(number){
			const cleaned = `${number}`.replace(/\D/g, '');

			const match1 = cleaned.match(/^(\d{3})$/);
			const match2 = cleaned.match(/^(\d{3})(\d{4})$/);
			const match3 = cleaned.match(/^(\d{3})(\d{4})(\d{4})$/);
			if (match1) {
				this.isForm.customer_phone = `${match1[1]}-`;
			}
			if (match2) {
				this.isForm.customer_phone = `${match2[1]}-${match2[2]}-`;
			}
			if (match3) {
				this.isForm.customer_phone = `${match3[1]}-${match3[2]}-${match3[3]}`;
			}

			// if (number.length >= 13){
			// 	// eslint-disable-next-line no-self-assign
			// 	this.isForm.customer_phone = number.slice(0, 13);
			// }
		},

		// genereateOptionAZ() {
		// 	const len = this.listAZ.length;

		// 	let idx = 0;

		// 	const result = [];

		// 	while (idx < len) {
		// 		result.push({
		// 			value: this.listAZ[idx],
		// 			text: this.listAZ[idx],
		// 			disabled: false,
		// 		});

		// 		idx++;
		// 	}

		// 	return [result, result];
		// },

		// formatter(arr) {
		// 	for (let idx = 0; idx < arr.length; idx++) {
		// 		if (!arr[idx]) {
		// 			return arr.join('');
		// 		}
		// 	}

		// 	return arr.join(':');
		// },

		// formatterAZ(arr) {
		// 	return arr.join('');
		// },

		// getSelectValueAZ(select) {
		// 	this.isForm.group = select;
		// },

		// getSelectStartTime(select) {
		// 	this.isForm.start_time = select;
		// },

		// getSelectEndTime(select) {
		// 	this.isForm.end_time = select;
		// },

		// getSelectBreakTime(select) {
		// 	this.isForm.break_time = select;
		// },
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

                        .item-form-postCode {
                            display: flex;
                            margin-bottom: 10px;
                            font-size: 18px;

                            .postCode {
                                margin: 0 8px;
                                display: flex;
                                align-items: center;
                            }
                        }
                    }
                }
            }
        }
    }
</style>
