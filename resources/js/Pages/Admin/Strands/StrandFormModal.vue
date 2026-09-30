<template>
  <Modal :show="show" @close="closeModal" max-width="lg">
    <div class="p-6 bg-white dark:bg-[#2D3A31] rounded-3xl border border-gray-200 dark:border-[#3F4F43] font-['Inter'] transition-colors">
      <!-- Header -->
      <div class="flex items-center justify-between pb-4 mb-5 border-b border-gray-100 dark:border-[#3F4F43]">
        <div class="flex items-center gap-3">
          <div class="w-10 h-10 rounded-2xl bg-emerald-50 dark:bg-[#1C261E] border border-emerald-100 dark:border-[#3F4F43] flex items-center justify-center text-[#004d08] dark:text-[#86EFAC] shrink-0">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
            </svg>
          </div>
          <div>
            <h3 class="text-xs font-medium uppercase tracking-wider text-gray-900 dark:text-white">
              {{ isEditing ? 'Edit Academic Strand' : 'Register New Strand' }}
            </h3>
            <p class="text-[11px] text-gray-500 dark:text-gray-400 font-normal">
              {{ isEditing ? 'Modify curriculum strand specialization and details' : 'Add a new track/strand offering to the curriculum' }}
            </p>
          </div>
        </div>

        <button 
          type="button"
          @click="closeModal" 
          class="p-1.5 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 rounded-xl hover:bg-gray-100 dark:hover:bg-white/5 transition-colors cursor-pointer"
        >
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>

      <!-- Form Body -->
      <form @submit.prevent="submit" class="space-y-4">
        <!-- Strand Code & Track Type -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
          <div>
            <label class="block text-[11px] font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1.5">
              Strand Code <span class="text-red-500">*</span>
            </label>
            <input 
              v-model="form.code"
              type="text" 
              placeholder="e.g. STEM"
              class="w-full px-3.5 py-2.5 text-xs bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white placeholder-gray-400 rounded-xl focus:ring-2 focus:ring-[#004d08] dark:focus:ring-[#86EFAC] focus:outline-none font-normal uppercase"
              :class="{ 'border-red-500 dark:border-red-500': form.errors.code }"
            />
            <p v-if="form.errors.code" class="mt-1 text-[11px] text-red-500 font-normal">{{ form.errors.code }}</p>
          </div>

          <div class="sm:col-span-2">
            <label class="block text-[11px] font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1.5">
              Track Type <span class="text-red-500">*</span>
            </label>
            <select 
              v-model="form.track_type"
              class="w-full px-3.5 py-2.5 text-xs bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white rounded-xl focus:ring-2 focus:ring-[#004d08] focus:outline-none font-normal"
              :class="{ 'border-red-500 dark:border-red-500': form.errors.track_type }"
            >
              <option value="Academic">Academic Track</option>
              <option value="TVL">Technical-Vocational-Livelihood (TVL)</option>
              <option value="Sports">Sports Track</option>
              <option value="Arts & Design">Arts & Design Track</option>
            </select>
            <p v-if="form.errors.track_type" class="mt-1 text-[11px] text-red-500 font-normal">{{ form.errors.track_type }}</p>
          </div>
        </div>

        <!-- Full Strand Name -->
        <div>
          <label class="block text-[11px] font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1.5">
            Full Strand Name <span class="text-red-500">*</span>
          </label>
          <input 
            v-model="form.name"
            type="text" 
            placeholder="e.g. Science, Technology, Engineering, and Mathematics"
            class="w-full px-3.5 py-2.5 text-xs bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white placeholder-gray-400 rounded-xl focus:ring-2 focus:ring-[#004d08] dark:focus:ring-[#86EFAC] focus:outline-none font-normal"
            :class="{ 'border-red-500 dark:border-red-500': form.errors.name }"
          />
          <p v-if="form.errors.name" class="mt-1 text-[11px] text-red-500 font-normal">{{ form.errors.name }}</p>
        </div>

        <!-- Description -->
        <div>
          <label class="block text-[11px] font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1.5">
            Strand Description / Overview
          </label>
          <textarea 
            v-model="form.description"
            rows="3" 
            placeholder="Overview of specialization, career paths, and focus subjects..."
            class="w-full px-3.5 py-2.5 text-xs bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white placeholder-gray-400 rounded-xl focus:ring-2 focus:ring-[#004d08] dark:focus:ring-[#86EFAC] focus:outline-none font-normal"
          ></textarea>
        </div>

        <!-- Active Toggle Switch -->
        <div class="pt-1">
          <label class="flex items-start gap-3 p-3 bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] rounded-xl hover:bg-gray-100/60 dark:hover:bg-white/5 transition-colors cursor-pointer">
            <input 
              v-model="form.is_active"
              type="checkbox" 
              class="mt-0.5 w-4 h-4 text-[#004d08] border-gray-300 rounded focus:ring-[#004d08] cursor-pointer"
            />
            <div class="space-y-0.5">
              <span class="block text-xs font-medium text-gray-900 dark:text-white">Active Status</span>
              <span class="block text-[10px] text-gray-500 dark:text-gray-400 font-normal">
                Active strands can be assigned to student enrollments and section assignments.
              </span>
            </div>
          </label>
        </div>

        <!-- Action Buttons -->
        <div class="mt-6 flex justify-end gap-2.5 pt-4 border-t border-gray-100 dark:border-[#3F4F43]">
          <button 
            type="button"
            @click="closeModal"
            class="px-4 py-2 text-xs font-normal text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-white/5 rounded-xl transition-all cursor-pointer"
          >
            Cancel
          </button>
          <button 
            type="submit"
            :disabled="form.processing"
            class="px-5 py-2 text-xs font-medium uppercase tracking-wider bg-[#004d08] text-white hover:bg-emerald-900 rounded-xl transition-all shadow-md active:scale-95 disabled:opacity-50 cursor-pointer"
          >
            {{ form.processing ? 'Saving...' : (isEditing ? 'Save Changes' : 'Register Strand') }}
          </button>
        </div>
      </form>
    </div>
  </Modal>
</template>

<script setup>
import { computed, watch } from 'vue'
import { useForm } from '@inertiajs/vue3'
import Modal from '@/Components/Modal.vue'

const props = defineProps({
  show: {
    type: Boolean,
    default: false
  },
  strand: {
    type: Object,
    default: null
  }
})

const emit = defineEmits(['close'])

const isEditing = computed(() => !!props.strand)

const form = useForm({
  code: '',
  name: '',
  track_type: 'Academic',
  description: '',
  is_active: true
})

watch(() => props.strand, (newVal) => {
  if (newVal) {
    form.code = newVal.code || ''
    form.name = newVal.name || ''
    form.track_type = newVal.track_type || 'Academic'
    form.description = newVal.description || ''
    form.is_active = newVal.is_active !== undefined ? !!newVal.is_active : true
  } else {
    form.reset()
    form.track_type = 'Academic'
  }
}, { immediate: true })

const closeModal = () => {
  form.reset()
  form.clearErrors()
  emit('close')
}

const submit = () => {
  if (isEditing.value) {
    form.put(route('admin.strands.update', props.strand.id), {
      onSuccess: () => closeModal()
    })
  } else {
    form.post(route('admin.strands.store'), {
      onSuccess: () => closeModal()
    })
  }
}
</script>