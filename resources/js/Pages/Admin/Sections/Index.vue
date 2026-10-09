<template>
  <AdminLayout>
    <div class="space-y-6 pb-10 font-['Inter']">

      <!-- Hero Banner -->
      <div
        class="relative overflow-hidden w-full rounded-2xl sm:rounded-3xl shadow-lg border border-[#006907]/20 dark:border-none min-h-[220px] sm:min-h-[240px] flex flex-col justify-center p-4 sm:p-8 md:p-10">
        <!-- Background Image Layer (Classroom / Grouping Layout Theme) -->
        <img
          :src="heroImage || 'https://images.unsplash.com/photo-1580582932707-520aed937b7b?q=80&w=1600&auto=format&fit=crop'"
          alt="Class Sections Hero" class="absolute inset-0 w-full h-full object-cover z-0 object-center" />

        <!-- Animated Dark Green Overlay -->
        <div class="absolute inset-0 bg-[#004d05]/85 dark:bg-[#152B1C]/90 animate-overlay z-0 mix-blend-multiply"></div>

        <!-- Ambient Light Glow Highlights -->
        <div
          class="absolute -right-10 -bottom-10 w-72 h-72 rounded-full bg-emerald-300/20 blur-3xl pointer-events-none z-0">
        </div>
        <div class="absolute right-24 -top-16 w-80 h-80 rounded-full bg-[#F9C20C]/20 blur-3xl pointer-events-none z-0">
        </div>

        <!-- Content Container -->
        <div class="relative z-10 w-full flex flex-col md:flex-row md:items-center justify-between gap-4">
          <div class="space-y-2 sm:space-y-3 max-w-2xl">
            <!-- Top Capsule Badges -->
            <div class="flex flex-wrap items-center gap-2 sm:gap-2.5">
              <span
                class="inline-flex items-center gap-1.5 bg-[#F9C20C] text-[#2C3E2D] font-black text-[10px] sm:text-xs px-3 sm:px-4 py-1 sm:py-1.5 rounded-full shadow-sm tracking-wide uppercase">
                <span>🏫</span> ACADEMICS
              </span>

              <span
                class="inline-flex items-center gap-2 bg-black/30 dark:bg-black/50 backdrop-blur-md text-white border border-white/20 text-[10px] sm:text-xs font-semibold px-3 sm:px-4 py-1 sm:py-1.5 rounded-full shadow-sm">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                Sections
              </span>
            </div>

            <!-- Title & Subtitle -->
            <div class="space-y-1 sm:space-y-1.5">
              <h1
                class="font-['Anton'] text-2xl sm:text-4xl md:text-5xl tracking-wide uppercase flex items-center gap-2 select-none flex-wrap leading-none text-white drop-shadow-md">
                <span>CLASS</span>
                <span class="text-[#F9C20C] drop-shadow-[0_2px_8px_rgba(249,194,12,0.4)]">SECTIONS</span>
              </h1>
              <p class="text-white/90 text-xs sm:text-sm md:text-base leading-relaxed font-medium">
                Manage class groupings, assign advisers, and track enrollment capacity per section.
              </p>
            </div>
          </div>

          <!-- Action Button -->
          <div class="flex items-center shrink-0 pt-2 md:pt-0">
            <button @click="openCreateModal"
              class="inline-flex items-center justify-center gap-2 bg-[#F9C20C] hover:bg-[#e0ae0a] text-[#2C3E2D] font-black px-4 sm:px-5 py-2.5 sm:py-3 rounded-xl transition-all shadow-md active:scale-95 text-xs sm:text-sm cursor-pointer">
              <span class="text-base sm:text-lg leading-none">+</span>
              <span>New Section</span>
            </button>
          </div>
        </div>
      </div>

      <!-- Stat Cards -->
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

        <div
          class="text-left p-5 rounded-2xl border shadow-xs flex items-center justify-between bg-white dark:bg-[#2D3A31] border-gray-200/80 dark:border-[#3F4F43]">
          <div class="space-y-1">
            <p class="text-[10px] font-medium uppercase text-gray-400 tracking-wider">Total Sections</p>
            <p class="text-2xl font-medium text-gray-900 dark:text-white">{{ counts.total_sections || 0 }}</p>
            <span class="text-[10px] text-gray-500 dark:text-gray-400 font-normal">This School Year</span>
          </div>
          <div
            class="w-12 h-12 rounded-2xl bg-emerald-50 dark:bg-[#1C261E] border border-emerald-100 dark:border-[#3F4F43] flex items-center justify-center text-emerald-700 dark:text-[#86EFAC] shrink-0">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round"
                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5m0 0h4m-4 0V11m0 0h4m-4 0H9m4 0v10m-4-10v10" />
            </svg>
          </div>
        </div>

        <div
          class="text-left p-5 rounded-2xl border shadow-xs flex items-center justify-between bg-white dark:bg-[#2D3A31] border-gray-200/80 dark:border-[#3F4F43]">
          <div class="space-y-1">
            <p class="text-[10px] font-medium uppercase text-sky-500 tracking-wider">Total Capacity</p>
            <p class="text-2xl font-medium text-gray-900 dark:text-white">{{ counts.total_capacity || 0 }}</p>
            <span class="text-[10px] text-sky-600 dark:text-sky-400 font-normal">Seat Slots</span>
          </div>
          <div
            class="w-12 h-12 rounded-2xl bg-sky-50 dark:bg-[#1C261E] border border-sky-100 dark:border-[#3F4F43] flex items-center justify-center text-sky-600 dark:text-sky-400 shrink-0">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round"
                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
          </div>
        </div>

        <div
          class="text-left p-5 rounded-2xl border shadow-xs flex items-center justify-between bg-white dark:bg-[#2D3A31] border-gray-200/80 dark:border-[#3F4F43]">
          <div class="space-y-1">
            <p class="text-[10px] font-medium uppercase text-emerald-500 tracking-wider">Enrolled</p>
            <p class="text-2xl font-medium text-gray-900 dark:text-white">{{ counts.total_enrolled || 0 }}</p>
            <span class="text-[10px] text-emerald-600 dark:text-emerald-400 font-normal">Students Placed</span>
          </div>
          <div
            class="w-12 h-12 rounded-2xl bg-emerald-50 dark:bg-[#1C261E] border border-emerald-100 dark:border-[#3F4F43] flex items-center justify-center text-emerald-600 dark:text-emerald-400 shrink-0">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </div>
        </div>

        <div
          class="text-left p-5 rounded-2xl border shadow-xs flex items-center justify-between bg-white dark:bg-[#2D3A31] border-gray-200/80 dark:border-[#3F4F43]">
          <div class="space-y-1">
            <p class="text-[10px] font-medium uppercase text-amber-500 tracking-wider">Occupancy</p>
            <p class="text-2xl font-medium text-gray-900 dark:text-white">{{ counts.occupancy_rate || 0 }}%</p>
            <span class="text-[10px] text-amber-600 dark:text-amber-400 font-normal">Capacity Used</span>
          </div>
          <div
            class="w-12 h-12 rounded-2xl bg-amber-50 dark:bg-[#1C261E] border border-amber-100 dark:border-[#3F4F43] flex items-center justify-center text-amber-600 dark:text-amber-400 shrink-0">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round"
                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
            </svg>
          </div>
        </div>
      </div>

      <!-- Main Card -->
      <div
        class="bg-white dark:bg-[#2D3A31] rounded-2xl border border-gray-200/80 dark:border-[#3F4F43] shadow-xs overflow-hidden transition-colors">

        <!-- Controls -->
        <div
          class="p-4 sm:p-5 border-b border-gray-100 dark:border-[#3F4F43] bg-gray-50/50 dark:bg-[#232D26]/50 flex flex-col lg:flex-row gap-3 justify-between lg:items-center">
          <div>
            <h2 class="text-xs font-medium text-gray-900 dark:text-white uppercase tracking-wider">Sections Roster</h2>
            <p class="text-[11px] text-gray-500 dark:text-gray-400 font-normal">Search, filter, and manage class
              groupings</p>
          </div>

          <div class="flex flex-wrap sm:flex-nowrap items-center gap-2 w-full lg:w-auto lg:justify-end">

            <div class="relative">
              <select v-model="filters.school_year_id" @change="resetAndFetch()"
                class="appearance-none pl-3 pr-8 py-2 text-xs bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white rounded-xl focus:ring-2 focus:ring-[#004d08] focus:outline-none font-normal cursor-pointer">
                <option value="">All School Years</option>
                <option v-for="sy in schoolYears" :key="sy.id" :value="sy.id">{{ sy.label }}</option>
              </select>
            </div>

            <div class="relative">
              <select v-model="filters.grade_level" @change="resetAndFetch()"
                class="appearance-none pl-3 pr-8 py-2 text-xs bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white rounded-xl focus:ring-2 focus:ring-[#004d08] focus:outline-none font-normal cursor-pointer">
                <option value="">All Grades</option>
                <option value="11">Grade 11</option>
                <option value="12">Grade 12</option>
              </select>
            </div>

            <div class="relative">
              <select v-model="filters.strand_id" @change="resetAndFetch()"
                class="appearance-none pl-3 pr-8 py-2 text-xs bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white rounded-xl focus:ring-2 focus:ring-[#004d08] focus:outline-none font-normal cursor-pointer max-w-[160px] truncate">
                <option value="">All Strands</option>
                <option v-for="s in strands" :key="s.id" :value="s.id">{{ s.code }}</option>
              </select>
            </div>

            <div class="relative w-full sm:w-56">
              <svg class="w-4 h-4 absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 dark:text-gray-500"
                fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
              </svg>
              <input v-model="filters.search" @input="debouncedFetch" type="text" placeholder="Search section name..."
                class="w-full pl-10 pr-8 py-2 text-xs bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white placeholder-gray-400 rounded-xl focus:ring-2 focus:ring-[#004d08] dark:focus:ring-[#86EFAC] focus:outline-none font-normal" />
              <button v-if="filters.search" @click="filters.search = ''; fetchSections()"
                class="absolute right-2.5 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 cursor-pointer">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
              </button>
            </div>

            <button v-if="hasActiveFilters" @click="clearFilters"
              class="px-3 py-2 text-xs font-normal text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-white/5 rounded-xl border border-gray-200 dark:border-[#3F4F43] transition-colors cursor-pointer shrink-0">
              Reset
            </button>
          </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
          <table class="w-full text-left border-collapse">
            <thead>
              <tr
                class="border-b border-gray-100 dark:border-[#3F4F43] bg-gray-50/50 dark:bg-[#232D26]/50 text-[10px] font-medium uppercase text-gray-400 tracking-wider">
                <th class="py-3.5 px-5">
                  <button @click="setSort('name')"
                    class="inline-flex items-center gap-1 hover:text-gray-700 dark:hover:text-gray-200 transition-colors cursor-pointer">
                    Section
                    <SortIcon field="name" />
                  </button>
                </th>
                <th class="py-3.5 px-5">
                  <button @click="setSort('grade_level')"
                    class="inline-flex items-center gap-1 hover:text-gray-700 dark:hover:text-gray-200 transition-colors cursor-pointer">
                    Grade
                    <SortIcon field="grade_level" />
                  </button>
                </th>
                <th class="py-3.5 px-5">
                  <button @click="setSort('strand')"
                    class="inline-flex items-center gap-1 hover:text-gray-700 dark:hover:text-gray-200 transition-colors cursor-pointer">
                    Strand
                    <SortIcon field="strand" />
                  </button>
                </th>
                <th class="py-3.5 px-5">Adviser</th>
                <th class="py-3.5 px-5 min-w-[180px]">
                  <button @click="setSort('enrolled')"
                    class="inline-flex items-center gap-1 hover:text-gray-700 dark:hover:text-gray-200 transition-colors cursor-pointer">
                    Enrolled / Capacity
                    <SortIcon field="enrolled" />
                  </button>
                </th>
                <th class="py-3.5 px-5 text-right">Actions</th>
              </tr>
            </thead>
            <tbody
              class="divide-y divide-gray-100 dark:divide-[#3F4F43] text-xs font-normal text-gray-800 dark:text-gray-200">
              <tr v-for="s in sections.data" :key="s.id"
                class="hover:bg-gray-50/80 dark:hover:bg-white/5 transition-colors">
                <td class="py-4 px-5">
                  <p class="text-xs font-medium text-gray-900 dark:text-white">{{ s.name }}</p>
                  <p class="text-[10px] text-gray-400">{{ s.school_year }}</p>
                </td>
                <td class="py-4 px-5 text-gray-700 dark:text-gray-300">Grade {{ s.grade_level }}</td>
                <td class="py-4 px-5">
                  <span v-if="s.strand_code"
                    class="font-mono text-[11px] text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-[#232D26] px-2 py-0.5 rounded border border-gray-200 dark:border-[#3F4F43]">
                    {{ s.strand_code }}
                  </span>
                  <span v-else class="text-gray-400">—</span>
                </td>
                <td class="py-4 px-5 text-gray-600 dark:text-gray-300">{{ s.adviser || 'Unassigned' }}</td>
                <td class="py-4 px-5">
                  <div>
                    <div class="flex items-center justify-between mb-1">
                      <span class="text-xs text-gray-900 dark:text-white font-medium">
                        {{ s.enrolled_count }} / {{ s.max_capacity }}
                      </span>
                      <span class="text-[10px] font-normal" :class="capacityTextClass(s)">
                        {{ capacityPercent(s) }}%
                      </span>
                    </div>
                    <div class="h-1.5 rounded-full bg-gray-200 dark:bg-[#1C261E] overflow-hidden">
                      <div class="h-full rounded-full transition-all" :class="capacityBarClass(s)"
                        :style="{ width: capacityPercent(s) + '%' }"></div>
                    </div>
                  </div>
                </td>
                <td class="py-4 px-5 text-right">
                  <div class="flex items-center justify-end gap-1.5">
                    <button @click="openViewModal(s)"
                      class="px-2.5 py-1 text-[10px] font-normal text-sky-700 dark:text-sky-300 bg-sky-50 hover:bg-sky-100 dark:bg-sky-950/50 rounded-lg border border-sky-200 dark:border-sky-800 transition-colors cursor-pointer">
                      View
                    </button>
                    <button @click="openEditModal(s)"
                      class="px-2.5 py-1 text-[10px] font-normal text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-[#232D26] hover:bg-gray-200 dark:hover:bg-white/10 rounded-lg transition-colors cursor-pointer">
                      Edit
                    </button>
                    <button @click="confirmDelete(s)"
                      class="px-2.5 py-1 text-[10px] font-normal text-red-700 dark:text-red-400 bg-red-50 hover:bg-red-100 dark:bg-red-950/50 rounded-lg border border-red-200 dark:border-red-900 transition-colors cursor-pointer">
                      Delete
                    </button>
                  </div>
                </td>
              </tr>
              <tr v-if="!sections.data || sections.data.length === 0">
                <td colspan="6" class="py-12 text-center text-gray-400 dark:text-gray-500 font-normal">
                  <p class="text-xs">No sections match your filters.</p>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Pagination -->
        <div v-if="sections.total > 0"
          class="px-5 py-3.5 bg-gray-50/50 dark:bg-[#232D26]/50 border-t border-gray-100 dark:border-[#3F4F43] flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-gray-500 dark:text-gray-400">
          <div class="flex items-center gap-3">
            <span>
              Showing <span class="font-semibold text-gray-900 dark:text-white">{{ sections.from || 0 }}</span>
              to <span class="font-semibold text-gray-900 dark:text-white">{{ sections.to || 0 }}</span>
              of <span class="font-semibold text-gray-900 dark:text-white">{{ sections.total }}</span> entries
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
            <button @click="changePage(sections.current_page - 1)" :disabled="sections.current_page <= 1"
              class="px-3 py-1.5 rounded-lg border border-gray-200 dark:border-[#3F4F43] bg-white dark:bg-[#2D3A31] text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-white/5 disabled:opacity-40 cursor-pointer disabled:cursor-not-allowed transition-colors">
              Previous
            </button>
            <span class="px-3 py-1.5 font-medium text-gray-900 dark:text-white">
              Page {{ sections.current_page }} of {{ sections.last_page }}
            </span>
            <button @click="changePage(sections.current_page + 1)"
              :disabled="sections.current_page >= sections.last_page"
              class="px-3 py-1.5 rounded-lg border border-gray-200 dark:border-[#3F4F43] bg-white dark:bg-[#2D3A31] text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-white/5 disabled:opacity-40 cursor-pointer disabled:cursor-not-allowed transition-colors">
              Next
            </button>
          </div>
        </div>
      </div>

      <!-- Modals -->
      <SectionFormModal :show="showFormModal" :section="selectedSection" :strands="strands" :teachers="teachers"
        :school-years="schoolYears" :default-max-capacity="defaultMaxCapacity"
        @close="showFormModal = false" @saved="fetchSections" />

      <ViewSectionModal :show="showViewModal" :section="selectedSection"
        @close="showViewModal = false" @edit="fromViewToEdit" @changed="fetchSections" />
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref, reactive, computed, onMounted, h } from 'vue'
import axios from 'axios'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import SectionFormModal from './SectionFormModal.vue'
import ViewSectionModal from './ViewSectionModal.vue'

