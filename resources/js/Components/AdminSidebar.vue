<template>
  <!-- Admin Sidebar Container -->
  <aside
    class="relative w-72 h-screen sticky top-0 flex flex-col bg-[#063B11] dark:bg-[#07220F] border-r border-[#002B0B] dark:border-[#11381C] overflow-hidden transition-colors duration-300 print:hidden shrink-0 font-['Inter'] z-30 shadow-xl"
  >
    <!-- Background Watermark Graphic Layer -->
    <div
      class="absolute inset-0 z-0 pointer-events-none bg-cover bg-center bg-no-repeat opacity-20 dark:opacity-10 mix-blend-overlay transition-opacity duration-300"
      :style="{ backgroundImage: `url(${sidebarBg})` }"
    ></div>

    <!-- 1. PINNED TOP HEADER SECTION (NON-SCROLLABLE) -->
    <div class="relative z-10 p-5 pb-3 shrink-0 border-b border-white/10 dark:border-white/5 bg-[#063B11]/90 dark:bg-[#07220F]/90 backdrop-blur-xs">
      <!-- Logo & School Name Header -->
      <div class="flex flex-col items-center mb-4 mt-1">
        <!-- Crest Logo with Water Droplet Ripple Animation -->
        <div class="relative flex items-center justify-center mb-3">
          <div class="ripple-wave absolute inset-0 rounded-full border border-[#86EFAC]/40"></div>
          <div class="ripple-wave absolute inset-0 rounded-full border border-[#86EFAC]/30" style="animation-delay: 1s;"></div>
          <div class="absolute inset-0 rounded-full bg-[#86EFAC]/10 blur-sm animate-pulse"></div>

          <!-- School Crest Image Container -->
          <div class="relative w-20 h-20 rounded-full bg-white dark:bg-[#2D3A31] border-2 border-[#F9C20C] flex items-center justify-center p-1 shadow-md transition-colors duration-300 z-10">
            <img :src="schoolLogo" alt="Salawag SHS Logo" class="w-full h-full object-contain rounded-full" />
          </div>
        </div>

        <h1 class="text-center text-white dark:text-[#86EFAC] font-extrabold text-xs leading-tight tracking-wide transition-colors duration-300 uppercase">
          SALAWAG<br />SENIOR HIGH SCHOOL
        </h1>

        <!-- Admin Logged Position Pill -->
        <div class="mt-2.5 px-3 py-0.5 bg-black/25 dark:bg-black/40 rounded-full border border-white/10 flex items-center gap-2">
          <span class="w-2 h-2 rounded-full bg-[#F9C20C] animate-ping"></span>
          <span class="text-[10px] font-extrabold text-emerald-200 dark:text-emerald-300 uppercase tracking-wider">
            {{ $page.props.auth?.user?.position?.name || 'System Admin' }}
          </span>
        </div>
      </div>

      <!-- Academic Workspace & School Year Row -->
      <div class="flex items-center justify-between px-1 pt-1">
        <span class="text-[10px] font-extrabold text-emerald-200/80 dark:text-emerald-300/70 tracking-widest uppercase">
          Academic Workspace
        </span>
        <span class="text-[10px] font-black text-[#063B11] bg-[#F9C20C] px-2.5 py-0.5 rounded-full shadow-xs">
          S.Y. 25-26
        </span>
      </div>
    </div>

    <!-- 2. SCROLLABLE MIDDLE NAVIGATION AREA -->
    <div class="relative z-10 flex-1 overflow-y-auto px-4 py-4 no-scrollbar space-y-4 text-xs font-semibold">
      <nav class="space-y-4">
        
        <!-- MAIN DASHBOARD -->
        <div class="space-y-1">
          <Link
            :href="route('admin.dashboard')"
            :class="[
              'flex items-center gap-3 px-4 py-2.5 rounded-xl font-bold transition-all duration-200',
              isRouteActive('admin.dashboard')
                ? 'bg-white text-[#063B11] dark:bg-[#86EFAC] dark:text-[#062910] shadow-md font-extrabold'
                : 'text-emerald-100/80 hover:bg-white/10 dark:hover:bg-white/5 hover:text-white dark:text-emerald-200/80 dark:hover:text-white'
            ]"
          >
            <!-- Dashboard Grid Icon -->
            <svg class="w-5 h-5 shrink-0 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M4 5a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM14 5a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1V5zM4 15a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1v-4zM14 15a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z" />
            </svg>
            <span>Dashboard</span>
          </Link>
        </div>

        <!-- MANAGEMENT SECTION -->
        <div class="space-y-1">
          <p class="px-3 text-[10px] font-black uppercase text-emerald-300/50 dark:text-emerald-400/40 tracking-widest">
            Management
          </p>

          <Link
            v-if="can('manage-enrollment')"
            :href="route('admin.applicants.index')"
            :class="[
              'flex items-center gap-3 px-4 py-2.5 rounded-xl font-bold transition-all duration-200',
              isRouteActive('admin.applicants.*')
                ? 'bg-white text-[#063B11] dark:bg-[#86EFAC] dark:text-[#062910] shadow-md font-extrabold'
                : 'text-emerald-100/80 hover:bg-white/10 dark:hover:bg-white/5 hover:text-white dark:text-emerald-200/80 dark:hover:text-white'
            ]"
          >
            <!-- Applications Icon -->
            <svg class="w-5 h-5 shrink-0 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            <span>Applications</span>
          </Link>

          <Link
            v-if="can('manage-enrollment')"
            :href="route('admin.enrollments.index')"
            :class="[
              'flex items-center gap-3 px-4 py-2.5 rounded-xl font-bold transition-all duration-200',
              isRouteActive('admin.enrollments.*')
                ? 'bg-white text-[#063B11] dark:bg-[#86EFAC] dark:text-[#062910] shadow-md font-extrabold'
                : 'text-emerald-100/80 hover:bg-white/10 dark:hover:bg-white/5 hover:text-white dark:text-emerald-200/80 dark:hover:text-white'
            ]"
          >
            <!-- Enrollments Icon -->
            <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            <span>Enrollments</span>
          </Link>

          <Link
            v-if="can('manage-enrollment')"
            :href="route('admin.entrance-exams.index')"
            :class="[
              'flex items-center gap-3 px-4 py-2.5 rounded-xl font-bold transition-all duration-200',
              isRouteActive('admin.entrance-exams.*') || isRouteActive('admin.exam-results.*')
                ? 'bg-white text-[#063B11] dark:bg-[#86EFAC] dark:text-[#062910] shadow-md font-extrabold'
                : 'text-emerald-100/80 hover:bg-white/10 dark:hover:bg-white/5 hover:text-white dark:text-emerald-200/80 dark:hover:text-white'
            ]"
          >
            <!-- Entrance Exams Icon -->
            <svg class="w-5 h-5 shrink-0 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
            </svg>
            <span>Entrance Exams</span>
          </Link>

          <Link
            v-if="can('manage-enrollment')"
            :href="route('admin.exam-records.index')"
            :class="[
              'flex items-center gap-3 px-4 py-2.5 rounded-xl font-bold transition-all duration-200',
              isRouteActive('admin.exam-records.*')
                ? 'bg-white text-[#063B11] dark:bg-[#86EFAC] dark:text-[#062910] shadow-md font-extrabold'
                : 'text-emerald-100/80 hover:bg-white/10 dark:hover:bg-white/5 hover:text-white dark:text-emerald-200/80 dark:hover:text-white'
            ]"
          >
            <!-- Exam Records Icon -->
            <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
            </svg>
            <span>Exam Records</span>
          </Link>

          <Link
            v-if="can('manage-students')"
            :href="route('admin.students.index')"
            :class="[
              'flex items-center gap-3 px-4 py-2.5 rounded-xl font-bold transition-all duration-200',
              isRouteActive('admin.students.*')
                ? 'bg-white text-[#063B11] dark:bg-[#86EFAC] dark:text-[#062910] shadow-md font-extrabold'
                : 'text-emerald-100/80 hover:bg-white/10 dark:hover:bg-white/5 hover:text-white dark:text-emerald-200/80 dark:hover:text-white'
            ]"
          >
            <!-- Graduation Cap Icon -->
            <svg class="w-5 h-5 shrink-0 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l9-5-9-5-9 5 9 5z" />
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0112 20.055a11.952 11.952 0 01-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
            </svg>
            <span>Students</span>
          </Link>

          <Link
            v-if="can('manage-teachers')"
            :href="route('admin.teachers.index')"
            :class="[
              'flex items-center gap-3 px-4 py-2.5 rounded-xl font-bold transition-all duration-200',
              isRouteActive('admin.teachers.*')
                ? 'bg-white text-[#063B11] dark:bg-[#86EFAC] dark:text-[#062910] shadow-md font-extrabold'
                : 'text-emerald-100/80 hover:bg-white/10 dark:hover:bg-white/5 hover:text-white dark:text-emerald-200/80 dark:hover:text-white'
            ]"
          >
            <!-- Teacher User Icon -->
            <svg class="w-5 h-5 shrink-0 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
            <span>Teachers</span>
          </Link>

          <Link
            v-if="can('manage-users')"
            :href="route('admin.users.index')"
            :class="[
              'flex items-center gap-3 px-4 py-2.5 rounded-xl font-bold transition-all duration-200',
              isRouteActive('admin.users.*')
                ? 'bg-white text-[#063B11] dark:bg-[#86EFAC] dark:text-[#062910] shadow-md font-extrabold'
                : 'text-emerald-100/80 hover:bg-white/10 dark:hover:bg-white/5 hover:text-white dark:text-emerald-200/80 dark:hover:text-white'
            ]"
          >
            <!-- Shield User Icon -->
            <svg class="w-5 h-5 shrink-0 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
            </svg>
            <span>Admin Users</span>
          </Link>
        </div>

        <!-- ACADEMICS SECTION -->
        <div class="space-y-1">
          <p class="px-3 text-[10px] font-black uppercase text-emerald-300/50 dark:text-emerald-400/40 tracking-widest">
            Academics
          </p>

          <Link
            v-if="can('manage-school-years')"
            :href="route('admin.school-years.index')"
            :class="[
              'flex items-center gap-3 px-4 py-2.5 rounded-xl font-bold transition-all duration-200',
              isRouteActive('admin.school-years.*')
                ? 'bg-white text-[#063B11] dark:bg-[#86EFAC] dark:text-[#062910] shadow-md font-extrabold'
                : 'text-emerald-100/80 hover:bg-white/10 dark:hover:bg-white/5 hover:text-white dark:text-emerald-200/80 dark:hover:text-white'
            ]"
          >
            <!-- Calendar Icon -->
            <svg class="w-5 h-5 shrink-0 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            <span>School Years</span>
          </Link>

          <Link
            v-if="can('manage-sections')"
            :href="route('admin.sections.index')"
            :class="[
              'flex items-center gap-3 px-4 py-2.5 rounded-xl font-bold transition-all duration-200',
              isRouteActive('admin.sections.*')
                ? 'bg-white text-[#063B11] dark:bg-[#86EFAC] dark:text-[#062910] shadow-md font-extrabold'
                : 'text-emerald-100/80 hover:bg-white/10 dark:hover:bg-white/5 hover:text-white dark:text-emerald-200/80 dark:hover:text-white'
            ]"
          >
            <!-- Building Classroom Icon -->
            <svg class="w-5 h-5 shrink-0 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5m0 0h4m-4 0V11m0 0h4m-4 0H9m4 0v10m-4-10v10" />
            </svg>
            <span>Sections</span>
          </Link>

          <Link
            v-if="can('manage-tracks') || can('manage-strands') || can('manage-subjects')"
            :href="route('admin.curriculum.index')"
            :class="[
              'flex items-center gap-3 px-4 py-2.5 rounded-xl font-bold transition-all duration-200',
              isRouteActive('admin.curriculum.*') || isRouteActive('admin.tracks.*') || isRouteActive('admin.strands.*') || isRouteActive('admin.subjects.*')
                ? 'bg-white text-[#063B11] dark:bg-[#86EFAC] dark:text-[#062910] shadow-md font-extrabold'
                : 'text-emerald-100/80 hover:bg-white/10 dark:hover:bg-white/5 hover:text-white dark:text-emerald-200/80 dark:hover:text-white'
            ]"
          >
            <!-- Curriculum Book Icon -->
            <svg class="w-5 h-5 shrink-0 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
            </svg>
            <span>Curriculum</span>
          </Link>

          <Link
            v-if="can('manage-rooms')"
            :href="route('admin.rooms.index')"
            :class="[
              'flex items-center gap-3 px-4 py-2.5 rounded-xl font-bold transition-all duration-200',
              isRouteActive('admin.rooms.*')
                ? 'bg-white text-[#063B11] dark:bg-[#86EFAC] dark:text-[#062910] shadow-md font-extrabold'
                : 'text-emerald-100/80 hover:bg-white/10 dark:hover:bg-white/5 hover:text-white dark:text-emerald-200/80 dark:hover:text-white'
            ]"
          >
            <!-- Room / Door Icon -->
            <svg class="w-5 h-5 shrink-0 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z" />
            </svg>
            <span>Rooms</span>
          </Link>
        </div>

        <!-- CONTENT SECTION -->
        <div class="space-y-1">
          <p class="px-3 text-[10px] font-black uppercase text-emerald-300/50 dark:text-emerald-400/40 tracking-widest">
            Content
          </p>

          <Link
            v-if="can('manage-announcements')"
            :href="route('admin.school-news.index')"
            :class="[
              'flex items-center gap-3 px-4 py-2.5 rounded-xl font-bold transition-all duration-200',
              isRouteActive('admin.school-news.*')
                ? 'bg-white text-[#063B11] dark:bg-[#86EFAC] dark:text-[#062910] shadow-md font-extrabold'
                : 'text-emerald-100/80 hover:bg-white/10 dark:hover:bg-white/5 hover:text-white dark:text-emerald-200/80 dark:hover:text-white'
            ]"
          >
            <!-- Newspaper / News Icon -->
            <svg class="w-5 h-5 shrink-0 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
            </svg>
            <span>School News</span>
          </Link>

          <Link
            v-if="hasRole('admin')"
            :href="route('admin.contact-messages.index')"
            :class="[
              'flex items-center gap-3 px-4 py-2.5 rounded-xl font-bold transition-all duration-200',
              isRouteActive('admin.contact-messages.*')
                ? 'bg-white text-[#063B11] dark:bg-[#86EFAC] dark:text-[#062910] shadow-md font-extrabold'
                : 'text-emerald-100/80 hover:bg-white/10 dark:hover:bg-white/5 hover:text-white dark:text-emerald-200/80 dark:hover:text-white'
            ]"
          >
            <!-- Chat / Messages Icon -->
            <svg class="w-5 h-5 shrink-0 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M8 10h.01M12 10h.01M16 10h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
            </svg>
            <span class="flex items-center gap-2 w-full">
              <span>Contact Messages</span>
              <span v-if="unreadContactCount > 0"
                    class="ml-auto inline-flex items-center justify-center min-w-[18px] h-[18px] px-1.5 rounded-full text-[9px] font-bold bg-red-500 text-white">
                {{ unreadContactCount > 99 ? '99+' : unreadContactCount }}
              </span>
            </span>
          </Link>
          <Link
            v-if="can('manage-contributions')"
            :href="route('admin.contributions.index')"
            :class="[
              'flex items-center gap-3 px-4 py-2.5 rounded-xl font-bold transition-all duration-200',
              isRouteActive('admin.contributions.*')
                ? 'bg-white text-[#063B11] dark:bg-[#86EFAC] dark:text-[#062910] shadow-md font-extrabold'
                : 'text-emerald-100/80 hover:bg-white/10 dark:hover:bg-white/5 hover:text-white dark:text-emerald-200/80 dark:hover:text-white'
            ]"
          >
            <!-- Credit card / Payments Icon -->
            <svg class="w-5 h-5 shrink-0 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
            </svg>
            <span>Contributions</span>
          </Link>
        </div>

        <!-- SYSTEM SECTION -->
        <div class="space-y-1">
          <p class="px-3 text-[10px] font-black uppercase text-emerald-300/50 dark:text-emerald-400/40 tracking-widest">
            System
          </p>

          <Link
            v-if="can('view-reports')"
            :href="route('admin.reports.index')"
            :class="[
              'flex items-center gap-3 px-4 py-2.5 rounded-xl font-bold transition-all duration-200',
              isRouteActive('admin.reports.*')
                ? 'bg-white text-[#063B11] dark:bg-[#86EFAC] dark:text-[#062910] shadow-md font-extrabold'
                : 'text-emerald-100/80 hover:bg-white/10 dark:hover:bg-white/5 hover:text-white dark:text-emerald-200/80 dark:hover:text-white'
            ]"
          >
            <!-- Bar Chart / Reports Icon -->
            <svg class="w-5 h-5 shrink-0 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
            </svg>
            <span>Reports</span>
          </Link>

          <Link
            v-if="can('manage-settings')"
            :href="route('admin.settings.index')"
            :class="[
              'flex items-center gap-3 px-4 py-2.5 rounded-xl font-bold transition-all duration-200',
              isRouteActive('admin.settings.*')
                ? 'bg-white text-[#063B11] dark:bg-[#86EFAC] dark:text-[#062910] shadow-md font-extrabold'
                : 'text-emerald-100/80 hover:bg-white/10 dark:hover:bg-white/5 hover:text-white dark:text-emerald-200/80 dark:hover:text-white'
            ]"
          >
            <!-- Settings Gear Icon -->
            <svg class="w-5 h-5 shrink-0 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
              <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
            <span>Settings</span>
          </Link>

          <Link
            v-if="can('view-audit-log')"
            :href="route('admin.audit-logs.index')"
            :class="[
              'flex items-center gap-3 px-4 py-2.5 rounded-xl font-bold transition-all duration-200',
              isRouteActive('admin.audit-logs.*')
                ? 'bg-white text-[#063B11] dark:bg-[#86EFAC] dark:text-[#062910] shadow-md font-extrabold'
                : 'text-emerald-100/80 hover:bg-white/10 dark:hover:bg-white/5 hover:text-white dark:text-emerald-200/80 dark:hover:text-white'
            ]"
          >
            <!-- Audit Log Clipboard Icon -->
            <svg class="w-5 h-5 shrink-0 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
            </svg>
            <span>Audit Logs</span>
          </Link>
        </div>

      </nav>
    </div>

    <!-- 3. PINNED BOTTOM FOOTER SECTION (NON-SCROLLABLE) -->
    <div class="relative z-10 p-4 pt-2 shrink-0 bg-[#063B11]/95 dark:bg-[#07220F]/95 backdrop-blur-xs border-t border-white/10 dark:border-white/5">
      <div
        class="bg-white/10 dark:bg-white/5 rounded-2xl p-4 shadow-inner border border-white/15 dark:border-white/10 backdrop-blur-md transition-colors duration-300"
      >
        <Link
          :href="route('home')"
          class="flex items-center gap-2.5 text-xs font-bold text-white dark:text-[#86EFAC] hover:text-[#F9C20C] dark:hover:text-[#F9C20C] transition-colors mb-3 group"
        >
          <svg
            xmlns="http://www.w3.org/2000/svg"
            class="h-4 w-4 shrink-0 group-hover:-translate-x-1 transition-transform"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor"
            stroke-width="2.5"
          >
            <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
          </svg>
          <span>Back to Public Website</span>
        </Link>

        <div
          class="flex items-center justify-between text-xs font-semibold text-emerald-200/80 dark:text-emerald-300/70 border-t border-white/10 dark:border-white/5 pt-2.5 transition-colors"
        >
          <span>School ID</span>
          <span class="text-white font-black tracking-wide">342512</span>
        </div>
      </div>
    </div>

  </aside>
