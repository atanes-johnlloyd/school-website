<style scoped>
/* Outlined Word Fill-to-Transparent Stroke Animation */
.outline-transition-text {
  -webkit-text-stroke: 3px #005506;
  background: linear-gradient(to right, #005506 50%, transparent 50%);
  background-size: 200% 100%;
  background-position: 0% 0%;
  -webkit-background-clip: text;
  background-clip: text;
  color: transparent;
  animation: fillToOutline 3.0s cubic-bezier(0.25, 1, 0.5, 1) 0.8s both;
}

/* Keyframe Definitions */
@keyframes fillToOutline {
  0% {
    background-position: 0% 0%;
  }

  100% {
    background-position: -100% 0%;
  }
}

@keyframes maskReveal {
  0% {
    transform: translateY(150%);
    opacity: 0;
  }

  100% {
    transform: translateY(0%);
    opacity: 1;
  }
}

@keyframes lineSlide {
  0% {
    transform: scaleX(0);
    opacity: 0;
  }

  100% {
    transform: scaleX(1);
    opacity: 1;
  }
}

@keyframes titlePop {
  0% {
    transform: scale(0.9) translateY(30px);
    opacity: 0;
  }

  100% {
    transform: scale(1) translateY(0);
    opacity: 1;
  }
}

@keyframes swipeUp {
  0% {
    transform: translateY(80px);
    opacity: 0;
  }

  100% {
    transform: translateY(0);
    opacity: 1;
  }
}

/* Animation Timings */
.animate-mask-reveal {
  animation: maskReveal 1.3s cubic-bezier(0.25, 1, 0.5, 1) both;
}

.animate-subtext-synced-reveal {
  animation: maskReveal 1.6s cubic-bezier(0.25, 1, 0.5, 1) 0.9s both;
}

.animate-line-slide {
  animation: lineSlide 2.0s cubic-bezier(0.16, 1, 0.3, 1) 1.3s both;
}

.animate-title-pop {
  animation: titlePop 0.8s cubic-bezier(0.16, 1, 0.3, 1) both;
}

.animate-swipe-up-bg {
  animation: swipeUp 1.0s cubic-bezier(0.16, 1, 0.3, 1) 0.3s both;
}

/* Staggered Card Swipes */
.animate-card-swipe-1 {
  animation: swipeUp 0.9s cubic-bezier(0.16, 1, 0.3, 1) 0.4s both;
}

.animate-card-swipe-2 {
  animation: swipeUp 0.9s cubic-bezier(0.16, 1, 0.3, 1) 0.5s both;
}

.animate-card-swipe-3 {
  animation: swipeUp 0.9s cubic-bezier(0.16, 1, 0.3, 1) 0.6s both;
}

.animate-card-swipe-4 {
  animation: swipeUp 0.9s cubic-bezier(0.16, 1, 0.3, 1) 0.7s both;
}

@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(30px);
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
    transform: translateY(-4px);
  }
}

