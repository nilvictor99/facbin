<script setup>
    import { onMounted, ref, computed } from 'vue';
    import ClassicLabel from '../Labels/ClassicLabel.vue';
    import InputSimple from '@/Components/Inputs/InputSimple.vue';

    const props = defineProps({
        modelValue: String,
        DisabledInput: {
            type: Boolean,
            required: false,
        },
        placeholder: {
            type: String,
            required: false,
        },
        type: {
            type: String,
            default: 'text',
        },
        name: {
            type: String,
            required: false,
        },
        autofocus: {
            type: Boolean,
            required: false,
        },
        theme: {
            type: String,
            default: 'gray',
            validator: value =>
                ['dark', 'gray', 'white', 'indigo', 'danger'].includes(value),
        },
        label: { type: String, default: '' },
        clearOnFocus: {
            type: Boolean,
            default: false,
        },
        defaultValue: {
            type: [String, Number],
            default: null,
        },
        calculateOnInput: {
            type: Boolean,
            default: false,
        },
        size: {
            type: String,
            default: 'sm',
            validator: value => ['xs', 'sm', 'base', 'lg'].includes(value),
        },
        weight: {
            type: String,
            default: 'semibold',
            validator: value =>
                ['normal', 'medium', 'semibold', 'bold'].includes(value),
        },
        transform: {
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
        autocomplete: {
            type: String,
            default: '',
        },
        id: {
            type: String,
            default: '',
        },
    });

    const emit = defineEmits(['update:modelValue', 'calculate']);

    const input = ref(null);
    const previousValue = ref(null);

    const labelTheme = computed(() => {
        const themeMap = {
            dark: 'gray',
            gray: 'gray',
            white: 'white',
            indigo: 'indigo',
            danger: 'primary',
        };
        return themeMap[props.theme] || 'gray';
    });

    const uniqueId = ref(Math.random().toString(36).substring(2, 9));
    const inputId = computed(() => props.id || `input-${uniqueId.value}`);

    const handleFocus = () => {
        if (props.clearOnFocus) {
            previousValue.value = props.modelValue;
            emit('update:modelValue', '');
        }
    };

    const handleBlur = () => {
        if (props.clearOnFocus && !props.modelValue) {
            emit(
                'update:modelValue',
                previousValue.value || props.defaultValue
            );
        }
    };

    const handleInput = value => {
        emit('update:modelValue', value);
        if (props.calculateOnInput) {
            emit('calculate', value);
        }
    };

    onMounted(() => {
        if (!props.modelValue && props.defaultValue !== null) {
            emit('update:modelValue', String(props.defaultValue));
        }
    });

    defineExpose({ focus: () => input.value?.focus() });
</script>

<template>
    <ClassicLabel
        v-if="label"
        :value="label"
        :theme="labelTheme"
        :weight="weight"
        :size="size"
        :transform="transform"
        :required="required"
        class="font-semibold mb-2"
        :for="inputId"
    />
    <InputSimple
        ref="input"
        :model-value="modelValue"
        :type="type"
        :size="size"
        :theme="theme"
        :weight="'bold'"
        :placeholder="placeholder"
        :disabled="DisabledInput"
        :has-error="false"
        :autofocus="autofocus"
        :name="name"
        :autocomplete="autocomplete"
        :required="required"
        :id="inputId"
        @update:model-value="handleInput"
        @focus="handleFocus"
        @blur="handleBlur"
    />
</template>
