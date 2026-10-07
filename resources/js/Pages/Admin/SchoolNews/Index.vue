<template>
  <AdminLayout>
    <div class="space-y-6 pb-10 font-['Inter']">

      <!-- Hero Banner -->
      <div
        class="relative overflow-hidden w-full rounded-2xl sm:rounded-3xl shadow-lg border border-[#006907]/20 dark:border-none min-h-[220px] sm:min-h-[240px] flex flex-col justify-center p-4 sm:p-8 md:p-10">
        <!-- Background Image Layer (Journalism / Media & News Theme) -->
        <img
          :src="heroImage || 'https://images.unsplash.com/photo-1504711434969-e33886168f5c?q=80&w=1600&auto=format&fit=crop'"
          alt="School News Hero" class="absolute inset-0 w-full h-full object-cover z-0 object-center" />

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
                <span>📢</span> CONTENT
              </span>

              <span
                class="inline-flex items-center gap-2 bg-black/30 dark:bg-black/50 backdrop-blur-md text-white border border-white/20 text-[10px] sm:text-xs font-semibold px-3 sm:px-4 py-1 sm:py-1.5 rounded-full shadow-sm">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                School News
              </span>
            </div>

            <!-- Title & Subtitle -->
            <div class="space-y-1 sm:space-y-1.5">
              <h1
                class="font-['Anton'] text-2xl sm:text-4xl md:text-5xl tracking-wide uppercase flex items-center gap-2 select-none flex-wrap leading-none text-white drop-shadow-md">
                <span>SCHOOL</span>
                <span class="text-[#F9C20C] drop-shadow-[0_2px_8px_rgba(249,194,12,0.4)]">NEWS</span>
              </h1>
              <p class="text-white/90 text-xs sm:text-sm md:text-base leading-relaxed font-medium">
                Publish announcements for the public site, dashboards, and all users.
              </p>
            </div>
          </div>

          <!-- Action Link Button -->
          <div class="flex items-center shrink-0 pt-2 md:pt-0">
            <Link :href="route('admin.school-news.create')"
              class="inline-flex items-center justify-center gap-2 bg-[#F9C20C] hover:bg-[#e0ae0a] text-[#2C3E2D] font-black px-4 sm:px-5 py-2.5 sm:py-3 rounded-xl transition-all shadow-md active:scale-95 text-xs sm:text-sm cursor-pointer">
              <span class="text-base sm:text-lg leading-none">+</span>
              <span>New Announcement</span>
            </Link>
          </div>
        </div>
      </div>

      <!-- Stat cards -->
      <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4">

        <button @click="setStatusFilter('')"
          :class="cardClass(filters.status === '' && !filters.pinned && !filters.priority, 'gray')">
          <div class="space-y-1">
            <p class="text-[10px] font-medium uppercase text-gray-400 tracking-wider">Total</p>
            <p class="text-2xl font-medium text-gray-900 dark:text-white">{{ counts.total || 0 }}</p>
            <span class="text-[10px] text-gray-500 dark:text-gray-400 font-normal">All Posts</span>
          </div>
          <div
            class="w-12 h-12 rounded-2xl bg-gray-50 dark:bg-[#1C261E] border border-gray-100 dark:border-[#3F4F43] flex items-center justify-center text-gray-600 dark:text-gray-300 shrink-0">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round"
                d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
            </svg>
          </div>
        </button>

        <button @click="setStatusFilter('published')" :class="cardClass(filters.status === 'published', 'emerald')">
          <div class="space-y-1">
            <p class="text-[10px] font-medium uppercase text-emerald-500 tracking-wider">Published</p>
            <p class="text-2xl font-medium text-gray-900 dark:text-white">{{ counts.published || 0 }}</p>
            <span class="text-[10px] text-emerald-600 dark:text-emerald-400 font-normal">Live Now</span>
          </div>
          <div
            class="w-12 h-12 rounded-2xl bg-emerald-50 dark:bg-[#1C261E] border border-emerald-100 dark:border-[#3F4F43] flex items-center justify-center text-emerald-600 dark:text-emerald-400 shrink-0">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </div>
        </button>

        <button @click="setStatusFilter('draft')" :class="cardClass(filters.status === 'draft', 'sky')">
          <div class="space-y-1">
            <p class="text-[10px] font-medium uppercase text-sky-500 tracking-wider">Drafts</p>
            <p class="text-2xl font-medium text-gray-900 dark:text-white">{{ counts.drafts || 0 }}</p>
            <span class="text-[10px] text-sky-600 dark:text-sky-400 font-normal">Not Visible</span>
          </div>
          <div
            class="w-12 h-12 rounded-2xl bg-sky-50 dark:bg-[#1C261E] border border-sky-100 dark:border-[#3F4F43] flex items-center justify-center text-sky-600 dark:text-sky-400 shrink-0">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round"
                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
            </svg>
          </div>
        </button>

        <button @click="togglePinnedFilter" :class="cardClass(filters.pinned === true, 'amber')">
          <div class="space-y-1">
            <p class="text-[10px] font-medium uppercase text-amber-500 tracking-wider">Pinned</p>
            <p class="text-2xl font-medium text-gray-900 dark:text-white">{{ counts.pinned || 0 }}</p>
            <span class="text-[10px] text-amber-600 dark:text-amber-400 font-normal">Sticky Top</span>
          </div>
          <div
            class="w-12 h-12 rounded-2xl bg-amber-50 dark:bg-[#1C261E] border border-amber-100 dark:border-[#3F4F43] flex items-center justify-center text-amber-600 dark:text-amber-400 shrink-0">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round"
                d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" />
            </svg>
          </div>
        </button>

        <button @click="setPriorityFilter('urgent')" :class="cardClass(filters.priority === 'urgent', 'red')">
          <div class="space-y-1">
            <p class="text-[10px] font-medium uppercase text-red-500 tracking-wider">Urgent</p>
            <p class="text-2xl font-medium text-gray-900 dark:text-white">{{ counts.urgent || 0 }}</p>
            <span class="text-[10px] text-red-600 dark:text-red-400 font-normal">Emails Sent</span>
          </div>
          <div
            class="w-12 h-12 rounded-2xl bg-red-50 dark:bg-[#1C261E] border border-red-100 dark:border-[#3F4F43] flex items-center justify-center text-red-600 dark:text-red-400 shrink-0">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round"
                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
          </div>
        </button>

      </div>

      <!-- Main card -->
      <div
        class="bg-white dark:bg-[#2D3A31] rounded-2xl border border-gray-200/80 dark:border-[#3F4F43] shadow-xs overflow-hidden">

        <!-- Controls -->
        <div
          class="p-4 sm:p-5 border-b border-gray-100 dark:border-[#3F4F43] bg-gray-50/50 dark:bg-[#232D26]/50 flex flex-col lg:flex-row gap-3 justify-between lg:items-center">
          <div>
            <h2 class="text-xs font-medium text-gray-900 dark:text-white uppercase tracking-wider">Announcements</h2>
            <p class="text-[11px] text-gray-500 dark:text-gray-400 font-normal">Manage school-wide posts and their
              priority</p>
          </div>

          <div class="flex flex-wrap sm:flex-nowrap items-center gap-2 w-full lg:w-auto lg:justify-end">
            <div class="relative">
              <select v-model="filters.priority" @change="onPriorityDropdownChange()"
                class="appearance-none pl-3 pr-8 py-2 text-xs bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white rounded-xl focus:ring-2 focus:ring-[#004d08] focus:outline-none font-normal cursor-pointer">
                <option value="">All Priorities</option>
                <option value="normal">Normal</option>
                <option value="important">Important</option>
                <option value="urgent">Urgent</option>
              </select>
            </div>

            <div class="relative w-full sm:w-64">
              <svg class="w-4 h-4 absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 dark:text-gray-500"
                fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
              </svg>
              <input v-model="filters.search" @input="debouncedFetch" type="text" placeholder="Search title or body..."
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

        <!-- Cards list -->
        <div v-if="announcements.data && announcements.data.length"
          class="divide-y divide-gray-100 dark:divide-[#3F4F43]">
          <article v-for="a in announcements.data" :key="a.id"
            class="p-5 hover:bg-gray-50/60 dark:hover:bg-white/5 transition-colors" :class="priorityRowClass(a)">

            <div class="flex gap-4">
              <!-- Thumbnail -->
              <div v-if="a.image_url"
                class="w-32 h-24 rounded-xl overflow-hidden border border-gray-200 dark:border-[#3F4F43] shrink-0 bg-gray-100 dark:bg-[#1C261E] hidden sm:block">
                <img :src="a.image_url" :alt="a.title" class="w-full h-full object-cover" />
              </div>

              <!-- Content -->
              <div class="flex-1 min-w-0">
                <div class="flex items-start gap-2 mb-1.5 flex-wrap">
                  <PriorityBadge :priority="a.priority" />
                  <span v-if="a.is_pinned"
                    class="px-2 py-0.5 rounded-full text-[10px] font-medium uppercase tracking-wider bg-amber-100 dark:bg-amber-950/60 text-amber-800 dark:text-amber-300 border border-amber-200 dark:border-amber-800 inline-flex items-center gap-1">
                    📌 Pinned
                  </span>
                  <span v-if="a.is_draft"
                    class="px-2 py-0.5 rounded-full text-[10px] font-medium uppercase tracking-wider bg-sky-100 dark:bg-sky-950/60 text-sky-800 dark:text-sky-300 border border-sky-200 dark:border-sky-800">
                    Draft
                  </span>
                  <span v-if="a.is_expired"
                    class="px-2 py-0.5 rounded-full text-[10px] font-medium uppercase tracking-wider bg-gray-100 dark:bg-[#232D26] text-gray-600 dark:text-gray-400 border border-gray-200 dark:border-[#3F4F43]">
                    Expired
                  </span>
                </div>

                <h3 class="text-sm font-medium text-gray-900 dark:text-white mb-1">{{ a.title }}</h3>
                <p class="text-xs text-gray-500 dark:text-gray-400 line-clamp-2 leading-relaxed">{{ a.excerpt }}</p>

                <div class="flex items-center gap-3 mt-3 text-[10px] text-gray-400 dark:text-gray-500">
                  <span v-if="a.author">By {{ a.author }}</span>
                  <span v-if="a.published_at">&#8226; Published {{ formatDate(a.published_at) }}</span>
                  <span v-else>&#8226; Never published</span>
                  <span v-if="a.expires_at">&#8226; Expires {{ formatDate(a.expires_at) }}</span>
                </div>
              </div>

              <!-- Actions -->
              <div class="flex flex-col items-end gap-1.5 shrink-0">
                <Link :href="route('admin.school-news.edit', a.id)"
                  class="px-2.5 py-1 text-[10px] font-normal text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-[#232D26] hover:bg-gray-200 dark:hover:bg-white/10 rounded-lg transition-colors cursor-pointer">
                  Edit
                </Link>

                <button @click="togglePin(a)"
                  class="px-2.5 py-1 text-[10px] font-normal text-amber-700 dark:text-amber-400 bg-amber-50 hover:bg-amber-100 dark:bg-amber-950/50 rounded-lg border border-amber-200 dark:border-amber-800 transition-colors cursor-pointer">
                  {{ a.is_pinned ? 'Unpin' : 'Pin' }}
                </button>

                <button @click="togglePublish(a)"
                  class="px-2.5 py-1 text-[10px] font-normal rounded-lg border transition-colors cursor-pointer"
                  :class="a.is_draft
                    ? 'text-emerald-700 dark:text-emerald-400 bg-emerald-50 hover:bg-emerald-100 dark:bg-emerald-950/50 border-emerald-200 dark:border-emerald-800'
                    : 'text-sky-700 dark:text-sky-300 bg-sky-50 hover:bg-sky-100 dark:bg-sky-950/50 border-sky-200 dark:border-sky-800'">
                  {{ a.is_draft ? 'Publish' : 'Unpublish' }}
                </button>

                <button @click="confirmDelete(a)"
                  class="px-2.5 py-1 text-[10px] font-normal text-red-700 dark:text-red-400 bg-red-50 hover:bg-red-100 dark:bg-red-950/50 rounded-lg border border-red-200 dark:border-red-900 transition-colors cursor-pointer">
                  Delete
                </button>
              </div>
            </div>
          </article>
        </div>

        <!-- Empty state -->
        <div v-else class="py-16 text-center text-gray-400 dark:text-gray-500">
          <p class="text-xs">No announcements yet.</p>
          <p class="text-[11px] mt-0.5">Click "New Announcement" to write your first post.</p>
        </div>

        <!-- Pagination -->
        <div v-if="announcements.total > 0"
          class="px-5 py-3.5 bg-gray-50/50 dark:bg-[#232D26]/50 border-t border-gray-100 dark:border-[#3F4F43] flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-gray-500 dark:text-gray-400">
          <div class="flex items-center gap-3">
            <span>
              Showing <span class="font-semibold text-gray-900 dark:text-white">{{ announcements.from || 0 }}</span>
              to <span class="font-semibold text-gray-900 dark:text-white">{{ announcements.to || 0 }}</span>
              of <span class="font-semibold text-gray-900 dark:text-white">{{ announcements.total }}</span> entries
            </span>
            <select v-model.number="filters.per_page" @change="changePageSize"
              class="appearance-none pl-2 pr-7 py-1 text-[11px] bg-white dark:bg-[#2D3A31] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white rounded-lg focus:ring-2 focus:ring-[#004d08] focus:outline-none cursor-pointer">
              <option :value="12">12</option>
              <option :value="24">24</option>
              <option :value="48">48</option>
            </select>
          </div>
          <div class="flex items-center gap-1">
            <button @click="changePage(announcements.current_page - 1)" :disabled="announcements.current_page <= 1"
              class="px-3 py-1.5 rounded-lg border border-gray-200 dark:border-[#3F4F43] bg-white dark:bg-[#2D3A31] disabled:opacity-40 cursor-pointer disabled:cursor-not-allowed">
              Previous
            </button>
            <span class="px-3 py-1.5 font-medium text-gray-900 dark:text-white">
              Page {{ announcements.current_page }} of {{ announcements.last_page }}
            </span>
            <button @click="changePage(announcements.current_page + 1)"
              :disabled="announcements.current_page >= announcements.last_page"
              class="px-3 py-1.5 rounded-lg border border-gray-200 dark:border-[#3F4F43] bg-white dark:bg-[#2D3A31] disabled:opacity-40 cursor-pointer disabled:cursor-not-allowed">
              Next
            </button>
          </div>
        </div>

      </div>

      <!-- Delete Modal -->
      <Modal :show="showDeleteModal" @close="showDeleteModal = false" max-width="md">
        <div
          class="bg-white dark:bg-[#2D3A31] rounded-2xl border border-gray-200 dark:border-[#3F4F43] p-6 font-['Inter']">
          <div class="flex items-center gap-3 mb-4">
            <div
              class="w-10 h-10 rounded-2xl bg-red-50 dark:bg-red-950/50 border border-red-100 dark:border-red-900/50 flex items-center justify-center text-red-600 dark:text-red-400 shrink-0">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                  d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
              </svg>
            </div>
            <div>
              <h3 class="text-xs font-medium uppercase tracking-wider text-gray-900 dark:text-white">Delete Announcement
              </h3>
              <p class="text-[11px] text-gray-500 dark:text-gray-400">This action cannot be undone</p>
            </div>
          </div>

          <p class="text-xs text-gray-600 dark:text-gray-300 leading-relaxed mb-6">
            Are you sure you want to delete <strong class="font-semibold text-gray-900 dark:text-white">{{
              deleteTarget?.title }}</strong>?
            It will be removed from all feeds immediately.
          </p>

          <div class="flex justify-end gap-2.5 pt-4 border-t border-gray-100 dark:border-[#3F4F43]">
            <button @click="showDeleteModal = false"
              class="px-4 py-2 text-xs text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-white/5 rounded-xl cursor-pointer">
              Cancel
            </button>
            <button @click="executeDelete" :disabled="saving"
              class="px-5 py-2 text-xs font-medium uppercase tracking-wider bg-red-600 hover:bg-red-700 text-white rounded-xl disabled:opacity-50 cursor-pointer">
              {{ saving ? 'Deleting…' : 'Delete' }}
            </button>
          </div>
        </div>
      </Modal>

    </div>
  </AdminLayout>
