<template>
  <Head :title="`${classroom.subject} - Salawag LMS`" />

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
              <span class="text-[#005506]">{{ classroom.subject }}</span>
            </div>
            
            <p class="text-xs sm:text-sm font-semibold text-slate-600">
              Section: <span class="text-slate-800 font-bold">{{ classroom.section }}</span>
              <span class="text-slate-300 mx-2">|</span> 
              Term: <span class="text-slate-800 font-bold">{{ classroom.term }}</span>
            </p>

            <!-- Accent Star Line -->
            <div class="flex items-center gap-2 pt-1 max-w-sm">
              <div class="h-[2px] w-full bg-[#005506]"></div>
              <span class="text-[#005506] text-xs">★</span>
            </div>
          </div>

          <!-- Back Button -->
          <Link 
            :href="route('teacher.classes.index')"
            class="inline-flex items-center gap-1.5 self-start md:self-center bg-white hover:bg-slate-50 text-slate-700 border border-slate-200/80 px-4 py-2 rounded-xl text-xs font-bold shadow-sm transition-all duration-200 shrink-0"
          >
            ← Back to Classes
          </Link>
        </div>

        <!-- SUB-NAVIGATION TABS -->
          <div class="relative z-10 flex border-b border-slate-200/80 gap-2 sm:gap-6">
            <button class="pb-3 text-xs sm:text-sm font-bold border-b-2 border-[#005506] text-[#005506] transition-colors">
              Students ({{ students.length }})
            </button>

            <Link
              :href="route('teacher.classes.assignments.index', classroom.id)"
              class="pb-3 text-xs sm:text-sm font-semibold text-slate-500 hover:text-[#005506] border-b-2 border-transparent transition-colors"
            >
              Assignments
            </Link>

            <button disabled class="pb-3 text-xs sm:text-sm font-semibold text-slate-300 cursor-not-allowed">
              Lessons (soon)
            </button>

            <button disabled class="pb-3 text-xs sm:text-sm font-semibold text-slate-300 cursor-not-allowed">
              Grades (soon)
            </button>
          </div>

        <!-- MAIN SECTION CANVAS -->
        <div class="relative rounded-3xl p-6 sm:p-8 overflow-hidden space-y-6">
          <div 
            class="absolute inset-0 bg-cover bg-center bg-no-repeat pointer-events-none z-0 opacity-15 rounded-3xl"
            :style="{ backgroundImage: `url(${dashboardBg})` }"
          ></div>

          <!-- STUDENTS DATA TABLE -->
          <div class="relative z-10 bg-white/90 backdrop-blur-sm rounded-2xl shadow-sm border border-slate-200/60 overflow-hidden">
            <div class="overflow-x-auto">
              <table class="w-full text-left border-collapse">
                <thead>
                  <tr class="bg-emerald-900/5 text-slate-600 uppercase text-[11px] font-extrabold tracking-wider border-b border-slate-200/60">
                    <th class="px-6 py-3.5">Student Name</th>
                    <th class="px-6 py-3.5">LRN</th>
                    <th class="px-6 py-3.5">Email Address</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs sm:text-sm">
                  <tr 
                    v-for="student in students" 
                    :key="student.id" 
                    class="hover:bg-emerald-50/40 transition-colors"
                  >
                    <td class="px-6 py-4 font-bold text-slate-800">
                      {{ student.name }}
                    </td>
                    <td class="px-6 py-4 font-mono font-medium text-slate-600">
                      {{ student.lrn }}
                    </td>
                    <td class="px-6 py-4 text-slate-600">
                      {{ student.email }}
                    </td>
                  </tr>

                  <tr v-if="!students.length">
                    <td colspan="3" class="px-6 py-10 text-center text-xs sm:text-sm text-slate-500">
                      No students currently enrolled in this classroom.
                    </td>
                  </tr>
                </tbody>
              </table>
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
  classroom: Object,
  students: Array,
})
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Anton&family=Inter:wght@400;500;600;700;800&display=swap');
</style>