<template>
    <div class="view-page">
        <div class="show-page">
            <router-view :key="key" />
        </div>
    </div>
</template>

<script>
export default {
	name: 'AppMain',
	computed: {
		key() {
			return this.$route.fullPath;
		},
	},
};
</script>

<style lang="scss" scoped>
    .view-page {
        z-index: 1;

        height: calc(100vh - 60px);
        overflow-y: auto;

        .show-page {
            margin: 20px 0;
        }
    }
</style>
