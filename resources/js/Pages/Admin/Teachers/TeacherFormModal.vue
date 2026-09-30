<template>
  <Modal :show="show" @close="closeModal" max-width="lg">
    <div class="p-6 bg-white dark:bg-[#2D3A31] rounded-3xl border border-gray-200 dark:border-[#3F4F43] font-['Inter'] transition-colors">
      <!-- Modal Header -->
      <div class="flex items-center justify-between pb-4 mb-5 border-b border-gray-100 dark:border-[#3F4F43]">
        <div class="flex items-center gap-3">
          <div class="w-10 h-10 rounded-2xl bg-emerald-50 dark:bg-[#1C261E] border border-emerald-100 dark:border-[#3F4F43] flex items-center justify-center text-[#004d08] dark:text-[#86EFAC] shrink-0">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
          </div>
          <div>
            <h3 class="text-xs font-medium uppercase tracking-wider text-gray-900 dark:text-white">
              {{ isEditing ? 'Edit Faculty Profile' : 'Register Faculty Member' }}
            </h3>
            <p class="text-[11px] text-gray-500 dark:text-gray-400 font-normal">
              {{ isEditing ? 'Update employment details and department assignment' : 'Add new teacher credentials to the directory' }}
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

      <form @submit.prevent="submit" class="space-y-4">

        <!-- Employee ID & Department -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
          <div>
            <label class="block text-[11px] font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1.5">
              Employee ID <span class="text-red-500">*</span>
            </label>
            <input v-model="form.employee_no" type="text" placeholder="e.g. T-2026-001"
                  class="w-full px-3.5 py-2.5 text-xs bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white rounded-xl focus:ring-2 focus:ring-[#004d08] focus:outline-none font-normal"
                  :class="{ 'border-red-500': form.errors.employee_no }" />
            <p v-if="form.errors.employee_no" class="mt-1 text-[11px] text-red-500">{{ form.errors.employee_no }}</p>
          </div>

          <div>
            <label class="block text-[11px] font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1.5">
              Department
            </label>
            <input v-model="form.department" type="text" placeholder="e.g. Mathematics"
                  class="w-full px-3.5 py-2.5 text-xs bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white rounded-xl focus:ring-2 focus:ring-[#004d08] focus:outline-none font-normal" />
          </div>
        </div>

        <!-- Full Name & Email -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
          <div>
            <label class="block text-[11px] font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1.5">
              Full Name <span class="text-red-500">*</span>
            </label>
            <input v-model="form.name" type="text" placeholder="e.g. Juan Dela Cruz"
                  class="w-full px-3.5 py-2.5 text-xs bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white rounded-xl focus:ring-2 focus:ring-[#004d08] focus:outline-none font-normal"
                  :class="{ 'border-red-500': form.errors.name }" />
            <p v-if="form.errors.name" class="mt-1 text-[11px] text-red-500">{{ form.errors.name }}</p>
          </div>

          <div>
            <label class="block text-[11px] font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1.5">
              Email Address <span class="text-red-500">*</span>
            </label>
            <input v-model="form.email" type="email" placeholder="teacher@school.edu.ph"
                  class="w-full px-3.5 py-2.5 text-xs bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white rounded-xl focus:ring-2 focus:ring-[#004d08] focus:outline-none font-normal"
                  :class="{ 'border-red-500': form.errors.email }" />
            <p v-if="form.errors.email" class="mt-1 text-[11px] text-red-500">{{ form.errors.email }}</p>
          </div>
        </div>

        <!-- Password (create only) -->
        <div v-if="!isEditing">
          <label class="block text-[11px] font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1.5">
            Initial Password <span class="text-red-500">*</span>
          </label>
          <input v-model="form.password" type="password" placeholder="••••••••"
                class="w-full px-3.5 py-2.5 text-xs bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white rounded-xl focus:ring-2 focus:ring-[#004d08] focus:outline-none font-normal"
                :class="{ 'border-red-500': form.errors.password }" />
          <p v-if="form.errors.password" class="mt-1 text-[11px] text-red-500">{{ form.errors.password }}</p>
        </div>

        <!-- Sex, DOB, Contact -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
          <div>
            <label class="block text-[11px] font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1.5">Sex</label>
            <select v-model="form.sex"
                    class="w-full px-3.5 py-2.5 text-xs bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white rounded-xl focus:ring-2 focus:ring-[#004d08] focus:outline-none font-normal cursor-pointer">
              <option value="">Select...</option>
              <option value="male">Male</option>
              <option value="female">Female</option>
            </select>
          </div>

          <div>
            <label class="block text-[11px] font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1.5">Date of Birth</label>
            <input v-model="form.date_of_birth" type="date"
                  class="w-full px-3.5 py-2.5 text-xs bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white rounded-xl focus:ring-2 focus:ring-[#004d08] focus:outline-none font-normal cursor-pointer" />
          </div>

          <div>
            <label class="block text-[11px] font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1.5">Contact No.</label>
            <input v-model="form.contact_number" type="text" placeholder="+63 912 345 6789"
                  class="w-full px-3.5 py-2.5 text-xs bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white rounded-xl focus:ring-2 focus:ring-[#004d08] focus:outline-none font-normal" />
          </div>
        </div>

        <!-- Date Hired & Specialization -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
          <div>
            <label class="block text-[11px] font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1.5">Date Hired</label>
            <input v-model="form.date_hired" type="date"
                  class="w-full px-3.5 py-2.5 text-xs bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white rounded-xl focus:ring-2 focus:ring-[#004d08] focus:outline-none font-normal cursor-pointer" />
          </div>

          <div>
            <label class="block text-[11px] font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1.5">Specialization</label>
            <input v-model="form.specialization" type="text" placeholder="e.g. Algebra and Geometry"
                  class="w-full px-3.5 py-2.5 text-xs bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white rounded-xl focus:ring-2 focus:ring-[#004d08] focus:outline-none font-normal" />
          </div>
        </div>

        <!-- Active toggle -->
        <div class="pt-2">
          <label class="flex items-start gap-3 p-3 bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] rounded-xl hover:bg-gray-100/60 dark:hover:bg-white/5 transition-colors cursor-pointer">
            <input v-model="form.is_active" type="checkbox"
                  class="mt-0.5 w-4 h-4 text-[#004d08] border-gray-300 rounded focus:ring-[#004d08] cursor-pointer" />
            <div class="space-y-0.5">
              <span class="block text-xs font-medium text-gray-900 dark:text-white">Active Employment Status</span>
              <span class="block text-[10px] text-gray-500 dark:text-gray-400 font-normal">
                Active faculty can be assigned to subjects and class sections.
              </span>
            </div>
          </label>
        </div>

        <!-- Footer -->
        <div class="mt-6 flex justify-end gap-2.5 pt-4 border-t border-gray-100 dark:border-[#3F4F43]">
          <button type="button" @click="closeModal"
                  class="px-4 py-2 text-xs font-normal text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-white/5 rounded-xl transition-all cursor-pointer">
            Cancel
          </button>
          <button type="submit" :disabled="form.processing"
                  class="px-5 py-2 text-xs font-medium uppercase tracking-wider bg-[#004d08] text-white hover:bg-emerald-900 rounded-xl transition-all shadow-md active:scale-95 disabled:opacity-50 cursor-pointer">
            {{ form.processing ? 'Saving...' : (isEditing ? 'Save Changes' : 'Register Faculty') }}
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
  show:    { type: Boolean, default: false },
  teacher: { type: Object,  default: null },
})

const emit = defineEmits(['close'])

const isEditing = computed(() => !!props.teacher)

const form = useForm({
  employee_no:    '',
  name:           '',
  email:          '',
  password:       '',
  sex:            '',
  date_of_birth:  '',
  contact_number: '',
  date_hired:     '',
  department:     '',
  specialization: '',
  is_active:      true,
})

watch(() => props.teacher, (newVal) => {
  if (newVal) {
    form.employee_no    = newVal.employee_no || ''
    form.name           = newVal.name || newVal.user?.name || ''
    form.email          = newVal.email || newVal.user?.email || ''
    form.password       = ''
    form.sex            = newVal.sex || ''
    form.date_of_birth  = newVal.date_of_birth || ''
    form.contact_number = newVal.contact_number || ''
    form.date_hired     = newVal.date_hired || ''
    form.department     = newVal.department || ''
    form.specialization = newVal.specialization || ''
    form.is_active      = newVal.is_active !== undefined ? !!newVal.is_active : true
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
    form.put(route('admin.teachers.update', props.teacher.id), {
      onSuccess: () => closeModal(),
    })
  } else {
    form.post(route('admin.teachers.store'), {
      onSuccess: () => closeModal(),
    })
  }
}
</script>