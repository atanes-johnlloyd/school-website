<template>
  <AdminLayout>
    <div class="space-y-6 pb-10 font-['Inter']">

      <!-- Hero Banner -->
      <div
        class="relative overflow-hidden w-full rounded-2xl sm:rounded-3xl shadow-lg border border-[#006907]/20 dark:border-none min-h-[220px] sm:min-h-[240px] flex flex-col justify-center p-4 sm:p-8 md:p-10">
        <!-- Background Image Layer (Academics / Planning Calendar Theme) -->
        <img
          :src="heroImage || 'https://images.unsplash.com/photo-1506784983877-45594efa4cbe?q=80&w=1600&auto=format&fit=crop'"
          alt="School Years Hero" class="absolute inset-0 w-full h-full object-cover z-0 object-center" />

        <!-- Animated Dark Green Overlay -->
        <div class="absolute inset-0 bg-[#004d05]/85 dark:bg-[#152B1C]/90 animate-overlay z-0 mix-blend-multiply"></div>

        <!-- Ambient Light Glow Highlights -->
        <div
          class="absolute -right-10 -bottom-10 w-72 h-72 rounded-full bg-emerald-300/20 blur-3xl pointer-events-none z-0">
        </div>
        <div class="absolute right-24 -top-16 w-80 h-80 rounded-full bg-[#F9C20C]/20 blur-3xl pointer-events-none z-0">
        </div>

        <!-- Content Container -->
        <div class="relative z-10 w-full flex flex-col md:flex-row md:items-center justify-between gap-4">
          <div class="space-y-2 sm:space-y-3 max-w-2xl">
            <!-- Top Capsule Badges -->
            <div class="flex flex-wrap items-center gap-2 sm:gap-2.5">
              <span
                class="inline-flex items-center gap-1.5 bg-[#F9C20C] text-[#2C3E2D] font-black text-[10px] sm:text-xs px-3 sm:px-4 py-1 sm:py-1.5 rounded-full shadow-sm tracking-wide uppercase">
                <span>📅</span> ACADEMICS
              </span>

              <span
                class="inline-flex items-center gap-2 bg-black/30 dark:bg-black/50 backdrop-blur-md text-white border border-white/20 text-[10px] sm:text-xs font-semibold px-3 sm:px-4 py-1 sm:py-1.5 rounded-full shadow-sm">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                School Years &amp; Terms
              </span>
            </div>

            <!-- Title & Subtitle -->
            <div class="space-y-1 sm:space-y-1.5">
              <h1
                class="font-['Anton'] text-2xl sm:text-4xl md:text-5xl tracking-wide uppercase flex items-center gap-2 select-none flex-wrap leading-none text-white drop-shadow-md">
                <span>SCHOOL</span>
                <span class="text-[#F9C20C] drop-shadow-[0_2px_8px_rgba(249,194,12,0.4)]">YEARS</span>
              </h1>
              <p class="text-white/90 text-xs sm:text-sm md:text-base leading-relaxed font-medium">
                Manage academic years and their semester or quarter breakdown. Only one year and one term can be active
                at a time.
              </p>
            </div>
          </div>

          <!-- Action Button -->
          <div class="flex items-center shrink-0 pt-2 md:pt-0">
            <button @click="openYearModal()"
              class="inline-flex items-center justify-center gap-2 bg-[#F9C20C] hover:bg-[#e0ae0a] text-[#2C3E2D] font-black px-4 sm:px-5 py-2.5 sm:py-3 rounded-xl transition-all shadow-md active:scale-95 text-xs sm:text-sm cursor-pointer">
              <span class="text-base sm:text-lg leading-none">+</span>
              <span>New School Year</span>
            </button>
          </div>
        </div>
      </div>

      <!-- Empty state -->
      <div v-if="!schoolYears || schoolYears.length === 0"
        class="bg-white dark:bg-[#2D3A31] rounded-2xl border border-gray-200/80 dark:border-[#3F4F43] p-12 text-center">
        <p class="text-xs text-gray-500 dark:text-gray-400">No school years yet.</p>
        <p class="text-[11px] text-gray-400 dark:text-gray-500 mt-0.5">Create one to get started.</p>
      </div>

      <!-- Year cards -->
      <div v-else class="space-y-3">
        <div v-for="year in schoolYears" :key="year.id"
          class="bg-white dark:bg-[#2D3A31] rounded-2xl border border-gray-200/80 dark:border-[#3F4F43] overflow-hidden transition-colors"
          :class="{ 'border-[#004d08]/40 dark:border-[#86EFAC]/30': year.is_active }">

          <!-- Year header -->
          <div class="p-4 sm:p-5 flex items-center gap-3">
            <button @click="toggleExpanded(year.id)"
              class="p-1.5 rounded-lg text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-white/5 transition-colors cursor-pointer shrink-0"
              :title="expanded.includes(year.id) ? 'Collapse' : 'Expand'">
              <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-90': expanded.includes(year.id) }" fill="none"
                stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
              </svg>
            </button>

            <div class="flex-1 min-w-0">
              <div class="flex items-center gap-2 mb-0.5 flex-wrap">
                <span class="text-sm font-semibold text-gray-900 dark:text-white font-mono">{{ year.label }}</span>
                <span v-if="year.is_active"
                  class="px-2 py-0.5 rounded-full text-[10px] font-medium uppercase tracking-wider inline-flex items-center gap-1 border bg-emerald-100 dark:bg-emerald-950/60 text-emerald-800 dark:text-emerald-300 border-emerald-200 dark:border-emerald-800">
                  <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                  Active
                </span>
              </div>
              <p class="text-[11px] text-gray-500 dark:text-gray-400">
                {{ formatDate(year.start_date) }} → {{ formatDate(year.end_date) }}
                &bull; {{ year.terms?.length || 0 }} term{{ (year.terms?.length || 0) === 1 ? '' : 's' }}
                &bull; {{ year.sections_count }} section{{ year.sections_count === 1 ? '' : 's' }}
                &bull; {{ year.enrollments_count }} enrollment{{ year.enrollments_count === 1 ? '' : 's' }}
              </p>
            </div>

            <div class="flex items-center gap-1.5 shrink-0">
              <button v-if="!year.is_active" @click="activateYear(year)"
                class="px-2.5 py-1 text-[10px] font-medium uppercase tracking-wider text-emerald-700 dark:text-emerald-400 bg-emerald-50 hover:bg-emerald-100 dark:bg-emerald-950/50 rounded-lg border border-emerald-200 dark:border-emerald-800 transition-colors cursor-pointer">
                Activate
              </button>
              <button @click="openYearModal(year)"
                class="px-2.5 py-1 text-[10px] font-normal text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-[#232D26] hover:bg-gray-200 dark:hover:bg-white/10 rounded-lg transition-colors cursor-pointer">
                Edit
              </button>
              <button @click="confirmDeleteYear(year)"
                class="px-2.5 py-1 text-[10px] font-normal text-red-700 dark:text-red-400 bg-red-50 hover:bg-red-100 dark:bg-red-950/50 rounded-lg border border-red-200 dark:border-red-900 transition-colors cursor-pointer">
                Delete
              </button>
            </div>
          </div>

          <!-- Terms panel (expanded) -->
          <Transition enter-active-class="transition-all duration-200 ease-out" enter-from-class="max-h-0 opacity-0"
            enter-to-class="max-h-[1000px] opacity-100" leave-active-class="transition-all duration-150 ease-in"
            leave-from-class="max-h-[1000px] opacity-100" leave-to-class="max-h-0 opacity-0">
            <div v-show="expanded.includes(year.id)"
              class="overflow-hidden border-t border-gray-100 dark:border-[#3F4F43]">
              <div class="p-4 sm:p-5 bg-gray-50/50 dark:bg-[#232D26]/30 space-y-3">

                <div class="flex items-center justify-between">
                  <p class="text-[10px] font-semibold uppercase tracking-wider text-[#004d08] dark:text-[#86EFAC]">
                    Terms for {{ year.label }}
                  </p>
                  <button @click="openTermModal(year)"
                    class="inline-flex items-center gap-1 text-[10px] font-medium uppercase tracking-wider text-[#004d08] dark:text-[#86EFAC] hover:underline cursor-pointer">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                    </svg>
                    Add Term
                  </button>
                </div>

                <div v-if="!year.terms || year.terms.length === 0"
                  class="py-6 text-center text-[11px] text-gray-400 dark:text-gray-500 italic">
                  No terms defined for this year.
                </div>

                <div v-else class="space-y-1.5">
                  <div v-for="term in year.terms" :key="term.id"
                    class="flex items-center gap-3 p-3 bg-white dark:bg-[#2D3A31] rounded-xl border border-gray-200/80 dark:border-[#3F4F43]"
                    :class="{ 'border-emerald-200 dark:border-emerald-800': term.is_active }">

                    <div class="flex-1 min-w-0">
                      <div class="flex items-center gap-2 mb-0.5 flex-wrap">
                        <span class="text-xs font-medium text-gray-900 dark:text-white">{{ term.name }}</span>
                        <span v-if="term.is_active"
                          class="px-1.5 py-0.5 rounded-full text-[9px] font-medium uppercase tracking-wider inline-flex items-center gap-1 border bg-emerald-100 dark:bg-emerald-950/60 text-emerald-800 dark:text-emerald-300 border-emerald-200 dark:border-emerald-800">
                          <span class="w-1 h-1 rounded-full bg-emerald-500"></span>
                          Active
                        </span>
                      </div>
                      <p class="text-[10px] text-gray-500 dark:text-gray-400">
                        {{ formatDate(term.start_date) }} → {{ formatDate(term.end_date) }}
                      </p>
                    </div>

                    <div class="flex items-center gap-1 shrink-0">
                      <button v-if="!term.is_active" @click="activateTerm(term)"
                        class="px-2 py-0.5 text-[10px] font-medium uppercase tracking-wider text-emerald-700 dark:text-emerald-400 bg-emerald-50 hover:bg-emerald-100 dark:bg-emerald-950/50 rounded-md border border-emerald-200 dark:border-emerald-800 transition-colors cursor-pointer">
                        Activate
                      </button>
                      <button @click="openTermModal(year, term)"
                        class="px-2 py-0.5 text-[10px] font-normal text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-[#232D26] hover:bg-gray-200 dark:hover:bg-white/10 rounded-md transition-colors cursor-pointer">
                        Edit
                      </button>
                      <button @click="confirmDeleteTerm(term)"
                        class="px-2 py-0.5 text-[10px] font-normal text-red-700 dark:text-red-400 bg-red-50 hover:bg-red-100 dark:bg-red-950/50 rounded-md border border-red-200 dark:border-red-900 transition-colors cursor-pointer">
                        Delete
                      </button>
                    </div>
                  </div>
                </div>

              </div>
            </div>
          </Transition>
        </div>
      </div>

      <!-- Year Form Modal -->
      <Modal :show="showYearModal" @close="closeYearModal" max-width="lg">
        <div class="bg-white dark:bg-[#2D3A31] rounded-2xl border border-gray-200 dark:border-[#3F4F43] font-['Inter']">
          <div class="flex items-center justify-between px-6 pt-5 pb-4 border-b border-gray-100 dark:border-[#3F4F43]">
            <div>
              <h3 class="text-xs font-medium uppercase tracking-wider text-gray-900 dark:text-white">
                {{ editingYear ? 'Edit School Year' : 'New School Year' }}
              </h3>
              <p class="text-[11px] text-gray-500 dark:text-gray-400 font-normal">
                {{ editingYear ? 'Update academic year dates' : 'Define a new academic year' }}
              </p>
            </div>
            <button type="button" @click="closeYearModal"
              class="p-1.5 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 rounded-xl hover:bg-gray-100 dark:hover:bg-white/5 transition-colors cursor-pointer">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </div>

          <div v-if="yearError"
            class="mx-6 mt-4 p-3 bg-red-50 dark:bg-red-950/40 border border-red-200 dark:border-red-800 rounded-xl text-red-700 dark:text-red-300 text-xs">
            {{ yearError }}
          </div>

          <form @submit.prevent="submitYear" class="px-6 py-5 space-y-4">
            <div>
              <label
                class="block text-[11px] font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1.5">
                Label <span class="text-red-500">*</span>
              </label>
              <input v-model="yearForm.label" type="text" placeholder="e.g. 2026-2027"
                class="w-full px-3.5 py-2.5 text-xs bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white rounded-xl focus:ring-2 focus:ring-[#004d08] focus:outline-none font-normal" />
            </div>

            <div class="grid grid-cols-2 gap-3">
              <div>
                <label
                  class="block text-[11px] font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1.5">
                  Start Date <span class="text-red-500">*</span>
                </label>
                <input v-model="yearForm.start_date" type="date"
                  class="w-full px-3.5 py-2.5 text-xs bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white rounded-xl focus:ring-2 focus:ring-[#004d08] focus:outline-none font-normal cursor-pointer" />
              </div>
              <div>
                <label
                  class="block text-[11px] font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1.5">
                  End Date <span class="text-red-500">*</span>
                </label>
                <input v-model="yearForm.end_date" type="date" :min="yearForm.start_date"
                  class="w-full px-3.5 py-2.5 text-xs bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white rounded-xl focus:ring-2 focus:ring-[#004d08] focus:outline-none font-normal cursor-pointer" />
              </div>
            </div>

            <label
              class="flex items-start gap-3 p-3 bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] rounded-xl hover:bg-gray-100/60 dark:hover:bg-white/5 transition-colors cursor-pointer">
              <input v-model="yearForm.is_active" type="checkbox"
                class="mt-0.5 w-4 h-4 text-[#004d08] border-gray-300 rounded focus:ring-[#004d08] cursor-pointer" />
              <div>
                <span class="block text-xs font-medium text-gray-900 dark:text-white">Set as Active School Year</span>
                <span class="block text-[10px] text-gray-500 dark:text-gray-400 font-normal">Deactivates any other
                  active year.</span>
              </div>
            </label>

            <div class="flex justify-end gap-2.5 pt-4 border-t border-gray-100 dark:border-[#3F4F43]">
              <button type="button" @click="closeYearModal"
                class="px-4 py-2 text-xs font-normal text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-white/5 rounded-xl transition-all cursor-pointer">
                Cancel
              </button>
              <button type="submit" :disabled="savingYear"
                class="px-5 py-2 text-xs font-medium uppercase tracking-wider bg-[#004d08] text-white hover:bg-emerald-900 rounded-xl transition-all shadow-md active:scale-95 disabled:opacity-50 cursor-pointer">
                {{ savingYear ? 'Saving…' : (editingYear ? 'Save Changes' : 'Create Year') }}
              </button>
            </div>
          </form>
        </div>
      </Modal>

      <!-- Term Form Modal -->
      <Modal :show="showTermModal" @close="closeTermModal" max-width="lg">
        <div class="bg-white dark:bg-[#2D3A31] rounded-2xl border border-gray-200 dark:border-[#3F4F43] font-['Inter']">
          <div class="flex items-center justify-between px-6 pt-5 pb-4 border-b border-gray-100 dark:border-[#3F4F43]">
            <div>
              <h3 class="text-xs font-medium uppercase tracking-wider text-gray-900 dark:text-white">
                {{ editingTerm ? 'Edit Term' : 'New Term' }}
              </h3>
              <p class="text-[11px] text-gray-500 dark:text-gray-400 font-normal">
                {{ termTargetYear ? `For ${termTargetYear.label}` : '' }}
              </p>
            </div>
            <button type="button" @click="closeTermModal"
              class="p-1.5 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 rounded-xl hover:bg-gray-100 dark:hover:bg-white/5 transition-colors cursor-pointer">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </div>

          <div v-if="termError"
            class="mx-6 mt-4 p-3 bg-red-50 dark:bg-red-950/40 border border-red-200 dark:border-red-800 rounded-xl text-red-700 dark:text-red-300 text-xs">
            {{ termError }}
          </div>

          <form @submit.prevent="submitTerm" class="px-6 py-5 space-y-4">
            <div>
              <label
                class="block text-[11px] font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1.5">
                Term Name <span class="text-red-500">*</span>
              </label>
              <input v-model="termForm.name" type="text" placeholder="e.g. 1st Semester"
                class="w-full px-3.5 py-2.5 text-xs bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white rounded-xl focus:ring-2 focus:ring-[#004d08] focus:outline-none font-normal" />
            </div>

            <div class="grid grid-cols-2 gap-3">
              <div>
                <label
                  class="block text-[11px] font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1.5">
                  Start Date <span class="text-red-500">*</span>
                </label>
                <input v-model="termForm.start_date" type="date" :min="termTargetYear?.start_date"
                  :max="termTargetYear?.end_date"
                  class="w-full px-3.5 py-2.5 text-xs bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white rounded-xl focus:ring-2 focus:ring-[#004d08] focus:outline-none font-normal cursor-pointer" />
                <p v-if="termTargetYear" class="mt-1 text-[10px] text-gray-400 dark:text-gray-500">
                  Must be between {{ formatDate(termTargetYear.start_date) }} and {{ formatDate(termTargetYear.end_date)
                  }}
                </p>
              </div>
              <div>
                <label
                  class="block text-[11px] font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1.5">
                  End Date <span class="text-red-500">*</span>
                </label>
                <input v-model="termForm.end_date" type="date" :min="termForm.start_date || termTargetYear?.start_date"
                  :max="termTargetYear?.end_date"
                  class="w-full px-3.5 py-2.5 text-xs bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white rounded-xl focus:ring-2 focus:ring-[#004d08] focus:outline-none font-normal cursor-pointer" />
              </div>
            </div>

            <label
              class="flex items-start gap-3 p-3 bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] rounded-xl hover:bg-gray-100/60 dark:hover:bg-white/5 transition-colors cursor-pointer">
              <input v-model="termForm.is_active" type="checkbox"
                class="mt-0.5 w-4 h-4 text-[#004d08] border-gray-300 rounded focus:ring-[#004d08] cursor-pointer" />
              <div>
                <span class="block text-xs font-medium text-gray-900 dark:text-white">Set as Active Term</span>
                <span class="block text-[10px] text-gray-500 dark:text-gray-400 font-normal">Deactivates any other
                  active term globally.</span>
              </div>
            </label>

            <div class="flex justify-end gap-2.5 pt-4 border-t border-gray-100 dark:border-[#3F4F43]">
              <button type="button" @click="closeTermModal"
                class="px-4 py-2 text-xs font-normal text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-white/5 rounded-xl transition-all cursor-pointer">
                Cancel
              </button>
              <button type="submit" :disabled="savingTerm"
                class="px-5 py-2 text-xs font-medium uppercase tracking-wider bg-[#004d08] text-white hover:bg-emerald-900 rounded-xl transition-all shadow-md active:scale-95 disabled:opacity-50 cursor-pointer">
                {{ savingTerm ? 'Saving…' : (editingTerm ? 'Save Changes' : 'Create Term') }}
              </button>
            </div>
          </form>
        </div>
      </Modal>

    </div>
  </AdminLayout>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue'
