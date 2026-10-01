<script setup>
import { Link, usePage } from '@inertiajs/vue3'
import { ref, computed } from 'vue'

import sshsLogo from '@/../assets/img/SSHS-logo.png'

const isMobileMenuOpen = ref(false)
const page = usePage()

// Check active route dynamically
const currentRoute = computed(() => page.url)

function toggleMobileMenu() {
  isMobileMenuOpen.value = !isMobileMenuOpen.value
}

function scrollToTop() {
  window.scrollTo({
    top: 0,
    behavior: 'smooth'
  })
}
</script>

<template>
  <header class="absolute top-0 left-0 right-0 z-30 w-full h-20 md:h-24 bg-transparent font-['Inter']">
    <!-- Navigation Bar Content -->
    <nav class="relative z-20 h-full flex items-center justify-between px-6 sm:px-12 md:px-16 lg:px-24 text-white">

      <!-- Logo & Brand Title -->
      <a href="/" @click.prevent="scrollToTop" class="flex items-center gap-3 md:gap-4 cursor-pointer group">
        <img :src="sshsLogo" alt="SSHS Logo"
          class="w-10 h-10 md:w-12 md:h-12 rounded-full object-cover border border-white/20 shadow-sm group-hover:scale-105 transition-transform" />
        <span class="font-bold text-xl md:text-2xl tracking-wide uppercase select-none">
          SALAWAG LMS
        </span>
      </a>

      <!-- Desktop Links -->
      <div class="hidden md:flex items-center gap-6 lg:gap-8 text-sm sm:text-base font-semibold">

        <!-- Home Link (Highlighted Active Green when on Home) -->
        <Link href="/" :class="[
          currentRoute === '/' || currentRoute === '/home'
            ? 'text-[#87EA8E] font-bold border-b-2 border-[#87EA8E] pb-0.5'
            : 'text-white hover:text-[#87EA8E] transition-colors duration-200'
        ]">
          Home
        </Link>

        <!-- Academics Link (Highlighted Active Green when on Academics page) -->
        <Link href="/academics" :class="[
          currentRoute.startsWith('/academics')
            ? 'text-[#87EA8E] font-bold border-b-2 border-[#87EA8E] pb-0.5'
            : 'text-white hover:text-[#87EA8E] transition-colors duration-200'
        ]">
          Academics
        </Link>

        <!-- Desktop Link -->
        <Link href="/admission" :class="[
          currentRoute.startsWith('/admission')
            ? 'text-[#87EA8E] font-bold border-b-2 border-[#87EA8E] pb-0.5'
            : 'text-white hover:text-[#87EA8E] transition-colors duration-200'
        ]">
          Admissions
        </Link>

        <a href="#about" class="text-white hover:text-[#87EA8E] transition-colors duration-200">
          About Us
        </a>

        <a href="#faculty" class="text-white hover:text-[#87EA8E] transition-colors duration-200">
          Faculty & Staff
        </a>

        <!-- Divider Line -->
        <div class="h-5 w-[1px] bg-white/40"></div>

        <!-- Log in Link -->
        <Link :href="route('login')"
          class="bg-amber-400 hover:bg-amber-300 text-slate-950 font-bold px-5 py-2 rounded-full transition-all duration-200 shadow-md hover:scale-105 active:scale-95">
          Log in
        </Link>
      </div>

      <!-- Mobile Hamburger Button -->
      <button @click="toggleMobileMenu" class="md:hidden flex flex-col justify-between w-6 h-4 focus:outline-none"
        aria-label="Toggle menu">
        <span class="w-6 h-0.5 bg-[#87EA8E] transition-all"></span>
        <span class="w-6 h-0.5 bg-[#87EA8E] transition-all"></span>
        <span class="w-6 h-0.5 bg-[#87EA8E] transition-all"></span>
      </button>
    </nav>

    <!-- Mobile Dropdown Menu -->
    <div v-if="isMobileMenuOpen"
      class="md:hidden absolute top-full left-0 right-0 bg-[#004d08]/95 backdrop-blur-md px-6 py-5 flex flex-col gap-4 text-white border-t border-white/10 shadow-xl">
      <Link href="/" @click="isMobileMenuOpen = false"
        :class="[currentRoute === '/' ? 'text-[#87EA8E] font-bold' : 'hover:text-[#87EA8E]']">
        Home
      </Link>
      <Link href="/academics" @click="isMobileMenuOpen = false"
        :class="[currentRoute.startsWith('/academics') ? 'text-[#87EA8E] font-bold' : 'hover:text-[#87EA8E]']">
        Academics
      </Link>
      <Link href="/admission" @click="isMobileMenuOpen = false"
        :class="[currentRoute.startsWith('/admission') ? 'text-[#87EA8E] font-bold' : 'hover:text-[#87EA8E]']">
        Admissions
      </Link>
      <a href="#about" @click="isMobileMenuOpen = false" class="hover:text-[#87EA8E]">
        About Us
      </a>
      <a href="#faculty" @click="isMobileMenuOpen = false" class="hover:text-[#87EA8E]">
        Faculty & Staff
      </a>
      <Link :href="route('login')" @click="isMobileMenuOpen = false"
        class="inline-block text-center bg-amber-400 text-slate-950 font-bold py-2.5 rounded-full mt-2">
        Log in
      </Link>
    </div>
  </header>
</template>