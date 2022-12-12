<template>
    <b-col>
        <b-container>
            <div class="page-detail-user">
                <div class="page-detail-user__header">
                    <b-row>
                        <b-col>
                            <div class="zone-title">
                                <span class="title-page">
                                    {{ $t('DETAIL_USER.TITLE_DETAIL_USER') }}
                                </span>
                            </div>
                        </b-col>
                    </b-row>
                    <LineGray />
                </div>
                <div class="page-detail-user__body">
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
                                    class="btn-color-active btn-edit"
                                    @click="onClickEdit()"
                                >
                                    {{ $t('APP.BUTTON_EDIT') }}
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
                                                {{ $t('DETAIL_USER.USER_INFORMATION') }}
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
                                                        <DetailForm :label="$t('DETAIL_USER.USER_ID')" :value="isForm.user_id" />
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
                                                        <DetailForm :label="$t('DETAIL_USER.USERNAME')" :value="isForm.user_name" />
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
                                                        <DetailForm :label="$t('DETAIL_USER.USER_AUTHORITY')" :value="isForm.user_authority" />
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
                                                        <DetailForm :label="$t('DETAIL_USER.PASSWORD')" :value="'●●●●●●'" />
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
import DetailForm from '@/components/DetailForm';
import { setLoading } from '@/utils/handleLoading';
import TitlePathForm from '@/components/TitlePathForm';
import { getUser } from '@/api/modules/userManagement';
import { convertValueToText } from '@/utils/handleSelect';

export default {
	name: 'DetailUser',
	components: {
		LineGray,
		TitlePathForm,
		DetailForm,
	},

	data() {
		return {
			CONSTANT,

			isForm: {
				user_id: '',
				user_name: '',
				user_authority: null,
				password: 'password',

				optionsAuthority: [
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

	created() {
		this.initData();
	},

	methods: {
		convertValueToText,

		async initData() {
			await this.handleGetUser();
		},

		async handleGetUser() {
			try {
				setLoading(true);

				const ID = this.$route.params.id;

				const USER = await getUser(`${CONSTANT.URL_API.GET_ONE_USER}/${ID}`);

				if (USER.code === 200) {
					this.isForm = {
						...this.isForm,

						user_id: USER.data.user_code,
						user_name: USER.data.user_name,
						user_authority: convertValueToText(this.isForm.optionsAuthority, USER.data.role),
						password: 'password',
					};
				}

				setLoading(false);
			} catch {
				setLoading(false);
			}
		},

		onClickReturn() {
			this.$router.push({ name: 'ListUser' });
		},

		onClickEdit() {
			this.$router.push({ name: 'UserEdit', params: { id: this.$route.params.id }});
		},
	},
};
</script>

<style lang="scss" scoped>
    @import '@/scss/variables';

    .page-detail-user {
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
                        margin-bottom: 20px;
                        font-size: 18px;
                    }
                }
            }
        }
    }
</style>
