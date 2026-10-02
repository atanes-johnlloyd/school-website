<template>

  <Head title="My Classes & Section Rosters - Salawag LMS" />

  <AuthenticatedLayout searchPlaceholder="Search learners (LRN), strand records, advisories..">

    <div class="relative z-10 px-3 sm:px-6 md:px-10 pb-16 sm:pb-24 space-y-4 sm:space-y-6 flex-1 mt-2">

      <!-- HERO BANNER -->
      <div v-observe
        class="relative w-full rounded-2xl sm:rounded-3xl overflow-hidden shadow-lg border border-[#006907]/20 dark:border-none min-h-[200px] sm:min-h-[220px] flex flex-col justify-center anim-fade-down">
        <img :src="heroImage" alt="Classes Hub Background"
          class="absolute inset-0 w-full h-full object-cover z-0 object-center" />
        <div class="absolute inset-0 bg-[#004d05]/85 dark:bg-[#152B1C]/90 animate-overlay z-0 mix-blend-multiply"></div>

        <div class="relative z-10 p-4 sm:p-8 md:p-10 flex flex-col justify-center">
          <div class="flex flex-wrap items-center gap-2 sm:gap-2.5 mb-3 sm:mb-4">
            <span class="bg-[#005506] text-white border border-emerald-400/30 text-[10px] sm:text-[11px] md:text-xs font-bold px-2.5 sm:px-3.5 py-1 sm:py-1.5 rounded-full shadow-sm flex items-center gap-1.5">
              <span>🏛️</span> DepEd Region IV-A • SDO Dasmariñas
            </span>
            <span class="bg-white/20 backdrop-blur-md text-white border border-white/20 text-[10px] sm:text-[11px] md:text-xs font-semibold px-2.5 sm:px-3.5 py-1 sm:py-1.5 rounded-full shadow-sm flex items-center gap-1.5">
              <span>📅</span> {{ activeTerm || 'No active term' }}
            </span>
          </div>

          <div class="space-y-1.5 sm:space-y-2 max-w-3xl">
            <h2 class="text-xl sm:text-3xl md:text-4xl font-extrabold text-white tracking-tight">
              My Classes & Section Rosters Hub
            </h2>
            <p class="text-white/90 text-xs sm:text-sm md:text-base leading-relaxed font-medium">
              Manage section registries, track DepEd Learner Reference Numbers (LRN), inspect quarterly transmutation
              metrics, and dispatch real-time parent advisories.
            </p>
          </div>
        </div>
      </div>

      <div class="space-y-4 sm:space-y-6 pt-2 sm:pt-4">

        <!-- AGGREGATE SNAPSHOT -->
        <div v-observe class="anim-slide-up grid grid-cols-1 sm:grid-cols-3 gap-3.5 sm:gap-5" style="animation-delay: 50ms;">
          <div class="bg-white dark:bg-[#2D3A31] rounded-2xl sm:rounded-3xl p-4 sm:p-6 shadow-sm border border-slate-200/60 dark:border-[#3F4F43] flex items-center justify-between">
            <div class="space-y-0.5">
              <span class="text-[10px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400">Total Classes</span>
              <div class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white">{{ totals.classes || 0 }}</div>
              <span class="text-[11px] font-medium text-slate-500">{{ activeTerm || 'This term' }}</span>
            </div>
            <div class="w-10 h-10 rounded-xl bg-emerald-50 dark:bg-emerald-950/60 text-[#006907] dark:text-[#86EFAC] flex items-center justify-center shrink-0 border border-emerald-100 dark:border-emerald-900/40">
              <Icon icon="academic-cap" size="md" />
            </div>
          </div>

          <div class="bg-white dark:bg-[#2D3A31] rounded-2xl sm:rounded-3xl p-4 sm:p-6 shadow-sm border border-slate-200/60 dark:border-[#3F4F43] flex items-center justify-between">
            <div class="space-y-0.5">
              <span class="text-[10px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400">Total Students</span>
              <div class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white">{{ totals.students || 0 }}</div>
              <span class="text-[11px] font-medium text-slate-500">Learners enrolled</span>
            </div>
            <div class="w-10 h-10 rounded-xl bg-amber-50 dark:bg-amber-950/60 text-amber-800 dark:text-amber-300 flex items-center justify-center shrink-0 border border-amber-100 dark:border-amber-900/40">
              <Icon icon="users" size="md" />
            </div>
          </div>

          <div class="bg-white dark:bg-[#2D3A31] rounded-2xl sm:rounded-3xl p-4 sm:p-6 shadow-sm border border-slate-200/60 dark:border-[#3F4F43] flex items-center justify-between">
            <div class="space-y-0.5">
              <span class="text-[10px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400">Assignments</span>
              <div class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white">{{ totals.assignments || 0 }}</div>
              <span class="text-[11px] font-medium text-slate-500">Across all classes</span>
            </div>
            <div class="w-10 h-10 rounded-xl bg-blue-50 dark:bg-blue-950/60 text-blue-700 dark:text-blue-300 flex items-center justify-center shrink-0 border border-blue-100 dark:border-blue-900/40">
              <Icon icon="clipboard-list" size="md" />
            </div>
          </div>
        </div>

        <!-- FILTERS -->
        <div v-observe
          class="anim-slide-up bg-white dark:bg-[#2D3A31] rounded-2xl sm:rounded-3xl p-3.5 sm:p-5 shadow-sm border border-slate-200/60 dark:border-[#3F4F43] flex flex-col lg:flex-row lg:items-center justify-between gap-3 sm:gap-4"
          style="animation-delay: 100ms;">

          <div class="flex flex-wrap items-center gap-1.5 sm:gap-2 w-full lg:w-auto">
            <button @click="setStrand(null)"
              :class="[
                'text-[11px] sm:text-xs font-black px-3 sm:px-4 py-1.5 sm:py-2 rounded-xl shadow-sm transition-all shrink-0',
                filters.strand_id === null
                  ? 'bg-[#005506] dark:bg-[#86EFAC] text-white dark:text-[#232D26]'
                  : 'bg-[#F9F7F1] dark:bg-[#232D26] text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-[#3F4F43] border border-slate-200/80 dark:border-[#3F4F43]'
              ]">
              All Classes ({{ totals.classes || 0 }})
            </button>

            <button v-for="s in availableStrands" :key="s.id" @click="setStrand(s.id)"
              :class="[
                'text-[11px] sm:text-xs font-bold px-3 sm:px-4 py-1.5 sm:py-2 rounded-xl transition-all shrink-0',
                filters.strand_id === s.id
                  ? 'bg-[#005506] dark:bg-[#86EFAC] text-white dark:text-[#232D26] shadow-sm font-black'
                  : 'bg-[#F9F7F1] dark:bg-[#232D26] text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-[#3F4F43] border border-slate-200/80 dark:border-[#3F4F43]'
              ]">
              {{ s.code || s.name }}
            </button>
          </div>

          <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 sm:gap-3 shrink-0 w-full lg:w-auto">
            <div class="relative">
              <select v-model="filters.term_id" @change="applyFilters"
                class="w-full sm:w-auto bg-[#F9F7F1] dark:bg-[#232D26] text-slate-800 dark:text-slate-200 text-xs font-extrabold px-3 py-2.5 pr-8 rounded-xl border border-slate-200/80 dark:border-[#3F4F43] focus:outline-none focus:ring-2 focus:ring-[#005506] appearance-none cursor-pointer">
                <option :value="null">All Terms</option>
                <option v-for="t in filterOptions.terms" :key="t.id" :value="t.id">{{ t.name }}</option>
              </select>
              <svg class="w-3 h-3 fill-current text-slate-400 absolute right-3 top-3.5 pointer-events-none" viewBox="0 0 24 24">
                <path d="M7 10l5 5 5-5z" />
              </svg>
            </div>

            <div class="relative w-full sm:w-64">
              <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                <Icon icon="search" size="sm" />
              </span>
              <input v-model="filters.search" @keydown.enter="applyFilters" type="text" placeholder="Search subject or section..."
                class="w-full pl-9 pr-3 py-2.5 rounded-xl bg-[#F9F7F1] dark:bg-[#232D26] border border-slate-200/80 dark:border-[#3F4F43] focus:outline-none focus:ring-2 focus:ring-[#005506] text-xs font-semibold text-slate-700 dark:text-slate-200" />
            </div>

            <button @click="applyFilters"
              class="bg-[#005506] hover:bg-[#004105] text-white text-xs font-black px-4 py-2.5 rounded-xl shadow-sm transition-all active:scale-95 shrink-0">
              Search
            </button>
          </div>
        </div>

        <!-- CLASS CARDS -->
        <div v-if="classes.length"
          class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 sm:gap-6">
          <div v-for="(klass, idx) in classes" :key="klass.id"
            v-observe
            :style="{ animationDelay: `${(idx + 1) * 80}ms` }"
            class="anim-slide-up bg-white dark:bg-[#2D3A31] rounded-2xl sm:rounded-3xl p-4 sm:p-6 shadow-sm border border-slate-200/60 dark:border-[#3F4F43] flex flex-col justify-between space-y-4 sm:space-y-5 hover:-translate-y-1 hover:shadow-md transition-all">

            <div class="space-y-3 sm:space-y-4">
              <div class="flex items-center justify-between gap-2">
                <div class="flex items-center gap-1.5">
                  <span class="bg-emerald-100/80 dark:bg-emerald-950/60 text-[#005506] dark:text-[#86EFAC] text-[10px] font-black px-2 sm:px-2.5 py-0.5 rounded-md border border-emerald-200/60 dark:border-emerald-900/40">
                    {{ klass.strand_code || 'CORE' }}
                  </span>
                  <span class="bg-amber-100/80 dark:bg-amber-950/60 text-amber-900 dark:text-amber-300 text-[10px] font-bold px-2 py-0.5 rounded-md">
                    G{{ klass.grade_level || '—' }}
                  </span>
                </div>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">{{ klass.subject_code }}</span>
              </div>

              <div class="space-y-0.5">
                <h4 class="text-sm sm:text-base font-black text-slate-900 dark:text-white truncate">
                  {{ klass.subject || 'Untitled Subject' }}
                </h4>
                <h3 class="text-base sm:text-lg font-black text-[#005506] dark:text-[#86EFAC] truncate">
                  {{ klass.section || '—' }}
                </h3>
              </div>

              <div class="bg-[#F9F7F1] dark:bg-[#232D26] p-3 sm:p-4 rounded-xl sm:rounded-2xl border border-slate-200/80 dark:border-[#3F4F43] space-y-2.5 text-xs">
                <div class="flex items-start gap-2 text-slate-700 dark:text-slate-300 font-medium">
                  <Icon icon="users" size="xs" class="text-[#005506] dark:text-[#86EFAC] shrink-0 mt-0.5" />
                  <span><strong>{{ klass.students_count || 0 }}</strong> enrolled learners</span>
                </div>
                <div class="flex items-start gap-2 text-slate-700 dark:text-slate-300 font-medium">
                  <Icon icon="clipboard-list" size="xs" class="text-[#005506] dark:text-[#86EFAC] shrink-0 mt-0.5" />
                  <span><strong>{{ klass.assignments_count || 0 }}</strong> assignments</span>
                </div>
                <div class="flex items-start gap-2 text-slate-700 dark:text-slate-300 font-medium">
                  <Icon icon="calendar" size="xs" class="text-[#005506] dark:text-[#86EFAC] shrink-0 mt-0.5" />
                  <span>{{ klass.term || '—' }}</span>
                </div>
              </div>

              <div class="flex items-center justify-between text-[11px]">
                <span class="text-slate-500 font-medium">Status</span>
                <span :class="klass.is_published
                  ? 'bg-emerald-100 dark:bg-emerald-950/60 text-[#005506] dark:text-[#86EFAC]'
                  : 'bg-slate-100 dark:bg-[#232D26] text-slate-500 dark:text-slate-400'"
                  class="text-[10px] font-black px-2 py-0.5 rounded-full uppercase tracking-wider">
                  {{ klass.is_published ? 'Published' : 'Draft' }}
                </span>
              </div>
            </div>

            <div class="space-y-2 pt-2 border-t border-slate-100 dark:border-[#3F4F43]">
              <Link :href="route('teacher.classes.show', klass.id)"
                class="w-full bg-[#005506] dark:bg-[#86EFAC] text-white dark:text-[#232D26] hover:bg-[#004105] py-2 sm:py-2.5 rounded-xl text-xs font-black shadow-sm flex items-center justify-center gap-2 transition-all">
                <Icon icon="users" size="xs" />
                View Details
              </Link>
              <div class="grid grid-cols-2 gap-2">
                <Link :href="route('teacher.classes.gradebook.show', klass.id)"
                  class="bg-[#F9F7F1] dark:bg-[#232D26] hover:bg-slate-100 dark:hover:bg-[#3F4F43] text-slate-700 dark:text-slate-200 text-[11px] sm:text-xs font-bold py-1.5 sm:py-2 rounded-xl border border-slate-200/80 dark:border-[#3F4F43] flex items-center justify-center gap-1.5 transition-colors">
                  Gradebook
                </Link>
                <Link :href="route('teacher.classes.attendance.index', klass.id)"
                  class="bg-[#F9F7F1] dark:bg-[#232D26] hover:bg-slate-100 dark:hover:bg-[#3F4F43] text-slate-700 dark:text-slate-200 text-[11px] sm:text-xs font-bold py-1.5 sm:py-2 rounded-xl border border-slate-200/80 dark:border-[#3F4F43] flex items-center justify-center gap-1.5 transition-colors">
                  Attendance
                </Link>
              </div>
            </div>
          </div>
        </div>

        <!-- EMPTY -->
        <div v-else
          class="bg-white dark:bg-[#2D3A31] rounded-2xl sm:rounded-3xl p-12 shadow-sm border border-dashed border-slate-300 dark:border-[#3F4F43] text-center space-y-3">
          <Icon icon="book-open" size="xl" class="text-slate-400 mx-auto" />
          <p class="text-sm font-bold text-slate-800 dark:text-white">
            {{ filters.search || filters.strand_id ? 'No classes match your filters' : 'No classes assigned yet' }}
          </p>
          <p class="text-xs text-slate-500 dark:text-slate-400">
            {{ filters.search || filters.strand_id ? 'Try clearing your search or filter.' : 'Classes you teach this term will appear here.' }}
          </p>
          <button v-if="filters.search || filters.strand_id" @click="resetFilters"
            class="bg-[#005506] dark:bg-[#86EFAC] text-white dark:text-[#232D26] text-xs font-bold px-4 py-2 rounded-xl hover:bg-[#004105] transition-colors">
            Reset Filters
          </button>
        </div>

        <!-- PAGINATION -->
        <div v-if="pagination.last_page > 1"
          class="flex flex-col sm:flex-row items-center justify-between gap-3 bg-white dark:bg-[#2D3A31] rounded-2xl p-4 border border-slate-200/80 dark:border-[#3F4F43]">
          <p class="text-[11px] sm:text-xs text-slate-500 dark:text-slate-400 font-medium text-center sm:text-left">
            Showing <strong class="text-slate-800 dark:text-white">{{ pagination.from || 0 }}</strong>
            to <strong class="text-slate-800 dark:text-white">{{ pagination.to || 0 }}</strong>
            of <strong class="text-slate-800 dark:text-white">{{ pagination.total }}</strong> classes
          </p>

          <div class="flex items-center gap-1 sm:gap-1.5">
            <button v-for="link in pagination.links" :key="link.label"
              :disabled="!link.url || link.active"
              @click="goToPage(link.url)"
              v-html="link.label"
              :class="[
                'min-w-[34px] h-9 px-3 rounded-xl text-xs font-bold transition-all',
                link.active
                  ? 'bg-[#005506] dark:bg-[#86EFAC] text-white dark:text-[#232D26] shadow-sm'
                  : link.url
                    ? 'bg-[#F9F7F1] dark:bg-[#232D26] text-slate-700 dark:text-slate-200 border border-slate-200 dark:border-[#3F4F43] hover:bg-slate-100 dark:hover:bg-[#3F4F43]'
                    : 'bg-slate-50 dark:bg-[#232D26] text-slate-300 dark:text-slate-600 cursor-not-allowed'
              ]"></button>
          </div>
        </div>

      </div>
    </div>

  </AuthenticatedLayout>

