<template>
  <Head :title="`${assignment.title} - Salawag LMS`" />

  <AuthenticatedLayout searchPlaceholder="Search assignments, tasks, rubric..">

    <div class="relative z-10 px-3 sm:px-6 md:px-10 pb-16 sm:pb-24 space-y-4 sm:space-y-6 flex-1 mt-2">

      <!-- HERO -->
      <div v-observe
        class="relative w-full rounded-2xl sm:rounded-3xl overflow-hidden shadow-lg border border-[#006907]/20 dark:border-none min-h-[200px] sm:min-h-[220px] flex flex-col justify-center anim-fade-down">
        <img :src="heroImage" alt="Assignment Background"
          class="absolute inset-0 w-full h-full object-cover z-0 object-center" />
        <div class="absolute inset-0 bg-[#004d05]/85 dark:bg-[#152B1C]/90 animate-overlay z-0 mix-blend-multiply"></div>

        <div class="relative z-10 p-4 sm:p-8 md:p-10 flex flex-col justify-center space-y-3 sm:space-y-4">
          <div class="flex flex-wrap items-center gap-2 sm:gap-2.5">
            <span :class="assignment.is_published
              ? 'bg-emerald-500/90 text-white'
              : 'bg-slate-200/90 text-slate-800'"
              class="text-[10px] sm:text-[11px] md:text-xs font-black uppercase tracking-wider px-2.5 sm:px-3.5 py-1 sm:py-1.5 rounded-full shadow-sm flex items-center gap-1.5">
              <Icon :icon="assignment.is_published ? 'check-circle' : 'edit'" size="xs" />
              {{ assignment.is_published ? 'Published' : 'Draft' }}
            </span>
            <span class="bg-white/20 backdrop-blur-md text-white border border-white/20 text-[10px] sm:text-[11px] md:text-xs font-semibold px-2.5 sm:px-3.5 py-1 sm:py-1.5 rounded-full shadow-sm flex items-center gap-1.5">
              <Icon icon="book-open" size="xs" />
              {{ assignment.subject }} • {{ assignment.section }}
            </span>
            <span class="bg-white/20 backdrop-blur-md text-white border border-white/20 text-[10px] sm:text-[11px] md:text-xs font-semibold px-2.5 sm:px-3.5 py-1 sm:py-1.5 rounded-full shadow-sm flex items-center gap-1.5">
              <Icon icon="award" size="xs" />
              {{ assignment.points }} points
            </span>
          </div>

          <div class="space-y-1.5 sm:space-y-2 max-w-3xl">
            <h2 class="text-2xl sm:text-4xl md:text-5xl font-black text-white tracking-tight leading-tight">
              {{ assignment.title }}
            </h2>
          </div>

          <div class="flex flex-wrap gap-2 pt-2">
            <BackButton fallback="teacher.tasks.index"
              class="inline-flex items-center gap-1.5 bg-white/15 hover:bg-white/25 backdrop-blur-md text-white border border-white/20 px-3.5 py-2 rounded-xl text-xs font-bold shadow-sm transition-all">
              Back
            </BackButton>
            <Link :href="route('teacher.assignments.edit', assignment.id)"
              class="inline-flex items-center gap-1.5 bg-white/15 hover:bg-white/25 backdrop-blur-md text-white border border-white/20 px-3.5 py-2 rounded-xl text-xs font-bold shadow-sm transition-all">
              <Icon icon="edit" size="xs" />
              Edit
            </Link>
            <button v-if="!assignment.is_published" @click="togglePublish(true)" :disabled="publishing"
              class="inline-flex items-center gap-1.5 bg-[#F9C20C] hover:bg-amber-400 text-[#2C3E2D] px-3.5 py-2 rounded-xl text-xs font-black shadow-sm transition-all active:scale-95 disabled:opacity-60">
              <Icon icon="check-circle" size="xs" />
              {{ publishing ? 'Publishing…' : 'Publish Now' }}
            </button>
            <button v-else @click="togglePublish(false)" :disabled="publishing"
              class="inline-flex items-center gap-1.5 bg-white/15 hover:bg-white/25 backdrop-blur-md text-white border border-white/20 px-3.5 py-2 rounded-xl text-xs font-bold shadow-sm transition-all active:scale-95 disabled:opacity-60">
              <Icon icon="x-circle" size="xs" />
              {{ publishing ? 'Unpublishing…' : 'Unpublish' }}
            </button>
            <button @click="destroy" :disabled="deleting"
              class="inline-flex items-center gap-1.5 bg-rose-500/90 hover:bg-rose-600 text-white px-3.5 py-2 rounded-xl text-xs font-bold shadow-sm transition-all active:scale-95 disabled:opacity-60">
              <Icon icon="trash" size="xs" />
              {{ deleting ? 'Deleting…' : 'Delete' }}
            </button>
          </div>
        </div>
      </div>

      <!-- STATS -->
      <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div v-observe class="anim-slide-up bg-white dark:bg-[#2D3A31] rounded-2xl sm:rounded-3xl p-5 shadow-sm border border-slate-200/60 dark:border-[#3F4F43]"
          style="animation-delay: 100ms;">
          <span class="text-[10px] sm:text-[11px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400 block">Enrolled Students</span>
          <p class="text-2xl sm:text-3xl font-black text-slate-800 dark:text-white mt-2">{{ stats.total_students }}</p>
        </div>
        <div v-observe class="anim-slide-up bg-white dark:bg-[#2D3A31] rounded-2xl sm:rounded-3xl p-5 shadow-sm border border-slate-200/60 dark:border-[#3F4F43]"
          style="animation-delay: 150ms;">
          <span class="text-[10px] sm:text-[11px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400 block">Submitted</span>
          <p class="text-2xl sm:text-3xl font-black text-blue-600 dark:text-blue-400 mt-2">{{ stats.submitted }}</p>
        </div>
        <div v-observe class="anim-slide-up bg-white dark:bg-[#2D3A31] rounded-2xl sm:rounded-3xl p-5 shadow-sm border border-slate-200/60 dark:border-[#3F4F43]"
          style="animation-delay: 200ms;">
          <span class="text-[10px] sm:text-[11px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400 block">Graded</span>
          <p class="text-2xl sm:text-3xl font-black text-[#005506] dark:text-[#86EFAC] mt-2">{{ stats.graded }}</p>
        </div>
      </div>

      <!-- META -->
      <div v-observe class="anim-slide-up bg-white dark:bg-[#2D3A31] rounded-2xl sm:rounded-3xl p-4 sm:p-6 shadow-sm border border-slate-200/60 dark:border-[#3F4F43] space-y-4"
        style="animation-delay: 250ms;">

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
          <div class="flex items-start gap-3">
            <div class="w-10 h-10 rounded-xl bg-emerald-50 dark:bg-emerald-950/60 text-[#005506] dark:text-[#86EFAC] flex items-center justify-center shrink-0 border border-emerald-100 dark:border-emerald-900/40">
              <Icon icon="calendar" size="md" />
            </div>
            <div class="min-w-0">
              <span class="text-[10px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400 block">Due Date</span>
              <span class="text-sm font-extrabold text-slate-900 dark:text-white block mt-0.5">{{ formatFull(assignment.due_at) }}</span>
              <span class="text-[10px] font-semibold text-slate-400">{{ dueIn }}</span>
            </div>
          </div>

          <div class="flex items-start gap-3">
            <div class="w-10 h-10 rounded-xl bg-amber-50 dark:bg-amber-950/60 text-amber-700 dark:text-amber-300 flex items-center justify-center shrink-0 border border-amber-100 dark:border-amber-900/40">
              <Icon icon="tag" size="md" />
            </div>
            <div class="min-w-0">
              <span class="text-[10px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400 block">Category</span>
              <span class="text-sm font-extrabold text-slate-900 dark:text-white block mt-0.5">{{ categoryLabel(assignment.category) }}</span>
              <span class="text-[10px] font-semibold text-slate-400">DepEd grade component</span>
            </div>
          </div>

          <div class="flex items-start gap-3">
            <div :class="assignment.allow_late
              ? 'bg-blue-50 dark:bg-blue-950/60 text-blue-700 dark:text-blue-300 border-blue-100 dark:border-blue-900/40'
              : 'bg-rose-50 dark:bg-rose-950/60 text-rose-600 dark:text-rose-400 border-rose-100 dark:border-rose-900/40'"
              class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0 border">
              <Icon :icon="assignment.allow_late ? 'check-circle' : 'lock-closed'" size="md" />
            </div>
            <div class="min-w-0">
              <span class="text-[10px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400 block">Late Submissions</span>
              <span class="text-sm font-extrabold text-slate-900 dark:text-white block mt-0.5">{{ assignment.allow_late ? 'Allowed' : 'Not Allowed' }}</span>
              <span class="text-[10px] font-semibold text-slate-400">{{ assignment.allow_late ? 'Will be flagged late' : 'Auto-rejected after due' }}</span>
            </div>
          </div>
        </div>

        <div v-if="assignment.instructions" class="pt-4 border-t border-slate-100 dark:border-[#3F4F43] space-y-2">
          <h3 class="text-xs font-extrabold uppercase tracking-wider text-slate-500 dark:text-slate-400">Instructions</h3>
          <p class="text-xs sm:text-sm text-slate-700 dark:text-slate-300 whitespace-pre-line leading-relaxed">{{ assignment.instructions }}</p>
        </div>
      </div>

      <!-- SUBMISSIONS -->
      <div v-observe class="anim-slide-up bg-white dark:bg-[#2D3A31] rounded-2xl sm:rounded-3xl p-4 sm:p-6 shadow-sm border border-slate-200/60 dark:border-[#3F4F43] space-y-4"
        style="animation-delay: 300ms;">

        <div class="flex items-center gap-3 pb-3 border-b border-slate-100 dark:border-[#3F4F43]">
          <div class="w-2.5 h-7 bg-[#005506] dark:bg-[#86EFAC] rounded-full"></div>
          <div>
            <h3 class="text-base sm:text-lg font-extrabold text-slate-900 dark:text-white">Student Submissions</h3>
            <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 mt-0.5">{{ submissions.length }} records</p>
          </div>
        </div>

        <div v-if="submissions.length" class="overflow-x-auto -mx-2 sm:mx-0 rounded-xl sm:rounded-2xl border border-slate-200/80 dark:border-[#3F4F43]">
          <table class="w-full min-w-[720px] text-left border-collapse">
            <thead>
              <tr class="bg-[#F9F7F1] dark:bg-[#232D26] text-[10px] sm:text-[11px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400 border-b border-slate-200/80 dark:border-[#3F4F43]">
                <th class="py-3 px-3 sm:px-4">Student</th>
                <th class="py-3 px-3 sm:px-4">Status</th>
                <th class="py-3 px-3 sm:px-4">Submitted At</th>
                <th class="py-3 px-3 sm:px-4 text-center">Grade</th>
                <th class="py-3 px-3 sm:px-4 text-right">Action</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-[#3F4F43] text-xs sm:text-sm font-medium text-slate-700 dark:text-slate-200">
              <tr v-for="s in submissions" :key="s.id" class="hover:bg-slate-50/80 dark:hover:bg-[#232D26]/40 transition-colors">
                <td class="py-3.5 px-3 sm:px-4">
                  <div class="flex items-center gap-2.5">
                    <span class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-800 dark:bg-emerald-950/60 dark:text-emerald-300 font-extrabold text-[11px] flex items-center justify-center shrink-0">
                      {{ initialsOf(s.student_name) }}
                    </span>
                    <span class="font-extrabold text-slate-800 dark:text-white">{{ s.student_name }}</span>
                  </div>
                </td>

                <td class="py-3.5 px-3 sm:px-4">
                  <span :class="statusColor(s.status)"
                    class="text-[10px] font-black px-2.5 py-1 rounded-full uppercase tracking-wider border">
                    {{ statusLabel(s.status) }}
                  </span>
                </td>

                <td class="py-3.5 px-3 sm:px-4 text-slate-600 dark:text-slate-400">
                  {{ s.submitted_at ? formatFull(s.submitted_at) : '—' }}
                </td>

                <td class="py-3.5 px-3 sm:px-4 text-center">
                  <span v-if="s.grade !== null && s.grade !== undefined" class="font-black text-slate-900 dark:text-white">
                    {{ s.grade }}<span class="text-slate-400 font-normal"> / {{ assignment.points }}</span>
                  </span>
                  <span v-else class="text-slate-400">—</span>
                </td>

                <td class="py-3.5 px-3 sm:px-4 text-right">
                  <button v-if="s.status !== 'not_submitted'" @click="openGrade(s)"
                    class="bg-[#005506] dark:bg-[#86EFAC] hover:bg-[#004105] text-white dark:text-[#232D26] text-[11px] font-black px-3.5 py-1.5 rounded-lg shadow-sm transition-transform active:scale-95">
                    {{ s.grade == null ? 'Grade' : 'Edit Grade' }}
                  </button>
                  <span v-else class="text-[11px] text-slate-400 italic">No submission</span>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <div v-else class="bg-[#F9F7F1] dark:bg-[#232D26] rounded-2xl p-12 text-center border border-dashed border-slate-300 dark:border-[#3F4F43] space-y-2">
          <Icon icon="users" size="xl" class="text-slate-400 mx-auto" />
          <p class="text-sm font-bold text-slate-800 dark:text-white">No submissions yet</p>
          <p class="text-xs text-slate-500 dark:text-slate-400">Student turn-ins will appear here once they submit.</p>
        </div>
      </div>
    </div>

    <!-- GRADE MODAL -->
    <div v-if="active" class="fixed inset-0 z-50 bg-slate-950/60 backdrop-blur-xs flex items-center justify-center p-4"
      @click.self="active = null">
      <div class="bg-white dark:bg-[#2D3A31] rounded-3xl shadow-2xl w-full max-w-lg p-6 space-y-5 border border-slate-100 dark:border-[#3F4F43]">

        <div>
          <h3 class="font-black text-slate-800 dark:text-white text-lg">Grade: {{ active.student_name }}</h3>
          <p class="text-xs text-slate-500 dark:text-slate-400 font-semibold">
            Maximum points available: <span class="text-[#005506] dark:text-[#86EFAC]">{{ assignment.points }}</span>
          </p>
        </div>

        <div class="space-y-4">
          <div v-if="active.text_content" class="bg-[#F9F7F1] dark:bg-[#232D26] border border-slate-200/80 dark:border-[#3F4F43] rounded-xl p-3.5 text-xs text-slate-700 dark:text-slate-300 max-h-36 overflow-auto space-y-1">
            <span class="block text-[10px] font-bold uppercase tracking-wider text-slate-400">Student Note / Text:</span>
            <p>{{ active.text_content }}</p>
          </div>

          <div v-if="active.has_file" class="bg-emerald-50/60 dark:bg-emerald-950/40 border border-emerald-200/80 dark:border-emerald-900/40 rounded-xl p-3 flex items-center justify-between">
            <span class="text-xs font-bold text-slate-700 dark:text-slate-200">Student File Submission</span>
            <a :href="active.download_url" target="_blank"
              class="bg-[#005506] hover:bg-[#004105] text-white text-xs font-bold px-3 py-1.5 rounded-lg shadow-sm">
              Download 📥
            </a>
          </div>

          <div class="space-y-1">
            <label class="block text-xs font-extrabold uppercase tracking-wider text-slate-700 dark:text-slate-300">Grade Points *</label>
            <input v-model="gradeForm.grade" type="number" step="0.01" :max="assignment.points" placeholder="e.g. 95"
              class="w-full bg-slate-50/50 dark:bg-[#232D26] border border-slate-200 dark:border-[#3F4F43] rounded-xl px-4 py-2.5 text-xs sm:text-sm font-semibold text-slate-800 dark:text-slate-100 focus:outline-none focus:border-[#005506] focus:bg-white dark:focus:bg-[#2D3A31] transition-all" />
            <p v-if="gradeForm.errors.grade" class="text-red-600 dark:text-red-400 text-xs font-semibold mt-1">{{ gradeForm.errors.grade }}</p>
          </div>

          <div class="space-y-1">
            <label class="block text-xs font-extrabold uppercase tracking-wider text-slate-700 dark:text-slate-300">Feedback / Comments</label>
            <textarea v-model="gradeForm.feedback" rows="3" placeholder="Optional notes for the student..."
              class="w-full bg-slate-50/50 dark:bg-[#232D26] border border-slate-200 dark:border-[#3F4F43] rounded-xl p-3.5 text-xs sm:text-sm font-medium text-slate-800 dark:text-slate-100 focus:outline-none focus:border-[#005506] focus:bg-white dark:focus:bg-[#2D3A31] transition-all"></textarea>
          </div>
        </div>

        <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-100 dark:border-[#3F4F43]">
          <button @click="active = null"
            class="px-4 py-2 text-slate-600 dark:text-slate-300 hover:text-slate-800 dark:hover:text-white text-xs font-bold transition-colors">
            Cancel
          </button>
          <button @click="submitGrade" :disabled="gradeForm.processing"
            class="bg-[#005506] dark:bg-[#86EFAC] hover:bg-[#004105] text-white dark:text-[#232D26] px-5 py-2 rounded-xl text-xs font-black shadow-sm transition-all active:scale-95 disabled:opacity-50">
            {{ gradeForm.processing ? 'Saving…' : 'Save Grade' }}
          </button>
        </div>
      </div>
    </div>

  </AuthenticatedLayout>
</template>

<script setup>
import { confirmAction, showError } from '@/Pages/useSweetAlert'
import { ref, computed } from 'vue'
import { Head, Link, useForm, router } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import Icon from '@/Components/Icon.vue'
import heroImage from '../../../../assets/img/local/assignment.png'
import BackButton from '@/Components/BackButton.vue'

const props = defineProps({
  assignment:  { type: Object, default: () => ({}) },
  submissions: { type: Array,  default: () => [] },
  stats:       { type: Object, default: () => ({ total_students: 0, submitted: 0, graded: 0 }) },
})

const active = ref(null)
const publishing = ref(false)
const deleting = ref(false)

const gradeForm = useForm({ grade: '', feedback: '' })

function openGrade(sub) {
  active.value = sub
  gradeForm.grade = sub.grade ?? ''
  gradeForm.feedback = sub.feedback ?? ''
}

function submitGrade() {
  gradeForm.put(route('teacher.submissions.grade', active.value.id), {
    preserveScroll: true,
    onSuccess: () => {
      active.value = null
      gradeForm.reset()
    },
  })
}

function togglePublish(next) {
  publishing.value = true
  router.put(route('teacher.assignments.update', props.assignment.id), {
    is_published: next,
  }, {
    preserveScroll: true,
    onFinish: () => { publishing.value = false },
  })
}

async function destroy() {
  if (!await confirmAction('Delete this assignment? This cannot be undone.')) return
  deleting.value = true
  router.delete(route('teacher.assignments.destroy', props.assignment.id), {
    onFinish: () => { deleting.value = false },
  })
}

const dueIn = computed(() => {
  if (!props.assignment.due_at) return 'No due date'
  const diff = new Date(props.assignment.due_at) - new Date()
  if (diff <= 0) return 'Past due'
  const days = Math.floor(diff / 86400000)
  const hours = Math.floor((diff % 86400000) / 3600000)
  if (days > 0) return `${days}d ${hours}h remaining`
  return `${hours}h remaining`
})

function formatFull(v) {
  if (!v) return '—'
  try {
    return new Date(v).toLocaleString('en-US', {
      month: 'short', day: 'numeric', year: 'numeric',
      hour: 'numeric', minute: '2-digit', hour12: true,
    })
  } catch { return '—' }
}

function initialsOf(name) {
  if (!name) return '?'
  const parts = String(name).trim().split(/\s+/)
  if (parts.length >= 2) return (parts[0][0] + parts[parts.length - 1][0]).toUpperCase()
  return name.substring(0, 2).toUpperCase()
}

function categoryLabel(cat) {
  return {
    written_work:     'Written Work',
    performance_task: 'Performance Task',
    quarterly_exam:   'Quarterly Exam',
  }[cat] || 'General'
}

function statusColor(status) {
  return {
    submitted:     'bg-blue-100 text-blue-800 border-blue-200 dark:bg-blue-950/60 dark:text-blue-300 dark:border-blue-900/40',
    late:          'bg-amber-100 text-amber-800 border-amber-200 dark:bg-amber-950/60 dark:text-amber-300 dark:border-amber-900/40',
    graded:        'bg-emerald-100 text-emerald-800 border-emerald-200 dark:bg-emerald-950/60 dark:text-[#86EFAC] dark:border-emerald-900/40',
    not_submitted: 'bg-slate-100 text-slate-500 border-slate-200 dark:bg-[#232D26] dark:text-slate-400 dark:border-[#3F4F43]',
  }[status] || 'bg-slate-100 text-slate-700 dark:bg-[#232D26] dark:text-slate-300 dark:border-[#3F4F43]'
}

function statusLabel(status) {
  return {
    submitted:     'Submitted',
    late:          'Late',
    graded:        'Graded',
    not_submitted: 'Not Submitted',
  }[status] || status
}

const vObserve = {
  mounted(el) {
    el.classList.add('not-visible')
    const observer = new IntersectionObserver(([entry]) => {
      if (entry.isIntersecting) el.classList.add('is-animated')
      else el.classList.remove('is-animated')
    }, { threshold: 0.1 })
    observer.observe(el)
  },
}
</script>

<style scoped>
@keyframes pulse-opacity { 0%, 100% { opacity: 0.88; } 50% { opacity: 0.65; } }
.animate-overlay { animation: pulse-opacity 6s infinite ease-in-out; }

@keyframes slideUpFade { from { opacity: 0; transform: translateY(24px); } to { opacity: 1; transform: translateY(0); } }
@keyframes fadeInDown { from { opacity: 0; transform: translateY(-16px); } to { opacity: 1; transform: translateY(0); } }

.not-visible { opacity: 0; }
.is-animated.anim-fade-down { animation: fadeInDown 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
.is-animated.anim-slide-up { animation: slideUpFade 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
</style>