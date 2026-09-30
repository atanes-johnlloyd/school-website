<template>
  <Head title="Terms Management" />

  <AdminLayout>
    <div class="space-y-6 max-w-7xl mx-auto pb-10">

      <!-- Hero Banner Header -->
      <div class="relative overflow-hidden bg-[#004d08] text-white rounded-2xl p-6 md:p-8 shadow-lg border border-emerald-900/40">
        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-4">
          <div>
            <div class="flex items-center gap-2 mb-2">
              <span class="bg-amber-400/20 text-amber-300 text-xs font-black px-2.5 py-0.5 rounded-md border border-amber-400/30">
                ACADEMIC CALENDAR
              </span>
              <span class="text-emerald-200 text-xs font-semibold">&bull; Term & Semester Management</span>
            </div>
            <h1 class="font-['Anton'] text-3xl md:text-4xl tracking-wide uppercase">
              Terms & <span class="text-amber-400">Semesters</span>
            </h1>
            <p class="text-emerald-100/80 text-sm mt-1 max-w-xl">
              Configure academic terms, setup grading periods, and associate terms with active school year sessions.
            </p>
          </div>

          <button 
            @click="openCreateModal"
            class="inline-flex items-center justify-center gap-2 bg-amber-400 hover:bg-amber-300 text-[#004d08] font-bold px-5 py-3 rounded-xl transition-all shadow-md active:scale-95 shrink-0 cursor-pointer"
          >
            <span class="text-lg leading-none">+</span>
            <span>Add Academic Term</span>
          </button>
        </div>

        <div class="absolute -right-8 -bottom-10 opacity-10 text-9xl font-['Anton'] pointer-events-none select-none text-white">
          TERM
        </div>
      </div>

      <!-- Telemetry Stats Cards -->
      <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white dark:bg-slate-800 p-4 rounded-xl border border-slate-200 dark:border-slate-700 shadow-sm flex items-center gap-4">
          <div class="w-12 h-12 rounded-xl bg-emerald-100 dark:bg-emerald-900/40 text-emerald-700 dark:text-emerald-300 flex items-center justify-center text-xl font-bold">
            📅
          </div>
          <div>
            <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Active Term</p>
            <p class="text-xl font-extrabold text-slate-800 dark:text-white mt-0.5">
              {{ activeTerm ? activeTerm.name : 'None Set' }}
            </p>
          </div>
        </div>

        <div class="bg-white dark:bg-slate-800 p-4 rounded-xl border border-slate-200 dark:border-slate-700 shadow-sm flex items-center gap-4">
          <div class="w-12 h-12 rounded-xl bg-blue-100 dark:bg-blue-900/40 text-blue-700 dark:text-blue-300 flex items-center justify-center text-xl font-bold">
            📊
          </div>
          <div>
            <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Total Terms</p>
            <p class="text-xl font-extrabold text-slate-800 dark:text-white mt-0.5">
              {{ terms.length }}
            </p>
          </div>
        </div>

        <div class="bg-white dark:bg-slate-800 p-4 rounded-xl border border-slate-200 dark:border-slate-700 shadow-sm flex items-center gap-4">
          <div class="w-12 h-12 rounded-xl bg-amber-100 dark:bg-amber-900/40 text-amber-700 dark:text-amber-300 flex items-center justify-center text-xl font-bold">
            ⏳
          </div>
          <div>
            <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Grading Period</p>
            <p class="text-xl font-extrabold text-slate-800 dark:text-white mt-0.5">
              Midterm Grading
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
              placeholder="Search terms..." 
              class="w-full pl-9 pr-4 py-2 text-sm bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl focus:ring-2 focus:ring-[#004d08] focus:border-transparent"
            />
            <span class="absolute left-3 top-2.5 text-slate-400 text-sm">🔍</span>
          </div>

          <div class="flex items-center gap-2 w-full sm:w-auto justify-end">
            <select 
              v-model="syFilter"
              class="text-sm bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-3 py-2 focus:ring-2 focus:ring-[#004d08]"
            >
              <option value="">All School Years</option>
              <option value="S.Y. 2026-2027">S.Y. 2026-2027</option>
              <option value="S.Y. 2025-2026">S.Y. 2025-2026</option>
            </select>
          </div>
        </div>

        <div class="overflow-x-auto">
          <table class="w-full text-left border-collapse text-sm">
            <thead>
              <tr class="border-b border-slate-200 dark:border-slate-700 bg-slate-100/60 dark:bg-slate-900/60 text-slate-600 dark:text-slate-400 font-bold uppercase text-[11px] tracking-wider">
                <th class="py-3.5 px-4">Term / Semester</th>
                <th class="py-3.5 px-4">School Year</th>
                <th class="py-3.5 px-4">Start Date</th>
                <th class="py-3.5 px-4">End Date</th>
                <th class="py-3.5 px-4 text-center">Status</th>
                <th class="py-3.5 px-4 text-right">Actions</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-700/60">
              <tr 
                v-for="term in filteredTerms" 
                :key="term.id"
                class="hover:bg-slate-50/80 dark:hover:bg-slate-700/30 transition-colors"
              >
                <td class="py-3.5 px-4 font-bold text-slate-900 dark:text-white">
                  <div class="flex items-center gap-2">
                    <span>{{ term.name }}</span>
                    <span v-if="term.is_active" class="bg-emerald-100 text-emerald-800 dark:bg-emerald-900/50 dark:text-emerald-300 text-[10px] font-black px-2 py-0.5 rounded-full border border-emerald-300/40">
                      CURRENT TERM
                    </span>
                  </div>
                </td>
                <td class="py-3.5 px-4 text-slate-600 dark:text-slate-300 font-semibold">
                  {{ term.school_year }}
                </td>
                <td class="py-3.5 px-4 text-slate-600 dark:text-slate-300">
                  {{ term.start_date }}
                </td>
                <td class="py-3.5 px-4 text-slate-600 dark:text-slate-300">
                  {{ term.end_date }}
                </td>
                <td class="py-3.5 px-4 text-center">
                  <span 
                    :class="[
                      'px-2.5 py-1 rounded-full text-xs font-bold inline-block',
                      term.is_active 
                        ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300' 
                        : 'bg-slate-100 text-slate-600 dark:bg-slate-700 dark:text-slate-400'
                    ]"
                  >
                    {{ term.is_active ? 'Active' : 'Inactive' }}
                  </span>
                </td>
                <td class="py-3.5 px-4 text-right space-x-2">
                  <button 
                    v-if="!term.is_active"
                    @click="setActiveTerm(term)"
                    class="px-2.5 py-1 text-xs font-bold text-amber-700 bg-amber-50 hover:bg-amber-100 dark:bg-amber-900/30 dark:text-amber-300 rounded-lg border border-amber-300/40 transition-all cursor-pointer"
                  >
                    Set Active
                  </button>
                  <button 
                    @click="openEditModal(term)"
                    class="px-2.5 py-1 text-xs font-bold text-slate-700 hover:text-slate-900 dark:text-slate-300 dark:hover:text-white bg-slate-100 hover:bg-slate-200 dark:bg-slate-700 dark:hover:bg-slate-600 rounded-lg transition-all cursor-pointer"
                  >
                    Edit
                  </button>
                  <button 
                    @click="deleteTerm(term)"
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

      <!-- Create / Edit Modal -->
      <Modal :show="showFormModal" @close="showFormModal = false" max-width="md">
        <div class="p-6 space-y-4">
          <h3 class="text-lg font-bold text-slate-900 dark:text-white">
            {{ editingTerm ? 'Edit Academic Term' : 'Add New Academic Term' }}
          </h3>

          <form @submit.prevent="saveTerm" class="space-y-4">
            <div>
              <label class="block text-xs font-bold uppercase text-slate-600 dark:text-slate-300 mb-1">Term Name</label>
              <input v-model="form.name" type="text" required placeholder="e.g. 1st Semester" class="w-full px-3.5 py-2 text-sm bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl focus:ring-2 focus:ring-[#004d08]" />
            </div>

            <div>
              <label class="block text-xs font-bold uppercase text-slate-600 dark:text-slate-300 mb-1">School Year</label>
              <select v-model="form.school_year" required class="w-full px-3.5 py-2 text-sm bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl focus:ring-2 focus:ring-[#004d08]">
                <option value="S.Y. 2026-2027">S.Y. 2026-2027</option>
                <option value="S.Y. 2025-2026">S.Y. 2025-2026</option>
              </select>
            </div>

            <div class="grid grid-cols-2 gap-3">
              <div>
                <label class="block text-xs font-bold uppercase text-slate-600 dark:text-slate-300 mb-1">Start Date</label>
                <input v-model="form.start_date" type="date" required class="w-full px-3.5 py-2 text-sm bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl focus:ring-2 focus:ring-[#004d08]" />
              </div>
              <div>
                <label class="block text-xs font-bold uppercase text-slate-600 dark:text-slate-300 mb-1">End Date</label>
                <input v-model="form.end_date" type="date" required class="w-full px-3.5 py-2 text-sm bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl focus:ring-2 focus:ring-[#004d08]" />
              </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-3">
              <button type="button" @click="showFormModal = false" class="px-4 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-100 rounded-xl">Cancel</button>
              <button type="submit" class="px-5 py-2 text-xs font-bold bg-[#004d08] text-white rounded-xl shadow-md">Save Term</button>
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
const syFilter = ref('')
const showFormModal = ref(false)
const editingTerm = ref(null)

