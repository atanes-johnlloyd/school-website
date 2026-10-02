<script setup>
import { Link, router, usePage } from '@inertiajs/vue3'
import { ref, computed, onMounted, onUnmounted } from 'vue'

import sshsLogo from '@/../assets/img/SSHS-logo.png'

const isMobileMenuOpen = ref(false)
const isUserMenuOpen   = ref(false)
const page = usePage()

const currentRoute = computed(() => page.url)

/* -------- Auth (same source as navbartop.vue) -------- */
const user  = computed(() => page.props.auth?.user  || null)
const roles = computed(() => page.props.auth?.roles || [])

const isAdmin   = computed(() => roles.value.includes('admin'))
const isTeacher = computed(() => roles.value.includes('teacher'))
const isStudent = computed(() => roles.value.includes('student'))

const roleLabel = computed(() => {
  if (!user.value) return ''
  if (isAdmin.value)   return user.value.adminPosition?.name || 'Administrator'
  if (isTeacher.value) return 'Teacher'
  if (isStudent.value) return 'Student'
  return 'User'
})

// Same "first + last initial" logic as navbartop.vue
const initials = computed(() => {
  const name = user.value?.name || 'U'
  const parts = name.trim().split(/\s+/)
  if (parts.length >= 2) return (parts[0][0] + parts[parts.length - 1][0]).toUpperCase()
  return name.substring(0, 2).toUpperCase()
})

// Where the "Dashboard" link should send each role.
// Adjust to match your actual route names if different.
const dashboardRoute = computed(() => {
  if (isAdmin.value)   return 'admin.dashboard'
  if (isTeacher.value) return 'teacher.dashboard'
  if (isStudent.value) return 'student.dashboard'
  return 'dashboard'
})

/* -------- Menu toggles -------- */
const toggleMobileMenu = () => { isMobileMenuOpen.value = !isMobileMenuOpen.value }
const toggleUserMenu   = () => { isUserMenuOpen.value   = !isUserMenuOpen.value }
const closeUserMenu    = () => { isUserMenuOpen.value   = false }

const scrollToTop = () => window.scrollTo({ top: 0, behavior: 'smooth' })

const handleLogout = () => {
  isUserMenuOpen.value   = false
  isMobileMenuOpen.value = false
  router.post(route('logout'))
}

/* -------- Close user menu on outside click -------- */
const userMenuRef = ref(null)
function handleClickOutside(e) {
  if (userMenuRef.value && !userMenuRef.value.contains(e.target)) {
    isUserMenuOpen.value = false
  }
}
onMounted(() => document.addEventListener('click', handleClickOutside))
onUnmounted(() => document.removeEventListener('click', handleClickOutside))

/* -------- Active-route helpers -------- */
const isHome      = computed(() => currentRoute.value === '/' || currentRoute.value === '/home')
const isAcademics = computed(() => currentRoute.value.startsWith('/academics'))
const isAdmission = computed(() => currentRoute.value.startsWith('/admission'))
const isAbout     = computed(() => page.component === 'Site/AboutUs/AboutUs')
const isFaculty   = computed(() => page.component === 'Site/FacultyStaff/FacultyStaff')
</script>

