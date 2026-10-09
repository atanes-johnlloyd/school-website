<template>
  <Modal :show="show" @close="closeModal" max-width="lg">
    <div class="bg-white dark:bg-[#2D3A31] rounded-2xl border border-gray-200 dark:border-[#3F4F43] font-['Inter'] transition-colors">

      <!-- Header -->
      <div class="flex items-center justify-between px-6 pt-5 pb-4 border-b border-gray-100 dark:border-[#3F4F43]">
        <div class="flex items-center gap-3">
          <div class="w-10 h-10 rounded-2xl bg-emerald-50 dark:bg-[#1C261E] border border-emerald-100 dark:border-[#3F4F43] flex items-center justify-center text-[#004d08] dark:text-[#86EFAC] shrink-0">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
            </svg>
          </div>
          <div>
            <h3 class="text-xs font-medium uppercase tracking-wider text-gray-900 dark:text-white">
              {{ isEditing ? 'Edit Admin User' : 'Create Admin User' }}
            </h3>
            <p class="text-[11px] text-gray-500 dark:text-gray-400 font-normal">
              {{ isEditing ? 'Update account details and assigned position' : 'A temporary password will be generated and emailed to the new user' }}
            </p>
          </div>
        </div>

        <button type="button" @click="closeModal"
                class="p-1.5 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 rounded-xl hover:bg-gray-100 dark:hover:bg-white/5 transition-colors cursor-pointer">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>

      <!-- Error banner -->
      <div v-if="generalError" class="mx-6 mt-4 p-3 bg-red-50 dark:bg-red-950/40 border border-red-200 dark:border-red-800 rounded-xl text-red-700 dark:text-red-300 text-xs">
        {{ generalError }}
      </div>

      <!-- Form -->
      <form @submit.prevent="submit" class="px-6 py-5 space-y-4">

        <div>
          <label class="block text-[11px] font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1.5">
            Full Name <span class="text-red-500">*</span>
          </label>
          <input v-model="form.name" type="text" placeholder="e.g. Juan Dela Cruz"
                 class="w-full px-3.5 py-2.5 text-xs bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white rounded-xl focus:ring-2 focus:ring-[#004d08] focus:outline-none font-normal"
                 :class="{ 'border-red-500': errors.name }" />
          <p v-if="errors.name" class="mt-1 text-[11px] text-red-500">{{ errors.name[0] }}</p>
        </div>

        <div>
          <label class="block text-[11px] font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1.5">
            Email Address <span class="text-red-500">*</span>
          </label>
          <input v-model="form.email" type="email" placeholder="user@school.edu.ph"
                 class="w-full px-3.5 py-2.5 text-xs bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white rounded-xl focus:ring-2 focus:ring-[#004d08] focus:outline-none font-normal"
                 :class="{ 'border-red-500': errors.email }" />
          <p v-if="errors.email" class="mt-1 text-[11px] text-red-500">{{ errors.email[0] }}</p>
        </div>

        <div>
          <label class="block text-[11px] font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1.5">
            Admin Position <span class="text-red-500">*</span>
          </label>
          <select v-model="form.admin_position_id"
                  class="w-full px-3.5 py-2.5 text-xs bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white rounded-xl focus:ring-2 focus:ring-[#004d08] focus:outline-none font-normal cursor-pointer"
                  :class="{ 'border-red-500': errors.admin_position_id }">
            <option value="" disabled>Select a position...</option>
            <option v-for="p in positions" :key="p.id" :value="p.id">{{ p.name }}</option>
          </select>
          <p v-if="errors.admin_position_id" class="mt-1 text-[11px] text-red-500">{{ errors.admin_position_id[0] }}</p>
          <p v-if="selectedPosition?.description" class="mt-1.5 text-[10px] text-gray-500 dark:text-gray-400 italic">
            {{ selectedPosition.description }}
          </p>
        </div>

        <!-- Info banner -->
        <div v-if="!isEditing" class="p-3 bg-emerald-50/50 dark:bg-emerald-950/20 border border-emerald-100 dark:border-emerald-900/50 rounded-xl text-[11px] text-gray-700 dark:text-gray-300 leading-relaxed">
          <strong class="font-semibold text-gray-900 dark:text-white">Note:</strong>
          The new admin will receive an email with a temporary password. They'll be required to change it on first login. Permissions are granted automatically based on the selected position.
        </div>

        <!-- Footer -->
        <div class="mt-6 flex justify-end gap-2.5 pt-4 border-t border-gray-100 dark:border-[#3F4F43]">
          <button type="button" @click="closeModal"
                  class="px-4 py-2 text-xs font-normal text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-white/5 rounded-xl transition-all cursor-pointer">
            Cancel
          </button>
          <button type="submit" :disabled="isLoading"
                  class="px-5 py-2 text-xs font-medium uppercase tracking-wider bg-[#004d08] text-white hover:bg-emerald-900 rounded-xl transition-all shadow-md active:scale-95 disabled:opacity-50 cursor-pointer">
            {{ isLoading ? 'Saving…' : (isEditing ? 'Save Changes' : 'Create User') }}
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
  show:      { type: Boolean, default: false },
  user:      { type: Object,  default: null },
  positions: { type: Array,   default: () => [] },
})

const emit = defineEmits(['close', 'saved'])

const flash = useFlash()
const isLoading    = ref(false)
const generalError = ref('')
const errors       = ref({})

const isEditing = computed(() => !!props.user?.id)

const form = reactive({
  name: '',
  email: '',
  admin_position_id: '',
})

const selectedPosition = computed(() =>
  props.positions.find(p => p.id === form.admin_position_id)
)

watch(() => props.show, (open) => {
  if (open) populate()
})

const populate = () => {
  errors.value = {}
  generalError.value = ''

  if (props.user) {
    form.name = props.user.name || ''
    form.email = props.user.email || ''
    form.admin_position_id = props.user.admin_position_id || ''
  } else {
    form.name = ''
    form.email = ''
    form.admin_position_id = ''
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
    const url = isEditing.value ? `/admin/users/${props.user.id}` : '/admin/users'
    const method = isEditing.value ? 'put' : 'post'
    const { data } = await axios[method](url, form)

    flash.success(data?.message || (isEditing.value ? 'User updated.' : 'Admin user created.'))

    emit('saved')
    closeModal()
  } catch (error) {
    if (error.response?.status === 422) {
      errors.value = error.response.data.errors || {}
      generalError.value = error.response.data.message || 'Please fix the highlighted fields.'
    } else {
      generalError.value = error.response?.data?.message || 'Failed to save user.'
    }
    flash.error(generalError.value)
  } finally {
    isLoading.value = false
  }
}
</script>