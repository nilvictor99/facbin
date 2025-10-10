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
    import ButtonDelete from '@/Components/Buttons/ButtonDelete.vue';
    import ModalDelete from '@/Components/Modals/ModalDelete.vue';

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
        customerId: {
            type: [String, Number],
            default: null,
        },
        customers: {
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
    const customerId = ref(props.customerId);
    const dateRange = ref(props.dateRange);
    const showDeleteModal = ref(false);
    const customerToDelete = ref(null);
    const isDeleting = ref(false);

    function handleSearch(val) {
        if (typeof val === 'object' && val !== null) {
            dateRange.value = val;
        }

        if (typeof val === 'string') {
            search.value = val;
        }

        router.get(
            route('customers.list'),
            {
                search: search.value,
                per_page: perPage.value,
                page: 1,
                customer_id: customerId.value,
                start: dateRange.value?.start,
                end: dateRange.value?.end,
            },
            {
                preserveState: true,
                replace: true,
            }
        );
    }

    const handleDelete = customer => {
        customerToDelete.value = customer.id;
        showDeleteModal.value = true;
    };

    const confirmDelete = () => {
        if (!customerToDelete.value) return;

        isDeleting.value = true;
        router.delete(route('customers.destroy', customerToDelete.value), {
            preserveScroll: true,
            onFinish: () => {
                isDeleting.value = false;
                showDeleteModal.value = false;
                customerToDelete.value = null;
            },
            onError: console.error,
        });
    };
</script>

<template>
    <AppLayout title="Customers List">
        <section class="mx-auto px-4 sm:px-6 lg:px-8 py-2 w-full max-w-7xl">
            <div class="flex flex-col gap-4">
                <div
                    class="bg-white p-4 rounded-md grid grid-cols-1 md:grid-cols-2 gap-6 items-end"
                >
                    <SectionDateRangeFilter
                        v-model="dateRange"
                        label="Filter by dates"
                        borderColor="border-orange-400"
                        focusColor="focus:border-orange-500 focus:ring-orange-200"
                        :bold="true"
                        :disabled="false"
                        :cleanButton="true"
                        @filter="handleSearch"
                    />
                    <InputSelectClasic
                        v-model="customerId"
                        :options="
                            customers.map(customer => ({
                                id: customer.id,
                                text: customer.profile.full_name,
                            }))
                        "
                        :initialValue="{
                            id: props.customerId,
                        }"
                        label="Customers"
                        placeholder="Select Customer"
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
                            placeholder="Search by name or document"
                            @search="handleSearch"
                            class="flex-1"
                        />

                        <ClasicButton
                            :roles="['super usuario', 'super_admin', 'Staff']"
                            :permissions="[]"
                            @click="
                                () => router.visit(route('customers.create'))
                            "
                            class="flex-1 sm:flex-none w-full sm:w-auto flex justify-center"
                        >
                            <div class="flex flex-row items-center">
                                <UserPlus class="w-5 h-5 mr-2" />
                                <span>Create Customer</span>
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
                                        Full Name
                                    </th>
                                    <th
                                        class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider"
                                    >
                                        Document
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
                                    v-for="customer in data.data"
                                    :key="customer.id"
                                    class="hover:bg-gray-50 transition-colors"
                                >
                                    <td class="px-6 py-4 text-sm text-gray-900">
                                        {{
                                            customer.profile.full_name || 'N/A'
                                        }}
                                    </td>
                                    <td
                                        class="px-6 py-4 text-center text-sm text-gray-900"
                                    >
                                        {{ customer.profile.document_number }}
                                    </td>
                                    <td class="px-6 py-4 text-center text-sm">
                                        <span
                                            :class="[
                                                'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium',
                                                customer.profile.active
                                                    ? 'bg-green-100 text-green-800'
                                                    : 'bg-red-100 text-red-800',
                                            ]"
                                        >
                                            {{
                                                customer.profile.active
                                                    ? 'Active'
                                                    : 'Inactive'
                                            }}
                                        </span>
                                    </td>
                                    <td
                                        class="flex px-6 py-4 text-center text-sm text-gray-900"
                                    >
                                        <EditButton
                                            @click="
                                                () =>
                                                    router.visit(
                                                        route(
                                                            'customers.edit',
                                                            customer.id
                                                        )
                                                    )
                                            "
                                        />
                                        <ButtonDelete
                                            :roles="['super usuario']"
                                            :permissions="[
                                                'delete_password::vault',
                                            ]"
                                            @click="handleDelete(customer)"
                                            :disabled="isDeleting"
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

        <ModalDelete
            :show="showDeleteModal"
            title="Eliminar Cliente"
            description="¿Está seguro que desea eliminar este cliente? Esta acción no se puede deshacer."
            itemType="Cliente"
            :itemId="customerToDelete"
            @close="showDeleteModal = false"
            @confirm="confirmDelete"
            :loading="isDeleting"
        />
    </AppLayout>
</template>
