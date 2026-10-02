<template>
  <Head :title="`Academics - ${school.name}`" />

  <div class="min-h-screen bg-[#E8FFE8] font-['Inter'] text-slate-800 overflow-x-hidden">
    <!-- HERO -->
    <section class="relative w-full min-h-[65vh] md:min-h-[72vh] bg-[#004d08] text-white overflow-hidden shadow-md flex items-center">
      <Navbar />

      <div class="absolute inset-0 z-0 pointer-events-none overflow-hidden">
        <img src="https://images.unsplash.com/photo-1562774053-701939374585?auto=format&fit=crop&w=1920&q=80"
          alt="Salawag SHS Campus & Facilities"
          class="w-full h-full object-cover object-center opacity-30 mix-blend-overlay scale-105 animate-hero-zoom transition-transform duration-1000" />
        <div class="absolute inset-0 bg-gradient-to-r from-[#004d08]/98 via-[#004d08]/85 to-[#003805]/75"></div>
      </div>
      <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/5 to-transparent animate-sheen pointer-events-none z-0"></div>
      <div class="absolute -top-24 -left-24 w-96 h-96 bg-emerald-400/15 rounded-full blur-3xl pointer-events-none animate-pulse-slow"></div>
      <div class="absolute -bottom-24 right-0 w-96 h-96 bg-amber-400/10 rounded-full blur-3xl pointer-events-none animate-pulse-slow delay-1000"></div>

      <div class="relative z-10 w-full max-w-[1500px] mx-auto px-6 sm:px-12 md:px-16 lg:px-24 pt-28 md:pt-36 pb-16 space-y-6">
        <div class="space-y-4 max-w-4xl">
          <h1 class="font-['Anton'] text-5xl sm:text-6xl md:text-7xl lg:text-8xl tracking-tight uppercase leading-[1.02] text-white animate-fade-in-up">
            Curriculum Designed for <br />
            <span class="text-amber-400 inline-block underline decoration-amber-400 decoration-4 underline-offset-8 animate-float-soft">
              College Readiness
            </span>, Tech Mastery & Civic Impact
          </h1>
          <p class="text-sm sm:text-base md:text-lg text-emerald-100/90 font-normal leading-relaxed pt-2 max-w-3xl animate-fade-in-up animation-delay-200">
            {{ school.name }} implements DepEd's modernized 2-track framework alongside legacy tracks for continuing
            cohorts, offering cutting-edge laboratories, TESDA-certified workshops, and verified industry immersions.
          </p>
        </div>

        <div class="pt-3 flex flex-wrap items-center gap-4 animate-fade-in-up animation-delay-400">
          <Link :href="route('site.admissions') + '#view-application-flow'"
            class="group relative inline-flex items-center gap-2 bg-amber-400 hover:bg-amber-300 text-slate-950 font-bold text-xs sm:text-sm px-7 py-3.5 rounded-full shadow-lg transition-all duration-300 hover:-translate-y-0.5 active:scale-95 cursor-pointer overflow-hidden">
            <span>Enroll for S.Y. 2026–2027</span>
            <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" /></svg>
          </Link>
          <a href="#pathways"
            class="inline-flex items-center gap-2 bg-white/10 hover:bg-white/20 border border-white/30 text-white font-medium text-xs sm:text-sm px-6 py-3.5 rounded-full transition-all duration-300 hover:-translate-y-0.5 active:scale-95 cursor-pointer backdrop-blur-sm">
            <svg class="w-4 h-4 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18c-2.305 0-4.408.867-6 2.292m0-14.25v14.25" /></svg>
            <span>Explore SHS Program Pathways</span>
          </a>
        </div>
      </div>
    </section>

    <!-- PATHWAYS -->
    <section ref="pathwaysRef" id="pathways" class="w-full text-slate-800 py-16 sm:py-20 px-6 sm:px-12 md:px-16 lg:px-24">
      <div class="max-w-[1500px] mx-auto space-y-10 transition-all duration-1000"
        :class="isPathwaysVisible ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-12'">

        <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-6">
          <div class="space-y-2 max-w-2xl">
            <span class="inline-block bg-[#eaf5ed] text-[#005506] text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider">PATHWAY CATALOG</span>
            <h2 class="font-['Anton'] text-4xl sm:text-5xl lg:text-6xl text-[#005506] uppercase tracking-wide leading-none">
              Explore the SHS Program Pathways
            </h2>
            <p class="text-sm sm:text-base text-slate-600 font-normal leading-relaxed">
              Toggle through modernized and legacy programs to review specialized course concentrations, state-of-the-art facilities, and future career horizons.
            </p>
          </div>

          <div class="bg-[#e9e6d7]/80 p-1.5 rounded-full inline-flex items-center gap-1 shrink-0 self-start lg:self-auto border border-slate-300/60 shadow-inner">
            <button v-for="tab in tabs" :key="tab.key" @click="activeTrack = tab.key"
              :class="activeTrack === tab.key ? 'bg-[#004d08] text-white shadow-md font-bold' : 'text-slate-700 hover:text-slate-900 font-medium'"
              class="px-5 py-2 rounded-full text-xs sm:text-sm transition-all duration-300 cursor-pointer">
              {{ tab.label }}
            </button>
          </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-stretch">
          <div v-for="program in filteredPrograms" :key="program.id"
            class="bg-white rounded-3xl p-6 sm:p-8 md:p-10 shadow-sm border border-slate-200/80 flex flex-col justify-between space-y-6 transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
            <div class="space-y-5">
              <div class="flex items-center justify-between gap-4">
                <span class="inline-block bg-[#eaf5ed] text-[#005506] text-xs font-bold px-3 py-1 rounded-full">
                  {{ program.alphaBadge }}
                </span>
                <span class="inline-flex items-center gap-1.5 bg-amber-100/80 text-amber-900 text-xs font-bold px-3 py-1 rounded-full">
                  <svg class="w-3.5 h-3.5 text-amber-600" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg>
                  <span>{{ program.alignedTag }}</span>
                </span>
              </div>

              <div class="space-y-3">
                <h3 class="text-2xl sm:text-3xl font-bold text-[#005506] leading-snug">{{ program.title }}</h3>
                <p class="text-xs sm:text-sm text-slate-600 leading-relaxed font-normal">{{ program.description }}</p>
              </div>

              <div class="space-y-2 pt-1">
                <h4 class="text-xs font-bold uppercase tracking-wider text-[#005506]">Flagship Core & Specialized Subjects:</h4>
                <div class="flex flex-wrap gap-2">
                  <span v-for="(sub, i) in program.subjects" :key="i"
                    class="bg-[#f5f4ed] text-slate-700 text-xs font-semibold px-3 py-1.5 rounded-lg border border-slate-200/60">{{ sub }}</span>
                </div>
              </div>

              <div class="bg-[#f5f4ed] rounded-2xl p-4 flex items-start gap-3 border border-slate-200/60">
                <div class="w-8 h-8 rounded-lg bg-emerald-100 text-[#005506] flex items-center justify-center shrink-0 mt-0.5">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" /></svg>
                </div>
                <div>
                  <h5 class="text-xs sm:text-sm font-bold text-[#005506]">Dedicated Facility: {{ program.facilityTitle }}</h5>
                  <p class="text-xs text-slate-500 font-normal leading-relaxed">{{ program.facilityDesc }}</p>
                </div>
              </div>
            </div>

            <div class="pt-4 border-t border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs sm:text-sm">
              <p class="text-slate-600 font-medium">
                <span class="font-bold text-[#005506]">Target Degrees:</span> {{ program.targetDegrees }}
              </p>
              <a href="#syllabus-finder" class="font-bold text-[#005506] hover:underline flex items-center gap-1 shrink-0 self-start sm:self-auto">
                <span>View Syllabus</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" /></svg>
              </a>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- SYLLABUS FINDER -->
    <section ref="syllabusRef" id="syllabus-finder" class="w-full text-slate-800 py-16 px-6 sm:px-12 md:px-16 lg:px-24">
      <div class="max-w-[1500px] mx-auto space-y-8 transition-all duration-1000"
        :class="isSyllabusVisible ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-12'">

        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
          <div class="space-y-2 max-w-3xl">
            <span class="inline-block bg-[#f5ebd7] text-[#916b1f] text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider">COURSE CATALOG DATABASE</span>
            <h2 class="font-['Anton'] text-4xl sm:text-5xl lg:text-6xl text-[#005506] uppercase tracking-wide leading-none">
              Interactive Subject Finder & Syllabus
            </h2>
            <p class="text-sm sm:text-base text-slate-600 font-normal leading-relaxed">
              Search, filter, and review prerequisites, weekly hours, and syllabus downloads for all approved course offerings.
            </p>
          </div>
          <div class="shrink-0">
            <span class="bg-white border border-slate-200 text-slate-700 text-xs sm:text-sm font-bold px-4 py-2 rounded-full shadow-2xs">
              Showing <span class="text-[#005506] font-extrabold">{{ filteredSubjects.length }}</span> subjects matching criteria
            </span>
          </div>
        </div>

        <div class="bg-[#f6f3eb] p-4 sm:p-5 rounded-3xl border border-slate-200/80 shadow-xs flex flex-col lg:flex-row items-center gap-4">
          <div class="relative w-full lg:flex-1">
            <svg class="w-4 h-4 absolute left-4 top-1/2 -translate-y-1/2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" /></svg>
            <input v-model="searchQuery" type="text"
              placeholder="Search subject code, title, or topic..."
              class="w-full bg-white text-slate-800 text-xs sm:text-sm pl-11 pr-4 py-3 rounded-2xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-[#005506] transition-all" />
          </div>
          <div class="flex flex-wrap items-center gap-3 w-full lg:w-auto shrink-0">
            <select v-model="selectedTrack" class="bg-[#ebd9bd]/50 hover:bg-[#ebd9bd] text-slate-800 text-xs sm:text-sm font-bold px-4 py-3 rounded-2xl border-0 cursor-pointer focus:outline-none">
              <option value="All">All Tracks & Strands</option>
              <option value="Academic">Academic Track</option>
              <option value="TechPro">TechPro Track</option>
            </select>
            <select v-model="selectedGrade" class="bg-[#ebd9bd]/50 hover:bg-[#ebd9bd] text-slate-800 text-xs sm:text-sm font-bold px-4 py-3 rounded-2xl border-0 cursor-pointer focus:outline-none">
              <option value="All">All Grades</option>
              <option value="Grade 11">Grade 11</option>
              <option value="Grade 12">Grade 12</option>
            </select>
            <select v-model="selectedSem" class="bg-[#ebd9bd]/50 hover:bg-[#ebd9bd] text-slate-800 text-xs sm:text-sm font-bold px-4 py-3 rounded-2xl border-0 cursor-pointer focus:outline-none">
              <option value="All">All Semesters</option>
              <option value="1">Semester 1</option>
              <option value="2">Semester 2</option>
            </select>
            <button @click="resetFilters" title="Reset Filters"
              class="w-11 h-11 bg-[#ebd9bd]/50 hover:bg-[#ebd9bd] text-slate-700 rounded-2xl flex items-center justify-center transition-colors cursor-pointer">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" /></svg>
            </button>
          </div>
        </div>

        <div class="w-full bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
          <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-[900px]">
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
              <tbody class="divide-y divide-slate-100 text-xs sm:text-sm">
                <tr v-for="item in filteredSubjects" :key="item.code" class="hover:bg-slate-50/80 transition-colors">
                  <td class="py-4 px-6">
                    <div class="font-extrabold text-[#005506] text-xs sm:text-sm">{{ item.code }}</div>
                    <div class="font-bold text-[#005506] text-xs sm:text-sm leading-snug">{{ item.title }}</div>
                  </td>
                  <td class="py-4 px-4">
                    <span class="inline-block px-3 py-1 rounded-full text-[11px] font-bold bg-[#eaf5ed] text-[#005506]">{{ item.strand }}</span>
                  </td>
                  <td class="py-4 px-4 text-slate-700 font-semibold">{{ item.levelSem }}</td>
                  <td class="py-4 px-4">
                    <span class="inline-block bg-[#f4ebd0] text-slate-800 text-[11px] font-bold px-2.5 py-1 rounded-md">{{ item.category }}</span>
                  </td>
                  <td class="py-4 px-4 font-bold text-slate-800">{{ item.weeklyHours }}</td>
                  <td class="py-4 px-4 text-slate-500 font-medium text-xs">{{ item.prerequisites }}</td>
                  <td class="py-4 px-6 text-right">
                    <a :href="item.downloadLink" download
                      class="inline-flex items-center gap-1.5 bg-[#fbf2cf] hover:bg-[#f7e7a8] text-[#7c5b12] text-xs font-bold px-3.5 py-2 rounded-full transition-colors cursor-pointer whitespace-nowrap">
                      <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" /></svg>
                      <span>PDF ({{ item.fileSize }})</span>
                    </a>
                  </td>
                </tr>
                <tr v-if="filteredSubjects.length === 0">
                  <td colspan="7" class="py-12 text-center text-slate-500 font-medium">No subjects found matching your criteria.</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </section>

    <Footer class="m-0 p-0 block" />
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue'
import { Link, Head } from '@inertiajs/vue3'
import Navbar from '@/Components/Navbar.vue'
import Footer from '@/Components/Footer.vue'

