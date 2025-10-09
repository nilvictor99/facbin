<script setup>
    import { watch, computed } from 'vue';
    import { useForm } from '@inertiajs/vue3';
    import ClasicButton from '../Buttons/ClasicButton.vue';
    import LabelInput from '../Inputs/LabelInput.vue';
    import InputFocus from '../Inputs/InputFocus.vue';
    import InputCheckbox from '../Inputs/InputCheckbox.vue';
    import CustomTooltip from '../Utils/CustomTooltip.vue';

    const props = defineProps({
        mode: {
            type: String,
            default: 'create',
        },
        data: {
            type: Array,
            default: () => [],
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
        const details =
            props.inventoryData?.inventory_details || props.data || [];
        form.inventoryDetails = details.map(item => ({
            product_id: item.product_id || item.id,
            product_name: item.product?.name || item.name,
            starting_amount: parseFloat(item.starting_amount || item.stock),
            ending_amount: parseFloat(item.ending_amount || item.stock),
            difference: parseFloat(item.difference) || 0,
            observation: item.observation || '',
            selected: props.mode === 'edit' ? true : false,
        }));
    };

    initializeForm();

    const calculateDifference = detail => {
        detail.difference = detail.ending_amount - detail.starting_amount;
    };

    const selectAll = () => {
        form.inventoryDetails.forEach(detail => {
            detail.selected = true;
        });
    };

    const deselectAll = () => {
        form.inventoryDetails.forEach(detail => {
            detail.selected = false;
        });
    };

    const allSelected = computed(() => {
        return form.inventoryDetails.every(detail => detail.selected);
    });

    const submitForm = () => {
        const isEdit = props.mode === 'edit';
        form[isEdit ? 'put' : 'post'](
            route(
                isEdit ? 'inventory.update' : 'inventory.store',
                isEdit ? props.inventoryData.id : undefined
            ),
            {
                inventoryDetails: form.inventoryDetails,
            },
            {
                onSuccess: () => emit('submitted'),
            }
        );
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
                <ClasicButton
                    v-if="!allSelected"
                    type="button"
                    variant="gray"
                    @click="selectAll"
                >
                    Seleccionar Todo
                </ClasicButton>
                <ClasicButton
                    v-if="allSelected"
                    type="button"
                    variant="gray"
                    @click="deselectAll"
                >
                    Desmarcar Todo
                </ClasicButton>
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
                            : mode === 'edit'
                              ? 'Actualizar Inventario'
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
                <div class="flex items-center justify-between">
                    <InputCheckbox
                        v-model:checked="detail.selected"
                        theme="gray"
                        size="sm"
                    />
                    <CustomTooltip :content="detail.product_name">
                        <h3 class="text-lg font-semibold">
                            {{
                                detail.product_name.length > 20
                                    ? detail.product_name.substring(0, 20) +
                                      '...'
                                    : detail.product_name
                            }}
                        </h3>
                    </CustomTooltip>
                </div>
                <div
                    class="mt-4 space-y-4 mb-4 flex flex-col sm:flex-row sm:space-x-4 sm:space-y-0"
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
