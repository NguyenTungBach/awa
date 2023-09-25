<template>
    <router-view :key="key" />
</template>

<script>
export default {
	name: 'LayoutCustomer',
	computed: {
		key() {
			return this.$route.fullPath;
		},
	},
};
</script>
