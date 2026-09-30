<template>
  <TransitionGroup 
    tag="div" 
    class="fixed bottom-5 right-5 z-50 flex flex-col gap-2 max-w-sm font-['Inter']"
    enter-active-class="transform transition duration-300 ease-out"
    enter-from-class="translate-y-2 opacity-0"
    enter-to-class="translate-y-0 opacity-100"
    leave-active-class="transition duration-200 ease-in"
    leave-from-class="opacity-100"
    leave-to-class="opacity-0"
  >
    <div 
      v-for="toast in toasts" 
      :key="toast.id"
      :class="[
        'p-4 rounded-2xl shadow-xl border flex items-center gap-3 text-xs',
        toast.type === 'success' 
          ? 'bg-[#004d08] text-white border-emerald-700' 
          : 'bg-red-900 text-white border-red-700'
      ]"
    >
      <!-- Success Icon -->
      <svg v-if="toast.type === 'success'" class="w-5 h-5 text-amber-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
      </svg>
      <!-- Error Icon -->
      <svg v-else class="w-5 h-5 text-red-300 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
      </svg>

      <span class="font-normal leading-snug">{{ toast.message }}</span>
    </div>
  </TransitionGroup>
</template>

<script setup>
import { ref, watch } from 'vue'
import { usePage } from '@inertiajs/vue3'

const toasts = ref([])
const page = usePage()

watch(() => page.props.flash, (flash) => {
  if (flash?.success) {
    addToast(flash.success, 'success')
  }
  if (flash?.error) {
    addToast(flash.error, 'error')
  }
}, { deep: true, immediate: true })

const addToast = (message, type = 'success') => {
  const id = Date.now()
  toasts.value.push({ id, message, type })
  setTimeout(() => {
    toasts.value = toasts.value.filter(t => t.id !== id)
  }, 4000)
}
</script>