<template>
    <b-td v-if="!dataNode">
        <div class="show-node" />
    </b-td>
    <b-td
        v-else-if="dataNode"
        :id="`node-${idxComponent}-${date}-${driverCode}`"
        :class="['node-base', isEdit ? 'show-node-edit node-base-hover' : '']"
        :style="{ backgroundColor: dataNode.course_names_color }"
        @click="onClickNode"
    >
        <div :class="['show-node']">
            <div v-if="isDayOff(dataNode.course_names_color)">
                {{ dataNode.value[0].name || '' }}
            </div>
            <div v-else>
                <template v-if="idxComponent === Number((dataNode.date).slice(-2))">
                    <div
                        class="show-course"
                    >
                        {{ dataNode.course_names }}
                    </div>
                </template>

                <template v-if="listText.length > 2">
                    <b-row>
                        <b-col
                            cols="11"
                            style="padding: 0"
                        >
                            <div class="show-course-more">
                                {{ listText[0].name }}
                            </div>
                            <div class="show-course-more">
                                {{ listText[1].name }}
                            </div>
                        </b-col>
                        <b-col
                            cols="1"
                            style="padding: 0"
                        >
                            <div class="icon-more">
                                <i class="fad fa-plus-circle icon-show-more" />
                            </div>
                        </b-col>
                    </b-row>

                    <b-popover
                        :target="`node-${idxComponent}-${date}-${driverCode}`"
                        triggers="hover"
                    >
                        <div
                            v-for="(item, idx) in listText"
                            :key="idx"
                        >
                            {{ idx + 1 }}. {{ item.name }}
                        </div>
                    </b-popover>
                </template>
            </div>
        </div>
    </b-td>
    <b-td
        v-else-if="
            (dataNode.value !== null && !handleDisabledDate(startDate, endDate, dataNode.date)) ||
                (dataNode.value === null && !handleDisabledDate(startDate, endDate, dataNode.date))
        "
        :class="['node-base', 'node-disabled']"
    >
        <div class="show-node" />
    </b-td>
    <b-td
        v-else
        :id="`node-${idxComponent}-${date}-${driverCode}`"
        :class="['node-base', isEdit ? 'show-node-edit node-base-hover' : '']"
    >
        <div class="show-node" />
    </b-td>
</template>

<script>
import CONSTANT from '@/const';
import { handleDisabledDate } from '@/pages/ShiftManagement/ListShift/helper/disabledDate';

