<template>
  <Modal :show="show" @close="closeModal" max-width="3xl">
    <div class="bg-white dark:bg-[#2D3A31] rounded-2xl border border-gray-200 dark:border-[#3F4F43] font-['Inter'] flex flex-col max-h-[88vh]">

      <!-- Header -->
      <div class="flex items-start justify-between gap-4 px-6 pt-5 pb-4 border-b border-gray-100 dark:border-[#3F4F43]">
        <div class="flex items-center gap-3 min-w-0">
          <div class="w-10 h-10 rounded-2xl bg-emerald-50 dark:bg-[#1C261E] border border-emerald-100 dark:border-[#3F4F43] flex items-center justify-center text-[#004d08] dark:text-[#86EFAC] shrink-0">
            <Icon icon="credit-card" size="sm" />
          </div>
          <div class="min-w-0">
            <h3 class="text-xs font-medium uppercase tracking-wider text-gray-900 dark:text-white truncate">Contribution Detail</h3>
            <p class="text-[11px] text-gray-500 dark:text-gray-400 font-normal truncate">
              {{ contribution?.title || 'Loading…' }}
            </p>
          </div>
        </div>
        <button type="button" @click="closeModal"
          class="p-1.5 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 rounded-xl hover:bg-gray-100 dark:hover:bg-white/5 transition-colors cursor-pointer shrink-0">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>

      <!-- Body -->
      <div class="flex-1 min-h-0 overflow-y-auto px-6 py-5 space-y-5">
        <div v-if="generalError"
          class="p-3 bg-red-50 dark:bg-red-950/40 border border-red-200 dark:border-red-800 rounded-xl text-red-700 dark:text-red-300 text-xs">
          {{ generalError }}
        </div>

        <div v-if="isLoading && !contribution" class="py-16 text-center">
          <div class="inline-block w-6 h-6 border-2 border-[#004d08] dark:border-[#86EFAC] border-t-transparent rounded-full animate-spin"></div>
          <p class="text-[11px] text-gray-500 dark:text-gray-400 mt-2">Loading…</p>
        </div>

        <template v-else-if="contribution">

          <!-- Info strip -->
          <section class="p-4 rounded-xl bg-gray-50 dark:bg-[#232D26] border border-gray-200/80 dark:border-[#3F4F43]">
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
              <div>
                <p class="text-[10px] font-medium uppercase tracking-wider text-gray-400 mb-0.5">Amount</p>
                <p class="text-xs text-gray-900 dark:text-white font-medium">
                  {{ contribution.amount_type === 'fixed'
                    ? '₱' + formatMoney(contribution.amount)
                    : 'Open • ₱' + formatMoney(contribution.min_amount) + '+' }}
                </p>
              </div>
              <div>
                <p class="text-[10px] font-medium uppercase tracking-wider text-gray-400 mb-0.5">Deadline</p>
                <p class="text-xs font-medium"
                  :class="isPast(contribution.deadline_at) ? 'text-red-600 dark:text-red-400' : 'text-gray-900 dark:text-white'">
                  {{ formatShortDate(contribution.deadline_at) }}
                </p>
              </div>
              <div>
                <p class="text-[10px] font-medium uppercase tracking-wider text-gray-400 mb-0.5">Guardian Consent</p>
                <p class="text-xs font-medium"
                  :class="contribution.requires_guardian_consent ? 'text-emerald-700 dark:text-emerald-400' : 'text-gray-500'">
                  {{ contribution.requires_guardian_consent ? 'Required' : 'Not required' }}
                </p>
              </div>
              <div>
                <p class="text-[10px] font-medium uppercase tracking-wider text-gray-400 mb-0.5">Created by</p>
                <p class="text-xs font-medium text-gray-900 dark:text-white truncate">{{ contribution.creator || '—' }}</p>
              </div>
            </div>
          </section>

          <!-- Progress -->
          <section class="p-4 rounded-xl bg-gray-50 dark:bg-[#232D26] border border-gray-200/80 dark:border-[#3F4F43] space-y-3">
            <div class="flex items-center justify-between">
              <span class="text-[10px] font-medium uppercase tracking-wider text-gray-500">Collection Progress</span>
              <span class="text-lg font-black text-[#004d08] dark:text-[#86EFAC]">{{ progressPercent }}%</span>
            </div>
            <div class="h-2 rounded-full bg-gray-200 dark:bg-[#1C261E] overflow-hidden">
              <div class="h-full rounded-full bg-emerald-500 transition-all duration-700"
                :style="{ width: progressPercent + '%' }"></div>
            </div>
            <div class="grid grid-cols-4 gap-2">
              <div class="p-2 rounded-lg bg-emerald-50/70 dark:bg-emerald-950/40 border border-emerald-200/60 dark:border-emerald-900/40 text-center">
                <span class="text-[9px] font-medium uppercase text-emerald-700 dark:text-emerald-300 block">Paid</span>
                <span class="text-sm font-black text-[#004d08] dark:text-[#86EFAC]">{{ paidCount }}</span>
              </div>
              <div class="p-2 rounded-lg bg-amber-50/70 dark:bg-amber-950/40 border border-amber-200/60 dark:border-amber-900/40 text-center">
                <span class="text-[9px] font-medium uppercase text-amber-700 dark:text-amber-300 block">Cash</span>
                <span class="text-sm font-black text-amber-700 dark:text-amber-400">{{ cashPendingCount }}</span>
              </div>
              <div class="p-2 rounded-lg bg-sky-50/70 dark:bg-sky-950/40 border border-sky-200/60 dark:border-sky-900/40 text-center">
                <span class="text-[9px] font-medium uppercase text-sky-700 dark:text-sky-300 block">Notified</span>
                <span class="text-sm font-black text-sky-700 dark:text-sky-400">{{ notifiedCount }}</span>
              </div>
              <div class="p-2 rounded-lg bg-rose-50/70 dark:bg-rose-950/40 border border-rose-200/60 dark:border-rose-900/40 text-center">
                <span class="text-[9px] font-medium uppercase text-rose-700 dark:text-rose-300 block">Declined</span>
                <span class="text-sm font-black text-rose-700 dark:text-rose-400">{{ declinedCount }}</span>
              </div>
            </div>
          </section>

          <!-- Roster -->
          <section>
            <div class="flex items-center justify-between mb-2 gap-2 flex-wrap">
              <h4 class="text-[10px] font-semibold uppercase tracking-wider text-[#004d08] dark:text-[#86EFAC]">
                Student Roster ({{ assignments.length }})
              </h4>
              <div class="flex items-center gap-1.5 overflow-x-auto no-scrollbar">
                <button v-for="f in statusFilters" :key="f.id" @click="statusFilter = f.id"
                  :class="[
                    'text-[10px] font-medium px-2.5 py-1 rounded-lg transition-colors shrink-0 cursor-pointer',
                    statusFilter === f.id
                      ? 'bg-[#004d08] dark:bg-[#86EFAC] text-white dark:text-[#232D26]'
                      : 'bg-gray-50 dark:bg-[#232D26] text-gray-600 dark:text-gray-300 border border-gray-200 dark:border-[#3F4F43]'
                  ]">
                  {{ f.label }} ({{ f.count }})
                </button>
              </div>
            </div>

            <div v-if="isLoading" class="py-10 text-center">
              <div class="inline-block w-5 h-5 border-2 border-[#004d08] dark:border-[#86EFAC] border-t-transparent rounded-full animate-spin"></div>
              <p class="text-[11px] text-gray-500 dark:text-gray-400 mt-2">Loading roster…</p>
            </div>

            <div v-else-if="!filteredAssignments.length" class="py-10 text-center text-gray-400 dark:text-gray-500">
              <p class="text-xs">
                {{ assignments.length === 0 ? 'No students assigned yet.' : 'No students match this filter.' }}
              </p>
            </div>

            <div v-else class="border border-gray-200/80 dark:border-[#3F4F43] rounded-xl overflow-hidden">
              <table class="w-full text-left border-collapse min-w-[700px]">
                <thead>
                  <tr class="bg-gray-50/50 dark:bg-[#232D26]/50 text-[10px] font-medium uppercase text-gray-400 tracking-wider border-b border-gray-100 dark:border-[#3F4F43]">
                    <th class="py-2.5 px-4">Student</th>
                    <th class="py-2.5 px-4">Guardian</th>
                    <th class="py-2.5 px-4 text-center">Amount</th>
                    <th class="py-2.5 px-4 text-center">Status</th>
                    <th class="py-2.5 px-4 text-right">Action</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-[#3F4F43] text-xs">
                  <tr v-for="a in filteredAssignments" :key="a.id"
                    class="hover:bg-gray-50/80 dark:hover:bg-white/5 transition-colors">
                    <td class="py-2.5 px-4">
                      <div class="flex items-center gap-2">
                        <span class="w-6 h-6 rounded-full bg-emerald-100 dark:bg-[#1C261E] text-[#004d08] dark:text-[#86EFAC] font-bold text-[10px] flex items-center justify-center shrink-0">
                          {{ initialsOf(a.student_name) }}
                        </span>
                        <div class="min-w-0">
                          <p class="font-medium text-gray-900 dark:text-white text-xs truncate">{{ a.student_name }}</p>
                          <p class="text-[10px] font-mono text-gray-400 truncate">LRN: {{ a.lrn }}</p>
                        </div>
                      </div>
                    </td>
                    <td class="py-2.5 px-4">
                      <div v-if="a.guardian_name" class="min-w-0">
                        <p class="text-[11px] font-medium text-gray-700 dark:text-gray-200 truncate">{{ a.guardian_name }}</p>
                        <p class="text-[10px] text-gray-500 truncate">{{ a.guardian_email || '—' }}</p>
                      </div>
                      <span v-else class="text-[10px] text-gray-400 italic">No guardian</span>
                    </td>
                    <td class="py-2.5 px-4 text-center font-bold text-gray-900 dark:text-white">
                      ₱{{ formatMoney(a.amount_owed) }}
                    </td>
                    <td class="py-2.5 px-4 text-center">
                      <span :class="statusBadgeClass(a.status)"
                        class="text-[10px] font-medium uppercase px-2 py-0.5 rounded-md border whitespace-nowrap">
                        {{ statusLabel(a.status) }}
                      </span>
                    </td>
                    <td class="py-2.5 px-4 text-right">
                      <!-- Resend: pending / notified / declined / overdue -->
                      <button v-if="['pending', 'notified', 'declined', 'overdue'].includes(a.status)"
                        @click="resendConsent(a)"
                        :disabled="resendingId === a.id"
                        class="px-2 py-1 text-[10px] font-normal text-emerald-700 dark:text-emerald-300 bg-emerald-50 hover:bg-emerald-100 dark:bg-emerald-950/50 rounded-md border border-emerald-200 dark:border-emerald-800 transition-colors cursor-pointer disabled:opacity-50 inline-flex items-center gap-1">
                        <Icon icon="envelope" size="xs" />
                        {{ resendingId === a.id ? '…' : 'Resend' }}
                      </button>

                      <!-- Confirm cash received: cash_pending -->
                      <button v-else-if="a.status === 'cash_pending'"
                        @click="markCashReceived(a)"
                        :disabled="busyId === a.id"
                        class="px-2 py-1 text-[10px] font-normal text-amber-700 dark:text-amber-300 bg-amber-50 hover:bg-amber-100 dark:bg-amber-950/50 rounded-md border border-amber-200 dark:border-amber-800 transition-colors cursor-pointer disabled:opacity-50 inline-flex items-center gap-1">
                        <Icon icon="banknotes" size="xs" />
                        {{ busyId === a.id ? '…' : 'Confirm Cash' }}
                      </button>

                      <!-- Override: everything else not paid -->
                      <button v-else-if="a.status !== 'paid'"
                        @click="openOverride(a)"
                        class="px-2 py-1 text-[10px] font-normal text-violet-700 dark:text-violet-300 bg-violet-50 hover:bg-violet-100 dark:bg-violet-950/50 rounded-md border border-violet-200 dark:border-violet-800 transition-colors cursor-pointer inline-flex items-center gap-1">
                        <Icon icon="shield-check" size="xs" /> Override
                      </button>

                      <span v-else class="text-[10px] text-[#004d08] dark:text-[#86EFAC] font-medium inline-flex items-center gap-1">
                        <Icon icon="check-circle" size="xs" /> Paid
                      </span>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </section>
        </template>
      </div>

      <!-- Footer -->
      <div class="px-6 py-4 border-t border-gray-100 dark:border-[#3F4F43] flex justify-end gap-2">
        <button type="button" @click="closeModal"
          class="px-4 py-2 text-xs font-normal text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-white/5 rounded-xl transition-all cursor-pointer">
          Close
        </button>
        <button v-if="contribution" type="button" @click="$emit('edit', contribution)"
          class="px-5 py-2 text-xs font-medium uppercase tracking-wider bg-[#004d08] hover:bg-emerald-900 text-white rounded-xl transition-all shadow-md active:scale-95 cursor-pointer">
          Edit Contribution
        </button>
      </div>
    </div>

    <!-- Override Modal (nested) -->
    <div v-if="overrideFor"
      class="fixed inset-0 z-[60] bg-slate-950/60 backdrop-blur-sm flex items-center justify-center p-4"
      @click.self="overrideFor = null">
      <div class="bg-white dark:bg-[#2D3A31] rounded-2xl border border-gray-200 dark:border-[#3F4F43] shadow-2xl w-full max-w-md p-6 space-y-4">
        <div class="flex items-start gap-3">
          <div class="w-10 h-10 rounded-2xl bg-violet-100 dark:bg-violet-950/40 text-violet-600 dark:text-violet-400 flex items-center justify-center shrink-0">
            <Icon icon="shield-check" size="sm" />
          </div>
          <div>
            <h3 class="text-xs font-medium uppercase tracking-wider text-gray-900 dark:text-white">Admin Override</h3>
            <p class="text-[11px] text-gray-500 dark:text-gray-400 mt-1 font-normal">
              Force-mark <strong class="text-gray-900 dark:text-white">{{ overrideFor.student_name }}</strong>'s contribution as paid. This bypasses the normal guardian flow.
            </p>
          </div>
        </div>
        <div class="space-y-1.5">
          <label class="block text-[11px] font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">Reason (for audit log) *</label>
          <textarea v-model="overrideReason" rows="3"
            placeholder="e.g. Parent paid at registrar's office on Oct 6"
            class="w-full px-3.5 py-2.5 text-xs bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white rounded-xl focus:ring-2 focus:ring-[#004d08] focus:outline-none font-normal resize-none"></textarea>
        </div>
        <div class="flex items-center justify-end gap-2 pt-1">
          <button @click="overrideFor = null"
            class="px-4 py-2 text-xs font-normal text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-white/5 rounded-xl transition-all cursor-pointer">
            Cancel
          </button>
          <button @click="overrideConfirm" :disabled="busyId === overrideFor.id || !overrideReason.trim()"
            class="px-5 py-2 text-xs font-medium uppercase tracking-wider bg-violet-600 hover:bg-violet-700 text-white rounded-xl transition-all shadow-md active:scale-95 disabled:opacity-50 cursor-pointer">
            {{ busyId === overrideFor.id ? 'Processing…' : 'Confirm Override' }}
          </button>
        </div>
      </div>
    </div>
  </Modal>
</template>

<script setup>
import { useFlash } from '@/Composables/useFlash'
import { ref, computed, watch } from 'vue'
import axios from 'axios'
import Modal from '@/Components/Modal.vue'
import Icon from '@/Components/Icon.vue'

const props = defineProps({
  show:         { type: Boolean, default: false },
  contribution: { type: Object,  default: null },
})

const emit = defineEmits(['close', 'edit', 'changed'])

const flash = useFlash()
const contribution = ref(null)
const assignments  = ref([])
const isLoading    = ref(false)
const generalError = ref('')
const statusFilter = ref('all')
const resendingId  = ref(null)
const busyId       = ref(null)

const overrideFor    = ref(null)
const overrideReason = ref('')

/* ─── Fetch ─────────────────────────────────────────────── */

watch(() => props.show, async (open) => {
  if (open && props.contribution?.id) {
    // Seed instantly with row data so we don't flash an empty modal
    contribution.value = { ...props.contribution }
    assignments.value  = []
    generalError.value = ''
    statusFilter.value = 'all'
    overrideFor.value  = null
    overrideReason.value = ''
    await fetchDetails()
  }
})

async function fetchDetails() {
  isLoading.value = true
  generalError.value = ''
  try {
    const { data } = await axios.get(
      `/admin/contributions/${props.contribution.id}/detail`,
      { headers: { Accept: 'application/json' } }
    )
    contribution.value = data.contribution
    assignments.value  = data.assignments || []
  } catch (e) {
    generalError.value = e.response?.data?.message
      || 'Could not load full details. Showing summary only.'
  } finally {
    isLoading.value = false
  }
}

/* ─── Computed stats ────────────────────────────────────── */

const paidCount        = computed(() => assignments.value.filter(a => a.status === 'paid').length)
const cashPendingCount = computed(() => assignments.value.filter(a => a.status === 'cash_pending').length)
const notifiedCount    = computed(() => assignments.value.filter(a => a.status === 'notified').length)
const declinedCount    = computed(() => assignments.value.filter(a => a.status === 'declined').length)
const totalCount       = computed(() => assignments.value.length)

const progressPercent = computed(() =>
  totalCount.value === 0 ? 0 : Math.round((paidCount.value / totalCount.value) * 100)
)

const statusFilters = computed(() => [
  { id: 'all',          label: 'All',      count: assignments.value.length },
  { id: 'paid',         label: 'Paid',     count: paidCount.value },
  { id: 'cash_pending', label: 'Cash',     count: cashPendingCount.value },
  { id: 'notified',     label: 'Notified', count: notifiedCount.value },
  { id: 'declined',     label: 'Declined', count: declinedCount.value },
].filter(f => f.id === 'all' || f.count > 0))

const filteredAssignments = computed(() =>
  statusFilter.value === 'all'
    ? assignments.value
    : assignments.value.filter(a => a.status === statusFilter.value)
)

/* ─── Actions ───────────────────────────────────────────── */

async function resendConsent(a) {
  resendingId.value = a.id
  try {
    await axios.post(route('admin.contributions.notify-guardian', a.id))
    flash.success(`Consent request sent to ${a.guardian_name || a.student_name}.`)
    await fetchDetails()
    emit('changed')
  } catch (e) {
    flash.error(e.response?.data?.message || 'Could not resend.')
  } finally {
    resendingId.value = null
  }
}

async function markCashReceived(a) {
  busyId.value = a.id
  try {
    await axios.post(route('admin.contributions.mark-cash-received', a.id))
    flash.success(`${a.student_name}'s cash payment confirmed.`)
    await fetchDetails()
    emit('changed')
  } catch (e) {
    flash.error(e.response?.data?.message || 'Could not mark as received.')
  } finally {
    busyId.value = null
  }
}

function openOverride(a) {
  overrideFor.value = a
  overrideReason.value = ''
}

async function overrideConfirm() {
  if (!overrideReason.value.trim()) return
  const student = overrideFor.value.student_name
  busyId.value = overrideFor.value.id
  try {
    await axios.post(
      route('admin.contributions.override-authorize', overrideFor.value.id),
      { reason: overrideReason.value }
    )
    flash.success(`${student} marked as paid (override).`)
    overrideFor.value = null
    await fetchDetails()
    emit('changed')
  } catch (e) {
    flash.error(e.response?.data?.message || 'Could not override.')
  } finally {
    busyId.value = null
  }
}

/* ─── Helpers ───────────────────────────────────────────── */

function initialsOf(name) {
  if (!name) return '?'
  const parts = String(name).trim().split(/\s+/)
  if (parts.length >= 2) return (parts[0][0] + parts[parts.length - 1][0]).toUpperCase()
  return name.substring(0, 2).toUpperCase()
}

function formatMoney(v) {
  return Number(v || 0).toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 })
}

