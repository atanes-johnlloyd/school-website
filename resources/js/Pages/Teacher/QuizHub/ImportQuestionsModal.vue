<template>
  <Modal :show="show" @close="closeModal" max-width="2xl">
    <div class="bg-white dark:bg-[#2D3A31] rounded-2xl border border-slate-200 dark:border-[#3F4F43] font-['Inter'] flex flex-col max-h-[85vh]">

      <!-- Header -->
      <div class="flex items-center justify-between px-6 pt-5 pb-4 border-b border-slate-100 dark:border-[#3F4F43]">
        <div class="flex items-center gap-3">
          <div class="w-10 h-10 rounded-2xl bg-emerald-50 dark:bg-[#1C261E] border border-emerald-100 dark:border-[#3F4F43] flex items-center justify-center text-[#005506] dark:text-[#86EFAC] shrink-0">
            <Icon icon="upload" size="md" />
          </div>
          <div>
            <h3 class="text-xs font-black uppercase tracking-wider text-slate-900 dark:text-white">Import Questions</h3>
            <p class="text-[11px] text-slate-500 dark:text-slate-400 font-normal">Upload a CSV to bulk-add questions to your bank</p>
          </div>
        </div>
        <button type="button" @click="closeModal"
          class="p-1.5 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 rounded-xl hover:bg-slate-100 dark:hover:bg-white/5 transition-colors cursor-pointer">
          <Icon icon="x" size="md" />
        </button>
      </div>

      <!-- Body -->
      <div class="flex-1 min-h-0 overflow-y-auto px-6 py-5 space-y-5">

        <!-- Instructions + template -->
        <div class="p-4 bg-emerald-50/50 dark:bg-emerald-950/20 border border-emerald-100 dark:border-emerald-900/50 rounded-xl space-y-3">
          <p class="text-xs text-slate-700 dark:text-slate-300 leading-relaxed">
            <strong class="font-bold text-slate-900 dark:text-white">Expected columns:</strong>
            type, question_text, option_a, option_b, option_c, option_d, correct, points, explanation.
            <br>
            Types: <code class="bg-white dark:bg-[#232D26] px-1 rounded text-[10px]">multiple_choice</code>,
            <code class="bg-white dark:bg-[#232D26] px-1 rounded text-[10px]">true_false</code>,
            <code class="bg-white dark:bg-[#232D26] px-1 rounded text-[10px]">short_answer</code>,
            <code class="bg-white dark:bg-[#232D26] px-1 rounded text-[10px]">essay</code>.
          </p>
          <button type="button" @click="downloadTemplate"
            class="inline-flex items-center gap-2 px-3 py-1.5 text-[11px] font-black uppercase tracking-wider text-[#005506] dark:text-[#86EFAC] bg-white dark:bg-[#232D26] border border-emerald-200 dark:border-emerald-800 rounded-lg hover:bg-emerald-50 dark:hover:bg-emerald-950/40 transition-colors cursor-pointer">
            <Icon icon="download" size="xs" />
            Download CSV Template
          </button>
        </div>

        <!-- Subject selector -->
        <div class="space-y-1">
          <label class="block text-[10px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400">
            Subject <span class="text-rose-500">*</span>
          </label>
          <select v-model="subjectId"
            class="w-full bg-[#F9F7F1] dark:bg-[#232D26] text-slate-800 dark:text-slate-100 text-xs font-extrabold px-3.5 py-2.5 rounded-xl border border-slate-200/80 dark:border-[#3F4F43] focus:outline-none focus:ring-2 focus:ring-[#005506] appearance-none cursor-pointer pr-8">
            <option value="">— Select a subject —</option>
            <option v-for="s in subjects" :key="s.id" :value="s.id">{{ s.code }} — {{ s.name }}</option>
          </select>
        </div>

        <!-- General error -->
        <div v-if="generalError" class="p-3 bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800 rounded-xl text-rose-700 dark:text-rose-300 text-xs">
          {{ generalError }}
        </div>

        <!-- File drop -->
        <div>
          <label class="block text-[10px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-2">
            CSV File <span class="text-rose-500">*</span>
          </label>
          <label class="flex flex-col items-center justify-center w-full h-32 border-2 border-dashed rounded-xl cursor-pointer transition-colors"
            :class="file ? 'border-emerald-300 dark:border-emerald-700 bg-emerald-50/40 dark:bg-emerald-950/20' : 'border-slate-300 dark:border-[#3F4F43] hover:border-[#005506] dark:hover:border-[#86EFAC] bg-slate-50 dark:bg-[#232D26]'">
            <input ref="fileInput" type="file" accept=".csv,text/csv" class="hidden" @change="onFilePicked" />
            <div class="text-center">
              <Icon icon="upload" size="lg" class="mx-auto text-slate-400 dark:text-slate-500 mb-2" />
              <p v-if="file" class="text-xs font-bold text-slate-900 dark:text-white">{{ file.name }}</p>
              <p v-else class="text-xs font-bold text-slate-600 dark:text-slate-400">Click to browse or drop a CSV file here</p>
              <p v-if="file" class="text-[10px] text-slate-500 dark:text-slate-400 mt-0.5">{{ formatFileSize(file.size) }}</p>
              <p v-else class="text-[10px] text-slate-400 dark:text-slate-500 mt-0.5">Max 5 MB • .csv only</p>
            </div>
          </label>
        </div>

        <!-- Results -->
        <div v-if="result" class="space-y-3">
          <div class="p-4 rounded-xl border flex items-start gap-3"
            :class="result.failed?.length ? 'bg-amber-50 dark:bg-amber-950/20 border-amber-200 dark:border-amber-900/50' : 'bg-emerald-50 dark:bg-emerald-950/20 border-emerald-200 dark:border-emerald-900/50'">
            <Icon :icon="result.failed?.length ? 'alert-triangle' : 'check-circle'" size="md"
              :class="result.failed?.length ? 'text-amber-600 dark:text-amber-400' : 'text-emerald-600 dark:text-emerald-400'" />
            <div class="flex-1 min-w-0">
              <p class="text-xs font-bold"
                :class="result.failed?.length ? 'text-amber-800 dark:text-amber-300' : 'text-emerald-800 dark:text-emerald-300'">
                {{ result.created }} question{{ result.created === 1 ? '' : 's' }} imported
                <template v-if="result.failed?.length">
                  &bull; {{ result.failed.length }} row{{ result.failed.length === 1 ? '' : 's' }} skipped
                </template>
              </p>
            </div>
          </div>

          <div v-if="result.failed?.length" class="border border-slate-200 dark:border-[#3F4F43] rounded-xl overflow-hidden">
            <p class="px-3 py-2 text-[10px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400 bg-slate-50 dark:bg-[#232D26] border-b border-slate-100 dark:border-[#3F4F43]">
              Skipped Rows
            </p>
            <ul class="max-h-48 overflow-y-auto divide-y divide-slate-100 dark:divide-[#3F4F43]">
              <li v-for="(err, i) in result.failed" :key="i"
                class="px-3 py-2 text-[11px] text-slate-700 dark:text-slate-300">
                <span class="font-black text-rose-600 dark:text-rose-400">Row {{ err.row }}:</span>
                {{ err.error }}
              </li>
            </ul>
          </div>
        </div>

      </div>

      <!-- Footer -->
      <div class="px-6 py-4 border-t border-slate-100 dark:border-[#3F4F43] flex justify-end gap-2.5">
        <button type="button" @click="closeModal"
          class="px-4 py-2 text-xs font-bold text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-white/5 rounded-xl transition-all cursor-pointer">
          {{ result ? 'Close' : 'Cancel' }}
        </button>
        <button v-if="!result" type="button" :disabled="!file || !subjectId || isLoading" @click="submitImport"
          class="px-5 py-2 text-xs font-black uppercase tracking-wider bg-[#005506] dark:bg-[#86EFAC] text-white dark:text-[#232D26] hover:bg-[#004105] rounded-xl transition-all shadow-sm active:scale-95 disabled:opacity-50 cursor-pointer flex items-center gap-2">
          <Icon icon="upload" size="xs" />
          {{ isLoading ? 'Importing…' : 'Import Questions' }}
        </button>
        <button v-else type="button" @click="closeModalAndRefresh"
          class="px-5 py-2 text-xs font-black uppercase tracking-wider bg-[#005506] dark:bg-[#86EFAC] text-white dark:text-[#232D26] hover:bg-[#004105] rounded-xl transition-all shadow-sm active:scale-95 cursor-pointer">
          Done
        </button>
      </div>
    </div>
  </Modal>
