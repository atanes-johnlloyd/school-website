<template>

  <Head title="My Classes - Salawag LMS" />

  <div :class="[
    'min-h-screen flex bg-[#e8f5e9] dark:bg-[#232D26] font-[\'Inter\'] relative transition-colors duration-300',
    `text-scale-${fontSizeMode}`
  ]">
    <Sidebar :is-open="isSidebarOpen" @close-sidebar="isSidebarOpen = false" />

    <main class="flex-1 relative overflow-y-auto min-h-screen flex flex-col justify-between w-full min-w-0">
      <navbartop searchPlaceholder="Search enrolled subjects, codes, or faculty..."
        @open-sidebar="isSidebarOpen = true"
        @font-size-changed="(size) => fontSizeMode = size" />

      <div class="relative z-10 p-4 sm:p-6 md:p-8 space-y-6 flex-1 pb-16">

        <!-- HERO -->
        <div
          class="animate-fade-in-down relative w-full rounded-2xl sm:rounded-3xl overflow-hidden shadow-lg border border-[#006907]/20 dark:border-none p-4 sm:p-6 md:p-8">

          <img :src="heroImage" alt="Student Subjects Hero"
            class="absolute inset-0 w-full h-full object-cover z-0 object-center" />

          <div class="absolute inset-0 bg-[#004d05] dark:bg-[#152B1C] animate-overlay z-0 mix-blend-multiply"></div>

          <div class="relative z-10 w-full space-y-3 sm:space-y-4">

            <div class="flex flex-wrap items-center gap-2">
              <div
                class="inline-flex items-center gap-1.5 bg-[#F9C20C] text-[#2C3E2D] font-black text-[10px] sm:text-xs px-3 py-1 rounded-full shadow-sm tracking-wide">
                <span>📚</span> STUDENT SUBJECTS
              </div>

              <div
                class="inline-flex items-center gap-2 bg-black/30 dark:bg-black/50 backdrop-blur-md text-white border border-white/20 text-[10px] sm:text-xs font-semibold px-3 py-1 rounded-full shadow-sm">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                {{ activeTerm || 'Active Term' }}
              </div>
            </div>

            <div class="flex items-center gap-3 sm:gap-4">
              <div
                class="w-12 h-12 sm:w-14 sm:h-14 rounded-2xl border-2 border-white/30 bg-white/10 backdrop-blur-sm overflow-hidden shrink-0 shadow-lg flex items-center justify-center transition-transform hover:scale-105 duration-300">
                <Icon icon="academic-cap" size="lg" class="text-white" />
              </div>

              <div class="space-y-0.5 min-w-0">
                <h2 class="text-lg sm:text-2xl md:text-3xl font-black text-white tracking-tight leading-tight">
                  Senior High School Curriculum
                </h2>
                <p class="text-white/90 text-[11px] sm:text-sm leading-snug font-medium">
                  {{ classList.length }} Active {{ classList.length === 1 ? 'Subject' : 'Subjects' }}
                  • School Year <span class="font-bold">2026–2027</span>
                </p>
              </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-1">
              <div
                class="bg-black/30 dark:bg-black/50 backdrop-blur-md border border-white/15 rounded-xl py-3 sm:py-4 px-4 sm:px-5 hover:bg-black/40 transition-all duration-300">
                <span class="text-[10px] font-semibold text-emerald-100/80 uppercase tracking-wider block">
                  Track
                </span>
                <div class="text-base sm:text-xl font-black text-[#F9C20C] leading-tight mt-1 break-words">
                  {{ studentStrand }}
                </div>
                <span class="text-[10px] text-emerald-100/70 block font-medium mt-0.5">Academic Strand</span>
              </div>

              <div
                class="bg-black/30 dark:bg-black/50 backdrop-blur-md border border-white/15 rounded-xl py-3 sm:py-4 px-4 sm:px-5 hover:bg-black/40 transition-all duration-300">
                <span class="text-[10px] font-semibold text-emerald-100/80 uppercase tracking-wider block">
                  Total Subjects
                </span>
                <div class="text-base sm:text-xl font-black text-white leading-tight mt-1">
                  {{ pagination.total || classList.length }}
                </div>
                <span class="text-[10px] text-emerald-100/70 block font-medium mt-0.5">{{ activeTerm || '—' }}</span>
              </div>
            </div>

          </div>
        </div>

        <!-- SEARCH & FILTER TOOLBAR -->
        <div
          class="animate-fade-slide-left rounded-3xl bg-[#fbfdf9] dark:bg-[#2D3A31] border border-slate-200/80 dark:border-[#3F4F43] p-4 sm:p-5 shadow-sm">
          <form @submit.prevent="executeSearch" class="flex flex-col sm:flex-row items-stretch gap-3 w-full">

            <!-- Search -->
            <div class="relative flex-1 w-full">
              <div
                class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 dark:text-slate-500">
                <Icon icon="search" size="md" />
              </div>
              <input
                v-model="searchQuery"
                @input="debouncedSearch"
                type="text"
                placeholder="Search subjects, codes, or teachers..."
                class="w-full pl-11 pr-10 py-3 bg-[#f5f7f2] dark:bg-[#232D26] border border-slate-200/80 dark:border-[#3F4F43] rounded-2xl text-xs font-semibold text-slate-800 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-[#004d08] dark:focus:ring-[#86EFAC] focus:bg-white dark:focus:bg-[#2D3A31] transition-all shadow-xs" />
              <button
                v-if="searchQuery"
                type="button"
                @click="clearSearch"
                class="absolute inset-y-0 right-3 flex items-center text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 cursor-pointer">
                <Icon icon="x" size="xs" />
              </button>
            </div>

            <!-- Sort -->
            <div class="relative w-full sm:w-56 shrink-0">
              <select
                v-model="sortBy"
                @change="executeSearch"
                class="w-full appearance-none bg-[#f5f7f2] dark:bg-[#232D26] border border-slate-200/80 dark:border-[#3F4F43] rounded-2xl pl-4 pr-10 py-3 text-xs font-bold text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-[#004d08] dark:focus:ring-[#86EFAC] cursor-pointer shadow-xs">
                <option value="subject">Subject (A–Z)</option>
                <option value="teacher">Teacher (A–Z)</option>
                <option value="created_at">Recently Added</option>
              </select>
              <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none text-slate-400 dark:text-slate-500">
                <Icon icon="chevron-down" size="xs" />
              </div>
            </div>

          </form>
        </div>

        <!-- SUBJECTS GRID -->
        <div class="animate-fade-slide-up relative rounded-3xl p-2 overflow-hidden space-y-6">

          <div v-if="!classList.length"
            class="relative z-10 bg-white/80 dark:bg-[#2D3A31]/80 backdrop-blur-sm rounded-3xl p-12 text-center border border-slate-200/60 dark:border-[#3F4F43] shadow-sm space-y-3">
            <div
              class="w-12 h-12 rounded-full bg-emerald-50 dark:bg-[#232D26] text-[#005506] dark:text-[#86EFAC] mx-auto flex items-center justify-center">
              <Icon icon="search" size="lg" />
            </div>
            <p class="text-slate-600 dark:text-slate-200 font-bold text-base">No subjects match your search</p>
            <p class="text-slate-400 dark:text-slate-400 text-xs">Try adjusting your search keywords or filter settings.
            </p>
          </div>

          <div v-else
            class="relative z-10 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6">
            <Link v-for="(klass, index) in classList" :key="klass.id" :href="route('student.classes.show', klass.id)"
              :style="{ animationDelay: `${index * 70}ms` }"
              class="subject-card bg-white dark:bg-[#2D3A31] rounded-3xl overflow-hidden shadow-sm hover:shadow-xl border border-slate-200/80 dark:border-[#3F4F43] flex flex-col justify-between transition-all duration-300 hover:-translate-y-1.5 group cursor-pointer">
              <div
                class="relative h-36 bg-gradient-to-br from-amber-100 via-emerald-50 to-teal-100 dark:from-[#232D26] dark:via-[#1B281F] dark:to-[#152B1C] p-4 flex flex-col justify-between overflow-hidden">
                <div
                  class="absolute inset-0 opacity-30 dark:opacity-10 flex items-center justify-center pointer-events-none">
                  <svg class="w-48 h-48 text-[#005506] dark:text-[#86EFAC]" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 3L1 9l11 6 9-4.91V17h2V9L12 3zM5 13.18v4l7 3.82 7-3.82v-4L12 17l-7-3.82z" />
                  </svg>
                </div>

                <div class="relative z-10 flex items-center justify-between">
                  <div
                    class="w-7 h-7 rounded-full bg-[#005506] dark:bg-[#86EFAC] text-white dark:text-[#232D26] font-extrabold text-xs flex items-center justify-center shadow-md">
                    {{ String(index + 1).padStart(2, '0') }}
                  </div>
                  <div
                    class="px-3 py-1 rounded-full bg-[#005506] dark:bg-[#86EFAC] text-white dark:text-[#232D26] font-extrabold text-[10px] tracking-wider uppercase shadow-sm">
                    {{ klass.subject_code || 'SUBJECT' }}
                  </div>
                </div>

                <div class="relative z-10 flex justify-center -mb-2">
                  <div class="flex items-end gap-1.5 opacity-90">
                    <div class="w-6 h-8 bg-orange-500 rounded-t-full"></div>
                    <div class="w-6 h-10 bg-amber-400 rounded-t-full"></div>
                    <div class="w-6 h-12 bg-[#005506] dark:bg-[#86EFAC] rounded-t-full"></div>
                    <div class="w-6 h-9 bg-teal-600 rounded-t-full"></div>
                  </div>
                </div>
              </div>

              <div class="p-5 flex-1 flex flex-col justify-between space-y-4">
                <div>
                  <h4
                    class="font-extrabold text-slate-900 dark:text-white text-sm leading-snug group-hover:text-[#005506] dark:group-hover:text-[#86EFAC] transition-colors line-clamp-2">
                    {{ klass.subject || 'Untitled Subject' }}
                  </h4>
                </div>

                <div
                  class="space-y-1 pt-3 border-t border-slate-100 dark:border-[#3F4F43] text-[11px] font-semibold text-slate-400 dark:text-slate-400">
                  <p class="truncate">
                    <span>Section:</span>
                    <span class="text-slate-600 dark:text-slate-200 font-bold ml-1">{{ klass.section || '—' }}</span>
                  </p>
                  <p class="truncate">
                    <span>Teacher:</span>
                    <span class="text-slate-600 dark:text-slate-200 font-bold ml-1">{{ klass.teacher || '—' }}</span>
                  </p>
                </div>
              </div>
            </Link>
          </div>
        </div>

        <!-- PAGINATION -->
        <div v-if="pagination.last_page > 1"
          class="flex flex-col sm:flex-row items-center justify-between gap-3 bg-[#fbfdf9] dark:bg-[#2D3A31] rounded-2xl p-4 border border-slate-200/80 dark:border-[#3F4F43]">
          <p class="text-xs font-semibold text-slate-500 dark:text-slate-400">
            Showing <strong class="text-slate-800 dark:text-white">{{ pagination.from ?? 0 }}</strong>
            to <strong class="text-slate-800 dark:text-white">{{ pagination.to ?? 0 }}</strong>
            of <strong class="text-slate-800 dark:text-white">{{ pagination.total }}</strong> subjects
          </p>
          <div class="flex items-center gap-1.5">
            <button v-for="link in pagination.links" :key="link.label" :disabled="!link.url || link.active"
              @click="goToPage(link.url)" v-html="link.label" :class="[
                'min-w-[36px] h-9 px-3 rounded-xl text-xs font-bold transition-all',
                link.active
                  ? 'bg-[#004d08] dark:bg-[#86EFAC] text-white dark:text-[#232D26] shadow-sm'
                  : link.url
                    ? 'bg-white dark:bg-[#232D26] text-slate-700 dark:text-slate-200 border border-slate-200 dark:border-[#3F4F43] hover:bg-slate-100 dark:hover:bg-[#3F4F43]'
                    : 'bg-slate-100 dark:bg-[#232D26] text-slate-400 cursor-not-allowed'
              ]"></button>
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
import navbartop from '@/Components/navbartop.vue'
import Icon from '@/Components/Icon.vue'
import heroImage from '../../../../assets/img/local/studentsubject.png'

