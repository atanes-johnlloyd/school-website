<template>

  <Head :title="`Admissions - ${school.name}`" />

  <!-- Main Background Canvas -->
  <div class="min-h-screen bg-[#E8FFE8] font-['Inter'] text-slate-800 overflow-x-hidden">

    <!-- OVERLAPPED HERO BANNER SECTION -->
    <section
      class="relative w-full min-h-[65vh] md:min-h-[72vh] bg-[#004d08] text-white overflow-hidden shadow-md flex items-center">

      <Navbar />

      <div class="absolute inset-0 z-0 pointer-events-none overflow-hidden">
        <img src="https://images.unsplash.com/photo-1523240795612-9a054b0db644?auto=format&fit=crop&w=1920&q=80"
          :alt="`${school.name} Admissions & Students`"
          class="w-full h-full object-cover object-center opacity-25 mix-blend-overlay scale-105 animate-hero-zoom transition-transform duration-1000" />
        <div class="absolute inset-0 bg-gradient-to-r from-[#004d08]/98 via-[#004d08]/88 to-[#003805]/75"></div>
      </div>

      <div
        class="absolute inset-0 bg-gradient-to-r from-transparent via-white/5 to-transparent animate-sheen pointer-events-none z-0">
      </div>

      <div
        class="absolute -top-24 -left-24 w-96 h-96 bg-emerald-400/15 rounded-full blur-3xl pointer-events-none animate-pulse-slow">
      </div>
      <div
        class="absolute -bottom-24 right-0 w-96 h-96 bg-amber-400/10 rounded-full blur-3xl pointer-events-none animate-pulse-slow delay-1000">
      </div>

      <!-- Hero Banner Content -->
      <div
        class="relative z-10 w-full max-w-[1500px] mx-auto px-6 sm:px-12 md:px-16 lg:px-24 pt-28 md:pt-36 pb-16 space-y-6">

        <div class="space-y-4 max-w-4xl">
          <h1
            class="font-['Anton'] text-5xl sm:text-6xl md:text-7xl lg:text-8xl tracking-tight uppercase leading-[1.02] text-white animate-fade-in-up">
            Your Senior High Journey <br />
            <span
              class="text-amber-400 inline-block underline decoration-amber-400 decoration-4 underline-offset-8 animate-float-soft">
              Starts Here.
            </span>
          </h1>

          <p
            class="text-sm sm:text-base md:text-lg text-emerald-100/90 font-normal leading-relaxed pt-2 max-w-3xl animate-fade-in-up animation-delay-200">
            Open to all incoming Grade 10 completers, Alternative Learning System (ALS) qualifiers, and senior high
            transferees. Receive 100% tuition-free, values-grounded DepEd secondary education with complete state
            voucher funding, cutting-edge labs, and zero hidden laboratory fees.
          </p>

          <p
            class="text-xs sm:text-sm text-emerald-200/90 font-semibold pt-2 animate-fade-in-up animation-delay-200">
            📅 Application window: <strong class="text-amber-300">{{ application_opens }}</strong> –
            <strong class="text-amber-300">{{ application_closes }}</strong>
          </p>
        </div>

        <div class="pt-3 flex flex-wrap items-center gap-4 animate-fade-in-up animation-delay-400">
          <a href="#view-application-flow"
            class="group relative inline-flex items-center gap-2.5 bg-amber-400 hover:bg-amber-300 text-slate-950 font-bold text-xs sm:text-sm px-7 py-3.5 rounded-full shadow-lg hover:shadow-amber-400/20 transition-all duration-300 hover:-translate-y-0.5 active:scale-95 cursor-pointer overflow-hidden">
            <span>Fill Registration Form</span>
            <svg class="w-4 h-4 text-slate-950 group-hover:rotate-12 transition-transform" fill="none"
              stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
              <path stroke-linecap="round" stroke-linejoin="round"
                d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
            </svg>
          </a>

          <a href="#status-check"
            class="inline-flex items-center gap-2.5 bg-white/10 hover:bg-white/20 border border-white/30 text-white font-medium text-xs sm:text-sm px-6 py-3.5 rounded-full transition-all duration-300 hover:-translate-y-0.5 active:scale-95 cursor-pointer backdrop-blur-sm">
            <svg class="w-4 h-4 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"
              stroke-width="2.5">
              <path stroke-linecap="round" stroke-linejoin="round"
                d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
            </svg>
            <span>Check Submitted Status</span>
          </a>
        </div>

      </div>

    </section>

    <!-- THE 5-PHASE TRAIL SECTION -->
    <section ref="phasesRef" class="w-full max-w-[1500px] mx-auto px-6 sm:px-12 md:px-16 lg:px-24 py-16 md:py-20">

      <div class="transition-all duration-1000"
        :class="isPhasesVisible ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-12'">

        <div class="text-center space-y-4 max-w-3xl mx-auto mb-12 sm:mb-16">
          <span
            class="inline-block bg-[#eaf5ed] text-[#005506] text-xs font-extrabold px-4 py-1.5 rounded-full uppercase tracking-widest border border-[#005506]/10">
            THE 5-PHASE TRAIL
          </span>
          <h2
            class="font-['Anton'] text-4xl sm:text-5xl lg:text-6xl text-[#005506] uppercase tracking-wide leading-tight">
            Seamless Journey from Form to Student RFID
          </h2>
          <p class="text-xs sm:text-sm md:text-base text-slate-600 font-normal leading-relaxed">
            No endless lines under the sun. Our streamlined hybrid enrollment tracks your paperwork through automated
            checks, interview appointments, and direct section issuance.
          </p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-5 items-stretch">

          <!-- PHASE 01 -->
          <div
            class="bg-white rounded-3xl p-6 shadow-sm border border-slate-200/90 flex flex-col justify-between space-y-6 hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
            <div class="space-y-4">
              <div class="flex items-center justify-between">
                <span
                  class="w-10 h-10 rounded-full bg-[#005506] text-white font-black text-sm flex items-center justify-center shadow-md">1</span>
                <span
                  class="bg-[#eaf5ed] text-[#005506] text-[10px] font-extrabold px-3 py-1 rounded-full uppercase tracking-wider">PHASE 01</span>
              </div>
              <div class="space-y-2">
                <h3 class="font-bold text-slate-900 text-lg leading-snug">Online Pre-Registration</h3>
                <p class="text-xs text-slate-500 leading-relaxed font-normal">Encode your 12-digit DepEd LRN, primary
                  strand interests, and parental guardian contacts.</p>
              </div>
            </div>
            <div
              class="pt-4 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-500 font-semibold">
              <div class="flex items-center gap-1.5 text-[#005506]">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>10 Mins</span>
              </div>
              <span class="text-slate-400">Online Form</span>
            </div>
          </div>

          <!-- PHASE 02 -->
          <div
            class="bg-white rounded-3xl p-6 shadow-sm border border-slate-200/90 flex flex-col justify-between space-y-6 hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
            <div class="space-y-4">
              <div class="flex items-center justify-between">
                <span
                  class="w-10 h-10 rounded-full bg-amber-400 text-slate-950 font-black text-sm flex items-center justify-center shadow-md">2</span>
                <span
                  class="bg-amber-100/80 text-amber-900 text-[10px] font-extrabold px-3 py-1 rounded-full uppercase tracking-wider">PHASE 02</span>
              </div>
              <div class="space-y-2">
                <h3 class="font-bold text-slate-900 text-lg leading-snug">Credential Scan & Upload</h3>
                <p class="text-xs text-slate-500 leading-relaxed font-normal">Submit clear snapshots or PDFs of Form 138
                  (G10 Card), PSA Birth Certificate, and Certificate of Good Moral.</p>
              </div>
            </div>
            <div
              class="pt-4 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-500 font-semibold">
              <div class="flex items-center gap-1.5 text-amber-700">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>24h Review</span>
              </div>
              <span class="text-slate-400">Document Desk</span>
            </div>
          </div>

          <!-- PHASE 03 -->
          <div
            class="bg-white rounded-3xl p-6 shadow-sm border border-slate-200/90 flex flex-col justify-between space-y-6 hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
            <div class="space-y-4">
              <div class="flex items-center justify-between">
                <span
                  class="w-10 h-10 rounded-full bg-[#005506] text-white font-black text-sm flex items-center justify-center shadow-md">3</span>
                <span
                  class="bg-[#eaf5ed] text-[#005506] text-[10px] font-extrabold px-3 py-1 rounded-full uppercase tracking-wider">PHASE 03</span>
              </div>
              <div class="space-y-2">
                <h3 class="font-bold text-slate-900 text-lg leading-snug">Aptitude & Guidance</h3>
                <p class="text-xs text-slate-500 leading-relaxed font-normal">Brief career profiling assessment or
                  online counselor session to confirm your strand fit.</p>
              </div>
            </div>
            <div
              class="pt-4 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-500 font-semibold">
              <div class="flex items-center gap-1 text-[#005506]">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round"
                    d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6 0 3.375 3.375 0 016 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                </svg>
                <span>On-Campus/Zoom</span>
              </div>
              <span class="text-slate-400">Guidance Office</span>
            </div>
          </div>

          <!-- PHASE 04 -->
          <div
            class="bg-white rounded-3xl p-6 shadow-sm border border-slate-200/90 flex flex-col justify-between space-y-6 hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
            <div class="space-y-4">
              <div class="flex items-center justify-between">
                <span
                  class="w-10 h-10 rounded-full bg-[#005506] text-white font-black text-sm flex items-center justify-center shadow-md">4</span>
                <span
                  class="bg-slate-100 text-slate-700 text-[10px] font-extrabold px-3 py-1 rounded-full uppercase tracking-wider">PHASE 04</span>
              </div>
              <div class="space-y-2">
                <h3 class="font-bold text-slate-900 text-lg leading-snug">Official Section & COR</h3>
                <p class="text-xs text-slate-500 leading-relaxed font-normal">Receive your digital Certificate of
                  Registration (COR), assigned homeroom adviser, and schedule.</p>
              </div>
            </div>
            <div
              class="pt-4 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-500 font-semibold">
              <div class="flex items-center gap-1.5 text-slate-700">
                <svg class="w-4 h-4 text-[#005506]" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                  stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round"
                    d="M12 9.75v6.75m0 0l-3-3m3 3l3-3m-8.25 6h16.5a1.5 1.5 0 001.5-1.5V5.25a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 5.25v13.5A1.5 1.5 0 003.75 21z" />
                </svg>
                <span>PDF Granted</span>
              </div>
              <span class="text-slate-400">Registrar Portal</span>
            </div>
          </div>

          <!-- PHASE 05 -->
          <div
            class="bg-white rounded-3xl p-6 shadow-sm border border-slate-200/90 flex flex-col justify-between space-y-6 hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
            <div class="space-y-4">
              <div class="flex items-center justify-between">
                <span
                  class="w-10 h-10 rounded-full bg-amber-400 text-slate-950 font-black text-sm flex items-center justify-center shadow-md">5</span>
                <span
                  class="bg-amber-100/80 text-amber-900 text-[10px] font-extrabold px-3 py-1 rounded-full uppercase tracking-wider">PHASE 05</span>
              </div>
              <div class="space-y-2">
                <h3 class="font-bold text-slate-900 text-lg leading-snug">RFID & Welcome Kit</h3>
                <p class="text-xs text-slate-500 leading-relaxed font-normal">Visit the campus media hub to capture your
                  biometrics, pick up student manual, and receive official badge.</p>
              </div>
            </div>
            <div
              class="pt-4 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-500 font-semibold">
              <div class="flex items-center gap-1.5 text-amber-800">
                <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                  stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round"
                    d="M15 9h3.75M15 12h3.75M15 15h3.75M4.5 19.5h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z" />
                </svg>
                <span>Campus Day</span>
              </div>
              <span class="text-slate-400">SSHS Gate 1</span>
            </div>
          </div>

        </div>

      </div>

    </section>

    <!-- MAIN CONTAINER -->
    <main class="w-full pl-4 md:pl-16 pr-4 md:pr-16 pb-8 flex-grow relative">

      <!-- APPLICATION FLOW CONTAINER -->
      <div id="view-application-flow" ref="formContainer"
        class="relative z-10 w-full max-w-[90%] md:max-w-[90%] lg:max-w-[900px] mx-auto mt-8 mb-12 pt-4 md:pt-8 scroll-mt-6">

        <div class="transition-all duration-1000"
          :class="isApplicationVisible ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-12'">

          <!-- Top Header Card -->
          <div
            class="bg-white rounded-2xl p-6 mt-10 sm:p-8 shadow-xl border border-emerald-100 mb-8 relative overflow-hidden">
            <div class="relative z-10">
              <span
                class="inline-block bg-emerald-100 text-emerald-800 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider mb-2">
                SY 2026–2027 Admissions
              </span>
              <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Student Application Form
              </h2>
              <p class="text-sm text-slate-600 mt-1">Please complete each section below with accurate details to finish
                your application.</p>
            </div>
          </div>

          <!-- Step Indicator Bar -->
          <div class="mb-10">
            <div class="flex items-center justify-between max-w-3xl mx-auto relative px-2">
              <div class="absolute left-0 top-1/2 -translate-y-1/2 w-full h-1 bg-slate-200 z-0"></div>
              <div class="absolute left-0 top-1/2 -translate-y-1/2 h-1 bg-emerald-600 z-0 transition-all duration-300"
                :style="{ width: ((currentStep - 1) * 25) + '%' }"></div>

              <div v-for="step in 5" :key="step" class="relative z-10 flex flex-col items-center">
                <div :class="getStepBadgeClass(step)">{{ step }}</div>
                <span class="text-xs font-semibold mt-2 hidden sm:block"
                  :class="currentStep >= step ? 'text-slate-700' : 'text-slate-500'">
                  {{ ['Personal', 'School', 'Parents', 'Track', 'Review'][step - 1] }}
                </span>
              </div>
            </div>
          </div>

          <!-- APPLICATION FORM -->
          <form @submit.prevent="submitApplication"
            class="bg-white rounded-2xl shadow-xl border border-slate-100 overflow-hidden">

            <!-- ============ STEP 1: PERSONAL INFORMATION ============ -->
            <div v-if="currentStep === 1" class="p-6 sm:p-10 space-y-6">
              <div class="border-b border-slate-100 pb-4">
                <h3 class="text-xl font-bold text-slate-800">1. Personal Information</h3>
                <p class="text-xs text-slate-500 mt-0.5">Fill out basic demographic and contact information.</p>
              </div>

              <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                  <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Surname <span
                      class="text-red-500">*</span></label>
                  <input type="text" v-model="form.surname" maxlength="50" data-field="surname"
                    autocomplete="family-name" class="w-full px-3.5 py-2.5 rounded-lg border text-sm transition-all"
                    :class="errors.surname ? 'border-red-500 ring-1 ring-red-500' : 'border-slate-300'">
                  <p v-if="errors.surname" class="text-red-500 text-xs mt-1">{{ errors.surname }}</p>
                </div>
                <div>
                  <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Given Name <span
                      class="text-red-500">*</span></label>
                  <input type="text" v-model="form.given_name" maxlength="50" data-field="given_name"
                    autocomplete="given-name" class="w-full px-3.5 py-2.5 rounded-lg border text-sm transition-all"
                    :class="errors.given_name ? 'border-red-500 ring-1 ring-red-500' : 'border-slate-300'">
                  <p v-if="errors.given_name" class="text-red-500 text-xs mt-1">{{ errors.given_name }}</p>
                </div>
                <div>
                  <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Middle
                    Name</label>
                  <input type="text" v-model="form.middle_name" maxlength="50" data-field="middle_name"
                    class="w-full px-3.5 py-2.5 rounded-lg border text-sm transition-all"
                    :class="errors.middle_name ? 'border-red-500 ring-1 ring-red-500' : 'border-slate-300'">
                  <p v-if="errors.middle_name" class="text-red-500 text-xs mt-1">{{ errors.middle_name }}</p>
                </div>
              </div>

              <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                <div>
                  <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Suffix</label>
                  <input type="text" v-model="form.suffix" maxlength="10" placeholder="e.g. Jr., III"
                    class="w-full px-3.5 py-2.5 rounded-lg border text-sm transition-all"
                    :class="errors.suffix ? 'border-red-500 ring-1 ring-red-500' : 'border-slate-300'">
                  <p v-if="errors.suffix" class="text-red-500 text-xs mt-1">{{ errors.suffix }}</p>
                </div>
                <div>
                  <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Date of Birth
                    <span class="text-red-500">*</span></label>
                  <input type="date" v-model="form.dob" @change="calculateAge" data-field="dob"
                    class="w-full px-3 py-2.5 rounded-lg border text-sm transition-all"
                    :class="errors.dob ? 'border-red-500 ring-1 ring-red-500' : 'border-slate-300'">
                  <p v-if="errors.dob" class="text-red-500 text-xs mt-1">{{ errors.dob }}</p>
                </div>
                <div>
                  <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Age</label>
                  <input type="number" v-model="form.age" readonly
                    class="w-full px-3.5 py-2.5 rounded-lg border border-slate-200 bg-slate-50 text-slate-500 text-sm font-semibold">
                </div>
                <div>
                  <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Sex <span
                      class="text-red-500">*</span></label>
                  <select v-model="form.sex" data-field="sex"
                    class="w-full px-3.5 py-2.5 rounded-lg border text-sm bg-white transition-all"
                    :class="errors.sex ? 'border-red-500 ring-1 ring-red-500' : 'border-slate-300'">
                    <option value="" disabled>Select</option>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                  </select>
                  <p v-if="errors.sex" class="text-red-500 text-xs mt-1">{{ errors.sex }}</p>
                </div>
              </div>

              <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                  <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">LRN (10–12 digits)
                    <span class="text-red-500">*</span></label>
                  <input type="text" v-model="form.lrn" @input="sanitizeNumeric('lrn', 12)" inputmode="numeric"
                    pattern="[0-9]*" maxlength="12" data-field="lrn"
                    class="w-full px-3.5 py-2.5 rounded-lg border text-sm transition-all"
                    :class="errors.lrn ? 'border-red-500 ring-1 ring-red-500' : 'border-slate-300'">
                  <p v-if="errors.lrn" class="text-red-500 text-xs mt-1">{{ errors.lrn }}</p>
                </div>
                <div>
                  <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Mobile Number
                    <span class="text-red-500">*</span></label>
                  <input type="text" v-model="form.mobile_number" @input="sanitizeNumeric('mobile_number', 11)"
                    inputmode="numeric" pattern="[0-9]*" maxlength="11" data-field="mobile_number"
                    placeholder="09XXXXXXXXX" class="w-full px-3.5 py-2.5 rounded-lg border text-sm transition-all"
                    :class="errors.mobile_number ? 'border-red-500 ring-1 ring-red-500' : 'border-slate-300'">
                  <p v-if="errors.mobile_number" class="text-red-500 text-xs mt-1">{{ errors.mobile_number }}</p>
                </div>
                <div>
                  <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Email Address
                    <span class="text-red-500">*</span></label>
                  <input type="email" v-model="form.email" maxlength="100" data-field="email" autocomplete="email"
                    class="w-full px-3.5 py-2.5 rounded-lg border text-sm transition-all"
                    :class="errors.email ? 'border-red-500 ring-1 ring-red-500' : 'border-slate-300'">
                  <p v-if="errors.email" class="text-red-500 text-xs mt-1">{{ errors.email }}</p>
                </div>
              </div>

              <div class="pt-2 border-t border-slate-100">
                <span class="text-xs font-bold text-emerald-800 uppercase tracking-wider block mb-3">Residential
                  Address</span>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-4">
                  <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Province <span
                        class="text-red-500">*</span></label>
                    <select v-model="form.province" @change="onProvinceChange" data-field="province"
                      class="w-full px-3.5 py-2.5 rounded-lg border text-sm bg-white transition-all"
                      :class="errors.province ? 'border-red-500 ring-1 ring-red-500' : 'border-slate-300'">
                      <option value="">Select province</option>
                      <option v-for="prov in provincesList" :key="prov.name" :value="prov.name">{{ prov.name }}</option>
                    </select>
                    <p v-if="errors.province" class="text-red-500 text-xs mt-1">{{ errors.province }}</p>
                  </div>
                  <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">City/Municipality <span
                        class="text-red-500">*</span></label>
                    <select v-model="form.municipality" @change="onMunicipalityChange" data-field="municipality"
                      class="w-full px-3.5 py-2.5 rounded-lg border text-sm bg-white transition-all"
                      :class="errors.municipality ? 'border-red-500 ring-1 ring-red-500' : 'border-slate-300'">
                      <option value="">Select city/municipality</option>
                      <option v-for="mun in citiesList" :key="mun.name" :value="mun.name">{{ mun.name }}</option>
                    </select>
                    <p v-if="errors.municipality" class="text-red-500 text-xs mt-1">{{ errors.municipality }}</p>
                  </div>
                  <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Barangay <span
                        class="text-red-500">*</span></label>
                    <select v-model="form.barangay" data-field="barangay"
                      class="w-full px-3.5 py-2.5 rounded-lg border text-sm bg-white transition-all"
                      :class="errors.barangay ? 'border-red-500 ring-1 ring-red-500' : 'border-slate-300'">
                      <option value="">Select barangay</option>
                      <option v-for="bgy in barangaysList" :key="bgy" :value="bgy">{{ bgy }}</option>
                    </select>
                    <p v-if="errors.barangay" class="text-red-500 text-xs mt-1">{{ errors.barangay }}</p>
                  </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                  <div class="sm:col-span-3">
                    <label class="block text-xs font-semibold text-slate-600 mb-1">House No. / Street / Subd. <span
                        class="text-red-500">*</span></label>
                    <input type="text" v-model="form.street" maxlength="100" data-field="street"
                      class="w-full px-3.5 py-2.5 rounded-lg border text-sm transition-all"
                      :class="errors.street ? 'border-red-500 ring-1 ring-red-500' : 'border-slate-300'">
                    <p v-if="errors.street" class="text-red-500 text-xs mt-1">{{ errors.street }}</p>
                  </div>
                  <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Zip Code <span
                        class="text-red-500">*</span></label>
                    <input type="text" v-model="form.zip_code" @input="sanitizeNumeric('zip_code', 4)"
                      inputmode="numeric" pattern="[0-9]*" maxlength="4" data-field="zip_code"
                      class="w-full px-3.5 py-2.5 rounded-lg border text-sm transition-all"
                      :class="errors.zip_code ? 'border-red-500 ring-1 ring-red-500' : 'border-slate-300'">
                    <p v-if="errors.zip_code" class="text-red-500 text-xs mt-1">{{ errors.zip_code }}</p>
                  </div>
                </div>

                <div class="mt-3">
                  <label class="block text-xs font-semibold text-slate-600 mb-1">Religion <span
                      class="text-red-500">*</span></label>
                  <input type="text" v-model="form.religion" maxlength="50" data-field="religion"
                    class="w-full px-3.5 py-2.5 rounded-lg border text-sm transition-all"
                    :class="errors.religion ? 'border-red-500 ring-1 ring-red-500' : 'border-slate-300'">
                  <p v-if="errors.religion" class="text-red-500 text-xs mt-1">{{ errors.religion }}</p>
                </div>
              </div>

              <div class="flex justify-between pt-4">
                <div></div>
                <button type="button" @click="handleNext(1)"
                  class="px-8 py-3 bg-emerald-700 hover:bg-emerald-800 text-white font-bold rounded-xl shadow-lg shadow-emerald-700/20 transition-all text-sm">
                  Next →
                </button>
              </div>
            </div>

            <!-- ============ STEP 2: SCHOOL & DOCUMENTS ============ -->
            <div v-if="currentStep === 2" class="p-6 sm:p-10 space-y-6">
              <div class="border-b border-slate-100 pb-4">
                <h3 class="text-xl font-bold text-slate-800">2. Junior High School & Documents</h3>
                <p class="text-xs text-slate-500 mt-0.5">Provide academic background and upload required scanned
                  credentials.</p>
              </div>

              <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                  <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">JHS Name <span
                      class="text-red-500">*</span></label>
                  <input type="text" v-model="form.jhs_name" maxlength="100" data-field="jhs_name"
                    class="w-full px-3.5 py-2.5 rounded-lg border text-sm transition-all"
                    :class="errors.jhs_name ? 'border-red-500 ring-1 ring-red-500' : 'border-slate-300'">
                  <p v-if="errors.jhs_name" class="text-red-500 text-xs mt-1">{{ errors.jhs_name }}</p>
                </div>
                <div>
                  <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">School Address
                    <span class="text-red-500">*</span></label>
                  <input type="text" v-model="form.school_address" maxlength="150" data-field="school_address"
                    class="w-full px-3.5 py-2.5 rounded-lg border text-sm transition-all"
                    :class="errors.school_address ? 'border-red-500 ring-1 ring-red-500' : 'border-slate-300'">
                  <p v-if="errors.school_address" class="text-red-500 text-xs mt-1">{{ errors.school_address }}</p>
                </div>
              </div>

              <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                  <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">School Type <span
                      class="text-red-500">*</span></label>
                  <select v-model="form.school_type" data-field="school_type"
                    class="w-full px-3.5 py-2.5 rounded-lg border text-sm bg-white transition-all"
                    :class="errors.school_type ? 'border-red-500 ring-1 ring-red-500' : 'border-slate-300'">
                    <option value="" disabled>Select</option>
                    <option value="Public">Public</option>
                    <option value="Private">Private</option>
                  </select>
                  <p v-if="errors.school_type" class="text-red-500 text-xs mt-1">{{ errors.school_type }}</p>
                </div>
                <div>
                  <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Completion Year
                    <span class="text-red-500">*</span></label>
                  <input type="text" v-model="form.year_graduated" @input="sanitizeNumeric('year_graduated', 4)"
                    inputmode="numeric" pattern="[0-9]*" maxlength="4" data-field="year_graduated" placeholder="2026"
                    class="w-full px-3.5 py-2.5 rounded-lg border text-sm transition-all"
                    :class="errors.year_graduated ? 'border-red-500 ring-1 ring-red-500' : 'border-slate-300'">
                  <p v-if="errors.year_graduated" class="text-red-500 text-xs mt-1">{{ errors.year_graduated }}</p>
                </div>
              </div>

              <div class="pt-4 border-t border-slate-100">
                <span class="text-xs font-bold text-emerald-800 uppercase tracking-wider block mb-4">Required Documents
                  Upload</span>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                  <!-- PSA -->
                  <div
                    class="border-2 border-dashed rounded-xl p-4 transition-all bg-slate-50/50 flex items-center justify-between"
                    :class="errors.psa ? 'border-red-500' : 'border-slate-300 hover:border-emerald-500'">
                    <div>
                      <p class="text-xs font-bold text-slate-800">PSA Birth Certificate <span
                          class="text-red-500">*</span></p>
                      <p class="text-[11px]" :class="fileNames.psa ? 'text-emerald-600 font-bold' : 'text-slate-400'">
                        {{ fileNames.psa || 'PDF or JPG (Max 5MB)' }}
                      </p>
                    </div>
                    <label
                      class="px-3 py-1.5 bg-white border border-slate-300 hover:bg-slate-100 text-slate-700 font-semibold rounded-lg text-xs cursor-pointer shadow-sm">
                      Choose
                      <input type="file" ref="ref_psa" @change="handleFileChange($event, 'psa')"
                        accept=".pdf,.png,.jpg,.jpeg" class="hidden">
                    </label>
                  </div>
                  <!-- Form 137 -->
                  <div
                    class="border-2 border-dashed rounded-xl p-4 transition-all bg-slate-50/50 flex items-center justify-between"
                    :class="errors.form137 ? 'border-red-500' : 'border-slate-300 hover:border-emerald-500'">
                    <div>
                      <p class="text-xs font-bold text-slate-800">Form 137 / SF10 <span class="text-red-500">*</span>
                      </p>
                      <p class="text-[11px]"
                        :class="fileNames.form137 ? 'text-emerald-600 font-bold' : 'text-slate-400'">
                        {{ fileNames.form137 || 'PDF or JPG (Max 5MB)' }}
                      </p>
                    </div>
                    <label
                      class="px-3 py-1.5 bg-white border border-slate-300 hover:bg-slate-100 text-slate-700 font-semibold rounded-lg text-xs cursor-pointer shadow-sm">
                      Choose
                      <input type="file" ref="ref_form137" @change="handleFileChange($event, 'form137')"
                        accept=".pdf,.png,.jpg,.jpeg" class="hidden">
                    </label>
                  </div>
                  <!-- Good Moral -->
                  <div
                    class="border-2 border-dashed rounded-xl p-4 transition-all bg-slate-50/50 flex items-center justify-between"
                    :class="errors.good_moral ? 'border-red-500' : 'border-slate-300 hover:border-emerald-500'">
                    <div>
                      <p class="text-xs font-bold text-slate-800">Good Moral Certificate <span
                          class="text-red-500">*</span></p>
                      <p class="text-[11px]"
                        :class="fileNames.good_moral ? 'text-emerald-600 font-bold' : 'text-slate-400'">
                        {{ fileNames.good_moral || 'PDF or JPG (Max 5MB)' }}
                      </p>
                    </div>
                    <label
                      class="px-3 py-1.5 bg-white border border-slate-300 hover:bg-slate-100 text-slate-700 font-semibold rounded-lg text-xs cursor-pointer shadow-sm">
                      Choose
                      <input type="file" ref="ref_good_moral" @change="handleFileChange($event, 'good_moral')"
                        accept=".pdf,.png,.jpg,.jpeg" class="hidden">
                    </label>
                  </div>
                  <!-- 2x2 Photo -->
                  <div
                    class="border-2 border-dashed rounded-xl p-4 transition-all bg-slate-50/50 flex items-center justify-between"
                    :class="errors.picture ? 'border-red-500' : 'border-slate-300 hover:border-emerald-500'">
                    <div>
                      <p class="text-xs font-bold text-slate-800">2x2 ID Photo <span class="text-red-500">*</span></p>
                      <p class="text-[11px]"
                        :class="fileNames.picture ? 'text-emerald-600 font-bold' : 'text-slate-400'">
                        {{ fileNames.picture || 'PNG or JPG (Max 2MB)' }}
                      </p>
                    </div>
                    <label
                      class="px-3 py-1.5 bg-white border border-slate-300 hover:bg-slate-100 text-slate-700 font-semibold rounded-lg text-xs cursor-pointer shadow-sm">
                      Choose
                      <input type="file" ref="ref_picture" @change="handleFileChange($event, 'picture')"
                        accept=".png,.jpg,.jpeg" class="hidden">
                    </label>
                  </div>
                </div>
              </div>

              <div class="flex justify-between pt-4">
                <button type="button" @click="goToStep(1)"
                  class="px-6 py-2.5 bg-slate-200 hover:bg-slate-300 text-slate-700 font-bold rounded-xl transition-all text-sm">
                  ← Back
                </button>
                <button type="button" @click="handleNext(2)"
                  class="px-8 py-3 bg-emerald-700 hover:bg-emerald-800 text-white font-bold rounded-xl shadow-lg shadow-emerald-700/20 transition-all text-sm">
                  Next →
                </button>
              </div>
            </div>

            <!-- ============ STEP 3: PARENTS / GUARDIAN ============ -->
            <div v-if="currentStep === 3" class="p-6 sm:p-10 space-y-6">
              <div class="border-b border-slate-100 pb-4">
                <h3 class="text-xl font-bold text-slate-800">3. Parents / Guardian Information</h3>
                <p class="text-xs text-slate-500 mt-0.5">Please provide primary emergency contact and guardian
                  information.</p>
              </div>

              <!-- Father -->
              <div class="space-y-3">
                <h4 class="text-xs font-bold text-emerald-800 uppercase tracking-wider">Father's Information</h4>
                <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                  <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Full
                      Name</label>
                    <input type="text" v-model="form.father_name" maxlength="80" data-field="father_name"
                      placeholder="e.g. Juan Dela Cruz"
                      class="w-full px-3.5 py-2.5 rounded-lg border text-sm transition-all"
                      :class="errors.father_name ? 'border-red-500 ring-1 ring-red-500' : 'border-slate-300'">
                    <p v-if="errors.father_name" class="text-red-500 text-xs mt-1">{{ errors.father_name }}</p>
                  </div>
                  <div>
                    <label
                      class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Occupation</label>
                    <input type="text" v-model="form.father_occupation" maxlength="60" data-field="father_occupation"
                      placeholder="e.g. Engineer" class="w-full px-3.5 py-2.5 rounded-lg border text-sm transition-all"
                      :class="errors.father_occupation ? 'border-red-500 ring-1 ring-red-500' : 'border-slate-300'">
                    <p v-if="errors.father_occupation" class="text-red-500 text-xs mt-1">{{ errors.father_occupation }}
                    </p>
                  </div>
                  <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Contact
                      Number</label>
                    <input type="text" v-model="form.father_contact" @input="sanitizeNumeric('father_contact', 11)"
                      inputmode="numeric" pattern="[0-9]*" maxlength="11" data-field="father_contact"
                      placeholder="09XXXXXXXXX" class="w-full px-3.5 py-2.5 rounded-lg border text-sm transition-all"
                      :class="errors.father_contact ? 'border-red-500 ring-1 ring-red-500' : 'border-slate-300'">
                    <p v-if="errors.father_contact" class="text-red-500 text-xs mt-1">{{ errors.father_contact }}</p>
                  </div>
                  <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Educational
                      Attainment</label>
                    <input type="text" v-model="form.father_education" maxlength="60" data-field="father_education"
                      placeholder="e.g. College Graduate"
                      class="w-full px-3.5 py-2.5 rounded-lg border text-sm transition-all"
                      :class="errors.father_education ? 'border-red-500 ring-1 ring-red-500' : 'border-slate-300'">
                    <p v-if="errors.father_education" class="text-red-500 text-xs mt-1">{{ errors.father_education }}
                    </p>
                  </div>
                </div>
              </div>

              <!-- Mother -->
              <div class="space-y-3 pt-3 border-t border-slate-100">
                <h4 class="text-xs font-bold text-emerald-800 uppercase tracking-wider">Mother's Information</h4>
                <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                  <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Full Maiden
                      Name</label>
                    <input type="text" v-model="form.mother_name" maxlength="80" data-field="mother_name"
                      placeholder="e.g. Maria Santos"
                      class="w-full px-3.5 py-2.5 rounded-lg border text-sm transition-all"
                      :class="errors.mother_name ? 'border-red-500 ring-1 ring-red-500' : 'border-slate-300'">
                    <p v-if="errors.mother_name" class="text-red-500 text-xs mt-1">{{ errors.mother_name }}</p>
                  </div>
                  <div>
                    <label
                      class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Occupation</label>
                    <input type="text" v-model="form.mother_occupation" maxlength="60" data-field="mother_occupation"
                      placeholder="e.g. Teacher" class="w-full px-3.5 py-2.5 rounded-lg border text-sm transition-all"
                      :class="errors.mother_occupation ? 'border-red-500 ring-1 ring-red-500' : 'border-slate-300'">
                    <p v-if="errors.mother_occupation" class="text-red-500 text-xs mt-1">{{ errors.mother_occupation }}
                    </p>
                  </div>
                  <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Contact
                      Number</label>
                    <input type="text" v-model="form.mother_contact" @input="sanitizeNumeric('mother_contact', 11)"
                      inputmode="numeric" pattern="[0-9]*" maxlength="11" data-field="mother_contact"
                      placeholder="09XXXXXXXXX" class="w-full px-3.5 py-2.5 rounded-lg border text-sm transition-all"
                      :class="errors.mother_contact ? 'border-red-500 ring-1 ring-red-500' : 'border-slate-300'">
                    <p v-if="errors.mother_contact" class="text-red-500 text-xs mt-1">{{ errors.mother_contact }}</p>
                  </div>
                  <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Educational
                      Attainment</label>
                    <input type="text" v-model="form.mother_education" maxlength="60" data-field="mother_education"
                      placeholder="e.g. College Graduate"
                      class="w-full px-3.5 py-2.5 rounded-lg border text-sm transition-all"
                      :class="errors.mother_education ? 'border-red-500 ring-1 ring-red-500' : 'border-slate-300'">
                    <p v-if="errors.mother_education" class="text-red-500 text-xs mt-1">{{ errors.mother_education }}
                    </p>
                  </div>
                </div>
              </div>

              <!-- Guardian -->
              <div class="space-y-3 pt-3 border-t border-slate-100">
                <h4 class="text-xs font-bold text-emerald-800 uppercase tracking-wider">Guardian's Information</h4>
                <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                  <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Full
                      Name</label>
                    <input type="text" v-model="form.guardian_name" maxlength="80" data-field="guardian_name"
                      placeholder="e.g. Pedro Santos"
                      class="w-full px-3.5 py-2.5 rounded-lg border text-sm transition-all"
                      :class="errors.guardian_name ? 'border-red-500 ring-1 ring-red-500' : 'border-slate-300'">
                    <p v-if="errors.guardian_name" class="text-red-500 text-xs mt-1">{{ errors.guardian_name }}</p>
                  </div>
                  <div>
                    <label
                      class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Occupation</label>
                    <input type="text" v-model="form.guardian_occupation" maxlength="60"
                      data-field="guardian_occupation" placeholder="e.g. Self-employed"
                      class="w-full px-3.5 py-2.5 rounded-lg border text-sm transition-all"
                      :class="errors.guardian_occupation ? 'border-red-500 ring-1 ring-red-500' : 'border-slate-300'">
                    <p v-if="errors.guardian_occupation" class="text-red-500 text-xs mt-1">{{ errors.guardian_occupation
                    }}</p>
                  </div>
                  <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Contact
                      Number</label>
                    <input type="text" v-model="form.guardian_contact" @input="sanitizeNumeric('guardian_contact', 11)"
                      inputmode="numeric" pattern="[0-9]*" maxlength="11" data-field="guardian_contact"
                      placeholder="09XXXXXXXXX" class="w-full px-3.5 py-2.5 rounded-lg border text-sm transition-all"
                      :class="errors.guardian_contact ? 'border-red-500 ring-1 ring-red-500' : 'border-slate-300'">
                    <p v-if="errors.guardian_contact" class="text-red-500 text-xs mt-1">{{ errors.guardian_contact }}
                    </p>
                  </div>
                  <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Educational
                      Attainment</label>
                    <input type="text" v-model="form.guardian_education" maxlength="60" data-field="guardian_education"
                      placeholder="e.g. High School Graduate"
                      class="w-full px-3.5 py-2.5 rounded-lg border text-sm transition-all"
                      :class="errors.guardian_education ? 'border-red-500 ring-1 ring-red-500' : 'border-slate-300'">
                    <p v-if="errors.guardian_education" class="text-red-500 text-xs mt-1">{{ errors.guardian_education
                    }}
                    </p>
                  </div>
                </div>
                <p class="text-xs text-slate-400 mt-1">* Fill out at least one complete profile (Father, Mother, or
                  Guardian).</p>
              </div>

              <div class="flex justify-between pt-4">
                <button type="button" @click="goToStep(2)"
                  class="px-6 py-2.5 bg-slate-200 hover:bg-slate-300 text-slate-700 font-bold rounded-xl transition-all text-sm">
                  ← Back
                </button>
                <button type="button" @click="handleNext(3)"
                  class="px-8 py-3 bg-emerald-700 hover:bg-emerald-800 text-white font-bold rounded-xl shadow-lg shadow-emerald-700/20 transition-all text-sm">
                  Next →
                </button>
              </div>
            </div>

            <!-- ============ STEP 4: TRACK SELECTION ============ -->
            <div v-if="currentStep === 4" class="p-6 sm:p-10 space-y-6">
              <div class="border-b border-slate-100 pb-4">
                <h3 class="text-xl font-bold text-slate-800">4. Select Senior High School Track</h3>
                <p class="text-xs text-slate-500 mt-0.5">Select one preferred strand or specialized elective cluster.
                </p>
              </div>

              <div v-if="allStrands.length === 0"
                class="text-center py-12 text-slate-500 italic text-sm bg-slate-50 rounded-2xl border border-slate-200">
                No strands are currently available. Please check back later or contact the registrar.
              </div>

              <div v-else class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div v-for="strand in allStrands" :key="strand.id"
                  @click="form.selected_cluster = strand.id"
                  class="cluster-card border-2 border-slate-200 hover:border-emerald-400 rounded-2xl p-5 cursor-pointer transition-all bg-white"
                  :class="{ 'selected border-emerald-600 shadow-md ring-2 ring-emerald-500': form.selected_cluster === strand.id }">
                  <div class="flex justify-between items-center mb-2">
                    <span class="text-xs font-bold bg-emerald-100 text-emerald-800 px-2.5 py-1 rounded-md">{{
                      strand.code }}</span>
                    <span class="text-xs text-slate-400 font-medium">{{ strand.track_name }}</span>
                  </div>
                  <h4 class="font-bold text-slate-900 text-base">{{ strand.name }}</h4>
                  <p v-if="strand.description" class="text-xs text-slate-500 mt-1 leading-relaxed">{{
                    strand.description }}</p>
                </div>
              </div>

              <div class="flex justify-between pt-4">
                <button type="button" @click="goToStep(3)"
                  class="px-6 py-2.5 bg-slate-200 hover:bg-slate-300 text-slate-700 font-bold rounded-xl transition-all text-sm">
                  ← Back
                </button>
                <button type="button" @click="handleNext(4)"
                  class="px-8 py-3 bg-emerald-700 hover:bg-emerald-800 text-white font-bold rounded-xl shadow-lg shadow-emerald-700/20 transition-all text-sm">
                  Next →
                </button>
              </div>
            </div>

            <!-- ============ STEP 5: REVIEW & SUBMIT ============ -->
            <div v-if="currentStep === 5" class="p-6 sm:p-10 space-y-6">
              <div class="border-b border-slate-100 pb-4">
                <h3 class="text-xl font-bold text-slate-800">5. Review Your Application</h3>
                <p class="text-xs text-slate-500 mt-0.5">Please double-check all details before submitting your
                  application.</p>
              </div>

              <div class="bg-slate-50 rounded-xl p-5 space-y-4 border border-slate-200 text-sm">
                <h4 class="font-bold text-slate-700 border-b border-slate-200 pb-2">Personal Information</h4>
                <div class="grid grid-cols-2 gap-2">
                  <div><span class="font-semibold text-slate-600">Surname:</span> {{ form.surname }}</div>
                  <div><span class="font-semibold text-slate-600">Given Name:</span> {{ form.given_name }}</div>
                  <div><span class="font-semibold text-slate-600">Middle Name:</span> {{ form.middle_name || 'N/A' }}
                  </div>
                  <div><span class="font-semibold text-slate-600">Suffix:</span> {{ form.suffix || 'N/A' }}</div>
                  <div><span class="font-semibold text-slate-600">Date of Birth:</span> {{ form.dob }}</div>
                  <div><span class="font-semibold text-slate-600">Age:</span> {{ form.age }}</div>
                  <div><span class="font-semibold text-slate-600">Sex:</span> {{ form.sex }}</div>
                  <div><span class="font-semibold text-slate-600">LRN:</span> {{ form.lrn }}</div>
                  <div><span class="font-semibold text-slate-600">Mobile:</span> {{ form.mobile_number }}</div>
                  <div><span class="font-semibold text-slate-600">Email:</span> {{ form.email }}</div>
                </div>

                <h4 class="font-bold text-slate-700 border-b border-slate-200 pb-2 mt-4">Address</h4>
                <div class="grid grid-cols-2 gap-2">
                  <div><span class="font-semibold text-slate-600">Province:</span> {{ form.province }}</div>
                  <div><span class="font-semibold text-slate-600">City/Municipality:</span> {{ form.municipality }}
                  </div>
                  <div><span class="font-semibold text-slate-600">Barangay:</span> {{ form.barangay }}</div>
                  <div><span class="font-semibold text-slate-600">Street:</span> {{ form.street }}</div>
                  <div><span class="font-semibold text-slate-600">Zip Code:</span> {{ form.zip_code }}</div>
                  <div><span class="font-semibold text-slate-600">Religion:</span> {{ form.religion }}</div>
                </div>

                <h4 class="font-bold text-slate-700 border-b border-slate-200 pb-2 mt-4">Selected Track</h4>
                <div class="flex items-center gap-4">
                  <span class="font-semibold text-slate-600">Track:</span>
                  <div class="w-56 h-20 bg-emerald-700 rounded-xl p-3 flex flex-col justify-center">
                    <span class="font-bold text-white text-sm">{{ selectedClusterObject?.code || '---' }}</span>
                    <span class="text-xs text-emerald-200">{{ selectedClusterObject?.name || 'No cluster selected'
                    }}</span>
                  </div>
                </div>
              </div>

              <div class="flex justify-between pt-4">
                <button type="button" @click="goToStep(4)"
                  class="px-6 py-2.5 bg-slate-200 hover:bg-slate-300 text-slate-700 font-bold rounded-xl transition-all text-sm">
                  ← Back
                </button>
                <button type="submit" :disabled="isSubmitting"
                  class="px-10 py-3 bg-emerald-600 hover:bg-emerald-700 disabled:opacity-60 disabled:cursor-not-allowed text-white font-extrabold rounded-xl shadow-lg shadow-emerald-600/30 transition-all text-sm uppercase tracking-wider">
                  {{ isSubmitting ? 'Submitting…' : 'Submit Application ✓' }}
                </button>
              </div>
            </div>

          </form>
        </div>
      </div>
    </main>

    <!-- APPLICATION PROGRESS TRACKER SECTION -->
    <section ref="statusRef" id="status-check"
      class="w-full max-w-[1500px] mx-auto px-6 sm:px-12 md:px-16 lg:px-24 py-16 scroll-mt-10">

      <div class="transition-all duration-1000"
        :class="isStatusVisible ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-12'">

        <div class="text-center space-y-3 max-w-3xl mx-auto mb-10">
          <span
            class="inline-block bg-[#fcf8e8] text-[#936e00] text-xs font-extrabold px-4 py-1.5 rounded-full uppercase tracking-wider border border-[#e3d7a5]/40">
            ADMISSIONS STATUS VERIFICATION
          </span>
          <h2 class="font-['Anton'] text-3xl sm:text-4xl md:text-5xl text-[#005506] tracking-wide uppercase">
            Track Your Application Progress
          </h2>
          <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
            Already registered? Enter your 9-digit application tracking reference code provided on your printable
            confirmation slip.
          </p>
        </div>

        <div class="max-w-4xl mx-auto bg-white rounded-3xl p-6 sm:p-10 shadow-xl border border-slate-100 space-y-8">

          <form @submit.prevent="lookupApplication" class="flex flex-col sm:flex-row gap-3">
            <div class="relative flex-grow">
              <svg class="w-5 h-5 text-slate-400 absolute left-4 top-1/2 -translate-y-1/2" fill="none"
                stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                  d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
              </svg>
              <input type="text" v-model="trackingCode" placeholder="e.g. SSHS-2026-8812"
                class="w-full pl-12 pr-4 py-3.5 rounded-2xl border border-slate-200 text-sm font-semibold text-slate-800 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-[#005506] focus:border-transparent transition-all" />
            </div>
            <button type="submit" :disabled="isLookingUp"
              class="bg-[#005506] hover:bg-[#004004] disabled:opacity-60 text-white font-bold text-sm px-8 py-3.5 rounded-2xl shadow-md transition-all flex items-center justify-center gap-2 cursor-pointer active:scale-95">
              <span>{{ isLookingUp ? 'Searching…' : 'Look Up Record' }}</span>
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
              </svg>
            </button>
          </form>

          <div v-if="searchError"
            class="bg-red-50 text-red-700 text-xs font-semibold px-4 py-3 rounded-2xl border border-red-200">
            {{ searchError }}
          </div>

          <div v-if="searchResult"
            class="bg-[#FAF7EE] rounded-2xl p-6 sm:p-8 border border-[#EDE7D5] space-y-6 animate-fade-in-up">

            <div
              class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-slate-200/60">
              <div>
                <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider block">
                  APPLICANT RECORD #{{ searchResult.reference_number }}
                </span>
                <h3 class="text-xl sm:text-2xl font-black text-[#005506] tracking-tight">
                  {{ searchResult.full_name }}
                </h3>
                <p class="text-xs text-slate-600 font-medium mt-0.5">
                  Primary Strand: <strong class="text-slate-800">{{ searchResult.strand || 'TBA' }}</strong>
                </p>
                <p class="text-xs text-slate-600 font-medium mt-0.5">
                  School Year: <strong class="text-slate-800">{{ searchResult.school_year || '—' }}</strong>
                </p>
              </div>

              <div class="sm:text-right">
                <span
                  class="inline-flex items-center gap-1.5 bg-emerald-100 text-[#005506] text-xs font-black px-3 py-1 rounded-full uppercase tracking-wide">
                  <span class="w-2 h-2 rounded-full bg-[#005506] animate-pulse"></span>
                  {{ searchResult.status_label }}
                </span>
                <p class="text-[11px] text-slate-400 mt-1 font-medium">
                  Submitted on: {{ formatDate(searchResult.submitted_at) }}
                </p>
              </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-3">
              <div class="bg-white rounded-xl p-4 border border-slate-200/80 shadow-sm space-y-1">
                <div class="flex items-center gap-1.5 text-emerald-700 text-xs font-bold uppercase tracking-wider">
                  <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                      d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                  <span>SUBMITTED</span>
                </div>
                <h4 class="font-bold text-[#005506] text-sm">Form Encoded</h4>
                <p class="text-[11px] text-slate-500">Passed validation</p>
              </div>

              <div class="bg-white rounded-xl p-4 border border-slate-200/80 shadow-sm space-y-1">
                <div class="flex items-center gap-1.5 text-emerald-700 text-xs font-bold uppercase tracking-wider">
                  <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                      d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                  <span>VERIFIED</span>
                </div>
                <h4 class="font-bold text-[#005506] text-sm">PSA & Form 138</h4>
                <p class="text-[11px] text-slate-500">Registrar cleared</p>
              </div>

              <div class="bg-[#FFFBEA] rounded-xl p-4 border border-amber-300/80 shadow-sm space-y-1">
                <div class="flex items-center gap-1.5 text-amber-800 text-xs font-bold uppercase tracking-wider">
                  <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                  <span>IN PROGRESS</span>
                </div>
                <h4 class="font-bold text-[#005506] text-sm">Strand Interview</h4>
                <p class="text-[11px] text-amber-700 font-bold">Awaiting schedule</p>
              </div>

              <div class="bg-slate-100/60 rounded-xl p-4 border border-slate-200/50 space-y-1 opacity-70">
                <div class="flex items-center gap-1.5 text-slate-400 text-xs font-bold uppercase tracking-wider">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                  <span>PENDING</span>
                </div>
                <h4 class="font-bold text-slate-700 text-sm">COR & Section</h4>
                <p class="text-[11px] text-slate-400">Awaiting interview</p>
              </div>
            </div>

            <div v-if="searchResult.rejection_reason"
              class="bg-red-50 rounded-xl p-4 border border-red-200">
              <h5 class="text-xs font-bold text-red-700">Rejection Reason</h5>
              <p class="text-xs text-red-600 leading-normal mt-0.5">{{ searchResult.rejection_reason }}</p>
            </div>

            <div class="bg-emerald-50 rounded-xl p-4 border border-emerald-100 flex items-start gap-3">
              <div
                class="w-8 h-8 rounded-lg bg-emerald-100 text-[#005506] flex items-center justify-center shrink-0 mt-0.5">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round"
                    d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                </svg>
              </div>
              <div>
                <h5 class="text-xs font-bold text-[#005506]">Next Step</h5>
                <p class="text-xs text-slate-600 leading-normal mt-0.5">
                  Please keep an eye on your registered email/phone for the guidance office appointment. Bring your
                  original Form 138 hardcopy.
                </p>
              </div>
            </div>

          </div>

        </div>

      </div>

    </section>

    <!-- ADMISSIONS FAQS & REQUIREMENTS SECTION -->
    <section ref="faqRef" class="w-full max-w-[1500px] mx-auto px-6 sm:px-12 md:px-16 lg:px-24 py-16">

      <div class="transition-all duration-1000"
        :class="isFaqVisible ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-12'">

        <div class="text-center space-y-3 max-w-3xl mx-auto mb-10">
          <span
            class="inline-block bg-slate-200/70 text-slate-700 text-xs font-extrabold px-4 py-1.5 rounded-full uppercase tracking-wider">
            NEED GUIDANCE?
          </span>
          <h2 class="font-['Anton'] text-3xl sm:text-4xl md:text-5xl text-[#005506] tracking-wide uppercase">
            Admissions FAQs & Requirements
          </h2>
          <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
            Quick clarity on Senior High vouchers, transferee policies, uniform kits, and technical strands.
          </p>
        </div>

        <!-- Requirements card (from props) -->
        <div v-if="requirements.length"
          class="max-w-4xl mx-auto mb-10 bg-white rounded-2xl p-6 border border-slate-200/80 shadow-sm">
          <h3 class="font-bold text-[#005506] text-sm mb-3 uppercase tracking-wider">Required Documents Checklist</h3>
          <ul class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-xs sm:text-sm text-slate-700">
            <li v-for="(req, i) in requirements" :key="i" class="flex items-start gap-2">
              <span
                class="w-4 h-4 mt-0.5 rounded-full bg-emerald-100 text-[#005506] flex items-center justify-center text-[10px] font-bold shrink-0">✓</span>
              <span>{{ req }}</span>
            </li>
          </ul>
        </div>

        <div class="max-w-4xl mx-auto space-y-3">
          <div v-for="(faq, index) in faqs" :key="index"
            class="bg-white rounded-2xl border border-slate-200/80 overflow-hidden transition-all duration-300 shadow-sm hover:shadow-md">
            <button type="button" @click="toggleFaq(index)"
              class="w-full text-left px-6 py-5 flex items-center justify-between gap-4 font-bold text-[#005506] text-sm sm:text-base hover:text-emerald-700 transition-colors cursor-pointer">
              <span>{{ faq.question }}</span>
              <svg class="w-5 h-5 text-emerald-800 shrink-0 transition-transform duration-300"
                :class="{ 'rotate-180': openFaqIndex === index }" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
              </svg>
            </button>

            <div v-show="openFaqIndex === index"
              class="px-6 pb-6 text-xs sm:text-sm text-slate-600 leading-relaxed border-t border-slate-100 pt-4 animate-fade-in-up">
              {{ faq.answer }}
            </div>
          </div>
        </div>

      </div>

    </section>

    <!-- Shared Footer -->
    <Footer class="m-0 p-0 block" />

  </div>
