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

/* Discover Section Timings */
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
</style>

<template>

  <Head title="Home - Salawag LMS" />

  <!-- Main Background Canvas -->
  <div class="min-h-screen bg-[#E8FFE8] font-['Inter'] text-slate-800 overflow-x-hidden">
    <!-- Navbar Component -->
    <Navbar />

    <!-- 1. Hero Section -->
    <section ref="heroRef"
      class="w-full max-w-[1500px] mx-auto px-6 sm:px-12 md:px-16 lg:px-24 pt-16 md:pt-24 pb-20 min-h-[85vh] flex flex-col justify-center">
      <div class="max-w-5xl space-y-8 text-left">

        <!-- Masked Hero Headline Container -->
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

        <!-- Subtext Paragraph -->
        <div class="overflow-hidden py-1">
          <p class="text-2xl md:text-3xl mt-5 lg:text-[30px] text-[#005506] font-normal leading-[1.5] max-w-5xl"
            :class="isHeroVisible ? 'animate-subtext-synced-reveal' : 'invisible'">
            Step into a Senior High community designed for your success. Access<br>
            specialized academic tracks, hands-on learning tools, and dedicated
            mentorship to turn your passion into a future-ready career.
          </p>
        </div>

        <!-- Left Accent Line -->
        <div class="pt-6">
          <div class="h-[2px] bg-slate-400/80 w-full max-w-[620px] origin-left"
            :class="isHeroVisible ? 'animate-line-slide' : 'invisible'"></div>
        </div>

      </div>
    </section>

    <!-- 2. Discover Your Potential Section -->
    <section ref="tracksRef"
      class="w-full max-w-[1400px] mx-auto px-6 sm:px-12 md:px-16 lg:px-24 mt-[3vh] pt-20 pb-32 text-center relative">
      <!-- Section Title -->
      <div class="overflow-hidden mb-16">
        <h2 class="font-['Anton'] text-4xl sm:text-5xl md:text-6xl text-[#005506] tracking-wide uppercase"
          :class="isTracksVisible ? 'animate-title-pop' : 'invisible'">
          DISCOVER YOUR POTENTIAL AT<br />
          SALAWAG SENIOR HIGH SCHOOL
        </h2>
      </div>

      <!-- Container holding Gray Background Box + Cards -->
      <div class="relative max-w-6xl mx-auto pt-8 pb-12 px-4 sm:px-8">

        <!-- Gray Box Background -->
        <div class="absolute bottom-0 right-0 w-[85%] sm:w-[90%] h-[75%] bg-[#E0E6E0] rounded-2xl z-0"
          :class="isTracksVisible ? 'animate-swipe-up-bg' : 'invisible'"></div>

        <!-- Grid of 4 Pillar Cards -->
        <div class="relative z-10 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 md:gap-8">
          <div v-for="(pillar, index) in pillars" :key="pillar.id"
            class="bg-white rounded-2xl p-6 shadow-xl border-[3px] border-[#005506] flex flex-col justify-start h-96 text-left transition-all duration-300 hover:-translate-y-2 hover:shadow-2xl"
            :class="isTracksVisible ? `animate-card-swipe-${index + 1}` : 'invisible'">
            <!-- Card Header Icon -->
            <div class="mb-4 text-[#005506] flex items-center gap-2">
              <!-- Sprout Icon -->
              <svg v-if="pillar.icon === 'sprout'" class="w-10 h-10 stroke-[#005506]" fill="none" viewBox="0 0 24 24"
                stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round"
                  d="M12 21v-8m0 0C10 8 5 8 5 3c6 0 7 5 7 10zm0 0c2-5 7-5 7-10-6 0-7 5-7 10z" />
              </svg>

              <!-- Compass Icon -->
              <svg v-else-if="pillar.icon === 'compass'" class="w-10 h-10 stroke-[#005506]" fill="none"
                viewBox="0 0 24 24" stroke-width="1.8">
                <circle cx="12" cy="12" r="9" />
                <polygon points="16.24 7.76 14.12 14.12 7.76 16.24 9.88 9.88 16.24 7.76" />
              </svg>

              <!-- Caring Educators Icon -->
              <svg v-else-if="pillar.icon === 'hands'" class="w-10 h-10 stroke-[#005506]" fill="none"
                viewBox="0 0 24 24" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round"
                  d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
              </svg>

              <!-- Future Ready Rocket Icon -->
              <svg v-else-if="pillar.icon === 'rocket'" class="w-10 h-10 stroke-[#005506]" fill="none"
                viewBox="0 0 24 24" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round"
                  d="M15.59 14.37a6 6 0 01-5.84 7.38v-4.8m5.84-2.58a14.98 14.98 0 006.16-12.12A14.98 14.98 0 009.63 8.41m5.96 5.96a14.926 14.926 0 01-5.841 2.58m-.119-8.54a6 6 0 00-7.381 5.84h4.8m2.581-5.84a14.927 14.927 0 00-2.58 5.84m2.58-5.84a14.927 14.927 0 015.84 2.58" />
              </svg>
            </div>

            <!-- Card Title -->
            <h3 class="font-bold text-2xl text-slate-900 leading-tight mb-4">
              {{ pillar.title }}
            </h3>

            <!-- Card Content: List View -->
            <ul v-if="pillar.type === 'list'"
              class="space-y-3 text-slate-700 text-sm leading-relaxed list-disc list-inside">
              <li v-for="(item, i) in pillar.items" :key="i" class="pl-1">
                {{ item }}
              </li>
            </ul>

            <!-- Card Content: Paragraph View -->
            <p v-else class="text-slate-700 text-sm leading-relaxed">
              {{ pillar.content }}
            </p>
          </div>
        </div>

      </div>
    </section>

    <!-- 3. Featured News Section (Expanded Full Width) -->
    <section ref="newsRef" class="w-full px-4 sm:px-8 md:px-12 lg:px-16 py-20 text-center relative">
      <!-- Section Title -->
      <div class="overflow-hidden mb-12">
        <h2 class="font-['Anton'] text-5xl sm:text-6xl lg:text-7xl text-[#005506] tracking-wide uppercase"
          :class="isNewsVisible ? 'animate-title-pop' : 'invisible'">
          FEATURED NEWS
        </h2>
      </div>

      <!-- Off-White Backdrop Card Grid Container (Stretches Full Width) -->
      <div class="w-full p-6 sm:p-10 lg:p-12 bg-[#f2f6f2] rounded-3xl shadow-sm">

        <!-- 3x2 Responsive News Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 md:gap-10 lg:gap-12 text-left">
          <article v-for="(article, index) in newsArticles" :key="article.id"
            class="group cursor-pointer flex flex-col justify-between"
            :class="isNewsVisible ? `animate-card-swipe-${(index % 4) + 1}` : 'invisible'">
            <div>
              <!-- Article Image with Zoom Effect -->
              <div class="overflow-hidden rounded-2xl mb-5 bg-slate-200 aspect-[16/10] shadow-md">
                <img :src="article.image" :alt="article.title"
                  class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" />
              </div>

              <!-- Headline -->
              <h3
                class="font-['Anton'] text-2xl sm:text-3xl text-slate-900 leading-snug uppercase tracking-wide group-hover:text-[#005506] transition-colors mb-3">
                {{ article.title }}
              </h3>

              <!-- Excerpt -->
              <p class="text-sm sm:text-base text-slate-600 font-normal leading-relaxed">
                {{ article.excerpt }}
              </p>
            </div>
          </article>
        </div>

      </div>
    </section>
    <Footer/>

  </div>
