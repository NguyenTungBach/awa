<template>
    <b-td
        :id="`node-${item.driver_code}-${item.date}`"
        :class="['base-node', dateNode.nodeClass, (isEdit && item.status === 'on') ? 'base-node-hover' : '']"
        @click="onClickNode"
    >
        <div
            v-if="item.has_codes"
            class="show-course"
        >
            <div class="zone-course">
                <template v-if="(getCourse(item.has_codes)).length === 1">
                    <div class="show-course-more">
                        {{ (getCourse(item.has_codes))[0] }}
                    </div>
                </template>

                <template v-if="(getCourse(item.has_codes)).length === 2">
                    <div
                        v-for="(course, idx) in getCourse(item.has_codes)"
                        :key="`course-${idx}`"
                        class="show-course-more"
                    >
                        {{ course }}
                    </div>
                </template>

                <template v-if="(getCourse(item.has_codes)).length > 2">
                    <div class="show-course-more">
                        {{ (getCourse(item.has_codes))[0] }}
                    </div>

                    <div class="show-course-more">
                        {{ (getCourse(item.has_codes))[1] }}
                    </div>

                    <b-popover
                        :target="`node-${item.driver_code}-${item.date}`"
                        triggers="hover"
                    >
                        <div
                            v-for="(item, idx) in getCourse(item.has_codes)"
                            :key="idx"
                        >
                            {{ idx + 1 }}. {{ item }}
                        </div>
                    </b-popover>
                </template>
            </div>

            <div v-if="(getCourse(item.has_codes)).length > 2" class="zone-icon">
                <i class="fad fa-plus-circle" />
            </div>
        </div>

        <span v-else>{{ $t(dateNode.nodeText) }}</span>
    </b-td>
</template>

<script>
import CONSTANT from '@/const';