</template>

<script setup>
import { ref, watch } from 'vue'
import { router } from '@inertiajs/vue3'
import Modal from '@/Components/Modal.vue'
import Icon from '@/Components/Icon.vue'

const props = defineProps({
  show:     { type: Boolean, default: false },
  subjects: { type: Array,   default: () => [] },
})

const emit = defineEmits(['close', 'imported'])

const fileInput    = ref(null)
const file         = ref(null)
const subjectId    = ref('')
const isLoading    = ref(false)
const generalError = ref('')
const result       = ref(null)

watch(() => props.show, (open) => {
  if (open) reset()
})

function reset() {
  file.value = null
  subjectId.value = ''
  isLoading.value = false
  generalError.value = ''
  result.value = null
  if (fileInput.value) fileInput.value.value = ''
}

function onFilePicked(e) {
  const picked = e.target.files?.[0]
  if (!picked) return
  if (!picked.name.toLowerCase().endsWith('.csv')) {
    generalError.value = 'Please select a .csv file.'
    return
  }
  generalError.value = ''
  file.value = picked
}

function downloadTemplate() {
  const headers = [
    'type', 'question_text',
    'option_a', 'option_b', 'option_c', 'option_d',
    'correct', 'points', 'explanation',
  ]
  const rows = [
    [
      'multiple_choice',
      'Which planet is known as the Red Planet?',
      'Venus', 'Mars', 'Jupiter', 'Saturn',
      'B', '1',
      'Mars appears red due to iron oxide (rust) on its surface.',
    ],
    [
      'true_false',
      'The Sun is a star.',
      'True', 'False', '', '',
      'A', '1',
      '',
    ],
    [
      'short_answer',
      'What is the chemical symbol for water?',
      '', '', '', '',
      '', '1',
      'Accepts "H2O" as the exact answer.',
    ],
    [
      'essay',
      'Explain Newton\'s Third Law with an example.',
      '', '', '', '',
      '', '5',
      'Graded manually by the teacher.',
    ],
  ]

  const csv = [headers, ...rows]
    .map(r => r.map(v => `"${String(v ?? '').replace(/"/g, '""')}"`).join(','))
    .join('\r\n')

  const blob = new Blob(['\uFEFF' + csv], { type: 'text/csv;charset=utf-8;' })
  const url = URL.createObjectURL(blob)
  const a = document.createElement('a')
  a.href = url
  a.download = 'questions-import-template.csv'
  document.body.appendChild(a)
  a.click()
  document.body.removeChild(a)
  URL.revokeObjectURL(url)
}

