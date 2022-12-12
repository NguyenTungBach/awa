<template>
    <div class="detail-form">
        <b-row>
            <b-col>
                <p class="text-left text-label">
                    {{ label }}
                </p>
            </b-col>
            <b-col>
                <p class="text-right">
                    {{ value ? value : '-' }}
                </p>
            </b-col>
        </b-row>
    </div>
</template>

<script>
export default {
	name: 'DetailFrom',
	props: {
		label: {
			type: String,
			default: 'Label',
		},

		value: {
			type: [String, Number, Array],
			default: '',
		},
	},
};
</script>

<style lang="scss" scoped>
    @import '@/scss/variables.scss';

    .detail-form {
        border-bottom: 1px solid $black;

        p {
            margin-bottom: 5px;
        }

        .text-label {
            font-weight: bold;
        }
    }
</style>
