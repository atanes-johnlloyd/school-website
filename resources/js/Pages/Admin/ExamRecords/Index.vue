<template>
  <AdminLayout>
    <div class="space-y-6 pb-10 font-['Inter']">

      <<div class="relative overflow-hidden bg-[#004d08] text-white rounded-2xl p-6 md:p-8 shadow-lg border border-emerald-900/40">
        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">

            <!-- Left: title & description -->
            <div class="min-w-0">
            <div class="flex items-center gap-2 mb-2">
                <span class="bg-amber-400/20 text-amber-300 text-xs font-medium px-2.5 py-0.5 rounded-md border border-amber-400/30">ADMISSIONS</span>
                <span class="text-emerald-200 text-xs font-normal">&bull; Exam Records</span>
            </div>
            <h1 class="font-['Anton'] text-3xl md:text-4xl tracking-wide uppercase">
                Exam <span class="text-amber-400">Records</span>
            </h1>
            <p class="text-emerald-100/80 text-sm mt-1 max-w-xl font-normal">
                Global view of every recorded entrance exam result. Filter, search, and report across sessions.
            </p>
            </div>

            <!-- Right: pass rate KPI -->
            <div class="shrink-0 flex items-center gap-4 bg-emerald-900/50 rounded-2xl border border-emerald-700/50 px-5 py-4 backdrop-blur-sm">
            <div class="w-14 h-14 rounded-2xl bg-amber-400/20 border border-amber-400/30 flex items-center justify-center text-amber-300 shrink-0">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
            </div>
            <div>
                <p class="text-[10px] font-medium uppercase tracking-wider text-emerald-200/80">Pass Rate</p>
                <p class="text-3xl font-['Anton'] tracking-wide text-white leading-none mt-1">
                {{ counts.passRate || 0 }}<span class="text-lg text-emerald-200/70">%</span>
                </p>
                <p class="text-[10px] text-emerald-200/70 mt-1">
                {{ counts.passed || 0 }} passed / {{ (counts.passed || 0) + (counts.failed || 0) }} attempted
                </p>
            </div>
            </div>

        </div>
        <div class="absolute -right-6 -bottom-8 opacity-10 text-9xl font-['Anton'] pointer-events-none select-none text-white">SCORES</div>
        </div>

      <!-- Stats -->
      <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4">

        <!-- Total -->
        <button
            @click="setResultFilter('')"
            :class="[
            'text-left p-5 rounded-2xl border shadow-xs flex items-center justify-between transition-colors cursor-pointer',
            filters.result === ''
                ? 'bg-[#004d08]/5 dark:bg-[#86EFAC]/5 border-[#004d08]/40 dark:border-[#86EFAC]/30'
                : 'bg-white dark:bg-[#2D3A31] border-gray-200/80 dark:border-[#3F4F43] hover:border-gray-300 dark:hover:border-gray-500'
            ]"
        >
            <div class="space-y-1">
            <p class="text-[10px] font-medium uppercase text-gray-400 tracking-wider">Total</p>
            <p class="text-2xl font-medium text-gray-900 dark:text-white">{{ counts.total || 0 }}</p>
            <span class="text-[10px] text-gray-500 dark:text-gray-400 font-normal">All Records</span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-gray-50 dark:bg-[#1C261E] border border-gray-100 dark:border-[#3F4F43] flex items-center justify-center text-gray-600 dark:text-gray-300 shrink-0">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
            </svg>
            </div>
        </button>

        <!-- Passed -->
        <button
            @click="setResultFilter('Passed')"
            :class="[
            'text-left p-5 rounded-2xl border shadow-xs flex items-center justify-between transition-colors cursor-pointer',
            filters.result === 'Passed'
                ? 'bg-emerald-50/60 dark:bg-emerald-950/20 border-emerald-300 dark:border-emerald-800'
                : 'bg-white dark:bg-[#2D3A31] border-gray-200/80 dark:border-[#3F4F43] hover:border-gray-300 dark:hover:border-gray-500'
            ]"
        >
            <div class="space-y-1">
            <p class="text-[10px] font-medium uppercase text-emerald-500 tracking-wider">Passed</p>
            <p class="text-2xl font-medium text-gray-900 dark:text-white">{{ counts.passed || 0 }}</p>
            <span class="text-[10px] text-emerald-600 dark:text-emerald-400 font-normal">Converted to Students</span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 dark:bg-[#1C261E] border border-emerald-100 dark:border-[#3F4F43] flex items-center justify-center text-emerald-600 dark:text-emerald-400 shrink-0">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            </div>
        </button>

        <!-- Failed -->
        <button
            @click="setResultFilter('Failed')"
            :class="[
            'text-left p-5 rounded-2xl border shadow-xs flex items-center justify-between transition-colors cursor-pointer',
            filters.result === 'Failed'
                ? 'bg-red-50/60 dark:bg-red-950/20 border-red-300 dark:border-red-800'
                : 'bg-white dark:bg-[#2D3A31] border-gray-200/80 dark:border-[#3F4F43] hover:border-gray-300 dark:hover:border-gray-500'
            ]"
        >
            <div class="space-y-1">
            <p class="text-[10px] font-medium uppercase text-red-500 tracking-wider">Failed</p>
            <p class="text-2xl font-medium text-gray-900 dark:text-white">{{ counts.failed || 0 }}</p>
            <span class="text-[10px] text-red-600 dark:text-red-400 font-normal">Did Not Pass</span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-red-50 dark:bg-[#1C261E] border border-red-100 dark:border-[#3F4F43] flex items-center justify-center text-red-600 dark:text-red-400 shrink-0">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            </div>
        </button>

        <!-- Absent -->
        <button
            @click="setResultFilter('Absent')"
            :class="[
            'text-left p-5 rounded-2xl border shadow-xs flex items-center justify-between transition-colors cursor-pointer',
            filters.result === 'Absent'
                ? 'bg-amber-50/60 dark:bg-amber-950/20 border-amber-300 dark:border-amber-800'
                : 'bg-white dark:bg-[#2D3A31] border-gray-200/80 dark:border-[#3F4F43] hover:border-gray-300 dark:hover:border-gray-500'
            ]"
        >
            <div class="space-y-1">
            <p class="text-[10px] font-medium uppercase text-amber-500 tracking-wider">Absent</p>
            <p class="text-2xl font-medium text-gray-900 dark:text-white">{{ counts.absent || 0 }}</p>
            <span class="text-[10px] text-amber-600 dark:text-amber-400 font-normal">No-Show</span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-amber-50 dark:bg-[#1C261E] border border-amber-100 dark:border-[#3F4F43] flex items-center justify-center text-amber-600 dark:text-amber-400 shrink-0">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            </div>
        </button>

        <!-- Pending -->
        <button
            @click="setResultFilter('Pending')"
            :class="[
            'text-left p-5 rounded-2xl border shadow-xs flex items-center justify-between transition-colors cursor-pointer',
            filters.result === 'Pending'
                ? 'bg-sky-50/60 dark:bg-sky-950/20 border-sky-300 dark:border-sky-800'
                : 'bg-white dark:bg-[#2D3A31] border-gray-200/80 dark:border-[#3F4F43] hover:border-gray-300 dark:hover:border-gray-500'
            ]"
        >
            <div class="space-y-1">
            <p class="text-[10px] font-medium uppercase text-sky-500 tracking-wider">Pending</p>
            <p class="text-2xl font-medium text-gray-900 dark:text-white">{{ counts.pending || 0 }}</p>
            <span class="text-[10px] text-sky-600 dark:text-sky-400 font-normal">Awaiting Score</span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-sky-50 dark:bg-[#1C261E] border border-sky-100 dark:border-[#3F4F43] flex items-center justify-center text-sky-600 dark:text-sky-400 shrink-0">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            </div>
        </button>

        </div>

      <!-- Main card -->
      <div class="bg-white dark:bg-[#2D3A31] rounded-2xl border border-gray-200/80 dark:border-[#3F4F43] overflow-hidden">

        <div class="p-4 sm:p-5 border-b border-gray-100 dark:border-[#3F4F43] bg-gray-50/50 dark:bg-[#232D26]/50 flex flex-col lg:flex-row gap-3 justify-between lg:items-center">
          <div>
            <h2 class="text-xs font-medium text-gray-900 dark:text-white uppercase tracking-wider">Records Directory</h2>
            <p class="text-[11px] text-gray-500 dark:text-gray-400 font-normal">Search and filter exam results</p>
          </div>

          <div class="flex flex-wrap sm:flex-nowrap items-center gap-2 w-full lg:w-auto lg:justify-end">
            <select v-model="filters.exam_id" @change="fetchList()"
                    class="appearance-none pl-3 pr-8 py-2 text-xs bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white rounded-xl focus:ring-2 focus:ring-[#004d08] focus:outline-none font-normal cursor-pointer max-w-[200px] truncate">
              <option value="">All Exams</option>
              <option v-for="e in exams" :key="e.id" :value="e.id">{{ e.exam_name }}</option>
            </select>

            <select v-model="filters.grade_level" @change="fetchList()"
                    class="appearance-none pl-3 pr-8 py-2 text-xs bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white rounded-xl focus:ring-2 focus:ring-[#004d08] focus:outline-none font-normal cursor-pointer">
              <option value="">All Grades</option>
              <option value="11">Grade 11</option>
              <option value="12">Grade 12</option>
            </select>

            <select v-model="filters.strand_id" @change="fetchList()"
                    class="appearance-none pl-3 pr-8 py-2 text-xs bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white rounded-xl focus:ring-2 focus:ring-[#004d08] focus:outline-none font-normal cursor-pointer max-w-[160px] truncate">
              <option value="">All Strands</option>
              <option v-for="s in strands" :key="s.id" :value="s.id">{{ s.code }}</option>
            </select>

            <div class="relative w-full sm:w-56">
              <svg class="w-4 h-4 absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
              </svg>
              <input v-model="filters.search" @input="debouncedFetch" type="text" placeholder="Search name, LRN..."
                     class="w-full pl-10 pr-8 py-2 text-xs bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white placeholder-gray-400 rounded-xl focus:ring-2 focus:ring-[#004d08] focus:outline-none font-normal" />
            </div>

            <button v-if="hasActiveFilters" @click="clearFilters"
                    class="px-3 py-2 text-xs font-normal text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-white/5 rounded-xl border border-gray-200 dark:border-[#3F4F43] transition-colors cursor-pointer shrink-0">
              Reset
            </button>
          </div>
        </div>

        <div class="overflow-x-auto">
          <table class="w-full text-left border-collapse">
            <thead>
              <tr class="border-b border-gray-100 dark:border-[#3F4F43] bg-gray-50/50 dark:bg-[#232D26]/50 text-[10px] font-medium uppercase text-gray-400 tracking-wider">
                <th class="py-3.5 px-5">
                  <button @click="setSort('student')" class="inline-flex items-center gap-1 hover:text-gray-700 dark:hover:text-gray-200 transition-colors cursor-pointer">
                    Applicant <SortIcon field="student" />
                  </button>
                </th>
                <th class="py-3.5 px-5">Exam</th>
                <th class="py-3.5 px-5 text-center">
                  <button @click="setSort('score')" class="inline-flex items-center gap-1 hover:text-gray-700 dark:hover:text-gray-200 transition-colors cursor-pointer">
                    Score <SortIcon field="score" />
                  </button>
                </th>
                <th class="py-3.5 px-5 text-center">
                  <button @click="setSort('result')" class="inline-flex items-center gap-1 hover:text-gray-700 dark:hover:text-gray-200 transition-colors cursor-pointer">
                    Result <SortIcon field="result" />
                  </button>
                </th>
                <th class="py-3.5 px-5">Recorded</th>
                <th class="py-3.5 px-5 text-right">Open</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-[#3F4F43] text-xs">
              <tr v-for="r in records.data" :key="r.id" class="hover:bg-gray-50/80 dark:hover:bg-white/5 transition-colors">
                <td class="py-4 px-5">
                  <p class="font-medium text-gray-900 dark:text-white">{{ r.name }}</p>
                  <p class="text-[10px] text-gray-400">
                    <span class="font-mono">{{ r.reference }}</span>
                    <span v-if="r.lrn"> &bull; LRN {{ r.lrn }}</span>
                  </p>
                </td>
                <td class="py-4 px-5">
                  <p class="text-xs text-gray-900 dark:text-white">{{ r.exam_name }}</p>
                  <p class="text-[10px] text-gray-400">{{ formatDate(r.exam_date) }}</p>
                </td>
                <td class="py-4 px-5 text-center text-gray-900 dark:text-white font-medium">
                  {{ r.score ?? '—' }}
                </td>
                <td class="py-4 px-5 text-center">
                  <span class="px-2.5 py-1 rounded-full text-[10px] font-normal uppercase tracking-wider inline-flex items-center gap-1.5 border" :class="resultBadge(r.result)">
                    <span :class="['w-1.5 h-1.5 rounded-full', resultDot(r.result)]"></span>
                    {{ r.result }}
                  </span>
                </td>
                <td class="py-4 px-5">
                  <p class="text-[10px] text-gray-500 dark:text-gray-400">{{ formatDateTime(r.recorded_at) }}</p>
                  <p v-if="r.recorder" class="text-[10px] text-gray-400">by {{ r.recorder }}</p>
                </td>
                <td class="py-4 px-5 text-right">
                  <Link :href="`/admin/applicants?search=${r.reference}`"
                        class="px-2.5 py-1 text-[10px] font-normal text-sky-700 dark:text-sky-300 bg-sky-50 hover:bg-sky-100 dark:bg-sky-950/50 rounded-lg border border-sky-200 dark:border-sky-800 transition-colors cursor-pointer inline-block">
                    View Applicant
                  </Link>
                </td>
              </tr>
              <tr v-if="!records.data || records.data.length === 0">
                <td colspan="6" class="py-12 text-center text-gray-400 dark:text-gray-500 font-normal">
                  <p class="text-xs">No exam records yet.</p>
                  <p class="text-[11px] mt-0.5">Record results from the Entrance Exams tab.</p>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <div v-if="records.total > 0" class="px-5 py-3.5 bg-gray-50/50 dark:bg-[#232D26]/50 border-t border-gray-100 dark:border-[#3F4F43] flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-gray-500 dark:text-gray-400">
          <div class="flex items-center gap-3">
            <span>
              Showing <span class="font-semibold text-gray-900 dark:text-white">{{ records.from || 0 }}</span>
              to <span class="font-semibold text-gray-900 dark:text-white">{{ records.to || 0 }}</span>
              of <span class="font-semibold text-gray-900 dark:text-white">{{ records.total }}</span> entries
            </span>
            <select v-model.number="filters.per_page" @change="changePageSize"
                    class="appearance-none pl-2 pr-7 py-1 text-[11px] bg-white dark:bg-[#2D3A31] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white rounded-lg focus:ring-2 focus:ring-[#004d08] focus:outline-none cursor-pointer">
              <option :value="10">10</option>
              <option :value="25">25</option>
              <option :value="50">50</option>
              <option :value="100">100</option>
            </select>
          </div>
          <div class="flex items-center gap-1">
            <button @click="changePage(records.current_page - 1)" :disabled="records.current_page <= 1"
                    class="px-3 py-1.5 rounded-lg border border-gray-200 dark:border-[#3F4F43] bg-white dark:bg-[#2D3A31] text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-white/5 disabled:opacity-40 cursor-pointer disabled:cursor-not-allowed transition-colors">
              Previous
            </button>
            <span class="px-3 py-1.5 font-medium text-gray-900 dark:text-white">
              Page {{ records.current_page }} of {{ records.last_page }}
            </span>
            <button @click="changePage(records.current_page + 1)" :disabled="records.current_page >= records.last_page"
                    class="px-3 py-1.5 rounded-lg border border-gray-200 dark:border-[#3F4F43] bg-white dark:bg-[#2D3A31] text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-white/5 disabled:opacity-40 cursor-pointer disabled:cursor-not-allowed transition-colors">
              Next
            </button>
          </div>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref, reactive, computed, onMounted, h } from 'vue'
import axios from 'axios'
import { Link } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'

const props = defineProps({
  exams:   { type: Array, default: () => [] },
  strands: { type: Array, default: () => [] },
})

const emptyPaginator = () => ({ data: [], current_page: 1, last_page: 1, total: 0, from: 0, to: 0 })

const records = ref(emptyPaginator())
const counts  = ref({ total: 0, passed: 0, failed: 0, absent: 0, pending: 0, passRate: 0 })

const filters = reactive({
  search: '',
  result: '',
  exam_id: '',
  grade_level: '',
  strand_id: '',
  date_from: '',
  date_to: '',
  per_page: 10,
  page: 1,
  sort_by: 'exam_date',
  sort_dir: 'desc',
})

let searchTimer = null

const hasActiveFilters = computed(() =>
  filters.search !== '' || filters.result !== '' || filters.exam_id !== '' ||
  filters.grade_level !== '' || filters.strand_id !== ''
)

const fetchList = async () => {
  try {
    const params = { ...filters }
    Object.keys(params).forEach(k => { if (params[k] === '') delete params[k] })
    const { data } = await axios.get('/admin/exam-records/list', { params })
    const p = data?.records
    records.value = p && Array.isArray(p.data) ? p : emptyPaginator()
    counts.value = data?.counts ?? counts.value
  } catch (e) {
    console.error('Failed to load records:', e)
    records.value = emptyPaginator()
  }
}

const debouncedFetch = () => {
  clearTimeout(searchTimer)
  searchTimer = setTimeout(() => { filters.page = 1; fetchList() }, 300)
}

const setResultFilter = (result) => {
  filters.result = result
  filters.page = 1
  fetchList()
}

const setSort = (field) => {
  if (filters.sort_by === field) filters.sort_dir = filters.sort_dir === 'asc' ? 'desc' : 'asc'
  else { filters.sort_by = field; filters.sort_dir = 'asc' }
  filters.page = 1
  fetchList()
}

const changePage = (p) => {
  if (p < 1 || p > records.value.last_page) return
  filters.page = p
  fetchList()
}

const changePageSize = () => { filters.page = 1; fetchList() }

const clearFilters = () => {
  Object.assign(filters, {
    search: '', result: '', exam_id: '', grade_level: '', strand_id: '',
    date_from: '', date_to: '', page: 1,
  })
  fetchList()
}

const SortIcon = (props) => {
  const active = filters.sort_by === props.field
  const dir = filters.sort_dir
  return h('svg', {
    class: ['w-3 h-3 transition-colors', active ? 'text-[#004d08] dark:text-[#86EFAC]' : 'text-gray-300 dark:text-gray-600'],
    fill: 'none', stroke: 'currentColor', viewBox: '0 0 24 24', 'stroke-width': 2,
  }, [
    h('path', { 'stroke-linecap': 'round', 'stroke-linejoin': 'round', d: 'M5 15l7-7 7 7', opacity: active && dir === 'asc' ? 1 : 0.35 }),
    h('path', { 'stroke-linecap': 'round', 'stroke-linejoin': 'round', d: 'M19 9l-7 7-7-7', opacity: active && dir === 'desc' ? 1 : 0.35 }),
  ])
}
SortIcon.props = { field: { type: String, required: true } }

const cardClass = (active, color = 'gray') => [
  'text-left p-4 rounded-2xl border shadow-xs transition-colors cursor-pointer',
  active
    ? `bg-${color}-50/60 dark:bg-${color}-950/20 border-${color}-300 dark:border-${color}-800`
    : 'bg-white dark:bg-[#2D3A31] border-gray-200/80 dark:border-[#3F4F43] hover:border-gray-300 dark:hover:border-gray-500',
].join(' ')

const resultBadge = (r) => ({
  Passed:          'bg-emerald-100 dark:bg-emerald-950/60 text-emerald-800 dark:text-emerald-300 border-emerald-200 dark:border-emerald-800',
  Failed:          'bg-red-50 dark:bg-red-950/50 text-red-700 dark:text-red-300 border-red-200 dark:border-red-800',
  Absent:          'bg-amber-50 dark:bg-amber-950/50 text-amber-700 dark:text-amber-300 border-amber-200 dark:border-amber-800',
  Pending:         'bg-sky-50 dark:bg-sky-950/50 text-sky-700 dark:text-sky-300 border-sky-200 dark:border-sky-800',
  'For Interview': 'bg-violet-50 dark:bg-violet-950/50 text-violet-700 dark:text-violet-300 border-violet-200 dark:border-violet-800',
}[r] || 'bg-gray-100 dark:bg-[#232D26] text-gray-600 dark:text-gray-400 border-gray-200 dark:border-[#3F4F43]')

const resultDot = (r) => ({
  Passed: 'bg-emerald-500', Failed: 'bg-red-500', Absent: 'bg-amber-500',
  Pending: 'bg-sky-500', 'For Interview': 'bg-violet-500',
}[r] || 'bg-gray-400')

const formatDate = (iso) => {
  if (!iso) return '—'
  try { return new Date(iso).toLocaleDateString('en-PH', { month: 'short', day: 'numeric', year: 'numeric' }) }
  catch { return iso }
}

const formatDateTime = (iso) => {
  if (!iso) return '—'
  try { return new Date(iso).toLocaleString('en-PH', { month: 'short', day: 'numeric', hour: 'numeric', minute: '2-digit' }) }
  catch { return iso }
}

onMounted(() => { fetchList() })
</script>