<template>
  <AdminLayout>
    <div class="space-y-6 pb-10 font-['Inter']">

      <!-- Hero Header Banner -->
      <div
        class="relative overflow-hidden w-full rounded-2xl sm:rounded-3xl shadow-lg border border-[#006907]/20 dark:border-none min-h-[200px] sm:min-h-[220px] flex flex-col justify-center p-4 sm:p-8 md:p-10">
        <!-- Background Image Layer -->
        <img
          :src="heroImage || 'https://images.unsplash.com/photo-1523240795612-9a054b0db644?q=80&w=1600&auto=format&fit=crop'"
          alt="Student Enrollments Hero" class="absolute inset-0 w-full h-full object-cover z-0 object-center" />

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
              <span>📋</span> ADMISSIONS
            </span>

            <span
              class="inline-flex items-center gap-2 bg-black/30 dark:bg-black/50 backdrop-blur-md text-white border border-white/20 text-[10px] sm:text-xs font-semibold px-3 sm:px-4 py-1 sm:py-1.5 rounded-full shadow-sm">
              <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
              Enrollments
            </span>
          </div>

          <!-- Title & Subtitle -->
          <div class="space-y-1 sm:space-y-1.5">
            <h1
              class="font-['Anton'] text-2xl sm:text-4xl md:text-5xl tracking-wide uppercase flex items-center gap-2 select-none flex-wrap leading-none text-white drop-shadow-md">
              <span>STUDENT</span>
              <span class="text-[#F9C20C] drop-shadow-[0_2px_8px_rgba(249,194,12,0.4)]">ENROLLMENTS</span>
            </h1>
            <p class="text-white/90 text-xs sm:text-sm md:text-base leading-relaxed font-medium">
              Approve registrations, assign sections, and monitor student placements per school year.
            </p>
          </div>
        </div>
      </div>

      <!-- Stat cards -->
      <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4">

        <button @click="setStatusFilter('')" :class="[
          'text-left p-5 rounded-2xl border shadow-xs flex items-center justify-between transition-colors cursor-pointer',
          filters.status === ''
            ? 'bg-[#004d08]/5 dark:bg-[#86EFAC]/5 border-[#004d08]/40 dark:border-[#86EFAC]/30'
            : 'bg-white dark:bg-[#2D3A31] border-gray-200/80 dark:border-[#3F4F43] hover:border-gray-300 dark:hover:border-gray-500'
        ]">
          <div class="space-y-1">
            <p class="text-[10px] font-medium uppercase text-gray-400 tracking-wider">Total</p>
            <p class="text-2xl font-medium text-gray-900 dark:text-white">{{ counts.total || 0 }}</p>
            <span class="text-[10px] text-gray-500 dark:text-gray-400 font-normal">All Records</span>
          </div>
          <div
            class="w-12 h-12 rounded-2xl bg-gray-50 dark:bg-[#1C261E] border border-gray-100 dark:border-[#3F4F43] flex items-center justify-center text-gray-600 dark:text-gray-300 shrink-0">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round"
                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
            </svg>
          </div>
        </button>

        <button @click="setStatusFilter('pending')" :class="[
          'text-left p-5 rounded-2xl border shadow-xs flex items-center justify-between transition-colors cursor-pointer',
          filters.status === 'pending'
            ? 'bg-amber-50/60 dark:bg-amber-950/20 border-amber-300 dark:border-amber-800'
            : 'bg-white dark:bg-[#2D3A31] border-gray-200/80 dark:border-[#3F4F43] hover:border-gray-300 dark:hover:border-gray-500'
        ]">
          <div class="space-y-1">
            <p class="text-[10px] font-medium uppercase text-amber-500 tracking-wider">Pending</p>
            <p class="text-2xl font-medium text-gray-900 dark:text-white">{{ counts.pending || 0 }}</p>
            <span class="text-[10px] text-amber-600 dark:text-amber-400 font-normal">Awaiting Action</span>
          </div>
          <div
            class="w-12 h-12 rounded-2xl bg-amber-50 dark:bg-[#1C261E] border border-amber-100 dark:border-[#3F4F43] flex items-center justify-center text-amber-600 dark:text-amber-400 shrink-0">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </div>
        </button>

        <button @click="setStatusFilter('enrolled')" :class="[
          'text-left p-5 rounded-2xl border shadow-xs flex items-center justify-between transition-colors cursor-pointer',
          filters.status === 'enrolled'
            ? 'bg-emerald-50/60 dark:bg-emerald-950/20 border-emerald-300 dark:border-emerald-800'
            : 'bg-white dark:bg-[#2D3A31] border-gray-200/80 dark:border-[#3F4F43] hover:border-gray-300 dark:hover:border-gray-500'
        ]">
          <div class="space-y-1">
            <p class="text-[10px] font-medium uppercase text-emerald-500 tracking-wider">Enrolled</p>
            <p class="text-2xl font-medium text-gray-900 dark:text-white">{{ counts.enrolled || 0 }}</p>
            <span class="text-[10px] text-emerald-600 dark:text-emerald-400 font-normal">Successfully Placed</span>
          </div>
          <div
            class="w-12 h-12 rounded-2xl bg-emerald-50 dark:bg-[#1C261E] border border-emerald-100 dark:border-[#3F4F43] flex items-center justify-center text-emerald-600 dark:text-emerald-400 shrink-0">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </div>
        </button>

        <button @click="setStatusFilter('dropped')" :class="[
          'text-left p-5 rounded-2xl border shadow-xs flex items-center justify-between transition-colors cursor-pointer',
          filters.status === 'dropped'
            ? 'bg-red-50/60 dark:bg-red-950/20 border-red-300 dark:border-red-800'
            : 'bg-white dark:bg-[#2D3A31] border-gray-200/80 dark:border-[#3F4F43] hover:border-gray-300 dark:hover:border-gray-500'
        ]">
          <div class="space-y-1">
            <p class="text-[10px] font-medium uppercase text-red-500 tracking-wider">Dropped</p>
            <p class="text-2xl font-medium text-gray-900 dark:text-white">{{ counts.dropped || 0 }}</p>
            <span class="text-[10px] text-red-600 dark:text-red-400 font-normal">Removed</span>
          </div>
          <div
            class="w-12 h-12 rounded-2xl bg-red-50 dark:bg-[#1C261E] border border-red-100 dark:border-[#3F4F43] flex items-center justify-center text-red-600 dark:text-red-400 shrink-0">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round"
                d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
            </svg>
          </div>
        </button>

        <button @click="showUnassigned" :class="[
          'text-left p-5 rounded-2xl border shadow-xs flex items-center justify-between transition-colors cursor-pointer',
          filters.unassigned === true
            ? 'bg-sky-50/60 dark:bg-sky-950/20 border-sky-300 dark:border-sky-800'
            : 'bg-white dark:bg-[#2D3A31] border-gray-200/80 dark:border-[#3F4F43] hover:border-gray-300 dark:hover:border-gray-500'
        ]">
          <div class="space-y-1">
            <p class="text-[10px] font-medium uppercase text-sky-500 tracking-wider">Unassigned</p>
            <p class="text-2xl font-medium text-gray-900 dark:text-white">{{ counts.unassigned || 0 }}</p>
            <span class="text-[10px] text-sky-600 dark:text-sky-400 font-normal">No Section Yet</span>
          </div>
          <div
            class="w-12 h-12 rounded-2xl bg-sky-50 dark:bg-[#1C261E] border border-sky-100 dark:border-[#3F4F43] flex items-center justify-center text-sky-600 dark:text-sky-400 shrink-0">
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
            <h2 class="text-xs font-medium text-gray-900 dark:text-white uppercase tracking-wider">Enrollments Roster
            </h2>
            <p class="text-[11px] text-gray-500 dark:text-gray-400 font-normal">Approve, reject, or reassign student
              placements</p>
          </div>

          <div class="flex flex-wrap sm:flex-nowrap items-center gap-2 w-full lg:w-auto lg:justify-end">
            <select v-model="filters.school_year_id" @change="fetchList()"
              class="appearance-none pl-3 pr-8 py-2 text-xs bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white rounded-xl focus:ring-2 focus:ring-[#004d08] focus:outline-none font-normal cursor-pointer">
              <option value="">All School Years</option>
              <option v-for="sy in schoolYears" :key="sy.id" :value="sy.id">{{ sy.label }}</option>
            </select>

            <select v-model="filters.section_id" @change="fetchList()"
              class="appearance-none pl-3 pr-8 py-2 text-xs bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white rounded-xl focus:ring-2 focus:ring-[#004d08] focus:outline-none font-normal cursor-pointer max-w-[180px] truncate">
              <option value="">All Sections</option>
              <option v-for="s in sectionsForYear" :key="s.id" :value="s.id">{{ s.name }}</option>
            </select>

            <div class="relative w-full sm:w-56">
              <svg class="w-4 h-4 absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 dark:text-gray-500"
                fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
              </svg>
              <input v-model="filters.search" @input="debouncedFetch" type="text" placeholder="Search student..."
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

        <!-- Table -->
        <div class="overflow-x-auto">
          <table class="w-full text-left border-collapse">
            <thead>
              <tr
                class="border-b border-gray-100 dark:border-[#3F4F43] bg-gray-50/50 dark:bg-[#232D26]/50 text-[10px] font-medium uppercase text-gray-400 tracking-wider">
                <th class="py-3.5 px-5">
                  <button @click="setSort('student_name')"
                    class="inline-flex items-center gap-1 hover:text-gray-700 dark:hover:text-gray-200 transition-colors cursor-pointer">
                    Student
                    <SortIcon field="student_name" />
                  </button>
                </th>
                <th class="py-3.5 px-5">School Year</th>
                <th class="py-3.5 px-5">Section</th>
                <th class="py-3.5 px-5 text-center">
                  <button @click="setSort('status')"
                    class="inline-flex items-center gap-1 hover:text-gray-700 dark:hover:text-gray-200 transition-colors cursor-pointer">
                    Status
                    <SortIcon field="status" />
                  </button>
                </th>
                <th class="py-3.5 px-5">
                  <button @click="setSort('enrolled_at')"
                    class="inline-flex items-center gap-1 hover:text-gray-700 dark:hover:text-gray-200 transition-colors cursor-pointer">
                    Enrolled
                    <SortIcon field="enrolled_at" />
                  </button>
                </th>
                <th class="py-3.5 px-5 text-right">Actions</th>
              </tr>
            </thead>
            <tbody
              class="divide-y divide-gray-100 dark:divide-[#3F4F43] text-xs font-normal text-gray-800 dark:text-gray-200">

              <tr v-for="e in enrollments.data" :key="e.id"
                class="hover:bg-gray-50/80 dark:hover:bg-white/5 transition-colors">
                <td class="py-4 px-5">
                  <div class="flex items-center gap-3">
                    <div
                      class="w-8 h-8 rounded-full bg-emerald-100 dark:bg-[#1C261E] text-[#004d08] dark:text-[#86EFAC] border border-emerald-200 dark:border-[#3F4F43] flex items-center justify-center font-medium text-xs shrink-0">
                      {{ initials(e.student_name) }}
                    </div>
                    <div class="min-w-0">
                      <p class="font-medium text-gray-900 dark:text-white truncate">{{ e.student_name || '—' }}</p>
                      <p class="text-[10px] text-gray-500 dark:text-gray-400 font-mono">LRN {{ e.lrn || '—' }}</p>
                    </div>
                  </div>
                </td>
                <td class="py-4 px-5 text-gray-600 dark:text-gray-300">{{ e.school_year || '—' }}</td>
                <td class="py-4 px-5">
                  <span v-if="e.section" class="text-gray-900 dark:text-white">{{ e.section }}</span>
                  <span v-else
                    class="px-2 py-0.5 rounded-md text-[10px] font-medium uppercase tracking-wider bg-amber-50 dark:bg-amber-950/50 text-amber-700 dark:text-amber-300 border border-amber-200 dark:border-amber-800">
                    Unassigned
                  </span>
                </td>
                <td class="py-4 px-5 text-center">
                  <span
                    class="px-2.5 py-1 rounded-full text-[10px] font-normal uppercase tracking-wider inline-flex items-center gap-1.5 border"
                    :class="statusBadge(e.status)">
                    <span :class="['w-1.5 h-1.5 rounded-full', statusDot(e.status)]"></span>
                    {{ e.status }}
                  </span>
                </td>
                <td class="py-4 px-5 text-[10px] text-gray-500 dark:text-gray-400">
                  {{ formatDate(e.enrolled_at) }}
                </td>
                <td class="py-4 px-5 text-right">
                  <div class="flex items-center justify-end gap-1.5">
                    <!-- Assign section (unassigned only) -->
                    <button v-if="!e.section_id" @click="openAssignModal(e)"
                      class="px-2.5 py-1 text-[10px] font-normal text-sky-700 dark:text-sky-300 bg-sky-50 hover:bg-sky-100 dark:bg-sky-950/50 rounded-lg border border-sky-200 dark:border-sky-800 transition-colors cursor-pointer">
                      Assign
                    </button>

                    <!-- Approve (pending only) -->
                    <button v-if="e.status === 'pending'" @click="approve(e)"
                      class="px-2.5 py-1 text-[10px] font-normal text-emerald-700 dark:text-emerald-400 bg-emerald-50 hover:bg-emerald-100 dark:bg-emerald-950/50 rounded-lg border border-emerald-200 dark:border-emerald-800 transition-colors cursor-pointer">
                      Approve
                    </button>

                    <!-- Reject (pending only) -->
                    <button v-if="e.status === 'pending'" @click="openRejectModal(e)"
                      class="px-2.5 py-1 text-[10px] font-normal text-red-700 dark:text-red-400 bg-red-50 hover:bg-red-100 dark:bg-red-950/50 rounded-lg border border-red-200 dark:border-red-900 transition-colors cursor-pointer">
                      Reject
                    </button>


                  </div>
                </td>
              </tr>

              <!-- Empty state -->
              <tr v-if="!enrollments.data || enrollments.data.length === 0">
                <td colspan="6" class="py-12 text-center text-gray-400 dark:text-gray-500 font-normal">
                  <p class="text-xs">No enrollments match your filters.</p>
                  <p class="text-[11px] mt-0.5">Try adjusting your filters or search keywords.</p>
                </td>
              </tr>

            </tbody>
          </table>
        </div>

        <!-- Pagination bar (with row-count dropdown) -->
        <div v-if="enrollments.total > 0"
          class="px-5 py-3.5 bg-gray-50/50 dark:bg-[#232D26]/50 border-t border-gray-100 dark:border-[#3F4F43] flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-gray-500 dark:text-gray-400">
          <div class="flex items-center gap-3">
            <span>
              Showing <span class="font-semibold text-gray-900 dark:text-white">{{ enrollments.from || 0 }}</span>
              to <span class="font-semibold text-gray-900 dark:text-white">{{ enrollments.to || 0 }}</span>
              of <span class="font-semibold text-gray-900 dark:text-white">{{ enrollments.total }}</span> entries
            </span>
            <div class="relative">
              <select v-model.number="filters.per_page" @change="changePageSize"
                class="appearance-none pl-2 pr-7 py-1 text-[11px] bg-white dark:bg-[#2D3A31] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white rounded-lg focus:ring-2 focus:ring-[#004d08] focus:outline-none cursor-pointer">
                <option :value="10">10</option>
                <option :value="15">15</option>
                <option :value="25">25</option>
                <option :value="50">50</option>
                <option :value="100">100</option>
              </select>
            </div>
          </div>

          <div class="flex items-center gap-1">
            <button @click="changePage(enrollments.current_page - 1)" :disabled="enrollments.current_page <= 1"
              class="px-3 py-1.5 rounded-lg border border-gray-200 dark:border-[#3F4F43] bg-white dark:bg-[#2D3A31] text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-white/5 disabled:opacity-40 cursor-pointer disabled:cursor-not-allowed transition-colors">
              Previous
            </button>
            <span class="px-3 py-1.5 font-medium text-gray-900 dark:text-white">
              Page {{ enrollments.current_page }} of {{ enrollments.last_page }}
            </span>
            <button @click="changePage(enrollments.current_page + 1)"
              :disabled="enrollments.current_page >= enrollments.last_page"
              class="px-3 py-1.5 rounded-lg border border-gray-200 dark:border-[#3F4F43] bg-white dark:bg-[#2D3A31] text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-white/5 disabled:opacity-40 cursor-pointer disabled:cursor-not-allowed transition-colors">
              Next
            </button>
          </div>
        </div>

      </div>

      <!-- Assign modal -->
      <Modal :show="showAssignModal" @close="showAssignModal = false" max-width="md">
        <div
          class="bg-white dark:bg-[#2D3A31] rounded-2xl border border-gray-200 dark:border-[#3F4F43] p-6 font-['Inter']">
          <div class="flex items-center gap-3 mb-4">
            <div
              class="w-10 h-10 rounded-2xl bg-sky-50 dark:bg-sky-950/50 border border-sky-100 dark:border-sky-900/50 flex items-center justify-center text-sky-600 dark:text-sky-400 shrink-0">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                  d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5m0 0h4m-4 0V11m0 0h4m-4 0H9m4 0v10" />
              </svg>
            </div>
            <div>
              <h3 class="text-xs font-medium uppercase tracking-wider text-gray-900 dark:text-white">Assign Section</h3>
              <p class="text-[11px] text-gray-500 dark:text-gray-400">
                {{ assignTarget?.student_name }} &bull; {{ assignTarget?.school_year }}
              </p>
            </div>
          </div>

          <select v-model="assignSectionId"
            class="w-full px-3.5 py-2.5 text-xs bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white rounded-xl focus:ring-2 focus:ring-[#004d08] focus:outline-none font-normal cursor-pointer">
            <option value="" disabled>Select a section...</option>
            <option v-for="s in assignableSections" :key="s.id" :value="s.id">{{ s.name }}</option>
          </select>
          <p v-if="!assignableSections.length" class="mt-2 text-[10px] text-amber-600 dark:text-amber-400">
            No sections available for this school year.
          </p>

          <div class="flex justify-end gap-2 mt-4">
            <button @click="showAssignModal = false"
              class="px-4 py-2 text-xs text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-white/5 rounded-xl cursor-pointer">
              Cancel
            </button>
            <button @click="confirmAssign" :disabled="saving || !assignSectionId"
              class="px-5 py-2 text-xs font-medium uppercase tracking-wider bg-[#004d08] hover:bg-emerald-900 text-white rounded-xl disabled:opacity-50 cursor-pointer">
              {{ saving ? 'Assigning…' : 'Assign & Enroll' }}
            </button>
          </div>
        </div>
      </Modal>

      <!-- Reject modal -->
      <Modal :show="showRejectModal" @close="showRejectModal = false" max-width="md">
        <div
          class="bg-white dark:bg-[#2D3A31] rounded-2xl border border-gray-200 dark:border-[#3F4F43] p-6 font-['Inter']">
          <div class="flex items-center gap-3 mb-4">
            <div
              class="w-10 h-10 rounded-2xl bg-red-50 dark:bg-red-950/50 border border-red-100 dark:border-red-900/50 flex items-center justify-center text-red-600 dark:text-red-400 shrink-0">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </div>
            <div>
              <h3 class="text-xs font-medium uppercase tracking-wider text-gray-900 dark:text-white">Reject Enrollment
              </h3>
              <p class="text-[11px] text-gray-500 dark:text-gray-400">The student will be marked as dropped</p>
            </div>
          </div>

          <textarea v-model="rejectReason" rows="3" placeholder="Optional reason (internal only)"
            class="w-full px-3.5 py-2.5 text-xs bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white rounded-xl focus:ring-2 focus:ring-[#004d08] focus:outline-none font-normal resize-none"></textarea>

          <div class="flex justify-end gap-2 mt-4">
            <button @click="showRejectModal = false"
              class="px-4 py-2 text-xs text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-white/5 rounded-xl cursor-pointer">
              Cancel
            </button>
            <button @click="confirmReject" :disabled="saving"
              class="px-5 py-2 text-xs font-medium uppercase tracking-wider bg-red-600 hover:bg-red-700 text-white rounded-xl disabled:opacity-50 cursor-pointer">
              {{ saving ? 'Rejecting…' : 'Reject' }}
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
import AdminLayout from '@/Layouts/AdminLayout.vue'
import Modal from '@/Components/Modal.vue'
import { useFlash } from '@/Composables/useFlash'


