<template>
  <AdminLayout>
    <div class="space-y-6 mx-auto pb-10 font-['Inter']">

      <!-- Hero Banner -->
      <div
        class="relative overflow-hidden w-full rounded-2xl sm:rounded-3xl shadow-lg border border-[#006907]/20 dark:border-none min-h-[220px] sm:min-h-[240px] flex flex-col justify-center p-4 sm:p-8 md:p-10">
        <!-- Background Image Layer (System Configuration / Cloud Network Infrastructure Theme) -->
        <img
          :src="heroImage || 'https://images.unsplash.com/photo-1451187580459-43490279c0fa?q=80&w=1600&auto=format&fit=crop'"
          alt="System Settings Hero" class="absolute inset-0 w-full h-full object-cover z-0 object-center" />

        <!-- Animated Dark Green Overlay -->
        <div class="absolute inset-0 bg-[#004d05]/85 dark:bg-[#152B1C]/90 animate-overlay z-0 mix-blend-multiply"></div>

        <!-- Ambient Light Glow Highlights -->
        <div
          class="absolute -right-10 -bottom-10 w-72 h-72 rounded-full bg-emerald-300/20 blur-3xl pointer-events-none z-0">
        </div>
        <div class="absolute right-24 -top-16 w-80 h-80 rounded-full bg-[#F9C20C]/20 blur-3xl pointer-events-none z-0">
        </div>

        <!-- Content Container -->
        <div class="relative z-10 w-full flex flex-col md:flex-row md:items-center justify-between gap-4">
          <div class="space-y-2 sm:space-y-3 max-w-2xl">
            <!-- Top Capsule Badges -->
            <div class="flex flex-wrap items-center gap-2 sm:gap-2.5">
              <span
                class="inline-flex items-center gap-1.5 bg-[#F9C20C] text-[#2C3E2D] font-black text-[10px] sm:text-xs px-3 sm:px-4 py-1 sm:py-1.5 rounded-full shadow-sm tracking-wide uppercase">
                <span>⚙️</span> SYSTEM
              </span>

              <span
                class="inline-flex items-center gap-2 bg-black/30 dark:bg-black/50 backdrop-blur-md text-white border border-white/20 text-[10px] sm:text-xs font-semibold px-3 sm:px-4 py-1 sm:py-1.5 rounded-full shadow-sm">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                Settings
              </span>
            </div>

            <!-- Title & Subtitle -->
            <div class="space-y-1 sm:space-y-1.5">
              <h1
                class="font-['Anton'] text-2xl sm:text-4xl md:text-5xl tracking-wide uppercase flex items-center gap-2 select-none flex-wrap leading-none text-white drop-shadow-md">
                <span>SYSTEM</span>
                <span class="text-[#F9C20C] drop-shadow-[0_2px_8px_rgba(249,194,12,0.4)]">SETTINGS</span>
              </h1>
              <p class="text-white/90 text-xs sm:text-sm md:text-base leading-relaxed font-medium">
                Configure school information, academic defaults, and system behavior.
              </p>
            </div>
          </div>

          <!-- Right Controls: Reset & Save Buttons -->
          <div class="flex items-center gap-2 shrink-0 flex-wrap sm:flex-nowrap pt-2 md:pt-0">
            <button @click="resetAll" :disabled="saving"
              class="inline-flex items-center justify-center gap-1.5 bg-black/30 hover:bg-black/40 backdrop-blur-md text-white border border-white/20 font-bold px-3.5 sm:px-4 py-2.5 sm:py-3 rounded-xl transition-all active:scale-95 text-xs sm:text-sm cursor-pointer shadow-sm disabled:opacity-50 disabled:cursor-not-allowed">
              <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-emerald-300" fill="none" stroke="currentColor"
                viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                  d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
              </svg>
              <span>Reset All</span>
            </button>

            <button @click="save" :disabled="saving || !hasChanges"
              class="inline-flex items-center justify-center gap-2 bg-[#F9C20C] hover:bg-[#e0ae0a] text-[#2C3E2D] font-black px-4 sm:px-5 py-2.5 sm:py-3 rounded-xl transition-all shadow-md active:scale-95 text-xs sm:text-sm cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed">
              <svg v-if="saving" class="w-4 h-4 animate-spin text-[#2C3E2D]" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor"
                  d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                </path>
              </svg>
              <span>{{ saving ? 'Saving…' : 'Save Changes' }}</span>
            </button>
          </div>
        </div>
      </div>

      <!-- Success banner -->
      <div v-if="successMessage"
        class="p-3 bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 rounded-xl text-emerald-700 dark:text-emerald-300 text-xs flex items-center gap-2">
        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        {{ successMessage }}
      </div>

      <!-- Error banner -->
      <div v-if="generalError"
        class="p-3 bg-red-50 dark:bg-red-950/40 border border-red-200 dark:border-red-800 rounded-xl text-red-700 dark:text-red-300 text-xs">
        {{ generalError }}
      </div>

      <!-- Groups -->
      <div v-for="(fields, group) in schema" :key="group"
        class="bg-white dark:bg-[#2D3A31] rounded-2xl border border-gray-200/80 dark:border-[#3F4F43] overflow-hidden">

        <!-- Group header -->
        <div
          class="flex items-center justify-between px-5 py-3.5 border-b border-gray-100 dark:border-[#3F4F43] bg-gray-50/50 dark:bg-[#232D26]/50">
          <div class="flex items-center gap-3">
            <div
              class="w-9 h-9 rounded-xl bg-emerald-50 dark:bg-[#1C261E] border border-emerald-100 dark:border-[#3F4F43] flex items-center justify-center text-[#004d08] dark:text-[#86EFAC] shrink-0">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" :d="groupIcon(group)" />
              </svg>
            </div>
            <div>
              <h3 class="text-xs font-medium uppercase tracking-wider text-gray-900 dark:text-white">
                {{ groupLabel(group) }}
              </h3>
              <p class="text-[11px] text-gray-500 dark:text-gray-400 font-normal">
                {{ groupDescription(group) }}
              </p>
            </div>
          </div>
          <button @click="resetGroup(group)" :disabled="saving"
            class="text-[10px] font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400 hover:text-red-600 dark:hover:text-red-400 transition-colors cursor-pointer disabled:opacity-50 shrink-0">
            Reset to defaults
          </button>
        </div>

        <!-- Fields -->
        <div class="divide-y divide-gray-100 dark:divide-[#3F4F43]">
          <div v-for="(config, key) in fields" :key="key"
            class="p-5 grid grid-cols-1 sm:grid-cols-3 gap-3 sm:gap-4 items-start">

            <!-- Label -->
            <div class="sm:col-span-1 pt-1">
              <label class="block text-xs font-medium text-gray-900 dark:text-white">
                {{ config.label }}
              </label>
              <p class="text-[10px] text-gray-400 dark:text-gray-500 mt-0.5 font-mono truncate">
                {{ key }}
              </p>
            </div>

            <!-- Input -->
            <div class="sm:col-span-2">
              <!-- Boolean toggle -->
              <template v-if="config.type === 'boolean'">
                <label class="flex items-center gap-3 cursor-pointer">
                  <button type="button" @click="toggleBoolean(key)" :class="isTruthy(key)
                    ? 'bg-[#004d08] dark:bg-emerald-500'
                    : 'bg-gray-200 dark:bg-[#3F4F43]'"
                    class="relative inline-flex h-6 w-11 shrink-0 rounded-full transition-colors cursor-pointer">
                    <span :class="isTruthy(key) ? 'translate-x-5' : 'translate-x-0.5'"
                      class="inline-block h-5 w-5 mt-0.5 transform rounded-full bg-white shadow-sm transition-transform"></span>
                  </button>
                  <span class="text-xs text-gray-600 dark:text-gray-300">
                    {{ isTruthy(key) ? 'Enabled' : 'Disabled' }}
                  </span>
                </label>
              </template>

              <!-- Number -->
              <template v-else-if="config.type === 'number'">
                <input v-model="values[key]" type="number"
                  class="w-full sm:max-w-xs px-3.5 py-2.5 text-xs bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white rounded-xl focus:ring-2 focus:ring-[#004d08] focus:outline-none font-normal" />
              </template>

              <!-- Email -->
              <template v-else-if="config.type === 'email'">
                <input v-model="values[key]" type="email"
                  class="w-full px-3.5 py-2.5 text-xs bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white rounded-xl focus:ring-2 focus:ring-[#004d08] focus:outline-none font-normal" />
              </template>

              <!-- Text (default) -->
              <template v-else>
                <input v-model="values[key]" type="text"
                  class="w-full px-3.5 py-2.5 text-xs bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white rounded-xl focus:ring-2 focus:ring-[#004d08] focus:outline-none font-normal" />
              </template>
            </div>
          </div>
        </div>
      </div>

      <!-- Floating unsaved-changes bar -->
      <Teleport to="body">
        <div v-if="hasChanges && !saving" class="fixed bottom-6 left-1/2 -translate-x-1/2 z-50 pointer-events-none">
          <div
            class="pointer-events-auto inline-flex items-center gap-3 bg-[#1a1a1a] text-white px-4 py-2.5 rounded-2xl shadow-2xl ring-1 ring-white/10">
            <span class="text-[11px] whitespace-nowrap">You have unsaved changes</span>
            <button type="button" @click="discardChanges"
              class="text-[11px] text-gray-400 hover:text-white transition-colors cursor-pointer">
              Discard
            </button>
            <button type="button" @click="save" :disabled="saving"
              class="text-[11px] font-medium uppercase tracking-wider bg-amber-400 hover:bg-amber-300 text-[#004d08] px-3 py-1.5 rounded-lg transition-colors cursor-pointer disabled:opacity-50">
              Save
            </button>
          </div>
        </div>
      </Teleport>

    </div>
  </AdminLayout>
