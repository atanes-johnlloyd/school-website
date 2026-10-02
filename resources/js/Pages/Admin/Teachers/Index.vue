<template>
  <AdminLayout>
    <div class="space-y-6 max-w-7xl mx-auto pb-10 font-['Inter']">

      <!-- Hero Banner -->
      <div
        class="relative overflow-hidden w-full rounded-2xl sm:rounded-3xl shadow-lg border border-[#006907]/20 dark:border-none min-h-[220px] sm:min-h-[240px] flex flex-col justify-center p-4 sm:p-8 md:p-10">
        <!-- Background Image Layer (Faculty / Teacher Workspace Theme) -->
        <img
          :src="heroImage || 'https://images.unsplash.com/photo-1577896851231-70ef18881754?q=80&w=1600&auto=format&fit=crop'"
          alt="Faculty Directory Hero" class="absolute inset-0 w-full h-full object-cover z-0 object-center" />

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
                <span>👩‍🏫</span> FACULTY MANAGEMENT
              </span>

              <span
                class="inline-flex items-center gap-2 bg-black/30 dark:bg-black/50 backdrop-blur-md text-white border border-white/20 text-[10px] sm:text-xs font-semibold px-3 sm:px-4 py-1 sm:py-1.5 rounded-full shadow-sm">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                Teacher Directory
              </span>
            </div>

            <!-- Title & Subtitle -->
            <div class="space-y-1 sm:space-y-1.5">
              <h1
                class="font-['Anton'] text-2xl sm:text-4xl md:text-5xl tracking-wide uppercase flex items-center gap-2 select-none flex-wrap leading-none text-white drop-shadow-md">
                <span>FACULTY</span>
                <span class="text-[#F9C20C] drop-shadow-[0_2px_8px_rgba(249,194,12,0.4)]">DIRECTORY</span>
              </h1>
              <p class="text-white/90 text-xs sm:text-sm md:text-base leading-relaxed font-medium">
                Manage teaching staff, track department specializations, and create user access accounts.
              </p>
            </div>
          </div>

          <!-- Action Buttons -->
          <div class="flex items-center gap-2 shrink-0 flex-wrap sm:flex-nowrap pt-2 md:pt-0">
            <button @click="showImportModal = true"
              class="inline-flex items-center justify-center gap-2 bg-black/30 hover:bg-black/40 backdrop-blur-md text-white border border-white/20 font-bold px-3.5 sm:px-4 py-2.5 sm:py-3 rounded-xl transition-all active:scale-95 text-xs sm:text-sm cursor-pointer shadow-sm"
              title="Import faculty from CSV">
              <svg class="w-4 h-4 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                  d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1M12 12V4m0 0L8 8m4-4l4 4" />
              </svg>
              <span class="hidden sm:inline">Import</span>
            </button>

            <button @click="exportTeachers"
              class="inline-flex items-center justify-center gap-2 bg-black/30 hover:bg-black/40 backdrop-blur-md text-white border border-white/20 font-bold px-3.5 sm:px-4 py-2.5 sm:py-3 rounded-xl transition-all active:scale-95 text-xs sm:text-sm cursor-pointer shadow-sm"
              title="Export filtered faculty">
              <svg class="w-4 h-4 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                  d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1M7 10l5 5m0 0l5-5m-5 5V3" />
              </svg>
              <span class="hidden sm:inline">Export</span>
            </button>

            <button @click="openCreateModal"
              class="inline-flex items-center justify-center gap-2 bg-[#F9C20C] hover:bg-[#e0ae0a] text-[#2C3E2D] font-black px-4 sm:px-5 py-2.5 sm:py-3 rounded-xl transition-all shadow-md active:scale-95 text-xs sm:text-sm cursor-pointer">
              <span class="text-base sm:text-lg leading-none">+</span>
              <span>Add Faculty</span>
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
            <p class="text-[10px] font-medium uppercase text-gray-400 tracking-wider">Total Faculty</p>
            <p class="text-2xl font-medium text-gray-900 dark:text-white">{{ counts.total || 0 }}</p>
            <span class="text-[10px] text-gray-500 dark:text-gray-400 font-normal">Registered Accounts</span>
          </div>
          <div
            class="w-12 h-12 rounded-2xl bg-emerald-50 dark:bg-[#1C261E] border border-emerald-100 dark:border-[#3F4F43] flex items-center justify-center text-emerald-700 dark:text-[#86EFAC] shrink-0">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round"
                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
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
            <span class="text-[10px] text-emerald-600 dark:text-emerald-400 font-normal">Available for Scheduling</span>
          </div>
          <div
            class="w-12 h-12 rounded-2xl bg-emerald-50 dark:bg-[#1C261E] border border-emerald-100 dark:border-[#3F4F43] flex items-center justify-center text-emerald-600 dark:text-emerald-400 shrink-0">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </div>
        </button>

        <button @click="setStatusFilter('inactive')" :class="[
          'text-left p-5 rounded-2xl border shadow-xs flex items-center justify-between transition-colors cursor-pointer',
          filters.status === 'inactive'
            ? 'bg-amber-50/60 dark:bg-amber-950/20 border-amber-300 dark:border-amber-800'
            : 'bg-white dark:bg-[#2D3A31] border-gray-200/80 dark:border-[#3F4F43] hover:border-gray-300 dark:hover:border-gray-500'
        ]">
          <div class="space-y-1">
            <p class="text-[10px] font-medium uppercase text-amber-500 tracking-wider">Inactive</p>
            <p class="text-2xl font-medium text-gray-900 dark:text-white">{{ counts.inactive || 0 }}</p>
            <span class="text-[10px] text-amber-600 dark:text-amber-400 font-normal">Not Available</span>
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
            <h2 class="text-xs font-medium text-gray-900 dark:text-white uppercase tracking-wider">Faculty Roster</h2>
            <p class="text-[11px] text-gray-500 dark:text-gray-400 font-normal">Search and manage faculty profiles</p>
          </div>

          <div class="flex flex-wrap sm:flex-nowrap items-center gap-2 w-full lg:w-auto lg:justify-end">

            <!-- Department Filter -->
            <div class="relative">
              <select v-model="filters.department" @change="fetchTeachers()"
                class="appearance-none pl-3 pr-8 py-2 text-xs bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white rounded-xl focus:ring-2 focus:ring-[#004d08] focus:outline-none font-normal cursor-pointer max-w-[180px] truncate">
                <option value="">All Departments</option>
                <option v-for="d in departments" :key="d" :value="d">{{ d }}</option>
              </select>
            </div>

            <!-- Sex Filter -->
            <div class="relative">
              <select v-model="filters.sex" @change="fetchTeachers()"
                class="appearance-none pl-3 pr-8 py-2 text-xs bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white rounded-xl focus:ring-2 focus:ring-[#004d08] focus:outline-none font-normal cursor-pointer">
                <option value="">All Sex</option>
                <option value="male">Male</option>
                <option value="female">Female</option>
              </select>
            </div>

            <!-- Search -->
            <div class="relative w-full sm:w-64">
              <svg class="w-4 h-4 absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 dark:text-gray-500"
                fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
              </svg>
              <input v-model="filters.search" @input="debouncedFetch" type="text" placeholder="Search name, ID, dept..."
                class="w-full pl-10 pr-8 py-2 text-xs bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white placeholder-gray-400 rounded-xl focus:ring-2 focus:ring-[#004d08] dark:focus:ring-[#86EFAC] focus:outline-none font-normal" />
              <button v-if="filters.search" @click="filters.search = ''; fetchTeachers()"
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
                  <button @click="setSort('employee_no')"
                    class="inline-flex items-center gap-1 hover:text-gray-700 dark:hover:text-gray-200 transition-colors cursor-pointer">
                    Emp No.
                    <SortIcon field="employee_no" />
                  </button>
                </th>
                <th class="py-3.5 px-5">
                  <button @click="setSort('name')"
                    class="inline-flex items-center gap-1 hover:text-gray-700 dark:hover:text-gray-200 transition-colors cursor-pointer">
                    Faculty Member
                    <SortIcon field="name" />
                  </button>
                </th>
                <th class="py-3.5 px-5">
                  <button @click="setSort('department')"
                    class="inline-flex items-center gap-1 hover:text-gray-700 dark:hover:text-gray-200 transition-colors cursor-pointer">
                    Department &amp; Specialization
                    <SortIcon field="department" />
                  </button>
                </th>
                <th class="py-3.5 px-5">Contact</th>
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
              <tr v-for="teacher in teachers.data" :key="teacher.id"
                class="hover:bg-gray-50/80 dark:hover:bg-white/5 transition-colors">
                <td class="py-4 px-5">
                  <span
                    class="font-semibold text-gray-900 dark:text-white text-xs bg-gray-100 dark:bg-[#232D26] px-2.5 py-1 rounded-lg border border-gray-200 dark:border-[#3F4F43] font-mono">
                    {{ teacher.employee_no }}
                  </span>
                </td>

                <td class="py-4 px-5">
                  <div class="flex items-center gap-3">
                    <div
                      class="w-8 h-8 rounded-full bg-emerald-100 dark:bg-[#1C261E] text-[#004d08] dark:text-[#86EFAC] border border-emerald-200 dark:border-[#3F4F43] flex items-center justify-center font-medium text-xs shrink-0">
                      {{ getInitials(teacher.name) }}
                    </div>
                    <div>
                      <span class="font-medium text-gray-900 dark:text-white block">{{ teacher.name || 'N/A' }}</span>
                      <span class="text-[11px] text-gray-500 dark:text-gray-400 font-normal">{{ teacher.email }}</span>
                    </div>
                  </div>
                </td>

                <td class="py-4 px-5">
                  <div>
                    <span class="text-xs text-gray-900 dark:text-white font-medium block">
                      {{ teacher.department || 'Unassigned' }}
                    </span>
                    <span v-if="teacher.specialization"
                      class="text-[11px] text-gray-500 dark:text-gray-400 font-normal">
                      {{ teacher.specialization }}
                    </span>
                  </div>
                </td>

                <td class="py-4 px-5 text-gray-600 dark:text-gray-300 font-normal">
                  {{ teacher.contact_number || 'None' }}
                </td>

                <td class="py-4 px-5 text-center">
                  <span
                    class="px-2.5 py-1 rounded-full text-[10px] font-normal uppercase tracking-wider inline-flex items-center gap-1.5 border"
                    :class="teacher.is_active
                      ? 'bg-emerald-100 dark:bg-emerald-950/60 text-emerald-800 dark:text-emerald-300 border-emerald-200 dark:border-emerald-800'
                      : 'bg-gray-100 dark:bg-[#232D26] text-gray-600 dark:text-gray-400 border-gray-200 dark:border-[#3F4F43]'">
                    <span
                      :class="['w-1.5 h-1.5 rounded-full', teacher.is_active ? 'bg-emerald-500' : 'bg-gray-400']"></span>
                    {{ teacher.is_active ? 'Active' : 'Inactive' }}
                  </span>
                </td>

                <td class="py-4 px-5 text-right">
                  <div class="flex items-center justify-end gap-1.5">
                    <button @click="openViewModal(teacher)"
                      class="px-2.5 py-1 text-[10px] font-normal text-sky-700 dark:text-sky-300 bg-sky-50 hover:bg-sky-100 dark:bg-sky-950/50 rounded-lg border border-sky-200 dark:border-sky-800 transition-colors cursor-pointer">
                      View
                    </button>
                    <button @click="openEditModal(teacher)"
                      class="px-2.5 py-1 text-[10px] font-normal text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-[#232D26] hover:bg-gray-200 dark:hover:bg-white/10 rounded-lg transition-colors cursor-pointer">
                      Edit
                    </button>
                    <button @click="openDeleteConfirmation(teacher)"
                      class="px-2.5 py-1 text-[10px] font-normal text-red-700 dark:text-red-400 bg-red-50 hover:bg-red-100 dark:bg-red-950/50 rounded-lg border border-red-200 dark:border-red-900 transition-colors cursor-pointer">
                      Deactivate
                    </button>
                  </div>
                </td>
              </tr>

              <tr v-if="!teachers.data || teachers.data.length === 0">
                <td colspan="6" class="py-12 text-center text-gray-400 dark:text-gray-500 font-normal">
                  <p class="text-xs">No faculty records found.</p>
                  <p class="text-[11px] mt-0.5">Try adjusting your filters or search keywords.</p>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Pagination -->
        <div v-if="teachers.total > 0"
          class="px-5 py-3.5 bg-gray-50/50 dark:bg-[#232D26]/50 border-t border-gray-100 dark:border-[#3F4F43] flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-gray-500 dark:text-gray-400">
          <div class="flex items-center gap-3">
            <span>
              Showing <span class="font-semibold text-gray-900 dark:text-white">{{ teachers.from || 0 }}</span>
              to <span class="font-semibold text-gray-900 dark:text-white">{{ teachers.to || 0 }}</span>
              of <span class="font-semibold text-gray-900 dark:text-white">{{ teachers.total }}</span> entries
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
            <button @click="changePage(teachers.current_page - 1)" :disabled="teachers.current_page <= 1"
              class="px-3 py-1.5 rounded-lg border border-gray-200 dark:border-[#3F4F43] bg-white dark:bg-[#2D3A31] text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-white/5 disabled:opacity-40 cursor-pointer disabled:cursor-not-allowed transition-colors">
              Previous
            </button>
            <span class="px-3 py-1.5 font-medium text-gray-900 dark:text-white">
              Page {{ teachers.current_page }} of {{ teachers.last_page }}
            </span>
            <button @click="changePage(teachers.current_page + 1)"
              :disabled="teachers.current_page >= teachers.last_page"
              class="px-3 py-1.5 rounded-lg border border-gray-200 dark:border-[#3F4F43] bg-white dark:bg-[#2D3A31] text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-white/5 disabled:opacity-40 cursor-pointer disabled:cursor-not-allowed transition-colors">
              Next
            </button>
          </div>
        </div>
      </div>

      <!-- Modals -->
      <TeacherFormModal :show="showFormModal" :teacher="selectedTeacher"
        @close="showFormModal = false; fetchTeachers()" />

      <ViewTeacherModal :show="showViewModal" :teacher="selectedTeacher" @close="showViewModal = false"
        @edit="fromViewToEdit" />

      <ImportTeachersModal :show="showImportModal" @close="showImportModal = false" @imported="fetchTeachers" />

      <!-- Deactivate Confirmation -->
      <Modal :show="showDeleteModal" @close="showDeleteModal = false" max-width="md">
        <div
          class="p-6 bg-white dark:bg-[#2D3A31] rounded-3xl border border-gray-200 dark:border-[#3F4F43] font-['Inter'] transition-colors">
          <div class="flex items-center gap-3.5 mb-4">
            <div
              class="w-10 h-10 rounded-2xl bg-red-50 dark:bg-red-950/50 border border-red-100 dark:border-red-900/50 flex items-center justify-center text-red-600 dark:text-red-400 shrink-0">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                  d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
              </svg>
            </div>
            <div>
              <h3 class="text-xs font-medium uppercase tracking-wider text-gray-900 dark:text-white">Deactivate Faculty
                Profile</h3>
              <p class="text-[11px] text-gray-500 dark:text-gray-400 font-normal">Confirm account status change</p>
            </div>
          </div>

          <p class="text-xs text-gray-600 dark:text-gray-300 font-normal leading-relaxed mb-6">
            Are you sure you want to deactivate <strong class="font-semibold text-gray-900 dark:text-white">{{
              teacherToDelete?.name || 'this faculty member' }}</strong>?
            This will soft-delete their record and set employment status to inactive.
          </p>

          <div class="flex justify-end gap-2.5 pt-4 border-t border-gray-100 dark:border-[#3F4F43]">
            <button type="button" @click="showDeleteModal = false"
              class="px-4 py-2 text-xs font-normal text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-white/5 rounded-xl transition-all cursor-pointer">
              Cancel
            </button>
            <button type="button" :disabled="isDeactivating" @click="executeDeactivation"
              class="px-5 py-2 text-xs font-medium uppercase tracking-wider bg-red-600 hover:bg-red-700 text-white rounded-xl transition-all shadow-md active:scale-95 disabled:opacity-50 cursor-pointer">
              {{ isDeactivating ? 'Deactivating…' : 'Confirm Deactivate' }}
            </button>
          </div>
        </div>
      </Modal>

    </div>
  </AdminLayout>
