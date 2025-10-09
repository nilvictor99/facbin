<script setup>
    import AppLayout from '@/Layouts/AppLayout.vue';
    import Pagination from '@/Components/Sections/SectionPagination.vue';
    import InputListSearch from '@/Components/Inputs/InputListSearch.vue';
    import { ref } from 'vue';
    import { router } from '@inertiajs/vue3';
    import PerPageSelector from '@/Components/Utils/PerPageSelector.vue';
    import SectionDateRangeFilter from '@/Components/Sections/SectionDateRangeFilter.vue';
    import InputSelectClasic from '@/Components/Inputs/InputSelectClasic.vue';
    import ClasicButton from '@/Components/Buttons/ClasicButton.vue';
    import UserPlus from '@/Components/Icons/UserPlus.vue';
    import EditButton from '@/Components/Buttons/EditButton.vue';

    const props = defineProps({
        data: {
            type: Object,
            required: true,
        },
        search: {
            type: String,
            default: '',
        },
        perPage: {
            type: Number,
            default: 5,
        },
        userId: {
            type: [String, Number],
            default: null,
        },
        users: {
            type: Array,
            default: () => [],
        },
        dateRange: {
            type: Object,
            default: null,
        },
    });

    const search = ref(props.search);
    const perPage = ref(props.perPage);
    const userId = ref(props.userId);
    const dateRange = ref(props.dateRange);

    function handleSearch() {
        const params = {
            search: search.value,
            page: 1,
            per_page: perPage.value,
        };

        if (dateRange.value && dateRange.value.start && dateRange.value.end) {
            params.date_from = dateRange.value.start;
            params.date_to = dateRange.value.end;
        }

        if (userId.value) {
            params.user_id = userId.value;
        }

        router.get(route('users.list'), params, {
            preserveState: true,
            replace: true,
        });
    }

    const getFullName = user => {
        if (user.profile && user.profile.full_name) {
            return user.profile.full_name;
        }
        return user.name;
    };

    const getGenderText = gender => {
        if (!gender) return '';
        return gender === 'M' ? 'Masculino' : 'Femenino';
    };
</script>

