<template>

  <Head title="Admissions - Salawag LMS" />

  <!-- Main Background Canvas -->
  <div class="min-h-screen bg-[#E8FFE8] font-['Inter'] text-slate-800 overflow-x-hidden">

    <!-- OVERLAPPED HERO BANNER SECTION (BRAND GREEN #004d08 WITH IMAGE OVERLAY) -->
    <section
      class="relative w-full min-h-[65vh] md:min-h-[72vh] bg-[#004d08] text-white overflow-hidden shadow-md flex items-center">

      <!-- Overlapped Transparent Navbar Component -->
      <Navbar />

      <!-- Background Image Overlay with Green Tint & Gradient -->
      <div class="absolute inset-0 z-0 pointer-events-none overflow-hidden">
        <img src="https://images.unsplash.com/photo-1523240795612-9a054b0db644?auto=format&fit=crop&w=1920&q=80"
          alt="Salawag High School Admissions & Students"
          class="w-full h-full object-cover object-center opacity-25 mix-blend-overlay scale-105 animate-hero-zoom transition-transform duration-1000" />
        <div class="absolute inset-0 bg-gradient-to-r from-[#004d08]/98 via-[#004d08]/88 to-[#003805]/75"></div>
      </div>

      <!-- Animated Gradient Sheen -->
      <div
        class="absolute inset-0 bg-gradient-to-r from-transparent via-white/5 to-transparent animate-sheen pointer-events-none z-0">
      </div>

      <!-- Ambient Glow Effects -->
      <div
        class="absolute -top-24 -left-24 w-96 h-96 bg-emerald-400/15 rounded-full blur-3xl pointer-events-none animate-pulse-slow">
      </div>
      <div
        class="absolute -bottom-24 right-0 w-96 h-96 bg-amber-400/10 rounded-full blur-3xl pointer-events-none animate-pulse-slow delay-1000">
      </div>

      <!-- Hero Banner Content -->
      <div
        class="relative z-10 w-full max-w-[1500px] mx-auto px-6 sm:px-12 md:px-16 lg:px-24 pt-28 md:pt-36 pb-16 space-y-6">

        <!-- Main Headline -->
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
        </div>

        <!-- Action Buttons -->
        <div class="pt-3 flex flex-wrap items-center gap-4 animate-fade-in-up animation-delay-400">
          <a href="#registration-form"
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
    <section class="w-full max-w-[1500px] mx-auto px-6 sm:px-12 md:px-16 lg:px-24 py-16 md:py-20">

      <!-- Header -->
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

      <!-- 5-Cards Grid -->
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-5 items-stretch">

        <!-- PHASE 01 -->
        <div
          class="bg-white rounded-3xl p-6 shadow-sm border border-slate-200/90 flex flex-col justify-between space-y-6 hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
          <div class="space-y-4">
            <div class="flex items-center justify-between">
              <span
                class="w-10 h-10 rounded-full bg-[#005506] text-white font-black text-sm flex items-center justify-center shadow-md">
                1
              </span>
              <span
                class="bg-[#eaf5ed] text-[#005506] text-[10px] font-extrabold px-3 py-1 rounded-full uppercase tracking-wider">
                PHASE 01
              </span>
            </div>

            <div class="space-y-2">
              <h3 class="font-bold text-slate-900 text-lg leading-snug">
                Online Pre-Registration
              </h3>
              <p class="text-xs text-slate-500 leading-relaxed font-normal">
                Encode your 12-digit DepEd LRN, primary strand interests, and parental guardian contacts.
              </p>
            </div>
          </div>

          <div
            class="pt-4 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-500 font-semibold">
            <div class="flex items-center gap-1.5 text-[#005506]">
              <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round"
                  d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
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
                class="w-10 h-10 rounded-full bg-amber-400 text-slate-950 font-black text-sm flex items-center justify-center shadow-md">
                2
              </span>
              <span
                class="bg-amber-100/80 text-amber-900 text-[10px] font-extrabold px-3 py-1 rounded-full uppercase tracking-wider">
                PHASE 02
              </span>
            </div>

            <div class="space-y-2">
              <h3 class="font-bold text-slate-900 text-lg leading-snug">
                Credential Scan & Upload
              </h3>
              <p class="text-xs text-slate-500 leading-relaxed font-normal">
                Submit clear snapshots or PDFs of Form 138 (G10 Card), PSA Birth Certificate, and Certificate of Good
                Moral.
              </p>
            </div>
          </div>

          <div
            class="pt-4 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-500 font-semibold">
            <div class="flex items-center gap-1.5 text-amber-700">
              <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round"
                  d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
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
                class="w-10 h-10 rounded-full bg-[#005506] text-white font-black text-sm flex items-center justify-center shadow-md">
                3
              </span>
              <span
                class="bg-[#eaf5ed] text-[#005506] text-[10px] font-extrabold px-3 py-1 rounded-full uppercase tracking-wider">
                PHASE 03
              </span>
            </div>

            <div class="space-y-2">
              <h3 class="font-bold text-slate-900 text-lg leading-snug">
                Aptitude & Guidance
              </h3>
              <p class="text-xs text-slate-500 leading-relaxed font-normal">
                Brief career profiling assessment or online counselor session to confirm your strand fit.
              </p>
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
                class="w-10 h-10 rounded-full bg-[#005506] text-white font-black text-sm flex items-center justify-center shadow-md">
                4
              </span>
              <span
                class="bg-slate-100 text-slate-700 text-[10px] font-extrabold px-3 py-1 rounded-full uppercase tracking-wider">
                PHASE 04
              </span>
            </div>

            <div class="space-y-2">
              <h3 class="font-bold text-slate-900 text-lg leading-snug">
                Official Section & COR
              </h3>
              <p class="text-xs text-slate-500 leading-relaxed font-normal">
                Receive your digital Certificate of Registration (COR), assigned homeroom adviser, and schedule.
              </p>
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
                class="w-10 h-10 rounded-full bg-amber-400 text-slate-950 font-black text-sm flex items-center justify-center shadow-md">
                5
              </span>
              <span
                class="bg-amber-100/80 text-amber-900 text-[10px] font-extrabold px-3 py-1 rounded-full uppercase tracking-wider">
                PHASE 05
              </span>
            </div>

            <div class="space-y-2">
              <h3 class="font-bold text-slate-900 text-lg leading-snug">
                RFID & Welcome Kit
              </h3>
              <p class="text-xs text-slate-500 leading-relaxed font-normal">
                Visit the campus media hub to capture your biometrics, pick up student manual, and receive official
                badge.
              </p>
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

    </section>

    <!-- MAIN CONTAINER -->
    <main class="w-full pl-4 md:pl-16 pr-4 md:pr-16 pb-8 flex-grow relative">

      <!-- APPLICATION FLOW CONTAINER -->
      <div id="view-application-flow" ref="formContainer"
        class="relative z-10 w-full max-w-[90%] md:max-w-[90%] lg:max-w-[900px] mx-auto mt-8 mb-12 pt-4 md:pt-8 scroll-mt-6">

        <!-- Top Header Card -->
        <div
          class="bg-white rounded-2xl p-6 mt-10 sm:p-8 shadow-xl border border-emerald-100 mb-8 relative overflow-hidden">
          <div class="relative z-10">
            <span
              class="inline-block bg-emerald-100 text-emerald-800 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider mb-2">
              SY 2026–2027 Admissions
            </span>
            <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Student Application Form</h2>
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

          <!-- STEP 1: PERSONAL INFORMATION -->
          <div v-if="currentStep === 1" class="p-6 sm:p-10 space-y-6">
            <div class="border-b border-slate-100 pb-4">
              <h3 class="text-xl font-bold text-slate-800">1. Personal Information</h3>
              <p class="text-xs text-slate-500 mt-0.5">Fill out basic demographic and contact information.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
              <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Surname <span
                    class="text-red-500">*</span></label>
                <input type="text" ref="ref_surname" v-model="form.surname"
                  class="w-full px-3.5 py-2.5 rounded-lg border text-sm transition-all"
                  :class="errors.surname ? 'border-red-500 ring-1 ring-red-500' : 'border-slate-300'">
                <p v-if="errors.surname" class="text-red-500 text-xs mt-1">{{ errors.surname }}</p>
              </div>
              <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Given Name <span
                    class="text-red-500">*</span></label>
                <input type="text" ref="ref_given_name" v-model="form.given_name"
                  class="w-full px-3.5 py-2.5 rounded-lg border text-sm transition-all"
                  :class="errors.given_name ? 'border-red-500 ring-1 ring-red-500' : 'border-slate-300'">
                <p v-if="errors.given_name" class="text-red-500 text-xs mt-1">{{ errors.given_name }}</p>
              </div>
              <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Middle Name</label>
                <input type="text" v-model="form.middle_name"
                  class="w-full px-3.5 py-2.5 rounded-lg border border-slate-300 text-sm">
              </div>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
              <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Suffix</label>
                <input type="text" v-model="form.suffix" placeholder="e.g. Jr., III"
                  class="w-full px-3.5 py-2.5 rounded-lg border border-slate-300 text-sm">
              </div>
              <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Date of Birth <span
                    class="text-red-500">*</span></label>
                <input type="date" ref="ref_dob" v-model="form.dob" @change="calculateAge"
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
                <select ref="ref_sex" v-model="form.sex"
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
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">LRN (10-12 digits)
                  <span class="text-red-500">*</span></label>
                <input type="text" ref="ref_lrn" v-model="form.lrn" maxlength="12"
                  class="w-full px-3.5 py-2.5 rounded-lg border text-sm transition-all"
                  :class="errors.lrn ? 'border-red-500 ring-1 ring-red-500' : 'border-slate-300'">
                <p v-if="errors.lrn" class="text-red-500 text-xs mt-1">{{ errors.lrn }}</p>
              </div>
              <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Mobile Number <span
                    class="text-red-500">*</span></label>
                <input type="tel" ref="ref_mobile_number" v-model="form.mobile_number" placeholder="09XXXXXXXXX"
                  class="w-full px-3.5 py-2.5 rounded-lg border text-sm transition-all"
                  :class="errors.mobile_number ? 'border-red-500 ring-1 ring-red-500' : 'border-slate-300'">
                <p v-if="errors.mobile_number" class="text-red-500 text-xs mt-1">{{ errors.mobile_number }}</p>
              </div>
              <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Email Address <span
                    class="text-red-500">*</span></label>
                <input type="email" ref="ref_email" v-model="form.email"
                  class="w-full px-3.5 py-2.5 rounded-lg border text-sm transition-all"
                  :class="errors.email ? 'border-red-500 ring-1 ring-red-500' : 'border-slate-300'">
                <p v-if="errors.email" class="text-red-500 text-xs mt-1">{{ errors.email }}</p>
              </div>
            </div>

            <!-- Address Section (PSGC Data Linked) -->
            <div class="pt-2 border-t border-slate-100">
              <span class="text-xs font-bold text-emerald-800 uppercase tracking-wider block mb-3">Residential
                Address</span>
              <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-4">
                <div>
                  <label class="block text-xs font-semibold text-slate-600 mb-1">Province <span
                      class="text-red-500">*</span></label>
                  <select ref="ref_province" v-model="form.province" @change="onProvinceChange"
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
                  <select ref="ref_municipality" v-model="form.municipality" @change="onMunicipalityChange"
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
                  <select ref="ref_barangay" v-model="form.barangay"
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
                  <input type="text" ref="ref_street" v-model="form.street"
                    class="w-full px-3.5 py-2.5 rounded-lg border text-sm transition-all"
                    :class="errors.street ? 'border-red-500 ring-1 ring-red-500' : 'border-slate-300'">
                  <p v-if="errors.street" class="text-red-500 text-xs mt-1">{{ errors.street }}</p>
                </div>
                <div>
                  <label class="block text-xs font-semibold text-slate-600 mb-1">Zip Code <span
                      class="text-red-500">*</span></label>
                  <input type="text" ref="ref_zip_code" v-model="form.zip_code" maxlength="4"
                    class="w-full px-3.5 py-2.5 rounded-lg border text-sm transition-all"
                    :class="errors.zip_code ? 'border-red-500 ring-1 ring-red-500' : 'border-slate-300'">
                  <p v-if="errors.zip_code" class="text-red-500 text-xs mt-1">{{ errors.zip_code }}</p>
                </div>
              </div>

              <div class="mt-3">
                <label class="block text-xs font-semibold text-slate-600 mb-1">Religion <span
                    class="text-red-500">*</span></label>
                <input type="text" ref="ref_religion" v-model="form.religion"
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

          <!-- STEP 2: SCHOOL HISTORY & UPLOADS -->
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
                <input type="text" ref="ref_jhs_name" v-model="form.jhs_name"
                  class="w-full px-3.5 py-2.5 rounded-lg border text-sm transition-all"
                  :class="errors.jhs_name ? 'border-red-500 ring-1 ring-red-500' : 'border-slate-300'">
                <p v-if="errors.jhs_name" class="text-red-500 text-xs mt-1">{{ errors.jhs_name }}</p>
              </div>
              <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">School Address <span
                    class="text-red-500">*</span></label>
                <input type="text" ref="ref_school_address" v-model="form.school_address"
                  class="w-full px-3.5 py-2.5 rounded-lg border text-sm transition-all"
                  :class="errors.school_address ? 'border-red-500 ring-1 ring-red-500' : 'border-slate-300'">
                <p v-if="errors.school_address" class="text-red-500 text-xs mt-1">{{ errors.school_address }}</p>
              </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">School Type <span
                    class="text-red-500">*</span></label>
                <select ref="ref_school_type" v-model="form.school_type"
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
                <input type="text" ref="ref_year_graduated" v-model="form.year_graduated" placeholder="2026"
                  class="w-full px-3.5 py-2.5 rounded-lg border text-sm transition-all"
                  :class="errors.year_graduated ? 'border-red-500 ring-1 ring-red-500' : 'border-slate-300'">
                <p v-if="errors.year_graduated" class="text-red-500 text-xs mt-1">{{ errors.year_graduated }}</p>
              </div>
            </div>

            <!-- Document Upload Section -->
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
                    <p class="text-xs font-bold text-slate-800">Form 137 / SF10 <span class="text-red-500">*</span></p>
                    <p class="text-[11px]" :class="fileNames.form137 ? 'text-emerald-600 font-bold' : 'text-slate-400'">
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
                    <p class="text-[11px]" :class="fileNames.picture ? 'text-emerald-600 font-bold' : 'text-slate-400'">
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

          <!-- STEP 3: PARENT / GUARDIAN DETAILS -->
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
                  <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Full Name</label>
                  <input type="text" ref="ref_father_name" v-model="form.father_name" placeholder="e.g. Juan Dela Cruz"
                    class="w-full px-3.5 py-2.5 rounded-lg border text-sm transition-all"
                    :class="errors.father_name ? 'border-red-500 ring-1 ring-red-500' : 'border-slate-300'">
                </div>
                <div>
                  <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Occupation</label>
                  <input type="text" ref="ref_father_occupation" v-model="form.father_occupation"
                    placeholder="e.g. Engineer" class="w-full px-3.5 py-2.5 rounded-lg border text-sm transition-all"
                    :class="errors.father_occupation ? 'border-red-500 ring-1 ring-red-500' : 'border-slate-300'">
                </div>
                <div>
                  <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Contact
                    Number</label>
                  <input type="tel" ref="ref_father_contact" v-model="form.father_contact" placeholder="09XXXXXXXXX"
                    class="w-full px-3.5 py-2.5 rounded-lg border text-sm transition-all"
                    :class="errors.father_contact ? 'border-red-500 ring-1 ring-red-500' : 'border-slate-300'">
                </div>
                <div>
                  <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Educational
                    Attainment</label>
                  <input type="text" ref="ref_father_education" v-model="form.father_education"
                    placeholder="e.g. College Graduate"
                    class="w-full px-3.5 py-2.5 rounded-lg border text-sm transition-all"
                    :class="errors.father_education ? 'border-red-500 ring-1 ring-red-500' : 'border-slate-300'">
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
                  <input type="text" ref="ref_mother_name" v-model="form.mother_name" placeholder="e.g. Maria Santos"
                    class="w-full px-3.5 py-2.5 rounded-lg border text-sm transition-all"
                    :class="errors.mother_name ? 'border-red-500 ring-1 ring-red-500' : 'border-slate-300'">
                </div>
                <div>
                  <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Occupation</label>
                  <input type="text" ref="ref_mother_occupation" v-model="form.mother_occupation"
                    placeholder="e.g. Teacher" class="w-full px-3.5 py-2.5 rounded-lg border text-sm transition-all"
                    :class="errors.mother_occupation ? 'border-red-500 ring-1 ring-red-500' : 'border-slate-300'">
                </div>
                <div>
                  <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Contact
                    Number</label>
                  <input type="tel" ref="ref_mother_contact" v-model="form.mother_contact" placeholder="09XXXXXXXXX"
                    class="w-full px-3.5 py-2.5 rounded-lg border text-sm transition-all"
                    :class="errors.mother_contact ? 'border-red-500 ring-1 ring-red-500' : 'border-slate-300'">
                </div>
                <div>
                  <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Educational
                    Attainment</label>
                  <input type="text" ref="ref_mother_education" v-model="form.mother_education"
                    placeholder="e.g. College Graduate"
                    class="w-full px-3.5 py-2.5 rounded-lg border text-sm transition-all"
                    :class="errors.mother_education ? 'border-red-500 ring-1 ring-red-500' : 'border-slate-300'">
                </div>
              </div>
            </div>

            <!-- Guardian -->
            <div class="space-y-3 pt-3 border-t border-slate-100">
              <h4 class="text-xs font-bold text-emerald-800 uppercase tracking-wider">Guardian's Information</h4>
              <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                <div>
                  <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Full Name</label>
                  <input type="text" ref="ref_guardian_name" v-model="form.guardian_name"
                    placeholder="e.g. Pedro Santos"
                    class="w-full px-3.5 py-2.5 rounded-lg border text-sm transition-all"
                    :class="errors.guardian_name ? 'border-red-500 ring-1 ring-red-500' : 'border-slate-300'">
                </div>
                <div>
                  <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Occupation</label>
                  <input type="text" ref="ref_guardian_occupation" v-model="form.guardian_occupation"
                    placeholder="e.g. Self-employed"
                    class="w-full px-3.5 py-2.5 rounded-lg border text-sm transition-all"
                    :class="errors.guardian_occupation ? 'border-red-500 ring-1 ring-red-500' : 'border-slate-300'">
                </div>
                <div>
                  <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Contact
                    Number</label>
                  <input type="tel" ref="ref_guardian_contact" v-model="form.guardian_contact" placeholder="09XXXXXXXXX"
                    class="w-full px-3.5 py-2.5 rounded-lg border text-sm transition-all"
                    :class="errors.guardian_contact ? 'border-red-500 ring-1 ring-red-500' : 'border-slate-300'">
                </div>
                <div>
                  <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Educational
                    Attainment</label>
                  <input type="text" ref="ref_guardian_education" v-model="form.guardian_education"
                    placeholder="e.g. High School Graduate"
                    class="w-full px-3.5 py-2.5 rounded-lg border text-sm transition-all"
                    :class="errors.guardian_education ? 'border-red-500 ring-1 ring-red-500' : 'border-slate-300'">
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

          <!-- STEP 4: TRACK SELECTION -->
          <div v-if="currentStep === 4" class="p-6 sm:p-10 space-y-6">
            <div class="border-b border-slate-100 pb-4">
              <h3 class="text-xl font-bold text-slate-800">4. Select Senior High School Track</h3>
              <p class="text-xs text-slate-500 mt-0.5">Select one preferred strand or specialized elective cluster.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div v-for="cluster in sampleClusters" :key="cluster.cluster_id"
                @click="form.selected_cluster = cluster.cluster_id"
                class="cluster-card border-2 border-slate-200 hover:border-emerald-400 rounded-2xl p-5 cursor-pointer transition-all bg-white"
                :class="{ 'selected border-emerald-600 shadow-md ring-2 ring-emerald-500': form.selected_cluster === cluster.cluster_id }">
                <div class="flex justify-between items-center mb-2">
                  <span class="text-xs font-bold bg-emerald-100 text-emerald-800 px-2.5 py-1 rounded-md">{{
                    cluster.cluster_code }}</span>
                  <span class="text-xs text-slate-400 font-medium">{{ cluster.track_name }}</span>
                </div>
                <h4 class="font-bold text-slate-900 text-base">{{ cluster.cluster_name }}</h4>
                <p class="text-xs text-slate-500 mt-1 leading-relaxed">{{ cluster.description }}</p>
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

          <!-- STEP 5: REVIEW & SUBMIT -->
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
                <div><span class="font-semibold text-slate-600">City/Municipality:</span> {{ form.municipality }}</div>
                <div><span class="font-semibold text-slate-600">Barangay:</span> {{ form.barangay }}</div>
                <div><span class="font-semibold text-slate-600">Street:</span> {{ form.street }}</div>
                <div><span class="font-semibold text-slate-600">Zip Code:</span> {{ form.zip_code }}</div>
                <div><span class="font-semibold text-slate-600">Religion:</span> {{ form.religion }}</div>
              </div>

              <h4 class="font-bold text-slate-700 border-b border-slate-200 pb-2 mt-4">Selected Track</h4>
              <div class="flex items-center gap-4">
                <span class="font-semibold text-slate-600">Track:</span>
                <div class="w-56 h-20 bg-emerald-700 rounded-xl p-3 flex flex-col justify-center">
                  <span class="font-bold text-white text-sm">{{ selectedClusterObject?.cluster_code || '---' }}</span>
                  <span class="text-xs text-emerald-200">{{ selectedClusterObject?.cluster_name || 'No cluster selected'
                    }}</span>
                </div>
              </div>
            </div>

            <div class="flex justify-between pt-4">
              <button type="button" @click="goToStep(4)"
                class="px-6 py-2.5 bg-slate-200 hover:bg-slate-300 text-slate-700 font-bold rounded-xl transition-all text-sm">
                ← Back
              </button>
              <button type="submit"
                class="px-10 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold rounded-xl shadow-lg shadow-emerald-600/30 transition-all text-sm uppercase tracking-wider">
                Submit Application ✓
              </button>
            </div>
          </div>

        </form>
      </div>
    </main>

    <!-- Shared Footer -->
    <Footer class="m-0 p-0 block" />

  </div>
</template>

<script setup>
import { ref, reactive, computed, onMounted, nextTick } from 'vue'
import Swal from 'sweetalert2'
import Navbar from '@/Components/Navbar.vue'
import Footer from '@/Components/Footer.vue'
import { Head } from '@inertiajs/vue3'

// Import PSGC data directly through Vite bundler
// Supports both .js (export default [...]) or .json formats
import rawPsgcData from './data/psgc.min.json'

const currentStep = ref(1)
const formContainer = ref(null)
const errors = reactive({})

const form = reactive({
  applicant_type: 'Grade11',
  desired_grade_level: '11',
  selected_track: 'ASSH',
  surname: '',
  given_name: '',
  middle_name: '',
  suffix: '',
  dob: '',
  age: '',
  sex: '',
  lrn: '',
  mobile_number: '',
  email: '',
  province: '',
  municipality: '',
  barangay: '',
  street: '',
  zip_code: '',
  religion: '',
  jhs_name: '',
  school_address: '',
  school_type: '',
  year_graduated: '2026',
  father_name: '',
  father_occupation: '',
  father_contact: '',
  father_education: '',
  mother_name: '',
  mother_occupation: '',
  mother_contact: '',
  mother_education: '',
  guardian_name: '',
  guardian_occupation: '',
  guardian_contact: '',
  guardian_education: '',
  selected_cluster: ''
})

const files = reactive({ psa: null, form137: null, good_moral: null, picture: null })
const fileNames = reactive({ psa: '', form137: '', good_moral: '', picture: '' })

const sampleClusters = [
  { cluster_id: '1', cluster_code: 'STEM-01', track_name: 'Academic Track', cluster_name: 'Science & Engineering Cluster', description: 'Specialized focus on advanced calculus, physics, chemistry, and research.' },
  { cluster_id: '2', cluster_code: 'ABM-01', track_name: 'Academic Track', cluster_name: 'Accountancy & Business Cluster', description: 'Fundamentals of financial management, entrepreneurship, and economics.' },
  { cluster_id: '3', cluster_code: 'HUMSS-01', track_name: 'Academic Track', cluster_name: 'Humanities & Social Sciences', description: 'Focus on communication, creative writing, world religions, and politics.' },
  { cluster_id: '4', cluster_code: 'TVL-ICT', track_name: 'TVL Track', cluster_name: 'Information & Communication Technology', description: 'Hands-on programming, computer systems servicing, and web design.' }
]

const addressData = ref([])
const provincesList = ref([])
const citiesList = ref([])
const barangaysList = ref([])

onMounted(() => {
  try {
    const flatData = Array.isArray(rawPsgcData) ? rawPsgcData : (rawPsgcData.default || [])
    addressData.value = buildAddressHierarchy(flatData)
    provincesList.value = addressData.value.slice().sort((a, b) => a.name.localeCompare(b.name))
  } catch (e) {
    console.error('PSGC data processing failed:', e)
  }
})

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
  if (prov) {
    citiesList.value = prov.cities.slice().sort((a, b) => a.name.localeCompare(b.name))
  }
}