</template>

<script setup>
import { ref, reactive, computed, onMounted, h } from 'vue'
import axios from 'axios'
import { router } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import Modal from '@/Components/Modal.vue'
import TeacherFormModal from './TeacherFormModal.vue'
import ViewTeacherModal from './ViewTeacherModal.vue'
import ImportTeachersModal from './ImportTeachersModal.vue'

const props = defineProps({
  departments: { type: Array, default: () => [] },
})

const emptyPaginator = () => ({ data: [], current_page: 1, last_page: 1, total: 0, from: 0, to: 0 })

const teachers = ref(emptyPaginator())
const counts = ref({ total: 0, active: 0, inactive: 0 })

const filters = reactive({
  search: '',
  status: '',
  department: '',
  sex: '',
  per_page: 10,
  page: 1,
  sort_by: 'employee_no',
  sort_dir: 'asc',
})

let searchTimeout = null

const showFormModal = ref(false)
const showViewModal = ref(false)
const showImportModal = ref(false)
const showDeleteModal = ref(false)
const selectedTeacher = ref(null)
const teacherToDelete = ref(null)
const isDeactivating = ref(false)

const hasActiveFilters = computed(() =>
  filters.search !== '' ||
  filters.status !== '' ||
  filters.department !== '' ||
  filters.sex !== ''
)