const props = defineProps({
  schoolYears: { type: Array, default: () => [] },
  sections: { type: Array, default: () => [] },
  activeYearId: { type: Number, default: null },
})

const flash = useFlash()
const emptyPaginator = () => ({ data: [], current_page: 1, last_page: 1, total: 0, from: 0, to: 0 })

const enrollments = ref(emptyPaginator())
const counts = ref({ total: 0, pending: 0, enrolled: 0, dropped: 0, unassigned: 0 })

const filters = reactive({
  search: '',
  status: '',
  school_year_id: '',
  section_id: '',
  unassigned: false,
  per_page: 10,
  page: 1,
  sort_by: 'enrolled_at',
  sort_dir: 'desc',
})

const saving = ref(false)
const showAssignModal = ref(false)
const showRejectModal = ref(false)
const assignTarget = ref(null)
const assignSectionId = ref('')
const rejectTarget = ref(null)
const rejectReason = ref('')

let searchTimer = null

const hasActiveFilters = computed(() =>
  filters.search !== '' || filters.status !== '' ||
  filters.school_year_id !== '' || filters.section_id !== '' || filters.unassigned
)

const sectionsForYear = computed(() =>
  !filters.school_year_id
    ? props.sections
    : props.sections.filter(s => s.school_year_id === filters.school_year_id)
)

