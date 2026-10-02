<template>
  <Head title="Academics - Salawag LMS" />

  <!-- Main Background Canvas -->
  <div class="min-h-screen bg-[#E8FFE8] font-['Inter'] text-slate-800 overflow-x-hidden">

    <!-- SINGLE OVERLAPPED HERO BANNER SECTION -->
    <section class="relative w-full min-h-[65vh] md:min-h-[72vh] bg-[#004d08] text-white overflow-hidden shadow-md flex items-center">
      
      <!-- Overlapped Transparent Navbar Component -->
      <Navbar />

      <!-- Background Image Overlay with Tint & Gradient -->
      <div class="absolute inset-0 z-0 pointer-events-none overflow-hidden">
        <img 
          src="https://images.unsplash.com/photo-1562774053-701939374585?auto=format&fit=crop&w=1920&q=80" 
          alt="Salawag SHS Campus & Facilities" 
          class="w-full h-full object-cover object-center opacity-30 mix-blend-overlay scale-105 animate-hero-zoom transition-transform duration-1000" 
        />
        <div class="absolute inset-0 bg-gradient-to-r from-[#004d08]/98 via-[#004d08]/85 to-[#003805]/75"></div>
      </div>

      <!-- Animated Gradient Sheen -->
      <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/5 to-transparent animate-sheen pointer-events-none z-0"></div>

      <!-- Ambient Glow Effects -->
      <div class="absolute -top-24 -left-24 w-96 h-96 bg-emerald-400/15 rounded-full blur-3xl pointer-events-none animate-pulse-slow"></div>
      <div class="absolute -bottom-24 right-0 w-96 h-96 bg-amber-400/10 rounded-full blur-3xl pointer-events-none animate-pulse-slow delay-1000"></div>

      <!-- Hero Banner Content -->
      <div class="relative z-10 w-full max-w-[1500px] mx-auto px-6 sm:px-12 md:px-16 lg:px-24 pt-28 md:pt-36 pb-16 space-y-6">
        
        <!-- Main Headline -->
        <div class="space-y-4 max-w-4xl">
          <h1 class="font-['Anton'] text-5xl sm:text-6xl md:text-7xl lg:text-8xl tracking-tight uppercase leading-[1.02] text-white animate-fade-in-up">
            Curriculum Designed for <br />
            <span class="text-amber-400 inline-block underline decoration-amber-400 decoration-4 underline-offset-8 animate-float-soft">
              College Readiness
            </span>, Tech Mastery & Civic Impact
          </h1>
          
          <p class="text-sm sm:text-base md:text-lg text-emerald-100/90 font-normal leading-relaxed pt-2 max-w-3xl animate-fade-in-up animation-delay-200">
            Salawag SHS implements DepEd's modernized 2-track framework alongside legacy tracks for continuing cohorts, offering cutting-edge laboratories, TESDA-certified workshops, and verified industry immersions.
          </p>
        </div>

        <!-- Action Buttons -->
        <div class="pt-3 flex flex-wrap items-center gap-4 animate-fade-in-up animation-delay-400">
          <a 
            href="/site/admission" 
            class="group relative inline-flex items-center gap-2 bg-amber-400 hover:bg-amber-300 text-slate-950 font-bold text-xs sm:text-sm px-7 py-3.5 rounded-full shadow-lg hover:shadow-amber-400/20 transition-all duration-300 hover:-translate-y-0.5 active:scale-95 cursor-pointer overflow-hidden"
          >
            <span>Enroll for S.Y. 2026–2027</span>
            <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
              <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
            </svg>
          </a>

          <a 
            href="#pathways" 
            class="inline-flex items-center gap-2 bg-white/10 hover:bg-white/20 border border-white/30 text-white font-medium text-xs sm:text-sm px-6 py-3.5 rounded-full transition-all duration-300 hover:-translate-y-0.5 active:scale-95 cursor-pointer backdrop-blur-sm"
          >
            <svg class="w-4 h-4 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18c-2.305 0-4.408.867-6 2.292m0-14.25v14.25" />
            </svg>
            <span>Explore SSHS Program Pathways</span>
          </a>
        </div>

      </div>

    </section>

    <!-- EXPLORE THE SHS PROGRAM PATHWAYS SECTION (ANIMATED ON SCROLL) -->
    <section ref="pathwaysRef" id="pathways" class="w-full text-slate-800 py-16 sm:py-20 px-6 sm:px-12 md:px-16 lg:px-24">
      <div 
        class="max-w-[1500px] mx-auto space-y-10 transition-all duration-1000"
        :class="isPathwaysVisible ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-12'"
      >
        
        <!-- HEADER & TRACK TOGGLE SWITCH -->
        <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-6">
          <div class="space-y-2 max-w-2xl">
            <span class="inline-block bg-[#eaf5ed] text-[#005506] text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider">
              PATHWAY CATALOG
            </span>
            <h2 class="font-['Anton'] text-4xl sm:text-5xl lg:text-6xl text-[#005506] uppercase tracking-wide leading-none">
              Explore the SHS Program Pathways
            </h2>
            <p class="text-sm sm:text-base text-slate-600 font-normal leading-relaxed">
              Toggle through modernized and legacy programs to review specialized course concentrations, state-of-the-art facilities, and future career horizons.
            </p>
          </div>

          <!-- TAB TOGGLES -->
          <div class="bg-[#e9e6d7]/80 p-1.5 rounded-full inline-flex items-center gap-1 shrink-0 self-start lg:self-auto border border-slate-300/60 shadow-inner">
            <button 
              @click="activeTrack = 'academic'"
              :class="[
                activeTrack === 'academic' 
                  ? 'bg-[#004d08] text-white shadow-md font-bold' 
                  : 'text-slate-700 hover:text-slate-900 font-medium'
              ]"
              class="px-5 py-2 rounded-full text-xs sm:text-sm transition-all duration-300 cursor-pointer"
            >
              Academic Track
            </button>
            <button 
              @click="activeTrack = 'techpro'"
              :class="[
                activeTrack === 'techpro' 
                  ? 'bg-[#004d08] text-white shadow-md font-bold' 
                  : 'text-slate-700 hover:text-slate-900 font-medium'
              ]"
              class="px-5 py-2 rounded-full text-xs sm:text-sm transition-all duration-300 cursor-pointer"
            >
              TechPro Track
            </button>
            <button 
              @click="activeTrack = 'legacy'"
              :class="[
                activeTrack === 'legacy' 
                  ? 'bg-[#004d08] text-white shadow-md font-bold' 
                  : 'text-slate-700 hover:text-slate-900 font-medium'
              ]"
              class="px-5 py-2 rounded-full text-xs sm:text-sm transition-all duration-300 cursor-pointer"
            >
              Legacy Strands (Gr. 12)
            </button>
          </div>
        </div>

        <!-- PROGRAM PATHWAYS GRID -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-stretch">
          
          <div 
            v-for="program in filteredPrograms" 
            :key="program.id"
            class="bg-white rounded-3xl p-6 sm:p-8 md:p-10 shadow-sm border border-slate-200/80 flex flex-col justify-between space-y-6 transition-all duration-300 hover:shadow-xl hover:-translate-y-1"
          >
            <div class="space-y-5">
              <!-- TOP BADGES -->
              <div class="flex items-center justify-between gap-4">
                <span class="inline-block bg-[#eaf5ed] text-[#005506] text-xs font-bold px-3 py-1 rounded-full">
                  {{ program.alphaBadge }}
                </span>
                <span class="inline-flex items-center gap-1.5 bg-amber-100/80 text-amber-900 text-xs font-bold px-3 py-1 rounded-full">
                  <svg class="w-3.5 h-3.5 text-amber-600" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                  </svg>
                  <span>{{ program.alignedTag }}</span>
                </span>
              </div>

              <!-- PROGRAM TITLE & DESC -->
              <div class="space-y-3">
                <h3 class="text-2xl sm:text-3xl font-bold text-[#005506] leading-snug">
                  {{ program.title }}
                </h3>
                <p class="text-xs sm:text-sm text-slate-600 leading-relaxed font-normal">
                  {{ program.description }}
                </p>
              </div>

              <!-- FLAGSHIP SUBJECTS -->
              <div class="space-y-2 pt-1">
                <h4 class="text-xs font-bold uppercase tracking-wider text-[#005506]">
                  Flagship Core & Specialized Subjects:
                </h4>
                <div class="flex flex-wrap gap-2">
                  <span 
                    v-for="(sub, i) in program.subjects" 
                    :key="i"
                    class="bg-[#f5f4ed] text-slate-700 text-xs font-semibold px-3 py-1.5 rounded-lg border border-slate-200/60"
                  >
                    {{ sub }}
                  </span>
                </div>
              </div>

              <!-- DEDICATED FACILITY BOX -->
              <div class="bg-[#f5f4ed] rounded-2xl p-4 flex items-start gap-3 border border-slate-200/60">
                <div class="w-8 h-8 rounded-lg bg-emerald-100 text-[#005506] flex items-center justify-center shrink-0 mt-0.5">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                  </svg>
                </div>
                <div>
                  <h5 class="text-xs sm:text-sm font-bold text-[#005506]">
                    Dedicated Facility: {{ program.facilityTitle }}
                  </h5>
                  <p class="text-xs text-slate-500 font-normal leading-relaxed">
                    {{ program.facilityDesc }}
                  </p>
                </div>
              </div>
            </div>

            <!-- FOOTER ACTION & TARGET DEGREES -->
            <div class="pt-4 border-t border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs sm:text-sm">
              <p class="text-slate-600 font-medium">
                <span class="font-bold text-[#005506]">Target Degrees:</span> {{ program.targetDegrees }}
              </p>
              <a href="#syllabus-finder" class="font-bold text-[#005506] hover:underline flex items-center gap-1 shrink-0 self-start sm:self-auto">
                <span>View Syllabus</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                </svg>
              </a>
            </div>
          </div>

        </div>

      </div>
    </section>

    <!-- INTERACTIVE SUBJECT FINDER & SYLLABUS TABULATOR SECTION (ANIMATED ON SCROLL) -->
    <section ref="syllabusRef" id="syllabus-finder" class="w-full text-slate-800 py-16 px-6 sm:px-12 md:px-16 lg:px-24">
      <div 
        class="max-w-[1500px] mx-auto space-y-8 transition-all duration-1000"
        :class="isSyllabusVisible ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-12'"
      >

        <!-- HEADER BLOCK -->
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
          <div class="space-y-2 max-w-3xl">
            <span class="inline-block bg-[#f5ebd7] text-[#916b1f] text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider">
              COURSE CATALOG DATABASE
            </span>
            <h2 class="font-['Anton'] text-4xl sm:text-5xl lg:text-6xl text-[#005506] uppercase tracking-wide leading-none">
              Interactive Subject Finder & Syllabus
            </h2>
            <p class="text-sm sm:text-base text-slate-600 font-normal leading-relaxed">
              Search, filter, and review prerequisites, weekly hours, and syllabus downloads for all approved course offerings.
            </p>
          </div>

          <!-- MATCHING COUNT BADGE -->
          <div class="shrink-0">
            <span class="bg-white border border-slate-200 text-slate-700 text-xs sm:text-sm font-bold px-4 py-2 rounded-full shadow-2xs">
              Showing <span class="text-[#005506] font-extrabold">{{ filteredSubjects.length }}</span> subjects matching criteria
            </span>
          </div>
        </div>

        <!-- SEARCH & FILTER TOOLBAR CONTAINER -->
        <div class="bg-[#f6f3eb] p-4 sm:p-5 rounded-3xl border border-slate-200/80 shadow-xs flex flex-col lg:flex-row items-center gap-4">
          
          <!-- SEARCH INPUT -->
          <div class="relative w-full lg:flex-1">
            <svg class="w-4 h-4 absolute left-4 top-1/2 -translate-y-1/2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
            </svg>
            <input 
              v-model="searchQuery" 
              type="text" 
              placeholder="Search subject code, title, or topic (e.g., Biology, Pre-Calculus, CSS, Ethics)..."
              class="w-full bg-white text-slate-800 text-xs sm:text-sm pl-11 pr-4 py-3 rounded-2xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-[#005506] transition-all"
            />
          </div>

          <!-- FILTER DROPDOWNS -->
          <div class="flex flex-wrap items-center gap-3 w-full lg:w-auto shrink-0">
            <!-- TRACK FILTER -->
            <select 
              v-model="selectedTrack" 
              class="bg-[#ebd9bd]/50 hover:bg-[#ebd9bd] text-slate-800 text-xs sm:text-sm font-bold px-4 py-3 rounded-2xl border-0 cursor-pointer focus:outline-none transition-colors"
            >
              <option value="All">All Tracks & Strands</option>
              <option value="Academic">Academic Track</option>
              <option value="TechPro">TechPro Track</option>
              <option value="Common Core">Common Core</option>
            </select>

            <!-- GRADE FILTER -->
            <select 
              v-model="selectedGrade" 
              class="bg-[#ebd9bd]/50 hover:bg-[#ebd9bd] text-slate-800 text-xs sm:text-sm font-bold px-4 py-3 rounded-2xl border-0 cursor-pointer focus:outline-none transition-colors"
            >
              <option value="All">All Grades</option>
              <option value="Grade 11">Grade 11</option>
              <option value="Grade 12">Grade 12</option>
            </select>

            <!-- SEMESTER FILTER -->
            <select 
              v-model="selectedSem" 
              class="bg-[#ebd9bd]/50 hover:bg-[#ebd9bd] text-slate-800 text-xs sm:text-sm font-bold px-4 py-3 rounded-2xl border-0 cursor-pointer focus:outline-none transition-colors"
            >
              <option value="All">All Semesters</option>
              <option value="Sem 1">Semester 1</option>
              <option value="Sem 2">Semester 2</option>
            </select>

            <!-- RESET BUTTON -->
            <button 
              @click="resetFilters" 
              title="Reset Filters"
              class="w-11 h-11 bg-[#ebd9bd]/50 hover:bg-[#ebd9bd] text-slate-700 rounded-2xl flex items-center justify-center transition-colors cursor-pointer"
            >
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
              </svg>
            </button>
          </div>

        </div>

        <!-- TABULATOR / DATA TABLE -->
        <div class="w-full bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
          <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-[900px]">
              
              <!-- TABLE HEADER -->
              <thead>
                <tr class="bg-[#f5ebd7]/60 text-slate-700 uppercase text-[11px] font-extrabold tracking-wider border-b border-slate-200/80">
                  <th class="py-4 px-6 w-[28%]">CODE & TITLE</th>
                  <th class="py-4 px-4 w-[16%]">TRACK / STRAND</th>
                  <th class="py-4 px-4 w-[13%]">LEVEL & SEM</th>
                  <th class="py-4 px-4 w-[13%]">CATEGORY</th>
                  <th class="py-4 px-4 w-[11%]">WEEKLY HOURS</th>
                  <th class="py-4 px-4 w-[19%]">PREREQUISITES</th>
                  <th class="py-4 px-6 text-right w-[10%]">SYLLABUS</th>
                </tr>
              </thead>

              <!-- TABLE BODY -->
              <tbody class="divide-y divide-slate-100 text-xs sm:text-sm">
                <tr 
                  v-for="item in filteredSubjects" 
                  :key="item.code" 
                  class="hover:bg-slate-50/80 transition-colors"
                >
                  <!-- CODE & TITLE -->
                  <td class="py-4 px-6">
                    <div class="font-extrabold text-[#005506] text-xs sm:text-sm">
                      {{ item.code }}
                    </div>
                    <div class="font-bold text-[#005506] text-xs sm:text-sm leading-snug">
                      {{ item.title }}
                    </div>
                  </td>

                  <!-- TRACK / STRAND BADGE -->
                  <td class="py-4 px-4">
                    <span 
                      class="inline-block px-3 py-1 rounded-full text-[11px] font-bold"
                      :class="item.strandBadgeStyle"
                    >
                      {{ item.strand }}
                    </span>
                  </td>

                  <!-- LEVEL & SEM -->
                  <td class="py-4 px-4 text-slate-700 font-semibold">
                    {{ item.levelSem }}
                  </td>

                  <!-- CATEGORY BADGE -->
                  <td class="py-4 px-4">
                    <span class="inline-block bg-[#f4ebd0] text-slate-800 text-[11px] font-bold px-2.5 py-1 rounded-md">
                      {{ item.category }}
                    </span>
                  </td>

                  <!-- WEEKLY HOURS -->
                  <td class="py-4 px-4 font-bold text-slate-800">
                    {{ item.weeklyHours }}
                  </td>

                  <!-- PREREQUISITES -->
                  <td class="py-4 px-4 text-slate-500 font-medium text-xs">
                    {{ item.prerequisites }}
                  </td>

                  <!-- SYLLABUS DOWNLOAD BUTTON -->
                  <td class="py-4 px-6 text-right">
                    <a 
                      :href="item.downloadLink" 
                      download 
                      class="inline-flex items-center gap-1.5 bg-[#fbf2cf] hover:bg-[#f7e7a8] text-[#7c5b12] text-xs font-bold px-3.5 py-2 rounded-full transition-colors cursor-pointer whitespace-nowrap"
                    >
                      <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                      </svg>
                      <span>PDF ({{ item.fileSize }})</span>
                    </a>
                  </td>
                </tr>

                <!-- EMPTY STATE -->
                <tr v-if="filteredSubjects.length === 0">
                  <td colspan="7" class="py-12 text-center text-slate-500 font-medium">
                    No subjects found matching your criteria. Try adjusting the search filters.
                  </td>
                </tr>
              </tbody>

            </table>
          </div>
        </div>

      </div>
    </section>

    <!-- FOOTER ATTACHED DIRECTLY -->
    <Footer class="m-0 p-0 block" />

  </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue'
