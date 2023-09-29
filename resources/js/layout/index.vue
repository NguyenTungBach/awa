<template>
    <div class="app-layout">
        <div class="app-layout__navbar">
            <Navbar />
        </div>

        <div class="app-layout__app-main">
            <AppMain />
        </div>
    </div>
</template>

<script>
import Navbar from './components/Navbar.vue';
import AppMain from './components/AppMain.vue';

export default {
	name: 'Layout',
	components: {
		Navbar,
		AppMain,
	},
};
</script>
<style lang="scss" scoped>
    .app-layout {
        font-size: 14px;
    }
</style>
