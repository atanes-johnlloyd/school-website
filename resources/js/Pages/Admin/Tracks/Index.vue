<template>
  <Head title="Tracks & Strands" />

  <AdminLayout>
    <div class="space-y-6 max-w-7xl mx-auto pb-10">

      <!-- Hero Banner Header -->
      <div class="relative overflow-hidden bg-[#004d08] text-white rounded-2xl p-6 md:p-8 shadow-lg border border-emerald-900/40">
        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-4">
          <div>
            <div class="flex items-center gap-2 mb-2">
              <span class="bg-amber-400/20 text-amber-300 text-xs font-black px-2.5 py-0.5 rounded-md border border-amber-400/30">
                CURRICULUM SETUP
              </span>
              <span class="text-emerald-200 text-xs font-semibold">&bull; Senior High Strands</span>
            </div>
            <h1 class="font-['Anton'] text-3xl md:text-4xl tracking-wide uppercase">
              Tracks & <span class="text-amber-400">Strands</span>
            </h1>
            <p class="text-emerald-100/80 text-sm mt-1 max-w-xl">
              Configure academic and technical-vocational tracks, strand specializations, and department coordinators.
            </p>
          </div>

          <button 
            @click="openCreateModal"
            class="inline-flex items-center justify-center gap-2 bg-amber-400 hover:bg-amber-300 text-[#004d08] font-bold px-5 py-3 rounded-xl transition-all shadow-md active:scale-95 shrink-0 cursor-pointer"
          >
            <span class="text-lg leading-none">+</span>
            <span>Add Strand</span>
          </button>
        </div>

        <div class="absolute -right-8 -bottom-10 opacity-10 text-9xl font-['Anton'] pointer-events-none select-none text-white">
          STRAND
        </div>
      </div>

      <!-- Telemetry Stats Cards -->
      <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white dark:bg-slate-800 p-4 rounded-xl border border-slate-200 dark:border-slate-700 shadow-sm flex items-center gap-4">
          <div class="w-12 h-12 rounded-xl bg-emerald-100 dark:bg-emerald-900/40 text-emerald-700 dark:text-emerald-300 flex items-center justify-center text-xl font-bold">
            📚
          </div>
          <div>
            <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Active Strands</p>
            <p class="text-xl font-extrabold text-slate-800 dark:text-white mt-0.5">
              {{ strands.length }}
            </p>
          </div>
        </div>

        <div class="bg-white dark:bg-slate-800 p-4 rounded-xl border border-slate-200 dark:border-slate-700 shadow-sm flex items-center gap-4">
          <div class="w-12 h-12 rounded-xl bg-blue-100 dark:bg-blue-900/40 text-blue-700 dark:text-blue-300 flex items-center justify-center text-xl font-bold">
            🎓
          </div>
          <div>
            <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Top Strand</p>
            <p class="text-xl font-extrabold text-slate-800 dark:text-white mt-0.5">
              STEM (540 Enrolled)
            </p>
          </div>
        </div>

        <div class="bg-white dark:bg-slate-800 p-4 rounded-xl border border-slate-200 dark:border-slate-700 shadow-sm flex items-center gap-4">
          <div class="w-12 h-12 rounded-xl bg-amber-100 dark:bg-amber-900/40 text-amber-700 dark:text-amber-300 flex items-center justify-center text-xl font-bold">
            🏛️
          </div>
          <div>
            <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Academic Tracks</p>
            <p class="text-xl font-extrabold text-slate-800 dark:text-white mt-0.5">
              2 Main Tracks
            </p>
          </div>
        </div>
      </div>

      <!-- Data Table Card -->
      <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden">
        
        <div class="p-4 sm:p-5 border-b border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/50 flex flex-col sm:flex-row gap-3 justify-between items-center">
          <div class="relative w-full sm:w-72">
            <input 
              v-model="search"
              type="text" 
              placeholder="Search strand or code..." 
              class="w-full pl-9 pr-4 py-2 text-sm bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl focus:ring-2 focus:ring-[#004d08] focus:border-transparent"
            />
            <span class="absolute left-3 top-2.5 text-slate-400 text-sm">🔍</span>
          </div>

          <div class="flex items-center gap-2 w-full sm:w-auto justify-end">
            <select 
              v-model="trackFilter"
              class="text-sm bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-3 py-2 focus:ring-2 focus:ring-[#004d08]"
            >
              <option value="">All Tracks</option>
              <option value="Academic Track">Academic Track</option>
              <option value="TVL Track">TVL Track</option>
            </select>
          </div>
        </div>

        <div class="overflow-x-auto">
          <table class="w-full text-left border-collapse text-sm">
            <thead>
              <tr class="border-b border-slate-200 dark:border-slate-700 bg-slate-100/60 dark:bg-slate-900/60 text-slate-600 dark:text-slate-400 font-bold uppercase text-[11px] tracking-wider">
                <th class="py-3.5 px-4">Code</th>
                <th class="py-3.5 px-4">Strand Name</th>
                <th class="py-3.5 px-4">Track</th>
                <th class="py-3.5 px-4">Coordinator</th>
                <th class="py-3.5 px-4 text-center">Enrolled</th>
                <th class="py-3.5 px-4 text-right">Actions</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-700/60">
              <tr 
                v-for="strand in filteredStrands" 
                :key="strand.id"
                class="hover:bg-slate-50/80 dark:hover:bg-slate-700/30 transition-colors"
              >
                <td class="py-3.5 px-4 font-extrabold text-[#004d08] dark:text-emerald-400 font-mono">
                  {{ strand.code }}
                </td>
                <td class="py-3.5 px-4 font-bold text-slate-900 dark:text-white">
                  {{ strand.name }}
                </td>
                <td class="py-3.5 px-4 text-slate-600 dark:text-slate-300">
                  <span class="px-2.5 py-0.5 rounded-md bg-slate-100 dark:bg-slate-700 text-xs font-semibold">
                    {{ strand.track }}
                  </span>
                </td>
                <td class="py-3.5 px-4 text-slate-600 dark:text-slate-300 font-medium">
                  {{ strand.coordinator }}
                </td>
                <td class="py-3.5 px-4 text-center font-bold text-slate-800 dark:text-white">
                  {{ strand.enrolled }}
                </td>
                <td class="py-3.5 px-4 text-right space-x-2">
                  <button 
                    @click="openEditModal(strand)"
                    class="px-2.5 py-1 text-xs font-bold text-slate-700 hover:text-slate-900 dark:text-slate-300 dark:hover:text-white bg-slate-100 hover:bg-slate-200 dark:bg-slate-700 dark:hover:bg-slate-600 rounded-lg transition-all cursor-pointer"
                  >
                    Edit
                  </button>
                  <button 
                    @click="deleteStrand(strand)"
                    class="px-2.5 py-1 text-xs font-bold text-red-600 hover:text-red-800 dark:text-red-400 bg-red-50 hover:bg-red-100 dark:bg-red-900/20 rounded-lg transition-all cursor-pointer"
                  >
                    Delete
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Create / Edit Strand Modal -->
      <Modal :show="showFormModal" @close="showFormModal = false" max-width="md">
        <div class="p-6 space-y-4">
          <h3 class="text-lg font-bold text-slate-900 dark:text-white">
            {{ editingStrand ? 'Edit Strand' : 'Add New Strand' }}
          </h3>

          <form @submit.prevent="saveStrand" class="space-y-4">
            <div class="grid grid-cols-3 gap-3">
              <div>
                <label class="block text-xs font-bold uppercase text-slate-600 dark:text-slate-300 mb-1">Code</label>
                <input v-model="form.code" type="text" required placeholder="STEM" class="w-full px-3.5 py-2 text-sm bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl focus:ring-2 focus:ring-[#004d08]" />
              </div>
              <div class="col-span-2">
                <label class="block text-xs font-bold uppercase text-slate-600 dark:text-slate-300 mb-1">Track</label>
                <select v-model="form.track" required class="w-full px-3.5 py-2 text-sm bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl focus:ring-2 focus:ring-[#004d08]">
                  <option value="Academic Track">Academic Track</option>
                  <option value="TVL Track">TVL Track</option>
                </select>
              </div>
            </div>

            <div>
              <label class="block text-xs font-bold uppercase text-slate-600 dark:text-slate-300 mb-1">Strand Full Name</label>
              <input v-model="form.name" type="text" required placeholder="Science, Technology, Engineering, and Math" class="w-full px-3.5 py-2 text-sm bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl focus:ring-2 focus:ring-[#004d08]" />
            </div>

            <div>
              <label class="block text-xs font-bold uppercase text-slate-600 dark:text-slate-300 mb-1">Coordinator Lead</label>
              <input v-model="form.coordinator" type="text" placeholder="Dr. Maria Cruz" class="w-full px-3.5 py-2 text-sm bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl focus:ring-2 focus:ring-[#004d08]" />
            </div>

            <div class="flex items-center justify-end gap-3 pt-3">
              <button type="button" @click="showFormModal = false" class="px-4 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-100 rounded-xl">Cancel</button>
              <button type="submit" class="px-5 py-2 text-xs font-bold bg-[#004d08] text-white rounded-xl shadow-md">Save Strand</button>
            </div>
          </form>
        </div>
      </Modal>

    </div>
  </AdminLayout>
