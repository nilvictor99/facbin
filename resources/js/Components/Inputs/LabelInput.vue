<script setup>
    import { onMounted, ref, computed } from 'vue';

    const props = defineProps({
        modelValue: String,
        disabled: {
            type: Boolean,
            default: false,
        },
        placeholder: String,
        type: {
            type: String,
            default: 'text',
        },
        name: String,
        label: String,
        size: {
            type: String,
            default: 'sm',
            validator: value => ['xs', 'sm', 'base', 'lg'].includes(value),
        },
        theme: {
            type: String,
            default: 'gray',
            validator: value =>
                ['dark', 'gray', 'white', 'indigo', 'danger'].includes(value),
        },
        required: Boolean,
        id: String,
        rounded: {
            type: String,
            default: 'md',
            validator: value =>
                ['none', 'sm', 'md', 'lg', 'full'].includes(value),
        },
    });

    defineEmits(['update:modelValue']);

    const input = ref(null);
    const uniqueId = ref(Math.random().toString(36).substring(2, 9));
    const inputId = computed(() => props.id || `input-${uniqueId.value}`);

    const inputClasses = computed(() => {
        const sizeClasses = {
            xs: 'px-2 py-1 text-xs',
            sm: 'px-3 py-2 text-sm',
            base: 'px-4 py-2 text-base',
            lg: 'px-4 py-3 text-lg',
        };

        const themeClasses = {
            dark: 'bg-gray-700 border-gray-600 text-white focus:border-gray-500 focus:ring-gray-500',
            gray: 'bg-gray-50 border-gray-300 text-gray-900 focus:border-gray-300 focus:ring-gray-600',
            white: 'bg-white border-gray-300 text-gray-900 focus:border-blue-500 focus:ring-blue-500',
            indigo: 'bg-indigo-50 border-indigo-300 text-indigo-900 focus:border-indigo-500 focus:ring-indigo-500',
            danger: 'bg-red-50 border-red-300 text-red-900 focus:border-red-500 focus:ring-red-500',
        };

        const roundedClasses = {
            none: 'rounded-none',
            sm: 'rounded-sm',
            md: 'rounded-md',
            lg: 'rounded-lg',
            full: 'rounded-full',
        };

        return [
            'block w-full border shadow-sm',
            'focus:outline-none focus:ring-2 focus:ring-opacity-50',
            'disabled:opacity-50 disabled:cursor-not-allowed',
            sizeClasses[props.size],
            themeClasses[props.theme],
            roundedClasses[props.rounded],
        ];
    });

    const labelClasses = computed(() => {
        const sizeClasses = {
            xs: 'text-xs',
            sm: 'text-sm',
            base: 'text-base',
            lg: 'text-lg',
        };

        return [
            'block font-medium text-gray-700 mb-1',
            sizeClasses[props.size],
        ];
    });

    onMounted(() => {
        if (props.autofocus) {
            input.value?.focus();
        }
    });

    defineExpose({ focus: () => input.value?.focus() });
</script>

<template>
    <div>
        <label v-if="label" :for="inputId" :class="labelClasses">
            {{ label }}
            <span v-if="required" class="text-red-500">*</span>
        </label>
        <input
            ref="input"
            :id="inputId"
            :type="type"
            :name="name"
            :value="modelValue"
            :disabled="disabled"
            :placeholder="placeholder"
            :required="required"
            :class="inputClasses"
            @input="$emit('update:modelValue', $event.target.value)"
        />
    </div>
</template>
