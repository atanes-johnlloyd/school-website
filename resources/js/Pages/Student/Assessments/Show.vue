<template>
  <Head :title="`${assignment.title} - Salawag LMS`" />

  <StudentLayout>
    <div class="space-y-6 pb-10 font-['Inter'] min-w-0">

      <!-- HERO BANNER -->
      <div v-observe
        class="anim-fade-down relative overflow-hidden w-full rounded-2xl sm:rounded-3xl shadow-lg border border-[#006907]/20 dark:border-none min-h-[220px] sm:min-h-[260px] flex flex-col justify-center p-4 sm:p-8 md:p-10">
        <img
          :src="heroImage || 'https://images.unsplash.com/photo-1434030216411-0b793f4b4173?q=80&w=1600&auto=format&fit=crop'"
          alt="Assessment Hero" class="absolute inset-0 w-full h-full object-cover z-0 object-center" />

        <div class="absolute inset-0 bg-[#004d05]/85 dark:bg-[#152B1C]/90 animate-overlay z-0 mix-blend-multiply"></div>

        <div class="absolute -right-10 -bottom-10 w-72 h-72 rounded-full bg-emerald-300/20 blur-3xl pointer-events-none z-0"></div>
        <div class="absolute right-24 -top-16 w-80 h-80 rounded-full bg-[#F9C20C]/20 blur-3xl pointer-events-none z-0"></div>

        <div class="relative z-10 w-full space-y-4 sm:space-y-5">

          <!-- Top row: badges + back -->
          <div class="flex flex-col md:flex-row md:items-start justify-between gap-4">
            <div class="space-y-2 max-w-2xl">
              <div class="flex flex-wrap items-center gap-2 sm:gap-2.5">
                <div class="inline-flex items-center gap-1.5 bg-[#F9C20C] text-[#2C3E2D] font-black text-[10px] sm:text-xs px-3 sm:px-4 py-1 sm:py-1.5 rounded-full shadow-sm tracking-wide uppercase">
                  <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                      d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                  </svg>
                  ASSESSMENT DETAILS
                </div>

                <div class="inline-flex items-center gap-2 bg-black/30 dark:bg-black/50 backdrop-blur-md text-white border border-white/20 text-[10px] sm:text-xs font-semibold px-3 sm:px-4 py-1 sm:py-1.5 rounded-full shadow-sm">
                  <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                  {{ heroStatusLabel }}
                </div>
              </div>

              <div class="space-y-1">
                <h1 class="font-['Anton'] text-2xl sm:text-4xl md:text-5xl tracking-wide uppercase flex items-center gap-2 select-none flex-wrap leading-none text-white drop-shadow-md">
                  <span>ASSIGNMENT</span>
                  <span class="text-[#F9C20C] drop-shadow-[0_2px_8px_rgba(249,194,12,0.4)]">BRIEF</span>
                </h1>
                <p class="text-white/90 text-xs sm:text-sm leading-relaxed font-medium italic">
                  "Review the brief, then submit your work before the deadline."
                </p>
              </div>
            </div>

            <button
              type="button"
              @click="goBack"
              class="inline-flex items-center justify-center gap-2 bg-black/30 hover:bg-black/40 backdrop-blur-md text-white border border-white/20 font-bold px-4 py-2.5 rounded-xl transition-all active:scale-95 text-xs sm:text-sm cursor-pointer shadow-sm shrink-0 self-start">
              <span>← Back</span>
            </button>
          </div>

          <!-- Bottom row: subject info + stat cards -->
          <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 items-center pt-1">
            <div class="lg:col-span-7 flex items-center gap-3 sm:gap-4">
              <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-2xl border-2 border-white/30 bg-white/10 backdrop-blur-sm overflow-hidden shrink-0 shadow-lg flex items-center justify-center transition-transform hover:scale-105 duration-300">
                <Icon icon="clipboard-list" size="lg" class="text-white" />
              </div>

              <div class="space-y-0.5 min-w-0">
                <h2 class="text-base sm:text-xl font-black text-white tracking-tight leading-snug truncate">
                  {{ assignment.title }}
                </h2>
                <p class="text-white/90 text-xs font-medium truncate">
                  {{ assignment.subject || 'Subject' }}
                  <span v-if="assignment.section"> • {{ assignment.section }}</span>
                  • Instructor <span class="font-bold">{{ assignment.teacher || '—' }}</span>
                </p>
                <p class="text-white/70 text-[10px] font-medium">
                  School Year <span class="font-bold">{{ activeYear }}</span>
                </p>
              </div>
            </div>

            <div class="lg:col-span-5 grid grid-cols-3 gap-2.5 sm:gap-3 w-full">
              <div class="bg-black/30 dark:bg-black/50 backdrop-blur-md border border-white/20 rounded-2xl p-3 sm:p-3.5 hover:bg-black/40 transition-all duration-300 flex flex-col justify-between shadow-sm min-h-[75px]">
                <span class="text-[9px] sm:text-[10px] font-black text-emerald-100/80 uppercase tracking-wider block">
                  Points
                </span>
                <div class="text-lg sm:text-2xl font-black text-[#F9C20C] leading-tight my-0.5">
                  {{ assignment.points ?? '—' }}
                </div>
                <span class="text-[9px] sm:text-[10px] text-emerald-100/70 block font-medium truncate">
                  Total weight
                </span>
              </div>

              <div class="bg-black/30 dark:bg-black/50 backdrop-blur-md border border-white/20 rounded-2xl p-3 sm:p-3.5 hover:bg-black/40 transition-all duration-300 flex flex-col justify-between shadow-sm min-h-[75px]">
                <span class="text-[9px] sm:text-[10px] font-black text-emerald-100/80 uppercase tracking-wider block">
                  Category
                </span>
                <div class="text-xs sm:text-sm font-black text-white leading-tight my-0.5">
                  {{ categoryShort(assignment.category) }}
                </div>
                <span class="text-[9px] sm:text-[10px] text-emerald-100/70 block font-medium truncate">
                  Weight group
                </span>
              </div>

              <div class="bg-black/30 dark:bg-black/50 backdrop-blur-md border border-white/20 rounded-2xl p-3 sm:p-3.5 hover:bg-black/40 transition-all duration-300 flex flex-col justify-between shadow-sm min-h-[75px]">
                <span class="text-[9px] sm:text-[10px] font-black text-emerald-100/80 uppercase tracking-wider block">
                  Status
                </span>
                <div :class="['text-xs sm:text-sm font-black leading-tight my-0.5', heroStatusColor]">
                  {{ heroStatusLabel }}
                </div>
                <span class="text-[9px] sm:text-[10px] text-emerald-100/70 block font-medium truncate">
                  Deadline
                </span>
              </div>
            </div>
          </div>

        </div>
      </div>

      <!-- INSTRUCTIONS -->
      <div v-if="assignment.instructions"
        class="animate-fade-slide-up rounded-3xl bg-[#fbfdf9] dark:bg-[#2D3A31] border border-slate-200/80 dark:border-[#3F4F43] p-4 sm:p-6 shadow-sm space-y-4">
        <div class="flex items-center gap-3 pb-3 border-b border-slate-200/60 dark:border-[#3F4F43]">
          <div class="w-2.5 h-7 bg-[#004d08] dark:bg-[#86EFAC] rounded-full shrink-0"></div>
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
            <div class="w-10 h-10 rounded-xl bg-emerald-50 dark:bg-emerald-950/60 text-[#004d08] dark:text-[#86EFAC] flex items-center justify-center shrink-0 border border-emerald-100 dark:border-emerald-900/40">
              <Icon icon="calendar" size="md" />
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
            <div class="w-10 h-10 rounded-xl bg-amber-50 dark:bg-amber-950/60 text-amber-700 dark:text-amber-300 flex items-center justify-center shrink-0 border border-amber-100 dark:border-amber-900/40">
              <Icon icon="tag" size="md" />
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
            <div class="w-10 h-10 rounded-xl bg-blue-50 dark:bg-blue-950/60 text-blue-700 dark:text-blue-300 flex items-center justify-center shrink-0 border border-blue-100 dark:border-blue-900/40">
              <Icon :icon="assignment.allow_late ? 'check-circle' : 'lock-closed'" size="md" />
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
      <div v-if="isGraded"
        class="animate-fade-slide-up rounded-3xl bg-emerald-50/90 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-900/40 p-6 shadow-sm space-y-3">
        <div class="flex items-center justify-between gap-4 flex-wrap">
          <div class="flex items-center gap-3">
            <div class="w-11 h-11 rounded-2xl bg-[#004d08] dark:bg-[#86EFAC] text-white dark:text-[#232D26] flex items-center justify-center shadow-sm">
              <Icon icon="award" size="md" />
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

        <div v-if="submission.feedback"
          class="bg-white/70 dark:bg-[#2D3A31]/70 rounded-2xl p-4 border border-emerald-200/60 dark:border-emerald-900/40">
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
            <div class="w-2.5 h-7 bg-[#004d08] dark:bg-[#86EFAC] rounded-full shrink-0"></div>
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
        <div v-if="submission?.has_file"
          class="bg-emerald-50/70 dark:bg-emerald-950/40 border border-emerald-200/80 dark:border-emerald-900/40 rounded-2xl p-3.5 flex items-center justify-between gap-3">
          <div class="flex items-center gap-3 min-w-0">
            <div class="w-10 h-10 rounded-xl bg-white dark:bg-[#2D3A31] border border-emerald-200 dark:border-emerald-900/40 flex items-center justify-center shrink-0">
              <Icon icon="paper-clip" size="md" class="text-emerald-700 dark:text-[#86EFAC]" />
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
            <Icon icon="lock-closed" size="xs" />
            Submission locked after grading
          </span>
          <div v-else class="ml-auto">
            <button
              @click="submit"
              :disabled="form.processing"
              class="bg-[#004d08] dark:bg-[#86EFAC] hover:bg-[#003805] text-white dark:text-[#232D26] text-xs font-black px-6 py-3 rounded-2xl shadow-sm hover:shadow-md transition-all duration-150 active:scale-95 disabled:opacity-50 cursor-pointer flex items-center gap-2"
            >
              <template v-if="form.processing">
                <span>Submitting...</span>
              </template>
              <template v-else>
                <span>{{ submission ? 'Resubmit Assignment' : 'Submit Assignment' }}</span>
                <Icon icon="arrow-right" size="xs" />
              </template>
            </button>
          </div>
        </div>
      </div>

    </div>
  </StudentLayout>
