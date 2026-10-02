<template>
  <Head :title="`${quiz.title} — Submissions - Salawag LMS`" />

  <AuthenticatedLayout searchPlaceholder="Search student attempts..">

    <div class="relative z-10 px-3 sm:px-6 md:px-10 pb-24 space-y-6 flex-1 mt-2 max-w-full">

      <!-- HERO -->
      <div v-observe
        class="anim-fade-down relative w-full rounded-2xl sm:rounded-3xl overflow-hidden shadow-lg border border-[#006907]/20 dark:border-none min-h-[180px] sm:min-h-[220px] flex flex-col justify-center">
        <img :src="heroImage" alt="Submissions Background"
          class="absolute inset-0 w-full h-full object-cover z-0 object-center" />
        <div class="absolute inset-0 bg-[#004d05]/85 dark:bg-[#152B1C]/90 animate-overlay z-0 mix-blend-multiply"></div>

        <div class="relative z-10 p-4 sm:p-8 md:p-10 max-w-3xl space-y-3">
          <div class="flex flex-wrap items-center gap-2">
            <span class="bg-[#F9C20C] text-[#2C3E2D] text-[10px] sm:text-xs font-black uppercase tracking-wider px-3 py-1 rounded-full shadow-sm flex items-center gap-1.5">
              <Icon icon="chart-bar" size="xs" />
              Submissions
            </span>
            <span class="bg-white/20 backdrop-blur-md text-white border border-white/20 text-[10px] sm:text-xs font-semibold px-3 py-1.5 rounded-full shadow-sm flex items-center gap-1.5">
              <Icon icon="academic-cap" size="xs" />
              {{ quiz.subject }} • {{ quiz.section }}
            </span>
          </div>
          <h2 class="text-xl sm:text-3xl md:text-4xl font-black text-white tracking-tight leading-tight">
            {{ quiz.title }}
          </h2>
          <div class="flex flex-wrap gap-2 pt-2">
            <BackButton fallback="teacher.quizzes.index"
              class="inline-flex items-center gap-1.5 bg-white/15 hover:bg-white/25 backdrop-blur-md text-white border border-white/20 px-3.5 py-2 rounded-xl text-xs font-bold shadow-sm transition-all">
              Back
            </BackButton>
            <Link :href="route('teacher.quizzes.show', quiz.id)"
              class="inline-flex items-center gap-1.5 bg-white/15 hover:bg-white/25 backdrop-blur-md text-white border border-white/20 px-3.5 py-2 rounded-xl text-xs font-bold shadow-sm transition-all">
              <Icon icon="edit" size="xs" />
              Manage Quiz
            </Link>
          </div>
        </div>
      </div>

      <!-- STATS -->
      <div class="grid grid-cols-2 lg:grid-cols-4 gap-3.5 sm:gap-5">
        <div class="bg-white dark:bg-[#2D3A31] rounded-2xl p-4 sm:p-6 shadow-sm border border-slate-200/60 dark:border-[#3F4F43] flex flex-col justify-between space-y-3">
          <span class="text-[10px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400">Attempted</span>
          <div class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white">{{ stats.attempted }}<span class="text-base text-slate-400">/{{ stats.total_students }}</span></div>
        </div>
        <div class="bg-white dark:bg-[#2D3A31] rounded-2xl p-4 sm:p-6 shadow-sm border border-slate-200/60 dark:border-[#3F4F43] flex flex-col justify-between space-y-3">
          <span class="text-[10px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400">Passed</span>
          <div class="text-2xl sm:text-3xl font-black text-[#005506] dark:text-[#86EFAC]">{{ stats.passed }}</div>
        </div>
        <div class="bg-white dark:bg-[#2D3A31] rounded-2xl p-4 sm:p-6 shadow-sm border border-slate-200/60 dark:border-[#3F4F43] flex flex-col justify-between space-y-3">
          <span class="text-[10px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400">Average</span>
          <div class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white">{{ stats.average_score ? Number(stats.average_score).toFixed(1) : '—' }}</div>
        </div>
        <div :class="stats.pending_essays > 0
          ? 'bg-amber-50 dark:bg-amber-950/30 border-amber-200/80 dark:border-amber-900/40'
          : 'bg-white dark:bg-[#2D3A31] border-slate-200/60 dark:border-[#3F4F43]'"
          class="rounded-2xl p-4 sm:p-6 shadow-sm border flex flex-col justify-between space-y-3">
          <span class="text-[10px] font-black uppercase tracking-wider text-amber-700 dark:text-amber-400">Pending Essays</span>
          <div class="text-2xl sm:text-3xl font-black text-amber-700 dark:text-amber-400">{{ stats.pending_essays }}</div>
        </div>
      </div>

      <!-- ATTEMPTS TABLE -->
      <div class="bg-white dark:bg-[#2D3A31] rounded-2xl p-4 sm:p-6 shadow-sm border border-slate-200/60 dark:border-[#3F4F43] space-y-4">

        <div class="flex items-center gap-3 pb-3 border-b border-slate-100 dark:border-[#3F4F43]">
          <div class="w-2.5 h-7 bg-[#005506] dark:bg-[#86EFAC] rounded-full"></div>
          <div class="flex-1">
            <h3 class="text-base font-extrabold text-slate-900 dark:text-white">Student Attempts</h3>
            <p class="text-[11px] font-semibold text-slate-500 dark:text-slate-400 mt-0.5">{{ attempts.length }} record{{ attempts.length === 1 ? '' : 's' }}</p>
          </div>
        </div>

        <div v-if="attempts.length" class="overflow-x-auto -mx-2 sm:mx-0 rounded-xl border border-slate-200/80 dark:border-[#3F4F43]">
          <table class="w-full min-w-[720px] text-left border-collapse">
            <thead>
              <tr class="bg-[#F9F7F1] dark:bg-[#232D26] text-[10px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400 border-b border-slate-200/80 dark:border-[#3F4F43]">
                <th class="py-3 px-3">Student</th>
                <th class="py-3 px-3 text-center">Attempt</th>
                <th class="py-3 px-3">Submitted</th>
                <th class="py-3 px-3 text-center">Score</th>
                <th class="py-3 px-3 text-center">Status</th>
                <th class="py-3 px-3 text-center">Warnings</th>
                <th class="py-3 px-3 text-right">Action</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-[#3F4F43] text-xs">
              <tr v-for="a in attempts" :key="a.id" class="hover:bg-slate-50/80 dark:hover:bg-[#232D26]/40 transition-colors">
                <td class="py-3 px-3">
                  <div class="flex items-center gap-2">
                    <span class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-800 dark:bg-emerald-950/60 dark:text-emerald-300 font-bold text-[10px] flex items-center justify-center shrink-0">
                      {{ initialsOf(a.student_name) }}
                    </span>
                    <span class="font-bold text-slate-800 dark:text-white">{{ a.student_name }}</span>
                  </div>
                </td>
                <td class="py-3 px-3 text-center font-bold text-slate-600 dark:text-slate-300">#{{ a.attempt_number }}</td>
                <td class="py-3 px-3 text-slate-600 dark:text-slate-300 text-[11px]">{{ a.submitted_at ? formatShort(a.submitted_at) : '—' }}</td>
                <td class="py-3 px-3 text-center">
                  <span v-if="a.score !== null" class="font-black text-slate-900 dark:text-white">
                    {{ a.score }}<span class="text-slate-400 font-normal"> / {{ a.total_points }}</span>
                  </span>
                  <span v-else class="text-slate-400">—</span>
                </td>
                <td class="py-3 px-3 text-center">
                  <span :class="statusBadge(a.status)"
                    class="text-[10px] font-black uppercase px-2.5 py-1 rounded-full border">
                    {{ a.status }}
                  </span>
                </td>
                <td class="py-3 px-3 text-center">
                  <span v-if="a.warning_count > 0" class="text-rose-600 dark:text-rose-400 font-black">{{ a.warning_count }}</span>
                  <span v-else class="text-slate-400">0</span>
                </td>
                <td class="py-3 px-3 text-right">
                  <Link :href="route('teacher.quiz-attempts.show', a.id)"
                    class="inline-flex items-center gap-1.5 bg-[#005506] dark:bg-[#86EFAC] hover:bg-[#004105] text-white dark:text-[#232D26] text-[11px] font-black px-3 py-1.5 rounded-lg shadow-sm transition-all">
                    <Icon icon="eye" size="xs" />
                    {{ a.has_pending_essay ? 'Grade Essays' : 'Review' }}
                  </Link>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <div v-else class="bg-[#F9F7F1] dark:bg-[#232D26] rounded-2xl p-12 text-center border border-dashed border-slate-300 dark:border-[#3F4F43] space-y-2">
          <Icon icon="users" size="xl" class="text-slate-400 mx-auto" />
          <p class="text-sm font-bold text-slate-800 dark:text-white">No attempts yet</p>
          <p class="text-xs text-slate-500 dark:text-slate-400">Student submissions will appear here once they take the quiz.</p>
        </div>

      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import { Head, Link } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import Icon from '@/Components/Icon.vue'
import BackButton from '@/Components/BackButton.vue'
import heroImage from '../../../../assets/img/local/quiz-hub-hero.jpg'

const props = defineProps({
  quiz:     { type: Object, default: () => ({}) },
  attempts: { type: Array,  default: () => [] },
  stats:    { type: Object, default: () => ({ total_students: 0, attempted: 0, passed: 0, average_score: null, pending_essays: 0 }) },
})

function initialsOf(name) {
  if (!name) return '?'
  const parts = String(name).trim().split(/\s+/)
  if (parts.length >= 2) return (parts[0][0] + parts[parts.length - 1][0]).toUpperCase()
  return name.substring(0, 2).toUpperCase()
}

function formatShort(v) {
  if (!v) return '—'
  try {
    return new Date(v).toLocaleString('en-US', { month: 'short', day: 'numeric', hour: 'numeric', minute: '2-digit', hour12: true })
  } catch { return '—' }
}

function statusBadge(status) {
  return {
    in_progress: 'bg-amber-100 text-amber-800 border-amber-200 dark:bg-amber-950/60 dark:text-amber-300 dark:border-amber-900/40',
    submitted:   'bg-blue-100 text-blue-800 border-blue-200 dark:bg-blue-950/60 dark:text-blue-300 dark:border-blue-900/40',
    graded:      'bg-emerald-100 text-emerald-800 border-emerald-200 dark:bg-emerald-950/60 dark:text-[#86EFAC] dark:border-emerald-900/40',
  }[status] || 'bg-slate-100 text-slate-700 border-slate-200 dark:bg-[#232D26] dark:text-slate-300 dark:border-[#3F4F43]'
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