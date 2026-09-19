<template>
  <Head title="My Classes - Salawag LMS" />

  <div class="min-h-screen flex bg-[#e8f5e9] font-['Inter'] relative">
    
    <!-- Sticky Sidebar Navigation -->
    <div class="sticky top-0 h-screen z-30 shrink-0">
      <Sidebar />
    </div>

    <!-- Main Workspace Canvas -->
    <main class="flex-1 relative overflow-y-auto min-h-screen flex flex-col justify-between">
      
      <div class="relative z-10 p-6 md:p-10 space-y-8 flex-1 pb-24">
        
        <!-- HEADER ROW -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
          <div class="space-y-1 max-w-2xl">
            <div class="flex items-center gap-2 font-['Anton'] text-2xl sm:text-3xl md:text-4xl tracking-wide uppercase leading-tight">
              <span class="text-[#005506]">My Assigned Classes</span>
            </div>
            
            <p v-if="activeTerm" class="text-xs sm:text-sm font-semibold text-slate-600">
              Active Term: <span class="text-slate-800 font-bold">{{ activeTerm }}</span>
            </p>

            <!-- Accent Star Line -->
            <div class="flex items-center gap-2 pt-1 max-w-sm">
              <div class="h-[2px] w-full bg-[#005506]"></div>
              <span class="text-[#005506] text-xs">★</span>
            </div>
          </div>
        </div>

        <!-- MAIN SECTION CANVAS -->
        <div class="relative rounded-3xl p-6 sm:p-8 overflow-hidden space-y-6">
          <div 
            class="absolute inset-0 bg-cover bg-center bg-no-repeat pointer-events-none z-0 opacity-15 rounded-3xl"
            :style="{ backgroundImage: `url(${dashboardBg})` }"
          ></div>

          <!-- SEARCH & FILTERS BAR -->
          <div class="relative z-10 flex flex-col sm:flex-row items-center gap-3">
            <div class="relative flex-1 w-full">
              <input
                v-model="search"
                type="text"
                placeholder="Search by subject name, code, or section..."
                class="w-full bg-white/90 backdrop-blur-sm border border-slate-200/80 rounded-xl px-4 py-2.5 text-xs text-slate-800 focus:outline-none focus:border-[#005506] transition-all"
                @input="handleSearch"
              />
            </div>

            <select
              v-model="selectedTerm"
              @change="handleFilter"
              class="w-full sm:w-auto bg-white/90 backdrop-blur-sm border border-slate-200/80 rounded-xl px-3 py-2.5 text-xs font-semibold text-slate-700 focus:outline-none focus:border-[#005506]"
            >
              <option value="">All Terms</option>
              <option v-for="t in filterOptions?.terms" :key="t.id" :value="t.id">
                {{ t.name }}
              </option>
            </select>
          </div>

          <!-- EMPTY STATE -->
          <div 
            v-if="!classList.length" 
            class="relative z-10 bg-white/90 backdrop-blur-sm rounded-2xl p-12 text-center text-slate-500 border border-slate-200/60 shadow-sm space-y-2"
          >
            <p class="font-bold text-slate-700 text-base sm:text-lg">No classes found</p>
            <p class="text-xs sm:text-sm text-slate-500">You don't have any class assignments registered under the selected filters.</p>
          </div>

          <!-- CLASS CARDS GRID -->
          <div v-else class="relative z-10 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <Link
              v-for="classroom in classList"
              :key="classroom.id"
              :href="route('teacher.classes.show', classroom.id)"
              class="group bg-white/90 backdrop-blur-sm rounded-2xl p-6 shadow-sm border border-slate-200/60 hover:border-[#005506]/50 transition-all duration-200 flex flex-col justify-between space-y-4"
            >
              <div class="space-y-2">
                <div class="flex items-start justify-between gap-3">
                  <span class="text-xs font-bold uppercase tracking-wider text-[#005506] bg-emerald-50 px-2.5 py-1 rounded-md border border-emerald-200/60">
                    {{ classroom.subject_code }}
                  </span>
                  
                  <span
                    class="text-xs font-bold px-2.5 py-1 rounded-full border"
                    :class="classroom.is_published
                      ? 'bg-emerald-100 text-emerald-800 border-emerald-200'
                      : 'bg-slate-100 text-slate-600 border-slate-200'"
                  >
                    {{ classroom.is_published ? 'Published' : 'Draft' }}
                  </span>
                </div>

                <h3 class="font-extrabold text-slate-800 text-lg group-hover:text-[#005506] transition-colors leading-snug">
                  {{ classroom.subject }}
                </h3>
              </div>

              <div class="border-t border-slate-100 pt-3 text-xs text-slate-600 space-y-1.5">
                <div class="flex items-center justify-between">
                  <span class="text-slate-400 font-medium">Section</span>
                  <span class="font-bold text-slate-700">{{ classroom.section }}</span>
                </div>
                <div class="flex items-center justify-between">
                  <span class="text-slate-400 font-medium">Grade & Strand</span>
                  <span class="font-bold text-slate-700">G{{ classroom.grade_level }} - {{ classroom.strand_code ?? 'N/A' }}</span>
                </div>
                <div class="flex items-center justify-between">
                  <span class="text-slate-400 font-medium">Enrolled Students</span>
                  <span class="font-bold text-slate-700">{{ classroom.students_count ?? 0 }} Students</span>
                </div>
              </div>
            </Link>
          </div>

          <!-- PAGINATION LINKS -->
          <div v-if="classes?.links?.length > 3" class="relative z-10 flex justify-center gap-1 pt-4">
            <Link
              v-for="(link, k) in classes.links"
              :key="k"
              :href="link.url || '#'"
              v-html="link.label"
              class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-all"
              :class="[
                link.active ? 'bg-[#005506] text-white' : 'bg-white/80 text-slate-600 hover:bg-slate-100',
                !link.url ? 'opacity-50 pointer-events-none' : ''
              ]"
            />
          </div>

        </div>

      </div>

    </main>

  </div>
</template>

<script setup>
import { computed, ref } from 'vue'
import { Head, Link, router } from '@inertiajs/vue3'
import Sidebar from '@/Components/Sidebar.vue'
import dashboardBg from '@/../assets/img/dashboardbackground.png'

const props = defineProps({
  classes: [Array, Object],
  activeTerm: String,
  filters: Object,
  filterOptions: Object,
})

// Safely extract the array from either paginated object or simple array[cite: 22]
const classList = computed(() => {
  if (Array.isArray(props.classes)) return props.classes
  return props.classes?.data ?? []
})

const search = ref(props.filters?.search ?? '')
const selectedTerm = ref(props.filters?.term_id ?? '')

let searchTimeout = null
const handleSearch = () => {
  clearTimeout(searchTimeout)
  searchTimeout = setTimeout(() => {
    handleFilter()
  }, 300)
}

const handleFilter = () => {
  router.get(
    route('teacher.classes.index'),
    {
      search: search.value,
      term_id: selectedTerm.value,
    },
    { preserveState: true, replace: true }
  )
}
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Anton&family=Inter:wght@400;500;600;700;800&display=swap');
</style>