<template>
  <Head title="My Contributions - Salawag LMS" />

  <div :class="[
    'h-screen w-full flex bg-[#e8f5e9] dark:bg-[#232D26] font-[\'Inter\'] relative transition-colors duration-300 overflow-hidden',
    `text-scale-${fontSizeMode}`
  ]">
    <Sidebar :is-open="isSidebarOpen" @close-sidebar="isSidebarOpen = false" class="sticky top-0 h-screen shrink-0 z-30" />

    <main class="flex-1 h-full overflow-y-auto overflow-x-hidden min-w-0 flex flex-col justify-between w-full">
      <navbartop searchPlaceholder="Search contributions..."
        @open-sidebar="isSidebarOpen = true"
        @font-size-changed="(size) => fontSizeMode = size" />

      <div class="relative z-10 p-3 sm:p-6 md:p-8 space-y-4 sm:space-y-6 flex-1 pb-16 min-w-0">

        <!-- HERO -->
        <div class="animate-fade-in-down relative w-full rounded-2xl sm:rounded-3xl overflow-hidden shadow-lg border border-[#006907]/20 dark:border-none min-h-[200px] sm:min-h-[240px] flex flex-col justify-center p-4 sm:p-8 md:p-10">
          <img :src="heroImage" alt="Contributions"
            class="absolute inset-0 w-full h-full object-cover z-0 object-center" />
          <div class="absolute inset-0 bg-[#004d05] dark:bg-[#152B1C] animate-overlay z-0 mix-blend-multiply"></div>

          <div class="relative z-10 w-full max-w-3xl space-y-3">
            <div class="flex flex-wrap items-center gap-2">
              <div class="inline-flex items-center gap-1.5 bg-[#F9C20C] text-[#2C3E2D] font-black text-[10px] sm:text-xs px-3 sm:px-4 py-1 sm:py-1.5 rounded-full shadow-sm tracking-wide">
                <Icon icon="credit-card" size="xs" /> CONTRIBUTIONS
              </div>
              <div class="inline-flex items-center gap-2 bg-black/30 dark:bg-black/50 backdrop-blur-md text-white border border-white/20 text-[10px] sm:text-xs font-semibold px-3 sm:px-4 py-1 sm:py-1.5 rounded-full shadow-sm">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                {{ counts.all }} total • {{ counts.pending }} awaiting action
              </div>
            </div>

            <div class="space-y-1.5">
              <h2 class="text-2xl sm:text-4xl md:text-5xl font-black text-white tracking-tight leading-tight">
                My Contributions
              </h2>
              <p class="text-white/90 text-xs sm:text-sm md:text-base font-medium">
                Guardian-approved payments for field trips, projects, and class activities.
              </p>
            </div>
          </div>
        </div>

        <!-- STATUS FILTER -->
        <div class="animate-fade-slide-left bg-[#fbfdf9] dark:bg-[#2D3A31] border border-slate-200/80 dark:border-[#3F4F43] rounded-2xl p-3.5 sm:p-4 shadow-sm">
          <div class="flex items-center gap-1.5 overflow-x-auto no-scrollbar">
            <button v-for="f in statusFilters" :key="f.id" @click="statusFilter = f.id"
              :class="[
                'text-[11px] font-black px-3.5 py-2 rounded-xl shadow-sm transition-all shrink-0',
                statusFilter === f.id
                  ? 'bg-[#005506] dark:bg-[#86EFAC] text-white dark:text-[#232D26]'
                  : 'bg-[#F9F7F1] dark:bg-[#232D26] text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-[#3F4F43] border border-slate-200/80 dark:border-[#3F4F43]'
              ]">
              {{ f.label }} ({{ f.count }})
            </button>
          </div>
        </div>

        <!-- LIST -->
        <div v-if="filteredAssignments.length" class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div v-for="a in filteredAssignments" :key="a.id"
            :class="[
              'bg-white dark:bg-[#2D3A31] rounded-2xl sm:rounded-3xl p-5 shadow-sm border space-y-4 transition-all',
              a.status === 'paid'
                ? 'border-emerald-200/80 dark:border-emerald-900/40'
                : a.status === 'authorized'
                  ? 'border-amber-300/80 dark:border-amber-900/50'
                  : a.status === 'declined'
                    ? 'border-rose-200/80 dark:border-rose-900/40'
                    : 'border-slate-200/60 dark:border-[#3F4F43]'
            ]">

            <!-- Header -->
            <div class="flex items-start justify-between gap-3">
              <div class="flex items-start gap-3 min-w-0">
                <div :class="['w-10 h-10 rounded-2xl flex items-center justify-center shrink-0 border', statusIconClass(a.status)]">
                  <Icon :icon="statusIcon(a.status)" size="md" />
                </div>
                <div class="min-w-0 space-y-0.5">
                  <h3 class="text-sm sm:text-base font-extrabold text-slate-900 dark:text-white leading-snug truncate">
                    {{ a.title }}
                  </h3>
                  <p class="text-[11px] font-semibold text-slate-500 dark:text-slate-400 truncate">
                    {{ a.purpose || 'Class activity' }}
                  </p>
                </div>
              </div>
              <span :class="statusPillClass(a.status)"
                class="text-[10px] font-black uppercase tracking-wider px-2.5 py-1 rounded-full border shrink-0 whitespace-nowrap">
                {{ statusLabel(a.status) }}
              </span>
            </div>

            <!-- Amount block -->
            <div class="bg-[#F9F7F1] dark:bg-[#232D26] p-3.5 rounded-2xl border border-slate-200/80 dark:border-[#3F4F43] space-y-2">
              <div class="flex items-center justify-between">
                <span class="text-[10px] font-black uppercase tracking-wider text-slate-500">Amount</span>
                <span class="text-lg font-black text-slate-900 dark:text-white">
                  ₱{{ formatMoney(a.amount_owed) }}
                </span>
              </div>
              <div v-if="a.deadline_at" class="flex items-center justify-between text-[11px]">
                <span class="font-semibold text-slate-500">Deadline</span>
                <span :class="isPast(a.deadline_at) ? 'text-rose-600 font-bold' : 'text-slate-700 dark:text-slate-300 font-bold'">
                  {{ formatDate(a.deadline_at) }}
                </span>
              </div>
              <div v-if="a.requires_consent && a.guardian" class="flex items-center justify-between text-[11px]">
                <span class="font-semibold text-slate-500">Guardian</span>
                <span class="text-slate-700 dark:text-slate-300 font-bold truncate max-w-[180px]">{{ a.guardian.name }}</span>
              </div>
            </div>

            <!-- Decline reason -->
            <div v-if="a.status === 'declined' && a.decline_reason"
              class="bg-rose-50 dark:bg-rose-950/30 border border-rose-200/80 dark:border-rose-900/40 rounded-xl p-3 text-[11px] text-rose-700 dark:text-rose-300">
              <p class="font-bold mb-0.5">Reason from guardian:</p>
              <p class="leading-snug">{{ a.decline_reason }}</p>
            </div>

            <!-- Actions -->
            <div class="space-y-2 pt-2 border-t border-slate-100 dark:border-[#3F4F43]">
              <button v-if="a.can_request_consent"
                @click="requestConsent(a)"
                :disabled="busyId === a.id"
                class="w-full bg-[#005506] dark:bg-[#86EFAC] hover:bg-[#004105] text-white dark:text-[#232D26] text-xs font-black py-2.5 rounded-xl shadow-sm flex items-center justify-center gap-2 transition-all active:scale-95 disabled:opacity-50">
                <Icon icon="envelope" size="xs" />
                {{ busyId === a.id ? 'Sending…' : (a.status === 'declined' ? 'Resend to Guardian' : 'Request Guardian Approval') }}
              </button>

              <div v-else-if="a.status === 'awaiting_guardian'"
                class="w-full bg-amber-50 dark:bg-amber-950/30 border border-amber-200/80 dark:border-amber-900/40 text-amber-900 dark:text-amber-300 text-xs font-bold py-2.5 rounded-xl flex items-center justify-center gap-2">
                <Icon icon="clock" size="xs" />
                Waiting for guardian approval
              </div>

              <button v-else-if="a.can_pay"
                @click="openCheckout(a)"
                :disabled="busyId === a.id"
                class="w-full bg-[#F9C20C] hover:bg-amber-400 text-[#2C3E2D] text-xs font-black py-2.5 rounded-xl shadow-sm flex items-center justify-center gap-2 transition-all active:scale-95 disabled:opacity-50">
                <Icon icon="credit-card" size="xs" />
                {{ busyId === a.id ? 'Opening…' : 'Pay Now' }}
              </button>

              <div v-else-if="a.status === 'paid'"
                class="w-full bg-emerald-50 dark:bg-emerald-950/30 border border-emerald-200/80 dark:border-emerald-900/40 text-[#005506] dark:text-[#86EFAC] text-xs font-black py-2.5 rounded-xl flex items-center justify-center gap-2">
                <Icon icon="check-circle" size="xs" />
                Paid on {{ formatDate(a.paid_at) }}
              </div>

              <Link :href="route('student.contributions.show', a.id)"
                class="block text-center text-[11px] font-bold text-slate-500 dark:text-slate-400 hover:text-[#005506] dark:hover:text-[#86EFAC] transition-colors">
                View details →
              </Link>
            </div>
          </div>
        </div>

        <div v-else class="bg-[#fbfdf9] dark:bg-[#2D3A31] rounded-2xl p-12 text-center shadow-sm border border-dashed border-slate-300 dark:border-[#3F4F43] space-y-3">
          <Icon icon="credit-card" size="xl" class="text-slate-400 mx-auto" />
          <p class="text-sm font-bold text-slate-800 dark:text-white">
            {{ assignments.length === 0 ? 'No contributions yet' : 'No contributions match this filter' }}
          </p>
          <p class="text-xs text-slate-500 dark:text-slate-400">
            {{ assignments.length === 0 ? 'Your teachers will assign contributions here.' : 'Try a different filter.' }}
          </p>
        </div>

        <!-- OPEN-AMOUNT MODAL -->
        <div v-if="openAmountFor"
          class="fixed inset-0 z-50 bg-slate-950/60 backdrop-blur-xs flex items-center justify-center p-4"
          @click.self="openAmountFor = null">
          <div class="bg-white dark:bg-[#2D3A31] rounded-3xl shadow-2xl w-full max-w-md p-6 space-y-4">
            <div>
              <h3 class="font-black text-slate-900 dark:text-white text-lg">Enter amount</h3>
              <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                Minimum: ₱{{ formatMoney(openAmountFor.min_amount || 0) }}
              </p>
            </div>
            <div class="space-y-1">
              <label class="text-[10px] font-black uppercase tracking-wider text-slate-500">Amount (PHP)</label>
              <input v-model.number="openAmountValue" type="number" min="1" step="0.01"
                :min="openAmountFor.min_amount || 1"
                class="w-full bg-[#F9F7F1] dark:bg-[#232D26] border border-slate-200/80 dark:border-[#3F4F43] rounded-xl px-4 py-3 text-lg font-black text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-[#005506]" />
            </div>
            <div class="flex items-center justify-end gap-2 pt-2">
              <button @click="openAmountFor = null"
                class="px-5 py-2.5 rounded-xl text-slate-600 dark:text-slate-300 text-xs font-bold hover:bg-slate-100 dark:hover:bg-[#3F4F43]">
                Cancel
              </button>
              <button @click="confirmOpenAmount"
                :disabled="!openAmountValue || openAmountValue < (openAmountFor.min_amount || 1)"
                class="bg-[#005506] dark:bg-[#86EFAC] text-white dark:text-[#232D26] px-6 py-2.5 rounded-xl text-xs font-black shadow-sm disabled:opacity-50">
                Continue to Payment
              </button>
            </div>
          </div>
        </div>

      </div>
    </main>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { Head, Link, router } from '@inertiajs/vue3'
