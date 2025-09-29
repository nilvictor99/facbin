<script setup>
    import NavLink from '@/Components/Utils/NavLink.vue';
    import { ref } from 'vue';

    const modules = ref([
        {
            name: 'Dashboard',
            route: 'dashboard',
            submodules: [],
        },
        {
            name: 'Usuarios',
            route: 'users.index',
            submodules: [],
        },
    ]);

    const expandedModules = ref({});
</script>

<template>
    <aside
        class="bg-gray-200 w-64 min-h-screen p-4 overflow-y-auto sm:w-full md:w-64"
    >
        <nav>
            <ul class="space-y-2">
                <li v-for="(module, index) in modules" :key="index">
                    <div
                        class="flex justify-center items-center border border-gray-300 rounded-md bg-white p-2 hover:bg-gray-100 cursor-pointer sm:p-3 md:p-2"
                        @click="
                            expandedModules[index] = !expandedModules[index]
                        "
                    >
                        <NavLink
                            :href="route(module.route)"
                            :active="route().current(module.route)"
                            class="block text-center sm:text-lg md:text-base"
                        >
                            {{ module.name }}
                        </NavLink>
                    </div>
                    <ul
                        v-if="
                            expandedModules[index] &&
                            module.submodules.length > 0
                        "
                        class="ml-4 space-y-1 mt-1 sm:ml-2 md:ml-4"
                    >
                        <li
                            v-for="(sub, subIndex) in module.submodules"
                            :key="subIndex"
                        >
                            <div
                                class="border border-gray-300 rounded-md bg-white p-2 hover:bg-gray-100 sm:p-3 md:p-2"
                            >
                                <NavLink
                                    :href="route(sub.route)"
                                    :active="route().current(sub.route)"
                                    class="block sm:text-lg md:text-base"
                                >
                                    {{ sub.name }}
                                </NavLink>
                            </div>
                        </li>
                    </ul>
                </li>
            </ul>
        </nav>
    </aside>
</template>
