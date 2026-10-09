<template>
  <AdminLayout>
    <div class="space-y-6 pb-10 font-['Inter']">

      <!-- Hero Banner (unchanged) -->
      <div
        class="relative overflow-hidden w-full rounded-2xl sm:rounded-3xl shadow-lg border border-[#006907]/20 dark:border-none min-h-[200px] sm:min-h-[220px] flex flex-col justify-center p-4 sm:p-8 md:p-10">
        <img
          :src="heroImage || 'https://images.unsplash.com/photo-1596526131083-e8c633c948d2?q=80&w=1600&auto=format&fit=crop'"
          alt="Contact Messages Hero" class="absolute inset-0 w-full h-full object-cover z-0 object-center" />
        <div class="absolute inset-0 bg-[#004d05]/85 dark:bg-[#152B1C]/90 animate-overlay z-0 mix-blend-multiply"></div>
        <div class="absolute -right-10 -bottom-10 w-72 h-72 rounded-full bg-emerald-300/20 blur-3xl pointer-events-none z-0"></div>
        <div class="absolute right-24 -top-16 w-80 h-80 rounded-full bg-[#F9C20C]/20 blur-3xl pointer-events-none z-0"></div>

        <div class="relative z-10 w-full max-w-4xl space-y-2.5 sm:space-y-3.5">
          <div class="flex flex-wrap items-center gap-2 sm:gap-2.5">
            <span class="inline-flex items-center gap-1.5 bg-[#F9C20C] text-[#2C3E2D] font-black text-[10px] sm:text-xs px-3 sm:px-4 py-1 sm:py-1.5 rounded-full shadow-sm tracking-wide uppercase">
              <span>📬</span> CONTENT
            </span>
            <span class="inline-flex items-center gap-2 bg-black/30 dark:bg-black/50 backdrop-blur-md text-white border border-white/20 text-[10px] sm:text-xs font-semibold px-3 sm:px-4 py-1 sm:py-1.5 rounded-full shadow-sm">
              <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
              Contact Messages
            </span>
          </div>
          <div class="space-y-1 sm:space-y-1.5">
            <h1 class="font-['Anton'] text-2xl sm:text-4xl md:text-5xl tracking-wide uppercase flex items-center gap-2 select-none flex-wrap leading-none text-white drop-shadow-md">
              <span>CONTACT</span>
              <span class="text-[#F9C20C] drop-shadow-[0_2px_8px_rgba(249,194,12,0.4)]">MESSAGES</span>
            </h1>
            <p class="text-white/90 text-xs sm:text-sm md:text-base leading-relaxed font-medium">
              Inbound inquiries from the public contact form. Review, reply, and triage.
            </p>
          </div>
        </div>
      </div>

      <!-- Stat cards (unchanged) -->
      <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <button @click="setStatusFilter('')" :class="cardClass(filters.status === '', 'gray')">
          <div class="space-y-1">
            <p class="text-[10px] font-medium uppercase text-gray-400 tracking-wider">Total</p>
            <p class="text-2xl font-medium text-gray-900 dark:text-white">{{ counts.total || 0 }}</p>
            <span class="text-[10px] text-gray-500 dark:text-gray-400 font-normal">All Messages</span>
          </div>
          <div class="w-12 h-12 rounded-2xl bg-gray-50 dark:bg-[#1C261E] border border-gray-100 dark:border-[#3F4F43] flex items-center justify-center text-gray-600 dark:text-gray-300 shrink-0">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
            </svg>
          </div>
        </button>

        <button @click="setStatusFilter('unread')" :class="cardClass(filters.status === 'unread', 'amber')">
          <div class="space-y-1">
            <p class="text-[10px] font-medium uppercase text-amber-500 tracking-wider">Unread</p>
            <p class="text-2xl font-medium text-gray-900 dark:text-white">{{ counts.unread || 0 }}</p>
            <span class="text-[10px] text-amber-600 dark:text-amber-400 font-normal">Needs Attention</span>
          </div>
          <div class="w-12 h-12 rounded-2xl bg-amber-50 dark:bg-[#1C261E] border border-amber-100 dark:border-[#3F4F43] flex items-center justify-center text-amber-600 dark:text-amber-400 shrink-0">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
            </svg>
          </div>
        </button>

        <button @click="setStatusFilter('read')" :class="cardClass(filters.status === 'read', 'emerald')">
          <div class="space-y-1">
            <p class="text-[10px] font-medium uppercase text-emerald-500 tracking-wider">Read</p>
            <p class="text-2xl font-medium text-gray-900 dark:text-white">{{ counts.read || 0 }}</p>
            <span class="text-[10px] text-emerald-600 dark:text-emerald-400 font-normal">Reviewed</span>
          </div>
          <div class="w-12 h-12 rounded-2xl bg-emerald-50 dark:bg-[#1C261E] border border-emerald-100 dark:border-[#3F4F43] flex items-center justify-center text-emerald-600 dark:text-emerald-400 shrink-0">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </div>
        </button>

        <button @click="setWhenFilter('week')" :class="cardClass(filters.when === 'week', 'sky')">
          <div class="space-y-1">
            <p class="text-[10px] font-medium uppercase text-sky-500 tracking-wider">This Week</p>
            <p class="text-2xl font-medium text-gray-900 dark:text-white">{{ counts.this_week || 0 }}</p>
            <span class="text-[10px] text-sky-600 dark:text-sky-400 font-normal">Last 7 Days</span>
          </div>
          <div class="w-12 h-12 rounded-2xl bg-sky-50 dark:bg-[#1C261E] border border-sky-100 dark:border-[#3F4F43] flex items-center justify-center text-sky-600 dark:text-sky-400 shrink-0">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
          </div>
        </button>
      </div>

      <!-- ═════════════ INBOX (split pane) ═════════════ -->
      <div class="bg-white dark:bg-[#2D3A31] rounded-2xl border border-gray-200/80 dark:border-[#3F4F43] shadow-xs overflow-hidden transition-colors">
        <div class="grid grid-cols-1 lg:grid-cols-[380px_minmax(0,1fr)] min-h-[600px]">

          <!-- ═══ LEFT: LIST ═══ -->
          <div class="border-b lg:border-b-0 lg:border-r border-gray-100 dark:border-[#3F4F43] flex flex-col min-w-0">

            <!-- Controls -->
            <div class="p-3 sm:p-4 border-b border-gray-100 dark:border-[#3F4F43] bg-gray-50/50 dark:bg-[#232D26]/50 space-y-2">
              <!-- Search -->
              <div class="relative">
                <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <input v-model="filters.search" @input="debouncedFetch" type="text"
                  placeholder="Search name, email, subject..."
                  class="w-full pl-9 pr-8 py-2 text-xs bg-white dark:bg-[#2D3A31] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white placeholder-gray-400 rounded-lg focus:ring-2 focus:ring-[#004d08] focus:outline-none font-normal" />
                <button v-if="filters.search" @click="filters.search = ''; fetchList()"
                  class="absolute right-2.5 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 cursor-pointer">
                  <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                  </svg>
                </button>
              </div>

              <!-- When filter + reset -->
              <div class="flex items-center gap-2">
                <select v-model="filters.when" @change="onWhenChange"
                  class="flex-1 appearance-none pl-2.5 pr-7 py-1.5 text-[11px] bg-white dark:bg-[#2D3A31] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white rounded-lg focus:ring-2 focus:ring-[#004d08] focus:outline-none cursor-pointer">
                  <option value="">All Time</option>
                  <option value="today">Today</option>
                  <option value="week">Last 7 Days</option>
                  <option value="month">Last 30 Days</option>
                </select>
                <button v-if="hasActiveFilters" @click="clearFilters"
                  class="px-2.5 py-1.5 text-[11px] font-normal text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-white/5 rounded-lg border border-gray-200 dark:border-[#3F4F43] transition-colors cursor-pointer shrink-0">
                  Reset
                </button>
              </div>
            </div>

            <!-- Message list -->
            <div ref="listContainer" class="flex-1 overflow-y-auto max-h-[600px]">
              <div v-if="loading" class="p-8 text-center">
                <div class="inline-block w-5 h-5 border-2 border-[#004d08] dark:border-[#86EFAC] border-t-transparent rounded-full animate-spin"></div>
              </div>

              <div v-else-if="!messages.data.length" class="py-16 text-center text-gray-400 dark:text-gray-500">
                <svg class="w-10 h-10 mx-auto mb-3 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
                <p class="text-xs">No messages.</p>
                <p class="text-[11px] mt-0.5">Public form submissions will appear here.</p>
              </div>

              <ul v-else class="divide-y divide-gray-100 dark:divide-[#3F4F43]">
                <li v-for="m in messages.data" :key="m.id">
                  <button @click="openMessage(m)"
                    class="w-full text-left p-3 sm:p-4 hover:bg-gray-50/80 dark:hover:bg-white/5 transition-colors cursor-pointer block"
                    :class="[
                      !m.is_read ? 'bg-amber-50/40 dark:bg-amber-950/10' : '',
                      selectedMessage?.id === m.id ? 'bg-emerald-50/60 dark:bg-emerald-950/20 border-l-2 border-l-[#004d08] dark:border-l-[#86EFAC]' : ''
                    ]">
                    <div class="flex items-start gap-3">
                      <div class="w-9 h-9 rounded-full bg-emerald-100 dark:bg-[#1C261E] text-[#004d08] dark:text-[#86EFAC] border border-emerald-200 dark:border-[#3F4F43] flex items-center justify-center font-medium text-xs shrink-0 relative">
                        {{ initials(m.name) }}
                        <span v-if="!m.is_read" class="absolute -top-0.5 -right-0.5 w-2.5 h-2.5 rounded-full bg-amber-500 border-2 border-white dark:border-[#2D3A31]"></span>
                      </div>
                      <div class="min-w-0 flex-1">
                        <div class="flex items-baseline justify-between gap-2">
                          <p class="text-xs truncate" :class="!m.is_read ? 'font-semibold text-gray-900 dark:text-white' : 'font-medium text-gray-700 dark:text-gray-300'">
                            {{ m.name }}
                          </p>
                          <span class="text-[10px] text-gray-400 dark:text-gray-500 shrink-0 whitespace-nowrap">
                            {{ formatRelative(m.created_at) }}
                          </span>
                        </div>
                        <p class="text-[11px] truncate mt-0.5" :class="!m.is_read ? 'text-gray-800 dark:text-gray-200 font-medium' : 'text-gray-600 dark:text-gray-400'">
                          {{ m.subject || '(No subject)' }}
                        </p>
                        <p class="text-[10px] text-gray-500 dark:text-gray-500 truncate mt-0.5">
                          {{ m.excerpt }}
                        </p>
                      </div>
                    </div>
                  </button>
                </li>
              </ul>
            </div>

            <!-- Pagination -->
            <div v-if="messages.total > 0"
              class="px-3 py-2.5 border-t border-gray-100 dark:border-[#3F4F43] bg-gray-50/50 dark:bg-[#232D26]/50 space-y-2">

              <!-- Row 1: summary + page size -->
              <div class="flex items-center justify-between gap-2 text-[10px] text-gray-500 dark:text-gray-400">
                <span>
                  {{ messages.from || 0 }}–{{ messages.to || 0 }} of {{ messages.total }}
                </span>
                <select v-model.number="filters.per_page" @change="changePageSize"
                  class="appearance-none pl-2 pr-6 py-1 text-[10px] bg-white dark:bg-[#2D3A31] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white rounded-md focus:ring-2 focus:ring-[#004d08] focus:outline-none cursor-pointer">
                  <option :value="10">10 / page</option>
                  <option :value="15">15 / page</option>
                  <option :value="25">25 / page</option>
                  <option :value="50">50 / page</option>
                </select>
              </div>

              <!-- Row 2: page navigation -->
              <div class="flex items-center justify-between gap-1">
                <button @click="changePage(1)" :disabled="messages.current_page <= 1"
                  class="px-2 py-1 text-[10px] font-medium rounded-md border border-gray-200 dark:border-[#3F4F43] bg-white dark:bg-[#2D3A31] text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-white/5 disabled:opacity-40 disabled:cursor-not-allowed cursor-pointer transition-colors"
                  title="First page">
                  «
                </button>

                <button @click="changePage(messages.current_page - 1)" :disabled="messages.current_page <= 1"
                  class="flex-1 px-2 py-1 text-[10px] font-medium rounded-md border border-gray-200 dark:border-[#3F4F43] bg-white dark:bg-[#2D3A31] text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-white/5 disabled:opacity-40 disabled:cursor-not-allowed cursor-pointer transition-colors">
                  ‹ Prev
                </button>

                <span class="px-2 py-1 text-[10px] font-medium text-gray-700 dark:text-gray-300 whitespace-nowrap">
                  {{ messages.current_page }} / {{ messages.last_page || 1 }}
                </span>

                <button @click="changePage(messages.current_page + 1)" :disabled="messages.current_page >= messages.last_page"
                  class="flex-1 px-2 py-1 text-[10px] font-medium rounded-md border border-gray-200 dark:border-[#3F4F43] bg-white dark:bg-[#2D3A31] text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-white/5 disabled:opacity-40 disabled:cursor-not-allowed cursor-pointer transition-colors">
                  Next ›
                </button>

                <button @click="changePage(messages.last_page)" :disabled="messages.current_page >= messages.last_page"
                  class="px-2 py-1 text-[10px] font-medium rounded-md border border-gray-200 dark:border-[#3F4F43] bg-white dark:bg-[#2D3A31] text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-white/5 disabled:opacity-40 disabled:cursor-not-allowed cursor-pointer transition-colors"
                  title="Last page">
                  »
                </button>
              </div>
            </div>
          </div>

          <!-- ═══ RIGHT: PREVIEW ═══ -->
          <div class="flex flex-col min-w-0">
            <!-- Empty state -->
            <div v-if="!selectedMessage" class="flex-1 flex items-center justify-center py-20 px-6 text-center">
              <div>
                <div class="w-16 h-16 mx-auto mb-3 rounded-2xl bg-gray-100 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] flex items-center justify-center">
                  <svg class="w-8 h-8 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                  </svg>
                </div>
                <p class="text-xs text-gray-500 dark:text-gray-400">Select a message to read it</p>
                <p class="text-[11px] text-gray-400 dark:text-gray-500 mt-0.5">Replies open in your email app</p>
              </div>
            </div>

            <!-- Message preview -->
            <template v-else>
              <!-- Header -->
              <div class="p-4 sm:p-5 border-b border-gray-100 dark:border-[#3F4F43] bg-gray-50/50 dark:bg-[#232D26]/50">
                <div class="flex items-start justify-between gap-3 mb-3">
                  <div class="min-w-0">
                    <h3 class="text-sm font-semibold text-gray-900 dark:text-white truncate">
                      {{ selectedMessage.subject || '(No subject)' }}
                    </h3>
                    <p class="text-[11px] text-gray-500 dark:text-gray-400 mt-0.5">
                      From <span class="font-medium text-gray-700 dark:text-gray-300">{{ selectedMessage.name }}</span>
                      &bull; {{ formatFullDate(selectedMessage.created_at) }}
                    </p>
                  </div>
                  <span v-if="!selectedMessage.is_read"
                    class="px-2 py-0.5 rounded-full text-[10px] font-medium uppercase tracking-wider bg-amber-100 dark:bg-amber-950/60 text-amber-800 dark:text-amber-300 border border-amber-200 dark:border-amber-800 shrink-0">
                    Unread
                  </span>
                </div>

                <a :href="`mailto:${selectedMessage.email}`"
                  class="inline-flex items-center gap-1.5 text-[11px] text-[#004d08] dark:text-[#86EFAC] hover:underline break-all">
                  <svg class="w-3 h-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                  </svg>
                  {{ selectedMessage.email }}
                </a>
              </div>

              <!-- Body -->
              <div class="flex-1 overflow-y-auto max-h-[480px] p-4 sm:p-6">
                <div class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed whitespace-pre-line">
                  {{ selectedMessage.message }}
                </div>
              </div>

              <!-- Actions -->
              <div class="p-4 sm:p-5 border-t border-gray-100 dark:border-[#3F4F43] bg-gray-50/50 dark:bg-[#232D26]/50 flex flex-wrap items-center justify-end gap-2">
                <button @click="toggleRead(selectedMessage)"
                  class="px-3 py-2 text-[11px] font-normal text-gray-700 dark:text-gray-300 bg-white dark:bg-[#2D3A31] hover:bg-gray-100 dark:hover:bg-white/10 border border-gray-200 dark:border-[#3F4F43] rounded-lg transition-colors cursor-pointer">
                  Mark as {{ selectedMessage.is_read ? 'Unread' : 'Read' }}
                </button>
                <button @click="confirmDelete(selectedMessage)"
                  class="px-3 py-2 text-[11px] font-normal text-red-700 dark:text-red-400 bg-red-50 hover:bg-red-100 dark:bg-red-950/50 border border-red-200 dark:border-red-900 rounded-lg transition-colors cursor-pointer">
                  Delete
                </button>
                <button @click="openReplyModal"
                  class="inline-flex items-center gap-1.5 px-4 py-2 text-[11px] font-medium uppercase tracking-wider bg-[#004d08] hover:bg-emerald-900 text-white rounded-lg transition-all shadow-sm active:scale-95 cursor-pointer">
                  <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6" />
                  </svg>
                  Reply
                </button>
              </div>
            </template>
          </div>

        </div>
      </div>

      <!-- Delete Confirmation Modal (kept) -->
      <Modal :show="showDeleteModal" @close="showDeleteModal = false" max-width="md">
        <div class="bg-white dark:bg-[#2D3A31] rounded-2xl border border-gray-200 dark:border-[#3F4F43] p-6 font-['Inter']">
          <div class="flex items-center gap-3 mb-4">
            <div class="w-10 h-10 rounded-2xl bg-red-50 dark:bg-red-950/50 border border-red-100 dark:border-red-900/50 flex items-center justify-center text-red-600 dark:text-red-400 shrink-0">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
              </svg>
            </div>
            <div>
              <h3 class="text-xs font-medium uppercase tracking-wider text-gray-900 dark:text-white">Delete Message</h3>
              <p class="text-[11px] text-gray-500 dark:text-gray-400">This cannot be undone</p>
            </div>
          </div>

          <p class="text-xs text-gray-600 dark:text-gray-300 leading-relaxed mb-6">
            Delete the message from <strong class="font-semibold text-gray-900 dark:text-white">{{ deleteTarget?.name }}</strong>?
            Once deleted it cannot be recovered.
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

      <ReplyModal :show="showReplyModal" :message="selectedMessage"
        @close="showReplyModal = false" @sent="fetchList" />

    </div>
  </AdminLayout>