import axios from 'axios'
import Sidebar from '@/Components/Sidebar.vue'
import navbartop from '@/Components/navbartop.vue'
import Icon from '@/Components/Icon.vue'
import heroImage from '../../../../assets/img/local/assignment.png'

const props = defineProps({
  assignments: { type: Array,  default: () => [] },
  counts:      { type: Object, default: () => ({ all: 0, pending: 0, ready: 0, paid: 0, declined: 0 }) },
})

const isSidebarOpen = ref(false)
const fontSizeMode  = ref('base')
const statusFilter  = ref('all')
const busyId        = ref(null)
const openAmountFor = ref(null)
const openAmountValue = ref(null)

const statusFilters = computed(() => [
  { id: 'all',      label: 'All',      count: props.counts.all },
  { id: 'pending',  label: 'Pending',  count: props.counts.pending },
  { id: 'ready',    label: 'Ready',    count: props.counts.ready },
  { id: 'paid',     label: 'Paid',     count: props.counts.paid },
  { id: 'declined', label: 'Declined', count: props.counts.declined },
].filter(f => f.id === 'all' || f.count > 0))

const filteredAssignments = computed(() => {
  if (statusFilter.value === 'all') return props.assignments
  if (statusFilter.value === 'pending')
    return props.assignments.filter(a => ['pending', 'awaiting_guardian'].includes(a.status))
  if (statusFilter.value === 'ready')
    return props.assignments.filter(a => a.status === 'authorized')
  if (statusFilter.value === 'paid')
    return props.assignments.filter(a => a.status === 'paid')
  if (statusFilter.value === 'declined')
    return props.assignments.filter(a => a.status === 'declined')
  return props.assignments
})

