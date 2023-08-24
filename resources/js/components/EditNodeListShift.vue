<template>
    <div class="edit-node-list-shift">
        <template v-for="(itemEdit, idxEdit) in listSelected">
            <div
                :key="`item-${idxEdit}`"
                class="edit-item"
                @click="onClickSelect(idxEdit)"
            >
                <div class="select-type">
                    <b-input-group>
                        <template #prepend>
                            <b-input-group-text
                                class="icon-delete"
                                @click="onClickRemove(itemEdit, idxEdit)"
                            >
                                <i class="fas fa-times-circle" />
                            </b-input-group-text>
                        </template>

                        <b-form-select v-model="itemEdit.type" @change="handleSelectChange">
                            <b-form-select-option
                                v-for="(item, idx) in listSelect"
                                :key="`select-dayoff-${idx + 1}`"
                                :value="item.value"
                                :disabled="item.disabled"
                            >
                                {{ $t(item.text) }}
                            </b-form-select-option>

                            <b-form-select-option
                                v-for="(item, idx) in halfDayOff"
                                :key="`select-halfdayoff-${idx + 1}`"
                                :value="item.value"
                                :disabled="item.disabled"
                            >
                                {{ $t(item.text) }}
                            </b-form-select-option>

                            <b-form-select-option
                                v-for="(course, idxCourse) in listCourse"
                                :key="`select-course-${idxCourse + 1}`"
                                :value="course.value"
                                :disabled="course.disabled"
                            >
                                {{ course.text }}
                            </b-form-select-option>
                        </b-form-select>
                    </b-input-group>
                </div>

                <div v-if="itemEdit.type">
                    <div v-if="!isSelectDayOff" class="select-time">
                        <div v-if="itemEdit.type !== null">
                            <div class="item-time">
                                <b-row>
                                    <b-col sm="3">
                                        <label>{{ $t('LIST_SHIFT.LABEL_START_TIME') }}</label>
                                    </b-col>
                                    <b-col sm="9">
                                        <SelectMultiple
                                            :options="listTime"
                                            :value="itemEdit.start_time"
                                            :formatter="formatter"
                                            :idx-loop="idxEdit"
                                            :key-data="'start_time'"
                                            @select="getSelectValue"
                                        />
                                    </b-col>
                                </b-row>
                            </div>
                            <div class="item-time">
                                <b-row>
                                    <b-col sm="3">
                                        <label>{{ $t('LIST_SHIFT.LABEL_CLOSING_TIME') }}</label>
                                    </b-col>
                                    <b-col sm="9">
                                        <SelectMultiple
                                            :options="listTime"
                                            :value="itemEdit.end_time"
                                            :formatter="formatter"
                                            :idx-loop="idxEdit"
                                            :key-data="'end_time'"
                                            @select="getSelectValue"
                                        />
                                    </b-col>
                                </b-row>
                            </div>
                            <div class="item-time">
                                <b-row>
                                    <b-col sm="3">
                                        <label>{{ $t('LIST_SHIFT.LABEL_BREAK_TIME') }}</label>
                                    </b-col>
                                    <b-col sm="9">
                                        <SelectMultiple
                                            :options="listTime"
                                            :value="itemEdit.break_time"
                                            :formatter="formatter"
                                            :idx-loop="idxEdit"
                                            :key-data="'break_time'"
                                            @select="getSelectValue"
                                        />
                                    </b-col>
                                </b-row>
                            </div>
                        </div>
                        <!-- <div v-if="itemEdit.course.flag !== 'yes'"> -->
                        <div v-if="itemEdit.type === null">
                            <div class="item-time text-center">
                                <span>
                                    <b>始業時間: </b>{{ itemEdit.course.start_time }}
                                </span>
                            </div>
                            <div class="item-time text-center">
                                <span>
                                    <b>終業時間: </b>{{ itemEdit.course.end_time }}
                                </span>
                            </div>
                            <div class="item-time text-center">
                                <span>
                                    <b>休憩時間: </b>{{ convertBreakTimeNumberToTime(itemEdit.course.break_time) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </template>

        <div :class="{ 'zone-add': true, 'disabled': handleDisabledAdd() }">
            <div class="zone-icon-add">
                <h6>
                    <span @click="onClickAdd">
                        +
                    </span>
                </h6>
            </div>
        </div>
    </div>
</template>

<script>
import CONSTANT from '@/const';
import SelectMultiple from '@/components/SelectMultiple';
import { convertBreakTimeNumberToTime } from '@/utils/convertTime';

export default {
	name: 'EditNodeListShift',
	components: {
		SelectMultiple,
	},

	props: {
		halfDayOff: {
			type: Array,
			default: () => {
				return [];
			},
		},

		listSelect: {
			type: Array,
			default: () => {
				return [];
			},
		},

		listCourse: {
			type: Array,
			default: () => {
				return [];
			},
		},

		listSelected: {
			type: Array,
			default: () => {
				return [];
			},
		},
	},

	data() {
		return {
			listTime: this.genereateOptionTime(0, 31),
			isSelectDayOff: false,
			indexHandle: null,
		};
	},

	computed: {
		isSelected() {
			if (this.listSelected.length === 0) {
				return false;
			}

			return this.listSelected[this.listSelected.length - 1].type;
		},
	},

	watch: {
		listSelected: {
			handler: function() {
				this.isSelectDayOff = this.isCheckSelectedDayOff();
				this.$emit('dayoff', this.isSelectDayOff);
			},

			deep: true,
		},

		isSelectDayOff() {
			this.$emit('dayoff', this.isSelectDayOff);
		},
	},

	methods: {
		convertBreakTimeNumberToTime,

		genereateOptionTime(min = 0, max = 23) {
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

		onClickAdd() {
			if (this.listSelected.length === 0) {
				this.$emit('add');
			} else {
				if (this.isSelected) {
					if (this.isCheckSelectedDayOff() === false) {
						this.$emit('add');
					}
				}
			}
		},

		handleDisabledAdd() {
			if (this.listSelected.length === 0) {
				return false;
			} else {
				if (this.isSelected) {
					if (this.isCheckSelectedDayOff() === false) {
						return false;
					}
				}
			}

			return true;
		},

		onClickRemove(data, index) {
			this.$emit('remove', data, index);
		},

		getSelectValue(select) {
			this.$emit('edit', select);
		},

		formatter(arr) {
			for (let idx = 0; idx < arr.length; idx++) {
				if (!arr[idx]) {
					return arr.join('');
				}
			}

			return arr.join(':');
		},

		getListValueSelected() {
			const result = [];
			const len = this.listSelected.length;
			let idx = 0;

			while (idx < len) {
				result.push(this.listSelected[idx].type);

				idx++;
			}

			return result;
		},

		isCheckSelectedDayOff() {
			const LIST_VALUE_SELECTED = this.getListValueSelected();
			const LIST_DAY_OFF = CONSTANT.LIST_SHIFT.LIST_VALUE_DAY_OFF;

			const len = LIST_VALUE_SELECTED.length;
			let idx = 0;

			while (idx < len) {
				if (LIST_DAY_OFF.includes(LIST_VALUE_SELECTED[idx])) {
					return true;
				}

				idx++;
			}

			return false;
		},

		handleSelectChange(value) {
			const data = {
				index: this.indexHandle,
				value,
			};

			this.$emit('change', data);
		},

		onClickSelect(idx) {
			this.indexHandle = idx;
		},
	},
};
</script>

<style lang="scss" scoped>
    @import '@/scss/variables';

    .edit-node-list-shift {
        margin: 10px 0;

        .icon-delete {
            background-color: $punch;

            i {
                color: $white;
            }

            cursor: pointer;
        }

        .edit-item {
            margin-bottom: 10px;
            .select-type {
                margin-bottom: 10px;
            }

            .select-time {
                .item-time {
                    margin-bottom: 10px;
                }
            }
        }

        .zone-add {
            margin-top: 20px;

            h6 {
                width: 100%;
                text-indent: 45.5%;
                border-bottom: 2px solid $black;
                line-height: 0;

                span {
                    margin-top: 20px;

                    background: $white;
                    padding: 0 10px;

                    color: $black;

                    font-weight: bold;

                    font-size: 30px;

                    cursor: pointer;
                }
            }
        }

        .zone-add.disabled {
            opacity: 0.5;

            span {
                cursor: not-allowed !important;
            }
        }
    }
</style>
