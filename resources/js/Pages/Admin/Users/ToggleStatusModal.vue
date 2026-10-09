<template>
  <Modal :show="show" @close="closeModal" max-width="md">
    <div class="bg-white dark:bg-[#2D3A31] rounded-2xl border border-gray-200 dark:border-[#3F4F43] font-['Inter']">
      <div class="flex items-center gap-3 px-6 pt-5 pb-4 border-b border-gray-100 dark:border-[#3F4F43]">
        <div class="w-10 h-10 rounded-2xl border flex items-center justify-center shrink-0"
             :class="isDisabling
               ? 'bg-red-50 dark:bg-red-950/50 border-red-100 dark:border-red-900/50 text-red-600 dark:text-red-400'
               : 'bg-emerald-50 dark:bg-emerald-950/50 border-emerald-100 dark:border-emerald-900/50 text-emerald-600 dark:text-emerald-400'">
          <svg v-if="isDisabling" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
          </svg>
          <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
        </div>
        <div>
          <h3 class="text-xs font-medium uppercase tracking-wider text-gray-900 dark:text-white">
            {{ isDisabling ? 'Disable Admin Account' : 'Reactivate Admin Account' }}
          </h3>
          <p class="text-[11px] text-gray-500 dark:text-gray-400 font-normal">
            {{ isDisabling ? 'The user will lose access immediately' : 'The user will regain access to the admin portal' }}
          </p>
        </div>
      </div>

      <div class="px-6 py-5 space-y-4">
        <div v-if="generalError" class="p-3 bg-red-50 dark:bg-red-950/40 border border-red-200 dark:border-red-800 rounded-xl text-red-700 dark:text-red-300 text-xs">
          {{ generalError }}
        </div>

        <p class="text-xs text-gray-600 dark:text-gray-300 leading-relaxed">
          <template v-if="isDisabling">
            Are you sure you want to disable <strong class="font-semibold text-gray-900 dark:text-white">{{ user?.name }}</strong>?
            They will be signed out and prevented from logging in until reactivated.
          </template>
          <template v-else>
            Are you sure you want to reactivate <strong class="font-semibold text-gray-900 dark:text-white">{{ user?.name }}</strong>?
            They will be able to sign in again immediately.
          </template>
        </p>

        <div v-if="isDisabling">
          <label class="block text-[11px] font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1.5">
            Reason <span class="text-red-500">*</span>
          </label>
          <textarea v-model="reason" rows="3"
                    placeholder="e.g. Employee separation, policy violation, extended leave…"
                    class="w-full px-3.5 py-2.5 text-xs bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white rounded-xl focus:ring-2 focus:ring-[#004d08] focus:outline-none resize-none"></textarea>
          <p class="text-[10px] text-gray-400 dark:text-gray-500 mt-1">This reason will be included in the notification email.</p>
        </div>
      </div>

      <div class="px-6 py-4 border-t border-gray-100 dark:border-[#3F4F43] flex justify-end gap-2.5">
        <button type="button" @click="closeModal"
                class="px-4 py-2 text-xs font-normal text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-white/5 rounded-xl transition-all cursor-pointer">
          Cancel
        </button>
        <button type="button" :disabled="isSaving || (isDisabling && !reason.trim())" @click="submit"
                class="px-5 py-2 text-xs font-medium uppercase tracking-wider text-white rounded-xl transition-all shadow-md active:scale-95 disabled:opacity-50 cursor-pointer"
                :class="isDisabling ? 'bg-red-600 hover:bg-red-700' : 'bg-[#004d08] hover:bg-emerald-900'">
          {{ isSaving ? 'Saving…' : (isDisabling ? 'Disable Account' : 'Reactivate Account') }}
        </button>
      </div>
    </div>
  </Modal>
</template>

<script setup>
import { ref, computed, watch } from 'vue'
import axios from 'axios'
import Modal from '@/Components/Modal.vue'
import { useFlash } from '@/Composables/useFlash'

const props = defineProps({
  show: { type: Boolean, default: false },
  user: { type: Object,  default: null },
})

const emit = defineEmits(['close', 'changed'])

const flash = useFlash()
const reason       = ref('')
const isSaving     = ref(false)
const generalError = ref('')

const isDisabling = computed(() => (props.user?.status ?? 'active') === 'active')

watch(() => props.show, (open) => {
  if (open) {
    reason.value = ''
    generalError.value = ''
    isSaving.value = false
  }
})

const closeModal = () => {
  reason.value = ''
  generalError.value = ''
  emit('close')
}

const submit = async () => {
  if (!props.user?.id) return
  isSaving.value = true
  generalError.value = ''

  try {
    const { data } = await axios.put(`/admin/users/${props.user.id}/toggle-status`, {
      reason: isDisabling.value ? reason.value.trim() : null,
    })
    flash.success(data?.message || (isDisabling.value ? 'Admin disabled.' : 'Admin reactivated.'))
    emit('changed')
    closeModal()
  } catch (error) {
    generalError.value = error.response?.data?.message || 'Failed to update status.'
    flash.error(generalError.value)
  } finally {
    isSaving.value = false
  }
}
</script>