const assignableSections = computed(() =>
  !assignTarget.value
    ? []
    : props.sections.filter(s => s.school_year_id === assignTarget.value.school_year_id)
)

onMounted(() => {
  if (props.activeYearId) filters.school_year_id = props.activeYearId
  fetchList()
})

const fetchList = async () => {
  const params = {}
  for (const [k, v] of Object.entries(filters)) {
    if (v !== '' && v !== false && v !== null && v !== undefined) params[k] = v
  }
  try {
    const { data } = await axios.get('/admin/enrollments/list', { params })
    const p = data?.enrollments
    enrollments.value = p && Array.isArray(p.data) ? p : emptyPaginator()
    counts.value = data?.counts ?? counts.value
  } catch (e) {
    console.error('Failed to load enrollments:', e)
    enrollments.value = emptyPaginator()
  }
}

const debouncedFetch = () => {
  clearTimeout(searchTimer)
  searchTimer = setTimeout(() => { filters.page = 1; fetchList() }, 300)
}

const setStatusFilter = (s) => {
  filters.status = s
  filters.unassigned = false
  filters.page = 1
  fetchList()
}

const showUnassigned = () => {
  filters.status = ''
  filters.unassigned = true
  filters.page = 1
  fetchList()
}

const setSort = (field) => {
  if (filters.sort_by === field) {
    filters.sort_dir = filters.sort_dir === 'asc' ? 'desc' : 'asc'
  } else {
    filters.sort_by = field
    filters.sort_dir = field === 'enrolled_at' ? 'desc' : 'asc'
  }
  filters.page = 1
  fetchList()
}

