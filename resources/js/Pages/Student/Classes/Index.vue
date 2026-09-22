<template>
  <Head title="My Classes - Salawag LMS" />

  <div class="min-h-screen flex bg-[#e8f5e9] font-['Inter'] relative">
    
    <!-- Sticky Desktop Sidebar Navigation -->
    <div class="sticky top-0 h-screen z-30 shrink-0">
      <Sidebar />
    </div>

    <!-- Main Workspace Canvas -->
    <main class="flex-1 relative overflow-y-auto min-h-screen flex flex-col justify-between">
      
      <!-- Main Content Container -->
      <div class="relative z-10 p-6 md:p-10 space-y-8 flex-1 pb-10">
        
        <!-- HEADER ROW: STUDENT SUBJECTS TITLE -->
        <div class="space-y-1">
          <div class="font-['Anton'] text-3xl sm:text-4xl md:text-5xl tracking-wide uppercase flex items-center gap-2 select-none">
            <!-- Solid Emerald Fill for STUDENT -->
            <span class="text-[#005506]">STUDENT</span>
            <!-- Reverse Fill-Stroke Animation for SUBJECTS -->
            <span class="animated-reverse-stroke-text">SUBJECTS</span>
          </div>
          
          <p class="text-xs sm:text-sm italic font-medium text-slate-600">
            "Your journey to knowledge starts with one click."
          </p>

          <!-- Decorative Star Divider Line -->
          <div class="flex items-center gap-2 pt-1 max-w-md">
            <div class="h-[2px] w-full bg-[#005506] animate-line-expand"></div>
            <span class="text-[#005506] text-xs">★</span>
          </div>
        </div>

        <!-- SEARCH AND FILTER BAR -->
        <form @submit.prevent="executeSearch" class="flex flex-col sm:flex-row items-center gap-3 w-full">
          
          <!-- Search Input Box -->
          <div class="relative flex-1 w-full">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-500">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
              </svg>
            </div>
            <input 
              v-model="searchQuery"
              type="text"
              placeholder="Search subjects, codes, or teachers..." 
              class="w-full pl-11 pr-4 py-3 bg-white/80 backdrop-blur-sm border border-[#005506]/30 rounded-2xl text-sm font-medium text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#005506] focus:bg-white transition-all shadow-sm"
              @input="handleQuickSearch"
            />
          </div>

          <!-- Filter Dropdown Button -->
          <div class="relative w-full sm:w-auto">
            <button 
              type="button" 
              @click="toggleFilterMenu"
              class="w-full sm:w-auto px-6 py-3 bg-[#005506]/70 hover:bg-[#005506]/80 text-white text-sm font-bold rounded-2xl shadow-sm transition-all flex items-center justify-center gap-2 border border-white/20 active:scale-95"
            >
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
              </svg>
              <span>Filter</span>
            </button>

            <!-- Filter Options Dropdown Popover -->
            <div 
              v-if="showFilterMenu"
              class="absolute right-0 mt-2 w-56 bg-white rounded-2xl shadow-xl border border-slate-200/80 p-3 z-30 space-y-2 animate-card-slide-up"
            >
              <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400 px-2 pt-1">
                Filter by Category
              </p>
              <button 
                type="button" 
                @click="setCategory('')" 
                :class="selectedCategory === '' ? 'bg-emerald-50 text-[#005506] font-bold' : 'text-slate-600 hover:bg-slate-50'"
                class="w-full text-left px-3 py-2 rounded-xl text-xs transition-colors"
              >
                All Subjects
              </button>
              <button 
                type="button" 
                @click="setCategory('CORE')" 
                :class="selectedCategory === 'CORE' ? 'bg-emerald-50 text-[#005506] font-bold' : 'text-slate-600 hover:bg-slate-50'"
                class="w-full text-left px-3 py-2 rounded-xl text-xs transition-colors"
              >
                Core Subjects
              </button>
              <button 
                type="button" 
                @click="setCategory('STEM')" 
                :class="selectedCategory === 'STEM' ? 'bg-emerald-50 text-[#005506] font-bold' : 'text-slate-600 hover:bg-slate-50'"
                class="w-full text-left px-3 py-2 rounded-xl text-xs transition-colors"
              >
                STEM Specialization
              </button>
            </div>
          </div>

          <!-- Primary Search Button -->
          <button 
            type="submit"
            class="w-full sm:w-auto px-8 py-3 bg-[#005506] hover:bg-[#004204] text-white text-sm font-bold rounded-2xl shadow-md transition-all flex items-center justify-center gap-2 border border-emerald-400/20 active:scale-95"
          >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
            <span>Search</span>
          </button>

        </form>

        <!-- SUBJECTS GRID CONTAINER WITH ISOLATED BACKGROUND -->
        <div class="relative rounded-3xl p-6 sm:p-8 overflow-hidden space-y-6">
          
          <!-- Isolated Background Pattern (15% Opacity behind grid) -->
          <div 
            class="absolute inset-0 bg-cover bg-center bg-no-repeat pointer-events-none z-0 opacity-15 rounded-3xl"
            :style="{ backgroundImage: `url(${dashboardBg})` }"
          ></div>

          <!-- Empty State -->
          <div 
            v-if="!filteredClasses.length" 
            class="relative z-10 bg-white/80 backdrop-blur-sm rounded-2xl p-12 text-center border border-slate-200/60 shadow-sm space-y-3"
          >
            <div class="w-12 h-12 rounded-full bg-emerald-50 text-[#005506] mx-auto flex items-center justify-center">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
            <p class="text-slate-600 font-bold text-base">No subjects match your search</p>
            <p class="text-slate-400 text-xs">Try adjusting your search keywords or filter settings.</p>
          </div>

          <!-- Subject Cards Grid -->
          <div v-else class="relative z-10 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6">
            
            <Link
              v-for="(klass, index) in filteredClasses"
              :key="klass.id"
              :href="route('student.classes.show', klass.id)"
              class="bg-white rounded-3xl overflow-hidden shadow-sm hover:shadow-xl border border-slate-200/80 flex flex-col justify-between transition-all duration-300 hover:-translate-y-1.5 group cursor-pointer"
            >
              <!-- Card Header / Banner Area -->
              <div class="relative h-36 bg-gradient-to-br from-amber-100 via-emerald-50 to-teal-100 p-4 flex flex-col justify-between overflow-hidden">
                
                <!-- Category Illustration / Graphic Element -->
                <div class="absolute inset-0 opacity-30 flex items-center justify-center pointer-events-none">
                  <svg class="w-48 h-48 text-[#005506]" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 3L1 9l11 6 9-4.91V17h2V9L12 3zM5 13.18v4l7 3.82 7-3.82v-4L12 17l-7-3.82z"/>
                  </svg>
                </div>

                <!-- Top Badges Row -->
                <div class="relative z-10 flex items-center justify-between">
                  <!-- Number Badge -->
                  <div class="w-7 h-7 rounded-full bg-[#005506] text-white font-extrabold text-xs flex items-center justify-center shadow-md">
                    {{ String(index + 1).padStart(2, '0') }}
                  </div>

                  <!-- Subject Code Pill -->
                  <div class="px-3 py-1 rounded-full bg-[#005506] text-white font-extrabold text-[10px] tracking-wider uppercase shadow-sm">
                    {{ klass.subject_code || 'CORE-COMM' }}
                  </div>
                </div>

                <!-- Abstract Banner Characters Graphic -->
                <div class="relative z-10 flex justify-center -mb-2">
                  <div class="flex items-end gap-1.5 opacity-90">
                    <div class="w-6 h-8 bg-orange-500 rounded-t-full"></div>
                    <div class="w-6 h-10 bg-amber-400 rounded-t-full"></div>
                    <div class="w-6 h-12 bg-[#005506] rounded-t-full"></div>
                    <div class="w-6 h-9 bg-teal-600 rounded-t-full"></div>
                  </div>
                </div>

              </div>

              <!-- Card Body Content -->
              <div class="p-5 flex-1 flex flex-col justify-between space-y-4">
                
                <!-- Subject Title -->
                <div>
                  <h4 class="font-extrabold text-slate-900 text-sm leading-snug group-hover:text-[#005506] transition-colors line-clamp-2">
                    {{ klass.subject }}
                  </h4>
                </div>

                <!-- Subject Metadata -->
                <div class="space-y-1 pt-3 border-t border-slate-100 text-[11px] font-semibold text-slate-400">
                  <p class="truncate">
                    <span>Section:</span> 
                    <span class="text-slate-600 font-bold ml-1">{{ klass.section || 'Grade 12 - ICT' }}</span>
                  </p>
                  <p class="truncate">
                    <span>Teacher:</span> 
                    <span class="text-slate-600 font-bold ml-1">{{ klass.teacher || 'Kaylin Leffler' }}</span>
                  </p>
                </div>

              </div>
            </Link>

          </div>

        </div>

      </div>

    </main>

  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { Head, Link, router } from '@inertiajs/vue3'
