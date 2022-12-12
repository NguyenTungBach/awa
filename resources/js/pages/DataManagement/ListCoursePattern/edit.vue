<template>
    <b-col>
        <div class="page-edit-course-pattern">
            <div class="page-edit-course-pattern__header">
                <b-row>
                    <b-col>
                        <div class="zone-title">
                            <span class="title-page">
                                {{ $t('EDIT_COURSE_PATTERN.TITLE_EDIT_COURSE_PATTERN') }}
                            </span>
                        </div>
                    </b-col>
                </b-row>

                <LineGray />
            </div>

            <div class="page-edit-course-pattern__body">
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
                                class="btn-color-active btn-save"
                                @click="onClickSaveCoursePattern()"
                            >
                                {{ $t('APP.BUTTON_SAVE') }}
                            </b-button>
                        </div>
                    </b-col>
                </b-row>

                <b-row>
                    <b-col>
                        <div class="zone-table">
                            <b-table-simple
                                :key="reRender"
                                bordered
                                no-border-collapse
                            >
                                <b-thead>
                                    <b-tr>
                                        <b-th class="base-th-course-id">
                                            {{ $t('EDIT_COURSE_PATTERN.COURSE_ID') }}
                                        </b-th>
                                        <b-th class="base-th-course-name">
                                            {{ $t('EDIT_COURSE_PATTERN.COURSE_NAME') }}
                                        </b-th>
                                        <template v-for="(course, idx) in listCoursePatern">
                                            <b-th
                                                :key="`b-th-${idx + 1}`"
                                                class="base-th-course"
                                            >
                                                {{ course.course_name }}
                                            </b-th>
                                        </template>
                                    </b-tr>
                                </b-thead>

                                <b-tbody>
                                    <template v-for="(course, idx) in listCoursePatern">
                                        <b-tr :key="`b-tr-${idx + 1}`">
                                            <b-td class="base-td-course-id">
                                                {{ course.course_code }}
                                            </b-td>
                                            <b-td class="base-td-course-name">
                                                {{ course.course_name }}
                                            </b-td>
                                            <template v-for="(courseChild, idxChild) in listCoursePatern">
                                                <NodeCoursePattern
                                                    :key="`td-course-${idxChild + 1}`"
                                                    :idx-course="idx"
                                                    :idx-item="idxChild"
                                                    :item="course"
                                                    :list-course="listCoursePatern"
                                                    :is-edit="true"
                                                    @clickNode="onClickNode"
                                                />
                                            </template>
                                        </b-tr>
                                    </template>
                                </b-tbody>
                            </b-table-simple>
                        </div>
                    </b-col>
                </b-row>
            </div>
        </div>

        <b-modal
            id="modal-edit"
            v-model="showModal"
            body-class="modal-edit-node"
            hide-footer
            :title="$t('COURSE_PATTERN.MODAL_CHANGE_COURSE_PATTERN')"
            no-close-on-esc
            no-close-on-backdrop
            static
            @close="handleCloseModal"
        >
            <div class="zone-select-day-off">
                <b-form-group
                    v-slot="{ ariaDescribedby }"
                    :label="$t('COURSE_PATTERN.MODAL_CHANGE_COURSE_PATTERN')"
                >
                    <b-form-radio
                        v-model="selectType"
                        :aria-describedby="ariaDescribedby"
                        name="type_list_schedule_active"
                        :value="CONSTANT.COURSE_PATTERN.TYPE_COURSE_PATTERN_ACTIVE"
                    >
                        <i class="far fa-circle" />
                    </b-form-radio>
                    <b-form-radio
                        v-model="selectType"
                        :aria-describedby="ariaDescribedby"
                        name="type_list_schedule_no_active"
                        :value="CONSTANT.COURSE_PATTERN.TYPE_COURSE_PATTERN_NO_ACTIVE"
                    >
                        <i class="far fa-times" />
                    </b-form-radio>
                </b-form-group>
            </div>
            <div class="zone-save">
                <b-button
                    block
                    pill
                    class="btn-save"
                    @click="handleSaveModal()"
                >
                    {{ $t('APP.BUTTON_SAVE') }}
                </b-button>
            </div>
        </b-modal>
    </b-col>
</template>

<script>
import CONSTANT from '@/const';
import { getList, postCoursePattern } from '@/api/modules/coursePatern';
import LineGray from '@/components/LineGray';
import TOAST_COURSE_MANAGEMENT from '@/toast/modules/coursePatternManagement';
import { setLoading } from '@/utils/handleLoading';
import NodeCoursePattern from '@/components/NodeCoursePattern';

