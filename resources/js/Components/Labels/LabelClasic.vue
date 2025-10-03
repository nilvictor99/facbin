<script setup>
    import { computed, ref } from 'vue';

    const props = defineProps({
        text: {
            type: String,
            default: '',
        },
        value: {
            type: String,
            default: '',
        },
        uppercase: {
            type: Boolean,
            default: false,
        },
        size: {
            type: String,
            default: 'md',
            validator: value => ['sm', 'md', 'lg'].includes(value),
        },
        variant: {
            type: String,
            default: 'default',
            validator: value =>
                [
                    'default',
                    'dark',
                    'light',
                    'primary',
                    'secondary',
                    'success',
                    'danger',
                    'warning',
                    'info',
                ].includes(value),
        },
        disabled: {
            type: Boolean,
            default: false,
        },
    });

    const labelRef = ref(null);

    const labelClasses = computed(() => {
        const baseClasses =
            'inline-block font-medium transition-colors duration-200';

        const sizeClasses = {
            sm: 'text-xs px-1.5 py-0.5',
            md: 'text-sm px-1 py-0',
            lg: 'text-base px-2 py-2',
        };

        const variantClasses = {
            default: 'text-gray-700 bg-transparent',
            dark: 'text-white bg-transparent',
            light: 'text-gray-900 bg-transparent',
            primary: 'text-white bg-transparent',
            secondary: 'text-white bg-transparent',
            success: 'text-white bg-transparent',
            danger: 'text-white bg-transparent',
            warning: 'text-gray-900 bg-transparent',
            info: 'text-white bg-transparent',
        };

        const stateClasses = {
            'cursor-not-allowed opacity-75': props.disabled,
            uppercase: props.uppercase,
        };

        return [
            baseClasses,
            sizeClasses[props.size],
            variantClasses[props.variant],
            stateClasses,
        ];
    });

    defineExpose({
        focus: () => labelRef.value?.focus(),
    });
</script>

<template>
    <label ref="labelRef" :class="labelClasses">
        <slot>{{ text || value }}</slot>
    </label>
</template>
