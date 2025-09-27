<script setup>
    import { computed } from 'vue';
    import AppLayout from '@/Layouts/AppLayout.vue';
    import UserHeader from '@/Components/Dashboard/UserHeader.vue';
    import ModuleCard from '@/Components/Dashboard/ModuleCard.vue';

    const props = defineProps({
        auth: Object,
    });

    const { roles, name, email, profile_photo_url } = props.auth.user;

    const optionsAccess = [
        {
            name: 'Sistema de Ventas',
            route: 'waiter',
            background:
                'https://i.pinimg.com/736x/e6/08/21/e60821b561cd75d26b9172ed17953d3b.jpg',
            roles: ['Mozo', 'super usuario', 'Administrador'],
        },
        {
            name: 'Sistema de Caja',
            route: 'cashier',
            background:
                'https://i.pinimg.com/1200x/c6/e7/c2/c6e7c290e1b3b99323b4367e26002008.jpg',
            roles: ['Cajero', 'super usuario', 'Administrador'],
        },
    ];

    const filteredOptions = computed(() => {
        return optionsAccess.filter(option => isAccess(option.roles));
    });

    const isAccess = rolesUnit => {
        return roles.some(role => rolesUnit.includes(role));
    };
</script>

<template>
    <AppLayout title="Dashboard">
        <div class="h-full bg-stone-50">
            <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 h-full">
                <!-- User Header Section -->
                <UserHeader
                    background-image="/System/samples/fondo-login.webp"
                    :profile-photo-url="profile_photo_url"
                    :name="name"
                    :email="email"
                    :roles="roles"
                />

                <!-- Modules Grid -->
                <div class="mt-10">
                    <div class="grid grid-cols-1 gap-8 sm:grid-cols-2">
                        <ModuleCard
                            v-for="option in filteredOptions"
                            :key="option.name"
                            :module="option"
                        />
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
