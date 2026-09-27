<template>
  <Head title="Assessments - Salawag LMS" />

  <div class="min-h-screen flex bg-[#e8f5e9] font-['Inter'] relative">
    
    <!-- Sticky Desktop Sidebar Navigation -->
    <div class="sticky top-0 h-screen z-30 shrink-0">
      <Sidebar />
    </div>

    <!-- Main Workspace Canvas -->
    <main class="flex-1 relative overflow-y-auto min-h-screen flex flex-col justify-between">
      
      <!-- Main Content Container -->
      <div class="relative z-10 p-6 md:p-8 space-y-6 flex-1 pb-16">
        
        <!-- HERO HEADER BANNER -->
        <div class="w-full bg-[#004d08] text-white rounded-3xl p-6 sm:p-8 shadow-md border border-[#003805] relative overflow-hidden space-y-4">
          
          <!-- Shared Header Title Block -->
          <div class="space-y-1">
            <div class="font-['Anton'] text-3xl sm:text-4xl md:text-5xl tracking-wide uppercase flex items-center gap-2 select-none">
              <span class="text-white">STUDENT</span>
              <span class="animated-stroke-text">ASSESSMENTS</span>
            </div>
            <p class="text-xs sm:text-sm italic font-medium text-emerald-100/80">
              "Your journey to knowledge starts with one click."
            </p>
            <div class="flex items-center gap-2 pt-1 max-w-xl">
              <div class="h-[1.5px] w-full bg-white/40"></div>
              <span class="text-white text-xs">★</span>
            </div>
          </div>

          <!-- HERO META INFO & TELEMETRY GRID -->
          <div class="grid grid-cols-1 lg:grid-cols-12 gap-5 items-center pt-2">
            
            <!-- Left Info Block -->
            <div class="lg:col-span-7 flex flex-col sm:flex-row items-center gap-4">
              <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-2xl border-2 border-white/20 bg-white/10 overflow-hidden shrink-0 shadow-inner flex items-center justify-center text-3xl">
                📝
              </div>
              <div class="space-y-1 text-center sm:text-left">
                <div class="inline-block bg-white/15 backdrop-blur-sm text-emerald-100 text-[11px] font-bold px-3 py-0.5 rounded-full border border-white/10">
                  {{ activeTerm || 'S.Y. 2026-2027 • 1st Semester' }}
                </div>
                <h2 class="font-bold text-lg sm:text-xl text-white tracking-tight leading-snug">
                  Academic Submissions & Rubrics
                </h2>
                <p class="text-xs text-emerald-100/70 font-medium">
                  Grade 12 STEM - Section Rizal • {{ filteredTasks.length }} Total Matching Deliverables
                </p>
              </div>
            </div>

            <!-- Right Quick Telemetry Counters -->
            <div class="lg:col-span-5 grid grid-cols-2 gap-3">
              <div class="bg-white/10 backdrop-blur-md border border-white/15 rounded-2xl py-3 px-4 flex flex-col justify-between">
                <span class="text-[11px] font-medium text-emerald-100/80">Pending Tasks</span>
                <div class="text-xl font-extrabold text-amber-300 my-0.5">{{ pendingCount }} Due</div>
                <span class="text-[10px] text-emerald-100/70">{{ highPriorityCount }} High Priority</span>
              </div>
              <div class="bg-white/10 backdrop-blur-md border border-white/15 rounded-2xl py-3 px-4 flex flex-col justify-between">
                <span class="text-[11px] font-medium text-emerald-100/80">Turn-in Rate</span>
                <div class="text-xl font-extrabold text-white my-0.5">94%</div>
                <span class="text-[10px] text-emerald-100/70">{{ gradedCount }} Graded Submissions</span>
              </div>
            </div>

          </div>

        </div>

        <!-- MAIN CONTENT WORKSPACE (LEFT FEED + SLIDING RIGHT DRAWER) -->
        <div class="rounded-3xl bg-[#fbfdf9] border border-slate-200/80 p-6 sm:p-8 shadow-sm space-y-6">
          
          <!-- FILTER & SEARCH BAR -->
          <div class="space-y-3 pb-4 border-b border-slate-200/60">
            <!-- Filter Tabs & Sort -->
            <div class="flex flex-wrap items-center justify-between gap-3">
              <div class="flex items-center gap-1.5 bg-[#f5f7f2] p-1.5 rounded-2xl border border-slate-200/80 overflow-x-auto">
                <button 
                  v-for="tab in filterTabsWithCounts" 
                  :key="tab.id"
                  @click="selectedTab = tab.id"
                  :class="[
                    'px-3.5 py-1.5 rounded-xl text-xs font-extrabold transition-all shrink-0 flex items-center gap-1.5 cursor-pointer',
                    selectedTab === tab.id 
                      ? 'bg-[#004d08] text-white shadow-xs' 
                      : 'text-slate-700 hover:text-slate-900'
                  ]"
                >
                  <span>{{ tab.label }}</span>
                  <span 
                    :class="[
                      'text-[10px] px-1.5 py-0.2 rounded-full font-black transition-colors',
                      selectedTab === tab.id ? 'bg-white/20 text-white' : 'bg-slate-200/80 text-slate-700'
                    ]"
                  >
                    {{ tab.count }}
                  </span>
                </button>
              </div>

              <!-- Search Bar -->
              <div class="relative w-full sm:w-72">
                <span class="absolute inset-y-0 left-3 flex items-center text-slate-400 text-xs">🔍</span>
                <input 
                  v-model="searchQuery"
                  type="text" 
                  placeholder="Search tasks or rubrics..." 
                  class="w-full bg-[#f5f7f2] border border-slate-200/80 rounded-2xl pl-8 pr-8 py-2 text-xs font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#004d08] transition-all"
                />
                <button 
                  v-if="searchQuery" 
                  @click="searchQuery = ''"
                  class="absolute inset-y-0 right-2.5 flex items-center text-slate-400 hover:text-slate-600 text-xs font-bold cursor-pointer"
                >
                  ✕
                </button>
              </div>
            </div>

            <!-- Subject Quick Filters -->
            <div class="flex items-center gap-2 overflow-x-auto pt-1">
              <span class="text-[10px] font-extrabold uppercase text-slate-400 shrink-0">SUBJECT:</span>
              <button 
                v-for="subject in subjectsList" 
                :key="subject"
                @click="selectedSubject = subject"
                :class="[
                  'px-3 py-1 rounded-xl text-[11px] font-bold transition-all shrink-0 border cursor-pointer',
                  selectedSubject === subject 
                    ? 'bg-[#004d08] text-white border-[#004d08]' 
                    : 'bg-[#f5f7f2] text-slate-700 border-slate-200/80 hover:bg-white'
                ]"
              >
                {{ subject }}
              </button>
            </div>
          </div>

          <!-- ANIMATED DUAL COLUMN GRID LAYOUT -->
          <div 
            class="grid-drawer-wrapper items-start"
            :class="{ 'drawer-active': isDrawerOpen }"
          >
            
            <!-- LEFT COLUMN: TASK LIST -->
            <div class="min-w-0 space-y-8">
              
              <!-- URGENCY PRIORITY QUEUE -->
              <div v-if="filteredPriorityTasks.length > 0" class="space-y-4">
                <div class="flex items-center justify-between">
                  <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-rose-500 animate-pulse"></span>
                    <h3 class="font-extrabold text-slate-900 text-base sm:text-lg">Urgency Priority Queue</h3>
                  </div>
                  <span class="text-xs font-bold text-amber-800 bg-amber-100/80 px-2.5 py-1 rounded-full border border-amber-200/80">
                    {{ filteredPriorityTasks.length }} Actionable Deadlines
                  </span>
                </div>

                <!-- Priority Cards -->
                <div class="grid grid-cols-1 gap-4">
                  <div 
                    v-for="task in filteredPriorityTasks" 
                    :key="task.id"
                    :class="[
                      'bg-[#f5f7f2] rounded-2xl p-5 border transition-all duration-300 space-y-3 relative overflow-hidden',
                      activeDrawerTask?.id === task.id ? 'border-amber-400 shadow-md ring-2 ring-amber-300/50' : 'border-slate-200/80 hover:border-slate-300'
                    ]"
                  >
                    <!-- Subject Tag & Due Pill -->
                    <div class="flex flex-wrap items-center justify-between gap-2">
                      <span class="text-[11px] font-extrabold uppercase text-[#004d08] bg-white px-2.5 py-0.5 rounded-md border border-slate-200/60">
                        {{ task.category }} • {{ task.subject }}
                      </span>
                      <span class="bg-rose-500 text-white text-[10px] font-black uppercase px-2.5 py-0.5 rounded-full shadow-xs">
                        ⏰ DUE TODAY • {{ task.dueTime }}
                      </span>
                    </div>

                    <!-- Title & Description -->
                    <div class="space-y-1">
                      <h4 class="font-black text-slate-900 text-sm sm:text-base leading-snug">{{ task.title }}</h4>
                      <p class="text-xs text-slate-600 font-medium leading-relaxed">{{ task.description }}</p>
                    </div>

                    <!-- Task Metadata Bar -->
                    <div class="flex flex-wrap items-center justify-between gap-3 pt-2 border-t border-slate-200/60">
                      <div class="flex items-center gap-3 text-xs font-bold text-slate-600">
                        <span>⭐ {{ task.points }} Points</span>
                        <span>⚡ +{{ task.sparks }} Sparks</span>
                        <span class="text-amber-700">✏️ {{ task.draftStatus }}</span>
                      </div>

                      <button 
                        @click="openDrawer(task)"
                        class="bg-amber-400 hover:bg-amber-500 text-slate-900 text-xs font-black px-4 py-2 rounded-xl transition-all shadow-xs flex items-center gap-1.5 cursor-pointer transform hover:-translate-y-0.5 active:translate-y-0"
                      >
                        📥 Open Submission Drawer
                      </button>
                    </div>

                    <!-- Attached File Pill -->
                    <div v-if="task.attachedFile" class="bg-white rounded-xl p-2.5 text-xs text-slate-700 font-bold border border-slate-200/60 flex items-center gap-2">
                      <span class="text-base">📄</span>
                      <div class="flex-1 truncate">
                        <div>{{ task.attachedFile.name }}</div>
                        <div class="text-[10px] text-slate-400 font-normal">{{ task.attachedFile.size }} • Sync Ready</div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- CLASSWORK PIPELINE (GENERAL LIST) -->
              <div v-if="filteredGeneralTasks.length > 0" class="space-y-4">
                <h3 class="font-extrabold text-slate-900 text-base sm:text-lg border-b border-slate-200/60 pb-2">
                  Classwork Pipeline
                </h3>

                <div class="grid grid-cols-1 gap-3">
                  <div 
                    v-for="task in filteredGeneralTasks" 
                    :key="task.id"
                    :class="[
                      'bg-[#f5f7f2] rounded-2xl p-4 border transition-all duration-300 flex flex-col sm:flex-row sm:items-center justify-between gap-4',
                      activeDrawerTask?.id === task.id ? 'border-[#004d08] bg-emerald-50/50' : 'border-slate-200/80 hover:border-slate-300'
                    ]"
                  >
                    <div class="space-y-1 flex-1">
                      <div class="flex items-center gap-2">
                        <span class="text-[10px] font-extrabold uppercase text-[#004d08] bg-white px-2 py-0.5 rounded border border-slate-200">
                          {{ task.subject }}
                        </span>
                        <span class="text-xs font-semibold text-slate-500">Teacher: {{ task.instructor }}</span>
                      </div>
                      <h4 class="font-bold text-slate-900 text-sm">{{ task.title }}</h4>
                      <p class="text-xs text-slate-500 line-clamp-1">{{ task.description }}</p>
                    </div>

                    <div class="flex sm:flex-col items-center sm:items-end justify-between gap-2 shrink-0 border-t sm:border-t-0 border-slate-200/60 pt-2 sm:pt-0">
                      <span class="text-xs font-bold text-slate-600">Due {{ task.dueDate }}</span>
                      <button 
                        @click="openDrawer(task)"
                        class="bg-white hover:bg-[#004d08] text-[#004d08] hover:text-white border border-[#004d08] text-xs font-bold px-3 py-1.5 rounded-xl transition-all cursor-pointer"
                      >
                        View & Submit
                      </button>
                    </div>
                  </div>
                </div>
              </div>

              <!-- EMPTY STATE -->
              <div v-if="filteredTasks.length === 0" class="text-center py-12 space-y-3 bg-[#f5f7f2] rounded-3xl border border-dashed border-slate-300">
                <div class="text-4xl">🔍</div>
                <div class="text-sm font-extrabold text-slate-800">No assessments found</div>
                <p class="text-xs text-slate-500 max-w-sm mx-auto">Try clearing your search query or switching your active subject filter tab.</p>
                <button 
                  @click="resetFilters" 
                  class="bg-[#004d08] text-white text-xs font-bold px-4 py-2 rounded-xl hover:bg-[#003805] transition-colors cursor-pointer"
                >
                  Reset Filters
                </button>
              </div>

            </div>

            <!-- RIGHT COLUMN: SLIDING DRAWER CONTAINER -->
            <div class="drawer-column min-w-0">
              <div 
                v-if="activeDrawerTask" 
                class="bg-[#f5f7f2] border border-slate-200/90 rounded-3xl p-5 sm:p-6 shadow-xl space-y-5 sticky top-6 drawer-inner-content"
              >
                <!-- Drawer Header -->
                <div class="flex items-center justify-between pb-3 border-b border-slate-200">
                  <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-600 animate-pulse"></span>
                    <span class="text-xs font-black uppercase text-[#004d08] tracking-wider">ACTIVE SUBMISSION</span>
                  </div>
                  <button 
                    @click="closeDrawer" 
                    class="w-7 h-7 rounded-full bg-white hover:bg-slate-200 text-slate-600 flex items-center justify-center font-bold text-xs transition-all cursor-pointer hover:scale-105 active:scale-95"
                  >
                    ✕
                  </button>
                </div>

                <!-- Task Title & Points Banner -->
                <div class="flex items-start justify-between gap-3">
                  <div>
                    <h3 class="font-black text-slate-900 text-lg leading-snug">{{ activeDrawerTask.subject }}</h3>
                    <p class="text-xs font-bold text-slate-600">{{ activeDrawerTask.title }}</p>
                  </div>
                  <div class="bg-amber-100 text-amber-900 px-3 py-1 rounded-xl text-center shrink-0 border border-amber-200/80">
                    <span class="text-xs font-extrabold block">{{ activeDrawerTask.points }} Pts</span>
                    <span class="text-[9px] font-bold uppercase text-amber-700">Total</span>
                  </div>
                </div>

                <!-- DepEd Rubric Breakdown Box -->
                <div class="bg-white rounded-2xl p-4 border border-slate-200/80 space-y-2.5 shadow-2xs">
                  <div class="flex items-center justify-between text-xs font-extrabold text-slate-700 border-b border-slate-100 pb-1.5">
                    <span>EVALUATION RUBRIC (DepEd K-12)</span>
                    <span class="text-[#004d08]">Weight Breakdown</span>
                  </div>
                  <div class="space-y-1.5 text-xs">
                    <div class="flex justify-between items-center text-slate-700 font-semibold">
                      <span>Data Accuracy & PhET Simulations</span>
                      <span class="font-bold text-emerald-800">15 pts</span>
                    </div>
                    <div class="flex justify-between items-center text-slate-700 font-semibold">
                      <span>Analysis, Equations & Error Derivation</span>
                      <span class="font-bold text-emerald-800">15 pts</span>
                    </div>
                    <div class="flex justify-between items-center text-slate-700 font-semibold">
                      <span>DepEd Lab Format & Synthesis Quality</span>
                      <span class="font-bold text-emerald-800">10 pts</span>
                    </div>
                  </div>
                </div>

                <!-- Drag & Drop Attachment Box -->
                <div class="space-y-2">
                  <label class="text-[11px] font-extrabold uppercase text-slate-500 tracking-wider block">ATTACH FINISHED WORK</label>
                  <div class="bg-white border-2 border-dashed border-slate-300 hover:border-[#004d08] rounded-2xl p-6 text-center space-y-2 transition-all cursor-pointer group hover:bg-emerald-50/20">
                    <div class="w-10 h-10 rounded-full bg-emerald-50 text-[#004d08] flex items-center justify-center mx-auto text-xl font-bold group-hover:scale-110 transition-transform">
                      ☁️
                    </div>
                    <div class="text-xs font-bold text-slate-800">Click to browse or drag and drop files here</div>
                    <p class="text-[10px] text-slate-400 font-medium">Supports PDF, DOCX, XLSX, ZIP (Max 25 MB per file)</p>
                  </div>
                </div>

                <!-- Attached File Card -->
                <div v-if="activeDrawerTask.attachedFile" class="bg-white rounded-2xl p-3 border border-slate-200/80 flex items-center justify-between text-xs shadow-2xs">
                  <div class="flex items-center gap-2.5 truncate">
                    <span class="text-lg">📊</span>
                    <div class="truncate">
                      <div class="font-bold text-slate-800 truncate">{{ activeDrawerTask.attachedFile.name }}</div>
                      <div class="text-[10px] text-emerald-700 font-semibold">Ready for submission</div>
                    </div>
                  </div>
                  <button class="text-slate-400 hover:text-slate-600 font-bold text-xs p-1 cursor-pointer">✕</button>
                </div>

                <!-- Private Clarification Note Input -->
                <div class="space-y-1.5">
                  <label class="text-[10px] font-extrabold uppercase text-slate-500 tracking-wider block">PRIVATE CLARIFICATION NOTE FOR INSTRUCTOR</label>
                  <textarea 
                    rows="3" 
                    placeholder="Add a note or context regarding your simulation data..." 
                    class="w-full bg-white border border-slate-200/80 rounded-2xl p-3 text-xs font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#004d08] transition-all"
                  ></textarea>
                </div>

                <!-- Academic Integrity Declaration Banner -->
                <div class="bg-emerald-50/80 rounded-2xl p-3 border border-emerald-200/80 flex items-start gap-2.5 text-[11px] text-emerald-900 font-medium">
                  <input type="checkbox" checked class="mt-0.5 rounded text-[#004d08] focus:ring-[#004d08] cursor-pointer" />
                  <span>I hereby declare that this assignment represents my own work and conforms to the <strong>Salawag SHS & DepEd Academic Integrity Guidelines</strong>.</span>
                </div>

                <!-- Turn In Action Button -->
                <button class="w-full bg-amber-400 hover:bg-amber-500 active:bg-amber-600 text-slate-900 text-xs sm:text-sm font-black py-3.5 rounded-2xl transition-all shadow-xs flex items-center justify-center gap-2 cursor-pointer transform hover:-translate-y-0.5 active:translate-y-0">
                  <span>➤</span> Turn In Assignment
                </button>

                <!-- Footer Encryption Note -->
                <div class="flex items-center justify-between text-[10px] text-slate-400 font-semibold">
                  <span>🔒 Encrypted Submission</span>
                  <span>Saved Draft ID: #PHY2-88412</span>
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
import { ref, computed } from 'vue'
import { Head } from '@inertiajs/vue3'
import Sidebar from '@/Components/Sidebar.vue'

