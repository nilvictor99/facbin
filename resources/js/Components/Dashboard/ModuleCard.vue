<script setup>
    defineProps({
        module: {
            type: Object,
            required: true,
        },
    });
</script>

<template>
    <a :href="module.route" class="block">
        <div
            class="group relative overflow-hidden rounded-xl bg-white shadow-lg transition-all duration-500 hover:shadow-2xl hover:-translate-y-1"
        >
            <div class="h-44 overflow-hidden">
                <img
                    :src="module.background"
                    :alt="module.name"
                    class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-110"
                    style="filter: brightness(0.95) saturate(1.1)"
                />
                <!-- Overlay -->
                <div
                    class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent opacity-75"
                ></div>
            </div>

            <div class="absolute bottom-0 w-full p-6">
                <h3 class="text-2xl font-bold text-white mb-2">
                    {{ module.name }}
                </h3>
                <div class="flex gap-2">
                    <span
                        v-for="role in module.roles.slice(0, 2)"
                        :key="role"
                        class="px-3 py-1 text-xs rounded-full bg-white/20 text-white backdrop-blur-sm"
                    >
                        {{ role }}
                    </span>
                </div>
            </div>
        </div>
    </a>
</template>
