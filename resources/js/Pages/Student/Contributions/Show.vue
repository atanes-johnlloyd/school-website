<template>
  <Head :title="`${assignment.title} - Salawag LMS`" />

  <StudentLayout>
    <div class="space-y-6 pb-10 font-['Inter'] min-w-0 max-w-3xl mx-auto">

      <!-- HERO -->
      <div class="animate-fade-in-down relative overflow-hidden rounded-3xl shadow-lg border border-[#006907]/20 min-h-[180px] flex flex-col justify-center p-6 sm:p-8">
        <img src="https://images.unsplash.com/photo-1554224155-8d04cb21cd6c?q=80&w=1600&auto=format&fit=crop"
          alt="Contribution" class="absolute inset-0 w-full h-full object-cover z-0" />
        <div class="absolute inset-0 bg-[#004d05]/85 dark:bg-[#152B1C]/90 mix-blend-multiply z-0"></div>

        <div class="relative z-10 space-y-2">
          <Link :href="route('student.contributions.index')"
            class="inline-flex items-center gap-1.5 text-white/80 hover:text-white text-[11px] font-bold uppercase tracking-wider">
            <Icon icon="arrow-left" size="xs" />
            Back to Contributions
          </Link>
          <h1 class="font-['Anton'] text-2xl sm:text-4xl uppercase text-white leading-none">
            {{ assignment.title }}
          </h1>
          <p class="text-white/90 text-xs sm:text-sm">{{ assignment.purpose }}</p>
          <span :class="statusPillClass(assignment.status)"
            class="inline-block text-[10px] font-black uppercase tracking-wider px-2.5 py-1 rounded-full border mt-2">
            {{ statusLabel(assignment.status) }}
          </span>
        </div>
      </div>

      <!-- AMOUNT + META -->
      <div class="rounded-3xl bg-[#fbfdf9] dark:bg-[#2D3A31] border border-slate-200/80 dark:border-[#3F4F43] p-5 sm:p-6 space-y-4">
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
          <div>
            <span class="text-[10px] font-black uppercase tracking-wider text-slate-500">Amount</span>
            <p class="text-2xl font-black text-slate-900 dark:text-white">₱{{ formatMoney(assignment.amount_owed) }}</p>
            <span v-if="assignment.amount_type === 'open' && assignment.min_amount"
              class="text-[11px] text-slate-500">Minimum ₱{{ formatMoney(assignment.min_amount) }}</span>
          </div>
          <div v-if="assignment.deadline_at">
            <span class="text-[10px] font-black uppercase tracking-wider text-slate-500">Deadline</span>
            <p class="text-sm font-bold text-slate-900 dark:text-white">{{ formatDate(assignment.deadline_at) }}</p>
          </div>
          <div v-if="assignment.section">
            <span class="text-[10px] font-black uppercase tracking-wider text-slate-500">For</span>
            <p class="text-sm font-bold text-slate-900 dark:text-white">{{ assignment.section }}</p>
          </div>
        </div>

        <div v-if="assignment.description" class="pt-4 border-t border-slate-100 dark:border-[#3F4F43]">
          <p class="text-xs sm:text-sm text-slate-700 dark:text-slate-300 whitespace-pre-line leading-relaxed">
            {{ assignment.description }}
          </p>
        </div>

        <div v-if="assignment.guardian" class="pt-4 border-t border-slate-100 dark:border-[#3F4F43] space-y-1">
          <span class="text-[10px] font-black uppercase tracking-wider text-slate-500">Guardian Contact</span>
          <p class="text-sm font-bold text-slate-900 dark:text-white">{{ assignment.guardian.name }}</p>
          <p class="text-xs text-slate-500">
            {{ assignment.guardian.relationship }}
            <span v-if="assignment.guardian.email"> • {{ assignment.guardian.email }}</span>
            <span v-if="assignment.guardian.contact"> • {{ assignment.guardian.contact }}</span>
          </p>
        </div>
      </div>

      <!-- PAYMENTS -->
      <div v-if="assignment.payments?.length" class="rounded-3xl bg-[#fbfdf9] dark:bg-[#2D3A31] border border-slate-200/80 dark:border-[#3F4F43] p-5 sm:p-6 space-y-3">
        <h3 class="text-sm font-black text-slate-900 dark:text-white uppercase tracking-wider">Payment Records</h3>
        <div v-for="p in assignment.payments" :key="p.id"
          class="flex items-center justify-between gap-3 p-3 bg-[#F9F7F1] dark:bg-[#232D26] rounded-xl border border-slate-200/80 dark:border-[#3F4F43]">
          <div>
            <p class="font-mono text-[11px] text-slate-600 dark:text-slate-300">{{ p.reference_no }}</p>
            <p class="text-[10px] text-slate-500 capitalize">{{ p.payment_method }} • {{ p.status }}</p>
          </div>
          <div class="text-right">
            <p class="text-sm font-black text-slate-900 dark:text-white">₱{{ formatMoney(p.amount) }}</p>
            <p v-if="p.paid_at" class="text-[10px] text-emerald-700 dark:text-[#86EFAC]">{{ formatDate(p.paid_at) }}</p>
          </div>
        </div>
      </div>

      <!-- ACTIONS -->
      <div class="rounded-3xl bg-[#fbfdf9] dark:bg-[#2D3A31] border border-slate-200/80 dark:border-[#3F4F43] p-5 sm:p-6 space-y-2">
        <button v-if="assignment.can_request_consent"
          class="w-full bg-[#005506] dark:bg-[#86EFAC] hover:bg-[#004105] text-white dark:text-[#232D26] text-xs font-black py-3 rounded-xl">
          Request Guardian Approval
        </button>
        <button v-else-if="assignment.can_pay"
          class="w-full bg-[#F9C20C] hover:bg-amber-400 text-[#2C3E2D] text-xs font-black py-3 rounded-xl">
          Pay Now
        </button>
        <div v-else-if="assignment.status === 'paid'"
          class="w-full bg-emerald-50 dark:bg-emerald-950/30 border border-emerald-200/80 dark:border-emerald-900/40 text-[#005506] dark:text-[#86EFAC] text-xs font-black py-3 rounded-xl text-center">
          Paid on {{ formatDate(assignment.paid_at) }}
        </div>
      </div>

    </div>
  </StudentLayout>