</template>

<script setup>
import { reactive, computed } from 'vue'
import { Head, Link, router } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import Icon from '@/Components/Icon.vue'
import heroImage from '../../../../assets/img/desktop-home-banner.png'

const availableStrands = computed(() => {
  const usedCodes = new Set(
    classes.value.map(c => c.strand_code).filter(Boolean)
  )
  return (props.filterOptions?.strands ?? []).filter(s => usedCodes.has(s.code))
})

const props = defineProps({
  classes:       { type: Object, default: () => ({ data: [], links: [], current_page: 1, last_page: 1, total: 0, from: 0, to: 0 }) },
  activeTerm:    { type: String, default: null },
  filters:       { type: Object, default: () => ({}) },
  filterOptions: { type: Object, default: () => ({ strands: [], terms: [] }) },
  totals:        { type: Object, default: () => ({ classes: 0, students: 0, assignments: 0 }) },
})

const classes = computed(() => props.classes?.data ?? [])

const pagination = computed(() => ({
  current_page: props.classes?.current_page ?? 1,
  last_page:    props.classes?.last_page ?? 1,
  total:        props.classes?.total ?? 0,
  from:         props.classes?.from ?? 0,
  to:           props.classes?.to ?? 0,
  links:        props.classes?.links ?? [],
}))

