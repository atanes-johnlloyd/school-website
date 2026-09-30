<template>
  <Modal :show="show" @close="closeModal" max-width="2xl">
    <div class="bg-white dark:bg-[#2D3A31] rounded-2xl border border-gray-200 dark:border-[#3F4F43] font-['Inter'] flex flex-col max-h-[85vh]">

      <!-- Header -->
      <div class="flex items-start justify-between gap-4 px-6 pt-5 pb-4 border-b border-gray-100 dark:border-[#3F4F43]">
        <div class="flex items-center gap-3 min-w-0">
          <div class="w-10 h-10 rounded-2xl bg-emerald-50 dark:bg-[#1C261E] border border-emerald-100 dark:border-[#3F4F43] flex items-center justify-center text-[#004d08] dark:text-[#86EFAC] shrink-0">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
          </div>
          <div class="min-w-0">
            <h3 class="text-xs font-medium uppercase tracking-wider text-gray-900 dark:text-white truncate">
              Student Profile
            </h3>
            <p class="text-[11px] text-gray-500 dark:text-gray-400 font-normal truncate">
              <template v-if="student">
                {{ student.user.name }} &bull;
                <span class="font-mono">{{ student.lrn }}</span>
              </template>
              <template v-else>Loading student details…</template>
            </p>
          </div>
        </div>

        <div class="flex items-center gap-2 shrink-0">
          <span
            v-if="student"
            class="px-2.5 py-1 rounded-full text-[10px] font-normal uppercase tracking-wider inline-flex items-center gap-1.5 border"
            :class="getStatusBadgeClass(student.status)"
          >
            <span class="w-1.5 h-1.5 rounded-full" :class="getStatusDotClass(student.status)"></span>
            {{ student.status.replace('_', ' ') }}
          </span>

          <button type="button" @click="closeModal"
                  class="p-1.5 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 rounded-xl hover:bg-gray-100 dark:hover:bg-white/5 transition-colors cursor-pointer">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>
      </div>

      <!-- Scrollable content -->
      <div class="flex-1 min-h-0 overflow-y-auto px-6 py-5 space-y-5">

        <div v-if="generalError" class="p-3 bg-red-50 dark:bg-red-950/40 border border-red-200 dark:border-red-800 rounded-xl text-red-700 dark:text-red-300 text-xs">
          {{ generalError }}
        </div>

        <div v-if="isLoading" class="py-16 text-center">
          <div class="inline-block w-6 h-6 border-2 border-[#004d08] dark:border-[#86EFAC] border-t-transparent rounded-full animate-spin"></div>
          <p class="text-[11px] text-gray-500 dark:text-gray-400 mt-2">Loading student profile…</p>
        </div>

        <div v-else-if="student" class="space-y-5">

          <!-- Personal Information -->
          <section>
            <h4 class="text-[10px] font-semibold uppercase tracking-wider text-[#004d08] dark:text-[#86EFAC] mb-2">Personal Information</h4>
            <div class="grid grid-cols-2 gap-3">
              <div v-for="f in personalFields" :key="f.label" :class="f.span || ''">
                <p class="text-[10px] font-medium uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-0.5">{{ f.label }}</p>
                <p class="text-xs text-gray-900 dark:text-white font-normal break-words"
                   :class="[f.mono ? 'font-mono' : '', f.capitalize ? 'capitalize' : '']">{{ f.value }}</p>
              </div>
            </div>
          </section>

          <!-- Address -->
          <section class="pt-3 border-t border-gray-100 dark:border-[#3F4F43]">
            <h4 class="text-[10px] font-semibold uppercase tracking-wider text-[#004d08] dark:text-[#86EFAC] mb-2">Address</h4>
            <div class="grid grid-cols-2 gap-3">
              <div v-for="f in addressFields" :key="f.label">
                <p class="text-[10px] font-medium uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-0.5">{{ f.label }}</p>
                <p class="text-xs text-gray-900 dark:text-white font-normal break-words">{{ f.value }}</p>
              </div>
            </div>
          </section>

          <!-- Guardian -->
          <section class="pt-3 border-t border-gray-100 dark:border-[#3F4F43]">
            <h4 class="text-[10px] font-semibold uppercase tracking-wider text-[#004d08] dark:text-[#86EFAC] mb-2">Guardian</h4>
            <div class="grid grid-cols-2 gap-3">
              <div>
                <p class="text-[10px] font-medium uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-0.5">Guardian Name</p>
                <p class="text-xs text-gray-900 dark:text-white font-normal">{{ student.guardian_name || '—' }}</p>
              </div>
              <div>
                <p class="text-[10px] font-medium uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-0.5">Guardian Contact</p>
                <p class="text-xs font-mono text-gray-900 dark:text-white font-normal">{{ student.guardian_contact || '—' }}</p>
              </div>
            </div>
          </section>

          <!-- Current Enrollment -->
          <section class="pt-3 border-t border-gray-100 dark:border-[#3F4F43]">
            <h4 class="text-[10px] font-semibold uppercase tracking-wider text-[#004d08] dark:text-[#86EFAC] mb-2">Current Enrollment</h4>
            <div class="grid grid-cols-2 gap-3">
              <div>
                <p class="text-[10px] font-medium uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-0.5">Grade Level</p>
                <p class="text-xs text-gray-900 dark:text-white font-normal">{{ student.grade_level ? `Grade ${student.grade_level}` : '—' }}</p>
              </div>
              <div>
                <p class="text-[10px] font-medium uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-0.5">Section</p>
                <p class="text-xs text-gray-900 dark:text-white font-normal">{{ student.section || '—' }}</p>
              </div>
            </div>
          </section>

          <!-- Enrollment History -->
          <section v-if="student.enrollments?.length" class="pt-3 border-t border-gray-100 dark:border-[#3F4F43]">
            <h4 class="text-[10px] font-semibold uppercase tracking-wider text-[#004d08] dark:text-[#86EFAC] mb-2">Enrollment History</h4>
            <div class="space-y-2">
              <div v-for="e in student.enrollments" :key="e.id"
                   class="flex items-center justify-between gap-3 p-3 rounded-xl bg-gray-50 dark:bg-[#232D26] border border-gray-200/80 dark:border-[#3F4F43]">
                <div class="min-w-0">
                  <p class="text-xs font-medium text-gray-900 dark:text-white truncate">
                    {{ e.school_year || '—' }} &bull; {{ e.section || '—' }}
                  </p>
                  <p class="text-[10px] text-gray-500 dark:text-gray-400 truncate">
                    {{ e.strand || 'General Academic' }}
                  </p>
                </div>
                <span class="px-2 py-0.5 rounded-full text-[10px] font-medium uppercase tracking-wider border shrink-0"
                      :class="enrollmentStatusClass(e.status)">
                  {{ e.status }}
                </span>
              </div>
            </div>
          </section>

          <!-- ══════════════ APPLICANT RECORD ══════════════ -->
          <section v-if="applicant" class="pt-3 border-t border-gray-100 dark:border-[#3F4F43]">
            <div class="flex items-center justify-between mb-2">
              <h4 class="text-[10px] font-semibold uppercase tracking-wider text-[#004d08] dark:text-[#86EFAC]">
                Applicant Record
              </h4>
              <span class="text-[10px] text-gray-400 dark:text-gray-500">
                Converted {{ formatDate(applicant.reviewed_at) }}
              </span>
            </div>

            <!-- Academic intent -->
            <div class="grid grid-cols-2 gap-3 mb-3">
              <div>
                <p class="text-[10px] font-medium uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-0.5">Reference No.</p>
                <p class="text-xs font-mono text-gray-900 dark:text-white">{{ applicant.reference_number }}</p>
              </div>
              <div>
                <p class="text-[10px] font-medium uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-0.5">Applicant Type</p>
                <p class="text-xs text-gray-900 dark:text-white capitalize">{{ applicant.applicant_type }}</p>
              </div>
              <div>
                <p class="text-[10px] font-medium uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-0.5">Applied For</p>
                <p class="text-xs text-gray-900 dark:text-white">Grade {{ applicant.desired_grade_level }}</p>
              </div>
              <div>
                <p class="text-[10px] font-medium uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-0.5">Original Strand</p>
                <p class="text-xs text-gray-900 dark:text-white">{{ applicant.strand || 'General Academic' }}</p>
              </div>
              <div>
                <p class="text-[10px] font-medium uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-0.5">Applied For S.Y.</p>
                <p class="text-xs text-gray-900 dark:text-white">{{ applicant.school_year || '—' }}</p>
              </div>
              <div>
                <p class="text-[10px] font-medium uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-0.5">Submitted</p>
                <p class="text-xs text-gray-900 dark:text-white">{{ formatDate(applicant.submitted_at) }}</p>
              </div>
            </div>

            <!-- Contacts from applicant days -->
            <div v-if="applicant.contacts?.length" class="mb-3">
              <p class="text-[10px] font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400 mb-2">
                Contacts on File
              </p>
              <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                <div v-for="c in applicant.contacts" :key="c.id"
                     class="p-2.5 rounded-lg bg-gray-50 dark:bg-[#232D26] border border-gray-200/80 dark:border-[#3F4F43]">
                  <div class="flex items-center justify-between mb-0.5">
                    <span class="text-[10px] font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">{{ c.role }}</span>
                    <span v-if="c.relationship" class="text-[10px] text-gray-400 dark:text-gray-500">{{ c.relationship }}</span>
                  </div>
                  <p class="text-xs font-medium text-gray-900 dark:text-white">{{ c.full_name }}</p>
                  <p v-if="c.occupation" class="text-[10px] text-gray-500 dark:text-gray-400">{{ c.occupation }}</p>
                  <div class="mt-1 space-y-0.5">
                    <p class="text-[10px] font-mono text-gray-600 dark:text-gray-300">{{ c.contact_number || '—' }}</p>
                    <p v-if="c.email" class="text-[10px] text-gray-500 dark:text-gray-400 truncate">{{ c.email }}</p>
                  </div>
                </div>
              </div>
            </div>

            <!-- Documents from applicant days -->
            <div v-if="applicant.documents?.length">
              <p class="text-[10px] font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400 mb-2">
                Documents on File
              </p>
              <div class="space-y-1.5">
                <div v-for="doc in applicant.documents" :key="doc.id"
                     class="flex items-center justify-between gap-3 p-2 rounded-lg bg-gray-50 dark:bg-[#232D26] border border-gray-200/80 dark:border-[#3F4F43]">
                  <div class="min-w-0">
                    <p class="text-xs font-medium text-gray-900 dark:text-white truncate">{{ doc.document_type }}</p>
                    <p class="text-[10px] text-gray-500 dark:text-gray-400 truncate">{{ doc.file_name }}</p>
                  </div>
                  <span class="px-2 py-0.5 rounded-full text-[10px] font-medium uppercase tracking-wider border shrink-0"
                        :class="docStatusClass(doc.status)">
                    {{ doc.status }}
                  </span>
                </div>
              </div>
            </div>
          </section>

          <!-- No applicant record -->
          <section v-else class="pt-3 border-t border-gray-100 dark:border-[#3F4F43]">
            <h4 class="text-[10px] font-semibold uppercase tracking-wider text-[#004d08] dark:text-[#86EFAC] mb-2">
              Applicant Record
            </h4>
            <p class="text-xs text-gray-500 dark:text-gray-400 italic">
              No linked applicant record. This student was either created directly by an admin or imported.
            </p>
          </section>

        </div>
      </div>

      <!-- Footer -->
      <div class="px-6 py-4 border-t border-gray-100 dark:border-[#3F4F43] flex justify-end gap-2">
        <button type="button" @click="closeModal"
                class="px-4 py-2 text-xs font-normal text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-white/5 rounded-xl transition-all cursor-pointer">
          Close
        </button>
        <button v-if="student" type="button" @click="$emit('edit', student)"
                class="px-5 py-2 text-xs font-medium uppercase tracking-wider bg-[#004d08] hover:bg-emerald-900 text-white rounded-xl transition-all shadow-md active:scale-95 cursor-pointer">
          Edit Student
        </button>
      </div>

    </div>
  </Modal>
