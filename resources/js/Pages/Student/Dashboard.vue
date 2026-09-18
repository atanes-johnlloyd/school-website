<template>
  <Head title="Student Dashboard - Salawag LMS" />

  <div class="min-h-screen flex bg-[#e8f5e9] font-['Inter'] relative">
    
    <!-- Sticky Desktop Sidebar Navigation -->
    <div class="sticky top-0 h-screen z-30 shrink-0">
      <Sidebar />
    </div>

    <!-- Main Workspace Canvas -->
    <main class="flex-1 relative overflow-y-auto min-h-screen flex flex-col justify-between">
      
      <!-- Main Dashboard Content Container -->
      <div class="relative z-10 p-6 md:p-10 space-y-16 flex-1 pb-32 lg:pb-48">
        
        <!-- HEADER ROW WITH CLIP TO PREVENT OVERFLOW SCROLLBARS -->
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6 overflow-x-clip py-2 px-1">
          
          <!-- Left Header Title & Subtext -->
          <div class="space-y-1">
            <div class="flex items-center gap-2 font-['Anton'] text-3xl sm:text-4xl md:text-5xl tracking-wide uppercase">
              <span class="text-[#005506]">STUDENT</span>
              <span class="text-transparent" style="-webkit-text-stroke: 1.5px #005506;">DASHBOARD</span>
            </div>
            
            <p class="text-xs sm:text-sm italic font-medium text-slate-600">
              "Your journey to knowledge starts with one click."
            </p>

            <!-- Decorative Star Divider Line -->
            <div class="flex items-center gap-2 pt-1 max-w-md">
              <div class="h-[2px] w-full bg-[#005506]"></div>
              <span class="text-[#005506] text-xs">★</span>
            </div>
          </div>

          <!-- Right Student Profile Pill Banner -->
          <div class="flex items-center self-end lg:self-auto overflow-hidden py-2 pl-4 animate-banner-auto-hide">
            
            <!-- Sliding Yellow Banner Pill -->
            <div class="bg-[#eab308] text-slate-900 px-8 sm:px-10 py-3.5 rounded-l-full shadow-lg flex flex-col justify-center text-right pr-14 -mr-10 animate-pill-slide-left">
              <h2 class="font-extrabold text-base sm:text-lg tracking-tight leading-tight whitespace-nowrap">
                Good day, {{ auth?.user?.name || 'Student' }}!
              </h2>
              <p class="text-xs font-bold text-slate-800 tracking-wide italic mt-0.5">
                STEM 11-2
              </p>
            </div>

            <!-- Pop-in Profile Avatar Ring -->
            <div class="relative z-10 w-20 h-20 sm:w-24 sm:h-24 rounded-full border-4 border-[#eab308] bg-slate-200 overflow-hidden shadow-xl shrink-0 animate-avatar-pop">
              <img 
                v-if="auth?.user?.avatar" 
                :src="auth.user.avatar" 
                alt="Student Avatar" 
                class="w-full h-full object-cover" 
              />
              <div v-else class="w-full h-full bg-[#005506] text-white font-bold flex items-center justify-center text-2xl uppercase">
                {{ auth?.user?.name ? auth.user.name.charAt(0) : 'S' }}
              </div>
            </div>

          </div>

        </div>

        <!-- SECTION 1: SUBJECTS (With dashboardbackground.png at 15% opacity) -->
        <div class="relative rounded-3xl p-6 sm:p-8 overflow-hidden space-y-6">
          
          <!-- Background Pattern Image ONLY behind the Subject Section -->
          <div 
            class="absolute inset-0 bg-cover bg-center bg-no-repeat pointer-events-none z-0 opacity-15 rounded-3xl"
            :style="{ backgroundImage: `url(${dashboardBg1})` }"
          ></div>

          <!-- Section Title -->
          <h3 class="relative z-10 text-xl sm:text-2xl font-bold text-[#005506] tracking-tight">
            Subjects
          </h3>

          <!-- Subject Cards Grid Container -->
          <div class="relative z-10 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-5">
            
            <div class="bg-white/90 backdrop-blur-sm rounded-2xl p-5 shadow-sm hover:shadow-md border border-slate-200/60 h-64 flex flex-col justify-between transition-all duration-200 hover:-translate-y-1 cursor-pointer">
              <div class="w-10 h-10 rounded-xl bg-emerald-50 text-[#005506] flex items-center justify-center font-bold text-sm">
                01
              </div>
              <div>
                <h4 class="font-bold text-slate-800 text-sm">General Mathematics</h4>
                <p class="text-xs text-slate-400 font-medium mt-1">STEM Core</p>
              </div>
            </div>

            <div class="bg-white/90 backdrop-blur-sm rounded-2xl p-5 shadow-sm hover:shadow-md border border-slate-200/60 h-64 flex flex-col justify-between transition-all duration-200 hover:-translate-y-1 cursor-pointer">
              <div class="w-10 h-10 rounded-xl bg-emerald-50 text-[#005506] flex items-center justify-center font-bold text-sm">
                02
              </div>
              <div>
                <h4 class="font-bold text-slate-800 text-sm">General Physics 1</h4>
                <p class="text-xs text-slate-400 font-medium mt-1">Specialization</p>
              </div>
            </div>

            <div class="bg-white/90 backdrop-blur-sm rounded-2xl p-5 shadow-sm hover:shadow-md border border-slate-200/60 h-64 flex flex-col justify-between transition-all duration-200 hover:-translate-y-1 cursor-pointer">
              <div class="w-10 h-10 rounded-xl bg-emerald-50 text-[#005506] flex items-center justify-center font-bold text-sm">
                03
              </div>
              <div>
                <h4 class="font-bold text-slate-800 text-sm">Earth Science</h4>
                <p class="text-xs text-slate-400 font-medium mt-1">STEM Core</p>
              </div>
            </div>

            <div class="bg-white/90 backdrop-blur-sm rounded-2xl p-5 shadow-sm hover:shadow-md border border-slate-200/60 h-64 flex flex-col justify-between transition-all duration-200 hover:-translate-y-1 cursor-pointer">
              <div class="w-10 h-10 rounded-xl bg-emerald-50 text-[#005506] flex items-center justify-center font-bold text-sm">
                04
              </div>
              <div>
                <h4 class="font-bold text-slate-800 text-sm">Empowerment Tech</h4>
                <p class="text-xs text-slate-400 font-medium mt-1">Applied Subject</p>
              </div>
            </div>

            <div class="bg-white/90 backdrop-blur-sm rounded-2xl p-5 shadow-sm hover:shadow-md border border-slate-200/60 h-64 flex flex-col justify-between transition-all duration-200 hover:-translate-y-1 cursor-pointer">
              <div class="w-10 h-10 rounded-xl bg-emerald-50 text-[#005506] flex items-center justify-center font-bold text-sm">
                05
              </div>
              <div>
                <h4 class="font-bold text-slate-800 text-sm">Oral Communication</h4>
                <p class="text-xs text-slate-400 font-medium mt-1">Core Subject</p>
              </div>
            </div>

            <div class="bg-white/90 backdrop-blur-sm rounded-2xl p-5 shadow-sm hover:shadow-md border border-slate-200/60 h-64 flex flex-col justify-between transition-all duration-200 hover:-translate-y-1 cursor-pointer">
              <div class="w-10 h-10 rounded-xl bg-emerald-50 text-[#005506] flex items-center justify-center font-bold text-sm">
                06
              </div>
              <div>
                <h4 class="font-bold text-slate-800 text-sm">Physical Education 1</h4>
                <p class="text-xs text-slate-400 font-medium mt-1">Core Subject</p>
              </div>
            </div>

          </div>
        </div>

        <!-- SECTION 2: ANNOUNCEMENTS (Matching Seamless Background Style) -->
        <div class="relative rounded-3xl p-6 sm:p-8 overflow-hidden space-y-6">
          
          <!-- Isolated Background Pattern 2 (15% Opacity behind cards only) -->
          <div 
            class="absolute inset-0 bg-cover bg-center bg-no-repeat pointer-events-none z-0 opacity-15 rounded-3xl"
            :style="{ backgroundImage: `url(${dashboardBg2})` }"
          ></div>

          <!-- Section Title -->
          <h3 class="relative z-10 text-xl sm:text-2xl font-bold text-[#005506] tracking-tight">
            Announcements
          </h3>

          <!-- Announcement Items List -->
          <div class="relative z-10 space-y-4">
            
            <!-- Announcement Item 1 -->
            <div class="relative bg-white/90 backdrop-blur-sm rounded-2xl shadow-sm border border-slate-200/60 p-5 pl-7 overflow-hidden transition-all duration-200 hover:shadow-md">
              <div class="absolute left-0 top-0 bottom-0 w-2.5 bg-[#005506] rounded-l-2xl"></div>
              <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                <h4 class="font-bold text-slate-800 text-base">
                  Midterm Examination Schedule Released
                </h4>
                <span class="text-xs font-semibold text-slate-400">Oct 24, 2026</span>
              </div>
              <p class="text-xs sm:text-sm text-slate-600 font-medium mt-1">
                Please review your subject schedules in the calendar tab to prepare for upcoming exam dates.
              </p>
            </div>

            <!-- Announcement Item 2 -->
            <div class="relative bg-white/90 backdrop-blur-sm rounded-2xl shadow-sm border border-slate-200/60 p-5 pl-7 overflow-hidden transition-all duration-200 hover:shadow-md">
              <div class="absolute left-0 top-0 bottom-0 w-2.5 bg-[#005506] rounded-l-2xl"></div>
              <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                <h4 class="font-bold text-slate-800 text-base">
                  Campus Science Fair Registration Open
                </h4>
                <span class="text-xs font-semibold text-slate-400">Oct 18, 2026</span>
              </div>
              <p class="text-xs sm:text-sm text-slate-600 font-medium mt-1">
                STEM students are encouraged to submit project proposals to their respective physics advisors.
              </p>
            </div>

            <!-- Announcement Item 3 -->
            <div class="relative bg-white/90 backdrop-blur-sm rounded-2xl shadow-sm border border-slate-200/60 p-5 pl-7 overflow-hidden transition-all duration-200 hover:shadow-md">
              <div class="absolute left-0 top-0 bottom-0 w-2.5 bg-[#005506] rounded-l-2xl"></div>
              <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                <h4 class="font-bold text-slate-800 text-base">
                  System Maintenance Notice
                </h4>
                <span class="text-xs font-semibold text-slate-400">Oct 12, 2026</span>
              </div>
              <p class="text-xs sm:text-sm text-slate-600 font-medium mt-1">
                The eLearn portal will undergo routine maintenance this Saturday from 11:00 PM to 2:00 AM.
              </p>
            </div>

          </div>

        </div>

      </div>

      <!-- Shared Footer Component with Full Bleed -->
      <Footer class="relative z-10 mt-auto w-full" />

    </main>

  </div>