</template>

<script setup>
import { confirmAction, showError } from '@/Pages/useSweetAlert'
import { ref, reactive, computed, onMounted, h } from 'vue'
import axios from 'axios'
import { Link } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import Modal from '@/Components/Modal.vue'

const emptyPaginator = () => ({ data: [], current_page: 1, last_page: 1, total: 0, from: 0, to: 0 })

const announcements = ref(emptyPaginator())
const counts = ref({ total: 0, published: 0, drafts: 0, pinned: 0, urgent: 0 })

const filters = reactive({
  search: '',
  status: '',
  priority: '',
  pinned: false,
  per_page: 12,
  page: 1,
})

const saving = ref(false)
const showDeleteModal = ref(false)
const deleteTarget = ref(null)

let searchTimer = null

const hasActiveFilters = computed(() =>
  filters.search !== '' || filters.status !== '' ||
  filters.priority !== '' || filters.pinned === true
)

const fetchList = async () => {
  const params = {}
  for (const [k, v] of Object.entries(filters)) {
    if (v === '' || v === false || v === null || v === undefined) continue
    // Laravel's boolean rule rejects the string "true"; send 1 instead
    params[k] = k === 'pinned' ? 1 : v
  }
  try {
    const { data } = await axios.get('/admin/school-news/list', { params })
    const p = data?.announcements
    announcements.value = p && Array.isArray(p.data) ? p : emptyPaginator()
    counts.value = data?.counts ?? counts.value
  } catch (e) {
    console.error('Failed to load announcements:', e)
    announcements.value = emptyPaginator()
  }
}

