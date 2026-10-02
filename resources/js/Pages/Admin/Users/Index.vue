<template>
  <AdminLayout>
    <div class="space-y-6 max-w-7xl mx-auto pb-10 font-['Inter']">

      <!-- Hero Banner -->
      <div
        class="relative overflow-hidden w-full rounded-2xl sm:rounded-3xl shadow-lg border border-[#006907]/20 dark:border-none min-h-[220px] sm:min-h-[240px] flex flex-col justify-center p-4 sm:p-8 md:p-10">
        <!-- Background Image Layer (Access Control / Server Security Theme) -->
        <img
          :src="heroImage || 'https://images.unsplash.com/photo-1558494949-ef010cbdcc31?q=80&w=1600&auto=format&fit=crop'"
          alt="Admin Users Hero" class="absolute inset-0 w-full h-full object-cover z-0 object-center" />

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
                <span>🛡️️</span> ACCESS CONTROL
              </span>

              <span
                class="inline-flex items-center gap-2 bg-black/30 dark:bg-black/50 backdrop-blur-md text-white border border-white/20 text-[10px] sm:text-xs font-semibold px-3 sm:px-4 py-1 sm:py-1.5 rounded-full shadow-sm">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                Admin Users
              </span>
            </div>

            <!-- Title & Subtitle -->
            <div class="space-y-1 sm:space-y-1.5">
              <h1
                class="font-['Anton'] text-2xl sm:text-4xl md:text-5xl tracking-wide uppercase flex items-center gap-2 select-none flex-wrap leading-none text-white drop-shadow-md">
                <span>ADMIN</span>
                <span class="text-[#F9C20C] drop-shadow-[0_2px_8px_rgba(249,194,12,0.4)]">USERS</span>
              </h1>
              <p class="text-white/90 text-xs sm:text-sm md:text-base leading-relaxed font-medium">
                Manage administrative accounts, assign positions, and control platform permissions.
              </p>
            </div>
          </div>

          <!-- Action Button -->
          <div class="flex items-center shrink-0 pt-2 md:pt-0">
            <button @click="openCreateModal"
              class="inline-flex items-center justify-center gap-2 bg-[#F9C20C] hover:bg-[#e0ae0a] text-[#2C3E2D] font-black px-4 sm:px-5 py-2.5 sm:py-3 rounded-xl transition-all shadow-md active:scale-95 text-xs sm:text-sm cursor-pointer">
              <span class="text-base sm:text-lg leading-none">+</span>
              <span>Create Admin User</span>
            </button>
          </div>
        </div>
      </div>

      <!-- Stat Cards -->
      <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <button @click="setStatusFilter('')" :class="[
          'text-left p-5 rounded-2xl border shadow-xs flex items-center justify-between transition-colors cursor-pointer',
          filters.status === ''
            ? 'bg-[#004d08]/5 dark:bg-[#86EFAC]/5 border-[#004d08]/40 dark:border-[#86EFAC]/30'
            : 'bg-white dark:bg-[#2D3A31] border-gray-200/80 dark:border-[#3F4F43] hover:border-gray-300 dark:hover:border-gray-500'
        ]">
          <div class="space-y-1">
            <p class="text-[10px] font-medium uppercase text-gray-400 tracking-wider">Total Admins</p>
            <p class="text-2xl font-medium text-gray-900 dark:text-white">{{ counts.total || 0 }}</p>
            <span class="text-[10px] text-gray-500 dark:text-gray-400 font-normal">With admin role</span>
          </div>
          <div
            class="w-12 h-12 rounded-2xl bg-emerald-50 dark:bg-[#1C261E] border border-emerald-100 dark:border-[#3F4F43] flex items-center justify-center text-emerald-700 dark:text-[#86EFAC] shrink-0">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round"
                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
            </svg>
          </div>
        </button>

        <button @click="setStatusFilter('active')" :class="[
          'text-left p-5 rounded-2xl border shadow-xs flex items-center justify-between transition-colors cursor-pointer',
          filters.status === 'active'
            ? 'bg-emerald-50/60 dark:bg-emerald-950/20 border-emerald-300 dark:border-emerald-800'
            : 'bg-white dark:bg-[#2D3A31] border-gray-200/80 dark:border-[#3F4F43] hover:border-gray-300 dark:hover:border-gray-500'
        ]">
          <div class="space-y-1">
            <p class="text-[10px] font-medium uppercase text-emerald-500 tracking-wider">Active</p>
            <p class="text-2xl font-medium text-gray-900 dark:text-white">{{ counts.active || 0 }}</p>
            <span class="text-[10px] text-emerald-600 dark:text-emerald-400 font-normal">Can sign in</span>
          </div>
          <div
            class="w-12 h-12 rounded-2xl bg-emerald-50 dark:bg-[#1C261E] border border-emerald-100 dark:border-[#3F4F43] flex items-center justify-center text-emerald-600 dark:text-emerald-400 shrink-0">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </div>
        </button>

        <button @click="setStatusFilter('disabled')" :class="[
          'text-left p-5 rounded-2xl border shadow-xs flex items-center justify-between transition-colors cursor-pointer',
          filters.status === 'disabled'
            ? 'bg-amber-50/60 dark:bg-amber-950/20 border-amber-300 dark:border-amber-800'
            : 'bg-white dark:bg-[#2D3A31] border-gray-200/80 dark:border-[#3F4F43] hover:border-gray-300 dark:hover:border-gray-500'
        ]">
          <div class="space-y-1">
            <p class="text-[10px] font-medium uppercase text-amber-500 tracking-wider">Disabled</p>
            <p class="text-2xl font-medium text-gray-900 dark:text-white">{{ counts.disabled || 0 }}</p>
            <span class="text-[10px] text-amber-600 dark:text-amber-400 font-normal">Access revoked</span>
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

      <!-- Main Card -->
      <div
        class="bg-white dark:bg-[#2D3A31] rounded-2xl border border-gray-200/80 dark:border-[#3F4F43] shadow-xs overflow-hidden transition-colors">

        <!-- Controls -->
        <div
          class="p-4 sm:p-5 border-b border-gray-100 dark:border-[#3F4F43] bg-gray-50/50 dark:bg-[#232D26]/50 flex flex-col lg:flex-row gap-3 justify-between lg:items-center">
          <div>
            <h2 class="text-xs font-medium text-gray-900 dark:text-white uppercase tracking-wider">Admin Directory</h2>
            <p class="text-[11px] text-gray-500 dark:text-gray-400 font-normal">Search, filter, and manage admin
              accounts</p>
          </div>

          <div class="flex flex-wrap sm:flex-nowrap items-center gap-2 w-full lg:w-auto lg:justify-end">

            <!-- Position Filter -->
            <div class="relative">
              <select v-model="filters.position_id" @change="fetchUsers()"
                class="appearance-none pl-3 pr-8 py-2 text-xs bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white rounded-xl focus:ring-2 focus:ring-[#004d08] focus:outline-none font-normal cursor-pointer max-w-[200px] truncate">
                <option value="">All Positions</option>
                <option v-for="p in positions" :key="p.id" :value="p.id">{{ p.name }}</option>
              </select>
            </div>

            <!-- Search -->
            <div class="relative w-full sm:w-64">
              <svg class="w-4 h-4 absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 dark:text-gray-500"
                fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
              </svg>
              <input v-model="filters.search" @input="debouncedFetch" type="text" placeholder="Search name or email..."
                class="w-full pl-10 pr-8 py-2 text-xs bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white placeholder-gray-400 rounded-xl focus:ring-2 focus:ring-[#004d08] dark:focus:ring-[#86EFAC] focus:outline-none font-normal" />
              <button v-if="filters.search" @click="filters.search = ''; fetchUsers()"
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
                    User
                    <SortIcon field="name" />
                  </button>
                </th>
                <th class="py-3.5 px-5">
                  <button @click="setSort('email')"
                    class="inline-flex items-center gap-1 hover:text-gray-700 dark:hover:text-gray-200 transition-colors cursor-pointer">
                    Email
                    <SortIcon field="email" />
                  </button>
                </th>
                <th class="py-3.5 px-5">Position</th>
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
              <tr v-for="user in users.data" :key="user.id"
                class="hover:bg-gray-50/80 dark:hover:bg-white/5 transition-colors">
                <td class="py-4 px-5">
                  <div class="flex items-center gap-3">
                    <div
                      class="w-8 h-8 rounded-full bg-emerald-100 dark:bg-[#1C261E] text-[#004d08] dark:text-[#86EFAC] border border-emerald-200 dark:border-[#3F4F43] flex items-center justify-center font-medium text-xs shrink-0">
                      {{ getInitials(user.name) }}
                    </div>
                    <div class="min-w-0">
                      <span class="font-medium text-gray-900 dark:text-white block truncate">
                        {{ user.name }}
                        <span v-if="user.is_primary"
                          class="ml-1 text-[9px] font-semibold text-amber-600 dark:text-amber-400 uppercase tracking-wider">Primary</span>
                      </span>
                    </div>
                  </div>
                </td>

                <td class="py-4 px-5 text-gray-600 dark:text-gray-300">{{ user.email }}</td>

                <td class="py-4 px-5">
                  <span class="text-xs text-gray-900 dark:text-white font-medium">
                    {{ user.admin_position || '—' }}
                  </span>
                </td>

                <td class="py-4 px-5 text-center">
                  <span
                    class="px-2.5 py-1 rounded-full text-[10px] font-normal uppercase tracking-wider inline-flex items-center gap-1.5 border"
                    :class="user.status === 'active'
                      ? 'bg-emerald-100 dark:bg-emerald-950/60 text-emerald-800 dark:text-emerald-300 border-emerald-200 dark:border-emerald-800'
                      : 'bg-amber-50 dark:bg-amber-950/50 text-amber-700 dark:text-amber-300 border-amber-200 dark:border-amber-800'">
                    <span
                      :class="['w-1.5 h-1.5 rounded-full', user.status === 'active' ? 'bg-emerald-500' : 'bg-amber-500']"></span>
                    {{ user.status }}
                  </span>
                </td>

                <td class="py-4 px-5 text-right">
                  <div class="flex items-center justify-end gap-1.5">
                    <button @click="openViewModal(user)"
                      class="px-2.5 py-1 text-[10px] font-normal text-sky-700 dark:text-sky-300 bg-sky-50 hover:bg-sky-100 dark:bg-sky-950/50 rounded-lg border border-sky-200 dark:border-sky-800 transition-colors cursor-pointer">
                      View
                    </button>
                    <button @click="openEditModal(user)" :disabled="user.is_primary"
                      :title="user.is_primary ? 'Primary admin cannot be edited' : ''"
                      class="px-2.5 py-1 text-[10px] font-normal text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-[#232D26] hover:bg-gray-200 dark:hover:bg-white/10 rounded-lg transition-colors cursor-pointer disabled:opacity-40 disabled:cursor-not-allowed">
                      Edit
                    </button>
                    <button @click="openStatusModal(user)" :disabled="user.is_primary || user.id === currentUserId"
                      :title="user.is_primary ? 'Cannot disable primary admin' : (user.id === currentUserId ? 'Cannot disable your own account' : '')"
                      class="px-2.5 py-1 text-[10px] font-normal rounded-lg border transition-colors cursor-pointer disabled:opacity-40 disabled:cursor-not-allowed"
                      :class="user.status === 'active'
                        ? 'text-red-700 dark:text-red-400 bg-red-50 hover:bg-red-100 dark:bg-red-950/50 border-red-200 dark:border-red-900'
                        : 'text-emerald-700 dark:text-emerald-400 bg-emerald-50 hover:bg-emerald-100 dark:bg-emerald-950/50 border-emerald-200 dark:border-emerald-800'">
                      {{ user.status === 'active' ? 'Disable' : 'Reactivate' }}
                    </button>
                  </div>
                </td>
              </tr>

              <tr v-if="!users.data || users.data.length === 0">
                <td colspan="5" class="py-12 text-center text-gray-400 dark:text-gray-500 font-normal">
                  <p class="text-xs">No admin users found.</p>
                  <p class="text-[11px] mt-0.5">Try adjusting your filters or search keywords.</p>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Pagination -->
        <div v-if="users.total > 0"
          class="px-5 py-3.5 bg-gray-50/50 dark:bg-[#232D26]/50 border-t border-gray-100 dark:border-[#3F4F43] flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-gray-500 dark:text-gray-400">
          <div class="flex items-center gap-3">
            <span>
              Showing <span class="font-semibold text-gray-900 dark:text-white">{{ users.from || 0 }}</span>
              to <span class="font-semibold text-gray-900 dark:text-white">{{ users.to || 0 }}</span>
              of <span class="font-semibold text-gray-900 dark:text-white">{{ users.total }}</span> entries
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
            <button @click="changePage(users.current_page - 1)" :disabled="users.current_page <= 1"
              class="px-3 py-1.5 rounded-lg border border-gray-200 dark:border-[#3F4F43] bg-white dark:bg-[#2D3A31] text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-white/5 disabled:opacity-40 cursor-pointer disabled:cursor-not-allowed transition-colors">
              Previous
            </button>
            <span class="px-3 py-1.5 font-medium text-gray-900 dark:text-white">
              Page {{ users.current_page }} of {{ users.last_page }}
            </span>
            <button @click="changePage(users.current_page + 1)" :disabled="users.current_page >= users.last_page"
              class="px-3 py-1.5 rounded-lg border border-gray-200 dark:border-[#3F4F43] bg-white dark:bg-[#2D3A31] text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-white/5 disabled:opacity-40 cursor-pointer disabled:cursor-not-allowed transition-colors">
              Next
            </button>
          </div>
        </div>
      </div>

      <!-- Modals -->
      <UserFormModal :show="showFormModal" :user="selectedUser" :positions="positions"
        @close="showFormModal = false; selectedUser = null" @saved="fetchUsers" />

      <ViewUserModal :show="showViewModal" :user="selectedUser" @close="showViewModal = false; selectedUser = null"
        @edit="fromViewToEdit" />

      <ToggleStatusModal :show="showStatusModal" :user="selectedUser"
        @close="showStatusModal = false; selectedUser = null" @changed="fetchUsers" />
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref, reactive, computed, onMounted, h } from 'vue'
import axios from 'axios'
import { usePage } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import UserFormModal from './UserFormModal.vue'
import ViewUserModal from './ViewUserModal.vue'
import ToggleStatusModal from './ToggleStatusModal.vue'

