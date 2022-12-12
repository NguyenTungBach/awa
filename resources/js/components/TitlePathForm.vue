<template>
    <div :class="addClass">
        <div class="title-path-form">
            <div class="show-node">
                <div class="node" />
            </div>
            <div class="show-title">
                <span>
                    <slot>Title Path Form</slot>
                </span>
            </div>
        </div>

        <LineGray />
    </div>
</template>

<script>
import LineGray from '@/components/LineGray';

export default {
	name: 'TitlePathForm',
	components: {
		LineGray,
	},

	props: {
		addClass: {
			type: [String, Array, Object],
			default: '',
		},
	},
};
</script>

<style lang="scss" scoped>
    @import '@/scss/variables';

    .title-path-form {
        display: flex;

        line-height: 30px;

        .show-node {
            display: flex;
            align-items: center;
            justify-content: center;

            margin: 0 10px;

            .node {
                width: 15px;
                height: 15px;
                background-color: $black;
            }
        }

        .show-title {
            span {
                font-size: 20px;
                font-weight: bold;
            }
        }
    }
</style>
