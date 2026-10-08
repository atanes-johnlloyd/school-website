<template>
  <Head title="Contributions - Admin" />

  <AdminLayout>
    <div class="space-y-6 pb-10 font-['Inter']">

      <!-- Hero Banner -->
      <div
        class="relative overflow-hidden w-full rounded-2xl sm:rounded-3xl shadow-lg border border-[#006907]/20 dark:border-none min-h-[220px] sm:min-h-[240px] flex flex-col justify-center p-4 sm:p-8 md:p-10">
        <img :src="heroImage" alt="Contributions"
          class="absolute inset-0 w-full h-full object-cover z-0 object-center" />
        <div class="absolute inset-0 bg-[#004d05]/85 dark:bg-[#152B1C]/90 animate-overlay z-0 mix-blend-multiply"></div>
        <div class="absolute -right-10 -bottom-10 w-72 h-72 rounded-full bg-emerald-300/20 blur-3xl pointer-events-none z-0"></div>
        <div class="absolute right-24 -top-16 w-80 h-80 rounded-full bg-[#F9C20C]/20 blur-3xl pointer-events-none z-0"></div>

        <div class="relative z-10 w-full flex flex-col md:flex-row md:items-center justify-between gap-4">
          <div class="space-y-2 sm:space-y-3 max-w-2xl">
            <div class="flex flex-wrap items-center gap-2 sm:gap-2.5">
              <span class="inline-flex items-center gap-1.5 bg-[#F9C20C] text-[#2C3E2D] font-black text-[10px] sm:text-xs px-3 sm:px-4 py-1 sm:py-1.5 rounded-full shadow-sm tracking-wide uppercase">
                <Icon icon="credit-card" size="xs" /> CONTRIBUTIONS
              </span>
              <span class="inline-flex items-center gap-2 bg-black/30 dark:bg-black/50 backdrop-blur-md text-white border border-white/20 text-[10px] sm:text-xs font-semibold px-3 sm:px-4 py-1 sm:py-1.5 rounded-full shadow-sm">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                {{ stats.total }} {{ stats.total === 1 ? 'Campaign' : 'Campaigns' }}
              </span>
              <span v-if="stats.active > 0"
                class="inline-flex items-center gap-1.5 bg-emerald-500/90 text-white border border-emerald-300/30 text-[10px] sm:text-xs font-black px-3 sm:px-4 py-1 sm:py-1.5 rounded-full shadow-sm">
                <Icon icon="check-circle" size="xs" /> {{ stats.active }} Active
              </span>
            </div>

            <div class="space-y-1 sm:space-y-1.5">
              <h1 class="font-['Anton'] text-2xl sm:text-4xl md:text-5xl tracking-wide uppercase flex items-center gap-2 select-none flex-wrap leading-none text-white drop-shadow-md">
                <span>CLASS</span>
                <span class="text-[#F9C20C] drop-shadow-[0_2px_8px_rgba(249,194,12,0.4)]">CONTRIBUTIONS</span>
              </h1>
              <p class="text-white/90 text-xs sm:text-sm md:text-base leading-relaxed font-medium">
                School-wide oversight of every contribution created by teachers. Verify guardians, confirm cash, override when needed.
              </p>
            </div>
          </div>

          <div class="flex items-center shrink-0 pt-2 md:pt-0">
            <button @click="openCreateModal"
              class="inline-flex items-center justify-center gap-2 bg-[#F9C20C] hover:bg-[#e0ae0a] text-[#2C3E2D] font-black px-4 sm:px-5 py-2.5 sm:py-3 rounded-xl transition-all shadow-md active:scale-95 text-xs sm:text-sm cursor-pointer">
              <span class="text-base sm:text-lg leading-none">+</span>
              <span>New Contribution</span>
            </button>
          </div>
        </div>
      </div>

      <!-- Stat Cards -->
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="text-left p-5 rounded-2xl border shadow-xs flex items-center justify-between bg-white dark:bg-[#2D3A31] border-gray-200/80 dark:border-[#3F4F43]">
          <div class="space-y-1">
            <p class="text-[10px] font-medium uppercase text-gray-400 tracking-wider">Campaigns</p>
            <p class="text-2xl font-medium text-gray-900 dark:text-white">{{ stats.total }}</p>
            <span class="text-[10px] text-gray-500 dark:text-gray-400 font-normal">{{ stats.active }} active</span>
          </div>
          <div class="w-12 h-12 rounded-2xl bg-emerald-50 dark:bg-[#1C261E] border border-emerald-100 dark:border-[#3F4F43] flex items-center justify-center text-emerald-700 dark:text-[#86EFAC] shrink-0">
            <Icon icon="credit-card" size="md" />
          </div>
        </div>

        <div class="text-left p-5 rounded-2xl border shadow-xs flex items-center justify-between bg-white dark:bg-[#2D3A31] border-gray-200/80 dark:border-[#3F4F43]">
          <div class="space-y-1">
            <p class="text-[10px] font-medium uppercase text-emerald-500 tracking-wider">Students Paid</p>
            <p class="text-2xl font-medium text-gray-900 dark:text-white">{{ stats.students_paid }}</p>
            <span class="text-[10px] text-emerald-600 dark:text-emerald-400 font-normal">of {{ stats.students_total }} assigned</span>
          </div>
          <div class="w-12 h-12 rounded-2xl bg-emerald-50 dark:bg-[#1C261E] border border-emerald-100 dark:border-[#3F4F43] flex items-center justify-center text-emerald-600 dark:text-emerald-400 shrink-0">
            <Icon icon="check-circle" size="md" />
          </div>
        </div>

        <div class="text-left p-5 rounded-2xl border shadow-xs flex items-center justify-between bg-white dark:bg-[#2D3A31] border-gray-200/80 dark:border-[#3F4F43]">
          <div class="space-y-1">
            <p class="text-[10px] font-medium uppercase text-gray-400 tracking-wider">Drafts</p>
            <p class="text-2xl font-medium text-gray-900 dark:text-white">{{ stats.drafts }}</p>
            <span class="text-[10px] text-gray-500 dark:text-gray-400 font-normal">Not yet published</span>
          </div>
          <div class="w-12 h-12 rounded-2xl bg-gray-50 dark:bg-[#1C261E] border border-gray-100 dark:border-[#3F4F43] flex items-center justify-center text-gray-600 dark:text-gray-300 shrink-0">
            <Icon icon="edit" size="md" />
          </div>
        </div>

        <div class="text-left p-5 rounded-2xl border shadow-xs flex items-center justify-between bg-white dark:bg-[#2D3A31] border-gray-200/80 dark:border-[#3F4F43]">
          <div class="space-y-1">
            <p class="text-[10px] font-medium uppercase text-amber-500 tracking-wider">Collected</p>
            <p class="text-2xl font-medium text-gray-900 dark:text-white truncate">₱{{ formatShort(stats.collected) }}</p>
            <span class="text-[10px] text-amber-600 dark:text-amber-400 font-normal">Total verified</span>
          </div>
          <div class="w-12 h-12 rounded-2xl bg-amber-50 dark:bg-[#1C261E] border border-amber-100 dark:border-[#3F4F43] flex items-center justify-center text-amber-600 dark:text-amber-400 shrink-0">
            <Icon icon="chart-bar" size="md" />
          </div>
        </div>
      </div>

      <!-- Main Card -->
      <div class="bg-white dark:bg-[#2D3A31] rounded-2xl border border-gray-200/80 dark:border-[#3F4F43] shadow-xs overflow-hidden transition-colors">

        <!-- Controls -->
        <div class="p-4 sm:p-5 border-b border-gray-100 dark:border-[#3F4F43] bg-gray-50/50 dark:bg-[#232D26]/50 flex flex-col lg:flex-row gap-3 justify-between lg:items-center">
          <div>
            <h2 class="text-xs font-medium text-gray-900 dark:text-white uppercase tracking-wider">Campaign Roster</h2>
            <p class="text-[11px] text-gray-500 dark:text-gray-400 font-normal">Search, filter, and manage contributions</p>
          </div>

          <div class="flex flex-wrap sm:flex-nowrap items-center gap-2 w-full lg:w-auto lg:justify-end">
            <div class="flex items-center gap-1.5 overflow-x-auto no-scrollbar pb-1 lg:pb-0">
              <button v-for="f in statusFilters" :key="f.id" @click="setFilter(f.id)"
                :class="[
                  'text-[11px] font-medium px-3 py-1.5 rounded-xl transition-colors shrink-0 cursor-pointer',
                  statusFilter === f.id
                    ? 'bg-[#004d08] dark:bg-[#86EFAC] text-white dark:text-[#232D26]'
                    : 'bg-gray-50 dark:bg-[#232D26] text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-white/5 border border-gray-200 dark:border-[#3F4F43]'
                ]">
                {{ f.label }} ({{ f.count }})
              </button>
            </div>

            <div class="relative w-full sm:w-56">
              <svg class="w-4 h-4 absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 dark:text-gray-500"
                fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
              </svg>
              <input v-model="search" @input="currentPage = 1" type="text" placeholder="Search title, teacher, section..."
                class="w-full pl-10 pr-3 py-2 text-xs bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white placeholder-gray-400 rounded-xl focus:ring-2 focus:ring-[#004d08] dark:focus:ring-[#86EFAC] focus:outline-none font-normal" />
            </div>
          </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
          <table class="w-full text-left border-collapse">
            <thead>
              <tr class="border-b border-gray-100 dark:border-[#3F4F43] bg-gray-50/50 dark:bg-[#232D26]/50 text-[10px] font-medium uppercase text-gray-400 tracking-wider">
                <th class="py-3.5 px-5">Contribution</th>
                <th class="py-3.5 px-5">Scope</th>
                <th class="py-3.5 px-5">Amount</th>
                <th class="py-3.5 px-5">Deadline</th>
                <th class="py-3.5 px-5 min-w-[180px]">Progress</th>
                <th class="py-3.5 px-5">Status</th>
                <th class="py-3.5 px-5 text-right">Actions</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-[#3F4F43] text-xs font-normal text-gray-800 dark:text-gray-200">
              <tr v-for="c in paginatedContributions" :key="c.id"
                class="hover:bg-gray-50/80 dark:hover:bg-white/5 transition-colors">
                <td class="py-4 px-5">
                  <p class="text-xs font-medium text-gray-900 dark:text-white">{{ c.title }}</p>
                  <p class="text-[10px] text-gray-400">{{ c.purpose || 'Class activity' }} &bull; by {{ c.creator || 'Teacher' }}</p>
                </td>
                <td class="py-4 px-5">
                  <span class="inline-flex items-center gap-1 text-[10px] font-medium text-emerald-700 dark:text-emerald-300 bg-emerald-50 dark:bg-emerald-950/50 border border-emerald-200 dark:border-emerald-800 px-2 py-0.5 rounded-md">
                    <Icon :icon="c.scope === 'section' ? 'users' : 'book-open'" size="xs" />
                    {{ c.display_name }}
                  </span>
                </td>
                <td class="py-4 px-5 font-medium text-gray-700 dark:text-gray-300">
                  {{ c.amount_type === 'fixed' ? '₱' + formatMoney(c.amount) : '₱' + formatMoney(c.min_amount) + '+' }}
                </td>
                <td class="py-4 px-5">
                  <span :class="isPast(c.deadline_at) ? 'text-red-600 dark:text-red-400 font-medium' : 'text-gray-600 dark:text-gray-300'" class="text-[11px]">
                    {{ formatShortDate(c.deadline_at) }}
                  </span>
                </td>
                <td class="py-4 px-5">
                  <div>
                    <div class="flex items-center justify-between mb-1">
                      <span class="text-xs text-gray-900 dark:text-white font-medium">
                        {{ c.paid_count }} / {{ c.total_count }} paid
                      </span>
                      <span class="text-[10px] font-normal text-emerald-600 dark:text-emerald-400">
                        {{ paidPercent(c) }}%
                      </span>
                    </div>
                    <div class="h-1.5 rounded-full bg-gray-200 dark:bg-[#1C261E] overflow-hidden">
                      <div class="h-full rounded-full bg-emerald-500 transition-all" :style="{ width: paidPercent(c) + '%' }"></div>
                    </div>
                  </div>
                </td>
                <td class="py-4 px-5">
                  <span :class="c.is_published
                    ? 'bg-emerald-100 dark:bg-emerald-950/60 text-[#004d08] dark:text-[#86EFAC] border-emerald-200 dark:border-emerald-800'
                    : 'bg-gray-100 dark:bg-[#232D26] text-gray-600 dark:text-gray-400 border-gray-200 dark:border-[#3F4F43]'"
                    class="text-[10px] font-medium uppercase px-2.5 py-1 rounded-md border">
                    {{ c.is_published ? 'Live' : 'Draft' }}
                  </span>
                </td>
                <td class="py-4 px-5 text-right">
                  <div class="flex items-center justify-end gap-1.5">
                    <button @click="openViewModal(c)"
                      class="px-2.5 py-1 text-[10px] font-normal text-sky-700 dark:text-sky-300 bg-sky-50 hover:bg-sky-100 dark:bg-sky-950/50 rounded-lg border border-sky-200 dark:border-sky-800 transition-colors cursor-pointer">
                      View
                    </button>
                    <button @click="openEditModal(c)"
                      class="px-2.5 py-1 text-[10px] font-normal text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-[#232D26] hover:bg-gray-200 dark:hover:bg-white/10 rounded-lg transition-colors cursor-pointer">
                      Edit
                    </button>
                  </div>
                </td>
              </tr>
              <tr v-if="!filteredContributions.length">
                <td colspan="7" class="py-12 text-center text-gray-400 dark:text-gray-500 font-normal">
                  <p class="text-xs">
                    {{ contributions.length === 0 ? 'No contributions yet.' : 'No matches found. Try clearing filters.' }}
                  </p>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- PAGINATION FOOTER -->
        <div v-if="filteredContributions.length > 0"
          class="px-5 py-3.5 bg-gray-50/50 dark:bg-[#232D26]/50 border-t border-gray-100 dark:border-[#3F4F43] flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-gray-500 dark:text-gray-400">
          <div class="flex items-center gap-3">
            <span>
              Showing <span class="font-semibold text-gray-900 dark:text-white">{{ pageFrom }}</span>
              to <span class="font-semibold text-gray-900 dark:text-white">{{ pageTo }}</span>
              of <span class="font-semibold text-gray-900 dark:text-white">{{ filteredContributions.length }}</span> entries
            </span>
            <select v-model.number="perPage" @change="currentPage = 1"
              class="appearance-none pl-2 pr-7 py-1 text-[11px] bg-white dark:bg-[#2D3A31] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white rounded-lg focus:ring-2 focus:ring-[#004d08] focus:outline-none cursor-pointer">
              <option :value="10">10</option>
              <option :value="25">25</option>
              <option :value="50">50</option>
              <option :value="100">100</option>
            </select>
          </div>
          <div class="flex items-center gap-1">
            <button @click="currentPage = Math.max(1, currentPage - 1)" :disabled="currentPage <= 1"
              class="px-3 py-1.5 rounded-lg border border-gray-200 dark:border-[#3F4F43] bg-white dark:bg-[#2D3A31] text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-white/5 disabled:opacity-40 cursor-pointer disabled:cursor-not-allowed transition-colors">
              Previous
            </button>
            <span class="px-3 py-1.5 font-medium text-gray-900 dark:text-white">
              Page {{ currentPage }} of {{ totalPages }}
            </span>
            <button @click="currentPage = Math.min(totalPages, currentPage + 1)" :disabled="currentPage >= totalPages"
              class="px-3 py-1.5 rounded-lg border border-gray-200 dark:border-[#3F4F43] bg-white dark:bg-[#2D3A31] text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-white/5 disabled:opacity-40 cursor-pointer disabled:cursor-not-allowed transition-colors">
              Next
            </button>
          </div>
        </div>
      </div>

      <!-- Modals -->
      <ContributionFormModal
        :show="showFormModal"
        :contribution="selectedContribution"
        :advisory-sections="advisorySections"
        :classrooms="classrooms"
        :strands="strands"
        :school-years="schoolYears"
        @close="showFormModal = false; selectedContribution = null"
        @saved="onSaved"
      />

      <ViewContributionModal
        :show="showViewModal"
        :contribution="selectedContribution"
        @close="showViewModal = false; selectedContribution = null"
        @edit="fromViewToEdit"
        @changed="reloadList"
      />

    </div>
  </AdminLayout>
</template>

<script setup>
import { ref, computed, watch } from 'vue'
import { Head, router } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import Icon from '@/Components/Icon.vue'
import ContributionFormModal from './ContributionFormModal.vue'
import ViewContributionModal from './ViewContributionModal.vue'
import heroImage from '../../../../assets/img/local/assignment.png'

const props = defineProps({
  contributions:    { type: Array,  default: () => [] },
  stats:            { type: Object, default: () => ({ total: 0, active: 0, drafts: 0, collected: 0, students_paid: 0, students_total: 0 }) },
  advisorySections: { type: Array,  default: () => [] },
  classrooms:       { type: Array,  default: () => [] },
  strands:          { type: Array,  default: () => [] },
  schoolYears:      { type: Array,  default: () => [] },
})

const statusFilter = ref('all')
const search = ref('')

// ─── Pagination (client-side) ─────────────────────────────
const currentPage = ref(1)
const perPage     = ref(10)

const statusFilters = computed(() => [
  { id: 'all',       label: 'All',       count: props.contributions.length },
  { id: 'published', label: 'Published', count: props.contributions.filter(c => c.is_published).length },
  { id: 'draft',     label: 'Drafts',    count: props.contributions.filter(c => !c.is_published).length },
].filter(f => f.id === 'all' || f.count > 0))

const filteredContributions = computed(() => {
  const q = search.value.trim().toLowerCase()
  return props.contributions.filter(c => {
    if (statusFilter.value === 'published' && !c.is_published) return false
    if (statusFilter.value === 'draft' && c.is_published) return false
    if (q && !(
      (c.title || '').toLowerCase().includes(q) ||
      (c.purpose || '').toLowerCase().includes(q) ||
      (c.creator || '').toLowerCase().includes(q) ||
      (c.display_name || '').toLowerCase().includes(q)
    )) return false
    return true
  })
})

const totalPages = computed(() =>
  Math.max(1, Math.ceil(filteredContributions.value.length / perPage.value))
)
const pageFrom = computed(() =>
  filteredContributions.value.length === 0 ? 0 : (currentPage.value - 1) * perPage.value + 1
)
const pageTo = computed(() =>
  Math.min(currentPage.value * perPage.value, filteredContributions.value.length)
)
const paginatedContributions = computed(() => {
  const start = (currentPage.value - 1) * perPage.value
  return filteredContributions.value.slice(start, start + perPage.value)
})

// Reset to page 1 when filter changes
watch([statusFilter, perPage], () => { currentPage.value = 1 })
// Clamp page when filtered list shrinks
watch(totalPages, (tp) => { if (currentPage.value > tp) currentPage.value = tp })

function setFilter(id) {
  statusFilter.value = id
  currentPage.value = 1
}

// ─── Modal state ──────────────────────────────────────────
const showFormModal        = ref(false)
const showViewModal        = ref(false)
const selectedContribution = ref(null)

function openCreateModal() {
  selectedContribution.value = null
  showFormModal.value = true
}
function openEditModal(c) {
  selectedContribution.value = c
  showFormModal.value = true
}
function openViewModal(c) {
  selectedContribution.value = c
  showViewModal.value = true
}
function fromViewToEdit(c) {
  showViewModal.value = false
  setTimeout(() => openEditModal(c), 220)
}
function onSaved() {
  showFormModal.value = false
  selectedContribution.value = null
  reloadList()
}
function reloadList() {
  router.reload({ only: ['contributions', 'stats'], preserveScroll: true })
}

// ─── Helpers ──────────────────────────────────────────────
function paidPercent(c) { return c.total_count ? Math.round((c.paid_count / c.total_count) * 100) : 0 }
function formatMoney(v) { return Number(v || 0).toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) }
function formatShort(v) {
  const n = Number(v || 0)
  if (n >= 1_000_000) return (n / 1_000_000).toFixed(1) + 'M'
  if (n >= 1_000) return (n / 1_000).toFixed(1) + 'K'
  return n.toFixed(0)
}
function formatShortDate(v) {
  if (!v) return '—'
  try { return new Date(v).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' }) }
  catch { return '—' }
}
function isPast(v) { return v && new Date(v) < new Date() }
</script>

<style scoped>
@keyframes pulse-opacity { 0%, 100% { opacity: 0.88; } 50% { opacity: 0.65; } }
.animate-overlay { animation: pulse-opacity 6s infinite ease-in-out; }
.no-scrollbar::-webkit-scrollbar { display: none; }
.no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>