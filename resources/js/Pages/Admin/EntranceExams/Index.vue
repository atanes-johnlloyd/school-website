<template>
  <AdminLayout>
    <div class="space-y-6 max-w-7xl mx-auto pb-10 font-['Inter']">

      <!-- Hero Banner -->
      <div
        class="relative overflow-hidden w-full rounded-2xl sm:rounded-3xl shadow-lg border border-[#006907]/20 dark:border-none min-h-[220px] sm:min-h-[240px] flex flex-col justify-center p-4 sm:p-8 md:p-10">
        <!-- Background Image Layer -->
        <img
          :src="heroImage || 'https://images.unsplash.com/photo-1434030216411-0b793f4b4173?q=80&w=1600&auto=format&fit=crop'"
          alt="Exam Sessions Hero" class="absolute inset-0 w-full h-full object-cover z-0 object-center" />

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
                <span>📝</span> ADMISSIONS
              </span>

              <span
                class="inline-flex items-center gap-2 bg-black/30 dark:bg-black/50 backdrop-blur-md text-white border border-white/20 text-[10px] sm:text-xs font-semibold px-3 sm:px-4 py-1 sm:py-1.5 rounded-full shadow-sm">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                Entrance Exams
              </span>
            </div>

            <!-- Title & Subtitle -->
            <div class="space-y-1 sm:space-y-1.5">
              <h1
                class="font-['Anton'] text-2xl sm:text-4xl md:text-5xl tracking-wide uppercase flex items-center gap-2 select-none flex-wrap leading-none text-white drop-shadow-md">
                <span>EXAM</span>
                <span class="text-[#F9C20C] drop-shadow-[0_2px_8px_rgba(249,194,12,0.4)]">SESSIONS</span>
              </h1>
              <p class="text-white/90 text-xs sm:text-sm md:text-base leading-relaxed font-medium">
                Schedule exam sessions, assign approved applicants, and record scores that convert passers into
                students.
              </p>
            </div>
          </div>

          <!-- Action Button -->
          <div class="flex items-center shrink-0 pt-2 md:pt-0">
            <button @click="openCreateModal"
              class="inline-flex items-center justify-center gap-2 bg-[#F9C20C] hover:bg-[#e0ae0a] text-[#2C3E2D] font-black px-4 sm:px-5 py-2.5 sm:py-3 rounded-xl transition-all shadow-md active:scale-95 text-xs sm:text-sm cursor-pointer">
              <span class="text-base sm:text-lg leading-none">+</span>
              <span>Schedule Exam</span>
            </button>
          </div>
        </div>
      </div>

      <!-- Telemetry Stat Cards -->
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">

        <!-- Total -->
        <button @click="setStatusFilter('')" :class="[
          'text-left p-5 rounded-2xl border shadow-xs flex items-center justify-between transition-colors cursor-pointer',
          filters.status === ''
            ? 'bg-[#004d08]/5 dark:bg-[#86EFAC]/5 border-[#004d08]/40 dark:border-[#86EFAC]/30'
            : 'bg-white dark:bg-[#2D3A31] border-gray-200/80 dark:border-[#3F4F43] hover:border-gray-300 dark:hover:border-gray-500'
        ]">
          <div class="space-y-1">
            <p class="text-[10px] font-medium uppercase text-gray-400 tracking-wider">Total Exams</p>
            <p class="text-2xl font-medium text-gray-900 dark:text-white">{{ counts.total || 0 }}</p>
            <span class="text-[10px] text-gray-500 dark:text-gray-400 font-normal">All Sessions</span>
          </div>
          <div
            class="w-12 h-12 rounded-2xl bg-gray-50 dark:bg-[#1C261E] border border-gray-100 dark:border-[#3F4F43] flex items-center justify-center text-gray-600 dark:text-gray-300 shrink-0">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round"
                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
            </svg>
          </div>
        </button>

        <!-- Upcoming -->
        <button @click="setStatusFilter('upcoming')" :class="[
          'text-left p-5 rounded-2xl border shadow-xs flex items-center justify-between transition-colors cursor-pointer',
          filters.status === 'upcoming'
            ? 'bg-sky-50/60 dark:bg-sky-950/20 border-sky-300 dark:border-sky-800'
            : 'bg-white dark:bg-[#2D3A31] border-gray-200/80 dark:border-[#3F4F43] hover:border-gray-300 dark:hover:border-gray-500'
        ]">
          <div class="space-y-1">
            <p class="text-[10px] font-medium uppercase text-sky-500 tracking-wider">Upcoming</p>
            <p class="text-2xl font-medium text-gray-900 dark:text-white">{{ counts.upcoming || 0 }}</p>
            <span class="text-[10px] text-sky-600 dark:text-sky-400 font-normal">Scheduled</span>
          </div>
          <div
            class="w-12 h-12 rounded-2xl bg-sky-50 dark:bg-[#1C261E] border border-sky-100 dark:border-[#3F4F43] flex items-center justify-center text-sky-600 dark:text-sky-400 shrink-0">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round"
                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
          </div>
        </button>

        <!-- Ongoing -->
        <button @click="setStatusFilter('ongoing')" :class="[
          'text-left p-5 rounded-2xl border shadow-xs flex items-center justify-between transition-colors cursor-pointer',
          filters.status === 'ongoing'
            ? 'bg-blue-50/60 dark:bg-blue-950/20 border-blue-300 dark:border-blue-800'
            : 'bg-white dark:bg-[#2D3A31] border-gray-200/80 dark:border-[#3F4F43] hover:border-gray-300 dark:hover:border-gray-500'
        ]">
          <div class="space-y-1">
            <p class="text-[10px] font-medium uppercase text-blue-500 tracking-wider">Ongoing</p>
            <p class="text-2xl font-medium text-gray-900 dark:text-white">{{ counts.ongoing || 0 }}</p>
            <span class="text-[10px] text-blue-600 dark:text-blue-400 font-normal">In Progress</span>
          </div>
          <div
            class="w-12 h-12 rounded-2xl bg-blue-50 dark:bg-[#1C261E] border border-blue-100 dark:border-[#3F4F43] flex items-center justify-center text-blue-600 dark:text-blue-400 shrink-0">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </div>
        </button>

        <!-- Completed -->
        <button @click="setStatusFilter('completed')" :class="[
          'text-left p-5 rounded-2xl border shadow-xs flex items-center justify-between transition-colors cursor-pointer',
          filters.status === 'completed'
            ? 'bg-emerald-50/60 dark:bg-emerald-950/20 border-emerald-300 dark:border-emerald-800'
            : 'bg-white dark:bg-[#2D3A31] border-gray-200/80 dark:border-[#3F4F43] hover:border-gray-300 dark:hover:border-gray-500'
        ]">
          <div class="space-y-1">
            <p class="text-[10px] font-medium uppercase text-emerald-500 tracking-wider">Completed</p>
            <p class="text-2xl font-medium text-gray-900 dark:text-white">{{ counts.completed || 0 }}</p>
            <span class="text-[10px] text-emerald-600 dark:text-emerald-400 font-normal">Ready to Score</span>
          </div>
          <div
            class="w-12 h-12 rounded-2xl bg-emerald-50 dark:bg-[#1C261E] border border-emerald-100 dark:border-[#3F4F43] flex items-center justify-center text-emerald-600 dark:text-emerald-400 shrink-0">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </div>
        </button>

        <!-- Cancelled -->
        <button @click="setStatusFilter('cancelled')" :class="[
          'text-left p-5 rounded-2xl border shadow-xs flex items-center justify-between transition-colors cursor-pointer',
          filters.status === 'cancelled'
            ? 'bg-amber-50/60 dark:bg-amber-950/20 border-amber-300 dark:border-amber-800'
            : 'bg-white dark:bg-[#2D3A31] border-gray-200/80 dark:border-[#3F4F43] hover:border-gray-300 dark:hover:border-gray-500'
        ]">
          <div class="space-y-1">
            <p class="text-[10px] font-medium uppercase text-amber-500 tracking-wider">Cancelled</p>
            <p class="text-2xl font-medium text-gray-900 dark:text-white">{{ counts.cancelled || 0 }}</p>
            <span class="text-[10px] text-amber-600 dark:text-amber-400 font-normal">No Longer Active</span>
          </div>
          <div
            class="w-12 h-12 rounded-2xl bg-amber-50 dark:bg-[#1C261E] border border-amber-100 dark:border-[#3F4F43] flex items-center justify-center text-amber-600 dark:text-amber-400 shrink-0">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round"
                d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
            </svg>
          </div>
        </button>

      </div>

      <!-- Main card -->
      <div
        class="bg-white dark:bg-[#2D3A31] rounded-2xl border border-gray-200/80 dark:border-[#3F4F43] shadow-xs overflow-hidden transition-colors">

        <!-- Controls -->
        <div
          class="p-4 sm:p-5 border-b border-gray-100 dark:border-[#3F4F43] bg-gray-50/50 dark:bg-[#232D26]/50 flex flex-col lg:flex-row gap-3 justify-between lg:items-center">
          <div>
            <h2 class="text-xs font-medium text-gray-900 dark:text-white uppercase tracking-wider">Exam Sessions</h2>
            <p class="text-[11px] text-gray-500 dark:text-gray-400 font-normal">Manage scheduled exams and applicant
              assignments</p>
          </div>

          <div class="flex flex-wrap sm:flex-nowrap items-center gap-2 w-full lg:w-auto lg:justify-end">
            <!-- Grade filter -->
            <div class="relative">
              <select v-model="filters.grade_level" @change="fetchExams()"
                class="appearance-none pl-3 pr-8 py-2 text-xs bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white rounded-xl focus:ring-2 focus:ring-[#004d08] focus:outline-none font-normal cursor-pointer">
                <option value="">All Grades</option>
                <option value="11">Grade 11</option>
                <option value="12">Grade 12</option>
              </select>
            </div>

            <!-- School Year filter -->
            <div class="relative">
              <select v-model="filters.school_year_id" @change="fetchExams()"
                class="appearance-none pl-3 pr-8 py-2 text-xs bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white rounded-xl focus:ring-2 focus:ring-[#004d08] focus:outline-none font-normal cursor-pointer">
                <option value="">All School Years</option>
                <option v-for="sy in schoolYears" :key="sy.id" :value="sy.id">{{ sy.label }}</option>
              </select>
            </div>

            <!-- Search -->
            <div class="relative w-full sm:w-56">
              <svg class="w-4 h-4 absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 dark:text-gray-500"
                fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
              </svg>
              <input v-model="filters.search" @input="debouncedFetch" type="text" placeholder="Search exam name..."
                class="w-full pl-10 pr-8 py-2 text-xs bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white placeholder-gray-400 rounded-xl focus:ring-2 focus:ring-[#004d08] dark:focus:ring-[#86EFAC] focus:outline-none font-normal" />
              <button v-if="filters.search" @click="filters.search = ''; fetchExams()"
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
                  <button @click="setSort('exam_name')"
                    class="inline-flex items-center gap-1 hover:text-gray-700 dark:hover:text-gray-200 transition-colors cursor-pointer">
                    Exam
                    <SortIcon field="exam_name" />
                  </button>
                </th>
                <th class="py-3.5 px-5">
                  <button @click="setSort('exam_date')"
                    class="inline-flex items-center gap-1 hover:text-gray-700 dark:hover:text-gray-200 transition-colors cursor-pointer">
                    Schedule
                    <SortIcon field="exam_date" />
                  </button>
                </th>
                <th class="py-3.5 px-5">Venue</th>
                <th class="py-3.5 px-5 text-center">Capacity</th>
                <th class="py-3.5 px-5 text-center">
                  <button @click="setSort('status')"
                    class="inline-flex items-center gap-1 hover:text-gray-700 dark:hover:text-gray-200 transition-colors cursor-pointer">
                    Status
                    <SortIcon field="status" />
                  </button>
                </th>
                <th class="py-3.5 px-5 text-right">Actions</th>
              </tr>
            </thead>
            <tbody
              class="divide-y divide-gray-100 dark:divide-[#3F4F43] text-xs font-normal text-gray-800 dark:text-gray-200">
              <tr v-for="exam in exams.data" :key="exam.id"
                class="hover:bg-gray-50/80 dark:hover:bg-white/5 transition-colors">
                <td class="py-4 px-5">
                  <p class="font-medium text-gray-900 dark:text-white truncate">{{ exam.exam_name }}</p>
                  <p class="text-[10px] text-gray-500 dark:text-gray-400">
                    Grade {{ exam.grade_level }} &bull; {{ exam.track || 'All Tracks' }}
                    <span v-if="exam.school_year"> &bull; {{ exam.school_year }}</span>
                  </p>
                </td>
                <td class="py-4 px-5">
                  <p class="text-xs text-gray-900 dark:text-white font-medium">{{ formatDate(exam.exam_date) }}</p>
                  <p class="text-[10px] text-gray-500 dark:text-gray-400">{{ exam.exam_time || '—' }}</p>
                </td>
                <td class="py-4 px-5 text-gray-600 dark:text-gray-300">{{ exam.venue || '—' }}</td>
                <td class="py-4 px-5 text-center">
                  <p class="text-xs font-medium text-gray-900 dark:text-white">
                    {{ exam.applicant_count }} / {{ exam.max_capacity }}
                  </p>
                  <p class="text-[10px] text-gray-500 dark:text-gray-400">{{ exam.remaining }} slots left</p>
                </td>
                <td class="py-4 px-5 text-center">
                  <span
                    class="px-2.5 py-1 rounded-full text-[10px] font-normal uppercase tracking-wider inline-flex items-center gap-1.5 border"
                    :class="statusBadgeClass(exam.status)">
                    <span class="w-1.5 h-1.5 rounded-full" :class="statusDotClass(exam.status)"></span>
                    {{ exam.status }}
                  </span>
                </td>
                <td class="py-4 px-5 text-right">
                  <div class="flex items-center justify-end gap-1.5">
                    <button @click="openManageModal(exam)"
                      class="px-2.5 py-1 text-[10px] font-normal text-sky-700 dark:text-sky-300 bg-sky-50 hover:bg-sky-100 dark:bg-sky-950/50 rounded-lg border border-sky-200 dark:border-sky-800 transition-colors cursor-pointer">
                      Manage
                    </button>
                    <button @click="openEditModal(exam)" :disabled="exam.status === 'Completed'"
                      class="px-2.5 py-1 text-[10px] font-normal text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-[#232D26] hover:bg-gray-200 dark:hover:bg-white/10 rounded-lg transition-colors cursor-pointer disabled:opacity-40 disabled:cursor-not-allowed">
                      Edit
                    </button>
                    <button v-if="exam.status === 'Upcoming' || exam.status === 'Ongoing'" @click="cancelExam(exam)"
                      class="px-2.5 py-1 text-[10px] font-normal text-amber-700 dark:text-amber-400 bg-amber-50 hover:bg-amber-100 dark:bg-amber-950/50 rounded-lg border border-amber-200 dark:border-amber-800 transition-colors cursor-pointer">
                      Cancel
                    </button>
                  </div>
                </td>
              </tr>
              <tr v-if="!exams.data || exams.data.length === 0">
                <td colspan="6" class="py-12 text-center text-gray-400 dark:text-gray-500">
                  <p class="text-xs">No exam sessions found.</p>
                  <p class="text-[11px] mt-0.5">Schedule one to get started.</p>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Pagination -->
        <div v-if="exams.total > 0"
          class="px-5 py-3.5 bg-gray-50/50 dark:bg-[#232D26]/50 border-t border-gray-100 dark:border-[#3F4F43] flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-gray-500 dark:text-gray-400">
          <div class="flex items-center gap-3">
            <span>
              Showing <span class="font-semibold text-gray-900 dark:text-white">{{ exams.from || 0 }}</span>
              to <span class="font-semibold text-gray-900 dark:text-white">{{ exams.to || 0 }}</span>
              of <span class="font-semibold text-gray-900 dark:text-white">{{ exams.total }}</span> entries
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
            <button @click="changePage(exams.current_page - 1)" :disabled="exams.current_page <= 1"
              class="px-3 py-1.5 rounded-lg border border-gray-200 dark:border-[#3F4F43] bg-white dark:bg-[#2D3A31] text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-white/5 disabled:opacity-40 cursor-pointer disabled:cursor-not-allowed transition-colors">
              Previous
            </button>
            <span class="px-3 py-1.5 font-medium text-gray-900 dark:text-white">
              Page {{ exams.current_page }} of {{ exams.last_page }}
            </span>
            <button @click="changePage(exams.current_page + 1)" :disabled="exams.current_page >= exams.last_page"
              class="px-3 py-1.5 rounded-lg border border-gray-200 dark:border-[#3F4F43] bg-white dark:bg-[#2D3A31] text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-white/5 disabled:opacity-40 cursor-pointer disabled:cursor-not-allowed transition-colors">
              Next
            </button>
          </div>
        </div>
      </div>

      <!-- Modals -->
      <EntranceExamFormModal :show="showFormModal" :exam="selectedExam" :tracks="tracks" :school-years="schoolYears"
        @close="showFormModal = false; selectedExam = null" @saved="fetchExams" />

      <ManageExamModal :show="showManageModal" :exam="selectedExam"
        @close="showManageModal = false; selectedExam = null" @changed="fetchExams" />
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref, reactive, computed, onMounted, h } from 'vue'
import axios from 'axios'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import EntranceExamFormModal from './EntranceExamFormModal.vue'
import ManageExamModal from './ManageExamModal.vue'
import { useFlash } from '@/Composables/useFlash'
import { useConfirm } from '@/Composables/useConfirm'

