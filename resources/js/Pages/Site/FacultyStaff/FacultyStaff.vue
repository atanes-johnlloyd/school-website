<template>

    <Head title="Faculty & Staff - Salawag LMS" />

    <div
        class="min-h-screen bg-[#E8FFE8] font-['Inter'] text-slate-800 overflow-x-hidden flex flex-col justify-between">

        <div>
            <!-- OVERLAPPED HERO BANNER SECTION WITH FACULTY STATS -->
            <section
                class="relative w-full min-h-[65vh] md:min-h-[72vh] bg-[#004d08] text-white overflow-hidden shadow-md flex items-center">

                <!-- Overlapped Transparent Navbar Component -->
                <Navbar />

                <!-- Background Image Overlay with Green Tint & Gradient -->
                <div class="absolute inset-0 z-0 pointer-events-none overflow-hidden">
                    <img src="https://images.unsplash.com/photo-1524178232363-1fb2b075b655?auto=format&fit=crop&w=1920&q=80"
                        alt="Salawag Senior High School Faculty & Staff"
                        class="w-full h-full object-cover object-center opacity-20 mix-blend-overlay scale-105 animate-hero-zoom transition-transform duration-1000" />
                    <div class="absolute inset-0 bg-gradient-to-r from-[#004d08]/98 via-[#004d08]/88 to-[#003805]/75">
                    </div>
                </div>

                <!-- Animated Gradient Sheen -->
                <div
                    class="absolute inset-0 bg-gradient-to-r from-transparent via-white/5 to-transparent animate-sheen pointer-events-none z-0">
                </div>

                <!-- Ambient Glow Orbs -->
                <div
                    class="absolute -top-24 -left-24 w-96 h-96 bg-emerald-400/15 rounded-full blur-3xl pointer-events-none animate-pulse-slow">
                </div>
                <div
                    class="absolute -bottom-24 right-0 w-96 h-96 bg-amber-400/10 rounded-full blur-3xl pointer-events-none animate-pulse-slow delay-1000">
                </div>

                <!-- Hero Content Container -->
                <div
                    class="relative z-10 w-full max-w-[1500px] mx-auto px-6 sm:px-12 md:px-16 lg:px-24 pt-28 md:pt-36 pb-16 space-y-8">

                    <!-- Headline & Description -->
                    <div class="space-y-4 max-w-4xl">
                        <h1
                            class="font-['Anton'] text-5xl sm:text-6xl md:text-7xl lg:text-8xl tracking-tight uppercase leading-[1.02] text-white animate-fade-in-up">
                            Faculty & Staff Directory
                        </h1>

                        <p
                            class="text-sm sm:text-base md:text-lg text-emerald-100/90 font-normal leading-relaxed pt-1 max-w-3xl animate-fade-in-up animation-delay-200">
                            Meet the mentors, innovators, and certified DepEd educators guiding Salawag Senior High
                            School scholars toward college readiness, regional leadership, and technical excellence.
                        </p>
                    </div>

                    <!-- 4 Stat Highlight Cards Grid -->
                    <div
                        class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 pt-2 animate-fade-in-up animation-delay-400">

                        <!-- STAT 1: INSTRUCTIONAL CORE -->
                        <div
                            class="bg-white/10 backdrop-blur-md rounded-2xl p-5 border border-white/15 space-y-2 hover:bg-white/15 transition-all">
                            <div class="flex items-center justify-between text-amber-300">
                                <span class="text-xs font-bold uppercase tracking-wider text-amber-200/90">Instructional
                                    Core</span>
                                <svg class="w-5 h-5 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.72m12 0a5.971 5.971 0 00-.941-3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                                </svg>
                            </div>
                            <p class="font-['Anton'] text-4xl sm:text-5xl text-white tracking-wide">84</p>
                            <p class="text-xs text-emerald-100/80 font-medium">Full-Time Licensed Faculty</p>
                        </div>

                        <!-- STAT 2: MASTER CADRE -->
                        <div
                            class="bg-white/10 backdrop-blur-md rounded-2xl p-5 border border-white/15 space-y-2 hover:bg-white/15 transition-all">
                            <div class="flex items-center justify-between text-amber-300">
                                <span class="text-xs font-bold uppercase tracking-wider text-amber-200/90">Master
                                    Cadre</span>
                                <svg class="w-5 h-5 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M16.5 18.75h-9m9 0a3 3 0 003-3V8.25a3 3 0 00-3-3h-9a3 3 0 00-3 3v7.5a3 3 0 003 3m9 0v-1.5a1.5 1.5 0 00-1.5-1.5h-6a1.5 1.5 0 00-1.5 1.5v1.5m6-6.75a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z" />
                                </svg>
                            </div>
                            <p class="font-['Anton'] text-4xl sm:text-5xl text-white tracking-wide">16</p>
                            <p class="text-xs text-emerald-100/80 font-medium">DepEd Master Teachers</p>
                        </div>

                        <!-- STAT 3: ADMIN OPERATIONS -->
                        <div
                            class="bg-white/10 backdrop-blur-md rounded-2xl p-5 border border-white/15 space-y-2 hover:bg-white/15 transition-all">
                            <div class="flex items-center justify-between text-amber-300">
                                <span class="text-xs font-bold uppercase tracking-wider text-amber-200/90">Admin
                                    Operations</span>
                                <svg class="w-5 h-5 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 21a9 9 0 100-18 9 9 0 000 18z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3" />
                                </svg>
                            </div>
                            <p class="font-['Anton'] text-4xl sm:text-5xl text-white tracking-wide">12</p>
                            <p class="text-xs text-emerald-100/80 font-medium">Registrar & Support Personnel</p>
                        </div>

                        <!-- STAT 4: CLASS RATIO -->
                        <div
                            class="bg-white/10 backdrop-blur-md rounded-2xl p-5 border border-white/15 space-y-2 hover:bg-white/15 transition-all">
                            <div class="flex items-center justify-between text-amber-300">
                                <span class="text-xs font-bold uppercase tracking-wider text-amber-200/90">Class
                                    Ratio</span>
                                <svg class="w-5 h-5 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M2.25 18L9 11.25l4.306 4.307a11.95 11.95 0 015.814-5.519l2.74-1.22m0 0l-5.94-2.28m5.94 2.28l-2.28 5.941" />
                                </svg>
                            </div>
                            <p class="font-['Anton'] text-4xl sm:text-5xl text-white tracking-wide">1:28</p>
                            <p class="text-xs text-emerald-100/80 font-medium">DepEd Standard Optimal Ratio</p>
                        </div>

                    </div>

                </div>

            </section>

        </div>

        <!-- SEARCH BAR & CATEGORY FILTER TABS -->
        <section ref="searchRef" class="w-full max-w-[1500px] mx-auto px-6 sm:px-12 md:px-16 lg:px-24 pt-12 pb-6">

            <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-slate-200/80 space-y-6 transition-all duration-1000"
                :class="isSearchVisible ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-12'">

                <!-- Top Row: Search Bar & Active Count Badge -->
                <div class="flex flex-col md:flex-row items-center justify-between gap-4">

                    <!-- Search Input -->
                    <div class="relative w-full md:max-w-2xl">
                        <svg class="w-5 h-5 text-slate-400 absolute left-4 top-1/2 -translate-y-1/2" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                        </svg>
                        <input type="text" v-model="searchQuery"
                            placeholder="Search educator by name, strand, room, or subject specialization..."
                            class="w-full pl-12 pr-4 py-3 rounded-2xl bg-slate-50 border border-slate-200 text-sm font-medium text-slate-800 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-[#005506] focus:bg-white transition-all" />
                        <button v-if="searchQuery" @click="searchQuery = ''"
                            class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 text-xs font-bold">
                            ✕ Clear
                        </button>
                    </div>

                    <!-- Control Buttons -->
                    <div class="flex items-center gap-3 w-full md:w-auto justify-between md:justify-end">
                        <button @click="resetFilters"
                            class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-600 hover:text-[#005506] bg-slate-100 hover:bg-slate-200/80 px-4 py-2.5 rounded-xl transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                            </svg>
                            <span>Reset</span>
                        </button>

                        <span
                            class="inline-flex items-center gap-1.5 bg-emerald-100 text-[#005506] text-xs font-extrabold px-3.5 py-2 rounded-xl border border-emerald-200">
                            <span class="w-2 h-2 rounded-full bg-[#005506] animate-pulse"></span>
                            {{ filteredFaculty.length }} Active Profiles
                        </span>
                    </div>

                </div>

                <!-- Category Filter Tabs -->
                <div class="flex items-center gap-2 overflow-x-auto pb-2 scrollbar-none">
                    <button v-for="cat in categories" :key="cat.id" @click="activeCategory = cat.id"
                        class="px-4 py-2.5 rounded-xl text-xs font-bold whitespace-nowrap transition-all cursor-pointer"
                        :class="activeCategory === cat.id ? 'bg-[#005506] text-white shadow-md' : 'bg-slate-100 text-slate-600 hover:bg-slate-200/80'">
                        {{ cat.name }} ({{ getCategoryCount(cat.id) }})
                    </button>
                </div>

            </div>

        </section>

        <!-- FACULTY & STAFF DIRECTORY GRID -->
        <section ref="directoryRef" class="w-full max-w-[1500px] mx-auto px-6 sm:px-12 md:px-16 lg:px-24 py-8">

            <div class="transition-all duration-1000"
                :class="isDirectoryVisible ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-12'">

                <!-- Empty State -->
                <div v-if="filteredFaculty.length === 0"
                    class="text-center py-16 bg-white rounded-3xl border border-slate-200 space-y-4">
                    <div
                        class="w-16 h-16 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center mx-auto">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                        </svg>
                    </div>
                    <h3 class="font-bold text-lg text-[#005506]">No Educator Profiles Found</h3>
                    <p class="text-xs text-slate-500 max-w-sm mx-auto">No faculty members matched your filter criteria or
                        search query "{{ searchQuery }}".</p>
                    <button @click="resetFilters"
                        class="px-5 py-2 bg-[#005506] text-white font-bold text-xs rounded-xl shadow">Reset Search</button>
                </div>

                <!-- Responsive 4-Column Grid -->
                <div v-else class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

                    <div v-for="member in filteredFaculty" :key="member.id"
                        class="bg-white rounded-3xl p-6 shadow-sm border border-slate-200/80 flex flex-col justify-between space-y-5 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 relative overflow-hidden group">
                        <!-- Top Colored Accent Strip -->
                        <div class="absolute top-0 left-0 right-0 h-1.5" :class="member.badgeClass"></div>

                        <!-- Card Header: Badges & Room -->
                        <div class="space-y-4 pt-1">
                            <div class="flex items-center justify-between text-[11px] font-bold">
                                <span class="px-2.5 py-1 rounded-md uppercase tracking-wider" :class="member.badgeTagStyle">
                                    {{ member.categoryLabel }}
                                </span>
                                <span class="text-slate-500 flex items-center gap-1 font-semibold">
                                    <span class="w-2 h-2 rounded-full" :class="member.dotStyle"></span>
                                    {{ member.room }}
                                </span>
                            </div>

                            <!-- Educator Photo & Info -->
                            <div class="text-center space-y-2">
                                <div
                                    class="relative w-24 h-24 mx-auto rounded-full overflow-hidden border-2 border-emerald-100 shadow-sm group-hover:scale-105 transition-transform duration-300">
                                    <img :src="member.image" :alt="member.name"
                                        class="w-full h-full object-cover object-top" />
                                </div>

                                <div>
                                    <h3 class="font-bold text-lg text-[#005506] leading-snug">{{ member.name }}</h3>
                                    <p class="text-xs font-bold text-amber-800">{{ member.title }}</p>
                                    <p class="text-[11px] text-slate-500 leading-normal mt-1">{{ member.roleSubtitle }}</p>
                                </div>
                            </div>

                            <!-- Detail Attributes Box -->
                            <div class="bg-slate-50 rounded-2xl p-3.5 space-y-2 text-xs border border-slate-100">
                                <div v-if="member.advisory" class="flex items-start justify-between gap-2">
                                    <span class="text-slate-400 font-semibold text-[11px]">Advisory:</span>
                                    <span class="text-slate-800 font-bold text-right text-[11px]">{{ member.advisory
                                    }}</span>
                                </div>

                                <div v-if="member.consultation" class="flex items-start justify-between gap-2">
                                    <span class="text-slate-400 font-semibold text-[11px]">Consultation:</span>
                                    <span class="text-slate-800 font-semibold text-right text-[11px]">{{ member.consultation
                                    }}</span>
                                </div>

                                <div v-if="member.specialization"
                                    class="flex items-start justify-between gap-2 border-t border-slate-200/60 pt-1.5 mt-1">
                                    <span class="text-slate-400 font-semibold text-[11px]">Specialization:</span>
                                    <span class="text-[#005506] font-bold text-right text-[11px]">{{ member.specialization
                                    }}</span>
                                </div>

                                <div v-if="member.email"
                                    class="flex items-start justify-between gap-2 border-t border-slate-200/60 pt-1.5 mt-1">
                                    <span class="text-slate-400 font-semibold text-[11px]">Email:</span>
                                    <span class="text-[#005506] font-bold text-right text-[11px] truncate max-w-[150px]">{{
                                        member.email }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Action Button -->
                        <button @click="bookConsultation(member)"
                            class="w-full py-3 px-4 rounded-xl font-bold text-xs text-white shadow-md transition-all flex items-center justify-center gap-2 cursor-pointer active:scale-95"
                            :class="member.buttonClass">
                            <span>{{ member.buttonLabel || 'Book Session' }}</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                            </svg>
                        </button>

                    </div>

                </div>

            </div>

        </section>

        <!-- OPERATIONAL INQUIRIES: DEPARTMENT & SUPPORT OFFICE SERVICE HOURS -->
        <section ref="operationsRef" class="w-full max-w-[1500px] mx-auto px-6 sm:px-12 md:px-16 lg:px-24 py-12 md:py-16">

            <div class="transition-all duration-1000"
                :class="isOperationsVisible ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-12'">

                <!-- Header -->
                <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-10">
                    <div class="space-y-3 max-w-2xl">
                        <span class="text-xs font-extrabold uppercase tracking-widest text-amber-800 block">
                            OPERATIONAL INQUIRIES
                        </span>
                        <h2
                            class="font-['Anton'] text-3xl sm:text-4xl md:text-5xl text-[#005506] tracking-wide uppercase leading-tight">
                            Department & Support Office Service Hours
                        </h2>
                        <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                            Official operating schedules for on-campus verification, document releases, and parent
                            consultations.
                        </p>
                    </div>

                    <!-- DepEd Citizen's Charter Tag -->
                    <div
                        class="inline-flex items-center gap-2 text-xs font-bold text-[#005506] bg-emerald-100/70 px-4 py-2 rounded-xl border border-emerald-200/60 shrink-0">
                        <svg class="w-4 h-4 text-[#005506]" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>DepEd Citizen's Charter Standard Service Window</span>
                    </div>
                </div>

                <!-- 5-Card Responsive Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-5">

                    <!-- CARD 1: OFFICE OF THE REGISTRAR -->
                    <div
                        class="bg-white rounded-3xl p-6 shadow-sm border border-slate-200/80 flex flex-col justify-between space-y-6 hover:shadow-lg transition-all duration-300">
                        <div class="space-y-4">
                            <div
                                class="w-10 h-10 rounded-2xl bg-emerald-100 text-[#005506] flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15 9h3.75M15 12h3.75M15 15h3.75M4.5 19.5h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z" />
                                </svg>
                            </div>

                            <div class="space-y-1">
                                <h3 class="font-bold text-lg text-[#005506] leading-snug">
                                    Office of the Registrar
                                </h3>
                                <p class="text-xs text-slate-500">
                                    SF9/SF10, Diplomas, Transcripts
                                </p>
                            </div>
                        </div>

                        <div class="pt-4 border-t border-slate-100 space-y-2 text-xs">
                            <div>
                                <span class="font-bold text-slate-800 block">Bldg A • Ground Floor Rm 103</span>
                                <span class="text-slate-500 font-medium">Mon–Fri • 8:00 AM – 4:30 PM</span>
                            </div>
                            <a href="mailto:registrar.sshs@deped.gov.ph"
                                class="text-[#005506] font-bold block truncate hover:underline">
                                registrar.sshs@deped.gov.ph
                            </a>
                        </div>
                    </div>

                    <!-- CARD 2: GUIDANCE & COUNSELING -->
                    <div
                        class="bg-white rounded-3xl p-6 shadow-sm border border-slate-200/80 flex flex-col justify-between space-y-6 hover:shadow-lg transition-all duration-300">
                        <div class="space-y-4">
                            <div
                                class="w-10 h-10 rounded-2xl bg-amber-100 text-amber-800 flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                                </svg>
                            </div>

                            <div class="space-y-1">
                                <h3 class="font-bold text-lg text-[#005506] leading-snug">
                                    Guidance & Counseling
                                </h3>
                                <p class="text-xs text-slate-500">
                                    Counseling, Moral Support, GMRC
                                </p>
                            </div>
                        </div>

                        <div class="pt-4 border-t border-slate-100 space-y-2 text-xs">
                            <div>
                                <span class="font-bold text-slate-800 block">Bldg A • 2nd Floor Suite</span>
                                <span class="text-slate-500 font-medium">Mon–Fri • 7:30 AM – 5:00 PM</span>
                            </div>
                            <a href="mailto:guidance.sshs@deped.gov.ph"
                                class="text-[#005506] font-bold block truncate hover:underline">
                                guidance.sshs@deped.gov.ph
                            </a>
                        </div>
                    </div>

                    <!-- CARD 3: SCHOOL HEALTH & CLINIC -->
                    <div
                        class="bg-white rounded-3xl p-6 shadow-sm border border-slate-200/80 flex flex-col justify-between space-y-6 hover:shadow-lg transition-all duration-300">
                        <div class="space-y-4">
                            <div
                                class="w-10 h-10 rounded-2xl bg-emerald-100 text-[#005506] flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 9v6m3-3H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>

                            <div class="space-y-1">
                                <h3 class="font-bold text-lg text-[#005506] leading-snug">
                                    School Health & Clinic
                                </h3>
                                <p class="text-xs text-slate-500">
                                    First Aid, Annual Medical, Vax
                                </p>
                            </div>
                        </div>

                        <div class="pt-4 border-t border-slate-100 space-y-2 text-xs">
                            <div>
                                <span class="font-bold text-slate-800 block">Bldg C • Clinic Unit 1</span>
                                <span class="text-slate-500 font-medium">Mon–Fri • 7:00 AM – 5:00 PM</span>
                            </div>
                            <a href="mailto:clinic.342512@deped.gov.ph"
                                class="text-[#005506] font-bold block truncate hover:underline">
                                clinic.342512@deped.gov.ph
                            </a>
                        </div>
                    </div>

                    <!-- CARD 4: PTA SECRETARIAT -->
                    <div
                        class="bg-white rounded-3xl p-6 shadow-sm border border-slate-200/80 flex flex-col justify-between space-y-6 hover:shadow-lg transition-all duration-300">
                        <div class="space-y-4">
                            <div
                                class="w-10 h-10 rounded-2xl bg-slate-100 text-slate-800 flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.72m12 0a5.971 5.971 0 00-.941-3.197M15 6.75a3 3 0 11-6 0 3 3 0 000 6z" />
                                </svg>
                            </div>

                            <div class="space-y-1">
                                <h3 class="font-bold text-lg text-[#005506] leading-snug">
                                    PTA Secretariat
                                </h3>
                                <p class="text-xs text-slate-500">
                                    Parent Assembly & Community
                                </p>
                            </div>
                        </div>

                        <div class="pt-4 border-t border-slate-100 space-y-2 text-xs">
                            <div>
                                <span class="font-bold text-slate-800 block">Campus Activity Pavilion</span>
                                <span class="text-slate-500 font-medium">Wed & Sat • 9:00 AM – 2:00 PM</span>
                            </div>
                            <a href="mailto:ptasalahigh@gmail.com"
                                class="text-[#005506] font-bold block truncate hover:underline">
                                ptasalahigh@gmail.com
                            </a>
                        </div>
                    </div>

                    <!-- CARD 5: DISBURSING & CASHIER -->
                    <div
                        class="bg-white rounded-3xl p-6 shadow-sm border border-slate-200/80 flex flex-col justify-between space-y-6 hover:shadow-lg transition-all duration-300">
                        <div class="space-y-4">
                            <div
                                class="w-10 h-10 rounded-2xl bg-amber-100 text-amber-800 flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5h16.5a1.5 1.5 0 011.5 1.5v9a1.5 1.5 0 01-1.5 1.5H3.75a1.5 1.5 0 01-1.5-1.5v-9a1.5 1.5 0 011.5-1.5z" />
                                </svg>
                            </div>

                            <div class="space-y-1">
                                <h3 class="font-bold text-lg text-[#005506] leading-snug">
                                    Disbursing & Cashier
                                </h3>
                                <p class="text-xs text-slate-500">
                                    Voucher Program, MOOE Support
                                </p>
                            </div>
                        </div>

                        <div class="pt-4 border-t border-slate-100 space-y-2 text-xs">
                            <div>
                                <span class="font-bold text-slate-800 block">Admin Bldg • Window 2</span>
                                <span class="text-slate-500 font-medium">Mon–Thu • 8:30 AM – 3:30 PM</span>
                            </div>
                            <a href="mailto:finance.sshs@deped.gov.ph"
                                class="text-[#005506] font-bold block truncate hover:underline">
                                finance.sshs@deped.gov.ph
                            </a>
                        </div>
                    </div>

                </div>

            </div>

        </section>

        <!-- SHARED FOOTER -->
        <Footer class="m-0 p-0 block" />

    </div>
</template>

<script setup>
import { Head } from '@inertiajs/vue3'
import Navbar from '@/Components/Navbar.vue'
import Footer from '@/Components/Footer.vue'

import { ref, computed, onMounted, onUnmounted, nextTick } from 'vue'
import Swal from 'sweetalert2'

// State variables ready for API bindings
const searchQuery = ref('')
const activeCategory = ref('all')

/* -------- Section refs (new scroll-triggered animations) -------- */
const searchRef = ref(null)
const directoryRef = ref(null)
const operationsRef = ref(null)

const isSearchVisible = ref(false)
const isDirectoryVisible = ref(false)
const isOperationsVisible = ref(false)

let sectionObserver = null

onMounted(() => {
    if (typeof IntersectionObserver !== 'undefined') {
        sectionObserver = new IntersectionObserver(
            (entries) => {
                entries.forEach((entry) => {
                    if (entry.target === searchRef.value) {
                        isSearchVisible.value = entry.isIntersecting
                    } else if (entry.target === directoryRef.value) {
                        isDirectoryVisible.value = entry.isIntersecting
                    } else if (entry.target === operationsRef.value) {
                        isOperationsVisible.value = entry.isIntersecting
                    }
                })
            },
            { threshold: 0.12 }
        )

        nextTick(() => {
            if (searchRef.value) sectionObserver.observe(searchRef.value)
            if (directoryRef.value) sectionObserver.observe(directoryRef.value)
            if (operationsRef.value) sectionObserver.observe(operationsRef.value)
        })
    } else {
        // Fallback for very old browsers – reveal everything immediately
        isSearchVisible.value = true
        isDirectoryVisible.value = true
        isOperationsVisible.value = true
    }
})

onUnmounted(() => {
    if (sectionObserver) sectionObserver.disconnect()
})

const categories = [
    { id: 'all', name: 'All Faculty' },
    { id: 'stem', name: 'Academic: STEM' },
    { id: 'humss', name: 'Academic: HUMSS & Arts' },
    { id: 'abm', name: 'Academic: ABM & Entrep' },
    { id: 'techpro', name: 'TechPro Track (ICT/Agri)' },
    { id: 'guidance', name: 'Guidance & Welfare' }
]

// Reactive dataset containing all faculty members
const facultyList = ref([
    {
        id: 1,
        name: 'Dr. Ronaldo S. Cruz',
        title: 'Principal IV / School Head',
        roleSubtitle: 'Institutional Governance & DepEd Programs',
        category: 'leadership',
        categoryLabel: 'LEADERSHIP',
        room: 'Office Rm 101',
        image: 'https://images.unsplash.com/photo-1560250097-0b93528c311a?auto=format&fit=crop&w=400&q=80',
        email: 'r.cruz@deped.gov.ph',
        consultation: 'Tue & Thu • 1:00 PM - 3:30 PM',
        badgeClass: 'bg-[#005506]',
        badgeTagStyle: 'bg-emerald-100 text-[#005506]',
        dotStyle: 'bg-amber-400',
        buttonClass: 'bg-[#005506] hover:bg-[#003d06]',
        buttonLabel: 'Consultation'
    },
    {
        id: 2,
        name: 'Engr. Benjamin Bautista',
        title: 'Master Teacher II',
        roleSubtitle: 'STEM Strand Coordinator • Robotics Lead',
        category: 'stem',
        categoryLabel: 'STEM TRACK',
        room: 'Science Lab 204',
        image: 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=400&q=80',
        advisory: 'Grade 12 - Archimedes',
        consultation: 'Mon & Wed • 3:00 PM - 5:00 PM',
        specialization: 'General Physics & Mechatronics',
        badgeClass: 'bg-[#005506]',
        badgeTagStyle: 'bg-emerald-100 text-[#005506]',
        dotStyle: 'bg-amber-400',
        buttonClass: 'bg-[#005506] hover:bg-[#003d06]',
        buttonLabel: 'Book Session'
    },
    {
        id: 3,
        name: 'Dr. Maricel Santos',
        title: 'Master Teacher I',
        roleSubtitle: 'General Chemistry & Biology',
        category: 'stem',
        categoryLabel: 'STEM TRACK',
        room: 'Science Annex 102',
        image: 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?auto=format&fit=crop&w=400&q=80',
        advisory: 'Grade 11 - Mendeleev',
        consultation: 'Wed & Fri • 2:00 PM - 4:00 PM',
        specialization: 'Cavite Flora Bioactive Screening',
        badgeClass: 'bg-[#005506]',
        badgeTagStyle: 'bg-emerald-100 text-[#005506]',
        dotStyle: 'bg-amber-400',
        buttonClass: 'bg-[#005506] hover:bg-[#003d06]',
        buttonLabel: 'Book Session'
    },
    {
        id: 4,
        name: 'Prof. Danilo Mendoza',
        title: 'Senior Teacher III',
        roleSubtitle: 'Financial Literacy & Business Math Lead',
        category: 'abm',
        categoryLabel: 'ABM STRAND',
        room: 'Bldg B Rm 301',
        image: 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=400&q=80',
        advisory: 'Grade 12 - SyCip',
        consultation: 'Tue & Thu • 9:30 AM - 11:30 AM',
        specialization: 'SSHS Youth Agri-Biz Incubator',
        badgeClass: 'bg-amber-500',
        badgeTagStyle: 'bg-amber-100 text-amber-900',
        dotStyle: 'bg-amber-400',
        buttonClass: 'bg-[#005506] hover:bg-[#003d06]',
        buttonLabel: 'Book Session'
    },
    {
        id: 5,
        name: 'Gng. Clara Villanueva',
        title: 'Master Teacher I',
        roleSubtitle: 'Kagawaran ng Wika at Panitikan',
        category: 'humss',
        categoryLabel: 'HUMSS & WIKA',
        room: 'Library Research Desk',
        image: 'https://images.unsplash.com/photo-1580894732413-a7235a915998?auto=format&fit=crop&w=400&q=80',
        advisory: 'Grade 11 - Balagtas',
        consultation: 'Lunes & Miyerkules • 1:30 PM - 3:30 PM',
        specialization: 'Tagapayo, Ang Tanglaw Gazette',
        badgeClass: 'bg-rose-500',
        badgeTagStyle: 'bg-rose-100 text-rose-900',
        dotStyle: 'bg-amber-400',
        buttonClass: 'bg-[#005506] hover:bg-[#003d06]',
        buttonLabel: 'Makipagpulong'
    },
    {
        id: 6,
        name: 'Engr. Ricardo Morales',
        title: 'TVL-ICT Coordinator',
        roleSubtitle: 'Cisco Academy & National TVL Assessor',
        category: 'techpro',
        categoryLabel: 'TECHPRO TVL',
        room: 'ICT Innovation Lab',
        image: 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&w=400&q=80',
        advisory: 'Grade 12 - Turing',
        consultation: 'Mon to Thu • 3:30 PM - 5:00 PM',
        specialization: 'DepEd TVL NC II Trainer',
        badgeClass: 'bg-emerald-600',
        badgeTagStyle: 'bg-emerald-100 text-[#005506]',
        dotStyle: 'bg-amber-400',
        buttonClass: 'bg-[#005506] hover:bg-[#003d06]',
        buttonLabel: 'Book Lab Time'
    },
    {
        id: 7,
        name: 'Mrs. Lorenza Mercado, RPM',
        title: 'Guidance Counselor III',
        roleSubtitle: 'Child Protection Focal Person',
        category: 'guidance',
        categoryLabel: 'STUDENT WELFARE',
        room: 'Guidance Suite 2F',
        image: 'https://images.unsplash.com/photo-1544005313-94ddf0286df2?auto=format&fit=crop&w=400&q=80',
        consultation: 'Mon-Fri • 8:00 AM - 4:30 PM',
        specialization: 'Strict Privacy Guaranteed',
        badgeClass: 'bg-amber-400',
        badgeTagStyle: 'bg-amber-100 text-amber-900',
        dotStyle: 'bg-emerald-600',
        buttonClass: 'bg-amber-400 text-slate-950 hover:bg-amber-300 font-extrabold',
        buttonLabel: 'Safe Counseling'
    },
    {
        id: 8,
        name: 'Mr. Arthur Alcantara',
        title: 'Teacher II',
        roleSubtitle: 'Creative Non-Fiction & Oral Communication',
        category: 'humss',
        categoryLabel: 'HUMSS STRAND',
        room: 'Media Studio',
        image: 'https://images.unsplash.com/photo-1519085360753-af0119f7cbe7?auto=format&fit=crop&w=400&q=80',
        advisory: 'Grade 11 - Joaquin',
        consultation: 'Tue & Thu • 1:00 PM - 3:00 PM',
        specialization: 'Debate & Theater Guild',
        badgeClass: 'bg-rose-500',
        badgeTagStyle: 'bg-rose-100 text-rose-900',
        dotStyle: 'bg-amber-400',
        buttonClass: 'bg-[#005506] hover:bg-[#003d06]',
        buttonLabel: 'Book Session'
    }
])

// Computed Property to filter faculty dynamically
const filteredFaculty = computed(() => {
    return facultyList.value.filter(item => {
        const matchesCategory = activeCategory.value === 'all' || item.category === activeCategory.value || (activeCategory.value === 'leadership' && item.category === 'leadership')
        const q = searchQuery.value.toLowerCase().trim()
        const matchesQuery = !q ||
            item.name.toLowerCase().includes(q) ||
            item.title.toLowerCase().includes(q) ||
            (item.specialization && item.specialization.toLowerCase().includes(q)) ||
            (item.room && item.room.toLowerCase().includes(q)) ||
            (item.advisory && item.advisory.toLowerCase().includes(q))

        return matchesCategory && matchesQuery
    })
})

const getCategoryCount = (catId) => {
    if (catId === 'all') return facultyList.value.length
    return facultyList.value.filter(item => item.category === catId).length
}

const resetFilters = () => {
    searchQuery.value = ''
    activeCategory.value = 'all'
}

const bookConsultation = (member) => {
    Swal.fire({
        title: `Schedule Consultation`,
        text: `Request a meeting slot with ${member.name} (${member.title}) during office hours: ${member.consultation || 'By Appointment'}?`,
        icon: 'info',
        showCancelButton: true,
        confirmButtonColor: '#005506',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Proceed to Booking',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: 'Booking Request Sent',
                text: 'Your consultation request has been logged. Check your student portal notifications for confirmation.',
                icon: 'success',
                confirmButtonColor: '#005506'
            })
        }
    })
}