</template>

<script setup>
import { ref, computed, watch } from 'vue'
import axios from 'axios'
import Modal from '@/Components/Modal.vue'

const props = defineProps({
  show:    { type: Boolean, default: false },
  student: { type: Object,  default: null },
})

const emit = defineEmits(['close', 'edit'])

const student   = ref(null)
const applicant = ref(null)
const isLoading = ref(false)
const generalError = ref('')

watch(() => props.show, async (open) => {
  if (open && props.student?.id) {
    reset()
    await fetchDetails()
  }
})

const reset = () => {
  student.value = null
  applicant.value = null
  generalError.value = ''
  isLoading.value = false
}

const fetchDetails = async () => {
  isLoading.value = true
  try {
    const { data } = await axios.get(`/admin/students/${props.student.id}`)
    student.value   = data.student
    applicant.value = data.applicant
  } catch (error) {
    generalError.value = error.response?.data?.message || 'Failed to load student details.'
  } finally {
    isLoading.value = false
  }
}

const personalFields = computed(() => student.value ? [
  { label: 'Full Name',    value: student.value.user.name },
  { label: 'Email',        value: student.value.user.email, span: 'col-span-2' },
  { label: 'LRN',          value: student.value.lrn, mono: true },
  { label: 'Sex',          value: student.value.sex || '—', capitalize: true },
  { label: 'Date of Birth', value: formatDate(student.value.date_of_birth) },
  { label: 'Contact No.',  value: student.value.contact_number || '—', mono: true },
] : [])

