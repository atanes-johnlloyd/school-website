<template>
  <Modal :show="show" @close="closeModal" max-width="lg">
    <div class="bg-white dark:bg-[#2D3A31] rounded-2xl border border-gray-200 dark:border-[#3F4F43] font-['Inter']">

      <div class="flex items-center justify-between px-6 pt-5 pb-4 border-b border-gray-100 dark:border-[#3F4F43]">
        <div class="flex items-center gap-3">
          <div class="w-10 h-10 rounded-2xl bg-emerald-50 dark:bg-[#1C261E] border border-emerald-100 dark:border-[#3F4F43] flex items-center justify-center text-[#004d08] dark:text-[#86EFAC] shrink-0">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
          </div>
          <div>
            <h3 class="text-xs font-medium uppercase tracking-wider text-gray-900 dark:text-white">
              {{ isEditing ? 'Edit School Year' : 'New School Year' }}
            </h3>
            <p class="text-[11px] text-gray-500 dark:text-gray-400 font-normal">
              {{ isEditing ? 'Update academic year details' : 'Define a new academic year' }}
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

      <div v-if="generalError" class="mx-6 mt-4 p-3 bg-red-50 dark:bg-red-950/40 border border-red-200 dark:border-red-800 rounded-xl text-red-700 dark:text-red-300 text-xs">
        {{ generalError }}
      </div>

      <form @submit.prevent="submit" class="px-6 py-5 space-y-4">

        <div>
          <label class="block text-[11px] font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1.5">
            Label <span class="text-red-500">*</span>
          </label>
          <input v-model="form.label" type="text" placeholder="e.g. 2026-2027"
                 class="w-full px-3.5 py-2.5 text-xs bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white rounded-xl focus:ring-2 focus:ring-[#004d08] focus:outline-none font-normal"
                 :class="{ 'border-red-500': errors.label }" />
          <p v-if="errors.label" class="mt-1 text-[11px] text-red-500">{{ errors.label[0] }}</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
          <div>
            <label class="block text-[11px] font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1.5">
              Start Date <span class="text-red-500">*</span>
            </label>
            <input v-model="form.start_date" type="date"
                   class="w-full px-3.5 py-2.5 text-xs bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white rounded-xl focus:ring-2 focus:ring-[#004d08] focus:outline-none font-normal cursor-pointer"
                   :class="{ 'border-red-500': errors.start_date }" />
            <p v-if="errors.start_date" class="mt-1 text-[11px] text-red-500">{{ errors.start_date[0] }}</p>
          </div>

          <div>
            <label class="block text-[11px] font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1.5">
              End Date <span class="text-red-500">*</span>
            </label>
            <input v-model="form.end_date" type="date"
                   class="w-full px-3.5 py-2.5 text-xs bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white rounded-xl focus:ring-2 focus:ring-[#004d08] focus:outline-none font-normal cursor-pointer"
                   :class="{ 'border-red-500': errors.end_date }" />
            <p v-if="errors.end_date" class="mt-1 text-[11px] text-red-500">{{ errors.end_date[0] }}</p>
          </div>
        </div>

        <label class="flex items-start gap-3 p-3 bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] rounded-xl hover:bg-gray-100/60 dark:hover:bg-white/5 transition-colors cursor-pointer">
          <input v-model="form.is_active" type="checkbox"
                 class="mt-0.5 w-4 h-4 text-[#004d08] border-gray-300 rounded focus:ring-[#004d08] cursor-pointer" />
          <div class="space-y-0.5">
            <span class="block text-xs font-medium text-gray-900 dark:text-white">Set as Active School Year</span>
            <span class="block text-[10px] text-gray-500 dark:text-gray-400 font-normal">
              Activating this year will deactivate all others. Only one school year can be active at a time.
            </span>
          </div>
        </label>

        <div class="mt-6 flex justify-end gap-2.5 pt-4 border-t border-gray-100 dark:border-[#3F4F43]">
          <button type="button" @click="closeModal"
                  class="px-4 py-2 text-xs font-normal text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-white/5 rounded-xl transition-all cursor-pointer">
            Cancel
          </button>
          <button type="submit" :disabled="isLoading"
                  class="px-5 py-2 text-xs font-medium uppercase tracking-wider bg-[#004d08] text-white hover:bg-emerald-900 rounded-xl transition-all shadow-md active:scale-95 disabled:opacity-50 cursor-pointer">
            {{ isLoading ? 'Saving…' : (isEditing ? 'Save Changes' : 'Create Year') }}
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

const props = defineProps({
  show: { type: Boolean, default: false },
  year: { type: Object,  default: null },
})

const emit = defineEmits(['close', 'saved'])

const isLoading    = ref(false)
const generalError = ref('')
const errors       = ref({})

const isEditing = computed(() => !!props.year?.id)

const form = reactive({
  label: '',
  start_date: '',
  end_date: '',
  is_active: false,
})

watch(() => props.show, (open) => { if (open) populate() })

const populate = () => {
  errors.value = {}
  generalError.value = ''
  if (props.year) {
    form.label      = props.year.label || ''
    form.start_date = props.year.start_date || ''
    form.end_date   = props.year.end_date || ''
    form.is_active  = !!props.year.is_active
  } else {
    form.label      = ''
    form.start_date = ''
    form.end_date   = ''
    form.is_active  = false
  }
}

const closeModal = () => { errors.value = {}; generalError.value = ''; emit('close') }

const submit = async () => {
  isLoading.value = true
  errors.value = {}
  generalError.value = ''
  try {
    const url = isEditing.value ? `/admin/school-years/${props.year.id}` : '/admin/school-years'
    const method = isEditing.value ? 'put' : 'post'
    await axios[method](url, form)
    emit('saved')
    closeModal()
  } catch (error) {
    if (error.response?.status === 422) {
      errors.value = error.response.data.errors || {}
      generalError.value = error.response.data.message || 'Please fix the highlighted fields.'
    } else {
      generalError.value = error.response?.data?.message || 'Failed to save school year.'
    }
  } finally {
    isLoading.value = false
  }
}
</script>