@keyframes bounceSubtle {

  0%,
  100% {
    transform: translateY(0);
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

.animate-bounce-subtle {
  animation: bounceSubtle 2s ease-in-out infinite;
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

/* Custom Hide Scrollbar Rules */
.no-scrollbar::-webkit-scrollbar {
  display: none;
}

.no-scrollbar {
  -ms-overflow-style: none;
  scrollbar-width: none;
}
</style>

<template>

  <Head title="Home - Salawag LMS" />

  <!-- Main Background Canvas -->
  <div class="min-h-screen bg-[#E8FFE8] font-['Inter'] text-slate-800 overflow-x-hidden">

    <!-- OVERLAPPED HERO BANNER SECTION -->
    <section
      class="relative w-full min-h-[65vh] md:min-h-[70vh] bg-[#004d08] text-white overflow-hidden shadow-md flex items-center">

      <!-- OVERLAPPED NAVBAR COMPONENT -->
      <Navbar />

      <!-- Background Image Overlay with Matching Admissions Green Tint & Gradient -->
      <div class="absolute inset-0 z-0 pointer-events-none overflow-hidden">
        <img src="https://images.unsplash.com/photo-1522202176988-66273c2fd55f?auto=format&fit=crop&w=1920&q=80"
          alt="Salawag Students Collaborating"
          class="w-full h-full object-cover object-center opacity-25 mix-blend-overlay scale-105 animate-hero-zoom transition-transform duration-1000" />
        <div class="absolute inset-0 bg-gradient-to-r from-[#004d08]/98 via-[#004d08]/88 to-[#003805]/75"></div>
      </div>

      <!-- Animated Gradient Sheen -->
      <div
        class="absolute inset-0 bg-gradient-to-r from-transparent via-white/5 to-transparent animate-sheen pointer-events-none z-0">
      </div>

      <!-- Ambient Light Glow Orbs -->
      <div
        class="absolute -top-24 -left-24 w-96 h-96 bg-emerald-400/15 rounded-full blur-3xl pointer-events-none animate-pulse-slow">
      </div>
      <div
        class="absolute -bottom-24 right-0 w-96 h-96 bg-amber-400/10 rounded-full blur-3xl pointer-events-none animate-pulse-slow delay-1000">
      </div>

      <!-- Main Content Container -->
      <div
        class="relative z-10 w-full max-w-[1500px] mx-auto px-6 sm:px-12 md:px-16 lg:px-24 pt-28 md:pt-36 pb-16 sm:pb-20 space-y-6">

        <div class="space-y-3 max-w-3xl">
          <h1
            class="font-['Anton'] text-5xl sm:text-6xl md:text-7xl lg:text-8xl tracking-tight uppercase leading-[1.02] text-white animate-fade-in-up">
            Learn Boldly.<br />
            <span class="text-amber-400 inline-block animate-float-soft">Grow Together.</span>
          </h1>

          <p
            class="text-sm sm:text-base md:text-lg text-emerald-100/90 font-medium leading-relaxed pt-1 max-w-2xl animate-fade-in-up animation-delay-200">
            Welcome to Salawag Senior High School. We nurture thinkers, digital creators, and trade leaders through
            vibrant DepEd tracks, gamified academic reviewers, and deep community bayanihan.
          </p>
        </div>

        <div class="pt-2 space-y-4 animate-fade-in-up animation-delay-400">
          <div>
            <a href="#tracksRef"
              class="group relative inline-flex items-center gap-2.5 bg-amber-400 hover:bg-amber-300 text-slate-950 font-black text-xs sm:text-sm px-7 py-3.5 rounded-full shadow-lg hover:shadow-amber-400/20 transition-all duration-300 hover:-translate-y-0.5 active:scale-95 cursor-pointer overflow-hidden">
              <span
                class="absolute inset-0 w-1/2 h-full bg-white/30 -skew-x-12 -translate-x-full group-hover:translate-x-[300%] transition-transform duration-1000 ease-out"></span>

              <span
                class="w-6 h-6 rounded-full bg-slate-950 text-amber-400 flex items-center justify-center group-hover:rotate-45 transition-transform duration-300">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9 9 0 100-18 9 9 0 000 18z" />
                  <path stroke-linecap="round" stroke-linejoin="round" d="M12 7v5l3 3" />
                </svg>
              </span>
              <span>Explore Strands & Programs</span>
            </a>
          </div>

          <div class="flex items-center gap-2 text-xs sm:text-sm font-bold text-emerald-200/90 pt-0.5">
            <svg class="w-4 h-4 text-amber-300 animate-bounce-subtle" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd"
                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                clip-rule="evenodd" />
            </svg>
            <span>No Tuition • Free Public Senior High</span>
          </div>
        </div>

      </div>

    </section>

    <!-- 1. Hero Section -->
    <section ref="heroRef"
      class="w-full max-w-[1500px] mx-auto px-6 sm:px-12 md:px-16 lg:px-24 pt-16 md:pt-24 pb-20 min-h-[85vh] flex flex-col justify-center">
      <div class="max-w-5xl space-y-8 text-left">

        <div class="overflow-hidden py-2">
          <h1
            class="font-['Anton'] text-7xl sm:text-8xl md:text-[100px] lg:text-[120px] tracking-wide leading-none uppercase"
            :class="isHeroVisible ? 'animate-mask-reveal' : 'invisible'">
            <span class="text-[#005506]">READY TO </span>
            <span class="inline-block" :class="isHeroVisible ? 'outline-transition-text' : 'invisible'">
              EXCEL?
            </span>
          </h1>
        </div>

        <div class="overflow-hidden py-1">
          <p class="text-2xl md:text-3xl mt-5 lg:text-[30px] text-[#005506] font-normal leading-[1.5] max-w-5xl"
            :class="isHeroVisible ? 'animate-subtext-synced-reveal' : 'invisible'">
            Step into a Senior High community designed for your success. Access<br>
            specialized academic tracks, hands-on learning tools, and dedicated
            mentorship to turn your passion into a future-ready career.
          </p>
        </div>

        <div class="pt-6">
          <div class="h-[2px] bg-slate-400/80 w-full max-w-[620px] origin-left"
            :class="isHeroVisible ? 'animate-line-slide' : 'invisible'"></div>
        </div>

      </div>
    </section>

    <!-- COMMUNITY STORIES SECTION (ANIMATED ON SCROLL) -->
    <section ref="voicesRef" class="w-full text-slate-800 py-16 px-6 sm:px-12 md:px-16 lg:px-24 overflow-hidden">
      <div class="max-w-[1500px] mx-auto space-y-8 transition-all duration-1000"
        :class="isVoicesVisible ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-12'">

        <!-- SECTION HEADER & CONTROLS -->
        <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4">
          <div class="space-y-2 max-w-2xl">
            <span class="text-[11px] sm:text-xs font-bold tracking-widest text-slate-500 uppercase">
              COMMUNITY STORIES • REAL VOICES
            </span>
            <h2
              class="font-['Anton'] text-5xl sm:text-6xl lg:text-7xl text-[#005506] tracking-wide uppercase leading-none">
              Voices of Salawag
            </h2>
            <p class="text-sm sm:text-base text-slate-600 font-normal leading-relaxed">
              Meet the students, master teachers, and alumni driving change in Dasmariñas and beyond.
            </p>
          </div>

          <!-- CAROUSEL ARROW BUTTONS -->
          <div class="flex items-center gap-2 shrink-0">
            <button @click="scroll('left')" aria-label="Scroll Left"
              class="w-11 h-11 rounded-full bg-white hover:bg-slate-100 border border-slate-200 text-slate-800 shadow-sm flex items-center justify-center transition-all duration-300 hover:scale-105 active:scale-95 cursor-pointer">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
              </svg>
            </button>
            <button @click="scroll('right')" aria-label="Scroll Right"
              class="w-11 h-11 rounded-full bg-white hover:bg-slate-100 border border-slate-200 text-slate-800 shadow-sm flex items-center justify-center transition-all duration-300 hover:scale-105 active:scale-95 cursor-pointer">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
              </svg>
            </button>
          </div>
        </div>

        <!-- HORIZONTALLY SCROLLABLE CAROUSEL CONTAINER -->
        <div ref="carouselRef"
          class="w-full max-w-full flex items-stretch gap-8 overflow-x-auto snap-x snap-mandatory scroll-smooth no-scrollbar py-4 -mx-2 px-2">
          <div v-for="voice in voices" :key="voice.id"
            class="snap-start shrink-0 w-[310px] sm:w-[370px] md:w-[420px] bg-white rounded-3xl p-5 sm:p-6 shadow-md border border-slate-200/80 flex flex-col justify-between space-y-5 transition-all duration-500 hover:-translate-y-2 hover:shadow-2xl group">
            <div class="space-y-4">
              <div class="relative w-full aspect-[4/3] rounded-2xl overflow-hidden bg-slate-100 shadow-inner">
                <img :src="voice.image" :alt="voice.name"
                  class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" />
                <span
                  class="absolute top-3 left-3 bg-[#004d08]/90 backdrop-blur-md text-white text-[10px] font-extrabold uppercase px-3 py-1 rounded-full border border-white/20 tracking-wider">
                  {{ voice.badge }}
                </span>
              </div>

              <p class="text-xs sm:text-sm text-slate-700 italic font-medium leading-relaxed line-clamp-4">
                "{{ voice.quote }}"
              </p>
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-end justify-between gap-3">
              <div>
                <h3 class="font-extrabold text-[#005506] text-sm sm:text-base leading-snug">
                  {{ voice.name }}
                </h3>
                <p class="text-[11px] sm:text-xs text-slate-500 font-semibold line-clamp-1">
                  {{ voice.role }}
                </p>
              </div>
              <div
                class="w-8 h-8 rounded-full bg-amber-100/80 text-amber-700 text-xs font-black flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform">
                ”
              </div>
            </div>
          </div>
        </div>

      </div>
    </section>

    <!-- TWO DISTINCT TRACKS SECTION (ANIMATED ON SCROLL) -->
    <section id="tracksRef" ref="tracksRef" class="w-full text-slate-800 py-16 px-6 sm:px-12 md:px-16 lg:px-24">
      <div class="max-w-[1400px] mx-auto space-y-12 transition-all duration-1000"
        :class="isTracksVisible ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-12'">

        <!-- SECTION HEADER -->
        <div class="text-center space-y-3 max-w-3xl mx-auto">
          <div
            class="inline-flex items-center gap-2 bg-[#eaf5ed] border border-[#a2d5ad]/40 text-[#005506] text-xs font-bold px-4 py-1.5 rounded-full animate-bounce-subtle">
            <svg class="w-4 h-4 text-[#005506]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round"
                d="M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.62 48.62 0 0112 20.904a48.62 48.62 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.399 5.84 51.38 51.38 0 00-2.658.814m-15.482 0A50.697 50.697 0 0112 13.489a50.702 50.702 0 017.74-3.342" />
            </svg>
            <span>DepEd Strengthened SHS Framework (S.Y. 2026–2027)</span>
          </div>

          <h2 class="text-4xl sm:text-5xl lg:text-6xl font-bold text-[#005506] tracking-tight leading-tight">
            Two Distinct Tracks. Limitless Horizons.
          </h2>

          <p class="text-sm sm:text-base md:text-lg text-slate-600 font-normal leading-relaxed">
            Salawag Senior High School streamlines secondary education into two powerful tracks designed for direct
            university enrollment or immediate certified technical employment.
          </p>
        </div>

        <!-- TRACK CARDS GRID -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-stretch">

          <!-- TRACK 01: ACADEMIC TRACK -->
          <div
            class="bg-white rounded-3xl p-6 sm:p-8 md:p-10 shadow-sm border border-slate-200/80 flex flex-col justify-between space-y-8 relative overflow-hidden transition-all duration-500 hover:shadow-xl hover:-translate-y-1">
            <div class="absolute top-0 left-0 right-0 h-2 bg-[#005506]"></div>

            <div class="space-y-6 pt-2">
              <div class="flex items-center justify-between gap-4">
                <span
                  class="inline-flex items-center gap-1.5 bg-[#eaf5ed] text-[#005506] text-xs font-bold px-3 py-1 rounded-full">
                  <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l9-5-9-5-9 5 9 5z" />
                  </svg>
                  <span>Higher Education Ready</span>
                </span>
                <span class="text-xs sm:text-sm font-bold tracking-widest text-slate-400 uppercase">
                  TRACK 01
                </span>
              </div>

              <div class="space-y-3">
                <h3 class="text-3xl sm:text-4xl font-bold text-[#005506] leading-tight">
                  Academic Track
                </h3>
                <p class="text-xs sm:text-sm text-slate-600 leading-relaxed font-normal">
                  Designed for college-bound scholars aiming for state universities, private colleges, and international
                  scholarship grants through rigorous research, analytics, and discourse.
                </p>
              </div>

              <div class="space-y-3 pt-2">
                <div
                  class="bg-[#f5f4ed] rounded-2xl p-3.5 flex items-center justify-between gap-3 hover:bg-[#edece3] transition-colors">
                  <div class="flex items-center gap-3">
                    <span
                      class="w-10 h-10 rounded-xl bg-[#005506] text-white text-xs font-bold flex items-center justify-center shrink-0">STEM</span>
                    <span class="font-bold text-[#005506] text-sm sm:text-base">Science, Technology, Eng. & Math</span>
                  </div>
                  <span class="text-xs text-slate-500 font-medium shrink-0">Robotics • Pre-Med</span>
                </div>

                <div
                  class="bg-[#f5f4ed] rounded-2xl p-3.5 flex items-center justify-between gap-3 hover:bg-[#edece3] transition-colors">
                  <div class="flex items-center gap-3">
                    <span
                      class="w-10 h-10 rounded-xl bg-[#635515] text-white text-xs font-bold flex items-center justify-center shrink-0">HUMSS</span>
                    <span class="font-bold text-[#005506] text-sm sm:text-base">Humanities & Social Sciences</span>
                  </div>
                  <span class="text-xs text-slate-500 font-medium shrink-0">Pre-Law • Comm</span>
                </div>

                <div
                  class="bg-[#f5f4ed] rounded-2xl p-3.5 flex items-center justify-between gap-3 hover:bg-[#edece3] transition-colors">
                  <div class="flex items-center gap-3">
                    <span
                      class="w-10 h-10 rounded-xl bg-[#1d1d1d] text-white text-xs font-bold flex items-center justify-center shrink-0">ABM</span>
                    <span class="font-bold text-[#005506] text-sm sm:text-base">Accountancy, Business & Entrep</span>
                  </div>
                  <span class="text-xs text-slate-500 font-medium shrink-0">Finance • Startups</span>
                </div>

                <div
                  class="bg-[#f5f4ed] rounded-2xl p-3.5 flex items-center justify-between gap-3 hover:bg-[#edece3] transition-colors">
                  <div class="flex items-center gap-3">
                    <span
                      class="w-10 h-10 rounded-xl bg-[#61852c] text-white text-xs font-bold flex items-center justify-center shrink-0">GAS</span>
                    <span class="font-bold text-[#005506] text-sm sm:text-base">General Academic & Health Fields</span>
                  </div>
                  <span class="text-xs text-slate-500 font-medium shrink-0">Education • Sports</span>
                </div>
              </div>
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-between gap-4 text-xs sm:text-sm">
              <div class="flex items-center gap-2 text-slate-600 font-medium">
                <svg class="w-4 h-4 text-[#005506]" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                  stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round"
                    d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751A11.959 11.959 0 0112 2.714z" />
                </svg>
                <span>UP, DLSU & Cavite State Univ. Aligned</span>
              </div>
              <a href="/academics#pathways" class="font-bold text-[#005506] hover:underline flex items-center gap-1">
                <span>View Syllabi</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                </svg>
              </a>
            </div>
          </div>

          <!-- TRACK 02: TECHNICAL-PROFESSIONAL -->
          <div
            class="bg-white rounded-3xl p-6 sm:p-8 md:p-10 shadow-sm border border-slate-200/80 flex flex-col justify-between space-y-8 relative overflow-hidden transition-all duration-500 hover:shadow-xl hover:-translate-y-1">
            <div class="absolute top-0 left-0 right-0 h-2 bg-[#e3ad1b]"></div>

            <div class="space-y-6 pt-2">
              <div class="flex items-center justify-between gap-4">
                <span
                  class="inline-flex items-center gap-1.5 bg-[#fef8e7] text-[#b88600] text-xs font-bold px-3 py-1 rounded-full">
                  <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                      d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z" />
                  </svg>
                  <span>Direct Employment & TESDA NC II</span>
                </span>
                <span class="text-xs sm:text-sm font-bold tracking-widest text-slate-400 uppercase">
                  TRACK 02
                </span>
              </div>

              <div class="space-y-3">
                <h3 class="text-3xl sm:text-4xl font-bold text-[#005506] leading-tight">
                  Technical–Professional (TechPro)
                </h3>
                <p class="text-xs sm:text-sm text-slate-600 leading-relaxed font-normal">
                  Hands-on mastery, 320-hour industry internships, and dual TESDA certifications for immediate workplace
                  readiness or advanced technical college degrees.
                </p>
              </div>

              <div class="space-y-3 pt-2">
                <div
                  class="bg-[#f5f4ed] rounded-2xl p-3.5 flex items-center justify-between gap-3 hover:bg-[#edece3] transition-colors">
                  <div class="flex items-center gap-3">
                    <span
                      class="w-10 h-10 rounded-xl bg-[#005506] text-white text-xs font-bold flex items-center justify-center shrink-0">ICT</span>
                    <span class="font-bold text-[#005506] text-sm sm:text-base">Software Dev, Animation & CSS</span>
                  </div>
                  <span class="text-xs text-slate-500 font-medium shrink-0">NC II Certified</span>
                </div>

                <div
                  class="bg-[#f5f4ed] rounded-2xl p-3.5 flex items-center justify-between gap-3 hover:bg-[#edece3] transition-colors">
                  <div class="flex items-center gap-3">
                    <span
                      class="w-10 h-10 rounded-xl bg-[#714d11] text-white text-xs font-bold flex items-center justify-center shrink-0">HE</span>
                    <span class="font-bold text-[#005506] text-sm sm:text-base">Culinary Arts & Hotel Hospitality</span>
                  </div>
                  <span class="text-xs text-slate-500 font-medium shrink-0">Cookery NC II</span>
                </div>

                <div
                  class="bg-[#f5f4ed] rounded-2xl p-3.5 flex items-center justify-between gap-3 hover:bg-[#edece3] transition-colors">
                  <div class="flex items-center gap-3">
                    <span
                      class="w-10 h-10 rounded-xl bg-[#1d1d1d] text-white text-xs font-bold flex items-center justify-center shrink-0">IA</span>
                    <span class="font-bold text-[#005506] text-sm sm:text-base">Industrial Arts: Electrical &
                      Mechatronics</span>
                  </div>
                  <span class="text-xs text-slate-500 font-medium shrink-0">EIM NC II</span>
                </div>

                <div
                  class="bg-[#f5f4ed] rounded-2xl p-3.5 flex items-center justify-between gap-3 hover:bg-[#edece3] transition-colors">
                  <div class="flex items-center gap-3">
                    <span
                      class="w-10 h-10 rounded-xl bg-[#d95214] text-white text-xs font-bold flex items-center justify-center shrink-0">AFA</span>
                    <span class="font-bold text-[#005506] text-sm sm:text-base">Agri–Fishery Urban Smart Farming</span>
                  </div>
                  <span class="text-xs text-slate-500 font-medium shrink-0">Hydroponics</span>
                </div>
              </div>
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-between gap-4 text-xs sm:text-sm">
              <div class="flex items-center gap-2 text-slate-600 font-medium">
                <svg class="w-4 h-4 text-[#e3ad1b]" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                  stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round"
                    d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751A11.959 11.959 0 0112 2.714z" />
                </svg>
                <span>100% Salawag On-Site Testing Lab</span>
              </div>
              <a href="/academics#pathways" class="font-bold text-[#b88600] hover:underline flex items-center gap-1">
                <span>View Certifications</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                </svg>
              </a>
            </div>
          </div>

        </div>

      </div>
    </section>

    <!-- 3. Featured News Section -->
    <section ref="newsRef" class="w-full px-4 sm:px-8 md:px-12 lg:px-16 py-20 text-center relative">
      <div class="overflow-hidden mb-12">
        <h2 class="font-['Anton'] text-5xl sm:text-6xl lg:text-7xl text-[#005506] tracking-wide uppercase"
          :class="isNewsVisible ? 'animate-title-pop' : 'invisible'">
          FEATURED NEWS
        </h2>
      </div>

      <div class="w-full p-6 sm:p-10 lg:p-12 bg-[#f2f6f2] rounded-3xl shadow-sm">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 md:gap-10 lg:gap-12 text-left">
          <article v-for="(article, index) in newsArticles" :key="article.id"
            class="group cursor-pointer flex flex-col justify-between"
            :class="isNewsVisible ? `animate-card-swipe-${(index % 4) + 1}` : 'invisible'">
            <div>
              <div class="overflow-hidden rounded-2xl mb-5 bg-slate-200 aspect-[16/10] shadow-md">
                <img :src="article.image" :alt="article.title"
                  class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" />
              </div>

              <h3
                class="font-['Anton'] text-2xl sm:text-3xl text-[#005506] leading-snug uppercase tracking-wide group-hover:text-emerald-700 transition-colors mb-3">
                {{ article.title }}
              </h3>

              <p class="text-sm sm:text-base text-slate-600 font-normal leading-relaxed">
                {{ article.excerpt }}
              </p>
            </div>
          </article>
        </div>
      </div>
    </section>

    <!-- CONNECTED ENROLLMENT BANNER (ANIMATED ON SCROLL) -->
    <section ref="ctaRef"
      class="w-full bg-[#005506] text-white pt-16 sm:pt-20 md:pt-24 pb-16 sm:pb-20 md:pb-24 px-6 sm:px-12 md:px-16 lg:px-24 relative overflow-hidden shadow-xl m-0">
      <div
        class="absolute -top-24 -left-24 w-80 h-80 sm:w-96 sm:h-96 md:w-[450px] md:h-[450px] bg-white/5 rounded-full pointer-events-none animate-pulse-slow">
      </div>
      <div
        class="absolute -bottom-32 -right-32 w-96 h-96 sm:w-[480px] sm:h-[480px] md:w-[600px] md:h-[600px] bg-white/5 rounded-full pointer-events-none animate-pulse-slow delay-1000">
      </div>

      <div class="relative z-10 max-w-4xl mx-auto text-center space-y-6 sm:space-y-8 transition-all duration-1000"
        :class="isCtaVisible ? 'opacity-100 scale-100' : 'opacity-0 scale-95'">
        <div>
          <span
            class="inline-flex items-center gap-2 bg-amber-400 text-slate-950 text-xs sm:text-sm font-bold px-4 sm:px-5 py-1.5 sm:py-2 rounded-full shadow-sm animate-bounce-subtle">
            <svg class="w-4 h-4 text-slate-950" fill="none" stroke="currentColor" viewBox="0 0 24 24"
              stroke-width="2.5">
              <path stroke-linecap="round" stroke-linejoin="round"
                d="M10.34 15.84c-.688-.06-1.38-.09-2.072-.09-1.96 0-3.86.25-5.688.72 1.307-3.8 4.316-6.81 8.116-8.117a18.258 18.258 0 01.72-5.688c.69.03 1.382.06 2.072.09m6.812 6.812c.06.69.09 1.382.09 2.072 0 1.96-.25 3.86-.72 5.688-3.8-1.307-6.81-4.316-8.117-8.116a18.258 18.258 0 01-5.688-.72c.03-.69.06-1.382.09-2.072" />
            </svg>
            <span>Admissions Open for School Year 2026–2027</span>
          </span>
        </div>

        <h2 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-bold text-white tracking-tight leading-tight">
          Your Future Starts at Salawag.
        </h2>

        <p class="text-sm sm:text-base md:text-lg text-emerald-100/90 font-normal leading-relaxed max-w-2xl mx-auto">
          Join over 2,400 ambitious high school learners in Dasmariñas, Cavite. Secure your spot in either the Academic
          or TechPro Track today — 100% tuition-free.
        </p>

        <div class="pt-2">
          <a href="/admission#view-application-flow"
            class="group relative inline-flex items-center justify-center gap-2.5 bg-amber-400 hover:bg-amber-300 text-slate-950 font-bold text-sm sm:text-base px-8 sm:px-10 py-4 rounded-full shadow-xl transition-all duration-300 hover:-translate-y-1 active:scale-95 cursor-pointer overflow-hidden">
            <span
              class="absolute inset-0 w-1/2 h-full bg-white/30 -skew-x-12 -translate-x-full group-hover:translate-x-[300%] transition-transform duration-1000 ease-out"></span>
            <span>Start Online Enrollment Now</span>
            <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor"
              viewBox="0 0 24 24" stroke-width="2.5">
              <path stroke-linecap="round" stroke-linejoin="round"
                d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
            </svg>
          </a>
        </div>

        <div
          class="flex flex-wrap items-center justify-center gap-6 sm:gap-8 pt-4 text-xs sm:text-sm font-medium text-emerald-100/90">
          <div class="flex items-center gap-2">
            <span
              class="w-4 h-4 rounded-full border-2 border-amber-400 text-amber-400 flex items-center justify-center text-[10px] font-bold">✓</span>
            <span>No Entrance Exam Fee</span>
          </div>
          <div class="flex items-center gap-2">
            <span
              class="w-4 h-4 rounded-full border-2 border-amber-400 text-amber-400 flex items-center justify-center text-[10px] font-bold">✓</span>
            <span>Form 137 / 138 Online Submissions</span>
          </div>
        </div>

      </div>
    </section>

    <!-- FOOTER ATTACHED DIRECTLY -->
    <Footer class="m-0 p-0 block" />

  </div>
</template>

<script setup>
import academicFairImage from '../../../../assets/img/local/news-academic-fair.jpg'
import enrollmentImage from '../../../../assets/img/local/news-enrollment.jpg'
import sportsClubImage from '../../../../assets/img/local/news-sports-club.jpg'
import stemLabImage from '../../../../assets/img/local/news-stem-lab.jpg'
import studentCouncilImage from '../../../../assets/img/local/news-student-council.jpg'
import workImmersionImage from '../../../../assets/img/local/news-work-immersion.jpg'
import Navbar from '@/Components/Navbar.vue'
import Footer from '@/Components/Footer.vue'
import { Head } from '@inertiajs/vue3'
import { ref, onMounted, onUnmounted } from 'vue'

// Section Visibility Refs for Scroll Triggering
const heroRef = ref(null)
const voicesRef = ref(null)
const tracksRef = ref(null)
const newsRef = ref(null)
const ctaRef = ref(null)
const carouselRef = ref(null)

const isHeroVisible = ref(false)
const isVoicesVisible = ref(false)
const isTracksVisible = ref(false)
const isNewsVisible = ref(false)
const isCtaVisible = ref(false)

// Smooth Horizontal Carousel Scroll Method
const scroll = (direction) => {
  if (!carouselRef.value) return
  const scrollAmount = direction === 'left' ? -420 : 420
  carouselRef.value.scrollBy({ left: scrollAmount, behavior: 'smooth' })
}

let observer = null

onMounted(() => {
  observer = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        if (entry.target === heroRef.value) {
          isHeroVisible.value = entry.isIntersecting
        } else if (entry.target === voicesRef.value) {
          isVoicesVisible.value = entry.isIntersecting
        } else if (entry.target === tracksRef.value) {
          isTracksVisible.value = entry.isIntersecting
        } else if (entry.target === newsRef.value) {
          isNewsVisible.value = entry.isIntersecting
        } else if (entry.target === ctaRef.value) {
          isCtaVisible.value = entry.isIntersecting
        }
      })
    },
    { threshold: 0.12 }
  )

  if (heroRef.value) observer.observe(heroRef.value)
  if (voicesRef.value) observer.observe(voicesRef.value)
  if (tracksRef.value) observer.observe(tracksRef.value)
  if (newsRef.value) observer.observe(newsRef.value)
  if (ctaRef.value) observer.observe(ctaRef.value)
})