const props = defineProps({
  strands: { type: Array, default: () => [] },
  teachers: { type: Array, default: () => [] },
  schoolYears: { type: Array, default: () => [] },
  defaultMaxCapacity: { type: Number, default: 40 },
})

const emptyPaginator = () => ({ data: [], current_page: 1, last_page: 1, total: 0, from: 0, to: 0 })

const sections = ref(emptyPaginator())
const counts = ref({ total_sections: 0, total_capacity: 0, total_enrolled: 0, occupancy_rate: 0 })

const filters = reactive({
  search: '',
  school_year_id: '',
  strand_id: '',
  grade_level: '',
  per_page: 10,
  page: 1,
  sort_by: 'name',
  sort_dir: 'asc',
})

let searchTimeout = null

const showFormModal = ref(false)
const showViewModal = ref(false)
const selectedSection = ref(null)

const hasActiveFilters = computed(() =>
  filters.search !== '' || filters.school_year_id !== '' ||
  filters.strand_id !== '' || filters.grade_level !== ''
)

const fetchSections = async () => {
  try {
    const { data } = await axios.get('/admin/sections/list', { params: filters })
    const p = data?.sections
    sections.value = p && Array.isArray(p.data) ? p : emptyPaginator()   // success: assign server data
    counts.value = data?.counts ?? counts.value
  } catch (e) {
    console.error('Failed to load sections:', e)
    flash.error(e.response?.data?.message || 'Failed to load sections. Please refresh.')
    // nothing assigned — table keeps showing its last-known rows
  }
}

