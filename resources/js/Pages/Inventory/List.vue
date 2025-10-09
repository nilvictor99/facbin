<script setup>
    import AppLayout from '@/Layouts/AppLayout.vue';
    import Pagination from '@/Components/Sections/SectionPagination.vue';
    import InputListSearch from '@/Components/Inputs/InputListSearch.vue';
    import ClasicButton from '@/Components/Buttons/ClasicButton.vue';
    import { ref } from 'vue';
    import { router } from '@inertiajs/vue3';
    import PerPageSelector from '@/Components/Utils/PerPageSelector.vue';
    import SectionDateRangeFilter from '@/Components/Sections/SectionDateRangeFilter.vue';
    import CirclePlus from '@/Components/Icons/CirclePlus.vue';
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
        dateRange: {
            type: Object,
            default: null,
        },
    });

    const search = ref(props.search);
    const perPage = ref(props.perPage);
    const dateRange = ref(props.dateRange);

    function handleSearch() {
        router.get(
            route('inventory.list'),
            {
                search: search.value,
                start: dateRange.value?.start,
                end: dateRange.value?.end,
                page: 1,
                per_page: perPage.value,
            },
            {
                preserveState: true,
                replace: true,
            }
        );
    }
</script>

<template>
    <AppLayout title="Inventory List">
        <section class="mx-auto px-4 sm:px-6 lg:px-8 py-2 w-full max-w-7xl">
            <div class="flex flex-col gap-4">
                <div class="bg-white p-4 rounded-md">
                    <SectionDateRangeFilter
                        v-model="dateRange"
                        label="Filter by date"
                        borderColor="border-orange-400"
                        focusColor="focus:border-orange-500 focus:ring-orange-200"
                        :bold="true"
                        :disabled="false"
                        :cleanButton="true"
                        @filter="handleSearch"
                    />
                </div>

                <div class="bg-white shadow-lg rounded-xl p-6">
                    <div
                        class="flex flex-col sm:flex-row gap-4 mb-6 items-center"
                    >
                        <InputListSearch
                            v-model="search"
                            placeholder="Buscar..."
                            @search="handleSearch"
                            class="flex-1"
                        />
                        <ClasicButton
                            variant="gray"
                            class="w-full sm:w-auto"
                            @click="() => router.get(route('inventory.create'))"
                        >
                            <div class="flex flex-row items-center">
                                <CirclePlus class="w-5 h-5 mr-2" />
                                <span>Create Product</span>
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
                                        User
                                    </th>
                                    <th
                                        class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider"
                                    >
                                        Details Count
                                    </th>
                                    <th
                                        class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider"
                                    >
                                        Created At
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
                                    v-for="inventory in data.data"
                                    :key="inventory.id"
                                    class="hover:bg-gray-50 transition-colors"
                                >
                                    <td
                                        class="px-6 py-4 text-sm font-medium text-gray-900"
                                    >
                                        {{ inventory.user.name }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900">
                                        {{ inventory.inventory_details.length }}
                                    </td>
                                    <td
                                        class="px-6 py-4 text-center text-sm text-gray-900"
                                    >
                                        {{
                                            new Date(
                                                inventory.created_at
                                            ).toLocaleDateString()
                                        }}
                                    </td>
                                    <td
                                        class="px-6 py-4 text-center text-sm text-gray-900"
                                    >
                                        <EditButton
                                            @click="
                                                () =>
                                                    router.visit(
                                                        route(
                                                            'inventory.edit',
                                                            inventory.id
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
