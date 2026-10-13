<template>
  <Modal :show="show" @close="closeModal" max-width="3xl">
    <div class="bg-white dark:bg-[#2D3A31] rounded-2xl border border-gray-200 dark:border-[#3F4F43] font-['Inter'] flex flex-col max-h-[88vh]">

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
                <span v-if="schedule?.is_irregular"
                  class="ml-1.5 px-1.5 py-0.5 rounded-md text-[9px] font-bold uppercase tracking-wider bg-amber-100 dark:bg-amber-950/60 text-amber-800 dark:text-amber-300 border border-amber-200 dark:border-amber-800">
                  Irregular
                </span>
              </template>
              <template v-else>Loading…</template>
            </p>
          </div>
        </div>

        <div class="flex items-center gap-2 shrink-0">
          <span v-if="student"
                class="px-2.5 py-1 rounded-full text-[10px] font-normal uppercase tracking-wider inline-flex items-center gap-1.5 border"
                :class="getStatusBadgeClass(student.status)">
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

      <!-- TABS -->
      <div v-if="student && !isLoading"
        class="border-b border-gray-100 dark:border-[#3F4F43] bg-gray-50/50 dark:bg-[#232D26]/50 px-4">
        <nav class="flex gap-1">
          <button v-for="t in tabs" :key="t.key" @click="activeTab = t.key"
            :class="[
              'px-4 py-2.5 text-[11px] font-medium uppercase tracking-wider transition-colors border-b-2 -mb-px cursor-pointer',
              activeTab === t.key
                ? 'text-[#004d08] dark:text-[#86EFAC] border-[#004d08] dark:border-[#86EFAC]'
                : 'text-gray-500 dark:text-gray-400 border-transparent hover:text-gray-700 dark:hover:text-gray-200',
            ]">
            {{ t.label }}
          </button>
        </nav>
      </div>

      <!-- Body -->
      <div class="flex-1 min-h-0 overflow-y-auto px-6 py-5 space-y-5">

        <div v-if="generalError" class="p-3 bg-red-50 dark:bg-red-950/40 border border-red-200 dark:border-red-800 rounded-xl text-red-700 dark:text-red-300 text-xs">
          {{ generalError }}
        </div>

        <div v-if="isLoading" class="py-16 text-center">
          <div class="inline-block w-6 h-6 border-2 border-[#004d08] dark:border-[#86EFAC] border-t-transparent rounded-full animate-spin"></div>
          <p class="text-[11px] text-gray-500 dark:text-gray-400 mt-2">Loading student profile…</p>
        </div>

        <!-- ═══════ PROFILE TAB ═══════ -->
        <template v-else-if="student && activeTab === 'profile'">
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

          <section class="pt-3 border-t border-gray-100 dark:border-[#3F4F43]">
            <h4 class="text-[10px] font-semibold uppercase tracking-wider text-[#004d08] dark:text-[#86EFAC] mb-2">Address</h4>
            <div class="grid grid-cols-2 gap-3">
              <div v-for="f in addressFields" :key="f.label">
                <p class="text-[10px] font-medium uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-0.5">{{ f.label }}</p>
                <p class="text-xs text-gray-900 dark:text-white font-normal break-words">{{ f.value }}</p>
              </div>
            </div>
          </section>

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
        </template>

        <!-- ═══════ SCHEDULE TAB ═══════ -->
        <template v-else-if="student && activeTab === 'schedule'">
          <div v-if="scheduleLoading" class="py-12 text-center">
            <div class="inline-block w-6 h-6 border-2 border-[#004d08] dark:border-[#86EFAC] border-t-transparent rounded-full animate-spin"></div>
            <p class="text-[11px] text-gray-500 dark:text-gray-400 mt-2">Loading schedule…</p>
          </div>

          <template v-else-if="schedule">
            <!-- Header with section + irregular toggle -->
            <div class="flex items-center justify-between gap-3 flex-wrap">
              <div class="text-xs text-gray-600 dark:text-gray-300">
                <span class="font-bold text-gray-900 dark:text-white">{{ schedule.section || 'No section' }}</span>
                <span v-if="schedule.term"> &bull; {{ schedule.term.name }}</span>
                <span v-if="schedule.is_irregular" class="ml-2 text-amber-600 dark:text-amber-400 font-bold uppercase text-[10px] tracking-wider">
                  Irregular Schedule
                </span>
              </div>
              <div class="flex items-center gap-2">
                <button v-if="schedule.is_irregular" @click="confirmResetToSection"
                  class="text-[10px] font-medium uppercase tracking-wider text-amber-700 dark:text-amber-400 hover:underline cursor-pointer">
                  Reset to Section
                </button>
                <button @click="openAddClass"
                  class="inline-flex items-center gap-1.5 px-3 py-1.5 text-[10px] font-medium uppercase tracking-wider text-[#004d08] dark:text-[#86EFAC] bg-emerald-50 hover:bg-emerald-100 dark:bg-emerald-950/50 border border-emerald-200 dark:border-emerald-800 rounded-lg transition-colors cursor-pointer">
                  <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                  </svg>
                  Add Class
                </button>
              </div>
            </div>

            <!-- No classes -->
            <div v-if="!schedule.classes.length" class="py-10 text-center text-gray-400 dark:text-gray-500">
              <p class="text-xs">No classes assigned yet.</p>
            </div>

            <!-- Weekly grid -->
            <div v-else class="space-y-3">
              <div v-for="day in daysOfWeek" :key="day" class="rounded-xl border border-gray-200/80 dark:border-[#3F4F43] overflow-hidden">
                <div class="px-3 py-2 bg-gray-50 dark:bg-[#232D26] border-b border-gray-100 dark:border-[#3F4F43]">
                  <p class="text-[10px] font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300">
                    {{ capitalize(day) }}
                  </p>
                </div>
                <div v-if="!sessionsForDay(day).length" class="px-3 py-2.5 text-[11px] text-gray-400 italic">
                  No classes
                </div>
                <ul v-else class="divide-y divide-gray-100 dark:divide-[#3F4F43]">
                  <li v-for="s in sessionsForDay(day)" :key="s.class_id + s.time_start"
                    class="px-3 py-2.5 flex items-center gap-3 group">
                    <div class="text-[10px] font-mono text-gray-500 dark:text-gray-400 shrink-0 w-20">
                      {{ s.time_start }}–{{ s.time_end }}
                    </div>
                    <div class="flex-1 min-w-0">
                      <p class="text-xs font-medium text-gray-900 dark:text-white truncate">
                        {{ s.subject }}
                      </p>
                      <p class="text-[10px] text-gray-500 dark:text-gray-400 truncate">
                        {{ s.section }}
                        <span v-if="s.teacher"> • {{ s.teacher }}</span>
                        <span v-if="s.room"> • {{ s.room }}</span>
                      </p>
                    </div>
                    <button @click="confirmRemoveClass(s)"
                      class="shrink-0 w-6 h-6 rounded-full text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-950/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-sm font-bold cursor-pointer"
                      :title="`Remove from ${s.subject}`">
                      ×
                    </button>
                  </li>
                </ul>
              </div>
            </div>
          </template>
        </template>

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

    <!-- Add Class Modal (nested) -->
    <Modal :show="showAddClassModal" @close="closeAddClass" max-width="2xl">
      <div class="bg-white dark:bg-[#2D3A31] rounded-2xl border border-gray-200 dark:border-[#3F4F43] font-['Inter'] flex flex-col max-h-[80vh]">
        <div class="flex items-center justify-between px-6 pt-5 pb-4 border-b border-gray-100 dark:border-[#3F4F43]">
          <div>
            <h3 class="text-xs font-medium uppercase tracking-wider text-gray-900 dark:text-white">Add Class</h3>
            <p class="text-[11px] text-gray-500 dark:text-gray-400 font-normal">
              {{ schedule?.section }} &bull; {{ schedule?.term?.name }}
            </p>
          </div>
          <button type="button" @click="closeAddClass"
                  class="p-1.5 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 rounded-xl hover:bg-gray-100 dark:hover:bg-white/5 transition-colors cursor-pointer">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>

        <div class="px-6 pt-4">
          <div class="relative">
            <svg class="w-4 h-4 absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
            <input v-model="searchQuery" type="text" placeholder="Search subject, section, teacher..."
                   class="w-full pl-10 pr-4 py-2.5 text-xs bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white placeholder-gray-400 rounded-xl focus:ring-2 focus:ring-[#004d08] focus:outline-none font-normal" />
          </div>
        </div>

        <div class="flex-1 min-h-0 overflow-y-auto px-6 py-4 space-y-1.5">
          <div v-if="availableLoading" class="py-10 text-center">
            <div class="inline-block w-5 h-5 border-2 border-[#004d08] dark:border-[#86EFAC] border-t-transparent rounded-full animate-spin"></div>
          </div>
          <div v-else-if="!filteredAvailable.length" class="py-10 text-center">
            <p class="text-xs text-gray-400 dark:text-gray-500">No classes match.</p>
          </div>
          <button v-for="c in filteredAvailable" :key="c.id"
            type="button"
            :disabled="!c.available"
            @click="addClass(c)"
            class="w-full text-left p-3 rounded-xl border transition-colors cursor-pointer"
            :class="c.available
              ? 'bg-white dark:bg-[#2D3A31] border-gray-200 dark:border-[#3F4F43] hover:border-emerald-400'
              : 'bg-gray-50 dark:bg-[#232D26] border-gray-200 dark:border-[#3F4F43] opacity-60 cursor-not-allowed'">
            <div class="flex items-start justify-between gap-2">
              <div class="min-w-0">
                <p class="text-xs font-medium text-gray-900 dark:text-white truncate">
                  {{ c.subject_code }} — {{ c.subject }}
                </p>
                <p class="text-[10px] text-gray-500 dark:text-gray-400 truncate mt-0.5">
                  {{ c.section }} <span v-if="c.teacher"> • {{ c.teacher }}</span>
                </p>
                <p class="text-[10px] text-gray-400 mt-1">
                  {{ sessionsLabel(c.schedules) }}
                </p>
              </div>
              <span v-if="!c.available" class="text-[9px] font-bold uppercase tracking-wider text-amber-600 dark:text-amber-400 shrink-0">
                Conflict
              </span>
            </div>
            <p v-if="!c.available && c.conflict" class="text-[10px] text-amber-700 dark:text-amber-400 mt-1.5 italic">
              Overlaps {{ c.conflict.subject }} ({{ c.conflict.section }}) on {{ capitalize(c.conflict.day) }} {{ c.conflict.time_start }}–{{ c.conflict.time_end }}
            </p>
          </button>
        </div>

        <div class="px-6 py-4 border-t border-gray-100 dark:border-[#3F4F43] flex justify-end">
          <button @click="closeAddClass"
                  class="px-4 py-2 text-xs font-normal text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-white/5 rounded-xl transition-all cursor-pointer">
            Close
          </button>
        </div>
      </div>
    </Modal>
  </Modal>