const debouncedFetch = () => {
  clearTimeout(searchTimer)
  searchTimer = setTimeout(() => { filters.page = 1; fetchList() }, 300)
}

/**
 * Reset every "category" filter (status, priority, pinned) but keep search.
 * Called by every stat card so clicking a card fully replaces the previous one.
 */
const resetCategoryFilters = () => {
  filters.status = ''
  filters.priority = ''
  filters.pinned = false
  filters.page = 1
}

const setStatusFilter = (status) => {
  resetCategoryFilters()
  filters.status = status
  fetchList()
}

const setPriorityFilter = (priority) => {
  resetCategoryFilters()
  filters.priority = priority
  fetchList()
}

const togglePinnedFilter = () => {
  const wasPinned = filters.pinned
  resetCategoryFilters()
  filters.pinned = !wasPinned
  fetchList()
}

const clearFilters = () => {
  Object.assign(filters, { search: '', status: '', priority: '', pinned: false, page: 1 })
  fetchList()
}

const changePage = (p) => {
  if (p < 1 || p > announcements.value.last_page) return
  filters.page = p
  fetchList()
}

const changePageSize = () => { filters.page = 1; fetchList() }

const cardClass = (active, color = 'gray') => {
  const base = 'text-left p-5 rounded-2xl border shadow-xs flex items-center justify-between transition-colors cursor-pointer'
  const activeMap = {
    gray: 'bg-[#004d08]/5 dark:bg-[#86EFAC]/5 border-[#004d08]/40 dark:border-[#86EFAC]/30',
    amber: 'bg-amber-50/60 dark:bg-amber-950/20 border-amber-300 dark:border-amber-800',
    emerald: 'bg-emerald-50/60 dark:bg-emerald-950/20 border-emerald-300 dark:border-emerald-800',
    sky: 'bg-sky-50/60 dark:bg-sky-950/20 border-sky-300 dark:border-sky-800',
    red: 'bg-red-50/60 dark:bg-red-950/20 border-red-300 dark:border-red-800',
  }
  const inactive = 'bg-white dark:bg-[#2D3A31] border-gray-200/80 dark:border-[#3F4F43] hover:border-gray-300 dark:hover:border-gray-500'
  return `${base} ${active ? activeMap[color] : inactive}`
}

