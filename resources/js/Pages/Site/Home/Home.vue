<style scoped>
/* ---------- unchanged animation styles (kept as-is) ---------- */
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
@keyframes fillToOutline { 0% { background-position: 0% 0%; } 100% { background-position: -100% 0%; } }
@keyframes maskReveal { 0% { transform: translateY(150%); opacity: 0; } 100% { transform: translateY(0%); opacity: 1; } }
@keyframes lineSlide { 0% { transform: scaleX(0); opacity: 0; } 100% { transform: scaleX(1); opacity: 1; } }
@keyframes titlePop { 0% { transform: scale(0.9) translateY(30px); opacity: 0; } 100% { transform: scale(1) translateY(0); opacity: 1; } }
@keyframes swipeUp { 0% { transform: translateY(80px); opacity: 0; } 100% { transform: translateY(0); opacity: 1; } }
.animate-mask-reveal { animation: maskReveal 1.3s cubic-bezier(0.25, 1, 0.5, 1) both; }
.animate-subtext-synced-reveal { animation: maskReveal 1.6s cubic-bezier(0.25, 1, 0.5, 1) 0.9s both; }
.animate-line-slide { animation: lineSlide 2.0s cubic-bezier(0.16, 1, 0.3, 1) 1.3s both; }
.animate-title-pop { animation: titlePop 0.8s cubic-bezier(0.16, 1, 0.3, 1) both; }
.animate-swipe-up-bg { animation: swipeUp 1.0s cubic-bezier(0.16, 1, 0.3, 1) 0.3s both; }
.animate-card-swipe-1 { animation: swipeUp 0.9s cubic-bezier(0.16, 1, 0.3, 1) 0.4s both; }
.animate-card-swipe-2 { animation: swipeUp 0.9s cubic-bezier(0.16, 1, 0.3, 1) 0.5s both; }
.animate-card-swipe-3 { animation: swipeUp 0.9s cubic-bezier(0.16, 1, 0.3, 1) 0.6s both; }
.animate-card-swipe-4 { animation: swipeUp 0.9s cubic-bezier(0.16, 1, 0.3, 1) 0.7s both; }
@keyframes fadeInUp { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
@keyframes heroZoom { 0%, 100% { transform: scale(1); } 50% { transform: scale(1.08); } }
@keyframes floatSoft { 0%, 100% { transform: translateY(0px); } 50% { transform: translateY(-4px); } }
@keyframes bounceSubtle { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-3px); } }
@keyframes pulseSlow { 0%, 100% { opacity: 0.3; } 50% { opacity: 0.7; } }
@keyframes sheenMove { 0% { transform: translateX(-100%); } 100% { transform: translateX(200%); } }
.animate-fade-in-up { animation: fadeInUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
.animate-hero-zoom { animation: heroZoom 20s ease-in-out infinite; }
.animate-float-soft { animation: floatSoft 4s ease-in-out infinite; }
.animate-bounce-subtle { animation: bounceSubtle 2s ease-in-out infinite; }
.animate-pulse-slow { animation: pulseSlow 6s ease-in-out infinite; }
.animate-sheen { animation: sheenMove 5s ease-in-out infinite; }
.animation-delay-200 { animation-delay: 0.2s; animation-fill-mode: backwards; }
.animation-delay-400 { animation-delay: 0.4s; animation-fill-mode: backwards; }
.no-scrollbar::-webkit-scrollbar { display: none; }
.no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>

<template>
  <Head :title="`Home - ${school.name}`" />

  <div class="min-h-screen bg-[#E8FFE8] font-['Inter'] text-slate-800 overflow-x-hidden">

    <!-- HERO BANNER -->
    <section class="relative w-full min-h-[65vh] md:min-h-[70vh] bg-[#004d08] text-white overflow-hidden shadow-md flex items-center">
      <Navbar />

      <div class="absolute inset-0 z-0 pointer-events-none overflow-hidden">
        <img src="https://images.unsplash.com/photo-1522202176988-66273c2fd55f?auto=format&fit=crop&w=1920&q=80"
          alt="Salawag Students Collaborating"
          class="w-full h-full object-cover object-center opacity-25 mix-blend-overlay scale-105 animate-hero-zoom transition-transform duration-1000" />
        <div class="absolute inset-0 bg-gradient-to-r from-[#004d08]/98 via-[#004d08]/88 to-[#003805]/75"></div>
      </div>

      <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/5 to-transparent animate-sheen pointer-events-none z-0"></div>
      <div class="absolute -top-24 -left-24 w-96 h-96 bg-emerald-400/15 rounded-full blur-3xl pointer-events-none animate-pulse-slow"></div>
      <div class="absolute -bottom-24 right-0 w-96 h-96 bg-amber-400/10 rounded-full blur-3xl pointer-events-none animate-pulse-slow delay-1000"></div>

      <div class="relative z-10 w-full max-w-[1500px] mx-auto px-6 sm:px-12 md:px-16 lg:px-24 pt-28 md:pt-36 pb-16 sm:pb-20 space-y-6">
        <div class="space-y-3 max-w-3xl">
          <h1 class="font-['Anton'] text-5xl sm:text-6xl md:text-7xl lg:text-8xl tracking-tight uppercase leading-[1.02] text-white animate-fade-in-up">
            Learn Boldly.<br />
            <span class="text-amber-400 inline-block animate-float-soft">Grow Together.</span>
          </h1>

          <p class="text-sm sm:text-base md:text-lg text-emerald-100/90 font-medium leading-relaxed pt-1 max-w-2xl animate-fade-in-up animation-delay-200">
            Welcome to {{ school.name }}. We nurture thinkers, digital creators, and trade leaders through
            vibrant DepEd tracks, gamified academic reviewers, and deep community bayanihan.
          </p>
        </div>

        <div class="pt-2 space-y-4 animate-fade-in-up animation-delay-400">
          <div>
            <a href="#tracksRef"
              class="group relative inline-flex items-center gap-2.5 bg-amber-400 hover:bg-amber-300 text-slate-950 font-black text-xs sm:text-sm px-7 py-3.5 rounded-full shadow-lg hover:shadow-amber-400/20 transition-all duration-300 hover:-translate-y-0.5 active:scale-95 cursor-pointer overflow-hidden">
              <span class="absolute inset-0 w-1/2 h-full bg-white/30 -skew-x-12 -translate-x-full group-hover:translate-x-[300%] transition-transform duration-1000 ease-out"></span>
              <span class="w-6 h-6 rounded-full bg-slate-950 text-amber-400 flex items-center justify-center group-hover:rotate-45 transition-transform duration-300">
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

    <!-- HERO SECTION -->
    <section ref="heroRef"
      class="w-full max-w-[1500px] mx-auto px-6 sm:px-12 md:px-16 lg:px-24 pt-16 md:pt-24 pb-20 min-h-[85vh] flex flex-col justify-center">
      <div class="max-w-5xl space-y-8 text-left">
        <div class="overflow-hidden py-2">
          <h1 class="font-['Anton'] text-7xl sm:text-8xl md:text-[100px] lg:text-[120px] tracking-wide leading-none uppercase"
            :class="isHeroVisible ? 'animate-mask-reveal' : 'invisible'">
            <span class="text-[#005506]">READY TO </span>
            <span class="inline-block" :class="isHeroVisible ? 'outline-transition-text' : 'invisible'">EXCEL?</span>
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

    <!-- VOICES OF SALAWAG -->
    <section ref="voicesRef" class="w-full text-slate-800 py-16 px-6 sm:px-12 md:px-16 lg:px-24 overflow-hidden">
      <div class="max-w-[1500px] mx-auto space-y-8 transition-all duration-1000"
        :class="isVoicesVisible ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-12'">

        <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4">
          <div class="space-y-2 max-w-2xl">
            <span class="text-[11px] sm:text-xs font-bold tracking-widest text-slate-500 uppercase">COMMUNITY STORIES • REAL VOICES</span>
            <h2 class="font-['Anton'] text-5xl sm:text-6xl lg:text-7xl text-[#005506] tracking-wide uppercase leading-none">
              Voices of Salawag
            </h2>
            <p class="text-sm sm:text-base text-slate-600 font-normal leading-relaxed">
              Meet the students, master teachers, and alumni driving change in Dasmariñas and beyond.
            </p>
          </div>

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

        <div ref="carouselRef"
          class="w-full max-w-full flex items-stretch gap-8 overflow-x-auto snap-x snap-mandatory scroll-smooth no-scrollbar py-4 -mx-2 px-2">
          <div v-for="voice in voices" :key="voice.id"
            class="snap-start shrink-0 w-[310px] sm:w-[370px] md:w-[420px] bg-white rounded-3xl p-5 sm:p-6 shadow-md border border-slate-200/80 flex flex-col justify-between space-y-5 transition-all duration-500 hover:-translate-y-2 hover:shadow-2xl group">
            <div class="space-y-4">
              <div class="relative w-full aspect-[4/3] rounded-2xl overflow-hidden bg-slate-100 shadow-inner">
                <img :src="voice.image" :alt="voice.name"
                  class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" />
                <span class="absolute top-3 left-3 bg-[#004d08]/90 backdrop-blur-md text-white text-[10px] font-extrabold uppercase px-3 py-1 rounded-full border border-white/20 tracking-wider">
                  {{ voice.badge }}
                </span>
              </div>
              <p class="text-xs sm:text-sm text-slate-700 italic font-medium leading-relaxed line-clamp-4">"{{ voice.quote }}"</p>
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-end justify-between gap-3">
              <div>
                <h3 class="font-extrabold text-[#005506] text-sm sm:text-base leading-snug">{{ voice.name }}</h3>
                <p class="text-[11px] sm:text-xs text-slate-500 font-semibold line-clamp-1">{{ voice.role }}</p>
              </div>
              <div class="w-8 h-8 rounded-full bg-amber-100/80 text-amber-700 text-xs font-black flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform">”</div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- TWO TRACKS -->
    <section id="tracksRef" ref="tracksRef" class="w-full text-slate-800 py-16 px-6 sm:px-12 md:px-16 lg:px-24">
      <div class="max-w-[1400px] mx-auto space-y-12 transition-all duration-1000"
        :class="isTracksVisible ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-12'">

        <div class="text-center space-y-3 max-w-3xl mx-auto">
          <div class="inline-flex items-center gap-2 bg-[#eaf5ed] border border-[#a2d5ad]/40 text-[#005506] text-xs font-bold px-4 py-1.5 rounded-full animate-bounce-subtle">
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
            {{ school.name }} streamlines secondary education into two powerful tracks designed for direct
            university enrollment or immediate certified technical employment.
          </p>
        </div>

        <!-- TRACK CARDS: dynamically rendered from props.tracks -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-stretch">
          <div v-for="(track, idx) in tracks" :key="track.id"
            class="bg-white rounded-3xl shadow-sm border border-slate-200/80 flex flex-col justify-between relative overflow-hidden transition-all duration-500 hover:shadow-xl hover:-translate-y-1">
            <div class="absolute top-0 left-0 right-0 h-2 z-10" :class="idx === 0 ? 'bg-[#005506]' : 'bg-[#e3ad1b]'"></div>

            <!-- Track image header — falls back to brand gradient if no image uploaded -->
            <div class="relative w-full h-40 sm:h-48 overflow-hidden">
              <img v-if="track.image_url" :src="track.image_url" :alt="track.name"
                class="w-full h-full object-cover" />
              <div v-else
                class="w-full h-full"
                :class="idx === 0
                  ? 'bg-gradient-to-br from-[#005506] via-[#004d08] to-[#003805]'
                  : 'bg-gradient-to-br from-[#e3ad1b] via-amber-500 to-amber-600'">
              </div>
              <div class="absolute inset-0 bg-gradient-to-t from-black/40 via-transparent to-transparent"></div>
            </div>

            <div class="p-6 sm:p-8 md:p-10 flex flex-col justify-between flex-1 space-y-8">
              <div class="space-y-6">
                <div class="flex items-center justify-between gap-4">
                  <span class="inline-flex items-center gap-1.5 bg-[#eaf5ed] text-[#005506] text-xs font-bold px-3 py-1 rounded-full">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l9-5-9-5-9 5 9 5z" />
                    </svg>
                    <span>{{ idx === 0 ? 'Higher Education Ready' : 'Direct Employment & TESDA NC II' }}</span>
                  </span>
                  <span class="text-xs sm:text-sm font-bold tracking-widest text-slate-400 uppercase">
                    TRACK 0{{ idx + 1 }}
                  </span>
                </div>

                <div class="space-y-3">
                  <h3 class="text-3xl sm:text-4xl font-bold text-[#005506] leading-tight">{{ track.name }}</h3>
                  <p class="text-xs sm:text-sm text-slate-600 leading-relaxed font-normal">{{ track.description }}</p>
                </div>

                <div class="space-y-3 pt-2">
                  <div v-for="strand in track.strands" :key="strand.id"
                    class="bg-[#f5f4ed] rounded-2xl p-3.5 flex items-center justify-between gap-3 hover:bg-[#edece3] transition-colors">
                    <div class="flex items-center gap-3 min-w-0">
                      <span class="w-10 h-10 rounded-xl bg-[#005506] text-white flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                          <path stroke-linecap="round" stroke-linejoin="round" :d="strandIconPath(strand.code)" />
                        </svg>
                      </span>
                      <span class="font-bold text-[#005506] text-sm sm:text-base truncate">{{ strand.name }}</span>
                    </div>
                  </div>
                </div>
              </div>

              <div class="pt-4 border-t border-slate-100 flex items-center justify-between gap-4 text-xs sm:text-sm">
                <div class="flex items-center gap-2 text-slate-600 font-medium">
                  <svg class="w-4 h-4 text-[#005506]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                      d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751A11.959 11.959 0 0112 2.714z" />
                  </svg>
                  <span>{{ idx === 0 ? 'UP, DLSU & Cavite State Univ. Aligned' : '100% Salawag On-Site Testing Lab' }}</span>
                </div>
                <Link :href="route('site.academics', { track: track.code }) + '#pathways'"
                  class="font-bold text-[#005506] hover:underline flex items-center gap-1">
                  <span>View Syllabi</span>
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                  </svg>
                </Link>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- FEATURED NEWS (dynamic) -->
    <section ref="newsRef" class="w-full px-4 sm:px-8 md:px-12 lg:px-16 py-20 text-center relative">
      <div class="overflow-hidden mb-12">
        <h2 class="font-['Anton'] text-5xl sm:text-6xl lg:text-7xl text-[#005506] tracking-wide uppercase"
          :class="isNewsVisible ? 'animate-title-pop' : 'invisible'">
          FEATURED NEWS
        </h2>
      </div>

      <div class="w-full p-6 sm:p-10 lg:p-12 bg-[#f2f6f2] rounded-3xl shadow-sm">
        <div v-if="latestNews.length" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 md:gap-10 lg:gap-12 text-left">
          <article v-for="(article, index) in latestNews" :key="article.id"
            class="group cursor-pointer flex flex-col justify-between"
            :class="isNewsVisible ? `animate-card-swipe-${(index % 4) + 1}` : 'invisible'">
            <div>
              <div class="overflow-hidden rounded-2xl mb-5 bg-slate-200 aspect-[16/10] shadow-md">
                <img :src="article.image_url || 'https://images.unsplash.com/photo-1523050854058-8df90110c9f1?auto=format&fit=crop&w=800&q=80'"
                  :alt="article.title"
                  class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" />
              </div>
              <h3 class="font-['Anton'] text-2xl sm:text-3xl text-[#005506] leading-snug uppercase tracking-wide group-hover:text-emerald-700 transition-colors mb-3">
                {{ article.title }}
              </h3>
              <p class="text-sm sm:text-base text-slate-600 font-normal leading-relaxed">{{ article.body_preview }}</p>
            </div>
          </article>
        </div>
        <p v-else class="text-slate-500 italic">No featured news yet. Check back soon!</p>
      </div>
    </section>

    <!-- CTA -->
    <section ref="ctaRef"
      class="w-full bg-[#005506] text-white pt-16 sm:pt-20 md:pt-24 pb-16 sm:pb-20 md:pb-24 px-6 sm:px-12 md:px-16 lg:px-24 relative overflow-hidden shadow-xl m-0">
      <div class="absolute -top-24 -left-24 w-80 h-80 sm:w-96 sm:h-96 md:w-[450px] md:h-[450px] bg-white/5 rounded-full pointer-events-none animate-pulse-slow"></div>
      <div class="absolute -bottom-32 -right-32 w-96 h-96 sm:w-[480px] sm:h-[480px] md:w-[600px] md:h-[600px] bg-white/5 rounded-full pointer-events-none animate-pulse-slow delay-1000"></div>

      <div class="relative z-10 max-w-4xl mx-auto text-center space-y-6 sm:space-y-8 transition-all duration-1000"
        :class="isCtaVisible ? 'opacity-100 scale-100' : 'opacity-0 scale-95'">
        <div>
          <span class="inline-flex items-center gap-2 bg-amber-400 text-slate-950 text-xs sm:text-sm font-bold px-4 sm:px-5 py-1.5 sm:py-2 rounded-full shadow-sm animate-bounce-subtle">
            <span>Admissions Open for School Year 2026–2027</span>
          </span>
        </div>

        <h2 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-bold text-white tracking-tight leading-tight">
          Your Future Starts at {{ school.name.split(' ')[0] }}.
        </h2>

        <p class="text-sm sm:text-base md:text-lg text-emerald-100/90 font-normal leading-relaxed max-w-2xl mx-auto">
          Join over {{ stats.students || 2400 }}+ ambitious high school learners in {{ school.address }}.
          Secure your spot in either the Academic or TechPro Track today — 100% tuition-free.
        </p>

        <div class="pt-2">
          <Link :href="route('site.admissions') + '#view-application-flow'"
            class="group relative inline-flex items-center justify-center gap-2.5 bg-amber-400 hover:bg-amber-300 text-slate-950 font-bold text-sm sm:text-base px-8 sm:px-10 py-4 rounded-full shadow-xl transition-all duration-300 hover:-translate-y-1 active:scale-95 cursor-pointer overflow-hidden">
            <span class="absolute inset-0 w-1/2 h-full bg-white/30 -skew-x-12 -translate-x-full group-hover:translate-x-[300%] transition-transform duration-1000 ease-out"></span>
            <span>Start Online Enrollment Now</span>
            <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
              <path stroke-linecap="round" stroke-linejoin="round"
                d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
            </svg>
          </Link>
        </div>

        <div class="flex flex-wrap items-center justify-center gap-6 sm:gap-8 pt-4 text-xs sm:text-sm font-medium text-emerald-100/90">
          <div class="flex items-center gap-2">
            <span class="w-4 h-4 rounded-full border-2 border-amber-400 text-amber-400 flex items-center justify-center text-[10px] font-bold">✓</span>
            <span>No Entrance Exam Fee</span>
          </div>
          <div class="flex items-center gap-2">
            <span class="w-4 h-4 rounded-full border-2 border-amber-400 text-amber-400 flex items-center justify-center text-[10px] font-bold">✓</span>
            <span>Form 137 / 138 Online Submissions</span>
          </div>
        </div>
      </div>
    </section>

    <Footer class="m-0 p-0 block" />
  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
import { Link, Head } from '@inertiajs/vue3'
import Navbar from '@/Components/Navbar.vue'
import Footer from '@/Components/Footer.vue'

const props = defineProps({
  school: { type: Object, required: true },
  stats: { type: Object, default: () => ({ students: 0, sections: 0, subjects: 0 }) },
  tracks: { type: Array, default: () => [] },
  latestNews: { type: Array, default: () => [] },
})

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

const scroll = (direction) => {
  if (!carouselRef.value) return
  const amount = direction === 'left' ? -420 : 420
  carouselRef.value.scrollBy({ left: amount, behavior: 'smooth' })
}

let observer = null

onMounted(() => {
  observer = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        if (entry.target === heroRef.value) isHeroVisible.value = entry.isIntersecting
        else if (entry.target === voicesRef.value) isVoicesVisible.value = entry.isIntersecting
        else if (entry.target === tracksRef.value) isTracksVisible.value = entry.isIntersecting
        else if (entry.target === newsRef.value) isNewsVisible.value = entry.isIntersecting
        else if (entry.target === ctaRef.value) isCtaVisible.value = entry.isIntersecting
      })
    },
    { threshold: 0.12 }
  )
  ;[heroRef, voicesRef, tracksRef, newsRef, ctaRef].forEach(r => { if (r.value) observer.observe(r.value) })
})

