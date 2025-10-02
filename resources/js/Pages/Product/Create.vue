<script setup>
    import Form from '@/Components/Products/Form.vue';
    import AppLayout from '@/Layouts/AppLayout.vue';

    defineProps({
        currencies: Array,
        measureUnits: Array,
    });

    const handleCancelled = () => {
        window.history.back();
    };
</script>

<template>
    <AppLayout title="Crear Productos">
        <section class="mx-auto px-4 sm:px-6 lg:px-8 py-4 w-full max-w-7xl">
            <div class="flex flex-col md:flex-row gap-4 mt-4">
                <Form
                    mode="create"
                    :currencies="currencies"
                    @cancelled="handleCancelled"
                />
            </div>
        </section>
    </AppLayout>
</template>