</template>

<script setup>
import { ref, reactive, computed, onMounted, onUnmounted, nextTick } from 'vue'
import Swal from 'sweetalert2'
import axios from 'axios'
import Navbar from '@/Components/Navbar.vue'
import Footer from '@/Components/Footer.vue'
import { Head } from '@inertiajs/vue3'
import rawPsgcData from './data/psgc.min.json'

/* -------------------- PROPS FROM BACKEND -------------------- */
const props = defineProps({
  school: { type: Object, required: true },
  tracks: { type: Array, default: () => [] },
  requirements: { type: Array, default: () => [] },
  application_opens: { type: String, default: 'June 1' },
  application_closes: { type: String, default: 'August 31' },
})

/* -------------------- FLATTEN STRANDS FROM TRACKS -------------------- */
const allStrands = computed(() => {
  const list = []
  props.tracks.forEach(track => {
    (track.strands || []).forEach(strand => {
      list.push({
        id: strand.id,
        code: strand.code,
        name: strand.name,
        description: strand.description || '',
        track_name: track.name,
        track_code: track.code,
      })
    })
  })
  return list
})

const selectedClusterObject = computed(() =>
  allStrands.value.find(c => c.id === form.selected_cluster)
)

/* -------------------- STATE -------------------- */
const currentStep = ref(1)
const formContainer = ref(null)
const errors = reactive({})
const isSubmitting = ref(false)

