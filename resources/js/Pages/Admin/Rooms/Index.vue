<template>
  <AdminLayout>
    <div class="space-y-6 pb-10 font-['Inter']">

      <!-- Compact Hero -->
      <div class="relative overflow-hidden bg-[#004d08] text-white rounded-2xl p-6 md:p-8 shadow-lg border border-emerald-900/40">
        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-4">
          <div>
            <div class="flex items-center gap-2 mb-2">
              <span class="bg-amber-400/20 text-amber-300 text-xs font-medium px-2.5 py-0.5 rounded-md border border-amber-400/30">
                ACADEMICS
              </span>
              <span class="text-emerald-200 text-xs font-normal">&bull; Physical Spaces</span>
            </div>
            <h1 class="font-['Anton'] text-3xl md:text-4xl tracking-wide uppercase">
              Campus <span class="text-amber-400">Rooms</span>
            </h1>
            <p class="text-emerald-100/80 text-sm mt-1 max-w-xl font-normal">
              Manage classrooms, laboratories, and workshops used for class scheduling.
            </p>
          </div>

          <button @click="openCreateModal"
                  class="inline-flex items-center justify-center gap-2 bg-amber-400 hover:bg-amber-300 text-[#004d08] font-medium px-5 py-3 rounded-xl transition-all shadow-md active:scale-95 text-sm cursor-pointer shrink-0">
            <span class="text-lg leading-none">+</span>
            <span>New Room</span>
          </button>
        </div>
        <div class="absolute -right-6 -bottom-8 opacity-10 text-9xl font-['Anton'] pointer-events-none select-none text-white">
          ROOMS
        </div>
      </div>

      <!-- Main Card -->
      <div class="bg-white dark:bg-[#2D3A31] rounded-2xl border border-gray-200/80 dark:border-[#3F4F43] shadow-xs overflow-hidden transition-colors">

        <!-- Controls -->
        <div class="p-4 sm:p-5 border-b border-gray-100 dark:border-[#3F4F43] bg-gray-50/50 dark:bg-[#232D26]/50 flex flex-col lg:flex-row gap-3 justify-between lg:items-center">
          <div>
            <h2 class="text-xs font-medium text-gray-900 dark:text-white uppercase tracking-wider">Rooms Directory</h2>
            <p class="text-[11px] text-gray-500 dark:text-gray-400 font-normal">
              {{ counts.total || 0 }} rooms total
              <span v-if="counts.active !== undefined"> &bull; {{ counts.active }} active</span>
            </p>
          </div>

          <div class="flex flex-wrap sm:flex-nowrap items-center gap-2 w-full lg:w-auto lg:justify-end">

            <!-- Type filter -->
            <div class="relative">
              <select v-model="filters.type" @change="fetchRooms()"
                      class="appearance-none pl-3 pr-8 py-2 text-xs bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white rounded-xl focus:ring-2 focus:ring-[#004d08] focus:outline-none font-normal cursor-pointer">
                <option value="">All Types</option>
                <option value="regular">Regular</option>
                <option value="lecture">Lecture</option>
                <option value="laboratory">Laboratory</option>
                <option value="computer_lab">Computer Lab</option>
                <option value="science_lab">Science Lab</option>
                <option value="workshop">Workshop</option>
              </select>
            </div>

            <!-- Building filter -->
            <div v-if="buildings.length" class="relative">
              <select v-model="filters.building" @change="fetchRooms()"
                      class="appearance-none pl-3 pr-8 py-2 text-xs bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white rounded-xl focus:ring-2 focus:ring-[#004d08] focus:outline-none font-normal cursor-pointer max-w-[180px] truncate">
                <option value="">All Buildings</option>
                <option v-for="b in buildings" :key="b" :value="b">{{ b }}</option>
              </select>
            </div>

            <!-- Search -->
            <div class="relative w-full sm:w-56">
              <svg class="w-4 h-4 absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
              </svg>
              <input v-model="filters.search" @input="debouncedFetch" type="text"
                     placeholder="Search code, name..."
                     class="w-full pl-10 pr-8 py-2 text-xs bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white placeholder-gray-400 rounded-xl focus:ring-2 focus:ring-[#004d08] dark:focus:ring-[#86EFAC] focus:outline-none font-normal" />
              <button v-if="filters.search" @click="filters.search = ''; fetchRooms()"
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
              <tr class="border-b border-gray-100 dark:border-[#3F4F43] bg-gray-50/50 dark:bg-[#232D26]/50 text-[10px] font-medium uppercase text-gray-400 tracking-wider">
                <th class="py-3.5 px-5">
                  <button @click="setSort('code')" class="inline-flex items-center gap-1 hover:text-gray-700 dark:hover:text-gray-200 transition-colors cursor-pointer">
                    Code <SortIcon field="code" />
                  </button>
                </th>
                <th class="py-3.5 px-5">
                  <button @click="setSort('name')" class="inline-flex items-center gap-1 hover:text-gray-700 dark:hover:text-gray-200 transition-colors cursor-pointer">
                    Name <SortIcon field="name" />
                  </button>
                </th>
                <th class="py-3.5 px-5">
                  <button @click="setSort('type')" class="inline-flex items-center gap-1 hover:text-gray-700 dark:hover:text-gray-200 transition-colors cursor-pointer">
                    Type <SortIcon field="type" />
                  </button>
                </th>
                <th class="py-3.5 px-5">
                  <button @click="setSort('building')" class="inline-flex items-center gap-1 hover:text-gray-700 dark:hover:text-gray-200 transition-colors cursor-pointer">
                    Location <SortIcon field="building" />
                  </button>
                </th>
                <th class="py-3.5 px-5 text-center">
                  <button @click="setSort('capacity')" class="inline-flex items-center gap-1 hover:text-gray-700 dark:hover:text-gray-200 transition-colors cursor-pointer">
                    Capacity <SortIcon field="capacity" />
                  </button>
                </th>
                <th class="py-3.5 px-5 text-center">
                  <button @click="setSort('is_active')" class="inline-flex items-center gap-1 hover:text-gray-700 dark:hover:text-gray-200 transition-colors cursor-pointer">
                    Status <SortIcon field="is_active" />
                  </button>
                </th>
                <th class="py-3.5 px-5 text-right">Actions</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-[#3F4F43] text-xs font-normal text-gray-800 dark:text-gray-200">
              <tr v-for="room in rooms.data" :key="room.id" class="hover:bg-gray-50/80 dark:hover:bg-white/5 transition-colors">
                <td class="py-4 px-5">
                  <span class="font-mono font-semibold text-gray-900 dark:text-white text-xs bg-gray-100 dark:bg-[#232D26] px-2.5 py-1 rounded-lg border border-gray-200 dark:border-[#3F4F43]">
                    {{ room.code }}
                  </span>
                </td>
                <td class="py-4 px-5 text-gray-900 dark:text-white font-medium">{{ room.name }}</td>
                <td class="py-4 px-5">
                  <span class="inline-flex items-center gap-1.5 text-[11px] text-gray-700 dark:text-gray-300">
                    <span class="w-1.5 h-1.5 rounded-full" :class="typeDotClass(room.type)"></span>
                    {{ typeLabel(room.type) }}
                  </span>
                </td>
                <td class="py-4 px-5 text-gray-600 dark:text-gray-300">
                  <p v-if="room.building">{{ room.building }}</p>
                  <p v-if="room.floor" class="text-[10px] text-gray-400">{{ room.floor }}</p>
                  <span v-if="!room.building && !room.floor" class="text-gray-400">—</span>
                </td>
                <td class="py-4 px-5 text-center text-gray-900 dark:text-white font-medium">
                  {{ room.capacity }}
                </td>
                <td class="py-4 px-5 text-center">
                  <span class="px-2.5 py-1 rounded-full text-[10px] font-normal uppercase tracking-wider inline-flex items-center gap-1.5 border"
                        :class="room.is_active
                          ? 'bg-emerald-100 dark:bg-emerald-950/60 text-emerald-800 dark:text-emerald-300 border-emerald-200 dark:border-emerald-800'
                          : 'bg-gray-100 dark:bg-[#232D26] text-gray-600 dark:text-gray-400 border-gray-200 dark:border-[#3F4F43]'">
                    <span :class="['w-1.5 h-1.5 rounded-full', room.is_active ? 'bg-emerald-500' : 'bg-gray-400']"></span>
                    {{ room.is_active ? 'Active' : 'Inactive' }}
                  </span>
                </td>
                <td class="py-4 px-5 text-right">
                  <div class="flex items-center justify-end gap-1.5">
                    <button @click="openEditModal(room)"
                            class="px-2.5 py-1 text-[10px] font-normal text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-[#232D26] hover:bg-gray-200 dark:hover:bg-white/10 rounded-lg transition-colors cursor-pointer">
                      Edit
                    </button>
                    <button @click="confirmDelete(room)"
                            class="px-2.5 py-1 text-[10px] font-normal text-red-700 dark:text-red-400 bg-red-50 hover:bg-red-100 dark:bg-red-950/50 rounded-lg border border-red-200 dark:border-red-900 transition-colors cursor-pointer">
                      Delete
                    </button>
                  </div>
                </td>
              </tr>
              <tr v-if="!rooms.data || rooms.data.length === 0">
                <td colspan="7" class="py-12 text-center text-gray-400 dark:text-gray-500 font-normal">
                  <p class="text-xs">No rooms found.</p>
                  <p class="text-[11px] mt-0.5">Add your first room to get started.</p>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Pagination -->
        <div v-if="rooms.total > 0" class="px-5 py-3.5 bg-gray-50/50 dark:bg-[#232D26]/50 border-t border-gray-100 dark:border-[#3F4F43] flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-gray-500 dark:text-gray-400">
          <div class="flex items-center gap-3">
            <span>
              Showing <span class="font-semibold text-gray-900 dark:text-white">{{ rooms.from || 0 }}</span>
              to <span class="font-semibold text-gray-900 dark:text-white">{{ rooms.to || 0 }}</span>
              of <span class="font-semibold text-gray-900 dark:text-white">{{ rooms.total }}</span> entries
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
            <button @click="changePage(rooms.current_page - 1)" :disabled="rooms.current_page <= 1"
                    class="px-3 py-1.5 rounded-lg border border-gray-200 dark:border-[#3F4F43] bg-white dark:bg-[#2D3A31] text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-white/5 disabled:opacity-40 cursor-pointer disabled:cursor-not-allowed transition-colors">
              Previous
            </button>
            <span class="px-3 py-1.5 font-medium text-gray-900 dark:text-white">
              Page {{ rooms.current_page }} of {{ rooms.last_page }}
            </span>
            <button @click="changePage(rooms.current_page + 1)" :disabled="rooms.current_page >= rooms.last_page"
                    class="px-3 py-1.5 rounded-lg border border-gray-200 dark:border-[#3F4F43] bg-white dark:bg-[#2D3A31] text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-white/5 disabled:opacity-40 cursor-pointer disabled:cursor-not-allowed transition-colors">
              Next
            </button>
          </div>
        </div>
      </div>

      <!-- Form Modal -->
      <RoomFormModal :show="showFormModal" :room="selectedRoom"
                     @close="showFormModal = false; selectedRoom = null"
                     @saved="fetchRooms" />
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref, reactive, computed, onMounted, h } from 'vue'
import axios from 'axios'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import RoomFormModal from './RoomFormModal.vue'

