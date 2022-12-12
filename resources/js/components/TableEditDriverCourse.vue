<template>
    <div class="table-edit-driver-course">
        <div
            class="zone-table-driver-course"
            :style="styleTable"
        >
            <b-table-simple
                bordered
                no-border-collapse
            >
                <b-thead>
                    <b-tr>
                        <b-th class="th-remove" />
                        <b-th class="th-runable-course-name">
                            {{ $t('EDIT_DRIVER.RUNABLE_COURSE_NAME') }}
                        </b-th>
                        <b-th class="th-exclusive text-center">
                            {{ $t('EDIT_DRIVER.TABLE_EXCLUSIVE') }}
                        </b-th>
                    </b-tr>
                </b-thead>
                <b-tbody>
                    <template v-for="(selected, idxSelected) in listSelected">
                        <b-tr :key="`course-selected-number-${idxSelected + 1}`">
                            <b-td>
                                <b-button @click="onClickDelete(idxSelected)">
                                    <i class="fas fa-minus" />
                                </b-button>
                            </b-td>
                            <b-td>
                                <b-form-select
                                    v-model="selected.id"
                                    placeholder="選択して下さい"
                                >
                                    <b-form-select-option :value="null">
                                        選択して下さい
                                    </b-form-select-option>
                                    <template v-for="(course, idxCourse) in listCourse">
                                        <b-form-select-option
                                            :key="`course-number-${idxCourse + 1}`"
                                            :value="course.id"
                                            :disabled="isCheckExit(listDisable, course.id)"
                                        >
                                            {{ course.course_name }}
                                        </b-form-select-option>
                                    </template>
                                </b-form-select>
                            </b-td>
                            <b-td class="text-center">
                                <b-form-checkbox
                                    v-model="selected.is_checked"
                                    style="margin-top: 4px;"
                                    value="yes"
                                    unchecked-value="no"
                                />
                            </b-td>
                        </b-tr>
                    </template>
                    <b-tr />
                </b-tbody>
            </b-table-simple>
        </div>

        <div class="zone-btn-add">
            <b-button
                block
                @click="onClickAdd()"
            >
                <i class="fas fa-plus-circle" />
            </b-button>
        </div>
    </div>
</template>

<script>
export default {
	name: 'TableEditDriverCourse',
	props: {
		listSelected: {
			type: Array,
			required: true,
			default: () => [],
		},

		listCourse: {
			type: Array,
			required: true,
			default: () => [],
		},

		styleTable: {
			type: String,
			required: false,
			default: 'max-height: calc(100vh - 320px); overflow-y: auto;',
		},
	},

	data() {
		return {
			listDisable: [],
		};
	},

	watch: {
		listSelected: {
			handler: function() {
				this.listDisable.length = 0;

				const len = this.listSelected.length;
				let idx = 0;

				while (idx < len) {
					this.listDisable.push(this.listSelected[idx].id);

					idx++;
				}
			},

			deep: true,
		},
	},

	methods: {
		onClickAdd() {
			this.$emit('add');
		},

		onClickDelete(index) {
			this.$emit('delete', index);
		},

		isCheckExit(list, item) {
			return list.includes(item);
		},
	},
};
</script>

<style lang="scss" scoped>
    @import '@/scss/variables';

    .table-edit-driver-course {
        .zone-table-driver-course {
            table {
                thead {
                    tr {
                        th {
                            background-color: $main;
                            color: $white;

                            position: sticky;
                            top: 0;
                        }

                        .th-remove {
                            width: 50px;
                        }

                        .th-fatigue {
                            width: 300px;
                        }

                        .th-exclusive {
                            width: 80px;
                        }
                    }
                }
            }
        }

        .zone-btn-add {
            margin-top: 10px;
        }

    }
</style>
