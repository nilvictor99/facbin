<script setup>
    import InputSimple from '@/Components/Inputs/InputSimple.vue';
    import { ref } from 'vue';
    import { defineProps, defineEmits } from 'vue';
    import Eye from '../Icons/Eye.vue';
    import EyeClosed from '../Icons/EyeClosed.vue';
    import ClassicLabel from '../Labels/ClassicLabel.vue';

    defineProps({
        modelValue: {
            type: String,
            default: '',
        },
        id: {
            type: String,
            default: 'password',
        },
        required: {
            type: Boolean,
            default: false,
        },
        autocomplete: {
            type: String,
            default: 'current-password',
        },
        theme: {
            type: String,
            default: 'gray',
            validator: value =>
                ['gray', 'white', 'indigo', 'primary', 'black'].includes(value),
        },
        weight: {
            type: String,
            default: 'bold',
            validator: value =>
                ['normal', 'medium', 'semibold', 'bold'].includes(value),
        },
        size: {
            type: String,
            default: 'sm',
            validator: value => ['xs', 'sm', 'base', 'lg'].includes(value),
        },
        transform: {
            type: String,
            default: 'normal',
            validator: value =>
                ['normal', 'uppercase', 'lowercase', 'capitalize'].includes(
                    value
                ),
        },
        placeholder: {
            type: String,
            default: '',
        },
        label: {
            type: String,
            default: '',
        },
    });
    defineEmits(['update:modelValue']);
    const showPassword = ref(false);

    function togglePassword() {
        showPassword.value = !showPassword.value;
    }
</script>

<template>
    <ClassicLabel
        v-if="label || $slots.default"
        :value="label"
        :theme="theme"
        :weight="weight"
        :size="size"
        :transform="transform"
        :required="required"
    >
        <slot />
    </ClassicLabel>
    <div class="relative">
        <InputSimple
            :id="id"
            :type="showPassword ? 'text' : 'password'"
            :model-value="modelValue"
            @update:model-value="$emit('update:modelValue', $event)"
            :placeholder="placeholder"
            :theme="theme"
            :weight="weight"
            :size="size"
            :required="required"
        />
        <button
            type="button"
            class="absolute top-1/2 -translate-y-1/2 right-2 flex items-center justify-center w-8 h-8 text-gray-600 hover:text-gray-900 focus:outline-none"
            @click="togglePassword"
        >
            <EyeClosed v-if="!showPassword" />
            <Eye v-else />
        </button>
    </div>
</template>