<template>
  <header class="absolute top-0 left-0 right-0 z-30 w-full h-20 md:h-24 bg-transparent font-['Inter']">
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

        <Link href="/" :class="[
          isHome
            ? 'text-[#87EA8E] font-bold border-b-2 border-[#87EA8E] pb-0.5'
            : 'text-white hover:text-[#87EA8E] transition-colors duration-200'
        ]">Home</Link>

        <Link href="/academics" :class="[
          isAcademics
            ? 'text-[#87EA8E] font-bold border-b-2 border-[#87EA8E] pb-0.5'
            : 'text-white hover:text-[#87EA8E] transition-colors duration-200'
        ]">Academics</Link>

        <Link href="/admission" :class="[
          isAdmission
            ? 'text-[#87EA8E] font-bold border-b-2 border-[#87EA8E] pb-0.5'
            : 'text-white hover:text-[#87EA8E] transition-colors duration-200'
        ]">Admissions</Link>

        <Link :href="route('about-us')"
          class="transition-colors"
          :class="isAbout ? 'text-amber-400 font-bold' : 'text-white hover:text-amber-400'">
          About Us
        </Link>

        <Link :href="route('faculty-staff')"
          class="transition-colors"
          :class="isFaculty ? 'text-amber-400 font-bold' : 'text-white hover:text-amber-400'">
          Faculty & Staff
        </Link>

        <div class="h-5 w-[1px] bg-white/40"></div>

        <!-- ============ GUEST ============ -->
        <Link v-if="!user" :href="route('login')"
          class="bg-amber-400 hover:bg-amber-300 text-slate-950 font-bold px-5 py-2 rounded-full transition-all duration-200 shadow-md hover:scale-105 active:scale-95">
          Log in
        </Link>

        <!-- ============ AUTHENTICATED — same pattern as navbartop.vue ============ -->
        <div v-else ref="userMenuRef" class="relative">
          <button type="button" @click.stop="toggleUserMenu"
            class="flex items-center gap-2.5 bg-white/10 hover:bg-white/20 border border-white/25 pl-1.5 pr-4 py-1.5 rounded-full transition-all duration-200 backdrop-blur-sm"
            aria-haspopup="menu"
            :aria-expanded="isUserMenuOpen">

            <!-- Avatar: image if present, else initials -->
            <span v-if="user.avatar_url"
              class="w-8 h-8 rounded-full overflow-hidden border-2 border-amber-400 shrink-0">
              <img :src="user.avatar_url" :alt="user.name" class="w-full h-full object-cover" />
            </span>
            <span v-else
              class="w-8 h-8 rounded-full bg-amber-400 text-slate-950 font-black text-xs flex items-center justify-center border-2 border-amber-400 shrink-0 select-none">
              {{ initials }}
            </span>

            <span class="text-sm font-bold text-white max-w-[140px] truncate">
              {{ user.name }}
            </span>

            <svg class="w-3.5 h-3.5 text-white/70 transition-transform duration-200"
              :class="{ 'rotate-180': isUserMenuOpen }"
              fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
              <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
            </svg>
          </button>

          <!-- Dropdown -->
          <div v-if="isUserMenuOpen"
            @click="closeUserMenu"
            class="absolute right-0 mt-3 w-60 bg-white rounded-2xl shadow-2xl border border-slate-200 overflow-hidden z-50 animate-fade-in-up">

            <!-- Header block — mirrors navbartop.vue style -->
            <div class="px-4 py-3 border-b border-slate-100 bg-slate-50/60">
              <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Signed in as</p>
              <p class="text-sm font-bold text-slate-800 truncate mt-0.5">{{ user.name }}</p>
              <p class="text-[11px] font-semibold text-[#006907] tracking-wide uppercase mt-0.5">
                {{ roleLabel }}
              </p>
            </div>

            <Link :href="route(dashboardRoute)"
              class="block px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-emerald-50 hover:text-[#005506] transition-colors">
              Dashboard
            </Link>

            <Link :href="route('profile.edit')"
              class="block px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-emerald-50 hover:text-[#005506] transition-colors">
              My Profile
            </Link>

            <button type="button" @click="handleLogout"
              class="w-full text-left px-4 py-2.5 text-sm font-bold text-red-600 hover:bg-red-50 transition-colors border-t border-slate-100">
              Sign Out
            </button>
          </div>
        </div>
      </div>

      <!-- Mobile Hamburger -->
      <button @click="toggleMobileMenu" class="md:hidden flex flex-col justify-between w-6 h-4 focus:outline-none"
        aria-label="Toggle menu">
        <span class="w-6 h-0.5 bg-[#87EA8E] transition-all"></span>
        <span class="w-6 h-0.5 bg-[#87EA8E] transition-all"></span>
        <span class="w-6 h-0.5 bg-[#87EA8E] transition-all"></span>
      </button>
    </nav>

    <!-- Mobile Dropdown -->
    <div v-if="isMobileMenuOpen"
      class="md:hidden absolute top-full left-0 right-0 bg-[#004d08]/95 backdrop-blur-md px-6 py-5 flex flex-col gap-4 text-white border-t border-white/10 shadow-xl">

      <Link href="/" @click="isMobileMenuOpen = false"
        :class="isHome ? 'text-[#87EA8E] font-bold' : 'hover:text-[#87EA8E]'">Home</Link>

      <Link href="/academics" @click="isMobileMenuOpen = false"
        :class="isAcademics ? 'text-[#87EA8E] font-bold' : 'hover:text-[#87EA8E]'">Academics</Link>

      <Link href="/admission" @click="isMobileMenuOpen = false"
        :class="isAdmission ? 'text-[#87EA8E] font-bold' : 'hover:text-[#87EA8E]'">Admissions</Link>

      <Link :href="route('about-us')" @click="isMobileMenuOpen = false"
        :class="isAbout ? 'text-amber-400 font-bold' : 'hover:text-[#87EA8E]'">About Us</Link>

      <Link :href="route('faculty-staff')" @click="isMobileMenuOpen = false"
        :class="isFaculty ? 'text-amber-400 font-bold' : 'hover:text-[#87EA8E]'">Faculty & Staff</Link>

      <!-- ============ GUEST (mobile) ============ -->
      <Link v-if="!user" :href="route('login')" @click="isMobileMenuOpen = false"
        class="inline-block text-center bg-amber-400 text-slate-950 font-bold py-2.5 rounded-full mt-2">
        Log in
      </Link>

      <!-- ============ AUTHENTICATED (mobile) ============ -->
      <div v-else class="mt-2 border-t border-white/15 pt-4 space-y-3">
        <div class="flex items-center gap-3">
          <span v-if="user.avatar_url"
            class="w-10 h-10 rounded-full overflow-hidden border-2 border-amber-400 shrink-0">
            <img :src="user.avatar_url" :alt="user.name" class="w-full h-full object-cover" />
          </span>
          <span v-else
            class="w-10 h-10 rounded-full bg-amber-400 text-slate-950 font-black text-sm flex items-center justify-center border-2 border-amber-400 shrink-0 select-none">
            {{ initials }}
          </span>
          <div class="min-w-0">
            <p class="text-sm font-bold truncate">{{ user.name }}</p>
            <p class="text-[11px] font-semibold text-emerald-200/80 tracking-wide uppercase truncate">
              {{ roleLabel }}
            </p>
          </div>
        </div>

        <Link :href="route(dashboardRoute)" @click="isMobileMenuOpen = false"
          class="block px-4 py-2.5 rounded-xl bg-white/10 hover:bg-white/20 font-semibold text-sm transition-colors">
          Dashboard
        </Link>

        <Link :href="route('profile.edit')" @click="isMobileMenuOpen = false"
          class="block px-4 py-2.5 rounded-xl bg-white/10 hover:bg-white/20 font-semibold text-sm transition-colors">
          My Profile
        </Link>

        <button type="button" @click="handleLogout"
          class="w-full text-center bg-red-500/90 hover:bg-red-500 text-white font-bold py-2.5 rounded-full transition-colors text-sm">
          Sign Out
        </button>
      </div>
    </div>
  </header>
</template>

<style scoped>
@keyframes fadeInUp {
  from { opacity: 0; transform: translateY(8px); }
  to   { opacity: 1; transform: translateY(0); }
}
.animate-fade-in-up { animation: fadeInUp 0.18s ease-out; }
</style>