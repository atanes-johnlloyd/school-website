<template>
  <AdminLayout>
    <div class="space-y-6 max-w-7xl mx-auto pb-10 font-['Inter']">

      <!-- Breadcrumb Navigation -->
      <div class="flex items-center gap-2 text-xs text-gray-500 dark:text-gray-400">
        <a href="/admin/sections" class="hover:text-gray-900 dark:hover:text-white transition-colors">Sections</a>
        <span>/</span>
        <span class="text-gray-900 dark:text-white font-medium">{{ section?.name || 'Section Details' }}</span>
      </div>

      <!-- Header Section Card -->
      <div class="bg-white dark:bg-[#2D3A31] rounded-2xl border border-gray-200/80 dark:border-[#3F4F43] p-6 shadow-xs transition-colors">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
          <div class="space-y-1">
            <div class="flex items-center gap-2">
              <span class="bg-emerald-100 dark:bg-emerald-950/60 text-emerald-800 dark:text-emerald-300 text-[10px] font-medium px-2.5 py-0.5 rounded-md uppercase tracking-wider">
                Grade {{ section?.grade_level }}
              </span>
              <span class="text-xs text-gray-500 dark:text-gray-400 font-normal" v-if="section?.school_year">
                &bull; SY {{ section.school_year.label }}
              </span>
            </div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
              {{ section?.name }}
            </h1>
            <p class="text-xs text-gray-500 dark:text-gray-400 font-normal" v-if="section?.strand">
              Strand: <span class="font-medium text-gray-700 dark:text-gray-300">{{ section.strand.code }} - {{ section.strand.name }}</span>
            </p>
          </div>

          <div class="flex items-center gap-3">
            <button 
              @click="showEnrollModal = true"
              class="inline-flex items-center gap-2 bg-[#004d08] hover:bg-emerald-900 text-white font-medium px-4 py-2.5 rounded-xl transition-all shadow-md active:scale-95 text-xs cursor-pointer"
            >
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
              </svg>
              <span>Enroll Student</span>
            </button>
          </div>
        </div>

        <!-- Telemetry Details Strip -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mt-6 pt-6 border-t border-gray-100 dark:border-[#3F4F43]">
          <div>
            <span class="text-[10px] uppercase font-medium text-gray-400 tracking-wider block">Class Adviser</span>
            <span class="text-xs font-semibold text-gray-900 dark:text-white mt-0.5 block">
              {{ section?.adviser?.user?.name || 'Unassigned' }}
            </span>
          </div>

          <div>
            <span class="text-[10px] uppercase font-medium text-gray-400 tracking-wider block">Enrolled Students</span>
            <span class="text-xs font-semibold text-gray-900 dark:text-white mt-0.5 block">
              {{ studentCount }} / {{ section?.max_capacity || 40 }}
            </span>
          </div>

          <div>
            <span class="text-[10px] uppercase font-medium text-gray-400 tracking-wider block">Active Classrooms</span>
            <span class="text-xs font-semibold text-gray-900 dark:text-white mt-0.5 block">
              {{ classesCount }} Subjects Assigned
            </span>
          </div>
        </div>
      </div>

      <!-- Enrolled Students Table -->
      <div class="bg-white dark:bg-[#2D3A31] rounded-2xl border border-gray-200/80 dark:border-[#3F4F43] shadow-xs overflow-hidden transition-colors">
        <div class="p-4 sm:p-5 border-b border-gray-100 dark:border-[#3F4F43] bg-gray-50/50 dark:bg-[#232D26]/50 flex items-center justify-between">
          <div>
            <h2 class="text-xs font-medium text-gray-900 dark:text-white uppercase tracking-wider">Enrolled Student Roster</h2>
            <p class="text-[11px] text-gray-500 dark:text-gray-400 font-normal">Active student registrations in this section</p>
          </div>
          <span class="text-xs font-medium bg-emerald-50 dark:bg-emerald-950/50 text-[#004d08] dark:text-[#86EFAC] px-3 py-1 rounded-full border border-emerald-200 dark:border-emerald-800">
            Total: {{ section?.students?.length || 0 }}
          </span>
        </div>

        <div class="overflow-x-auto">
          <table class="w-full text-left border-collapse">
            <thead>
              <tr class="border-b border-gray-100 dark:border-[#3F4F43] bg-gray-50/50 dark:bg-[#232D26]/50 text-[10px] font-medium uppercase text-gray-400 tracking-wider">
                <th class="py-3.5 px-5">Student Name</th>
                <th class="py-3.5 px-5">Email Address</th>
                <th class="py-3.5 px-5 text-right">Actions</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-[#3F4F43] text-xs font-normal text-gray-800 dark:text-gray-200">
              <tr 
                v-for="student in section?.students" 
                :key="student.id"
                class="hover:bg-gray-50/80 dark:hover:bg-white/5 transition-colors"
              >
                <td class="py-4 px-5">
                  <div class="flex items-center gap-2.5">
                    <div class="w-7 h-7 rounded-full bg-emerald-100 dark:bg-[#1C261E] text-[#004d08] dark:text-[#86EFAC] flex items-center justify-center font-bold text-xs shrink-0">
                      {{ (student.user?.name || 'S').charAt(0) }}
                    </div>
                    <span class="font-medium text-gray-900 dark:text-white text-xs">
                      {{ student.user?.name || 'N/A' }}
                    </span>
                  </div>
                </td>

                <td class="py-4 px-5 text-gray-500 dark:text-gray-400">
                  {{ student.user?.email || 'N/A' }}
                </td>

                <td class="py-4 px-5 text-right">
                  <button 
                    @click="confirmRemoveStudent(student)"
                    class="px-2.5 py-1 text-[10px] font-normal text-red-700 dark:text-red-400 bg-red-50 hover:bg-red-100 dark:bg-red-950/50 rounded-lg border border-red-200 dark:border-red-900 transition-colors cursor-pointer"
                  >
                    Remove
                  </button>
                </td>
              </tr>

              <tr v-if="!section?.students || section.students.length === 0">
                <td colspan="3" class="py-12 text-center text-gray-400 dark:text-gray-500 font-normal">
                  No students currently enrolled in this section.
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Enroll Modal -->
      <EnrollStudentModal 
        :show="showEnrollModal"
        :section="section"
        :available-students="availableStudents"
        @close="showEnrollModal = false"
        @enrolled="fetchSectionDetails"
      />

      <!-- Remove Student Confirmation Modal -->
      <Modal :show="showRemoveModal" @close="showRemoveModal = false" max-width="md">
        <div class="p-6 bg-white dark:bg-[#2D3A31] rounded-3xl border border-gray-200 dark:border-[#3F4F43] font-['Inter'] transition-colors">
          <div class="flex items-center gap-3.5 mb-4">
            <div class="w-10 h-10 rounded-2xl bg-red-50 dark:bg-red-950/50 border border-red-100 dark:border-red-900/50 flex items-center justify-center text-red-600 dark:text-red-400 shrink-0">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
              </svg>
            </div>
            <div>
              <h3 class="text-xs font-medium uppercase tracking-wider text-gray-900 dark:text-white">
                Remove Student
              </h3>
              <p class="text-[11px] text-gray-500 dark:text-gray-400 font-normal">
                Disenroll student from section and linked classrooms
              </p>
            </div>
          </div>

          <p class="text-xs text-gray-600 dark:text-gray-300 font-normal leading-relaxed mb-6">
            Are you sure you want to remove <strong class="font-semibold text-gray-900 dark:text-white">{{ studentToRemove?.user?.name }}</strong> from this section? They will also be removed from all attached subject classrooms.
          </p>

          <div class="flex justify-end gap-2.5 pt-4 border-t border-gray-100 dark:border-[#3F4F43]">
            <button 
              type="button"
              @click="showRemoveModal = false"
              class="px-4 py-2 text-xs font-normal text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-white/5 rounded-xl transition-all cursor-pointer"
            >
              Cancel
            </button>
            <button 
              type="button"
              :disabled="isRemoving"
              @click="executeRemoveStudent"
              class="px-5 py-2 text-xs font-medium uppercase tracking-wider bg-red-600 hover:bg-red-700 text-white rounded-xl transition-all shadow-md active:scale-95 disabled:opacity-50 cursor-pointer"
            >
              {{ isRemoving ? 'Removing...' : 'Confirm Remove' }}
            </button>
          </div>
        </div>
      </Modal>

    </div>
  </AdminLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import Modal from '@/Components/Modal.vue'
import EnrollStudentModal from './EnrollStudentModal.vue'

const props = defineProps({
  sectionId: {
    type: [Number, String],
    required: true
  }
})

const section = ref(null)
const studentCount = ref(0)
const classesCount = ref(0)
const availableStudents = ref([])

const showEnrollModal = ref(false)
const showRemoveModal = ref(false)
const studentToRemove = ref(null)
const isRemoving = ref(false)

const fetchSectionDetails = async () => {
  try {
    const response = await axios.get(`/api/admin/sections/${props.sectionId}`)
    section.value = response.data.section
    studentCount.value = response.data.student_count
    classesCount.value = response.data.classes_count
  } catch (error) {
    console.error('Failed to fetch section details:', error)
  }
}

const confirmRemoveStudent = (student) => {
  studentToRemove.value = student
  showRemoveModal.value = true
}

const executeRemoveStudent = async () => {
  if (!studentToRemove.value || !section.value) return

  isRemoving.value = true
  try {
    await axios.delete(`/api/admin/sections/${section.value.id}/students/${studentToRemove.value.id}`)
    showRemoveModal.value = false
    studentToRemove.value = null
    await fetchSectionDetails()
  } catch (error) {
    console.error('Failed to remove student:', error)
  } finally {
    isRemoving.value = false
  }
}

onMounted(() => {
  fetchSectionDetails()
})
</script>