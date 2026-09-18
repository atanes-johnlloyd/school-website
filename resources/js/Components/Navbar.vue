<script setup>
import { Link } from '@inertiajs/vue3'
import { ref } from 'vue'

// Import images directly from resources/assets/img/
import sshsLogo from '@/../assets/img/SSHS-logo.png'
import bannerImg from '@/../assets/img/desktop-home-banner.png'

const isMobileMenuOpen = ref(false)

function toggleMobileMenu() {
  isMobileMenuOpen.value = !isMobileMenuOpen.value
}
</script>

<template>
  <header class="relative w-full h-20 md:h-24 overflow-hidden shadow-md font-['Inria_Sans']">
    <!-- Background Image Layer -->
    <img 
      :src="bannerImg" 
      alt="Campus Banner" 
      class="absolute inset-0 w-full h-full object-cover z-0"
    />

    <!-- Green Overlay (Color: #005506 with 81% Opacity via Tailwind) -->
    <div class="absolute inset-0 bg-[#005506] bg-opacity-[0.81] z-10 pointer-events-none"></div>

    <!-- Navigation Bar Content -->
    <nav class="relative z-20 h-full flex items-center justify-between px-6 md:px-16 text-white">
      
      <!-- Logo & Brand Title -->
      <div class="flex items-center gap-3 md:gap-4">
        <img 
          :src="sshsLogo" 
          alt="SSHS Logo" 
          class="w-10 h-10 md:w-14 md:h-14 rounded-full object-cover border border-white/20"
        />
        <span class="font-bold text-xl md:text-2xl tracking-wide uppercase select-none">
          SALAWAG LMS
        </span>
      </div>

      <!-- Desktop Links -->
      <div class="hidden md:flex items-center gap-8 text-base font-semibold">
        <Link 
          :href="route('dashboard')" 
          class="hover:text-[#ffd51c] transition-colors duration-200"
        >
          Home
        </Link>
        
        <!-- Divider Line -->
        <div class="h-5 w-[1px] bg-white/40"></div>

        <Link 
          :href="route('login')" 
          class="hover:text-[#ffd51c] transition-colors duration-200"
        >
          Log in
        </Link>
      </div>

      <!-- Mobile Hamburger Button -->
      <button 
        @click="toggleMobileMenu" 
        class="md:hidden flex flex-col justify-between w-6 h-4 focus:outline-none"
        aria-label="Toggle menu"
      >
        <span class="w-6 h-0.5 bg-[#87EA8E] transition-all"></span>
        <span class="w-6 h-0.5 bg-[#87EA8E] transition-all"></span>
        <span class="w-6 h-0.5 bg-[#87EA8E] transition-all"></span>
      </button>
    </nav>

    <!-- Mobile Dropdown Menu -->
    <div 
      v-if="isMobileMenuOpen" 
      class="md:hidden relative z-30 bg-[#005506] px-6 py-4 flex flex-col gap-4 text-white border-t border-white/10"
    >
      <Link :href="route('dashboard')" class="hover:text-[#ffd51c]">Home</Link>
      <Link :href="route('login')" class="hover:text-[#ffd51c]">Log in</Link>
    </div>
  </header>
</template>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Inria+Sans:wght@400;700&display=swap');
</style>