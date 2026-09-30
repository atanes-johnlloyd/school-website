<template>
  <AdminLayout>
    <div class="space-y-6 max-w-7xl mx-auto pb-10 font-['Inter']">

      <!-- Hero Banner -->
      <div class="relative overflow-hidden bg-[#004d08] text-white rounded-2xl p-6 md:p-8 shadow-lg border border-emerald-900/40">
        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-4">
          <div>
            <div class="flex items-center gap-2 mb-2">
              <span class="bg-amber-400/20 text-amber-300 text-xs font-medium px-2.5 py-0.5 rounded-md border border-amber-400/30">
                STUDENT MANAGEMENT
              </span>
              <span class="text-emerald-200 text-xs font-normal">&bull; Student Directory</span>
            </div>
            <h1 class="font-['Anton'] text-3xl md:text-4xl tracking-wide uppercase">
              Student <span class="text-amber-400">Directory</span>
            </h1>
            <p class="text-emerald-100/80 text-sm mt-1 max-w-xl font-normal">
              Manage enrollments, LRN identifiers, section placements, and guardian records.
            </p>
          </div>

          <div class="flex items-center gap-2 shrink-0">
            <button
              @click="showImportModal = true"
              class="inline-flex items-center gap-1.5 bg-emerald-900/60 hover:bg-emerald-900 text-emerald-50 border border-emerald-700/50 font-medium px-4 py-3 rounded-xl transition-all active:scale-95 text-sm cursor-pointer"
              title="Import students from CSV"
            >
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1M12 12V4m0 0L8 8m4-4l4 4" />
              </svg>
              <span class="hidden sm:inline">Import</span>
            </button>

            <button
              @click="exportStudents"
              class="inline-flex items-center gap-1.5 bg-emerald-900/60 hover:bg-emerald-900 text-emerald-50 border border-emerald-700/50 font-medium px-4 py-3 rounded-xl transition-all active:scale-95 text-sm cursor-pointer"
              title="Export filtered students"
            >
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1M7 10l5 5m0 0l5-5m-5 5V3" />
              </svg>
              <span class="hidden sm:inline">Export</span>
            </button>

            <button
              @click="openCreateModal"
              class="inline-flex items-center gap-2 bg-amber-400 hover:bg-amber-300 text-[#004d08] font-medium px-5 py-3 rounded-xl transition-all shadow-md active:scale-95 text-sm cursor-pointer"
            >
              <span class="text-lg leading-none">+</span>
              <span>Register New Student</span>
            </button>
          </div>
        </div>

        <div class="absolute -right-6 -bottom-8 opacity-10 text-9xl font-['Anton'] pointer-events-none select-none text-white">
          LEARN
        </div>
      </div>

      <!-- Stat Cards -->
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
        <button
          @click="setStatusFilter('')"
          :class="cardClass(filters.status === '', 'emerald')"
        >
          <div class="space-y-1">
            <p class="text-[10px] font-medium uppercase text-gray-400 tracking-wider">Total Students</p>
            <p class="text-2xl font-medium text-gray-900 dark:text-white">{{ counts.total || 0 }}</p>
            <span class="text-[10px] text-gray-500 dark:text-gray-400 font-normal">All Records</span>
          </div>
          <div class="w-12 h-12 rounded-2xl bg-emerald-50 dark:bg-[#1C261E] border border-emerald-100 dark:border-[#3F4F43] flex items-center justify-center text-emerald-700 dark:text-[#86EFAC] shrink-0">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
          </div>
        </button>

        <button @click="setStatusFilter('active')"
                :class="cardClass(filters.status === 'active', 'emerald')">
          <div class="space-y-1">
            <p class="text-[10px] font-medium uppercase text-emerald-500 tracking-wider">Active</p>
            <p class="text-2xl font-medium text-gray-900 dark:text-white">{{ counts.active || 0 }}</p>
            <span class="text-[10px] text-emerald-600 dark:text-emerald-400 font-normal">Currently Enrolled</span>
          </div>
          <div class="w-12 h-12 rounded-2xl bg-emerald-50 dark:bg-[#1C261E] border border-emerald-100 dark:border-[#3F4F43] flex items-center justify-center text-emerald-600 dark:text-emerald-400 shrink-0">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </div>
        </button>

        <button @click="setStatusFilter('graduated')"
                :class="cardClass(filters.status === 'graduated', 'sky')">
          <div class="space-y-1">
            <p class="text-[10px] font-medium uppercase text-sky-500 tracking-wider">Graduated</p>
            <p class="text-2xl font-medium text-gray-900 dark:text-white">{{ counts.graduated || 0 }}</p>
            <span class="text-[10px] text-sky-600 dark:text-sky-400 font-normal">Completed Program</span>
          </div>
          <div class="w-12 h-12 rounded-2xl bg-sky-50 dark:bg-[#1C261E] border border-sky-100 dark:border-[#3F4F43] flex items-center justify-center text-sky-600 dark:text-sky-400 shrink-0">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l9-5-9-5-9 5 9 5z" />
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0112 20.055a11.952 11.952 0 01-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
            </svg>
          </div>
        </button>

        <button @click="setStatusFilter('dropped_out')"
                :class="cardClass(filters.status === 'dropped_out', 'red')">
          <div class="space-y-1">
            <p class="text-[10px] font-medium uppercase text-red-500 tracking-wider">Dropped Out</p>
            <p class="text-2xl font-medium text-gray-900 dark:text-white">{{ counts.dropped_out || 0 }}</p>
            <span class="text-[10px] text-red-600 dark:text-red-400 font-normal">Ceased Attendance</span>
          </div>
          <div class="w-12 h-12 rounded-2xl bg-red-50 dark:bg-[#1C261E] border border-red-100 dark:border-[#3F4F43] flex items-center justify-center text-red-600 dark:text-red-400 shrink-0">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
            </svg>
          </div>
        </button>

        <button @click="setStatusFilter('transferred_out')"
                :class="cardClass(filters.status === 'transferred_out', 'amber')">
          <div class="space-y-1">
            <p class="text-[10px] font-medium uppercase text-amber-500 tracking-wider">Transferred</p>
            <p class="text-2xl font-medium text-gray-900 dark:text-white">{{ counts.transferred_out || 0 }}</p>
            <span class="text-[10px] text-amber-600 dark:text-amber-400 font-normal">Moved Schools</span>
          </div>
          <div class="w-12 h-12 rounded-2xl bg-amber-50 dark:bg-[#1C261E] border border-amber-100 dark:border-[#3F4F43] flex items-center justify-center text-amber-600 dark:text-amber-400 shrink-0">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
            </svg>
          </div>
        </button>
      </div>

      <!-- Main Card -->
      <div class="bg-white dark:bg-[#2D3A31] rounded-2xl border border-gray-200/80 dark:border-[#3F4F43] shadow-xs overflow-hidden transition-colors">

        <!-- Controls -->
        <div class="p-4 sm:p-5 border-b border-gray-100 dark:border-[#3F4F43] bg-gray-50/50 dark:bg-[#232D26]/50 flex flex-col lg:flex-row gap-3 justify-between lg:items-center">
          <div>
            <h2 class="text-xs font-medium text-gray-900 dark:text-white uppercase tracking-wider">Students Roster</h2>
            <p class="text-[11px] text-gray-500 dark:text-gray-400 font-normal">Search, filter, and manage student profiles</p>
          </div>

          <div class="flex flex-wrap sm:flex-nowrap items-center gap-2 w-full lg:w-auto lg:justify-end">

            <!-- Grade Filter -->
            <div class="relative">
              <select v-model="filters.grade_level" @change="fetchStudents()"
                      class="appearance-none pl-3 pr-8 py-2 text-xs bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white rounded-xl focus:ring-2 focus:ring-[#004d08] focus:outline-none font-normal cursor-pointer">
                <option value="">All Grades</option>
                <option v-for="g in [7,8,9,10,11,12]" :key="g" :value="g">Grade {{ g }}</option>
              </select>
            </div>

            <!-- Section Filter -->
            <div class="relative">
              <select
                v-model="filters.section_id"
                @change="fetchStudents()"
                class="appearance-none pl-3 pr-8 py-2 text-xs bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white rounded-xl focus:ring-2 focus:ring-[#004d08] focus:outline-none font-normal cursor-pointer max-w-[220px] truncate"
              >
                <option value="">All Sections</option>
                <option v-for="s in sections" :key="s.id" :value="s.id">
                  Grade {{ s.grade_level }} — {{ s.name }}
                </option>
              </select>
            </div>

            <!-- Search -->
            <div class="relative w-full sm:w-64">
              <svg class="w-4 h-4 absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
              </svg>
              <input v-model="filters.search" @input="debouncedFetch" type="text"
                     placeholder="Search name, LRN, section..."
                     class="w-full pl-10 pr-8 py-2 text-xs bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white placeholder-gray-400 rounded-xl focus:ring-2 focus:ring-[#004d08] dark:focus:ring-[#86EFAC] focus:outline-none font-normal" />
              <button v-if="filters.search" @click="filters.search = ''; fetchStudents()"
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
                  <button @click="setSort('lrn')" class="inline-flex items-center gap-1 hover:text-gray-700 dark:hover:text-gray-200 transition-colors cursor-pointer">
                    LRN / No.
                    <SortIcon field="lrn" />
                  </button>
                </th>
                <th class="py-3.5 px-5">
                  <button @click="setSort('name')" class="inline-flex items-center gap-1 hover:text-gray-700 dark:hover:text-gray-200 transition-colors cursor-pointer">
                    Student Name
                    <SortIcon field="name" />
                  </button>
                </th>
                <th class="py-3.5 px-5">
                  <button @click="setSort('grade_level')" class="inline-flex items-center gap-1 hover:text-gray-700 dark:hover:text-gray-200 transition-colors cursor-pointer">
                    Grade &amp; Section
                    <SortIcon field="grade_level" />
                  </button>
                </th>
                <th class="py-3.5 px-5">Guardian Info</th>
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
              <tr v-for="student in students.data" :key="student.id" class="hover:bg-gray-50/80 dark:hover:bg-white/5 transition-colors">
                <td class="py-4 px-5">
                  <span class="font-semibold text-gray-900 dark:text-white text-xs bg-gray-100 dark:bg-[#232D26] px-2.5 py-1 rounded-lg border border-gray-200 dark:border-[#3F4F43]">
                    {{ student.lrn }}
                  </span>
                </td>

                <td class="py-4 px-5">
                  <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-emerald-100 dark:bg-[#1C261E] text-[#004d08] dark:text-[#86EFAC] border border-emerald-200 dark:border-[#3F4F43] flex items-center justify-center font-medium text-xs shrink-0">
                      {{ getInitials(student.name) }}
                    </div>
                    <div>
                      <span class="font-medium text-gray-900 dark:text-white block">{{ student.name || 'N/A' }}</span>
                      <span class="text-[11px] text-gray-500 dark:text-gray-400 font-normal">{{ student.email }}</span>
                    </div>
                  </div>
                </td>

                <td class="py-4 px-5">
                  <div>
                    <span class="text-xs text-gray-900 dark:text-white font-medium block">
                      Grade {{ student.grade_level || 'N/A' }}
                    </span>
                    <span v-if="student.section" class="text-[11px] text-gray-500 dark:text-gray-400 font-normal">
                      Section: {{ student.section }}
                    </span>
                  </div>
                </td>

                <td class="py-4 px-5">
                  <div>
                    <span class="text-xs text-gray-800 dark:text-gray-200 font-normal block">
                      {{ student.guardian_name || 'Unspecified' }}
                    </span>
                    <span v-if="student.guardian_contact" class="text-[11px] text-gray-500 dark:text-gray-400 font-normal">
                      {{ student.guardian_contact }}
                    </span>
                  </div>
                </td>

                <td class="py-4 px-5 text-center">
                  <span class="px-2.5 py-1 rounded-full text-[10px] font-normal uppercase tracking-wider inline-flex items-center gap-1.5 border"
                        :class="getStatusBadgeClass(student.status)">
                    <span class="w-1.5 h-1.5 rounded-full" :class="getStatusDotClass(student.status)"></span>
                    {{ formatStatus(student.status) }}
                  </span>
                </td>

                <td class="py-4 px-5 text-right">
                  <div class="flex items-center justify-end gap-1.5">
                    <button @click="openViewModal(student)"
                            class="px-2.5 py-1 text-[10px] font-normal text-sky-700 dark:text-sky-300 bg-sky-50 hover:bg-sky-100 dark:bg-sky-950/50 rounded-lg border border-sky-200 dark:border-sky-800 transition-colors cursor-pointer">
                      View
                    </button>
                    <button @click="openEditModal(student)"
                            class="px-2.5 py-1 text-[10px] font-normal text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-[#232D26] hover:bg-gray-200 dark:hover:bg-white/10 rounded-lg transition-colors cursor-pointer">
                      Edit
                    </button>
                    <button @click="openDeleteConfirmation(student)"
                            class="px-2.5 py-1 text-[10px] font-normal text-red-700 dark:text-red-400 bg-red-50 hover:bg-red-100 dark:bg-red-950/50 rounded-lg border border-red-200 dark:border-red-900 transition-colors cursor-pointer">
                      Deactivate
                    </button>
                  </div>
                </td>
              </tr>

              <tr v-if="!students.data || students.data.length === 0">
                <td colspan="6" class="py-12 text-center text-gray-400 dark:text-gray-500 font-normal">
                  <p class="text-xs">No student records found.</p>
                  <p class="text-[11px] mt-0.5">Try adjusting your filters or search keywords.</p>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Pagination -->
        <div v-if="students.total > 0" class="px-5 py-3.5 bg-gray-50/50 dark:bg-[#232D26]/50 border-t border-gray-100 dark:border-[#3F4F43] flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-gray-500 dark:text-gray-400">
          <div class="flex items-center gap-3">
            <span>
              Showing <span class="font-semibold text-gray-900 dark:text-white">{{ students.from || 0 }}</span>
              to <span class="font-semibold text-gray-900 dark:text-white">{{ students.to || 0 }}</span>
              of <span class="font-semibold text-gray-900 dark:text-white">{{ students.total }}</span> entries
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
            <button @click="changePage(students.current_page - 1)" :disabled="students.current_page <= 1"
                    class="px-3 py-1.5 rounded-lg border border-gray-200 dark:border-[#3F4F43] bg-white dark:bg-[#2D3A31] text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-white/5 disabled:opacity-40 cursor-pointer disabled:cursor-not-allowed transition-colors">
              Previous
            </button>
            <span class="px-3 py-1.5 font-medium text-gray-900 dark:text-white">
              Page {{ students.current_page }} of {{ students.last_page }}
            </span>
            <button @click="changePage(students.current_page + 1)" :disabled="students.current_page >= students.last_page"
                    class="px-3 py-1.5 rounded-lg border border-gray-200 dark:border-[#3F4F43] bg-white dark:bg-[#2D3A31] text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-white/5 disabled:opacity-40 cursor-pointer disabled:cursor-not-allowed transition-colors">
              Next
            </button>
          </div>
        </div>
      </div>

      <!-- Form Modal -->
      <StudentFormModal
        :show="showFormModal"
        :student="selectedStudent"
        :sections="sections"
        @close="showFormModal = false; fetchStudents()"
      />

      <!-- View Modal -->
      <ViewStudentModal :show="showViewModal" :student="selectedStudent"
                        @close="showViewModal = false"
                        @edit="fromViewToEdit" />

      <!-- Import Modal -->
      <ImportStudentsModal :show="showImportModal" @close="showImportModal = false" @imported="fetchStudents" />

      <!-- Deactivate Confirmation -->
      <Modal :show="showDeleteModal" @close="showDeleteModal = false" max-width="md">
        <div class="p-6 bg-white dark:bg-[#2D3A31] rounded-3xl border border-gray-200 dark:border-[#3F4F43] font-['Inter'] transition-colors">
          <div class="flex items-center gap-3.5 mb-4">
            <div class="w-10 h-10 rounded-2xl bg-red-50 dark:bg-red-950/50 border border-red-100 dark:border-red-900/50 flex items-center justify-center text-red-600 dark:text-red-400 shrink-0">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
              </svg>
            </div>
            <div>
              <h3 class="text-xs font-medium uppercase tracking-wider text-gray-900 dark:text-white">Deactivate Student Account</h3>
              <p class="text-[11px] text-gray-500 dark:text-gray-400 font-normal">Confirm status update</p>
            </div>
          </div>

          <p class="text-xs text-gray-600 dark:text-gray-300 font-normal leading-relaxed mb-6">
            Are you sure you want to deactivate <strong class="font-semibold text-gray-900 dark:text-white">{{ studentToDelete?.name || 'this student' }}</strong>?
            This will soft-delete their record and revoke portal access.
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
import StudentFormModal from './StudentFormModal.vue'
import ViewStudentModal from './ViewStudentModal.vue'
import ImportStudentsModal from './ImportStudentsModal.vue'

const props = defineProps({
  sections: { type: Array, default: () => [] },
})

/* ─── State ─────────────────────────────────────────────── */
const emptyPaginator = () => ({ data: [], current_page: 1, last_page: 1, total: 0, from: 0, to: 0 })

const students = ref(emptyPaginator())
const counts   = ref({ total: 0, active: 0, graduated: 0, dropped_out: 0, transferred_out: 0 })

const filters = reactive({
  search: '',
  status: '',
  grade_level: '',
  section_id: '',
  per_page: 10,
  page: 1,
  sort_by: 'created_at',
  sort_dir: 'desc',
})

let searchTimeout = null

const showFormModal   = ref(false)
const showViewModal   = ref(false)
const showImportModal = ref(false)
const showDeleteModal = ref(false)
const selectedStudent = ref(null)
const studentToDelete = ref(null)
const isDeactivating  = ref(false)

/* ─── Computed ──────────────────────────────────────────── */
const hasActiveFilters = computed(() =>
  filters.search !== '' ||
  filters.status !== '' ||
  filters.grade_level !== '' ||
  filters.section_id !== ''
)

/* ─── Fetch ─────────────────────────────────────────────── */
const fetchStudents = async () => {
  try {
    const { data } = await axios.get('/admin/students/list', { params: filters })
    const p = data?.students
    students.value = p && Array.isArray(p.data) ? p : emptyPaginator()
    counts.value   = data?.counts ?? counts.value
  } catch (e) {
    console.error('Failed to load students:', e)
    students.value = emptyPaginator()
  }
}

/* ─── Filters / Pagination / Sort ───────────────────────── */
const debouncedFetch = () => {
  clearTimeout(searchTimeout)
  searchTimeout = setTimeout(() => {
    filters.page = 1
    fetchStudents()
  }, 300)
}

const setStatusFilter = (status) => {
  filters.status = status
  filters.page = 1
  fetchStudents()
}

const setSort = (field) => {
  if (filters.sort_by === field) {
    filters.sort_dir = filters.sort_dir === 'asc' ? 'desc' : 'asc'
  } else {
    filters.sort_by = field
    filters.sort_dir = 'asc'
  }
  filters.page = 1
  fetchStudents()
}

const changePage = (page) => {
  if (page < 1 || page > students.value.last_page) return
  filters.page = page
  fetchStudents()
}

const changePageSize = () => {
  filters.page = 1
  fetchStudents()
}

const clearFilters = () => {
  filters.search = ''
  filters.status = ''
  filters.grade_level = ''
  filters.section_id = ''
  filters.page = 1
  fetchStudents()
}

/* ─── SortIcon (inline child component) ─────────────────── */
const SortIcon = (props) => {
  const active = filters.sort_by === props.field
  const dir    = filters.sort_dir

  return h('svg', {
    class: [
      'w-3 h-3 transition-colors',
      active ? 'text-[#004d08] dark:text-[#86EFAC]' : 'text-gray-300 dark:text-gray-600',
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
SortIcon.props = { field: { type: String, required: true } }

/* ─── Display helpers ───────────────────────────────────── */
const getInitials = (name) => {
  if (!name) return 'S'
  return name.split(' ').map(n => n[0]).join('').substring(0, 2).toUpperCase()
}

const formatStatus = (s) => !s ? 'Unknown' : s.replace(/_/g, ' ')

const getStatusBadgeClass = (status) => ({
  active:          'bg-emerald-100 dark:bg-emerald-950/60 text-emerald-800 dark:text-emerald-300 border-emerald-200 dark:border-emerald-800',
  graduated:       'bg-sky-50 dark:bg-sky-950/50 text-sky-700 dark:text-sky-300 border-sky-200 dark:border-sky-800',
  dropped_out:     'bg-red-50 dark:bg-red-950/50 text-red-700 dark:text-red-300 border-red-200 dark:border-red-800',
  transferred_out: 'bg-amber-50 dark:bg-amber-950/50 text-amber-700 dark:text-amber-300 border-amber-200 dark:border-amber-800',
}[status] || 'bg-gray-100 dark:bg-[#232D26] text-gray-600 dark:text-gray-400 border-gray-200 dark:border-[#3F4F43]')

const getStatusDotClass = (status) => ({
  active: 'bg-emerald-500', graduated: 'bg-sky-500',
  dropped_out: 'bg-red-500', transferred_out: 'bg-amber-500',
}[status] || 'bg-gray-400')

const cardClass = (active, color) => {
  if (active) {
    return 'text-left p-5 rounded-2xl border shadow-xs flex items-center justify-between transition-colors cursor-pointer bg-' + color + '-50/60 dark:bg-' + color + '-950/20 border-' + color + '-300 dark:border-' + color + '-800'
  }
  return 'text-left p-5 rounded-2xl border shadow-xs flex items-center justify-between transition-colors cursor-pointer bg-white dark:bg-[#2D3A31] border-gray-200/80 dark:border-[#3F4F43] hover:border-gray-300 dark:hover:border-gray-500'
}

/* ─── Modals ────────────────────────────────────────────── */
const openCreateModal = () => {
  selectedStudent.value = null
  showFormModal.value = true
}

const openEditModal = async (student) => {
  try {
    const { data } = await axios.get(`/admin/students/${student.id}`)
    selectedStudent.value = data.student
    showFormModal.value = true
  } catch (e) {
    console.error(e)
    alert('Failed to load student details. Please try again.')
  }
}

const openViewModal = (student) => {
  selectedStudent.value = student
  showViewModal.value = true
}

const fromViewToEdit = (student) => {
  showViewModal.value = false
  setTimeout(() => openEditModal(student), 220)
}

const openDeleteConfirmation = (student) => {
  studentToDelete.value = student
  showDeleteModal.value = true
}

const executeDeactivation = () => {
  if (!studentToDelete.value) return
  isDeactivating.value = true
  router.delete(route('admin.students.destroy', studentToDelete.value.id), {
    onFinish: () => {
      isDeactivating.value = false
      showDeleteModal.value = false
      studentToDelete.value = null
      fetchStudents()
    },
  })
}

/* ─── Export ────────────────────────────────────────────── */
const exportStudents = () => {
  const params = new URLSearchParams()
  if (filters.status)      params.set('status', filters.status)
  if (filters.grade_level) params.set('grade_level', filters.grade_level)
  if (filters.section_id)     params.set('section_id', filters.section_id)
  if (filters.search)      params.set('search', filters.search)

  const qs = params.toString()
  window.location.href = `/admin/students/export${qs ? '?' + qs : ''}`
}

onMounted(() => {
  fetchStudents()
})
</script>