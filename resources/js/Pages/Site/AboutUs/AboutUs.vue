<template>
  <Head :title="`About Us - ${school.name}`" />

  <div class="min-h-screen bg-[#E8FFE8] font-['Inter'] text-slate-800 overflow-x-hidden flex flex-col justify-between">
    <div>
      <!-- HERO -->
      <section class="relative w-full min-h-[65vh] md:min-h-[72vh] bg-[#004d08] text-white overflow-hidden shadow-md flex items-center">
        <Navbar />

        <div class="absolute inset-0 z-0 pointer-events-none overflow-hidden">
          <img src="https://images.unsplash.com/photo-1577896851231-70ef18881754?auto=format&fit=crop&w=1920&q=80"
            :alt="`${school.name} Campus`"
            class="w-full h-full object-cover object-center opacity-25 mix-blend-overlay scale-105 animate-hero-zoom transition-transform duration-1000" />
          <div class="absolute inset-0 bg-gradient-to-r from-[#004d08]/98 via-[#004d08]/88 to-[#003805]/75"></div>
        </div>

        <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/5 to-transparent animate-sheen pointer-events-none z-0"></div>
        <div class="absolute -top-24 -left-24 w-96 h-96 bg-emerald-400/15 rounded-full blur-3xl pointer-events-none animate-pulse-slow"></div>
        <div class="absolute -bottom-24 right-0 w-96 h-96 bg-amber-400/10 rounded-full blur-3xl pointer-events-none animate-pulse-slow delay-1000"></div>

        <div class="relative z-10 w-full max-w-[1500px] mx-auto px-6 sm:px-12 md:px-16 lg:px-24 pt-28 md:pt-36 pb-16 space-y-6">
          <div class="space-y-4 max-w-4xl">
            <h1 class="font-['Anton'] text-5xl sm:text-6xl md:text-7xl lg:text-8xl tracking-tight uppercase leading-[1.02] text-white animate-fade-in-up">
              A Decade of Purpose.<br />
              <span class="text-amber-400 inline-block animate-float-soft">A Legacy of Filipino Excellence.</span>
            </h1>
            <p class="text-sm sm:text-base md:text-lg text-emerald-100/90 font-normal leading-relaxed pt-2 max-w-3xl animate-fade-in-up animation-delay-200">
              Discover the founding narrative, civic mission, and heraldic identity of {{ school.name }}—nurturing
              empowered Salawagueño scholars dedicated to nation-building, technical innovation, and communal pride.
            </p>
          </div>
        </div>
      </section>

      <!-- PILLARS -->
      <section ref="pillarsRef" class="w-full max-w-[1500px] mx-auto px-6 sm:px-12 md:px-16 lg:px-24 py-16 md:py-24">
        <div class="transition-all duration-1000"
          :class="isPillarsVisible ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-12'">
          <div class="mb-10 sm:mb-14">
            <span class="text-xs font-extrabold uppercase tracking-widest text-[#005506] block mb-2">
              DEPED MANDATE & INSTITUTIONAL BLUEPRINT
            </span>
            <h2 class="font-['Anton'] text-3xl sm:text-4xl md:text-5xl text-[#005506] tracking-wide uppercase">
              The Pillars of Salawagueño Formation
            </h2>
          </div>

          <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-stretch">
            <!-- CARD 01 -->
            <div class="bg-white rounded-3xl p-8 sm:p-10 shadow-sm border border-slate-200/80 relative flex flex-col justify-between overflow-hidden hover:shadow-xl transition-all duration-300">
              <div class="absolute top-0 left-0 right-0 h-2 bg-gradient-to-r from-[#005506] to-amber-400"></div>
              <div class="space-y-6">
                <div class="flex items-center justify-between">
                  <div class="inline-flex items-center gap-2 bg-[#eaf5ed] text-[#005506] text-xs font-bold px-3.5 py-1.5 rounded-full">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                      <path stroke-linecap="round" stroke-linejoin="round"
                        d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                    </svg>
                    <span>The DepEd Vision</span>
                  </div>
                  <span class="text-slate-300 font-extrabold text-lg">01</span>
                </div>
                <div class="space-y-4">
                  <h3 class="font-bold text-2xl sm:text-3xl text-[#005506] leading-snug">A Vision of Empowered Nation-Builders</h3>
                  <blockquote class="text-slate-600 text-sm sm:text-base italic leading-relaxed pt-1">
                    "We dream of Filipinos who passionately love their country and whose values and
                    competencies enable them to realize their full potential and contribute meaningfully
                    to building the nation. As a learner-centered public institution, the Department of
                    Education continuously improves itself to better serve its stakeholders."
                  </blockquote>
                </div>
              </div>
            </div>

            <!-- CARD 02 -->
            <div class="bg-white rounded-3xl p-8 sm:p-10 shadow-sm border border-slate-200/80 relative flex flex-col justify-between overflow-hidden hover:shadow-xl transition-all duration-300">
              <div class="absolute top-0 left-0 right-0 h-2 bg-gradient-to-r from-amber-400 to-[#005506]"></div>
              <div class="space-y-6">
                <div class="flex items-center justify-between">
                  <div class="inline-flex items-center gap-2 bg-amber-50 text-amber-800 text-xs font-bold px-3.5 py-1.5 rounded-full">
                    <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9 9 0 100-18 9 9 0 000 18z" />
                      <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3" />
                    </svg>
                    <span>The DepEd Mission</span>
                  </div>
                  <span class="text-slate-300 font-extrabold text-lg">02</span>
                </div>
                <div class="space-y-3">
                  <h3 class="font-bold text-2xl sm:text-3xl text-[#005506] leading-snug">Uncompromising Public Education</h3>
                  <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                    To protect and promote the right of every Filipino to quality, equitable,
                    culture-based, and complete basic education where:
                  </p>
                </div>
                <ul class="space-y-3.5 pt-1">
                  <li class="flex items-start gap-3 text-xs sm:text-sm text-slate-700 leading-normal">
                    <div class="w-5 h-5 rounded-full bg-emerald-100 text-[#005506] flex items-center justify-center shrink-0 mt-0.5">
                      <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                    </div>
                    <span><strong class="font-bold text-[#005506]">Students</strong> learn in a child-friendly, gender-sensitive, safe, and motivating learning environment.</span>
                  </li>
                  <li class="flex items-start gap-3 text-xs sm:text-sm text-slate-700 leading-normal">
                    <div class="w-5 h-5 rounded-full bg-emerald-100 text-[#005506] flex items-center justify-center shrink-0 mt-0.5">
                      <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                    </div>
                    <span><strong class="font-bold text-[#005506]">Teachers</strong> facilitate learning and constantly nurture every learner's critical curiosity.</span>
                  </li>
                  <li class="flex items-start gap-3 text-xs sm:text-sm text-slate-700 leading-normal">
                    <div class="w-5 h-5 rounded-full bg-emerald-100 text-[#005506] flex items-center justify-center shrink-0 mt-0.5">
                      <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                    </div>
                    <span><strong class="font-bold text-[#005506]">Administrators & Staff</strong> steward an enabling and supportive atmosphere for effective school governance.</span>
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- SEAL -->
      <section ref="sealRef" class="w-full max-w-[1500px] mx-auto px-6 sm:px-12 md:px-16 lg:px-24 py-16">
        <div class="transition-all duration-1000"
          :class="isSealVisible ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-12'">
          <div class="text-center space-y-3 max-w-3xl mx-auto mb-14">
            <span class="text-xs font-extrabold uppercase tracking-widest text-[#005506] block">HERALDIC ICONOGRAPHY</span>
            <h2 class="font-['Anton'] text-3xl sm:text-4xl md:text-5xl text-[#005506] tracking-wide uppercase leading-tight">
              The {{ school.name.split(' ')[0] }} Seal of Excellence
            </h2>
            <p class="text-xs sm:text-sm text-slate-600 leading-relaxed max-w-2xl mx-auto">
              Adopted upon the institution's establishment in 2016, our emblem synthesizes public educational
              stewardship, academic rigor, and eternal hope for Salawagueño youth.
            </p>
          </div>

          <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center max-w-6xl mx-auto">
            <div class="lg:col-span-4 space-y-6">
              <div class="bg-white rounded-3xl p-6 sm:p-7 shadow-sm border border-slate-200/80 space-y-3 hover:shadow-md transition-shadow">
                <div class="flex items-center gap-3">
                  <span class="w-8 h-8 rounded-full bg-amber-100 text-amber-800 font-bold text-xs flex items-center justify-center shrink-0">A</span>
                  <h3 class="font-bold text-lg text-[#005506]">Ang Tanglaw ng Karunungan</h3>
                </div>
                <p class="text-xs text-slate-600 leading-relaxed">
                  <strong class="text-slate-900">The Torch of Knowledge:</strong> Situated centrally, the golden flame symbolizes intellectual enlightenment.
                </p>
              </div>
              <div class="bg-white rounded-3xl p-6 sm:p-7 shadow-sm border border-slate-200/80 space-y-3 hover:shadow-md transition-shadow">
                <div class="flex items-center gap-3">
                  <span class="w-8 h-8 rounded-full bg-emerald-100 text-[#005506] font-bold text-xs flex items-center justify-center shrink-0">B</span>
                  <h3 class="font-bold text-lg text-[#005506]">Ang Bukas na Aklat</h3>
                </div>
                <p class="text-xs text-slate-600 leading-relaxed">
                  <strong class="text-slate-900">The Open Book:</strong> The deep green folios represent the boundless reservoir of basic education.
                </p>
              </div>
            </div>

            <div class="lg:col-span-4 flex flex-col items-center justify-center space-y-6 my-4 lg:my-0">
              <div class="relative w-64 sm:w-80 h-64 sm:h-80 flex items-center justify-center group">
                <div class="absolute inset-0 bg-emerald-300/20 rounded-full blur-2xl group-hover:bg-amber-300/30 transition-all duration-500"></div>
                <img :src="SSHSLogo" :alt="`${school.name} Seal`"
                  class="relative z-10 w-full h-full object-contain drop-shadow-xl hover:scale-105 transition-transform duration-300" />
              </div>
            </div>

            <div class="lg:col-span-4 space-y-6">
              <div class="bg-white rounded-3xl p-6 sm:p-7 shadow-sm border border-slate-200/80 space-y-3 hover:shadow-md transition-shadow">
                <div class="flex items-center gap-3">
                  <span class="w-8 h-8 rounded-full bg-amber-100 text-amber-800 font-bold text-xs flex items-center justify-center shrink-0">C</span>
                  <h3 class="font-bold text-lg text-[#005506]">Ang Tatlong Bituin</h3>
                </div>
                <p class="text-xs text-slate-600 leading-relaxed">
                  <strong class="text-slate-900">Luzon, Visayas, Mindanao:</strong> Reaffirming cultural inclusivity and our shared destiny under the Philippine Republic.
                </p>
              </div>
              <div class="bg-white rounded-3xl p-6 sm:p-7 shadow-sm border border-slate-200/80 space-y-3 hover:shadow-md transition-shadow">
                <div class="flex items-center gap-3">
                  <span class="w-8 h-8 rounded-full bg-emerald-100 text-[#005506] font-bold text-xs flex items-center justify-center shrink-0">D</span>
                  <h3 class="font-bold text-lg text-[#005506]">Lunti at Ginto (Tinctures)</h3>
                </div>
                <p class="text-xs text-slate-600 leading-relaxed">
                  <strong class="text-slate-900">Green & Gold:</strong> Deep Forest Green evokes the fertile lands of Dasmariñas; Bright Gold heralds academic honor.
                </p>
              </div>
            </div>
          </div>

          <div class="mt-12 max-w-5xl mx-auto bg-white rounded-2xl p-6 shadow-sm border border-slate-200/80 grid grid-cols-2 md:grid-cols-4 gap-6 text-center">
            <div class="space-y-1 md:border-r border-slate-100 last:border-0">
              <span class="text-[10px] font-extrabold uppercase tracking-widest text-slate-400 block">YEAR ESTABLISHED</span>
              <p class="font-bold text-slate-800 text-sm sm:text-base">June 2026</p>
            </div>
            <div class="space-y-1 md:border-r border-slate-100 last:border-0">
              <span class="text-[10px] font-extrabold uppercase tracking-widest text-slate-400 block">OFFICIAL DIVISION</span>
              <p class="font-bold text-[#005506] text-sm sm:text-base">Schools Division of Cavite</p>
            </div>
            <div class="space-y-1 md:border-r border-slate-100 last:border-0">
              <span class="text-[10px] font-extrabold uppercase tracking-widest text-slate-400 block">DEPED SCHOOL ID</span>
              <p class="font-bold text-slate-800 text-sm sm:text-base">342512</p>
            </div>
            <div class="space-y-1">
              <span class="text-[10px] font-extrabold uppercase tracking-widest text-slate-400 block">ACTIVE FACULTY</span>
              <p class="font-bold text-[#005506] text-sm sm:text-base">{{ faculty_count }} Educators</p>
            </div>
          </div>
        </div>
      </section>

      <!-- TIMELINE -->
      <section ref="timelineSection" class="w-full max-w-[1500px] mx-auto px-6 sm:px-12 md:px-16 lg:px-24 py-16 md:py-24 overflow-hidden">
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-16">
          <div class="space-y-3 max-w-2xl">
            <span class="text-xs font-extrabold uppercase tracking-widest text-[#005506] block">DECADE OF PROGRESS</span>
            <h2 class="font-['Anton'] text-3xl sm:text-4xl md:text-5xl text-[#005506] tracking-wide uppercase leading-tight">
              Our Journey: From Pioneer Vision to Center of Excellence
            </h2>
          </div>
          <p class="text-xs sm:text-sm text-slate-500 max-w-md leading-relaxed">
            A decade of continuous infrastructure development, curriculum enhancements, and faculty advancement
            in Salawag, Dasmariñas.
          </p>
        </div>

        <div class="relative max-w-5xl mx-auto">
          <div class="absolute left-1/2 top-0 bottom-0 w-0.5 bg-emerald-200/80 -translate-x-1/2 hidden md:block"></div>
          <div class="space-y-12 md:space-y-16">

            <!-- ITEM 1 -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center relative">
              <div class="absolute left-1/2 top-6 -translate-x-1/2 w-4 h-4 rounded-full bg-[#005506] border-4 border-[#E8FFE8] z-10 hidden md:block"></div>
              <div data-animate="slide-left" class="timeline-item opacity-0 space-y-3 md:text-right md:pr-10">
                <span class="inline-block bg-emerald-100 text-[#005506] text-xs font-black px-3 py-1 rounded-full uppercase tracking-wider">🚩 2016</span>
                <h3 class="font-bold text-xl sm:text-2xl text-[#005506]">Foundation & Pioneer Cohort</h3>
                <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                  {{ school.name }} officially opened its gates under DepEd Order guidelines to accommodate the pioneer
                  K–12 cohort in Dasmariñas, welcoming 320 Grade 11 scholars.
                </p>
              </div>
              <div data-animate="slide-right" class="timeline-item opacity-0 md:pl-10">
                <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-200/80 flex items-center gap-4">
                  <div class="w-12 h-12 rounded-xl bg-amber-100 text-amber-800 flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.72m12 0a5.971 5.971 0 00-.941-3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" /></svg>
                  </div>
                  <div>
                    <h4 class="font-bold text-slate-900 text-sm sm:text-base">320 Founding Scholars</h4>
                    <p class="text-xs text-slate-500">Golden City campus annex temporary buildings</p>
                  </div>
                </div>
              </div>
            </div>

            <!-- ITEM 2 -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center relative">
              <div class="absolute left-1/2 top-6 -translate-x-1/2 w-4 h-4 rounded-full bg-[#005506] border-4 border-[#E8FFE8] z-10 hidden md:block"></div>
              <div data-animate="slide-left" class="timeline-item opacity-0 md:pr-10 order-2 md:order-1">
                <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-200/80 flex items-center gap-4">
                  <div class="w-12 h-12 rounded-xl bg-emerald-100 text-[#005506] flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3a1.5 1.5 0 011.5-1.5h3a1.5 1.5 0 011.5 1.5v3" /></svg>
                  </div>
                  <div>
                    <h4 class="font-bold text-slate-900 text-sm sm:text-base">4-Storey School Building Complex</h4>
                    <p class="text-xs text-slate-500">16 new classrooms & 4 high-spec laboratories</p>
                  </div>
                </div>
              </div>
              <div data-animate="slide-right" class="timeline-item opacity-0 space-y-3 md:pl-10 order-1 md:order-2">
                <span class="inline-block bg-emerald-100 text-[#005506] text-xs font-black px-3 py-1 rounded-full uppercase tracking-wider">🏛️ 2018</span>
                <h3 class="font-bold text-xl sm:text-2xl text-[#005506]">Academic Annex & Laboratory Wing</h3>
                <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                  Inauguration of the four-storey modern classroom building equipped with specialized Chemistry,
                  Physics, and Computer Systems laboratories.
                </p>
              </div>
            </div>

            <!-- ITEM 3 -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center relative">
              <div class="absolute left-1/2 top-6 -translate-x-1/2 w-4 h-4 rounded-full bg-[#005506] border-4 border-[#E8FFE8] z-10 hidden md:block"></div>
              <div data-animate="slide-left" class="timeline-item opacity-0 space-y-3 md:text-right md:pr-10">
                <span class="inline-block bg-amber-100 text-amber-900 text-xs font-black px-3 py-1 rounded-full uppercase tracking-wider">📡 2021</span>
                <h3 class="font-bold text-xl sm:text-2xl text-[#005506]">Cavite Model for Blended Learning</h3>
                <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                  Commended by the DepEd Division of Cavite for pioneering the "Salawag E-Hub" and hybrid modular delivery.
                </p>
              </div>
              <div data-animate="slide-right" class="timeline-item opacity-0 md:pl-10">
                <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-200/80 flex items-center gap-4">
                  <div class="w-12 h-12 rounded-xl bg-emerald-100 text-[#005506] flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 17.25v1.007a3 3 0 01-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0115 18.257V17.25m6-12V15a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 15V5.25A2.25 2.25 0 015.25 3h13.5A2.25 2.25 0 0121 5.25z" /></svg>
                  </div>
                  <div>
                    <h4 class="font-bold text-slate-900 text-sm sm:text-base">Zero-Drop Blended Network</h4>
                    <p class="text-xs text-slate-500">Division Benchmark for Hybrid Learning</p>
                  </div>
                </div>
              </div>
            </div>

            <!-- ITEM 4 -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center relative">
              <div class="absolute left-1/2 top-6 -translate-x-1/2 w-4 h-4 rounded-full bg-[#005506] border-4 border-[#E8FFE8] z-10 hidden md:block"></div>
              <div data-animate="slide-left" class="timeline-item opacity-0 md:pr-10 order-2 md:order-1">
                <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-200/80 flex items-center gap-4">
                  <div class="w-12 h-12 rounded-xl bg-amber-100 text-amber-800 flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 18.75h-9m9 0a3 3 0 003-3V8.25a3 3 0 00-3-3h-9a3 3 0 00-3 3v7.5a3 3 0 003 3m9 0v-1.5a1.5 1.5 0 00-1.5-1.5h-6a1.5 1.5 0 00-1.5 1.5v1.5m6-6.75a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z" /></svg>
                  </div>
                  <div>
                    <h4 class="font-bold text-slate-900 text-sm sm:text-base">DepEd TVL National Certification Center</h4>
                    <p class="text-xs text-slate-500">Computer Systems & Technical Skills Training</p>
                  </div>
                </div>
              </div>
              <div data-animate="slide-right" class="timeline-item opacity-0 space-y-3 md:pl-10 order-1 md:order-2">
                <span class="inline-block bg-emerald-100 text-[#005506] text-xs font-black px-3 py-1 rounded-full uppercase tracking-wider">🛡️ 2023</span>
                <h3 class="font-bold text-xl sm:text-2xl text-[#005506]">Expanded Technical & Vocational Excellence</h3>
                <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                  Recognized as a premier Assessment & Competency hub for Technical-Vocational-Livelihood certifications.
                </p>
              </div>
            </div>

            <!-- ITEM 5 -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center relative">
              <div class="absolute left-1/2 top-6 -translate-x-1/2 w-4 h-4 rounded-full bg-amber-400 border-4 border-[#E8FFE8] z-10 hidden md:block"></div>
              <div data-animate="slide-left" class="timeline-item opacity-0 space-y-3 md:text-right md:pr-10">
                <span class="inline-block bg-amber-400 text-slate-950 text-xs font-black px-3.5 py-1.5 rounded-full uppercase tracking-wider shadow-sm">🏆 2026 • Present</span>
                <h3 class="font-bold text-xl sm:text-2xl text-[#005506]">Strengthened SHS Pilot School</h3>
                <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                  Selected as one of Cavite Division's core pilot institutions for the enhanced Senior High School Framework.
                </p>
              </div>
              <div data-animate="slide-right" class="timeline-item opacity-0 md:pl-10">
                <div class="bg-white rounded-2xl p-5 shadow-sm border border-amber-300/80 flex items-center gap-4">
                  <div class="w-12 h-12 rounded-xl bg-amber-400 text-slate-950 flex items-center justify-center shrink-0 shadow-sm">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.436 60.436 0 01-.491-6.347A48.627 48.627 0 0112 2.09c4.787 0 9.227 1.48 12.983 4.025a60.316 60.316 0 01-.491 6.347m-15.482 0a50.57 50.57 0 00-2.658 8.14c-.19.786.393 1.543 1.189 1.543h18.358c.796 0 1.38-.757 1.189-1.543a50.53 50.53 0 00-2.658-8.14m-15.482 0A50.697 50.697 0 0112 13.489a50.702 50.702 0 017.743-3.342" /></svg>
                  </div>
                  <div>
                    <h4 class="font-bold text-slate-900 text-sm sm:text-base">Division Pilot Center</h4>
                    <p class="text-xs text-slate-500">Enhanced SHS Curriculum Implementation</p>
                  </div>
                </div>
              </div>
            </div>

          </div>
        </div>
      </section>

      <!-- LEADERSHIP -->
      <section ref="leadershipRef" class="w-full max-w-[1500px] mx-auto px-6 sm:px-12 md:px-16 lg:px-24 py-16 md:py-24">
        <div class="transition-all duration-1000"
          :class="isLeadershipVisible ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-12'">
          <div class="mb-12">
            <span class="text-xs font-extrabold uppercase tracking-widest text-[#005506] block mb-2">EXECUTIVE STEWARDSHIP</span>
            <h2 class="font-['Anton'] text-3xl sm:text-4xl md:text-5xl text-[#005506] tracking-wide uppercase">Office of the Principal</h2>
          </div>

          <div class="bg-white rounded-3xl p-6 sm:p-10 shadow-sm border border-slate-200/80 mb-16 overflow-hidden">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 md:gap-12 items-center">
              <div class="lg:col-span-5 relative">
                <div class="relative rounded-2xl overflow-hidden shadow-md group">
                  <img :src="principalPhoto"
                    :alt="`${school.principal_name || 'Principal'}`"
                    class="w-full h-[360px] sm:h-[420px] object-cover object-top group-hover:scale-105 transition-transform duration-500" />
                  <div class="absolute inset-0 bg-gradient-to-t from-black/40 via-transparent to-transparent"></div>
                  <div class="absolute bottom-4 left-4 bg-[#005506] text-white px-4 py-2 rounded-xl shadow-lg border border-emerald-400/30">
                    <p class="font-bold text-xs sm:text-sm uppercase">{{ school.principal_name || 'PRINCIPAL IV' }}</p>
                    <p class="text-[10px] text-emerald-200 uppercase tracking-wider font-semibold">Principal IV</p>
                  </div>
                </div>
              </div>
              <div class="lg:col-span-7 space-y-6">
                <div class="inline-flex items-center gap-2 bg-[#eaf5ed] text-[#005506] text-xs font-bold px-3.5 py-1.5 rounded-full border border-emerald-200/60">
                  <svg class="w-4 h-4 text-[#005506]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                  <span>Principal IV, DepEd Division of Cavite</span>
                </div>
                <div>
                  <h3 class="font-['Anton'] text-3xl sm:text-4xl text-[#005506] tracking-wide uppercase">{{ school.principal_name || 'School Head' }}</h3>
                  <p class="text-xs sm:text-sm font-bold text-amber-800 mt-1">Doctor of Education in Educational Management</p>
                </div>
                <blockquote class="text-slate-600 text-sm sm:text-base italic leading-relaxed pt-2 border-l-4 border-amber-400 pl-4">
                  "Education in {{ school.name }} is not merely about completing competencies—it is about restoring the
                  dignity of public schooling. We strive daily so that no Salawagueño child is left behind in knowledge,
                  character, or global competence."
                </blockquote>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-4 border-t border-slate-100">
                  <div class="bg-[#FAF7EE] rounded-2xl p-4 border border-[#EDE7D5] space-y-1">
                    <span class="text-[10px] font-extrabold uppercase tracking-widest text-slate-400 block">OPEN CONSULTATION HOURS</span>
                    <div class="flex items-center gap-2 text-xs font-bold text-[#005506]">
                      <svg class="w-4 h-4 text-amber-800" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" /></svg>
                      <span>Tuesday & Thursday</span>
                    </div>
                    <p class="text-[11px] text-slate-500">1:00 PM – 4:00 PM (Admin Bldg, Rm 101)</p>
                  </div>
                  <div class="bg-[#FAF7EE] rounded-2xl p-4 border border-[#EDE7D5] space-y-1">
                    <span class="text-[10px] font-extrabold uppercase tracking-widest text-slate-400 block">OFFICIAL CONTACT</span>
                    <div class="flex items-center gap-2 text-xs font-bold text-[#005506] truncate">
                      <svg class="w-4 h-4 text-amber-800 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" /></svg>
                      <span class="truncate">{{ school.email || 'principal@deped.gov.ph' }}</span>
                    </div>
                    <p class="text-[11px] text-slate-500">{{ school.phone || '(046) 416-0000' }}</p>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="space-y-8">
            <h2 class="font-['Anton'] text-2xl sm:text-3xl md:text-4xl text-[#005506] tracking-wide uppercase">Academic Councils & Department Coordinators</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
              <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-200/80 space-y-4 hover:shadow-md transition-shadow">
                <div class="w-10 h-10 rounded-full bg-emerald-100 text-[#005506] flex items-center justify-center shrink-0">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.436 60.436 0 01-.491-6.347A48.627 48.627 0 0112 2.09c4.787 0 9.227 1.48 12.983 4.025a60.316 60.316 0 01-.491 6.347m-15.482 0a50.57 50.57 0 00-2.658 8.14c-.19.786.393 1.543 1.189 1.543h18.358c.796 0 1.38-.757 1.189-1.543a50.53 50.53 0 00-2.658-8.14m-15.482 0A50.697 50.697 0 0112 13.489a50.702 50.702 0 017.743-3.342" /></svg>
                </div>
                <div class="space-y-1">
                  <h3 class="font-bold text-base text-[#005506]">Ma. Elena V. Santos</h3>
                  <p class="text-xs font-bold text-amber-800">Assistant Principal for Academics</p>
                </div>
                <p class="text-xs text-slate-500 leading-relaxed pt-1">Overviews curriculum adherence, faculty development, and SHS track implementation.</p>
              </div>
              <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-200/80 space-y-4 hover:shadow-md transition-shadow">
                <div class="w-10 h-10 rounded-full bg-amber-100 text-amber-800 flex items-center justify-center shrink-0">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9.75 3.104v5.714a2.25 2.25 0 01-.659 1.591L5 14.5M9.75 3.104c-.251.023-.501.05-.75.082m.75-.082a24.301 24.301 0 014.5 0m0 0v5.714c0 .597.237 1.17.659 1.591L19 14.5M14.25 3.104c.251.023.501.05.75.082M19 14.5l-3.25 3.25a2.25 2.25 0 01-1.591.659H9.841a2.25 2.25 0 01-1.591-.659L5 14.5m14 0V19.5a2.25 2.25 0 01-2.25 2.25H7.25A2.25 2.25 0 015 19.5V14.5" /></svg>
                </div>
                <div class="space-y-1">
                  <h3 class="font-bold text-base text-[#005506]">Engr. Jerome D. Reyes</h3>
                  <p class="text-xs font-bold text-amber-800">STEM Track Department Head</p>
                </div>
                <p class="text-xs text-slate-500 leading-relaxed pt-1">Coordinates research laboratories, robotics initiatives, and science competitions.</p>
              </div>
              <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-200/80 space-y-4 hover:shadow-md transition-shadow">
                <div class="w-10 h-10 rounded-full bg-emerald-100 text-[#005506] flex items-center justify-center shrink-0">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17L17.25 21A2.652 2.652 0 0021 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766l2.12-.635a.75.75 0 00.518-.948l-.497-1.492a.75.75 0 00-.916-.484l-2.015.504a2.25 2.25 0 01-1.68-.28L10.5 6.643M11.42 15.17L7.5 19.092a2.25 2.25 0 01-3.182 0l-.548-.547a2.25 2.25 0 010-3.182l3.922-3.922M10.5 6.643a2.25 2.25 0 00-2.25-2.25H6.75A2.25 2.25 0 004.5 6.643v1.5a2.25 2.25 0 002.25 2.25h1.5a2.25 2.25 0 002.25-2.25v-1.5z" /></svg>
                </div>
                <div class="space-y-1">
                  <h3 class="font-bold text-base text-[#005506]">Mrs. Clarita B. Mendoza</h3>
                  <p class="text-xs font-bold text-amber-800">TVL / TechPro Head</p>
                </div>
                <p class="text-xs text-slate-500 leading-relaxed pt-1">Steward of DepEd National TVL certifications, industry linkages, and work immersion.</p>
              </div>
              <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-200/80 space-y-4 hover:shadow-md transition-shadow">
                <div class="w-10 h-10 rounded-full bg-emerald-100 text-[#005506] flex items-center justify-center shrink-0">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 18v-5.25m0 0a6.01 6.01 0 001.5-.189m-1.5.189a6.01 6.01 0 01-1.5-.189m3.75 2.625a3.375 3.375 0 00-3.375-3.375h-1.5A3.375 3.375 0 006 15.375v.114c0 1.057.49 2.059 1.32 2.7l.08.062a4.508 4.508 0 005.2 0l.08-.062a3.375 3.375 0 001.32-2.701v-.114zM12 9a3 3 0 100-6 3 3 0 000 6z" /></svg>
                </div>
                <div class="space-y-1">
                  <h3 class="font-bold text-base text-[#005506]">Sir Gabriel F. Ramos</h3>
                  <p class="text-xs font-bold text-amber-800">Guidance & Career Counselor</p>
                </div>
                <p class="text-xs text-slate-500 leading-relaxed pt-1">College entrance exam orientations, student mental health, and career path coaching.</p>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- ACCREDITATIONS -->
      <section ref="accreditationsRef" class="w-full max-w-[1500px] mx-auto px-6 sm:px-12 md:px-16 lg:px-24 py-16 md:py-24">
        <div class="transition-all duration-1000"
          :class="isAccreditationsVisible ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-12'">
          <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-12">
            <div class="space-y-3 max-w-2xl">
              <span class="text-xs font-extrabold uppercase tracking-widest text-[#005506] block">INSTITUTIONAL ACCREDITATIONS</span>
              <h2 class="font-['Anton'] text-3xl sm:text-4xl md:text-5xl text-[#005506] tracking-wide uppercase leading-tight">
                Hallmarks of Cavite Public Education
              </h2>
            </div>
            <p class="text-xs sm:text-sm text-slate-600 max-w-md leading-relaxed">
              Recognized by division, regional, and national bodies for sustainable civic excellence and pedagogical integrity.
            </p>
          </div>
          <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="bg-[#005506] text-white rounded-3xl p-8 sm:p-10 shadow-lg relative flex flex-col justify-between overflow-hidden hover:-translate-y-1 transition-all duration-300">
              <div class="space-y-6">
                <div class="w-12 h-12 rounded-2xl bg-white/10 border border-white/20 flex items-center justify-center text-amber-300">
                  <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z" /></svg>
                </div>
                <div class="space-y-3">
                  <h3 class="font-bold text-xl sm:text-2xl text-white leading-snug">DOST Star Partner High School</h3>
                  <p class="text-xs sm:text-sm text-emerald-100/90 leading-relaxed">Honored by DOST Region IV-A for continuous high qualification rate in Junior Level Science Scholarships.</p>
                </div>
              </div>
              <div class="pt-8 border-t border-white/15 mt-6">
                <span class="inline-block bg-amber-400 text-slate-950 text-[10px] font-extrabold px-3 py-1 rounded-full uppercase tracking-wider">2023 - 2026 RECIPIENT</span>
              </div>
            </div>
            <div class="bg-[#004d08] text-white rounded-3xl p-8 sm:p-10 shadow-lg relative flex flex-col justify-between overflow-hidden hover:-translate-y-1 transition-all duration-300">
              <div class="space-y-6">
                <div class="w-12 h-12 rounded-2xl bg-white/10 border border-white/20 flex items-center justify-center text-amber-300">
                  <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" /></svg>
                </div>
                <div class="space-y-3">
                  <h3 class="font-bold text-xl sm:text-2xl text-white leading-snug">Brigada Eskwela Hall of Fame</h3>
                  <p class="text-xs sm:text-sm text-emerald-100/90 leading-relaxed">Three-time First Place Awardee for "Mega School Category" across the Schools Division of Cavite.</p>
                </div>
              </div>
              <div class="pt-8 border-t border-white/15 mt-6">
                <span class="inline-block bg-emerald-400/20 text-emerald-200 text-[10px] font-extrabold px-3 py-1 rounded-full uppercase tracking-wider border border-emerald-400/30">CAVITE DIVISION DISTINCTION</span>
              </div>
            </div>
            <div class="bg-[#003d06] text-white rounded-3xl p-8 sm:p-10 shadow-lg relative flex flex-col justify-between overflow-hidden hover:-translate-y-1 transition-all duration-300">
              <div class="space-y-6">
                <div class="w-12 h-12 rounded-2xl bg-white/10 border border-white/20 flex items-center justify-center text-amber-300">
                  <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 18.75h-9m9 0a3 3 0 003-3V8.25a3 3 0 00-3-3h-9a3 3 0 00-3 3v7.5a3 3 0 003 3m9 0v-1.5a1.5 1.5 0 00-1.5-1.5h-6a1.5 1.5 0 00-1.5 1.5v1.5m6-6.75a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z" /></svg>
                </div>
                <div class="space-y-3">
                  <h3 class="font-bold text-xl sm:text-2xl text-white leading-snug">Regional Science Fair Champion</h3>
                  <p class="text-xs sm:text-sm text-emerald-100/90 leading-relaxed">First Place in the 2024 Calabarzon Science and Technology Fair (Robotics & Intelligent Machines category).</p>
                </div>
              </div>
              <div class="pt-8 border-t border-white/15 mt-6">
                <span class="inline-block bg-emerald-400/20 text-emerald-200 text-[10px] font-extrabold px-3 py-1 rounded-full uppercase tracking-wider border border-emerald-400/30">REGION IV-A DEPED 2024</span>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- CAMPUS MAP -->
      <section ref="campusRef" class="w-full max-w-[1500px] mx-auto px-6 sm:px-12 md:px-16 lg:px-24 py-16">
        <div class="transition-all duration-1000"
          :class="isCampusVisible ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-12'">
          <div class="mb-10">
            <span class="text-xs font-extrabold uppercase tracking-widest text-[#005506] block mb-2">CAMPUS ACCESS</span>
            <h2 class="font-['Anton'] text-3xl sm:text-4xl md:text-5xl text-[#005506] tracking-wide uppercase">
              Visiting {{ school.name }}
            </h2>
          </div>

          <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-stretch">
            <div class="lg:col-span-7 bg-white rounded-3xl p-4 sm:p-6 shadow-sm border border-slate-200/80 flex flex-col justify-between space-y-4">
              <div class="w-full h-[350px] sm:h-[400px] rounded-2xl overflow-hidden border border-slate-200 relative">
                <iframe title="Salawag Senior High School Google Map Location"
                  src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3864.8878952431686!2d120.97852107584144!3d14.318047983935293!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3397d5a5cf2b0a9f%3A0x6b2b62bb6b820bb7!2sSalawag%20Integrated%20National%20High%20School!5e0!3m2!1sen!2sph!4v1710000000000!5m2!1sen!2sph"
                  class="w-full h-full border-0" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
              </div>
              <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pt-2">
                <div class="flex items-start gap-3">
                  <div class="w-9 h-9 rounded-full bg-emerald-100 text-[#005506] flex items-center justify-center shrink-0 mt-0.5">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" /></svg>
                  </div>
                  <div>
                    <h3 class="font-bold text-sm text-[#005506]">Main Academic Campus</h3>
                    <p class="text-xs text-slate-500">{{ school.address }}</p>
                  </div>
                </div>
                <a href="https://maps.google.com/?q=Salawag+Integrated+National+High+School+Dasmari%C3%B1as+Cavite"
                  target="_blank" rel="noopener noreferrer"
                  class="inline-flex items-center justify-center gap-2 bg-[#005506] hover:bg-[#003d06] text-white text-xs font-bold px-5 py-2.5 rounded-xl shadow-md transition-all active:scale-95 shrink-0">
                  <span>Open in Maps</span>
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" /></svg>
                </a>
              </div>
            </div>

            <div class="lg:col-span-5 bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-slate-200/80 flex flex-col justify-between space-y-6">
              <div class="space-y-6">
                <h3 class="font-bold text-xl text-[#005506] border-b border-slate-100 pb-3">Commuter & Public Transit Guide</h3>
                <div class="space-y-5">
                  <div class="flex items-start gap-3.5">
                    <div class="w-8 h-8 rounded-lg bg-emerald-100 text-[#005506] flex items-center justify-center shrink-0 mt-0.5">
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0C2.678 5.57 2.25 6.05 2.25 6.618v1.033" /></svg>
                    </div>
                    <div class="space-y-1">
                      <h4 class="font-bold text-xs sm:text-sm text-[#005506]">From Pala-pala / Robinsons Dasmariñas:</h4>
                      <p class="text-xs text-slate-600 leading-relaxed">Take a jeepney bound for GMA / Paliparan. Alight at Golden City Crossing / Salawag Barangay Hall.</p>
                    </div>
                  </div>
                  <div class="flex items-start gap-3.5">
                    <div class="w-8 h-8 rounded-lg bg-amber-100 text-amber-800 flex items-center justify-center shrink-0 mt-0.5">
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0C2.678 5.57 2.25 6.05 2.25 6.618v1.033" /></svg>
                    </div>
                    <div class="space-y-1">
                      <h4 class="font-bold text-xs sm:text-sm text-[#005506]">From Alabang / Molino (Bacoor):</h4>
                      <p class="text-xs text-slate-600 leading-relaxed">Ride a modern PUV or jeep along Molino-Paliparan Road. Drop off at Salawag Junction.</p>
                    </div>
                  </div>
                  <div class="flex items-start gap-3.5">
                    <div class="w-8 h-8 rounded-lg bg-emerald-100 text-[#005506] flex items-center justify-center shrink-0 mt-0.5">
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" /></svg>
                    </div>
                    <div class="space-y-1">
                      <h4 class="font-bold text-xs sm:text-sm text-[#005506]">Campus Gate Protocol:</h4>
                      <p class="text-xs text-slate-600 leading-relaxed">All guests must present a valid government or student ID to the security guard station at Gate 1.</p>
                    </div>
                  </div>
                </div>
              </div>
              <div class="bg-[#FAF7EE] rounded-2xl p-4 border border-[#EDE7D5] space-y-1">
                <h4 class="text-xs font-bold text-[#005506]">Security & Gate Inquiries:</h4>
                <p class="text-xs text-slate-600">
                  Landline: <strong>{{ school.phone || '(046) 416-0000' }}</strong>
                </p>
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>

    <Footer class="m-0 p-0 block" />
  </div>
