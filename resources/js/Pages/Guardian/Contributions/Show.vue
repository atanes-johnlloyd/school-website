<template>
  <Head :title="`${contribution.title} - Salawag LMS`" />

  <AuthenticatedLayout searchPlaceholder="Search students by name or LRN...">
    <div class="relative z-10 px-4 sm:px-6 md:px-10 pb-24 space-y-6 flex-1 mt-4">

      <!-- HERO -->
      <div v-observe
        class="anim-fade-down relative w-full rounded-3xl overflow-hidden shadow-lg border border-[#006907]/20 dark:border-none min-h-[220px] flex flex-col justify-center p-6 sm:p-8 md:p-10">
        <img :src="heroImage" alt="Contribution"
          class="absolute inset-0 w-full h-full object-cover z-0 object-center" />
        <div class="absolute inset-0 bg-[#004d05] dark:bg-[#152B1C] animate-overlay z-0 mix-blend-multiply"></div>

        <div class="relative z-10 max-w-3xl space-y-3">
          <div class="flex flex-wrap items-center gap-2">
            <span :class="contribution.is_published
              ? 'bg-emerald-500 text-white'
              : 'bg-slate-200 text-slate-800'"
              class="text-[10px] sm:text-xs font-black uppercase tracking-wider px-3 py-1 rounded-full shadow-sm flex items-center gap-1.5">
              <Icon :icon="contribution.is_published ? 'check-circle' : 'edit'" size="xs" />
              {{ contribution.is_published ? 'Published' : 'Draft' }}
            </span>
            <span class="bg-white/20 backdrop-blur-md text-white border border-white/20 text-[10px] sm:text-xs font-semibold px-3 py-1.5 rounded-full shadow-sm flex items-center gap-1.5">
              <Icon :icon="contribution.scope === 'section' ? 'users' : 'book-open'" size="xs" />
              {{ contribution.display_name }}
            </span>
            <span class="bg-white/20 backdrop-blur-md text-white border border-white/20 text-[10px] sm:text-xs font-semibold px-3 py-1.5 rounded-full shadow-sm">
              {{ contribution.amount_type === 'fixed'
                ? '₱' + formatMoney(contribution.amount) + ' fixed'
                : 'Open — min ₱' + formatMoney(contribution.min_amount) }}
            </span>
          </div>

          <div class="space-y-1.5">
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
            <Link :href="route('teacher.contributions.create')" class="hidden"></Link>
          </div>
        </div>
      </div>

      <!-- STAT CARDS -->
      <div v-observe class="anim-slide-up grid grid-cols-2 lg:grid-cols-4 gap-3.5 sm:gap-5" style="animation-delay: 100ms;">
        <div v-for="(card, i) in statCards" :key="i"
          class="bg-white dark:bg-[#2D3A31] rounded-2xl sm:rounded-3xl p-4 sm:p-6 shadow-sm border border-slate-200/60 dark:border-[#3F4F43] flex flex-col justify-between space-y-2">
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
      <div v-observe class="anim-slide-up bg-white dark:bg-[#2D3A31] rounded-3xl border border-slate-200/60 dark:border-[#3F4F43] p-4 sm:p-6 shadow-sm space-y-4" style="animation-delay: 150ms;">
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
            <span class="text-[10px] font-black uppercase text-amber-700 dark:text-amber-300 block">Cash Incoming</span>
            <span class="text-lg font-black text-amber-700 dark:text-amber-400">{{ cashPendingCount }}</span>
          </div>
          <div class="bg-sky-50 dark:bg-sky-950/40 rounded-xl p-3 text-center border border-sky-200/60 dark:border-sky-900/40">
            <span class="text-[10px] font-black uppercase text-sky-700 dark:text-sky-300 block">Notified</span>
            <span class="text-lg font-black text-sky-700 dark:text-sky-400">{{ notifiedCount }}</span>
          </div>
          <div class="bg-rose-50 dark:bg-rose-950/40 rounded-xl p-3 text-center border border-rose-200/60 dark:border-rose-900/40">
            <span class="text-[10px] font-black uppercase text-rose-700 dark:text-rose-300 block">Declined</span>
            <span class="text-lg font-black text-rose-700 dark:text-rose-400">{{ declinedCount }}</span>
          </div>
        </div>
      </div>

      <!-- ROSTER -->
      <div v-observe class="anim-slide-up bg-white dark:bg-[#2D3A31] rounded-3xl border border-slate-200/60 dark:border-[#3F4F43] p-4 sm:p-6 shadow-sm space-y-4" style="animation-delay: 200ms;">
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
                  : 'bg-[#F9F7F1] dark:bg-[#232D26] text-slate-600 dark:text-slate-300 border border-slate-200/80 dark:border-[#3F4F43]'
              ]">
              {{ f.label }} ({{ f.count }})
            </button>
          </div>
        </div>

        <div v-if="filteredAssignments.length" class="overflow-x-auto -mx-2 sm:mx-0 rounded-xl border border-slate-200/80 dark:border-[#3F4F43]">
          <table class="w-full min-w-[900px] text-left border-collapse">
            <thead>
              <tr class="bg-[#F9F7F1] dark:bg-[#232D26] text-[10px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400 border-b border-slate-200/80 dark:border-[#3F4F43]">
                <th class="py-3 px-3 text-center w-12">#</th>
                <th class="py-3 px-3">Student</th>
                <th class="py-3 px-3">Guardian</th>
                <th class="py-3 px-3 text-center">Amount</th>
                <th class="py-3 px-3 text-center">Status</th>
                <th class="py-3 px-3 text-right">Actions</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-[#3F4F43] text-xs">
              <tr v-for="(a, idx) in filteredAssignments" :key="a.id"
                class="hover:bg-slate-50/80 dark:hover:bg-[#232D26]/40 transition-colors">
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
                  <span v-else class="text-[11px] text-rose-600 dark:text-rose-400 font-bold italic">⚠ No guardian</span>
                </td>
                <td class="py-3 px-3 text-center font-black text-slate-900 dark:text-white">
                  ₱{{ formatMoney(a.amount_owed) }}
                </td>
                <td class="py-3 px-3 text-center">
                  <span :class="statusBadgeClass(a.status)"
                    class="text-[10px] font-black uppercase px-2.5 py-1 rounded-full border whitespace-nowrap inline-flex items-center gap-1">
                    <Icon :icon="statusIcon(a.status)" size="xs" />
                    {{ statusLabel(a.status) }}
                  </span>
                </td>
                <td class="py-3 px-3 text-right">
                  <div class="flex items-center justify-end gap-1.5 flex-wrap">
                    <!-- Notify / resend -->
                    <button v-if="['pending', 'notified', 'declined', 'overdue'].includes(a.status)"
                      @click="notifyGuardian(a)"
                      :disabled="busyId === a.id"
                      title="Resend payment email"
                      class="w-8 h-8 rounded-lg bg-white dark:bg-[#2D3A31] border border-slate-200 dark:border-[#3F4F43] flex items-center justify-center text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-[#3F4F43] transition-colors disabled:opacity-50">
                      <Icon icon="envelope" size="xs" />
                    </button>

                    <!-- Cash pending → confirm -->
                    <button v-if="a.status === 'cash_pending'"
                      @click="markCashReceived(a)"
                      :disabled="busyId === a.id"
                      title="Confirm cash received"
                      class="bg-emerald-600 hover:bg-emerald-700 text-white text-[10px] font-black px-2.5 py-1.5 rounded-lg shadow-sm transition-all active:scale-95 disabled:opacity-50 inline-flex items-center gap-1">
                      <Icon icon="check-circle" size="xs" />
                      Confirm Cash
                    </button>

                    <!-- Cash pending → reject -->
                    <button v-if="a.status === 'cash_pending'"
                      @click="openRejectCash(a)"
                      :disabled="busyId === a.id"
                      title="Reject cash (not received)"
                      class="w-8 h-8 rounded-lg bg-white dark:bg-[#2D3A31] border border-rose-200 dark:border-rose-900/40 flex items-center justify-center text-rose-600 dark:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-950/40 transition-colors disabled:opacity-50">
                      <Icon icon="x" size="xs" />
                    </button>

                    <!-- Notified/pending → mark cash received directly (offline) -->
                    <button v-if="['notified', 'pending'].includes(a.status)"
                      @click="markCashReceived(a)"
                      :disabled="busyId === a.id"
                      title="Mark as paid (cash received offline)"
                      class="bg-amber-500 hover:bg-amber-600 text-white text-[10px] font-black px-2.5 py-1.5 rounded-lg shadow-sm transition-all active:scale-95 disabled:opacity-50 inline-flex items-center gap-1">
                      <Icon icon="banknotes" size="xs" />
                      Mark Paid
                    </button>

                    <!-- Paid -->
                    <span v-if="a.status === 'paid'" class="text-[10px] text-[#004d08] dark:text-[#86EFAC] font-bold inline-flex items-center gap-1">
                      <Icon icon="check-circle" size="xs" />
                      {{ a.paid_at ? formatDate(a.paid_at) : 'Paid' }}
                    </span>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <div v-else class="bg-[#F9F7F1] dark:bg-[#232D26] rounded-2xl p-12 text-center border border-dashed border-slate-300 dark:border-[#3F4F43] space-y-2">
          <Icon icon="users" size="xl" class="text-slate-400 mx-auto" />
          <p class="text-sm font-bold text-slate-800 dark:text-white">No students match this filter</p>
          <p class="text-xs text-slate-500 dark:text-slate-400">Try a different status.</p>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>

  <!-- REJECT CASH MODAL -->
  <div v-if="rejectCashFor"
    class="fixed inset-0 z-50 bg-slate-950/60 backdrop-blur-sm flex items-center justify-center p-4"
    @click.self="rejectCashFor = null">
    <div class="bg-white dark:bg-[#2D3A31] rounded-3xl shadow-2xl w-full max-w-md p-6 space-y-4 border border-slate-100 dark:border-[#3F4F43]">
      <div>
        <h3 class="font-black text-slate-900 dark:text-white text-base">Reject cash?</h3>
        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
          Guardian for <strong>{{ rejectCashFor.student_name }}</strong> will be notified to try again.
        </p>
      </div>
      <div class="space-y-1">
        <label class="text-[10px] font-black uppercase tracking-wider text-slate-500">Reason *</label>
        <textarea v-model="rejectReason" rows="3"
          placeholder="e.g. Cash was not received"
          class="w-full bg-[#F9F7F1] dark:bg-[#232D26] border border-slate-200/80 dark:border-[#3F4F43] rounded-xl p-3 text-xs text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-rose-400 resize-none"></textarea>
      </div>
      <div class="flex items-center justify-end gap-2 pt-1">
        <button @click="rejectCashFor = null"
          class="px-5 py-2.5 rounded-xl text-slate-600 dark:text-slate-300 text-xs font-bold hover:bg-slate-100 dark:hover:bg-[#3F4F43]">
          Cancel
        </button>
        <button @click="rejectCash" :disabled="busyId === rejectCashFor.id"
          class="bg-rose-600 hover:bg-rose-700 text-white px-6 py-2.5 rounded-xl text-xs font-black shadow-sm disabled:opacity-50">
          {{ busyId === rejectCashFor.id ? 'Rejecting…' : 'Confirm Reject' }}
        </button>
      </div>
    </div>
  </div>
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
const busyId = ref(null)
const rejectCashFor = ref(null)
const rejectReason = ref('')

const paidCount        = computed(() => props.assignments.filter(a => a.status === 'paid').length)
const cashPendingCount = computed(() => props.assignments.filter(a => a.status === 'cash_pending').length)
const notifiedCount    = computed(() => props.assignments.filter(a => a.status === 'notified').length)
const declinedCount    = computed(() => props.assignments.filter(a => a.status === 'declined').length)
const totalCount       = computed(() => props.assignments.length)

const progressPercent = computed(() =>
  totalCount.value === 0 ? 0 : Math.round((paidCount.value / totalCount.value) * 100))

const statCards = computed(() => [
  { label: 'Assigned', icon: 'users', value: totalCount.value, hint: 'Total students',
    iconClass: 'bg-emerald-50 dark:bg-emerald-950/60 text-[#004d08] dark:text-[#86EFAC] border-emerald-100 dark:border-emerald-900/40' },
  { label: 'Paid', icon: 'check-circle', value: paidCount.value, hint: 'Collected',
    iconClass: 'bg-emerald-50 dark:bg-emerald-950/60 text-[#004d08] dark:text-[#86EFAC] border-emerald-100 dark:border-emerald-900/40',
    valueClass: 'text-[#004d08] dark:text-[#86EFAC]' },
  { label: 'Cash Incoming', icon: 'banknotes', value: cashPendingCount.value, hint: 'Awaiting confirmation',
    iconClass: 'bg-amber-50 dark:bg-amber-950/60 text-amber-700 dark:text-amber-300 border-amber-100 dark:border-amber-900/40',
    valueClass: 'text-amber-700 dark:text-amber-400' },
  { label: 'Notified', icon: 'envelope', value: notifiedCount.value + declinedCount.value, hint: 'Awaiting response',
    iconClass: 'bg-sky-50 dark:bg-sky-950/60 text-sky-700 dark:text-sky-300 border-sky-100 dark:border-sky-900/40',
    valueClass: 'text-sky-700 dark:text-sky-400' },
])

const statusFilters = computed(() => [
  { id: 'all',          label: 'All',          count: props.assignments.length },
  { id: 'paid',         label: 'Paid',         count: paidCount.value },
  { id: 'cash_pending', label: 'Cash',         count: cashPendingCount.value },
  { id: 'notified',     label: 'Notified',     count: notifiedCount.value },
  { id: 'declined',     label: 'Declined',     count: declinedCount.value },
].filter(f => f.id === 'all' || f.count > 0))

const filteredAssignments = computed(() =>
  statusFilter.value === 'all'
    ? props.assignments
    : props.assignments.filter(a => a.status === statusFilter.value))

async function notifyGuardian(a) {
  busyId.value = a.id
  try {
    await axios.post(route('teacher.contributions.notify-guardian', a.id))
    router.reload({ only: ['assignments'], preserveScroll: true })
  } catch (e) {
    showError(e.response?.data?.message || 'Could not send.')
  } finally {
    busyId.value = null
  }
}

async function markCashReceived(a) {
  if (!await confirmAction(`Mark ₱${formatMoney(a.amount_owed)} as received for ${a.student_name}?`)) return
  busyId.value = a.id
  try {
    await axios.post(route('teacher.contributions.mark-cash-received', a.id))
    router.reload({ only: ['assignments'], preserveScroll: true })
  } catch (e) {
    showError(e.response?.data?.message || 'Could not mark as paid.')
  } finally {
    busyId.value = null
  }
}

function openRejectCash(a) {
  rejectCashFor.value = a
  rejectReason.value = ''
}

async function rejectCash() {
  if (!rejectReason.value.trim()) {
    showError('Please provide a reason.')
    return
  }
  busyId.value = rejectCashFor.value.id
  try {
    await axios.post(route('teacher.contributions.reject-cash', rejectCashFor.value.id), {
      reason: rejectReason.value,
    })
    rejectCashFor.value = null
    router.reload({ only: ['assignments'], preserveScroll: true })
  } catch (e) {
    showError(e.response?.data?.message || 'Could not reject.')
  } finally {
    busyId.value = null
  }
}

function initialsOf(name) {
  if (!name) return '?'
  const parts = String(name).trim().split(/\s+/)
  if (parts.length >= 2) return (parts[0][0] + parts[parts.length - 1][0]).toUpperCase()
  return name.substring(0, 2).toUpperCase()
}

function formatMoney(v) { return Number(v || 0).toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) }
function formatDate(v) {
  if (!v) return '—'
  try { return new Date(v).toLocaleDateString('en-US', { month: 'short', day: 'numeric' }) }
  catch { return '—' }
}