const props = defineProps({
  classes:       { type: Object, default: () => ({ data: [], links: [], current_page: 1, last_page: 1, total: 0 }) },
  filters:       { type: Object, default: () => ({}) },
  activeTerm:    { type: String, default: null },
  studentStrand: { type: String, default: null },
  filterOptions: { type: Object, default: () => ({ terms: [] }) },
})

const isSidebarOpen = ref(false)
const fontSizeMode  = ref('base')

const searchQuery = ref(props.filters?.search || '')
const sortBy      = ref(props.filters?.sort || 'subject')

/* ─── Data ────────────────────────────────────────────── */
const classList = computed(() => props.classes?.data ?? [])

/**
 * Only show strands the student actually has classes in.
 * Scoped to the currently loaded page of the paginator — for a
 * fully accurate list across all pages, the backend should also
 * pass a `used_strands` prop. (See backend snippet at bottom.)
 */


const pagination = computed(() => ({
  current_page: props.classes?.current_page ?? 1,
  last_page:    props.classes?.last_page ?? 1,
  total:        props.classes?.total ?? 0,
  from:         props.classes?.from ?? 0,
  to:           props.classes?.to ?? 0,
  links:        props.classes?.links ?? [],
}))

const studentStrand = computed(() =>
  props.studentStrand || classList.value[0]?.strand || '—'
)

