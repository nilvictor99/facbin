<script setup>
    import { ref, watchEffect } from 'vue';
    import { usePage } from '@inertiajs/vue3';
    import CircleCheck from '../Icons/CircleCheck.vue';
    import TrianguleDanger from '../Icons/TrianguleDanger.vue';
    import XIcon from '../Icons/XIcon.vue';

    const page = usePage();
    const show = ref(true);
    const style = ref('success');
    const message = ref('');
    const duration = 5000;

    watchEffect(() => {
        style.value = page.props.jetstream.flash?.bannerStyle || 'success';
        message.value = page.props.jetstream.flash?.banner || '';
        show.value = !!message.value;
        if (message.value) {
            setTimeout(() => {
                show.value = false;
            }, duration);
        }
    });
</script>

<template>
    <Transition
        enter-active-class="transform transition-all duration-500 ease-out animate-bounce-gravity"
        enter-from-class="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
        enter-to-class="translate-y-0 opacity-100 sm:translate-x-0"
        leave-active-class="transition ease-in duration-300"
        leave-from-class="opacity-100"
        leave-to-class="opacity-0 -translate-y-4"
    >
        <div
            v-if="show && message"
            class="fixed top-4 right-4 z-50 max-w-sm w-full shadow-lg rounded-lg pointer-events-auto motion-safe:animate-bounce"
            :class="{
                'bg-gray-500': style == 'success',
                'bg-red-700': style == 'danger',
            }"
        >
            <div class="max-w-screen-xl mx-auto py-2 px-3 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between flex-wrap">
                    <div class="w-0 flex-1 flex items-center min-w-0">
                        <span
                            class="flex p-2 rounded-lg"
                            :class="{
                                'bg-gray-600': style == 'success',
                                'bg-red-600': style == 'danger',
                            }"
                        >
                            <CircleCheck :style="style" />
                            <TrianguleDanger :style="style" />
                        </span>
                        <p class="ms-3 font-medium text-sm text-white truncate">
                            {{ message }}
                        </p>
                    </div>
                    <div class="shrink-0 sm:ms-3">
                        <button
                            type="button"
                            class="-me-1 flex p-2 rounded-md focus:outline-none sm:-me-2 transition"
                            :class="{
                                'hover:bg-gray-600 focus:bg-gray-600':
                                    style == 'success',
                                'hover:bg-red-600 focus:bg-red-600':
                                    style == 'danger',
                            }"
                            aria-label="Dismiss"
                            @click.prevent="show = false"
                        >
                            <XIcon class="size-5 text-white" />
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </Transition>
</template>