const addressFields = computed(() => student.value ? [
  { label: 'House / Street', value: student.value.house_street || '—' },
  { label: 'Barangay',       value: student.value.barangay || '—' },
  { label: 'Municipality',   value: student.value.municipality || '—' },
  { label: 'Province',       value: student.value.province || '—' },
  { label: 'ZIP Code',       value: student.value.zip_code || '—' },
] : [])

const formatDate = (iso) => {
  if (!iso) return '—'
  try { return new Date(iso).toLocaleDateString('en-PH', { year: 'numeric', month: 'long', day: 'numeric' }) }
  catch { return iso }
}

const getStatusBadgeClass = (status) => ({
  active:          'bg-emerald-100 dark:bg-emerald-950/60 text-emerald-800 dark:text-emerald-300 border-emerald-200 dark:border-emerald-800',
  graduated:       'bg-sky-50 dark:bg-sky-950/50 text-sky-700 dark:text-sky-300 border-sky-200 dark:border-sky-800',
  dropped_out:     'bg-red-50 dark:bg-red-950/50 text-red-700 dark:text-red-300 border-red-200 dark:border-red-800',
  transferred_out: 'bg-amber-50 dark:bg-amber-950/50 text-amber-700 dark:text-amber-300 border-amber-200 dark:border-amber-800',
}[status] || 'bg-gray-100 dark:bg-[#232D26] text-gray-600 dark:text-gray-400 border-gray-200 dark:border-[#3F4F43]')