async function requestConsent(a) {
  busyId.value = a.id
  try {
    await axios.post(route('student.contributions.request-consent', a.id))
    router.reload({ only: ['assignments', 'counts'], preserveScroll: true })
  } catch (e) {
    alert(e.response?.data?.message || 'Could not send consent request.')
  } finally {
    busyId.value = null
  }
}

function openCheckout(a) {
  if (a.amount_type === 'open') {
    openAmountFor.value = a
    openAmountValue.value = a.amount_owed || a.min_amount || 100
    return
  }
  proceedCheckout(a, null)
}

function confirmOpenAmount() {
  const a = openAmountFor.value
  const amount = openAmountValue.value
  openAmountFor.value = null
  proceedCheckout(a, amount)
}

async function proceedCheckout(a, amount) {
  busyId.value = a.id
  try {
    const { data } = await axios.post(route('student.contributions.checkout', a.id), {
      amount: amount ?? undefined,
    })
    if (data.checkout_url) {
      window.location.href = data.checkout_url
    }
  } catch (e) {
    alert(e.response?.data?.message || 'Could not start checkout.')
    busyId.value = null
  }
}

function statusLabel(s) {
  return {
    pending: 'Pending', awaiting_guardian: 'Awaiting Guardian',
    authorized: 'Ready to Pay', paid: 'Paid', declined: 'Declined',
    waived: 'Waived', expired: 'Expired',
  }[s] || s
}