import Sidebar from '@/Components/Sidebar.vue'

import dashboardBg from '@/../assets/img/dashboardbackground.png'

const props = defineProps({
  classes: {
    type: Object,
    default: () => ({ data: [] }),
  },
  filters: {
    type: Object,
    default: () => ({ search: '', category: '' }),
  },
})

const searchQuery = ref(props.filters?.search || '')
const selectedCategory = ref(props.filters?.category || '')
const showFilterMenu = ref(false)

// Default sample classes fallback if backend data is empty
const defaultClasses = [
  { id: 1, subject: 'Effective Communication / Mabisang Komunikasyon', subject_code: 'CORE-COMM', section: 'Grade 12 - ICT', teacher: 'Kaylin Leffler' },
  { id: 2, subject: 'Effective Communication / Mabisang Komunikasyon', subject_code: 'CORE-COMM', section: 'Grade 12 - ICT', teacher: 'Kaylin Leffler' },
  { id: 3, subject: 'Effective Communication / Mabisang Komunikasyon', subject_code: 'CORE-COMM', section: 'Grade 12 - ICT', teacher: 'Kaylin Leffler' },
  { id: 4, subject: 'Effective Communication / Mabisang Komunikasyon', subject_code: 'CORE-COMM', section: 'Grade 12 - ICT', teacher: 'Kaylin Leffler' },
  { id: 5, subject: 'Effective Communication / Mabisang Komunikasyon', subject_code: 'CORE-COMM', section: 'Grade 12 - ICT', teacher: 'Kaylin Leffler' },
  { id: 6, subject: 'Effective Communication / Mabisang Komunikasyon', subject_code: 'CORE-COMM', section: 'Grade 12 - ICT', teacher: 'Kaylin Leffler' },
  { id: 7, subject: 'Effective Communication / Mabisang Komunikasyon', subject_code: 'CORE-COMM', section: 'Grade 12 - ICT', teacher: 'Kaylin Leffler' },
  { id: 8, subject: 'Effective Communication / Mabisang Komunikasyon', subject_code: 'CORE-COMM', section: 'Grade 12 - ICT', teacher: 'Kaylin Leffler' },
  { id: 9, subject: 'Effective Communication / Mabisang Komunikasyon', subject_code: 'CORE-COMM', section: 'Grade 12 - ICT', teacher: 'Kaylin Leffler' },
  { id: 10, subject: 'Effective Communication / Mabisang Komunikasyon', subject_code: 'CORE-COMM', section: 'Grade 12 - ICT', teacher: 'Kaylin Leffler' },
]

