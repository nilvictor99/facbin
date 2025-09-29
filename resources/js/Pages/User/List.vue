<script setup>
    import AppLayout from '@/Layouts/AppLayout.vue';
    import Pagination from '@/Components/Sections/SectionPagination.vue';
    import InputListSearch from '@/Components/Inputs/InputListSearch.vue';
    import { ref } from 'vue';
    import { router } from '@inertiajs/vue3';
    import PerPageSelector from '@/Components/Utils/PerPageSelector.vue';
    import SectionDateRangeFilter from '@/Components/Sections/SectionDateRangeFilter.vue';
    import InputSelectClasic from '@/Components/Inputs/InputSelectClasic.vue';

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
                                        Name
                                    </th>
                                    <th
                                        class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider"
                                    >
                                        Email
                                    </th>
                                    <th
                                        class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider"
                                    >
                                        Roles
                                    </th>
                                    <th
                                        class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider"
                                    >
                                        Created At
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr
                                    v-for="user in data.data"
                                    :key="user.id"
                                    class="hover:bg-gray-50 transition-colors"
                                >
                                    <td
                                        class="px-6 py-4 text-sm font-medium text-gray-900"
                                    >
                                        {{ user.name }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900">
                                        {{ user.email }}
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span
                                            v-if="user.roles"
                                            class="bg-blue-100 text-blue-800 inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold"
                                        >
                                            {{ user.roles.join(', ') }}
                                        </span>
                                    </td>
                                    <td
                                        class="px-6 py-4 text-center text-sm text-gray-900"
                                    >
                                        {{
                                            new Date(
                                                user.created_at
                                            ).toLocaleDateString()
                                        }}
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
