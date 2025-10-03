<script setup>
    import { watch } from 'vue';
    import { useForm } from '@inertiajs/vue3';
    import ClasicButton from '../Buttons/ClasicButton.vue';
    import LabelInput from '../Inputs/LabelInput.vue';
    import InputFocus from '../Inputs/InputFocus.vue';

    const props = defineProps({
        mode: {
            type: String,
            default: 'create',
        },
        data: {
            type: Array,
            required: true,
        },
        inventoryData: {
            type: Object,
            default: () => ({}),
        },
    });

    const emit = defineEmits(['submitted', 'cancelled']);

    const form = useForm({
        inventoryDetails: [],
    });

    const initializeForm = () => {
        form.inventoryDetails = (
            (props.mode === 'edit' && props.inventoryData?.inventoryDetails) ||
            props.data
        ).map(item => ({
            product_id: item.product_id || item.id,
            product_name: item.product?.name || item.name,
            starting_amount: item.starting_amount || item.stock,
            ending_amount: item.ending_amount || item.stock,
            difference: item.difference || 0,
            observation: item.observation || '',
        }));
    };

    initializeForm();

    const calculateDifference = detail => {
        detail.difference = detail.starting_amount - detail.ending_amount;
    };

    const submitForm = () => {
        if (props.mode === 'edit') {
            form.put(route('inventory.update', props.inventoryData.id), {
                onSuccess: () => emit('submitted'),
            });
        } else {
            form.post(route('inventory.store'), {
                onSuccess: () => emit('submitted'),
            });
        }
    };

    const cancel = () => {
        emit('cancelled');
    };

    watch(
        () => [props.data, props.inventoryData, props.mode],
        () => {
            initializeForm();
        },
        { deep: true }
    );
</script>

<template>
    <form @submit.prevent="submitForm" class="w-full">
        <section
            class="fixed top-16 left-0 right-0 z-50 bg-transparent py-4 px-6"
        >
            <div class="max-w-7xl mx-auto flex justify-end space-x-4">
                <ClasicButton type="button" variant="gray" @click="cancel">
                    Cancelar
                </ClasicButton>

                <ClasicButton
                    type="submit"
                    variant="gray"
                    :loading="form.processing"
                    :disabled="form.processing"
                >
                    {{
                        form.processing
                            ? 'Guardando...'
                            : 'Registrar Inventario'
                    }}
                </ClasicButton>
            </div>
        </section>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-2">
            <div
                v-for="detail in form.inventoryDetails"
                :key="detail.product_id"
                class="bg-white p-4 rounded-lg shadow-md"
            >
                <h3 class="text-lg font-semibold mb-3">
                    {{ detail.product_name }}
                </h3>

                <div
                    class="space-y-4 mb-4 flex flex-col sm:flex-row sm:space-x-4 sm:space-y-0"
                >
                    <div>
                        <LabelInput
                            v-model="detail.starting_amount"
                            label="Inicial"
                            type="number"
                            disabled
                            theme="gray"
                        />
                    </div>
                    <div>
                        <LabelInput
                            v-model="detail.difference"
                            label="Diferencia"
                            type="number"
                            disabled
                            theme="gray"
                        />
                    </div>
                </div>

                <div class="space-y-2">
                    <div>
                        <InputFocus
                            v-model="detail.ending_amount"
                            label="Final"
                            type="number"
                            theme="gray"
                            :calculateOnInput="true"
                            :clearOnFocus="true"
                            @calculate="() => calculateDifference(detail)"
                        />
                    </div>

                    <div>
                        <LabelInput
                            v-model="detail.observation"
                            label="Observación"
                            type="text"
                            theme="gray"
                        />
                    </div>
                </div>
            </div>
        </div>
    </form>
</template>
