<template>
  <Head title="Response Recorded" />
  <div class="min-h-screen bg-gradient-to-br from-slate-50 via-emerald-50/40 to-slate-50 dark:from-[#1a2420] dark:via-[#152B1C] dark:to-[#1a2420] py-16 px-4 font-['Inter'] flex items-center justify-center">
    <div class="max-w-md w-full bg-white dark:bg-[#2D3A31] rounded-3xl shadow-xl border border-slate-200/60 dark:border-[#3F4F43] p-8 text-center space-y-5">

      <div :class="iconClass" class="w-16 h-16 rounded-full mx-auto flex items-center justify-center">
        <Icon :icon="iconName" size="xl" />
      </div>

      <div class="space-y-2">
        <h1 class="text-xl font-black text-slate-900 dark:text-white">{{ title }}</h1>
        <p class="text-sm text-slate-500 dark:text-slate-400 leading-relaxed">
          You responded on <strong>{{ formatDate(action_at) }}</strong> for the contribution
          "<strong>{{ contribution }}</strong>" for <strong>{{ student }}</strong>.
        </p>
      </div>

      <div class="bg-[#f5f7f2] dark:bg-[#232D26] rounded-2xl p-4 border border-slate-200/80 dark:border-[#3F4F43] text-left space-y-2 text-xs">
        <div class="flex items-center justify-between">
          <span class="font-semibold text-slate-500">Amount</span>
          <span class="font-black text-slate-900 dark:text-white">₱{{ formatMoney(amount) }}</span>
        </div>
        <div class="flex items-center justify-between">
          <span class="font-semibold text-slate-500">Current status</span>
          <span :class="statusBadgeClass" class="text-[10px] font-black uppercase px-2 py-0.5 rounded-full border">
            {{ statusLabel }}
          </span>
        </div>
      </div>

      <div v-if="decline_reason" class="bg-rose-50 dark:bg-rose-950/30 border border-rose-200/80 dark:border-rose-900/40 rounded-xl p-3 text-left">
        <p class="text-[10px] font-black uppercase tracking-wider text-rose-700 dark:text-rose-300">Your reason</p>
        <p class="text-xs text-rose-900 dark:text-rose-200 mt-1">{{ decline_reason }}</p>
      </div>

      <p class="text-[11px] text-slate-400 dark:text-slate-500 leading-relaxed">
        If you need to change your response, contact {{ student }}'s teacher directly.
      </p>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'
import { Head } from '@inertiajs/vue3'
import Icon from '@/Components/Icon.vue'

const props = defineProps({
  action:         { type: String, default: null },
  action_at:      { type: String, default: null },
  student:        { type: String, default: 'Student' },
  contribution:   { type: String, default: 'Contribution' },
  amount:         { type: Number, default: 0 },
  status:         { type: String, default: 'notified' },
  decline_reason: { type: String, default: null },
})

const title = computed(() => ({
  pay_online: 'Payment in progress',
  pay_cash:   'Cash payment selected',
  decline:    'Request declined',
}[props.action] || 'Response recorded'))

const iconName = computed(() => ({
  pay_online: 'clock',
  pay_cash:   'banknotes',
  decline:    'x-circle',
}[props.action] || 'check-circle'))

const iconClass = computed(() => ({
  pay_online: 'bg-amber-100 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400',
  pay_cash:   'bg-emerald-100 dark:bg-emerald-950/60 text-[#004d08] dark:text-[#86EFAC]',
  decline:    'bg-rose-100 dark:bg-rose-950/60 text-rose-600 dark:text-rose-400',
}[props.action] || 'bg-slate-100 text-slate-500'))

const statusLabel = computed(() => ({
  pending:      'Pending',
  notified:     'Notified',
  paid:         'Paid',
  cash_pending: 'Cash Incoming',
  declined:     'Declined',
  overdue:      'Overdue',
  waived:       'Waived',
}[props.status] || props.status))

const statusBadgeClass = computed(() => ({
  paid:         'bg-emerald-100 dark:bg-emerald-950/60 text-[#004d08] dark:text-[#86EFAC] border-emerald-200 dark:border-emerald-900/40',
  cash_pending: 'bg-amber-100 dark:bg-amber-950/60 text-amber-800 dark:text-amber-300 border-amber-200 dark:border-amber-900/40',
  declined:     'bg-rose-100 dark:bg-rose-950/60 text-rose-700 dark:text-rose-300 border-rose-200 dark:border-rose-900/40',
  notified:     'bg-sky-100 dark:bg-sky-950/60 text-sky-700 dark:text-sky-300 border-sky-200 dark:border-sky-900/40',
}[props.status] || 'bg-slate-100 dark:bg-[#232D26] text-slate-600 dark:text-slate-300 border-slate-200 dark:border-[#3F4F43]'))

function formatMoney(v) { return Number(v || 0).toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) }
function formatDate(v) {
  if (!v) return '—'
  try { return new Date(v).toLocaleString('en-US', { month: 'short', day: 'numeric', year: 'numeric', hour: 'numeric', minute: '2-digit' }) }
  catch { return '—' }
}
</script>