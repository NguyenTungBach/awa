<template>
    <b-overlay
        :show="overlay.show"
        :variant="overlay.variant"
        :opacity="overlay.opacity"
        :blur="overlay.blur"
        :rounded="overlay.sm"
    >
        <template #overlay>
            <div class="text-center">
                <b-icon
                    icon="clock"
                    animation="cylon"
                    font-scale="4"
                    shift-v="8"
                    class="icon-loading"
                />
                <p class="text-loading">
                    {{ $t('APP.PLEASE_WAIT') }}
                </p>
            </div>
        </template>

        <router-view />

        <b-modal
            id="modal-message-response-ai"
            v-model="showModalMessage"
            :title="$t('MESSAGE_RESPONSE_AI.TITLE_MODAL')"
            no-close-on-backdrop
            no-close-on-esc
            hide-header
            hide-footer
            hide-header-close
            centered
            body-class="modal-body-response-ai"
            footer-class="modal-footer-response-ai"
            static
        >
            <template #default>
                <div
                    :class="{
                        'title-message': true,
                        'title-message-success': typeMessage === 'success',
                        'title-message-error': typeMessage === 'error',
                    }"
                >
                    <span>{{ $t(converStatusToTextStatus(typeMessage)) }}</span>
                </div>

                <div class="text-ai-response">
                    <span v-html="textMessage" />
                </div>

                <div class="control-ai-response">
                    <b-button
                        class="btn-close-message-response-ai"
                        pill
                        @click="onClickOk"
                    >
                        {{ $t('MESSAGE_RESPONSE_AI.BUTTON_OK') }}
                    </b-button>
                </div>
            </template>
        </b-modal>
    </b-overlay>
</template>

<script>
import CONSTANT from '@/const';
import { addRoutes } from '@/utils/handleRoutes';
import { getMessageResponseAi } from '@/api/modules/shiftManagement';
import { isAvailable } from '@/utils/isAvailable';
import { converStatusToTextStatus, convertStatusToMessage } from '@/utils/handleListShift';
import { handleReloadTableListShift } from '@/pages/ShiftManagement/ListShift/helper/reloadTable';

window.onbeforeunload = function() {
	const LIST_PAGE = [
		'/shift-management/list-shift-edit',
		'/shift-management/list-day-off-edit',
		'/shift-management/list-schedule-edit',
	];

	const ACTION = 'AWAIT CHANGE ROUTE';

	if (LIST_PAGE.includes(window.location.pathname)) {
		return ACTION;
	} else {
		window.removeEventListener(ACTION);
	}
};

export default {
	name: 'App',

	data() {
		return {
			isCheckAi: null,

			isPadding: false,
		};
	},

	computed: {
		overlay() {
			return this.$store.getters.loading;
		},

		showModalMessage() {
			return this.$store.getters.showModalResponseAI;
		},

		typeMessage() {
			return this.$store.getters.typeMessage;
		},

		textMessage() {
			return this.$store.getters.textMessge;
		},
	},

	created() {
		this.createdEventBus();

		const ROLES = this.$store.getters.profile.role;

		if (ROLES) {
			this.$store.dispatch('permissions/generateRoutes', { roles: [ROLES], permissions: [] })
				.then((routes) => {
					addRoutes(routes);
				});
		}

		console.log(
			'%cGlobal Air Cargo',
			'font-size: 20px; padding: 5px 10px 5px 10px; border-radius: .25rem; color: #FFF; background-color: #0282CB; text-align: center;'
		);
	},

	destroyed() {
		this.destroyedEventBus();
	},

	methods: {
		converStatusToTextStatus,

		async handleCheckResult() {
			console.log('%c[Global Air Cargo] - CHECK STATUS', 'font-size: 12px; padding: 3px; border-radius: .25rem; background-color: #7FB5FF; color: #3F3F3F; font-weight: bold;');
			try {
				const PARAMS = {
					status: 'new',
				};

				const AI = await getMessageResponseAi(CONSTANT.URL_API.GET_MESSAGE_RESPONSE_AI, PARAMS);

				if (AI.code === 200) {
					if (AI.status === 'on') {
						console.log('%cSTATUS: LOADING...', 'font-size: 12px; padding: 3px; border-radius: .25rem; background-color: #FBDF07; color: #3F3F3F; font-weight: bold;');

						setTimeout(async() => {
							await this.handleCheckResult();
						}, 5000);
					} else {
						if (isAvailable(AI, 'data')) {
							console.log(`%cSTATUS: ${AI.status}`, 'font-size: 12px; padding: 3px; border-radius: .25rem; background-color: #0282CB; color: #3F3F3F; font-weight: bold;');

							this.$store.dispatch('handleAI/setTypeMessage', AI.status)
								.then(() => {
									const MESSAGE = this.$t(convertStatusToMessage(AI.data.status)) || '';

									this.$store.dispatch('handleAI/setTextMessage', MESSAGE)
										.then(() => {
											this.setShowModalMessageResponseAI(true);
											this.setPaddingShift(false);
										});
								});
						} else {
							console.log(`%cSTATUS: NO AI TO RUN`, 'font-size: 12px; padding: 3px; border-radius: .25rem; background-color: #0282CB; color: #3F3F3F; font-weight: bold;');

							this.$store.dispatch('handleAI/setTypeMessage', AI.status)
								.then(() => {
									const MESSAGE = this.$t('MESSAGE_RESPONSE_AI.MESSAGE_NO_AI_TO_RUN');

									this.$store.dispatch('handleAI/setTextMessage', MESSAGE)
										.then(() => {
											this.setShowModalMessageResponseAI(true);
											this.setPaddingShift(false);
										});
								});
						}
					}
				}
			} catch (error) {
				this.setPaddingShift(false);

				console.log(error);
			}
		},

		createdEventBus() {
			this.$bus.on('TOSHIN_CHECK_AI', () => {
				setTimeout(async() => {
					await this.handleCheckResult();
				}, 5000);
			});
		},

		destroyedEventBus() {
			this.$bus.off('TOSHIN_CHECK_AI');
		},

		setPaddingShift(status = true) {
			this.$store.dispatch('loading/setPaddingShift', status);
		},

		setShowModalMessageResponseAI(status = true) {
			this.$store.dispatch('handleAI/setModalMessage', status);
		},

		async onClickOk() {
			try {
				const PARAMS = {
					status: 'update',
				};

				const { code } = await getMessageResponseAi(CONSTANT.URL_API.GET_MESSAGE_RESPONSE_AI, PARAMS);

				if (code === 200) {
					this.setShowModalMessageResponseAI(false);
				}

				handleReloadTableListShift();
			} catch (error) {
				handleReloadTableListShift();

				this.setShowModalMessageResponseAI(false);
			}
		},
	},
};
</script>

<style lang="scss" scoped>
@import "@/scss/variables";

.control-ai-response {
    display: flex;
    justify-content: center;

    .btn-close-message-response-ai {
        border: none;
        outline: none;

        min-width: 100px;

        &:hover {
            opacity: 0.8;
        }
    }
}

.text-ai-response {
    text-align: center;

    margin-top: 80px;
    margin-bottom: 80px;
}

.title-message {
    display: flex;
    background-color: $gray;
    color: $white;
    font-weight: bold;
    border-radius: 0.25rem;

    padding-top: 5px;
    padding-bottom: 5px;

    justify-content: center;
}

.title-message-success {
    background-color: $main;
}

.title-message-error {
    background-color: $punch;
}
</style>
