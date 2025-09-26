<script setup>
    import { Head, Link } from '@inertiajs/vue3';

    defineProps({
        canLogin: Boolean,
    });
</script>

<template>
    <Head title="Welcome" />
    <div class="relative min-h-screen overflow-hidden">
        <!-- Background Image -->
        <div class="absolute inset-0 z-0">
            <img
                src="/System/samples/fondo.webp"
                class="h-full w-full object-cover opacity-50"
                alt="Background"
            />
            <div
                class="absolute inset-0 bg-gradient-to-b from-black/50 to-black/30"
            ></div>
        </div>

        <header class="fixed top-0 left-0 right-0 z-20 w-full">
            <div class="mx-auto">
                <div
                    class="flex items-center justify-between p-6 backdrop-blur-md bg-black/20"
                >
                    <div class="flex items-center">
                        <!-- Logo section -->
                        <div class="text-white text-2xl font-bold">
                            <!-- You can replace this with your logo -->
                            <span
                                class="bg-gradient-to-r from-blue-500 to-purple-500 bg-clip-text text-transparent"
                            >
                                Logo
                            </span>
                        </div>
                    </div>

                    <div v-if="canLogin" class="flex items-center space-x-4">
                        <Link
                            v-if="$page.props.auth.user"
                            :href="route('dashboard')"
                            class="rounded-full bg-gradient-to-r from-blue-500 to-purple-500 px-6 py-2 text-white transition-all hover:shadow-lg hover:shadow-purple-500/20"
                        >
                            Dashboard
                        </Link>

                        <Link
                            v-else
                            :href="route('login')"
                            class="rounded-full border border-white/20 px-6 py-2 text-white transition-all hover:bg-white/10"
                        >
                            Log in
                        </Link>
                    </div>
                </div>
            </div>
        </header>
        <div class="relative z-10 min-h-screen">
            <!-- Navigation -->
            <!-- Main Content -->
            <main class="container mx-auto px-4 py-2">
                <div class="text-center">
                    <div class="relative mt-40 mb-20">
                        <!-- Círculo principal con gradiente gris -->
                        <div
                            class="relative mx-auto w-96 h-96 bg-gradient-to-br from-gray-700 via-gray-800 to-gray-900 rounded-full shadow-2xl overflow-hidden"
                        >
                            <!-- Corte inferior en tono más oscuro -->
                            <div
                                class="absolute -bottom-24 left-1/2 transform -translate-x-1/2 w-72 h-48 bg-gray-950 rounded-full"
                            ></div>

                            <!-- Contenido del círculo -->
                            <div
                                class="relative z-10 flex flex-col items-center justify-center h-full text-center p-8"
                            >
                                <div
                                    class="bg-gray-600/20 backdrop-blur-sm rounded-full p-6 mb-4"
                                >
                                    <!-- Icono SVG -->
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="h-12 w-12 text-gray-200"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"
                                        />
                                    </svg>
                                </div>
                                <h3
                                    class="text-2xl font-bold text-gray-100 mb-2"
                                >
                                    Innovation
                                </h3>
                                <p class="text-gray-300 text-sm">
                                    Built with modern technology
                                </p>
                            </div>

                            <!-- Efectos decorativos -->
                            <div
                                class="absolute top-8 left-8 w-4 h-4 bg-gray-400/30 rounded-full animate-pulse"
                            ></div>
                            <div
                                class="absolute top-16 right-12 w-2 h-2 bg-gray-300/40 rounded-full animate-pulse delay-300"
                            ></div>
                            <div
                                class="absolute bottom-20 left-12 w-3 h-3 bg-gray-500/20 rounded-full animate-pulse delay-700"
                            ></div>
                        </div>

                        <!-- Elementos decorativos con tonos grises -->
                        <div
                            class="absolute -top-4 -left-4 w-8 h-8 bg-gray-500/30 rounded-full blur-sm"
                        ></div>
                        <div
                            class="absolute -top-8 -right-8 w-12 h-12 bg-gray-600/20 rounded-full blur-md"
                        ></div>
                        <div
                            class="absolute -bottom-8 left-8 w-6 h-6 bg-gray-400/25 rounded-full blur-sm"
                        ></div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</template>

<style scoped>
    .container {
        max-width: 1280px;
    }

    /* Animación personalizada para el círculo */
    @keyframes float {
        0%,
        100% {
            transform: translateY(0px);
        }
        50% {
            transform: translateY(-10px);
        }
    }

    @keyframes glow {
        0%,
        100% {
            box-shadow: 0 0 20px rgba(59, 130, 246, 0.3);
        }
        50% {
            box-shadow: 0 0 40px rgba(147, 51, 234, 0.5);
        }
    }

    /* Aplicar animaciones */
    .relative .w-96 {
        animation:
            float 3s ease-in-out infinite,
            glow 2s ease-in-out infinite alternate;
    }
</style>