export default {
	name: 'NodeListShift',
	props: {
		isEdit: {
			type: Boolean,
			default: false,
		},

		idxComponent: {
			type: Number,
			default: 0,
			required: true,
		},

		date: {
			type: Number,
			required: true,
		},

		dataNode: {
			type: [Object],
			// required: true,
			nullable: true,
			default: null,
		},

		driverCode: {
			type: String,
			required: true,
		},

		driverName: {
			type: String,
			required: true,
		},

		startDate: {
			type: String,
			default: null,
		},

		endDate: {
			type: String,
			default: null,
		},
	},

	data() {
		return {
			CONSTANT,

			dateNode: {
				nodeType: null,
				nodeText: [],
				nodeClass: '',
			},

			listText: [],
		};
	},

	created() {
		// this.handleFilterListText();
	},

	methods: {
		handleDisabledDate,
		// handleFilterListText() {
		// 	const NOT_SHOW = [
		// 		CONSTANT.LIST_SHIFT.DATE_WAIT_BETWEEN_TASK,
		// 	];

		// 	if (this.dataNode.value.length > 0) {
		// 		this.listText = (this.dataNode.value).filter((item) => !NOT_SHOW.includes(item.type));
		// 	}
		// },

		onClickNode() {
			const DATA = {
				index: this.idxComponent,
				date: this.date,
				driverCode: this.driverCode,
				driverName: this.driverName,
				dataNode: this.dataNode,
			};

			this.$bus.emit('LIST_SHITF_CLICK_NODE', DATA);
		},

		isDayOff(color) {
			return [
				CONSTANT.LIST_SHIFT.COLOR_HOLIDAY,
				CONSTANT.LIST_SHIFT.COLOR_FIXED_DAY_OFF,
				CONSTANT.LIST_SHIFT.COLOR_DAY_OFF_REQUEST,
				CONSTANT.LIST_SHIFT.COLOR_PAID,
			].includes(color);
		},

		generate() {
			const STATUS = this.empData.status;
			if (STATUS === 'on' && this.item.value !== null) {
				const LIST_TPYE_DATE = [
					CONSTANT.LIST_SHIFT.DATE_HOLIDAY,
					CONSTANT.LIST_SHIFT.DATE_FIXED_DAY_OFF,
					CONSTANT.LIST_SHIFT.DATE_DAY_OFF_REQUEST,
					CONSTANT.LIST_SHIFT.DATE_PAID,
				];

				const TYPE = this.item.dataNode;

				if (LIST_TPYE_DATE.includes(TYPE)) {
					this.dateNode.nodeType = TYPE;
					this.dateNode.nodeClass = this.processClass(TYPE);
					this.dateNode.nodeText = this.processText(TYPE);
				} else {
					const TYPE_DEFAULT = CONSTANT.LIST_SHIFT.DATE_PAID;

					this.dateNode.nodeType = TYPE_DEFAULT;
					this.dateNode.nodeClass = this.processClass(TYPE_DEFAULT);
					this.dateNode.nodeText = this.processText(TYPE_DEFAULT);
				}
			} else if (STATUS === 'on' && this.item.value === null) {
				this.dateNode.nodeType = null;
				this.dateNode.nodeClass = '';
				this.dateNode.nodeText = '';
			}

			if (STATUS === 'off') {
				const TYPE_DISABLE = 'DISABLE';

				this.dateNode.nodeType = TYPE_DISABLE;
				this.dateNode.nodeClass = 'node-disable';
				this.dateNode.nodeText = '';
			}
		},

		processClass(type) {
			switch (type) {
				case CONSTANT.LIST_SHIFT.DATE_WORKING_DAY:
					return CONSTANT.LIST_SHIFT.COLOR_WORKING_DAY;

				case CONSTANT.LIST_SHIFT.DATE_HOLIDAY:
					return CONSTANT.LIST_SHIFT.COLOR_HOLIDAY;

				case CONSTANT.LIST_SHIFT.DATE_FIXED_DAY_OFF:
					return CONSTANT.LIST_SHIFT.COLOR_FIXED_DAY_OFF;

				case CONSTANT.LIST_SHIFT.DATE_DAY_OFF_REQUEST:
					return CONSTANT.LIST_SHIFT.COLOR_DAY_OFF_REQUEST;

				case CONSTANT.LIST_SHIFT.DATE_PAID:
					return CONSTANT.LIST_SHIFT.COLOR_PAID;

				default:
					return '';
			}
		},

		processText(type) {
			switch (type) {
				case CONSTANT.LIST_SHIFT.DATE_HOLIDAY:
					return CONSTANT.LIST_SHIFT.TEXT_DATE_HOLIDAY;

				case CONSTANT.LIST_SHIFT.DATE_FIXED_DAY_OFF:
					return CONSTANT.LIST_SHIFT.TEXT_DATE_FIXED_DAY_OFF;

				case CONSTANT.LIST_SHIFT.DATE_DAY_OFF_REQUEST:
					return CONSTANT.LIST_SHIFT.TEXT_DATE_DAY_OFF_REQUEST;

				case CONSTANT.LIST_SHIFT.DATE_PAID:
					return CONSTANT.LIST_SHIFT.TEXT_DATE_PAID;

				default:
					return '';
			}
		},
	},
};
</script>

<style lang="scss" scoped>
    @import '@/scss/variables';

    .node-base {
        width: 150px;

        vertical-align: middle;
    }

    .node-base-hover {
        &:hover {
            background-color: $main !important;
            color: $white;
        }
    }

    .show-node {
        min-height: 78px;

        display: flex;
        align-items: center;
        justify-content: center;

        margin-left: 5px;
        margin-right: 5px;

        div {
            text-align: center;

            font-size: 16px;
        }

        word-spacing: 1px;

        .show-course {
            margin-bottom: 5px;

            white-space: nowrap;
            max-width: 150px;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .show-course-more {
            margin-bottom: 5px;

            white-space: nowrap;
            max-width: 100px;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .icon-more {
            .icon-show-more {
                margin-top: 20px;
                margin-left: 5px;
                color: $main;
            }
        }
    }

    .show-node-edit {
        cursor: pointer;
    }

    .node-disabled {
        cursor: not-allowed !important;
        background-color: #c6c6c6 !important;
    }

    .show-node-working-day {
        background-color: $working-day;
    }

    .show-node-holiday {
        background-color: $holiday;
    }

    .show-node-fixed-day-off {
        background-color: $fixed-day-off;
    }

    .show-node-day-off-request {
        background-color: $day-off-request;
    }

    .show-node-paid {
        background-color: $paid;
    }
</style>