/* ─── Search + Filter actions ─────────────────────────── */
let searchTimeout = null

function debouncedSearch() {
  clearTimeout(searchTimeout)
  searchTimeout = setTimeout(() => {
    executeSearch()
  }, 350)
}

function clearSearch() {
  searchQuery.value = ''
  executeSearch()
}

function executeSearch() {
  router.get(
    route('student.classes.index'),
    {
      search: searchQuery.value || undefined,
      sort:   sortBy.value,
    },
    {
      preserveState:  true,
      preserveScroll: true,
      replace:        true,
    }
  )
}

function goToPage(url) {
  if (!url) return
  router.visit(url, { preserveScroll: true, preserveState: true })
}
</script>

<style scoped>
.animated-stroke-text {
  color: transparent;
  -webkit-text-stroke: 1.5px #ffffff;
}

@keyframes fadeInDown {
  from {
    opacity: 0;
    transform: translateY(-20px);
  }

  to {
    opacity: 1;
    transform: translateY(0);
  }
}

@keyframes fadeSlideLeft {
  from {
    opacity: 0;
    transform: translateX(-30px);
  }

  to {
    opacity: 1;
    transform: translateX(0);
  }
}

@keyframes fadeSlideUp {
  from {
    opacity: 0;
    transform: translateY(30px);
  }

  to {
    opacity: 1;
    transform: translateY(0);
  }
}

