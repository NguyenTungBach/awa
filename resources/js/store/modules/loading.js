const state = {
	overlay: {
		show: false,
		variant: 'light',
		opacity: 1,
		blur: '1rem',
		rounded: 'sm',
	},

	paddingShift: {
		show: false,
		variant: 'white',
		opacity: 1,
		blur: '1rem',
		rounded: 'sm',
	},
};

const mutations = {
	SET_LOADING: (state, status) => {
		state.overlay.show = status;
	},
	SET_PADDING_SHIFT: (state, status) => {
		state.paddingShift.show = status;
	},
};

const actions = {
	setLoading({ commit }, status = true) {
		commit('SET_LOADING', status);
	},
	setPaddingShift({ commit }, status = true) {
		commit('SET_PADDING_SHIFT', status);
	},
};

export default {
	namespaced: true,
	state,
	mutations,
	actions,
};
