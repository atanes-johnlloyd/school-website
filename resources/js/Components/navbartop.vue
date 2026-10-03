<template>
  <header class="flex flex-col md:flex-row md:items-center justify-between px-4 sm:px-6 md:px-10 py-3.5 sm:py-5 bg-[#F9F7F1] dark:bg-[#232D26] sticky top-0 z-30 gap-3 md:gap-8 transition-colors duration-300 border-b md:border-b-0 border-gray-200/60 dark:border-[#3F4F43]">

    <div class="flex items-center justify-between w-full md:w-auto gap-3">
      <button @click="$emit('open-sidebar')"
        class="md:hidden p-2.5 rounded-xl bg-white dark:bg-[#2D3A31] text-gray-800 dark:text-white border border-gray-200 dark:border-[#3F4F43] shadow-sm hover:bg-gray-50 dark:hover:bg-[#3F4F43] transition-colors shrink-0">
        <Icon icon="menu" size="lg" />
      </button>

      <div class="relative w-full max-w-2xl">
        <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 sm:pl-4 text-gray-500 dark:text-gray-400">
          <Icon icon="search" size="md" />
        </span>
        <input type="text" :placeholder="searchPlaceholder"
          class="w-full pl-10 sm:pl-12 pr-4 py-2.5 sm:py-3 rounded-full bg-white dark:bg-[#2D3A31] border border-gray-200 dark:border-[#3F4F43] focus:outline-none focus:ring-2 focus:ring-[#006907] text-xs sm:text-sm text-gray-700 dark:text-slate-200 shadow-sm" />
      </div>
    </div>

    <div class="flex items-center justify-end gap-2 sm:gap-4 md:gap-5 shrink-0 relative">

      <!-- Font size -->
      <div class="flex items-center bg-[#EAE7DF] dark:bg-[#3F4F43] rounded-full p-1 gap-1">
        <button @click="decreaseFont" class="px-2 sm:px-2.5 py-1 rounded-full text-[11px] sm:text-xs font-bold text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white">A-</button>
        <button @click="resetFont"
          :class="fontSizeMode === 'base' ? 'bg-white dark:bg-[#2D3A31] text-gray-900 dark:text-white shadow-sm' : 'text-gray-600 dark:text-gray-300'"
          class="px-2 sm:px-2.5 py-1 rounded-full text-[11px] sm:text-xs font-black">A</button>
        <button @click="increaseFont" class="px-2 sm:px-2.5 py-1 rounded-full text-[11px] sm:text-xs font-bold text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white">A+</button>
      </div>

      <!-- Theme toggle -->
      <button @click="toggleDarkMode"
        class="text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white p-1.5 rounded-full hover:bg-gray-100 dark:hover:bg-[#3F4F43] transition-colors">
        <svg v-if="!isDark" class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
          <path d="M12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22ZM12 20V4C16.4183 4 20 7.58172 20 12C20 16.4183 16.4183 20 12 20Z"/>
        </svg>
        <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
        </svg>
      </button>

      <!-- Notifications -->
      <div class="relative">
        <button @click="toggleNotifications"
          class="relative text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white p-1.5 rounded-full hover:bg-gray-100 dark:hover:bg-[#3F4F43] transition-colors">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
          </svg>
          <span v-if="notificationCount > 0"
            class="absolute top-1 right-1 w-2 h-2 rounded-full bg-red-600 ring-2 ring-[#F9F7F1] dark:ring-[#232D26]"></span>
        </button>

        <div v-if="showNotifications"
          class="absolute right-0 mt-3 w-64 sm:w-72 bg-white dark:bg-[#2D3A31] rounded-xl shadow-xl border border-gray-100 dark:border-[#3F4F43] overflow-hidden z-50">
          <div class="p-3 border-b border-gray-100 dark:border-[#3F4F43] bg-gray-50 dark:bg-[#232D26] flex items-center justify-between">
            <h4 class="text-xs sm:text-sm font-bold text-gray-800 dark:text-white">Notifications</h4>
            <span v-if="notificationCount > 0" class="text-[10px] font-bold text-white bg-red-600 px-2 py-0.5 rounded-full">{{ notificationCount }}</span>
          </div>
          <div class="p-6 text-center">
            <p class="text-xs text-gray-500 dark:text-gray-400">No new notifications.</p>
          </div>
        </div>
      </div>

      <!-- Profile -->
      <div class="relative flex items-center gap-2 sm:gap-3 pl-2 sm:pl-4 border-l border-gray-300 dark:border-[#3F4F43]">
        <button @click="toggleProfile" class="flex items-center gap-2 sm:gap-3 focus:outline-none">
          <span v-if="user?.avatar_url" class="w-8 h-8 sm:w-10 sm:h-10 rounded-full border-2 border-[#F9C20C] overflow-hidden shrink-0">
            <img :src="user.avatar_url" :alt="user.name" class="w-full h-full object-cover" />
          </span>
          <span v-else
            class="w-8 h-8 sm:w-10 sm:h-10 rounded-full border-2 border-[#F9C20C] bg-[#005506] text-white text-xs sm:text-sm font-bold flex items-center justify-center shrink-0">
            {{ initials }}
          </span>
          <div class="hidden sm:block text-left">
            <p class="text-xs sm:text-sm font-bold text-gray-900 dark:text-slate-100 leading-none truncate max-w-[140px]">{{ user?.name || 'User' }}</p>
            <p class="text-[10px] sm:text-[11px] font-bold text-[#006907] dark:text-[#86EFAC] mt-1 tracking-wide uppercase">{{ roleLabel }}</p>
          </div>
        </button>

        <div v-if="showProfile"
          class="absolute right-0 top-12 mt-2 w-48 bg-white dark:bg-[#2D3A31] rounded-xl shadow-xl border border-gray-100 dark:border-[#3F4F43] overflow-hidden z-50">
          <ul class="py-1">
            <li>
              <Link :href="route('profile.edit')" @click="showProfile = false"
                class="block px-4 py-2 text-xs sm:text-sm text-gray-700 dark:text-slate-200 hover:bg-gray-100 dark:hover:bg-[#3F4F43]">
                My Profile
              </Link>
            </li>
            <li class="border-t border-gray-100 dark:border-[#3F4F43]">
              <button @click="handleLogout"
                class="w-full text-left block px-4 py-2 text-xs sm:text-sm text-red-600 dark:text-red-400 hover:bg-gray-100 dark:hover:bg-[#3F4F43] font-bold">
                Sign Out
              </button>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </header>

  <div v-if="showNotifications || showProfile" @click="closeDropdowns" class="fixed inset-0 z-20"></div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { Link, usePage, router } from '@inertiajs/vue3'
