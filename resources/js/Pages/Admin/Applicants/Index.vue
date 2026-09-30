<template>
  <AdminLayout>
    <div class="space-y-6 max-w-7xl mx-auto pb-10 font-['Inter']">

      <!-- Hero Header Banner -->
      <div class="relative overflow-hidden bg-[#004d08] text-white rounded-2xl p-6 md:p-8 shadow-lg border border-emerald-900/40">
        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-4">
          <div>
            <div class="flex items-center gap-2 mb-2">
              <span class="bg-amber-400/20 text-amber-300 text-xs font-medium px-2.5 py-0.5 rounded-md border border-amber-400/30">
                STUDENT MANAGEMENT
              </span>
              <span class="text-emerald-200 text-xs font-normal">&bull; Admissions &amp; Applications</span>
            </div>
            <h1 class="font-['Anton'] text-3xl md:text-4xl tracking-wide uppercase">
              Admissions <span class="text-amber-400">Pipeline</span>
            </h1>
            <p class="text-emerald-100/80 text-sm mt-1 max-w-xl font-normal">
              Review incoming enrollment requests, verify applicant records, and process student admissions.
            </p>
          </div>

          <!-- Right side of the hero banner, replace the single button -->
          <div class="flex items-center gap-2 shrink-0">
            <button
              @click="showImportModal = true"
              class="inline-flex items-center justify-center gap-2 bg-emerald-900/60 hover:bg-emerald-900 text-emerald-50 border border-emerald-700/50 font-medium px-4 py-3 rounded-xl transition-all active:scale-95 text-sm cursor-pointer"
              title="Import applicants from CSV"
            >
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1M12 12V4m0 0L8 8m4-4l4 4" />
              </svg>
              <span class="hidden sm:inline">Import</span>
            </button>

            <button
              @click="exportApplicants"
              class="inline-flex items-center justify-center gap-2 bg-emerald-900/60 hover:bg-emerald-900 text-emerald-50 border border-emerald-700/50 font-medium px-4 py-3 rounded-xl transition-all active:scale-95 text-sm cursor-pointer"
              title="Export filtered applicants to CSV"
            >
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1M7 10l5 5m0 0l5-5m-5 5V3" />
              </svg>
              <span class="hidden sm:inline">Export</span>
            </button>

            <button
              @click="openCreateModal"
              class="inline-flex items-center justify-center gap-2 bg-amber-400 hover:bg-amber-300 text-[#004d08] font-medium px-5 py-3 rounded-xl transition-all shadow-md active:scale-95 text-sm cursor-pointer"
            >
              <span class="text-lg leading-none">+</span>
              <span>New Application</span>
            </button>
          </div>
        </div>

        <div class="absolute -right-6 -bottom-8 opacity-10 text-9xl font-['Anton'] pointer-events-none select-none text-white">
          APPLY
        </div>
      </div>

      <!-- Telemetry Stat Cards -->
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">

        <!-- Total -->
        <button
          @click="setStatusFilter('')"
          :class="[
            'text-left p-5 rounded-2xl border shadow-xs flex items-center justify-between transition-colors cursor-pointer',
            filters.status === ''
              ? 'bg-[#004d08]/5 dark:bg-[#86EFAC]/5 border-[#004d08]/40 dark:border-[#86EFAC]/30'
              : 'bg-white dark:bg-[#2D3A31] border-gray-200/80 dark:border-[#3F4F43] hover:border-gray-300 dark:hover:border-gray-500'
          ]"
        >
          <div class="space-y-1">
            <p class="text-[10px] font-medium uppercase text-gray-400 tracking-wider">Total Applications</p>
            <p class="text-2xl font-medium text-gray-900 dark:text-white">{{ totalApplicationsCount }}</p>
            <span class="text-[10px] text-gray-500 dark:text-gray-400 font-normal">All Records</span>
          </div>
          <div class="w-12 h-12 rounded-2xl bg-gray-50 dark:bg-[#1C261E] border border-gray-100 dark:border-[#3F4F43] flex items-center justify-center text-gray-600 dark:text-gray-300 shrink-0">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
            </svg>
          </div>
        </button>

        <!-- Pending -->
        <button
          @click="setStatusFilter('pending')"
          :class="[
            'text-left p-5 rounded-2xl border shadow-xs flex items-center justify-between transition-colors cursor-pointer',
            filters.status === 'pending'
              ? 'bg-amber-50/60 dark:bg-amber-950/20 border-amber-300 dark:border-amber-800'
              : 'bg-white dark:bg-[#2D3A31] border-gray-200/80 dark:border-[#3F4F43] hover:border-gray-300 dark:hover:border-gray-500'
          ]"
        >
          <div class="space-y-1">
            <p class="text-[10px] font-medium uppercase text-amber-500 tracking-wider">Pending</p>
            <p class="text-2xl font-medium text-gray-900 dark:text-white">{{ counts.pending || 0 }}</p>
            <span class="text-[10px] text-amber-600 dark:text-amber-400 font-normal">Awaiting Action</span>
          </div>
          <div class="w-12 h-12 rounded-2xl bg-amber-50 dark:bg-[#1C261E] border border-amber-100 dark:border-[#3F4F43] flex items-center justify-center text-amber-600 dark:text-amber-400 shrink-0">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </div>
        </button>

        <!-- Under Review -->
        <button
          @click="setStatusFilter('under_review')"
          :class="[
            'text-left p-5 rounded-2xl border shadow-xs flex items-center justify-between transition-colors cursor-pointer',
            filters.status === 'under_review'
              ? 'bg-blue-50/60 dark:bg-blue-950/20 border-blue-300 dark:border-blue-800'
              : 'bg-white dark:bg-[#2D3A31] border-gray-200/80 dark:border-[#3F4F43] hover:border-gray-300 dark:hover:border-gray-500'
          ]"
        >
          <div class="space-y-1">
            <p class="text-[10px] font-medium uppercase text-blue-500 tracking-wider">Under Review</p>
            <p class="text-2xl font-medium text-gray-900 dark:text-white">{{ counts.under_review || 0 }}</p>
            <span class="text-[10px] text-blue-600 dark:text-blue-400 font-normal">Being Evaluated</span>
          </div>
          <div class="w-12 h-12 rounded-2xl bg-blue-50 dark:bg-[#1C261E] border border-blue-100 dark:border-[#3F4F43] flex items-center justify-center text-blue-600 dark:text-blue-400 shrink-0">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
              <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
            </svg>
          </div>
        </button>

        <!-- Approved -->
        <button
          @click="setStatusFilter('approved')"
          :class="[
            'text-left p-5 rounded-2xl border shadow-xs flex items-center justify-between transition-colors cursor-pointer',
            filters.status === 'approved'
              ? 'bg-sky-50/60 dark:bg-sky-950/20 border-sky-300 dark:border-sky-800'
              : 'bg-white dark:bg-[#2D3A31] border-gray-200/80 dark:border-[#3F4F43] hover:border-gray-300 dark:hover:border-gray-500'
          ]"
        >
          <div class="space-y-1">
            <p class="text-[10px] font-medium uppercase text-sky-500 tracking-wider">Approved</p>
            <p class="text-2xl font-medium text-gray-900 dark:text-white">{{ counts.approved || 0 }}</p>
            <span class="text-[10px] text-sky-600 dark:text-sky-400 font-normal">Scheduled for Exam</span>
          </div>
          <div class="w-12 h-12 rounded-2xl bg-sky-50 dark:bg-[#1C261E] border border-sky-100 dark:border-[#3F4F43] flex items-center justify-center text-sky-600 dark:text-sky-400 shrink-0">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </div>
        </button>

        <!-- Enrolled -->
        <button
          @click="setStatusFilter('enrolled')"
          :class="[
            'text-left p-5 rounded-2xl border shadow-xs flex items-center justify-between transition-colors cursor-pointer',
            filters.status === 'enrolled'
              ? 'bg-emerald-50/60 dark:bg-emerald-950/20 border-emerald-300 dark:border-emerald-800'
              : 'bg-white dark:bg-[#2D3A31] border-gray-200/80 dark:border-[#3F4F43] hover:border-gray-300 dark:hover:border-gray-500'
          ]"
        >
          <div class="space-y-1">
            <p class="text-[10px] font-medium uppercase text-emerald-500 tracking-wider">Enrolled</p>
            <p class="text-2xl font-medium text-gray-900 dark:text-white">{{ counts.enrolled || 0 }}</p>
            <span class="text-[10px] text-emerald-600 dark:text-emerald-400 font-normal">Successfully Admitted</span>
          </div>
          <div class="w-12 h-12 rounded-2xl bg-emerald-50 dark:bg-[#1C261E] border border-emerald-100 dark:border-[#3F4F43] flex items-center justify-center text-emerald-600 dark:text-emerald-400 shrink-0">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l9-5-9-5-9 5 9 5z" />
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0112 20.055a11.952 11.952 0 01-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
            </svg>
          </div>
        </button>

      </div>

      <!-- Main Directory Card -->
      <div class="bg-white dark:bg-[#2D3A31] rounded-2xl border border-gray-200/80 dark:border-[#3F4F43] shadow-xs overflow-hidden transition-colors">

        <!-- Controls Header -->
        <div class="p-4 sm:p-5 border-b border-gray-100 dark:border-[#3F4F43] bg-gray-50/50 dark:bg-[#232D26]/50 flex flex-col lg:flex-row gap-3 justify-between lg:items-center">
          <div>
            <h2 class="text-xs font-medium text-gray-900 dark:text-white uppercase tracking-wider">Applications Roster</h2>
            <p class="text-[11px] text-gray-500 dark:text-gray-400 font-normal">Search, filter, and process applicant records</p>
          </div>

          <div class="flex flex-wrap sm:flex-nowrap items-center gap-2 w-full lg:w-auto lg:justify-end">

            <!-- Cluster Filter (was "Strand") -->
            <div class="relative">
              <select
                v-model="filters.strand_id"
                @change="fetchApplications()"
                class="appearance-none pl-3 pr-8 py-2 text-xs bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white rounded-xl focus:ring-2 focus:ring-[#004d08] focus:outline-none font-normal cursor-pointer max-w-[180px] truncate"
                title="Filter by cluster"
              >
                <option value="">All Clusters</option>
                <option v-for="strand in strands" :key="strand.id" :value="strand.id" :title="strand.name">
                  {{ strand.code || strand.name }}
                </option>
              </select>
            </div>

            <!-- Grade Level Filter (new) -->
            <div class="relative">
              <select
                v-model="filters.grade_level"
                @change="fetchApplications()"
                class="appearance-none pl-3 pr-8 py-2 text-xs bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white rounded-xl focus:ring-2 focus:ring-[#004d08] focus:outline-none font-normal cursor-pointer"
              >
                <option value="">All Grades</option>
                <option :value="11">Grade 11</option>
                <option :value="12">Grade 12</option>
              </select>
            </div>

            <!-- School Year Filter -->
            <div class="relative">
              <select
                v-model="filters.school_year_id"
                @change="fetchApplications()"
                class="appearance-none pl-3 pr-8 py-2 text-xs bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white rounded-xl focus:ring-2 focus:ring-[#004d08] focus:outline-none font-normal cursor-pointer"
              >
                <option value="">All School Years</option>
                <option v-for="sy in schoolYears" :key="sy.id" :value="sy.id">
                  {{ sy.label || sy.name }}
                </option>
              </select>
            </div>

            <!-- Search Input (unchanged) -->
            <div class="relative w-full sm:w-64">
              <svg class="w-4 h-4 absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
              </svg>
              <input
                v-model="filters.search"
                @input="debouncedFetch"
                type="text"
                placeholder="Search reference, name, LRN..."
                class="w-full pl-10 pr-8 py-2 text-xs bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white placeholder-gray-400 rounded-xl focus:ring-2 focus:ring-[#004d08] dark:focus:ring-[#86EFAC] focus:outline-none font-normal"
              />
              <button
                v-if="filters.search"
                @click="filters.search = ''; fetchApplications()"
                class="absolute right-2.5 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 cursor-pointer"
              >
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
              </button>
            </div>

            <!-- Reset Filters -->
            <button
              v-if="hasActiveFilters"
              @click="clearFilters"
              class="px-3 py-2 text-xs font-normal text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-white/5 rounded-xl border border-gray-200 dark:border-[#3F4F43] transition-colors cursor-pointer shrink-0"
            >
              Reset
            </button>

          </div>
        </div>

        <!-- Directory Table -->
        <div class="overflow-x-auto">
          <table class="w-full text-left border-collapse">
            <thead>
              <tr class="border-b border-gray-100 dark:border-[#3F4F43] bg-gray-50/50 dark:bg-[#232D26]/50 text-[10px] font-medium uppercase text-gray-400 tracking-wider">
                <th class="py-3.5 px-5">
                  <button @click="setSort('full_name')" class="inline-flex items-center gap-1 hover:text-gray-700 dark:hover:text-gray-200 transition-colors cursor-pointer">
                    Applicant Student
                    <SortIcon field="full_name" />
                  </button>
                </th>
                <th class="py-3.5 px-5">
                  <button @click="setSort('desired_grade_level')" class="inline-flex items-center gap-1 hover:text-gray-700 dark:hover:text-gray-200 transition-colors cursor-pointer">
                    Academic Intent
                    <SortIcon field="desired_grade_level" />
                  </button>
                </th>
                <th class="py-3.5 px-5">
                  <button @click="setSort('email')" class="inline-flex items-center gap-1 hover:text-gray-700 dark:hover:text-gray-200 transition-colors cursor-pointer">
                    Contact &amp; LRN
                    <SortIcon field="email" />
                  </button>
                </th>
                <th class="py-3.5 px-5 text-center">
                  <button @click="setSort('status')" class="inline-flex items-center gap-1 hover:text-gray-700 dark:hover:text-gray-200 transition-colors cursor-pointer">
                    Status
                    <SortIcon field="status" />
                  </button>
                </th>
                <th class="py-3.5 px-5 text-right">Actions</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-[#3F4F43] text-xs font-normal text-gray-800 dark:text-gray-200">

              <tr
                v-for="item in applications.data"
                :key="item.id"
                class="hover:bg-gray-50/80 dark:hover:bg-white/5 transition-colors"
              >
                <!-- Applicant Name & Reference -->
                <td class="py-4 px-5">
                  <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-emerald-100 dark:bg-[#1C261E] text-[#004d08] dark:text-[#86EFAC] border border-emerald-200 dark:border-[#3F4F43] flex items-center justify-center font-medium text-xs shrink-0">
                      {{ getInitials(item.full_name) }}
                    </div>
                    <div>
                      <span class="font-medium text-gray-900 dark:text-white block">
                        {{ item.full_name }}
                      </span>
                      <span class="text-[10px] font-mono text-gray-400 block">{{ item.reference_number }}</span>
                    </div>
                  </div>
                </td>

                <!-- Academic Intent -->
                <td class="py-4 px-5">
                  <div>
                    <span class="text-xs text-gray-900 dark:text-white font-medium block">
                      Grade {{ item.desired_grade_level }}
                    </span>
                    <span class="text-[11px] text-gray-500 dark:text-gray-400 font-normal block">
                      {{ item.strand || 'General Academic' }}
                      <span class="capitalize">&bull; {{ item.applicant_type || 'New' }}</span>
                    </span>
                  </div>
                </td>

                <!-- Contact & LRN -->
                <td class="py-4 px-5">
                  <div>
                    <span class="text-xs text-gray-800 dark:text-gray-200 font-normal block">
                      {{ item.email }}
                    </span>
                    <span class="text-[11px] text-gray-500 dark:text-gray-400 font-normal font-mono block">
                      {{ item.lrn ? `LRN: ${item.lrn}` : 'No LRN Provided' }}
                    </span>
                  </div>
                </td>

                <!-- Status Badge -->
                <td class="py-4 px-5 text-center">
                  <span
                    class="px-2.5 py-1 rounded-full text-[10px] font-normal uppercase tracking-wider inline-flex items-center gap-1.5 border"
                    :class="getStatusBadgeClass(item.status)"
                  >
                    <span class="w-1.5 h-1.5 rounded-full" :class="getStatusDotClass(item.status)"></span>
                    {{ item.status ? item.status.replace('_', ' ') : 'Pending' }}
                  </span>
                </td>

                <!-- Actions -->
                <td class="py-4 px-5 text-right">
                  <div class="flex items-center justify-end gap-1.5">
                    <button
                      @click="openViewModal(item)"
                      :disabled="isLockedByOther(item)"
                      :title="isLockedByOther(item) ? `Under review by ${item.reviewer_name || 'another admin'}` : 'Review Application'"
                      class="px-2.5 py-1 text-[10px] font-normal text-sky-700 dark:text-sky-300 bg-sky-50 hover:bg-sky-100 dark:bg-sky-950/50 rounded-lg border border-sky-200 dark:border-sky-800 transition-colors cursor-pointer disabled:opacity-40 disabled:cursor-not-allowed"
                    >
                      View
                    </button>

                    <button
                      @click="openEditModal(item)"
                      :disabled="isLockedByOther(item)"
                      :title="isLockedByOther(item) ? `Under review by ${item.reviewer_name || 'another admin'}` : ''"
                      class="px-2.5 py-1 text-[10px] font-normal text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-[#232D26] hover:bg-gray-200 dark:hover:bg-white/10 rounded-lg transition-colors cursor-pointer disabled:opacity-40 disabled:cursor-not-allowed"
                    >
                      Edit
                    </button>
                  </div>
                </td>
              </tr>

              <!-- Empty State -->
              <tr v-if="!applications.data || applications.data.length === 0">
                <td colspan="5" class="py-12 text-center text-gray-400 dark:text-gray-500 font-normal">
                  <p class="text-xs">No applications found.</p>
                  <p class="text-[11px] mt-0.5">Try adjusting your filters or search keywords.</p>
                </td>
              </tr>

            </tbody>
          </table>
        </div>

        <!-- Pagination Bar (row-count moved here, matching Students page) -->
        <div v-if="applications.total > 0" class="px-5 py-3.5 bg-gray-50/50 dark:bg-[#232D26]/50 border-t border-gray-100 dark:border-[#3F4F43] flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-gray-500 dark:text-gray-400">
          <div class="flex items-center gap-3">
            <span>
              Showing <span class="font-semibold text-gray-900 dark:text-white">{{ applications.from || 0 }}</span>
              to <span class="font-semibold text-gray-900 dark:text-white">{{ applications.to || 0 }}</span>
              of <span class="font-semibold text-gray-900 dark:text-white">{{ applications.total }}</span> entries
            </span>
            <div class="relative">
              <select
                v-model.number="filters.per_page"
                @change="changePageSize"
                class="appearance-none pl-2 pr-7 py-1 text-[11px] bg-white dark:bg-[#2D3A31] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white rounded-lg focus:ring-2 focus:ring-[#004d08] focus:outline-none cursor-pointer"
              >
                <option :value="10">10</option>
                <option :value="20">20</option>
                <option :value="50">50</option>
                <option :value="100">100</option>
              </select>
            </div>
          </div>

          <div class="flex items-center gap-1">
            <button
              @click="changePage(applications.current_page - 1)"
              :disabled="applications.current_page <= 1"
              class="px-3 py-1.5 rounded-lg border border-gray-200 dark:border-[#3F4F43] bg-white dark:bg-[#2D3A31] text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-white/5 disabled:opacity-40 cursor-pointer disabled:cursor-not-allowed transition-colors"
            >
              Previous
            </button>
            <span class="px-3 py-1.5 font-medium text-gray-900 dark:text-white">
              Page {{ applications.current_page }} of {{ applications.last_page }}
            </span>
            <button
              @click="changePage(applications.current_page + 1)"
              :disabled="applications.current_page >= applications.last_page"
              class="px-3 py-1.5 rounded-lg border border-gray-200 dark:border-[#3F4F43] bg-white dark:bg-[#2D3A31] text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-white/5 disabled:opacity-40 cursor-pointer disabled:cursor-not-allowed transition-colors"
            >
              Next
            </button>
          </div>
        </div>

      </div>

      <!-- Application Form Modal -->
      <ApplicationModal
        :show="showModal"
        :application="selectedApplication"
        :strands="strands"
        :school-years="schoolYears"
        @close="showModal = false"
        @saved="fetchApplications"
      />

      <!-- Import Modal -->
      <ImportApplicantsModal
        :show="showImportModal"
        @close="showImportModal = false"
        @imported="fetchApplications"
      />

      <!-- Review / View Application Modal -->
      <ViewApplicationModal
        :show="showViewModal"
        :application="selectedApplication"
        @close="showViewModal = false"
        @changed="fetchApplications"
      />

      <!-- Enroll Modal -->
      <EnrollModal
        :show="showEnrollModal"
        :application="selectedApplication"
        :sections="sections"
        @close="showEnrollModal = false"
        @enrolled="fetchApplications"
      />

    </div>
  </AdminLayout>
</template>

<script setup>
import { ref, reactive, computed, onMounted, h } from 'vue'
import axios from 'axios'
import { usePage } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import ApplicationModal from './ApplicationModal.vue'
import EnrollModal from './EnrollModal.vue'
import ViewApplicationModal from './ViewApplicationModal.vue'
import ImportApplicantsModal from './ImportApplicantsModal.vue'

const props = defineProps({
  strands: { type: Array, default: () => [] },
  schoolYears: { type: Array, default: () => [] },
  sections: { type: Array, default: () => [] },
})

const SortIcon = (props) => {
  const active = filters.sort_by === props.field
  const dir    = filters.sort_dir

  return h('svg', {
    class: [
      'w-3 h-3 transition-colors',
      active
        ? 'text-[#004d08] dark:text-[#86EFAC]'
        : 'text-gray-300 dark:text-gray-600',
    ],
    fill: 'none',
    stroke: 'currentColor',
    viewBox: '0 0 24 24',
    'stroke-width': 2,
  }, [
    h('path', {
      'stroke-linecap': 'round',
      'stroke-linejoin': 'round',
      d: 'M5 15l7-7 7 7',
      opacity: active && dir === 'asc' ? 1 : 0,
    }),
    h('path', {
      'stroke-linecap': 'round',
      'stroke-linejoin': 'round',
      d: 'M19 9l-7 7-7-7',
      opacity: active && dir === 'desc' ? 1 : 0,
    }),
  ])
}

SortIcon.props = {
  field: { type: String, required: true },
}

// ─── Current user (for lock checks) ────────────────────────────
const page = usePage()
const currentUserId = computed(() => page.props.auth?.user?.id ?? null)

// ─── List state ────────────────────────────────────────────────
const emptyPaginator = () => ({
  data: [], current_page: 1, last_page: 1, total: 0, from: 0, to: 0,
})

const applications = ref(emptyPaginator())
const counts = ref({})
const showModal = ref(false)
const showEnrollModal = ref(false)
const showViewModal = ref(false)
const selectedApplication = ref(null)

const filters = reactive({
  search: '',
  status: '',
  strand_id: '',
  grade_level: '',
  school_year_id: '',
  per_page: 10,
  page: 1,
  sort_by: 'submitted_at',   // ← NEW
  sort_dir: 'desc',
})

const setSort = (field) => {
  if (filters.sort_by === field) {
    // Same column → toggle direction
    filters.sort_dir = filters.sort_dir === 'asc' ? 'desc' : 'asc'
  } else {
    // New column → default to ascending (except submitted_at, which reads better desc)
    filters.sort_by = field
    filters.sort_dir = field === 'submitted_at' ? 'desc' : 'asc'
  }
  filters.page = 1
  fetchApplications()
}

let searchTimeout = null

const hasActiveFilters = computed(() => {
  return filters.search !== ''
    || filters.status !== ''
    || filters.strand_id !== ''
    || filters.grade_level !== ''
    || filters.school_year_id !== ''
    || filters.per_page !== 20
})

const totalApplicationsCount = computed(() => {
  if (!counts.value) return 0
  return (counts.value.pending || 0) +
         (counts.value.under_review || 0) +
         (counts.value.approved || 0) +
         (counts.value.enrolled || 0) +
         (counts.value.needs_resubmission || 0)
})

// ─── Lock helper — true if under_review by a *different* admin ──
const isLockedByOther = (item) => {
  if (!item) return false
  if (item.status !== 'under_review') return false
  const rb = item.reviewed_by_id
  return !!rb && rb !== currentUserId.value
}

// ─── Fetch ─────────────────────────────────────────────────────
const fetchApplications = async () => {
  try {
    const response = await axios.get('/admin/applicants/list', { params: filters })

    const paginator = response.data?.applicants
    applications.value = paginator && Array.isArray(paginator.data)
      ? paginator
      : emptyPaginator()

    counts.value = response.data?.counts ?? {}
  } catch (error) {
    console.error('Failed to load applications:', error)
    applications.value = emptyPaginator()
    counts.value = {}
  }
}

// ─── Utilities ─────────────────────────────────────────────────
const getInitials = (name) => {
  if (!name) return '?'
  const parts = name.trim().split(' ')
  if (parts.length === 1) return parts[0].charAt(0).toUpperCase()
  return (parts[0].charAt(0) + parts[parts.length - 1].charAt(0)).toUpperCase()
}

const getStatusBadgeClass = (status) => {
  switch (status) {
    case 'enrolled':
      return 'bg-emerald-100 dark:bg-emerald-950/60 text-emerald-800 dark:text-emerald-300 border-emerald-200 dark:border-emerald-800'
    case 'approved':
      return 'bg-sky-50 dark:bg-sky-950/50 text-sky-700 dark:text-sky-300 border-sky-200 dark:border-sky-800'
    case 'under_review':
      return 'bg-blue-50 dark:bg-blue-950/50 text-blue-700 dark:text-blue-300 border-blue-200 dark:border-blue-800'
    case 'needs_resubmission':
      return 'bg-amber-50 dark:bg-amber-950/50 text-amber-700 dark:text-amber-300 border-amber-200 dark:border-amber-800'
    case 'rejected':
      return 'bg-red-50 dark:bg-red-950/50 text-red-700 dark:text-red-300 border-red-200 dark:border-red-800'
    default:
      return 'bg-gray-100 dark:bg-[#232D26] text-gray-600 dark:text-gray-400 border-gray-200 dark:border-[#3F4F43]'
  }
}

const getStatusDotClass = (status) => {
  switch (status) {
    case 'enrolled': return 'bg-emerald-500'
    case 'approved': return 'bg-sky-500'
    case 'under_review': return 'bg-blue-500'
    case 'needs_resubmission': return 'bg-amber-500'
    case 'rejected': return 'bg-red-500'
    default: return 'bg-gray-400'
  }
}

// ─── Filter / Pagination actions ───────────────────────────────
const clearFilters = () => {
  filters.search = ''
  filters.status = ''
  filters.strand_id = ''
  filters.grade_level = ''
  filters.school_year_id = ''
  filters.per_page = 20
  filters.page = 1
  fetchApplications()
}

const debouncedFetch = () => {
  clearTimeout(searchTimeout)
  searchTimeout = setTimeout(() => {
    filters.page = 1
    fetchApplications()
  }, 300)
}

const setStatusFilter = (status) => {
  filters.status = status
  filters.page = 1
  fetchApplications()
}

const changePage = (page) => {
  if (page < 1 || page > applications.value.last_page) return
  filters.page = page
  fetchApplications()
}

const changePageSize = () => {
  filters.page = 1
  fetchApplications()
}

// ─── Modals ────────────────────────────────────────────────────
const openCreateModal = () => {
  selectedApplication.value = null
  showModal.value = true
}

const openEditModal = async (item) => {
  try {
    const { data } = await axios.get(`/admin/applicants/${item.id}`)
    selectedApplication.value = {
      ...data.applicant,
      contacts:  data.contacts  || [],
      documents: data.documents || [],
    }
    showModal.value = true
  } catch (error) {
    console.error('Failed to load applicant details:', error)
    alert('Failed to load applicant details. Please try again.')
  }
}

const openEnrollModal = (item) => {
  selectedApplication.value = item
  showEnrollModal.value = true
}

const openViewModal = (item) => {
  selectedApplication.value = item
  showViewModal.value = true
}

onMounted(() => {
  fetchApplications()
})

const showImportModal = ref(false)

const exportApplicants = () => {
  // Build query string from current filters
  const params = new URLSearchParams()

  if (filters.status)         params.set('status', filters.status)
  if (filters.strand_id)      params.set('strand_id', filters.strand_id)
  if (filters.grade_level)    params.set('grade_level', filters.grade_level)
  if (filters.school_year_id) params.set('school_year_id', filters.school_year_id)
  if (filters.search)         params.set('search', filters.search)

  const qs = params.toString()
  const url = `/admin/applicants/export${qs ? '?' + qs : ''}`

  // Browser handles Content-Disposition: attachment → downloads file
  window.location.href = url
}
</script>