const fetchTeachers = async () => {
  try {
    const { data } = await axios.get('/admin/teachers/list', { params: filters })
    const p = data?.teachers
    teachers.value = p && Array.isArray(p.data) ? p : emptyPaginator()
    counts.value = data?.counts ?? counts.value
  } catch (e) {
    console.error('Failed to load teachers:', e)
    teachers.value = emptyPaginator()
  }
}

const debouncedFetch = () => {
  clearTimeout(searchTimeout)
  searchTimeout = setTimeout(() => {
    filters.page = 1
    fetchTeachers()
  }, 300)
}

const setStatusFilter = (status) => {
  filters.status = status
  filters.page = 1
  fetchTeachers()
}

const setSort = (field) => {
  if (filters.sort_by === field) {
    filters.sort_dir = filters.sort_dir === 'asc' ? 'desc' : 'asc'
  } else {
    filters.sort_by = field
    filters.sort_dir = 'asc'
  }
  filters.page = 1
  fetchTeachers()
}

const changePage = (page) => {
  if (page < 1 || page > teachers.value.last_page) return
  filters.page = page
  fetchTeachers()
}

const changePageSize = () => {
  filters.page = 1
  fetchTeachers()
}

const clearFilters = () => {
  filters.search = ''
  filters.status = ''
  filters.department = ''
  filters.sex = ''
  filters.page = 1
  fetchTeachers()
}