</template>

<script setup>
import { ref, reactive, computed, onMounted } from 'vue'
import axios from 'axios'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import Modal from '@/Components/Modal.vue'
import { useFlash } from '@/Composables/useFlash'
import ReplyModal from './ReplyModal.vue'

const flash = useFlash()

const emptyPaginator = () => ({ data: [], current_page: 1, last_page: 1, total: 0, from: 0, to: 0 })

const messages = ref(emptyPaginator())
const counts = ref({ total: 0, unread: 0, read: 0, this_week: 0 })
const loading = ref(false)
const listContainer = ref(null)

const filters = reactive({
  search: '',
  status: '',
  when: '',
  per_page: 15,
  page: 1,
  sort_by: 'created_at',
  sort_dir: 'desc',
})

const saving = ref(false)
const showDeleteModal = ref(false)
const selectedMessage = ref(null)
const deleteTarget = ref(null)

let searchTimer = null

const hasActiveFilters = computed(() =>
  filters.search !== '' || filters.status !== '' || filters.when !== ''
)

const fetchList = async () => {
  const params = {}
  for (const [k, v] of Object.entries(filters)) {
    if (v !== '' && v !== false && v !== null && v !== undefined) params[k] = v
  }
  loading.value = true
  try {
    const { data } = await axios.get('/admin/contact-messages/list', { params })
    const p = data?.messages
    messages.value = p && Array.isArray(p.data) ? p : emptyPaginator()
    counts.value = data?.counts ?? counts.value

    // Keep the currently-selected message in sync with the refreshed list
    if (selectedMessage.value) {
      const fresh = messages.value.data.find(m => m.id === selectedMessage.value.id)
      if (fresh) selectedMessage.value = fresh
    }
  } catch (e) {
    console.error('Failed to load messages:', e)
    flash.error(e.response?.data?.message || 'Failed to load messages. Please refresh.')
  } finally {
    loading.value = false
  }
}