</template>

<script setup>
import { computed } from 'vue'
import { Link, usePage } from '@inertiajs/vue3'

import sidebarBg from '@/../assets/img/newsidebarbg.png'
import schoolLogo from '@/../assets/img/logo_trans.png'

const page = usePage()
const unreadContactCount = computed(() => page.props.unreadContactCount ?? 0)

const can = (permission) => {
  const userPermissions = page.props.auth?.can || {}
  return !!userPermissions[permission]
}

const hasRole = (role) => {
  const userRoles = page.props.auth?.roles || []
  return userRoles.includes(role)
}

const isRouteActive = (pattern) => {
  if (!pattern) return false
  try {
    return route().current(pattern)
  } catch (e) {
    return false
  }
}
</script>

<style scoped>
.no-scrollbar::-webkit-scrollbar {
  display: none;
}

.no-scrollbar {
  -ms-overflow-style: none;
  scrollbar-width: none;
}

/* WATER DROPLET RIPPLE ANIMATION */
@keyframes waterDripRipple {
  0% {
    transform: scale(0.95);
    opacity: 0.8;
  }
  50% {
    opacity: 0.4;
  }
  100% {
    transform: scale(1.35);
    opacity: 0;
  }
}

.ripple-wave {
  animation: waterDripRipple 2.5s cubic-bezier(0.25, 0.8, 0.25, 1) infinite;
}
</style>