import Navbar from '@/Components/Navbar.vue'
import Footer from '@/Components/Footer.vue'
import { Head } from '@inertiajs/vue3'

const activeTrack = ref('academic')
const searchQuery = ref('')
const selectedTrack = ref('All')
const selectedGrade = ref('All')
const selectedSem = ref('All')

// Section Visibility Refs for Scroll Triggering
const pathwaysRef = ref(null)
const syllabusRef = ref(null)

const isPathwaysVisible = ref(false)
const isSyllabusVisible = ref(false)

let observer = null

onMounted(() => {
  observer = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        if (entry.target === pathwaysRef.value) {
          isPathwaysVisible.value = entry.isIntersecting
        } else if (entry.target === syllabusRef.value) {
          isSyllabusVisible.value = entry.isIntersecting
        }
      })
    },
    { threshold: 0.12 }
  )

  if (pathwaysRef.value) observer.observe(pathwaysRef.value)
  if (syllabusRef.value) observer.observe(syllabusRef.value)
})

onUnmounted(() => {
  if (observer) observer.disconnect()
})

function resetFilters() {
  searchQuery.value = ''
  selectedTrack.value = 'All'
  selectedGrade.value = 'All'
  selectedSem.value = 'All'
}

const allPrograms = [
  {
    id: 1,
    track: 'academic',
    alphaBadge: 'Concentration Alpha',
    alignedTag: 'DOST Aligned',
    title: 'Science, Technology, Engineering & Applied Math (STEM)',
    description: 'Focused on rigorous investigative sciences, algorithmic computation, and advanced pre-calculus for aspiring engineers, data scientists, and healthcare leaders.',
    subjects: ['General Biology 1 & 2', 'Differential Calculus', 'Robotics & Microcontrollers', 'Research / Capstone Project'],
    facilityTitle: 'Salawag Advanced Science & Wet Chemistry Lab',
    facilityDesc: 'Features calibrated digital balances, laminar flow hoods, and Vernier sensor interfaces.',
    targetDegrees: 'BS CS, CE, Nursing, Medicine, Architecture'
  },
  {
    id: 2,
    track: 'academic',
    alphaBadge: 'Concentration Beta',
    alignedTag: 'Civic Leadership',
    title: 'Arts, Social Sciences, Law & Governance (HUMSS)',
    description: 'Nurtures critical discourse, civic participation, community engagement, and creative writing. Prepares scholars for legal studies, education, and mass communication.',
    subjects: ['Philippine Politics & Governance', 'Creative Nonfiction', 'Community Engagement & Citizenship', 'Disciplines of Social Sciences'],
    facilityTitle: 'Salawag Moot Court & Media Studio',
    facilityDesc: 'Acoustic broadcast audio booths, model assembly rostrum, and press briefing suites.',
    targetDegrees: 'BA PolSci, Communication, Secondary Ed, LLB, Psychology'
  },
  {
    id: 3,
    track: 'academic',
    alphaBadge: 'Concentration Gamma',
    alignedTag: 'Cavite Young Biz',
    title: 'Commerce, Accountancy & Enterprise Management',
    description: 'Empowers future accountants, enterprise founders, and financial analysts with foundational fiscal management, corporate ethics, and market prototyping.',
    subjects: ['Fundamentals of ABM 1 & 2', 'Business Finance', 'Principles of Marketing', 'Enterprise Simulation Practicum'],
    facilityTitle: 'SSHS Cooperative & FinTech Incubation Lab',
    facilityDesc: 'Live micro-retail POS terminals, QuickBooks financial suites, and investor pitch arenas.',
    targetDegrees: 'BS Accountancy, Business Administration, Customs Admin'
  },
  {
    id: 4,
    track: 'academic',
    alphaBadge: 'Concentration Delta',
    alignedTag: 'Calabarzon Athletics',
    title: 'Human Movement, Sports & Community Health',
    description: 'Groundwork in exercise physiology, sports officiating, biomechanics, and first responder care for future physical therapists, sports coaches, and athletic directors.',
    subjects: ['Human Anatomy & Kinesiology', 'Sports Officiating & Safety', 'Fitness Assessment & Exercise Rx', 'Community Health Advocacy'],
    facilityTitle: 'Covered Gymnasium & Conditioning Pavilion',
    facilityDesc: 'Standard FIBA vinyl courts, plyometric jump boxes, and emergency sports triage unit.',
    targetDegrees: 'BS Physical Therapy, Sports Science, Education (PE)'
  },
  {
    id: 5,
    track: 'techpro',
    alphaBadge: 'TechPro Specialization 01',
    alignedTag: 'TESDA NC II Certified',
    title: 'Information & Communications Tech (ICT)',
    description: 'Practical training in web development, database management, network administration, and digital animation for immediate IT industry deployment.',
    subjects: ['Computer Systems Servicing NC II', 'Web Development & CSS', 'Java Programming', 'Animation & Visual Effects'],
    facilityTitle: 'SSHS High-Spec CISCO Networking & Hardware Lab',
    facilityDesc: 'Dedicated server racks, fiber splicing stations, and high-performance workstations.',
    targetDegrees: 'BS Information Technology, Computer Engineering, Software Dev'
  },
  {
    id: 6,
    track: 'techpro',
    alphaBadge: 'TechPro Specialization 02',
    alignedTag: 'TESDA NC II Certified',
    title: 'Home Economics & Hospitality Management (HE)',
    description: 'Mastery in commercial culinary arts, bread and pastry production, and hotel front office operations aligned with national hospitality standards.',
    subjects: ['Cookery NC II', 'Bread & Pastry Production NC II', 'Front Office Services', 'Food & Beverage Service'],
    facilityTitle: 'Commercial Culinary Kitchen & Mock Hotel Suite',
    facilityDesc: 'Industrial stainless steel cooktops, baking ovens, and front desk simulation counter.',
    targetDegrees: 'BS Hospitality Management, Culinary Arts, Tourism'
  }
]

