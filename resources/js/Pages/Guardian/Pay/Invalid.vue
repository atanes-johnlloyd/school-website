<template>
  <Head title="Link Unavailable" />
  <div class="min-h-screen bg-gradient-to-br from-slate-50 to-rose-50/40 dark:from-[#1a2420] dark:to-[#2a1a1c] py-16 px-4 font-['Inter'] flex items-center justify-center">
    <div class="max-w-md w-full bg-white dark:bg-[#2D3A31] rounded-3xl shadow-xl border border-slate-200/60 dark:border-[#3F4F43] p-8 text-center space-y-4">
      <div class="w-16 h-16 rounded-full mx-auto bg-rose-100 dark:bg-rose-950/60 text-rose-600 dark:text-rose-400 flex items-center justify-center">
        <Icon icon="alert-triangle" size="xl" />
      </div>
      <h1 class="text-xl font-black text-slate-900 dark:text-white">Link Unavailable</h1>
      <p class="text-sm text-slate-500 dark:text-slate-400 leading-relaxed">
        {{ reason || 'This payment link is no longer valid.' }}
      </p>
      <p class="text-[11px] text-slate-400 dark:text-slate-500 pt-2">
        Please contact Salawag Senior High School if you believe this is a mistake.
      </p>
    </div>
  </div>
</template>

<script setup>
import { Head } from '@inertiajs/vue3'
import Icon from '@/Components/Icon.vue'
defineProps({ reason: { type: String, default: '' } })
</script>