const classList = computed(() => {
  return props.classes?.data?.length ? props.classes.data : defaultClasses
})

const filteredClasses = computed(() => {
  return classList.value.filter((klass) => {
    const matchesSearch = 
      !searchQuery.value ||
      klass.subject?.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
      klass.subject_code?.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
      klass.teacher?.toLowerCase().includes(searchQuery.value.toLowerCase())

    const matchesCategory = 
      !selectedCategory.value || 
      klass.subject_code?.includes(selectedCategory.value)

    return matchesSearch && matchesCategory
  })
})

const toggleFilterMenu = () => {
  showFilterMenu.value = !showFilterMenu.value
}

const setCategory = (cat) => {
  selectedCategory.value = cat
  showFilterMenu.value = false
  executeSearch()
}

const handleQuickSearch = () => {
  // Live local filtering already computed reactive
}

const executeSearch = () => {
  router.get(
    route('student.classes.index'),
    { search: searchQuery.value, category: selectedCategory.value },
    { preserveState: true, replace: true }
  )
}
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Anton&family=Inter:wght@400;500;600;700;800&display=swap');

/* REVERSE ANIMATED FILL-STROKE TEXT EFFECT FOR 'SUBJECTS' */
.animated-reverse-stroke-text {
  color: transparent;
  -webkit-text-stroke: 1.5px #005506;
  background: linear-gradient(to right, transparent 50%, #005506 50%);
  background-size: 200% 100%;
  background-position: 100% 0;
  -webkit-background-clip: text;
  background-clip: text;
  animation: reverseFillStrokeAnim 1.4s cubic-bezier(0.16, 1, 0.3, 1) 0.2s forwards;
}

@keyframes reverseFillStrokeAnim {
  0% {
    background-position: 100% 0;
  }
  100% {
    background-position: 0 0;
  }
}

@keyframes lineExpand {
  0% {
    width: 0%;
  }
  100% {
    width: 100%;
  }
}

.animate-line-expand {
  animation: lineExpand 1s cubic-bezier(0.16, 1, 0.3, 1) 0.4s both;
}

@keyframes cardSlideUp {
  0% {
    opacity: 0;
    transform: translateY(10px);
  }
  100% {
    opacity: 1;
    transform: translateY(0);
  }
}

.animate-card-slide-up {
  animation: cardSlideUp 0.3s cubic-bezier(0.16, 1, 0.3, 1) both;
}
</style>