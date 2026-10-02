<template>
  <Head :title="`Faculty & Staff - ${school.name}`" />

  <div class="min-h-screen bg-[#E8FFE8] font-['Inter'] text-slate-800 overflow-x-hidden flex flex-col justify-between">
    <div>
      <!-- HERO -->
      <section class="relative w-full min-h-[65vh] md:min-h-[72vh] bg-[#004d08] text-white overflow-hidden shadow-md flex items-center">
        <Navbar />

        <div class="absolute inset-0 z-0 pointer-events-none overflow-hidden">
          <img src="https://images.unsplash.com/photo-1524178232363-1fb2b075b655?auto=format&fit=crop&w=1920&q=80"
            :alt="`${school.name} Faculty & Staff`"
            class="w-full h-full object-cover object-center opacity-20 mix-blend-overlay scale-105 animate-hero-zoom transition-transform duration-1000" />
          <div class="absolute inset-0 bg-gradient-to-r from-[#004d08]/98 via-[#004d08]/88 to-[#003805]/75"></div>
        </div>
        <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/5 to-transparent animate-sheen pointer-events-none z-0"></div>
        <div class="absolute -top-24 -left-24 w-96 h-96 bg-emerald-400/15 rounded-full blur-3xl pointer-events-none animate-pulse-slow"></div>
        <div class="absolute -bottom-24 right-0 w-96 h-96 bg-amber-400/10 rounded-full blur-3xl pointer-events-none animate-pulse-slow delay-1000"></div>

        <div class="relative z-10 w-full max-w-[1500px] mx-auto px-6 sm:px-12 md:px-16 lg:px-24 pt-28 md:pt-36 pb-16 space-y-8">
          <div class="space-y-4 max-w-4xl">
            <h1 class="font-['Anton'] text-5xl sm:text-6xl md:text-7xl lg:text-8xl tracking-tight uppercase leading-[1.02] text-white animate-fade-in-up">
              Faculty & Staff Directory
            </h1>
            <p class="text-sm sm:text-base md:text-lg text-emerald-100/90 font-normal leading-relaxed pt-1 max-w-3xl animate-fade-in-up animation-delay-200">
              Meet the mentors, innovators, and certified DepEd educators guiding {{ school.name }} scholars toward
              college readiness, regional leadership, and technical excellence.
            </p>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 pt-2 animate-fade-in-up animation-delay-400">
            <div class="bg-white/10 backdrop-blur-md rounded-2xl p-5 border border-white/15 space-y-2">
              <span class="text-xs font-bold uppercase tracking-wider text-amber-200/90">Instructional Core</span>
              <p class="font-['Anton'] text-4xl sm:text-5xl text-white tracking-wide">{{ faculty.length }}</p>
              <p class="text-xs text-emerald-100/80 font-medium">Listed Faculty Members</p>
            </div>
            <div class="bg-white/10 backdrop-blur-md rounded-2xl p-5 border border-white/15 space-y-2">
              <span class="text-xs font-bold uppercase tracking-wider text-amber-200/90">Master Cadre</span>
              <p class="font-['Anton'] text-4xl sm:text-5xl text-white tracking-wide">{{ masterCount }}</p>
              <p class="text-xs text-emerald-100/80 font-medium">DepEd Master Teachers</p>
            </div>
            <div class="bg-white/10 backdrop-blur-md rounded-2xl p-5 border border-white/15 space-y-2">
              <span class="text-xs font-bold uppercase tracking-wider text-amber-200/90">Departments</span>
              <p class="font-['Anton'] text-4xl sm:text-5xl text-white tracking-wide">{{ departmentCount }}</p>
              <p class="text-xs text-emerald-100/80 font-medium">Academic & Support Units</p>
            </div>
            <div class="bg-white/10 backdrop-blur-md rounded-2xl p-5 border border-white/15 space-y-2">
              <span class="text-xs font-bold uppercase tracking-wider text-amber-200/90">Class Ratio</span>
              <p class="font-['Anton'] text-4xl sm:text-5xl text-white tracking-wide">1:28</p>
              <p class="text-xs text-emerald-100/80 font-medium">DepEd Standard Optimal Ratio</p>
            </div>
          </div>
        </div>
      </section>
    </div>

    <!-- SEARCH & FILTER -->
    <section ref="searchRef" class="w-full max-w-[1500px] mx-auto px-6 sm:px-12 md:px-16 lg:px-24 pt-12 pb-6">
      <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-slate-200/80 space-y-6 transition-all duration-1000"
        :class="isSearchVisible ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-12'">

        <div class="flex flex-col md:flex-row items-center justify-between gap-4">
          <div class="relative w-full md:max-w-2xl">
            <svg class="w-5 h-5 text-slate-400 absolute left-4 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" /></svg>
            <input type="text" v-model="searchQuery"
              placeholder="Search educator by name, strand, room, or subject specialization..."
              class="w-full pl-12 pr-4 py-3 rounded-2xl bg-slate-50 border border-slate-200 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-[#005506] focus:bg-white transition-all" />
            <button v-if="searchQuery" @click="searchQuery = ''" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 text-xs font-bold">✕ Clear</button>
          </div>
          <div class="flex items-center gap-3 w-full md:w-auto justify-between md:justify-end">
            <button @click="resetFilters" class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-600 hover:text-[#005506] bg-slate-100 hover:bg-slate-200/80 px-4 py-2.5 rounded-xl transition-all">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" /></svg>
              <span>Reset</span>
            </button>
            <span class="inline-flex items-center gap-1.5 bg-emerald-100 text-[#005506] text-xs font-extrabold px-3.5 py-2 rounded-xl border border-emerald-200">
              <span class="w-2 h-2 rounded-full bg-[#005506] animate-pulse"></span>
              {{ filteredFaculty.length }} Active Profiles
            </span>
          </div>
        </div>

        <div class="flex items-center gap-2 overflow-x-auto pb-2 scrollbar-none">
          <button v-for="cat in categories" :key="cat.id" @click="activeCategory = cat.id"
            class="px-4 py-2.5 rounded-xl text-xs font-bold whitespace-nowrap transition-all cursor-pointer"
            :class="activeCategory === cat.id ? 'bg-[#005506] text-white shadow-md' : 'bg-slate-100 text-slate-600 hover:bg-slate-200/80'">
            {{ cat.name }}
          </button>
        </div>
      </div>
    </section>

    <!-- DIRECTORY GRID -->
    <section ref="directoryRef" class="w-full max-w-[1500px] mx-auto px-6 sm:px-12 md:px-16 lg:px-24 py-8">
      <div class="transition-all duration-1000"
        :class="isDirectoryVisible ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-12'">

        <div v-if="filteredFaculty.length === 0" class="text-center py-16 bg-white rounded-3xl border border-slate-200 space-y-4">
          <h3 class="font-bold text-lg text-[#005506]">No Educator Profiles Found</h3>
          <p class="text-xs text-slate-500 max-w-sm mx-auto">No faculty members matched your filter criteria.</p>
          <button @click="resetFilters" class="px-5 py-2 bg-[#005506] text-white font-bold text-xs rounded-xl shadow">Reset Search</button>
        </div>

        <div v-else class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
          <div v-for="member in filteredFaculty" :key="member.id"
            class="bg-white rounded-3xl p-6 shadow-sm border border-slate-200/80 flex flex-col justify-between space-y-5 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 relative overflow-hidden group">
            <div class="absolute top-0 left-0 right-0 h-1.5 bg-[#005506]"></div>

            <div class="space-y-4 pt-1">
              <div class="flex items-center justify-between text-[11px] font-bold">
                <span class="px-2.5 py-1 rounded-md uppercase tracking-wider bg-emerald-100 text-[#005506]">
                  {{ member.categoryLabel }}
                </span>
                <span class="text-slate-500 flex items-center gap-1 font-semibold">
                  <span class="w-2 h-2 rounded-full bg-amber-400"></span>
                  {{ member.room }}
                </span>
              </div>

              <div class="text-center space-y-2">
                <div class="relative w-24 h-24 mx-auto rounded-full overflow-hidden border-2 border-emerald-100 shadow-sm group-hover:scale-105 transition-transform duration-300">
                  <img :src="member.image || defaultAvatar" :alt="member.name" class="w-full h-full object-cover object-top" />
                </div>
                <div>
                  <h3 class="font-bold text-lg text-[#005506] leading-snug">{{ member.name }}</h3>
                  <p class="text-xs font-bold text-amber-800">{{ member.title }}</p>
                  <p class="text-[11px] text-slate-500 leading-normal mt-1">{{ member.roleSubtitle }}</p>
                </div>
              </div>

              <div class="bg-slate-50 rounded-2xl p-3.5 space-y-2 text-xs border border-slate-100">
                <div v-if="member.advisory" class="flex items-start justify-between gap-2">
                  <span class="text-slate-400 font-semibold text-[11px]">Advisory:</span>
                  <span class="text-slate-800 font-bold text-right text-[11px]">{{ member.advisory }}</span>
                </div>
                <div v-if="member.consultation" class="flex items-start justify-between gap-2">
                  <span class="text-slate-400 font-semibold text-[11px]">Consultation:</span>
                  <span class="text-slate-800 font-semibold text-right text-[11px]">{{ member.consultation }}</span>
                </div>
                <div v-if="member.specialization" class="flex items-start justify-between gap-2 border-t border-slate-200/60 pt-1.5 mt-1">
                  <span class="text-slate-400 font-semibold text-[11px]">Specialization:</span>
                  <span class="text-[#005506] font-bold text-right text-[11px]">{{ member.specialization }}</span>
                </div>
                <div v-if="member.email" class="flex items-start justify-between gap-2 border-t border-slate-200/60 pt-1.5 mt-1">
                  <span class="text-slate-400 font-semibold text-[11px]">Email:</span>
                  <span class="text-[#005506] font-bold text-right text-[11px] truncate max-w-[150px]">{{ member.email }}</span>
                </div>
              </div>
            </div>

            <button @click="bookConsultation(member)"
              class="w-full py-3 px-4 rounded-xl font-bold text-xs text-white shadow-md transition-all flex items-center justify-center gap-2 cursor-pointer active:scale-95 bg-[#005506] hover:bg-[#003d06]">
              <span>Book Session</span>
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" /></svg>
            </button>
          </div>
        </div>
      </div>
    </section>

    <!-- OFFICE HOURS -->
    <section ref="operationsRef" class="w-full max-w-[1500px] mx-auto px-6 sm:px-12 md:px-16 lg:px-24 py-12 md:py-16">
      <div class="transition-all duration-1000"
        :class="isOperationsVisible ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-12'">
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-10">
          <div class="space-y-3 max-w-2xl">
            <span class="text-xs font-extrabold uppercase tracking-widest text-amber-800 block">OPERATIONAL INQUIRIES</span>
            <h2 class="font-['Anton'] text-3xl sm:text-4xl md:text-5xl text-[#005506] tracking-wide uppercase leading-tight">
              Department & Support Office Service Hours
            </h2>
            <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
              Official operating schedules for on-campus verification, document releases, and parent consultations.
            </p>
          </div>
          <div class="inline-flex items-center gap-2 text-xs font-bold text-[#005506] bg-emerald-100/70 px-4 py-2 rounded-xl border border-emerald-200/60 shrink-0">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            <span>DepEd Citizen's Charter Standard Service Window</span>
          </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-5">
          <div v-for="office in officeHours" :key="office.id"
            class="bg-white rounded-3xl p-6 shadow-sm border border-slate-200/80 flex flex-col justify-between space-y-6 hover:shadow-lg transition-all duration-300">
            <div class="space-y-4">
              <div class="w-10 h-10 rounded-2xl flex items-center justify-center shrink-0 bg-emerald-100 text-[#005506]">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 9h3.75M15 12h3.75M15 15h3.75M4.5 19.5h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z" /></svg>
              </div>
              <div class="space-y-1">
                <h3 class="font-bold text-lg text-[#005506] leading-snug">{{ office.title }}</h3>
                <p class="text-xs text-slate-500">{{ office.subtitle }}</p>
              </div>
            </div>
            <div class="pt-4 border-t border-slate-100 space-y-2 text-xs">
              <div>
                <span class="font-bold text-slate-800 block">{{ office.location }}</span>
                <span class="text-slate-500 font-medium">{{ office.schedule }}</span>
              </div>
              <a :href="`mailto:${office.email}`" class="text-[#005506] font-bold block truncate hover:underline">{{ office.email }}</a>
            </div>
          </div>
        </div>
      </div>
    </section>

    <Footer class="m-0 p-0 block" />
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted, nextTick } from 'vue'
import { Head } from '@inertiajs/vue3'
import Navbar from '@/Components/Navbar.vue'
import Footer from '@/Components/Footer.vue'
import Swal from 'sweetalert2'