const filteredPrograms = computed(() => {
  return allPrograms.filter(p => p.track === activeTrack.value || (activeTrack.value === 'legacy' && p.track === 'academic'))
})

const subjects = [
  {
    code: 'STEM-PC111',
    title: 'Pre-Calculus & Analytic Geometry',
    strand: 'Academic • STEM',
    strandBadgeStyle: 'bg-[#eaf5ed] text-[#005506]',
    levelSem: 'Grade 11 • Sem 1',
    category: 'Specialized',
    weeklyHours: '4.0 hrs / wk',
    prerequisites: 'JHS Grade 10 Math (Proficient)',
    fileSize: '1.4MB',
    downloadLink: '#'
  },
  {
    code: 'CORE-KP111',
    title: 'Komunikasyon at Pananaliksik sa Wika at Kulturang Pilipino',
    strand: 'Common Core',
    strandBadgeStyle: 'bg-slate-100 text-slate-700',
    levelSem: 'Grade 11 • Sem 1',
    category: 'Core Subject',
    weeklyHours: '4.0 hrs / wk',
    prerequisites: 'None',
    fileSize: '980KB',
    downloadLink: '#'
  },
  {
    code: 'TECH-CSS101',
    title: 'Computer Systems Servicing & Networking Principles',
    strand: 'TechPro • ICT',
    strandBadgeStyle: 'bg-[#eaf5ed] text-[#005506]',
    levelSem: 'Grade 11 • Sem 1',
    category: 'TESDA NC II',
    weeklyHours: '6.0 hrs / wk',
    prerequisites: 'None (DCP Lab Safety Pass)',
    fileSize: '2.1MB',
    downloadLink: '#'
  },
  {
    code: 'ABM-FABM1',
    title: 'Fundamentals of Accountancy, Business & Management 1',
    strand: 'Academic • ABM',
    strandBadgeStyle: 'bg-[#fef8e7] text-[#b88600]',
    levelSem: 'Grade 11 • Sem 2',
    category: 'Specialized',
    weeklyHours: '4.0 hrs / wk',
    prerequisites: 'General Mathematics',
    fileSize: '1.1MB',
    downloadLink: '#'
  },
  {
    code: 'HUMSS-PPG',
    title: 'Philippine Politics, Governance & Constitution',
    strand: 'Academic • HUMSS',
    strandBadgeStyle: 'bg-[#f5ebd7] text-[#714d11]',
    levelSem: 'Grade 11 • Sem 2',
    category: 'Specialized',
    weeklyHours: '4.0 hrs / wk',
    prerequisites: 'Understanding Culture, Society & Politics',
    fileSize: '1.8MB',
    downloadLink: '#'
  },
  {
    code: 'STEM-PHY121',
    title: 'General Physics 1 (Mechanics, Waves, Thermodynamics)',
    strand: 'Legacy • STEM',
    strandBadgeStyle: 'bg-[#eaf5ed] text-[#005506]',
    levelSem: 'Grade 12 • Sem 1',
    category: 'Specialized Lab',
    weeklyHours: '5.0 hrs / wk',
    prerequisites: 'Pre-Calculus & Basic Calculus',
    fileSize: '2.5MB',
    downloadLink: '#'
  },
  {
    code: 'TECH-CUL202',
    title: 'Commercial Cookery & Food Production NC II',
    strand: 'TechPro • Hospitality',
    strandBadgeStyle: 'bg-[#eaf5ed] text-[#005506]',
    levelSem: 'Grade 12 • Sem 2',
    category: 'TESDA NC II',
    weeklyHours: '6.0 hrs / wk',
    prerequisites: 'Food Sanitation & Kitchen Safety',
    fileSize: '1.6MB',
    downloadLink: '#'
  },
  {
    code: 'APPL-IMM200',
    title: 'Work Immersion / Culminating Enterprise Practicum',
    strand: 'Graduation Requirement',
    strandBadgeStyle: 'bg-slate-200 text-slate-800',
    levelSem: 'Grade 12 • Sem 2',
    category: 'Applied Immersion',
    weeklyHours: '80–320 hrs total',
    prerequisites: 'All Core & Track Specialized Subjects',
    fileSize: '3.2MB',
    downloadLink: '#'
  }
]