export default {
	name: 'EditListCoursePattern',
	components: {
		LineGray,
		NodeCoursePattern,
	},

	data() {
		return {
			CONSTANT,

			items: null,
			idxItem: null,

			listCoursePatern: [],

			listUpdate: [],

			showModal: false,
			selectType: CONSTANT.LIST_SCHEDULE.TYPE_LIST_SCHEDULE_ACTIVE,

			reRender: 0,
		};
	},

	watch: {
		listUpdate: {
			handler: function() {
				this.$store.dispatch('listCoursePattern/setWarningNotSave', true);
			},

			deep: true,
		},
	},

	created() {
		this.initData();
	},

	methods: {

		async initData() {
			await this.handleGetList();
		},

		async handleGetList() {
			try {
				setLoading(true);

				const LIST = await getList(CONSTANT.URL_API.GET_LIST_COURSE_PATERN, null);

				if (LIST.code === 200) {
					this.listCoursePatern = LIST.data;
				} else {
					this.listCoursePatern = [];
				}

				this.$store.dispatch('listCoursePattern/setList', this.listCoursePatern)
					.then(() => {
						setLoading(false);
					})
					.catch((error) => {
						console.log(error);

						setLoading(false);
					});
			} catch {
				setLoading(false);
			}
		},

		goToEdit() {
			this.$router.push({
				name: 'EditListCoursePattern',
			});
		},

		onClickReturn() {
			this.$router.push({
				name: 'ListCoursePattern',
			});
		},

		onClickNode(item, idx) {
			this.items = item;
			this.idxItem = idx;

			this.selectType = this.items.course_patterns[this.idxItem].status;

			this.showModal = true;
		},

		handleCloseModal() {
			this.selectType = CONSTANT.LIST_SCHEDULE.TYPE_LIST_SCHEDULE_ACTIVE;
		},

		handleSaveModal() {
			const finData = this.listCoursePatern;
			for (let index = 0; index < finData.length; index++) {
				const element = finData[index];
				for (let indexs = 0; indexs < element.course_patterns.length; indexs++) {
					const data = element.course_patterns[indexs];

					if (data.id === this.items.course_patterns[this.idxItem].id) {
						this.handleUpdateListUpdate(data.id, this.selectType);

						const PARENT = this.listCoursePatern[index].course_patterns[this.idxItem].course_parent_code;
						const CHILD = this.listCoursePatern[index].course_patterns[this.idxItem].course_child_code;

						const ID = this.handleUpdateSymmetryCourse(PARENT, CHILD, this.selectType);
						this.handleUpdateListUpdate(ID, this.selectType);

						this.listCoursePatern[index].course_patterns[this.idxItem].status = this.selectType;
						this.reRender = this.reRender + 1;
						this.showModal = false;
					}
				}
			}

			this.$store.dispatch('listCoursePattern/setList', this.listUpdate)
				.then(() => {
					setLoading(false);
				})
				.catch((error) => {
					console.log(error);

					setLoading(false);
				});
		},

		handleUpdateSymmetryCourse(parent, child, status) {
			const len = this.listCoursePatern.length;
			let idx = 0;

			while (idx < len) {
				if (this.listCoursePatern[idx].course_code === child) {
					const lenChild = this.listCoursePatern[idx].course_patterns.length;
					let idxChild = 0;

					while (idxChild < lenChild) {
						if (this.listCoursePatern[idx].course_patterns[idxChild].course_child_code === parent) {
							this.listCoursePatern[idx].course_patterns[idxChild].status = status;

							return this.listCoursePatern[idx].course_patterns[idxChild].id;
						}

						idxChild++;
					}
				}

				idx++;
			}
		},

		handleUpdateListUpdate(ID, status) {
			const len = this.listUpdate.length;
			let idx = 0;

			let isExit = {
				status: false,
				index: -1,
			};

			while (idx < len) {
				if (this.listUpdate[idx].id === ID) {
					isExit = {
						status: true,
						index: idx,
					};
				}

				idx++;
			}

			if (isExit.status) {
				this.listUpdate[isExit.index].status = status;
			} else {
				this.listUpdate.push({
					id: ID,
					status: status,
				});
			}
		},

		async onClickSaveCoursePattern() {
			try {
				await this.$store.dispatch('listCoursePattern/setWarningNotSave', false)
					.then(async() => {
						if (this.listUpdate.length > 0) {
							setLoading(true);

							const dataUpdate = this.listUpdate;

							const COURSEPATTERN = await postCoursePattern(CONSTANT.URL_API.POST_COURSE_PATTERN, { items: dataUpdate });

							if (COURSEPATTERN.code === 200) {
								this.onClickReturn();
								TOAST_COURSE_MANAGEMENT.update();
							}

							setLoading(false);
						} else {
							TOAST_COURSE_MANAGEMENT.messageSuccess(this.$t('MESSAGE_APP.COURSE_PATTERN_VALIDATE_LIST_UPDATE_EMPTY'));

							this.onClickReturn();
						}
					});
			} catch {
				setLoading(false);
			}
		},
	},
};
</script>

<style lang="scss" scoped>
    @import '@/scss/variables';

    .page-edit-course-pattern {
        width: 100%;
        height: calc(100vh - 80px);

        &__body {
            .zone-control {
                margin-bottom: 10px;
            }

            .zone-table {
                height: calc(100vh - 200px);
                overflow-y: auto;

                table {
                    thead {
                        tr {
                            th {
                                background-color: $main;
                                color: $white;

                                text-align: center;
                                vertical-align: middle;

                                position: sticky;
                                top: 0;
                                z-index: 9;
                            }

                            th.base-th-course-id {
                                min-width: 150px;

                                text-align: left;

                                position: sticky;
                                top: 0;
                                left: 0;
                                z-index: 10;
                            }

                            th.base-th-course-name {
                                min-width: 200px;

                                text-align: left;

                                position: sticky;
                                top: 0;
                                left: 150px;
                                z-index: 10;
                            }

                            th.base-th-course {
                                min-width: 150px;
                            }
                        }
                    }

                    tbody {
                        tr {
                            td {
                                background-color: $white;
                            }

                            td.base-td-course-id {
                                background-color: $sub-main;

                                position: sticky;
                                top: 0;
                                left: 0;
                                z-index: 9;

                                font-weight: bold;
                            }

                            td.base-td-course-name {
                                background-color: $sub-main;

                                position: sticky;
                                top: 0;
                                left: 150px;
                                z-index: 9;

                                font-weight: bold;
                            }
                        }
                    }
                }
            }
        }
    }

    .modal-edit-node {
        .zone-save {
            margin-top: 20px;

            .btn-save {
                background-color: $main;
                color: $white;
                font-weight: 600;

                border-color: transparent;

                &:hover {
                    opacity: 0.8;
                }
            }
        }
    }
</style>
