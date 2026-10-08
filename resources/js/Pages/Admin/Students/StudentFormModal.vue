<template>
  <Modal :show="show" @close="closeModal" max-width="lg">
    <div class="p-6 bg-white dark:bg-[#2D3A31] rounded-3xl border border-gray-200 dark:border-[#3F4F43] font-['Inter'] transition-colors">
      <!-- Modal Header -->
      <div class="flex items-center justify-between pb-4 mb-5 border-b border-gray-100 dark:border-[#3F4F43]">
        <div class="flex items-center gap-3">
          <div class="w-10 h-10 rounded-2xl bg-emerald-50 dark:bg-[#1C261E] border border-emerald-100 dark:border-[#3F4F43] flex items-center justify-center text-[#004d08] dark:text-[#86EFAC] shrink-0">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l9-5-9-5-9 5 9 5z" />
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0112 20.055a11.952 11.952 0 01-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
            </svg>
          </div>
          <div>
            <h3 class="text-xs font-medium uppercase tracking-wider text-gray-900 dark:text-white">
              {{ isEditing ? 'Edit Student Profile' : 'Register New Student' }}
            </h3>
            <p class="text-[11px] text-gray-500 dark:text-gray-400 font-normal">
              {{ isEditing ? 'Update student enrollment and personal records' : 'Create new student account and assign academic placement' }}
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
        <!-- Full Name & LRN / Student No -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
          <div class="sm:col-span-2">
            <label class="block text-[11px] font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1.5">
              Full Name <span class="text-red-500">*</span>
            </label>
            <input 
              v-model="form.name"
              type="text" 
              placeholder="e.g. Maria Santos"
              class="w-full px-3.5 py-2.5 text-xs bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white placeholder-gray-400 rounded-xl focus:ring-2 focus:ring-[#004d08] dark:focus:ring-[#86EFAC] focus:outline-none font-normal"
              :class="{ 'border-red-500 dark:border-red-500': form.errors.name }"
            />
            <p v-if="form.errors.name" class="mt-1 text-[11px] text-red-500 font-normal">{{ form.errors.name }}</p>
          </div>

          <div>
            <label class="block text-[11px] font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1.5">
              LRN / Student No. <span class="text-red-500">*</span>
            </label>
            <input 
              v-model="form.lrn"
              type="text" 
              placeholder="123456789012"
              class="w-full px-3.5 py-2.5 text-xs bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white placeholder-gray-400 rounded-xl focus:ring-2 focus:ring-[#004d08] dark:focus:ring-[#86EFAC] focus:outline-none font-normal uppercase"
              :class="{ 'border-red-500 dark:border-red-500': form.errors.lrn }"
            />
            <p v-if="form.errors.lrn" class="mt-1 text-[11px] text-red-500 font-normal">{{ form.errors.lrn }}</p>
          </div>
        </div>

        <!-- Email & Password -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
          <div>
            <label class="block text-[11px] font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1.5">
              Email Address <span class="text-red-500">*</span>
            </label>
            <input 
              v-model="form.email"
              type="email" 
              placeholder="student@school.edu.ph"
              class="w-full px-3.5 py-2.5 text-xs bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white placeholder-gray-400 rounded-xl focus:ring-2 focus:ring-[#004d08] dark:focus:ring-[#86EFAC] focus:outline-none font-normal"
              :class="{ 'border-red-500 dark:border-red-500': form.errors.email }"
            />
            <p v-if="form.errors.email" class="mt-1 text-[11px] text-red-500 font-normal">{{ form.errors.email }}</p>
          </div>

          <div v-if="!isEditing">
            <label class="block text-[11px] font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1.5">
              Initial Password <span class="text-red-500">*</span>
            </label>
            <input 
              v-model="form.password"
              type="password" 
              placeholder="••••••••"
              class="w-full px-3.5 py-2.5 text-xs bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white placeholder-gray-400 rounded-xl focus:ring-2 focus:ring-[#004d08] dark:focus:ring-[#86EFAC] focus:outline-none font-normal"
              :class="{ 'border-red-500 dark:border-red-500': form.errors.password }"
            />
            <p v-if="form.errors.password" class="mt-1 text-[11px] text-red-500 font-normal">{{ form.errors.password }}</p>
          </div>

          <div v-else>
            <label class="block text-[11px] font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1.5">
              Contact Number
            </label>
            <input 
              v-model="form.contact_number"
              type="text" 
              placeholder="+63 912 345 6789"
              class="w-full px-3.5 py-2.5 text-xs bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white placeholder-gray-400 rounded-xl focus:ring-2 focus:ring-[#004d08] dark:focus:ring-[#86EFAC] focus:outline-none font-normal"
            />
          </div>
        </div>

        <!-- Academic Placement: Grade Level & Section -->
          <div>
            <label class="block text-[11px] font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1.5">
              Section
            </label>
            <select
              v-model="form.section_id"
              class="w-full px-3.5 py-2.5 text-xs bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white rounded-xl focus:ring-2 focus:ring-[#004d08] focus:outline-none font-normal cursor-pointer"
              :class="{ 'border-red-500 dark:border-red-500': form.errors.section_id }"
            >
              <option value="">Unassigned</option>
              <option v-for="s in sections" :key="s.id" :value="s.id">
                Grade {{ s.grade_level }} — {{ s.name }}
              </option>
            </select>
            <p v-if="form.errors.section_id" class="mt-1 text-[11px] text-red-500 font-normal">{{ form.errors.section_id }}</p>
          </div>
        

        <!-- Personal Info: Sex & Date of Birth -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
          <div>
            <label class="block text-[11px] font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1.5">
              Sex
            </label>
            <select 
              v-model="form.sex"
              class="w-full px-3.5 py-2.5 text-xs bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white rounded-xl focus:ring-2 focus:ring-[#004d08] focus:outline-none font-normal"
            >
              <option value="">Select...</option>
              <option value="male">Male</option>
              <option value="female">Female</option>
            </select>
          </div>

          <div>
            <label class="block text-[11px] font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1.5">
              Date of Birth
            </label>
            <input 
              v-model="form.date_of_birth"
              type="date" 
              class="w-full px-3.5 py-2.5 text-xs bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white rounded-xl focus:ring-2 focus:ring-[#004d08] focus:outline-none font-normal"
            />
          </div>
        </div>

        <!-- Guardian Emergency Contact -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
          <div>
            <label class="block text-[11px] font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1.5">
              Parent / Guardian Name
            </label>
            <input 
              v-model="form.guardian_name"
              type="text" 
              placeholder="e.g. Roberto Santos"
              class="w-full px-3.5 py-2.5 text-xs bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white placeholder-gray-400 rounded-xl focus:ring-2 focus:ring-[#004d08] dark:focus:ring-[#86EFAC] focus:outline-none font-normal"
            />
          </div>

          <div>
            <label class="block text-[11px] font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1.5">
              Guardian Contact
            </label>
            <input 
              v-model="form.guardian_contact"
              type="text" 
              placeholder="+63 918 765 4321"
              class="w-full px-3.5 py-2.5 text-xs bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white placeholder-gray-400 rounded-xl focus:ring-2 focus:ring-[#004d08] dark:focus:ring-[#86EFAC] focus:outline-none font-normal"
            />
          </div>
        </div>

        <!-- Active Toggle Switch (Editing only) -->
        <div v-if="isEditing" class="pt-2">
          <label class="block text-[11px] font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1.5">
            Enrollment Status
          </label>
          <select
            v-model="form.status"
            class="w-full px-3.5 py-2.5 text-xs bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white rounded-xl focus:ring-2 focus:ring-[#004d08] focus:outline-none font-normal cursor-pointer capitalize"
          >
            <option value="active">Active</option>
            <option value="graduated">Graduated</option>
            <option value="dropped_out">Dropped Out</option>
            <option value="transferred_out">Transferred Out</option>
          </select>
        </div>

        <!-- Footer Action Buttons -->
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
            {{ form.processing ? 'Saving...' : (isEditing ? 'Save Changes' : 'Register Student') }}
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
  student: {
    type: Object,
    default: null
  },
  sections: { type: Array,   default: () => [] },
})

