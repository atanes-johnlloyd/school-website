<template>
  <Modal :show="show" @close="closeModal" max-width="lg">
    <div class="bg-white dark:bg-[#2D3A31] rounded-2xl border border-gray-200 dark:border-[#3F4F43] font-['Inter']">

      <div class="flex items-center justify-between px-6 pt-5 pb-4 border-b border-gray-100 dark:border-[#3F4F43]">
        <div class="flex items-center gap-3">
          <div class="w-10 h-10 rounded-2xl bg-emerald-50 dark:bg-[#1C261E] border border-emerald-100 dark:border-[#3F4F43] flex items-center justify-center text-[#004d08] dark:text-[#86EFAC] shrink-0">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
            </svg>
          </div>
          <div>
            <h3 class="text-xs font-medium uppercase tracking-wider text-gray-900 dark:text-white">
              {{ isEditing ? 'Edit Entrance Exam' : 'Schedule Entrance Exam' }}
            </h3>
            <p class="text-[11px] text-gray-500 dark:text-gray-400 font-normal">
              {{ isEditing ? 'Update exam details' : 'Create a new exam session for applicants' }}
            </p>
          </div>
        </div>
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

      <div v-if="generalError" class="mx-6 mt-4 p-3 bg-red-50 dark:bg-red-950/40 border border-red-200 dark:border-red-800 rounded-xl text-red-700 dark:text-red-300 text-xs">
        {{ generalError }}
      </div>

      <form @submit.prevent="submit" class="px-6 py-5 space-y-4">

        <div>
          <label class="block text-[11px] font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1.5">
            Exam Name <span class="text-red-500">*</span>
          </label>
          <input v-model="form.exam_name" type="text" placeholder="e.g. SHS Entrance Exam — Batch 1"
                 class="w-full px-3.5 py-2.5 text-xs bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white rounded-xl focus:ring-2 focus:ring-[#004d08] focus:outline-none font-normal"
                 :class="{ 'border-red-500': errors.exam_name }" />
          <p v-if="errors.exam_name" class="mt-1 text-[11px] text-red-500">{{ errors.exam_name[0] }}</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
          <div>
            <label class="block text-[11px] font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1.5">
              School Year <span class="text-red-500">*</span>
            </label>
            <select v-model="form.school_year_id"
                    class="w-full px-3.5 py-2.5 text-xs bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white rounded-xl focus:ring-2 focus:ring-[#004d08] focus:outline-none font-normal cursor-pointer"
                    :class="{ 'border-red-500': errors.school_year_id }">
              <option value="" disabled>Select...</option>
              <option v-for="sy in schoolYears" :key="sy.id" :value="sy.id">{{ sy.label }}</option>
            </select>
            <p v-if="errors.school_year_id" class="mt-1 text-[11px] text-red-500">{{ errors.school_year_id[0] }}</p>
          </div>

          <div>
            <label class="block text-[11px] font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1.5">
              Track <span class="text-gray-400 font-normal">(optional)</span>
            </label>
            <select v-model="form.track_id"
                    class="w-full px-3.5 py-2.5 text-xs bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white rounded-xl focus:ring-2 focus:ring-[#004d08] focus:outline-none font-normal cursor-pointer">
              <option value="">All tracks</option>
              <option v-for="t in tracks" :key="t.id" :value="t.id">{{ t.name }}</option>
            </select>
          </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
          <div>
            <label class="block text-[11px] font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1.5">
              Exam Date <span class="text-red-500">*</span>
            </label>
            <input v-model="form.exam_date" type="date"
                   class="w-full px-3.5 py-2.5 text-xs bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white rounded-xl focus:ring-2 focus:ring-[#004d08] focus:outline-none font-normal cursor-pointer"
                   :class="{ 'border-red-500': errors.exam_date }" />
            <p v-if="errors.exam_date" class="mt-1 text-[11px] text-red-500">{{ errors.exam_date[0] }}</p>
          </div>

          <div>
            <label class="block text-[11px] font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1.5">
              Exam Time <span class="text-red-500">*</span>
            </label>
            <input v-model="form.exam_time" type="time"
                   class="w-full px-3.5 py-2.5 text-xs bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white rounded-xl focus:ring-2 focus:ring-[#004d08] focus:outline-none font-normal cursor-pointer"
                   :class="{ 'border-red-500': errors.exam_time }" />
            <p v-if="errors.exam_time" class="mt-1 text-[11px] text-red-500">{{ errors.exam_time[0] }}</p>
          </div>
        </div>

        <div>
          <label class="block text-[11px] font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1.5">
            Venue
          </label>
          <input v-model="form.venue" type="text" placeholder="e.g. Computer Laboratory 1"
                 class="w-full px-3.5 py-2.5 text-xs bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white rounded-xl focus:ring-2 focus:ring-[#004d08] focus:outline-none font-normal" />
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
          <div>
            <label class="block text-[11px] font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1.5">
              Grade Level <span class="text-red-500">*</span>
            </label>
            <select v-model="form.grade_level"
                    class="w-full px-3.5 py-2.5 text-xs bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white rounded-xl focus:ring-2 focus:ring-[#004d08] focus:outline-none font-normal cursor-pointer">
              <option value="All">All Grade Levels</option>
              <option value="11">Grade 11 Only</option>
              <option value="12">Grade 12 Only</option>
            </select>
          </div>

          <div>
            <label class="block text-[11px] font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1.5">
              Max Capacity <span class="text-red-500">*</span>
            </label>
            <input v-model.number="form.max_capacity" type="number" min="1" max="500"
                   class="w-full px-3.5 py-2.5 text-xs bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white rounded-xl focus:ring-2 focus:ring-[#004d08] focus:outline-none font-normal" />
            <p v-if="errors.max_capacity" class="mt-1 text-[11px] text-red-500">{{ errors.max_capacity[0] }}</p>
          </div>
        </div>

        <div class="mt-6 flex justify-end gap-2.5 pt-4 border-t border-gray-100 dark:border-[#3F4F43]">
          <button type="button" @click="closeModal"
                  class="px-4 py-2 text-xs font-normal text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-white/5 rounded-xl transition-all cursor-pointer">
            Cancel
          </button>
          <button type="submit" :disabled="isLoading"
                  class="px-5 py-2 text-xs font-medium uppercase tracking-wider bg-[#004d08] text-white hover:bg-emerald-900 rounded-xl transition-all shadow-md active:scale-95 disabled:opacity-50 cursor-pointer">
            {{ isLoading ? 'Saving…' : (isEditing ? 'Save Changes' : 'Schedule Exam') }}
          </button>
        </div>
      </form>
    </div>
  </Modal>