const debouncedFetch = () => {
  clearTimeout(searchTimeout)
  searchTimeout = setTimeout(() => { filters.page = 1; fetchSections() }, 300)
}

const setSort = (field) => {
  if (filters.sort_by === field) {
    filters.sort_dir = filters.sort_dir === 'asc' ? 'desc' : 'asc'
  } else {
    filters.sort_by = field
    filters.sort_dir = 'asc'
  }
  filters.page = 1
  fetchSections()
}

const changePage = (page) => {
  if (page < 1 || page > sections.value.last_page) return
  filters.page = page
  fetchSections()
}

const changePageSize = () => { filters.page = 1; fetchSections() }

const clearFilters = () => {
  filters.search = ''
  filters.school_year_id = ''
  filters.strand_id = ''
  filters.grade_level = ''
  filters.page = 1
  fetchSections()
}

const SortIcon = (props) => {
  const active = filters.sort_by === props.field
  const dir = filters.sort_dir
  return h('svg', {
    class: ['w-3 h-3 transition-colors', active ? 'text-[#004d08] dark:text-[#86EFAC]' : 'text-gray-300 dark:text-gray-600'],
    fill: 'none', stroke: 'currentColor', viewBox: '0 0 24 24', 'stroke-width': 2,
  }, [
    h('path', { 'stroke-linecap': 'round', 'stroke-linejoin': 'round', d: 'M5 15l7-7 7 7', opacity: active && dir === 'asc' ? 1 : 0 }),
    h('path', { 'stroke-linecap': 'round', 'stroke-linejoin': 'round', d: 'M19 9l-7 7-7-7', opacity: active && dir === 'desc' ? 1 : 0 }),
  ])
}
SortIcon.props = { field: { type: String, required: true } }

