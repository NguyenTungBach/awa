<template>
    <b-td
        v-if="(item.status !== 'on')"
        :key="keyRender" :style="setBackgroundColor(item.color)"
        :class="['node-course-base', 'node-disabled']"
    >
        <div class="node-course-base" />
    </b-td>
    <b-td
        v-else
        :key="keyRender" :style="setBackgroundColor(item.color)"
        @click="onClickNode"
    >
        <div class="node-course-base">
            {{ item.driver ? item.driver : '-' }}
        </div>
    </b-td>
</template>

<script>
export default {
	name: 'NodeCourseBase',
	props: {
		isEdit: {
			type: Boolean,
			default: false,
		},

		idxComponent: {
			type: Number,
			default: 0,
			required: false,
		},

		// date: {
		// 	type: Number,
		// 	required: false,
		// },

		idxCourse: {
			type: Number,
			required: false,
		},

		// dataNode: {
		// 	type: [Object],
		// 	// required: true,
		// 	nullable: true,
		// 	default: null,
		// },

		courseCode: {
			type: String,
			required: false,
		},

		courseName: {
			type: String,
			required: false,
		},

		startDate: {
			type: String,
			default: null,
		},

		endDate: {
			type: String,
			default: null,
		},

		keyRender: {
			type: [String, Number],
			default: function() {
				return '';
			},
		},

		item: {
			type: Object,
			default: function() {
				return {

				};
			},
		},
	},

	methods: {
		setBackgroundColor(color) {
			return `background-color: ${color}`;
		},

		onClickNode() {
			if (this.isEdit && this.item.status === 'on') {
				const data = {
					idx_component: this.idxComponent,
					idx_course: this.idxCourse,
					course_code: this.courseCode,
					course_name: this.courseName,
					item: this.item,
				};
				// const DATA = {
				// 	item: this.item,
				// 	driverCode: this.driverCode,
				// 	date: this.dateEdit,
				// };
				// const DATA = {
				//     index: this.idxComponent,
				//     date: this.date,
				//     driverCode: this.driverCode,
				//     driverName: this.driverName,
				//     dataNode: this.dataNode,
				// };

				// this.$bus.emit('LIST_SHITF_CLICK_NODE', DATA);

				console.log('click node', data);
				console.log('item', this.item);
				this.$emit('clickNode', data);
			}
		},

	},
};
</script>

<style lang="scss" scoped>
    @import '@/scss/variables';

    td {
        vertical-align: middle;

        .node-course-base {
            display: flex;
            height: 78px;
            justify-content: center;
            align-items: center;
            overflow: hidden;
        }

        .node-disabled {
            cursor: not-allowed !important;
            // background-color: #EBEBEB !important;
            // background-color: red !important;
        }
    }
</style>
