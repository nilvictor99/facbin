<script setup>
    import { watch, computed } from 'vue';
    import { useForm } from '@inertiajs/vue3';
    import ClasicButton from '../Buttons/ClasicButton.vue';
    import InputError from '../Inputs/InputError.vue';
    import InputDateClasic from '../Inputs/InputDateClasic.vue';
    import InputSelectClasic from '../Inputs/InputSelectClasic.vue';
    import LabelInput from '../Inputs/LabelInput.vue';

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

    const form = useForm({
        name: props.data.name || '',
        email: props.data.email || '',
        password: '',
        identification_type_id:
            props.data.profile?.identification_type_id || '',
        document_number: props.data.profile?.document_number || '',
        paternal_surname: props.data.profile?.paternal_surname || '',
        maternal_surname: props.data.profile?.maternal_surname || '',
        gender: props.data.profile?.gender || '',
        date_of_birth: props.data.profile?.date_of_birth || '',
    });

    const submitForm = () => {
        if (props.mode === 'edit') {
            form.put(route('users.update', props.data.id), {
                onSuccess: () => emit('submitted'),
            });
        } else {
            form.post(route('users.store'), {
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
                form.email = newData.email || '';
                if (newData.profile) {
                    form.identification_type_id =
                        newData.profile.identification_type_id || '';
                    form.document_number =
                        newData.profile.document_number || '';
                    form.paternal_surname =
                        newData.profile.paternal_surname || '';
                    form.maternal_surname =
                        newData.profile.maternal_surname || '';
                    form.gender = newData.profile.gender || '';
                    form.date_of_birth = newData.profile.date_of_birth || '';
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
</script>

<template>
    <form
        @submit.prevent="submitForm"
        class="bg-white p-6 rounded-lg shadow-md w-full mx-auto"
    >
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-gray-900">
                {{ mode === 'edit' ? 'Editar Usuario' : 'Crear Usuario' }}
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

        <!-- Additional Profile Fields -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
            <div>
                <LabelInput
                    v-model="form.name"
                    label="Nombre Completo"
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
                    :required="true"
                    name="maternal_surname"
                    theme="gray"
                    size="base"
                />
                <InputError :message="form.errors.maternal_surname" />
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
            <div>
                <InputDateClasic
                    v-model="form.date_of_birth"
                    label="Fecha de Nacimiento"
                    theme="gray"
                    size="base"
                    weight="semibold"
                    required
                    name="fecha"
                />
                <InputError :message="form.errors.date_of_birth" />
            </div>
            <div>
                <LabelInput
                    v-model="form.name"
                    label="Nombre de Usuario"
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
                    v-model="form.email"
                    label="Email"
                    type="email"
                    :required="true"
                    name="email"
                    theme="gray"
                    size="base"
                />
                <InputError :message="form.errors.email" />
            </div>
            <div v-if="mode === 'create'">
                <LabelInput
                    v-model="form.password"
                    label="Contraseña"
                    type="password"
                    :required="true"
                    name="password"
                    theme="gray"
                    size="base"
                />
                <InputError :message="form.errors.password" />
            </div>
        </div>

        <!-- Submit Buttons -->
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
