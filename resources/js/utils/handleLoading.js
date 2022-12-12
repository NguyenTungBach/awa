import store from '@/store';

function setLoading(status = true) {
	store.dispatch('loading/setLoading', status);
}

export {
	setLoading
};