onUnmounted(() => { if (observer) observer.disconnect() })

/* ─── Strand icons ──────────────────────────────────────────
 * Each SHS cluster maps to a Heroicons outline `d` path.
 * Fallback is a generic book icon so an unlisted code still
 * renders a coherent badge.
 */
const STRAND_ICON_FALLBACK = 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253'

const STRAND_ICONS = {
  // Academic clusters
  'ARTS-SOC-HUM':
    'M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z',
  'STEM':
    'M9.75 3.104v5.714a2.25 2.25 0 01-.659 1.591L5 14.5M9.75 3.104c-.251.023-.501.05-.75.082m.75-.082a24.301 24.301 0 014.5 0m0 0v5.714c0 .597.237 1.17.659 1.591L19.8 15.3M14.25 3.104c.251.023.501.05.75.082M19.8 15.3l-1.57.393A9.065 9.065 0 0112 15a9.065 9.065 0 00-6.23-.693L5 14.5m14.8.8l1.402 1.402c1.232 1.232.65 3.318-1.067 3.611A48.309 48.309 0 0112 21c-2.773 0-5.491-.235-8.135-.687-1.718-.293-2.3-2.379-1.067-3.61L5 14.5',
  'SPORTS-WELLNESS':
    'M16.5 18.75h-9m9 0a3 3 0 013 3h-15a3 3 0 013-3m9 0v-3.375c0-.621-.503-1.125-1.125-1.125h-.871M7.5 18.75v-3.375c0-.621.504-1.125 1.125-1.125h.872m5.007 0H9.497m5.007 0a7.454 7.454 0 01-.982-3.172M9.497 14.25a7.454 7.454 0 00.981-3.172M5.25 4.236c-.982.143-1.954.317-2.916.52A6.003 6.003 0 007.73 9.728M5.25 4.236V4.5c0 2.108.966 3.99 2.48 5.228M5.25 4.236V2.721C7.456 2.41 9.71 2.25 12 2.25c2.291 0 4.545.16 6.75.47v1.516M7.73 9.728a6.726 6.726 0 002.748 1.35m8.272-6.842V4.5c0 2.108-.966 3.99-2.48 5.228m2.48-5.492a46.32 46.32 0 012.916.52 6.003 6.003 0 01-5.395 4.972m0 0a6.726 6.726 0 01-2.749 1.35m0 0a6.772 6.772 0 01-3.044 0',
  'BUSINESS-ENTREP':
    'M20.25 14.15v4.073a2.25 2.25 0 01-2.25 2.25h-12a2.25 2.25 0 01-2.25-2.25v-4.073M15.75 6a.75.75 0 00-.75-.75h-6a.75.75 0 00-.75.75m7.5 0v.75h1.5a2.25 2.25 0 012.25 2.25V15M15.75 6v.75H8.25V6m0 .75H6.75A2.25 2.25 0 004.5 9v6M8.25 6.75v.75h7.5v-.75',
  'FIELD-EXP':
    'M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0112 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 013 12c0-1.605.42-3.113 1.157-4.418',

  // TechPro clusters
  'AESTHETIC-WELLNESS':
    'M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09zM18.259 8.715L18 9.75l-.259-1.035a3.375 3.375 0 00-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 002.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 002.456 2.456L21.75 6l-1.035.259a3.375 3.375 0 00-2.456 2.456z',
  'AGRI-FISHERY':
    'M12 3v2.25m6.364.386l-1.591 1.591M21 12h-2.25m-.386 6.364l-1.591-1.591M12 18.75V21m-4.773-4.227l-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z',
  'ARTISANRY':
    'M9.53 16.122a3 3 0 00-5.78 1.128 2.25 2.25 0 01-2.4 2.245 4.5 4.5 0 008.4-2.245c0-.399-.078-.78-.22-1.128zm0 0a15.998 15.998 0 003.388-1.62m-5.043-.025a15.994 15.994 0 011.622-3.395m3.42 3.42a15.995 15.995 0 004.764-4.648l3.876-5.814a1.151 1.151 0 00-1.597-1.597L14.146 6.32a15.996 15.996 0 00-4.649 4.763m3.42 3.42a6.776 6.776 0 00-3.42-3.42',
  'AUTOMOTIVE':
    'M11.42 15.17L17.25 21A2.652 2.652 0 0021 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766M11.42 15.17l-4.655 5.653a2.548 2.548 0 11-3.586-3.586l6.837-5.63m5.108-.233c.55-.164 1.163-.188 1.743-.14a4.5 4.5 0 004.486-6.336l-3.276 3.277a3.004 3.004 0 01-2.25-2.25l3.276-3.276a4.5 4.5 0 00-6.336 4.486c.091 1.076-.071 2.264-.904 2.95l-.102.085m-1.745 1.437L5.909 7.5H4.5L2.25 3.75l1.5-1.5L7.5 4.5v1.409l4.26 4.26m-1.745 1.437L1.5 15l3.75 3.75 5.67-5.67',
  'CONSTRUCTION':
    'M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008z',
  'CREATIVE-ARTS':
    'M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09zM18.259 8.715L18 9.75l-.259-1.035a3.375 3.375 0 00-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 002.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 002.456 2.456L21.75 6l-1.035.259a3.375 3.375 0 00-2.456 2.456z',
  'HOSPITALITY':
    'M13.5 21v-7.5a.75.75 0 01.75-.75h3a.75.75 0 01.75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349m-16.5 11.65V9.35m0 0a3.001 3.001 0 003.75-.615A2.993 2.993 0 009.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 002.25 1.016c.896 0 1.7-.393 2.25-1.016a3.001 3.001 0 003.75.614m-16.5 0a3.004 3.004 0 01-.621-4.72L4.318 3.44A1.5 1.5 0 015.378 3h13.243a1.5 1.5 0 011.06.44l1.19 1.189a3 3 0 01-.621 4.72m-13.5 8.65h3.75a.75.75 0 00.75-.75V13.5a.75.75 0 00-.75-.75H6.75a.75.75 0 00-.75.75v3.75c0 .415.336.75.75.75z',
  'INDUSTRIAL':
    'M11.42 15.17L17.25 21A2.652 2.652 0 0021 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766M11.42 15.17l-4.655 5.653a2.548 2.548 0 11-3.586-3.586l6.837-5.63m5.108-.233c.55-.164 1.163-.188 1.743-.14a4.5 4.5 0 004.486-6.336l-3.276 3.277a3.004 3.004 0 01-2.25-2.25l3.276-3.276a4.5 4.5 0 00-6.336 4.486c.091 1.076-.071 2.264-.904 2.95l-.102.085m-1.745 1.437L5.909 7.5H4.5L2.25 3.75l1.5-1.5L7.5 4.5v1.409l4.26 4.26m-1.745 1.437L1.5 15l3.75 3.75 5.67-5.67',
  'ICT':
    'M9 17.25v1.007a3 3 0 01-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0115 18.257V17.25m6-12V15a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 15V5.25m18 0A2.25 2.25 0 0018.75 3H5.25A2.25 2.25 0 003 5.25m18 0V12a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 12V5.25',
  'MARITIME':
    'M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0112 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 013 12c0-1.605.42-3.113 1.157-4.418',
}