const props = defineProps({
  school: { type: Object, required: true },
  faculty: { type: Array, default: () => [] },
})

const defaultAvatar = 'https://images.unsplash.com/photo-1544005313-94ddf0286df2?auto=format&fit=crop&w=400&q=80'

const searchQuery = ref('')
const activeCategory = ref('all')

const searchRef = ref(null)
const directoryRef = ref(null)
const operationsRef = ref(null)
const isSearchVisible = ref(false)
const isDirectoryVisible = ref(false)
const isOperationsVisible = ref(false)

let sectionObserver = null

onMounted(() => {
  sectionObserver = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
      if (entry.target === searchRef.value) isSearchVisible.value = entry.isIntersecting
      else if (entry.target === directoryRef.value) isDirectoryVisible.value = entry.isIntersecting
      else if (entry.target === operationsRef.value) isOperationsVisible.value = entry.isIntersecting
    })
  }, { threshold: 0.12 })

  nextTick(() => {
    if (searchRef.value) sectionObserver.observe(searchRef.value)
    if (directoryRef.value) sectionObserver.observe(directoryRef.value)
    if (operationsRef.value) sectionObserver.observe(operationsRef.value)
  })
})
onUnmounted(() => { if (sectionObserver) sectionObserver.disconnect() })

const categories = [
  { id: 'all', name: 'All Faculty' },
  { id: 'stem', name: 'Academic: STEM' },
  { id: 'humss', name: 'Academic: HUMSS & Arts' },
  { id: 'abm', name: 'Academic: ABM & Entrep' },
  { id: 'techpro', name: 'TechPro Track' },
  { id: 'guidance', name: 'Guidance & Welfare' },
]

