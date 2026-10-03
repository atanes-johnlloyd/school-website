<template>
  <AdminLayout>
    <div class="space-y-6 pb-10 font-['Inter']">

      <!-- Hero Banner -->
      <div
        class="relative overflow-hidden w-full rounded-2xl sm:rounded-3xl shadow-lg border border-[#006907]/20 dark:border-none min-h-[200px] sm:min-h-[220px] flex flex-col justify-center p-4 sm:p-8 md:p-10">
        <!-- Background Image Layer (Audit Trails / Security & Compliance Theme) -->
        <img
          :src="heroImage || 'https://images.unsplash.com/photo-1526374965328-7f61d4dc18c5?q=80&w=1600&auto=format&fit=crop'"
          alt="Audit Logs Hero" class="absolute inset-0 w-full h-full object-cover z-0 object-center" />

        <!-- Animated Dark Green Overlay -->
        <div class="absolute inset-0 bg-[#004d05]/85 dark:bg-[#152B1C]/90 animate-overlay z-0 mix-blend-multiply"></div>

        <!-- Ambient Light Glow Highlights -->
        <div
          class="absolute -right-10 -bottom-10 w-72 h-72 rounded-full bg-emerald-300/20 blur-3xl pointer-events-none z-0">
        </div>
        <div class="absolute right-24 -top-16 w-80 h-80 rounded-full bg-[#F9C20C]/20 blur-3xl pointer-events-none z-0">
        </div>

        <!-- Content Container -->
        <div class="relative z-10 w-full max-w-4xl space-y-2.5 sm:space-y-3.5">
          <!-- Top Capsule Badges -->
          <div class="flex flex-wrap items-center gap-2 sm:gap-2.5">
            <span
              class="inline-flex items-center gap-1.5 bg-[#F9C20C] text-[#2C3E2D] font-black text-[10px] sm:text-xs px-3 sm:px-4 py-1 sm:py-1.5 rounded-full shadow-sm tracking-wide uppercase">
              <span>🛡️</span> SYSTEM
            </span>

            <span
              class="inline-flex items-center gap-2 bg-black/30 dark:bg-black/50 backdrop-blur-md text-white border border-white/20 text-[10px] sm:text-xs font-semibold px-3 sm:px-4 py-1 sm:py-1.5 rounded-full shadow-sm">
              <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
              Audit Logs
            </span>
          </div>

          <!-- Title & Subtitle -->
          <div class="space-y-1 sm:space-y-1.5">
            <h1
              class="font-['Anton'] text-2xl sm:text-4xl md:text-5xl tracking-wide uppercase flex items-center gap-2 select-none flex-wrap leading-none text-white drop-shadow-md">
              <span>AUDIT</span>
              <span class="text-[#F9C20C] drop-shadow-[0_2px_8px_rgba(249,194,12,0.4)]">LOGS</span>
            </h1>
            <p class="text-white/90 text-xs sm:text-sm md:text-base leading-relaxed font-medium">
              Read-only history of every model change — for compliance, debugging, and accountability.
            </p>
          </div>
        </div>
      </div>

      <!-- Stat cards -->
      <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4">

        <button @click="setActionFilter('')" :class="[
          'text-left p-5 rounded-2xl border shadow-xs flex items-center justify-between transition-colors cursor-pointer',
          filters.action === '' && !filters.when
            ? 'bg-[#004d08]/5 dark:bg-[#86EFAC]/5 border-[#004d08]/40 dark:border-[#86EFAC]/30'
            : 'bg-white dark:bg-[#2D3A31] border-gray-200/80 dark:border-[#3F4F43] hover:border-gray-300 dark:hover:border-gray-500'
        ]">
          <div class="space-y-1">
            <p class="text-[10px] font-medium uppercase text-gray-400 tracking-wider">Total Logs</p>
            <p class="text-2xl font-medium text-gray-900 dark:text-white">{{ counts.total || 0 }}</p>
            <span class="text-[10px] text-gray-500 dark:text-gray-400 font-normal">All Time</span>
          </div>
          <div
            class="w-12 h-12 rounded-2xl bg-gray-50 dark:bg-[#1C261E] border border-gray-100 dark:border-[#3F4F43] flex items-center justify-center text-gray-600 dark:text-gray-300 shrink-0">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round"
                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
            </svg>
          </div>
        </button>

        <button @click="setWhenFilter('today')" :class="[
          'text-left p-5 rounded-2xl border shadow-xs flex items-center justify-between transition-colors cursor-pointer',
          filters.when === 'today'
            ? 'bg-sky-50/60 dark:bg-sky-950/20 border-sky-300 dark:border-sky-800'
            : 'bg-white dark:bg-[#2D3A31] border-gray-200/80 dark:border-[#3F4F43] hover:border-gray-300 dark:hover:border-gray-500'
        ]">
          <div class="space-y-1">
            <p class="text-[10px] font-medium uppercase text-sky-500 tracking-wider">Today</p>
            <p class="text-2xl font-medium text-gray-900 dark:text-white">{{ counts.today || 0 }}</p>
            <span class="text-[10px] text-sky-600 dark:text-sky-400 font-normal">Since Midnight</span>
          </div>
          <div
            class="w-12 h-12 rounded-2xl bg-sky-50 dark:bg-[#1C261E] border border-sky-100 dark:border-[#3F4F43] flex items-center justify-center text-sky-600 dark:text-sky-400 shrink-0">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </div>
        </button>

        <button @click="setWhenFilter('week')" :class="[
          'text-left p-5 rounded-2xl border shadow-xs flex items-center justify-between transition-colors cursor-pointer',
          filters.when === 'week'
            ? 'bg-violet-50/60 dark:bg-violet-950/20 border-violet-300 dark:border-violet-800'
            : 'bg-white dark:bg-[#2D3A31] border-gray-200/80 dark:border-[#3F4F43] hover:border-gray-300 dark:hover:border-gray-500'
        ]">
          <div class="space-y-1">
            <p class="text-[10px] font-medium uppercase text-violet-500 tracking-wider">This Week</p>
            <p class="text-2xl font-medium text-gray-900 dark:text-white">{{ counts.this_week || 0 }}</p>
            <span class="text-[10px] text-violet-600 dark:text-violet-400 font-normal">Last 7 Days</span>
          </div>
          <div
            class="w-12 h-12 rounded-2xl bg-violet-50 dark:bg-[#1C261E] border border-violet-100 dark:border-[#3F4F43] flex items-center justify-center text-violet-600 dark:text-violet-400 shrink-0">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round"
                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
          </div>
        </button>

        <button @click="setActionFilter('created')" :class="[
          'text-left p-5 rounded-2xl border shadow-xs flex items-center justify-between transition-colors cursor-pointer',
          filters.action === 'created'
            ? 'bg-emerald-50/60 dark:bg-emerald-950/20 border-emerald-300 dark:border-emerald-800'
            : 'bg-white dark:bg-[#2D3A31] border-gray-200/80 dark:border-[#3F4F43] hover:border-gray-300 dark:hover:border-gray-500'
        ]">
          <div class="space-y-1">
            <p class="text-[10px] font-medium uppercase text-emerald-500 tracking-wider">Created</p>
            <p class="text-2xl font-medium text-gray-900 dark:text-white">{{ counts.created || 0 }}</p>
            <span class="text-[10px] text-emerald-600 dark:text-emerald-400 font-normal">New Records</span>
          </div>
          <div
            class="w-12 h-12 rounded-2xl bg-emerald-50 dark:bg-[#1C261E] border border-emerald-100 dark:border-[#3F4F43] flex items-center justify-center text-emerald-600 dark:text-emerald-400 shrink-0">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
            </svg>
          </div>
        </button>

        <button @click="setActionFilter('deleted')" :class="[
          'text-left p-5 rounded-2xl border shadow-xs flex items-center justify-between transition-colors cursor-pointer',
          filters.action === 'deleted'
            ? 'bg-red-50/60 dark:bg-red-950/20 border-red-300 dark:border-red-800'
            : 'bg-white dark:bg-[#2D3A31] border-gray-200/80 dark:border-[#3F4F43] hover:border-gray-300 dark:hover:border-gray-500'
        ]">
          <div class="space-y-1">
            <p class="text-[10px] font-medium uppercase text-red-500 tracking-wider">Deleted</p>
            <p class="text-2xl font-medium text-gray-900 dark:text-white">{{ counts.deleted || 0 }}</p>
            <span class="text-[10px] text-red-600 dark:text-red-400 font-normal">Removals</span>
          </div>
          <div
            class="w-12 h-12 rounded-2xl bg-red-50 dark:bg-[#1C261E] border border-red-100 dark:border-[#3F4F43] flex items-center justify-center text-red-600 dark:text-red-400 shrink-0">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round"
                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M1 7h22M9 3h6" />
            </svg>
          </div>
        </button>

      </div>

      <!-- Main card -->
      <div
        class="bg-white dark:bg-[#2D3A31] rounded-2xl border border-gray-200/80 dark:border-[#3F4F43] shadow-xs overflow-hidden">

        <!-- Controls -->
        <div
          class="p-4 sm:p-5 border-b border-gray-100 dark:border-[#3F4F43] bg-gray-50/50 dark:bg-[#232D26]/50 flex flex-col gap-3">
          <div class="flex flex-col lg:flex-row gap-3 justify-between lg:items-center">
            <div>
              <h2 class="text-xs font-medium text-gray-900 dark:text-white uppercase tracking-wider">Activity History
              </h2>
              <p class="text-[11px] text-gray-500 dark:text-gray-400 font-normal">Read-only record of every model change
              </p>
            </div>

            <div class="flex flex-wrap sm:flex-nowrap items-center gap-2">

              <select v-model="filters.action" @change="fetchList()"
                class="appearance-none pl-3 pr-8 py-2 text-xs bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white rounded-xl focus:ring-2 focus:ring-[#004d08] focus:outline-none font-normal cursor-pointer">
                <option value="">All Actions</option>
                <option value="created">Created</option>
                <option value="updated">Updated</option>
                <option value="deleted">Deleted</option>
                <option value="restored">Restored</option>
              </select>

              <select v-model="filters.user_id" @change="fetchList()"
                class="appearance-none pl-3 pr-8 py-2 text-xs bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white rounded-xl focus:ring-2 focus:ring-[#004d08] focus:outline-none font-normal cursor-pointer max-w-[180px] truncate">
                <option value="">All Users</option>
                <option v-for="u in users" :key="u.id" :value="u.id">{{ u.name }}</option>
              </select>

              <select v-model="filters.model_type" @change="fetchList()"
                class="appearance-none pl-3 pr-8 py-2 text-xs bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white rounded-xl focus:ring-2 focus:ring-[#004d08] focus:outline-none font-normal cursor-pointer max-w-[180px] truncate">
                <option value="">All Models</option>
                <option v-for="m in modelTypes" :key="m.value" :value="m.value">{{ m.label }}</option>
              </select>

              <div class="relative w-full sm:w-56">
                <svg class="w-4 h-4 absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 dark:text-gray-500"
                  fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round"
                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <input v-model="filters.search" @input="debouncedFetch" type="text"
                  placeholder="Search action, IP, user..."
                  class="w-full pl-10 pr-8 py-2 text-xs bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white placeholder-gray-400 rounded-xl focus:ring-2 focus:ring-[#004d08] dark:focus:ring-[#86EFAC] focus:outline-none font-normal" />
                <button v-if="filters.search" @click="filters.search = ''; fetchList()"
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

          <!-- Date range row -->
          <div class="flex items-center gap-2 text-[11px] text-gray-500 dark:text-gray-400">
            <span class="font-medium uppercase tracking-wider text-[10px]">Date Range</span>
            <input v-model="filters.date_from" @change="fetchList()" type="date"
              class="px-2.5 py-1.5 text-[11px] bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white rounded-lg focus:ring-2 focus:ring-[#004d08] focus:outline-none cursor-pointer" />
            <span>→</span>
            <input v-model="filters.date_to" @change="fetchList()" type="date"
              class="px-2.5 py-1.5 text-[11px] bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white rounded-lg focus:ring-2 focus:ring-[#004d08] focus:outline-none cursor-pointer" />
            <button v-if="filters.date_from || filters.date_to"
              @click="filters.date_from = ''; filters.date_to = ''; fetchList()"
              class="text-[10px] uppercase tracking-wider text-gray-400 hover:text-red-600 dark:hover:text-red-400 transition-colors cursor-pointer">
              Clear
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
                  <button @click="setSort('created_at')"
                    class="inline-flex items-center gap-1 hover:text-gray-700 dark:hover:text-gray-200 transition-colors cursor-pointer">
                    When
                    <SortIcon field="created_at" />
                  </button>
                </th>
                <th class="py-3.5 px-5">User</th>
                <th class="py-3.5 px-5 text-center">
                  <button @click="setSort('action')"
                    class="inline-flex items-center gap-1 hover:text-gray-700 dark:hover:text-gray-200 transition-colors cursor-pointer">
                    Action
                    <SortIcon field="action" />
                  </button>
                </th>
                <th class="py-3.5 px-5">
                  <button @click="setSort('auditable_type')"
                    class="inline-flex items-center gap-1 hover:text-gray-700 dark:hover:text-gray-200 transition-colors cursor-pointer">
                    Model
                    <SortIcon field="auditable_type" />
                  </button>
                </th>
                <th class="py-3.5 px-5">IP</th>
                <th class="py-3.5 px-5 text-right">Actions</th>
              </tr>
            </thead>
            <tbody
              class="divide-y divide-gray-100 dark:divide-[#3F4F43] text-xs font-normal text-gray-800 dark:text-gray-200">

              <tr v-for="log in logs.data" :key="log.id"
                class="hover:bg-gray-50/80 dark:hover:bg-white/5 transition-colors cursor-pointer"
                @click="openLog(log)">
                <td class="py-4 px-5 whitespace-nowrap">
                  <p class="text-xs text-gray-900 dark:text-white">{{ formatShortDate(log.created_at) }}</p>
                  <p class="text-[10px] text-gray-400">{{ formatTime(log.created_at) }}</p>
                </td>

                <td class="py-4 px-5">
                  <div class="flex items-center gap-3">
                    <div
                      class="w-8 h-8 rounded-full bg-emerald-100 dark:bg-[#1C261E] text-[#004d08] dark:text-[#86EFAC] border border-emerald-200 dark:border-[#3F4F43] flex items-center justify-center font-medium text-xs shrink-0">
                      {{ initials(log.user_name) }}
                    </div>
                    <div class="min-w-0">
                      <p class="text-xs font-medium text-gray-900 dark:text-white truncate">{{ log.user_name }}</p>
                      <p v-if="log.user_email" class="text-[10px] text-gray-500 dark:text-gray-400 truncate">{{
                        log.user_email }}</p>
                    </div>
                  </div>
                </td>

                <td class="py-4 px-5 text-center">
                  <span
                    class="px-2.5 py-1 rounded-full text-[10px] font-normal uppercase tracking-wider inline-flex items-center gap-1.5 border"
                    :class="actionBadge(log.action)">
                    <span :class="['w-1.5 h-1.5 rounded-full', actionDot(log.action)]"></span>
                    {{ log.action }}
                  </span>
                </td>

                <td class="py-4 px-5">
                  <p class="text-xs text-gray-900 dark:text-white">{{ log.auditable_label || '—' }}</p>
                  <p v-if="log.auditable_id" class="text-[10px] text-gray-400 font-mono">ID {{ log.auditable_id }}</p>
                </td>

                <td class="py-4 px-5 text-[10px] text-gray-500 dark:text-gray-400 font-mono">
                  {{ log.ip_address || '—' }}
                </td>

                <td class="py-4 px-5 text-right" @click.stop>
                  <button @click="openLog(log)"
                    class="px-2.5 py-1 text-[10px] font-normal text-sky-700 dark:text-sky-300 bg-sky-50 hover:bg-sky-100 dark:bg-sky-950/50 rounded-lg border border-sky-200 dark:border-sky-800 transition-colors cursor-pointer">
                    View
                  </button>
                </td>
              </tr>

              <tr v-if="!logs.data || logs.data.length === 0">
                <td colspan="6" class="py-16 text-center text-gray-400 dark:text-gray-500 font-normal">
                  <p class="text-xs">No audit log entries match your filters.</p>
                  <p class="text-[11px] mt-0.5">Change tracking activates as soon as records are created or updated.</p>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Pagination -->
        <div v-if="logs.total > 0"
          class="px-5 py-3.5 bg-gray-50/50 dark:bg-[#232D26]/50 border-t border-gray-100 dark:border-[#3F4F43] flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-gray-500 dark:text-gray-400">
          <div class="flex items-center gap-3">
            <span>
              Showing <span class="font-semibold text-gray-900 dark:text-white">{{ logs.from || 0 }}</span>
              to <span class="font-semibold text-gray-900 dark:text-white">{{ logs.to || 0 }}</span>
              of <span class="font-semibold text-gray-900 dark:text-white">{{ logs.total }}</span> entries
            </span>
            <select v-model.number="filters.per_page" @change="changePageSize"
              class="appearance-none pl-2 pr-7 py-1 text-[11px] bg-white dark:bg-[#2D3A31] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white rounded-lg focus:ring-2 focus:ring-[#004d08] focus:outline-none cursor-pointer">
              <option :value="15">15</option>
              <option :value="25">25</option>
              <option :value="50">50</option>
              <option :value="100">100</option>
            </select>
          </div>
          <div class="flex items-center gap-1">
            <button @click="changePage(logs.current_page - 1)" :disabled="logs.current_page <= 1"
              class="px-3 py-1.5 rounded-lg border border-gray-200 dark:border-[#3F4F43] bg-white dark:bg-[#2D3A31] disabled:opacity-40 cursor-pointer disabled:cursor-not-allowed">
              Previous
            </button>
            <span class="px-3 py-1.5 font-medium text-gray-900 dark:text-white">
              Page {{ logs.current_page }} of {{ logs.last_page }}
            </span>
            <button @click="changePage(logs.current_page + 1)" :disabled="logs.current_page >= logs.last_page"
              class="px-3 py-1.5 rounded-lg border border-gray-200 dark:border-[#3F4F43] bg-white dark:bg-[#2D3A31] disabled:opacity-40 cursor-pointer disabled:cursor-not-allowed">
              Next
            </button>
          </div>
        </div>

      </div>

      <!-- Detail Modal -->
      <ViewAuditLogModal :show="showDetailModal" :log="selectedLog"
        @close="showDetailModal = false; selectedLog = null" />
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref, reactive, computed, onMounted, h } from 'vue'
import axios from 'axios'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import ViewAuditLogModal from './ViewAuditLogModal.vue'
import { useFlash } from '@/Composables/useFlash'