import Icon from '@/Components/Icon.vue'

defineProps({
  searchPlaceholder: { type: String, default: 'Search...' },
})

const emit = defineEmits(['font-size-changed', 'open-sidebar'])

const page = usePage()
const user = computed(() => page.props.auth?.user || {})
const roles = computed(() => page.props.auth?.roles || [])
const unreadContactCount = computed(() => page.props.unreadContactCount ?? 0)

const isAdmin = computed(() => roles.value.includes('admin'))
const isTeacher = computed(() => roles.value.includes('teacher'))
const isStudent = computed(() => roles.value.includes('student'))

const roleLabel = computed(() => {
  if (isAdmin.value) return user.value?.adminPosition?.name || 'Administrator'
  if (isTeacher.value) return 'Teacher'
  if (isStudent.value) return 'Student'
  return 'User'
})

const initials = computed(() => {
  const name = user.value?.name || 'U'
  const parts = name.trim().split(/\s+/)
  if (parts.length >= 2) return (parts[0][0] + parts[parts.length - 1][0]).toUpperCase()
  return name.substring(0, 2).toUpperCase()
})

const notificationCount = computed(() => isAdmin.value ? unreadContactCount.value : 0)

// --- DIRECT DOM FONT SCALING ---
const fontSizeMode = ref('base')

const applyFontScale = (mode) => {
  fontSizeMode.value = mode
  const root = document.documentElement

  // Remove existing scale classes
  root.classList.remove('font-scale-sm', 'font-scale-normal', 'font-scale-lg', 'font-scale-xl')

  // Map mode to app.css class
  const classMap = {
    'sm': 'font-scale-sm',
    'base': 'font-scale-normal',
    'lg': 'font-scale-lg'
  }

  root.classList.add(classMap[mode] || 'font-scale-normal')
  localStorage.setItem('user_font_scale', mode)
  emit('font-size-changed', mode)
}

const decreaseFont = () => {
  if (fontSizeMode.value === 'lg') applyFontScale('base')
  else if (fontSizeMode.value === 'base') applyFontScale('sm')
}

const increaseFont = () => {
  if (fontSizeMode.value === 'sm') applyFontScale('base')
  else if (fontSizeMode.value === 'base') applyFontScale('lg')
}

const resetFont = () => {
  applyFontScale('base')
}

// --- DROPDOWN STATE ---
const showNotifications = ref(false)
const showProfile = ref(false)
const toggleNotifications = () => { showNotifications.value = !showNotifications.value; showProfile.value = false }
const toggleProfile = () => { showProfile.value = !showProfile.value; showNotifications.value = false }
const closeDropdowns = () => { showNotifications.value = false; showProfile.value = false }

const handleLogout = () => router.post(route('logout'))

// --- DARK MODE TOGGLE (DEFAULT TO LIGHT MODE) ---
const isDark = ref(false)

const toggleDarkMode = () => {
  isDark.value = !isDark.value
  if (isDark.value) {
    document.documentElement.classList.add('dark')
    localStorage.setItem('theme', 'dark')
  } else {
    document.documentElement.classList.remove('dark')
    localStorage.setItem('theme', 'light')
  }
}

onMounted(() => {
  // Restore Font Scale Choice
  const savedScale = localStorage.getItem('user_font_scale') || 'base'
  applyFontScale(savedScale)

  // Restore Theme Choice (Only enable Dark Mode if explicitly saved)
  const savedTheme = localStorage.getItem('theme')
  if (savedTheme === 'dark') {
    isDark.value = true
    document.documentElement.classList.add('dark')
  } else {
    isDark.value = false;
    document.documentElement.classList.remove('dark')
  }
})
</script>