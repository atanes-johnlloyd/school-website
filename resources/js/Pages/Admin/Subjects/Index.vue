<template>
  <AdminLayout>
    <div class="space-y-6 max-w-7xl mx-auto pb-10 font-['Inter']">

      <!-- Hero Header Banner -->
      <div class="relative overflow-hidden bg-[#004d08] text-white rounded-2xl p-6 md:p-8 shadow-lg border border-emerald-900/40">
        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-4">
          <div>
            <div class="flex items-center gap-2 mb-2">
              <span class="bg-amber-400/20 text-amber-300 text-xs font-medium px-2.5 py-0.5 rounded-md border border-amber-400/30">
                ACADEMIC CURRICULUM
              </span>
              <span class="text-emerald-200 text-xs font-normal">&bull; Subject Offerings</span>
            </div>
            <h1 class="font-['Anton'] text-3xl md:text-4xl tracking-wide uppercase">
              Subject <span class="text-amber-400">Management</span>
            </h1>
            <p class="text-emerald-100/80 text-sm mt-1 max-w-xl font-normal">
              Manage subject offerings, grade level distribution, prerequisites, and track/strand specializations.
            </p>
          </div>

          <button 
            @click="openCreateModal"
            class="inline-flex items-center justify-center gap-2 bg-amber-400 hover:bg-amber-300 text-[#004d08] font-medium px-5 py-3 rounded-xl transition-all shadow-md active:scale-95 shrink-0 text-sm cursor-pointer"
          >
            <span class="text-lg leading-none">+</span>
            <span>Add New Subject</span>
          </button>
        </div>

        <div class="absolute -right-6 -bottom-8 opacity-10 text-9xl font-['Anton'] pointer-events-none select-none text-white">
          SUBJECT
        </div>
      </div>

      <!-- Telemetry Stat Cards -->
      <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white dark:bg-[#2D3A31] p-5 rounded-2xl border border-gray-200/80 dark:border-[#3F4F43] shadow-xs flex items-center justify-between transition-colors">
          <div class="space-y-1">
            <p class="text-[10px] font-medium uppercase text-gray-400 tracking-wider">Total Subjects</p>
            <p class="text-2xl font-medium text-gray-900 dark:text-white">{{ subjectsList.length }}</p>
            <span class="text-[10px] text-gray-500 dark:text-gray-400 font-normal">Registered Offerings</span>
          </div>
          <div class="w-12 h-12 rounded-2xl bg-emerald-50 dark:bg-[#1C261E] border border-emerald-100 dark:border-[#3F4F43] flex items-center justify-center text-emerald-700 dark:text-[#86EFAC] shrink-0">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
            </svg>
          </div>
        </div>

        <div class="bg-white dark:bg-[#2D3A31] p-5 rounded-2xl border border-gray-200/80 dark:border-[#3F4F43] shadow-xs flex items-center justify-between transition-colors">
          <div class="space-y-1">
            <p class="text-[10px] font-medium uppercase text-gray-400 tracking-wider">Core Subjects</p>
            <p class="text-2xl font-medium text-gray-900 dark:text-white">{{ coreSubjectsCount }}</p>
            <span class="text-[10px] text-amber-600 dark:text-amber-400 font-normal">Mandatory Courses</span>
          </div>
          <div class="w-12 h-12 rounded-2xl bg-amber-50 dark:bg-[#1C261E] border border-amber-100 dark:border-[#3F4F43] flex items-center justify-center text-amber-600 dark:text-amber-400 shrink-0">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
            </svg>
          </div>
        </div>

        <div class="bg-white dark:bg-[#2D3A31] p-5 rounded-2xl border border-gray-200/80 dark:border-[#3F4F43] shadow-xs flex items-center justify-between transition-colors">
          <div class="space-y-1">
            <p class="text-[10px] font-medium uppercase text-gray-400 tracking-wider">Specialized / Applied</p>
            <p class="text-2xl font-medium text-gray-900 dark:text-white">{{ specializedSubjectsCount }}</p>
            <span class="text-[10px] text-blue-600 dark:text-blue-400 font-normal">Strand Specific</span>
          </div>
          <div class="w-12 h-12 rounded-2xl bg-blue-50 dark:bg-[#1C261E] border border-blue-100 dark:border-[#3F4F43] flex items-center justify-center text-blue-600 dark:text-blue-400 shrink-0">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
            </svg>
          </div>
        </div>
      </div>

      <!-- Main Directory Card -->
      <div class="bg-white dark:bg-[#2D3A31] rounded-2xl border border-gray-200/80 dark:border-[#3F4F43] shadow-xs overflow-hidden transition-colors">
        <!-- Controls Header -->
        <div class="p-4 sm:p-5 border-b border-gray-100 dark:border-[#3F4F43] bg-gray-50/50 dark:bg-[#232D26]/50 flex flex-col sm:flex-row gap-3 justify-between items-center">
          <div>
            <h2 class="text-xs font-medium text-gray-900 dark:text-white uppercase tracking-wider">Subject Roster</h2>
            <p class="text-[11px] text-gray-500 dark:text-gray-400 font-normal">Search and filter active academic subjects</p>
          </div>

          <div class="flex flex-wrap sm:flex-nowrap items-center gap-2 w-full sm:w-auto justify-end">
            <!-- Strand Filter -->
            <select 
              v-model="selectedStrand"
              class="px-3 py-2 text-xs bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white rounded-xl focus:ring-2 focus:ring-[#004d08] focus:outline-none font-normal"
            >
              <option value="">All Strands</option>
              <option v-for="s in strands" :key="s.id" :value="s.id">{{ s.code }}</option>
            </select>

            <!-- Grade Filter -->
            <select 
              v-model="selectedGrade"
              class="px-3 py-2 text-xs bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white rounded-xl focus:ring-2 focus:ring-[#004d08] focus:outline-none font-normal"
            >
              <option value="">All Grades</option>
              <option v-for="g in [7, 8, 9, 10, 11, 12]" :key="g" :value="String(g)">Grade {{ g }}</option>
            </select>

            <!-- Search Input -->
            <div class="relative w-full sm:w-64">
              <svg class="w-4 h-4 absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
              </svg>
              <input 
                v-model="search"
                type="text" 
                placeholder="Search code, title, description..." 
                class="w-full pl-10 pr-4 py-2 text-xs bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white placeholder-gray-400 rounded-xl focus:ring-2 focus:ring-[#004d08] dark:focus:ring-[#86EFAC] focus:outline-none font-normal"
              />
            </div>
          </div>
        </div>

        <!-- Directory Table -->
        <div class="overflow-x-auto">
          <table class="w-full text-left border-collapse">
            <thead>
              <tr class="border-b border-gray-100 dark:border-[#3F4F43] bg-gray-50/50 dark:bg-[#232D26]/50 text-[10px] font-medium uppercase text-gray-400 tracking-wider">
                <th class="py-3.5 px-5">Code</th>
                <th class="py-3.5 px-5">Subject Title</th>
                <th class="py-3.5 px-5">Grade & Strand</th>
                <th class="py-3.5 px-5">Type</th>
                <th class="py-3.5 px-5">Prerequisite</th>
                <th class="py-3.5 px-5 text-center">Status</th>
                <th class="py-3.5 px-5 text-right">Actions</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-[#3F4F43] text-xs font-normal text-gray-800 dark:text-gray-200">
              <tr 
                v-for="sub in filteredSubjects" 
                :key="sub.id"
                class="hover:bg-gray-50/80 dark:hover:bg-white/5 transition-colors"
              >
                <!-- Code -->
                <td class="py-4 px-5">
                  <span class="font-semibold text-gray-900 dark:text-white text-xs bg-gray-100 dark:bg-[#232D26] px-2.5 py-1 rounded-lg border border-gray-200 dark:border-[#3F4F43] uppercase">
                    {{ sub.code }}
                  </span>
                </td>

                <!-- Name & Description -->
                <td class="py-4 px-5">
                  <div class="max-w-xs">
                    <span class="font-medium text-gray-900 dark:text-white block">
                      {{ sub.name }}
                    </span>
                    <span class="text-[11px] text-gray-500 dark:text-gray-400 font-normal truncate block" v-if="sub.description">
                      {{ sub.description }}
                    </span>
                  </div>
                </td>

                <!-- Grade & Strand -->
                <td class="py-4 px-5">
                  <div>
                    <span class="text-xs text-gray-900 dark:text-white font-medium block">
                      {{ sub.grade_level === 'both' ? 'Grades 11 & 12' : `Grade ${sub.grade_level}` }}
                    </span>
                    <span class="text-[11px] text-emerald-700 dark:text-[#86EFAC] font-normal" v-if="sub.strand">
                      {{ sub.strand.code }}
                    </span>
                    <span class="text-[11px] text-gray-400 font-normal" v-else>
                      All Strands
                    </span>
                  </div>
                </td>

                <!-- Type (Core vs Specialized) -->
                <td class="py-4 px-5">
                  <span 
                    :class="[
                      'px-2 py-0.5 rounded text-[10px] font-medium uppercase tracking-wider inline-block',
                      sub.is_core 
                        ? 'bg-amber-100 dark:bg-amber-950/60 text-amber-800 dark:text-amber-300 border border-amber-200 dark:border-amber-800' 
                        : 'bg-blue-100 dark:bg-blue-950/60 text-blue-800 dark:text-blue-300 border border-blue-200 dark:border-blue-800'
                    ]"
                  >
                    {{ sub.is_core ? 'Core' : 'Specialized' }}
                  </span>
                  <span class="text-[10px] text-gray-400 block mt-0.5" v-if="sub.hours">
                    {{ sub.hours }} Hours
                  </span>
                </td>

                <!-- Prerequisite -->
                <td class="py-4 px-5">
                  <span v-if="sub.prerequisite" class="text-xs text-gray-800 dark:text-gray-200 font-normal">
                    {{ sub.prerequisite.code }}
                  </span>
                  <span v-else class="text-xs text-gray-400 font-normal">
                    None
                  </span>
                </td>

                <!-- Status Badge -->
                <td class="py-4 px-5 text-center">
                  <span 
                    :class="[
                      'px-2.5 py-1 rounded-full text-[10px] font-normal uppercase tracking-wider inline-flex items-center gap-1.5',
                      sub.is_active 
                        ? 'bg-emerald-100 dark:bg-emerald-950/60 text-emerald-800 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800' 
                        : 'bg-gray-100 dark:bg-[#232D26] text-gray-600 dark:text-gray-400 border border-gray-200 dark:border-[#3F4F43]'
                    ]"
                  >
                    <span :class="['w-1.5 h-1.5 rounded-full', sub.is_active ? 'bg-emerald-500' : 'bg-gray-400']"></span>
                    {{ sub.is_active ? 'Active' : 'Inactive' }}
                  </span>
                </td>

                <!-- Actions -->
                <td class="py-4 px-5 text-right">
                  <div class="flex items-center justify-end gap-1.5">
                    <button 
                      @click="openEditModal(sub)"
                      class="px-2.5 py-1 text-[10px] font-normal text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-[#232D26] hover:bg-gray-200 dark:hover:bg-white/10 rounded-lg transition-colors cursor-pointer"
                    >
                      Edit
                    </button>
                    <button 
                      @click="openDeleteConfirmation(sub)"
                      class="px-2.5 py-1 text-[10px] font-normal text-red-700 dark:text-red-400 bg-red-50 hover:bg-red-100 dark:bg-red-950/50 rounded-lg border border-red-200 dark:border-red-900 transition-colors cursor-pointer"
                    >
                      Delete
                    </button>
                  </div>
                </td>
              </tr>

              <tr v-if="filteredSubjects.length === 0">
                <td colspan="7" class="py-12 text-center text-gray-400 dark:text-gray-500 font-normal">
                  <p class="text-xs">No subjects match the selected criteria.</p>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Form Modal -->
      <SubjectFormModal 
        :show="showFormModal"
        :subject="selectedSubject"
        :strands="strands"
        :prerequisites="prerequisites"
        @close="showFormModal = false"
      />

      <!-- Delete Confirmation Modal -->
      <Modal :show="showDeleteModal" @close="showDeleteModal = false" max-width="md">
        <div class="p-6 bg-white dark:bg-[#2D3A31] rounded-3xl border border-gray-200 dark:border-[#3F4F43] font-['Inter'] transition-colors">
          <div class="flex items-center gap-3.5 mb-4">
            <div class="w-10 h-10 rounded-2xl bg-red-50 dark:bg-red-950/50 border border-red-100 dark:border-red-900/50 flex items-center justify-center text-red-600 dark:text-red-400 shrink-0">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
              </svg>
            </div>
            <div>
              <h3 class="text-xs font-medium uppercase tracking-wider text-gray-900 dark:text-white">
                Delete Academic Subject
              </h3>
              <p class="text-[11px] text-gray-500 dark:text-gray-400 font-normal">
                Confirm subject removal
              </p>
            </div>
          </div>

          <p class="text-xs text-gray-600 dark:text-gray-300 font-normal leading-relaxed mb-6">
            Are you sure you want to delete <strong class="font-semibold text-gray-900 dark:text-white">{{ subjectToDelete?.code }} - {{ subjectToDelete?.name }}</strong>? This action cannot be undone.
          </p>

          <div class="flex justify-end gap-2.5 pt-4 border-t border-gray-100 dark:border-[#3F4F43]">
            <button 
              type="button"
              @click="showDeleteModal = false"
              class="px-4 py-2 text-xs font-normal text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-white/5 rounded-xl transition-all cursor-pointer"
            >
              Cancel
            </button>
            <button 
              type="button"
              :disabled="isDeleting"
              @click="executeDelete"
              class="px-5 py-2 text-xs font-medium uppercase tracking-wider bg-red-600 hover:bg-red-700 text-white rounded-xl transition-all shadow-md active:scale-95 disabled:opacity-50 cursor-pointer"
            >
              {{ isDeleting ? 'Deleting...' : 'Confirm Delete' }}
            </button>
          </div>
        </div>
      </Modal>

    </div>
  </AdminLayout>
