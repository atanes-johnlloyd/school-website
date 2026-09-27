<script setup>
// If your images are stored under resources/assets/img/ or resources/js/assets/img/
import bannerImg from '@/../assets/img/desktop-home-banner.png'
import logoImg from '@/../assets/img/SSHS-logo.png'
import catImg from '@/../assets/img/cat_not_hug.png'
</script>

<template>
    <!-- Set pb-0 to eliminate the bottom space -->
    <footer class="relative w-full pt-16 pb-0 overflow-visible font-['Inria_Sans'] z-30">
        <!-- Full-Bleed Outer Container -->
        <div class="relative w-full">

            <!-- Mascot Overlapping Above the Footer Top Border (+40% Bigger) -->
            <div class="absolute left-2 sm:left-6 lg:left-12 -top-20 sm:-top-28 lg:-top-36 z-20 pointer-events-none">
                <img :src="catImg" alt="Salawag SHS Mascot"
                    class="w-[290px] sm:w-[360px] md:w-[400px] lg:w-[450px] h-auto object-contain drop-shadow-xl" />
            </div>

            <div class="relative z-10 w-full border-t-2 border-[#eab308]/60 shadow-2xl pl-64 sm:pl-80 md:pl-[420px] lg:pl-[500px] pr-6 sm:pr-12 md:pr-16 lg:pr-24 py-8 md:py-10 text-white flex flex-col md:flex-row items-center md:items-start justify-between gap-8 md:gap-12"
                :style="{
                    backgroundImage: `linear-gradient(rgba(0, 85, 6, 0.81), rgba(0, 85, 6, 0.81)), url(${bannerImg})`,
                    backgroundSize: 'cover',
                    backgroundPosition: 'center'
                }">
                <!-- Branding Section (Logo & Name) -->
                <div class="flex items-center gap-3 min-w-max">
                    <img :src="logoImg" alt="Salawag SHS Logo" class="w-12 h-12 md:w-14 md:h-14 object-contain" />
                    <div>
                        <h3 class="font-['Anton'] text-xl sm:text-2xl tracking-wide uppercase leading-tight">
                            SALAWAG SHS
                        </h3>
                        <p class="text-[10px] sm:text-xs tracking-wider text-slate-200 uppercase font-medium">
                            INTEGRATED NATIONAL HIGH SCHOOL
                        </p>
                    </div>
                </div>

                <!-- Contact Us Column -->
                <div class="text-left space-y-2.5 max-w-xs text-xs sm:text-sm">
                    <h4 class="font-bold tracking-wider uppercase border-b border-white/20 pb-1 text-slate-100">
                        CONTACT US
                    </h4>
                    <ul class="space-y-1.5 text-slate-200 font-light leading-relaxed">
                        <li class="flex items-start gap-2">
                            <span class="opacity-80">📍</span>
                            <span>Salawag Road, Barangay Salawag, Dasmariñas City, Cavite 4114, Philippines</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="opacity-80">📞</span>
                            <span>+63 46 123 4567</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="opacity-80">✉️</span>
                            <span>admissions@salawagshs.edu.ph</span>
                        </li>
                    </ul>
                </div>

                <!-- Quick Links Column & Social Icons -->
                <div class="text-left space-y-2.5 text-xs sm:text-sm min-w-[160px]">
                    <div class="flex items-center justify-between border-b border-white/20 pb-1">
                        <h4 class="font-bold tracking-wider uppercase text-slate-100">
                            QUICK LINKS
                        </h4>
                        <!-- Social Icons -->
                        <div class="flex items-center gap-1.5 pl-2">
                            <a href="#"
                                class="w-5 h-5 rounded border border-white/40 flex items-center justify-center text-[10px] hover:bg-white/20 transition-colors">f</a>
                            <a href="#"
                                class="w-5 h-5 rounded border border-white/40 flex items-center justify-center text-[10px] hover:bg-white/20 transition-colors">📷</a>
                            <a href="#"
                                class="w-5 h-5 rounded border border-white/40 flex items-center justify-center text-[10px] hover:bg-white/20 transition-colors">𝕏</a>
                            <a href="#"
                                class="w-5 h-5 rounded border border-white/40 flex items-center justify-center text-[10px] hover:bg-white/20 transition-colors">v</a>
                        </div>
                    </div>

                    <ul class="space-y-1 text-slate-200 font-light">
                        <li><a href="#" class="hover:underline transition-all">School Calendar</a></li>
                        <li><a href="#" class="hover:underline transition-all">Academic Tracks</a></li>
                        <li><a href="#" class="hover:underline transition-all">Admission Policy</a></li>
                        <li><a href="#" class="hover:underline transition-all">Accreditation</a></li>
                        <li><a href="#" class="hover:underline transition-all">FAQs</a></li>
                    </ul>
                </div>

            </div>
        </div>
    </footer>
</template>
