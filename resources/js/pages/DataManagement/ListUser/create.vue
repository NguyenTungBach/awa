<template>
    <b-col>
        <b-container>
            <div class="page-create-user">
                <div class="page-create-user__header">
                    <b-row>
                        <b-col>
                            <div class="zone-title">
                                <span class="title-page">
                                    {{ $t('CREATE_USER.TITLE_CREATE_USER') }}
                                </span>
                            </div>
                        </b-col>
                    </b-row>
                    <LineGray />
                </div>
                <div class="page-create-user__body">
                    <b-row>
                        <b-col>
                            <div class="zone-control text-right">
                                <b-button
                                    pill
                                    :disabled="isLoading"
                                    class="btn-return"
                                    @click="onClickReturn()"
                                >
                                    {{ $t('APP.BUTTON_RETURN') }}
                                </b-button>
                                <b-button
                                    pill
                                    class="btn-color-active btn-save"
                                    :disabled="isLoading"
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
                                                {{ $t('CREATE_USER.USER_INFORMATION') }}
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
                                                            {{ $t('CREATE_USER.USER_ID') }}
                                                        </label>
                                                        <b-form-input
                                                            id="input-user-id"
                                                            v-model="isForm.user_id"
                                                            type="number"
                                                            :disabled="isLoading"
                                                            @keydown.native="validInputNumber"
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
                                                            {{ $t('CREATE_USER.USERNAME') }}
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
                                                            {{ $t('CREATE_USER.USER_AUTHORITY') }}
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
                                                            {{ $t('CREATE_USER.PASSWORD') }}
                                                        </label>

                                                        <b-input-group>
                                                            <b-form-input
                                                                id="input-user-password"
                                                                v-model="isForm.password"
                                                                :type="showPassword ? 'text' : 'password'"
                                                                :disabled="isLoading"
                                                                autocomplete="new-password"
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
import { validInputNumber } from '@/utils/handleInput';
import { setLoading } from '@/utils/handleLoading';
import { validateUser } from '@/utils/validateCRUD';
import TitlePathForm from '@/components/TitlePathForm';
import { postUser } from '@/api/modules/userManagement';
import TOAST_USER_MANAGEMENT from '@/toast/modules/userManagement';

export default {
	name: 'CreateUser',
	components: {
		LineGray,
		TitlePathForm,
	},

	data() {
		return {
			CONSTANT,

			showPassword: false,

			isForm: {
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
		};
	},

	computed: {
		isLoading() {
			return this.$store.getters.loading.show;
		},
	},

	methods: {
		validInputNumber,

		onClickReturn() {
			this.$router.push({ name: 'ListUser' });
		},

		async onClickSave() {
			try {
				setLoading(true);

				const USER = {
					user_code: this.isForm.user_id,
					user_name: this.isForm.user_name,
					password: this.isForm.password,
					role: this.isForm.user_authority,
				};

				const VALIDATE = validateUser(USER);

				if (VALIDATE.status) {
					const STATUS = await postUser(CONSTANT.URL_API.POST_USER, USER);

					if (STATUS.code === 200) {
						this.goToListUser();
						setLoading(false);
						TOAST_USER_MANAGEMENT.success();
					}
				} else {
					setLoading(false);
					TOAST_USER_MANAGEMENT.validate(VALIDATE.message);
				}
			} catch {
				setLoading(false);
			}
		},

		goToListUser() {
			this.$router.push({ name: 'ListUser' });
		},
	},
};
</script>

<style lang="scss" scoped>
    @import '@/scss/variables';

    .page-create-user {
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