function strandIconPath(code) {
  if (!code) return STRAND_ICON_FALLBACK
  const key = String(code).trim().toUpperCase()
  return STRAND_ICONS[key] || STRAND_ICON_FALLBACK
}

// Voices stays local (not in DB yet)
const voices = [
  { id: 1, name: 'Marjorie Anne Reyes', role: 'Robotics Club Captain • DOST Scholar', badge: 'STEM • CLASS OF 2025',
    quote: 'Salawag gave me access to high-spec robotics equipment I thought only private academies had. My team represented Calabarzon at the National Science Quest!',
    image: 'https://images.unsplash.com/photo-1581092160607-ee22621dd758?auto=format&fit=crop&w=800&q=80' },
  { id: 2, name: 'Joshua Kyle Mendoza', role: 'Philippine Skills Competition Finalist', badge: 'TVL-ICT • COMPUTER SYSTEMS',
    quote: 'Before graduation, I already earned my TESDA National Certificate II in Computer Systems Servicing. The hands-on network lab prepared me directly for industry immersion.',
    image: 'https://images.unsplash.com/photo-1531482615713-2afd69097998?auto=format&fit=crop&w=800&q=80' },
  { id: 3, name: 'Kassandra Mae Bato', role: 'Cavite Youth Parliament Delegate', badge: 'HUMSS • SOCIAL SCIENCES',
    quote: 'Our teachers teach us how to think, not what to think. Through community immersion in Salawag and parliamentary debates, my passion for public service was sparked.',
    image: 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?auto=format&fit=crop&w=800&q=80' },
  { id: 4, name: 'Christian Joseph Aranda', role: 'Young Entrepreneurs League Lead', badge: 'ABM • BUSINESS MANAGEMENT',
    quote: 'Running our campus enterprise trade fair taught me real financial literacy, supply chain logic, and pitch presentations to actual Cavite business mentors.',
    image: 'https://images.unsplash.com/photo-1519085360753-af0119f7cbe7?auto=format&fit=crop&w=800&q=80' },
  { id: 5, name: 'Engr. Dalisay Santos', role: 'Master Teacher II • Innovation Hub Lead', badge: 'FACULTY • RESEARCH MENTOR',
    quote: 'Watching public school students design patents and automate local agricultural tools reminds me every day why teaching in Salawag is my lifetime calling.',
    image: 'https://images.unsplash.com/photo-1573497019940-1c28c88b4f3e?auto=format&fit=crop&w=800&q=80' },
  { id: 6, name: 'Lianne Beatrice Dizon', role: 'Software Engineer • Alumna Class of 2021', badge: 'ALUMNI • B.S. COMPUTER SCIENCE',
    quote: 'The rigorous STEM curriculum at Salawag Senior High built the exact problem-solving mindset that carried me straight to a tech scholarship and corporate success.',
    image: 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=800&q=80' },
]
</script>