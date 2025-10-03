<script setup>
    import Form from '@/Components/Products/Form.vue';
    import AppLayout from '@/Layouts/AppLayout.vue';

    defineProps({
        data: Object,
        currencies: Array,
    });

    const handleCancelled = () => {
        window.history.back();
    };
</script>

<template>
    <AppLayout title="Editar Productos">
        <section class="mx-auto px-4 sm:px-6 lg:px-8 py-4 w-full max-w-7xl">
            <div class="flex flex-col md:flex-row gap-4 mt-4">
                <Form
                    mode="edit"
                    :data="data"
                    :currencies="currencies"
                    @cancelled="handleCancelled"
                />
            </div>
        </section>
    </AppLayout>
</template>
