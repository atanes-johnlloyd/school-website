<template>
  <AdminLayout>
    <div class="space-y-6 max-w-7xl mx-auto pb-10 font-['Inter']">

      <!-- Breadcrumbs -->
      <div class="flex items-center gap-2 text-xs text-gray-500 dark:text-gray-400">
        <a href="/admin/classrooms" class="hover:text-gray-900 dark:hover:text-white transition-colors">Classrooms</a>
        <span>/</span>
        <span class="text-gray-900 dark:text-white font-medium">{{ classroom?.subject?.name || 'Details' }}</span>
      </div>

      <!-- Header Card -->
      <div class="bg-white dark:bg-[#2D3A31] rounded-2xl border border-gray-200/80 dark:border-[#3F4F43] p-6 shadow-xs transition-colors">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
          <div class="space-y-1">
            <div class="flex items-center gap-2">
              <span class="bg-emerald-100 dark:bg-emerald-950/60 text-emerald-800 dark:text-emerald-300 text-[10px] font-medium px-2.5 py-0.5 rounded-md uppercase tracking-wider">
                {{ classroom?.subject?.code }}
              </span>
              <span class="text-xs text-gray-500 dark:text-gray-400 font-normal">
                &bull; Section: {{ classroom?.section?.name }} (Grade {{ classroom?.section?.grade_level }})
              </span>
            </div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
              {{ classroom?.subject?.name }}
            </h1>
          </div>

          <button 
            @click="syncRoster"
            :disabled="isSyncing"
            class="inline-flex items-center gap-2 bg-[#004d08] hover:bg-emerald-900 text-white font-medium px-4 py-2 rounded-xl transition-all shadow-md active:scale-95 text-xs cursor-pointer disabled:opacity-50"
          >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
            </svg>
            <span>{{ isSyncing ? 'Syncing Roster...' : 'Sync Roster with Section' }}</span>
          </button>
        </div>

        <!-- Information Strip -->
        <div class="grid grid-cols-1 sm:grid-cols-4 gap-4 mt-6 pt-6 border-t border-gray-100 dark:border-[#3F4F43]">
          <div>
            <span class="text-[10px] uppercase font-medium text-gray-400 tracking-wider block">Subject Teacher</span>
            <span class="text-xs font-semibold text-gray-900 dark:text-white mt-0.5 block">
              {{ classroom?.teacher?.user?.name || 'Unassigned' }}
            </span>
          </div>

          <div>
            <span class="text-[10px] uppercase font-medium text-gray-400 tracking-wider block">Schedule</span>
            <span class="text-xs font-semibold text-gray-900 dark:text-white mt-0.5 block">
              {{ classroom?.schedule || 'TBA' }}
            </span>
          </div>

          <div>
            <span class="text-[10px] uppercase font-medium text-gray-400 tracking-wider block">Room Number</span>
            <span class="text-xs font-semibold text-gray-900 dark:text-white mt-0.5 block">
              {{ classroom?.room_number || 'Unassigned' }}
            </span>
          </div>

          <div>
            <span class="text-[10px] uppercase font-medium text-gray-400 tracking-wider block">Class Roster Count</span>
            <span class="text-xs font-semibold text-gray-900 dark:text-white mt-0.5 block">
              {{ studentCount }} Enrolled Students
            </span>
          </div>
        </div>
      </div>

      <!-- Class Roster Table -->
      <div class="bg-white dark:bg-[#2D3A31] rounded-2xl border border-gray-200/80 dark:border-[#3F4F43] shadow-xs overflow-hidden transition-colors">
        <div class="p-4 sm:p-5 border-b border-gray-100 dark:border-[#3F4F43] bg-gray-50/50 dark:bg-[#232D26]/50 flex items-center justify-between">
          <div>
            <h2 class="text-xs font-medium text-gray-900 dark:text-white uppercase tracking-wider">Classroom Roster</h2>
            <p class="text-[11px] text-gray-500 dark:text-gray-400 font-normal">Students currently enrolled in this subject class</p>
          </div>
        </div>

        <div class="overflow-x-auto">
          <table class="w-full text-left border-collapse">
            <thead>
              <tr class="border-b border-gray-100 dark:border-[#3F4F43] bg-gray-50/50 dark:bg-[#232D26]/50 text-[10px] font-medium uppercase text-gray-400 tracking-wider">
                <th class="py-3.5 px-5">Student Name</th>
                <th class="py-3.5 px-5">Email Address</th>
                <th class="py-3.5 px-5">Status</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-[#3F4F43] text-xs font-normal text-gray-800 dark:text-gray-200">
              <tr 
                v-for="student in classroom?.students" 
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

                <td class="py-4 px-5">
                  <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium bg-emerald-50 dark:bg-emerald-950/50 text-[#004d08] dark:text-[#86EFAC] border border-emerald-200 dark:border-emerald-800 capitalize">
                    {{ student.pivot?.status || 'active' }}
                  </span>
                </td>
              </tr>

              <tr v-if="!classroom?.students || classroom.students.length === 0">
                <td colspan="3" class="py-12 text-center text-gray-400 dark:text-gray-500 font-normal">
                  No students in this classroom roster.
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

    </div>
  </AdminLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import AdminLayout from '@/Layouts/AdminLayout.vue'

const props = defineProps({
  classroomId: { type: [Number, String], required: true }
})

const classroom = ref(null)
const studentCount = ref(0)
const isSyncing = ref(false)

const fetchClassroom = async () => {
  try {
    const response = await axios.get(`/api/admin/classrooms/${props.classroomId}`)
    classroom.value = response.data.classroom
    studentCount.value = response.data.student_count
  } catch (error) {
    console.error('Failed to load classroom details:', error)
  }
}

const syncRoster = async () => {
  if (!classroom.value) return
  isSyncing.value = true
  try {
    await axios.post(`/api/admin/classrooms/${classroom.value.id}/sync-roster`)
    await fetchClassroom()
  } catch (error) {
    console.error('Failed to sync roster:', error)
  } finally {
    isSyncing.value = false
  }
}

onMounted(() => {
  fetchClassroom()
})
</script>