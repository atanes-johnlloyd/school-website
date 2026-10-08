<template>
  <Modal :show="show" @close="closeModal" max-width="2xl">
    <div class="bg-white dark:bg-[#2D3A31] rounded-2xl border border-gray-200 dark:border-[#3F4F43] font-['Inter'] flex flex-col max-h-[85vh]">

      <div class="flex items-center justify-between px-6 pt-5 pb-4 border-b border-gray-100 dark:border-[#3F4F43]">
        <div class="flex items-center gap-3">
          <div class="w-10 h-10 rounded-2xl bg-emerald-50 dark:bg-[#1C261E] border border-emerald-100 dark:border-[#3F4F43] flex items-center justify-center text-[#004d08] dark:text-[#86EFAC] shrink-0">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1M12 12V4m0 0L8 8m4-4l4 4" />
            </svg>
          </div>
          <div>
            <h3 class="text-xs font-medium uppercase tracking-wider text-gray-900 dark:text-white">Bulk Import Students</h3>
            <p class="text-[11px] text-gray-500 dark:text-gray-400 font-normal">Upload a CSV to create multiple student accounts</p>
          </div>
        </div>
        <button type="button" @click="closeModal" class="p-1.5 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 rounded-xl hover:bg-gray-100 dark:hover:bg-white/5 transition-colors cursor-pointer">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>

      <div class="flex-1 min-h-0 overflow-y-auto px-6 py-5 space-y-5">

        <div class="p-4 bg-emerald-50/50 dark:bg-emerald-950/20 border border-emerald-100 dark:border-emerald-900/50 rounded-xl">
          <p class="text-xs text-gray-700 dark:text-gray-300 leading-relaxed">
            <strong class="font-semibold text-gray-900 dark:text-white">Required columns:</strong>
            name, email, lrn, grade_level. All others are optional. Invalid rows are skipped; valid rows always save.
          </p>
          <button type="button" @click="downloadTemplate"
                  class="mt-3 inline-flex items-center gap-2 px-3 py-1.5 text-[11px] font-medium uppercase tracking-wider text-[#004d08] dark:text-[#86EFAC] bg-white dark:bg-[#232D26] border border-emerald-200 dark:border-emerald-800 rounded-lg hover:bg-emerald-50 dark:hover:bg-emerald-950/40 transition-colors cursor-pointer">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1M7 10l5 5m0 0l5-5m-5 5V3" />
            </svg>
            Download CSV Template
          </button>
        </div>

        <div v-if="generalError" class="p-3 bg-red-50 dark:bg-red-950/40 border border-red-200 dark:border-red-800 rounded-xl text-red-700 dark:text-red-300 text-xs">
          {{ generalError }}
        </div>

        <div>
          <label class="block text-[11px] font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-2">
            CSV File <span class="text-red-500">*</span>
          </label>
          <label class="flex flex-col items-center justify-center w-full h-32 border-2 border-dashed rounded-xl cursor-pointer transition-colors"
                 :class="file ? 'border-emerald-300 dark:border-emerald-700 bg-emerald-50/40 dark:bg-emerald-950/20' : 'border-gray-300 dark:border-[#3F4F43] hover:border-[#004d08] dark:hover:border-[#86EFAC] bg-gray-50 dark:bg-[#232D26]'">
            <input ref="fileInput" type="file" accept=".csv,text/csv" class="hidden" @change="onFilePicked" />
            <div class="text-center">
              <svg class="w-8 h-8 mx-auto text-gray-400 dark:text-gray-500 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
              </svg>
              <p v-if="file" class="text-xs font-medium text-gray-900 dark:text-white">{{ file.name }}</p>
              <p v-else class="text-xs font-medium text-gray-600 dark:text-gray-400">Click to browse or drop a CSV file here</p>
              <p v-if="file" class="text-[10px] text-gray-500 dark:text-gray-400 mt-0.5">{{ formatFileSize(file.size) }}</p>
              <p v-else class="text-[10px] text-gray-400 dark:text-gray-500 mt-0.5">Max 10 MB • .csv only</p>
            </div>
          </label>
        </div>

        <div v-if="result" class="space-y-3">
          <div class="p-4 rounded-xl border flex items-start gap-3"
               :class="result.errors.length ? 'bg-amber-50 dark:bg-amber-950/20 border-amber-200 dark:border-amber-900/50' : 'bg-emerald-50 dark:bg-emerald-950/20 border-emerald-200 dark:border-emerald-900/50'">
            <svg class="w-5 h-5 shrink-0 mt-0.5"
                 :class="result.errors.length ? 'text-amber-600 dark:text-amber-400' : 'text-emerald-600 dark:text-emerald-400'"
                 fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" :d="result.errors.length
                ? 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z'
                : 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'" />
            </svg>
            <div class="flex-1 min-w-0">
              <p class="text-xs font-semibold"
                 :class="result.errors.length ? 'text-amber-800 dark:text-amber-300' : 'text-emerald-800 dark:text-emerald-300'">
                {{ result.created }} student{{ result.created === 1 ? '' : 's' }} imported
                <template v-if="result.errors.length"> &bull; {{ result.errors.length }} row{{ result.errors.length === 1 ? '' : 's' }} skipped</template>
              </p>
            </div>
          </div>

          <div v-if="result.errors.length" class="border border-gray-200 dark:border-[#3F4F43] rounded-xl overflow-hidden">
            <p class="px-3 py-2 text-[10px] font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400 bg-gray-50 dark:bg-[#232D26] border-b border-gray-100 dark:border-[#3F4F43]">Skipped Rows</p>
            <ul class="max-h-48 overflow-y-auto divide-y divide-gray-100 dark:divide-[#3F4F43]">
              <li v-for="(err, i) in result.errors" :key="i" class="px-3 py-2 text-[11px] text-gray-700 dark:text-gray-300">{{ err }}</li>
            </ul>
          </div>
        </div>

      </div>

      <div class="px-6 py-4 border-t border-gray-100 dark:border-[#3F4F43] flex justify-end gap-2.5">
        <button type="button" @click="closeModal"
                class="px-4 py-2 text-xs font-normal text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-white/5 rounded-xl transition-all cursor-pointer">
          {{ result ? 'Close' : 'Cancel' }}
        </button>
        <button v-if="!result" type="button" :disabled="!file || isLoading" @click="submitImport"
                class="px-5 py-2 text-xs font-medium uppercase tracking-wider bg-[#004d08] text-white hover:bg-emerald-900 rounded-xl transition-all shadow-md active:scale-95 disabled:opacity-50 cursor-pointer">
          {{ isLoading ? 'Importing…' : 'Import Students' }}
        </button>
        <button v-else type="button" @click="closeModalAndRefresh"
                class="px-5 py-2 text-xs font-medium uppercase tracking-wider bg-[#004d08] text-white hover:bg-emerald-900 rounded-xl transition-all shadow-md active:scale-95 cursor-pointer">
          Done
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