<template>
    <AppLayout title="Users List">
        <section class="mx-auto px-4 sm:px-6 lg:px-8 py-2 w-full max-w-7xl">
            <div class="flex flex-col gap-4">
                <div
                    class="bg-white p-4 rounded-md grid grid-cols-1 md:grid-cols-2 gap-6 items-end"
                >
                    <SectionDateRangeFilter
                        v-model="dateRange"
                        label="Filtrar por fechas"
                        borderColor="border-orange-400"
                        focusColor="focus:border-orange-500 focus:ring-orange-200"
                        :bold="true"
                        :disabled="false"
                        :cleanButton="true"
                        @filter="handleSearch"
                    />
                    <InputSelectClasic
                        v-model="userId"
                        :options="
                            users.map(user => ({
                                id: user.id,
                                text: user.name,
                            }))
                        "
                        :initialValue="{
                            id: props.userId,
                        }"
                        :roles="['super usuario', 'super_admin']"
                        :permissions="[]"
                        label="Usuarios"
                        placeholder="Usuario"
                        :disabled="false"
                        theme="gray"
                        :bold="true"
                        :CleanButton="true"
                        @change="handleSearch"
                    />
                </div>

                <div class="bg-white shadow-lg rounded-xl p-6">
                    <div
                        class="flex flex-col sm:flex-row gap-4 mb-6 items-center"
                    >
                        <InputListSearch
                            v-model="search"
                            :cleanButton="true"
                            placeholder="Search by name or email"
                            @search="handleSearch"
                            class="flex-1"
                        />
                        <ClasicButton
                            :roles="['super usuario', 'super_admin', 'Staff']"
                            :permissions="[]"
                            @click="() => router.visit(route('users.create'))"
                            class="flex-1 sm:flex-none w-full sm:w-auto flex justify-center"
                        >
                            <div class="flex flex-row items-center">
                                <UserPlus class="w-5 h-5 mr-2" />
                                <span>Create User</span>
                            </div>
                        </ClasicButton>
                    </div>

                    <div class="overflow-x-auto">
                        <table
                            class="min-w-full divide-y divide-gray-200 bg-white rounded-lg shadow-sm"
                        >
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider"
                                    >
                                        Usuario
                                    </th>
                                    <th
                                        class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider"
                                    >
                                        Información
                                    </th>
                                    <th
                                        class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider"
                                    >
                                        Tipo
                                    </th>
                                    <th
                                        class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider"
                                    >
                                        Roles
                                    </th>
                                    <th
                                        class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider"
                                    >
                                        Acciones
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr
                                    v-for="user in data.data"
                                    :key="user.id"
                                    class="hover:bg-gray-50 transition-colors"
                                >
                                    <!-- Columna de Usuario con foto -->
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <div
                                                class="flex-shrink-0 h-12 w-12"
                                            >
                                                <img
                                                    :src="
                                                        user.profile_photo_url
                                                    "
                                                    :alt="user.name"
                                                    class="h-12 w-12 rounded-full object-cover border-2 border-gray-200"
                                                />
                                            </div>
                                            <div class="ml-4">
                                                <div
                                                    class="text-sm font-medium text-gray-900"
                                                >
                                                    {{ getFullName(user) }}
                                                </div>
                                                <div
                                                    class="text-sm text-gray-500"
                                                >
                                                    {{ user.email }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Columna de Información adicional -->
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900">
                                            <div v-if="user.profile">
                                                <div
                                                    class="flex items-center space-x-2"
                                                >
                                                    <span class="text-gray-600"
                                                        >Género:</span
                                                    >
                                                    <span>{{
                                                        getGenderText(
                                                            user.profile.gender
                                                        )
                                                    }}</span>
                                                </div>
                                                <div
                                                    v-if="
                                                        user.profile
                                                            .date_of_birth
                                                    "
                                                    class="flex items-center space-x-2 mt-1"
                                                >
                                                    <span class="text-gray-600"
                                                        >Nacimiento:</span
                                                    >
                                                    <span>{{
                                                        new Date(
                                                            user.profile.date_of_birth
                                                        ).toLocaleDateString()
                                                    }}</span>
                                                </div>
                                            </div>
                                            <div
                                                v-else
                                                class="text-gray-500 italic"
                                            >
                                                Sin perfil completado
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Columna de Tipo de Usuario -->
                                    <td class="px-6 py-4 text-center">
                                        <span
                                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold"
                                            :class="{
                                                'bg-blue-100 text-blue-800':
                                                    user.type_user === 'Simple',
                                                'bg-green-100 text-green-800':
                                                    user.type_user === 'Staff',
                                                'bg-purple-100 text-purple-800':
                                                    user.type_user === 'Admin',
                                            }"
                                        >
                                            {{ user.type_user }}
                                        </span>
                                    </td>

                                    <!-- Columna de Roles -->
                                    <td class="px-6 py-4 text-center">
                                        <div
                                            v-if="
                                                user.roles &&
                                                user.roles.length > 0
                                            "
                                        >
                                            <span
                                                v-for="role in user.roles"
                                                :key="role"
                                                class="inline-flex items-center px-2 py-1 mx-1 rounded-full text-xs font-semibold bg-indigo-100 text-indigo-800"
                                            >
                                                {{ role }}
                                            </span>
                                        </div>
                                        <span
                                            v-else
                                            class="text-gray-500 text-sm"
                                        >
                                            Sin roles
                                        </span>
                                    </td>

                                    <!-- Columna de Acciones -->
                                    <td class="px-6 py-4 text-center">
                                        <EditButton
                                            @click="
                                                () =>
                                                    router.visit(
                                                        route(
                                                            'users.edit',
                                                            user.id
                                                        )
                                                    )
                                            "
                                        />
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div
                        v-if="data.data.length > 4"
                        class="mt-6 flex flex-col sm:flex-row items-center justify-between gap-4"
                    >
                        <PerPageSelector
                            v-model="perPage"
                            :options="[5, 10, 25, 50, 100]"
                            @change="handleSearch"
                        />
                        <Pagination :pagination="data" />
                    </div>
                </div>
            </div>
        </section>
    </AppLayout>
</template>
