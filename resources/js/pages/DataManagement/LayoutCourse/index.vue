<template>
    <router-view :key="key" />
</template>

<script>
export default {
	name: 'LayoutCourse',
	computed: {
		key() {
			return this.$route.fullPath;
		},
	},
};
</script>
