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
              Subject: <span class="text-slate-800">{{ assignment.subject }}</span> 
              <span class="text-slate-300">|</span> 
              Instructor: <span class="text-slate-800">{{ assignment.teacher }}</span>
            </p>

            <!-- Star Line Accent -->
            <div class="flex items-center gap-2 pt-1 max-w-sm">
              <div class="h-[2px] w-full bg-[#005506]"></div>
              <span class="text-[#005506] text-xs">★</span>
            </div>
          </div>

          <!-- Back Navigation Button -->
          <Link 
            :href="route('student.classes.show', assignment.id)"
            class="inline-flex items-center gap-2 self-start md:self-center bg-white/90 hover:bg-[#005506] text-[#005506] hover:text-white px-4 py-2 rounded-xl text-xs font-bold border border-slate-200/80 shadow-sm transition-all duration-200 group shrink-0"
          >
            <span class="transition-transform group-hover:-translate-x-1">←</span> Back
          </Link>
        </div>

        <!-- MAIN SECTION CANVAS -->
        <div class="relative rounded-3xl p-6 sm:p-8 overflow-hidden space-y-6">
          <div 
            class="absolute inset-0 bg-cover bg-center bg-no-repeat pointer-events-none z-0 opacity-15 rounded-3xl"
            :style="{ backgroundImage: `url(${dashboardBg})` }"
          ></div>

          <!-- ASSIGNMENT DETAILS CARD -->
          <div class="relative z-10 bg-white/90 backdrop-blur-sm rounded-2xl p-6 shadow-sm border border-slate-200/60 space-y-4">
            <div class="flex items-center gap-3 text-xs sm:text-sm font-semibold flex-wrap">
              <!-- Due Date Pill -->
              <span 
                class="px-3 py-1 rounded-lg border font-bold"
                :class="isOverdue ? 'bg-rose-50 text-rose-700 border-rose-200' : 'bg-emerald-50 text-[#005506] border-emerald-200'"
              >
                Due: {{ formatDate(assignment.due_at) }}
              </span>

              <!-- Points Badge -->
              <span class="bg-slate-100 text-slate-700 px-3 py-1 rounded-lg border border-slate-200 font-bold">
                {{ assignment.points }} Points
              </span>

              <!-- Late Submission Flag -->
              <span 
                v-if="!assignment.allow_late"
                class="bg-rose-100/70 text-rose-800 text-xs px-3 py-1 rounded-lg font-bold border border-rose-200"
              >
                Late Submissions Closed
              </span>
            </div>

            <!-- Instructions -->
            <div v-if="assignment.instructions" class="text-xs sm:text-sm text-slate-700 leading-relaxed whitespace-pre-wrap border-t border-slate-100 pt-4">
              <h4 class="font-bold text-slate-800 mb-1 text-sm">Instructions:</h4>
              {{ assignment.instructions }}
            </div>
          </div>

          <!-- GRADED FEEDBACK BANNER -->
          <div v-if="isGraded" class="relative z-10 bg-emerald-50/90 backdrop-blur-sm border-2 border-[#005506]/30 rounded-2xl p-6 shadow-sm space-y-2">
            <div class="flex items-center justify-between">
              <span class="text-xs font-bold uppercase tracking-wider text-[#005506] bg-emerald-100 px-3 py-1 rounded-full">
                Graded
              </span>
              <span class="text-2xl font-black text-[#005506]">
                {{ submission.grade }} <span class="text-sm font-normal text-slate-500">/ {{ assignment.points }} pts</span>
              </span>
            </div>
            <p v-if="submission.feedback" class="text-xs sm:text-sm text-slate-700 leading-relaxed whitespace-pre-wrap pt-2 border-t border-emerald-200/60">
              <span class="font-bold text-slate-800">Feedback:</span> {{ submission.feedback }}
            </p>
          </div>

          <!-- SUBMISSION FORM CARD -->
          <div class="relative z-10 bg-white/90 backdrop-blur-sm rounded-2xl p-6 sm:p-8 shadow-sm border border-slate-200/60 space-y-5">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
              <h3 class="font-extrabold text-slate-800 text-base sm:text-lg">
                {{ submission ? 'Your Submission' : 'Submit Your Work' }}
              </h3>

              <span v-if="submission" class="text-xs font-semibold text-slate-500">
                Submitted {{ formatDate(submission.submitted_at) }}
                <span v-if="submission.status === 'late'" class="text-amber-700 font-bold ml-1">(Late)</span>
              </span>
            </div>

            <!-- Text Content Entry -->
            <div class="space-y-2">
              <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider">
                Text Response
              </label>
              <textarea 
                v-model="form.text_content" 
                rows="6"
                :disabled="isGraded"
                class="w-full text-xs sm:text-sm rounded-xl border-slate-200 focus:border-[#005506] focus:ring-[#005506] disabled:bg-slate-100/70 disabled:text-slate-500 p-3.5 transition-colors shadow-sm"
                placeholder="Type your response here..."
              ></textarea>
              <p v-if="form.errors.text_content" class="text-rose-600 text-xs font-semibold mt-1">
                {{ form.errors.text_content }}
              </p>
            </div>

            <!-- File Upload Input -->
            <div v-if="!isGraded" class="space-y-2 pt-2">
              <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider">
                Attach File (Optional)
              </label>
              <input 
                type="file" 
                @change="handleFileChange"
                class="block w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-emerald-50 file:text-[#005506] hover:file:bg-emerald-100 transition-colors cursor-pointer"
              />
              <p v-if="form.errors.file" class="text-rose-600 text-xs font-semibold mt-1">
                {{ form.errors.file }}
              </p>
            </div>

            <!-- Actions Footer -->
            <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
              <span v-if="isGraded" class="text-xs font-semibold text-slate-400">
                Submission locked after grading.
              </span>
              <div v-else class="ml-auto">
                <button 
                  @click="submit" 
                  :disabled="form.processing"
                  class="bg-[#005506] hover:bg-[#003d04] text-white text-xs font-bold px-6 py-2.5 rounded-xl shadow-sm hover:shadow transition-all duration-150 active:scale-95 disabled:opacity-50 cursor-pointer"
                >
                  {{ form.processing ? 'Submitting...' : (submission ? 'Resubmit Assignment' : 'Submit Assignment') }}
                </button>
              </div>
            </div>
          </div>

        </div>

      </div>

    </main>

  </div>
</template>

<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3'
import Sidebar from '@/Components/Sidebar.vue'
import dashboardBg from '@/../assets/img/dashboardbackground.png'

const props = defineProps({
  assignment: Object,
  submission: Object,
})

const form = useForm({
  text_content: props.submission?.text_content ?? '',
  file: null,
})

function handleFileChange(event) {
  form.file = event.target.files[0]
}

function submit() {
  form.post(route('student.assignments.submit', props.assignment.id))
}

const isGraded = props.submission?.status === 'graded'
const isOverdue = props.assignment?.due_at ? new Date(props.assignment.due_at) < new Date() : false

function formatDate(dateStr) {
  if (!dateStr) return 'N/A'
  return new Date(dateStr).toLocaleString('en-US', {
    month: 'short',
    day: 'numeric',
    year: 'numeric',
    hour: 'numeric',
    minute: '2-digit',
    hour12: true
  })
}
</script>
