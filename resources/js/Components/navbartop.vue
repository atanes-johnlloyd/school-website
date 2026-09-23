<template>
  <header class="flex flex-col-reverse lg:flex-row lg:items-center justify-between px-6 md:px-10 py-5 bg-[#F9F7F1] dark:bg-[#232D26] sticky top-0 z-30 gap-4 lg:gap-8 transition-colors duration-300">
    
    <!-- Search Bar -->
    <div class="relative w-full max-w-2xl">
      <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-500 dark:text-gray-400">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
        </svg>
      </span>
      <input 
        type="text" 
        :placeholder="searchPlaceholder" 
        class="w-full pl-12 pr-4 py-3 rounded-full bg-white dark:bg-[#2D3A31] border border-gray-200 dark:border-[#3F4F43] focus:outline-none focus:ring-2 focus:ring-[#006907] text-sm text-gray-700 dark:text-slate-200 shadow-sm transition-colors" 
      />
    </div>

    <!-- Right Controls & Profile -->
    <div class="flex items-center justify-end gap-5 md:gap-6 shrink-0 relative">
      
      <!-- Font Size Toggle (Self-contained in Navbar) -->
      <div class="hidden md:flex items-center bg-[#EAE7DF] dark:bg-[#3F4F43] rounded-full p-1 gap-1 transition-colors">
        <button @click="changeFontSize('sm')" :class="fontSizeMode === 'sm' ? 'bg-white dark:bg-[#2D3A31] text-gray-900 dark:text-white shadow-sm' : 'text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white'" class="px-3 py-1 rounded-full text-xs font-bold transition-colors">A-</button>
        <button @click="changeFontSize('base')" :class="fontSizeMode === 'base' ? 'bg-white dark:bg-[#2D3A31] text-gray-900 dark:text-white shadow-sm' : 'text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white'" class="px-3 py-1 rounded-full text-xs font-black transition-colors">A</button>
        <button @click="changeFontSize('lg')" :class="fontSizeMode === 'lg' ? 'bg-white dark:bg-[#2D3A31] text-gray-900 dark:text-white shadow-sm' : 'text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white'" class="px-3 py-1 rounded-full text-xs font-bold transition-colors">A+</button>
      </div>

      <!-- Theme Toggle -->
      <button @click="toggleDarkMode" class="text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white transition-colors">
        <svg v-if="!isDark" class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
          <path d="M12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22ZM12 20V4C16.4183 4 20 7.58172 20 12C20 16.4183 16.4183 20 12 20Z"/>
        </svg>
        <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
           <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
        </svg>
      </button>

      <!-- Notifications Bell & Dropdown -->
      <div class="relative">
        <button @click="toggleNotifications" class="relative text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white transition-colors">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
          </svg>
          <span class="absolute top-0 right-0.5 block w-2 h-2 rounded-full bg-red-600 ring-2 ring-[#F9F7F1] dark:ring-[#232D26]"></span>
        </button>
        
        <div v-if="showNotifications" class="absolute right-0 mt-3 w-72 bg-white dark:bg-[#2D3A31] rounded-xl shadow-xl border border-gray-100 dark:border-[#3F4F43] overflow-hidden z-50">
          <div class="p-3 border-b border-gray-100 dark:border-[#3F4F43] bg-gray-50 dark:bg-[#232D26]">
            <h4 class="text-sm font-bold text-gray-800 dark:text-white">Notifications</h4>
          </div>
          <div class="p-4 text-center">
            <p class="text-sm text-gray-500 dark:text-gray-400">No new notifications.</p>
          </div>
        </div>
      </div>

      <!-- Profile & Dropdown -->
      <div class="relative flex items-center gap-3 pl-2 sm:pl-4 border-l border-gray-300 dark:border-[#3F4F43]">
        <button @click="toggleProfile" class="flex items-center gap-3 focus:outline-none">
          <img src="https://ui-avatars.com/api/?name=Maria+Santos&background=random" alt="Maria Santos" class="w-10 h-10 md:w-11 md:h-11 rounded-full border-2 border-[#F9C20C] object-cover cursor-pointer" />
          <div class="hidden sm:block text-left">
            <p class="text-sm font-bold text-gray-900 dark:text-slate-100 leading-none">Maria Santos, LPT</p>
            <p class="text-[11px] font-bold text-[#006907] dark:text-[#86EFAC] mt-1 tracking-wide">SHS Faculty • STEM</p>
          </div>
        </button>

        <div v-if="showProfile" class="absolute right-0 top-12 mt-2 w-48 bg-white dark:bg-[#2D3A31] rounded-xl shadow-xl border border-gray-100 dark:border-[#3F4F43] overflow-hidden z-50">
          <ul class="py-1">
            <li>
              <a href="#" class="block px-4 py-2 text-sm text-gray-700 dark:text-slate-200 hover:bg-gray-100 dark:hover:bg-[#3F4F43]">My Profile</a>
            </li>
            <li>
              <a href="#" class="block px-4 py-2 text-sm text-gray-700 dark:text-slate-200 hover:bg-gray-100 dark:hover:bg-[#3F4F43]">Account Settings</a>
            </li>
            <li class="border-t border-gray-100 dark:border-[#3F4F43]">
              <button @click="handleLogout" class="w-full text-left block px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-gray-100 dark:hover:bg-[#3F4F43]">
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
import { ref, onMounted } from 'vue'
import { router } from '@inertiajs/vue3'

defineProps({
  searchPlaceholder: {
    type: String,
    default: 'Search learners (LRN), strand records, advisories..'
  }
})

const emit = defineEmits(['font-size-changed'])

const fontSizeMode = ref('base')
const changeFontSize = (size) => {
  fontSizeMode.value = size
  emit('font-size-changed', size)
}

const showNotifications = ref(false)
const showProfile = ref(false)
const toggleNotifications = () => { showNotifications.value = !showNotifications.value; showProfile.value = false; }
const toggleProfile = () => { showProfile.value = !showProfile.value; showNotifications.value = false; }
const closeDropdowns = () => { showNotifications.value = false; showProfile.value = false; }

const handleLogout = () => {
  router.post(route('logout'), {}, {
    onSuccess: () => {
      router.visit('/')
    }
  })
}

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