const changePage = (p) => {
  if (p < 1 || p > enrollments.value.last_page) return
  filters.page = p
  fetchList()
}

const changePageSize = () => {
  filters.page = 1
  fetchList()
}

const clearFilters = () => {
  Object.assign(filters, {
    search: '',
    status: '',
    school_year_id: props.activeYearId || '',
    section_id: '',
    unassigned: false,
    page: 1,
  })
  fetchList()
}

/* SortIcon — same inline pattern used across the admin pages */
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

const statusBadge = (s) => ({
  pending: 'bg-amber-50 dark:bg-amber-950/50 text-amber-700 dark:text-amber-300 border-amber-200 dark:border-amber-800',
  enrolled: 'bg-emerald-100 dark:bg-emerald-950/60 text-emerald-800 dark:text-emerald-300 border-emerald-200 dark:border-emerald-800',
  dropped: 'bg-red-50 dark:bg-red-950/50 text-red-700 dark:text-red-300 border-red-200 dark:border-red-800',
  transferred: 'bg-sky-50 dark:bg-sky-950/50 text-sky-700 dark:text-sky-300 border-sky-200 dark:border-sky-800',
  completed: 'bg-gray-100 dark:bg-[#232D26] text-gray-600 dark:text-gray-400 border-gray-200 dark:border-[#3F4F43]',
}[s] || 'bg-gray-100 dark:bg-[#232D26] text-gray-600 dark:text-gray-400 border-gray-200 dark:border-[#3F4F43]')

