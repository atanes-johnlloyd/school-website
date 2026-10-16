<script setup>
import { computed, onMounted, onUnmounted, watch } from 'vue';

const props = defineProps({
    show: {
        type: Boolean,
        default: false,
    },
    maxWidth: {
        type: String,
        default: '2xl',
    },
    closeable: {
        type: Boolean,
        default: true,
    },
});

const emit = defineEmits(['close']);

watch(
    () => props.show,
    (val) => {
        document.body.style.overflow = val ? 'hidden' : '';
    }
);

onMounted(() => {
    document.addEventListener('keydown', closeOnEscape);
    if (props.show) {
        document.body.style.overflow = 'hidden';
    }
});

onUnmounted(() => {
    document.removeEventListener('keydown', closeOnEscape);
    document.body.style.overflow = '';
});

const close = () => {
    if (props.closeable) {
        emit('close');
    }
};

const closeOnEscape = (e) => {
    if (e.key === 'Escape' && props.show) {
        e.preventDefault();
        close();
    }
};

const maxWidthClass = computed(() => {
    return {
        sm: 'sm:max-w-sm',
        md: 'sm:max-w-md',
        lg: 'sm:max-w-lg',
        xl: 'sm:max-w-xl',
        '2xl': 'sm:max-w-2xl',
        '3xl': 'sm:max-w-3xl',
        '4xl': 'sm:max-w-4xl',
        '5xl': 'sm:max-w-5xl',
    }[props.maxWidth];
});
</script>

<template>
    <Teleport to="body">
        <Transition leave-active-class="duration-200">
            <div
                v-show="show"
                class="fixed inset-0 z-[100] overflow-y-auto p-4 sm:p-6 flex items-center justify-center"
                scroll-region
            >
                <!-- 
                  Static Blur Layer: 
                  Rendered once, not animated. This prevents the "delayed blur" 
                  effect caused by trying to fade `backdrop-filter` over time.
                  You can change `backdrop-blur-xs` to `backdrop-blur-sm` or 
                  `backdrop-blur-md` for a stronger effect.
                -->
                <div 
                    class="fixed inset-0 bg-black/50 backdrop-blur-xs transform-gpu" 
                    aria-hidden="true"
                ></div>

                <!-- Fading Dark Overlay (Click to close) -->
                <Transition
                    enter-active-class="ease-out duration-200"
                    enter-from-class="opacity-0"
                    enter-to-class="opacity-100"
                    leave-active-class="ease-in duration-150"
                    leave-from-class="opacity-100"
                    leave-to-class="opacity-0"
                >
                    <div
                        v-show="show"
                        class="fixed inset-0 bg-black/40"
                        @click="close"
                    ></div>
                </Transition>

                <!-- Modal Card -->
                <Transition
                    enter-active-class="ease-out duration-300"
                    enter-from-class="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    enter-to-class="opacity-100 translate-y-0 sm:scale-100"
                    leave-active-class="ease-in duration-200"
                    leave-from-class="opacity-100 translate-y-0 sm:scale-100"
                    leave-to-class="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                >
                    <div
                        v-show="show"
                        class="relative z-10 my-auto w-full transform overflow-hidden rounded-3xl shadow-2xl transition-all"
                        :class="maxWidthClass"
                    >
                        <slot v-if="show" />
                    </div>
                </Transition>
            </div>
        </Transition>
    </Teleport>
</template>