function statusPillClass(s) {
  return {
    pending:            'bg-slate-100 dark:bg-[#232D26] text-slate-600 dark:text-slate-300 border-slate-200/80 dark:border-[#3F4F43]',
    awaiting_guardian:  'bg-amber-100 dark:bg-amber-950/60 text-amber-800 dark:text-amber-300 border-amber-200/80 dark:border-amber-900/40',
    authorized:         'bg-amber-100 dark:bg-amber-950/60 text-amber-800 dark:text-amber-300 border-amber-200/80 dark:border-amber-900/40',
    paid:               'bg-emerald-100 dark:bg-emerald-950/60 text-[#005506] dark:text-[#86EFAC] border-emerald-200/80 dark:border-emerald-900/40',
    declined:           'bg-rose-100 dark:bg-rose-950/60 text-rose-700 dark:text-rose-300 border-rose-200/80 dark:border-rose-900/40',
    waived:             'bg-blue-100 dark:bg-blue-950/60 text-blue-700 dark:text-blue-300 border-blue-200/80 dark:border-blue-900/40',
    expired:            'bg-slate-100 dark:bg-[#232D26] text-slate-500 border-slate-200 dark:border-[#3F4F43]',
  }[s] || 'bg-slate-100 text-slate-600'
}

function statusIcon(s) {
  return {
    pending: 'clock', awaiting_guardian: 'envelope',
    authorized: 'credit-card', paid: 'check-circle',
    declined: 'x-circle', waived: 'check-badge', expired: 'clock',
  }[s] || 'information-circle'
}

function statusIconClass(s) {
  return {
    pending: 'bg-slate-100 dark:bg-[#232D26] text-slate-500 border-slate-200/80 dark:border-[#3F4F43]',
    awaiting_guardian: 'bg-amber-100 dark:bg-amber-950/60 text-amber-700 dark:text-amber-300 border-amber-200/80 dark:border-amber-900/40',
    authorized: 'bg-amber-100 dark:bg-amber-950/60 text-amber-700 dark:text-amber-300 border-amber-200/80 dark:border-amber-900/40',
    paid: 'bg-emerald-100 dark:bg-emerald-950/60 text-[#005506] dark:text-[#86EFAC] border-emerald-200/80 dark:border-emerald-900/40',
    declined: 'bg-rose-100 dark:bg-rose-950/60 text-rose-700 dark:text-rose-300 border-rose-200/80 dark:border-rose-900/40',
  }[s] || 'bg-slate-100 text-slate-500 border-slate-200/80'
}

function formatMoney(v) {
  return Number(v || 0).toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 })
}

function formatDate(v) {
  if (!v) return '—'
  try { return new Date(v).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' }) }
  catch { return '—' }
}

function isPast(v) { return v && new Date(v) < new Date() }
</script>

<style scoped>
.no-scrollbar::-webkit-scrollbar { display: none; }
.no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }

@keyframes fadeInDown { from { opacity: 0; transform: translateY(-20px); } to { opacity: 1; transform: translateY(0); } }
@keyframes fadeSlideLeft { from { opacity: 0; transform: translateX(-30px); } to { opacity: 1; transform: translateX(0); } }
@keyframes pulse-opacity { 0%, 100% { opacity: 0.88; } 50% { opacity: 0.65; } }
.animate-overlay { animation: pulse-opacity 6s infinite ease-in-out; }
.animate-fade-in-down { animation: fadeInDown 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
.animate-fade-slide-left { animation: fadeSlideLeft 0.7s cubic-bezier(0.16, 1, 0.3, 1) 0.1s both; }
</style>