const debouncedFetch = () => {
  clearTimeout(searchTimer)
  searchTimer = setTimeout(() => { filters.page = 1; fetchList() }, 300)
}

const setStatusFilter = (status) => {
  filters.status = status
  filters.when = ''
  filters.page = 1
  fetchList()
}

const setWhenFilter = (when) => {
  filters.when = when
  filters.status = ''
  filters.page = 1
  fetchList()
}

const onWhenChange = () => {
  filters.status = ''
  filters.page = 1
  fetchList()
}

const changePage = (p) => {
  if (p < 1 || p > messages.value.last_page) return
  filters.page = p
  fetchList().then(() => {
    // Scroll list back to top
    listContainer.value?.scrollTo({ top: 0, behavior: 'smooth' })

    // If the selected message is no longer on this page, clear the preview
    if (selectedMessage.value) {
      const stillHere = messages.value.data.some(m => m.id === selectedMessage.value.id)
      if (!stillHere) selectedMessage.value = null
    }
  })
}

const changePageSize = () => {
  filters.page = 1
  fetchList()
}

const clearFilters = () => {
  Object.assign(filters, { search: '', status: '', when: '', page: 1 })
  fetchList()
}

const cardClass = (active, color = 'gray') => {
  const base = 'text-left p-5 rounded-2xl border shadow-xs flex items-center justify-between transition-colors cursor-pointer'
  const activeMap = {
    gray: 'bg-[#004d08]/5 dark:bg-[#86EFAC]/5 border-[#004d08]/40 dark:border-[#86EFAC]/30',
    amber: 'bg-amber-50/60 dark:bg-amber-950/20 border-amber-300 dark:border-amber-800',
    emerald: 'bg-emerald-50/60 dark:bg-emerald-950/20 border-emerald-300 dark:border-emerald-800',
    sky: 'bg-sky-50/60 dark:bg-sky-950/20 border-sky-300 dark:border-sky-800',
  }
  const inactive = 'bg-white dark:bg-[#2D3A31] border-gray-200/80 dark:border-[#3F4F43] hover:border-gray-300 dark:hover:border-gray-500'
  return `${base} ${active ? activeMap[color] : inactive}`
}

