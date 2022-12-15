<template>
    <div class="zone-navbar">
        <div class="zone-navigation">
            <div class="show-logo">
                <img :src="require('@/assets/images/logo.png')" alt="Logo">
            </div>
            <div class="show-menu">
                <ul class="reset item-modules">
                    <template v-for="(modules, idx) in routes">
                        <li :key="`modules-${idx + 1}`">
                            <span>
                                {{ $t(modules.meta.title) }}
                            </span>
                            <ul
                                class="reset item-path"
                                dusk="select-menu"
                            >
                                <template v-for="(path, idxPath) in modules.children">
                                    <li
                                        v-if="path.hidden !== true"
                                        :key="`path-${idxPath + 1}`"
                                    >
                                        <router-link :to="`${modules.path}/${path.path}`">
                                            {{ $t(path.meta.title) }}
                                        </router-link>
                                        <ul class="reset item-child">
                                            <template v-for="(child, idxChild) in path.children">
                                                <li
                                                    v-if="child.hidden !== true"
                                                    :key="`child-${idxChild + 1}`"
                                                >
                                                    <router-link :to="`${modules.path}/${path.path}/${child.path}`">
                                                        {{ $t(child.meta.title) }}
                                                    </router-link>
                                                </li>
                                            </template>
                                        </ul>
                                    </li>
                                </template>
                            </ul>
                        </li>
                    </template>
                </ul>
            </div>
        </div>

        <div class="show-menu-right">
            <div v-if="showPickYearMonth" class="picker-month-year">
                <PickerYearMonth />
            </div>

            <div class="show-profile">
                <div class="icon-profile">
                    <i class="fas fa-user-circle" />
                </div>
                <div class="username">
                    <span>{{ username }}</span>
                </div>
                <div class="icon-dropdown">
                    <i class="fas fa-caret-down" />
                </div>

                <ul class="reset menu-profile">
                    <li @click="onClickLogout()">
                        <span>{{ $t('LAYOUT.LOGOUT') }}</span>
                        <i class="fas fa-sign-out" />
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>

<script>
import PickerYearMonth from './PickerYearMonth';
import TOAST_LOGOUT from '@/toast/modules/logout';

export default {
	name: 'Navbar',
	components: {
		PickerYearMonth,
	},

	data() {
		return {
			showPickYearMonth: true,
		};
	},

	computed: {
		routes() {
			return this.$store.getters.permissionRoutes.filter((route) => route.hidden !== true);
		},

		username() {
			return this.$store.getters.profile.user_name;
		},

		currentRouter() {
			return this.$route.name;
		},
	},

	watch: {
		currentRouter() {
			const ROUTER_NAME = this.$route.name;
			const PAGE = ['ListShift', 'ListDayOff', 'ListSchedule'];

			this.showPickYearMonth = PAGE.includes(ROUTER_NAME);
		},
	},

	methods: {
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
@import '@/scss/variables.scss';

.zone-navbar {
    display: inline-flex;
    width: 100%;
    height: 70px;

    background-color: $main-header;

    z-index: 10 !important;

    justify-content: space-between;

    .zone-navigation {
        display: flex;

        .show-logo {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 110px;
            height: 40px;
            margin: 10px 40px;

            img {
                height: 35px;
                padding-right: 20px;
            }
        }

        .show-menu {
            ul li ul {
                display: none;
            }

            ul {
                list-style-type: none;

                z-index: 999 !important;

                li {
                    float: left;
                    position: relative;

                    width: 170px;

                    span {
                        display: flex;
                        align-items: center;
                        justify-content: center;

                        line-height: 70px;

                        padding: 0 10px;

                        color: $white;

                        font-weight: bold;

                        &:hover {
                            text-decoration: none;

                            background-color: $main-header;

                            cursor: pointer;
                        }
                    }

                    &:hover {
                        ul {
                            display: block;
                            position: absolute;

                            z-index: 999 !important;

                            li ul {
                                display: none;
                            }

                            li {
                                background-color: $main-header;
                                width: 170px;

                                border-top: 1px solid $white;
                                border-left: 1px solid $white;
                                border-right: 1px solid $white;

                                &:last-child {
                                    border-bottom: 1px solid $white;
                                }

                                a {
                                    display: flex;
                                    align-items: center;
                                    justify-content: center;

                                    line-height: 60px;

                                    color: $white;

                                    font-weight: bold;

                                    &:hover {
                                        text-decoration: none;

                                        background-color: $hover-list;
                                    }

                                }

                                &:hover {
                                    ul {
                                        display: block;
                                        position: absolute;
                                        left: 168px;
                                        top: 0;

                                        z-index: 999 !important;

                                        li {
                                            background-color: $hover-list;

                                            border-top: 1px solid $white;
                                            border-left: 1px solid $white;
                                            border-right: 1px solid $white;

                                            width: 170px;

                                            &:last-child {
                                                border-bottom: 1px solid $white;
                                            }

                                            a {
                                                display: flex;
                                                align-items: center;
                                                justify-content: center;

                                                width: 170px;

                                                line-height: 60px;

                                                color: $white;

                                                font-weight: bold;

                                                &:hover {
                                                    text-decoration: none;

                                                    background-color: $hover-list;
                                                }

                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    .show-menu-right {
        display: flex;
        align-items: center;
        justify-content: flex-end;

        .picker-month-year {
            margin: 0 10px;
        }

        .show-profile {
            width: 200px;
            padding: 0 10px;

            line-height: 60px;

            display: flex;
            align-items: center;
            justify-content: center;

            cursor: pointer;

            .icon-profile {
                i {
                    font-size: 30px;
                    color: $white;

                    display: flex;
                    align-items: center;
                    justify-content: center;

                    margin-right: 10px;
                }
            }

            .username {
                color: $white;
                font-weight: bold;

                white-space: nowrap;
                width: 200px;
                overflow: hidden;
                text-overflow: ellipsis;
            }

            .icon-dropdown {
                i {
                    font-size: 18px;
                    color: $white;

                    display: flex;
                    align-items: center;
                    justify-content: center;

                    margin-left: 10px;

                    width: 1rem;
                }
            }

            ul {
                list-style-type: none;
                display: none;

                z-index: 999 !important;

                li {
                    font-weight: bold;
                }
            }

            &:hover {
                ul {
                    display: block;
                    position: absolute;
                    top: 60px;

                    width: 200px;
                    background-color: $main-header;

                    li {
                        width: 200px;

                        color: $white;

                        display: flex;
                        align-items: center;
                        justify-content: center;

                        i {
                            margin-left: 10px;
                        }

                        span {
                            left: -100px;
                        }

                        &:hover {
                            background-color: $hover-list;
                        }
                    }
                }
            }
        }
    }
}
</style>
