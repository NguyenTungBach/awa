<template>
    <div>
        <input v-model="editedValue" type="text">
        <button @click="handSave">
            Save
        </button>
    </div>
</template>

<script>

export default {
	name: 'TestEmitNode',
	props: {
		testEmitValue: {
			type: String,
			required: true,
		},
	},

	data(){
		return {
			editedValue: this.testEmitValue,
		};
	},

	methods: {
		handSave(){
			this.$emit('update-value', this.editedValue);
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