</template>

<script setup>
import { confirmAction, showError } from '@/Pages/useSweetAlert'
import { ref, reactive, computed } from 'vue'
import axios from 'axios'
import AdminLayout from '@/Layouts/AdminLayout.vue'

const props = defineProps({
  schema: { type: Object, default: () => ({}) },
  values: { type: Object, default: () => ({}) },
})

/* Local editable copy — initialized from the Inertia props */
const values = reactive({ ...props.values })
const originalValues = ref({ ...props.values })

const saving = ref(false)
const generalError = ref('')
const successMessage = ref('')
let successTimer = null

/* Detect unsaved changes */
const hasChanges = computed(() =>
  Object.keys(values).some(key => String(values[key] ?? '') !== String(originalValues.value[key] ?? ''))
)

/* Save all values */
const save = async () => {
  saving.value = true
  generalError.value = ''
  successMessage.value = ''

  try {
    const { data } = await axios.put('/admin/settings', { settings: values })
    originalValues.value = { ...values }
    successMessage.value = data.message || 'Settings saved.'
    if (successTimer) clearTimeout(successTimer)
    successTimer = setTimeout(() => { successMessage.value = '' }, 4000)
  } catch (e) {
    generalError.value = e.response?.data?.message || 'Failed to save settings.'
  } finally {
    saving.value = false
  }
}

