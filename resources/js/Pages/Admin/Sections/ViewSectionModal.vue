<template>
  <Modal :show="show" @close="closeModal" max-width="3xl">
    <div class="bg-white dark:bg-[#2D3A31] rounded-2xl border border-gray-200 dark:border-[#3F4F43] font-['Inter'] flex flex-col max-h-[88vh]">

      <div class="flex items-start justify-between gap-4 px-6 pt-5 pb-4 border-b border-gray-100 dark:border-[#3F4F43]">
        <div class="flex items-center gap-3 min-w-0">
          <div class="w-10 h-10 rounded-2xl bg-emerald-50 dark:bg-[#1C261E] border border-emerald-100 dark:border-[#3F4F43] flex items-center justify-center text-[#004d08] dark:text-[#86EFAC] shrink-0">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5m0 0h4m-4 0V11m0 0h4m-4 0H9m4 0v10m-4-10v10" />
            </svg>
          </div>
          <div class="min-w-0">
            <h3 class="text-xs font-medium uppercase tracking-wider text-gray-900 dark:text-white truncate">Section Roster</h3>
            <p class="text-[11px] text-gray-500 dark:text-gray-400 font-normal truncate">
              <template v-if="section">{{ section.name }}</template>
              <template v-else>Loading…</template>
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

      <div class="flex-1 min-h-0 overflow-y-auto px-6 py-5 space-y-5">

        <div v-if="generalError" class="p-3 bg-red-50 dark:bg-red-950/40 border border-red-200 dark:border-red-800 rounded-xl text-red-700 dark:text-red-300 text-xs">
          {{ generalError }}
        </div>

        <div v-if="isLoading" class="py-16 text-center">
          <div class="inline-block w-6 h-6 border-2 border-[#004d08] dark:border-[#86EFAC] border-t-transparent rounded-full animate-spin"></div>
          <p class="text-[11px] text-gray-500 dark:text-gray-400 mt-2">Loading section…</p>
        </div>

        <template v-else-if="section">

          <!-- Info + capacity -->
          <section class="p-4 rounded-xl bg-gray-50 dark:bg-[#232D26] border border-gray-200/80 dark:border-[#3F4F43]">
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-4">
              <div>
                <p class="text-[10px] font-medium uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-0.5">Grade</p>
                <p class="text-xs text-gray-900 dark:text-white">Grade {{ section.grade_level }}</p>
              </div>
              <div>
                <p class="text-[10px] font-medium uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-0.5">Strand</p>
                <p class="text-xs text-gray-900 dark:text-white">{{ section.strand || '—' }}</p>
              </div>
              <div>
                <p class="text-[10px] font-medium uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-0.5">Adviser</p>
                <p class="text-xs text-gray-900 dark:text-white">{{ section.adviser || 'Unassigned' }}</p>
              </div>
              <div>
                <p class="text-[10px] font-medium uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-0.5">School Year</p>
                <p class="text-xs text-gray-900 dark:text-white">{{ section.school_year || '—' }}</p>
              </div>
            </div>

            <div>
              <div class="flex items-center justify-between mb-1.5">
                <span class="text-[10px] font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">Capacity</span>
                <span class="text-xs font-medium text-gray-900 dark:text-white">
                  {{ section.enrolled_count }} / {{ section.max_capacity }}
                  <span class="text-[10px] font-normal" :class="capacityColor">
                    &bull; {{ remaining }} remaining
                  </span>
                </span>
              </div>
              <div class="h-2 rounded-full bg-gray-200 dark:bg-[#1C261E] overflow-hidden">
                <div class="h-full rounded-full transition-all"
                     :class="capacityBarClass"
                     :style="{ width: capacityPercent + '%' }"></div>
              </div>
            </div>
          </section>

          <!-- Enrolled students -->
          <section>
            <div class="flex items-center justify-between mb-2">
              <h4 class="text-[10px] font-semibold uppercase tracking-wider text-[#004d08] dark:text-[#86EFAC]">
                Enrolled Students
              </h4>
              <button type="button" @click="openEnrollModal"
                      :disabled="remaining <= 0"
                      :title="remaining <= 0 ? 'Section is at capacity' : 'Enroll a student'"
                      class="inline-flex items-center gap-1.5 px-3 py-1.5 text-[10px] font-medium uppercase tracking-wider text-[#004d08] dark:text-[#86EFAC] bg-emerald-50 hover:bg-emerald-100 dark:bg-emerald-950/50 border border-emerald-200 dark:border-emerald-800 rounded-lg transition-colors cursor-pointer disabled:opacity-40 disabled:cursor-not-allowed">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                Enroll Student
              </button>
            </div>

            <div v-if="!students.length" class="py-10 text-center text-gray-400 dark:text-gray-500">
              <p class="text-xs">No students enrolled yet.</p>
            </div>

            <div v-else class="border border-gray-200/80 dark:border-[#3F4F43] rounded-xl overflow-hidden">
              <table class="w-full text-left border-collapse">
                <thead>
                  <tr class="bg-gray-50/50 dark:bg-[#232D26]/50 text-[10px] font-medium uppercase text-gray-400 tracking-wider border-b border-gray-100 dark:border-[#3F4F43]">
                    <th class="py-2.5 px-4">LRN</th>
                    <th class="py-2.5 px-4">Student</th>
                    <th class="py-2.5 px-4">Enrolled</th>
                    <th class="py-2.5 px-4 text-right">Actions</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-[#3F4F43] text-xs">
                  <tr v-for="s in students" :key="s.id" class="hover:bg-gray-50/80 dark:hover:bg-white/5 transition-colors">
                    <td class="py-2.5 px-4">
                      <span class="font-mono text-[11px] text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-[#232C26] px-2 py-0.5 rounded border border-gray-200 dark:border-[#3F4F43]">
                        {{ s.lrn }}
                      </span>
                    </td>
                    <td class="py-2.5 px-4">
                      <p class="text-xs font-medium text-gray-900 dark:text-white">{{ s.name }}</p>
                      <p class="text-[10px] text-gray-500 dark:text-gray-400">{{ s.email }}</p>
                    </td>
                    <td class="py-2.5 px-4 text-gray-500 dark:text-gray-400 text-[11px]">
                      {{ formatDate(s.enrolled_at) }}
                    </td>
                    <td class="py-2.5 px-4 text-right">
                      <button @click="confirmRemove(s)"
                              class="px-2 py-1 text-[10px] font-normal text-red-700 dark:text-red-400 bg-red-50 hover:bg-red-100 dark:bg-red-950/50 border border-red-200 dark:border-red-900 rounded-md transition-colors cursor-pointer">
                        Remove
                      </button>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </section>

          <!-- Classes -->
          <section v-if="classes.length" class="pt-3 border-t border-gray-100 dark:border-[#3F4F43]">
            <h4 class="text-[10px] font-semibold uppercase tracking-wider text-[#004d08] dark:text-[#86EFAC] mb-2">
              Subject Classes ({{ classes.length }})
            </h4>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
              <div v-for="c in classes" :key="c.id"
                   class="p-2.5 rounded-lg bg-gray-50 dark:bg-[#232D26] border border-gray-200/80 dark:border-[#3F4F43]">
                <p class="text-xs font-medium text-gray-900 dark:text-white">{{ c.subject }}</p>
                <p class="text-[10px] text-gray-500 dark:text-gray-400">
                  <span class="font-mono">{{ c.subject_code }}</span> &bull; {{ c.teacher || 'No teacher' }}
                </p>
              </div>
            </div>
          </section>

        </template>
      </div>

      <div class="px-6 py-4 border-t border-gray-100 dark:border-[#3F4F43] flex justify-end gap-2">
        <button type="button" @click="closeModal"
                class="px-4 py-2 text-xs font-normal text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-white/5 rounded-xl transition-all cursor-pointer">
          Close
        </button>
        <button v-if="section" type="button" @click="$emit('edit', section)"
                class="px-5 py-2 text-xs font-medium uppercase tracking-wider bg-[#004d08] hover:bg-emerald-900 text-white rounded-xl transition-all shadow-md active:scale-95 cursor-pointer">
          Edit Section
        </button>
      </div>
    </div>
  </Modal>

  <!-- Enroll Modal -->
  <Modal :show="showEnrollModal" @close="closeEnrollModal" max-width="2xl">
    <div class="bg-white dark:bg-[#2D3A31] rounded-2xl border border-gray-200 dark:border-[#3F4F43] font-['Inter'] flex flex-col max-h-[80vh]">
      <div class="flex items-center justify-between px-6 pt-5 pb-4 border-b border-gray-100 dark:border-[#3F4F43]">
        <div>
          <h3 class="text-xs font-medium uppercase tracking-wider text-gray-900 dark:text-white">Enroll Student</h3>
          <p class="text-[11px] text-gray-500 dark:text-gray-400 font-normal">
            {{ section?.name }} &bull; {{ remaining }} slots available
          </p>
        </div>
        <button type="button" @click="closeEnrollModal"
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
          <input v-model="enrollSearch" @input="debouncedEligible" type="text" placeholder="Search name, LRN, email..."
                 class="w-full pl-10 pr-4 py-2.5 text-xs bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white placeholder-gray-400 rounded-xl focus:ring-2 focus:ring-[#004d08] focus:outline-none font-normal" />
        </div>
      </div>

      <div class="flex-1 min-h-0 overflow-y-auto px-6 py-4">
        <div v-if="eligibleLoading" class="py-10 text-center">
          <div class="inline-block w-5 h-5 border-2 border-[#004d08] dark:border-[#86EFAC] border-t-transparent rounded-full animate-spin"></div>
        </div>
        <div v-else-if="!eligible.length" class="py-10 text-center">
          <p class="text-xs text-gray-400 dark:text-gray-500">No eligible students found.</p>
          <p class="text-[11px] text-gray-400 dark:text-gray-500 mt-0.5">All active students are already enrolled this year.</p>
        </div>
        <div v-else class="space-y-1.5">
          <label v-for="s in eligible" :key="s.id"
                 class="flex items-center gap-3 p-3 rounded-xl border transition-colors cursor-pointer"
                 :class="selectedStudents.includes(s.id)
                   ? 'bg-emerald-50/50 dark:bg-emerald-950/20 border-emerald-200 dark:border-emerald-800'
                   : 'bg-white dark:bg-[#2D3A31] border-gray-200/80 dark:border-[#3F4F43] hover:bg-gray-50 dark:hover:bg-white/5'">
            <input type="checkbox" :value="s.id" v-model="selectedStudents"
                   :disabled="selectedStudents.length >= remaining && !selectedStudents.includes(s.id)"
                   class="w-4 h-4 text-[#004d08] border-gray-300 rounded focus:ring-[#004d08] cursor-pointer disabled:opacity-40" />
            <div class="min-w-0 flex-1">
              <p class="text-xs font-medium text-gray-900 dark:text-white truncate">{{ s.name }}</p>
              <p class="text-[10px] text-gray-500 dark:text-gray-400 truncate">
                <span class="font-mono">{{ s.lrn }}</span> &bull; {{ s.email }}
              </p>
            </div>
          </label>
        </div>
      </div>

      <div class="px-6 py-4 border-t border-gray-100 dark:border-[#3F4F43] flex items-center justify-between gap-2">
        <span class="text-[11px] text-gray-500 dark:text-gray-400">
          {{ selectedStudents.length }} selected
          <span v-if="selectedStudents.length >= remaining" class="text-amber-600 dark:text-amber-400">
            &bull; capacity limit reached
          </span>
        </span>
        <div class="flex gap-2">
          <button type="button" @click="closeEnrollModal"
                  class="px-4 py-2 text-xs font-normal text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-white/5 rounded-xl transition-all cursor-pointer">
            Cancel
          </button>
          <button type="button" :disabled="!selectedStudents.length || enrollSaving"
                  @click="submitEnroll"
                  class="px-5 py-2 text-xs font-medium uppercase tracking-wider bg-[#004d08] text-white hover:bg-emerald-900 rounded-xl transition-all shadow-md active:scale-95 disabled:opacity-50 cursor-pointer">
            {{ enrollSaving ? 'Enrolling…' : `Enroll ${selectedStudents.length || ''}` }}
          </button>
        </div>
      </div>
    </div>
  </Modal>
</template>

<script setup>
import { confirmAction, showError } from '@/Pages/useSweetAlert'
import { ref, computed, watch } from 'vue'
import axios from 'axios'
import Modal from '@/Components/Modal.vue'

const props = defineProps({
  show:    { type: Boolean, default: false },
  section: { type: Object,  default: null },
})

const emit = defineEmits(['close', 'edit', 'changed'])

const section      = ref(null)
const students     = ref([])
const classes      = ref([])
const isLoading    = ref(false)
const generalError = ref('')

// Enroll modal state
const showEnrollModal  = ref(false)
const eligible         = ref([])
const eligibleLoading  = ref(false)
const enrollSearch     = ref('')
const selectedStudents = ref([])
const enrollSaving     = ref(false)

let searchTimer = null

watch(() => props.show, async (open) => {
  if (open && props.section?.id) {
    reset()
    await fetchDetails()
  }
})

const reset = () => {
  section.value = null
  students.value = []
  classes.value = []
  generalError.value = ''
  isLoading.value = false
}

const fetchDetails = async () => {
  isLoading.value = true
  try {
    const { data } = await axios.get(`/admin/sections/${props.section.id}`)
    section.value  = data.section
    students.value = data.students || []
    classes.value  = data.classes || []
  } catch (e) {
    generalError.value = e.response?.data?.message || 'Failed to load section.'
  } finally {
    isLoading.value = false
  }
}

const remaining = computed(() => {
  if (!section.value) return 0
  return Math.max(0, section.value.max_capacity - students.value.length)
})

const capacityPercent = computed(() => {
  if (!section.value || !section.value.max_capacity) return 0
  return Math.min(100, Math.round((students.value.length / section.value.max_capacity) * 100))
})

const capacityColor = computed(() => {
  const p = capacityPercent.value
  if (p >= 100) return 'text-red-600 dark:text-red-400'
  if (p >= 90)  return 'text-amber-600 dark:text-amber-400'
  return 'text-emerald-600 dark:text-emerald-400'
})

const capacityBarClass = computed(() => {
  const p = capacityPercent.value
  if (p >= 100) return 'bg-red-500'
  if (p >= 90)  return 'bg-amber-500'
  return 'bg-emerald-500'
})

const formatDate = (iso) => {
  if (!iso) return '—'
  try { return new Date(iso).toLocaleDateString('en-PH', { year: 'numeric', month: 'short', day: 'numeric' }) }
  catch { return iso }
}

/* ─── Enroll ─── */
const openEnrollModal = async () => {
  showEnrollModal.value = true
  enrollSearch.value = ''
  selectedStudents.value = []
  await fetchEligible()
}

const closeEnrollModal = () => {
  showEnrollModal.value = false
  eligible.value = []
  selectedStudents.value = []
}

const debouncedEligible = () => {
  clearTimeout(searchTimer)
  searchTimer = setTimeout(fetchEligible, 300)
}

const fetchEligible = async () => {
  eligibleLoading.value = true
  try {
    const { data } = await axios.get(`/admin/sections/${props.section.id}/eligible-students`, {
      params: { search: enrollSearch.value || undefined },
    })
    eligible.value = data.students || []
  } catch (e) {
    generalError.value = e.response?.data?.message || 'Failed to load eligible students.'
  } finally {
    eligibleLoading.value = false
  }
}

const submitEnroll = async () => {
  if (!selectedStudents.value.length) return
  enrollSaving.value = true
  const errors = []
  for (const sid of selectedStudents.value) {
    try {
      await axios.post(`/admin/sections/${props.section.id}/enroll`, { student_id: sid })
    } catch (e) {
      errors.push(e.response?.data?.message || 'Failed to enroll a student.')
    }
  }
  enrollSaving.value = false
  if (errors.length) generalError.value = errors[0]
  closeEnrollModal()
  await fetchDetails()
  emit('changed')
}

const confirmRemove = async (student) => {
  if (!await confirmAction(`Remove ${student.name} from this section?`)) return
  try {
    await axios.delete(`/admin/sections/${props.section.id}/students/${student.student_id}`)
    await fetchDetails()
    emit('changed')
  } catch (e) {
    generalError.value = e.response?.data?.message || 'Failed to remove student.'
  }
}

const closeModal = () => {
  closeEnrollModal()
  reset()
  emit('close')
}
</script>