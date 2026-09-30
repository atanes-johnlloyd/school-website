<template>
  <Modal :show="show" @close="closeModal" max-width="lg">
    <div class="bg-white dark:bg-[#2D3A31] rounded-2xl border border-gray-200 dark:border-[#3F4F43] font-['Inter']">

      <div class="flex items-center justify-between px-6 pt-5 pb-4 border-b border-gray-100 dark:border-[#3F4F43]">
        <div class="flex items-center gap-3">
          <div class="w-10 h-10 rounded-2xl bg-emerald-50 dark:bg-[#1C261E] border border-emerald-100 dark:border-[#3F4F43] flex items-center justify-center text-[#004d08] dark:text-[#86EFAC] shrink-0">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z" />
            </svg>
          </div>
          <div>
            <h3 class="text-xs font-medium uppercase tracking-wider text-gray-900 dark:text-white">
              {{ isEditing ? 'Edit Room' : 'New Room' }}
            </h3>
            <p class="text-[11px] text-gray-500 dark:text-gray-400 font-normal">
              {{ isEditing ? 'Update room details' : 'Add a physical space to the campus map' }}
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

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
          <div>
            <label class="block text-[11px] font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1.5">
              Code <span class="text-red-500">*</span>
            </label>
            <input v-model="form.code" type="text" placeholder="e.g. R-101" maxlength="20"
                   class="w-full px-3.5 py-2.5 text-xs bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white rounded-xl focus:ring-2 focus:ring-[#004d08] focus:outline-none font-normal uppercase" />
            <p class="mt-1 text-[10px] text-gray-400 dark:text-gray-500">Unique identifier used across timetables.</p>
          </div>
          <div>
            <label class="block text-[11px] font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1.5">
              Type <span class="text-red-500">*</span>
            </label>
            <select v-model="form.type"
                    class="w-full px-3.5 py-2.5 text-xs bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white rounded-xl focus:ring-2 focus:ring-[#004d08] focus:outline-none font-normal cursor-pointer">
              <option value="regular">Regular Classroom</option>
              <option value="lecture">Lecture Hall</option>
              <option value="laboratory">Laboratory</option>
              <option value="computer_lab">Computer Laboratory</option>
              <option value="science_lab">Science Laboratory</option>
              <option value="workshop">Workshop</option>
            </select>
          </div>
        </div>

        <div>
          <label class="block text-[11px] font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1.5">
            Name <span class="text-red-500">*</span>
          </label>
          <input v-model="form.name" type="text" placeholder="e.g. Room 101"
                 class="w-full px-3.5 py-2.5 text-xs bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white rounded-xl focus:ring-2 focus:ring-[#004d08] focus:outline-none font-normal" />
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
          <div>
            <label class="block text-[11px] font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1.5">
              Building
            </label>
            <input v-model="form.building" type="text" placeholder="e.g. Main Building"
                   class="w-full px-3.5 py-2.5 text-xs bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white rounded-xl focus:ring-2 focus:ring-[#004d08] focus:outline-none font-normal" />
          </div>
          <div>
            <label class="block text-[11px] font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1.5">
              Floor
            </label>
            <input v-model="form.floor" type="text" placeholder="e.g. 1st Floor" maxlength="20"
                   class="w-full px-3.5 py-2.5 text-xs bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white rounded-xl focus:ring-2 focus:ring-[#004d08] focus:outline-none font-normal" />
          </div>
        </div>

        <div>
          <label class="block text-[11px] font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1.5">
            Capacity <span class="text-red-500">*</span>
          </label>
          <input v-model.number="form.capacity" type="number" min="1" max="500" placeholder="40"
                 class="w-full px-3.5 py-2.5 text-xs bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white rounded-xl focus:ring-2 focus:ring-[#004d08] focus:outline-none font-normal" />
          <p class="mt-1 text-[10px] text-gray-400 dark:text-gray-500">Maximum students the room can seat (1–500).</p>
        </div>

        <label class="flex items-start gap-3 p-3 bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] rounded-xl hover:bg-gray-100/60 dark:hover:bg-white/5 transition-colors cursor-pointer">
          <input v-model="form.is_active" type="checkbox"
                 class="mt-0.5 w-4 h-4 text-[#004d08] border-gray-300 rounded focus:ring-[#004d08] cursor-pointer" />
          <div>
            <span class="block text-xs font-medium text-gray-900 dark:text-white">Room is Active</span>
            <span class="block text-[10px] text-gray-500 dark:text-gray-400 font-normal">Inactive rooms can't be selected for new class schedules.</span>
          </div>
        </label>

        <div class="flex justify-end gap-2.5 pt-4 border-t border-gray-100 dark:border-[#3F4F43]">
          <button type="button" @click="closeModal"
                  class="px-4 py-2 text-xs font-normal text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-white/5 rounded-xl transition-all cursor-pointer">
            Cancel
          </button>
          <button type="submit" :disabled="isLoading"
                  class="px-5 py-2 text-xs font-medium uppercase tracking-wider bg-[#004d08] text-white hover:bg-emerald-900 rounded-xl transition-all shadow-md active:scale-95 disabled:opacity-50 cursor-pointer">
            {{ isLoading ? 'Saving…' : (isEditing ? 'Save Changes' : 'Create Room') }}
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
  room: { type: Object,  default: null },
})

const emit = defineEmits(['close', 'saved'])

const isLoading    = ref(false)
const generalError = ref('')

const isEditing = computed(() => !!props.room?.id)

const form = reactive({
  code: '',
  name: '',
  building: '',
  floor: '',
  capacity: 40,
  type: 'regular',
  is_active: true,
})

watch(() => props.show, (open) => { if (open) populate() })

const populate = () => {
  generalError.value = ''
  if (props.room) {
    form.code      = props.room.code || ''
    form.name      = props.room.name || ''
    form.building  = props.room.building || ''
    form.floor     = props.room.floor || ''
    form.capacity  = props.room.capacity || 40
    form.type      = props.room.type || 'regular'
    form.is_active = !!props.room.is_active
  } else {
    form.code = ''; form.name = ''; form.building = ''; form.floor = ''
    form.capacity = 40; form.type = 'regular'; form.is_active = true
  }
}

const closeModal = () => { generalError.value = ''; emit('close') }

const submit = async () => {
  generalError.value = ''

  if (!form.code?.trim()) {
    generalError.value = 'Room code is required.'
    return
  }
  if (!form.name?.trim()) {
    generalError.value = 'Room name is required.'
    return
  }
  if (!form.capacity || form.capacity < 1 || form.capacity > 500) {
    generalError.value = 'Capacity must be between 1 and 500.'
    return
  }

  isLoading.value = true

  const payload = {
    ...form,
    building: form.building || null,
    floor:    form.floor || null,
  }

  try {
    const url = isEditing.value ? `/admin/rooms/${props.room.id}` : '/admin/rooms'
    const method = isEditing.value ? 'put' : 'post'
    await axios[method](url, payload)
    emit('saved')
    closeModal()
  } catch (e) {
    if (e.response?.status === 422) {
      generalError.value = Object.values(e.response.data.errors || {}).flat()[0] || 'Validation failed.'
    } else {
      generalError.value = e.response?.data?.message || 'Failed to save room.'
    }
  } finally {
    isLoading.value = false
  }
}
</script>