const priorityRowClass = (a) => {
  if (a.priority === 'urgent' && !a.is_expired) return 'border-l-4 border-l-red-500'
  if (a.priority === 'important' && !a.is_expired) return 'border-l-4 border-l-amber-500'
  return ''
}

const PriorityBadge = (props) => {
  const config = {
    normal: { label: 'Normal', cls: 'bg-gray-100 dark:bg-[#232D26] text-gray-600 dark:text-gray-400 border-gray-200 dark:border-[#3F4F43]' },
    important: { label: 'Important', cls: 'bg-amber-100 dark:bg-amber-950/60 text-amber-800 dark:text-amber-300 border-amber-200 dark:border-amber-800' },
    urgent: { label: 'Urgent', cls: 'bg-red-100 dark:bg-red-950/60 text-red-800 dark:text-red-300 border-red-200 dark:border-red-800' },
  }
  const c = config[props.priority] || config.normal
  return h('span', {
    class: `px-2 py-0.5 rounded-full text-[10px] font-medium uppercase tracking-wider border ${c.cls}`,
  }, c.label)
}
PriorityBadge.props = { priority: { type: String, default: 'normal' } }

const formatDate = (iso) => {
  if (!iso) return '—'
  try { return new Date(iso).toLocaleDateString('en-PH', { month: 'short', day: 'numeric', year: 'numeric' }) }
  catch { return iso }
}