const props = defineProps({
  tracks: { type: Array, default: () => [] },
  schoolYears: { type: Array, default: () => [] },
})
const flash = useFlash()
const confirm = useConfirm()
const emptyPaginator = () => ({ data: [], current_page: 1, last_page: 1, total: 0, from: 0, to: 0 })

const exams = ref(emptyPaginator())
const counts = ref({ total: 0, upcoming: 0, ongoing: 0, completed: 0, cancelled: 0 })

const filters = reactive({
  search: '',
  status: '',
  grade_level: '',
  school_year_id: '',
  per_page: 10,
  page: 1,
  sort_by: 'exam_date',
  sort_dir: 'desc',
})

let searchTimeout = null

const showFormModal = ref(false)
const showManageModal = ref(false)
const selectedExam = ref(null)

const hasActiveFilters = computed(() =>
  filters.search !== '' || filters.status !== '' ||
  filters.grade_level !== '' || filters.school_year_id !== ''
)

const fetchExams = async () => {
  try {
    const { data } = await axios.get('/admin/entrance-exams/list', { params: filters })
    const p = data?.exams
    exams.value = p && Array.isArray(p.data) ? p : emptyPaginator()
    counts.value = data?.counts ?? counts.value
  } catch (e) {
    console.error('Failed to load exams:', e)
    exams.value = emptyPaginator()
    flash.error('Failed to load exam sessions. Please refresh.')   // ← add
  }
}