const openChildProtectionGuide = () => {
    Swal.fire({
        title: 'DepEd Child Protection Policy',
        text: 'All interactions follow DepEd Order No. 40, s. 2012 guidelines to protect learners and guarantee academic integrity.',
        icon: 'shield',
        confirmButtonColor: '#005506'
    })
}

const officeHoursList = ref([
  {
    id: 'registrar',
    title: 'Office of the Registrar',
    subtitle: 'SF9/SF10, Diplomas, Transcripts',
    location: 'Bldg A • Ground Floor Rm 103',
    schedule: 'Mon–Fri • 8:00 AM – 4:30 PM',
    email: 'registrar.sshs@deped.gov.ph',
    bgIcon: 'bg-emerald-100 text-[#005506]'
  },
  {
    id: 'guidance',
    title: 'Guidance & Counseling',
    subtitle: 'Counseling, Moral Support, GMRC',
    location: 'Bldg A • 2nd Floor Suite',
    schedule: 'Mon–Fri • 7:30 AM – 5:00 PM',
    email: 'guidance.sshs@deped.gov.ph',
    bgIcon: 'bg-amber-100 text-amber-800'
  },
  {
    id: 'clinic',
    title: 'School Health & Clinic',
    subtitle: 'First Aid, Annual Medical, Vax',
    location: 'Bldg C • Clinic Unit 1',
    schedule: 'Mon–Fri • 7:00 AM – 5:00 PM',
    email: 'clinic.342512@deped.gov.ph',
    bgIcon: 'bg-emerald-100 text-[#005506]'
  },
  {
    id: 'pta',
    title: 'PTA Secretariat',
    subtitle: 'Parent Assembly & Community',
    location: 'Campus Activity Pavilion',
    schedule: 'Wed & Sat • 9:00 AM – 2:00 PM',
    email: 'ptasalahigh@gmail.com',
    bgIcon: 'bg-slate-100 text-slate-800'
  },
  {
    id: 'cashier',
    title: 'Disbursing & Cashier',
    subtitle: 'Voucher Program, MOOE Support',
    location: 'Admin Bldg • Window 2',
    schedule: 'Mon–Thu • 8:30 AM – 3:30 PM',
    email: 'finance.sshs@deped.gov.ph',
    bgIcon: 'bg-amber-100 text-amber-800'
  }
])
</script>

<style scoped>
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes heroZoom {

    0%,
    100% {
        transform: scale(1);
    }

    50% {
        transform: scale(1.08);
    }
}

@keyframes pulseSlow {

    0%,
    100% {
        opacity: 0.3;
    }

    50% {
        opacity: 0.7;
    }
}

@keyframes sheenMove {
    0% {
        transform: translateX(-100%);
    }

    100% {
        transform: translateX(200%);
    }
}

.animate-fade-in-up {
    animation: fadeInUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}

.animate-hero-zoom {
    animation: heroZoom 20s ease-in-out infinite;
}

.animate-pulse-slow {
    animation: pulseSlow 6s ease-in-out infinite;
}

.animate-sheen {
    animation: sheenMove 5s ease-in-out infinite;
}

.animation-delay-200 {
    animation-delay: 0.2s;
    animation-fill-mode: backwards;
}

.animation-delay-400 {
    animation-delay: 0.4s;
    animation-fill-mode: backwards;
}
</style>