const statusDot = (s) => ({
  pending: 'bg-amber-500',
  enrolled: 'bg-emerald-500',
  dropped: 'bg-red-500',
  transferred: 'bg-sky-500',
  completed: 'bg-gray-400',
}[s] || 'bg-gray-400')

const formatDate = (iso) => {
  if (!iso) return '—'
  try { return new Date(iso).toLocaleDateString('en-PH', { month: 'short', day: 'numeric', year: 'numeric' }) }
  catch { return iso }
}

const approve = async (e) => {
  const { confirmed } = await confirm({ title, message, details, confirmLabel, variant }); if (!confirmed) return
  try {
    await axios.put(`/admin/enrollments/${e.id}/approve`)
    flash.success(`${e.student_name}'s enrollment approved.`)
    fetchList()
  } catch (err) {
    const msg = err.response?.data?.message || 'Failed to approve.'
    flash.error(msg)
  }
}

const openRejectModal = (e) => {
  rejectTarget.value = e
  rejectReason.value = ''
  showRejectModal.value = true
}

const confirmReject = async () => {
  if (!rejectTarget.value) return
  saving.value = true
  try {
    await axios.put(`/admin/enrollments/${rejectTarget.value.id}/reject`, {
      reason: rejectReason.value || null,
    })
    flash.success(`${rejectTarget.value.student_name}'s enrollment rejected.`)
    showRejectModal.value = false
    rejectTarget.value = null
    fetchList()
  } catch (err) {
    const msg = err.response?.data?.message || 'Failed to reject.'
    flash.error(msg)
  } finally {
    saving.value = false
  }
}

const openAssignModal = (e) => {
  assignTarget.value = e
  assignSectionId.value = ''
  showAssignModal.value = true
}

const confirmAssign = async () => {
  if (!assignTarget.value || !assignSectionId.value) return
  saving.value = true
  try {
    await axios.put(`/admin/enrollments/${assignTarget.value.id}/assign-section`, {
      section_id: assignSectionId.value,
    })
    flash.success(`${assignTarget.value.student_name} assigned.`)
    showAssignModal.value = false
    assignTarget.value = null
    fetchList()
  } catch (err) {
    const msg = err.response?.data?.message || 'Failed to assign section.'
    flash.error(msg)
  } finally {
    saving.value = false
  }
}
</script>