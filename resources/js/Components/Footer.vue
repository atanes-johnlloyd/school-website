<script setup>
import bannerImg from '@/../assets/img/desktop-home-banner.png'
import logoImg from '@/../assets/img/SSHS-logo.png'
import catImg from '@/../assets/img/cat_not_hug.png'
</script>

<template>
  <footer class="relative w-full pt-12 sm:pt-16 pb-0 overflow-visible font-['Inter'] z-30 m-0">
    <!-- Full-Bleed Outer Container -->
    <div class="relative w-full">

      <!-- Mascot Overlapping Above Footer Border (Mobile Optimized) -->
      <div class="absolute left-3 sm:left-6 lg:left-12 -top-14 sm:-top-24 md:-top-28 lg:-top-36 z-20 pointer-events-none">
        <img 
          :src="catImg" 
          alt="Salawag SHS Mascot"
          class="w-[140px] xs:w-[170px] sm:w-[220px] md:w-[320px] lg:w-[420px] h-auto object-contain drop-shadow-xl" 
        />
      </div>

      <!-- Footer Green Banner Section -->
      <div 
        class="relative z-10 w-full border-t-2 border-[#eab308]/60 shadow-2xl pt-16 sm:pt-20 md:pt-14 pb-10 md:pb-14 pl-5 sm:pl-8 md:pl-[340px] lg:pl-[440px] pr-5 sm:pr-8 md:pr-12 lg:pr-20 text-white"
        :style="{
          backgroundImage: `linear-gradient(rgba(0, 85, 6, 0.92), rgba(0, 85, 6, 0.92)), url(${bannerImg})`,
          backgroundSize: 'cover',
          backgroundPosition: 'center'
        }"
      >
        <div class="max-w-[1400px] mx-auto space-y-8 md:space-y-10">

          <!-- 4-COLUMN CONTENT GRID -->
          <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 sm:gap-8 lg:gap-10 text-left">

            <!-- COLUMN 1: BRANDING & SCHOOL ID -->
            <div class="space-y-3">
              <div class="flex items-center gap-3">
                <img :src="logoImg" alt="Salawag SHS Logo" class="w-10 h-10 sm:w-12 sm:h-12 object-contain shrink-0" />
                <div>
                  <h3 class="font-['Anton'] text-lg sm:text-xl tracking-wide uppercase leading-tight text-white">
                    SALAWAG SHS
                  </h3>
                  <p class="text-[10px] sm:text-xs tracking-wider text-emerald-100 uppercase font-medium">
                    INTEGRATED NATIONAL HIGH SCHOOL
                  </p>
                </div>
              </div>

              <p class="text-xs text-emerald-100/90 leading-relaxed font-normal">
                Committed to accessible excellence, values-driven public secondary education, and holistic student pathways under DepEd Cavite.
              </p>

              <div>
                <span class="inline-block bg-white/10 backdrop-blur-sm text-amber-300 text-[11px] sm:text-xs font-bold px-3 py-1 rounded-full border border-white/20">
                  School ID: 342512
                </span>
              </div>
            </div>

            <!-- COLUMN 2: CAMPUS & LOCATION -->
            <div class="space-y-2.5 text-xs sm:text-sm">
              <h4 class="font-bold tracking-wider uppercase border-b border-white/20 pb-1 text-slate-100">
                CAMPUS & LOCATION
              </h4>
              <p class="text-xs text-emerald-100/90 leading-relaxed font-normal">
                Golden City Subdivision, Barangay Salawag, City of Dasmariñas, Cavite 4114, Philippines
              </p>
              <div class="space-y-1 text-xs text-emerald-100/90 pt-1">
                <p><span class="font-bold text-white">Email:</span> 342512@deped.gov.ph</p>
                <p><span class="font-bold text-white">Landline:</span> (046) 416-0000</p>
              </div>
            </div>

            <!-- COLUMN 3: EMERGENCY HOTLINES -->
            <div class="space-y-2.5 text-xs sm:text-sm">
              <h4 class="font-bold tracking-wider uppercase border-b border-white/20 pb-1 text-slate-100">
                EMERGENCY HOTLINES
              </h4>
              <div class="space-y-1.5 text-xs text-emerald-100/90">
                <p><span class="font-bold text-white">Dasmariñas CDRRMO:</span> (046) 481-4600</p>
                <p><span class="font-bold text-white">PNP Salawag Sub-Station:</span> (046) 432-1111</p>
                <p><span class="font-bold text-white">DepEd Cavite Division:</span> (046) 419-1286</p>
                <p><span class="font-bold text-white">Guidance & Mental Health:</span> 0917-000-SSHS</p>
              </div>
            </div>

            <!-- COLUMN 4: PUBLIC TRANSPARENCY & QUICK LINKS -->
            <div class="space-y-2.5 text-xs sm:text-sm">
              <div class="flex items-center justify-between border-b border-white/20 pb-1">
                <h4 class="font-bold tracking-wider uppercase text-slate-100">
                  TRANSPARENCY
                </h4>
                <!-- Social Icons -->
                <div class="flex items-center gap-1.5">
                  <a href="#" class="w-5 h-5 rounded border border-white/40 flex items-center justify-center text-[10px] hover:bg-white/20 transition-colors">f</a>
                  <a href="#" class="w-5 h-5 rounded border border-white/40 flex items-center justify-center text-[10px] hover:bg-white/20 transition-colors">yt</a>
                </div>
              </div>
              <ul class="space-y-1.5 text-xs text-emerald-100/90 font-medium">
                <li><a href="#" class="hover:text-amber-300 hover:underline transition-colors">Citizen's Charter</a></li>
                <li><a href="#" class="hover:text-amber-300 hover:underline transition-colors">Data Privacy Notice</a></li>
                <li><a href="#" class="hover:text-amber-300 hover:underline transition-colors">DepEd Transparency Seal</a></li>
                <li><a href="#" class="hover:text-amber-300 hover:underline transition-colors">Procurement & BAC Opportunities</a></li>
              </ul>
            </div>

          </div>

          <!-- BOTTOM COPYRIGHT BAR -->
          <div class="pt-6 border-t border-white/20 flex flex-col sm:flex-row items-center justify-between gap-3 text-center sm:text-left text-[11px] text-emerald-200/80 font-normal">
            <p>© 2026 Salawag Senior High School. Department of Education - Republic of the Philippines.</p>
            <p class="font-medium text-amber-300 shrink-0">Serbisyong Tapat Para sa Kabataang Salawagueño</p>
          </div>

        </div>
      </div>

    </div>
  </footer>
</template>