const props = defineProps({
  school: { type: Object, required: true },
  tracks: { type: Array, default: () => [] },
  subjects: { type: Array, default: () => [] },
})

const tabs = [
  { key: 'academic', label: 'Academic Track' },
  { key: 'techpro', label: 'TechPro Track' },
  { key: 'legacy', label: 'Legacy Strands (Gr. 12)' },
]

const activeTrack = ref('academic')
const searchQuery = ref('')
const selectedTrack = ref('All')
const selectedGrade = ref('All')
const selectedSem = ref('All')

const pathwaysRef = ref(null)
const syllabusRef = ref(null)
const isPathwaysVisible = ref(false)
const isSyllabusVisible = ref(false)

let observer = null

onMounted(() => {
  observer = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
      if (entry.target === pathwaysRef.value) isPathwaysVisible.value = entry.isIntersecting
      else if (entry.target === syllabusRef.value) isSyllabusVisible.value = entry.isIntersecting
    })
  }, { threshold: 0.12 })
  if (pathwaysRef.value) observer.observe(pathwaysRef.value)
  if (syllabusRef.value) observer.observe(syllabusRef.value)
})
onUnmounted(() => { if (observer) observer.disconnect() })

function resetFilters() {
  searchQuery.value = ''
  selectedTrack.value = 'All'
  selectedGrade.value = 'All'
  selectedSem.value = 'All'
}