const filteredSubjects = computed(() => {
  return subjects.filter(item => {
    const matchesSearch = 
      item.code.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
      item.title.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
      item.strand.toLowerCase().includes(searchQuery.value.toLowerCase())
    
    const matchesTrack = selectedTrack.value === 'All' || item.strand.includes(selectedTrack.value)
    const matchesGrade = selectedGrade.value === 'All' || item.levelSem.includes(selectedGrade.value)
    const matchesSem = selectedSem.value === 'All' || item.levelSem.includes(selectedSem.value)

    return matchesSearch && matchesTrack && matchesGrade && matchesSem
  })
})
</script>

<style scoped>
@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

@keyframes heroZoom {
  0% { transform: scale(1); }
  50% { transform: scale(1.08); }
  100% { transform: scale(1); }
}

@keyframes floatSoft {
  0%, 100% { transform: translateY(0px); }
  50% { transform: translateY(-3px); }
}

@keyframes pulseSlow {
  0%, 100% { opacity: 0.3; }
  50% { opacity: 0.7; }
}

@keyframes sheenMove {
  0% { transform: translateX(-100%); }
  100% { transform: translateX(200%); }
}

.animate-fade-in-up {
  animation: fadeInUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}

.animate-hero-zoom {
  animation: heroZoom 20s ease-in-out infinite;
}

.animate-float-soft {
  animation: floatSoft 4s ease-in-out infinite;
}

.animate-pulse-slow {
  animation: pulseSlow 6s ease-in-out infinite;
}

.animate-sheen {
  animation: sheenMove 5s ease-in-out infinite;
}

.animation-delay-200 {
  animation-delay: 0.2s;
  animation-fill-mode: backwards;
}

.animation-delay-400 {
  animation-delay: 0.4s;
  animation-fill-mode: backwards;
}
</style>