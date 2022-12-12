<template
    v-for="(data, idx) in empData.course_schedules"
    :key="idx"
>
    <b-td
        v-if="empData.end_date && compareDate(item.schedule_date, empData.end_date)"
        :class="['base-td-end-date']"
    >
        <span v-html="dateNode.nodeText" />
    </b-td>
    <b-td
        v-else-if="empData.start_date && compareDate(empData.start_date, item.schedule_date)"
        :class="['base-td-end-date']"
    >
        <span v-html="dateNode.nodeText" />
    </b-td>
    <b-td
        v-else
        :class="['node-base', isEdit ? 'node-base-hover' : '', dateNode.nodeText === ICON_NO ? 'base-td-no' : '' ]"
        @click="onClickNode()"
    >
        <span v-html="dateNode.nodeText" />
    </b-td>
</template>

<script>
import CONSTANT from '@/const';

const ICON_YES = '<i class="far fa-circle" />';
const ICON_NO = '<i class="far fa-times" />';

export default {
	name: 'NodeSchedule',
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
					course_code: '',
					course_name: '',
					course_schedules: {},
					end_date: '',
					id: '',
					start_date: '',
					group: '',
				};
			},
		},

		courseCode: {
			type: [String, Number],
			required: false,
			default: '',
		},

		dateEdit: {
			type: [String, Number],
			required: false,
			default: '',
		},

		empData: {
			type: Object,
			required: true,
		},

	},

	data() {
		return {
			ICON_YES,
			ICON_NO,

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
		generate() {
			const TYPE = this.item.status;
			this.dateNode.nodeType = TYPE;
			this.dateNode.nodeClass = this.processClass(TYPE);
			this.dateNode.nodeText = this.processText(TYPE);
		},

		processClass(type) {
			return '';
		},

		processText(type) {
			switch (type) {
				case CONSTANT.LIST_SCHEDULE.TYPE_LIST_SCHEDULE_ACTIVE:
					return ICON_YES;
				case CONSTANT.LIST_SCHEDULE.TYPE_LIST_SCHEDULE_NO_ACTIVE:
					return ICON_NO;
				default:
					return '';
			}
		},

		onClickNode() {
			if (this.isEdit) {
				const DATA = {
					item: this.item,
					courseCode: this.courseCode,
					date: this.dateEdit,
				};

				this.$emit('clickNode', DATA);
			}
		},

		compareDate(date, endDate) {
			const _date = new Date(date);
			const _endDate = new Date(endDate);

			return _date.getTime() > _endDate.getTime();
		},
	},
};
</script>

<style lang="scss" scoped>
    @import '@/scss/variables';

    .node-base {
        min-width: 50px;
    }

    .node-base-hover {
        &:hover {
            background-color: $main !important;
            color: $white;
            cursor: pointer;
        }
    }

    .base-td-no {
        background-color: #f0f0f0 !important;
    }

	.base-td-end-date {
        background-color: #c6c6c6 !important;
    }
</style>
