<template>
    <div class="login-page">
        <b-container>
            <div class="login-container">
                <div class="form-login">
                    <!-- <img class="show-icon" :src="require('@/assets/images/char8_1.png')" alt="Icon"> -->
                    <div class="show-title">
                        <!-- <img :src="require('@/assets/images/Global_Air_Cargo.png')" alt="Logo login"> -->
                        <h1 class="title-login">
                            {{ $t('LOGIN.TITLE_LOGIN') }}
                        </h1>
                    </div>
                    <div class="form-input-account">
                        <b-row align-h="center">
                            <b-col
                                sm="10"
                                md="10"
                                lg="6"
                                xl="5"
                            >
                                <div class="input-user-id">
                                    <b-input-group>
                                        <template #prepend>
                                            <b-input-group-text>
                                                <i class="fas fa-user-alt" />
                                            </b-input-group-text>
                                        </template>
                                        <b-form-input
                                            id="user_id"
                                            v-model="Account.id"
                                            :placeholder="$t('LOGIN.PLACEHOLDER_USER_ID')"
                                            spellcheck="false"
                                            :disabled="isLoading"
                                            @keyup.enter="clickLogin()"
                                        />
                                    </b-input-group>
                                </div>
                            </b-col>
                        </b-row>
                        <b-row align-h="center">
                            <b-col
                                sm="10"
                                md="10"
                                lg="6"
                                xl="5"
                            >
                                <div class="input-password">
                                    <b-input-group>
                                        <template #prepend>
                                            <b-input-group-text>
                                                <i class="fas fa-key" />
                                            </b-input-group-text>
                                        </template>
                                        <b-form-input
                                            id="password"
                                            v-model="Account.password"
                                            type="password"
                                            :placeholder="$t('LOGIN.PLACEHOLDER_USER_PASSWORD')"
                                            spellcheck="false"
                                            :disabled="isLoading"
                                            @keyup.enter="clickLogin()"
                                        />
                                    </b-input-group>
                                </div>
                            </b-col>
                        </b-row>
                    </div>
                    <div class="form-submit">
                        <b-button
                            class="btn-submit login-btn"
                            :disabled="isLoading"
                            @click="clickLogin()"
                        >
                            <i
                                v-if="isLoading"
                                class="fad fa-spinner-third fa-spin"
                            />
                            <span>
                                {{ $t('LOGIN.BUTTON_LOGIN') }}
                            </span>
                        </b-button>
                    </div>
                </div>
            </div>
        </b-container>
    </div>
</template>

<script>
import CONSTANT from '@/const';
import TOAST_LOGIN from '@/toast/modules/login';
import { postLogin } from '@/api/modules/login';
import { addRoutes } from '@/utils/handleRoutes';
import { validateUserID, validPassword } from '@/utils/validate';

export default {
	name: 'Login',
	data() {
		return {
			isLoading: false,
			Account: {
				id: '',
				password: '',
			},
		};
	},

	methods: {
		handleValidate(account) {
			const validate = {
				status: false,
				message: '',
			};

			if (!account.id || !account.password) {
				return {
					status: false,
					message: 'MESSAGE_APP.LOGIN_REQUIRED',
				};
			}

			if (!validateUserID(account.id)) {
				validate.status = false;
				validate.message = 'MESSAGE_APP.LOGIN_VALIDATE_USER_ID';

				return validate;
			} else if (!validPassword(account.password)) {
				validate.status = false;
				validate.message = 'MESSAGE_APP.LOGIN_VALIDATE_PASSWORD';

				return validate;
			} else {
				validate.status = true;
				validate.message = '';
			}

			return validate;
		},

		async clickLogin() {
			try {
				this.isLoading = true;

				const VALIDATE = this.handleValidate(this.Account);

				if (VALIDATE.status) {
					const BODY = {
						user_code: this.Account.id,
						password: this.Account.password,
					};

					const LOGIN = await postLogin(CONSTANT.URL_API.POST_LOGIN, BODY);

					if (LOGIN.code === 200) {
						TOAST_LOGIN.success();

						const TOKEN = LOGIN.data.access_token;
						const PROFILE = LOGIN.data.profile;

						this.$store.dispatch('listShift/setIsWeekOrMonth', CONSTANT.LIST_SHIFT.MONTH);

						this.$store.dispatch('login/saveToken', TOKEN)
							.then(() => {
								this.$store.dispatch('login/saveProfile', PROFILE)
									.then(() => {
										this.$store.dispatch('permissions/generateRoutes', { roles: [PROFILE.role], permissions: [] })
											.then((routes) => {
												addRoutes(routes);
												this.isLoading = false;

												this.$router.push({
													name: 'ListShift',
												});
											});
									});
							}).catch(() => {
								this.isLoading = false;
							});
					} else {
						this.isLoading = false;
						TOAST_LOGIN.exception();
					}
				} else {
					this.isLoading = false;
					TOAST_LOGIN.validate(VALIDATE.message);
				}
			} catch {
				this.isLoading = false;
			}
		},
	},
};
</script>

<style lang="scss" scoped>
@import '@/scss/variables';

.login-page {
    width: 100%;
    height: 100vh;
    overflow: hidden;
    background-color: $white;
    display: flex;
    align-items: center;

    .login-container {
        background-color: $sub-main;
        border-color: $silver-chalice;
        border-style: solid;
        border-width: 2px;
        border-radius: 20px;

        .form-login {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;

            margin: 30px 20px;

            overflow: hidden;

            position: relative;

            .show-icon {
                position: absolute;
                right: 5%;
                bottom: 0;

                height: 60%;

                z-index: 1;
            }

            .show-title {
                text-align: center;
                h1.title-login {
                    font-family: $font-josefin-sans, 'sans-serif';
                    width: 327px;
                    font-size: 80px;
                    font-weight: 300;
                    color: $main-header;
                    margin-top: 60px;
                }

                margin-bottom: 100px;

                z-index: 2;
            }

            .form-input-account {
                width: 100%;

                .input-user-id,
                .input-password {
                    margin-bottom: 30px;

                    .input-group-text {
                        background-color: $white;
                        border-right-color: transparent;

                        width: 50px;

                        justify-content: center;

                        i {
                            color: $manatee;

                            font-size: 22px;
                        }
                    }

                    .form-control {
                        border-left-color: transparent;

                        height: 50px;

                        font-size: 22px;

                        &:focus {
                            box-shadow: none;
                            border-color: $mischka;
                            border-left-color: transparent;
                        }
                    }
                }

                z-index: 2;
            }

            .form-submit {
                z-index: 2;

                margin-top: 90px;
                margin-bottom: 80px;

                .btn-submit {
                    min-width: 200px;
                    height: 70px;
                    background: $main-header;
                    box-shadow: 0px 4px 4px rgba(0, 0, 0, 0.25);
                    border-radius: 50px;
                    font-size: 32px;

                    color: $white;
                    border: none;

                    font-family: $font-inter, 'sans-serif';
                    font-weight: 600;

                    &:hover {
                        opacity: 0.8;
                    }
                }
            }
        }
    }
}
</style>