const form = ref({
  name: '',
  school_year: 'S.Y. 2026-2027',
  start_date: '',
  end_date: ''
})

const terms = ref([
  { id: 1, name: '1st Semester', school_year: 'S.Y. 2026-2027', start_date: '2026-08-01', end_date: '2026-12-20', is_active: true },
  { id: 2, name: '2nd Semester', school_year: 'S.Y. 2026-2027', start_date: '2027-01-10', end_date: '2027-05-30', is_active: false },
  { id: 3, name: 'Summer Term', school_year: 'S.Y. 2025-2026', start_date: '2026-06-01', end_date: '2026-07-20', is_active: false }
])

const activeTerm = computed(() => terms.value.find(t => t.is_active))

const filteredTerms = computed(() => {
  return terms.value.filter(t => {
    const matchesSearch = t.name.toLowerCase().includes(search.value.toLowerCase())
    const matchesSy = syFilter.value === '' || t.school_year === syFilter.value
    return matchesSearch && matchesSy
  })
})

const openCreateModal = () => {
  editingTerm.value = null
  form.value = { name: '', school_year: 'S.Y. 2026-2027', start_date: '', end_date: '' }
  showFormModal.value = true
}

const openEditModal = (term) => {
  editingTerm.value = term
  form.value = { ...term }
  showFormModal.value = true
}

const saveTerm = () => {
  if (editingTerm.value) {
    Object.assign(editingTerm.value, form.value)
  } else {
    terms.value.push({
      id: Date.now(),
      ...form.value,
      is_active: false
    })
  }
  showFormModal.value = false
}

const setActiveTerm = (term) => {
  terms.value.forEach(t => t.is_active = (t.id === term.id))
}

const deleteTerm = (term) => {
  if (confirm(`Are you sure you want to delete ${term.name}?`)) {
    terms.value = terms.value.filter(t => t.id !== term.id)
  }
}
</script>