export default {
	name: 'NodeDayOff',
	props: {
		isEdit: {
			type: Boolean,
			default: false,
		},

		item: {
			type: Object,
			required: true,
			default: () => {
				return {
					driver_code: '',
					date: '',
					type: '',
					color: '',
					status: '',
					has_codes: '',
					lunar_jp: {
						id: null,
						date: '',
						week: '',
						rokuyou: '',
						holiday: null,
					},
				};
			},
		},

		idDriver: {
			type: Number,
			required: false,
		},

		driverCode: {
			type: [String, Number],
			required: false,
			default: '',
		},

		dateEdit: {
			type: [String, Number],
			required: false,
			default: '',
		},

		listCourse: {
			type: Array,
			required: false,
			default: () => {
				return [];
			},
		},
	},

	data() {
		return {
			dateNode: {
				nodeType: null,
				nodeText: '',
				nodeClass: '',
			},
		};
	},

	created() {
		this.generate();
	},

	methods: {
		splitCourse(list_course) {
			if (list_course) {
				return list_course.split(',');
			}

			return [];
		},

		getKeyInList(list, key) {
			const len = list.length;
			let idx = 0;
			const result = [];

			while (idx < len) {
				result.push(list[idx][key]);

				idx++;
			}

			return result;
		},

		getCourse(list_course) {
			const LIST_COURSE = this.splitCourse(list_course);

			const COURSES = this.listCourse.filter((course) => LIST_COURSE.includes(course.course_code));

			const LIST_COURSE_NAME = this.getKeyInList(COURSES, 'course_name');

			return LIST_COURSE_NAME;
		},

		generate() {
			const STATUS = this.item.status;

			const listUpdate = this.$store.getters.listUpdateDayoff;

			if (listUpdate.length === 0) {
				if (STATUS === 'on') {
					const LIST_TPYE_DATE = [
						CONSTANT.LIST_DAY_OFF.TYPE_DAY_OFF_FIXED_DAY_OFF,
						CONSTANT.LIST_DAY_OFF.TYPE_DAY_OFF_DAY_OFF_REQUEST,
						CONSTANT.LIST_DAY_OFF.TYPE_DAY_OFF_PAID,
						CONSTANT.LIST_DAY_OFF.TYPE_DAY_OFF_DEFAULT,
					];

					const TYPE = this.item.type;

					if (LIST_TPYE_DATE.includes(TYPE)) {
						this.dateNode.nodeType = TYPE;
						this.dateNode.nodeClass = this.processClass(TYPE);
						this.dateNode.nodeText = this.processText(TYPE);
					} else {
						const TYPE_DEFAULT = CONSTANT.LIST_DAY_OFF.TYPE_DAY_OFF_DEFAULT;

						this.dateNode.nodeType = TYPE_DEFAULT;
						this.dateNode.nodeClass = this.processClass(TYPE_DEFAULT);
						this.dateNode.nodeText = this.processText(TYPE_DEFAULT);
					}
				}

				if (STATUS === 'off') {
					const TYPE_DISABLE = 'DISABLE';

					this.dateNode.nodeType = TYPE_DISABLE;
					this.dateNode.nodeClass = 'node-disable';
					this.dateNode.nodeText = '';
				}
			}

			if (listUpdate.length !== 0) {
				let index = -1;
				for (let i = 0; i < listUpdate.length; i++) {
					if (listUpdate[i].date_off === this.item.date && listUpdate.driver_code === this.item.driverCode) {
						index = i;
						break;
					}
				}

				if (index !== -1 && listUpdate[index].driver_code === this.item.driver_code && this.isEdit) {
					if (STATUS === 'on') {
						const LIST_TPYE_DATE = [
							CONSTANT.LIST_DAY_OFF.TYPE_DAY_OFF_FIXED_DAY_OFF,
							CONSTANT.LIST_DAY_OFF.TYPE_DAY_OFF_DAY_OFF_REQUEST,
							CONSTANT.LIST_DAY_OFF.TYPE_DAY_OFF_PAID,
							CONSTANT.LIST_DAY_OFF.TYPE_DAY_OFF_DEFAULT,
						];

						const TYPE = listUpdate[index].type;

						if (LIST_TPYE_DATE.includes(TYPE)) {
							this.dateNode.nodeType = TYPE;
							this.dateNode.nodeClass = this.processClass(TYPE);
							this.dateNode.nodeText = this.processText(TYPE);
						} else {
							const TYPE_DEFAULT = CONSTANT.LIST_DAY_OFF.TYPE_DAY_OFF_DEFAULT;

							this.dateNode.nodeType = TYPE_DEFAULT;
							this.dateNode.nodeClass = this.processClass(TYPE_DEFAULT);
							this.dateNode.nodeText = this.processText(TYPE_DEFAULT);
						}
					}

					if (STATUS === 'off') {
						const TYPE_DISABLE = 'DISABLE';

						this.dateNode.nodeType = TYPE_DISABLE;
						this.dateNode.nodeClass = 'node-disable';
						this.dateNode.nodeText = '';
					}
				} else {
					if (STATUS === 'on') {
						const LIST_TPYE_DATE = [
							CONSTANT.LIST_DAY_OFF.TYPE_DAY_OFF_FIXED_DAY_OFF,
							CONSTANT.LIST_DAY_OFF.TYPE_DAY_OFF_DAY_OFF_REQUEST,
							CONSTANT.LIST_DAY_OFF.TYPE_DAY_OFF_PAID,
							CONSTANT.LIST_DAY_OFF.TYPE_DAY_OFF_DEFAULT,
						];

						const TYPE = this.item.type;

						if (LIST_TPYE_DATE.includes(TYPE)) {
							this.dateNode.nodeType = TYPE;
							this.dateNode.nodeClass = this.processClass(TYPE);
							this.dateNode.nodeText = this.processText(TYPE);
						} else {
							const TYPE_DEFAULT = CONSTANT.LIST_DAY_OFF.TYPE_DAY_OFF_DEFAULT;

							this.dateNode.nodeType = TYPE_DEFAULT;
							this.dateNode.nodeClass = this.processClass(TYPE_DEFAULT);
							this.dateNode.nodeText = this.processText(TYPE_DEFAULT);
						}
					}

					if (STATUS === 'off') {
						const TYPE_DISABLE = 'DISABLE';

						this.dateNode.nodeType = TYPE_DISABLE;
						this.dateNode.nodeClass = 'node-disable';
						this.dateNode.nodeText = '';
					}
				}
			}
		},

		processClass(type) {
			if (this.item.has_codes) {
				return 'node-work';
			}

			switch (type) {
				case CONSTANT.LIST_DAY_OFF.TYPE_DAY_OFF_FIXED_DAY_OFF:
					return CONSTANT.LIST_DAY_OFF.CLASS_DAY_OFF_FIXED_DAY_OFF;
				case CONSTANT.LIST_DAY_OFF.TYPE_DAY_OFF_DAY_OFF_REQUEST:
					return CONSTANT.LIST_DAY_OFF.CLASS_DAY_OFF_DAY_OFF_REQUEST;
				case CONSTANT.LIST_DAY_OFF.TYPE_DAY_OFF_PAID:
					return CONSTANT.LIST_DAY_OFF.CLASS_DAY_OFF_PAID;
				case CONSTANT.LIST_DAY_OFF.TYPE_DAY_OFF_DEFAULT:
					return CONSTANT.LIST_DAY_OFF.CLASS_DAY_OFF_DEFAULT;
				default:
					return '';
			}
		},

		processText(type) {
			switch (type) {
				case CONSTANT.LIST_DAY_OFF.TYPE_DAY_OFF_FIXED_DAY_OFF:
					return CONSTANT.LIST_DAY_OFF.TEXT_DAY_OFF_FIXED_DAY_OFF;
				case CONSTANT.LIST_DAY_OFF.TYPE_DAY_OFF_DAY_OFF_REQUEST:
					return CONSTANT.LIST_DAY_OFF.TEXT_DAY_OFF_DAY_OFF_REQUEST;
				case CONSTANT.LIST_DAY_OFF.TYPE_DAY_OFF_PAID:
					return CONSTANT.LIST_DAY_OFF.TEXT_DAY_OFF_PAID;
				case CONSTANT.LIST_DAY_OFF.TYPE_DAY_OFF_DEFAULT:
					return CONSTANT.LIST_DAY_OFF.TEXT_DAY_OFF_DEFAULT;
				default:
					return '';
			}
		},

		onClickNode() {
			if (this.isEdit && this.item.status === 'on') {
				const DATA = {
					item: this.item,
					idDriver: this.idDriver,
					driverCode: this.driverCode,
					date: this.dateEdit,
				};

				this.$emit('clickNode', DATA);
			}
		},
	},
};
</script>