function formatShortDate(v) {
  if (!v) return '—'
  try { return new Date(v).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' }) }
  catch { return '—' }
}

function isPast(v) { return v && new Date(v) < new Date() }

function statusLabel(s) {
  return {
    pending:      'Pending',
    notified:     'Notified',
    cash_pending: 'Cash Pending',
    overdue:      'Overdue',
    paid:         'Paid',
    declined:     'Declined',
  }[s] || s
}

function statusBadgeClass(s) {
  return {
    pending:      'bg-gray-100 dark:bg-[#232D26] text-gray-600 dark:text-gray-300 border-gray-200 dark:border-[#3F4F43]',
    notified:     'bg-sky-100 dark:bg-sky-950/60 text-sky-700 dark:text-sky-300 border-sky-200 dark:border-sky-800',
    cash_pending: 'bg-amber-100 dark:bg-amber-950/60 text-amber-800 dark:text-amber-300 border-amber-200 dark:border-amber-800',
    overdue:      'bg-rose-100 dark:bg-rose-950/60 text-rose-700 dark:text-rose-300 border-rose-200 dark:border-rose-800',
    paid:         'bg-emerald-100 dark:bg-emerald-950/60 text-[#004d08] dark:text-[#86EFAC] border-emerald-200 dark:border-emerald-800',
    declined:     'bg-gray-200 dark:bg-[#3F4F43] text-gray-700 dark:text-gray-300 border-gray-300 dark:border-[#4F5F53]',
  }[s] || 'bg-gray-100 text-gray-600 border-gray-200'
}

function closeModal() {
  contribution.value = null
  assignments.value = []
  generalError.value = ''
  statusFilter.value = 'all'
  overrideFor.value = null
  overrideReason.value = ''
  emit('close')
}
</script>

<style scoped>
.no-scrollbar::-webkit-scrollbar { display: none; }
.no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>