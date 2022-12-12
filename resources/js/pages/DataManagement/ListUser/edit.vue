<template>
    <b-col>
        <b-container>
            <div class="page-edit-user">
                <div class="page-edit-user__header">
                    <b-row>
                        <b-col>
                            <div class="zone-title">
                                <span class="title-page">
                                    {{ $t('EDIT_USER.TITLE_EDIT_USER') }}
                                </span>
                            </div>
                        </b-col>
                    </b-row>
                    <LineGray />
                </div>
                <div class="page-edit-user__body">
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
                                    @click="onClickSave()"
                                >
                                    {{ $t('APP.BUTTON_SAVE') }}
                                </b-button>
                            </div>
                        </b-col>
                    </b-row>
                    <b-row>
                        <b-col>
                            <div class="zone-form">
                                <div class="form-body">
                                    <b-row>
                                        <b-col
                                            :cols="12"
                                            :sm="12"
                                            :md="12"
                                            :lg="4"
                                            :xl="4"
                                        >
                                            <div class="zone-avatar">
                                                <img
                                                    class="avatar-user"
                                                    :src="require('@/assets/images/user_icon.png')"
                                                    alt="Avatar User"
                                                >
                                            </div>
                                        </b-col>
                                        <b-col
                                            :cols="12"
                                            :sm="12"
                                            :md="12"
                                            :lg="8"
                                            :xl="8"
                                        >
                                            <TitlePathForm>
                                                {{ $t('EDIT_USER.USER_INFORMATION') }}
                                            </TitlePathForm>
                                            <b-row>
                                                <b-col
                                                    :cols="12"
                                                    :sm="12"
                                                    :md="12"
                                                    :lg="12"
                                                    :xl="12"
                                                >
                                                    <div class="item-form">
                                                        <label for="input-user-id">
                                                            {{ $t('EDIT_USER.USER_ID') }}
                                                        </label>
                                                        <b-form-input
                                                            id="input-user-id"
                                                            type="number"
                                                            :value="isForm.user_id"
                                                            disabled
                                                        />
                                                    </div>
                                                </b-col>
                                                <b-col
                                                    :cols="12"
                                                    :sm="12"
                                                    :md="12"
                                                    :lg="12"
                                                    :xl="12"
                                                >
                                                    <div class="item-form">
                                                        <label for="input-user-name">
                                                            {{ $t('EDIT_USER.USERNAME') }}
                                                        </label>
                                                        <b-form-input
                                                            id="input-user-name"
                                                            v-model="isForm.user_name"
                                                            :disabled="isLoading"
                                                        />
                                                    </div>
                                                </b-col>
                                            </b-row>
                                            <b-row>
                                                <b-col
                                                    :cols="12"
                                                    :sm="12"
                                                    :md="12"
                                                    :lg="12"
                                                    :xl="12"
                                                >
                                                    <div class="item-form">
                                                        <label for="input-user-authority">
                                                            {{ $t('EDIT_USER.USER_AUTHORITY') }}
                                                        </label>
                                                        <b-form-select
                                                            id="input-user-authority"
                                                            v-model="isForm.user_authority"
                                                            :options="isForm.optionsAuthority"
                                                            :disabled="isLoading"
                                                        />
                                                    </div>
                                                </b-col>
                                                <b-col
                                                    :cols="12"
                                                    :sm="12"
                                                    :md="12"
                                                    :lg="12"
                                                    :xl="12"
                                                >
                                                    <div class="item-form">
                                                        <label for="input-user-password">
                                                            {{ $t('EDIT_USER.PASSWORD') }}
                                                        </label>
                                                        <b-input-group>
                                                            <b-form-input
                                                                id="input-user-password"
                                                                v-model="isForm.password"
                                                                :type="showPassword ? 'text' : 'password'"
                                                                :disabled="isLoading"
                                                                autocomplete="new-password"
                                                                placeholder="●●●●●●●"
                                                            />
                                                            <b-input-group-append class="mouse-poiter" @click="showPassword = !showPassword">
                                                                <b-input-group-text>
                                                                    <i v-if="showPassword === false" class="fa-solid fa-eye" />

                                                                    <i v-else class="fa-solid fa-eye-slash" />
                                                                </b-input-group-text>
                                                            </b-input-group-append>
                                                        </b-input-group>
                                                    </div>
                                                </b-col>
                                            </b-row>
                                        </b-col>
                                    </b-row>
                                </div>
                            </div>
                        </b-col>
                    </b-row>
                </div>
            </div>
        </b-container>
    </b-col>
