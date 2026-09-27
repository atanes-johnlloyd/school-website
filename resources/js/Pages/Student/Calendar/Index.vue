<template>

    <Head title="Calendar - Salawag LMS" />

    <div class="min-h-screen flex bg-[#e8f5e9] font-['Inter'] relative">

        <!-- Sticky Desktop Sidebar Navigation -->
        <div class="sticky top-0 h-screen z-30 shrink-0">
            <Sidebar />
        </div>

        <!-- Main Workspace Canvas -->
        <main class="flex-1 relative overflow-y-auto min-h-screen flex flex-col justify-between">

            <!-- Isolated Pattern Background (15% Opacity) -->
            <div class="absolute inset-0 bg-cover bg-center bg-no-repeat pointer-events-none z-0 opacity-15"
                :style="{ backgroundImage: `url(${dashboardBg})` }"></div>

            <!-- Main Content Container -->
            <div class="relative z-10 p-6 md:p-10 space-y-8 flex-1 pb-16">

                <!-- HEADER ROW: STUDENT CALENDAR -->
                <div class="space-y-1">
                    <div
                        class="font-['Anton'] text-3xl sm:text-4xl md:text-5xl tracking-wide uppercase flex items-center gap-2 select-none">
                        <!-- Solid Emerald Fill for STUDENT -->
                        <span class="text-[#005506]">STUDENT</span>
                        <!-- Reverse Fill-Stroke Animation for CALENDAR -->
                        <span class="animated-reverse-stroke-text">CALENDAR</span>
                    </div>

                    <p class="text-xs sm:text-sm italic font-medium text-slate-600">
                        "Your journey to knowledge starts with one click."
                    </p>

                    <!-- Decorative Star Divider Line -->
                    <div class="flex items-center gap-2 pt-1 max-w-md">
                        <div class="h-[2px] w-full bg-[#005506] animate-line-expand"></div>
                        <span class="text-[#005506] text-xs">★</span>
                    </div>
                </div>

                <!-- SECTION: UPDATED CALENDAR -->
                <div class="space-y-4">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <h3 class="text-xl sm:text-2xl font-bold text-[#005506] tracking-tight">
                            Updated Calendar
                        </h3>

                        <!-- Month Controls & Legend -->
                        <div class="flex items-center gap-3">
                            <div
                                class="flex items-center bg-white/80 backdrop-blur-sm border border-[#005506]/30 rounded-2xl p-1 shadow-sm">
                                <button @click="prevMonth" type="button"
                                    class="w-8 h-8 rounded-xl flex items-center justify-center text-[#005506] hover:bg-[#005506] hover:text-white transition-colors text-lg font-bold">
                                    ‹
                                </button>
                                <span class="px-4 font-bold text-xs text-slate-800 min-w-[120px] text-center">
                                    {{ currentMonthYear }}
                                </span>
                                <button @click="nextMonth" type="button"
                                    class="w-8 h-8 rounded-xl flex items-center justify-center text-[#005506] hover:bg-[#005506] hover:text-white transition-colors text-lg font-bold">
                                    ›
                                </button>
                            </div>

                            <button @click="goToToday" type="button"
                                class="px-4 py-2 bg-[#005506] hover:bg-[#004204] text-white text-xs font-bold rounded-2xl shadow-sm transition-all active:scale-95">
                                Today
                            </button>
                        </div>
                    </div>

                    <!-- TABULAR CALENDAR FRAME -->
                    <div
                        class="bg-white/90 backdrop-blur-sm rounded-3xl border-2 border-[#005506] overflow-hidden shadow-sm">

                        <table class="w-full border-collapse table-fixed text-center">
                            <!-- Table Header (Days of the Week) -->
                            <thead>
                                <tr class="bg-[#e8f5e9] border-b-2 border-[#005506]">
                                    <th v-for="(day, i) in weekDays" :key="day" :class="[
                                        'py-3 font-[\'Anton\'] text-xs sm:text-sm text-[#005506] tracking-wider uppercase font-normal',
                                        i < 6 ? 'border-r-2 border-[#005506]' : ''
                                    ]">
                                        {{ day }}
                                    </th>
                                </tr>
                            </thead>

                            <!-- Tabular Days Grid -->
                            <tbody class="bg-white">
                                <tr v-for="weekIndex in Math.ceil(calendarDays.length / 7)" :key="weekIndex"
                                    class="border-b-2 border-[#005506] last:border-b-0">
                                    <td v-for="(day, dayIndex) in calendarDays.slice((weekIndex - 1) * 7, weekIndex * 7)"
                                        :key="dayIndex" :class="[
                                            'h-[100px] sm:h-[120px] p-2 align-top transition-colors relative',
                                            dayIndex < 6 ? 'border-r-2 border-[#005506]' : '',
                                            !day.isCurrentMonth ? 'bg-slate-50/40' : 'hover:bg-emerald-50/30 text-slate-800'
                                        ]">
                                        <div class="h-full flex flex-col justify-between">
                                            <!-- Day Number & Today Highlight -->
                                            <div class="flex items-center justify-between">
                                                <span v-if="day.isCurrentMonth" :class="[
                                                    'text-xs sm:text-sm font-extrabold w-6 h-6 sm:w-7 sm:h-7 rounded-full flex items-center justify-center',
                                                    day.isToday ? 'bg-[#005506] text-white shadow-md' : 'text-slate-800'
                                                ]">
                                                    {{ day.date }}
                                                </span>
                                                <span v-else class="w-6 h-6 sm:w-7 sm:h-7"></span>

                                                <!-- Event Counter Indicator -->
                                                <span v-if="day.isCurrentMonth && day.events?.length"
                                                    class="text-[10px] font-bold text-[#005506] bg-emerald-100 px-1.5 py-0.5 rounded-full">
                                                    {{ day.events.length }}
                                                </span>
                                            </div>

                                            <!-- Event Pills Container -->
                                            <div v-if="day.isCurrentMonth"
                                                class="space-y-1.5 my-1 overflow-y-auto max-h-[60px] custom-scrollbar text-left">
                                                <div v-for="evt in day.events" :key="evt.id" :class="[
                                                    'px-2 py-1 rounded-lg text-[10px] font-bold truncate leading-tight border',
                                                    evt.type === 'exam' ? 'bg-rose-50 text-rose-700 border-rose-200' :
                                                        evt.type === 'assignment' ? 'bg-amber-50 text-amber-800 border-amber-200' :
                                                            'bg-emerald-50 text-[#005506] border-emerald-200'
                                                ]">
                                                    {{ evt.title }}
                                                </div>
                                            </div>

                                            <div class="h-1"></div>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                    </div>

                    <!-- EVENT CATEGORY LEGEND -->
                    <div class="flex flex-wrap items-center gap-6 pt-2 text-xs font-semibold text-slate-600">
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full bg-[#005506]"></span>
                            <span>Class Event / Lesson</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full bg-amber-500"></span>
                            <span>Assignments & Projects</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full bg-rose-500"></span>
                            <span>Exams & Quizzes</span>
                        </div>
                    </div>

                </div>

            </div>

        </main>

    </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { Head } from '@inertiajs/vue3'
import Sidebar from '@/Components/Sidebar.vue'

import dashboardBg from '@/../assets/img/dashboardbackground.png'

const weekDays = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat']

const currentDate = ref(new Date(2026, 4, 1)) // Default May 2026

const currentMonthYear = computed(() => {
    return currentDate.value.toLocaleString('en-US', { month: 'long', year: 'numeric' })
})

const prevMonth = () => {
    currentDate.value = new Date(currentDate.value.getFullYear(), currentDate.value.getMonth() - 1, 1)
}

const nextMonth = () => {
    currentDate.value = new Date(currentDate.value.getFullYear(), currentDate.value.getMonth() + 1, 1)
}

const goToToday = () => {
    currentDate.value = new Date(2026, 8, 22)
}

const calendarDays = computed(() => {
    const year = currentDate.value.getFullYear()
    const month = currentDate.value.getMonth()

    const firstDayOfMonth = new Date(year, month, 1).getDay()
    const daysInMonth = new Date(year, month + 1, 0).getDate()

    const days = []

    // Blank leading slots before 1st of month
    for (let i = firstDayOfMonth - 1; i >= 0; i--) {
        days.push({
            date: null,
            isCurrentMonth: false,
            isToday: false,
            events: [],
        })
    }

    // Active Month Days
    for (let d = 1; d <= daysInMonth; d++) {
        const isToday = d === 22 && month === 8 && year === 2026

        let events = []
        if (d === 15) events.push({ id: 1, title: 'Orientation', type: 'class' })
        if (d === 22) events.push({ id: 2, title: 'Math Quiz 1', type: 'exam' })
        if (d === 28) events.push({ id: 3, title: 'Physics Lab Due', type: 'assignment' })

        days.push({
            date: d,
            isCurrentMonth: true,
            isToday,
            events,
        })
    }

    // Round up grid to complete 7-column rows
    const totalSlotsNeeded = Math.ceil(days.length / 7) * 7
    const remainingSlots = totalSlotsNeeded - days.length

    // Blank trailing slots
    for (let n = 1; n <= remainingSlots; n++) {
        days.push({
            date: null,
            isCurrentMonth: false,
            isToday: false,
            events: [],
        })
    }

    return days
})
</script>

<style scoped>

/* REVERSE ANIMATED FILL-STROKE TEXT EFFECT FOR 'CALENDAR' */
.animated-reverse-stroke-text {
    color: transparent;
    -webkit-text-stroke: 1.5px #005506;
    background: linear-gradient(to right, transparent 50%, #005506 50%);
    background-size: 200% 100%;
    background-position: 100% 0;
    -webkit-background-clip: text;
    background-clip: text;
    animation: reverseFillStrokeAnim 1.4s cubic-bezier(0.16, 1, 0.3, 1) 0.2s forwards;
}

@keyframes reverseFillStrokeAnim {
    0% {
        background-position: 100% 0;
    }

    100% {
        background-position: 0 0;
    }
}

@keyframes lineExpand {
    0% {
        width: 0%;
    }

    100% {
        width: 100%;
    }
}

.animate-line-expand {
    animation: lineExpand 1s cubic-bezier(0.16, 1, 0.3, 1) 0.4s both;
}

/* Custom Scrollbars */
.custom-scrollbar::-webkit-scrollbar {
    width: 4px;
}

.custom-scrollbar::-webkit-scrollbar-track {
    background: transparent;
}

.custom-scrollbar::-webkit-scrollbar-thumb {
    background: rgba(0, 85, 6, 0.2);
    border-radius: 4px;
}
</style>