const debouncedFetch = () => {
  clearTimeout(searchTimeout)
  searchTimeout = setTimeout(() => { filters.page = 1; fetchExams() }, 300)
}

const setStatusFilter = (status) => { filters.status = status; filters.page = 1; fetchExams() }

const setSort = (field) => {
  if (filters.sort_by === field) {
    filters.sort_dir = filters.sort_dir === 'asc' ? 'desc' : 'asc'
  } else {
    filters.sort_by = field
    filters.sort_dir = 'asc'
  }
  filters.page = 1
  fetchExams()
}

const changePage = (page) => {
  if (page < 1 || page > exams.value.last_page) return
  filters.page = page
  fetchExams()
}

const changePageSize = () => { filters.page = 1; fetchExams() }

const clearFilters = () => {
  filters.search = ''
  filters.status = ''
  filters.grade_level = ''
  filters.school_year_id = ''
  filters.page = 1
  fetchExams()
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

const formatDate = (iso) => {
  if (!iso) return '—'
  try { return new Date(iso).toLocaleDateString('en-PH', { year: 'numeric', month: 'short', day: 'numeric' }) }
  catch { return iso }
}

const statusBadgeClass = (status) => ({
  Upcoming: 'bg-sky-50 dark:bg-sky-950/50 text-sky-700 dark:text-sky-300 border-sky-200 dark:border-sky-800',
  Ongoing: 'bg-blue-50 dark:bg-blue-950/50 text-blue-700 dark:text-blue-300 border-blue-200 dark:border-blue-800',
  Completed: 'bg-emerald-100 dark:bg-emerald-950/60 text-emerald-800 dark:text-emerald-300 border-emerald-200 dark:border-emerald-800',
  Cancelled: 'bg-gray-100 dark:bg-[#232D26] text-gray-600 dark:text-gray-400 border-gray-200 dark:border-[#3F4F43]',
}[status] || 'bg-gray-100 dark:bg-[#232D26] text-gray-600 dark:text-gray-400 border-gray-200 dark:border-[#3F4F43]')

const statusDotClass = (status) => ({
  Upcoming: 'bg-sky-500', Ongoing: 'bg-blue-500', Completed: 'bg-emerald-500', Cancelled: 'bg-gray-400',
}[status] || 'bg-gray-400')

const openCreateModal = () => { selectedExam.value = null; showFormModal.value = true }
const openEditModal = (exam) => { selectedExam.value = exam; showFormModal.value = true }
const openManageModal = (exam) => { selectedExam.value = exam; showManageModal.value = true }

const cancelExam = async (exam) => {
  const { confirmed } = await confirm({
    title: 'Cancel Exam Session',
    message: `Cancel "${exam.exam_name}"?`,
    details: [
      'All assigned applicants will be unassigned from this exam.',
      'Each assigned applicant will receive a cancellation email.',
      'This cannot be undone.',
    ],
    confirmLabel: 'Cancel Exam',
    variant: 'danger',
  })
  if (!confirmed) return

  try {
    const { data } = await axios.put(`/admin/entrance-exams/${exam.id}/cancel`)
    flash.success(data.message || 'Exam cancelled.')
    fetchExams()
  } catch (e) {
    flash.error(e.response?.data?.message || 'Failed to cancel exam.')
  }
}

onMounted(() => { fetchExams() })
</script>