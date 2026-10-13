<template>
  <Modal :show="show" @close="closeModal" max-width="lg">
    <div class="bg-white dark:bg-[#2D3A31] rounded-2xl border border-gray-200 dark:border-[#3F4F43] font-['Inter']">

      <template v-if="!clusterChangeMode">
        <div class="flex items-center justify-between px-6 pt-5 pb-4 border-b border-gray-100 dark:border-[#3F4F43]">
          <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-2xl bg-emerald-50 dark:bg-[#1C261E] border border-emerald-100 dark:border-[#3F4F43] flex items-center justify-center text-[#004d08] dark:text-[#86EFAC] shrink-0">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5m0 0h4m-4 0V11m0 0h4m-4 0H9m4 0v10m-4-10v10" />
              </svg>
            </div>
            <div>
              <h3 class="text-xs font-medium uppercase tracking-wider text-gray-900 dark:text-white">
                {{ isEditing ? 'Edit Section' : 'New Section' }}
              </h3>
              <p class="text-[11px] text-gray-500 dark:text-gray-400 font-normal">
                {{ isEditing ? 'Update section details' : 'Classes and schedule are generated automatically' }}
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

          <div v-if="!isEditing">
            <label class="block text-[11px] font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1.5">
              School Year <span class="text-red-500">*</span>
            </label>
            <select v-model="form.school_year_id"
                    class="w-full px-3.5 py-2.5 text-xs bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white rounded-xl focus:ring-2 focus:ring-[#004d08] focus:outline-none font-normal cursor-pointer">
              <option value="" disabled>Select school year...</option>
              <option v-for="sy in schoolYears" :key="sy.id" :value="sy.id">
                {{ sy.label }}{{ sy.is_active ? ' (active)' : '' }}
              </option>
            </select>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            <div>
              <label class="block text-[11px] font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1.5">
                Grade Level <span class="text-red-500">*</span>
              </label>
              <select v-model="form.grade_level" :disabled="isEditing"
                      class="w-full px-3.5 py-2.5 text-xs bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white rounded-xl focus:ring-2 focus:ring-[#004d08] focus:outline-none font-normal cursor-pointer disabled:opacity-60">
                <option value="11">Grade 11</option>
                <option value="12">Grade 12</option>
              </select>
            </div>
            <div>
              <label class="block text-[11px] font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1.5">
                Strand <span class="text-red-500">*</span>
              </label>
              <select v-model="form.strand_id"
                      class="w-full px-3.5 py-2.5 text-xs bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white rounded-xl focus:ring-2 focus:ring-[#004d08] focus:outline-none font-normal cursor-pointer">
                <option value="" disabled>Select strand...</option>
                <option v-for="s in strands" :key="s.id" :value="s.id">{{ s.code }} — {{ s.name }}</option>
              </select>
            </div>
          </div>

          <div>
            <label class="block text-[11px] font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1.5">
              Section Name <span class="text-red-500">*</span>
            </label>
            <input v-model="form.name" @input="nameTouched = true" type="text" placeholder="e.g. STEM 11-A"
                   class="w-full px-3.5 py-2.5 text-xs bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white rounded-xl focus:ring-2 focus:ring-[#004d08] focus:outline-none font-normal" />
            <p v-if="suggestedName && form.name === suggestedName && !nameTouched" class="mt-1 text-[10px] text-emerald-600 dark:text-emerald-400">
              Auto-suggested from strand, grade, and existing sections.
            </p>
            <p v-else class="mt-1 text-[10px] text-gray-400 dark:text-gray-500">
              Must be unique within the same school year and grade level.
            </p>
          </div>

          <div>
            <label class="block text-[11px] font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1.5">
              Home Room
            </label>
            <select v-model="form.home_room_id"
                    class="w-full px-3.5 py-2.5 text-xs bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white rounded-xl focus:ring-2 focus:ring-[#004d08] focus:outline-none font-normal cursor-pointer">
              <option value="">Auto-assign</option>
              <option v-for="r in homeRooms" :key="r.id" :value="r.id">
                {{ r.code }} — {{ r.name }}
              </option>
            </select>
            <p class="mt-1 text-[10px] text-gray-400 dark:text-gray-500">
              Regular subjects default here. Labs use their own room type.
            </p>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            <div>
              <label class="block text-[11px] font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1.5">
                Adviser
              </label>
              <select v-model="form.adviser_id"
                      class="w-full px-3.5 py-2.5 text-xs bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white rounded-xl focus:ring-2 focus:ring-[#004d08] focus:outline-none font-normal cursor-pointer">
                <option value="">Unassigned</option>
                <option v-for="t in availableTeachers" :key="t.id" :value="t.id">
                  {{ t.name }}<template v-if="t.advising_section_id"> (current adviser)</template>
                </option>
              </select>
            </div>
            <div>
              <label class="block text-[11px] font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1.5">
                Max Capacity <span class="text-red-500">*</span>
              </label>
              <input v-model.number="form.max_capacity" type="number" min="1" max="100"
                     class="w-full px-3.5 py-2.5 text-xs bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white rounded-xl focus:ring-2 focus:ring-[#004d08] focus:outline-none font-normal" />
            </div>
          </div>

          <div class="flex justify-end gap-2.5 pt-4 border-t border-gray-100 dark:border-[#3F4F43]">
            <button type="button" @click="closeModal"
                    class="px-4 py-2 text-xs font-normal text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-white/5 rounded-xl transition-all cursor-pointer">
              Cancel
            </button>
            <button type="submit" :disabled="isLoading"
                    class="px-5 py-2 text-xs font-medium uppercase tracking-wider bg-[#004d08] text-white hover:bg-emerald-900 rounded-xl transition-all shadow-md active:scale-95 disabled:opacity-50 cursor-pointer">
              {{ isLoading ? 'Saving…' : (isEditing ? 'Save Changes' : 'Create Section') }}
            </button>
          </div>
        </form>
      </template>

      <template v-else>
        <div class="flex items-center gap-3 px-6 pt-5 pb-4 border-b border-gray-100 dark:border-[#3F4F43]">
          <div class="w-10 h-10 rounded-2xl bg-amber-50 dark:bg-amber-950/50 border border-amber-100 dark:border-amber-900/50 flex items-center justify-center text-amber-600 dark:text-amber-400 shrink-0">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
          </div>
          <div>
            <h3 class="text-xs font-medium uppercase tracking-wider text-gray-900 dark:text-white">
              Cluster Changed
            </h3>
            <p class="text-[11px] text-gray-500 dark:text-gray-400 font-normal">
              {{ clusterChangeFrom }} → {{ clusterChangeTo }}
            </p>
          </div>
        </div>

        <div class="px-6 py-5 space-y-4">
          <p class="text-xs text-gray-700 dark:text-gray-300 leading-relaxed">
            Core subjects stay untouched. Only the section's elective subjects are affected.
          </p>

          <div class="space-y-2">
            <button type="button" @click="applyClusterChange('replace')"
                    :disabled="isLoading"
                    class="w-full text-left p-3 rounded-xl border-2 border-emerald-300 dark:border-emerald-800 bg-emerald-50/50 dark:bg-emerald-950/20 hover:border-emerald-400 transition-colors cursor-pointer disabled:opacity-50">
              <p class="text-xs font-bold text-[#004d08] dark:text-[#86EFAC]">Replace electives</p>
              <p class="text-[10px] text-gray-600 dark:text-gray-400 mt-0.5">
                Keep the same class times. Swap subject, teacher, and room only.
              </p>
            </button>

            <button type="button" @click="applyClusterChange('regenerate')"
                    :disabled="isLoading"
                    class="w-full text-left p-3 rounded-xl border-2 border-amber-300 dark:border-amber-800 bg-amber-50/50 dark:bg-amber-950/20 hover:border-amber-400 transition-colors cursor-pointer disabled:opacity-50">
              <p class="text-xs font-bold text-amber-700 dark:text-amber-400">Regenerate all</p>
              <p class="text-[10px] text-gray-600 dark:text-gray-400 mt-0.5">
                Wipe and rebuild the entire schedule from curriculum.
              </p>
            </button>
          </div>

          <div v-if="generalError" class="p-3 bg-red-50 dark:bg-red-950/40 border border-red-200 dark:border-red-800 rounded-xl text-red-700 dark:text-red-300 text-xs">
            {{ generalError }}
          </div>
        </div>

        <div class="px-6 py-4 border-t border-gray-100 dark:border-[#3F4F43] flex justify-end">
          <button type="button" @click="cancelClusterChange" :disabled="isLoading"
                  class="px-4 py-2 text-xs font-normal text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-white/5 rounded-xl transition-all cursor-pointer disabled:opacity-50">
            Cancel
          </button>
        </div>
      </template>

    </div>
  </Modal>
</template>

<script setup>
import { ref, reactive, computed, watch } from 'vue'
import axios from 'axios'
import Modal from '@/Components/Modal.vue'
import { useFlash } from '@/Composables/useFlash'

const props = defineProps({
  show:        { type: Boolean, default: false },
  section:     { type: Object,  default: null },
  strands:     { type: Array,   default: () => [] },
  teachers:    { type: Array,   default: () => [] },
  schoolYears: { type: Array,   default: () => [] },
  homeRooms:   { type: Array,   default: () => [] },
  defaultMaxCapacity: { type: Number, default: 40 },
})

const emit  = defineEmits(['close', 'saved'])
const flash = useFlash()

const isLoading    = ref(false)
const generalError = ref('')

const nameTouched   = ref(false)
const suggestedName = ref('')

const isEditing = computed(() => !!props.section?.id)

// Cluster-change flow state
const clusterChangeMode = ref(false)
const clusterChangeFrom = ref('')
const clusterChangeTo   = ref('')
const clusterChangeId   = ref(null)

const availableTeachers = computed(() =>
  props.teachers.filter(t => {
    if (!t.advising_section_id) return true
    if (isEditing.value && t.id === form.adviser_id) return true
    return false
  })
)

const form = reactive({
  school_year_id: '',
  grade_level:    '11',
  strand_id:      '',
  name:           '',
  adviser_id:     '',
  home_room_id:   '',
  max_capacity:   40,
})

watch(() => props.show, (open) => { if (open) populate() })

const populate = () => {
  generalError.value = ''
  clusterChangeMode.value = false
  nameTouched.value = false
  suggestedName.value = ''

  if (props.section) {
    form.school_year_id = props.section.school_year_id
    form.grade_level    = props.section.grade_level
    form.strand_id      = props.section.strand_id || ''
    form.name           = props.section.name || ''
    form.adviser_id     = props.section.adviser_id || ''
    form.home_room_id   = props.section.home_room_id || ''
    form.max_capacity   = props.section.max_capacity || props.defaultMaxCapacity
    nameTouched.value   = true   // never auto-overwrite an existing name
  } else {
    form.school_year_id = props.schoolYears.find(sy => sy.is_active)?.id || ''
    form.grade_level    = '11'
    form.strand_id      = ''
    form.name           = ''
    form.adviser_id     = ''
    form.home_room_id   = ''
    form.max_capacity   = props.defaultMaxCapacity
  }
}

/* ─── Auto-suggest name ────────────────────────────────────────── */

watch(
  () => [form.school_year_id, form.grade_level, form.strand_id],
  async ([sy, grade, strand]) => {
    if (isEditing.value) return
    if (! sy || ! grade || ! strand) return

    try {
      const { data } = await axios.get('/admin/sections/next-name', {
        params: { school_year_id: sy, grade_level: grade, strand_id: strand },
      })

      suggestedName.value = data.name || ''

      if (! nameTouched.value) {
        form.name = data.name || ''
      }
    } catch (e) {
      // silent — leave field for manual entry
    }
  }
)

const closeModal = () => { generalError.value = ''; clusterChangeMode.value = false; emit('close') }

const submit = async () => {
  generalError.value = ''

  if (!form.name?.trim())     { generalError.value = 'Section name is required.'; return }
  if (!form.school_year_id)   { generalError.value = 'School year is required.'; return }
  if (!form.strand_id)        { generalError.value = 'Strand is required.'; return }
  if (form.max_capacity < 1 || form.max_capacity > 100) {
    generalError.value = 'Max capacity must be between 1 and 100.'
    return
  }

  isLoading.value = true

  const payload = {
    ...form,
    strand_id:    form.strand_id    || null,
    adviser_id:   form.adviser_id   || null,
    home_room_id: form.home_room_id || null,
  }

  try {
    if (isEditing.value) {
      const strandChanged = Number(form.strand_id) !== Number(props.section.strand_id)

      if (strandChanged) {
        await axios.put(`/admin/sections/${props.section.id}`, {
          name:         payload.name,
          adviser_id:   payload.adviser_id,
          home_room_id: payload.home_room_id,
          max_capacity: payload.max_capacity,
        })

        const oldStrand = props.strands.find(s => s.id === props.section.strand_id)
        const newStrand = props.strands.find(s => s.id === form.strand_id)

        clusterChangeFrom.value = oldStrand?.code || '—'
        clusterChangeTo.value   = newStrand?.code || '—'
        clusterChangeId.value   = form.strand_id
        clusterChangeMode.value = true
        isLoading.value = false
        return
      }

      await axios.put(`/admin/sections/${props.section.id}`, payload)
      flash.success(`Section ${form.name} updated.`)
    } else {
      await axios.post('/admin/sections', payload)
      flash.success(`Section ${form.name} created with auto-generated schedule.`)
    }

    emit('saved')
    closeModal()
  } catch (e) {
    const status = e.response?.status
    if (status === 422) {
      generalError.value = Object.values(e.response.data.errors || {}).flat()[0] || 'Validation failed.'
    } else if (status === 409) {
      generalError.value = e.response?.data?.message || 'Schedule conflict.'
    } else {
      generalError.value = e.response?.data?.message || 'Failed to save section.'
    }
    flash.error(generalError.value)
  } finally {
    isLoading.value = false
  }
}

const applyClusterChange = async (mode) => {
  generalError.value = ''
  isLoading.value = true

  try {
    await axios.put(`/admin/sections/${props.section.id}/change-cluster`, {
      strand_id: clusterChangeId.value,
      mode,
    })
    flash.success(mode === 'replace' ? 'Electives replaced.' : 'Schedule regenerated.')
    emit('saved')
    closeModal()
  } catch (e) {
    generalError.value = e.response?.data?.message || 'Cluster change failed.'
    flash.error(generalError.value)
  } finally {
    isLoading.value = false
  }
}

const cancelClusterChange = () => {
  clusterChangeMode.value = false
  generalError.value = ''
  form.strand_id = props.section.strand_id
}
</script>