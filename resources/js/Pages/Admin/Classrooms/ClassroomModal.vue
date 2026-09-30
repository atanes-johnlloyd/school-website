<template>
  <Modal :show="show" @close="closeModal" max-width="md">
    <div class="p-6 bg-white dark:bg-[#2D3A31] rounded-3xl border border-gray-200 dark:border-[#3F4F43] font-['Inter'] transition-colors">
      
      <!-- Header -->
      <div class="flex items-center justify-between pb-4 mb-5 border-b border-gray-100 dark:border-[#3F4F43]">
        <div class="flex items-center gap-3">
          <div class="w-10 h-10 rounded-2xl bg-emerald-50 dark:bg-[#1C261E] border border-emerald-100 dark:border-[#3F4F43] flex items-center justify-center text-[#004d08] dark:text-[#86EFAC] shrink-0">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
            </svg>
          </div>
          <div>
            <h3 class="text-xs font-medium uppercase tracking-wider text-gray-900 dark:text-white">
              {{ isEditing ? 'Edit Subject Classroom' : 'Create Subject Classroom' }}
            </h3>
            <p class="text-[11px] text-gray-500 dark:text-gray-400 font-normal">
              {{ isEditing ? 'Update teacher, room, and schedule' : 'Assign a subject to a section' }}
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

      <!-- General Error Alert -->
      <div v-if="generalError" class="mb-4 p-3 bg-red-50 dark:bg-red-950/40 border border-red-200 dark:border-red-800 rounded-xl text-red-700 dark:text-red-300 text-xs">
        {{ generalError }}
      </div>

      <!-- Form Body -->
      <form @submit.prevent="submitForm" class="space-y-4">
        
        <!-- Section Selection (Disabled on edit) -->
        <div>
          <label class="block text-[11px] font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1">
            Section <span class="text-red-500">*</span>
          </label>
          <select 
            v-model="form.section_id"
            :disabled="isEditing || isLoading"
            class="w-full px-3.5 py-2.5 text-xs bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white rounded-xl focus:ring-2 focus:ring-[#004d08] focus:outline-none disabled:opacity-60 font-normal"
          >
            <option value="" disabled>-- Select Section --</option>
            <option v-for="section in sections" :key="section.id" :value="section.id">
              {{ section.name }} (Grade {{ section.grade_level }})
            </option>
          </select>
          <p v-if="errors.section_id" class="text-[10px] text-red-500 mt-1">{{ errors.section_id[0] }}</p>
        </div>

        <!-- Subject Selection (Disabled on edit) -->
        <div>
          <label class="block text-[11px] font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1">
            Subject <span class="text-red-500">*</span>
          </label>
          <select 
            v-model="form.subject_id"
            :disabled="isEditing || isLoading"
            class="w-full px-3.5 py-2.5 text-xs bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white rounded-xl focus:ring-2 focus:ring-[#004d08] focus:outline-none disabled:opacity-60 font-normal"
          >
            <option value="" disabled>-- Select Subject --</option>
            <option v-for="subject in subjects" :key="subject.id" :value="subject.id">
              {{ subject.code }} - {{ subject.name }}
            </option>
          </select>
          <p v-if="errors.subject_id" class="text-[10px] text-red-500 mt-1">{{ errors.subject_id[0] }}</p>
        </div>

        <!-- Assigned Teacher -->
        <div>
          <label class="block text-[11px] font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1">
            Assigned Subject Teacher
          </label>
          <select 
            v-model="form.teacher_id"
            :disabled="isLoading"
            class="w-full px-3.5 py-2.5 text-xs bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white rounded-xl focus:ring-2 focus:ring-[#004d08] focus:outline-none font-normal"
          >
            <option :value="null">-- Unassigned --</option>
            <option v-for="teacher in teachers" :key="teacher.id" :value="teacher.id">
              {{ teacher.user?.name || teacher.name }}
            </option>
          </select>
          <p v-if="errors.teacher_id" class="text-[10px] text-red-500 mt-1">{{ errors.teacher_id[0] }}</p>
        </div>

        <!-- Schedule & Room Number -->
        <div class="grid grid-cols-2 gap-3">
          <div>
            <label class="block text-[11px] font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1">
              Room Number
            </label>
            <input 
              v-model="form.room_number"
              type="text" 
              placeholder="e.g. Lab 201"
              :disabled="isLoading"
              class="w-full px-3.5 py-2.5 text-xs bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white rounded-xl focus:ring-2 focus:ring-[#004d08] focus:outline-none font-normal"
            />
            <p v-if="errors.room_number" class="text-[10px] text-red-500 mt-1">{{ errors.room_number[0] }}</p>
          </div>

          <div>
            <label class="block text-[11px] font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1">
              Schedule
            </label>
            <input 
              v-model="form.schedule"
              type="text" 
              placeholder="e.g. MWF 8:00-9:00 AM"
              :disabled="isLoading"
              class="w-full px-3.5 py-2.5 text-xs bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white rounded-xl focus:ring-2 focus:ring-[#004d08] focus:outline-none font-normal"
            />
            <p v-if="errors.schedule" class="text-[10px] text-red-500 mt-1">{{ errors.schedule[0] }}</p>
          </div>
        </div>

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
            :disabled="isLoading"
            class="px-5 py-2 text-xs font-medium uppercase tracking-wider bg-[#004d08] text-white hover:bg-emerald-900 rounded-xl transition-all shadow-md active:scale-95 disabled:opacity-50 cursor-pointer"
          >
            {{ isLoading ? 'Saving...' : (isEditing ? 'Update Classroom' : 'Create Classroom') }}
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
  classroom: { type: Object, default: null },
  sections: { type: Array, default: () => [] },
  subjects: { type: Array, default: () => [] },
  teachers: { type: Array, default: () => [] },
})

const emit = defineEmits(['close', 'saved'])

const isEditing = computed(() => !!props.classroom?.id)
const isLoading = ref(false)
const generalError = ref('')
const errors = ref({})

const form = reactive({
  section_id: '',
  subject_id: '',
  teacher_id: null,
  room_number: '',
  schedule: '',
})

watch(() => props.classroom, (newVal) => {
  if (newVal) {
    form.section_id = newVal.section_id || ''
    form.subject_id = newVal.subject_id || ''
    form.teacher_id = newVal.teacher_id || null
    form.room_number = newVal.room_number || ''
    form.schedule = newVal.schedule || ''
  } else {
    resetForm()
  }
}, { immediate: true })

const resetForm = () => {
  form.section_id = ''
  form.subject_id = ''
  form.teacher_id = null
  form.room_number = ''
  form.schedule = ''
  errors.value = {}
  generalError.value = ''
}

const closeModal = () => {
  resetForm()
  emit('close')
}

const submitForm = async () => {
  isLoading.value = true
  errors.value = {}
  generalError.value = ''

  try {
    if (isEditing.value) {
      await axios.put(`/api/admin/classrooms/${props.classroom.id}`, {
        teacher_id: form.teacher_id,
        room_number: form.room_number,
        schedule: form.schedule,
      })
    } else {
      await axios.post('/api/admin/classrooms', form)
    }

    emit('saved')
    closeModal()
  } catch (error) {
    if (error.response?.status === 422) {
      errors.value = error.response.data.errors || {}
      if (error.response.data.message && !Object.keys(errors.value).length) {
        generalError.value = error.response.data.message
      }
    } else {
      generalError.value = 'An unexpected error occurred.'
    }
  } finally {
    isLoading.value = false
  }
}
</script>