<template>
  <Head title="Already Paid" />
  <div class="min-h-screen bg-gradient-to-br from-slate-50 via-emerald-50/40 to-slate-50 dark:from-[#1a2420] dark:via-[#152B1C] dark:to-[#1a2420] py-16 px-4 font-['Inter'] flex items-center justify-center">
    <div class="max-w-md w-full bg-white dark:bg-[#2D3A31] rounded-3xl shadow-xl border border-slate-200/60 dark:border-[#3F4F43] p-8 text-center space-y-5">

      <div class="w-16 h-16 rounded-full mx-auto bg-emerald-100 dark:bg-emerald-950/60 text-[#004d08] dark:text-[#86EFAC] flex items-center justify-center">
        <Icon icon="check-circle" size="xl" />
      </div>

      <div class="space-y-2">
        <h1 class="text-xl font-black text-slate-900 dark:text-white">Already Paid</h1>
        <p class="text-sm text-slate-500 dark:text-slate-400 leading-relaxed">
          The contribution "<strong>{{ contribution }}</strong>" for <strong>{{ student }}</strong> has already been paid.
        </p>
      </div>

      <div class="bg-[#f5f7f2] dark:bg-[#232D26] rounded-2xl p-4 border border-slate-200/80 dark:border-[#3F4F43] space-y-2 text-xs">
        <div class="flex items-center justify-between">
          <span class="font-semibold text-slate-500">Amount paid</span>
          <span class="font-black text-slate-900 dark:text-white">₱{{ formatMoney(amount) }}</span>
        </div>
        <div class="flex items-center justify-between">
          <span class="font-semibold text-slate-500">Paid on</span>
          <span class="font-bold text-emerald-700 dark:text-[#86EFAC]">{{ formatDate(paid_at) }}</span>
        </div>
      </div>

      <p class="text-[11px] text-slate-400 dark:text-slate-500">Thank you! No further action is needed.</p>
    </div>
  </div>
</template>

<script setup>
import { Head } from '@inertiajs/vue3'
import Icon from '@/Components/Icon.vue'

defineProps({
  student:      { type: String, default: 'Student' },
  contribution: { type: String, default: 'Contribution' },
  amount:       { type: Number, default: 0 },
  paid_at:      { type: String, default: null },
})

function formatMoney(v) { return Number(v || 0).toLocaleString('en-PH', { minimumFractionDigits: 2 }) }
function formatDate(v) {
  if (!v) return '—'
  return new Date(v).toLocaleString('en-US', { month: 'short', day: 'numeric', year: 'numeric', hour: 'numeric', minute: '2-digit' })
}
</script>