// File input refs
const ref_psa = ref(null)
const ref_form137 = ref(null)
const ref_good_moral = ref(null)
const ref_picture = ref(null)

const MAX = {
  surname: 50, given_name: 50, middle_name: 50, suffix: 10,
  street: 100, religion: 50, jhs_name: 100, school_address: 150,
  father_name: 80, father_occupation: 60, father_education: 60,
  mother_name: 80, mother_occupation: 60, mother_education: 60,
  guardian_name: 80, guardian_occupation: 60, guardian_education: 60,
  lrn: 12, mobile_number: 11, zip_code: 4, year_graduated: 4,
}

const makeInitialForm = () => ({
  applicant_type: 'Grade11',
  desired_grade_level: '11',
  surname: '', given_name: '', middle_name: '', suffix: '',
  dob: '', age: '', sex: '', lrn: '', mobile_number: '', email: '',
  province: '', municipality: '', barangay: '', street: '', zip_code: '', religion: '',
  jhs_name: '', school_address: '', school_type: '', year_graduated: '2026',
  father_name: '', father_occupation: '', father_contact: '', father_education: '',
  mother_name: '', mother_occupation: '', mother_contact: '', mother_education: '',
  guardian_name: '', guardian_occupation: '', guardian_contact: '', guardian_education: '',
  selected_cluster: '',
})

