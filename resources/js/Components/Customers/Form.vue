<script setup>
    import { watch, computed, ref } from 'vue';
    import { useForm } from '@inertiajs/vue3';
    import ClasicButton from '../Buttons/ClasicButton.vue';
    import InputError from '../Inputs/InputError.vue';
    import InputDateClasic from '../Inputs/InputDateClasic.vue';
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
        identificationTypes: {
            type: Array,
            default: () => [],
        },
    });

    const emit = defineEmits(['submitted', 'cancelled']);

    const ubigeoDisplay = ref('');

    const form = useForm({
        identification_type_id: props.data.identification_type_id || '',
        document_number: props.data.document_number || '',
        name: props.data.name || '',
        paternal_surname: props.data.paternal_surname || '',
        maternal_surname: props.data.maternal_surname || '',
        gender: props.data.gender || '',
        date_of_birth: props.data.date_of_birth || '',
        ubigeo_cod: props.data.ubigeo_cod || '',
        contact_type: props.data.contact_type || '',
        contact_value: props.data.contact_value || '',
        address: props.data.address || '',
        reference: props.data.reference || '',
    });

    const submitForm = () => {
        if (props.mode === 'edit') {
            form.put(route('customers.update', props.data.id), {
                onSuccess: () => emit('submitted'),
            });
        } else {
            form.post(route('customers.store'), {
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
                form.identification_type_id =
                    newData.identification_type_id || '';
                form.document_number = newData.document_number || '';
                form.name = newData.name || '';
                form.paternal_surname = newData.paternal_surname || '';
                form.maternal_surname = newData.maternal_surname || '';
                form.gender = newData.gender || '';
                form.date_of_birth = newData.date_of_birth || '';
                form.ubigeo_cod = newData.ubigeo_cod || '';
                form.contact_type = newData.contact_type || '';
                form.contact_value = newData.contact_value || '';
                form.address = newData.address || '';
                form.reference = newData.reference || '';
                if (newData.ubigeo) {
                    ubigeoDisplay.value = newData.ubigeo.name || '';
                }
            }
        },
        { deep: true }
    );

    const IdentificationTypesoptions = computed(() => {
        return props.identificationTypes.map(type => ({
            id: type.id,
            text: type.name,
        }));
    });

    const ubigeoInitialValue = computed(() => {
        if (props.mode === 'edit' && props.data.ubigeo) {
            return props.data.ubigeo;
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
                {{ mode === 'edit' ? 'Editar Cliente' : 'Crear Cliente' }}
            </h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
            <div>
                <InputSelectClasic
                    v-model="form.identification_type_id"
                    label="Tipo de Identificación"
                    :options="IdentificationTypesoptions"
                    placeholder="Seleccione tipo de identificación"
                    theme="gray"
                    :CleanButton="true"
                />
                <InputError :message="form.errors.identification_type_id" />
            </div>

            <div>
                <LabelInput
                    v-model="form.document_number"
                    label="Número de Documento"
                    type="text"
                    :required="true"
                    name="document_number"
                    theme="gray"
                    size="base"
                />
                <InputError :message="form.errors.document_number" />
            </div>
            <div>
                <InputSelectClasic
                    v-model="form.gender"
                    label="Género"
                    :options="[
                        { id: 'M', text: 'Masculino' },
                        { id: 'F', text: 'Femenino' },
                    ]"
                    placeholder="Seleccione género"
                    theme="gray"
                    :CleanButton="true"
                />
                <InputError :message="form.errors.gender" />
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
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
                <LabelInput
                    v-model="form.paternal_surname"
                    label="Apellido Paterno"
                    type="text"
                    :required="true"
                    name="paternal_surname"
                    theme="gray"
                    size="base"
                />
                <InputError :message="form.errors.paternal_surname" />
            </div>

            <div>
                <LabelInput
                    v-model="form.maternal_surname"
                    label="Apellido Materno"
                    type="text"
                    required
                    name="maternal_surname"
                    theme="gray"
                    size="base"
                />
                <InputError :message="form.errors.maternal_surname" />
            </div>
            <div>
                <InputDateClasic
                    v-model="form.date_of_birth"
                    label="Fecha de Nacimiento"
                    theme="gray"
                    size="base"
                    weight="semibold"
                    name="date_of_birth"
                />
                <InputError :message="form.errors.date_of_birth" />
            </div>
        </div>
        <div class="mb-4">
            <h3 class="text-lg font-semibold text-gray-900 mb-2">
                Contacto y Dirección
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <InputSelectClasic
                        v-model="form.contact_type"
                        label="Tipo de Contacto"
                        :options="[
                            { id: 'mobil', text: 'Móvil' },
                            { id: 'email', text: 'Correo Electrónico' },
                        ]"
                        placeholder="Seleccione tipo de contacto"
                        theme="gray"
                        :CleanButton="true"
                    />
                    <InputError :message="form.errors.contact_type" />
                </div>

                <div>
                    <LabelInput
                        v-model="form.contact_value"
                        label="Contacto"
                        type="text"
                        name="contact_value"
                        theme="gray"
                        size="base"
                    />
                    <InputError :message="form.errors.contact_value" />
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                <div>
                    <InputSearchList
                        search-route="ubigeos.search"
                        label="Ubigeo"
                        v-model="form.ubigeo_cod"
                        :initial-value="ubigeoInitialValue"
                        size="base"
                        placeholder="Buscar ubigeo por nombre o código"
                        :display-config="{
                            mainField: 'full_ubigeo',
                            secondaryField: 'cod_ubigeo',
                            uniqueId: 'cod_ubigeo',
                        }"
                        :clean-button="true"
                    />
                    <InputError :message="form.errors.ubigeo_cod" />
                </div>

                <div>
                    <LabelInput
                        v-model="form.address"
                        label="Dirección"
                        type="text"
                        name="address"
                        theme="gray"
                        size="base"
                    />
                    <InputError :message="form.errors.address" />
                </div>
                <div>
                    <LabelInput
                        v-model="form.reference"
                        label="Referencia"
                        type="text"
                        name="reference"
                        theme="gray"
                        size="base"
                    />
                    <InputError :message="form.errors.reference" />
                </div>
            </div>
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
