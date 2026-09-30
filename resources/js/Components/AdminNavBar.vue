<template>
  <header class="flex flex-row items-center justify-between px-6 md:px-10 py-4 bg-[#F9F7F1] dark:bg-[#232D26] border-b border-gray-200/80 dark:border-[#3F4F43] sticky top-0 z-30 gap-4 lg:gap-8 transition-colors duration-300 font-['Inter']">
    
    <!-- Left: Breadcrumb Navigation -->
    <div class="flex items-center gap-2 bg-white dark:bg-[#2D3A31] px-3.5 py-1.5 rounded-full border border-gray-200 dark:border-[#3F4F43] shadow-xs">
      <Link 
        :href="route('admin.dashboard')" 
        class="text-[#006907] dark:text-[#86EFAC] font-extrabold hover:text-[#004d08] transition-colors flex items-center gap-1.5 text-xs sm:text-sm"
      >
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
        </svg>
        <span>Admin Portal</span>
      </Link>
      <span class="text-gray-300 dark:text-gray-600 text-xs">/</span>
      <span class="text-gray-900 dark:text-white font-extrabold capitalize text-xs sm:text-sm tracking-wide">
        {{ currentRouteName }}
      </span>
    </div>

    <!-- Right Controls & Profile -->
    <div class="flex items-center justify-end gap-4 md:gap-6 shrink-0">
      
      <!-- Font Size Toggle -->
      <div class="hidden md:flex items-center bg-[#EAE7DF] dark:bg-[#3F4F43] rounded-full p-1 gap-1 transition-colors">
        <button 
          @click="changeFontSize('sm')" 
          :class="fontSizeMode === 'sm' ? 'bg-white dark:bg-[#2D3A31] text-gray-900 dark:text-white shadow-xs' : 'text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white'" 
          class="px-3 py-1 rounded-full text-xs font-bold transition-colors"
        >
          A-
        </button>
        <button 
          @click="changeFontSize('base')" 
          :class="fontSizeMode === 'base' ? 'bg-white dark:bg-[#2D3A31] text-gray-900 dark:text-white shadow-xs' : 'text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white'" 
          class="px-3 py-1 rounded-full text-xs font-black transition-colors"
        >
          A
        </button>
        <button 
          @click="changeFontSize('lg')" 
          :class="fontSizeMode === 'lg' ? 'bg-white dark:bg-[#2D3A31] text-gray-900 dark:text-white shadow-xs' : 'text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white'" 
          class="px-3 py-1 rounded-full text-xs font-bold transition-colors"
        >
          A+
        </button>
      </div>

      <!-- Theme Toggle -->
      <button 
        @click="toggleDarkMode" 
        class="p-2 rounded-full text-gray-700 dark:text-gray-300 hover:bg-black/5 dark:hover:bg-white/5 transition-colors"
        title="Toggle Theme"
      >
        <svg v-if="!isDark" class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
          <path d="M12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22ZM12 20V4C16.4183 4 20 7.58172 20 12C20 16.4183 16.4183 20 12 20Z"/>
        </svg>
        <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
        </svg>
      </button>

      <!-- Notifications Bell & Dropdown -->
      <div class="relative">
        <button 
          @click="toggleNotifications" 
          class="p-2 rounded-full relative text-gray-700 dark:text-gray-300 hover:bg-black/5 dark:hover:bg-white/5 transition-colors"
        >
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
          </svg>
          <span class="absolute top-1.5 right-1.5 block w-2 h-2 rounded-full bg-red-600 ring-2 ring-[#F9F7F1] dark:ring-[#232D26]"></span>
        </button>

        <!-- Fixed Notification Dropdown Positioning -->
        <div 
          v-if="showNotifications" 
          class="absolute right-0 top-full mt-2 w-72 bg-white dark:bg-[#2D3A31] rounded-2xl shadow-2xl border border-gray-100 dark:border-[#3F4F43] overflow-hidden z-50"
        >
          <div class="p-3.5 border-b border-gray-100 dark:border-[#3F4F43] bg-gray-50 dark:bg-[#232D26]">
            <h4 class="text-xs font-bold text-gray-800 dark:text-white uppercase tracking-wider">Notifications</h4>
          </div>
          <Link
            v-if="unreadContactCount > 0"
            :href="route('admin.contact-messages.index')"
            @click="closeDropdowns"
            class="flex items-center justify-between gap-3 px-4 py-3 border-b border-gray-100 dark:border-[#3F4F43] hover:bg-gray-50 dark:hover:bg-white/5 transition-colors"
          >
            <div class="flex items-center gap-3 min-w-0">
              <div class="w-8 h-8 rounded-full bg-amber-100 dark:bg-amber-950/60 flex items-center justify-center text-amber-600 dark:text-amber-400 shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
              </div>
              <div class="min-w-0">
                <p class="text-xs font-medium text-gray-900 dark:text-white truncate">
                  {{ unreadContactCount }} unread contact message{{ unreadContactCount === 1 ? '' : 's' }}
                </p>
                <p class="text-[10px] text-gray-500 dark:text-gray-400">From the public contact form</p>
              </div>
            </div>
            <svg class="w-4 h-4 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
            </svg>
          </Link>

          <!-- existing empty state -->
          <div v-if="unreadContactCount === 0" class="p-5 text-center">
            <p class="text-xs text-gray-500 dark:text-gray-400 font-medium">No new notifications.</p>
          </div>
          <div class="p-5 text-center">
            <p class="text-xs text-gray-500 dark:text-gray-400 font-medium">No new notifications.</p>
          </div>
        </div>
      </div>

      <!-- Profile Button & Fixed Dropdown -->
      <div class="relative pl-2 sm:pl-4 border-l border-gray-300 dark:border-[#3F4F43]">
        <button @click="toggleProfile" class="flex items-center gap-3 focus:outline-none group">
          <span class="w-10 h-10 md:w-11 md:h-11 rounded-full border-2 border-[#F9C20C] bg-[#006907] text-white text-xs font-black flex items-center justify-center cursor-pointer shadow-xs group-hover:scale-105 transition-transform">
            {{ userInitials }}
          </span>
          <div class="hidden sm:block text-left">
            <p class="text-xs sm:text-sm font-extrabold text-gray-900 dark:text-slate-100 leading-none">
              {{ currentUser?.name || 'Administrator' }}
            </p>
            <p class="text-[10px] font-extrabold text-[#006907] dark:text-[#86EFAC] mt-1 tracking-wider uppercase">
              {{ currentUser?.position?.name || 'System Admin' }}
            </p>
          </div>
        </button>

        <!-- Fixed Profile Dropdown Positioning -->
        <div 
          v-if="showProfile" 
          class="absolute right-0 top-full mt-2 w-48 sm:w-52 bg-white dark:bg-[#2D3A31] rounded-2xl shadow-2xl border border-gray-100 dark:border-[#3F4F43] overflow-hidden z-50"
        >
          <ul class="py-1 text-xs font-semibold">
            <li>
              <Link :href="route('profile.edit')" class="block px-4 py-2.5 text-gray-700 dark:text-slate-200 hover:bg-[#006907]/10 dark:hover:bg-[#3F4F43] hover:text-[#006907] dark:hover:text-white transition-colors">
                My Profile
              </Link>
            </li>
            <li class="border-t border-gray-100 dark:border-[#3F4F43]">
              <button @click="handleLogout" class="w-full text-left block px-4 py-2.5 text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-950/30 transition-colors font-bold">
                Sign Out
              </button>
            </li>
          </ul>
        </div>
      </div>

    </div>
  </header>

  <!-- Overlay to close dropdowns -->
  <div v-if="showNotifications || showProfile" @click="closeDropdowns" class="fixed inset-0 z-20"></div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { Link, usePage, router } from '@inertiajs/vue3'