const filters = reactive({
  search:      props.filters?.search ?? '',
  strand_id:   props.filters?.strand_id ?? null,
  term_id:     props.filters?.term_id ?? null,
  grade_level: props.filters?.grade_level ?? null,
})

function setStrand(id) {
  filters.strand_id = id
  applyFilters()
}

function applyFilters() {
  router.get(route('teacher.classes.index'), {
    search:      filters.search || undefined,
    strand_id:   filters.strand_id ?? undefined,
    term_id:     filters.term_id ?? undefined,
    grade_level: filters.grade_level ?? undefined,
  }, { preserveState: true, replace: true })
}

function resetFilters() {
  filters.search = ''
  filters.strand_id = null
  filters.term_id = null
  filters.grade_level = null
  applyFilters()
}

function goToPage(url) {
  if (!url) return
  router.visit(url, { preserveScroll: true, preserveState: true })
}

const vObserve = {
  mounted(el) {
    el.classList.add('not-visible')
    const observer = new IntersectionObserver(([entry]) => {
      if (entry.isIntersecting) el.classList.add('is-animated')
      else el.classList.remove('is-animated')
    }, { threshold: 0.1 })
    observer.observe(el)
  },
}
</script>

<style scoped>
@keyframes pulse-opacity { 0%, 100% { opacity: 0.88; } 50% { opacity: 0.65; } }
.animate-overlay { animation: pulse-opacity 6s infinite ease-in-out; }

