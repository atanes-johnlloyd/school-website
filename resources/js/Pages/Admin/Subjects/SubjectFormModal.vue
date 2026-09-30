<template>
  <Modal :show="show" @close="closeModal" max-width="lg">
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
              {{ isEditing ? 'Edit Subject Details' : 'Register New Subject' }}
            </h3>
            <p class="text-[11px] text-gray-500 dark:text-gray-400 font-normal">
              {{ isEditing ? 'Update subject attributes and curriculum settings' : 'Add a new subject offering to the master curriculum' }}
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

      <!-- Form Body -->
      <form @submit.prevent="submit" class="space-y-4">
        <!-- Subject Code & Classification Type -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
          <div>
            <label class="block text-[11px] font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1.5">
              Subject Code <span class="text-red-500">*</span>
            </label>
            <input 
              v-model="form.code"
              type="text" 
              placeholder="e.g. MATH101"
              class="w-full px-3.5 py-2.5 text-xs bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white placeholder-gray-400 rounded-xl focus:ring-2 focus:ring-[#004d08] dark:focus:ring-[#86EFAC] focus:outline-none font-normal uppercase"
              :class="{ 'border-red-500 dark:border-red-500': form.errors.code }"
            />
            <p v-if="form.errors.code" class="mt-1 text-[11px] text-red-500 font-normal">{{ form.errors.code }}</p>
          </div>

          <div class="sm:col-span-2">
            <label class="block text-[11px] font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1.5">
              Subject Type / Category <span class="text-red-500">*</span>
            </label>
            <select 
              v-model="form.subject_type"
              class="w-full px-3.5 py-2.5 text-xs bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white rounded-xl focus:ring-2 focus:ring-[#004d08] focus:outline-none font-normal"
              :class="{ 'border-red-500 dark:border-red-500': form.errors.subject_type }"
            >
              <option value="Core">Core Subject</option>
              <option value="Applied">Applied Subject</option>
              <option value="Specialized">Specialized Strand Subject</option>
            </select>
            <p v-if="form.errors.subject_type" class="mt-1 text-[11px] text-red-500 font-normal">{{ form.errors.subject_type }}</p>
          </div>
        </div>

        <!-- Full Subject Name -->
        <div>
          <label class="block text-[11px] font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1.5">
            Subject Title / Name <span class="text-red-500">*</span>
          </label>
          <input 
            v-model="form.name"
            type="text" 
            placeholder="e.g. General Mathematics"
            class="w-full px-3.5 py-2.5 text-xs bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white placeholder-gray-400 rounded-xl focus:ring-2 focus:ring-[#004d08] dark:focus:ring-[#86EFAC] focus:outline-none font-normal"
            :class="{ 'border-red-500 dark:border-red-500': form.errors.name }"
          />
          <p v-if="form.errors.name" class="mt-1 text-[11px] text-red-500 font-normal">{{ form.errors.name }}</p>
        </div>

        <!-- Associated Strand & Hours/Units -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
          <div class="sm:col-span-2">
            <label class="block text-[11px] font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1.5">
              Assigned Strand <span v-if="form.subject_type === 'Specialized'" class="text-red-500">*</span>
            </label>
            <select 
              v-model="form.strand_id"
              :disabled="form.subject_type !== 'Specialized'"
              class="w-full px-3.5 py-2.5 text-xs bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white rounded-xl focus:ring-2 focus:ring-[#004d08] focus:outline-none font-normal disabled:opacity-50"
              :class="{ 'border-red-500 dark:border-red-500': form.errors.strand_id }"
            >
              <option :value="null">-- Applicable to All Strands --</option>
              <option v-for="st in strands" :key="st.id" :value="st.id">
                {{ st.code }} - {{ st.name }}
              </option>
            </select>
            <p v-if="form.errors.strand_id" class="mt-1 text-[11px] text-red-500 font-normal">{{ form.errors.strand_id }}</p>
          </div>

          <div>
            <label class="block text-[11px] font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1.5">
              Hours / Units <span class="text-red-500">*</span>
            </label>
            <input 
              v-model="form.units"
              type="number" 
              step="1"
              min="1"
              placeholder="e.g. 80"
              class="w-full px-3.5 py-2.5 text-xs bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white placeholder-gray-400 rounded-xl focus:ring-2 focus:ring-[#004d08] dark:focus:ring-[#86EFAC] focus:outline-none font-normal"
              :class="{ 'border-red-500 dark:border-red-500': form.errors.units }"
            />
            <p v-if="form.errors.units" class="mt-1 text-[11px] text-red-500 font-normal">{{ form.errors.units }}</p>
          </div>
        </div>

        <!-- Year Level & Semester Placement -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
          <div>
            <label class="block text-[11px] font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1.5">
              Year Level Offering
            </label>
            <select 
              v-model="form.year_level"
              class="w-full px-3.5 py-2.5 text-xs bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white rounded-xl focus:ring-2 focus:ring-[#004d08] focus:outline-none font-normal"
            >
              <option value="Grade 11">Grade 11</option>
              <option value="Grade 12">Grade 12</option>
              <option value="Both">Both Grade 11 & 12</option>
            </select>
          </div>

          <div>
            <label class="block text-[11px] font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1.5">
              Semester Offering
            </label>
            <select 
              v-model="form.semester"
              class="w-full px-3.5 py-2.5 text-xs bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white rounded-xl focus:ring-2 focus:ring-[#004d08] focus:outline-none font-normal"
            >
              <option value="1st Semester">1st Semester</option>
              <option value="2nd Semester">2nd Semester</option>
              <option value="Both">Both Semesters</option>
            </select>
          </div>
        </div>

        <!-- Description -->
        <div>
          <label class="block text-[11px] font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1.5">
            Subject Syllabus / Overview
          </label>
          <textarea 
            v-model="form.description"
            rows="2" 
            placeholder="Course overview, prerequisites, and learning objectives..."
            class="w-full px-3.5 py-2.5 text-xs bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white placeholder-gray-400 rounded-xl focus:ring-2 focus:ring-[#004d08] dark:focus:ring-[#86EFAC] focus:outline-none font-normal"
          ></textarea>
        </div>

        <!-- Active Toggle Switch -->
        <div class="pt-1">
          <label class="flex items-start gap-3 p-3 bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] rounded-xl hover:bg-gray-100/60 dark:hover:bg-white/5 transition-colors cursor-pointer">
            <input 
              v-model="form.is_active"
              type="checkbox" 
              class="mt-0.5 w-4 h-4 text-[#004d08] border-gray-300 rounded focus:ring-[#004d08] cursor-pointer"
            />
            <div class="space-y-0.5">
              <span class="block text-xs font-medium text-gray-900 dark:text-white">Active Status</span>
              <span class="block text-[10px] text-gray-500 dark:text-gray-400 font-normal">
                Active subjects can be scheduled in class timetables and gradebooks.
              </span>
            </div>
          </label>
        </div>

        <!-- Action Buttons -->
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
            :disabled="form.processing"
            class="px-5 py-2 text-xs font-medium uppercase tracking-wider bg-[#004d08] text-white hover:bg-emerald-900 rounded-xl transition-all shadow-md active:scale-95 disabled:opacity-50 cursor-pointer"
          >
            {{ form.processing ? 'Saving...' : (isEditing ? 'Save Changes' : 'Register Subject') }}
          </button>
        </div>
      </form>
    </div>
  </Modal>