const form = reactive(makeInitialForm())
const files = reactive({ psa: null, form137: null, good_moral: null, picture: null })
const fileNames = reactive({ psa: '', form137: '', good_moral: '', picture: '' })

/* -------------------- ADDRESS CASCADE -------------------- */
const addressData = ref([])
const provincesList = ref([])
const citiesList = ref([])
const barangaysList = ref([])

function buildAddressHierarchy(flatData) {
  const provinces = {}
  const municipalities = {}
  let currentProvince = null
  let currentMun = null

  flatData.forEach(entry => {
    const name = entry.Name || entry.name
    const level = entry['Geographic Level'] || entry.geographic_level || entry.level
    if (level === 'Prov') {
      currentProvince = name
      provinces[name] = { name, cities: [] }
      currentMun = null
    } else if (level === 'City' || level === 'Mun' || level === 'SubMun') {
      if (currentProvince) {
        const munObj = { name, barangays: [] }
        provinces[currentProvince].cities.push(munObj)
        currentMun = name
        municipalities[name] = munObj
      }
    } else if (level === 'Bgy') {
      if (currentMun && municipalities[currentMun]) {
        municipalities[currentMun].barangays.push(name)
      }
    }
  })
  return Object.values(provinces)
}

const onProvinceChange = () => {
  citiesList.value = []
  barangaysList.value = []
  form.municipality = ''
  form.barangay = ''
  const prov = addressData.value.find(p => p.name === form.province)
  if (prov) citiesList.value = prov.cities.slice().sort((a, b) => a.name.localeCompare(b.name))
}