const props = defineProps({
  activeTerm: String,
})

const selectedTab = ref('all')
const selectedSubject = ref('All Subjects (6)')
const searchQuery = ref('')
const isDrawerOpen = ref(false)
const activeDrawerTask = ref(null)

const filterTabs = [
  { id: 'all', label: 'All Assignments' },
  { id: 'pending', label: 'Assigned / Pending' },
  { id: 'submitted', label: 'Submitted' },
  { id: 'graded', label: 'Graded' },
  { id: 'returned', label: 'Returned / Revisions' },
]

const subjectsList = [
  'All Subjects (6)',
  'General Physics 2',
  'Basic Calculus',
  'Practical Research II',
  'Pagsulat sa Filipino',
  'Media & Info Literacy'
]

const allTasks = ref([
  {
    id: 1,
    isPriority: true,
    status: 'pending',
    category: 'STEM CORE',
    subject: 'General Physics 2',
    instructor: 'Engr. Benjamin Bautista',
    title: 'Laboratory Activity 4: Electromagnetic Induction & Faraday’s Law Simulation Report',
    description: 'Submit completed PhET simulation data tables, secondary coil schematics, induced voltage calculations, and a concise 3-paragraph synthesis linking magnetic flux variations to Lenz’s law.',
    dueTime: '11:59 PM Today',
    points: 40,
    sparks: 35,
    draftStatus: 'Draft saved 2h ago',
    attachedFile: { name: 'Faraday_Sim_Data_Table_v1.xlsx', size: '1.4 MB' }
  },
  {
    id: 2,
    isPriority: true,
    status: 'pending',
    category: 'STEM APPLIED',
    subject: 'Practical Research II',
    instructor: 'Dr. Josefa Garcia',
    title: 'Chapter 3: Research Methodology & Instrument Validation Draft',
    description: 'Capstone Group 4: Present sampling design, expert validator matrices for the water contaminant IoT sensor, and statistical data treatment protocols.',
    dueTime: 'Tomorrow • 5:00 PM',
    points: 50,
    sparks: 25,
    draftStatus: 'Draft saved 10m ago',
    attachedFile: { name: 'Chapter_3_Draft_v2.pdf', size: '3.2 MB' }
  },
  {
    id: 3,
    isPriority: false,
    status: 'pending',
    subject: 'Basic Calculus',
    instructor: 'Sir Marlon Aquino',
    title: 'Problem Set 5: Definite Integrals & Fundamental Theorem',
    description: '15 problem sets applying Riemann sums and area under parametric curves. Show complete manual solutions.',
    dueDate: 'March 13',
    points: 50
  },
  {
    id: 4,
    isPriority: false,
    status: 'submitted',
    subject: 'Pagsulat sa Filipino',
    instructor: 'Gng. Rosario Santos',
    title: 'Akademikong Sulatin: Bionote at Sintesis',
    description: 'Pagsulat ng sariling bionote at buod ng binasang pananaliksik gamit ang tamang pormat ng DepEd.',
    dueDate: 'March 14',
    points: 30
  },
  {
    id: 5,
    isPriority: false,
    status: 'graded',
    subject: 'Media & Info Literacy',
    instructor: 'Ms. Clara Alonzo',
    title: 'Media Architecture Analysis Paper',
    description: 'Critique of modern algorithmic newsfeed curation and its socio-cognitive impact.',
    dueDate: 'March 01',
    points: 100
  }
])