const getStatusDotClass = (status) => ({
  active: 'bg-emerald-500', graduated: 'bg-sky-500',
  dropped_out: 'bg-red-500', transferred_out: 'bg-amber-500',
}[status] || 'bg-gray-400')

const enrollmentStatusClass = (status) => ({
  enrolled:    'bg-emerald-50 dark:bg-emerald-950/50 text-emerald-700 dark:text-emerald-300 border-emerald-200 dark:border-emerald-800',
  pending:     'bg-amber-50 dark:bg-amber-950/50 text-amber-700 dark:text-amber-300 border-amber-200 dark:border-amber-800',
  dropped:     'bg-red-50 dark:bg-red-950/50 text-red-700 dark:text-red-300 border-red-200 dark:border-red-800',
  transferred: 'bg-sky-50 dark:bg-sky-950/50 text-sky-700 dark:text-sky-300 border-sky-200 dark:border-sky-800',
  completed:   'bg-gray-100 dark:bg-[#232D26] text-gray-600 dark:text-gray-400 border-gray-200 dark:border-[#3F4F43]',
}[status] || 'bg-gray-100 dark:bg-[#232D26] text-gray-600 dark:text-gray-400 border-gray-200 dark:border-[#3F4F43]')

const docStatusClass = (status) => ({
  verified:   'bg-emerald-50 dark:bg-emerald-950/50 text-emerald-700 dark:text-emerald-300 border-emerald-200 dark:border-emerald-800',
  received:   'bg-sky-50 dark:bg-sky-950/50 text-sky-700 dark:text-sky-300 border-sky-200 dark:border-sky-800',
  incomplete: 'bg-amber-50 dark:bg-amber-950/50 text-amber-700 dark:text-amber-300 border-amber-200 dark:border-amber-800',
  rejected:   'bg-red-50 dark:bg-red-950/50 text-red-700 dark:text-red-300 border-red-200 dark:border-red-800',
  pending:    'bg-gray-100 dark:bg-[#232D26] text-gray-600 dark:text-gray-400 border-gray-200 dark:border-[#3F4F43]',
}[status] || 'bg-gray-100 dark:bg-[#232D26] text-gray-600 dark:text-gray-400 border-gray-200 dark:border-[#3F4F43]')

const closeModal = () => {
  reset()
  emit('close')
}
</script>