const masterCount = computed(() =>
  props.faculty.filter(f => (f.title || '').toLowerCase().includes('master teacher')).length
)
const departmentCount = computed(() => new Set(props.faculty.map(f => f.category)).size)

const filteredFaculty = computed(() => {
  return props.faculty.filter(item => {
    const matchesCategory = activeCategory.value === 'all' || item.category === activeCategory.value
    const q = searchQuery.value.toLowerCase().trim()
    const matchesQuery = !q ||
      (item.name || '').toLowerCase().includes(q) ||
      (item.title || '').toLowerCase().includes(q) ||
      (item.specialization || '').toLowerCase().includes(q) ||
      (item.room || '').toLowerCase().includes(q)
    return matchesCategory && matchesQuery
  })
})

const resetFilters = () => { searchQuery.value = ''; activeCategory.value = 'all' }

const bookConsultation = (member) => {
  Swal.fire({
    title: 'Schedule Consultation',
    text: `Request a meeting slot with ${member.name} (${member.title}) during office hours: ${member.consultation || 'By Appointment'}?`,
    icon: 'info',
    showCancelButton: true,
    confirmButtonColor: '#005506',
    cancelButtonColor: '#64748b',
    confirmButtonText: 'Proceed to Booking',
    cancelButtonText: 'Cancel'
  }).then((result) => {
    if (result.isConfirmed) {
      Swal.fire({
        title: 'Booking Request Sent',
        text: 'Your consultation request has been logged. Check your student portal for confirmation.',
        icon: 'success',
        confirmButtonColor: '#005506'
      })
    }
  })
}