const onMunicipalityChange = () => {
  barangaysList.value = []
  form.barangay = ''
  const prov = addressData.value.find(p => p.name === form.province)
  if (prov) {
    const mun = prov.cities.find(m => m.name === form.municipality)
    if (mun) {
      barangaysList.value = mun.barangays.slice().sort((a, b) => a.localeCompare(b))
    }
  }
}

const calculateAge = () => {
  if (!form.dob) {
    form.age = ''
    return
  }
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

const scrollToForm = () => {
  nextTick(() => {
    if (formContainer.value) {
      formContainer.value.scrollIntoView({ behavior: 'smooth', block: 'start' })
    }
  })
}

const goToStep = (step) => {
  currentStep.value = step
  scrollToForm()
}

const handleNext = (step) => {
  Object.keys(errors).forEach(k => delete errors[k])
  let isValid = true

  if (step === 1) {
    if (!form.surname || !/^[A-Za-z\s\-']+$/.test(form.surname)) { errors.surname = 'Valid surname is required.'; isValid = false }
    if (!form.given_name || !/^[A-Za-z\s\-']+$/.test(form.given_name)) { errors.given_name = 'Valid given name is required.'; isValid = false }
    if (!form.dob) { errors.dob = 'Date of birth is required.'; isValid = false }
    else if (form.age < 15 || form.age > 85) { errors.dob = 'Age must be between 15 and 85.'; isValid = false }
    if (!form.sex) { errors.sex = 'Please select sex.'; isValid = false }
    if (!form.lrn || !/^\d{10,12}$/.test(form.lrn)) { errors.lrn = 'LRN must be 10 to 12 digits.'; isValid = false }
    if (!form.mobile_number || !/^[0-9+\-\s()]{7,15}$/.test(form.mobile_number)) { errors.mobile_number = 'Valid mobile number is required.'; isValid = false }
    if (!form.email || !/^\S+@\S+\.\S+$/.test(form.email)) { errors.email = 'Valid email address is required.'; isValid = false }
    if (!form.province) { errors.province = 'Province is required.'; isValid = false }
    if (!form.municipality) { errors.municipality = 'City/Municipality is required.'; isValid = false }
    if (!form.barangay) { errors.barangay = 'Barangay is required.'; isValid = false }
    if (!form.street) { errors.street = 'Street address is required.'; isValid = false }
    if (!form.zip_code || !/^\d{4}$/.test(form.zip_code)) { errors.zip_code = '4-digit zip code required.'; isValid = false }
    if (!form.religion) { errors.religion = 'Religion is required.'; isValid = false }
  } else if (step === 2) {
    if (!form.jhs_name) { errors.jhs_name = 'JHS name is required.'; isValid = false }
    if (!form.school_address) { errors.school_address = 'School address is required.'; isValid = false }
    if (!form.school_type) { errors.school_type = 'School type is required.'; isValid = false }
    if (!form.year_graduated || !/^\d{4}$/.test(form.year_graduated)) { errors.year_graduated = 'Valid completion year required.'; isValid = false }
    if (!files.psa) { errors.psa = true; isValid = false }
    if (!files.form137) { errors.form137 = true; isValid = false }
    if (!files.good_moral) { errors.good_moral = true; isValid = false }
    if (!files.picture) { errors.picture = true; isValid = false }
  } else if (step === 3) {
    const hasFather = form.father_name && form.father_occupation && form.father_contact && form.father_education
    const hasMother = form.mother_name && form.mother_occupation && form.mother_contact && form.mother_education
    const hasGuardian = form.guardian_name && form.guardian_occupation && form.guardian_contact && form.guardian_education
    if (!hasFather && !hasMother && !hasGuardian) {
      Swal.fire({ icon: 'warning', title: 'Incomplete Family Info', text: 'Please complete at least one parent or guardian entry.', confirmButtonColor: '#006907' })
      isValid = false
    }
  } else if (step === 4) {
    if (!form.selected_cluster) {
      Swal.fire({ icon: 'warning', title: 'Track Required', text: 'Please select a senior high school cluster.', confirmButtonColor: '#006907' })
      isValid = false
    }
  }

  if (isValid) {
    currentStep.value = step + 1
    scrollToForm()
  } else {
    Swal.fire({ icon: 'error', title: 'Form Incomplete', text: 'Please complete all required fields properly.', confirmButtonColor: '#006907' })
  }
}

const selectedClusterObject = computed(() => sampleClusters.find(c => c.cluster_id === form.selected_cluster))

const getStepBadgeClass = (step) => {
  if (currentStep.value > step) return 'w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm bg-emerald-100 text-emerald-800 border-2 border-emerald-600'
  if (currentStep.value === step) return 'w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm bg-emerald-700 text-white shadow-lg'
  return 'w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm bg-white text-slate-500 border-2 border-slate-300'
}

const submitApplication = () => {
  Swal.fire({
    icon: 'success',
    title: 'Application Submitted!',
    text: 'Your application form has been received successfully.',
    confirmButtonColor: '#006907'
  })
}
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
  0% {
    transform: scale(1);
  }

  50% {
    transform: scale(1.08);
  }

  100% {
    transform: scale(1);
  }
}

@keyframes floatSoft {

  0%,
  100% {
    transform: translateY(0px);
  }

  50% {
    transform: translateY(-3px);
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
</style>