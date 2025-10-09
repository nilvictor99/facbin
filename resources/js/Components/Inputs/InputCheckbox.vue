<script setup>
    import { computed, ref } from 'vue';
    import LabelClasic from '../Labels/LabelClasic.vue';

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
                    'blue',
                    'green',
                    'yellow',
                    'red',
                ].includes(value),
        },
        size: {
            type: String,
            default: 'sm',
            validator: value =>
                ['xs', 'sm', 'base', 'lg', 'xl'].includes(value),
        },
        borderShape: {
            type: String,
            default: 'rounded',
            validator: value =>
                ['none', 'partial', 'rounded', 'full-rounded'].includes(value),
        },
        multiple: {
            type: Boolean,
            default: false,
        },
        label: {
            type: String,
            default: '',
        },
        labelTheme: {
            type: String,
            default: 'gray',
            validator: value =>
                ['gray', 'white', 'indigo', 'primary', 'black'].includes(value),
        },
        labelWeight: {
            type: String,
            default: 'medium',
            validator: value =>
                ['normal', 'medium', 'semibold', 'bold'].includes(value),
        },
        labelSize: {
            type: String,
            default: 'sm',
            validator: value => ['xs', 'sm', 'base', 'lg'].includes(value),
        },
        labelTransform: {
            type: String,
            default: 'normal',
            validator: value =>
                ['normal', 'uppercase', 'lowercase', 'capitalize'].includes(
                    value
                ),
        },
        required: {
            type: Boolean,
            default: false,
        },
        disabled: {
            type: Boolean,
            default: false,
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

    const checkboxClasses = computed(() => {
        const baseClasses = [
            'shadow-sm transition-all duration-300 ease-in-out',
            'focus:outline-none focus:ring-2 focus:ring-opacity-50',
            props.disabled ? 'opacity-50 cursor-not-allowed' : '',
        ];

        const sizeClasses = {
            xs: 'h-3 w-3',
            sm: 'h-4 w-4',
            base: 'h-5 w-5',
            lg: 'h-6 w-6',
            xl: 'h-8 w-8',
        };

        const themeClasses = {
            black: 'border-gray-800 text-gray-900 focus:ring-gray-900',
            gray: 'border-gray-300 text-gray-600 focus:ring-gray-400',
            white: 'border-white text-gray-800 focus:ring-gray-200',
            indigo: 'border-indigo-500 text-indigo-600 focus:ring-indigo-500',
            primary: 'border-blue-500 text-blue-600 focus:ring-blue-500',
            danger: 'border-red-500 text-red-600 focus:ring-red-500',
            blue: 'border-blue-500 text-blue-600 focus:ring-blue-500',
            green: 'border-green-500 text-green-600 focus:ring-green-500',
            yellow: 'border-yellow-500 text-yellow-600 focus:ring-yellow-500',
            red: 'border-red-500 text-red-600 focus:ring-red-500',
        };

        const borderShapeClasses = {
            none: 'border-0',
            partial: 'rounded-sm',
            rounded: 'rounded',
            'full-rounded': 'rounded-full',
        };

        return [
            ...baseClasses,
            sizeClasses[props.size] || sizeClasses.sm,
            themeClasses[props.theme] || themeClasses.gray,
            borderShapeClasses[props.borderShape] || borderShapeClasses.rounded,
        ];
    });

    const uniqueId = ref(Math.random().toString(36).substring(2, 9));
    const checkboxId = computed(() => props.id || `checkbox-${uniqueId.value}`);
</script>

<template>
    <div class="flex items-center">
        <input
            :id="checkboxId"
            v-model="proxyChecked"
            type="checkbox"
            :value="value"
            :class="checkboxClasses"
            :disabled="disabled"
            :required="required"
        />
        <LabelClasic
            v-if="label"
            :value="label"
            :theme="labelTheme"
            :weight="labelWeight"
            :size="labelSize"
            :transform="labelTransform"
            :required="required"
            class="ml-2 cursor-pointer"
            :for="checkboxId"
        />
    </div>
</template>