</template>

<script setup>
import { ref, computed, watch } from 'vue'
import axios from 'axios'
import Modal from '@/Components/Modal.vue'
import { useFlash } from '@/Composables/useFlash'
import { useConfirm } from '@/Composables/useConfirm'

const props = defineProps({
  show:    { type: Boolean, default: false },
  student: { type: Object,  default: null },
})

const emit  = defineEmits(['close', 'edit'])
const flash = useFlash()
const confirm = useConfirm()

const student      = ref(null)
const isLoading    = ref(false)
const generalError = ref('')
const activeTab    = ref('profile')

const schedule        = ref(null)
const scheduleLoading = ref(false)

const daysOfWeek = ['monday','tuesday','wednesday','thursday','friday']

const tabs = [
  { key: 'profile',  label: 'Profile' },
  { key: 'schedule', label: 'Schedule' },
]

watch(() => props.show, async (open) => {
  if (open && props.student?.id) {
    reset()
    await fetchDetails()
  }
})

watch(activeTab, (tab) => {
  if (tab === 'schedule' && ! schedule.value) fetchSchedule()
})

const reset = () => {
  student.value = null
  generalError.value = ''
  isLoading.value = false
  activeTab.value = 'profile'
  schedule.value = null
}

const fetchDetails = async () => {
  isLoading.value = true
  try {
    const { data } = await axios.get(`/admin/students/${props.student.id}`)
    student.value = data.student
  } catch (error) {
    generalError.value = error.response?.data?.message || 'Failed to load student details.'
  } finally {
    isLoading.value = false
  }
}

