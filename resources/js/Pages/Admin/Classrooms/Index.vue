<template>
  <AdminLayout>
    <div class="space-y-6 max-w-7xl mx-auto pb-10 font-['Inter']">

      <!-- Page Header -->
      <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
          <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Subject Classrooms</h1>
          <p class="text-xs text-gray-500 dark:text-gray-400 font-normal mt-1">
            Manage subject classes, schedule assignments, room locations, and assigned teachers.
          </p>
        </div>

        <button 
          @click="openCreateModal"
          class="inline-flex items-center gap-2 bg-[#004d08] hover:bg-emerald-900 text-white font-medium px-4 py-2.5 rounded-xl transition-all shadow-md active:scale-95 text-xs cursor-pointer self-start sm:self-auto"
        >
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
          </svg>
          <span>Create Subject Classroom</span>
        </button>
      </div>

      <!-- Filters & Search Bar -->
      <div class="bg-white dark:bg-[#2D3A31] p-4 rounded-2xl border border-gray-200/80 dark:border-[#3F4F43] shadow-xs transition-colors">
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
          
          <!-- Search Bar -->
          <div>
            <label class="block text-[10px] font-medium text-gray-400 uppercase tracking-wider mb-1">Search</label>
            <div class="relative">
              <input 
                v-model="filters.search"
                @input="debouncedFetch"
                type="text" 
                placeholder="Search subject, section, room..." 
                class="w-full pl-9 pr-3.5 py-2 text-xs bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white rounded-xl focus:ring-2 focus:ring-[#004d08] focus:outline-none font-normal"
              />
              <svg class="w-4 h-4 absolute left-3 top-2.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
              </svg>
            </div>
          </div>

          <!-- Section Filter -->
          <div>
            <label class="block text-[10px] font-medium text-gray-400 uppercase tracking-wider mb-1">Filter by Section</label>
            <select 
              v-model="filters.section_id"
              @change="fetchClassrooms"
              class="w-full px-3.5 py-2 text-xs bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white rounded-xl focus:ring-2 focus:ring-[#004d08] focus:outline-none font-normal cursor-pointer"
            >
              <option value="">All Sections</option>
              <option v-for="section in sections" :key="section.id" :value="section.id">
                {{ section.name }} (Grade {{ section.grade_level }})
              </option>
            </select>
          </div>

          <!-- Teacher Filter -->
          <div>
            <label class="block text-[10px] font-medium text-gray-400 uppercase tracking-wider mb-1">Filter by Teacher</label>
            <select 
              v-model="filters.teacher_id"
              @change="fetchClassrooms"
              class="w-full px-3.5 py-2 text-xs bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white rounded-xl focus:ring-2 focus:ring-[#004d08] focus:outline-none font-normal cursor-pointer"
            >
              <option value="">All Teachers</option>
              <option v-for="teacher in teachers" :key="teacher.id" :value="teacher.id">
                {{ teacher.user?.name || teacher.name }}
              </option>
            </select>
          </div>

        </div>
      </div>

      <!-- Classrooms Table -->
      <div class="bg-white dark:bg-[#2D3A31] rounded-2xl border border-gray-200/80 dark:border-[#3F4F43] shadow-xs overflow-hidden transition-colors">
        <div class="overflow-x-auto">
          <table class="w-full text-left border-collapse">
            <thead>
              <tr class="border-b border-gray-100 dark:border-[#3F4F43] bg-gray-50/50 dark:bg-[#232D26]/50 text-[10px] font-medium uppercase text-gray-400 tracking-wider">
                <th class="py-3.5 px-5">Subject</th>
                <th class="py-3.5 px-5">Section</th>
                <th class="py-3.5 px-5">Subject Teacher</th>
                <th class="py-3.5 px-5">Schedule & Room</th>
                <th class="py-3.5 px-5">Enrolled</th>
                <th class="py-3.5 px-5 text-right">Actions</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-[#3F4F43] text-xs font-normal text-gray-800 dark:text-gray-200">
              <tr 
                v-for="item in classrooms.data" 
                :key="item.id"
                class="hover:bg-gray-50/80 dark:hover:bg-white/5 transition-colors"
              >
                <!-- Subject Info -->
                <td class="py-4 px-5">
                  <a :href="`/admin/classrooms/${item.id}`" class="font-semibold text-gray-900 dark:text-white hover:text-[#004d08] dark:hover:text-[#86EFAC] transition-colors block">
                    {{ item.subject?.name }}
                  </a>
                  <span class="text-[10px] font-mono text-gray-400 block">{{ item.subject?.code }}</span>
                </td>

                <!-- Section Info -->
                <td class="py-4 px-5">
                  <span class="font-medium text-gray-900 dark:text-white block">{{ item.section?.name }}</span>
                  <span class="text-[10px] text-gray-400 block">Grade {{ item.section?.grade_level }} &bull; SY {{ item.section?.school_year?.label }}</span>
                </td>

                <!-- Teacher -->
                <td class="py-4 px-5 text-gray-600 dark:text-gray-300">
                  {{ item.teacher?.user?.name || 'Unassigned' }}
                </td>

                <!-- Schedule & Room -->
                <td class="py-4 px-5">
                  <div class="text-xs text-gray-800 dark:text-gray-200">{{ item.schedule || 'TBA' }}</div>
                  <div class="text-[10px] text-gray-400">Room: {{ item.room_number || 'Unassigned' }}</div>
                </td>

                <!-- Enrolled Count -->
                <td class="py-4 px-5">
                  <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-50 dark:bg-emerald-950/50 text-[#004d08] dark:text-[#86EFAC] border border-emerald-200 dark:border-emerald-800">
                    {{ item.students_count ?? 0 }} Students
                  </span>
                </td>

                <!-- Actions -->
                <td class="py-4 px-5 text-right">
                  <div class="flex items-center justify-end gap-2">
                    <a 
                      :href="`/admin/classrooms/${item.id}`"
                      class="px-2.5 py-1 text-[11px] font-medium text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-white/5 rounded-lg border border-gray-200 dark:border-[#3F4F43] transition-colors"
                    >
                      View
                    </a>
                    <button 
                      @click="openEditModal(item)"
                      class="px-2.5 py-1 text-[11px] font-medium text-[#004d08] dark:text-[#86EFAC] bg-emerald-50 hover:bg-emerald-100 dark:bg-emerald-950/40 rounded-lg border border-emerald-200 dark:border-emerald-800 transition-colors cursor-pointer"
                    >
                      Edit
                    </button>
                    <button 
                      @click="confirmDelete(item)"
                      class="px-2.5 py-1 text-[11px] font-medium text-red-600 dark:text-red-400 bg-red-50 hover:bg-red-100 dark:bg-red-950/40 rounded-lg border border-red-200 dark:border-red-900 transition-colors cursor-pointer"
                    >
                      Delete
                    </button>
                  </div>
                </td>
              </tr>

              <tr v-if="!classrooms.data || classrooms.data.length === 0">
                <td colspan="6" class="py-12 text-center text-gray-400 dark:text-gray-500 font-normal">
                  No subject classrooms found matching the criteria.
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Pagination -->
        <div v-if="classrooms.links && classrooms.links.length > 3" class="p-4 border-t border-gray-100 dark:border-[#3F4F43] flex items-center justify-between">
          <p class="text-[11px] text-gray-500 dark:text-gray-400">
            Showing {{ classrooms.from }} to {{ classrooms.to }} of {{ classrooms.total }} results
          </p>
          <div class="flex gap-1">
            <button 
              v-for="(link, i) in classrooms.links" 
              :key="i"
              @click="changePage(link.url)"
              :disabled="!link.url || link.active"
              v-html="link.label"
              class="px-3 py-1 text-xs rounded-lg transition-colors cursor-pointer disabled:opacity-50"
              :class="link.active ? 'bg-[#004d08] text-white font-medium' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-white/5'"
            />
          </div>
        </div>
      </div>

      <!-- Create / Edit Modal -->
      <ClassroomModal 
        :show="showModal"
        :classroom="selectedClassroom"
        :sections="sections"
        :subjects="subjects"
        :teachers="teachers"
        @close="showModal = false"
        @saved="fetchClassrooms"
      />

      <!-- Delete Confirmation Modal -->
      <Modal :show="showDeleteModal" @close="showDeleteModal = false" max-width="md">
        <div class="p-6 bg-white dark:bg-[#2D3A31] rounded-3xl border border-gray-200 dark:border-[#3F4F43] font-['Inter']">
          <h3 class="text-xs font-medium uppercase tracking-wider text-gray-900 dark:text-white mb-2">
            Delete Subject Classroom
          </h3>
          <p class="text-xs text-gray-600 dark:text-gray-300 leading-relaxed mb-6">
            Are you sure you want to remove <strong class="text-gray-900 dark:text-white">{{ classroomToDelete?.subject?.name }}</strong> for section <strong class="text-gray-900 dark:text-white">{{ classroomToDelete?.section?.name }}</strong>?
          </p>

          <div v-if="deleteError" class="mb-4 p-3 bg-red-50 dark:bg-red-950/40 border border-red-200 dark:border-red-800 rounded-xl text-red-700 dark:text-red-300 text-xs">
            {{ deleteError }}
          </div>

          <div class="flex justify-end gap-2.5 pt-4 border-t border-gray-100 dark:border-[#3F4F43]">
            <button 
              type="button" 
              @click="showDeleteModal = false" 
              class="px-4 py-2 text-xs font-normal text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-white/5 rounded-xl cursor-pointer"
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
import { ref, reactive, onMounted } from 'vue'
import axios from 'axios'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import Modal from '@/Components/Modal.vue'
import ClassroomModal from './ClassroomModal.vue'

const props = defineProps({
  sections: { type: Array, default: () => [] },
  subjects: { type: Array, default: () => [] },
  teachers: { type: Array, default: () => [] },
})

const classrooms = ref({ data: [], links: [] })
const showModal = ref(false)
const selectedClassroom = ref(null)

const showDeleteModal = ref(false)
const classroomToDelete = ref(null)
const isDeleting = ref(false)
const deleteError = ref('')

const filters = reactive({
  search: '',
  section_id: '',
  teacher_id: '',
})

let searchTimeout = null

const debouncedFetch = () => {
  clearTimeout(searchTimeout)
  searchTimeout = setTimeout(() => {
    fetchClassrooms()
  }, 300)
}

const fetchClassrooms = async (url = '/api/admin/classrooms') => {
  try {
    const response = await axios.get(url, { params: filters })
    classrooms.value = response.data.classrooms
  } catch (error) {
    console.error('Failed to load classrooms:', error)
  }
}

const changePage = (url) => {
  if (url) fetchClassrooms(url)
}

const openCreateModal = () => {
  selectedClassroom.value = null
  showModal.value = true
}

const openEditModal = (item) => {
  selectedClassroom.value = item
  showModal.value = true
}

const confirmDelete = (item) => {
  classroomToDelete.value = item
  deleteError.value = ''
  showDeleteModal.value = true
}

const executeDelete = async () => {
  if (!classroomToDelete.value) return
  isDeleting.value = true
  deleteError.value = ''

  try {
    await axios.delete(`/api/admin/classrooms/${classroomToDelete.value.id}`)
    showDeleteModal.value = false
    classroomToDelete.value = null
    await fetchClassrooms()
  } catch (error) {
    deleteError.value = error.response?.data?.message || 'Failed to delete classroom.'
  } finally {
    isDeleting.value = false
  }
}

onMounted(() => {
  fetchClassrooms()
})
</script>