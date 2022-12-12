<template>
    <div
        v-click-outside-element="closeDropdown"
        class="select-multiple"
    >
        <b-form-input
            :class="{
                'select-multiple-input': true,
                'custom-select': isSelect
            }"
            readonly
            :value="text"
            @click="openDropdown()"
        />

        <div
            :class="{
                'list-select': true,
                'show': show
            }"
            tabindex="0"
        >
            <div class="item-select">
                <div
                    v-for="(option, idxOption) in options"
                    :key="`col-option-number-${idxOption + 1}`"
                    class="select-option"
                >
                    <ul class="list-option">
                        <li
                            v-for="(child, idxChild) in option"
                            :key="`option-number-${idxChild + 1}`"
                        >
                            <div
                                :key="reRender"
                                :class="{
                                    'item-option': true,
                                    'item-option-active': child.value === select[idxOption],
                                    'item-option-disabled': child.disabled,
                                }"
                                @click="onClickOption(child.disabled || false, child.value, idxOption)"
                            >
                                <span>{{ child.text }}</span>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Vue from 'vue';
import VueClickOutsideElement from 'vue-click-outside-element';

Vue.use(VueClickOutsideElement);

export default {
	name: 'SelectMultiple',
	props: {
		isSelect: {
			type: Boolean,
			default: true,
		},

		options: {
			type: Array,
			default: () => [],
		},

		value: {
			type: Array,
			default: () => [],
		},

		formatter: {
			type: Function,
			default: () => {
				return '';
			},
		},

		idxLoop: {
			type: Number,
			default: () => {
				return -1;
			},
		},

		keyData: {
			type: String,
			default: () => {
				return '';
			},
		},
	},

	data() {
		return {
			reRender: 0,
			show: false,
			select: [],
			text: '',
			zoneClick: false,
		};
	},

	computed: {
		getValue() {
			return this.value;
		},
	},

	watch: {
		getValue() {
			this.select = this.value;
			this.text = this.formatter(this.select);
			this.emitValue();
		},
	},

	created() {
		this.select = this.value;
		this.text = this.formatter(this.select);
		this.emitValue();
	},

	methods: {
		openDropdown() {
			this.show = true;
		},

		closeDropdown() {
			if (this.zoneClick === false) {
				this.show = false;
			}

			this.zoneClick = false;
		},

		onClickOption(disabled = false, value, index) {
			this.zoneClick = true;
			if (disabled === false) {
				if (index <= this.select.length && index >= 0) {
					if (this.select[index] === value) {
						this.select[index] = null;
					} else {
						this.select[index] = value;
					}

					this.text = this.formatter(this.select);
					this.emitValue(index, this.select);
					this.reRender++;
				} else {
					console.warn(`Can not set value with - [value: ${value}] [index: ${index}]`);
				}
			}
		},

		emitValue() {
			let dataEmit;

			if (this.idxLoop >= 0) {
				dataEmit = {
					value: this.select,
					index: this.idxLoop,
					key: this.keyData,
				};
			} else {
				dataEmit = this.select;
			}

			this.$emit('select', dataEmit);
		},
	},
};
</script>

<style lang="scss" scoped>
    @import '@/scss/variables';

    .select-multiple {
        .form-control {
            &:disabled {
                background-color: $white;
            }

            &:read-only {
                background-color: $white;
            }
        }

        .select-multiple-input {
            cursor: default;
        }

        .list-select {
            display: flex;
            flex-direction: row;

            display: none;

            border-left: 1px solid $black;
            border-right: 1px solid $black;
            border-bottom: 1px solid $black;

            background-color: $white;

            padding: 5px;

            cursor: default;

            .item-select {
                display: flex;
                flex-direction: row;

                .select-option {
                    list-style: none;
                    max-height: 100px;
                    overflow-x: hidden;
                    overflow-y: auto;

                    padding: 5px 10px 0 10px;

                    ul {
                        all: unset;
                    }

                    ul.list-option {
                        li {
                            min-width: 50px;

                            .item-option {
                                text-align: center;
                                vertical-align: middle;

                                padding: 10px 20px;

                                background-color: $white;

                                font-weight: bold;
                                color: $black;

                                cursor: pointer;

                                margin-bottom: 5px;
                            }

                            .item-option-active {
                                background-color: $main;
                                color: $white;
                            }

                            .item-option-disabled {
                                background-color: $gray;
                                color: $white;
                                opacity: 0.8;

                                cursor: not-allowed;
                            }
                        }
                    }
                }
            }
        }

        .show {
            display: block;
            position: absolute;
            z-index: 9999 !important;
        }
    }
</style>