<style lang="scss" scoped>
    @import '@/scss/variables';

    .base-node {
        min-width: 125px;
        vertical-align: middle;

        .show-course {
            display: flex;
			justify-content: center;
            padding: 0 10px;

            .zone-course {
                display: flex;
                align-items: center;
                flex-direction: column;
				justify-content: center;

                .show-course-more {
                    margin-bottom: 5px;

                    white-space: nowrap;
                    max-width: 110px;
                    overflow: hidden;
                    text-overflow: ellipsis;
                }
            }

            .zone-icon {
                display: flex;
                align-items: center;
                height: 72px;
                color: $main;
				margin-left: 10px;
            }
        }
    }

    .base-node-hover {
        &:hover {
            background-color: $main !important;
            color: $white;
            cursor: pointer;

            .zone-icon {
                color: $white;
            }
        }
    }

    .node-fixed-day-off {
		height: 72px;
        background-color: $day-off-fixed-day-off;
    }

    .node-day-off-request {
		height: 72px;
        background-color: $day-off-request;
    }

    .node-paid {
		height: 72px;
        background-color: $day-off-paid;
    }

    .node-work {
		height: 72px;
        background-color: $gallery;
    }

    .node-default {
		height: 72px;
        background-color: $day-off-default;
    }

    .node-disable {
		height: 72px;
        background-color: #c6c6c6 !important;
        cursor: not-allowed !important;
    }
</style>
