<template>
  <Modal :show="show" @close="closeModal" max-width="md">
    <div class="p-6 bg-white dark:bg-[#2D3A31] rounded-3xl border border-gray-200 dark:border-[#3F4F43] font-['Inter'] transition-colors">
      
      <!-- Header -->
      <div class="flex items-center justify-between pb-4 mb-5 border-b border-gray-100 dark:border-[#3F4F43]">
        <div class="flex items-center gap-3">
          <div class="w-10 h-10 rounded-2xl bg-emerald-50 dark:bg-[#1C261E] border border-emerald-100 dark:border-[#3F4F43] flex items-center justify-center text-[#004d08] dark:text-[#86EFAC] shrink-0">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5m0 0h4m-4 0V11m0 0a2 2 0 100-4 2 2 0 000 4z" />
            </svg>
          </div>
          <div>
            <h3 class="text-xs font-medium uppercase tracking-wider text-gray-900 dark:text-white">
              Enroll Applicant
            </h3>
            <p class="text-[11px] text-gray-500 dark:text-gray-400 font-normal">
              Assign applicant to an official section and create student record
            </p>
          </div>
        </div>

        <button 
          type="button" 
          @click="closeModal" 
          class="p-1.5 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 rounded-xl hover:bg-gray-100 dark:hover:bg-white/5 transition-colors cursor-pointer"
        >
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>

      <!-- Applicant Summary Card -->
      <div v-if="application" class="mb-5 p-3.5 bg-gray-50 dark:bg-[#232D26] rounded-2xl border border-gray-200/80 dark:border-[#3F4F43] space-y-1">
        <div class="flex items-center justify-between">
          <span class="text-xs font-semibold text-gray-900 dark:text-white">
            {{ application.full_name || (application.first_name + ' ' + application.last_name) }}
          </span>
          <span class="text-[10px] font-mono text-gray-400">
            {{ application.reference_number || application.application_no }}
          </span>
        </div>
        <div class="text-[11px] text-gray-500 dark:text-gray-400 flex items-center gap-2">
          <span>Grade {{ application.desired_grade_level || application.grade_level }}</span>
          <span>&bull;</span>
          <span>{{ application.strand?.name || application.strand?.code || 'General Academic' }}</span>
        </div>
      </div>

      <!-- General Error Alert -->
      <div v-if="generalError" class="mb-4 p-3 bg-red-50 dark:bg-red-950/40 border border-red-200 dark:border-red-800 rounded-xl text-red-700 dark:text-red-300 text-xs">
        {{ generalError }}
      </div>

      <!-- Form -->
      <form @submit.prevent="submitEnrollment" class="space-y-4">
        
        <!-- Section Selection -->
        <div>
          <label class="block text-[11px] font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1">
            Target Section <span class="text-red-500">*</span>
          </label>
          <select 
            v-model="form.section_id"
            :disabled="isLoading"
            class="w-full px-3.5 py-2.5 text-xs bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white rounded-xl focus:ring-2 focus:ring-[#004d08] focus:outline-none font-normal cursor-pointer"
          >
            <option value="" disabled>-- Select Section --</option>
            <option 
              v-for="section in availableSections" 
              :key="section.id" 
              :value="section.id"
            >
              {{ section.name }} (Grade {{ section.grade_level }})
            </option>
          </select>
          <p v-if="errors.section_id" class="text-[10px] text-red-500 mt-1">{{ errors.section_id[0] }}</p>
        </div>

        <p class="text-[11px] text-gray-500 dark:text-gray-400 leading-relaxed font-normal bg-emerald-50/50 dark:bg-emerald-950/20 p-3 rounded-xl border border-emerald-100 dark:border-emerald-900/50">
          <strong>Note:</strong> Enrolling this applicant will automatically create their User and Student account, attach them to this section, and add them to all subject classrooms linked to the section.
        </p>

        <!-- Footer Actions -->
        <div class="mt-6 flex justify-end gap-2.5 pt-4 border-t border-gray-100 dark:border-[#3F4F43]">
          <button 
            type="button" 
            @click="closeModal" 
            class="px-4 py-2 text-xs font-normal text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-white/5 rounded-xl transition-all cursor-pointer"
          >
            Cancel
          </button>
          <button 
            type="submit" 
            :disabled="isLoading || !form.section_id"
            class="px-5 py-2 text-xs font-medium uppercase tracking-wider bg-[#004d08] text-white hover:bg-emerald-900 rounded-xl transition-all shadow-md active:scale-95 disabled:opacity-50 cursor-pointer"
          >
            {{ isLoading ? 'Enrolling...' : 'Confirm Enrollment' }}
          </button>
        </div>

      </form>
    </div>
  </Modal>
</template>

<script setup>
import { ref, reactive, watch, computed } from 'vue'
import axios from 'axios'
import Modal from '@/Components/Modal.vue'

const props = defineProps({
  show: { type: Boolean, default: false },
  application: { type: Object, default: null },
  sections: { type: Array, default: () => [] },
})

const emit = defineEmits(['close', 'enrolled'])

const isLoading = ref(false)
const generalError = ref('')
const errors = ref({})

const form = reactive({
  section_id: '',
})

// Optionally filter sections matching desired grade level if applicable
const availableSections = computed(() => {
  if (!props.application) return props.sections
  const targetGrade = props.application.desired_grade_level || props.application.grade_level
  if (!targetGrade) return props.sections

  const matched = props.sections.filter(s => Number(s.grade_level) === Number(targetGrade))
  return matched.length > 0 ? matched : props.sections
})

watch(() => props.show, (newVal) => {
  if (newVal) {
    resetForm()
  }
})

const resetForm = () => {
  form.section_id = ''
  errors.value = {}
  generalError.value = ''
}

const closeModal = () => {
  resetForm()
  emit('close')
}

const submitEnrollment = async () => {
  if (!props.application?.id || !form.section_id) return

  isLoading.value = true
  errors.value = {}
  generalError.value = ''

  try {
    await axios.post(`/api/admin/applicants/${props.application.id}/enroll`, {
      section_id: form.section_id,
    })

    emit('enrolled')
    closeModal()
  } catch (error) {
    if (error.response?.status === 422) {
      errors.value = error.response.data.errors || {}
      generalError.value = error.response.data.message || 'Validation failed.'
    } else {
      generalError.value = error.response?.data?.message || 'Failed to enroll applicant.'
    }
  } finally {
    isLoading.value = false
  }
}
</script>