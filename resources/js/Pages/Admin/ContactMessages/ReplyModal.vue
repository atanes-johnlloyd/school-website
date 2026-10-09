<template>
  <Modal :show="show" @close="closeModal" max-width="lg">
    <div class="bg-white dark:bg-[#2D3A31] rounded-2xl border border-gray-200 dark:border-[#3F4F43] font-['Inter'] flex flex-col max-h-[85vh]">

      <div class="flex items-center gap-3 px-6 pt-5 pb-4 border-b border-gray-100 dark:border-[#3F4F43]">
        <div class="w-10 h-10 rounded-2xl bg-emerald-50 dark:bg-[#1C261E] border border-emerald-100 dark:border-[#3F4F43] flex items-center justify-center text-[#004d08] dark:text-[#86EFAC] shrink-0">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6" />
          </svg>
        </div>
        <div class="min-w-0">
          <h3 class="text-xs font-medium uppercase tracking-wider text-gray-900 dark:text-white">Reply to Message</h3>
          <p class="text-[11px] text-gray-500 dark:text-gray-400 font-normal truncate">
            <template v-if="message">To {{ message.name }} &bull; {{ message.email }}</template>
          </p>
        </div>
        <button type="button" @click="closeModal"
          class="ml-auto p-1.5 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 rounded-xl hover:bg-gray-100 dark:hover:bg-white/5 transition-colors cursor-pointer">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>

      <div v-if="generalError" class="mx-6 mt-4 p-3 bg-red-50 dark:bg-red-950/40 border border-red-200 dark:border-red-800 rounded-xl text-red-700 dark:text-red-300 text-xs">
        {{ generalError }}
      </div>

      <div class="flex-1 min-h-0 overflow-y-auto px-6 py-5 space-y-4">

        <div>
          <label class="block text-[11px] font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1.5">
            Subject <span class="text-red-500">*</span>
          </label>
          <input v-model="form.subject" type="text" maxlength="200"
            class="w-full px-3.5 py-2.5 text-xs bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white rounded-xl focus:ring-2 focus:ring-[#004d08] focus:outline-none font-normal" />
          <p v-if="errors.subject" class="mt-1 text-[11px] text-red-500">{{ errors.subject[0] }}</p>
        </div>

        <div>
          <label class="block text-[11px] font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1.5">
            Message <span class="text-red-500">*</span>
          </label>
          <textarea v-model="form.body" rows="8" maxlength="5000"
            placeholder="Type your reply here..."
            class="w-full px-3.5 py-2.5 text-xs bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white placeholder-gray-400 rounded-xl focus:ring-2 focus:ring-[#004d08] focus:outline-none resize-none leading-relaxed"></textarea>
          <div class="flex justify-between mt-1">
            <p v-if="errors.body" class="text-[11px] text-red-500">{{ errors.body[0] }}</p>
            <p class="text-[10px] text-gray-400 dark:text-gray-500 ml-auto">{{ form.body.length }} / 5000</p>
          </div>
        </div>

        <div v-if="message" class="p-3 bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] rounded-xl">
          <p class="text-[10px] font-medium uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-1">Original Message</p>
          <p class="text-[11px] text-gray-600 dark:text-gray-300 line-clamp-3 whitespace-pre-line">{{ message.message }}</p>
        </div>
      </div>

      <div class="px-6 py-4 border-t border-gray-100 dark:border-[#3F4F43] flex justify-end gap-2.5">
        <button type="button" @click="closeModal"
          class="px-4 py-2 text-xs font-normal text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-white/5 rounded-xl transition-all cursor-pointer">
          Cancel
        </button>
        <button type="button" :disabled="sending || !form.subject.trim() || !form.body.trim()"
          @click="submit"
          class="px-5 py-2 text-xs font-medium uppercase tracking-wider bg-[#004d08] text-white hover:bg-emerald-900 rounded-xl transition-all shadow-md active:scale-95 disabled:opacity-50 cursor-pointer">
          {{ sending ? 'Sending…' : 'Send Reply' }}
        </button>
      </div>
    </div>
  </Modal>
</template>

<script setup>
import { ref, reactive, watch, computed } from 'vue'
import axios from 'axios'
import Modal from '@/Components/Modal.vue'
import { useFlash } from '@/Composables/useFlash'

const props = defineProps({
  show:    { type: Boolean, default: false },
  message: { type: Object,  default: null },
})

const emit = defineEmits(['close', 'sent'])
const flash = useFlash()

const sending      = ref(false)
const generalError = ref('')
const errors       = ref({})

const form = reactive({ subject: '', body: '' })

watch(() => props.show, (open) => {
  if (open) {
    errors.value = {}
    generalError.value = ''
    form.body = ''
    form.subject = props.message?.subject
      ? `Re: ${props.message.subject}`
      : 'Re: Your inquiry'
  }
})

const submit = async () => {
  if (!props.message?.id) return
  sending.value = true
  errors.value = {}
  generalError.value = ''

  try {
    const { data } = await axios.post(
      `/admin/contact-messages/${props.message.id}/reply`,
      { subject: form.subject.trim(), body: form.body.trim() }
    )
    flash.success(data?.message || 'Reply sent.')
    emit('sent')
    closeModal()
  } catch (e) {
    if (e.response?.status === 422) {
      errors.value = e.response.data.errors || {}
      generalError.value = e.response.data.message || 'Please fix the highlighted fields.'
    } else {
      generalError.value = e.response?.data?.message || 'Failed to send reply.'
    }
    flash.error(generalError.value)
  } finally {
    sending.value = false
  }
}

const closeModal = () => {
  if (sending.value) return
  errors.value = {}
  generalError.value = ''
  emit('close')
}
</script>