const props = defineProps({ show: { type: Boolean, default: false } })
const emit  = defineEmits(['close', 'imported'])
const flash = useFlash()
const fileInput    = ref(null)
const file         = ref(null)
const isLoading    = ref(false)
const generalError = ref('')
const result       = ref(null)

watch(() => props.show, (open) => { if (open) reset() })

const reset = () => {
  file.value = null
  isLoading.value = false
  generalError.value = ''
  result.value = null
  if (fileInput.value) fileInput.value.value = ''
}

const onFilePicked = (e) => {
  const picked = e.target.files?.[0]
  if (!picked) return
  if (!picked.name.toLowerCase().endsWith('.csv')) {
    generalError.value = 'Please select a .csv file.'
    return
  }
  generalError.value = ''
  file.value = picked
  flash.error(generalError.value)
}

const downloadTemplate = () => {
  const headers = [
    'name', 'email', 'lrn', 'sex', 'date_of_birth', 'contact_number',
    'grade_level', 'section',
    'house_street', 'barangay', 'municipality', 'province', 'zip_code',
    'guardian_name', 'guardian_contact',
  ]
  const sample = [
    'Maria Santos', 'maria.santos@student.deped.gov.ph', '123456789012', 'female', '2009-05-14', '09171234567',
    '11', 'STEM-A',
    'Blk 1 Lot 2', 'Barangay Salawag', 'Dasmariñas', 'Cavite', '4114',
    'Roberto Santos', '09187654321',
  ]
  const csv = [headers, sample]
    .map(row => row.map(v => `"${String(v).replace(/"/g, '""')}"`).join(','))
    .join('\r\n')

  const blob = new Blob(['\uFEFF' + csv], { type: 'text/csv;charset=utf-8;' })
  const url  = URL.createObjectURL(blob)
  const a    = document.createElement('a')
  a.href     = url
  a.download = 'students-import-template.csv'
  document.body.appendChild(a)
  a.click()
  document.body.removeChild(a)
  URL.revokeObjectURL(url)
}

const submitImport = async () => {
  if (!file.value) return
  isLoading.value = true
  generalError.value = ''
  result.value = null

  const fd = new FormData()
  fd.append('file', file.value)

  try {
    const { data } = await axios.post('/admin/students/import', fd)
    result.value = { created: data.created, errors: data.errors || [] }

    if (data.errors?.length) {
      flash.info(`Imported ${data.created} student(s). ${data.errors.length} row(s) skipped.`)
    } else {
      flash.success(`Imported ${data.created} student(s).`)
    }

    emit('imported')
  } catch (error) {
    if (error.response?.status === 422) {
      const errs = error.response.data.errors || {}
      generalError.value = errs.file?.[0] || error.response.data.message || 'Validation failed.'
    } else {
      generalError.value = error.response?.data?.message || 'Import failed. Please try again.'
    }
    flash.error(generalError.value)
  }
}

const closeModal = () => { reset(); emit('close') }
const closeModalAndRefresh = () => { emit('imported'); closeModal() }

const formatFileSize = (b) => {
  if (!b) return '—'
  const kb = b / 1024
  return kb < 1024 ? `${kb.toFixed(1)} KB` : `${(kb / 1024).toFixed(2)} MB`
}
</script>