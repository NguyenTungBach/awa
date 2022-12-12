<template>
    <router-view :key="key" />
</template>

<script>
export default {
	name: 'LayoutUser',
	computed: {
		key() {
			return this.$route.fullPath;
		},
	},
};
</script>