const fetchSchedule = async () => {
  scheduleLoading.value = true
  try {
    const { data } = await axios.get(`/admin/students/${props.student.id}/schedule`)
    schedule.value = data
  } catch (e) {
    generalError.value = e.response?.data?.message || 'Failed to load schedule.'
  } finally {
    scheduleLoading.value = false
  }
}

/* ─── Sessions helpers ──────────────────────────────────────── */

const sessionsForDay = (day) => {
  if (! schedule.value?.classes) return []
  const out = []
  for (const c of schedule.value.classes) {
    for (const s of (c.schedules || [])) {
      if (s.day_of_week !== day) continue
      out.push({
        class_id:   c.id,
        subject:    c.subject,
        subject_code: c.subject_code,
        section:    c.section,
        teacher:    c.teacher,
        room:       s.room,
        time_start: s.time_start,
        time_end:   s.time_end,
      })
    }
  }
  return out.sort((a, b) => a.time_start.localeCompare(b.time_start))
}

const sessionsLabel = (schedules) => {
  if (! schedules?.length) return 'No schedule yet'
  const days = [...new Set(schedules.map(s => s.day_of_week))]
  const first = schedules[0]
  const dayLabel = days.length === 5 ? 'Mon–Fri' : days.map(capitalize).join(', ')
  return `${dayLabel} ${first.time_start}–${first.time_end}`
}

