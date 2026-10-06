<template>
  <Head :title="`${contribution.title} - Salawag LMS`" />

  <AuthenticatedLayout searchPlaceholder="Search students by name or LRN...">
    <div class="relative z-10 px-3 sm:px-6 md:px-10 pb-16 sm:pb-24 space-y-4 sm:space-y-6 flex-1 mt-2">

      <!-- HERO -->
      <div v-observe
        class="anim-fade-down relative w-full rounded-2xl sm:rounded-3xl overflow-hidden shadow-lg border border-[#006907]/20 dark:border-none min-h-[220px] sm:min-h-[280px] flex flex-col justify-center p-4 sm:p-8 md:p-10">
        <img :src="heroImage" alt="Contribution Detail"
          class="absolute inset-0 w-full h-full object-cover z-0 object-center" />
        <div class="absolute inset-0 bg-[#004d05]/85 dark:bg-[#152B1C]/90 animate-overlay z-0 mix-blend-multiply"></div>
        <div class="absolute -right-10 -bottom-10 w-72 h-72 rounded-full bg-emerald-300/20 blur-3xl pointer-events-none z-0"></div>

        <div class="relative z-10 w-full space-y-3">
          <div class="flex flex-wrap items-center gap-2">
            <span :class="contribution.is_published
              ? 'bg-emerald-500 text-white'
              : 'bg-slate-200 text-slate-800'"
              class="text-[10px] sm:text-xs font-black uppercase tracking-wider px-3 py-1 rounded-full shadow-sm flex items-center gap-1.5">
              <Icon :icon="contribution.is_published ? 'check-circle' : 'edit'" size="xs" />
              {{ contribution.is_published ? 'Published' : 'Draft' }}
            </span>
            <span class="bg-white/20 backdrop-blur-md text-white border border-white/20 text-[10px] sm:text-xs font-semibold px-3 py-1.5 rounded-full shadow-sm">
              {{ contribution.classroom?.subject }} • {{ contribution.classroom?.section }}
            </span>
            <span v-if="contribution.amount_type === 'fixed'" class="bg-white/20 backdrop-blur-md text-white border border-white/20 text-[10px] sm:text-xs font-semibold px-3 py-1.5 rounded-full shadow-sm">
              ₱{{ formatMoney(contribution.amount) }} fixed
            </span>
            <span v-else class="bg-white/20 backdrop-blur-md text-white border border-white/20 text-[10px] sm:text-xs font-semibold px-3 py-1.5 rounded-full shadow-sm">
              Open — min ₱{{ formatMoney(contribution.min_amount) }}
            </span>
          </div>

          <div class="space-y-1.5 max-w-3xl">
            <h2 class="text-2xl sm:text-4xl md:text-5xl font-black text-white tracking-tight leading-tight">
              {{ contribution.title }}
            </h2>
            <p v-if="contribution.purpose" class="text-white/90 text-xs sm:text-sm md:text-base font-medium italic">
              {{ contribution.purpose }}
            </p>
          </div>

          <div class="flex flex-wrap gap-2 pt-2">
            <BackButton fallback="teacher.contributions.index"
              class="inline-flex items-center gap-1.5 bg-white/15 hover:bg-white/25 backdrop-blur-md text-white border border-white/20 px-3.5 py-2 rounded-xl text-xs font-bold shadow-sm transition-all">
              Back
            </BackButton>
            <Link :href="route('teacher.contributions.create')"
              class="hidden"></Link>
          </div>
        </div>
      </div>

      <!-- STAT CARDS -->
      <div v-observe class="anim-fade-slide-up grid grid-cols-2 lg:grid-cols-4 gap-3.5 sm:gap-5" style="animation-delay: 100ms;">
        <div v-for="(card, i) in statCards" :key="i"
          class="bg-[#fbfdf9] dark:bg-[#2D3A31] rounded-2xl sm:rounded-3xl p-4 sm:p-6 shadow-sm border border-slate-200/80 dark:border-[#3F4F43] flex flex-col justify-between space-y-2">
          <div class="flex items-start justify-between">
            <span class="text-[10px] sm:text-[11px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400">
              {{ card.label }}
            </span>
            <div :class="['w-9 h-9 rounded-xl flex items-center justify-center shrink-0 border', card.iconClass]">
              <Icon :icon="card.icon" size="sm" />
            </div>
          </div>
          <div :class="['text-xl sm:text-2xl font-black', card.valueClass || 'text-slate-900 dark:text-white']">
            {{ card.value }}
          </div>
          <p class="text-[11px] font-medium text-slate-500 dark:text-slate-400">{{ card.hint }}</p>
        </div>
      </div>

      <!-- PROGRESS -->
      <div v-observe class="anim-fade-slide-up bg-[#fbfdf9] dark:bg-[#2D3A31] rounded-3xl border border-slate-200/80 dark:border-[#3F4F43] p-4 sm:p-6 shadow-sm space-y-4" style="animation-delay: 150ms;">
        <div class="flex items-center justify-between">
          <div>
            <h3 class="text-sm sm:text-base font-extrabold text-slate-900 dark:text-white">Collection Progress</h3>
            <p class="text-[11px] sm:text-xs text-slate-500 dark:text-slate-400 font-medium">
              {{ paidCount }} of {{ totalCount }} students paid
            </p>
          </div>
          <div class="text-right">
            <span class="text-2xl sm:text-3xl font-black text-[#004d08] dark:text-[#86EFAC]">{{ progressPercent }}%</span>
            <p class="text-[10px] text-slate-500 dark:text-slate-400 font-bold uppercase tracking-wider">Collected</p>
          </div>
        </div>
        <div class="w-full h-3 rounded-full bg-slate-200 dark:bg-[#3F4F43] overflow-hidden">
          <div class="h-full rounded-full bg-gradient-to-r from-[#004d08] via-emerald-500 to-emerald-400 transition-all duration-700"
            :style="{ width: progressPercent + '%' }"></div>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 pt-2">
          <div class="bg-emerald-50 dark:bg-emerald-950/40 rounded-xl p-3 text-center border border-emerald-200/60 dark:border-emerald-900/40">
            <span class="text-[10px] font-black uppercase text-emerald-700 dark:text-emerald-300 block">Paid</span>
            <span class="text-lg font-black text-[#004d08] dark:text-[#86EFAC]">{{ paidCount }}</span>
          </div>
          <div class="bg-amber-50 dark:bg-amber-950/40 rounded-xl p-3 text-center border border-amber-200/60 dark:border-amber-900/40">
            <span class="text-[10px] font-black uppercase text-amber-700 dark:text-amber-300 block">Authorized</span>
            <span class="text-lg font-black text-amber-700 dark:text-amber-400">{{ authorizedCount }}</span>
          </div>
          <div class="bg-sky-50 dark:bg-sky-950/40 rounded-xl p-3 text-center border border-sky-200/60 dark:border-sky-900/40">
            <span class="text-[10px] font-black uppercase text-sky-700 dark:text-sky-300 block">Awaiting</span>
            <span class="text-lg font-black text-sky-700 dark:text-sky-400">{{ awaitingCount }}</span>
          </div>
          <div class="bg-rose-50 dark:bg-rose-950/40 rounded-xl p-3 text-center border border-rose-200/60 dark:border-rose-900/40">
            <span class="text-[10px] font-black uppercase text-rose-700 dark:text-rose-300 block">Declined</span>
            <span class="text-lg font-black text-rose-700 dark:text-rose-400">{{ declinedCount }}</span>
          </div>
        </div>
      </div>

      <!-- ROSTER -->
      <div v-observe class="anim-fade-slide-up bg-[#fbfdf9] dark:bg-[#2D3A31] rounded-3xl border border-slate-200/80 dark:border-[#3F4F43] p-4 sm:p-6 shadow-sm space-y-4" style="animation-delay: 200ms;">
        <div class="flex flex-wrap items-center justify-between gap-3 pb-4 border-b border-slate-200/60 dark:border-[#3F4F43]">
          <div>
            <h3 class="text-base sm:text-lg font-extrabold text-slate-900 dark:text-white">Student Roster</h3>
            <p class="text-[11px] sm:text-xs text-slate-500 dark:text-slate-400 font-medium">
              {{ assignments.length }} {{ assignments.length === 1 ? 'student' : 'students' }} assigned
            </p>
          </div>

          <div class="flex items-center gap-1.5 overflow-x-auto no-scrollbar">
            <button v-for="f in statusFilters" :key="f.id" @click="statusFilter = f.id"
              :class="[
                'text-[11px] font-black px-3 py-1.5 rounded-xl transition-all shrink-0 active:scale-95',
                statusFilter === f.id
                  ? 'bg-[#004d08] dark:bg-[#86EFAC] text-white dark:text-[#232D26]'
                  : 'bg-[#f5f7f2] dark:bg-[#232D26] text-slate-600 dark:text-slate-300 border border-slate-200/80 dark:border-[#3F4F43]'
              ]">
              {{ f.label }} ({{ f.count }})
            </button>
          </div>
        </div>

        <div v-if="filteredAssignments.length" class="overflow-x-auto -mx-2 sm:mx-0 rounded-xl border border-slate-200/80 dark:border-[#3F4F43]">
          <table class="w-full min-w-[800px] text-left border-collapse">
            <thead>
              <tr class="bg-[#f5f7f2] dark:bg-[#232D26] text-[10px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400 border-b border-slate-200/80 dark:border-[#3F4F43]">
                <th class="py-3 px-3 text-center w-12">#</th>
                <th class="py-3 px-3">Student</th>
                <th class="py-3 px-3">Guardian</th>
                <th class="py-3 px-3 text-center">Amount</th>
                <th class="py-3 px-3 text-center">Status</th>
                <th class="py-3 px-3 text-right">Action</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-[#3F4F43] text-xs">
              <tr v-for="(a, idx) in filteredAssignments" :key="a.id" class="hover:bg-slate-50/80 dark:hover:bg-[#232D26]/40 transition-colors">
                <td class="py-3 px-3 text-center font-bold text-slate-400">{{ String(idx + 1).padStart(2, '0') }}</td>
                <td class="py-3 px-3">
                  <div class="flex items-center gap-2">
                    <span class="w-8 h-8 rounded-full bg-emerald-100 dark:bg-emerald-950/60 text-[#004d08] dark:text-[#86EFAC] font-extrabold text-[11px] flex items-center justify-center shrink-0">
                      {{ initialsOf(a.student_name) }}
                    </span>
                    <div class="min-w-0">
                      <p class="font-extrabold text-slate-800 dark:text-white truncate">{{ a.student_name }}</p>
                      <p class="text-[10px] font-mono text-slate-400 truncate">LRN: {{ a.lrn }}</p>
                    </div>
                  </div>
                </td>
                <td class="py-3 px-3">
                  <div v-if="a.guardian_name" class="min-w-0">
                    <p class="text-[11px] font-bold text-slate-700 dark:text-slate-200 truncate">{{ a.guardian_name }}</p>
                    <p class="text-[10px] text-slate-500 dark:text-slate-400 truncate">{{ a.guardian_email || '—' }}</p>
                  </div>
                  <span v-else class="text-[11px] text-slate-400 italic">No guardian on file</span>
                </td>
                <td class="py-3 px-3 text-center font-black text-slate-900 dark:text-white">
                  ₱{{ formatMoney(a.amount_owed) }}
                </td>
                <td class="py-3 px-3 text-center">
                  <span :class="statusBadgeClass(a.status)"
                    class="text-[10px] font-black uppercase px-2.5 py-1 rounded-full border whitespace-nowrap">
                    {{ statusLabel(a.status) }}
                  </span>
                </td>
                <td class="py-3 px-3 text-right">
                  <button v-if="['pending', 'declined'].includes(a.status)"
                    @click="resendConsent(a)"
                    :disabled="resendingId === a.id"
                    class="bg-[#004d08] dark:bg-[#86EFAC] text-white dark:text-[#232D26] text-[11px] font-black px-3 py-1.5 rounded-lg shadow-sm transition-all active:scale-95 disabled:opacity-50 inline-flex items-center gap-1">
                    <Icon icon="envelope" size="xs" />
                    {{ resendingId === a.id ? 'Sending…' : 'Resend' }}
                  </button>
                  <span v-else-if="a.status === 'awaiting_guardian'" class="text-[10px] text-amber-700 dark:text-amber-400 font-bold">Awaiting…</span>
                  <span v-else-if="a.status === 'authorized'" class="text-[10px] text-emerald-700 dark:text-emerald-400 font-bold">Ready to pay</span>
                  <span v-else-if="a.status === 'paid'" class="text-[10px] text-[#004d08] dark:text-[#86EFAC] font-bold inline-flex items-center gap-1">
                    <Icon icon="check-circle" size="xs" /> Paid
                  </span>
                  <span v-else class="text-[10px] text-slate-400">—</span>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <div v-else class="bg-[#f5f7f2] dark:bg-[#232D26] rounded-2xl p-12 text-center border border-dashed border-slate-300 dark:border-[#3F4F43] space-y-2">
          <Icon icon="users" size="xl" class="text-slate-400 mx-auto" />
          <p class="text-sm font-bold text-slate-800 dark:text-white">No students match this filter</p>
          <p class="text-xs text-slate-500 dark:text-slate-400">Try a different status.</p>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import { confirmAction, showError } from '@/Pages/useSweetAlert'
import { ref, computed } from 'vue'
import { Head, Link, router } from '@inertiajs/vue3'
import axios from 'axios'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import BackButton from '@/Components/BackButton.vue'
import Icon from '@/Components/Icon.vue'
import heroImage from '../../../../assets/img/local/assignment.png'

const props = defineProps({
  contribution: { type: Object, default: () => ({}) },
  assignments:  { type: Array,  default: () => [] },
})

const statusFilter = ref('all')
const resendingId  = ref(null)

const paidCount       = computed(() => props.assignments.filter(a => a.status === 'paid').length)
const authorizedCount = computed(() => props.assignments.filter(a => a.status === 'authorized').length)
const awaitingCount   = computed(() => props.assignments.filter(a => a.status === 'awaiting_guardian').length)
const declinedCount   = computed(() => props.assignments.filter(a => a.status === 'declined').length)
const totalCount      = computed(() => props.assignments.length)

const progressPercent = computed(() =>
  totalCount.value === 0 ? 0 : Math.round((paidCount.value / totalCount.value) * 100))

const statCards = computed(() => [
  { label: 'Assigned', icon: 'users', value: totalCount.value, hint: 'Total students',
    iconClass: 'bg-emerald-50 dark:bg-emerald-950/60 text-[#004d08] dark:text-[#86EFAC] border-emerald-100 dark:border-emerald-900/40' },
  { label: 'Paid', icon: 'check-circle', value: paidCount.value, hint: 'Collected',
    iconClass: 'bg-emerald-50 dark:bg-emerald-950/60 text-[#004d08] dark:text-[#86EFAC] border-emerald-100 dark:border-emerald-900/40',
    valueClass: 'text-[#004d08] dark:text-[#86EFAC]' },
  { label: 'Pending', icon: 'clock', value: awaitingCount.value + authorizedCount.value, hint: 'In progress',
    iconClass: 'bg-amber-50 dark:bg-amber-950/60 text-amber-700 dark:text-amber-300 border-amber-100 dark:border-amber-900/40',
    valueClass: 'text-amber-700 dark:text-amber-400' },
  { label: 'Declined', icon: 'x-circle', value: declinedCount.value, hint: 'Guardian said no',
    iconClass: 'bg-rose-50 dark:bg-rose-950/60 text-rose-600 dark:text-rose-400 border-rose-100 dark:border-rose-900/40',
    valueClass: 'text-rose-600 dark:text-rose-400' },
])

const statusFilters = computed(() => [
  { id: 'all',       label: 'All',       count: props.assignments.length },
  { id: 'paid',      label: 'Paid',      count: paidCount.value },
  { id: 'authorized',label: 'Ready',     count: authorizedCount.value },
  { id: 'awaiting_guardian', label: 'Awaiting', count: awaitingCount.value },
  { id: 'declined',  label: 'Declined',  count: declinedCount.value },
].filter(f => f.id === 'all' || f.count > 0))

const filteredAssignments = computed(() =>
  statusFilter.value === 'all'
    ? props.assignments
    : props.assignments.filter(a => a.status === statusFilter.value))

async function resendConsent(a) {
  resendingId.value = a.id
  try {
    await axios.post(route('teacher.contributions.resend-consent', a.id))
    router.reload({ only: ['assignments'], preserveScroll: true })
  } catch (e) {
    showError(e.response?.data?.message || 'Could not resend.')
  } finally {
    resendingId.value = null
  }
}

function initialsOf(name) {
  if (!name) return '?'
  const parts = String(name).trim().split(/\s+/)
  if (parts.length >= 2) return (parts[0][0] + parts[parts.length - 1][0]).toUpperCase()
  return name.substring(0, 2).toUpperCase()
}

function formatMoney(v) {
  return Number(v || 0).toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 })
}

