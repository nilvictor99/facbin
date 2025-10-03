<script setup>
    import Form from '@/Components/Inventories/Form.vue';
    import AppLayout from '@/Layouts/AppLayout.vue';

    defineProps({
        data: {
            type: Array,
            default: () => [],
        },
    });

    const handleCancelled = () => {
        window.history.back();
    };
</script>

<template>
    <AppLayout title="Crear Inventario">
        <section class="mx-auto px-2 sm:px-2 lg:px-0 py-4 w-full">
            <div class="flex flex-col md:flex-row">
                <Form mode="create" :data="data" @cancelled="handleCancelled" />
            </div>
        </section>
    </AppLayout>
</template>