const capitalize = (s) => s ? s.charAt(0).toUpperCase() + s.slice(1) : ''

/* ─── Add class ─────────────────────────────────────────────── */

const showAddClassModal = ref(false)
const availableClasses  = ref([])
const availableLoading  = ref(false)
const searchQuery       = ref('')

const filteredAvailable = computed(() => {
  const q = searchQuery.value.trim().toLowerCase()
  if (! q) return availableClasses.value
  return availableClasses.value.filter(c =>
    (c.subject ?? '').toLowerCase().includes(q) ||
    (c.subject_code ?? '').toLowerCase().includes(q) ||
    (c.section ?? '').toLowerCase().includes(q) ||
    (c.teacher ?? '').toLowerCase().includes(q)
  )
})

const openAddClass = async () => {
  showAddClassModal.value = true
  availableClasses.value = []
  searchQuery.value = ''
  availableLoading.value = true
  try {
    const { data } = await axios.get(`/admin/students/${props.student.id}/available-classes`)
    availableClasses.value = data.classes || []
  } catch (e) {
    flash.error(e.response?.data?.message || 'Failed to load classes.')
  } finally {
    availableLoading.value = false
  }
}

const closeAddClass = () => {
  showAddClassModal.value = false
  availableClasses.value = []
}

const addClass = async (c) => {
  if (! c.available) return
  try {
    await axios.post(`/admin/students/${props.student.id}/classes`, { class_id: c.id })
    flash.success(`${c.subject} added.`)
    closeAddClass()
    await fetchSchedule()
  } catch (e) {
    flash.error(e.response?.data?.message || 'Failed to add class.')
  }
}

/* ─── Remove class ─────────────────────────────────────────── */

const confirmRemoveClass = async (s) => {
  const studentName = student.value?.user?.name || props.student?.name || 'this student'

  const { confirmed } = await confirm({
    title: 'Remove Class',
    message: `Remove ${s.subject} from ${studentName}'s schedule?`,
    details: [
      'The student will no longer attend this class.',
      'This makes their schedule irregular.',
    ],
    confirmLabel: 'Remove',
    variant: 'warning',
  })
  if (! confirmed) return
  try {
    await axios.delete(`/admin/students/${props.student.id}/classes/${s.class_id}`)
    flash.success(`${s.subject} removed.`)
    await fetchSchedule()
  } catch (e) {
    flash.error(e.response?.data?.message || 'Failed to remove class.')
  }
}

/* ─── Reset to section ─────────────────────────────────────── */

const confirmResetToSection = async () => {
  const { confirmed } = await confirm({
    title: 'Reset to Section Schedule',
    message: `Restore ${student.value?.user?.name}'s schedule to match their section?`,
    details: [
      'All manual additions will be removed.',
      'All exclusions will be restored.',
      'The student will follow their section again.',
    ],
    confirmLabel: 'Reset',
    variant: 'warning',
  })
  if (! confirmed) return

  try {
    // Re-sync from section
    const sectionId = schedule.value?.section_id
    if (! sectionId) return
    await axios.post(`/admin/students/${props.student.id}/classes/reset`, { section_id: sectionId })
    flash.success('Schedule reset to section.')
    await fetchSchedule()
  } catch (e) {
    flash.error(e.response?.data?.message || 'Failed to reset.')
  }
}

/* ─── Field groups (unchanged) ─────────────────────────────── */

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

const closeModal = () => {
  emit('close')
}
</script>