const officeHours = [
  { id: 'registrar', title: 'Office of the Registrar', subtitle: 'SF9/SF10, Diplomas, Transcripts',
    location: 'Bldg A • Ground Floor Rm 103', schedule: 'Mon–Fri • 8:00 AM – 4:30 PM', email: 'registrar.sshs@deped.gov.ph' },
  { id: 'guidance', title: 'Guidance & Counseling', subtitle: 'Counseling, Moral Support, GMRC',
    location: 'Bldg A • 2nd Floor Suite', schedule: 'Mon–Fri • 7:30 AM – 5:00 PM', email: 'guidance.sshs@deped.gov.ph' },
  { id: 'clinic', title: 'School Health & Clinic', subtitle: 'First Aid, Annual Medical, Vax',
    location: 'Bldg C • Clinic Unit 1', schedule: 'Mon–Fri • 7:00 AM – 5:00 PM', email: 'clinic.342512@deped.gov.ph' },
  { id: 'pta', title: 'PTA Secretariat', subtitle: 'Parent Assembly & Community',
    location: 'Campus Activity Pavilion', schedule: 'Wed & Sat • 9:00 AM – 2:00 PM', email: 'ptasalahigh@gmail.com' },
  { id: 'cashier', title: 'Disbursing & Cashier', subtitle: 'Voucher Program, MOOE Support',
    location: 'Admin Bldg • Window 2', schedule: 'Mon–Thu • 8:30 AM – 3:30 PM', email: 'finance.sshs@deped.gov.ph' },
]
</script>

<style scoped>
@keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
@keyframes heroZoom { 0%, 100% { transform: scale(1); } 50% { transform: scale(1.08); } }
@keyframes pulseSlow { 0%, 100% { opacity: 0.3; } 50% { opacity: 0.7; } }
@keyframes sheenMove { 0% { transform: translateX(-100%); } 100% { transform: translateX(200%); } }
.animate-fade-in-up { animation: fadeInUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
.animate-hero-zoom { animation: heroZoom 20s ease-in-out infinite; }
.animate-pulse-slow { animation: pulseSlow 6s ease-in-out infinite; }
.animate-sheen { animation: sheenMove 5s ease-in-out infinite; }
.animation-delay-200 { animation-delay: 0.2s; animation-fill-mode: backwards; }
.animation-delay-400 { animation-delay: 0.4s; animation-fill-mode: backwards; }
</style>