<script setup>
    import { watch, computed, ref } from 'vue';
    import { useForm } from '@inertiajs/vue3';
    import ClasicButton from '../Buttons/ClasicButton.vue';
    import InputError from '../Inputs/InputError.vue';
    import InputSelectClasic from '../Inputs/InputSelectClasic.vue';
    import LabelInput from '../Inputs/LabelInput.vue';
    import InputSearchList from '../Inputs/InputSearchList.vue';

    const props = defineProps({
        mode: {
            type: String,
            default: 'create',
        },
        data: {
            type: Object,
            default: () => ({}),
        },
        currencies: {
            type: Array,
            default: () => [],
        },
    });

    const emit = defineEmits(['submitted', 'cancelled']);

    const measureUnitDisplay = ref('');

    const form = useForm({
        name: props.data.name || '',
        description: props.data.description || '',
        measure_unit_id: props.data.measure_unit_id || '',
        stock: props.data.stock || '',
        sale_price: props.data.sale_price || '',
        purchase_price: props.data.purchase_price || '',
        currency_id: props.data.currency_id || '',
    });

    const submitForm = () => {
        if (props.mode === 'edit') {
            form.put(route('products.update', props.data.id), {
                onSuccess: () => emit('submitted'),
            });
        } else {
            form.post(route('products.store'), {
                onSuccess: () => emit('submitted'),
            });
        }
    };

    const cancel = () => {
        emit('cancelled');
    };

    watch(
        () => props.data,
        newData => {
            if (newData) {
                form.name = newData.name || '';
                form.description = newData.description || '';
                form.measure_unit_id = newData.measure_unit_id || '';
                form.stock = newData.stock || '';
                form.sale_price = newData.sale_price || '';
                form.purchase_price = newData.purchase_price || '';
                form.currency_id = newData.currency_id || '';
                if (newData.measure_unit) {
                    measureUnitDisplay.value = newData.measure_unit.name || '';
                }
            }
        },
        { deep: true }
    );

    const currencyOptions = computed(() => {
        return props.currencies.map(currency => ({
            id: currency.id,
            text: currency.name,
        }));
    });

    const measureUnitInitialValue = computed(() => {
        if (props.mode === 'edit' && props.data.measure_unit) {
            return props.data.measure_unit;
        }
        return {};
    });
</script>

<template>
    <form
        @submit.prevent="submitForm"
        class="bg-white p-6 rounded-lg shadow-md w-full mx-auto"
    >
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-gray-900">
                {{ mode === 'edit' ? 'Editar Producto' : 'Crear Producto' }}
            </h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div>
                <LabelInput
                    v-model="form.name"
                    label="Nombre"
                    type="text"
                    :required="true"
                    name="name"
                    theme="gray"
                    size="base"
                />
                <InputError :message="form.errors.name" />
            </div>

            <div>
                <InputSearchList
                    search-route="measure_units.search"
                    label="Unidad de Medida"
                    v-model="form.measure_unit_id"
                    :initial-value="measureUnitInitialValue"
                    size="base"
                    :clean-button="true"
                    placeholder="Buscar unidad de medida por nombre o código"
                    :display-config="{
                        mainField: 'name',
                        secondaryField: 'code',
                        uniqueId: 'id',
                    }"
                />
                <InputError :message="form.errors.measure_unit_id" />
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div>
                <LabelInput
                    v-model="form.stock"
                    label="Stock"
                    type="number"
                    name="stock"
                    theme="gray"
                    size="base"
                />
                <InputError :message="form.errors.stock" />
            </div>

            <div>
                <InputSelectClasic
                    v-model="form.currency_id"
                    label="Moneda"
                    :options="currencyOptions"
                    placeholder="Seleccione moneda"
                    theme="gray"
                    :CleanButton="true"
                />
                <InputError :message="form.errors.currency_id" />
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div>
                <LabelInput
                    v-model="form.sale_price"
                    label="Precio de Venta"
                    type="number"
                    step="0.01"
                    name="sale_price"
                    theme="gray"
                    size="base"
                />
                <InputError :message="form.errors.sale_price" />
            </div>

            <div>
                <LabelInput
                    v-model="form.purchase_price"
                    label="Precio de Compra"
                    type="number"
                    step="0.01"
                    name="purchase_price"
                    theme="gray"
                    size="base"
                />
                <InputError :message="form.errors.purchase_price" />
            </div>
        </div>

        <div class="mb-4">
            <LabelInput
                v-model="form.description"
                label="Descripción"
                type="textarea"
                name="description"
                theme="gray"
                size="base"
            />
            <InputError :message="form.errors.description" />
        </div>

        <div class="mt-6 flex justify-end space-x-4">
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
                          ? 'Actualizar'
                          : 'Crear'
                }}
            </ClasicButton>
        </div>
    </form>
</template>