const togglePin = async (a) => {
  try {
    await axios.put(`/admin/school-news/${a.id}/pin`)
    fetchList()
  } catch (e) {
    showError(e.response?.data?.message || 'Failed to toggle pin.')
  }
}

const togglePublish = async (a) => {
  const willPublish = a.is_draft
  if (willPublish && a.priority === 'urgent') {
    if (!await confirmAction('This urgent announcement will be emailed to all active users. Continue?')) return
  } else if (a.is_draft) {
    if (!await confirmAction('Publish this announcement? It will be visible to all users.')) return
  } else {
    if (!await confirmAction('Unpublish this announcement? It will be hidden from all users.')) return
  }

  try {
    await axios.put(`/admin/school-news/${a.id}/publish`)
    fetchList()
  } catch (e) {
    showError(e.response?.data?.message || 'Failed to toggle publish state.')
  }
}

const confirmDelete = (a) => {
  deleteTarget.value = a
  showDeleteModal.value = true
}

const executeDelete = async () => {
  if (!deleteTarget.value) return
  saving.value = true
  try {
    await axios.delete(`/admin/school-news/${deleteTarget.value.id}`)
    showDeleteModal.value = false
    deleteTarget.value = null
    fetchList()
  } catch (e) {
    showError(e.response?.data?.message || 'Failed to delete.')
  } finally {
    saving.value = false
  }
}

onMounted(() => { fetchList() })

const onPriorityDropdownChange = () => {
  // Don't clear priority — the dropdown set it. Just clear the other categories.
  filters.status = ''
  filters.pinned = false
  filters.page = 1
  fetchList()
}
</script>