<template>
  <div class="p-5">
    <div class="flex items-center justify-between mb-4">
      <div>
        <h2 class="text-xs font-medium text-gray-900 dark:text-white uppercase tracking-wider">Tracks</h2>
        <p class="text-[11px] text-gray-500 dark:text-gray-400 font-normal">Two tracks: Academic and Technical-Professional</p>
      </div>
      <button @click="openModal()"
              class="inline-flex items-center gap-1.5 px-3 py-2 text-xs font-medium uppercase tracking-wider text-[#004d08] dark:text-[#86EFAC] bg-emerald-50 hover:bg-emerald-100 dark:bg-emerald-950/50 border border-emerald-200 dark:border-emerald-800 rounded-xl transition-colors cursor-pointer">
        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
          <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
        </svg>
        Add Track
      </button>
    </div>

    <div v-if="tracks.length === 0" class="py-12 text-center">
      <p class="text-xs text-gray-400 dark:text-gray-500">Add your first track.</p>
    </div>

    <div v-else class="border border-gray-200/80 dark:border-[#3F4F43] rounded-xl overflow-hidden">
      <table class="w-full text-left border-collapse">
        <thead>
          <tr class="bg-gray-50/50 dark:bg-[#232D26]/50 text-[10px] font-medium uppercase text-gray-400 tracking-wider border-b border-gray-100 dark:border-[#3F4F43]">
            <th class="py-3 px-5">Code</th>
            <th class="py-3 px-5">Name</th>
            <th class="py-3 px-5">Description</th>
            <th class="py-3 px-5 text-center">Strands</th>
            <th class="py-3 px-5 text-center">Status</th>
            <th class="py-3 px-5 text-right">Actions</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-100 dark:divide-[#3F4F43] text-xs">
          <tr v-for="track in tracks" :key="track.id" class="hover:bg-gray-50/80 dark:hover:bg-white/5 transition-colors">
            <td class="py-3 px-5">
              <span class="font-mono font-semibold text-gray-900 dark:text-white text-xs bg-gray-100 dark:bg-[#232D26] px-2 py-1 rounded-md border border-gray-200 dark:border-[#3F4F43]">
                {{ track.code }}
              </span>
            </td>
            <td class="py-3 px-5 text-gray-900 dark:text-white font-medium">{{ track.name }}</td>
            <td class="py-3 px-5 text-gray-600 dark:text-gray-300 max-w-xs truncate">{{ track.description || '—' }}</td>
            <td class="py-3 px-5 text-center text-gray-900 dark:text-white font-medium">{{ track.strands_count }}</td>
            <td class="py-3 px-5 text-center">
              <span class="px-2 py-0.5 rounded-full text-[10px] font-normal uppercase tracking-wider inline-flex items-center gap-1.5 border"
                    :class="track.is_active
                      ? 'bg-emerald-100 dark:bg-emerald-950/60 text-emerald-800 dark:text-emerald-300 border-emerald-200 dark:border-emerald-800'
                      : 'bg-gray-100 dark:bg-[#232D26] text-gray-600 dark:text-gray-400 border-gray-200 dark:border-[#3F4F43]'">
                <span :class="['w-1.5 h-1.5 rounded-full', track.is_active ? 'bg-emerald-500' : 'bg-gray-400']"></span>
                {{ track.is_active ? 'Active' : 'Inactive' }}
              </span>
            </td>
            <td class="py-3 px-5 text-right">
              <div class="flex items-center justify-end gap-1.5">
                <button @click="openModal(track)"
                        class="px-2.5 py-1 text-[10px] font-normal text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-[#232D26] hover:bg-gray-200 dark:hover:bg-white/10 rounded-md transition-colors cursor-pointer">
                  Edit
                </button>
                <button @click="confirmDelete(track)"
                        class="px-2.5 py-1 text-[10px] font-normal text-red-700 dark:text-red-400 bg-red-50 hover:bg-red-100 dark:bg-red-950/50 rounded-md border border-red-200 dark:border-red-900 transition-colors cursor-pointer">
                  Delete
                </button>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <Modal :show="showModal" @close="closeModal" max-width="lg">
      <div class="bg-white dark:bg-[#2D3A31] rounded-2xl border border-gray-200 dark:border-[#3F4F43] font-['Inter']">
        <div class="flex items-center justify-between px-6 pt-5 pb-4 border-b border-gray-100 dark:border-[#3F4F43]">
          <div>
            <h3 class="text-xs font-medium uppercase tracking-wider text-gray-900 dark:text-white">
              {{ editing ? 'Edit Track' : 'New Track' }}
            </h3>
            <p class="text-[11px] text-gray-500 dark:text-gray-400 font-normal">
              {{ editing ? 'Update track details' : 'Define a new SHS track' }}
            </p>
          </div>
          <button type="button" @click="closeModal" class="p-1.5 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 rounded-xl hover:bg-gray-100 dark:hover:bg-white/5 transition-colors cursor-pointer">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>

        <div v-if="error" class="mx-6 mt-4 p-3 bg-red-50 dark:bg-red-950/40 border border-red-200 dark:border-red-800 rounded-xl text-red-700 dark:text-red-300 text-xs">
          {{ error }}
        </div>

        <form @submit.prevent="submit" class="px-6 py-5 space-y-4">
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            <div>
              <label class="block text-[11px] font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1.5">
                Code <span class="text-red-500">*</span>
              </label>
              <input v-model="form.code" type="text" placeholder="e.g. ACAD" maxlength="10"
                     class="w-full px-3.5 py-2.5 text-xs bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white rounded-xl focus:ring-2 focus:ring-[#004d08] focus:outline-none font-normal uppercase" />
            </div>
            <div>
              <label class="block text-[11px] font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1.5">
                Name <span class="text-red-500">*</span>
              </label>
              <input v-model="form.name" type="text" placeholder="e.g. Academic Track"
                     class="w-full px-3.5 py-2.5 text-xs bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white rounded-xl focus:ring-2 focus:ring-[#004d08] focus:outline-none font-normal" />
            </div>
          </div>

          <div>
            <label class="block text-[11px] font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1.5">Description</label>
            <textarea v-model="form.description" rows="2" placeholder="Short description..."
                      class="w-full px-3.5 py-2.5 text-xs bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white rounded-xl focus:ring-2 focus:ring-[#004d08] focus:outline-none font-normal resize-none"></textarea>
          </div>

          <label class="flex items-start gap-3 p-3 bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] rounded-xl hover:bg-gray-100/60 dark:hover:bg-white/5 transition-colors cursor-pointer">
            <input v-model="form.is_active" type="checkbox" class="mt-0.5 w-4 h-4 text-[#004d08] border-gray-300 rounded focus:ring-[#004d08] cursor-pointer" />
            <span class="text-xs font-medium text-gray-900 dark:text-white">Active</span>
          </label>

          <div class="flex justify-end gap-2.5 pt-4 border-t border-gray-100 dark:border-[#3F4F43]">
            <button type="button" @click="closeModal"
                    class="px-4 py-2 text-xs font-normal text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-white/5 rounded-xl transition-all cursor-pointer">
              Cancel
            </button>
            <button type="submit" :disabled="saving"
                    class="px-5 py-2 text-xs font-medium uppercase tracking-wider bg-[#004d08] text-white hover:bg-emerald-900 rounded-xl transition-all shadow-md active:scale-95 disabled:opacity-50 cursor-pointer">
              {{ saving ? 'Saving…' : (editing ? 'Save Changes' : 'Create Track') }}
            </button>
          </div>
        </form>
      </div>
    </Modal>
  </div>
</template>

<script setup>
import { ref, reactive } from 'vue'
import axios from 'axios'
import Modal from '@/Components/Modal.vue'

const props = defineProps({
  tracks: { type: Array, default: () => [] },
})
const emit = defineEmits(['changed'])

const showModal = ref(false)
const editing   = ref(null)
const saving    = ref(false)
const error     = ref('')
const form      = reactive({ code: '', name: '', description: '', is_active: true })

const openModal = (track = null) => {
  editing.value = track
  error.value = ''
  if (track) {
    form.code        = track.code
    form.name        = track.name
    form.description = track.description || ''
    form.is_active   = !!track.is_active
  } else {
    form.code = ''; form.name = ''; form.description = ''; form.is_active = true
  }
  showModal.value = true
}

const closeModal = () => { showModal.value = false; editing.value = null; error.value = '' }

const submit = async () => {
  saving.value = true
  error.value = ''
  try {
    if (editing.value) await axios.put(`/admin/tracks/${editing.value.id}`, form)
    else               await axios.post('/admin/tracks', form)
    closeModal()
    emit('changed')
  } catch (e) {
    if (e.response?.status === 422) error.value = Object.values(e.response.data.errors || {}).flat()[0]
    else                            error.value = e.response?.data?.message || 'Failed to save track.'
  } finally {
    saving.value = false
  }
}

const confirmDelete = async (track) => {
  if (!confirm(`Delete track "${track.name}"? Fails if strands are linked.`)) return
  try {
    await axios.delete(`/admin/tracks/${track.id}`)
    emit('changed')
  } catch (e) {
    alert(e.response?.data?.message || 'Cannot delete track.')
  }
}
</script>