@keyframes floatSoft {

  0%,
  100% {
    transform: translateY(0);
  }

  50% {
    transform: translateY(-4px);
  }
}

@keyframes sheenMove {
  0% {
    transform: translateX(-100%);
  }

  100% {
    transform: translateX(200%);
  }
}

@keyframes spinSlow {
  from {
    transform: rotate(0deg);
  }

  to {
    transform: rotate(360deg);
  }
}

.animate-fade-in-down {
  animation: fadeInDown 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}

.animate-fade-slide-left {
  animation: fadeSlideLeft 0.7s cubic-bezier(0.16, 1, 0.3, 1) 0.1s both;
}

.animate-fade-slide-up {
  animation: fadeSlideUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) 0.2s both;
}

.animate-float-soft {
  animation: floatSoft 3s ease-in-out infinite;
}

.animate-sheen {
  animation: sheenMove 4s ease-in-out infinite;
}

.animate-spin-slow {
  display: inline-block;
  animation: spinSlow 12s linear infinite;
}

.text-scale-sm :deep(.text-xs) {
  font-size: 0.65rem !important;
  line-height: 0.85rem !important;
}

.text-scale-sm :deep(.text-sm) {
  font-size: 0.75rem !important;
  line-height: 1rem !important;
}

.text-scale-sm :deep(.text-base) {
  font-size: 0.875rem !important;
  line-height: 1.25rem !important;
}

.text-scale-lg :deep(.text-xs) {
  font-size: 0.875rem !important;
  line-height: 1.25rem !important;
}

.text-scale-lg :deep(.text-sm) {
  font-size: 1rem !important;
  line-height: 1.5rem !important;
}

.text-scale-lg :deep(.text-base) {
  font-size: 1.125rem !important;
  line-height: 1.75rem !important;
}
</style>