const filteredTasks = computed(() => {
  return allTasks.value.filter(task => {
    const matchesTab = selectedTab.value === 'all' || task.status === selectedTab.value
    const matchesSubject = selectedSubject.value === 'All Subjects (6)' || task.subject === selectedSubject.value
    const query = searchQuery.value.toLowerCase().trim()
    const matchesSearch = !query || 
      task.title.toLowerCase().includes(query) ||
      task.description.toLowerCase().includes(query) ||
      task.subject.toLowerCase().includes(query)

    return matchesTab && matchesSubject && matchesSearch
  })
})

const filteredPriorityTasks = computed(() => filteredTasks.value.filter(task => task.isPriority))
const filteredGeneralTasks = computed(() => filteredTasks.value.filter(task => !task.isPriority))

const filterTabsWithCounts = computed(() => {
  return filterTabs.map(tab => {
    const count = allTasks.value.filter(task => {
      const matchesTab = tab.id === 'all' || task.status === tab.id
      const matchesSubject = selectedSubject.value === 'All Subjects (6)' || task.subject === selectedSubject.value
      const query = searchQuery.value.toLowerCase().trim()
      const matchesSearch = !query || task.title.toLowerCase().includes(query) || task.subject.toLowerCase().includes(query)
      return matchesTab && matchesSubject && matchesSearch
    }).length

    return { ...tab, count }
  })
})