const flash = useFlash()
const props = defineProps({
  users: { type: Array, default: () => [] },
  modelTypes: { type: Array, default: () => [] },
})

const emptyPaginator = () => ({ data: [], current_page: 1, last_page: 1, total: 0, from: 0, to: 0 })

const logs = ref(emptyPaginator())
const counts = ref({ total: 0, today: 0, this_week: 0, created: 0, deleted: 0 })

const filters = reactive({
  search: '',
  action: '',
  user_id: '',
  model_type: '',
  date_from: '',
  date_to: '',
  when: '',    // synthetic — converted to date_from server-side
  per_page: 25,
  page: 1,
  sort_by: 'created_at',
  sort_dir: 'desc',
})

let searchTimer = null

const showDetailModal = ref(false)
const selectedLog = ref(null)

const hasActiveFilters = computed(() =>
  filters.search !== '' || filters.action !== '' || filters.user_id !== '' ||
  filters.model_type !== '' || filters.date_from !== '' || filters.date_to !== '' ||
  filters.when !== ''
)

const fetchList = async () => {
  const params = { ...filters }

  // Convert the "when" pseudo-filter to a date range
  if (params.when === 'today') {
    params.date_from = new Date().toISOString().slice(0, 10)
    params.date_to = params.date_from
  } else if (params.when === 'week') {
    const d = new Date()
    d.setDate(d.getDate() - 7)
    params.date_from = d.toISOString().slice(0, 10)
    params.date_to = ''
  }
  delete params.when

  // Strip empty values
  for (const k of Object.keys(params)) {
    if (params[k] === '' || params[k] === false || params[k] === null || params[k] === undefined) {
      delete params[k]
    }
  }

  try {
    const { data } = await axios.get('/admin/audit-logs/list', { params })
    const p = data?.logs
    logs.value = p && Array.isArray(p.data) ? p : emptyPaginator()
    counts.value = data?.counts ?? counts.value
  } catch (e) {
    console.error('Failed to load audit logs:', e)
    logs.value = emptyPaginator()
    flash.error('Failed to load audit logs. Please refresh.')   // ✅ NEW
  }
}

