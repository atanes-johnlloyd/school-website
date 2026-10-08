<template>
  <Modal :show="show" @close="closeModal" max-width="3xl">
    <div class="bg-white dark:bg-[#2D3A31] rounded-2xl border border-gray-200 dark:border-[#3F4F43] font-['Inter'] flex flex-col max-h-[88vh]">

      <!-- Header -->
      <div class="flex items-start justify-between gap-4 px-6 pt-5 pb-4 border-b border-gray-100 dark:border-[#3F4F43]">
        <div class="flex items-center gap-3 min-w-0">
          <button v-if="view === 'picker'" type="button" @click="view = 'applicants'"
                  class="p-1.5 -ml-1 rounded-lg text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-white/5 transition-colors cursor-pointer"
                  title="Back">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
            </svg>
          </button>
          <div class="w-10 h-10 rounded-2xl bg-emerald-50 dark:bg-[#1C261E] border border-emerald-100 dark:border-[#3F4F43] flex items-center justify-center text-[#004d08] dark:text-[#86EFAC] shrink-0">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
            </svg>
          </div>
          <div class="min-w-0">
            <h3 class="text-xs font-medium uppercase tracking-wider text-gray-900 dark:text-white truncate">
              {{ view === 'picker' ? 'Assign Applicants' : 'Manage Exam' }}
            </h3>
            <p class="text-[11px] text-gray-500 dark:text-gray-400 font-normal truncate">
              <template v-if="exam">
                {{ exam.exam_name }} &bull;
                <span class="font-mono">{{ exam.exam_date }}</span>
              </template>
              <template v-else>Loading…</template>
            </p>
          </div>
        </div>

        <div class="flex items-center gap-2 shrink-0">
          <span v-if="exam"
                class="px-2.5 py-1 rounded-full text-[10px] font-normal uppercase tracking-wider inline-flex items-center gap-1.5 border"
                :class="statusBadgeClass(exam.status)">
            <span class="w-1.5 h-1.5 rounded-full" :class="statusDotClass(exam.status)"></span>
            {{ exam.status }}
          </span>
          <button
            type="button"
            @click.stop="closeModal"
            class="p-1.5 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 rounded-xl hover:bg-gray-100 dark:hover:bg-white/5 transition-colors cursor-pointer"
          >
            <svg class="w-5 h-5 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>
      </div>

      <!-- Body -->
      <div class="flex-1 min-h-0 overflow-y-auto px-6 py-5 space-y-5">

        <div v-if="generalError" class="p-3 bg-red-50 dark:bg-red-950/40 border border-red-200 dark:border-red-800 rounded-xl text-red-700 dark:text-red-300 text-xs">
          {{ generalError }}
        </div>

        <div v-if="isLoading" class="py-16 text-center">
          <div class="inline-block w-6 h-6 border-2 border-[#004d08] dark:border-[#86EFAC] border-t-transparent rounded-full animate-spin"></div>
          <p class="text-[11px] text-gray-500 dark:text-gray-400 mt-2">Loading exam details…</p>
        </div>

        <!-- ═════════ APPLICANTS VIEW ═════════ -->
        <template v-else-if="exam && view === 'applicants'">

          <!-- Exam details card -->
          <section class="p-4 rounded-xl bg-gray-50 dark:bg-[#232D26] border border-gray-200/80 dark:border-[#3F4F43]">
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
              <div>
                <p class="text-[10px] font-medium uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-0.5">Date</p>
                <p class="text-xs text-gray-900 dark:text-white">{{ formatDate(exam.exam_date) }}</p>
              </div>
              <div>
                <p class="text-[10px] font-medium uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-0.5">Time</p>
                <p class="text-xs text-gray-900 dark:text-white">{{ exam.exam_time || '—' }}</p>
              </div>
              <div>
                <p class="text-[10px] font-medium uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-0.5">Venue</p>
                <p class="text-xs text-gray-900 dark:text-white">{{ exam.venue || '—' }}</p>
              </div>
              <div>
                <p class="text-[10px] font-medium uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-0.5">Grade Level</p>
                <p class="text-xs text-gray-900 dark:text-white">{{ exam.grade_level }}</p>
              </div>
              <div>
                <p class="text-[10px] font-medium uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-0.5">Track</p>
                <p class="text-xs text-gray-900 dark:text-white">{{ exam.track || 'All' }}</p>
              </div>
              <div>
                <p class="text-[10px] font-medium uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-0.5">School Year</p>
                <p class="text-xs text-gray-900 dark:text-white">{{ exam.school_year || '—' }}</p>
              </div>
              <div>
                <p class="text-[10px] font-medium uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-0.5">Capacity</p>
                <p class="text-xs text-gray-900 dark:text-white">
                  {{ exam.applicant_count }} / {{ exam.max_capacity }}
                </p>
              </div>
              <div>
                <p class="text-[10px] font-medium uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-0.5">Remaining</p>
                <p class="text-xs text-gray-900 dark:text-white">{{ exam.remaining }}</p>
              </div>
            </div>
          </section>

          <!-- Applicants header -->
          <section>
            <div class="flex items-center justify-between mb-2 gap-3 flex-wrap">
              <h4 class="text-[10px] font-semibold uppercase tracking-wider text-[#004d08] dark:text-[#86EFAC]">
                Assigned Applicants
                <span v-if="dirtyCount > 0"
                      class="ml-2 px-1.5 py-0.5 rounded-full text-[9px] bg-amber-100 dark:bg-amber-950/60 text-amber-700 dark:text-amber-300 border border-amber-200 dark:border-amber-800">
                  {{ dirtyCount }} unsaved
                </span>
              </h4>

              <div class="flex items-center gap-2">
                <button v-if="dirtyCount > 0" type="button" @click="saveAll"
                        :disabled="isBulkSaving"
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 text-[10px] font-medium uppercase tracking-wider text-white bg-[#004d08] hover:bg-emerald-900 rounded-lg transition-colors cursor-pointer disabled:opacity-50">
                  <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                  </svg>
                  {{ isBulkSaving ? 'Saving…' : `Save All (${dirtyCount})` }}
                </button>

                <button type="button" @click="openPicker"
                        :disabled="exam.status === 'Completed' || exam.status === 'Cancelled'"
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 text-[10px] font-medium uppercase tracking-wider text-[#004d08] dark:text-[#86EFAC] bg-emerald-50 hover:bg-emerald-100 dark:bg-emerald-950/50 border border-emerald-200 dark:border-emerald-800 rounded-lg transition-colors cursor-pointer disabled:opacity-40 disabled:cursor-not-allowed">
                  <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                  </svg>
                  Assign Applicants
                </button>
              </div>
            </div>

            <div v-if="!applicants.length" class="py-12 text-center text-gray-400 dark:text-gray-500">
              <p class="text-xs">No applicants assigned yet.</p>
              <p class="text-[11px] mt-0.5">Click "Assign Applicants" to add some.</p>
            </div>

            <div v-else class="overflow-hidden border border-gray-200/80 dark:border-[#3F4F43] rounded-xl">
              <table class="w-full text-left border-collapse">
                <thead>
                  <tr class="bg-gray-50/50 dark:bg-[#232D26]/50 text-[10px] font-medium uppercase text-gray-400 tracking-wider border-b border-gray-100 dark:border-[#3F4F43]">
                    <th class="py-2.5 px-3">Applicant</th>
                    <th class="py-2.5 px-3">Strand</th>
                    <th class="py-2.5 px-3 text-center">Score</th>
                    <th class="py-2.5 px-3 text-center">Result</th>
                    <th class="py-2.5 px-3 text-right">Actions</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-[#3F4F43] text-xs">
                  <tr v-for="a in applicants" :key="a.id" class="hover:bg-gray-50/80 dark:hover:bg-white/5 transition-colors">
                    <td class="py-2.5 px-3">
                      <p class="text-xs font-medium text-gray-900 dark:text-white truncate">{{ a.name }}</p>
                      <p class="text-[10px] text-gray-400 font-mono">{{ a.reference }}</p>
                    </td>

                    <td class="py-2.5 px-3 text-gray-600 dark:text-gray-300">{{ a.strand || '—' }}</td>

                    <!-- Score + non-score toggles -->
                    <td class="py-2.5 px-3">
                      <div class="flex flex-col items-center gap-1">
                        <input
                          v-model="a.draft_score"
                          type="number" min="0" max="100" step="0.01"
                          :disabled="resultLocked(a) || !canRecord || a.draft_absent || a.draft_interview"
                          class="w-20 px-2 py-1 text-xs text-center bg-white dark:bg-[#1C261E] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white rounded-lg focus:ring-2 focus:ring-[#004d08] focus:outline-none disabled:opacity-40 disabled:cursor-not-allowed"
                        />
                        <div class="flex items-center gap-2 text-[9px]">
                          <label class="flex items-center gap-1 cursor-pointer select-none text-gray-500 dark:text-gray-400"
                                :title="'Mark as absent — score will be cleared'">
                            <input type="checkbox" v-model="a.draft_absent"
                                  @change="if (a.draft_absent) { a.draft_score = ''; a.draft_interview = false }"
                                  :disabled="!canRecord || resultLocked(a)"
                                  class="w-3 h-3 accent-red-500 cursor-pointer" />
                            Absent
                          </label>
                          <label class="flex items-center gap-1 cursor-pointer select-none text-gray-500 dark:text-gray-400"
                                :title="'Mark for interview — score will be cleared'">
                            <input type="checkbox" v-model="a.draft_interview"
                                  @change="if (a.draft_interview) { a.draft_score = ''; a.draft_absent = false }"
                                  :disabled="!canRecord || resultLocked(a)"
                                  class="w-3 h-3 accent-sky-500 cursor-pointer" />
                            Interview
                          </label>
                        </div>
                      </div>
                    </td>

                    <!-- Computed result badge -->
                    <td class="py-2.5 px-3 text-center">
                      <span class="px-2 py-0.5 rounded-full text-[10px] font-normal uppercase tracking-wider border inline-flex items-center gap-1"
                            :class="resultBadgeClass(computedResult(a))">
                        {{ computedResult(a) }}
                      </span>
                    </td>

                    <td class="py-2.5 px-3 text-right">
                      <div class="flex items-center justify-end gap-1.5">
                        <button type="button" @click="saveResult(a)"
                                :disabled="!canRecord || a.saving || !isDirty(a) || resultLocked(a)"
                                class="px-2 py-1 text-[10px] font-medium uppercase tracking-wider text-white bg-[#004d08] hover:bg-emerald-900 rounded-md transition-colors cursor-pointer disabled:opacity-40 disabled:cursor-not-allowed">
                          {{ a.saving ? '…' : 'Save' }}
                        </button>
                        <button type="button" @click="removeApplicant(a)"
                                :disabled="a.saving || exam.status === 'Completed' || resultLocked(a)"
                                class="px-2 py-1 text-[10px] font-medium uppercase tracking-wider text-red-700 dark:text-red-400 bg-red-50 hover:bg-red-100 dark:bg-red-950/50 border border-red-200 dark:border-red-900 rounded-md transition-colors cursor-pointer disabled:opacity-40 disabled:cursor-not-allowed">
                          Remove
                        </button>
                      </div>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>

            <p v-if="!canRecord" class="mt-2 text-[10px] text-amber-600 dark:text-amber-400 italic">
              Score entry becomes available once the exam is Ongoing or Completed.
            </p>
          </section>
        </template>

        <!-- ═════════ PICKER VIEW ═════════ -->
        <template v-else-if="view === 'picker'">
          <section>
            <p class="text-xs text-gray-600 dark:text-gray-300 mb-3">
              Select approved applicants to add to <strong>{{ exam?.exam_name }}</strong>.
              Already-assigned applicants and those who have completed an exam are hidden.
            </p>

            <div v-if="!eligible.length" class="py-12 text-center text-gray-400 dark:text-gray-500">
              <p class="text-xs">No eligible applicants found.</p>
              <p class="text-[11px] mt-0.5">All approved applicants are already assigned or have taken an exam.</p>
            </div>

            <div v-else class="border border-gray-200/80 dark:border-[#3F4F43] rounded-xl overflow-hidden max-h-[50vh] overflow-y-auto">
              <label v-for="a in eligible" :key="a.id"
                     class="flex items-center gap-3 p-3 border-b border-gray-100 dark:border-[#3F4F43] last:border-b-0 hover:bg-gray-50/80 dark:hover:bg-white/5 transition-colors cursor-pointer">
                <input type="checkbox" :value="a.id" v-model="selectedApplicantIds"
                       class="w-4 h-4 text-[#004d08] border-gray-300 rounded focus:ring-[#004d08] cursor-pointer" />
                <div class="min-w-0 flex-1">
                  <p class="text-xs font-medium text-gray-900 dark:text-white truncate">{{ a.full_name }}</p>
                  <p class="text-[10px] text-gray-500 dark:text-gray-400 truncate">
                    <span class="font-mono">{{ a.reference_number }}</span> &bull;
                    Grade {{ a.desired_grade_level }} &bull; {{ a.strand || 'N/A' }}
                  </p>
                </div>
              </label>
            </div>

            <div v-if="eligible.length" class="mt-3 flex justify-end">
              <button type="button" @click="assignSelected"
                      :disabled="isAssigning || !selectedApplicantIds.length"
                      class="px-5 py-2 text-xs font-medium uppercase tracking-wider bg-[#004d08] text-white hover:bg-emerald-900 rounded-xl transition-all shadow-md active:scale-95 disabled:opacity-50 cursor-pointer">
                {{ isAssigning ? 'Assigning…' : `Assign Selected (${selectedApplicantIds.length})` }}
              </button>
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
      </div>
    </div>
  </Modal>
</template>

<script setup>
import { ref, computed, watch } from 'vue'
import axios from 'axios'
import Modal from '@/Components/Modal.vue'
import { useFlash } from '@/Composables/useFlash'
import { useConfirm } from '@/Composables/useConfirm'

const props = defineProps({
  show: { type: Boolean, default: false },
  exam: { type: Object,  default: null },
})

const emit = defineEmits(['close', 'changed'])
const flash = useFlash()
const confirm = useConfirm()
const view                = ref('applicants')
const exam                = ref(null)
const applicants          = ref([])
const eligible            = ref([])
const selectedApplicantIds = ref([])
const resultLocked = (a) => a.result === 'Passed' && !a.draft_unlocking
const isLoading    = ref(false)
const isAssigning  = ref(false)
const generalError = ref('')

const canRecord = computed(() =>
  exam.value && ['Ongoing', 'Completed'].includes(exam.value.status)
)

watch(() => props.show, async (open) => {
  if (open && props.exam?.id) {
    view.value = 'applicants'
    reset()
    await fetchDetails()
  }
})

const reset = () => {
  exam.value = null
  applicants.value = []
  eligible.value = []
  selectedApplicantIds.value = []
  generalError.value = ''
  isLoading.value = false
}

const fetchDetails = async () => {
  isLoading.value = true
  try {
    const { data } = await axios.get(`/admin/entrance-exams/${props.exam.id}`)
    exam.value         = data.exam
    passingGrade.value = data.passing_grade ?? 75

    applicants.value = (data.applicants || []).map(a => ({
      ...a,
      draft_score:     a.score ?? '',
      draft_absent:    a.result === 'Absent',
      draft_interview: a.result === 'For Interview',
      draft_remarks:   a.remarks ?? '',
      saving: false,
    }))
  } catch (e) {
    generalError.value = e.response?.data?.message || 'Failed to load exam details.'
    flash.error(generalError.value)
  } finally {
    isLoading.value = false
  }
}

const isDirty = (a) => {
  const origScore  = a.score ?? ''
  const origResult = a.result || 'Pending'
  return String(a.draft_score ?? '') !== String(origScore) || computedResult(a) !== origResult
}

const saveResult = async (a) => {
  a.saving = true
  generalError.value = ''
  try {
    const result = computedResult(a)
    const { data } = await axios.put(`/admin/exam-results/${a.id}`, {
      score:   a.draft_score === '' ? null : Number(a.draft_score),
      result,
      remarks: a.draft_remarks || null,
    })

    a.score  = a.draft_score
    a.result = result

    emit('changed')

    if (data.conversion?.message) {
      flash.success(`Saved. ${data.conversion.message}`)
    } else {
      flash.success(`${a.name} — saved as ${result}.`)
    }
  } catch (e) {
    generalError.value = e.response?.data?.message || 'Failed to save result.'
    flash.error(generalError.value)
  } finally {
    a.saving = false
  }
}

const saveAll = async () => {
  const dirty = applicants.value.filter(a => isDirty(a))
  if (!dirty.length) return

  const passing = dirty.filter(a => computedResult(a) === 'Passed').length
  const failing = dirty.filter(a => computedResult(a) === 'Failed').length
  const absent  = dirty.filter(a => computedResult(a) === 'Absent').length

  const details = []
  if (passing > 0) details.push(`${passing} applicant${passing === 1 ? '' : 's'} will be converted to student accounts and emailed their exam pass.`)
  if (failing > 0) details.push(`${failing} applicant${failing === 1 ? '' : 's'} will receive a "Failed" notification.`)
  if (absent  > 0) details.push(`${absent} applicant${absent === 1 ? '' : 's'} will receive an absence notice.`)

  const { confirmed } = await confirm({
    title: 'Save Exam Results',
    message: `Save ${dirty.length} result${dirty.length === 1 ? '' : 's'}?`,
    details,
    confirmLabel: `Save All (${dirty.length})`,
    variant: passing > 0 ? 'info' : 'warning',
  })
  if (!confirmed) return

  isBulkSaving.value = true
  generalError.value = ''

  const payload = {
    results: dirty.map(a => ({
      id:      a.id,
      score:   a.draft_score === '' ? null : Number(a.draft_score),
      result:  computedResult(a),
      remarks: a.draft_remarks || null,
    })),
  }

  try {
    const { data } = await axios.put(`/admin/entrance-exams/${props.exam.id}/results/bulk`, payload)
    await fetchDetails()
    emit('changed')
    flash.success(data.message || `Saved ${dirty.length} result${dirty.length === 1 ? '' : 's'}.`)
    if (data.errors?.length) {
      flash.info(`${data.errors.length} row(s) skipped — see details below.`)
      // Optional: surface data.errors as a visible list in the modal.
    }
    emit('changed')
    flash.success(data.message || `Saved ${dirty.length} result${dirty.length === 1 ? '' : 's'}.`)
    if (data.errors?.length) {
      flash.info(`${data.errors.length} row(s) reported issues — check the log.`)
    }
  } catch (e) {
    generalError.value = e.response?.data?.message || 'Bulk save failed.'
    flash.error(generalError.value)
  } finally {
    isBulkSaving.value = false
  }
}

const removeApplicant = async (a) => {
  const { confirmed, reason } = await confirm({
    title: 'Remove Applicant',
    message: `Remove ${a.name} from "${exam.value?.exam_name}"?`,
    details: [
      'They will no longer be scheduled for this exam session.',
      'They can be reassigned to another exam later.',
    ],
    confirmLabel: 'Remove',
    variant: 'danger',
    requireReason: true,
    reasonLabel: 'Reason (optional)',
    reasonPlaceholder: 'e.g. transferred to a different session',
    reasonRequired: false,
  })
  if (!confirmed) return

  a.saving = true
  generalError.value = ''
  try {
    await axios.post(`/admin/entrance-exams/${exam.value.id}/remove-applicant`, {
      applicant_id: a.applicant_id,
      reason: reason || null,
    })
    applicants.value = applicants.value.filter(x => x.id !== a.id)
    emit('changed')
    flash.success(`${a.name} removed.`)
  } catch (e) {
    generalError.value = e.response?.data?.message || 'Failed to remove applicant.'
    flash.error(generalError.value)
  } finally {
    a.saving = false
  }
}

const openPicker = async () => {
  view.value = 'picker'
  selectedApplicantIds.value = []
  eligible.value = []
  generalError.value = ''
  try {
    const { data } = await axios.get(`/admin/entrance-exams/${exam.value.id}/eligible-applicants`)
    eligible.value = data.applicants || []
  } catch (e) {
    generalError.value = e.response?.data?.message || 'Failed to load eligible applicants.'
    flash.error(generalError.value)
  }
}

const assignSelected = async () => {
  isAssigning.value = true
  generalError.value = ''
  try {
    const { data } = await axios.post(
      `/admin/entrance-exams/${exam.value.id}/assign`,
      { applicant_ids: selectedApplicantIds.value }
    )

    await fetchDetails()
    view.value = 'applicants'
    selectedApplicantIds.value = []
    emit('changed')
    flash.success(data.message || 'Applicants assigned.')
  } catch (e) {
    generalError.value = e.response?.data?.message || 'Failed to assign applicants.'
    flash.error(generalError.value)
  } finally {
    isAssigning.value = false
  }
}

const formatDate = (iso) => {
  if (!iso) return '—'
  try { return new Date(iso).toLocaleDateString('en-PH', { year: 'numeric', month: 'long', day: 'numeric' }) }
  catch { return iso }
}

const statusBadgeClass = (status) => ({
  Upcoming:  'bg-sky-50 dark:bg-sky-950/50 text-sky-700 dark:text-sky-300 border-sky-200 dark:border-sky-800',
  Ongoing:   'bg-blue-50 dark:bg-blue-950/50 text-blue-700 dark:text-blue-300 border-blue-200 dark:border-blue-800',
  Completed: 'bg-emerald-100 dark:bg-emerald-950/60 text-emerald-800 dark:text-emerald-300 border-emerald-200 dark:border-emerald-800',
  Cancelled: 'bg-gray-100 dark:bg-[#232D26] text-gray-600 dark:text-gray-400 border-gray-200 dark:border-[#3F4F43]',
}[status] || 'bg-gray-100 dark:bg-[#232D26] text-gray-600 dark:text-gray-400 border-gray-200 dark:border-[#3F4F43]')

const statusDotClass = (status) => ({
  Upcoming: 'bg-sky-500', Ongoing: 'bg-blue-500', Completed: 'bg-emerald-500', Cancelled: 'bg-gray-400',
}[status] || 'bg-gray-400')

const closeModal = async () => {
  if (dirtyCount.value > 0) {
    const { confirmed } = await confirm({
      title: 'Unsaved Changes',
      message: `You have ${dirtyCount.value} unsaved change${dirtyCount.value === 1 ? '' : 's'}.`,
      details: ['If you close now, your edits will be discarded.'],
      confirmLabel: 'Discard & Close',
      cancelLabel: 'Keep Editing',
      variant: 'warning',
    })
    if (!confirmed) return
  }
  reset()
  emit('close')
}
const passingGrade = ref(75)      // overwritten by show() response
const isBulkSaving = ref(false)

const dirtyCount = computed(() =>
  applicants.value.filter(a => isDirty(a)).length
)

// Score → Passed/Failed. Absent / Interview toggles override.
const computedResult = (a) => {
  if (a.draft_absent)    return 'Absent'
  if (a.draft_interview) return 'For Interview'

  const raw = a.draft_score
  if (raw === '' || raw === null || raw === undefined) return 'Pending'

  const n = Number(raw)
  if (Number.isNaN(n)) return 'Pending'
  return n >= passingGrade.value ? 'Passed' : 'Failed'
}

const resultBadgeClass = (result) => ({
  Passed:          'bg-emerald-100 dark:bg-emerald-950/60 text-emerald-800 dark:text-emerald-300 border-emerald-200 dark:border-emerald-800',
  Failed:          'bg-red-50 dark:bg-red-950/50 text-red-700 dark:text-red-300 border-red-200 dark:border-red-800',
  Absent:          'bg-gray-100 dark:bg-[#232D26] text-gray-600 dark:text-gray-400 border-gray-200 dark:border-[#3F4F43]',
  'For Interview': 'bg-sky-50 dark:bg-sky-950/50 text-sky-700 dark:text-sky-300 border-sky-200 dark:border-sky-800',
  Pending:         'bg-amber-50 dark:bg-amber-950/50 text-amber-700 dark:text-amber-300 border-amber-200 dark:border-amber-800',
}[result] || 'bg-gray-100 dark:bg-[#232D26] text-gray-600 dark:text-gray-400 border-gray-200 dark:border-[#3F4F43]')
</script>