</template>

<script setup>
import { ref, computed } from 'vue'
import { router } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import Modal from '@/Components/Modal.vue'
import SubjectFormModal from './SubjectFormModal.vue'

const props = defineProps({
  subjects: {
    type: Array,
    default: () => []
  },
  strands: {
    type: Array,
    default: () => []
  },
  prerequisites: {
    type: Array,
    default: () => []
  }
})

const search = ref('')
const selectedStrand = ref('')
const selectedGrade = ref('')
const showFormModal = ref(false)
const selectedSubject = ref(null)

// Delete Modal State
const showDeleteModal = ref(false)
const subjectToDelete = ref(null)
const isDeleting = ref(false)

const subjectsList = computed(() => props.subjects || [])

const coreSubjectsCount = computed(() => {
  return subjectsList.value.filter(s => s.is_core).length
})

const specializedSubjectsCount = computed(() => {
  return subjectsList.value.filter(s => !s.is_core).length
})

const filteredSubjects = computed(() => {
  return subjectsList.value.filter(s => {
    const query = search.value.toLowerCase()
    const code = s.code?.toLowerCase() || ''
    const name = s.name?.toLowerCase() || ''
    const desc = s.description?.toLowerCase() || ''

    const matchesQuery = code.includes(query) || name.includes(query) || desc.includes(query)
    const matchesStrand = selectedStrand.value ? String(s.strand_id) === String(selectedStrand.value) : true
    const matchesGrade = selectedGrade.value ? (String(s.grade_level) === String(selectedGrade.value) || s.grade_level === 'both') : true

    return matchesQuery && matchesStrand && matchesGrade
  })
})

const openCreateModal = () => {
  selectedSubject.value = null
  showFormModal.value = true
}

const openEditModal = (sub) => {
  selectedSubject.value = sub
  showFormModal.value = true
}

const openDeleteConfirmation = (sub) => {
  subjectToDelete.value = sub
  showDeleteModal.value = true
}

const executeDelete = () => {
  if (!subjectToDelete.value) return
  
  isDeleting.value = true
  router.delete(route('admin.subjects.destroy', subjectToDelete.value.id), {
    onFinish: () => {
      isDeleting.value = false
      showDeleteModal.value = false
      subjectToDelete.value = null
    }
  })
}
</script>