function statusLabel(s) {
  return {
    pending: 'Pending', awaiting_guardian: 'Awaiting Guardian',
    authorized: 'Ready to Pay', paid: 'Paid', declined: 'Declined',
    waived: 'Waived', expired: 'Expired',
  }[s] || s
}

function statusBadgeClass(s) {
  return {
    pending:           'bg-slate-100 dark:bg-[#232D26] text-slate-600 dark:text-slate-300 border-slate-200 dark:border-[#3F4F43]',
    awaiting_guardian: 'bg-amber-100 dark:bg-amber-950/60 text-amber-800 dark:text-amber-300 border-amber-200 dark:border-amber-900/40',
    authorized:        'bg-amber-100 dark:bg-amber-950/60 text-amber-800 dark:text-amber-300 border-amber-200 dark:border-amber-900/40',
    paid:              'bg-emerald-100 dark:bg-emerald-950/60 text-[#004d08] dark:text-[#86EFAC] border-emerald-200 dark:border-emerald-900/40',
    declined:          'bg-rose-100 dark:bg-rose-950/60 text-rose-700 dark:text-rose-300 border-rose-200 dark:border-rose-900/40',
    waived:            'bg-sky-100 dark:bg-sky-950/60 text-sky-700 dark:text-sky-300 border-sky-200 dark:border-sky-900/40',
  }[s] || 'bg-slate-100 text-slate-600 border-slate-200'
}

const vObserve = {
  mounted(el) {
    el.classList.add('not-visible')
    const observer = new IntersectionObserver(([entry]) => {
      if (entry.isIntersecting) el.classList.add('is-animated')
      else el.classList.remove('is-animated')
    }, { threshold: 0.1 })
    observer.observe(el)
  },
}
</script>

<style scoped>
.no-scrollbar::-webkit-scrollbar { display: none; }
.no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
@keyframes pulse-opacity { 0%, 100% { opacity: 0.88; } 50% { opacity: 0.65; } }
.animate-overlay { animation: pulse-opacity 6s infinite ease-in-out; }
@keyframes fadeInDown { from { opacity: 0; transform: translateY(-20px); } to { opacity: 1; transform: translateY(0); } }
@keyframes fadeSlideUp { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
.not-visible { opacity: 0; }
.is-animated.anim-fade-down { animation: fadeInDown 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
.is-animated.anim-fade-slide-up { animation: fadeSlideUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) 0.1s both; }
</style>