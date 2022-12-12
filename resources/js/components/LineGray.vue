<template>
    <div class="component-line-gray" />
</template>

<script>
export default {
	name: 'LineGray',
};
</script>

<style lang="scss" scoped>
    @import '@/scss/variables';

    .component-line-gray {
        margin: 10px 0;

        width: 100%;
        height: 3px;
        background-color: $alto;
    }
</style>