</template>

<script setup>
import { ref, computed } from 'vue'
import { Head, useForm, usePage, router } from '@inertiajs/vue3'
import StudentLayout from '@/Layouts/StudentLayout.vue'
import Icon from '@/Components/Icon.vue'
import { useConfirm } from '@/Composables/useConfirm'

const props = defineProps({
  assignment: { type: Object, default: () => ({}) },
  submission: { type: Object, default: null },
})

const page = usePage()
const confirm = useConfirm()
const fileInput = ref(null)

const activeYear = computed(() => page.props.activeSchoolYear || '—')

function goBack() {
  // Inertia doesn't guarantee `router.back()`; use the browser API directly.
  // `history.length > 2` because the current page itself counts as 1 entry,
  // and Inertia pre-seeds one more during its first visit.
  if (window.history.length > 2 && document.referrer) {
    window.history.back()
    return
  }
  router.visit(
    props.assignment.classroom_id
      ? route('student.classes.show', props.assignment.classroom_id)
      : route('student.assessments.index')
  )
}

const form = useForm({
  text_content: props.submission?.text_content ?? '',
  file: null,
})

function handleFileChange(event) {
  form.file = event.target.files[0] ?? null
}

async function submit() {
  if (props.submission) {
    const ok = await confirm.open({
      title: 'Resubmit assignment?',
      message: 'This replaces your previous submission. The previously uploaded file (if any) will be deleted.',
      confirmLabel: 'Resubmit',
      tone: 'warning',
    })
    if (!ok) return
  }

  form.post(route('student.assignments.submit', props.assignment.id), {
    forceFormData: true,
    preserveScroll: true,
    onSuccess: () => {
      form.file = null
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
@keyframes fadeInDown {
  from { opacity: 0; transform: translateY(-20px); }
  to   { opacity: 1; transform: translateY(0); }
}

@keyframes fadeSlideUp {
  from { opacity: 0; transform: translateY(30px); }
  to   { opacity: 1; transform: translateY(0); }
}

.animate-fade-in-down  { animation: fadeInDown 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
.animate-fade-slide-up { animation: fadeSlideUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) 0.1s both; }
</style>