/* Reset one group */
const resetGroup = async (group) => {
  if (!await confirmAction(`Reset all settings in "${groupLabel(group)}" to their defaults?`)) return
  saving.value = true
  generalError.value = ''
  try {
    await axios.post('/admin/settings/reset', { group })
    window.location.reload()
  } catch (e) {
    generalError.value = e.response?.data?.message || 'Failed to reset settings.'
    saving.value = false
  }
}

/* Reset every group */
const resetAll = async () => {
  if (!await confirmAction('Reset ALL settings to their defaults? This cannot be undone.')) return
  saving.value = true
  generalError.value = ''
  try {
    await Promise.all(['school', 'academic', 'system'].map(g =>
      axios.post('/admin/settings/reset', { group: g })
    ))
    window.location.reload()
  } catch (e) {
    generalError.value = e.response?.data?.message || 'Failed to reset settings.'
    saving.value = false
  }
}

/* Discard local edits */
const discardChanges = () => {
  Object.keys(values).forEach(key => { values[key] = originalValues.value[key] })
}

/* Boolean helpers */
const isTruthy = (key) => {
  const v = values[key]
  return v === '1' || v === 1 || v === true
}
const toggleBoolean = (key) => {
  values[key] = isTruthy(key) ? '0' : '1'
}

/* Human-readable labels for groups */
const groupLabel = (group) => ({
  school: 'School Information',
  academic: 'Academic Defaults',
  system: 'System Behavior',
}[group] || group)

const groupDescription = (group) => ({
  school: 'Public-facing school details used across the site',
  academic: 'Default academic rules for grading and class management',
  system: 'Limits, auto-hide windows, and platform-wide toggles',
}[group] || '')

const groupIcon = (group) => ({
  school: 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5m0 0h4m-4 0V11m0 0h4m-4 0H9m4 0v10',
  academic: 'M12 14l9-5-9-5-9 5 9 5z M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0112 20.055a11.952 11.952 0 01-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z',
  system: 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0z',
}[group] || 'M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4')
</script>