const props = defineProps({
  buildings: { type: Array, default: () => [] },
})

const emptyPaginator = () => ({ data: [], current_page: 1, last_page: 1, total: 0, from: 0, to: 0 })

const rooms  = ref(emptyPaginator())
const counts = ref({ total: 0, active: 0, inactive: 0 })

const filters = reactive({
  search: '',
  type: '',
  building: '',
  per_page: 10,
  page: 1,
  sort_by: 'code',
  sort_dir: 'asc',
})

let searchTimeout = null

const showFormModal  = ref(false)
const selectedRoom   = ref(null)

const hasActiveFilters = computed(() =>
  filters.search !== '' || filters.type !== '' || filters.building !== ''
)

const fetchRooms = async () => {
  try {
    const { data } = await axios.get('/admin/rooms/list', { params: filters })
    const p = data?.rooms
    rooms.value = p && Array.isArray(p.data) ? p : emptyPaginator()
    counts.value = data?.counts ?? counts.value
  } catch (e) {
    console.error('Failed to load rooms:', e)
    rooms.value = emptyPaginator()
  }
}

const debouncedFetch = () => {
  clearTimeout(searchTimeout)
  searchTimeout = setTimeout(() => { filters.page = 1; fetchRooms() }, 300)
}

const setSort = (field) => {
  if (filters.sort_by === field) {
    filters.sort_dir = filters.sort_dir === 'asc' ? 'desc' : 'asc'
  } else {
    filters.sort_by = field
    filters.sort_dir = 'asc'
  }
  filters.page = 1
  fetchRooms()
}

