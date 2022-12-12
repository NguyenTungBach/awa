import store from '@/store';

function handleReloadTableListShift() {
	store.dispatch('listShift/setReloadTable');
}

export {
	handleReloadTableListShift
};