function statusLabel(s) {
  return {
    pending:      'Pending',
    notified:     'Notified',
    paid:         'Paid',
    declined:     'Declined',
    cash_pending: 'Cash Incoming',
    overdue:      'Overdue',
    waived:       'Waived',
  }[s] || s
}

function statusIcon(s) {
  return {
    pending:      'clock',
    notified:     'envelope',
    paid:         'check-circle',
    declined:     'x-circle',
    cash_pending: 'banknotes',
    overdue:      'alert-triangle',
    waived:       'check-badge',
  }[s] || 'info'
}

function statusBadgeClass(s) {
  return {
    pending:      'bg-slate-100 dark:bg-[#232D26] text-slate-600 dark:text-slate-300 border-slate-200 dark:border-[#3F4F43]',
    notified:     'bg-sky-100 dark:bg-sky-950/60 text-sky-700 dark:text-sky-300 border-sky-200 dark:border-sky-900/40',
    paid:         'bg-emerald-100 dark:bg-emerald-950/60 text-[#004d08] dark:text-[#86EFAC] border-emerald-200 dark:border-emerald-900/40',
    declined:     'bg-rose-100 dark:bg-rose-950/60 text-rose-700 dark:text-rose-300 border-rose-200 dark:border-rose-900/40',
    cash_pending: 'bg-amber-100 dark:bg-amber-950/60 text-amber-800 dark:text-amber-300 border-amber-200 dark:border-amber-900/40',
    overdue:      'bg-rose-100 dark:bg-rose-950/60 text-rose-700 dark:text-rose-300 border-rose-200 dark:border-rose-900/40',
    waived:       'bg-slate-100 dark:bg-[#232D26] text-slate-600 dark:text-slate-300 border-slate-200 dark:border-[#3F4F43]',
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
@keyframes fadeInDown { from { opacity: 0; transform: translateY(-16px); } to { opacity: 1; transform: translateY(0); } }
@keyframes slideUpFade { from { opacity: 0; transform: translateY(24px); } to { opacity: 1; transform: translateY(0); } }
.not-visible { opacity: 0; }
.is-animated.anim-fade-down { animation: fadeInDown 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
.is-animated.anim-slide-up { animation: slideUpFade 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
</style>