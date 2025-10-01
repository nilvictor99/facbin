<script setup>
    import Form from '@/Components/Users/Form.vue';
    import AppLayout from '@/Layouts/AppLayout.vue';

    defineProps({
        identificationTypes: {
            type: Array,
            default: () => [],
        },
    });

    const handleCancelled = () => {
        window.history.back();
    };
</script>

<template>
    <AppLayout title="Crear Usuario">
        <section class="mx-auto px-4 sm:px-6 lg:px-8 py-4 w-full max-w-7xl">
            <div class="flex flex-col md:flex-row gap-4 mt-4">
                <Form
                    mode="create"
                    :identificationTypes="identificationTypes"
                    @cancelled="handleCancelled"
                />
            </div>
        </section>
    </AppLayout>
</template>