/* SortIcon inline component */
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
  if (!name) return 'T'
  return name.split(' ').map(n => n[0]).join('').substring(0, 2).toUpperCase()
}

/* Modals */
const openCreateModal = () => {
  selectedTeacher.value = null
  showFormModal.value = true
}

const openEditModal = async (teacher) => {
  try {
    const { data } = await axios.get(`/admin/teachers/${teacher.id}`)
    selectedTeacher.value = data.teacher
    showFormModal.value = true
  } catch (e) {
    console.error(e)
    alert('Failed to load faculty details. Please try again.')
  }
}

const openViewModal = (teacher) => {
  selectedTeacher.value = teacher
  showViewModal.value = true
}

const fromViewToEdit = (teacher) => {
  showViewModal.value = false
  setTimeout(() => openEditModal(teacher), 220)
}

const openDeleteConfirmation = (teacher) => {
  teacherToDelete.value = teacher
  showDeleteModal.value = true
}

const executeDeactivation = () => {
  if (!teacherToDelete.value) return
  isDeactivating.value = true
  router.delete(route('admin.teachers.destroy', teacherToDelete.value.id), {
    onFinish: () => {
      isDeactivating.value = false
      showDeleteModal.value = false
      teacherToDelete.value = null
      fetchTeachers()
    },
  })
}

/* Export */
const exportTeachers = () => {
  const params = new URLSearchParams()
  if (filters.status) params.set('status', filters.status)
  if (filters.department) params.set('department', filters.department)
  if (filters.sex) params.set('sex', filters.sex)
  if (filters.search) params.set('search', filters.search)

  const qs = params.toString()
  window.location.href = `/admin/teachers/export${qs ? '?' + qs : ''}`
}

onMounted(() => {
  fetchTeachers()
})
</script>