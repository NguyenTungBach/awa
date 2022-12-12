<template>
    <b-td
        :class="[isNode.listClass, isNode.isText === ICON_NO ? 'base-td-no' : '' ]"
        @click="onClickNode()"
    >
        <div v-if="isNode.isDisable === false">
            <div v-html="isNode.isText" />
        </div>
    </b-td>
</template>

<script>

const ICON_YES = '<i class="far fa-circle" />';
const ICON_NO = '<i class="far fa-times" />';

export default {
	name: 'NodeCoursePattern',
	props: {
		isEdit: {
			type: Boolean,
			default: () => false,
		},

		item: {
			type: Object,
			required: true,
			default: () => {
				return {
					status: null,
				};
			},
		},

		idxCourse: {
			type: Number,
			required: true,
			default: () => {
				return null;
			},
		},

		idxItem: {
			type: Number,
			required: true,
			default: () => {
				return null;
			},
		},

		listCourse: {
			type: Array,
			required: true,
			default: () => [],
		},
	},

	data() {
		return {
			ICON_YES,
			ICON_NO,

			isNode: {
				isDisable: null,

				isText: '',

				listClass: [],
			},
		};
	},

	created() {
		this.handleProcess();
	},

	methods: {
		handleProcess() {
			this.isNode.isDisable = this.handleDisable(this.idxCourse, this.idxItem, this.listCourse);

			this.isNode.listClass = this.handleGenereateClass(this.isNode);
		},

		handleDisable(idxCourse, idx, listCourse = []) {
			if (listCourse[idxCourse].course_patterns[idx].status === 'duplicate') {
				return true;
			} else {
				const STATUS = listCourse[idxCourse].course_patterns[idx].status;

				switch (STATUS) {
					case 'yes':
						this.isNode.isText = ICON_YES;
						break;

					case 'no':
						this.isNode.isText = ICON_NO;
						break;
					default:
						this.isNode.isText = '';
						break;
				}
			}

			return false;
		},

		handleGenereateClass(data) {
			const LIST_CLASS = ['base-td'];

			if (data.isDisable) {
				LIST_CLASS.push('base-td-disable');
			} else {
				if (this.isEdit) {
					LIST_CLASS.push('base-td-edit');
				}
			}

			return LIST_CLASS;
		},

		onClickNode() {
			if (this.isEdit && this.isNode.isDisable === false) {
				this.$emit('clickNode', this.item, this.idxItem);
			}
		},
	},
};
</script>

<style lang="scss" scoped>
    @import '@/scss/variables';

    .base-td {
        text-align: center;
        vertical-align: middle;
    }

    .base-td-disable {
        background-color: #c6c6c6 !important;

        cursor: not-allowed;
    }

    .base-td-no {
        background-color: #f0f0f0 !important;
    }

    .base-td-edit {
        &:hover {
            background-color: $main !important;
            color: $white;

            cursor: pointer;
        }
    }
</style>
