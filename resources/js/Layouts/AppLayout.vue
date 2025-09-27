<script setup>
    import { ref } from 'vue';
    import { Head, Link, router } from '@inertiajs/vue3';
    import ApplicationMark from '@/Components/Utils/ApplicationMark.vue';
    import Banner from '@/Components/Utils/Banner.vue';
    import Dropdown from '@/Components/Utils/Dropdown.vue';
    import DropdownLink from '@/Components/Utils/DropdownLink.vue';
    import NavLink from '@/Components/Utils/NavLink.vue';
    import ResponsiveNavLink from '@/Components/Utils/ResponsiveNavLink.vue';

    defineProps({
        title: String,
    });

    const showingNavigationDropdown = ref(false);

    const switchToTeam = team => {
        router.put(
            route('current-team.update'),
            {
                team_id: team.id,
            },
            {
                preserveState: false,
            }
        );
    };

    const logout = () => {
        router.post(route('logout'));
    };
</script>

<template>
    <div>
        <Head :title="title">
            <link
                rel="icon"
                type="image/x-icon"
                href="/System/favicon/favicon.ico"
            />
        </Head>

        <Banner />

        <div class="min-h-screen bg-gray-100 flex flex-col">
            <nav class="bg-white border-b border-gray-100 flex-none">
                <!-- Primary Navigation Menu -->
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <!-- Left side: Logo and Navigation -->
                        <div class="flex items-center flex-1">
                            <!-- Logo -->
                            <div class="flex-shrink-0">
                                <Link
                                    :href="route('dashboard')"
                                    class="flex items-center"
                                >
                                    <ApplicationMark class="h-9 w-auto" />
                                </Link>
                            </div>

                            <!-- Navigation Links -->
                            <div
                                class="hidden sm:flex sm:items-center sm:ml-8 space-x-8"
                            >
                                <NavLink
                                    :href="route('dashboard')"
                                    :active="route().current('dashboard')"
                                >
                                    Dashboard
                                </NavLink>
                            </div>
                        </div>

                        <div class="flex items-center">
                            <div class="ms-3 relative">
                                <!-- Teams Dropdown -->
                                <Dropdown
                                    v-if="$page.props.jetstream.hasTeamFeatures"
                                    align="right"
                                    width="60"
                                >
                                    <template #trigger>
                                        <span class="inline-flex rounded-md">
                                            <button
                                                type="button"
                                                class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none focus:bg-gray-50 active:bg-gray-50 transition ease-in-out duration-150"
                                            >
                                                {{
                                                    $page.props.auth.user
                                                        .current_team.name
                                                }}

                                                <svg
                                                    class="ms-2 -me-0.5 size-4"
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    fill="none"
                                                    viewBox="0 0 24 24"
                                                    stroke-width="1.5"
                                                    stroke="currentColor"
                                                >
                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        d="M8.25 15L12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9"
                                                    />
                                                </svg>
                                            </button>
                                        </span>
                                    </template>

                                    <template #content>
                                        <div class="w-60">
                                            <!-- Team Management -->
                                            <div
                                                class="block px-4 py-2 text-xs text-gray-400"
                                            >
                                                Manage Team
                                            </div>

                                            <!-- Team Settings -->
                                            <DropdownLink
                                                :href="
                                                    route(
                                                        'teams.show',
                                                        $page.props.auth.user
                                                            .current_team
                                                    )
                                                "
                                            >
                                                Team Settings
                                            </DropdownLink>

                                            <DropdownLink
                                                v-if="
                                                    $page.props.jetstream
                                                        .canCreateTeams
                                                "
                                                :href="route('teams.create')"
                                            >
                                                Create New Team
                                            </DropdownLink>

                                            <!-- Team Switcher -->
                                            <template
                                                v-if="
                                                    $page.props.auth.user
                                                        .all_teams.length > 1
                                                "
                                            >
                                                <div
                                                    class="border-t border-gray-200"
                                                />

                                                <div
                                                    class="block px-4 py-2 text-xs text-gray-400"
                                                >
                                                    Switch Teams
                                                </div>

                                                <template
                                                    v-for="team in $page.props
                                                        .auth.user.all_teams"
                                                    :key="team.id"
                                                >
                                                    <form
                                                        @submit.prevent="
                                                            switchToTeam(team)
                                                        "
                                                    >
                                                        <DropdownLink
                                                            as="button"
                                                        >
                                                            <div
                                                                class="flex items-center"
                                                            >
                                                                <svg
                                                                    v-if="
                                                                        team.id ==
                                                                        $page
                                                                            .props
                                                                            .auth
                                                                            .user
                                                                            .current_team_id
                                                                    "
                                                                    class="me-2 size-5 text-green-400"
                                                                    xmlns="http://www.w3.org/2000/svg"
                                                                    fill="none"
                                                                    viewBox="0 0 24 24"
                                                                    stroke-width="1.5"
                                                                    stroke="currentColor"
                                                                >
                                                                    <path
                                                                        stroke-linecap="round"
                                                                        stroke-linejoin="round"
                                                                        d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                                                                    />
                                                                </svg>

                                                                <div>
                                                                    {{
                                                                        team.name
                                                                    }}
                                                                </div>
                                                            </div>
                                                        </DropdownLink>
                                                    </form>
                                                </template>
                                            </template>
                                        </div>
                                    </template>
                                </Dropdown>
                            </div>

                            <!-- Settings Dropdown -->
                            <div class="ms-3 relative">
                                <Dropdown align="right" width="48">
                                    <template #trigger>
                                        <button
                                            v-if="
                                                $page.props.jetstream
                                                    .managesProfilePhotos
                                            "
                                            class="flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300 transition"
                                        >
                                            <img
                                                class="size-8 rounded-full object-cover"
                                                :src="
                                                    $page.props.auth.user
                                                        .profile_photo_url
                                                "
                                                :alt="
                                                    $page.props.auth.user.name
                                                "
                                            />
                                        </button>

                                        <span
                                            v-else
                                            class="inline-flex rounded-md"
                                        >
                                            <button
                                                type="button"
                                                class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none focus:bg-gray-50 active:bg-gray-50 transition ease-in-out duration-150"
                                            >
                                                {{ $page.props.auth.user.name }}

                                                <svg
                                                    class="ms-2 -me-0.5 size-4"
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    fill="none"
                                                    viewBox="0 0 24 24"
                                                    stroke-width="1.5"
                                                    stroke="currentColor"
                                                >
                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        d="M19.5 8.25l-7.5 7.5-7.5-7.5"
                                                    />
                                                </svg>
                                            </button>
                                        </span>
                                    </template>

                                    <template #content>
                                        <!-- Account Management -->
                                        <div
                                            class="block px-4 py-2 text-xs text-gray-400"
                                        >
                                            Manage Account
                                        </div>

                                        <DropdownLink
                                            :href="route('profile.show')"
                                        >
                                            Profile
                                        </DropdownLink>
                                        <DropdownLink href="/admin" as="a">
                                            Panel Administrativo
                                        </DropdownLink>
                                        <DropdownLink
                                            v-if="
                                                $page.props.jetstream
                                                    .hasApiFeatures
                                            "
                                            :href="route('api-tokens.index')"
                                        >
                                            API Tokens
                                        </DropdownLink>

                                        <div class="border-t border-gray-200" />

                                        <!-- Authentication -->
                                        <form @submit.prevent="logout">
                                            <DropdownLink as="button">
                                                Log Out
                                            </DropdownLink>
                                        </form>
                                    </template>
                                </Dropdown>
                            </div>

                            <!-- Hamburger -->
                            <div class="sm:hidden flex items-center -me-2">
                                <button
                                    class="inline-flex justify-center items-center hover:bg-gray-100 focus:bg-gray-100 p-2 rounded-md focus:outline-none text-gray-400 hover:text-gray-500 focus:text-gray-500 transition duration-150 ease-in-out"
                                    @click="
                                        showingNavigationDropdown =
                                            !showingNavigationDropdown
                                    "
                                >
                                    <svg
                                        class="size-6"
                                        stroke="currentColor"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            :class="{
                                                hidden: showingNavigationDropdown,
                                                'inline-flex':
                                                    !showingNavigationDropdown,
                                            }"
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M4 6h16M4 12h16M4 18h16"
                                        />
                                        <path
                                            :class="{
                                                hidden: !showingNavigationDropdown,
                                                'inline-flex':
                                                    showingNavigationDropdown,
                                            }"
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12"
                                        />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Responsive Navigation Menu -->
                <div
                    v-show="showingNavigationDropdown"
                    class="sm:hidden bg-white border-t border-gray-200"
                >
                    <div class="space-y-1 pt-2 pb-3">
                        <ResponsiveNavLink
                            :href="route('dashboard')"
                            :active="route().current('dashboard')"
                        >
                            Dashboard
                        </ResponsiveNavLink>
                    </div>
                </div>
            </nav>

            <!-- Page Heading -->
            <header v-if="$slots.header" class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    <slot name="header" />
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-auto">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    <slot />
                </div>
            </main>
        </div>
    </div>
</template>