</template>

<script setup>
import { Head } from '@inertiajs/vue3'
import Navbar from '@/Components/Navbar.vue'
import Footer from '@/Components/Footer.vue'
import SSHSLogo from '../../../../assets/img/SSHS-about-logo.png'
import { ref, onMounted, onUnmounted, nextTick } from 'vue'

const props = defineProps({
  school: { type: Object, required: true },
  faculty_count: { type: Number, default: 0 },
})

const principalPhoto = 'https://images.unsplash.com/photo-1560250097-0b93528c311a?auto=format&fit=crop&w=800&q=80'

const pillarsRef = ref(null)
const sealRef = ref(null)
const timelineSection = ref(null)
const leadershipRef = ref(null)
const accreditationsRef = ref(null)
const campusRef = ref(null)

const isPillarsVisible = ref(false)
const isSealVisible = ref(false)
const isLeadershipVisible = ref(false)
const isAccreditationsVisible = ref(false)
const isCampusVisible = ref(false)

let timelineObserver = null
let sectionObserver = null

onMounted(() => {
  // Timeline slide-in observer
  timelineObserver = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        const target = entry.target
        const direction = target.getAttribute('data-animate')
        if (direction === 'slide-left') target.classList.add('animate-slide-in-left')
        else if (direction === 'slide-right') target.classList.add('animate-slide-in-right')
        target.classList.remove('opacity-0')
        timelineObserver.unobserve(target)
      }
    })
  }, { threshold: 0.2, rootMargin: '0px 0px -50px 0px' })

  document.querySelectorAll('.timeline-item').forEach(el => timelineObserver.observe(el))

  // Section reveal observer
  if (typeof IntersectionObserver !== 'undefined') {
    sectionObserver = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry) => {
          if (entry.target === pillarsRef.value) isPillarsVisible.value = entry.isIntersecting
          else if (entry.target === sealRef.value) isSealVisible.value = entry.isIntersecting
          else if (entry.target === leadershipRef.value) isLeadershipVisible.value = entry.isIntersecting
          else if (entry.target === accreditationsRef.value) isAccreditationsVisible.value = entry.isIntersecting
          else if (entry.target === campusRef.value) isCampusVisible.value = entry.isIntersecting
        })
      },
      { threshold: 0.12 }
    )
    nextTick(() => {
      ;[pillarsRef, sealRef, leadershipRef, accreditationsRef, campusRef].forEach(r => { if (r.value) sectionObserver.observe(r.value) })
    })
  } else {
    isPillarsVisible.value = isSealVisible.value = isLeadershipVisible.value = isAccreditationsVisible.value = isCampusVisible.value = true
  }
})