</template>

<script setup>
import { ref, reactive, computed, watch } from 'vue'
import axios from 'axios'
import Modal from '@/Components/Modal.vue'
import { useFlash } from '@/Composables/useFlash'

const props = defineProps({
  show:        { type: Boolean, default: false },
  exam:        { type: Object,  default: null },
  tracks:      { type: Array,   default: () => [] },
  schoolYears: { type: Array,   default: () => [] },
})

const emit = defineEmits(['close', 'saved'])

const flash = useFlash()
const isLoading    = ref(false)
const generalError = ref('')
const errors       = ref({})

const isEditing = computed(() => !!props.exam?.id)

const form = reactive({
  exam_name: '',
  school_year_id: '',
  track_id: '',
  exam_date: '',
  exam_time: '',
  venue: '',
  grade_level: 'All',
  max_capacity: 30,
})

watch(() => props.show, (open) => {
  if (open) populate()
})

const populate = () => {
  errors.value = {}
  generalError.value = ''

  if (props.exam) {
    form.exam_name       = props.exam.exam_name || ''
    form.school_year_id  = props.exam.school_year_id || ''
    form.track_id        = props.exam.track_id || ''
    form.exam_date       = props.exam.exam_date || ''
    form.exam_time       = props.exam.exam_time || ''
    form.venue           = props.exam.venue || ''
    form.grade_level     = props.exam.grade_level || 'All'
    form.max_capacity    = props.exam.max_capacity || 30
  } else {
    form.exam_name       = ''
    form.school_year_id  = props.schoolYears.length ? props.schoolYears[0].id : ''
    form.track_id        = ''
    form.exam_date       = ''
    form.exam_time       = ''
    form.venue           = ''
    form.grade_level     = 'All'
    form.max_capacity    = 30
  }
}

const closeModal = () => {
  errors.value = {}
  generalError.value = ''
  emit('close')
}

const submit = async () => {
  isLoading.value = true
  errors.value = {}
  generalError.value = ''

  try {
    const url    = isEditing.value ? `/admin/entrance-exams/${props.exam.id}` : '/admin/entrance-exams'
    const method = isEditing.value ? 'put' : 'post'
    const { data } = await axios[method](url, form)

    if (isEditing.value) {
      const r = data?.reassignment
      if (r && (r.removed > 0 || r.moved > 0)) {
        const parts = []
        if (r.removed > 0) parts.push(`${r.removed} unassigned`)
        if (r.moved   > 0) parts.push(`${r.moved} moved`)
        flash.success(`Exam updated. ${parts.join(', ')}.`)
        if (r.removed > 0) {
          flash.info(`${r.removed} applicant(s) no longer fit this exam and were removed. Review the applicant list.`)
        }
      } else {
        flash.success('Exam updated.')
      }
    } else if (data?.auto_assigned) {
      flash.success(`Exam scheduled — ${data.auto_assigned} applicant(s) auto-assigned.`)
    } else {
      flash.success('Exam scheduled.')
    }

    emit('saved')
    closeModal()
  } catch (error) {
    if (error.response?.status === 422) {
      errors.value = error.response.data.errors || {}
      generalError.value = error.response.data.message || 'Please fix the highlighted fields.'
    } else {
      generalError.value = error.response?.data?.message || 'Failed to save exam.'
    }
    flash.error(generalError.value)
  } finally {
    isLoading.value = false
  }
}
</script>