const onMunicipalityChange = () => {
  barangaysList.value = []
  form.barangay = ''
  const prov = addressData.value.find(p => p.name === form.province)
  if (prov) {
    const mun = prov.cities.find(m => m.name === form.municipality)
    if (mun) barangaysList.value = mun.barangays.slice().sort((a, b) => a.localeCompare(b))
  }
}

/* -------------------- HELPERS -------------------- */
const calculateAge = () => {
  if (!form.dob) { form.age = ''; return }
  const birth = new Date(form.dob)
  const today = new Date()
  let age = today.getFullYear() - birth.getFullYear()
  const m = today.getMonth() - birth.getMonth()
  if (m < 0 || (m === 0 && today.getDate() < birth.getDate())) age--
  form.age = (age >= 0 && age <= 100) ? age : ''
}

const handleFileChange = (e, key) => {
  const file = e.target.files[0]
  if (file) {
    files[key] = file
    fileNames[key] = file.name
    delete errors[key]
  }
}

const sanitizeNumeric = (field, maxLen) => {
  let v = String(form[field] ?? '').replace(/\D/g, '')
  if (maxLen && v.length > maxLen) v = v.slice(0, maxLen)
  if (form[field] !== v) form[field] = v
}

const focusFirstError = () => {
  const firstKey = Object.keys(errors)[0]
  if (!firstKey) return
  nextTick(() => {
    const el = document.querySelector(`[data-field="${firstKey}"]`)
    if (el) {
      el.scrollIntoView({ behavior: 'smooth', block: 'center' })
      setTimeout(() => { try { el.focus({ preventScroll: true }) } catch (_) { } }, 350)
    }
  })
}

