<template>
  <Modal :show="show" @close="closeModal" max-width="2xl">
    <div class="bg-white dark:bg-[#2D3A31] rounded-2xl border border-gray-200 dark:border-[#3F4F43] font-['Inter'] flex flex-col max-h-[85vh]">

      <div class="flex items-start justify-between gap-4 px-6 pt-5 pb-4 border-b border-gray-100 dark:border-[#3F4F43]">
        <div class="flex items-center gap-3 min-w-0">
          <div class="w-10 h-10 rounded-2xl bg-emerald-50 dark:bg-[#1C261E] border border-emerald-100 dark:border-[#3F4F43] flex items-center justify-center text-[#004d08] dark:text-[#86EFAC] shrink-0">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
          </div>
          <div class="min-w-0">
            <h3 class="text-xs font-medium uppercase tracking-wider text-gray-900 dark:text-white truncate">
              Admin User Profile
            </h3>
            <p class="text-[11px] text-gray-500 dark:text-gray-400 font-normal truncate">
              <template v-if="user">{{ user.name }} &bull; {{ user.email }}</template>
              <template v-else>Loading…</template>
            </p>
          </div>
        </div>

        <div class="flex items-center gap-2 shrink-0">
          <span v-if="user"
                class="px-2.5 py-1 rounded-full text-[10px] font-normal uppercase tracking-wider inline-flex items-center gap-1.5 border"
                :class="user.status === 'active'
                  ? 'bg-emerald-100 dark:bg-emerald-950/60 text-emerald-800 dark:text-emerald-300 border-emerald-200 dark:border-emerald-800'
                  : 'bg-amber-50 dark:bg-amber-950/50 text-amber-700 dark:text-amber-300 border-amber-200 dark:border-amber-800'">
            <span :class="['w-1.5 h-1.5 rounded-full', user.status === 'active' ? 'bg-emerald-500' : 'bg-amber-500']"></span>
            {{ user.status }}
          </span>

          <button type="button" @click="closeModal"
                  class="p-1.5 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 rounded-xl hover:bg-gray-100 dark:hover:bg-white/5 transition-colors cursor-pointer">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>
      </div>

      <div class="flex-1 min-h-0 overflow-y-auto px-6 py-5 space-y-5">
        <div v-if="generalError" class="p-3 bg-red-50 dark:bg-red-950/40 border border-red-200 dark:border-red-800 rounded-xl text-red-700 dark:text-red-300 text-xs">
          {{ generalError }}
        </div>

        <!-- Primary admin notice -->
        <div v-if="user?.is_primary"
             class="p-3 bg-amber-50 dark:bg-amber-950/30 border border-amber-200 dark:border-amber-800 rounded-xl text-amber-700 dark:text-amber-300 text-xs flex items-start gap-2">
          <svg class="w-4 h-4 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
          <span>This is the primary system admin. Certain actions (disable, revoke) are locked to protect the platform.</span>
        </div>

        <div v-if="isLoading" class="py-16 text-center">
          <div class="inline-block w-6 h-6 border-2 border-[#004d08] dark:border-[#86EFAC] border-t-transparent rounded-full animate-spin"></div>
          <p class="text-[11px] text-gray-500 dark:text-gray-400 mt-2">Loading user details…</p>
        </div>

        <div v-else-if="user" class="space-y-5">

          <!-- Account -->
          <section>
            <h4 class="text-[10px] font-semibold uppercase tracking-wider text-[#004d08] dark:text-[#86EFAC] mb-2">Account Information</h4>
            <div class="grid grid-cols-2 gap-3">
              <div class="col-span-2">
                <p class="text-[10px] font-medium uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-0.5">Full Name</p>
                <p class="text-xs text-gray-900 dark:text-white">{{ user.name }}</p>
              </div>
              <div class="col-span-2">
                <p class="text-[10px] font-medium uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-0.5">Email Address</p>
                <p class="text-xs text-gray-900 dark:text-white">{{ user.email }}</p>
              </div>
              <div>
                <p class="text-[10px] font-medium uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-0.5">Account Created</p>
                <p class="text-xs text-gray-900 dark:text-white">{{ formatDate(user.created_at) }}</p>
              </div>
              <div>
                <p class="text-[10px] font-medium uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-0.5">Last Updated</p>
                <p class="text-xs text-gray-900 dark:text-white">{{ formatDate(user.updated_at) }}</p>
              </div>
            </div>
          </section>

          <!-- Role -->
          <section class="pt-3 border-t border-gray-100 dark:border-[#3F4F43]">
            <h4 class="text-[10px] font-semibold uppercase tracking-wider text-[#004d08] dark:text-[#86EFAC] mb-2">Administrative Role</h4>
            <div class="p-3 rounded-xl bg-emerald-50/50 dark:bg-emerald-950/20 border border-emerald-100 dark:border-emerald-900/50">
              <p class="text-xs font-medium text-gray-900 dark:text-white">{{ user.admin_position || 'Unassigned' }}</p>
              <p v-if="user.admin_position_description" class="text-[11px] text-gray-500 dark:text-gray-400 mt-0.5">
                {{ user.admin_position_description }}
              </p>
            </div>
          </section>

          <!-- Disabled reason -->
          <section v-if="user.status === 'disabled' && user.disabled_reason" class="pt-3 border-t border-gray-100 dark:border-[#3F4F43]">
            <h4 class="text-[10px] font-semibold uppercase tracking-wider text-amber-600 dark:text-amber-400 mb-2">Disabled Reason</h4>
            <p class="text-xs text-gray-700 dark:text-gray-300 bg-amber-50/60 dark:bg-amber-950/20 border border-amber-100 dark:border-amber-900/50 rounded-xl p-3 leading-relaxed">
              {{ user.disabled_reason }}
            </p>
          </section>

          <!-- Permissions -->
          <section v-if="user.permissions?.length" class="pt-3 border-t border-gray-100 dark:border-[#3F4F43]">
            <div class="flex items-center justify-between mb-2">
              <h4 class="text-[10px] font-semibold uppercase tracking-wider text-[#004d08] dark:text-[#86EFAC]">
                Granted Permissions
              </h4>
              <span class="text-[10px] text-gray-400 dark:text-gray-500">{{ user.permissions.length }} total</span>
            </div>
            <div class="flex flex-wrap gap-1.5">
              <span v-for="perm in user.permissions" :key="perm"
                    class="inline-block px-2 py-0.5 text-[10px] font-mono bg-gray-100 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] text-gray-700 dark:text-gray-300 rounded-md">
                {{ perm }}
              </span>
            </div>
          </section>

        </div>
      </div>

      <div class="px-6 py-4 border-t border-gray-100 dark:border-[#3F4F43] flex justify-end gap-2">
        <button type="button" @click="closeModal"
                class="px-4 py-2 text-xs font-normal text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-white/5 rounded-xl transition-all cursor-pointer">
          Close
        </button>
        <button v-if="user && !user.is_primary" type="button" @click="$emit('edit', user)"
                class="px-5 py-2 text-xs font-medium uppercase tracking-wider bg-[#004d08] hover:bg-emerald-900 text-white rounded-xl transition-all shadow-md active:scale-95 cursor-pointer">
          Edit User
        </button>
      </div>

    </div>
  </Modal>
</template>

<script setup>
import { ref, watch } from 'vue'
import axios from 'axios'
import Modal from '@/Components/Modal.vue'

const props = defineProps({
  show: { type: Boolean, default: false },
  user: { type: Object,  default: null },
})

const emit = defineEmits(['close', 'edit'])

const user         = ref(null)
const isLoading    = ref(false)
const generalError = ref('')

watch(() => props.show, async (open) => {
  if (open && props.user?.id) {
    user.value = null
    generalError.value = ''
    await fetchDetails()
  }
})

const fetchDetails = async () => {
  isLoading.value = true
  try {
    const { data } = await axios.get(`/admin/users/${props.user.id}`)
    user.value = data.user
  } catch (e) {
    generalError.value = e.response?.data?.message || 'Failed to load user details.'
  } finally {
    isLoading.value = false
  }
}

const formatDate = (iso) => {
  if (!iso) return '—'
  try { return new Date(iso).toLocaleDateString('en-PH', { year: 'numeric', month: 'long', day: 'numeric' }) }
  catch { return iso }
}

const closeModal = () => {
  user.value = null
  generalError.value = ''
  emit('close')
}
</script>