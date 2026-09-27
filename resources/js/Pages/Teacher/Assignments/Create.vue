<template>
  <Head title="Create Assignment - Salawag LMS" />

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
              <span class="text-[#005506]">Create New Assignment</span>
            </div>
            
            <p class="text-xs sm:text-sm font-semibold text-slate-600">
              Class: <span class="text-slate-800 font-bold">{{ classroom.subject }}</span> ({{ classroom.section }})
            </p>

            <!-- Accent Star Line -->
            <div class="flex items-center gap-2 pt-1 max-w-sm">
              <div class="h-[2px] w-full bg-[#005506]"></div>
              <span class="text-[#005506] text-xs">★</span>
            </div>
          </div>

          <Link 
            :href="route('teacher.classes.assignments.index', classroom.id)"
            class="inline-flex items-center gap-1.5 bg-white hover:bg-slate-50 text-slate-700 border border-slate-200/80 px-4 py-2.5 rounded-xl text-xs font-bold shadow-sm transition-all shrink-0"
          >
            ← Cancel
          </Link>
        </div>

        <!-- FORM CANVAS CARD -->
        <div class="relative rounded-3xl p-6 sm:p-8 overflow-hidden">
          <div 
            class="absolute inset-0 bg-cover bg-center bg-no-repeat pointer-events-none z-0 opacity-15 rounded-3xl"
            :style="{ backgroundImage: `url(${dashboardBg})` }"
          ></div>

          <form @submit.prevent="submit" class="relative z-10 bg-white/90 backdrop-blur-sm rounded-2xl p-6 sm:p-8 border border-slate-200/60 shadow-sm max-w-3xl space-y-6">
            
            <!-- Assignment Title -->
            <div class="space-y-1">
              <label class="block text-xs font-extrabold uppercase tracking-wider text-slate-700">Assignment Title *</label>
              <input 
                v-model="form.title" 
                type="text" 
                placeholder="e.g. Chapter 1 Problem Set"
                class="w-full bg-slate-50/50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs sm:text-sm font-medium text-slate-800 focus:outline-none focus:border-[#005506] focus:bg-white transition-all"
              />
              <p v-if="form.errors.title" class="text-red-600 text-xs font-semibold mt-1">{{ form.errors.title }}</p>
            </div>

            <!-- Instructions -->
            <div class="space-y-1">
              <label class="block text-xs font-extrabold uppercase tracking-wider text-slate-700">Instructions</label>
              <textarea 
                v-model="form.instructions" 
                rows="5" 
                placeholder="Provide guidelines, submission rules, or task details..."
                class="w-full bg-slate-50/50 border border-slate-200 rounded-xl p-4 text-xs sm:text-sm font-medium text-slate-800 focus:outline-none focus:border-[#005506] focus:bg-white transition-all"
              ></textarea>
              <p v-if="form.errors.instructions" class="text-red-600 text-xs font-semibold mt-1">{{ form.errors.instructions }}</p>
            </div>

            <!-- Due Date & Points Row -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
              <div class="space-y-1">
                <label class="block text-xs font-extrabold uppercase tracking-wider text-slate-700">Due Date & Time *</label>
                <input 
                  v-model="form.due_at" 
                  type="datetime-local" 
                  class="w-full bg-slate-50/50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs sm:text-sm font-medium text-slate-800 focus:outline-none focus:border-[#005506] focus:bg-white transition-all"
                />
                <p v-if="form.errors.due_at" class="text-red-600 text-xs font-semibold mt-1">{{ form.errors.due_at }}</p>
              </div>

              <div class="space-y-1">
                <label class="block text-xs font-extrabold uppercase tracking-wider text-slate-700">Max Points *</label>
                <input 
                  v-model="form.points" 
                  type="number" 
                  min="1" 
                  class="w-full bg-slate-50/50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs sm:text-sm font-medium text-slate-800 focus:outline-none focus:border-[#005506] focus:bg-white transition-all"
                />
                <p v-if="form.errors.points" class="text-red-600 text-xs font-semibold mt-1">{{ form.errors.points }}</p>
              </div>
            </div>

            <!-- Checkboxes -->
            <div class="flex flex-wrap gap-6 pt-2 border-t border-slate-100">
              <label class="inline-flex items-center gap-2 text-xs font-bold text-slate-700 cursor-pointer">
                <input type="checkbox" v-model="form.allow_late" class="rounded border-slate-300 text-[#005506] focus:ring-[#005506]" /> 
                Allow late submissions
              </label>
              <label class="inline-flex items-center gap-2 text-xs font-bold text-slate-700 cursor-pointer">
                <input type="checkbox" v-model="form.is_published" class="rounded border-slate-300 text-[#005506] focus:ring-[#005506]" /> 
                Publish immediately (visible to students)
              </label>
            </div>

            <!-- Submit Action Bar -->
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
              <Link 
                :href="route('teacher.classes.assignments.index', classroom.id)"
                class="px-5 py-2.5 text-slate-600 hover:text-slate-800 text-xs font-bold transition-colors"
              >
                Cancel
              </Link>
              <button 
                type="submit" 
                :disabled="form.processing"
                class="bg-[#005506] hover:bg-[#003d04] text-white px-6 py-2.5 rounded-xl text-xs font-bold shadow-sm hover:shadow transition-all duration-200 active:scale-95 disabled:opacity-50"
              >
                {{ form.processing ? 'Creating...' : 'Create Assignment' }}
              </button>
            </div>

          </form>
        </div>

      </div>

    </main>

  </div>
</template>

<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3'
import Sidebar from '@/Components/Sidebar.vue'
import dashboardBg from '@/../assets/img/dashboardbackground.png'

const props = defineProps({ classroom: Object })

const form = useForm({
  title: '',
  instructions: '',
  due_at: '',
  points: 100,
  allow_late: true,
  is_published: false,
})

function submit() {
  form.post(route('teacher.classes.assignments.store', props.classroom.id))
}
</script>