const changePage = (page) => {
  if (page < 1 || page > rooms.value.last_page) return
  filters.page = page
  fetchRooms()
}

const changePageSize = () => { filters.page = 1; fetchRooms() }

const clearFilters = () => {
  filters.search = ''
  filters.type = ''
  filters.building = ''
  filters.page = 1
  fetchRooms()
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

const typeLabel = (type) => ({
  regular:      'Regular',
  lecture:      'Lecture Hall',
  laboratory:   'Laboratory',
  computer_lab: 'Computer Lab',
  science_lab:  'Science Lab',
  workshop:     'Workshop',
}[type] || type)

const typeDotClass = (type) => ({
  regular:      'bg-sky-500',
  lecture:      'bg-indigo-500',
  laboratory:   'bg-emerald-500',
  computer_lab: 'bg-violet-500',
  science_lab:  'bg-teal-500',
  workshop:     'bg-amber-500',
}[type] || 'bg-gray-400')

const openCreateModal = () => { selectedRoom.value = null; showFormModal.value = true }
const openEditModal   = (room) => { selectedRoom.value = room; showFormModal.value = true }

const confirmDelete = async (room) => {
  if (!confirm(`Delete "${room.name}" (${room.code})? Fails if it's used in any class schedule.`)) return
  try {
    await axios.delete(`/admin/rooms/${room.id}`)
    fetchRooms()
  } catch (e) {
    alert(e.response?.data?.message || 'Cannot delete room.')
  }
}

onMounted(() => { fetchRooms() })
</script>