const emit = defineEmits(['close'])

const isEditing = computed(() => !!props.student)

const form = useForm({
  name: '',
  email: '',
  password: '',
  lrn: '',
  section_id: '',      // ← replaces grade_level + section
  sex: '',
  date_of_birth: '',
  contact_number: '',
  guardian_name: '',
  guardian_contact: '',
  status: 'active',    // ← replaces is_active
})

watch(() => props.student, (newVal) => {
  if (newVal) {
    form.name             = newVal.user?.name || ''
    form.email            = newVal.user?.email || ''
    form.password         = ''
    form.lrn              = newVal.lrn || ''
    form.section_id       = newVal.section_id || ''
    form.sex              = newVal.sex || ''
    form.date_of_birth    = newVal.date_of_birth || ''
    form.contact_number   = newVal.contact_number || ''
    form.guardian_name    = newVal.guardian_name || ''
    form.guardian_contact = newVal.guardian_contact || ''
    form.status           = newVal.status || 'active'
  } else {
    form.reset()
  }
}, { immediate: true })

const closeModal = () => {
  form.reset()
  form.clearErrors()
  emit('close')
}

const submit = () => {
  if (isEditing.value) {
    form.put(route('admin.students.update', props.student.id), {
      onSuccess: () => closeModal()
    })
  } else {
    form.post(route('admin.students.store'), {
      onSuccess: () => closeModal()
    })
  }
}
</script>