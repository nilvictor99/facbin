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
    const progress = ref(100);
    const duration = 5000;

    const startProgress = () => {
        const startTime = Date.now();
        const updateProgress = () => {
            const elapsed = Date.now() - startTime;
            progress.value = 100 - (elapsed / duration) * 100;

            if (progress.value > 0 && show.value) {
                requestAnimationFrame(updateProgress);
            } else {
                show.value = false;
            }
        };

        requestAnimationFrame(updateProgress);
    };

    const close = () => {
        show.value = false;
    };

    watchEffect(() => {
        style.value = page.props.jetstream.flash?.bannerStyle || 'success';
        message.value = page.props.jetstream.flash?.banner || '';
        show.value = !!message.value;
        if (message.value) {
            progress.value = 100;
            startProgress();
        }
    });
</script>

<template>
    <Transition
        enter-active-class="transform transition-all duration-300 ease-out"
        enter-from-class="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
        enter-to-class="translate-y-0 opacity-100 sm:translate-x-0"
        leave-active-class="transition ease-in duration-300"
        leave-from-class="opacity-100"
        leave-to-class="opacity-0"
    >
        <div
            v-if="show && message"
            class="fixed top-4 right-4 z-50 max-w-sm w-full overflow-hidden rounded-lg shadow-lg"
        >
            <div
                class="relative"
                :class="{
                    'bg-gray-500': style == 'success',
                    'bg-red-700': style == 'danger',
                }"
            >
                <div
                    class="absolute bottom-0 right-0 h-0.5 transition-all duration-100 ease-out"
                    :class="{
                        'bg-gray-300': style == 'success',
                        'bg-red-300': style == 'danger',
                    }"
                    :style="{ width: `${progress}%` }"
                />

                <div class="py-2 px-3">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <span
                                class="flex p-1.5 rounded-lg"
                                :class="{
                                    'bg-gray-600': style == 'success',
                                    'bg-red-600': style == 'danger',
                                }"
                            >
                                <CircleCheck
                                    v-if="style === 'success'"
                                    :style="style"
                                    class="size-4 text-white"
                                />
                                <TrianguleDanger
                                    v-else
                                    :style="style"
                                    class="size-4 text-white"
                                />
                            </span>
                        </div>
                        <div class="ml-2 flex-1">
                            <p class="text-sm font-medium text-white truncate">
                                {{ message }}
                            </p>
                        </div>
                        <div class="ml-2 flex-shrink-0">
                            <button
                                type="button"
                                class="inline-flex rounded-md text-white focus:outline-none focus:ring-1"
                                :class="{
                                    'hover:bg-gray-600': style == 'success',
                                    'hover:bg-red-600': style == 'danger',
                                }"
                                @click="close"
                            >
                                <XIcon class="size-4" />
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </Transition>
</template>
