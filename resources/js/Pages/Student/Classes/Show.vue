<template>
  <Head :title="`${classroom.subject} - Salawag LMS`" />

  <div class="min-h-screen flex bg-[#e8f5e9] font-['Inter'] relative">
    
    <!-- Sticky Desktop Sidebar Navigation -->
    <div class="sticky top-0 h-screen z-30 shrink-0">
      <Sidebar />
    </div>

    <!-- Main Workspace Canvas -->
    <main class="flex-1 relative overflow-y-auto min-h-screen flex flex-col justify-between">
      
      <div class="relative z-10 p-6 md:p-10 space-y-8 flex-1 pb-24">
        
        <!-- HEADER ROW -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
          <div class="space-y-1">
            <div class="flex items-center gap-2 font-['Anton'] text-2xl sm:text-3xl md:text-4xl tracking-wide uppercase">
              <span class="text-[#005506]">{{ classroom.subject }}</span>
            </div>
            
            <p class="text-xs sm:text-sm font-semibold text-slate-600">
              {{ classroom.section }} <span class="text-slate-300">|</span> Instructor: {{ classroom.teacher }}
            </p>

            <!-- Star Divider Line -->
            <div class="flex items-center gap-2 pt-1 max-w-sm">
              <div class="h-[2px] w-full bg-[#005506]"></div>
              <span class="text-[#005506] text-xs">★</span>
            </div>
          </div>

          <!-- Back Navigation Button -->
          <Link 
            :href="route('student.classes.index')"
            class="inline-flex items-center gap-2 self-start md:self-center bg-white/90 hover:bg-[#005506] text-[#005506] hover:text-white px-4 py-2 rounded-xl text-xs font-bold border border-slate-200/80 shadow-sm transition-all duration-200 group"
          >
            <span class="transition-transform group-hover:-translate-x-1">←</span> Back to classes
          </Link>
        </div>

        <!-- MAIN CONTAINER -->
        <div class="relative rounded-3xl p-6 sm:p-8 overflow-hidden space-y-6">
          <div 
            class="absolute inset-0 bg-cover bg-center bg-no-repeat pointer-events-none z-0 opacity-15 rounded-3xl"
            :style="{ backgroundImage: `url(${dashboardBg2})` }"
          ></div>

          <!-- CLASS NAVIGATION TABS -->
          <div class="relative z-10 border-b border-slate-200/80">
            <nav class="flex gap-8">
              <button 
                type="button" 
                class="pb-3 text-sm font-bold border-b-2 border-[#005506] text-[#005506]"
              >
                Assignments ({{ assignments?.length || 0 }})
              </button>
              <button disabled type="button" class="pb-3 text-sm font-semibold text-slate-400 cursor-not-allowed">
                Materials <span class="text-[10px] bg-slate-200/60 px-1.5 py-0.5 rounded text-slate-500 font-normal ml-1">soon</span>
              </button>
              <button disabled type="button" class="pb-3 text-sm font-semibold text-slate-400 cursor-not-allowed">
                Grades <span class="text-[10px] bg-slate-200/60 px-1.5 py-0.5 rounded text-slate-500 font-normal ml-1">soon</span>
              </button>
            </nav>
          </div>

          <!-- DIRECT ASSIGNMENT LIST SECTION -->
          <div class="relative z-10 space-y-4">
            
            <!-- EMPTY STATE -->
            <div 
              v-if="!assignments?.length" 
              class="bg-white/80 backdrop-blur-sm rounded-2xl p-10 text-center border border-slate-200/60 shadow-sm text-slate-500 text-sm font-medium"
            >
              No assignments posted for this subject yet.
            </div>

            <!-- ASSIGNMENT CARDS -->
            <div v-else class="space-y-3">
              <Link
                v-for="a in assignments"
                :key="a.id"
                :href="route('student.assignments.show', a.id)"
                class="block bg-white/90 backdrop-blur-sm rounded-2xl p-5 shadow-sm hover:shadow-md border border-slate-200/60 transition-all duration-200 hover:-translate-y-0.5 group"
              >
                <div class="flex items-start justify-between gap-4">
                  <div class="space-y-1">
                    <h3 class="font-bold text-slate-800 text-base group-hover:text-[#005506] transition-colors">
                      {{ a.title }}
                    </h3>
                    <p class="text-xs text-slate-500 font-medium">
                      Due {{ formatDueDate(a.due_at) }} · <span class="font-semibold text-slate-700">{{ a.points }} pts</span>
                    </p>
                  </div>

                  <!-- SUBMISSION STATUS BADGE -->
                  <span 
                    class="text-xs px-3 py-1 rounded-full font-bold shrink-0" 
                    :class="statusClass(a.submission)"
                  >
                    {{ statusLabel(a.submission) }}
                  </span>
                </div>

                <!-- GRADED INDICATOR -->
                <div v-if="a.submission?.grade != null" class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                  <span class="text-slate-500 font-medium">Recorded Grade:</span>
                  <span class="font-bold text-[#005506]">
                    {{ a.submission.grade }} / {{ a.points }} pts
                  </span>
                </div>
              </Link>
            </div>

          </div>

        </div>

      </div>

    </main>

  </div>
</template>

<script setup>
import { Head, Link } from '@inertiajs/vue3'
import Sidebar from '@/Components/Sidebar.vue'
import dashboardBg2 from '@/../assets/img/dashboardbackground2.png'

defineProps({
  classroom: Object,
  assignments: Array,
})

function formatDueDate(dueAt) {
  if (!dueAt) return 'No due date'
  return new Date(dueAt).toLocaleString('en-US', {
    month: 'short',
    day: 'numeric',
    year: 'numeric',
    hour: 'numeric',
    minute: '2-digit',
    hour12: true
  })
}

function statusLabel(sub) {
  if (!sub) return 'Not submitted'
  return sub.status === 'graded' ? 'Graded'
       : sub.status === 'late'   ? 'Submitted (late)'
       : 'Submitted'
}

function statusClass(sub) {
  if (!sub) return 'bg-slate-100 text-slate-600 border border-slate-200'
  if (sub.status === 'graded') return 'bg-emerald-100/80 text-[#005506]'
  if (sub.status === 'late')   return 'bg-amber-100 text-amber-800'
  return 'bg-blue-100 text-blue-800'
}
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Anton&family=Inter:wght@400;500;600;700;800&display=swap');
</style>