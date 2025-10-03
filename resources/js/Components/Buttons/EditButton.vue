<script setup>
    import Edit from '@/Components/Icons/Edit.vue';

    defineProps({
        theme: {
            type: String,
            default: 'gray',
            validator: value =>
                ['primary', 'warning', 'success', 'error', 'info'].includes(
                    value
                ),
        },
        size: {
            type: String,
            default: 'lg',
            validator: value => ['sm', 'md', 'lg'].includes(value),
        },
        label: {
            type: String,
            default: 'Editar',
        },
    });

    defineEmits(['click']);

    const themeClasses = {
        primary: 'text-blue-500 hover:text-blue-600',
        warning: 'text-orange-500 hover:text-orange-600',
        success: 'text-green-500 hover:text-green-600',
        error: 'text-red-500 hover:text-red-600',
        info: 'text-cyan-500 hover:text-cyan-600',
        gray: 'text-gray-500 hover:text-gray-600',
    };
</script>

<template>
    <section :class="`tooltip tooltip-${theme}`" :data-tip="label">
        <button
            @click="$emit('click')"
            :class="[
                'btn btn-link p-1',
                `btn-${size}`,
                'hover:bg-gray-100',
                'rounded-md',
                'inline-flex',
                'items-center',
                'gap-x-4',
                'font-bold',
                'border',
                'border-transparent',
                themeClasses[theme],
                'focus:outline-none',
                'text-center',
            ]"
            type="button"
            :aria-label="label"
            :title="label"
        >
            <Edit />
        </button>
    </section>
</template>