</template>

<script setup>
import { Head } from '@inertiajs/vue3'
import Sidebar from '@/Components/Sidebar.vue'
import Footer from '@/Components/Footer.vue'

import dashboardBg1 from '@/../assets/img/dashboardbackground.png'
import dashboardBg2 from '@/../assets/img/dashboardbackground2.png'

defineProps({
  auth: {
    type: Object,
  },
})
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Anton&family=Inter:wght@400;500;600;700;800&display=swap');

@keyframes avatarPop {
  0% {
    opacity: 0;
    transform: scale(0.2);
  }
  70% {
    transform: scale(1.1);
  }
  100% {
    opacity: 1;
    transform: scale(1);
  }
}

@keyframes pillSlideLeft {
  0% {
    opacity: 0;
    transform: translateX(120%);
  }
  100% {
    opacity: 1;
    transform: translateX(0);
  }
}

@keyframes bannerAutoHide {
  0%, 80% {
    opacity: 1;
    transform: scale(1);
  }
  100% {
    opacity: 0;
    transform: scale(0.95);
    visibility: hidden;
    pointer-events: none;
  }
}

.animate-avatar-pop {
  animation: avatarPop 0.7s cubic-bezier(0.34, 1.56, 0.64, 1) both;
}

.animate-pill-slide-left {
  animation: pillSlideLeft 0.85s cubic-bezier(0.22, 1, 0.36, 1) 0.3s both;
}

.animate-banner-auto-hide {
  animation: bannerAutoHide 0.8s cubic-bezier(0.4, 0, 0.2, 1) 5.8s forwards;
}
</style>