onUnmounted(() => {
  if (observer) observer.disconnect()
})

// Featured News Data
const newsArticles = [
  {
    id: 1,
    title: 'SENIOR HIGH ENROLLMENT OPEN',
    excerpt: 'Secure your future. Complete your application now for the upcoming academic year.',
    image: enrollmentImage,
  },
  {
    id: 2,
    title: 'STRAND PATHWAYS & ACADEMIC FAIR',
    excerpt: 'Explore our specialized strands (STEM, ABM, HUMSS, TVL) and discover your academic pathway.',
    image: academicFairImage,
  },
  {
    id: 3,
    title: 'CAMPUS SPORTS FEST & CLUB DAY',
    excerpt: 'Get involved, showcase your talent, and celebrate school spirit with diverse sports and activities.',
    image: sportsClubImage,
  },
  {
    id: 4,
    title: 'NEW ADVANCED STEM LAB INAUGURATED',
    excerpt: 'Experience hands-on learning with state-of-the-art technology and tools in our upgraded facilities.',
    image: stemLabImage,
  },
  {
    id: 5,
    title: 'STUDENT COUNCIL ELECTIONS 2026-2027',
    excerpt: 'Vote for your representatives and shape the future of our student body. Learn about the candidates.',
    image: studentCouncilImage,
  },
  {
    id: 6,
    title: 'WORK IMMERSION & INDUSTRY PARTNERSHIPS',
    excerpt: 'Prepare for the future. Our Grade 12 students gain real-world experience and build career skills.',
    image: workImmersionImage,
  },
]