onUnmounted(() => {
  if (timelineObserver) timelineObserver.disconnect()
  if (sectionObserver) sectionObserver.disconnect()
})
</script>

<style scoped>
@keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
@keyframes heroZoom { 0%, 100% { transform: scale(1); } 50% { transform: scale(1.08); } }
@keyframes floatSoft { 0%, 100% { transform: translateY(0px); } 50% { transform: translateY(-3px); } }
@keyframes pulseSlow { 0%, 100% { opacity: 0.3; } 50% { opacity: 0.7; } }
@keyframes sheenMove { 0% { transform: translateX(-100%); } 100% { transform: translateX(200%); } }
.animate-fade-in-up { animation: fadeInUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
.animate-hero-zoom { animation: heroZoom 20s ease-in-out infinite; }
.animate-float-soft { animation: floatSoft 4s ease-in-out infinite; }
.animate-pulse-slow { animation: pulseSlow 6s ease-in-out infinite; }
.animate-sheen { animation: sheenMove 5s ease-in-out infinite; }
.animation-delay-200 { animation-delay: 0.2s; animation-fill-mode: backwards; }
@keyframes slideInLeft { from { opacity: 0; transform: translateX(-60px); } to { opacity: 1; transform: translateX(0); } }
@keyframes slideInRight { from { opacity: 0; transform: translateX(60px); } to { opacity: 1; transform: translateX(0); } }
.animate-slide-in-left { animation: slideInLeft 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
.animate-slide-in-right { animation: slideInRight 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
</style>