import axios from 'axios'
import { router } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import Modal from '@/Components/Modal.vue'
import { useFlash } from '@/Composables/useFlash'
import { useConfirm } from '@/Composables/useConfirm'

const props = defineProps({
  schoolYears: { type: Array, default: () => [] },
})

const flash = useFlash()
const confirm = useConfirm()
const expanded = ref([])

onMounted(() => {
  const active = props.schoolYears.find(y => y.is_active)
  if (active) expanded.value = [active.id]
})

const toggleExpanded = (id) => {
  const idx = expanded.value.indexOf(id)
  if (idx >= 0) expanded.value.splice(idx, 1)
  else expanded.value.push(id)
}

const formatDate = (iso) => {
  if (!iso) return '—'
  try { return new Date(iso).toLocaleDateString('en-PH', { year: 'numeric', month: 'short', day: 'numeric' }) }
  catch { return iso }
}

const refresh = () => router.reload({ only: ['schoolYears'] })

/* ─── Year modal ─── */
const showYearModal = ref(false)
const editingYear = ref(null)
const savingYear = ref(false)
const yearError = ref('')
const yearForm = reactive({ label: '', start_date: '', end_date: '', is_active: false })

const openYearModal = (year = null) => {
  editingYear.value = year
  yearError.value = ''
  if (year) {
    yearForm.label = year.label
    yearForm.start_date = year.start_date
    yearForm.end_date = year.end_date
    yearForm.is_active = !!year.is_active
  } else {
    yearForm.label = ''
    yearForm.start_date = ''
    yearForm.end_date = ''
    yearForm.is_active = false
  }
  showYearModal.value = true
}

