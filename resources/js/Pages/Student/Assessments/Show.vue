<template>
  <Head :title="`${assignment.title} - Salawag LMS`" />

  <div
    :class="[
      'min-h-screen flex bg-[#e8f5e9] dark:bg-[#232D26] font-[\'Inter\'] relative transition-colors duration-300',
      `text-scale-${fontSizeMode}`
    ]"
  >
    <Sidebar :is-open="isSidebarOpen" @close-sidebar="isSidebarOpen = false" />

    <main class="flex-1 relative overflow-y-auto min-h-screen flex flex-col justify-between w-full min-w-0">
      <navbartop
        searchPlaceholder="Search assessments, subjects, or deadlines..."
        @open-sidebar="isSidebarOpen = true"
        @font-size-changed="(size) => fontSizeMode = size"
      />

      <div class="relative z-10 p-4 sm:p-6 md:p-8 space-y-6 flex-1 pb-16">

        <!-- HERO BANNER -->
        <div class="animate-fade-in-down w-full bg-[#004d08] dark:bg-[#152B1C] text-white rounded-3xl p-6 sm:p-8 shadow-md border border-[#003805] dark:border-[#3F4F43] relative overflow-hidden space-y-4">
          <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/5 to-transparent animate-sheen pointer-events-none"></div>

          <div class="flex flex-col md:flex-row md:items-start justify-between gap-4 relative z-10">
            <div class="space-y-1 max-w-2xl">
              <div class="font-['Anton'] text-3xl sm:text-4xl md:text-5xl tracking-wide uppercase flex items-center gap-2 select-none flex-wrap">
                <span class="text-white">ASSESSMENT</span>
                <span class="animated-stroke-text">DETAILS</span>
              </div>
              <p class="text-xs sm:text-sm italic font-medium text-emerald-100/80">
                Review the brief, then submit your work before the deadline.
              </p>
              <div class="flex items-center gap-2 pt-1 max-w-xl">
                <div class="h-[1.5px] w-full bg-white/40"></div>
                <span class="text-white text-xs animate-spin-slow">★</span>
              </div>
            </div>

            <Link
              :href="assignment.classroom_id
                ? route('student.classes.show', assignment.classroom_id)
                : route('student.dashboard')"
              class="inline-flex items-center gap-1.5 bg-white/10 hover:bg-white/20 text-white border border-white/20 px-4 py-2 rounded-xl text-xs font-bold transition-all shrink-0 self-start"
            >
              ← Back
            </Link>
          </div>

          <div class="grid grid-cols-1 lg:grid-cols-12 gap-5 items-center pt-2 relative z-10">
            <div class="lg:col-span-7 flex flex-col sm:flex-row items-center gap-4">
              <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-2xl border-2 border-white/20 bg-white/10 overflow-hidden shrink-0 shadow-inner flex items-center justify-center text-3xl transition-transform hover:scale-105 duration-300">
                📝
              </div>
              <div class="space-y-1 text-center sm:text-left">
                <div class="inline-block bg-white/15 backdrop-blur-sm text-emerald-100 text-[11px] font-bold px-3 py-0.5 rounded-full border border-white/10 animate-float-soft">
                  {{ assignment.subject || 'Subject' }}
                  <span v-if="assignment.section"> • {{ assignment.section }}</span>
                </div>
                <h2 class="font-bold text-lg sm:text-xl text-white tracking-tight leading-snug">
                  {{ assignment.title }}
                </h2>
                <p class="text-xs text-emerald-100/70 font-medium">
                  Instructor: {{ assignment.teacher || '—' }}
                </p>
              </div>
            </div>

            <div class="lg:col-span-5 grid grid-cols-3 gap-2 sm:gap-3">
              <div class="bg-white/10 backdrop-blur-md border border-white/15 rounded-2xl py-3 px-3 flex flex-col justify-between hover:-translate-y-1 hover:bg-white/15 transition-all duration-300">
                <span class="text-[10px] font-medium text-emerald-100/80">Points</span>
                <div class="text-xl font-extrabold text-amber-300 my-0.5">
                  {{ assignment.points ?? '—' }}
                </div>
                <span class="text-[9px] text-emerald-100/70">Total weight</span>
              </div>

              <div class="bg-white/10 backdrop-blur-md border border-white/15 rounded-2xl py-3 px-3 flex flex-col justify-between hover:-translate-y-1 hover:bg-white/15 transition-all duration-300">
                <span class="text-[10px] font-medium text-emerald-100/80">Category</span>
                <div class="text-[11px] font-extrabold text-white my-0.5 leading-tight">
                  {{ categoryShort(assignment.category) }}
                </div>
                <span class="text-[9px] text-emerald-100/70">Weight group</span>
              </div>

              <div class="bg-white/10 backdrop-blur-md border border-white/15 rounded-2xl py-3 px-3 flex flex-col justify-between hover:-translate-y-1 hover:bg-white/15 transition-all duration-300">
                <span class="text-[10px] font-medium text-emerald-100/80">Status</span>
                <div :class="['text-[11px] font-extrabold my-0.5 leading-tight', heroStatusColor]">
                  {{ heroStatusLabel }}
                </div>
                <span class="text-[9px] text-emerald-100/70">Deadline</span>
              </div>
            </div>
          </div>
        </div>

        <!-- INSTRUCTIONS -->
        <div
          v-if="assignment.instructions"
          class="animate-fade-slide-up rounded-3xl bg-[#fbfdf9] dark:bg-[#2D3A31] border border-slate-200/80 dark:border-[#3F4F43] p-4 sm:p-6 shadow-sm space-y-4"
        >
          <div class="flex items-center gap-3 pb-3 border-b border-slate-200/60 dark:border-[#3F4F43]">
            <div class="w-2.5 h-7 bg-[#004d08] dark:bg-[#86EFAC] rounded-full"></div>
            <div>
              <h3 class="text-lg font-bold text-slate-900 dark:text-white tracking-tight leading-none">
                Instructions
              </h3>
              <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 mt-1">
                Read carefully before submitting
              </p>
            </div>
          </div>

          <div class="text-xs sm:text-sm text-slate-700 dark:text-slate-300 leading-relaxed whitespace-pre-wrap">
            {{ assignment.instructions }}
          </div>
        </div>

        <!-- METADATA ROW -->
        <div class="animate-fade-slide-up rounded-3xl bg-[#fbfdf9] dark:bg-[#2D3A31] border border-slate-200/80 dark:border-[#3F4F43] p-4 sm:p-6 shadow-sm">
          <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="flex items-start gap-3">
              <div class="w-10 h-10 rounded-xl bg-emerald-50 dark:bg-emerald-950/60 text-[#004d08] dark:text-[#86EFAC] flex items-center justify-center shrink-0 border border-emerald-100 dark:border-emerald-900/40 text-lg">
                📅
              </div>
              <div class="min-w-0">
                <span class="text-[10px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400 block">Due Date</span>
                <span :class="['text-sm font-extrabold block mt-0.5', isOverdue ? 'text-rose-600 dark:text-rose-400' : 'text-slate-900 dark:text-white']">
                  {{ formatDate(assignment.due_at) }}
                </span>
                <span v-if="!isOverdue" class="text-[10px] font-semibold text-emerald-700 dark:text-[#86EFAC]">Time remaining</span>
                <span v-else class="text-[10px] font-semibold text-rose-600 dark:text-rose-400">Past due</span>
              </div>
            </div>

            <div class="flex items-start gap-3">
              <div class="w-10 h-10 rounded-xl bg-amber-50 dark:bg-amber-950/60 text-amber-700 dark:text-amber-300 flex items-center justify-center shrink-0 border border-amber-100 dark:border-amber-900/40 text-lg">
                🏷️
              </div>
              <div class="min-w-0">
                <span class="text-[10px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400 block">Category</span>
                <span class="text-sm font-extrabold text-slate-900 dark:text-white block mt-0.5">
                  {{ categoryLabel(assignment.category) }}
                </span>
                <span class="text-[10px] font-semibold text-slate-500 dark:text-slate-400">Weight group</span>
              </div>
            </div>

            <div class="flex items-start gap-3">
              <div class="w-10 h-10 rounded-xl bg-blue-50 dark:bg-blue-950/60 text-blue-700 dark:text-blue-300 flex items-center justify-center shrink-0 border border-blue-100 dark:border-blue-900/40 text-lg">
                {{ assignment.allow_late ? '✅' : '🔒' }}
              </div>
              <div class="min-w-0">
                <span class="text-[10px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400 block">Late Submissions</span>
                <span class="text-sm font-extrabold text-slate-900 dark:text-white block mt-0.5">
                  {{ assignment.allow_late ? 'Allowed' : 'Not Allowed' }}
                </span>
                <span class="text-[10px] font-semibold text-slate-500 dark:text-slate-400">
                  {{ assignment.allow_late ? 'Will be flagged as late' : 'Auto-rejected after deadline' }}
                </span>
              </div>
            </div>
          </div>
        </div>

        <!-- GRADED FEEDBACK -->
        <div
          v-if="isGraded"
          class="animate-fade-slide-up rounded-3xl bg-gradient-to-br from-emerald-50 to-teal-50 dark:from-emerald-950/40 dark:to-teal-950/30 border-2 border-[#005506]/30 dark:border-[#86EFAC]/30 p-6 shadow-sm space-y-3"
        >
          <div class="flex items-center justify-between gap-4 flex-wrap">
            <div class="flex items-center gap-3">
              <div class="w-11 h-11 rounded-2xl bg-[#004d08] text-white flex items-center justify-center text-xl shadow-sm">
                🏆
              </div>
              <div>
                <span class="text-[10px] font-black uppercase tracking-wider text-[#005506] dark:text-[#86EFAC]">Graded</span>
                <p class="text-xs font-semibold text-slate-600 dark:text-slate-400">
                  Reviewed {{ formatDate(submission.graded_at) }}
                </p>
              </div>
            </div>
            <div class="text-right">
              <span class="text-3xl font-black text-[#004d08] dark:text-[#86EFAC]">{{ submission.grade }}</span>
              <span class="text-sm font-bold text-slate-500 dark:text-slate-400"> / {{ assignment.points }}</span>
              <p class="text-[10px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mt-0.5">
                {{ gradePercent }}% • {{ gradeRemarks }}
              </p>
            </div>
          </div>

          <div v-if="submission.feedback" class="bg-white/70 dark:bg-[#2D3A31]/70 rounded-2xl p-4 border border-emerald-200/60 dark:border-emerald-900/40">
            <span class="text-[10px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400 block mb-1">Teacher Feedback</span>
            <p class="text-xs sm:text-sm text-slate-700 dark:text-slate-300 leading-relaxed whitespace-pre-wrap">
              {{ submission.feedback }}
            </p>
          </div>
        </div>

        <!-- SUBMISSION PANEL -->
        <div class="animate-fade-slide-up rounded-3xl bg-[#fbfdf9] dark:bg-[#2D3A31] border border-slate-200/80 dark:border-[#3F4F43] p-4 sm:p-6 md:p-8 shadow-sm space-y-6">
          <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3 border-b border-slate-200/60 dark:border-[#3F4F43]">
            <div class="flex items-center gap-3">
              <div class="w-2.5 h-7 bg-[#004d08] dark:bg-[#86EFAC] rounded-full"></div>
              <div>
                <h3 class="text-lg font-bold text-slate-900 dark:text-white tracking-tight leading-none">
                  {{ submission ? 'Your Submission' : 'Submit Your Work' }}
                </h3>
                <p v-if="submission" class="text-xs font-semibold text-slate-500 dark:text-slate-400 mt-1">
                  Submitted {{ formatDate(submission.submitted_at) }}
                  <span v-if="submission.status === 'late'" class="text-amber-700 dark:text-amber-400 font-bold ml-1">
                    (Late)
                  </span>
                </p>
                <p v-else class="text-xs font-semibold text-slate-500 dark:text-slate-400 mt-1">
                  No submission on record yet
                </p>
              </div>
            </div>

            <span :class="statusPillClass"
              class="inline-flex items-center gap-1.5 text-[10px] font-extrabold px-3 py-1.5 rounded-full border uppercase tracking-wider self-start sm:self-auto">
              {{ statusPillLabel }}
            </span>
          </div>

          <!-- Existing file -->
          <div v-if="submission?.has_file" class="bg-emerald-50/70 dark:bg-emerald-950/40 border border-emerald-200/80 dark:border-emerald-900/40 rounded-2xl p-3.5 flex items-center justify-between gap-3">
            <div class="flex items-center gap-3 min-w-0">
              <div class="w-10 h-10 rounded-xl bg-white dark:bg-[#2D3A31] border border-emerald-200 dark:border-emerald-900/40 flex items-center justify-center text-lg shrink-0">
                📎
              </div>
              <div class="min-w-0">
                <p class="text-xs font-bold text-slate-800 dark:text-slate-200">Attached submission file</p>
                <p class="text-[10px] font-semibold text-emerald-700 dark:text-[#86EFAC]">Ready for teacher review</p>
              </div>
            </div>
            <a
              :href="submission.download_url"
              target="_blank"
              class="bg-[#004d08] hover:bg-[#003805] text-white text-xs font-bold px-4 py-2 rounded-xl shrink-0 transition-colors"
            >
              Download
            </a>
          </div>

          <!-- Text response -->
          <div class="space-y-2">
            <label class="block text-[11px] font-black uppercase tracking-wider text-slate-600 dark:text-slate-400">
              Text Response
            </label>
            <textarea
              v-model="form.text_content"
              rows="6"
              :disabled="isGraded"
              class="w-full text-xs sm:text-sm rounded-2xl bg-[#f5f7f2] dark:bg-[#232D26] border border-slate-200/80 dark:border-[#3F4F43] focus:border-[#004d08] dark:focus:border-[#86EFAC] focus:ring-2 focus:ring-[#004d08]/20 dark:focus:ring-[#86EFAC]/20 disabled:bg-slate-100/70 dark:disabled:bg-[#232D26]/60 disabled:text-slate-500 p-4 transition-all shadow-xs"
              placeholder="Type your response, paste a link, or summarize your work..."
            ></textarea>
            <p v-if="form.errors.text_content" class="text-rose-600 dark:text-rose-400 text-xs font-semibold mt-1">
              {{ form.errors.text_content }}
            </p>
          </div>

          <!-- File upload -->
          <div v-if="!isGraded" class="space-y-2 pt-2">
            <label class="block text-[11px] font-black uppercase tracking-wider text-slate-600 dark:text-slate-400">
              Attach File (Optional)
            </label>
            <input
              ref="fileInput"
              type="file"
              @change="handleFileChange"
              class="block w-full text-xs text-slate-500 dark:text-slate-400 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-[#004d08] dark:file:bg-[#86EFAC] file:text-white dark:file:text-[#232D26] hover:file:bg-[#003805] cursor-pointer"
            />
            <p v-if="form.errors.file" class="text-rose-600 dark:text-rose-400 text-xs font-semibold mt-1">
              {{ form.errors.file }}
            </p>
            <p class="text-[10px] text-slate-400 dark:text-slate-500 font-medium">
              Accepted: PDF, DOC, DOCX, PPT, PPTX, XLS, XLSX, TXT, JPG, PNG, ZIP • Max 10 MB
            </p>
          </div>

          <!-- Actions -->
          <div class="pt-4 border-t border-slate-100 dark:border-[#3F4F43] flex items-center justify-between">
            <span v-if="isGraded" class="text-xs font-semibold text-slate-400 dark:text-slate-500 flex items-center gap-1.5">
              <span>🔒</span> Submission locked after grading
            </span>
            <div v-else class="ml-auto">
              <button
                @click="submit"
                :disabled="form.processing"
                class="bg-[#004d08] dark:bg-[#86EFAC] hover:bg-[#003805] text-white dark:text-[#232D26] text-xs font-black px-6 py-3 rounded-2xl shadow-sm hover:shadow-md transition-all duration-150 active:scale-95 disabled:opacity-50 cursor-pointer flex items-center gap-2"
              >
                <span v-if="form.processing">Submitting...</span>
                <template v-else>
                  <span>{{ submission ? 'Resubmit Assignment' : 'Submit Assignment' }}</span>
                  <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                  </svg>
                </template>
              </button>
            </div>
          </div>
        </div>

      </div>
    </main>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { Head, Link, useForm } from '@inertiajs/vue3'
import Sidebar from '@/Components/Sidebar.vue'
import navbartop from '@/Components/navbartop.vue'

const props = defineProps({
  assignment: { type: Object, default: () => ({}) },
  submission: { type: Object, default: null },
})

const isSidebarOpen = ref(false)
const fontSizeMode = ref('base')
const fileInput = ref(null)

const form = useForm({
  text_content: props.submission?.text_content ?? '',
  file: null,
})

function handleFileChange(event) {
  form.file = event.target.files[0] ?? null
}

function submit() {
  form.post(route('student.assignments.submit', props.assignment.id), {
    forceFormData: true,
    preserveScroll: true,
    onSuccess: () => {
      if (fileInput.value) fileInput.value.value = ''
    },
  })
}

const isGraded = computed(() => props.submission?.status === 'graded')
const isOverdue = computed(() =>
  props.assignment?.due_at ? new Date(props.assignment.due_at) < new Date() : false
)

const heroStatusColor = computed(() => {
  if (isGraded.value) return 'text-[#86EFAC]'
  if (isOverdue.value) return 'text-rose-300'
  if (props.submission) return 'text-amber-300'
  return 'text-amber-200'
})

const heroStatusLabel = computed(() => {
  if (isGraded.value) return 'Graded'
  if (isOverdue.value) return 'Overdue'
  if (props.submission) return 'Submitted'
  return 'Pending'
})

const statusPillClass = computed(() => {
  if (isGraded.value) return 'bg-emerald-100 dark:bg-emerald-950/60 text-emerald-800 dark:text-[#86EFAC] border-emerald-200 dark:border-emerald-900/40'
  if (props.submission?.status === 'late') return 'bg-amber-100 dark:bg-amber-950/60 text-amber-800 dark:text-amber-300 border-amber-200 dark:border-amber-900/40'
  if (props.submission) return 'bg-blue-100 dark:bg-blue-950/60 text-blue-800 dark:text-blue-300 border-blue-200 dark:border-blue-900/40'
  return 'bg-slate-100 dark:bg-[#232D26] text-slate-600 dark:text-slate-300 border-slate-200 dark:border-[#3F4F43]'
})

const statusPillLabel = computed(() => {
  if (isGraded.value) return 'Graded'
  if (props.submission?.status === 'late') return 'Submitted (Late)'
  if (props.submission) return 'Submitted'
  return 'Not Submitted'
})

const gradePercent = computed(() => {
  if (!props.submission?.grade || !props.assignment?.points) return '—'
  return Math.round((props.submission.grade / props.assignment.points) * 100)
})

const gradeRemarks = computed(() => {
  const pct = parseFloat(gradePercent.value)
  if (isNaN(pct)) return ''
  if (pct >= 95) return 'Outstanding'
  if (pct >= 85) return 'Very Satisfactory'
  if (pct >= 75) return 'Satisfactory'
  if (pct >= 65) return 'Fairly Satisfactory'
  return 'Needs Improvement'
})

function formatDate(dateStr) {
  if (!dateStr) return 'N/A'
  try {
    return new Date(dateStr).toLocaleString('en-US', {
      month: 'short', day: 'numeric', year: 'numeric',
      hour: 'numeric', minute: '2-digit', hour12: true,
    })
  } catch { return dateStr }
}

function categoryLabel(cat) {
  return {
    written_work:     'Written Work',
    performance_task: 'Performance Task',
    quarterly_exam:   'Quarterly Exam',
  }[cat] || 'General'
}

function categoryShort(cat) {
  return {
    written_work:     'WW',
    performance_task: 'PT',
    quarterly_exam:   'QE',
  }[cat] || '—'
}
</script>

<style scoped>
.animated-stroke-text { color: transparent; -webkit-text-stroke: 1.5px #ffffff; }

@keyframes fadeInDown  { from { opacity: 0; transform: translateY(-20px); } to { opacity: 1; transform: translateY(0); } }
@keyframes fadeSlideUp { from { opacity: 0; transform: translateY(30px);  } to { opacity: 1; transform: translateY(0); } }
@keyframes floatSoft   { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-4px); } }
@keyframes sheenMove   { 0% { transform: translateX(-100%); } 100% { transform: translateX(200%); } }
@keyframes spinSlow    { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }

.animate-fade-in-down  { animation: fadeInDown 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
.animate-fade-slide-up { animation: fadeSlideUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) 0.1s both; }
.animate-float-soft    { animation: floatSoft 3s ease-in-out infinite; }
.animate-sheen         { animation: sheenMove 4s ease-in-out infinite; }
.animate-spin-slow     { display: inline-block; animation: spinSlow 12s linear infinite; }

.text-scale-sm :deep(.text-xs)   { font-size: 0.65rem !important; line-height: 0.85rem !important; }
.text-scale-sm :deep(.text-sm)   { font-size: 0.75rem !important; line-height: 1rem !important; }
.text-scale-sm :deep(.text-base) { font-size: 0.875rem !important; line-height: 1.25rem !important; }
.text-scale-lg :deep(.text-xs)   { font-size: 0.875rem !important; line-height: 1.25rem !important; }
.text-scale-lg :deep(.text-sm)   { font-size: 1rem !important; line-height: 1.5rem !important; }
.text-scale-lg :deep(.text-base) { font-size: 1.125rem !important; line-height: 1.75rem !important; }
</style>