const props = defineProps({
  positions: { type: Array, default: () => [] },
})

const page = usePage()
const currentUserId = computed(() => page.props.auth?.user?.id ?? null)

const emptyPaginator = () => ({ data: [], current_page: 1, last_page: 1, total: 0, from: 0, to: 0 })

const users = ref(emptyPaginator())
const counts = ref({ total: 0, active: 0, disabled: 0 })

const filters = reactive({
  search: '',
  status: '',
  position_id: '',
  per_page: 10,
  page: 1,
  sort_by: 'created_at',
  sort_dir: 'desc',
})

let searchTimeout = null

const showFormModal = ref(false)
const showViewModal = ref(false)
const showStatusModal = ref(false)
const selectedUser = ref(null)

const hasActiveFilters = computed(() =>
  filters.search !== '' || filters.status !== '' || filters.position_id !== ''
)

const fetchUsers = async () => {
  try {
    const { data } = await axios.get('/admin/users/list', { params: filters })
    const p = data?.users
    users.value = p && Array.isArray(p.data) ? p : emptyPaginator()
    counts.value = data?.counts ?? counts.value
  } catch (e) {
    console.error('Failed to load admin users:', e)
    users.value = emptyPaginator()
  }
}

const debouncedFetch = () => {
  clearTimeout(searchTimeout)
  searchTimeout = setTimeout(() => { filters.page = 1; fetchUsers() }, 300)
}