const debouncedFetch = () => {
  clearTimeout(searchTimer)
  searchTimer = setTimeout(() => { filters.page = 1; fetchList() }, 300)
}

const resetFilters = () => {
  filters.action = ''
  filters.user_id = ''
  filters.model_type = ''
  filters.date_from = ''
  filters.date_to = ''
  filters.when = ''
  filters.page = 1
}

const setActionFilter = (action) => {
  resetFilters()
  filters.action = action
  fetchList()
}

const setWhenFilter = (when) => {
  resetFilters()
  filters.when = when
  fetchList()
}

const setSort = (field) => {
  if (filters.sort_by === field) {
    filters.sort_dir = filters.sort_dir === 'asc' ? 'desc' : 'asc'
  } else {
    filters.sort_by = field
    filters.sort_dir = field === 'created_at' ? 'desc' : 'asc'
  }
  filters.page = 1
  fetchList()
}

const changePage = (p) => {
  if (p < 1 || p > logs.value.last_page) return
  filters.page = p
  fetchList()
}

const changePageSize = () => { filters.page = 1; fetchList() }

const clearFilters = () => {
  filters.search = ''
  resetFilters()
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

const initials = (name) => {
  if (!name) return '?'
  const parts = name.trim().split(' ')
  return parts.length === 1
    ? parts[0].charAt(0).toUpperCase()
    : (parts[0].charAt(0) + parts[parts.length - 1].charAt(0)).toUpperCase()
}

const actionBadge = (action) => ({
  created: 'bg-emerald-100 dark:bg-emerald-950/60 text-emerald-800 dark:text-emerald-300 border-emerald-200 dark:border-emerald-800',
  updated: 'bg-sky-50 dark:bg-sky-950/50 text-sky-700 dark:text-sky-300 border-sky-200 dark:border-sky-800',
  deleted: 'bg-red-50 dark:bg-red-950/50 text-red-700 dark:text-red-300 border-red-200 dark:border-red-800',
  restored: 'bg-violet-50 dark:bg-violet-950/50 text-violet-700 dark:text-violet-300 border-violet-200 dark:border-violet-800',
}[action] || 'bg-gray-100 dark:bg-[#232D26] text-gray-600 dark:text-gray-400 border-gray-200 dark:border-[#3F4F43]')

const actionDot = (action) => ({
  created: 'bg-emerald-500', updated: 'bg-sky-500', deleted: 'bg-red-500', restored: 'bg-violet-500',
}[action] || 'bg-gray-400')

const formatShortDate = (iso) => {
  if (!iso) return '—'
  try { return new Date(iso).toLocaleDateString('en-PH', { month: 'short', day: 'numeric', year: 'numeric' }) }
  catch { return iso }
}

const formatTime = (iso) => {
  if (!iso) return ''
  try { return new Date(iso).toLocaleTimeString('en-PH', { hour: 'numeric', minute: '2-digit' }) }
  catch { return '' }
}

const openLog = (log) => {
  selectedLog.value = log
  showDetailModal.value = true
}

onMounted(() => { fetchList() })
</script>