const closeYearModal = () => {
  showYearModal.value = false
  yearError.value = ''
  // NOTE: do not reset editingYear / yearForm here — the modal is still
  // fading out and would flip to the "create" layout mid-animation.
  // The next openYearModal() call resets everything.
}

const submitYear = async () => {
  yearError.value = ''

  // Label
  if (!yearForm.label?.trim()) {
    yearError.value = 'Label is required.'
    return
  }
  if (!/^\d{4}-\d{4}$/.test(yearForm.label)) {
    yearError.value = 'Label must be in YYYY-YYYY format (e.g. 2026-2027).'
    return
  }
  const [y1, y2] = yearForm.label.split('-').map(Number)
  if (y2 !== y1 + 1) {
    yearError.value = 'Label years must be consecutive (e.g. 2026-2027).'
    return
  }

  // Dates
  if (!yearForm.start_date) {
    yearError.value = 'Start date is required.'
    return
  }
  if (!yearForm.end_date) {
    yearError.value = 'End date is required.'
    return
  }
  if (yearForm.end_date <= yearForm.start_date) {
    yearError.value = 'End date must be after start date.'
    return
  }

  // Guard: don't let the admin deactivate the only active year
  if (
    editingYear.value?.is_active &&
    !yearForm.is_active &&
    props.schoolYears.filter(y => y.id !== editingYear.value.id && y.is_active).length === 0
  ) {
    yearError.value = 'Cannot deactivate the only active school year. Activate another year first.'
    return
  }

  savingYear.value = true
  try {
    if (editingYear.value) {
      await axios.put(`/admin/school-years/${editingYear.value.id}`, yearForm)
      flash.success(`School year ${yearForm.label} updated.`)
    } else {
      await axios.post('/admin/school-years', yearForm)
      flash.success(`School year ${yearForm.label} created.`)
    }
    closeYearModal()
    refresh()
  } catch (e) {
    if (e.response?.status === 422) {
      yearError.value = Object.values(e.response.data.errors || {}).flat()[0] || 'Validation failed.'
    } else {
      yearError.value = e.response?.data?.message || 'Failed to save school year.'
    }
    flash.error(yearError.value)
  } finally {
    savingYear.value = false
  }
}