const setStatusFilter = (status) => { filters.status = status; filters.page = 1; fetchUsers() }

const setSort = (field) => {
  if (filters.sort_by === field) {
    filters.sort_dir = filters.sort_dir === 'asc' ? 'desc' : 'asc'
  } else {
    filters.sort_by = field
    filters.sort_dir = field === 'created_at' ? 'desc' : 'asc'
  }
  filters.page = 1
  fetchUsers()
}

const changePage = (page) => {
  if (page < 1 || page > users.value.last_page) return
  filters.page = page
  fetchUsers()
}

const changePageSize = () => { filters.page = 1; fetchUsers() }

const clearFilters = () => {
  filters.search = ''
  filters.status = ''
  filters.position_id = ''
  filters.page = 1
  fetchUsers()
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

const getInitials = (name) => {
  if (!name) return 'U'
  return name.split(' ').map(n => n[0]).join('').substring(0, 2).toUpperCase()
}

const openCreateModal = () => { selectedUser.value = null; showFormModal.value = true }

const openEditModal = (user) => {
  if (user.is_primary) return
  selectedUser.value = user
  showFormModal.value = true
}

const openViewModal = (user) => { selectedUser.value = user; showViewModal.value = true }

const openStatusModal = (user) => {
  if (user.is_primary || user.id === currentUserId.value) return
  selectedUser.value = user
  showStatusModal.value = true
}

const fromViewToEdit = (user) => {
  showViewModal.value = false
  setTimeout(() => openEditModal(user), 220)
}

onMounted(() => { fetchUsers() })
</script>