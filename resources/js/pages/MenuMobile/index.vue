<template>
    <div class="menuMobile">
        <div class="menuMobile__shift" @click="onClickReturnListShift()">
            <div class="icon_shift">
                <!-- <img :src="require('@/assets/images/SearchinList.png')" alt="Avatar menuMobile"> -->
                <i class="fas fa-list icon-menu" />
            </div>
            <div class="text_shift">
                <p>シフト表</p>
            </div>
        </div>
        <div class="menuMobile__logout" @click="onClickLogout()">
            <div class="icon_logout">
                <!-- <img :src="require('@/assets/images/Logout.png')" alt="Avatar menuMobile"> -->
                <i class="fas fa-sign-out-alt icon-menu" />
            </div>
            <div class="text_logout">
                <p>ログアウト</p>
            </div>
        </div>
    </div>
</template>

<script>
import TOAST_LOGOUT from '@/toast/modules/logout';
export default {
	name: 'MenuNavbar',
	methods: {
		onClickReturnListShift() {
			this.$router.push({ name: 'ListShift' });
		},

		onClickLogout() {
			this.$store.dispatch('login/saveLogout')
				.then(() => {
					TOAST_LOGOUT.success();

					this.$router.push('/login');
				});
		},
	},
};
</script>

<style lang="scss" scoped>
@import "@/scss/variables";
.menuMobile {
    &__shift {
        display: flex;
        margin: 5px 30px;
        cursor: pointer;
        .text_shift {
            font-size: 30px;
            margin: 0 10px;
        }
        .icon_shift {
            .icon-menu {
                font-size: 30px;
                color: $dusty-gray;
            }
        }
    }
    &__logout {
        display: flex;
        margin: 5px 30px;
        cursor: pointer;
        .text_logout {
            font-size: 30px;
            margin: 0 10px;
        }
        .icon_logout {
            .icon-menu {
                font-size: 30px;
                color: $dusty-gray;
            }
        }
    }
}
</style>