const activateYear = async (year) => {
  const { confirmed } = await confirm({
    title: 'Activate School Year',
    message: `Activate ${year.label}?`,
    details: ['Any currently active school year will be deactivated.'],
    confirmLabel: 'Activate',
    variant: 'info',
  })
  if (!confirmed) return

  try {
    await axios.put(`/admin/school-years/${year.id}/activate`)
    flash.success(`${year.label} is now the active school year.`)
    refresh()
  } catch (e) {
    flash.error(e.response?.data?.message || 'Failed to activate school year.')
  }
}

const confirmDeleteYear = async (year) => {
  const { confirmed } = await confirm({
    title: 'Delete School Year',
    message: `Delete ${year.label}?`,
    details: ['This fails if the year has linked terms, sections, or enrollments.'],
    confirmLabel: 'Delete',
    variant: 'danger',
  })
  if (!confirmed) return

  try {
    await axios.delete(`/admin/school-years/${year.id}`)
    flash.success(`School year ${year.label} deleted.`)
    refresh()
  } catch (e) {
    flash.error(e.response?.data?.message || 'Cannot delete school year.')
  }
}

/* ─── Term modal ─── */
const showTermModal = ref(false)
const editingTerm = ref(null)
const termTargetYear = ref(null)
const savingTerm = ref(false)
const termError = ref('')
const termForm = reactive({ name: '', start_date: '', end_date: '', is_active: false })

