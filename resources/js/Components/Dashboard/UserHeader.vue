<script setup>
    defineProps({
        backgroundImage: {
            type: String,
            required: true,
        },
        profilePhotoUrl: {
            type: String,
            required: true,
        },
        name: {
            type: String,
            required: true,
        },
        email: {
            type: String,
            required: true,
        },
        roles: {
            type: Array,
            required: true,
        },
    });
</script>

<template>
    <div class="relative mb-20">
        <!-- Background Image -->
        <div class="relative h-44 overflow-hidden rounded-xl">
            <img
                :src="backgroundImage"
                alt="Background Profile"
                class="h-full w-full object-cover"
                style="filter: brightness(0.85) saturate(1.2)"
            />
            <div
                class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"
            ></div>
        </div>

        <!-- Profile Photo -->
        <div class="absolute left-1/2 -bottom-16 -translate-x-1/2">
            <div class="p-2 bg-white rounded-full shadow-xl">
                <img
                    :src="profilePhotoUrl"
                    class="w-32 h-32 rounded-full border-4 border-white"
                    alt="Profile Photo"
                />
            </div>
        </div>
    </div>
</template>
