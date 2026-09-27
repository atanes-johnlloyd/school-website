<template>
  <Head :title="`${assignment.title} - Salawag LMS`" />

  <div class="min-h-screen flex bg-[#e8f5e9] font-['Inter'] relative">
    
    <!-- Sticky Sidebar Navigation -->
    <div class="sticky top-0 h-screen z-30 shrink-0">
      <Sidebar />
    </div>

    <!-- Main Workspace Canvas -->
    <main class="flex-1 relative overflow-y-auto min-h-screen flex flex-col justify-between">
      
      <div class="relative z-10 p-6 md:p-10 space-y-8 flex-1 pb-24">
        
        <!-- HEADER ROW -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
          <div class="space-y-1 max-w-2xl">
            <div class="flex items-center gap-2 font-['Anton'] text-2xl sm:text-3xl md:text-4xl tracking-wide uppercase leading-tight">
              <span class="text-[#005506]">{{ assignment.title }}</span>
            </div>
            
            <p class="text-xs sm:text-sm font-semibold text-slate-600">
              {{ assignment.subject }} · {{ assignment.section }} · <span class="text-[#005506] font-bold">{{ assignment.points }} Max Points</span>
            </p>

            <!-- Accent Star Line -->
            <div class="flex items-center gap-2 pt-1 max-w-sm">
              <div class="h-[2px] w-full bg-[#005506]"></div>
              <span class="text-[#005506] text-xs">★</span>
            </div>
          </div>

          <!-- Quick Action Buttons -->
          <div class="flex flex-wrap items-center gap-3 shrink-0">
            <button
              v-if="!assignment.is_published"
              @click="$inertia.put(route('teacher.assignments.update', assignment.id), { is_published: true })"
              class="bg-amber-500 hover:bg-amber-600 text-white text-xs font-bold px-4 py-2.5 rounded-xl shadow-sm transition-all"
            >
              Publish Now
            </button>
            <span v-else class="text-xs font-bold bg-emerald-100 text-emerald-800 border border-emerald-200 px-3 py-2 rounded-xl">
              Published
            </span>

            <Link 
              :href="route('teacher.assignments.edit', assignment.id)"
              class="bg-white hover:bg-slate-50 text-slate-700 border border-slate-200/80 text-xs font-bold px-4 py-2.5 rounded-xl shadow-sm transition-all"
            >
              Edit
            </Link>

            <Link 
              :href="route('teacher.classes.assignments.index', assignment.classroom_id)"
              class="bg-[#005506] hover:bg-[#003d04] text-white text-xs font-bold px-4 py-2.5 rounded-xl shadow-sm transition-all"
            >
              ← Back
            </Link>
          </div>
        </div>

        <!-- MAIN SECTION CANVAS -->
        <div class="relative rounded-3xl p-6 sm:p-8 overflow-hidden space-y-6">
          <div 
            class="absolute inset-0 bg-cover bg-center bg-no-repeat pointer-events-none z-0 opacity-15 rounded-3xl"
            :style="{ backgroundImage: `url(${dashboardBg})` }"
          ></div>

          <!-- METRICS STAT CARDS -->
          <div class="relative z-10 grid grid-cols-1 sm:grid-cols-3 gap-5">
            <div class="bg-white/90 backdrop-blur-sm rounded-2xl p-5 shadow-sm border border-slate-200/60">
              <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Enrolled Students</span>
              <p class="text-2xl sm:text-3xl font-black text-slate-800 mt-2">{{ stats.total_students }}</p>
            </div>

            <div class="bg-white/90 backdrop-blur-sm rounded-2xl p-5 shadow-sm border border-slate-200/60">
              <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Submitted</span>
              <p class="text-2xl sm:text-3xl font-black text-blue-600 mt-2">{{ stats.submitted }}</p>
            </div>

            <div class="bg-white/90 backdrop-blur-sm rounded-2xl p-5 shadow-sm border border-slate-200/60">
              <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Graded</span>
              <p class="text-2xl sm:text-3xl font-black text-[#005506] mt-2">{{ stats.graded }}</p>
            </div>
          </div>

          <!-- INSTRUCTIONS DETAIL (IF AVAILABLE) -->
          <div v-if="assignment.instructions" class="relative z-10 bg-white/90 backdrop-blur-sm rounded-2xl p-5 shadow-sm border border-slate-200/60 space-y-1">
            <h3 class="text-xs font-extrabold uppercase tracking-wider text-slate-500">Instructions</h3>
            <p class="text-xs sm:text-sm text-slate-700 whitespace-pre-line leading-relaxed">{{ assignment.instructions }}</p>
          </div>

          <!-- SUBMISSIONS TABLE -->
          <div class="relative z-10 bg-white/90 backdrop-blur-sm rounded-2xl shadow-sm border border-slate-200/60 overflow-hidden">
            <div class="overflow-x-auto">
              <table class="w-full text-left border-collapse">
                <thead>
                  <tr class="bg-emerald-900/5 text-slate-600 uppercase text-[11px] font-extrabold tracking-wider border-b border-slate-200/60">
                    <th class="px-6 py-3.5">Student</th>
                    <th class="px-6 py-3.5">Status</th>
                    <th class="px-6 py-3.5">Submitted At</th>
                    <th class="px-6 py-3.5">Grade</th>
                    <th class="px-6 py-3.5 text-right">Action</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs sm:text-sm">
                  <tr 
                    v-for="s in submissions" 
                    :key="s.id" 
                    class="hover:bg-emerald-50/40 transition-colors"
                  >
                    <td class="px-6 py-4 font-bold text-slate-800">
                      {{ s.student_name }}
                    </td>

                    <td class="px-6 py-4">
                      <span class="text-xs font-bold px-2.5 py-1 rounded-full uppercase" :class="statusColor(s.status)">
                        {{ s.status }}
                      </span>
                    </td>

                    <td class="px-6 py-4 text-slate-600 font-medium">
                      {{ s.submitted_at ? new Date(s.submitted_at).toLocaleString() : '—' }}
                    </td>

                    <td class="px-6 py-4 font-bold text-slate-800">
                      {{ s.grade ?? '—' }} <span v-if="s.grade != null" class="text-slate-400 font-normal">/ {{ assignment.points }}</span>
                    </td>

                    <td class="px-6 py-4 text-right">
                      <button 
                        v-if="s.status !== 'not_submitted'"
                        @click="openGrade(s)"
                        class="bg-[#005506] hover:bg-[#003d04] text-white text-xs font-bold px-3 py-1.5 rounded-lg shadow-sm transition-transform active:scale-95"
                      >
                        {{ s.grade == null ? 'Grade' : 'Edit Grade' }}
                      </button>
                    </td>
                  </tr>

                  <tr v-if="!submissions.length">
                    <td colspan="5" class="px-6 py-10 text-center text-xs sm:text-sm text-slate-500">
                      No student submissions yet.
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>

        </div>

      </div>

    </main>

    <!-- GRADING MODAL OVERLAY -->
    <div v-if="active" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4 z-50">
      <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg p-6 space-y-5 border border-slate-100">
        
        <div>
          <h3 class="font-black text-slate-800 text-lg">Grade: {{ active.student_name }}</h3>
          <p class="text-xs text-slate-500 font-semibold">Maximum points available: <span class="text-[#005506]">{{ assignment.points }}</span></p>
        </div>

        <div class="space-y-4">
          <!-- Text Submission Content -->
          <div v-if="active.text_content" class="bg-slate-50 border border-slate-200/80 rounded-xl p-3.5 text-xs text-slate-700 max-h-36 overflow-auto space-y-1">
            <span class="block text-[10px] font-bold uppercase tracking-wider text-slate-400">Student Note / Text:</span>
            <p>{{ active.text_content }}</p>
          </div>

          <!-- Attached File Link -->
          <div v-if="active.has_file" class="bg-emerald-50/60 border border-emerald-200/80 rounded-xl p-3 flex items-center justify-between">
            <span class="text-xs font-bold text-slate-700">Student File Submission:</span>
            <a 
              :href="active.download_url" 
              target="_blank" 
              class="bg-[#005506] hover:bg-[#003d04] text-white text-xs font-bold px-3 py-1.5 rounded-lg shadow-xs"
            >
              Download File 📥
            </a>
          </div>

          <!-- Grade Input -->
          <div class="space-y-1">
            <label class="block text-xs font-extrabold uppercase tracking-wider text-slate-700">Grade Points *</label>
            <input 
              v-model="gradeForm.grade" 
              type="number" 
              step="0.01"
              placeholder="e.g. 95"
              class="w-full bg-slate-50/50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs sm:text-sm font-semibold text-slate-800 focus:outline-none focus:border-[#005506] focus:bg-white transition-all"
            />
            <p v-if="gradeForm.errors.grade" class="text-red-600 text-xs font-semibold mt-1">{{ gradeForm.errors.grade }}</p>
          </div>

          <!-- Feedback Input -->
          <div class="space-y-1">
            <label class="block text-xs font-extrabold uppercase tracking-wider text-slate-700">Feedback / Comments</label>
            <textarea 
              v-model="gradeForm.feedback" 
              rows="3"
              placeholder="Optional notes for the student..."
              class="w-full bg-slate-50/50 border border-slate-200 rounded-xl p-3.5 text-xs sm:text-sm font-medium text-slate-800 focus:outline-none focus:border-[#005506] focus:bg-white transition-all"
            ></textarea>
          </div>
        </div>

        <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-100">
          <button 
            @click="active = null" 
            class="px-4 py-2 text-slate-600 hover:text-slate-800 text-xs font-bold transition-colors"
          >
            Cancel
          </button>
          <button 
            @click="submitGrade" 
            :disabled="gradeForm.processing"
            class="bg-[#005506] hover:bg-[#003d04] text-white px-5 py-2 rounded-xl text-xs font-bold shadow-sm hover:shadow transition-all duration-200 active:scale-95 disabled:opacity-50"
          >
            Save Grade
          </button>
        </div>

      </div>
    </div>

  </div>
</template>

<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3'
import { ref } from 'vue'
import Sidebar from '@/Components/Sidebar.vue'
import dashboardBg from '@/../assets/img/dashboardbackground.png'

const props = defineProps({
  assignment: Object,
  submissions: Array,
  stats: Object,
})

const active = ref(null)
const gradeForm = useForm({ grade: '', feedback: '' })

function openGrade(sub) {
  active.value = sub
  gradeForm.grade = sub.grade ?? ''
  gradeForm.feedback = sub.feedback ?? ''
}

function submitGrade() {
  gradeForm.put(route('teacher.submissions.grade', active.value.id), {
    onSuccess: () => { active.value = null; gradeForm.reset() }
  })
}

function statusColor(status) {
  return {
    submitted: 'bg-blue-100 text-blue-800 border border-blue-200',
    late:      'bg-amber-100 text-amber-800 border border-amber-200',
    graded:    'bg-emerald-100 text-emerald-800 border border-emerald-200',
    not_submitted: 'bg-slate-100 text-slate-500 border border-slate-200',
  }[status] || 'bg-slate-100 text-slate-700'
}
</script>