const openTermModal = (year, term = null) => {
  editingTerm.value = term
  termTargetYear.value = year
  termError.value = ''

  if (term) {
    termForm.name = term.name
    termForm.start_date = term.start_date
    termForm.end_date = term.end_date
    termForm.is_active = !!term.is_active
  } else {
    termForm.name = ''
    termForm.is_active = false

    // Smart prefill: start right after the latest existing term, else at year start
    const existing = (year.terms || []).slice().sort((a, b) => a.end_date.localeCompare(b.end_date))
    const latestEnd = existing.length ? existing[existing.length - 1].end_date : null

    if (latestEnd && latestEnd < year.end_date) {
      const next = new Date(latestEnd)
      next.setDate(next.getDate() + 1)
      termForm.start_date = next.toISOString().slice(0, 10)
      termForm.end_date = year.end_date
    } else if (!existing.length) {
      termForm.start_date = year.start_date
      termForm.end_date = year.end_date
    } else {
      termForm.start_date = ''
      termForm.end_date = ''
    }
  }
  showTermModal.value = true
}

const closeTermModal = () => {
  showTermModal.value = false
  termError.value = ''
  // Same — don't clear editingTerm / termTargetYear / termForm here.
}

const submitTerm = async () => {
  termError.value = ''

  const year = termTargetYear.value
  if (!year) return

  if (!termForm.name?.trim()) {
    termError.value = 'Term name is required.'
    return
  }
  if (!termForm.start_date) {
    termError.value = 'Start date is required.'
    return
  }
  if (!termForm.end_date) {
    termError.value = 'End date is required.'
    return
  }
  if (termForm.start_date < year.start_date) {
    termError.value = `Start date must be on or after ${formatDate(year.start_date)}.`
    return
  }
  if (termForm.end_date > year.end_date) {
    termError.value = `End date must be on or before ${formatDate(year.end_date)}.`
    return
  }
  if (termForm.end_date <= termForm.start_date) {
    termError.value = 'End date must be after start date.'
    return
  }

  // Client-side overlap check
  const others = (year.terms || []).filter(t => !editingTerm.value || t.id !== editingTerm.value.id)
  const clash = others.find(t =>
    termForm.start_date <= t.end_date && termForm.end_date >= t.start_date
  )
  if (clash) {
    termError.value = `Dates overlap with existing term "${clash.name}" (${formatDate(clash.start_date)} – ${formatDate(clash.end_date)}).`
    return
  }

  savingTerm.value = true
  const payload = { ...termForm, school_year_id: year.id }
  try {
    if (editingTerm.value) {
      await axios.put(`/admin/terms/${editingTerm.value.id}`, payload)
      flash.success(`Term ${termForm.name} updated.`)
    } else {
      await axios.post('/admin/terms', payload)
      flash.success(`Term ${termForm.name} created.`)
    }
    closeTermModal()
    refresh()
  } catch (e) {
    if (e.response?.status === 422) {
      termError.value = Object.values(e.response.data.errors || {}).flat()[0] || 'Validation failed.'
    } else {
      termError.value = e.response?.data?.message || 'Failed to save term.'
    }
    flash.error(termError.value)
  } finally {
    savingTerm.value = false
  }
}

const activateTerm = async (term) => {
  const { confirmed } = await confirm({
    title: 'Activate Term',
    message: `Activate ${term.name}?`,
    details: ['Any currently active term will be deactivated globally.'],
    confirmLabel: 'Activate',
    variant: 'info',
  })
  if (!confirmed) return

  try {
    await axios.put(`/admin/terms/${term.id}/activate`)
    flash.success(`${term.name} is now the active term.`)
    refresh()
  } catch (e) {
    flash.error(e.response?.data?.message || 'Failed to activate term.')
  }
}

const confirmDeleteTerm = async (term) => {
  const { confirmed } = await confirm({
    title: 'Delete Term',
    message: `Delete ${term.name}?`,
    details: ['This fails if the term has linked classes.'],
    confirmLabel: 'Delete',
    variant: 'danger',
  })
  if (!confirmed) return

  try {
    await axios.delete(`/admin/terms/${term.id}`)
    flash.success(`Term ${term.name} deleted.`)
    refresh()
  } catch (e) {
    flash.error(e.response?.data?.message || 'Cannot delete term.')
  }
}
</script>