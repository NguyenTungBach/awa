<template>
    <b-col>
        <div class="page-list-course-pattern">
            <div class="page-list-course-pattern__header">
                <b-row>
                    <b-col>
                        <div class="zone-title">
                            <span class="title-page">
                                {{ $t('LIST_COURSE_PATTERN.TITLE_LIST_COURSE_PATTERN') }}
                            </span>
                        </div>
                    </b-col>
                </b-row>

                <LineGray />
            </div>

            <div class="page-list-course-pattern__body">
                <b-row>
                    <b-col>
                        <div class="zone-control text-right">
                            <b-button
                                pill
                                class="btn-color-active btn-edit"
                                @click="goToEdit()"
                            >
                                {{ $t('APP.BUTTON_EDIT') }}
                            </b-button>
                        </div>
                    </b-col>
                </b-row>

                <b-row>
                    <b-col>
                        <div class="zone-table">
                            <b-table-simple
                                bordered
                                no-border-collapse
                            >
                                <b-thead>
                                    <b-tr>
                                        <b-th class="base-th-course-id">
                                            {{ $t('LIST_COURSE_PATTERN.COURSE_ID') }}
                                        </b-th>
                                        <b-th class="base-th-course-name">
                                            {{ $t('LIST_COURSE_PATTERN.COURSE_NAME') }}
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
                                                />
                                            </template>
                                        </b-tr>
                                    </template>
                                </b-tbody>
                            </b-table-simple>
                            <template v-if="listCoursePatern.length === 0">
                                <div class="text-center">
                                    {{ $t('APP.TABLE_NO_DATA') }}
                                </div>
                            </template>
                        </div>
                    </b-col>
                </b-row>
            </div>
        </div>
    </b-col>
</template>

<script>
import CONSTANT from '@/const';
import { getList } from '@/api/modules/coursePatern';
import { cleanObject } from '@/utils/handleObject';
import LineGray from '@/components/LineGray';
import { setLoading } from '@/utils/handleLoading';
import NodeCoursePattern from '@/components/NodeCoursePattern';

export default {
	name: 'ListCoursePattern',
	components: {
		LineGray,
		NodeCoursePattern,
	},

	data() {
		return {
			listCoursePatern: [],
		};
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

				let PARAMS = {};

				PARAMS = cleanObject(PARAMS);

				const LIST = await getList(CONSTANT.URL_API.GET_LIST_COURSE_PATERN, PARAMS);

				if (LIST.code === 200) {
					this.listCoursePatern = LIST.data;
				} else {
					this.listCoursePatern = [];
				}

				setLoading(false);
			} catch {
				setLoading(false);
			}
		},

		goToEdit() {
			this.$router.push({
				name: 'EditListCoursePattern',
			});
		},
	},
};
</script>

<style lang="scss" scoped>
    @import '@/scss/variables';

    .page-list-course-pattern {
        width: 100%;

        &__body {
            .zone-control {
                margin-bottom: 10px;
            }

            .zone-table {
                height: calc(100vh - 210px);
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
</style>