function submitImport() {
  if (!file.value || !subjectId.value) return
  isLoading.value = true
  generalError.value = ''
  result.value = null

  const fd = new FormData()
  fd.append('file', file.value)
  fd.append('subject_id', subjectId.value)
  fd.append('category', '')

  router.post(route('teacher.questions.import-csv'), fd, {
    forceFormData: true,
    preserveScroll: true,
    onSuccess: (page) => {
      // Controller returns { message, created, failed }
      // Inertia doesn't pipe custom response payloads through onSuccess directly,
      // so we read from flash if available, else assume success
      const flashCreated = page?.props?.flash?.created ?? 0
      result.value = {
        created: flashCreated,
        failed: page?.props?.flash?.failed ?? [],
      }
      emit('imported')
    },
    onError: (errors) => {
      generalError.value = errors.file || errors.subject_id || 'Import failed. Check your CSV file.'
    },
    onFinish: () => {
      isLoading.value = false
    },
  })
}

function closeModal() {
  reset()
  emit('close')
}

function closeModalAndRefresh() {
  emit('imported')
  closeModal()
}

function formatFileSize(bytes) {
  if (!bytes) return '—'
  const kb = bytes / 1024
  return kb < 1024 ? `${kb.toFixed(1)} KB` : `${(kb / 1024).toFixed(2)} MB`
}
</script>