</template>

<script setup>
import { Head, Link } from '@inertiajs/vue3'
import StudentLayout from '@/Layouts/StudentLayout.vue'
import Icon from '@/Components/Icon.vue'

const props = defineProps({
  assignment: { type: Object, required: true },
})

function statusLabel(s) {
  return {
    pending: 'Pending', awaiting_guardian: 'Awaiting Guardian',
    authorized: 'Ready to Pay', paid: 'Paid', declined: 'Declined',
    waived: 'Waived', expired: 'Expired',
  }[s] || s
}

function statusPillClass(s) {
  return {
    pending: 'bg-slate-100 text-slate-600 border-slate-200',
    awaiting_guardian: 'bg-amber-100 text-amber-800 border-amber-200',
    authorized: 'bg-amber-100 text-amber-800 border-amber-200',
    paid: 'bg-emerald-100 text-[#005506] border-emerald-200',
    declined: 'bg-rose-100 text-rose-700 border-rose-200',
    waived: 'bg-blue-100 text-blue-700 border-blue-200',
    expired: 'bg-slate-100 text-slate-500 border-slate-200',
  }[s] || 'bg-slate-100 text-slate-600 border-slate-200'
}

function formatMoney(v) {
  return Number(v || 0).toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 })
}

function formatDate(v) {
  if (!v) return '—'
  try { return new Date(v).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' }) }
  catch { return '—' }
}
</script>