</template>

<script>
import CONSTANT from '@/const';
import LineGray from '@/components/LineGray';
import { setLoading } from '@/utils/handleLoading';
import { validateUser } from '@/utils/validateCRUD';
import TitlePathForm from '@/components/TitlePathForm';
import { getUser, putUser } from '@/api/modules/userManagement';
import TOAST_USER_MANAGEMENT from '@/toast/modules/userManagement';

export default {
	name: 'EditlUser',
	components: {
		LineGray,
		TitlePathForm,
	},

	data() {
		return {
			CONSTANT,

			showPassword: false,

			isForm: {
				id: null,
				user_id: '',
				user_name: '',
				user_authority: null,
				password: '',

				optionsAuthority: [
					{
						value: null,
						text: this.$t('APP.PLEASE_SELECT'),
					},
					{
						value: CONSTANT.LIST_USER.ROLE_DRIVER,
						text: this.$t(CONSTANT.LIST_USER.TEXT_ROLE_DRIVER),
					},
					{
						value: CONSTANT.LIST_USER.ROLE_SYSTEM_ADMINISTRATOR,
						text: this.$t(CONSTANT.LIST_USER.TEXT_ROLE_SYSTEM_ADMINISTRATOR),
					},
				],
			},

			inited: false,
		};
	},

	computed: {
		isLoading() {
			return this.$store.getters.loading.show;
		},

		changeForm() {
			return this.isForm;
		},
	},

	watch: {
		changeForm: {
			handler: function() {
				if (this.inited) {
					this.$store.dispatch('user/setWarningNotSave', true);
				}
			},

			deep: true,
		},
	},

	created() {
		this.initData();
	},

	methods: {
		async initData() {
			this.inited = false;
			await this.handleGetUser();
			this.inited = true;
		},

		async handleGetUser() {
			try {
				setLoading(true);

				const ID = this.$route.params.id;

				const USER = await getUser(`${CONSTANT.URL_API.GET_ONE_USER}/${ID}`);

				if (USER.code === 200) {
					this.isForm = {
						...this.isForm,

						id: USER.data.id,
						user_id: USER.data.user_code,
						user_name: USER.data.user_name,
						user_authority: USER.data.role,
						password: '',
					};
				}

				setLoading(false);
			} catch {
				setLoading(false);
			}
		},

		onClickReturn() {
			this.$router.push({ name: 'UserDetail', params: { id: this.isForm.id }});
		},

		async onClickSave() {
			try {
				await this.$store.dispatch('user/setWarningNotSave', false)
					.then(async() => {
						setLoading(true);

						const ID = this.isForm.id;

						let BODY = {
							user_name: this.isForm.user_name,
							role: this.isForm.user_authority,
						};

						if (this.isForm.password) {
							BODY = {
								...BODY,
								password: this.isForm.password,
							};
						}

						const VALIDATE = validateUser(BODY, Object.keys(BODY));

						if (VALIDATE.status) {
							const USER = await putUser(`${CONSTANT.URL_API.PUT_USER}/${ID}`, BODY);

							if (USER.code === 200) {
								setLoading(false);

								this.goToDetailUser();
								TOAST_USER_MANAGEMENT.update();
							}
						} else {
							TOAST_USER_MANAGEMENT.validate(VALIDATE.message);
						}

						setLoading(false);
					});
			} catch {
				setLoading(false);
			}
		},

		goToDetailUser() {
			this.$router.push({ name: 'UserDetail', params: { id: this.isForm.id }});
		},
	},
};
</script>

<style lang="scss" scoped>
    @import '@/scss/variables';

    .page-edit-user {
        width: 100%;

        &__body {
            .zone-control {
                margin-bottom: 10px;
            }

            .zone-form {
                height: calc(100vh - 210px);
                border: 1px solid $geyser;

                .form-body {
                    margin: 80px 20px 20px 20px;

                    .zone-avatar {
                        display: flex;
                        justify-content: center;
                        align-items: center;
                        vertical-align: middle;

                        .avatar-user {
                            height: 230px;
                        }
                    }

                    .item-form {
                        margin-bottom: 10px;
                        font-size: 18px;
                    }
                }
            }
        }
    }

    .mouse-poiter {
        cursor: pointer;
    }
</style>
