<template>
  <Head title="Teacher Dashboard - Salawag LMS" />

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
            <div class="flex items-center gap-2 font-['Anton'] text-3xl sm:text-4xl md:text-5xl tracking-wide uppercase">
              <span class="text-[#005506]">STUDENT</span>
              <span class="text-transparent" style="-webkit-text-stroke: 1.5px #005506;">DASHBOARD</span>
            </div>

            <p class="text-xs sm:text-sm italic font-medium text-slate-600">
              "Your journey to knowledge starts with one click."
            </p>
            
            <p v-if="activeTerm" class="text-xs sm:text-sm font-semibold text-slate-600">
              Active Term: <span class="text-slate-800 font-bold">{{ activeTerm }}</span>
              <span v-if="today" class="text-slate-300 mx-2">|</span> 
              <span v-if="today" class="text-slate-600">{{ today }}</span>
            </p>

            <!-- Accent Star Line -->
            <div class="flex items-center gap-2 pt-1 max-w-sm">
              <div class="h-[2px] w-full bg-[#005506]"></div>
              <span class="text-[#005506] text-xs">★</span>
            </div>
          </div>

          <!-- Quick Action -->
          <Link 
            :href="route('teacher.classes.index')"
            class="inline-flex items-center gap-2 self-start md:self-center bg-[#005506] hover:bg-[#003d04] text-white px-5 py-2.5 rounded-xl text-xs font-bold shadow-sm hover:shadow transition-all duration-200 active:scale-95 shrink-0"
          >
            Manage Classes →
          </Link>
        </div>

        <!-- MAIN SECTION CANVAS -->
        <div class="relative rounded-3xl p-6 sm:p-8 overflow-hidden space-y-8">
          <div 
            class="absolute inset-0 bg-cover bg-center bg-no-repeat pointer-events-none z-0 opacity-15 rounded-3xl"
            :style="{ backgroundImage: `url(${dashboardBg})` }"
          ></div>

          <!-- STATS GRID -->
          <div class="relative z-10 grid grid-cols-1 sm:grid-cols-3 gap-5">
            <!-- Active Classes Metric -->
            <Link 
              :href="route('teacher.classes.index')"
              class="bg-white/90 backdrop-blur-sm rounded-2xl p-6 shadow-sm border border-slate-200/60 hover:border-[#005506]/40 transition-all duration-200 group block"
            >
              <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Active Classes</span>
                <span class="text-xs text-[#005506] group-hover:translate-x-1 transition-transform">→</span>
              </div>
              <p class="text-3xl sm:text-4xl font-black text-[#005506] mt-3">
                {{ stats?.classes ?? 0 }}
              </p>
            </Link>

            <!-- Total Students Metric -->
            <div class="bg-white/90 backdrop-blur-sm rounded-2xl p-6 shadow-sm border border-slate-200/60">
              <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Total Students</span>
              <p class="text-3xl sm:text-4xl font-black text-slate-800 mt-3">
                {{ stats?.students ?? 0 }}
              </p>
            </div>

            <!-- Pending Grading Metric -->
            <div class="bg-white/90 backdrop-blur-sm rounded-2xl p-6 shadow-sm border border-slate-200/60">
              <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Needs Grading</span>
              <p 
                class="text-3xl sm:text-4xl font-black mt-3"
                :class="(stats?.pending_grading ?? 0) > 0 ? 'text-amber-600' : 'text-slate-800'"
              >
                {{ stats?.pending_grading ?? 0 }}
              </p>
            </div>
          </div>

          <!-- TWO-COLUMN WORKSPACE: NEEDS GRADING & TODAY'S SCHEDULE -->
          <div class="relative z-10 grid grid-cols-1 lg:grid-cols-2 gap-8">
            
            <!-- SUBMISSIONS TO GRADE CARD -->
            <div class="bg-white/90 backdrop-blur-sm rounded-2xl p-6 sm:p-7 shadow-sm border border-slate-200/60 space-y-4">
              <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <h3 class="font-extrabold text-slate-800 text-base sm:text-lg flex items-center gap-2">
                  <span>📝</span> Submissions Needing Grading
                </h3>
                <span v-if="needs_grading?.length" class="text-xs font-bold bg-amber-100 text-amber-800 px-2.5 py-1 rounded-full">
                  {{ needs_grading.length }} Pending
                </span>
              </div>

              <!-- Empty State -->
              <div v-if="!needs_grading || !needs_grading.length" class="py-8 text-center text-xs sm:text-sm text-slate-500 space-y-1">
                <p class="font-semibold text-slate-600">All caught up!</p>
                <p>No pending assignment submissions to review right now.</p>
              </div>

              <!-- Submission List -->
              <div v-else class="space-y-3">
                <div 
                  v-for="item in needs_grading" 
                  :key="item.id"
                  class="p-4 rounded-xl border border-slate-100 bg-slate-50/50 hover:bg-emerald-50/50 hover:border-emerald-200/60 transition-all duration-150 flex items-center justify-between gap-3"
                >
                  <div class="space-y-1 min-w-0">
                    <p class="font-bold text-slate-800 text-xs sm:text-sm truncate">
                      {{ item.assignment }}
                    </p>
                    <p class="text-xs text-slate-500">
                      Student: <span class="font-semibold text-slate-700">{{ item.student_name }}</span> · 
                      <span class="text-slate-600">{{ item.subject }}</span> ({{ item.section }})
                    </p>
                  </div>

                  <Link 
                    :href="route('teacher.assignments.show', item.assignment_id)"
                    class="bg-[#005506] hover:bg-[#003d04] text-white text-xs font-bold px-3 py-1.5 rounded-lg shadow-sm shrink-0 transition-transform active:scale-95"
                  >
                    Grade
                  </Link>
                </div>
              </div>
            </div>

            <!-- TODAY'S SCHEDULE CARD -->
            <div class="bg-white/90 backdrop-blur-sm rounded-2xl p-6 sm:p-7 shadow-sm border border-slate-200/60 space-y-4">
              <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <h3 class="font-extrabold text-slate-800 text-base sm:text-lg flex items-center gap-2">
                  <span>📅</span> Today's Class Schedule
                </h3>
              </div>

              <!-- Empty State -->
              <div v-if="!today_schedule || !today_schedule.length" class="py-8 text-center text-xs sm:text-sm text-slate-500 space-y-1">
                <p class="font-semibold text-slate-600">No classes scheduled today.</p>
                <p>Enjoy your teaching break or prepare lesson plans!</p>
              </div>

              <!-- Schedule List -->
              <div v-else class="space-y-3">
                <div 
                  v-for="classItem in today_schedule" 
                  :key="classItem.id"
                  class="p-4 rounded-xl border border-slate-100 bg-slate-50/50 flex items-center justify-between gap-3"
                >
                  <div class="space-y-1">
                    <p class="font-bold text-slate-800 text-xs sm:text-sm">
                      {{ classItem.subject }}
                    </p>
                    <p class="text-xs text-slate-500">
                      {{ classItem.section }} · <span class="font-medium text-slate-700">{{ classItem.room }}</span>
                    </p>
                  </div>

                  <div class="text-right shrink-0">
                    <span class="text-xs font-bold bg-emerald-50 text-[#005506] border border-emerald-200 px-2.5 py-1 rounded-lg">
                      {{ classItem.time_start }} - {{ classItem.time_end }}
                    </span>
                  </div>
                </div>
              </div>
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
import dashboardBg from '@/../assets/img/dashboardbackground.png'

defineProps({
  stats: Object,
  activeTerm: String,
  today: String,
  needs_grading: Array,
  today_schedule: Array,
})
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Anton&family=Inter:wght@400;500;600;700;800&display=swap');
</style>