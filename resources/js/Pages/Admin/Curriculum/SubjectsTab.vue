<template>
  <div class="p-5">
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-3 mb-4">
      <div>
        <h2 class="text-xs font-medium text-gray-900 dark:text-white uppercase tracking-wider">Subjects</h2>
        <p class="text-[11px] text-gray-500 dark:text-gray-400 font-normal">{{ filtered.length }} subjects defined</p>
      </div>

      <div class="flex flex-wrap items-center gap-2">
        <div class="relative w-full sm:w-56">
          <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
          </svg>
          <input v-model="search" type="text" placeholder="Search code or name..."
                 class="w-full pl-9 pr-3 py-2 text-xs bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white placeholder-gray-400 rounded-xl focus:ring-2 focus:ring-[#004d08] focus:outline-none font-normal" />
        </div>

        <select v-model="filterStrand"
                class="appearance-none pl-3 pr-8 py-2 text-xs bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white rounded-xl focus:ring-2 focus:ring-[#004d08] focus:outline-none font-normal cursor-pointer max-w-[160px] truncate">
          <option value="">All Strands</option>
          <option value="__core__">Core (no strand)</option>
          <option v-for="s in strands" :key="s.id" :value="s.id">{{ s.code }}</option>
        </select>

        <select v-model="filterGrade"
                class="appearance-none pl-3 pr-8 py-2 text-xs bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white rounded-xl focus:ring-2 focus:ring-[#004d08] focus:outline-none font-normal cursor-pointer">
          <option value="">All Grades</option>
          <option value="11">Grade 11</option>
          <option value="12">Grade 12</option>
          <option value="both">Both</option>
        </select>

        <button @click="openModal()"
                class="inline-flex items-center gap-1.5 px-3 py-2 text-xs font-medium uppercase tracking-wider text-[#004d08] dark:text-[#86EFAC] bg-emerald-50 hover:bg-emerald-100 dark:bg-emerald-950/50 border border-emerald-200 dark:border-emerald-800 rounded-xl transition-colors cursor-pointer shrink-0">
          <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
          </svg>
          Add Subject
        </button>
      </div>
    </div>

    <div v-if="filtered.length === 0" class="py-12 text-center">
      <p class="text-xs text-gray-400 dark:text-gray-500">
        {{ subjects.length === 0 ? 'No subjects yet.' : 'No subjects match your filters.' }}
      </p>
    </div>

    <div v-else class="border border-gray-200/80 dark:border-[#3F4F43] rounded-xl overflow-hidden">
      <table class="w-full text-left border-collapse">
        <thead>
          <tr class="bg-gray-50/50 dark:bg-[#232D26]/50 text-[10px] font-medium uppercase text-gray-400 tracking-wider border-b border-gray-100 dark:border-[#3F4F43]">
            <th class="py-3 px-4">Code</th>
            <th class="py-3 px-4">Name</th>
            <th class="py-3 px-4">Strand</th>
            <th class="py-3 px-4 text-center">Grade</th>
            <th class="py-3 px-4 text-center">Hours</th>
            <th class="py-3 px-4 text-center">Core</th>
            <th class="py-3 px-4 text-center">Status</th>
            <th class="py-3 px-4 text-right">Actions</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-100 dark:divide-[#3F4F43] text-xs">
          <tr v-for="subject in filtered" :key="subject.id" class="hover:bg-gray-50/80 dark:hover:bg-white/5 transition-colors">
            <td class="py-3 px-4">
              <span class="font-mono text-xs text-gray-900 dark:text-white bg-gray-100 dark:bg-[#232D26] px-2 py-1 rounded-md border border-gray-200 dark:border-[#3F4F43]">
                {{ subject.code }}
              </span>
            </td>
            <td class="py-3 px-4 text-gray-900 dark:text-white font-medium max-w-xs truncate">{{ subject.name }}</td>
            <td class="py-3 px-4 text-gray-600 dark:text-gray-300">{{ subject.strand?.code || '—' }}</td>
            <td class="py-3 px-4 text-center text-gray-900 dark:text-white">{{ subject.grade_level }}</td>
            <td class="py-3 px-4 text-center text-gray-900 dark:text-white">{{ subject.hours }}</td>
            <td class="py-3 px-4 text-center">
              <span v-if="subject.is_core" class="px-2 py-0.5 rounded-md text-[10px] font-medium uppercase tracking-wider bg-amber-50 dark:bg-amber-950/50 text-amber-700 dark:text-amber-300 border border-amber-200 dark:border-amber-800">
                Core
              </span>
              <span v-else class="text-gray-400">—</span>
            </td>
            <td class="py-3 px-4 text-center">
              <span class="px-2 py-0.5 rounded-full text-[10px] font-normal uppercase tracking-wider inline-flex items-center gap-1.5 border"
                    :class="subject.is_active
                      ? 'bg-emerald-100 dark:bg-emerald-950/60 text-emerald-800 dark:text-emerald-300 border-emerald-200 dark:border-emerald-800'
                      : 'bg-gray-100 dark:bg-[#232D26] text-gray-600 dark:text-gray-400 border-gray-200 dark:border-[#3F4F43]'">
                <span :class="['w-1.5 h-1.5 rounded-full', subject.is_active ? 'bg-emerald-500' : 'bg-gray-400']"></span>
                {{ subject.is_active ? 'Active' : 'Inactive' }}
              </span>
            </td>
            <td class="py-3 px-4 text-right">
              <div class="flex items-center justify-end gap-1.5">
                <button @click="openModal(subject)"
                        class="px-2.5 py-1 text-[10px] font-normal text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-[#232D26] hover:bg-gray-200 dark:hover:bg-white/10 rounded-md transition-colors cursor-pointer">
                  Edit
                </button>
                <button @click="confirmDelete(subject)"
                        class="px-2.5 py-1 text-[10px] font-normal text-red-700 dark:text-red-400 bg-red-50 hover:bg-red-100 dark:bg-red-950/50 rounded-md border border-red-200 dark:border-red-900 transition-colors cursor-pointer">
                  Delete
                </button>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <Modal :show="showModal" @close="closeModal" max-width="xl">
      <div class="bg-white dark:bg-[#2D3A31] rounded-2xl border border-gray-200 dark:border-[#3F4F43] font-['Inter']">
        <div class="flex items-center justify-between px-6 pt-5 pb-4 border-b border-gray-100 dark:border-[#3F4F43]">
          <div>
            <h3 class="text-xs font-medium uppercase tracking-wider text-gray-900 dark:text-white">
              {{ editing ? 'Edit Subject' : 'New Subject' }}
            </h3>
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
          <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
            <div>
              <label class="block text-[11px] font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1.5">
                Code <span class="text-red-500">*</span>
              </label>
              <input v-model="form.code" type="text" placeholder="e.g. CORE-MATH"
                     class="w-full px-3 py-2 text-xs bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white rounded-xl focus:ring-2 focus:ring-[#004d08] focus:outline-none font-normal uppercase" />
            </div>
            <div class="sm:col-span-2">
              <label class="block text-[11px] font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1.5">
                Name <span class="text-red-500">*</span>
              </label>
              <input v-model="form.name" type="text"
                     class="w-full px-3 py-2 text-xs bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white rounded-xl focus:ring-2 focus:ring-[#004d08] focus:outline-none font-normal" />
            </div>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
            <div>
              <label class="block text-[11px] font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1.5">Strand</label>
              <select v-model="form.strand_id"
                      class="w-full px-3 py-2 text-xs bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white rounded-xl focus:ring-2 focus:ring-[#004d08] focus:outline-none font-normal cursor-pointer">
                <option value="">None (Core)</option>
                <option v-for="s in strands" :key="s.id" :value="s.id">{{ s.code }} — {{ s.name }}</option>
              </select>
            </div>
            <div>
              <label class="block text-[11px] font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1.5">
                Grade Level <span class="text-red-500">*</span>
              </label>
              <select v-model="form.grade_level"
                      class="w-full px-3 py-2 text-xs bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white rounded-xl focus:ring-2 focus:ring-[#004d08] focus:outline-none font-normal cursor-pointer">
                <option value="11">Grade 11</option>
                <option value="12">Grade 12</option>
                <option value="both">Both</option>
              </select>
            </div>
            <div>
              <label class="block text-[11px] font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1.5">
                Hours <span class="text-red-500">*</span>
              </label>
              <input v-model.number="form.hours" type="number" min="1" max="1000"
                     class="w-full px-3 py-2 text-xs bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white rounded-xl focus:ring-2 focus:ring-[#004d08] focus:outline-none font-normal" />
            </div>
          </div>

          <div>
            <label class="block text-[11px] font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1.5">Description</label>
            <textarea v-model="form.description" rows="2"
                      class="w-full px-3 py-2 text-xs bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white rounded-xl focus:ring-2 focus:ring-[#004d08] focus:outline-none font-normal resize-none"></textarea>
          </div>

          <div>
            <label class="block text-[11px] font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1.5">Prerequisite</label>
            <select v-model="form.prerequisite_subject_id"
                    class="w-full px-3 py-2 text-xs bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white rounded-xl focus:ring-2 focus:ring-[#004d08] focus:outline-none font-normal cursor-pointer">
              <option value="">None</option>
              <option v-for="p in prerequisites.filter(p => p.id !== editing?.id)" :key="p.id" :value="p.id">{{ p.code }} — {{ p.name }}</option>
            </select>
          </div>

          <div class="grid grid-cols-2 gap-3">
            <label class="flex items-start gap-3 p-3 bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] rounded-xl hover:bg-gray-100/60 dark:hover:bg-white/5 transition-colors cursor-pointer">
              <input v-model="form.is_core" type="checkbox" class="mt-0.5 w-4 h-4 text-[#004d08] border-gray-300 rounded focus:ring-[#004d08] cursor-pointer" />
              <span class="text-xs font-medium text-gray-900 dark:text-white">Core Subject</span>
            </label>
            <label class="flex items-start gap-3 p-3 bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] rounded-xl hover:bg-gray-100/60 dark:hover:bg-white/5 transition-colors cursor-pointer">
              <input v-model="form.is_active" type="checkbox" class="mt-0.5 w-4 h-4 text-[#004d08] border-gray-300 rounded focus:ring-[#004d08] cursor-pointer" />
              <span class="text-xs font-medium text-gray-900 dark:text-white">Active</span>
            </label>
          </div>

          <div class="flex justify-end gap-2.5 pt-4 border-t border-gray-100 dark:border-[#3F4F43]">
            <button type="button" @click="closeModal"
                    class="px-4 py-2 text-xs font-normal text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-white/5 rounded-xl transition-all cursor-pointer">
              Cancel
            </button>
            <button type="submit" :disabled="saving"
                    class="px-5 py-2 text-xs font-medium uppercase tracking-wider bg-[#004d08] text-white hover:bg-emerald-900 rounded-xl transition-all shadow-md active:scale-95 disabled:opacity-50 cursor-pointer">
              {{ saving ? 'Saving…' : (editing ? 'Save Changes' : 'Create Subject') }}
            </button>
          </div>
        </form>
      </div>
    </Modal>
  </div>
</template>

<script setup>
import { useFlash } from '@/Composables/useFlash'
import { useConfirm } from '@/Composables/useConfirm'
import { ref, reactive, computed } from 'vue'
import axios from 'axios'
import Modal from '@/Components/Modal.vue'

const props = defineProps({
  subjects:      { type: Array, default: () => [] },
  strands:       { type: Array, default: () => [] },
  prerequisites: { type: Array, default: () => [] },
})
const emit = defineEmits(['changed'])

const flash = useFlash()
const confirm = useConfirm()
const search       = ref('')
const filterStrand = ref('')
const filterGrade  = ref('')

const filtered = computed(() => {
  let list = props.subjects

  if (search.value) {
    const q = search.value.toLowerCase()
    list = list.filter(s =>
      s.code.toLowerCase().includes(q) || s.name.toLowerCase().includes(q)
    )
  }
  if (filterStrand.value === '__core__') {
    list = list.filter(s => !s.strand_id)
  } else if (filterStrand.value) {
    list = list.filter(s => s.strand_id === filterStrand.value)
  }
  if (filterGrade.value) {
    list = list.filter(s => s.grade_level === filterGrade.value)
  }
  return list
})

const showModal = ref(false)
const editing   = ref(null)
const saving    = ref(false)
const error     = ref('')
const form      = reactive({
  code: '', name: '', description: '',
  strand_id: '', grade_level: '11', hours: 80,
  is_core: false, prerequisite_subject_id: '', is_active: true,
})

const openModal = (subject = null) => {
  editing.value = subject
  error.value = ''
  if (subject) {
    form.code                    = subject.code
    form.name                    = subject.name
    form.description             = subject.description || ''
    form.strand_id               = subject.strand_id || ''
    form.grade_level             = subject.grade_level || '11'
    form.hours                   = subject.hours || 80
    form.is_core                 = !!subject.is_core
    form.prerequisite_subject_id = subject.prerequisite_subject_id || ''
    form.is_active               = !!subject.is_active
  } else {
    form.code = ''; form.name = ''; form.description = ''
    form.strand_id = ''; form.grade_level = '11'; form.hours = 80
    form.is_core = false; form.prerequisite_subject_id = ''; form.is_active = true
  }
  showModal.value = true
}

const closeModal = () => {
  showModal.value = false
  error.value = ''
  // Do NOT reset editing / form here — the modal is still fading out and
  // the title would flip from "Edit …" to "New …" mid-animation.
  // openModal() fully resets on the next open.
}

const submit = async () => {
  saving.value = true
  error.value = ''
  const payload = {
    ...form,
    strand_id:               form.strand_id || null,
    prerequisite_subject_id: form.prerequisite_subject_id || null,
  }
  try {
    if (editing.value) await axios.put(`/admin/subjects/${editing.value.id}`, payload)
    else               await axios.post('/admin/subjects', payload)

    flash.success(editing.value ? `Subject ${form.name} updated.` : `Subject ${form.name} created.`)
    closeModal()
    emit('changed')
  } catch (e) {
    if (e.response?.status === 422) error.value = Object.values(e.response.data.errors || {}).flat()[0]
    else                            error.value = e.response?.data?.message || 'Failed to save subject.'
    flash.error(error.value)
  } finally {
    saving.value = false
  }
}

const confirmDelete = async (subject) => {
  const { confirmed } = await confirm({
    title: 'Delete Subject',
    message: `Delete subject "${subject.name}"?`,
    details: ['Fails if the subject is used in any classes.'],
    confirmLabel: 'Delete',
    variant: 'danger',
  })
  if (!confirmed) return

  try {
    await axios.delete(`/admin/subjects/${subject.id}`)
    flash.success(`Subject ${subject.name} deleted.`)
    emit('changed')
  } catch (e) {
    flash.error(e.response?.data?.message || 'Cannot delete subject.')
  }
}
</script>