</template>

<script setup>
import { computed, watch } from 'vue'
import { useForm } from '@inertiajs/vue3'
import Modal from '@/Components/Modal.vue'

const props = defineProps({
  show: {
    type: Boolean,
    default: false
  },
  subject: {
    type: Object,
    default: null
  },
  strands: {
    type: Array,
    default: () => []
  }
})

const emit = defineEmits(['close'])

const isEditing = computed(() => !!props.subject)

const form = useForm({
  code: '',
  name: '',
  subject_type: 'Core',
  strand_id: null,
  units: 80,
  year_level: 'Grade 11',
  semester: '1st Semester',
  description: '',
  is_active: true
})

watch(() => props.subject, (newVal) => {
  if (newVal) {
    form.code = newVal.code || ''
    form.name = newVal.name || ''
    form.subject_type = newVal.subject_type || 'Core'
    form.strand_id = newVal.strand_id || null
    form.units = newVal.units || 80
    form.year_level = newVal.year_level || 'Grade 11'
    form.semester = newVal.semester || '1st Semester'
    form.description = newVal.description || ''
    form.is_active = newVal.is_active !== undefined ? !!newVal.is_active : true
  } else {
    form.reset()
    form.subject_type = 'Core'
    form.year_level = 'Grade 11'
    form.semester = '1st Semester'
    form.units = 80
  }
}, { immediate: true })

const closeModal = () => {
  form.reset()
  form.clearErrors()
  emit('close')
}

const submit = () => {
  if (isEditing.value) {
    form.put(route('admin.subjects.update', props.subject.id), {
      onSuccess: () => closeModal()
    })
  } else {
    form.post(route('admin.subjects.store'), {
      onSuccess: () => closeModal()
    })
  }
}
</script>  