<template>
  <Head :title="`Materials - ${classroom.subject} - Salawag LMS`" />

  <div class="min-h-screen flex bg-[#e8f5e9] font-['Inter'] relative">
    <div class="sticky top-0 h-screen z-30 shrink-0">
      <Sidebar />
    </div>

    <main class="flex-1 relative overflow-y-auto min-h-screen flex flex-col justify-between">
      <div class="relative z-10 p-6 md:p-10 space-y-8 flex-1 pb-24">
        
        <!-- HEADER ROW -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
          <div class="space-y-1 max-w-2xl">
            <h1 class="font-['Anton'] text-2xl sm:text-3xl md:text-4xl tracking-wide uppercase text-[#005506]">
              Class Materials
            </h1>
            <p class="text-xs sm:text-sm font-semibold text-slate-600">
              Subject: <span class="text-slate-800 font-bold">{{ classroom.subject }}</span>
              <span class="text-slate-300 mx-2">|</span> 
              Section: <span class="text-slate-800 font-bold">{{ classroom.section }}</span>
            </p>
          </div>

          <div class="flex items-center gap-3 shrink-0">
            <button 
              @click="showCreateModal = true"
              class="inline-flex items-center gap-2 bg-[#005506] hover:bg-[#003d04] text-white px-5 py-2.5 rounded-xl text-xs font-bold shadow-sm transition-all active:scale-95"
            >
              + Add Material
            </button>
          </div>
        </div>

        <!-- TABS -->
        <div class="relative z-10 flex border-b border-slate-200/80 gap-2 sm:gap-6 overflow-x-auto">
          <Link :href="route('teacher.classes.show', classroom.id)" class="pb-3 text-xs sm:text-sm font-semibold text-slate-500 hover:text-[#005506]">Overview</Link>
          <Link :href="route('teacher.classes.assignments.index', classroom.id)" class="pb-3 text-xs sm:text-sm font-semibold text-slate-500 hover:text-[#005506]">Assignments</Link>
          <Link :href="route('teacher.classes.materials.index', classroom.id)" class="pb-3 text-xs sm:text-sm font-bold border-b-2 border-[#005506] text-[#005506]">Materials</Link>
        </div>

        <!-- MATERIALS LIST -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div v-if="!materials.length" class="col-span-full bg-white/90 rounded-2xl p-12 text-center text-slate-500 border border-slate-200/60 shadow-sm">
            No learning materials added yet.
          </div>

          <div 
            v-for="material in materials" 
            :key="material.id"
            class="bg-white/90 backdrop-blur-sm rounded-2xl p-5 border border-slate-200/60 shadow-sm flex items-start justify-between gap-4"
          >
            <div class="space-y-2">
              <div class="flex items-center gap-2">
                <span class="p-2 rounded-lg bg-emerald-50 text-[#005506] font-bold text-xs">
                  {{ material.type === 'link' ? '🔗 Link' : '📄 File' }}
                </span>
                <h3 class="font-bold text-slate-800 text-sm sm:text-base">{{ material.title }}</h3>
              </div>
              <p class="text-xs text-slate-600 line-clamp-2">{{ material.description || 'No description provided.' }}</p>
            </div>

            <div class="flex items-center gap-2">
              <a 
                :href="material.file_url || material.link_url" 
                target="_blank" 
                class="text-xs bg-[#005506] text-white px-3 py-1.5 rounded-lg font-bold hover:bg-[#003d04]"
              >
                Open
              </a>
              <button 
                @click="deleteMaterial(material.id)"
                class="text-xs text-rose-600 hover:text-rose-800 font-bold px-2 py-1.5"
              >
                Delete
              </button>
            </div>
          </div>
        </div>

      </div>
    </main>

    <!-- CREATE MATERIAL MODAL -->
    <div v-if="showCreateModal" class="fixed inset-0 z-50 bg-slate-900/40 backdrop-blur-xs flex items-center justify-center p-4">
      <div class="bg-white rounded-3xl p-6 sm:p-8 max-w-lg w-full space-y-6 shadow-xl">
        <h2 class="font-bold text-lg text-slate-800">Post Learning Material</h2>

        <form @submit.prevent="submitMaterial" class="space-y-4">
          <div>
            <label class="text-xs font-bold text-slate-600 block mb-1">Title</label>
            <input v-model="form.title" type="text" required class="w-full text-xs p-3 rounded-xl border border-slate-200" placeholder="e.g. Chapter 1 Slides" />
          </div>

          <div>
            <label class="text-xs font-bold text-slate-600 block mb-1">Description</label>
            <textarea v-model="form.description" class="w-full text-xs p-3 rounded-xl border border-slate-200" rows="3" placeholder="Brief notes for students..."></textarea>
          </div>

          <div>
            <label class="text-xs font-bold text-slate-600 block mb-1">Material Type</label>
            <select v-model="form.type" class="w-full text-xs p-3 rounded-xl border border-slate-200">
              <option value="file">File Upload</option>
              <option value="link">External URL Link</option>
            </select>
          </div>

          <div v-if="form.type === 'file'">
            <label class="text-xs font-bold text-slate-600 block mb-1">Upload File</label>
            <input type="file" @change="e => form.file = e.target.files[0]" class="w-full text-xs text-slate-500" />
          </div>

          <div v-else>
            <label class="text-xs font-bold text-slate-600 block mb-1">Resource URL</label>
            <input v-model="form.link_url" type="url" class="w-full text-xs p-3 rounded-xl border border-slate-200" placeholder="https://..." />
          </div>

          <div class="flex justify-end gap-2 pt-2">
            <button type="button" @click="showCreateModal = false" class="px-4 py-2 text-xs font-bold text-slate-600">Cancel</button>
            <button type="submit" :disabled="form.processing" class="px-5 py-2 text-xs font-bold bg-[#005506] text-white rounded-xl">Save & Publish</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { Head, Link, useForm, router } from '@inertiajs/vue3'
import Sidebar from '@/Components/Sidebar.vue'

const props = defineProps({
  classroom: Object,
  materials: Array,
})

const showCreateModal = ref(false)

const form = useForm({
  title: '',
  description: '',
  type: 'file',
  file: null,
  link_url: '',
})

function submitMaterial() {
  form.post(route('teacher.classes.materials.store', props.classroom.id), {
    onSuccess: () => {
      showCreateModal.value = false
      form.reset()
    },
  })
}

function deleteMaterial(id) {
  if (confirm('Are you sure you want to delete this material?')) {
    router.delete(route('teacher.materials.destroy', id))
  }
}
</script>