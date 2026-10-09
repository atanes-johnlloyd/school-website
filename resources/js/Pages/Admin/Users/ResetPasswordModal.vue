<template>
  <Modal :show="show" @close="closeModal" max-width="md">
    <div class="bg-white dark:bg-[#2D3A31] rounded-2xl border border-gray-200 dark:border-[#3F4F43] font-['Inter']">
      <div class="flex items-center gap-3 px-6 pt-5 pb-4 border-b border-gray-100 dark:border-[#3F4F43]">
        <div class="w-10 h-10 rounded-2xl bg-violet-50 dark:bg-violet-950/50 border border-violet-100 dark:border-violet-900/50 flex items-center justify-center text-violet-600 dark:text-violet-400 shrink-0">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
          </svg>
        </div>
        <div class="min-w-0">
          <h3 class="text-xs font-medium uppercase tracking-wider text-gray-900 dark:text-white">
            {{ tempKey ? 'Temporary Password' : 'Reset Password' }}
          </h3>
          <p class="text-[11px] text-gray-500 dark:text-gray-400 font-normal truncate">
            {{ user?.name }} &bull; {{ user?.email }}
          </p>
        </div>
      </div>

      <div class="px-6 py-5 space-y-4">
        <div v-if="generalError" class="p-3 bg-red-50 dark:bg-red-950/40 border border-red-200 dark:border-red-800 rounded-xl text-red-700 dark:text-red-300 text-xs">
          {{ generalError }}
        </div>

        <!-- Confirm step -->
        <template v-if="!tempKey">
          <p class="text-xs text-gray-600 dark:text-gray-300 leading-relaxed">
            Generate a new temporary password for <strong class="font-semibold text-gray-900 dark:text-white">{{ user?.name }}</strong>?
            Their current password will stop working immediately, and they'll be required to change it on next login.
          </p>
        </template>

        <!-- Result step -->
        <template v-else>
          <div class="p-3 bg-emerald-50 dark:bg-emerald-950/20 border border-emerald-200 dark:border-emerald-900/50 rounded-xl">
            <p class="text-[11px] font-semibold text-emerald-800 dark:text-emerald-300 mb-2 uppercase tracking-wider">
              Share this key with the user
            </p>
            <div class="flex items-center gap-2">
              <code class="flex-1 px-3 py-2 text-sm font-mono text-gray-900 dark:text-white bg-white dark:bg-[#1C261E] border border-emerald-200 dark:border-emerald-900/50 rounded-lg select-all">{{ tempKey }}</code>
              <button type="button" @click="copyKey"
                class="shrink-0 px-3 py-2 text-[11px] font-medium uppercase tracking-wider text-white bg-[#004d08] hover:bg-emerald-900 rounded-lg transition-colors cursor-pointer">
                {{ copied ? 'Copied' : 'Copy' }}
              </button>
            </div>
          </div>

          <div class="p-3 bg-amber-50 dark:bg-amber-950/30 border border-amber-200 dark:border-amber-800 rounded-xl text-amber-700 dark:text-amber-300 text-xs flex items-start gap-2">
            <svg class="w-4 h-4 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M4.93 19h14.14a2 2 0 001.74-3L13.74 4a2 2 0 00-3.48 0L3.19 16a2 2 0 001.74 3z" />
            </svg>
            <span>This key won't be shown again. Copy it before closing.</span>
          </div>
        </template>
      </div>

      <div class="px-6 py-4 border-t border-gray-100 dark:border-[#3F4F43] flex justify-end gap-2.5">
        <button type="button" @click="closeModal"
                class="px-4 py-2 text-xs font-normal text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-white/5 rounded-xl transition-all cursor-pointer">
          {{ tempKey ? 'Close' : 'Cancel' }}
        </button>
        <button v-if="!tempKey" type="button" :disabled="isSaving" @click="submit"
                class="px-5 py-2 text-xs font-medium uppercase tracking-wider bg-violet-600 hover:bg-violet-700 text-white rounded-xl transition-all shadow-md active:scale-95 disabled:opacity-50 cursor-pointer">
          {{ isSaving ? 'Resetting…' : 'Reset Password' }}
        </button>
      </div>
    </div>
  </Modal>
</template>

<script setup>
import { ref, watch } from 'vue'
import axios from 'axios'
import Modal from '@/Components/Modal.vue'
import { useFlash } from '@/Composables/useFlash'

const props = defineProps({
  show: { type: Boolean, default: false },
  user: { type: Object,  default: null },
})

const emit = defineEmits(['close'])
const flash = useFlash()

const tempKey      = ref('')
const copied       = ref(false)
const isSaving     = ref(false)
const generalError = ref('')

watch(() => props.show, (open) => {
  if (open) {
    tempKey.value = ''
    copied.value = false
    generalError.value = ''
    isSaving.value = false
  }
})

const submit = async () => {
  if (!props.user?.id) return
  isSaving.value = true
  generalError.value = ''
  try {
    const { data } = await axios.post(`/admin/users/${props.user.id}/reset-password`)
    tempKey.value = data.temp_key
    flash.success('Password reset.')
  } catch (error) {
    generalError.value = error.response?.data?.message || 'Failed to reset password.'
    flash.error(generalError.value)
  } finally {
    isSaving.value = false
  }
}

const copyKey = async () => {
  try {
    await navigator.clipboard.writeText(tempKey.value)
    copied.value = true
    setTimeout(() => { copied.value = false }, 2000)
  } catch {
    /* clipboard unavailable — user can still select the code */
  }
}

const closeModal = () => {
  tempKey.value = ''
  generalError.value = ''
  emit('close')
}
</script>