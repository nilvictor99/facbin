<script setup>
    import AppLayout from '@/Layouts/AppLayout.vue';
    import Pagination from '@/Components/Sections/SectionPagination.vue';
    import InputListSearch from '@/Components/Inputs/InputListSearch.vue';
    import { ref } from 'vue';
    import { router } from '@inertiajs/vue3';
    import PerPageSelector from '@/Components/Utils/PerPageSelector.vue';
    import SectionDateRangeFilter from '@/Components/Sections/SectionDateRangeFilter.vue';
    import ClasicButton from '@/Components/Buttons/ClasicButton.vue';
    import Box from '@/Components/Icons/Box.vue';
    import EditButton from '@/Components/Buttons/EditButton.vue';
    import CustomTooltip from '@/Components/Utils/CustomTooltip.vue';

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
        const params = {
            search: search.value,
            page: 1,
            per_page: perPage.value,
        };

        if (dateRange.value && dateRange.value.start && dateRange.value.end) {
            params.date_from = dateRange.value.start;
            params.date_to = dateRange.value.end;
        }

        router.get(
            route('products.list'),
            {
                search: search.value,
                start: dateRange.value.start,
                end: dateRange.value.end,
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
    <AppLayout title="Products List">
        <section class="mx-auto px-4 sm:px-6 lg:px-8 py-2 w-full max-w-7xl">
            <div class="flex flex-col gap-4">
                <div class="bg-white p-4 rounded-md">
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
                </div>

                <div class="bg-white shadow-lg rounded-xl p-6">
                    <div
                        class="flex flex-col sm:flex-row gap-4 mb-6 items-center"
                    >
                        <InputListSearch
                            v-model="search"
                            :cleanButton="true"
                            placeholder="Search by name or description"
                            @search="handleSearch"
                            class="flex-1"
                        />

                        <ClasicButton
                            :roles="['super usuario', 'super_admin', 'Staff']"
                            :permissions="[]"
                            @click="
                                () => router.visit(route('products.create'))
                            "
                            class="flex-1 sm:flex-none w-full sm:w-auto flex justify-center"
                        >
                            <div class="flex flex-row items-center">
                                <Box class="w-5 h-5 mr-2" />
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
                                        class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider cursor-pointer"
                                        @click="toggleSort('name')"
                                    >
                                        Name
                                        <span v-if="sortBy === 'name'">
                                            {{
                                                sortDirection === 'asc'
                                                    ? '↑'
                                                    : '↓'
                                            }}
                                        </span>
                                    </th>
                                    <th
                                        class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider cursor-pointer"
                                        @click="toggleSort('description')"
                                    >
                                        Description
                                        <span v-if="sortBy === 'description'">
                                            {{
                                                sortDirection === 'asc'
                                                    ? '↑'
                                                    : '↓'
                                            }}
                                        </span>
                                    </th>
                                    <th
                                        class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider cursor-pointer"
                                        @click="toggleSort('stock')"
                                    >
                                        Stock
                                        <span v-if="sortBy === 'stock'">
                                            {{
                                                sortDirection === 'asc'
                                                    ? '↑'
                                                    : '↓'
                                            }}
                                        </span>
                                    </th>
                                    <th
                                        class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider cursor-pointer"
                                        @click="toggleSort('sale_price')"
                                    >
                                        Sale Price
                                        <span v-if="sortBy === 'sale_price'">
                                            {{
                                                sortDirection === 'asc'
                                                    ? '↑'
                                                    : '↓'
                                            }}
                                        </span>
                                    </th>
                                    <th
                                        class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider cursor-pointer"
                                        @click="toggleSort('purchase_price')"
                                    >
                                        Purchase Price
                                        <span
                                            v-if="sortBy === 'purchase_price'"
                                        >
                                            {{
                                                sortDirection === 'asc'
                                                    ? '↑'
                                                    : '↓'
                                            }}
                                        </span>
                                    </th>
                                    <th
                                        class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider"
                                    >
                                        Active
                                    </th>
                                    <th
                                        class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider"
                                    >
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr
                                    v-for="product in data.data"
                                    :key="product.id"
                                    class="hover:bg-gray-50 transition-colors"
                                >
                                    <td
                                        class="px-6 py-4 text-sm font-medium text-gray-900"
                                    >
                                        {{ product.name }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900">
                                        <CustomTooltip
                                            :content="product.description"
                                        >
                                            <span>
                                                {{
                                                    product.description.length >
                                                    50
                                                        ? product.description.substring(
                                                              0,
                                                              50
                                                          ) + '...'
                                                        : product.description
                                                }}
                                            </span>
                                        </CustomTooltip>
                                    </td>
                                    <td
                                        class="px-6 py-4 text-center text-sm text-gray-900"
                                    >
                                        {{ product.stock }}
                                    </td>
                                    <td
                                        class="px-6 py-4 text-center text-sm text-gray-900"
                                    >
                                        {{ product.currency.symbol }}
                                        {{ product.sale_price }}
                                    </td>
                                    <td
                                        class="px-6 py-4 text-center text-sm text-gray-900"
                                    >
                                        {{ product.currency.symbol }}
                                        {{ product.purchase_price }}
                                    </td>
                                    <td class="px-6 py-4 text-center text-sm">
                                        <span
                                            :class="
                                                product.active
                                                    ? 'text-green-600 font-semibold'
                                                    : 'text-red-600 font-semibold'
                                            "
                                        >
                                            {{
                                                product.active
                                                    ? 'Active'
                                                    : 'Inactive'
                                            }}
                                        </span>
                                    </td>
                                    <td
                                        class="px-6 py-4 text-center text-sm text-gray-900"
                                    >
                                        <EditButton
                                            @click="
                                                () =>
                                                    router.visit(
                                                        route(
                                                            'products.edit',
                                                            product.id
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