// 6 VOICES STORIES DATA
const voices = [
  {
    id: 1,
    name: 'Marjorie Anne Reyes',
    role: 'Robotics Club Captain • DOST Scholar',
    badge: 'STEM • CLASS OF 2025',
    quote: 'Salawag gave me access to high-spec robotics equipment I thought only private academies had. My team represented Calabarzon at the National Science Quest!',
    image: 'https://images.unsplash.com/photo-1581092160607-ee22621dd758?auto=format&fit=crop&w=800&q=80'
  },
  {
    id: 2,
    name: 'Joshua Kyle Mendoza',
    role: 'Philippine Skills Competition Finalist',
    badge: 'TVL-ICT • COMPUTER SYSTEMS',
    quote: 'Before graduation, I already earned my TESDA National Certificate II in Computer Systems Servicing. The hands-on network lab prepared me directly for industry immersion.',
    image: 'https://images.unsplash.com/photo-1531482615713-2afd69097998?auto=format&fit=crop&w=800&q=80'
  },
  {
    id: 3,
    name: 'Kassandra Mae Bato',
    role: 'Cavite Youth Parliament Delegate',
    badge: 'HUMSS • SOCIAL SCIENCES',
    quote: 'Our teachers teach us how to think, not what to think. Through community immersion in Salawag and parliamentary debates, my passion for public service was sparked.',
    image: 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?auto=format&fit=crop&w=800&q=80'
  },
  {
    id: 4,
    name: 'Christian Joseph Aranda',
    role: 'Young Entrepreneurs League Lead',
    badge: 'ABM • BUSINESS MANAGEMENT',
    quote: 'Running our campus enterprise trade fair taught me real financial literacy, supply chain logic, and pitch presentations to actual Cavite business mentors.',
    image: 'https://images.unsplash.com/photo-1519085360753-af0119f7cbe7?auto=format&fit=crop&w=800&q=80'
  },
  {
    id: 5,
    name: 'Engr. Dalisay Santos',
    role: 'Master Teacher II • Innovation Hub Lead',
    badge: 'FACULTY • RESEARCH MENTOR',
    quote: 'Watching public school students design patents and automate local agricultural tools reminds me every day why teaching in Salawag is my lifetime calling.',
    image: 'https://images.unsplash.com/photo-1573497019940-1c28c88b4f3e?auto=format&fit=crop&w=800&q=80'
  },
  {
    id: 6,
    name: 'Lianne Beatrice Dizon',
    role: 'Software Engineer • Alumna Class of 2021',
    badge: 'ALUMNI • B.S. COMPUTER SCIENCE',
    quote: 'The rigorous STEM curriculum at Salawag Senior High built the exact problem-solving mindset that carried me straight to a tech scholarship and corporate success.',
    image: 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=800&q=80'
  }
]
</script>