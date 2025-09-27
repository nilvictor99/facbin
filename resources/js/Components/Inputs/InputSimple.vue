<script setup>
    import { computed } from 'vue';

    const props = defineProps({
        modelValue: {
            type: [String, Number],
            default: '',
        },
        type: {
            type: String,
            default: 'text',
        },
        size: {
            type: String,
            default: 'sm',
            validator: value => ['xs', 'sm', 'base', 'lg'].includes(value),
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
        weight: {
            type: String,
            default: 'normal',
            validator: value =>
                ['normal', 'medium', 'semibold', 'bold'].includes(value),
        },
        placeholder: {
            type: String,
            default: '',
        },
        disabled: {
            type: Boolean,
            default: false,
        },
        hasError: {
            type: Boolean,
            default: false,
        },
        required: {
            type: Boolean,
            default: false,
        },
    });

    const themeClasses = computed(() => {
        switch (props.theme) {
            case 'black':
                return 'border-gray-800 focus:border-gray-900 focus:ring-2 focus:ring-gray-900 bg-gray-900 text-white placeholder-gray-400';
            case 'gray':
                return 'border-gray-300 focus:border-gray-400 focus:ring-2 focus:ring-gray-400 bg-white text-gray-600 placeholder-gray-400';
            case 'white':
                return 'border-white focus:border-gray-200 focus:ring-2 focus:ring-gray-200 bg-white text-gray-800 placeholder-gray-400';
            case 'indigo':
                return 'border-indigo-500 focus:border-indigo-700 focus:ring-2 focus:ring-indigo-700 bg-indigo-50 text-indigo-900 placeholder-indigo-400';
            case 'danger':
                return 'border-red-500 focus:border-red-700 focus:ring-2 focus:ring-red-700 bg-red-50 text-red-900 placeholder-red-400';
            default:
                return 'border-gray-300 focus:border-gray-400 focus:ring-2 focus:ring-gray-400 bg-white text-gray-800 placeholder-gray-400';
        }
    });

    const sizeClasses = computed(() => {
        switch (props.size) {
            case 'xs':
                return 'px-2.5 py-1 text-xs';
            case 'sm':
                return 'px-3 py-1.5 text-sm';
            case 'base':
                return 'px-4 py-2 text-base';
            case 'lg':
                return 'px-5 py-2.5 text-lg';
            default:
                return 'px-3 py-1.5 text-sm';
        }
    });

    const weightClasses = computed(() => {
        switch (props.weight) {
            case 'normal':
                return 'font-normal';
            case 'medium':
                return 'font-medium';
            case 'semibold':
                return 'font-semibold';
            case 'bold':
                return 'font-bold';
            default:
                return 'font-normal';
        }
    });
</script>

<template>
    <div class="inline-block w-full">
        <input
            :type="type"
            :required="required"
            :value="modelValue"
            @input="$emit('update:modelValue', $event.target.value)"
            :placeholder="placeholder"
            :disabled="disabled"
            :class="[
                'w-full rounded-lg border transition-all duration-300 ease-in-out',
                themeClasses,
                sizeClasses,
                weightClasses,
                disabled && 'cursor-not-allowed opacity-75',
                hasError && 'border-red-500',
            ]"
        />
    </div>
</template>