const scrollToForm = () => {
  nextTick(() => {
    if (formContainer.value) formContainer.value.scrollIntoView({ behavior: 'smooth', block: 'start' })
  })
}

const goToStep = (step) => {
  Object.keys(errors).forEach(k => delete errors[k])
  currentStep.value = step
  scrollToForm()
}

/* -------------------- VALIDATION -------------------- */
const namePattern = /^[A-Za-zÀ-ÿ\s\-'.]+$/
const alphaNumPattern = /^[A-Za-zÀ-ÿ0-9\s\-'.,&]+$/
const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/

const validateProfile = (prefix, label) => {
  const nameKey = `${prefix}_name`
  const occKey = `${prefix}_occupation`
  const contactKey = `${prefix}_contact`
  const eduKey = `${prefix}_education`

  const name = (form[nameKey] || '').trim()
  const occ = (form[occKey] || '').trim()
  const contact = (form[contactKey] || '').trim()
  const edu = (form[eduKey] || '').trim()

  const hasAny = name || occ || contact || edu
  if (!hasAny) return { active: false, valid: true }

  const errs = []
  if (!name) errs.push([nameKey, `${label} name is required.`])
  else if (name.length > MAX[nameKey]) errs.push([nameKey, `${label} name must be ${MAX[nameKey]} characters or less.`])
  else if (!namePattern.test(name)) errs.push([nameKey, `${label} name contains invalid characters.`])

  if (!occ) errs.push([occKey, `${label} occupation is required.`])
  else if (occ.length > MAX[occKey]) errs.push([occKey, `${label} occupation must be ${MAX[occKey]} characters or less.`])
  else if (!alphaNumPattern.test(occ)) errs.push([occKey, `${label} occupation contains invalid characters.`])

  if (!contact) errs.push([contactKey, `${label} contact number is required.`])
  else if (!/^\d{7,11}$/.test(contact)) errs.push([contactKey, `${label} contact must be 7–11 digits.`])

  if (!edu) errs.push([eduKey, `${label} educational attainment is required.`])
  else if (edu.length > MAX[eduKey]) errs.push([eduKey, `${label} educational attainment must be ${MAX[eduKey]} characters or less.`])
  else if (!alphaNumPattern.test(edu)) errs.push([eduKey, `${label} educational attainment contains invalid characters.`])

  return { active: true, valid: errs.length === 0, errs }
}

const handleNext = (step) => {
  Object.keys(errors).forEach(k => delete errors[k])

  if (step === 1) {
    if (!form.surname) errors.surname = 'Surname is required.'
    else if (form.surname.length > MAX.surname) errors.surname = `Surname must be ${MAX.surname} characters or less.`
    else if (!namePattern.test(form.surname)) errors.surname = 'Surname may only contain letters, spaces, hyphens, apostrophes, and periods.'

    if (!form.given_name) errors.given_name = 'Given name is required.'
    else if (form.given_name.length > MAX.given_name) errors.given_name = `Given name must be ${MAX.given_name} characters or less.`
    else if (!namePattern.test(form.given_name)) errors.given_name = 'Given name may only contain letters, spaces, hyphens, apostrophes, and periods.'

    if (form.middle_name) {
      if (form.middle_name.length > MAX.middle_name) errors.middle_name = `Middle name must be ${MAX.middle_name} characters or less.`
      else if (!namePattern.test(form.middle_name)) errors.middle_name = 'Middle name may only contain letters, spaces, hyphens, apostrophes, and periods.'
    }

    if (form.suffix) {
      if (form.suffix.length > MAX.suffix) errors.suffix = `Suffix must be ${MAX.suffix} characters or less.`
      else if (!/^[A-Za-z.]+$/.test(form.suffix)) errors.suffix = 'Suffix may only contain letters and periods.'
    }

    if (!form.dob) errors.dob = 'Date of birth is required.'
    else if (!form.age || form.age < 15 || form.age > 85) errors.dob = 'Age must be between 15 and 85.'

    if (!form.sex) errors.sex = 'Please select sex.'

    if (!form.lrn) errors.lrn = 'LRN is required.'
    else if (!/^\d{10,12}$/.test(form.lrn)) errors.lrn = 'LRN must be 10–12 digits.'

    if (!form.mobile_number) errors.mobile_number = 'Mobile number is required.'
    else if (!/^\d{10,11}$/.test(form.mobile_number)) errors.mobile_number = 'Mobile number must be 10–11 digits (e.g., 09XXXXXXXXX).'

    if (!form.email) errors.email = 'Email address is required.'
    else if (form.email.length > 100) errors.email = 'Email must be 100 characters or less.'
    else if (!emailPattern.test(form.email)) errors.email = 'Please enter a valid email address.'

    if (!form.province) errors.province = 'Province is required.'
    if (!form.municipality) errors.municipality = 'City/Municipality is required.'
    if (!form.barangay) errors.barangay = 'Barangay is required.'

    if (!form.street) errors.street = 'Street address is required.'
    else if (form.street.length > MAX.street) errors.street = `Street address must be ${MAX.street} characters or less.`
    else if (!/^[A-Za-z0-9\s.,#\-/]+$/.test(form.street)) errors.street = 'Street address contains invalid characters.'

    if (!form.zip_code) errors.zip_code = 'Zip code is required.'
    else if (!/^\d{4}$/.test(form.zip_code)) errors.zip_code = 'Zip code must be exactly 4 digits.'

    if (!form.religion) errors.religion = 'Religion is required.'
    else if (form.religion.length > MAX.religion) errors.religion = `Religion must be ${MAX.religion} characters or less.`
    else if (!/^[A-Za-z\s]+$/.test(form.religion)) errors.religion = 'Religion may only contain letters and spaces.'
  }

  if (step === 2) {
    if (!form.jhs_name) errors.jhs_name = 'JHS name is required.'
    else if (form.jhs_name.length > MAX.jhs_name) errors.jhs_name = `JHS name must be ${MAX.jhs_name} characters or less.`
    else if (!alphaNumPattern.test(form.jhs_name)) errors.jhs_name = 'JHS name contains invalid characters.'

    if (!form.school_address) errors.school_address = 'School address is required.'
    else if (form.school_address.length > MAX.school_address) errors.school_address = `School address must be ${MAX.school_address} characters or less.`
    else if (!/^[A-Za-z0-9\s.,#\-/]+$/.test(form.school_address)) errors.school_address = 'School address contains invalid characters.'

    if (!form.school_type) errors.school_type = 'School type is required.'

    const currentYear = new Date().getFullYear()
    if (!form.year_graduated) errors.year_graduated = 'Completion year is required.'
    else if (!/^\d{4}$/.test(form.year_graduated)) errors.year_graduated = 'Completion year must be 4 digits.'
    else {
      const y = parseInt(form.year_graduated, 10)
      if (y < 1990 || y > currentYear) errors.year_graduated = `Year must be between 1990 and ${currentYear}.`
    }

    if (!files.psa) errors.psa = true
    if (!files.form137) errors.form137 = true
    if (!files.good_moral) errors.good_moral = true
    if (!files.picture) errors.picture = true
  }

  if (step === 3) {
    const father = validateProfile('father', 'Father')
    const mother = validateProfile('mother', 'Mother')
    const guardian = validateProfile('guardian', 'Guardian')

    ;[...father.errs, ...mother.errs, ...guardian.errs].forEach(([k, m]) => { errors[k] = m })

    const hasComplete = [father, mother, guardian].some(p => p.active && p.valid)
    if (!hasComplete && Object.keys(errors).length === 0) {
      errors.father_name = 'Please complete at least one parent/guardian profile.'
    }
  }

  if (step === 4) {
    if (!form.selected_cluster) {
      Swal.fire({
        icon: 'warning',
        title: 'Track Required',
        text: 'Please select a senior high school cluster.',
        confirmButtonColor: '#006907'
      })
      return
    }
  }

  if (Object.keys(errors).length > 0) {
    focusFirstError()
    return
  }

  currentStep.value = step + 1
  scrollToForm()
}

const getStepBadgeClass = (step) => {
  if (currentStep.value > step) return 'w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm bg-emerald-100 text-emerald-800 border-2 border-emerald-600'
  if (currentStep.value === step) return 'w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm bg-emerald-700 text-white shadow-lg'
  return 'w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm bg-white text-slate-500 border-2 border-slate-300'
}

/* -------------------- SUBMIT & RESET -------------------- */
const resetApplication = () => {
  Object.assign(form, makeInitialForm())
  files.psa = files.form137 = files.good_moral = files.picture = null
  fileNames.psa = fileNames.form137 = fileNames.good_moral = fileNames.picture = ''
  ;[ref_psa, ref_form137, ref_good_moral, ref_picture].forEach(r => { if (r.value) r.value.value = '' })
  Object.keys(errors).forEach(k => delete errors[k])
  citiesList.value = []
  barangaysList.value = []
  currentStep.value = 1

  nextTick(() => {
    if (formContainer.value) formContainer.value.scrollIntoView({ behavior: 'smooth', block: 'start' })
    else window.scrollTo({ top: 0, behavior: 'smooth' })
  })
}

const submitApplication = async () => {
  isSubmitting.value = true
  try {
    const payload = new FormData()

    // Personal
    payload.append('strand_id', form.selected_cluster)
    payload.append('applicant_type', form.applicant_type)
    payload.append('desired_grade_level', form.desired_grade_level)
    payload.append('last_name', form.surname)
    payload.append('first_name', form.given_name)
    payload.append('middle_name', form.middle_name || '')
    payload.append('extension_name', form.suffix || '')
    payload.append('lrn', form.lrn)
    payload.append('date_of_birth', form.dob)
    payload.append('sex', form.sex)
    payload.append('religion', form.religion)
    payload.append('contact_number', form.mobile_number)
    payload.append('email', form.email)

    // Address
    payload.append('house_street', form.street)
    payload.append('barangay', form.barangay)
    payload.append('municipality', form.municipality)
    payload.append('province', form.province)
    payload.append('zip_code', form.zip_code)

    // Previous school
    payload.append('prev_school_name', form.jhs_name)
    payload.append('prev_school_address', form.school_address)
    payload.append('prev_school_type', form.school_type)
    payload.append('last_school_year', form.year_graduated)

    // Contacts (only those with name)
    const contacts = [
      { role: 'father', full_name: form.father_name, occupation: form.father_occupation, contact_number: form.father_contact, relationship: 'Father' },
      { role: 'mother', full_name: form.mother_name, occupation: form.mother_occupation, contact_number: form.mother_contact, relationship: 'Mother' },
      { role: 'guardian', full_name: form.guardian_name, occupation: form.guardian_occupation, contact_number: form.guardian_contact, relationship: 'Guardian' },
    ].filter(c => (c.full_name || '').trim())

    contacts.forEach((c, i) => {
      payload.append(`contacts[${i}][role]`, c.role)
      payload.append(`contacts[${i}][full_name]`, c.full_name)
      payload.append(`contacts[${i}][relationship]`, c.relationship)
      payload.append(`contacts[${i}][occupation]`, c.occupation || '')
      payload.append(`contacts[${i}][contact_number]`, c.contact_number || '')
    })

    // Documents
    const uploads = [
      { key: 'psa', label: 'PSA Birth Certificate' },
      { key: 'form137', label: 'Form 137' },
      { key: 'good_moral', label: 'Good Moral Certificate' },
      { key: 'picture', label: '2x2 ID Photo' },
    ]
    uploads.forEach((u, i) => {
      if (files[u.key]) {
        payload.append(`documents[${i}]`, files[u.key])
        payload.append(`document_types[${i}]`, u.label)
      }
    })

    const { data } = await axios.post('/site/admission/apply', payload, {
      headers: { 'Content-Type': 'multipart/form-data' },
    })

    await Swal.fire({
      icon: 'success',
      title: 'Application Submitted!',
      html: `Your reference number is:<br><strong class="text-lg tracking-wider">${data.reference_number}</strong><br><br>Please save this for tracking your status.`,
      confirmButtonColor: '#006907',
      allowOutsideClick: false,
      allowEscapeKey: false,
    })

    // Set the tracker code so the user can immediately check
    trackingCode.value = data.reference_number
    resetApplication()
  } catch (err) {
    const message = err.response?.data?.message || 'Submission failed. Please check all fields and try again.'
    const fieldErrors = err.response?.data?.errors || {}
    Object.assign(errors, Object.fromEntries(
      Object.entries(fieldErrors).map(([k, v]) => [k, Array.isArray(v) ? v[0] : v])
    ))
    Swal.fire({
      icon: 'error',
      title: 'Could not submit',
      text: message,
      confirmButtonColor: '#b91c1c',
    })
  } finally {
    isSubmitting.value = false
  }
}

/* -------------------- TRACKER -------------------- */
const trackingCode = ref('')
const searchResult = ref(null)
const searchError = ref('')
const isLookingUp = ref(false)

const formatDate = (iso) => {
  if (!iso) return '—'
  try {
    return new Date(iso).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' })
  } catch { return iso }
}

const lookupApplication = async () => {
  searchError.value = ''
  searchResult.value = null
  if (!trackingCode.value.trim()) return

  isLookingUp.value = true
  try {
    const { data } = await axios.get(`/site/admission/status/${encodeURIComponent(trackingCode.value.trim())}`)
    searchResult.value = data.applicant
  } catch (err) {
    searchError.value = err.response?.data?.message || 'Application not found. Please check your reference number.'
  } finally {
    isLookingUp.value = false
  }
}

/* -------------------- FAQS -------------------- */
const openFaqIndex = ref(0)
const toggleFaq = (index) => {
  openFaqIndex.value = openFaqIndex.value === index ? null : index
}

const faqs = [
  {
    question: 'Are there any tuition or laboratory registration fees at Salawag Senior High School?',
    answer: 'Salawag Senior High School is a 100% tuition-free DepEd Public High School. All public JHS completers enter automatically without fees. Completers from private junior high schools are fully covered under the DepEd Senior High School Voucher Program (SHS-VP) and do not need to pay top-up fees.'
  },
  {
    question: 'What if my Grade 10 Final Report Card (Form 138) is still incomplete or not yet released?',
    answer: 'You may temporarily upload your latest available 3rd Quarter Report Card or a signed Certificate of Enrollment from your previous principal. Final official enrollment confirmation requires submitting the original Form 138 before classes start.'
  },
  {
    question: 'Are tools, PPEs, and culinary ingredients provided for TVL track students?',
    answer: 'Yes. Salawag SHS provides all specialized laboratory equipment, protective safety gear (PPE), and basic raw culinary materials through state voucher allocations with zero out-of-pocket costs.'
  },
  {
    question: 'Can transferees from other senior high schools in Cavite apply?',
    answer: 'Yes. Grade 11 transferees and ALS completers with certified eligibility certificates are welcome to enroll subject to available strand slots.'
  }
]

/* -------------------- SCROLL OBSERVERS -------------------- */
const phasesRef = ref(null)
const statusRef = ref(null)
const faqRef = ref(null)

const isPhasesVisible = ref(false)
const isApplicationVisible = ref(false)
const isStatusVisible = ref(false)
const isFaqVisible = ref(false)

let observer = null

onMounted(() => {
  // Address data
  try {
    const flatData = Array.isArray(rawPsgcData) ? rawPsgcData : (rawPsgcData.default || [])
    addressData.value = buildAddressHierarchy(flatData)
    provincesList.value = addressData.value.slice().sort((a, b) => a.name.localeCompare(b.name))
  } catch (e) {
    console.error('PSGC data processing failed:', e)
  }

  // Scroll reveal
  if (typeof IntersectionObserver !== 'undefined') {
    observer = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry) => {
          if (entry.target === phasesRef.value) isPhasesVisible.value = entry.isIntersecting
          else if (entry.target === statusRef.value) isStatusVisible.value = entry.isIntersecting
          else if (entry.target === faqRef.value) isFaqVisible.value = entry.isIntersecting
          else if (entry.target === formContainer.value) isApplicationVisible.value = entry.isIntersecting
        })
      },
      { threshold: 0.12 }
    )

    nextTick(() => {
      if (phasesRef.value) observer.observe(phasesRef.value)
      if (statusRef.value) observer.observe(statusRef.value)
      if (faqRef.value) observer.observe(faqRef.value)
      if (formContainer.value) observer.observe(formContainer.value)
    })
  } else {
    isPhasesVisible.value = true
    isApplicationVisible.value = true
    isStatusVisible.value = true
    isFaqVisible.value = true
  }
})

onUnmounted(() => {
  if (observer) observer.disconnect()
})
</script>

<style scoped>
@keyframes fadeInUp {
  from { opacity: 0; transform: translateY(20px); }
  to { opacity: 1; transform: translateY(0); }
}
@keyframes heroZoom {
  0% { transform: scale(1); }
  50% { transform: scale(1.08); }
  100% { transform: scale(1); }
}
@keyframes floatSoft {
  0%, 100% { transform: translateY(0px); }
  50% { transform: translateY(-3px); }
}
@keyframes pulseSlow {
  0%, 100% { opacity: 0.3; }
  50% { opacity: 0.7; }
}
@keyframes sheenMove {
  0% { transform: translateX(-100%); }
  100% { transform: translateX(200%); }
}
.animate-fade-in-up {
  animation: fadeInUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}
.animate-hero-zoom {
  animation: heroZoom 20s ease-in-out infinite;
}
.animate-float-soft {
  animation: floatSoft 4s ease-in-out infinite;
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

.cluster-card.selected {
  border-color: #059669 !important;
  box-shadow: 0 0 0 4px rgba(5, 150, 105, 0.2);
  transform: scale(1.02);
}

/* Prevent number spinner arrows on numeric fields */
input[inputmode="numeric"]::-webkit-outer-spin-button,
input[inputmode="numeric"]::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}
input[inputmode="numeric"] {
  -moz-appearance: textfield;
}
</style>