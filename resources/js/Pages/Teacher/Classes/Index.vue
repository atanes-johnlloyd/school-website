<template>

  <Head title="My Classes & Section Rosters - Salawag LMS" />

  <div class="min-h-screen flex bg-[#F9F7F1] dark:bg-[#232D26] font-['Inter'] relative transition-colors duration-300">

    <!-- Sticky Sidebar Navigation -->
    <div class="sticky top-0 h-screen z-40 shrink-0 shadow-lg">
      <Sidebart />
    </div>

    <!-- Main Workspace Canvas -->
    <main :class="['flex-1 relative overflow-y-auto min-h-screen flex flex-col', `text-scale-${fontSizeMode}`]">

      <!-- TOP NAVIGATION BAR (Exact copy from Dashboard) -->
      <header
        class="flex flex-col-reverse lg:flex-row lg:items-center justify-between px-6 md:px-10 py-5 bg-[#F9F7F1] dark:bg-[#232D26] sticky top-0 z-30 gap-4 lg:gap-8 transition-colors duration-300">

        <!-- Search Bar -->
        <div class="relative w-full max-w-2xl">
          <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-500 dark:text-gray-400">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
          </span>
          <input type="text" placeholder="Search learners (LRN), strand records, advisories.."
            class="w-full pl-12 pr-4 py-3 rounded-full bg-white dark:bg-[#2D3A31] border border-gray-200 dark:border-[#3F4F43] focus:outline-none focus:ring-2 focus:ring-[#006907] text-sm text-gray-700 dark:text-slate-200 shadow-sm transition-colors" />
        </div>

        <!-- Right Controls & Profile -->
        <div class="flex items-center justify-end gap-5 md:gap-6 shrink-0 relative">

          <!-- Font Size Toggle -->
          <div
            class="hidden md:flex items-center bg-[#EAE7DF] dark:bg-[#3F4F43] rounded-full p-1 gap-1 transition-colors">
            <button @click="changeFontSize('sm')"
              :class="fontSizeMode === 'sm' ? 'bg-white dark:bg-[#2D3A31] text-gray-900 dark:text-white shadow-sm' : 'text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white'"
              class="px-3 py-1 rounded-full text-xs font-bold transition-colors">A-</button>
            <button @click="changeFontSize('base')"
              :class="fontSizeMode === 'base' ? 'bg-white dark:bg-[#2D3A31] text-gray-900 dark:text-white shadow-sm' : 'text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white'"
              class="px-3 py-1 rounded-full text-xs font-black transition-colors">A</button>
            <button @click="changeFontSize('lg')"
              :class="fontSizeMode === 'lg' ? 'bg-white dark:bg-[#2D3A31] text-gray-900 dark:text-white shadow-sm' : 'text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white'"
              class="px-3 py-1 rounded-full text-xs font-bold transition-colors">A+</button>
          </div>

          <!-- Theme Toggle -->
          <button @click="toggleDarkMode"
            class="text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white transition-colors">
            <svg v-if="!isDark" class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
              <path
                d="M12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22ZM12 20V4C16.4183 4 20 7.58172 20 12C20 16.4183 16.4183 20 12 20Z" />
            </svg>
            <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
              xmlns="http://www.w3.org/2000/svg">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z">
              </path>
            </svg>
          </button>

          <!-- Notifications Bell & Dropdown -->
          <div class="relative">
            <button @click="toggleNotifications"
              class="relative text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white transition-colors">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
                </path>
              </svg>
              <span
                class="absolute top-0 right-0.5 block w-2 h-2 rounded-full bg-red-600 ring-2 ring-[#F9F7F1] dark:ring-[#232D26]"></span>
            </button>

            <div v-if="showNotifications"
              class="absolute right-0 mt-3 w-72 bg-white dark:bg-[#2D3A31] rounded-xl shadow-xl border border-gray-100 dark:border-[#3F4F43] overflow-hidden z-50">
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
              <img src="https://ui-avatars.com/api/?name=Maria+Santos&background=random" alt="Maria Santos"
                class="w-10 h-10 md:w-11 md:h-11 rounded-full border-2 border-[#F9C20C] object-cover cursor-pointer" />
              <div class="hidden sm:block text-left">
                <p class="text-sm font-bold text-gray-900 dark:text-slate-100 leading-none">Maria Santos, LPT</p>
                <p class="text-[11px] font-bold text-[#006907] dark:text-[#86EFAC] mt-1 tracking-wide">SHS Faculty •
                  STEM</p>
              </div>
            </button>

            <div v-if="showProfile"
              class="absolute right-0 top-12 mt-2 w-48 bg-white dark:bg-[#2D3A31] rounded-xl shadow-xl border border-gray-100 dark:border-[#3F4F43] overflow-hidden z-50">
              <ul class="py-1">
                <li>
                  <a href="#"
                    class="block px-4 py-2 text-sm text-gray-700 dark:text-slate-200 hover:bg-gray-100 dark:hover:bg-[#3F4F43]">My
                    Profile</a>
                </li>
                <li>
                  <a href="#"
                    class="block px-4 py-2 text-sm text-gray-700 dark:text-slate-200 hover:bg-gray-100 dark:hover:bg-[#3F4F43]">Account
                    Settings</a>
                </li>
                <li class="border-t border-gray-100 dark:border-[#3F4F43]">
                  <a href="#"
                    class="block px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-gray-100 dark:hover:bg-[#3F4F43]">Sign
                    Out</a>
                </li>
              </ul>
            </div>
          </div>

        </div>
      </header>

      <!-- Overlay to close dropdowns -->
      <div v-if="showNotifications || showProfile" @click="closeDropdowns" class="fixed inset-0 z-20"></div>

      <!-- MAIN PAGE CONTENT -->
      <div class="relative z-10 px-6 md:px-10 pb-24 space-y-6 flex-1 mt-2">

        <!-- CLASSES HERO BANNER -->
        <div
          class="relative w-full rounded-3xl overflow-hidden shadow-lg border border-[#006907]/20 dark:border-none min-h-[220px] flex flex-col justify-center">
          <!-- Background Image properly aligned and fitted -->
          <img src="https://images.unsplash.com/photo-1523050854058-8df90110c9f1?q=80&w=2000&auto=format&fit=crop"
            alt="Classes Hub Background" class="absolute inset-0 w-full h-full object-cover z-0 object-center" />

          <!-- Animated Green Overlay -->
          <div class="absolute inset-0 bg-[#004d05] dark:bg-[#152B1C] animate-overlay z-0 mix-blend-multiply"></div>

          <!-- Inner Content Container -->
          <div class="relative z-10 p-6 sm:p-8 md:p-10 flex flex-col justify-center">

            <!-- Top Badges (DepEd LIS Synced removed) -->
            <div class="flex flex-wrap items-center gap-2.5 mb-4">
              <span
                class="bg-[#005506] text-white border border-emerald-400/30 text-[11px] md:text-xs font-bold px-3.5 py-1.5 rounded-full shadow-sm flex items-center gap-1.5">
                <span>🏛️</span> DepEd Region IV-A • SDO Dasmariñas
              </span>
              <span
                class="bg-white/20 backdrop-blur-md text-white border border-white/20 text-[11px] md:text-xs font-semibold px-3.5 py-1.5 rounded-full shadow-sm flex items-center gap-1.5">
                <span>📅</span> S.Y. 2025–2026 • 2nd Semester (Midterm)
              </span>
            </div>

            <!-- Title & Description -->
            <div class="space-y-2 max-w-3xl">
              <h2 class="text-2xl sm:text-3xl md:text-4xl font-extrabold text-white tracking-tight">
                My Classes & Section Rosters Hub
              </h2>
              <p class="text-white/90 text-xs sm:text-sm md:text-base leading-relaxed font-medium">
                Manage section registries, track DepEd Learner Reference Numbers (LRN), inspect quarterly transmutation
                metrics, and dispatch real-time parent advisories.
              </p>
            </div>

          </div>
        </div>

        <!-- FOUR-CARD METRIC GRID -->
        <div class="relative z-10 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">

          <!-- 1. TOTAL ACTIVE LEARNERS -->
          <div
            class="bg-white/90 dark:bg-[#2D3A31]/90 backdrop-blur-sm rounded-2xl p-6 shadow-sm border border-slate-200/60 dark:border-[#3F4F43] flex flex-col justify-between space-y-4">
            <div class="flex items-start justify-between">
              <span
                class="text-[15px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 max-w-[120px]">Total
                Active Learners</span>
              <div
                class="w-10 h-10 rounded-2xl bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 flex items-center justify-center shrink-0 border border-emerald-100 dark:border-emerald-900/30">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                  </path>
                </svg>
              </div>
            </div>

            <div class="space-y-2">
              <div class="flex items-baseline gap-3">
                <span class="text-3xl font-black text-slate-800 dark:text-white">134</span>
                <span
                  class="text-xs font-bold bg-[#EAF3EC] dark:bg-[#3F4F43] text-[#005506] dark:text-[#86EFAC] px-2.5 py-1 rounded-full border border-emerald-200/60 dark:border-emerald-900/40">
                  100% Verified
                </span>
              </div>
              <p class="text-xs text-slate-500 dark:text-slate-400 font-medium leading-relaxed">
                Distributed in 3 senior class sections
              </p>
            </div>
          </div>

          <!-- 2. TEACHING WORKLOAD -->
          <div
            class="bg-white/90 dark:bg-[#2D3A31]/90 backdrop-blur-sm rounded-2xl p-6 shadow-sm border border-slate-200/60 dark:border-[#3F4F43] flex flex-col justify-between space-y-4">
            <div class="flex items-start justify-between">
              <span
                class="text-[15px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 max-w-[120px]">Teaching
                Workload</span>
              <div
                class="w-10 h-10 rounded-2xl bg-amber-50 dark:bg-amber-950/40 text-amber-600 dark:text-amber-400 flex items-center justify-center shrink-0 border border-amber-100 dark:border-amber-900/30">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
              </div>
            </div>

            <div class="space-y-2">
              <span class="text-3xl font-black text-slate-800 dark:text-white block">18h/wk</span>
              <p class="text-xs text-slate-500 dark:text-slate-400 font-medium leading-relaxed">
                4 distinct academic preparations
              </p>
            </div>
          </div>

          <!-- 3. ADVISORY ASSIGNMENT -->
          <div
            class="bg-white/90 dark:bg-[#2D3A31]/90 backdrop-blur-sm rounded-2xl p-6 shadow-sm border border-slate-200/60 dark:border-[#3F4F43] flex flex-col justify-between space-y-4">
            <div class="flex items-start justify-between">
              <span
                class="text-[15px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 max-w-[120px]">Advisory
                Assignment</span>
              <div
                class="w-10 h-10 rounded-2xl bg-yellow-50 dark:bg-amber-950/40 text-yellow-700 dark:text-amber-400 flex items-center justify-center shrink-0 border border-yellow-100 dark:border-amber-900/30">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                  <path
                    d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" />
                </svg>
              </div>
            </div>

            <div class="space-y-1">
              <h4 class="text-2xl font-black text-slate-800 dark:text-white">12–STEM Jose Rizal</h4>
              <p class="text-xs text-slate-500 dark:text-slate-400 font-medium leading-relaxed">
                45 STEM students • Room 302
              </p>
            </div>
          </div>

          <!-- 4. MIDTERM GWA HEALTH -->
          <div
            class="bg-white/90 dark:bg-[#2D3A31]/90 backdrop-blur-sm rounded-2xl p-6 shadow-sm border border-slate-200/60 dark:border-[#3F4F43] flex flex-col justify-between space-y-4">
            <div class="flex items-start justify-between">
              <span
                class="text-[15px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 max-w-[120px]">Midterm
                GWA Health</span>
              <div
                class="w-10 h-10 rounded-2xl bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 flex items-center justify-center shrink-0 border border-emerald-100 dark:border-emerald-900/30">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                </svg>
              </div>
            </div>

            <div class="space-y-2">
              <div class="flex items-baseline gap-2.5">
                <span class="text-3xl font-black text-slate-800 dark:text-white">91.4%</span>
                <span
                  class="text-[11px] font-bold bg-[#EAF3EC] dark:bg-[#3F4F43] text-[#005506] dark:text-[#86EFAC] px-2 py-0.5 rounded-full border border-emerald-200/60 dark:border-emerald-900/40">
                  +2.1% Q-o-Q
                </span>
              </div>
              <p class="text-xs text-slate-500 dark:text-slate-400 font-medium leading-relaxed">
                98.6% on-track submission index
              </p>
            </div>
          </div>

        </div>

        <!-- TEACHING ASSIGNMENTS & ROSTERS SECTION -->
        <div class="space-y-6 pt-4">

          <!-- Section Header & Semester Status -->
          <div
            class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white/90 dark:bg-[#2D3A31]/90 backdrop-blur-sm rounded-2xl p-6 shadow-sm border border-slate-200/60 dark:border-[#3F4F43]">
            <div class="space-y-1">
              <h3 class="text-base md:text-lg font-black text-slate-800 dark:text-white">Teaching Assignments & Rosters
              </h3>
              <p class="text-xs text-slate-500 dark:text-slate-400 font-medium">
                Select an active section to load student roster data, SF-2 attendance logs, and transmuted e-records.
              </p>
            </div>

            <div class="flex items-center gap-3 shrink-0">
              <span class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">SEMESTER
                STATUS:</span>
              <span
                class="bg-[#EAF3EC] dark:bg-emerald-950/60 text-[#005506] dark:text-[#86EFAC] border border-emerald-200 dark:border-emerald-900/40 text-xs font-extrabold px-4 py-2 rounded-full shadow-sm flex items-center gap-1.5">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                Midterm Window Open
              </span>
            </div>
          </div>

          <!-- CARDS GRID -->
          <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

            <!-- CARD 1: ADVISORY SECTION (Jose Rizal) -->
            <div
              class="bg-white/90 dark:bg-[#2D3A31]/90 backdrop-blur-sm rounded-3xl p-6 shadow-sm border border-slate-200/60 dark:border-[#3F4F43] flex flex-col justify-between space-y-6">

              <div class="space-y-4">
                <!-- Top Badges -->
                <div class="flex items-center justify-between gap-2">
                  <span
                    class="bg-[#F9C20C] text-[#2C3E2D] text-xs font-black px-3.5 py-1.5 rounded-full shadow-sm flex items-center gap-1">
                    <span>★</span> Advisory Section
                  </span>
                  <span
                    class="bg-[#EAF3EC] dark:bg-emerald-950/50 text-[#005506] dark:text-[#86EFAC] border border-emerald-200/60 dark:border-emerald-900/40 text-xs font-bold px-3 py-1 rounded-full">
                    STEM Track
                  </span>
                </div>

                <!-- Title & Description -->
                <div class="space-y-1">
                  <h4 class="text-base font-black text-slate-800 dark:text-white">
                    Grade 12 STEM – Jose Rizal
                  </h4>
                  <p class="text-xs text-slate-600 dark:text-slate-400 font-medium leading-relaxed">
                    Gen. Physics 2 & Practical Research II
                  </p>
                </div>

                <!-- Info Box -->
                <div
                  class="bg-[#F9F7F1] dark:bg-[#232D26] rounded-2xl p-4 border border-slate-200/80 dark:border-[#3F4F43] space-y-3">
                  <div class="flex items-start justify-between gap-2">
                    <div class="flex items-start gap-2 text-xs text-slate-600 dark:text-slate-300">
                      <span class="text-[#005506] dark:text-[#86EFAC] mt-0.5">📍</span>
                      <span>Room 302, Sci-Tech Bldg</span>
                    </div>
                    <span class="text-xs font-extrabold text-slate-800 dark:text-white shrink-0 text-right">
                      45 Enrolled
                    </span>
                  </div>

                  <div
                    class="flex items-start justify-between gap-2 border-t border-slate-200/60 dark:border-[#3F4F43] pt-2.5">
                    <div class="flex items-start gap-2 text-xs text-slate-600 dark:text-slate-300">
                      <span class="text-[#005506] dark:text-[#86EFAC] mt-0.5">🕒</span>
                      <span>MWF 07:30 – 09:30 AM</span>
                    </div>
                    <span class="text-[11px] font-semibold text-slate-500 dark:text-slate-400 shrink-0 text-right">
                      23 M / 22 F
                    </span>
                  </div>
                </div>

                <!-- Attendance Health Bar -->
                <div class="space-y-1.5 pt-1">
                  <div class="flex items-center justify-between text-xs font-bold">
                    <span class="text-slate-500 dark:text-slate-400">Attendance Health</span>
                    <span class="text-[#005506] dark:text-[#86EFAC]">98.6% Regular</span>
                  </div>
                  <div class="w-full h-2 rounded-full bg-slate-100 dark:bg-[#232D26] overflow-hidden">
                    <div class="h-full bg-[#005506] dark:bg-[#86EFAC] rounded-full w-[98.6%]"></div>
                  </div>
                </div>
              </div>

              <!-- Card Footer Action -->
              <div class="flex items-center justify-between pt-4 border-t border-slate-100 dark:border-[#3F4F43]">
                <span class="text-xs font-extrabold text-[#005506] dark:text-[#86EFAC] flex items-center gap-1.5">
                  <span class="w-2 h-2 rounded-full bg-[#005506] dark:bg-[#86EFAC]"></span> Active Section
                </span>
                <div class="flex items-center gap-2">
                  <button
                    class="w-9 h-9 rounded-xl bg-slate-100 dark:bg-[#232D26] text-slate-700 dark:text-slate-300 hover:bg-emerald-50 hover:text-[#005506] dark:hover:bg-[#3F4F43] flex items-center justify-center transition-colors shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                  </button>
                  <button
                    class="w-9 h-9 rounded-xl bg-slate-100 dark:bg-[#232D26] text-slate-700 dark:text-slate-300 hover:bg-emerald-50 hover:text-[#005506] dark:hover:bg-[#3F4F43] flex items-center justify-center transition-colors shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4">
                      </path>
                    </svg>
                  </button>
                </div>
              </div>

            </div>

            <!-- CARD 2: SUBJECT CLASS (Archimedes) -->
            <div
              class="bg-white/90 dark:bg-[#2D3A31]/90 backdrop-blur-sm rounded-3xl p-6 shadow-sm border border-slate-200/60 dark:border-[#3F4F43] flex flex-col justify-between space-y-6">

              <div class="space-y-4">
                <!-- Top Badges -->
                <div class="flex items-center justify-between gap-2">
                  <span
                    class="bg-[#EAE7DF] dark:bg-[#3F4F43] text-slate-700 dark:text-slate-300 text-xs font-black px-3.5 py-1.5 rounded-full shadow-sm">
                    Subject Class
                  </span>
                  <span
                    class="bg-[#EAF3EC] dark:bg-emerald-950/50 text-[#005506] dark:text-[#86EFAC] border border-emerald-200/60 dark:border-emerald-900/40 text-xs font-bold px-3 py-1 rounded-full">
                    STEM Track
                  </span>
                </div>

                <!-- Title & Description -->
                <div class="space-y-1">
                  <h4 class="text-base font-black text-slate-800 dark:text-white">
                    Grade 12 STEM – Archimedes
                  </h4>
                  <p class="text-xs text-slate-600 dark:text-slate-400 font-medium leading-relaxed">
                    General Physics 2
                  </p>
                </div>

                <!-- Info Box -->
                <div
                  class="bg-[#F9F7F1] dark:bg-[#232D26] rounded-2xl p-4 border border-slate-200/80 dark:border-[#3F4F43] space-y-3">
                  <div class="flex items-start justify-between gap-2">
                    <div class="flex items-start gap-2 text-xs text-slate-600 dark:text-slate-300">
                      <span class="text-[#005506] dark:text-[#86EFAC] mt-0.5">📍</span>
                      <span>Sci-Lab 2, 2nd Floor</span>
                    </div>
                    <span class="text-xs font-extrabold text-slate-800 dark:text-white shrink-0 text-right">
                      44 Enrolled
                    </span>
                  </div>

                  <div
                    class="flex items-start justify-between gap-2 border-t border-slate-200/60 dark:border-[#3F4F43] pt-2.5">
                    <div class="flex items-start gap-2 text-xs text-slate-600 dark:text-slate-300">
                      <span class="text-[#005506] dark:text-[#86EFAC] mt-0.5">🕒</span>
                      <span>TTh 09:45 – 11:45 AM</span>
                    </div>
                    <span class="text-[11px] font-semibold text-slate-500 dark:text-slate-400 shrink-0 text-right">
                      21 M / 23 F
                    </span>
                  </div>
                </div>

                <!-- Attendance Health Bar -->
                <div class="space-y-1.5 pt-1">
                  <div class="flex items-center justify-between text-xs font-bold">
                    <span class="text-slate-500 dark:text-slate-400">Attendance Health</span>
                    <span class="text-[#005506] dark:text-[#86EFAC]">97.2% Regular</span>
                  </div>
                  <div class="w-full h-2 rounded-full bg-slate-100 dark:bg-[#232D26] overflow-hidden">
                    <div class="h-full bg-[#005506] dark:bg-[#86EFAC] rounded-full w-[97.2%]"></div>
                  </div>
                </div>
              </div>

              <!-- Card Footer Action -->
              <div class="flex items-center justify-between pt-4 border-t border-slate-100 dark:border-[#3F4F43]">
                <a href="#"
                  class="text-xs font-extrabold text-[#005506] dark:text-[#86EFAC] hover:underline flex items-center gap-1">
                  Switch to Archimedes →
                </a>
                <button
                  class="w-9 h-9 rounded-xl bg-slate-100 dark:bg-[#232D26] text-slate-700 dark:text-slate-300 hover:bg-emerald-50 hover:text-[#005506] dark:hover:bg-[#3F4F43] flex items-center justify-center transition-colors shadow-sm">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4">
                    </path>
                  </svg>
                </button>
              </div>

            </div>

            <!-- CARD 3: SUBJECT CLASS (Alan Turing) -->
            <div
              class="bg-white/90 dark:bg-[#2D3A31]/90 backdrop-blur-sm rounded-3xl p-6 shadow-sm border border-slate-200/60 dark:border-[#3F4F43] flex flex-col justify-between space-y-6">

              <div class="space-y-4">
                <!-- Top Badges -->
                <div class="flex items-center justify-between gap-2">
                  <span
                    class="bg-[#EAE7DF] dark:bg-[#3F4F43] text-slate-700 dark:text-slate-300 text-xs font-black px-3.5 py-1.5 rounded-full shadow-sm">
                    Subject Class
                  </span>
                  <span
                    class="bg-red-50 dark:bg-rose-950/50 text-red-700 dark:text-rose-300 border border-red-200/60 dark:border-rose-900/40 text-xs font-bold px-3 py-1 rounded-full">
                    TVL-ICT Track
                  </span>
                </div>

                <!-- Title & Description -->
                <div class="space-y-1">
                  <h4 class="text-base font-black text-slate-800 dark:text-white">
                    TechPro 11 – Alan Turing
                  </h4>
                  <p class="text-xs text-slate-600 dark:text-slate-400 font-medium leading-relaxed">
                    Robotics & Applied Electronics
                  </p>
                </div>

                <!-- Info Box -->
                <div
                  class="bg-[#F9F7F1] dark:bg-[#232D26] rounded-2xl p-4 border border-slate-200/80 dark:border-[#3F4F43] space-y-3">
                  <div class="flex items-start justify-between gap-2">
                    <div class="flex items-start gap-2 text-xs text-slate-600 dark:text-slate-300">
                      <span class="text-[#005506] dark:text-[#86EFAC] mt-0.5">📍</span>
                      <span>TechLab 3 (Robotics Wing)</span>
                    </div>
                    <span class="text-xs font-extrabold text-slate-800 dark:text-white shrink-0 text-right">
                      45 Enrolled
                    </span>
                  </div>

                  <div
                    class="flex items-start justify-between gap-2 border-t border-slate-200/60 dark:border-[#3F4F43] pt-2.5">
                    <div class="flex items-start gap-2 text-xs text-slate-600 dark:text-slate-300">
                      <span class="text-[#005506] dark:text-[#86EFAC] mt-0.5">🕒</span>
                      <span>MWF 01:00 – 02:30 PM</span>
                    </div>
                    <span class="text-[11px] font-semibold text-slate-500 dark:text-slate-400 shrink-0 text-right">
                      28 M / 17 F
                    </span>
                  </div>
                </div>

                <!-- Lab Pipeline Task Progress Bar -->
                <div class="space-y-1.5 pt-1">
                  <div class="flex items-center justify-between text-xs font-bold">
                    <span class="text-slate-500 dark:text-slate-400">Lab Pipeline Task Turn-in</span>
                    <span class="text-amber-600 dark:text-amber-400 font-black">12 / 38 Submissions</span>
                  </div>
                  <div class="w-full h-2 rounded-full bg-slate-100 dark:bg-[#232D26] overflow-hidden">
                    <div class="h-full bg-amber-500 rounded-full w-[31%]"></div>
                  </div>
                </div>
              </div>

              <!-- Card Footer Action -->
              <div class="flex items-center justify-between pt-4 border-t border-slate-100 dark:border-[#3F4F43]">
                <a href="#"
                  class="text-xs font-extrabold text-[#005506] dark:text-[#86EFAC] hover:underline flex items-center gap-1">
                  Switch to Turing →
                </a>
                <button
                  class="w-9 h-9 rounded-xl bg-slate-100 dark:bg-[#232D26] text-slate-700 dark:text-slate-300 hover:bg-emerald-50 hover:text-[#005506] dark:hover:bg-[#3F4F43] flex items-center justify-center transition-colors shadow-sm">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4">
                    </path>
                  </svg>
                </button>
              </div>

            </div>
          </div>

          <!-- SECTION ROSTER TABLE WITH TABULATOR FILTER -->
        <div class="bg-white/90 dark:bg-[#2D3A31]/90 backdrop-blur-sm rounded-3xl p-6 sm:p-8 shadow-sm border border-slate-200/60 dark:border-[#3F4F43] space-y-6">
          
          <!-- Header Info -->
          <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 border-b border-slate-100 dark:border-[#3F4F43] pb-6">
            <div class="space-y-1">
              <div class="flex items-center gap-3">
                <h3 class="text-base sm:text-lg font-black text-slate-800 dark:text-white">
                  Section Roster: Grade 12 STEM – Jose Rizal
                </h3>
                <span class="bg-[#EAF3EC] dark:bg-emerald-950/50 text-[#005506] dark:text-[#86EFAC] border border-emerald-200/60 dark:border-emerald-900/40 text-xs font-bold px-3 py-1 rounded-full">
                  Room 302
                </span>
              </div>
              <p class="text-xs text-slate-500 dark:text-slate-400 font-medium">
                Official DepEd SF-1 Learner Register with live RFID tap-in timestamps and quarter transmutation.
              </p>
            </div>
          </div>

          <!-- Search Bar & Tabulator Filter Pills -->
          <div class="flex flex-col md:flex-row items-stretch md:items-center justify-between gap-4 bg-[#F9F7F1] dark:bg-[#232D26] p-4 rounded-2xl border border-slate-200/60 dark:border-[#3F4F43]">
            <!-- Search input -->
            <div class="relative w-full md:w-80">
              <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
              </span>
              <input 
                type="text" 
                placeholder="Search by student name, LRN.." 
                class="w-full pl-9 pr-4 py-2 rounded-xl bg-white dark:bg-[#2D3A31] border border-slate-200 dark:border-[#3F4F43] focus:outline-none focus:ring-2 focus:ring-[#006907] text-xs text-slate-700 dark:text-slate-200 shadow-sm" 
              />
            </div>

            <!-- Tabulator Filter Buttons -->
            <div class="flex flex-wrap items-center gap-1.5 overflow-x-auto">
              <button class="bg-[#005506] dark:bg-[#86EFAC] text-white dark:text-[#232D26] px-4 py-2 rounded-xl text-xs font-bold shadow-sm transition-all">
                All (45)
              </button>
              <button class="bg-white dark:bg-[#2D3A31] text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-[#3F4F43] px-4 py-2 rounded-xl text-xs font-semibold transition-all border border-slate-200 dark:border-[#3F4F43]">
                Male (23)
              </button>
              <button class="bg-white dark:bg-[#2D3A31] text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-[#3F4F43] px-4 py-2 rounded-xl text-xs font-semibold transition-all border border-slate-200 dark:border-[#3F4F43]">
                Female (22)
              </button>
              <button class="bg-white dark:bg-[#2D3A31] text-amber-700 dark:text-amber-400 hover:bg-amber-50 dark:hover:bg-amber-950/40 px-4 py-2 rounded-xl text-xs font-semibold transition-all border border-amber-200 dark:border-amber-900/30 flex items-center gap-1">
                <span>★</span> With Honors (14)
              </button>
              <button class="bg-white dark:bg-[#2D3A31] text-red-700 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-950/40 px-4 py-2 rounded-xl text-xs font-semibold transition-all border border-red-200 dark:border-red-900/30 flex items-center gap-1">
                <span>⚠️</span> At Risk (1)
              </button>
            </div>
          </div>

          <!-- Table Container -->
          <div class="overflow-x-auto rounded-2xl border border-slate-200/60 dark:border-[#3F4F43]">
            <table class="w-full text-left border-collapse">
              <thead>
                <tr class="bg-[#F9F7F1] dark:bg-[#232D26] text-[11px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400 border-b border-slate-200/60 dark:border-[#3F4F43]">
                  <th class="py-3.5 px-4">No. / LRN</th>
                  <th class="py-3.5 px-4">Learner Full Name</th>
                  <th class="py-3.5 px-4">Gender</th>
                  <th class="py-3.5 px-4">RFID Status (Today)</th>
                  <th class="py-3.5 px-4">Midterm Transmuted</th>
                  <th class="py-3.5 px-4">Honors Standing</th>
                  <th class="py-3.5 px-4">Guardian Contact</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-slate-100 dark:divide-[#3F4F43] text-xs font-medium text-slate-700 dark:text-slate-200">
                
                <!-- Row 1 -->
                <tr class="hover:bg-slate-50/60 dark:hover:bg-[#232D26]/40 transition-colors">
                  <td class="py-4 px-4 font-mono">
                    <span class="font-bold text-slate-900 dark:text-white">#01</span><br>
                    <span class="text-[11px] text-slate-400">109384729104</span>
                  </td>
                  <td class="py-4 px-4">
                    <div class="flex items-center gap-3">
                      <img src="https://ui-avatars.com/api/?name=Juan+Carlos+Dela+Cruz&background=random" alt="Juan Carlos" class="w-10 h-10 rounded-full object-cover shrink-0 border border-slate-200 dark:border-[#3F4F43]" />
                      <div>
                        <p class="font-bold text-slate-900 dark:text-white">Dela Cruz, Juan Carlos M.</p>
                        <p class="text-[11px] text-slate-500">Seat 01 • Class President</p>
                      </div>
                    </div>
                  </td>
                  <td class="py-4 px-4">
                    <span class="bg-slate-100 dark:bg-[#3F4F43] text-slate-700 dark:text-slate-300 font-bold px-2.5 py-1 rounded-lg text-[11px]">Male</span>
                  </td>
                  <td class="py-4 px-4">
                    <div class="flex items-center gap-1.5 text-emerald-700 dark:text-emerald-400 font-bold">
                      <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                      <span>Tapped 07:12 AM</span>
                    </div>
                    <p class="text-[11px] text-slate-400 mt-0.5">18-day streak</p>
                  </td>
                  <td class="py-4 px-4">
                    <span class="font-bold text-slate-900 dark:text-white bg-emerald-100 dark:bg-emerald-950/60 px-2.5 py-1 rounded-lg">95</span>
                    <span class="text-[11px] text-slate-500 block mt-0.5">General Physics</span>
                  </td>
                  <td class="py-4 px-4">
                    <span class="bg-amber-100 dark:bg-amber-950/60 text-amber-900 dark:text-amber-300 font-extrabold text-[11px] px-3 py-1 rounded-full inline-flex items-center gap-1 border border-amber-200 dark:border-amber-900/30">
                      ★ With High Honors
                    </span>
                  </td>
                  <td class="py-4 px-4 font-mono text-[11px]">
                    0917-812-2045<br>
                    <span class="text-slate-500 font-sans">Mrs. Elena Cruz</span>
                  </td>
                </tr>

                <!-- Row 2 -->
                <tr class="hover:bg-slate-50/60 dark:hover:bg-[#232D26]/40 transition-colors">
                  <td class="py-4 px-4 font-mono">
                    <span class="font-bold text-slate-900 dark:text-white">#02</span><br>
                    <span class="text-[11px] text-slate-400">109384729105</span>
                  </td>
                  <td class="py-4 px-4">
                    <div class="flex items-center gap-3">
                      <img src="https://ui-avatars.com/api/?name=Clarissa+Mae+Santos&background=random" alt="Clarissa Mae" class="w-10 h-10 rounded-full object-cover shrink-0 border border-slate-200 dark:border-[#3F4F43]" />
                      <div>
                        <p class="font-bold text-slate-900 dark:text-white">Santos, Clarissa Mae V.</p>
                        <p class="text-[11px] text-slate-500">Seat 02 • Science Club VP</p>
                      </div>
                    </div>
                  </td>
                  <td class="py-4 px-4">
                    <span class="bg-slate-100 dark:bg-[#3F4F43] text-slate-700 dark:text-slate-300 font-bold px-2.5 py-1 rounded-lg text-[11px]">Female</span>
                  </td>
                  <td class="py-4 px-4">
                    <div class="flex items-center gap-1.5 text-emerald-700 dark:text-emerald-400 font-bold">
                      <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                      <span>Tapped 07:18 AM</span>
                    </div>
                    <p class="text-[11px] text-slate-400 mt-0.5">24-day streak</p>
                  </td>
                  <td class="py-4 px-4">
                    <span class="font-bold text-slate-900 dark:text-white bg-emerald-100 dark:bg-emerald-950/60 px-2.5 py-1 rounded-lg">99</span>
                    <span class="text-[11px] text-slate-500 block mt-0.5">Top 1 STEM Section</span>
                  </td>
                  <td class="py-4 px-4">
                    <span class="bg-amber-400 text-amber-950 font-black text-[11px] px-3 py-1 rounded-full inline-flex items-center gap-1 shadow-sm">
                      ★ Highest Honors (Apex)
                    </span>
                  </td>
                  <td class="py-4 px-4 font-mono text-[11px]">
                    0920-918-4421<br>
                    <span class="text-slate-500 font-sans">Engr. Roberto Santos</span>
                  </td>
                </tr>

                <!-- Row 3 -->
                <tr class="hover:bg-slate-50/60 dark:hover:bg-[#232D26]/40 transition-colors">
                  <td class="py-4 px-4 font-mono">
                    <span class="font-bold text-slate-900 dark:text-white">#03</span><br>
                    <span class="text-[11px] text-slate-400">109384729112</span>
                  </td>
                  <td class="py-4 px-4">
                    <div class="flex items-center gap-3">
                      <div class="w-10 h-10 rounded-full bg-rose-100 text-rose-700 dark:bg-rose-950/60 dark:text-rose-400 font-black flex items-center justify-center shrink-0 border border-rose-200">
                        KA
                      </div>
                      <div>
                        <p class="font-bold text-slate-900 dark:text-white">Alcantara, Kenneth D.</p>
                        <p class="text-[11px] text-rose-600 font-semibold">Pending 2 Lab Experiments</p>
                      </div>
                    </div>
                  </td>
                  <td class="py-4 px-4">
                    <span class="bg-slate-100 dark:bg-[#3F4F43] text-slate-700 dark:text-slate-300 font-bold px-2.5 py-1 rounded-lg text-[11px]">Male</span>
                  </td>
                  <td class="py-4 px-4">
                    <div class="flex items-center gap-1.5 text-red-600 dark:text-red-400 font-bold">
                      <span class="w-2 h-2 rounded-full bg-red-500"></span>
                      <span>Unrecorded / Late</span>
                    </div>
                    <p class="text-[11px] text-slate-400 mt-0.5">3 consecutive absences</p>
                  </td>
                  <td class="py-4 px-4">
                    <span class="font-bold text-red-700 dark:text-red-400 bg-red-100 dark:bg-red-950/60 px-2.5 py-1 rounded-lg">84</span>
                    <span class="text-[11px] text-red-600 block mt-0.5 font-semibold">Intervention</span>
                  </td>
                  <td class="py-4 px-4">
                    <span class="bg-slate-100 dark:bg-[#3F4F43] text-slate-600 dark:text-slate-300 font-semibold text-[11px] px-3 py-1 rounded-full">
                      Regular Passing
                    </span>
                  </td>
                  <td class="py-4 px-4 font-mono text-[11px]">
                    0919-444-1982<br>
                    <span class="text-slate-500 font-sans">Mrs. Tessie Alcantara</span>
                  </td>
                </tr>

                <!-- Row 4 -->
                <tr class="hover:bg-slate-50/60 dark:hover:bg-[#232D26]/40 transition-colors">
                  <td class="py-4 px-4 font-mono">
                    <span class="font-bold text-slate-900 dark:text-white">#04</span><br>
                    <span class="text-[11px] text-slate-400">109384729119</span>
                  </td>
                  <td class="py-4 px-4">
                    <div class="flex items-center gap-3">
                      <img src="https://ui-avatars.com/api/?name=Hannah+Sofia+Villafuerte&background=random" alt="Hannah Sofia" class="w-10 h-10 rounded-full object-cover shrink-0 border border-slate-200 dark:border-[#3F4F43]" />
                      <div>
                        <p class="font-bold text-slate-900 dark:text-white">Villafuerte, Hannah Sofia B.</p>
                        <p class="text-[11px] text-slate-500">Seat 04 • Lead Researcher</p>
                      </div>
                    </div>
                  </td>
                  <td class="py-4 px-4">
                    <span class="bg-slate-100 dark:bg-[#3F4F43] text-slate-700 dark:text-slate-300 font-bold px-2.5 py-1 rounded-lg text-[11px]">Female</span>
                  </td>
                  <td class="py-4 px-4">
                    <div class="flex items-center gap-1.5 text-emerald-700 dark:text-emerald-400 font-bold">
                      <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                      <span>Tapped 07:22 AM</span>
                    </div>
                    <p class="text-[11px] text-slate-400 mt-0.5">15-day streak</p>
                  </td>
                  <td class="py-4 px-4">
                    <span class="font-bold text-slate-900 dark:text-white bg-emerald-100 dark:bg-emerald-950/60 px-2.5 py-1 rounded-lg">94</span>
                    <span class="text-[11px] text-slate-500 block mt-0.5">Physics & Math</span>
                  </td>
                  <td class="py-4 px-4">
                    <span class="bg-emerald-100 dark:bg-emerald-950/60 text-emerald-800 dark:text-emerald-300 font-extrabold text-[11px] px-3 py-1 rounded-full inline-flex items-center gap-1 border border-emerald-200 dark:border-emerald-900/30">
                      ★ With Honors
                    </span>
                  </td>
                  <td class="py-4 px-4 font-mono text-[11px]">
                    0928-123-9842<br>
                    <span class="text-slate-500 font-sans">Capt. Ben Villafuerte</span>
                  </td>
                </tr>

              </tbody>
            </table>
          </div>

          <!-- Pagination Footer -->
          <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-2">
            <p class="text-xs text-slate-500 dark:text-slate-400 font-medium">
              Showing 4 of 45 verified learners • <span class="font-bold text-slate-700 dark:text-slate-200">100% Enrollment SF-1 Match</span>
            </p>

            <div class="flex items-center gap-1.5">
              <button class="px-3.5 py-2 rounded-xl border border-slate-200 dark:border-[#3F4F43] text-xs font-bold text-slate-400 dark:text-slate-500 bg-slate-50 dark:bg-[#232D26] cursor-not-allowed">
                Previous
              </button>
              <button class="w-9 h-9 rounded-xl bg-[#005506] dark:bg-[#86EFAC] text-white dark:text-[#232D26] text-xs font-bold flex items-center justify-center shadow-sm">
                1
              </button>
              <button class="w-9 h-9 rounded-xl hover:bg-slate-100 dark:hover:bg-[#3F4F43] text-slate-600 dark:text-slate-300 text-xs font-bold flex items-center justify-center transition-colors">
                2
              </button>
              <button class="w-9 h-9 rounded-xl hover:bg-slate-100 dark:hover:bg-[#3F4F43] text-slate-600 dark:text-slate-300 text-xs font-bold flex items-center justify-center transition-colors">
                3
              </button>
              <button class="px-3.5 py-2 rounded-xl border border-slate-200 dark:border-[#3F4F43] text-xs font-bold text-slate-700 dark:text-slate-200 bg-white dark:bg-[#2D3A31] hover:bg-slate-50 transition-colors">
                Next
              </button>
            </div>
          </div>

        </div>

        

        </div>

      </div>

    </main>

  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { Head, Link } from '@inertiajs/vue3'
import Sidebart from '@/Components/Sidebart.vue'

// UI State Management
const showNotifications = ref(false)
const showProfile = ref(false)
const toggleNotifications = () => { showNotifications.value = !showNotifications.value; showProfile.value = false; }
const toggleProfile = () => { showProfile.value = !showProfile.value; showNotifications.value = false; }
const closeDropdowns = () => { showNotifications.value = false; showProfile.value = false; }

// Dark Mode Toggle Logic
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

// Font Scaling Logic
const fontSizeMode = ref('base')
const changeFontSize = (size) => { fontSizeMode.value = size }

onMounted(() => {
  if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
    isDark.value = true
    document.documentElement.classList.add('dark')
  }
})
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Anton&family=Inter:wght@400;500;600;700;800;900&display=swap');

/* Breathing opacity animation for the green background layer */
@keyframes pulse-opacity {

  0%,
  100% {
    opacity: 0.88;
  }

  50% {
    opacity: 0.65;
  }
}

.animate-overlay {
  animation: pulse-opacity 6s infinite ease-in-out;
}

/* TEXT SCALING OVERRIDES */
.text-scale-sm :deep(.text-xs) {
  font-size: 0.65rem !important;
  line-height: 0.85rem !important;
}

.text-scale-sm :deep(.text-sm) {
  font-size: 0.75rem !important;
  line-height: 1rem !important;
}

.text-scale-sm :deep(.text-base) {
  font-size: 0.875rem !important;
  line-height: 1.25rem !important;
}

.text-scale-sm :deep(.text-lg) {
  font-size: 1rem !important;
  line-height: 1.5rem !important;
}

.text-scale-sm :deep(.text-xl) {
  font-size: 1.125rem !important;
  line-height: 1.75rem !important;
}

.text-scale-sm :deep(.text-3xl) {
  font-size: 1.5rem !important;
  line-height: 2rem !important;
}

.text-scale-sm :deep(.sm\:text-4xl) {
  font-size: 1.875rem !important;
  line-height: 2.25rem !important;
}

.text-scale-sm :deep(.md\:text-5xl) {
  font-size: 2.25rem !important;
  line-height: 1 !important;
}

.text-scale-lg :deep(.text-xs) {
  font-size: 0.875rem !important;
  line-height: 1.25rem !important;
}

.text-scale-lg :deep(.text-sm) {
  font-size: 1rem !important;
  line-height: 1.5rem !important;
}

.text-scale-lg :deep(.text-base) {
  font-size: 1.125rem !important;
  line-height: 1.75rem !important;
}

.text-scale-lg :deep(.text-lg) {
  font-size: 1.25rem !important;
  line-height: 1.75rem !important;
}

.text-scale-lg :deep(.text-xl) {
  font-size: 1.5rem !important;
  line-height: 2rem !important;
}

.text-scale-lg :deep(.text-3xl) {
  font-size: 2.25rem !important;
  line-height: 2.5rem !important;
}

.text-scale-lg :deep(.sm\:text-4xl) {
  font-size: 2.75rem !important;
  line-height: 1 !important;
}

.text-scale-lg :deep(.md\:text-5xl) {
  font-size: 3.5rem !important;
  line-height: 1 !important;
}
</style>