const initials = (name) => {
  if (!name) return '?'
  const parts = name.trim().split(' ')
  return parts.length === 1
    ? parts[0].charAt(0).toUpperCase()
    : (parts[0].charAt(0) + parts[parts.length - 1].charAt(0)).toUpperCase()
}

const formatRelative = (iso) => {
  if (!iso) return '—'
  try {
    const d = new Date(iso)
    const diff = (Date.now() - d.getTime()) / 1000
    if (diff < 60) return 'just now'
    if (diff < 3600) return Math.floor(diff / 60) + 'm ago'
    if (diff < 86400) return Math.floor(diff / 3600) + 'h ago'
    if (diff < 604800) return Math.floor(diff / 86400) + 'd ago'
    return d.toLocaleDateString('en-PH', { month: 'short', day: 'numeric' })
  } catch { return iso }
}

const formatFullDate = (iso) => {
  if (!iso) return '—'
  try { return new Date(iso).toLocaleString('en-PH', { month: 'long', day: 'numeric', year: 'numeric', hour: 'numeric', minute: '2-digit' }) }
  catch { return iso }
}

const openMessage = async (m) => {
  selectedMessage.value = m

  // Auto-mark as read
  if (!m.is_read) {
    try {
      await axios.put(`/admin/contact-messages/${m.id}/read`)
      m.is_read = true
      // Silent refresh of counts + list state
      const fresh = messages.value.data.find(x => x.id === m.id)
      if (fresh) fresh.is_read = true
      counts.value.unread = Math.max(0, (counts.value.unread || 0) - 1)
      counts.value.read   = (counts.value.read || 0) + 1
    } catch (e) {
      // Roll back local flag so the modal state stays consistent
      m.is_read = false
      console.error('Auto-mark-as-read failed:', e)
    }
  }
}

const toggleRead = async (m) => {
  try {
    await axios.put(`/admin/contact-messages/${m.id}/read`)
    const nowRead = !m.is_read
    m.is_read = nowRead
    flash.success(nowRead ? 'Marked as read.' : 'Marked as unread.')
    fetchList()
  } catch (e) {
    flash.error(e.response?.data?.message || 'Failed to update read status.')
  }
}

const confirmDelete = (m) => {
  deleteTarget.value = m
  showDeleteModal.value = true
}

const executeDelete = async () => {
  if (!deleteTarget.value) return
  const target = deleteTarget.value
  saving.value = true
  try {
    await axios.delete(`/admin/contact-messages/${target.id}`)
    flash.success('Message deleted.')

    // Close preview if it's showing the same message
    if (selectedMessage.value?.id === target.id) {
      selectedMessage.value = null
    }
    showDeleteModal.value = false
    deleteTarget.value = null
    fetchList()
  } catch (e) {
    flash.error(e.response?.data?.message || 'Failed to delete.')
  } finally {
    saving.value = false
  }
}

const showReplyModal = ref(false)

const openReplyModal = () => {
  if (!selectedMessage.value) return
  showReplyModal.value = true
}

onMounted(() => { fetchList() })
</script>