@keyframes slideUpFade { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
@keyframes fadeInDown { from { opacity: 0; transform: translateY(-20px); } to { opacity: 1; transform: translateY(0); } }

.not-visible { opacity: 0; }
.is-animated.anim-fade-down { animation: fadeInDown 0.7s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
.is-animated.anim-slide-up { animation: slideUpFade 0.7s cubic-bezier(0.16, 1, 0.3, 1) forwards; }

.text-scale-sm :deep(.text-xs)   { font-size: 0.65rem !important; line-height: 0.85rem !important; }
.text-scale-sm :deep(.text-sm)   { font-size: 0.75rem !important; line-height: 1rem !important; }
.text-scale-sm :deep(.text-base) { font-size: 0.875rem !important; line-height: 1.25rem !important; }
.text-scale-sm :deep(.text-lg)   { font-size: 1rem !important; line-height: 1.5rem !important; }
.text-scale-sm :deep(.text-xl)   { font-size: 1.125rem !important; line-height: 1.75rem !important; }
.text-scale-sm :deep(.text-3xl)  { font-size: 1.5rem !important; line-height: 2rem !important; }
.text-scale-sm :deep(.sm\:text-4xl) { font-size: 1.875rem !important; line-height: 2.25rem !important; }
.text-scale-sm :deep(.md\:text-5xl) { font-size: 2.25rem !important; line-height: 1 !important; }

.text-scale-lg :deep(.text-xs)   { font-size: 0.875rem !important; line-height: 1.25rem !important; }
.text-scale-lg :deep(.text-sm)   { font-size: 1rem !important; line-height: 1.5rem !important; }
.text-scale-lg :deep(.text-base) { font-size: 1.125rem !important; line-height: 1.75rem !important; }
.text-scale-lg :deep(.text-lg)   { font-size: 1.25rem !important; line-height: 1.75rem !important; }
.text-scale-lg :deep(.text-xl)   { font-size: 1.5rem !important; line-height: 2rem !important; }
.text-scale-lg :deep(.text-3xl)  { font-size: 2.25rem !important; line-height: 2.5rem !important; }
.text-scale-lg :deep(.sm\:text-4xl) { font-size: 2.75rem !important; line-height: 1 !important; }
.text-scale-lg :deep(.md\:text-5xl) { font-size: 3.5rem !important; line-height: 1 !important; }
</style>