const emit = defineEmits(['font-size-changed'])

const page = usePage()
const unreadContactCount = computed(() => page.props.unreadContactCount ?? 0)
const currentUser = computed(() => page.props.auth?.user || {})

// Compute User Initials
const userInitials = computed(() => {
  const name = currentUser.value?.name || 'Admin'
  const parts = name.trim().split(' ')
  if (parts.length >= 2) {
    return (parts[0][0] + parts[parts.length - 1][0]).toUpperCase()
  }
  return name.substring(0, 2).toUpperCase()
})

// Dynamic Route Name for Breadcrumb
const currentRouteName = computed(() => {
  try {
    const routeName = route().current()
    if (!routeName) return 'Dashboard'
    
    const parts = routeName.split('.')
    if (parts.length > 1) {
      let mainPart = parts[1]
      if (mainPart === 'dashboard') return 'Dashboard'
      return mainPart.replace(/-/g, ' ').replace(/\b\w/g, l => l.toUpperCase())
    }
    return routeName.replace(/-/g, ' ')
  } catch (e) {
    return 'Dashboard'
  }
})

// Font Size Controls
const fontSizeMode = ref('base')
const changeFontSize = (size) => {
  fontSizeMode.value = size
  emit('font-size-changed', size)
}

// Dropdown Toggles
const showNotifications = ref(false)
const showProfile = ref(false)

const toggleNotifications = () => {
  showNotifications.value = !showNotifications.value
  showProfile.value = false
}

const toggleProfile = () => {
  showProfile.value = !showProfile.value
  showNotifications.value = false
}

const closeDropdowns = () => {
  showNotifications.value = false
  showProfile.value = false
}

// Logout Handlers
const handleLogout = () => {
  router.post(route('logout'), {}, {
    onSuccess: () => {
      router.visit('/')
    }
  })
}

// Theme Controls
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
  if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
    isDark.value = true
    document.documentElement.classList.add('dark')
  }
})
</script>