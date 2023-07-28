<template>
    <b-col>
        <b-container>
            <div class="list-user">
                <div class="list-user__header">
                    <b-row>
                        <b-col>
                            <div class="zone-title">
                                <span class="title-page">
                                    {{ $t('LIST_USER.TITLE_LIST_USER') }}
                                </span>
                            </div>
                        </b-col>
                        <b-col>
                            <div class="zone-control text-right">
                                <b-button
                                    pill
                                    class="btn-control"
                                    @click="goToCreate()"
                                >
                                    {{ $t('APP.BUTTON_SIGN_UP') }}
                                </b-button>
                            </div>
                        </b-col>
                    </b-row>
                    <LineGray />
                </div>
                <div class="list-user__body">
                    <div class="zone-table">
                        <b-table
                            :items="items"
                            :fields="fields"
                            bordered
                            no-sort-reset
                            no-local-sorting
                            show-empty
                            @sort-changed="handleSort"
                        >
                            <template #cell(role)="scope">
                                {{ getRoleName(scope.item.role) }}
                            </template>
                            <template #cell(detail)="scope">
                                <i
                                    class="fas fa-eye"
                                    @click="goToDetail(scope.item)"
                                />
                            </template>
                            <template #cell(delete)="scope">
                                <i
                                    class="fas fa-trash-alt"
                                    @click="handleDelete(scope.item)"
                                />
                            </template>
                            <template #empty>
                                <div class="text-center">
                                    {{ $t('APP.TABLE_NO_DATA') }}
                                </div>
                            </template>
                        </b-table>
                    </div>
                </div>
            </div>
        </b-container>

        <b-modal
            id="modal-delete"
            ref="modal-delete"
            v-model="modalDelete"
            body-class="modal-detail"
            static
            :title="$t('APP.TITLE_MODAL_CONFIRM')"
            :cancel-title="$t('APP.TEXT_CANCEL')"
            :ok-title="$t('APP.TEXT_CONFIRM')"
            @hidden="resetModal"
            @ok="handleOk"
        >
            <div class="text-center">
                <span>{{ $t('LIST_USER.TEXT_CONFIRM_DELETE') }}</span>
            </div>
        </b-modal>
    </b-col>
</template>

<script>
import CONSTANT from '@/const';
import LineGray from '@/components/LineGray';
import { setLoading } from '@/utils/handleLoading';
import { cleanObject } from '@/utils/handleObject';
import { getRoleName } from '@/utils/handleCodeToText';
import TOAST_USER_MANAGEMENT from '@/toast/modules/userManagement';
import { getList, deleteUser } from '@/api/modules/userManagement';

export default {
	name: 'ListUser',
	components: {
		LineGray,
	},

	data() {
		return {
			CONSTANT,

			items: [],

			sortTable: {
				sortBy: '',
				sortType: '',
			},

			modalDelete: false,

			userScope: {
				id: null,
				user_code: null,
				user_name: null,
				role: null,
				jwt_active: null,
				remember_token: null,
				created_at: null,
				updated_at: null,
			},
		};
	},

	computed: {
		fields() {
			return [
				{ key: 'user_code', label: this.$t('LIST_USER.USER_ID'), sortable: true, thClass: 'base-th' },
				{ key: 'user_name', label: this.$t('LIST_USER.USER_NAME'), sortable: true, thClass: 'base-th' },
				{ key: 'role', label: this.$t('LIST_USER.USER_AUTHORITY'), sortable: true, thClass: 'base-th' },
				{ key: 'detail', label: this.$t('LIST_USER.DETAIL'), sortable: false, thClass: 'text-center base-th base-th-control', tdClass: 'text-center base-td-control' },
				{ key: 'delete', label: this.$t('LIST_USER.DELETE'), sortable: false, thClass: 'text-center base-th base-th-control', tdClass: 'text-center base-td-control' },
			];
		},
	},

	watch: {
		sortTable: {
			handler() {
				this.handleGetListUser();
			},

			deep: true,
		},
	},

	created() {
		this.initData();
	},

	methods: {
		getRoleName,
		async initData() {
			await this.handleGetListUser();
		},

		async handleGetListUser() {
			try {
				setLoading(true);

				let PARAMS = {
					order_by: this.sortTable.sortBy,
					sort: this.sortTable.sortType,
				};

				PARAMS = cleanObject(PARAMS);

				const LIST_USER = await getList(CONSTANT.URL_API.GET_LIST_USER, PARAMS);

				if (LIST_USER.code === 200) {
					this.items = LIST_USER.data;
				} else {
					this.items = [];
				}

				setLoading(false);
			} catch {
				setLoading(false);
			}
		},

		goToCreate() {
			this.$router.push({ name: 'UserCreate' });
		},

		goToDetail(scope) {
			this.$router.push({ name: 'UserDetail', params: { id: scope.id }});
		},

		handleDelete(scope) {
			this.userScope = scope;
			this.modalDelete = true;
		},

		resetModal() {
			this.userScope = {
				id: null,
				user_code: null,
				user_name: null,
				role: null,
				jwt_active: null,
				remember_token: null,
				created_at: null,
				updated_at: null,
			};
		},

		async handleOk() {
			try {
				this.modalDelete = false;
				setLoading(true);

				const DELETE_USER = await deleteUser(`${CONSTANT.URL_API.DELETE_USER}/${this.userScope.id}`);

				if (DELETE_USER.code === 200) {
					await this.handleGetListUser();
					TOAST_USER_MANAGEMENT.delete();
				}

				setLoading(false);
			} catch {
				setLoading(false);
			}
		},

		handleSort(ctx) {
			console.log('sort:', ctx);
			this.sortTable = {
				sortBy: ctx.sortBy,
				sortType: ctx.sortDesc ? 'asc' : 'desc',
			};
		},
	},
};
</script>

<style lang="scss" scoped>
    @import '@/scss/variables';

    .list-user {
        width: 100%;
        .list-user__body {
            ::v-deep .zone-table {
                height: calc(100vh - 162px);
                overflow-y: auto;

                th {
                    position: sticky !important;
                    top: 0;
                }

                .base-th {
                    background-color: $main;
                    color: $white;
                }

                .base-th-control {
                    width: 90px;
                }

                .base-td-control {
                    i {
                        color: $dusty-gray;
                        font-size: 24;

                        cursor: pointer;
                    }
                }

                .employee-retired {
                    background-color: $swiss-coffee;
                }

                td {
                    vertical-align: middle;
                }
            }
        }
    }
</style>
