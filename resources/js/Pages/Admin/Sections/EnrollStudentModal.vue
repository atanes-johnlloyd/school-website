<template>
  <Modal :show="show" @close="closeModal" max-width="md">
    <div class="p-6 bg-white dark:bg-[#2D3A31] rounded-3xl border border-gray-200 dark:border-[#3F4F43] font-['Inter'] transition-colors">
      <!-- Header -->
      <div class="flex items-center justify-between pb-4 mb-5 border-b border-gray-100 dark:border-[#3F4F43]">
        <div class="flex items-center gap-3">
          <div class="w-10 h-10 rounded-2xl bg-emerald-50 dark:bg-[#1C261E] border border-emerald-100 dark:border-[#3F4F43] flex items-center justify-center text-[#004d08] dark:text-[#86EFAC] shrink-0">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
            </svg>
          </div>
          <div>
            <h3 class="text-xs font-medium uppercase tracking-wider text-gray-900 dark:text-white">
              Enroll Student
            </h3>
            <p class="text-[11px] text-gray-500 dark:text-gray-400 font-normal">
              Assign student to {{ section?.name }} (Grade {{ section?.grade_level }})
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

      <!-- Capacity Warning Badge -->
      <div 
        v-if="isAtCapacity"
        class="mb-4 p-3 bg-amber-50 dark:bg-amber-950/40 border border-amber-200 dark:border-amber-800 rounded-xl text-amber-800 dark:text-amber-300 text-xs flex items-center gap-2"
      >
        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
        </svg>
        <span>This section has reached its maximum capacity of {{ section?.max_capacity }} students.</span>
      </div>

      <!-- Error Alert -->
      <div 
        v-if="errorMessage"
        class="mb-4 p-3 bg-red-50 dark:bg-red-950/40 border border-red-200 dark:border-red-800 rounded-xl text-red-700 dark:text-red-300 text-xs"
      >
        {{ errorMessage }}
      </div>

      <!-- Form -->
      <form @submit.prevent="submitEnrollment" class="space-y-4">
        <div>
          <label class="block text-[11px] font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1.5">
            Select Student <span class="text-red-500">*</span>
          </label>
          <select 
            v-model="selectedStudentId"
            :disabled="isAtCapacity || isLoading"
            class="w-full px-3.5 py-2.5 text-xs bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white rounded-xl focus:ring-2 focus:ring-[#004d08] focus:outline-none font-normal disabled:opacity-50"
          >
            <option :value="null" disabled>-- Choose Eligible Student --</option>
            <option v-for="student in availableStudents" :key="student.id" :value="student.id">
              {{ student.user?.name || student.name }} (ID: {{ student.id }})
            </option>
          </select>
        </div>

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
            :disabled="!selectedStudentId || isAtCapacity || isLoading"
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
import { ref, computed } from 'vue'
import axios from 'axios'
import Modal from '@/Components/Modal.vue'

const props = defineProps({
  show: {
    type: Boolean,
    default: false
  },
  section: {
    type: Object,
    default: null
  },
  availableStudents: {
    type: Array,
    default: () => []
  }
})

const emit = defineEmits(['close', 'enrolled'])

const selectedStudentId = ref(null)
const isLoading = ref(false)
const errorMessage = ref('')

const isAtCapacity = computed(() => {
  if (!props.section) return false
  return (props.section.students?.length || 0) >= (props.section.max_capacity || Infinity)
})

const closeModal = () => {
  selectedStudentId.value = null
  errorMessage.value = ''
  emit('close')
}

const submitEnrollment = async () => {
  if (!selectedStudentId.value || !props.section?.id) return

  isLoading.value = true
  errorMessage.value = ''

  try {
    const response = await axios.post(`/api/admin/sections/${props.section.id}/enroll`, {
      student_id: selectedStudentId.value
    })
    
    emit('enrolled', response.data)
    closeModal()
  } catch (error) {
    if (error.response && error.response.data && error.response.data.message) {
      errorMessage.value = error.response.data.message
    } else {
      errorMessage.value = 'An unexpected error occurred while enrolling the student.'
    }
  } finally {
    isLoading.value = false
  }
}
</script>