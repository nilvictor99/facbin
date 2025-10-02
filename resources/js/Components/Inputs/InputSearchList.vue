<script setup>
    import { ref, watch, onMounted, computed } from 'vue';
    import axios from 'axios';
    import ButtonClean from '@/Components/Buttons/ButtonClean.vue';
    import ClassicLabel from '@/Components/Labels/ClassicLabel.vue';

    const props = defineProps({
        searchRoute: {
            type: String,
            required: true,
        },
        placeholder: {
            type: String,
            default: 'Buscar...',
        },
        displayConfig: {
            type: Object,
            default: () => ({
                mainField: 'profile.full_name',
                secondaryField: 'profile.document_number',
                imageUrl: '/system/images/userimage.webp',
            }),
        },
        initialValue: {
            type: Object,
            default: () => ({}),
        },
        id: {
            type: String,
            default: '',
        },
        cleanButton: {
            type: Boolean,
            default: false,
        },
        forceValue: {
            type: String,
            default: null,
        },
        bold: { type: Boolean, default: false },
        label: { type: String, default: '' },
        theme: {
            type: String,
            default: 'gray',
            validator: value =>
                ['dark', 'gray', 'white', 'indigo', 'danger'].includes(value),
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
        DisabledInput: {
            type: Boolean,
            default: false,
        },
        autofocus: {
            type: Boolean,
            default: false,
        },
        name: {
            type: String,
            default: '',
        },
        autocomplete: {
            type: String,
            default: 'off',
        },
        rounded: {
            type: String,
            default: 'md',
            validator: value =>
                ['none', 'sm', 'md', 'lg', 'full'].includes(value),
        },
    });

    const searchTerm = ref('');
    const filteredResults = ref([]);
    const emit = defineEmits([
        'select',
        'search',
        'update:modelValue',
        'cleared',
    ]);

    const uniqueId = ref(Math.random().toString(36).substring(2, 9));
    const inputId = computed(() => props.id || `input-${uniqueId.value}`);
    const inputRef = ref(null);

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

        const weightClasses = {
            normal: 'font-normal',
            medium: 'font-medium',
            semibold: 'font-semibold',
            bold: 'font-bold',
        };

        const transformClasses = {
            normal: '',
            uppercase: 'uppercase',
            lowercase: 'lowercase',
            capitalize: 'capitalize',
        };

        return [
            'block w-full border shadow-sm',
            'focus:outline-none focus:ring-2 focus:ring-opacity-50',
            'disabled:opacity-50 disabled:cursor-not-allowed',
            sizeClasses[props.size],
            themeClasses[props.theme],
            roundedClasses[props.rounded],
            weightClasses[props.weight],
            transformClasses[props.transform],
            props.bold ? 'font-bold' : '',
        ];
    });

    watch(
        () => props.forceValue,
        newValue => {
            if (newValue !== null) {
                searchTerm.value = newValue;
                if (newValue === '0') {
                    emit('update:modelValue', '0');
                }
            }
        }
    );

    const getNestedValue = (obj, path) => {
        return path.split('.').reduce((acc, part) => acc?.[part], obj);
    };

    const setInitialValue = () => {
        if (props.initialValue && Object.keys(props.initialValue).length > 0) {
            const mainValue = getNestedValue(
                props.initialValue,
                props.displayConfig.mainField
            );

            if (mainValue) {
                searchTerm.value = mainValue;

                if (props.initialValue.id) {
                    emit('update:modelValue', props.initialValue.id);
                    emit('select', props.initialValue);
                }
            }
        }
    };

    onMounted(() => {
        setInitialValue();
        if (props.autofocus) {
            inputRef.value?.focus();
        }
    });

    watch(searchTerm, async newValue => {
        if (newValue.length > 0) {
            try {
                const searchUrl = props.searchRoute.includes('://')
                    ? props.searchRoute
                    : route(props.searchRoute);

                const response = await axios.get(searchUrl, {
                    params: { query: newValue },
                });
                filteredResults.value = response.data.data;
            } catch (error) {
                console.error('Error searching:', error);
                filteredResults.value = [];
            }
        } else {
            filteredResults.value = [];
        }
    });

    const selectItem = item => {
        emit('select', item);
        searchTerm.value =
            getNestedValue(item, props.displayConfig.mainField) ||
            item.name ||
            '';
        filteredResults.value = [];
    };

    const clearSearch = () => {
        searchTerm.value = '';
        filteredResults.value = [];
        emit('select', null);
        emit('update:modelValue', '');
        emit('cleared');
    };

    defineExpose({ focus: () => inputRef.value?.focus() });
</script>

<template>
    <div class="relative">
        <ClassicLabel
            v-if="label"
            :value="label"
            :theme="labelTheme"
            :weight="weight"
            :size="size"
            :transform="transform"
            :required="required"
            class="font-semibold mb-1"
            :for="inputId"
        />
        <div class="relative">
            <input
                ref="inputRef"
                :class="[inputClasses, cleanButton ? 'pr-10' : '']"
                :autocomplete="autocomplete"
                type="text"
                :id="inputId"
                v-model="searchTerm"
                :placeholder="placeholder"
                :disabled="DisabledInput"
                :autofocus="autofocus"
                :name="name"
                :required="required"
            />
            <ButtonClean
                v-if="cleanButton && searchTerm.length > 0"
                @click="clearSearch"
                class="absolute right-2 top-1/2 transform -translate-y-1/2 z-30"
                :isDeleting="true"
                buttonClass="bg-[#3c1415] w-8 h-8 flex items-center justify-center p-2 text-white rounded-md hover:bg-[#5e0f0b] focus:outline-none"
            />
        </div>

        <ul
            v-if="filteredResults.length > 0 && searchTerm.length > 0"
            class="absolute z-10 w-full mt-0 overflow-hidden bg-white border border-gray-200 rounded-md shadow-sm max-h-60 overflow-y-auto"
        >
            <li
                v-for="item in filteredResults"
                :key="item.cod_ubigeo"
                @click="selectItem(item)"
                class="px-2 py-1 text-gray-100 cursor-pointer hover:bg-indigo-500/30"
            >
                <div
                    class="flex items-center gap-2 px-2 py-1 text-gray-100 rounded-md cursor-pointer"
                >
                    <img
                        v-if="displayConfig.imageUrl"
                        class="object-contain w-10 h-10 rounded-full"
                        :src="displayConfig.imageUrl"
                    />
                    <div class="flex flex-col">
                        <span class="font-semibold text-gray-900">
                            {{ getNestedValue(item, displayConfig.mainField) }}
                        </span>
                        <span
                            v-if="displayConfig.secondaryField"
                            class="text-xs text-gray-600 font-medium"
                        >
                            {{
                                getNestedValue(
                                    item,
                                    displayConfig.secondaryField
                                )
                            }}
                        </span>
                    </div>
                </div>
            </li>
        </ul>
    </div>
</template>
