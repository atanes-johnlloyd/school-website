<template>
  <Modal :show="show" @close="closeModal" max-width="2xl">
    <div class="bg-white dark:bg-[#2D3A31] rounded-2xl border border-gray-200 dark:border-[#3F4F43] font-['Inter'] flex flex-col max-h-[85vh]">

      <div class="flex items-start justify-between gap-4 px-6 pt-5 pb-4 border-b border-gray-100 dark:border-[#3F4F43]">
        <div class="flex items-center gap-3 min-w-0">
          <div class="w-10 h-10 rounded-2xl bg-emerald-50 dark:bg-[#1C261E] border border-emerald-100 dark:border-[#3F4F43] flex items-center justify-center text-[#004d08] dark:text-[#86EFAC] shrink-0">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
          </div>
          <div class="min-w-0">
            <h3 class="text-xs font-medium uppercase tracking-wider text-gray-900 dark:text-white truncate">
              School Year Details
            </h3>
            <p class="text-[11px] text-gray-500 dark:text-gray-400 font-normal truncate">
              <template v-if="year">{{ year.label }}</template>
              <template v-else>Loading…</template>
            </p>
          </div>
        </div>

        <div class="flex items-center gap-2 shrink-0">
          <span v-if="year"
                class="px-2.5 py-1 rounded-full text-[10px] font-normal uppercase tracking-wider inline-flex items-center gap-1.5 border"
                :class="year.is_active
                  ? 'bg-emerald-100 dark:bg-emerald-950/60 text-emerald-800 dark:text-emerald-300 border-emerald-200 dark:border-emerald-800'
                  : 'bg-gray-100 dark:bg-[#232D26] text-gray-600 dark:text-gray-400 border-gray-200 dark:border-[#3F4F43]'">
            <span :class="['w-1.5 h-1.5 rounded-full', year.is_active ? 'bg-emerald-500' : 'bg-gray-400']"></span>
            {{ year.is_active ? 'Active' : 'Inactive' }}
          </span>
          <button type="button" @click="closeModal"
                  class="p-1.5 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 rounded-xl hover:bg-gray-100 dark:hover:bg-white/5 transition-colors cursor-pointer">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>
      </div>

      <div class="flex-1 min-h-0 overflow-y-auto px-6 py-5 space-y-5">

        <div v-if="generalError" class="p-3 bg-red-50 dark:bg-red-950/40 border border-red-200 dark:border-red-800 rounded-xl text-red-700 dark:text-red-300 text-xs">
          {{ generalError }}
        </div>

        <div v-if="isLoading" class="py-16 text-center">
          <div class="inline-block w-6 h-6 border-2 border-[#004d08] dark:border-[#86EFAC] border-t-transparent rounded-full animate-spin"></div>
          <p class="text-[11px] text-gray-500 dark:text-gray-400 mt-2">Loading details…</p>
        </div>

        <div v-else-if="year" class="space-y-5">

          <section>
            <h4 class="text-[10px] font-semibold uppercase tracking-wider text-[#004d08] dark:text-[#86EFAC] mb-2">Duration</h4>
            <div class="grid grid-cols-2 gap-3">
              <div>
                <p class="text-[10px] font-medium uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-0.5">Start Date</p>
                <p class="text-xs text-gray-900 dark:text-white">{{ formatDate(year.start_date) }}</p>
              </div>
              <div>
                <p class="text-[10px] font-medium uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-0.5">End Date</p>
                <p class="text-xs text-gray-900 dark:text-white">{{ formatDate(year.end_date) }}</p>
              </div>
            </div>
          </section>

          <section class="pt-3 border-t border-gray-100 dark:border-[#3F4F43]">
            <h4 class="text-[10px] font-semibold uppercase tracking-wider text-[#004d08] dark:text-[#86EFAC] mb-2">Linked Records</h4>
            <div class="grid grid-cols-3 gap-3">
              <div class="p-3 rounded-xl bg-gray-50 dark:bg-[#232D26] border border-gray-200/80 dark:border-[#3F4F43]">
                <p class="text-2xl font-medium text-gray-900 dark:text-white">{{ year.terms_count }}</p>
                <p class="text-[10px] font-medium uppercase tracking-wider text-gray-400 dark:text-gray-500 mt-0.5">Terms</p>
              </div>
              <div class="p-3 rounded-xl bg-gray-50 dark:bg-[#232D26] border border-gray-200/80 dark:border-[#3F4F43]">
                <p class="text-2xl font-medium text-gray-900 dark:text-white">{{ year.sections_count }}</p>
                <p class="text-[10px] font-medium uppercase tracking-wider text-gray-400 dark:text-gray-500 mt-0.5">Sections</p>
              </div>
              <div class="p-3 rounded-xl bg-gray-50 dark:bg-[#232D26] border border-gray-200/80 dark:border-[#3F4F43]">
                <p class="text-2xl font-medium text-gray-900 dark:text-white">{{ year.enrollments_count }}</p>
                <p class="text-[10px] font-medium uppercase tracking-wider text-gray-400 dark:text-gray-500 mt-0.5">Enrollments</p>
              </div>
            </div>
          </section>

        </div>
      </div>

      <div class="px-6 py-4 border-t border-gray-100 dark:border-[#3F4F43] flex justify-end gap-2">
        <button type="button" @click="closeModal"
                class="px-4 py-2 text-xs font-normal text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-white/5 rounded-xl transition-all cursor-pointer">
          Close
        </button>
        <button v-if="year" type="button" @click="$emit('edit', year)"
                class="px-5 py-2 text-xs font-medium uppercase tracking-wider bg-[#004d08] hover:bg-emerald-900 text-white rounded-xl transition-all shadow-md active:scale-95 cursor-pointer">
          Edit Year
        </button>
      </div>

    </div>
  </Modal>
</template>

<script setup>
import { ref, watch } from 'vue'
import axios from 'axios'
import Modal from '@/Components/Modal.vue'

const props = defineProps({
  show: { type: Boolean, default: false },
  year: { type: Object,  default: null },
})

const emit = defineEmits(['close', 'edit'])

const year         = ref(null)
const isLoading    = ref(false)
const generalError = ref('')

watch(() => props.show, async (open) => {
  if (open && props.year?.id) {
    year.value = null
    generalError.value = ''
    await fetchDetails()
  }
})

const fetchDetails = async () => {
  isLoading.value = true
  try {
    const { data } = await axios.get(`/admin/school-years/${props.year.id}`)
    year.value = data.school_year
    // counts are not returned by show(); pull them from the passed prop
    year.value.terms_count       = props.year.terms_count || 0
    year.value.sections_count    = props.year.sections_count || 0
    year.value.enrollments_count = props.year.enrollments_count || 0
  } catch (e) {
    generalError.value = e.response?.data?.message || 'Failed to load school year.'
  } finally {
    isLoading.value = false
  }
}

const formatDate = (iso) => {
  if (!iso) return '—'
  try { return new Date(iso).toLocaleDateString('en-PH', { year: 'numeric', month: 'long', day: 'numeric' }) }
  catch { return iso }
}

const closeModal = () => { year.value = null; generalError.value = ''; emit('close') }
</script>