</template>


<script setup>
import academicFairImage from '../../../assets/img/local/news-academic-fair.jpg'
import enrollmentImage from '../../../assets/img/local/news-enrollment.jpg'
import sportsClubImage from '../../../assets/img/local/news-sports-club.jpg'
import stemLabImage from '../../../assets/img/local/news-stem-lab.jpg'
import studentCouncilImage from '../../../assets/img/local/news-student-council.jpg'
import workImmersionImage from '../../../assets/img/local/news-work-immersion.jpg'
import Navbar from '@/Components/Navbar.vue'
import { Head } from '@inertiajs/vue3'
import { ref, onMounted, onUnmounted } from 'vue'
import Footer from '@/Components/Footer.vue'
// Section Visibility Refs for Scroll Triggering
const heroRef = ref(null)
const tracksRef = ref(null)
const newsRef = ref(null)

const isHeroVisible = ref(false)
const isTracksVisible = ref(false)
const isNewsVisible = ref(false)

let observer = null

onMounted(() => {
  observer = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        if (entry.target === heroRef.value) {
          isHeroVisible.value = entry.isIntersecting
        } else if (entry.target === tracksRef.value) {
          isTracksVisible.value = entry.isIntersecting
        } else if (entry.target === newsRef.value) {
          isNewsVisible.value = entry.isIntersecting
        }
      })
    },
    { threshold: 0.15 }
  )

  if (heroRef.value) observer.observe(heroRef.value)
  if (tracksRef.value) observer.observe(tracksRef.value)
  if (newsRef.value) observer.observe(newsRef.value)
})

onUnmounted(() => {
  if (observer) observer.disconnect()
})

// Pillar Cards Data
const pillars = [
  {
    id: 1,
    title: 'Empowering Potential',
    type: 'list',
    items: [
      'Empowering potential, cultivating your environment.',
      'Empowering potential and culture.'
    ],
    icon: 'sprout'
  },
  {
    id: 2,
    title: 'Faith & Purpose',
    type: 'paragraph',
    content: 'Faith & Purpose provides guiding principles, action with Faith & Purpose.',
    icon: 'compass'
  },
  {
    id: 3,
    title: 'Caring Educators',
    type: 'paragraph',
    content: 'Caring Educators, educators nurturing community and providers.',
    icon: 'hands'
  },
  {
    id: 4,
    title: 'Future Ready',
    type: 'paragraph',
    content: 'Designed to equip students with technology skills, integrated network for graduation and career readiness.',
    icon: 'rocket'
  }
]

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
</script>