// Build program "cards" from props.tracks
const filteredPrograms = computed(() => {
  const trackMap = {
    academic: props.tracks.find(t => t.code === 'ACADEMIC' || t.name?.toLowerCase().includes('academic')),
    techpro: props.tracks.find(t => t.code === 'TECHPRO' || t.name?.toLowerCase().includes('technical') || t.name?.toLowerCase().includes('techpro')),
    legacy: props.tracks[0],
  }

  const buildCard = (track, idx, badgeTag = 'DepEd Aligned') => {
    if (!track) return null
    return {
      id: `${activeTrack.value}-${idx}`,
      alphaBadge: `Concentration ${String.fromCharCode(65 + idx)}`,
      alignedTag: badgeTag,
      title: track.name,
      description: track.description,
      subjects: (track.strands || []).map(s => s.name),
      facilityTitle: `${track.name} Laboratory`,
      facilityDesc: 'Dedicated, industry-standard facility for hands-on learning.',
      targetDegrees: 'College & Industry Ready',
    }
  }

  if (activeTrack.value === 'academic') {
    return trackMap.academic ? [buildCard(trackMap.academic, 0, 'DOST Aligned')] : []
  }
  if (activeTrack.value === 'techpro') {
    return trackMap.techpro ? [buildCard(trackMap.techpro, 0, 'TESDA NC II Certified')] : []
  }
  // Legacy: show academic strands as individual cards
  if (trackMap.legacy?.strands) {
    return trackMap.legacy.strands.slice(0, 4).map((s, i) => ({
      id: `legacy-${s.id}`,
      alphaBadge: s.code,
      alignedTag: 'Legacy Curriculum',
      title: s.name,
      description: s.description || `${s.name} — legacy curriculum for continuing Grade 12 cohorts.`,
      subjects: [s.name],
      facilityTitle: `${s.code} Learning Space`,
      facilityDesc: 'Existing facilities maintained for continuing cohorts.',
      targetDegrees: 'College & Industry Ready',
    }))
  }
  return []
})

