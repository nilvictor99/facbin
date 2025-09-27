<script setup>
    import { computed } from 'vue';

    const emit = defineEmits(['update:checked']);

    const props = defineProps({
        checked: {
            type: [Array, Boolean],
            default: false,
        },
        value: {
            type: String,
            default: null,
        },
        theme: {
            type: String,
            default: 'gray',
            validator: value =>
                [
                    'gray',
                    'white',
                    'indigo',
                    'primary',
                    'black',
                    'danger',
                ].includes(value),
        },
        size: {
            type: String,
            default: 'sm',
            validator: value => ['xs', 'sm', 'base', 'lg'].includes(value),
        },
    });

    const proxyChecked = computed({
        get() {
            return props.checked;
        },
        set(val) {
            emit('update:checked', val);
        },
    });

    const themeClasses = computed(() => {
        switch (props.theme) {
            case 'black':
                return 'border-gray-800 text-gray-900 focus:ring-gray-900';
            case 'gray':
                return 'border-gray-300 text-gray-600 focus:ring-gray-400';
            case 'white':
                return 'border-white text-gray-800 focus:ring-gray-200';
            case 'indigo':
                return 'border-indigo-500 text-indigo-600 focus:ring-indigo-500';
            case 'danger':
                return 'border-red-500 text-red-600 focus:ring-red-500';
            default:
                return 'border-indigo-500 text-indigo-600 focus:ring-indigo-500';
        }
    });

    const sizeClasses = computed(() => {
        switch (props.size) {
            case 'xs':
                return 'h-3 w-3';
            case 'sm':
                return 'h-4 w-4';
            case 'base':
                return 'h-5 w-5';
            case 'lg':
                return 'h-6 w-6';
            default:
                return 'h-4 w-4';
        }
    });
</script>

<template>
    <input
        v-model="proxyChecked"
        type="checkbox"
        :value="value"
        :class="[
            'rounded shadow-sm transition-all duration-300 ease-in-out',
            themeClasses,
            sizeClasses,
        ]"
    />
</template>
