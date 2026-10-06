<template>
  <Head title="Consent Already Recorded" />
  <div class="min-h-screen bg-gradient-to-br from-slate-50 to-emerald-50 dark:from-[#1a2420] dark:to-[#152B1C] py-16 px-4 flex items-center justify-center">
    <div class="max-w-md w-full bg-white dark:bg-[#2D3A31] rounded-3xl shadow-xl border border-slate-200/60 dark:border-[#3F4F43] p-8 text-center space-y-4">
      <div :class="action === 'approved'
        ? 'bg-emerald-100 dark:bg-emerald-950/60 text-[#005506] dark:text-[#86EFAC]'
        : 'bg-rose-100 dark:bg-rose-950/60 text-rose-600 dark:text-rose-400'"
        class="w-16 h-16 rounded-full mx-auto flex items-center justify-center">
        <Icon :icon="action === 'approved' ? 'check-circle' : 'x-circle'" size="xl" />
      </div>
      <h1 class="text-xl font-black text-slate-900 dark:text-white">
        {{ action === 'approved' ? 'Already Approved' : 'Already Declined' }}
      </h1>
      <p class="text-sm text-slate-500 dark:text-slate-400">
        You already responded to the contribution for <strong>{{ student }}</strong> —
        "{{ contribution }}" (₱{{ formatMoney(amount) }}) on {{ formatDate(action_at) }}.
      </p>
    </div>
  </div>
</template>

<script setup>
import { Head } from '@inertiajs/vue3'
import Icon from '@/Components/Icon.vue'

defineProps({
  action:       { type: String, default: 'approved' },
  action_at:    { type: String, default: null },
  student:      { type: String, default: 'Student' },
  contribution: { type: String, default: 'Contribution' },
  amount:       { type: Number, default: 0 },
})

function formatMoney(v) { return Number(v || 0).toLocaleString('en-PH', { minimumFractionDigits: 2 }) }
function formatDate(v) {
  if (!v) return '—'
  return new Date(v).toLocaleString('en-US', { month: 'short', day: 'numeric', year: 'numeric', hour: 'numeric', minute: '2-digit' })
}
</script>