const pendingCount = computed(() => allTasks.value.filter(t => t.status === 'pending').length)
const highPriorityCount = computed(() => allTasks.value.filter(t => t.isPriority && t.status === 'pending').length)
const gradedCount = computed(() => allTasks.value.filter(t => t.status === 'graded').length)

const openDrawer = (task) => {
  activeDrawerTask.value = task
  isDrawerOpen.value = true
}

const closeDrawer = () => {
  isDrawerOpen.value = false
  setTimeout(() => {
    activeDrawerTask.value = null
  }, 500)
}

const resetFilters = () => {
  selectedTab.value = 'all'
  selectedSubject.value = 'All Subjects (6)'
  searchQuery.value = ''
}
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Anton&family=Inter:wght@400;500;600;700;800;900&display=swap');

.animated-stroke-text {
  color: transparent;
  -webkit-text-stroke: 1.5px #ffffff;
}

/* 60FPS CSS GRID COLUMN ANIMATION */
.grid-drawer-wrapper {
  display: grid;
  grid-template-columns: 1fr 0fr;
  gap: 0px;
  /* Fast default transition for closing (0.4s) */
  transition: 
    grid-template-columns 0.4s cubic-bezier(0.4, 0, 0.2, 1),
    gap 0.4s cubic-bezier(0.4, 0, 0.2, 1);
  will-change: grid-template-columns, gap;
}

@media (min-width: 1024px) {
  .grid-drawer-wrapper.drawer-active {
    grid-template-columns: 7fr 5fr;
    gap: 24px;
    /* Slower, ultra-smooth transition for opening (0.8s) */
    transition: 
      grid-template-columns 0.8s cubic-bezier(0.16, 1, 0.3, 1),
      gap 0.8s cubic-bezier(0.16, 1, 0.3, 1);
  }
}

.drawer-column {
  overflow: hidden;
}

/* INNER CONTENT ANIMATION */
.drawer-inner-content {
  opacity: 0;
  transform: translateX(32px) scale(0.97);
  /* Fast fade out on closing */
  transition: 
    opacity 0.25s ease-out,
    transform 0.25s ease-out;
  will-change: opacity, transform;
}

.drawer-active .drawer-inner-content {
  opacity: 1;
  transform: translateX(0) scale(1);
  /* Slow, graceful entry fade with a slight delay so column expands first */
  transition: 
    opacity 0.5s cubic-bezier(0.16, 1, 0.3, 1) 0.25s,
    transform 0.6s cubic-bezier(0.16, 1, 0.3, 1) 0.25s;
}
</style>