const filteredSubjects = computed(() => {
  return props.subjects.filter(item => {
    const q = searchQuery.value.toLowerCase()
    const matchesSearch = !q || item.code.toLowerCase().includes(q) || item.title.toLowerCase().includes(q) || item.strand.toLowerCase().includes(q)
    const matchesTrack = selectedTrack.value === 'All' || item.strand.includes(selectedTrack.value)
    const matchesGrade = selectedGrade.value === 'All' || item.levelSem.includes(selectedGrade.value)
    const matchesSem = selectedSem.value === 'All' || item.levelSem.includes(selectedSem.value)
    return matchesSearch && matchesTrack && matchesGrade && matchesSem
  })
})
</script>

<style scoped>
@keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
@keyframes heroZoom { 0%, 100% { transform: scale(1); } 50% { transform: scale(1.08); } }
@keyframes floatSoft { 0%, 100% { transform: translateY(0px); } 50% { transform: translateY(-3px); } }
@keyframes pulseSlow { 0%, 100% { opacity: 0.3; } 50% { opacity: 0.7; } }
@keyframes sheenMove { 0% { transform: translateX(-100%); } 100% { transform: translateX(200%); } }
.animate-fade-in-up { animation: fadeInUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
.animate-hero-zoom { animation: heroZoom 20s ease-in-out infinite; }
.animate-float-soft { animation: floatSoft 4s ease-in-out infinite; }
.animate-pulse-slow { animation: pulseSlow 6s ease-in-out infinite; }
.animate-sheen { animation: sheenMove 5s ease-in-out infinite; }
.animation-delay-200 { animation-delay: 0.2s; animation-fill-mode: backwards; }
.animation-delay-400 { animation-delay: 0.4s; animation-fill-mode: backwards; }
</style>