const capacityPercent = (s) => s.max_capacity > 0
  ? Math.min(100, Math.round((s.enrolled_count / s.max_capacity) * 100))
  : 0

const capacityBarClass = (s) => {
  const p = capacityPercent(s)
  if (p >= 100) return 'bg-red-500'
  if (p >= 90) return 'bg-amber-500'
  return 'bg-emerald-500'
}

const capacityTextClass = (s) => {
  const p = capacityPercent(s)
  if (p >= 100) return 'text-red-600 dark:text-red-400'
  if (p >= 90) return 'text-amber-600 dark:text-amber-400'
  return 'text-emerald-600 dark:text-emerald-400'
}

const openCreateModal = () => { selectedSection.value = null; showFormModal.value = true }
const openEditModal = (s) => { selectedSection.value = s; showFormModal.value = true }
const openViewModal = (s) => { selectedSection.value = s; showViewModal.value = true }

const fromViewToEdit = (s) => {
  showViewModal.value = false
  setTimeout(() => openEditModal(s), 220)
}

const confirmDelete = async (section) => {
  const { confirmed } = await confirm({
    title: 'Delete Section',
    message: `Delete "${section.name}"?`,
    details: ['Fails if students are enrolled in this section.'],
    confirmLabel: 'Delete',
    variant: 'danger',
  })
  if (!confirmed) return
  try {
    await axios.delete(`/admin/sections/${section.id}`)
    flash.success(`Section ${section.name} deleted.`)
    fetchSections()
  } catch (e) {
    flash.error(e.response?.data?.message || 'Cannot delete section.')
  }
}

const resetAndFetch = () => {
  filters.page = 1
  fetchSections()
}

onMounted(() => { fetchSections() })
</script>