</template>

<script setup>
import { ref, computed } from 'vue'
import { Head } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import Modal from '@/Components/Modal.vue'

const search = ref('')
const trackFilter = ref('')
const showFormModal = ref(false)
const editingStrand = ref(null)

const form = ref({
  code: '',
  name: '',
  track: 'Academic Track',
  coordinator: ''
})

const strands = ref([
  { id: 1, code: 'STEM', name: 'Science, Technology, Engineering, & Mathematics', track: 'Academic Track', coordinator: 'Dr. Roberto Santos', enrolled: 540 },
  { id: 2, code: 'ABM', name: 'Accountancy, Business, & Management', track: 'Academic Track', coordinator: 'Prof. Clara Reyes', enrolled: 410 },
  { id: 3, code: 'HUMSS', name: 'Humanities & Social Sciences', track: 'Academic Track', coordinator: 'Mr. Gabriel Ramos', enrolled: 380 },
  { id: 4, code: 'TVL-ICT', name: 'Information & Communications Technology', track: 'TVL Track', coordinator: 'Engr. Liza Mendoza', enrolled: 290 },
  { id: 5, code: 'GAS', name: 'General Academic Strand', track: 'Academic Track', coordinator: 'Ms. Elena Torres', enrolled: 220 }
])

const filteredStrands = computed(() => {
  return strands.value.filter(s => {
    const matchesSearch = s.name.toLowerCase().includes(search.value.toLowerCase()) || 
                          s.code.toLowerCase().includes(search.value.toLowerCase())
    const matchesTrack = trackFilter.value === '' || s.track === trackFilter.value
    return matchesSearch && matchesTrack
  })
})

const openCreateModal = () => {
  editingStrand.value = null
  form.value = { code: '', name: '', track: 'Academic Track', coordinator: '' }
  showFormModal.value = true
}

const openEditModal = (strand) => {
  editingStrand.value = strand
  form.value = { ...strand }
  showFormModal.value = true
}

const saveStrand = () => {
  if (editingStrand.value) {
    Object.assign(editingStrand.value, form.value)
  } else {
    strands.value.push({
      id: Date.now(),
      ...form.value,
      enrolled: 0
    })
  }
  showFormModal.value = false
}

const deleteStrand = (strand) => {
  if (confirm(`Are you sure you want to delete ${strand.code}?`)) {
    strands.value = strands.value.filter(s => s.id !== strand.id)
  }
}
</script>