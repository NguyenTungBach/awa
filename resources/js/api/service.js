import axios from 'axios';
import i18n from '@/lang';
import store from '@/store';
import router from '@/router';
import { getToken } from '@/utils/handleToken';
import { MakeToast } from '@/toast/toastMessage';
import { getLanguage } from '@/lang/helper/getLang';

const baseURL = process.env.MIX_BASE_API;

const service = axios.create({
	baseURL: baseURL,
	timeout: 100000,
});

service.interceptors.request.use(
	config => {
		const token = getToken();

		config.headers['Accept-Language'] = getLanguage();
		config.headers['Authorization'] = token;

		return config;
	},
	error => {
		Promise.reject(error);
	}
);

service.interceptors.response.use(
	response => {
		return response.data;
	},
	error => {
		const CODE = error.response.data.code;
		const MESSAGE = error.response.data.message || error.response.data.message_content || i18n.t('MESSAGE_APP.EXCEPTION');

		if (CODE === 401 && error.response.data.message_content === 'token expire') {
			if (store.getters.token) {
				store.dispatch('login/saveLogout')
					.then(() => {
						router.push({ name: 'Login' });

						MakeToast({
							variant: 'warning',
							title: i18n.t('TOAST.WARNING'),
							content: i18n.t('MESSAGE_APP.TOKEN_EXPIRE'),
						});
					});
			}
		} else {
			if (
				(CODE !== 401 && error.response.data.message_content !== 'token not provided') ||
                    (CODE === 401 && error.response.data.message_content !== 'token not provided')
			) {
				MakeToast({
					variant: 'danger',
					title: i18n.t('TOAST.DANGER'),
					content: MESSAGE,
				});
			}
		}

		return Promise.reject(error);
	}
);

export { service };
