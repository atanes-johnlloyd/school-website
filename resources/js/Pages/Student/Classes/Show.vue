<template>
  <Head :title="`${classroom.subject} - Salawag LMS`" />

  <div class="min-h-screen flex bg-[#e8f5e9] font-['Inter'] relative">
    
    <!-- Sticky Desktop Sidebar Navigation -->
    <div class="sticky top-0 h-screen z-30 shrink-0">
      <Sidebar />
    </div>

    <!-- Main Workspace Canvas -->
    <main class="flex-1 relative overflow-y-auto min-h-screen flex flex-col justify-between">

      <!-- Main Content Container -->
      <div class="relative z-10 p-6 md:p-10 space-y-8 flex-1 pb-16">
        
        <!-- HEADER ROW: TITLE LEFT & SUBJECT TITLE RIGHT -->
        <div class="flex flex-col md:flex-row md:items-start justify-between gap-4">
          
          <!-- Left Title & Subtext -->
          <div class="space-y-1">
            <div class="font-['Anton'] text-3xl sm:text-4xl md:text-5xl tracking-wide uppercase flex items-center gap-2 select-none">
              <span class="text-[#005506]">STUDENT</span>
              <span class="animated-reverse-stroke-text">SUBJECTS</span>
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

          <!-- Right Class Title -->
          <div class="text-left md:text-rightbackdrop-blur-sm p-4 rounded-2xl">
            <h2 class="font-['Anton'] text-2xl sm:text-3xl md:text-4xl text-[#005506] tracking-wide uppercase">
              {{ classroom.subject || 'The Work of Rizal' }}
            </h2>
            <p v-if="classroom.teacher" class="text-xs font-bold text-slate-600 mt-0.5 flex items-center md:justify-end gap-1.5">
              <span>Instructor:</span>
              <span class="text-[#005506] bg-emerald-100/60 px-2.5 py-0.5 rounded-full text-[11px]">{{ classroom.teacher }}</span>
            </p>
          </div>

        </div>

        <!-- TAB NAVIGATION ROW -->
        <div class="flex items-center justify-between border-b border-[#005506]/20 pb-2">
          <div class="flex items-center gap-6 sm:gap-8 overflow-x-auto">
            
            <!-- Tab 1: Announcement -->
            <button 
              @click="activeTab = 'announcements'"
              :class="[
                'text-sm sm:text-base font-extrabold transition-all relative pb-3 whitespace-nowrap flex items-center gap-2',
                activeTab === 'announcements' 
                  ? 'text-[#005506]' 
                  : 'text-slate-400 hover:text-slate-700'
              ]"
            >
              <span>Announcements</span>
              <span class="bg-emerald-100 text-[#005506] text-xs px-2 py-0.5 rounded-full font-bold">
                {{ announcementsList.length }}
              </span>
              <div 
                v-if="activeTab === 'announcements'" 
                class="absolute bottom-0 left-0 right-0 h-1 bg-[#005506] rounded-full"
              ></div>
            </button>

            <!-- Tab 2: Assignment -->
            <button 
              @click="activeTab = 'assignments'"
              :class="[
                'text-sm sm:text-base font-extrabold transition-all relative pb-3 whitespace-nowrap flex items-center gap-2',
                activeTab === 'assignments' 
                  ? 'text-[#005506]' 
                  : 'text-slate-400 hover:text-slate-700'
              ]"
            >
              <span>Assignments</span>
              <span class="bg-amber-100 text-amber-800 text-xs px-2 py-0.5 rounded-full font-bold">
                {{ assignmentsList.length }}
              </span>
              <div 
                v-if="activeTab === 'assignments'" 
                class="absolute bottom-0 left-0 right-0 h-1 bg-[#005506] rounded-full"
              ></div>
            </button>

            <!-- Tab 3: Materials -->
            <button 
              @click="activeTab = 'materials'"
              :class="[
                'text-sm sm:text-base font-extrabold transition-all relative pb-3 whitespace-nowrap',
                activeTab === 'materials' 
                  ? 'text-[#005506]' 
                  : 'text-slate-400 hover:text-slate-700'
              ]"
            >
              Materials & Resources
              <div 
                v-if="activeTab === 'materials'" 
                class="absolute bottom-0 left-0 right-0 h-1 bg-[#005506] rounded-full"
              ></div>
            </button>

            <!-- Tab 4: Grades -->
            <button 
              @click="activeTab = 'grades'"
              :class="[
                'text-sm sm:text-base font-extrabold transition-all relative pb-3 whitespace-nowrap',
                activeTab === 'grades' 
                  ? 'text-[#005506]' 
                  : 'text-slate-400 hover:text-slate-700'
              ]"
            >
              Grades
              <div 
                v-if="activeTab === 'grades'" 
                class="absolute bottom-0 left-0 right-0 h-1 bg-[#005506] rounded-full"
              ></div>
            </button>
          </div>

          <!-- Quick Action Button for Instructor -->
          <button 
            v-if="isTeacher" 
            @click="showCreateModal = true"
            class="hidden sm:flex items-center gap-2 bg-[#005506] hover:bg-[#003805] text-white text-xs font-bold px-4 py-2 rounded-xl transition-all shadow-xs"
          >
            <span>+ Create</span>
          </button>
        </div>

        <!-- TAB CONTENT 1: ANNOUNCEMENTS -->
        <div v-if="activeTab === 'announcements'" class="space-y-6 animate-fade-in">
          
          <!-- FEATURED / PINNED ANNOUNCEMENT HERO CARD -->
          <div 
            v-if="announcementsList.find(a => a.isPinned)"
            class="bg-gradient-to-br from-[#005506] to-[#003304] text-white rounded-3xl p-6 sm:p-8 shadow-md relative overflow-hidden group cursor-pointer"
            @click="openAnnouncementModal(announcementsList.find(a => a.isPinned))"
          >
            <div class="absolute -right-10 -bottom-10 w-48 h-48 bg-white/5 rounded-full blur-2xl pointer-events-none"></div>

            <div class="relative z-10 space-y-4">
              <div class="flex items-center justify-between">
                <span class="bg-amber-400 text-slate-950 font-black text-[10px] uppercase tracking-wider px-3 py-1 rounded-full flex items-center gap-1 shadow-xs">
                  📌 Pinned Announcement
                </span>
                <span class="text-xs text-emerald-200/80 font-medium">{{ announcementsList[0].date }}</span>
              </div>

              <div class="space-y-2 max-w-3xl">
                <h3 class="font-black text-xl sm:text-2xl text-white group-hover:text-emerald-300 transition-colors">
                  {{ announcementsList[0].title }}
                </h3>
                <p class="text-xs sm:text-sm text-emerald-100/90 leading-relaxed line-clamp-3">
                  {{ announcementsList[0].content }}
                </p>
              </div>

              <div class="pt-4 border-t border-white/10 flex items-center justify-between text-xs text-emerald-200">
                <div class="flex items-center gap-3">
                  <div class="w-8 h-8 rounded-full bg-white/20 flex items-center justify-center font-bold text-white border border-white/30">
                    {{ announcementsList[0].postedBy.charAt(0) }}
                  </div>
                  <div>
                    <span class="font-extrabold block text-white">{{ announcementsList[0].postedBy }}</span>
                    <span class="text-[10px] text-emerald-300">Class Instructor</span>
                  </div>
                </div>

                <span class="font-bold text-white group-hover:translate-x-1 transition-transform flex items-center gap-1">
                  Read Full Notice →
                </span>
              </div>
            </div>
          </div>

          <!-- REGULAR ANNOUNCEMENTS GRID -->
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div 
              v-for="item in announcementsList.filter(a => !a.isPinned)" 
              :key="item.id"
              @click="openAnnouncementModal(item)"
              class="bg-[#fbfdf9] rounded-3xl p-6 shadow-xs border border-slate-200/80 min-h-[220px] flex flex-col justify-between hover:border-[#005506] hover:shadow-md transition-all cursor-pointer group relative"
            >
              <div class="space-y-3">
                <div class="flex items-center justify-between">
                  <span class="text-[10px] font-extrabold uppercase bg-emerald-100 text-[#005506] px-3 py-1 rounded-full">
                    {{ item.category || 'General' }}
                  </span>
                  <span class="text-[11px] font-semibold text-slate-400">{{ item.date }}</span>
                </div>

                <h4 class="font-extrabold text-slate-900 text-base leading-snug group-hover:text-[#005506] transition-colors">
                  {{ item.title }}
                </h4>

                <p class="text-xs text-slate-600 line-clamp-3 leading-relaxed font-medium">
                  {{ item.content }}
                </p>
              </div>

              <div class="pt-4 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500 font-semibold">
                <div class="flex items-center gap-2">
                  <div class="w-6 h-6 rounded-full bg-[#005506] text-white text-[10px] flex items-center justify-center font-bold">
                    {{ item.postedBy.charAt(0) }}
                  </div>
                  <span class="text-slate-700 font-bold">{{ item.postedBy }}</span>
                </div>
                <span class="text-emerald-700 font-bold group-hover:translate-x-0.5 transition-transform">Read →</span>
              </div>
            </div>
          </div>

        </div>

        <!-- TAB CONTENT 2: ASSIGNMENTS -->
        <div v-if="activeTab === 'assignments'" class="space-y-6 animate-fade-in">
          
          <!-- ASSIGNMENT FILTER BAR -->
          <div class="bg-[#fbfdf9] rounded-2xl p-4 border border-slate-200/80 flex flex-wrap items-center justify-between gap-4 shadow-xs">
            <div class="flex items-center gap-2">
              <span class="text-xs font-extrabold text-slate-500">Filter Status:</span>
              <button 
                @click="assignmentFilter = 'all'"
                :class="['px-3 py-1 rounded-xl text-xs font-extrabold transition-all', assignmentFilter === 'all' ? 'bg-[#005506] text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200']"
              >
                All Tasks
              </button>
              <button 
                @click="assignmentFilter = 'pending'"
                :class="['px-3 py-1 rounded-xl text-xs font-extrabold transition-all', assignmentFilter === 'pending' ? 'bg-[#005506] text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200']"
              >
                Pending
              </button>
              <button 
                @click="assignmentFilter = 'graded'"
                :class="['px-3 py-1 rounded-xl text-xs font-extrabold transition-all', assignmentFilter === 'graded' ? 'bg-[#005506] text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200']"
              >
                Graded
              </button>
            </div>

            <span class="text-xs font-semibold text-slate-400">
              Showing {{ filteredAssignments.length }} of {{ assignmentsList.length }} items
            </span>
          </div>

          <!-- ASSIGNMENTS CARDS GRID -->
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div 
              v-for="item in filteredAssignments" 
              :key="item.id"
              class="bg-[#fbfdf9] rounded-3xl p-6 shadow-xs border border-slate-200/80 flex flex-col justify-between hover:border-[#005506] hover:shadow-md transition-all group relative"
            >
              <div class="space-y-4">
                
                <!-- TOP META & BADGES -->
                <div class="flex items-start justify-between gap-2">
                  <span class="text-[10px] font-black uppercase tracking-wider px-3 py-1 rounded-full bg-slate-100 text-slate-700">
                    {{ item.type || 'Homework' }}
                  </span>

                  <!-- Status Badge -->
                  <span 
                    v-if="item.status === 'Graded'" 
                    class="px-3 py-1 rounded-full text-[10px] font-extrabold border border-emerald-600 text-emerald-800 bg-emerald-50 shrink-0 shadow-2xs"
                  >
                    Graded
                  </span>
                  <span 
                    v-else-if="item.status === 'Submitted'" 
                    class="px-3 py-1 rounded-full text-[10px] font-extrabold border border-blue-400 text-blue-800 bg-blue-50 shrink-0 shadow-2xs"
                  >
                    Submitted
                  </span>
                  <span 
                    v-else-if="item.status === 'Pending'" 
                    class="px-3 py-1 rounded-full text-[10px] font-extrabold border border-amber-400 text-amber-800 bg-amber-50 shrink-0 shadow-2xs"
                  >
                    Pending
                  </span>
                  <span 
                    v-else-if="item.status === 'Missed'" 
                    class="px-3 py-1 rounded-full text-[10px] font-extrabold border border-rose-400 text-rose-800 bg-rose-50 shrink-0 shadow-2xs"
                  >
                    Missed
                  </span>
                </div>

                <!-- TITLE & DESCRIPTION -->
                <div class="space-y-1.5">
                  <h4 class="font-extrabold text-slate-900 text-base leading-snug group-hover:text-[#005506] transition-colors">
                    {{ item.title }}
                  </h4>
                  <p class="text-xs text-slate-500 font-medium line-clamp-2">
                    {{ item.instructions || 'Submit required documentation and answers prior to the cutoff date.' }}
                  </p>
                </div>

                <!-- DUE DATE BOX -->
                <div class="flex items-center gap-2 bg-slate-100/70 p-2.5 rounded-2xl text-xs text-slate-600 font-semibold">
                  <span class="text-base">📅</span>
                  <div>
                    <span class="block text-[10px] font-extrabold text-slate-400 uppercase">Deadline</span>
                    <span>{{ item.dueDate }}</span>
                  </div>
                </div>

              </div>

              <!-- CARD FOOTER & ACTIONS -->
              <div class="pt-5 border-t border-slate-200/80 flex items-center justify-between mt-4">
                <div>
                  <span class="text-[10px] font-extrabold text-slate-400 uppercase block">Grade</span>
                  <span class="text-xs font-black text-slate-900">
                    {{ item.grade !== null ? item.grade : '--' }} / {{ item.totalPoints }}
                  </span>
                </div>

                <!-- Primary Action Button -->
                <button 
                  @click="openSubmitModal(item)"
                  :class="[
                    'px-4 py-2 text-xs font-extrabold rounded-xl transition-all shadow-2xs active:scale-95',
                    item.status === 'Graded' ? 'bg-slate-200 text-slate-700 hover:bg-slate-300' : 'bg-[#005506] text-white hover:bg-[#003805]'
                  ]"
                >
                  {{ item.status === 'Graded' ? 'View Grade' : item.status === 'Submitted' ? 'Edit Work' : 'Submit Work' }}
                </button>
              </div>

            </div>
          </div>

        </div>

        <!-- TAB CONTENT 3: MATERIALS & RESOURCES WITH WORKING LINKS -->
        <div v-if="activeTab === 'materials'" class="space-y-6 animate-fade-in">
          
          <!-- Section A: PRESENTATIONS & HANDOUTS WITH DIRECT DOWNLOAD LINKS -->
          <div class="bg-[#fbfdf9] rounded-3xl p-6 border border-slate-200/80 shadow-xs space-y-4">
            <div class="flex items-center justify-between">
              <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-xl bg-emerald-100 text-[#005506] flex items-center justify-center font-bold">
                  📚
                </div>
                <h3 class="font-extrabold text-slate-800 text-base uppercase tracking-wider">
                  Course Modules & Lecture Decks
                </h3>
              </div>
              <span class="text-xs font-semibold text-slate-400">Click to view/download</span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
              <a 
                v-for="resource in classResources.documents" 
                :key="resource.id"
                :href="resource.url"
                target="_blank"
                rel="noopener noreferrer"
                class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-xs hover:border-[#005506] hover:shadow-md transition-all group flex items-start justify-between gap-3"
              >
                <div class="flex items-start gap-3">
                  <div class="w-10 h-10 bg-emerald-50 rounded-xl flex items-center justify-center shrink-0 group-hover:bg-[#005506] group-hover:text-white transition-colors text-emerald-800">
                    <span v-if="resource.type === 'PPT'" class="font-black text-xs">PPT</span>
                    <span v-else class="font-black text-xs">PDF</span>
                  </div>
                  <div>
                    <span class="text-xs font-extrabold text-slate-900 group-hover:text-[#005506] transition-colors block">
                      {{ resource.title }}
                    </span>
                    <span class="text-[11px] text-slate-500 font-medium block mt-0.5">
                      {{ resource.description }}
                    </span>
                    <span class="text-[10px] text-slate-400 font-semibold block mt-1">
                      {{ resource.size }} • Updated {{ resource.date }}
                    </span>
                  </div>
                </div>

                <div class="text-slate-400 group-hover:text-[#005506] group-hover:translate-x-0.5 transition-all pt-1">
                  ↗
                </div>
              </a>
            </div>
          </div>

          <!-- Section B: VIDEO LECTURES & RECORDINGS WITH EXTERNAL LINKS -->
          <div class="bg-[#fbfdf9] rounded-3xl p-6 border border-slate-200/80 shadow-xs space-y-4">
            <div class="flex items-center justify-between">
              <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-xl bg-amber-100 text-amber-800 flex items-center justify-center font-bold">
                  🎬
                </div>
                <h3 class="font-extrabold text-slate-800 text-base uppercase tracking-wider">
                  Recorded Video Lectures & Links
                </h3>
              </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
              <a 
                v-for="video in classResources.videos" 
                :key="video.id"
                :href="video.url"
                target="_blank"
                rel="noopener noreferrer"
                class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-xs hover:border-[#005506] hover:shadow-md transition-all group flex items-center gap-4"
              >
                <div class="w-14 h-12 bg-amber-50 rounded-xl flex items-center justify-center shrink-0 group-hover:bg-[#005506] transition-colors">
                  <div class="w-8 h-8 rounded-full bg-white text-[#005506] flex items-center justify-center shadow-xs group-hover:text-[#005506]">
                    <svg class="w-4 h-4 ml-0.5" fill="currentColor" viewBox="0 0 24 24">
                      <path d="M8 5v14l11-7z"/>
                    </svg>
                  </div>
                </div>
                <div class="flex-1 min-w-0">
                  <span class="text-xs font-extrabold text-slate-900 group-hover:text-[#005506] transition-colors block truncate">
                    {{ video.title }}
                  </span>
                  <span class="text-[11px] text-slate-500 font-medium block truncate">
                    {{ video.duration }} • {{ video.platform }}
                  </span>
                </div>
                <span class="text-xs font-bold text-[#005506] opacity-0 group-hover:opacity-100 transition-opacity">
                  Watch
                </span>
              </a>
            </div>
          </div>

        </div>

        <!-- TAB CONTENT 4: GRADES OVERVIEW & DETAILED TABLE (RESTORED) -->
        <div v-if="activeTab === 'grades'" class="space-y-6 animate-fade-in">
          
          <!-- METRIC HIGHLIGHT STAT CARDS -->
          <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            
            <div class="bg-[#fbfdf9] rounded-3xl p-5 border border-slate-200/80 shadow-xs flex items-center gap-4">
              <div class="w-12 h-12 rounded-2xl bg-emerald-100 text-[#005506] flex items-center justify-center text-xl font-bold shrink-0">
                🏆
              </div>
              <div>
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Overall Average</p>
                <div class="flex items-baseline gap-2">
                  <span class="text-2xl font-black text-slate-900">{{ cumulativePercentage }}%</span>
                  <span class="text-xs font-bold text-emerald-700 bg-emerald-100 px-2 py-0.5 rounded-md">
                    {{ parseFloat(cumulativePercentage) >= 75 ? 'Passing' : 'Needs Work' }}
                  </span>
                </div>
              </div>
            </div>

            <div class="bg-[#fbfdf9] rounded-3xl p-5 border border-slate-200/80 shadow-xs flex items-center gap-4">
              <div class="w-12 h-12 rounded-2xl bg-teal-100 text-teal-800 flex items-center justify-center text-xl font-bold shrink-0">
                🎯
              </div>
              <div>
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Accumulated Score</p>
                <p class="text-2xl font-black text-slate-900">
                  {{ totalEarnedPoints }} <span class="text-xs font-semibold text-slate-400">/ {{ totalMaxPoints }} pts</span>
                </p>
              </div>
            </div>

            <div class="bg-[#fbfdf9] rounded-3xl p-5 border border-slate-200/80 shadow-xs flex items-center gap-4">
              <div class="w-12 h-12 rounded-2xl bg-amber-100 text-amber-800 flex items-center justify-center text-xl font-bold shrink-0">
                📝
              </div>
              <div>
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Completed Tasks</p>
                <p class="text-2xl font-black text-slate-900">
                  {{ assignmentsList.filter(a => a.status === 'Graded' || a.status === 'Submitted').length }}
                  <span class="text-xs font-semibold text-slate-400">/ {{ assignmentsList.length }} tasks</span>
                </p>
              </div>
            </div>

          </div>

          <!-- DETAILED GRADES DATA TABLE -->
          <div class="bg-[#fbfdf9] rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden">
            <div class="p-5 border-b border-slate-200/80 flex items-center justify-between">
              <div>
                <h3 class="font-extrabold text-slate-900 text-base">Gradebook Breakdown</h3>
                <p class="text-xs text-slate-500 font-medium">Individual assignment scores and feedback status.</p>
              </div>
            </div>

            <div class="overflow-x-auto">
              <table class="w-full text-left text-xs">
                <thead class="bg-slate-100/70 uppercase text-slate-500 font-extrabold tracking-wider border-b border-slate-200">
                  <tr>
                    <th class="py-3.5 px-6">Assessment Title</th>
                    <th class="py-3.5 px-4">Type</th>
                    <th class="py-3.5 px-4">Deadline</th>
                    <th class="py-3.5 px-4 text-center">Status</th>
                    <th class="py-3.5 px-6 text-right">Score / Max</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-slate-200/60 font-medium text-slate-700">
                  <tr 
                    v-for="item in assignmentsList" 
                    :key="`grade-${item.id}`"
                    class="hover:bg-emerald-50/40 transition-colors"
                  >
                    <td class="py-4 px-6 font-bold text-slate-900">
                      {{ item.title }}
                    </td>
                    <td class="py-4 px-4">
                      <span class="bg-slate-200/70 text-slate-700 px-2.5 py-1 rounded-md text-[10px] font-extrabold">
                        {{ item.type || 'Assessment' }}
                      </span>
                    </td>
                    <td class="py-4 px-4 text-slate-500">
                      {{ item.dueDate }}
                    </td>
                    <td class="py-4 px-4 text-center">
                      <span 
                        v-if="item.status === 'Graded'" 
                        class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold border border-emerald-600 text-emerald-800 bg-emerald-50"
                      >
                        Graded
                      </span>
                      <span 
                        v-else-if="item.status === 'Submitted'" 
                        class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold border border-blue-400 text-blue-800 bg-blue-50"
                      >
                        Submitted
                      </span>
                      <span 
                        v-else-if="item.status === 'Pending'" 
                        class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold border border-amber-400 text-amber-800 bg-amber-50"
                      >
                        Pending
                      </span>
                      <span 
                        v-else 
                        class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold border border-rose-400 text-rose-800 bg-rose-50"
                      >
                        Missed
                      </span>
                    </td>
                    <td class="py-4 px-6 text-right font-black text-slate-900">
                      <span v-if="item.grade !== null" class="text-[#005506] text-sm">{{ item.grade }}</span>
                      <span v-else class="text-slate-400">--</span>
                      <span class="text-slate-400 text-[10px] font-normal"> / {{ item.totalPoints }}</span>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>

        </div>

      </div>

    </main>

    <!-- MODAL 1: VIEW ANNOUNCEMENT DETAIL -->
    <div 
      v-if="selectedAnnouncement" 
      class="fixed inset-0 z-50 bg-slate-950/60 backdrop-blur-xs flex items-center justify-center p-4 animate-fade-in"
      @click.self="selectedAnnouncement = null"
    >
      <div class="bg-white rounded-3xl max-w-xl w-full p-6 sm:p-8 space-y-6 shadow-2xl border border-slate-100">
        <div class="flex items-start justify-between gap-4">
          <div class="space-y-1">
            <span class="text-[10px] font-extrabold uppercase bg-emerald-100 text-[#005506] px-3 py-1 rounded-full">
              {{ selectedAnnouncement.category || 'Announcement' }}
            </span>
            <h3 class="font-extrabold text-slate-900 text-xl pt-2">{{ selectedAnnouncement.title }}</h3>
            <p class="text-xs text-slate-400 font-semibold">Posted on {{ selectedAnnouncement.date }}</p>
          </div>
          <button @click="selectedAnnouncement = null" class="text-slate-400 hover:text-slate-700 text-lg font-bold">✕</button>
        </div>

        <div class="text-slate-700 text-xs sm:text-sm leading-relaxed space-y-3 bg-slate-50 p-4 rounded-2xl border border-slate-200/60">
          <p>{{ selectedAnnouncement.content }}</p>
        </div>

        <div class="flex items-center justify-between pt-2">
          <div class="flex items-center gap-2">
            <div class="w-8 h-8 rounded-full bg-[#005506] text-white text-xs flex items-center justify-center font-bold">
              {{ selectedAnnouncement.postedBy.charAt(0) }}
            </div>
            <span class="text-xs font-extrabold text-slate-800">{{ selectedAnnouncement.postedBy }}</span>
          </div>

          <button 
            @click="selectedAnnouncement = null" 
            class="px-5 py-2 bg-[#005506] text-white text-xs font-bold rounded-xl"
          >
            Close Notice
          </button>
        </div>
      </div>
    </div>

    <!-- MODAL 2: SUBMIT ASSIGNMENT MODAL -->
    <div 
      v-if="selectedAssignment" 
      class="fixed inset-0 z-50 bg-slate-950/60 backdrop-blur-xs flex items-center justify-center p-4 animate-fade-in"
      @click.self="selectedAssignment = null"
    >
      <div class="bg-white rounded-3xl max-w-xl w-full p-6 sm:p-8 space-y-6 shadow-2xl border border-slate-100">
        <div class="flex items-start justify-between gap-4">
          <div class="space-y-1">
            <span class="text-[10px] font-extrabold uppercase bg-amber-100 text-amber-800 px-3 py-1 rounded-full">
              Task Submission
            </span>
            <h3 class="font-extrabold text-slate-900 text-xl pt-2">{{ selectedAssignment.title }}</h3>
            <p class="text-xs text-slate-400 font-semibold">Deadline: {{ selectedAssignment.dueDate }}</p>
          </div>
          <button @click="selectedAssignment = null" class="text-slate-400 hover:text-slate-700 text-lg font-bold">✕</button>
        </div>

        <!-- Grade Display if already graded -->
        <div v-if="selectedAssignment.status === 'Graded'" class="bg-emerald-50 border border-emerald-200 rounded-2xl p-4 space-y-1">
          <span class="text-xs font-extrabold text-emerald-800 block">Assessment Score</span>
          <p class="text-2xl font-black text-[#005506]">
            {{ selectedAssignment.grade }} / {{ selectedAssignment.totalPoints }}
          </p>
          <p class="text-xs text-emerald-700 italic font-medium">Instructor Feedback: "Great historical perspective and well-structured argument."</p>
        </div>

        <!-- Submission Form -->
        <form @submit.prevent="submitWork" class="space-y-4">
          <div class="space-y-1.5">
            <label class="text-xs font-extrabold text-slate-700">Submission Text / Notes</label>
            <textarea 
              v-model="submissionForm.comments" 
              rows="3" 
              placeholder="Add details, link to repository, or response notes..."
              class="w-full text-xs p-3 rounded-2xl border border-slate-200 focus:ring-2 focus:ring-[#005506] outline-none"
            ></textarea>
          </div>

          <div class="space-y-1.5">
            <label class="text-xs font-extrabold text-slate-700">Attach Document (PDF, DOCX)</label>
            <input 
              type="file" 
              @change="handleFileUpload"
              class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-emerald-50 file:text-[#005506] hover:file:bg-emerald-100 cursor-pointer"
            />
          </div>

          <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
            <button 
              type="button" 
              @click="selectedAssignment = null"
              class="px-4 py-2 text-xs font-bold text-slate-500 hover:text-slate-800"
            >
              Cancel
            </button>
            <button 
              type="submit" 
              :disabled="submissionForm.processing"
              class="px-5 py-2.5 bg-[#005506] hover:bg-[#003805] text-white text-xs font-extrabold rounded-xl transition-all shadow-xs"
            >
              {{ submissionForm.processing ? 'Submitting...' : 'Confirm Submission' }}
            </button>
          </div>
        </form>

      </div>
    </div>

  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { Head, Link, useForm } from '@inertiajs/vue3'
import Sidebar from '@/Components/Sidebar.vue'

const props = defineProps({
  classroom: {
    type: Object,
    default: () => ({
      id: 1,
      subject: 'The Work of Rizal',
      teacher: 'Kaylin Leffler',
    }),
  },
  announcements: Array,
  assignments: Array,
  resources: Object,
  isTeacher: {
    type: Boolean,
    default: false,
  }
})

// Active tab state
const activeTab = ref('announcements')
const assignmentFilter = ref('all')

// Modals State
const selectedAnnouncement = ref(null)
const selectedAssignment = ref(null)

// Inertia Submission Form
const submissionForm = useForm({
  assignment_id: null,
  file: null,
  comments: '',
})

// Data Fallbacks
const announcementsList = ref(props.announcements?.length ? props.announcements : [
  { 
    id: 1, 
    title: 'Midterm Examination Schedule & Guidelines', 
    content: 'The Midterm examination for Rizal Course will take place next Friday. Please review all PowerPoint decks from Week 1 to Week 5.', 
    date: 'Oct 24, 2026', 
    postedBy: 'Kaylin Leffler',
    category: 'Exam Notice',
    isPinned: true,
  },
  { 
    id: 2, 
    title: 'Group Project Orientation - Noli Me Tangere Analysis', 
    content: 'Form groups of 5 for the final term presentation on Noli Me Tangere. Submission details are posted under assignments.', 
    date: 'Oct 20, 2026', 
    postedBy: 'Kaylin Leffler',
    category: 'Project',
    isPinned: false,
  },
  { 
    id: 3, 
    title: 'Consultation Hours Updated for November', 
    content: 'Office hours are shifted to Thursdays 2:00 PM - 4:00 PM via Google Meet room.', 
    date: 'Oct 15, 2026', 
    postedBy: 'Kaylin Leffler',
    category: 'Schedule',
    isPinned: false,
  },
])

const assignmentsList = ref(props.assignments?.length ? props.assignments : [
  { id: 1, title: 'Reflection Paper 1: Youth of Calamba', dueDate: '10/28/2026, 11:59 PM', totalPoints: '100.00', grade: '98.00', status: 'Graded', type: 'Essay' },
  { id: 2, title: 'Analysis of Noli Me Tangere Characters', dueDate: '11/02/2026, 11:59 PM', totalPoints: '100.00', grade: null, status: 'Pending', type: 'Case Study' },
  { id: 3, title: 'Quiz 2: Rizal in Europe and Dapitan', dueDate: '10/15/2026, 11:59 PM', totalPoints: '50.00', grade: '0.00', status: 'Missed', type: 'Quiz' },
  { id: 4, title: 'Final Synthesis Paper Outline', dueDate: '11/12/2026, 11:59 PM', totalPoints: '100.00', grade: null, status: 'Submitted', type: 'Project' },
])

const classResources = ref(props.resources || {
  documents: [
    { id: 1, title: 'Week 1: Introduction to Rizal Law', description: 'RA 1425 History & Context', size: '3.4 MB', date: 'Oct 02, 2026', type: 'PPT', url: '/storage/materials/week1-rizal-law.pptx' },
    { id: 2, title: 'Week 2: 19th Century Philippines', description: 'Economic & Social Conditions', size: '2.1 MB', date: 'Oct 09, 2026', type: 'PDF', url: '/storage/materials/week2-19th-century.pdf' },
    { id: 3, title: 'Week 3: Noli Me Tangere Reading Guide', description: 'Key Themes & Symbolisms', size: '1.8 MB', date: 'Oct 16, 2026', type: 'PDF', url: '/storage/materials/week3-noli-guide.pdf' },
  ],
  videos: [
    { id: 1, title: 'Lecture Stream: Rizal in Europe', duration: '45 mins', platform: 'Panopto Video', url: 'https://youtube.com' },
    { id: 2, title: 'Documentary: Rizal\'s Last Days', duration: '28 mins', platform: 'YouTube', url: 'https://youtube.com' },
  ]
})

// Computed Filters
const filteredAssignments = computed(() => {
  if (assignmentFilter.value === 'pending') {
    return assignmentsList.value.filter(a => a.status === 'Pending' || a.status === 'Submitted')
  }
  if (assignmentFilter.value === 'graded') {
    return assignmentsList.value.filter(a => a.status === 'Graded')
  }
  return assignmentsList.value
})

// Modal Handlers
const openAnnouncementModal = (item) => {
  selectedAnnouncement.value = item
}

const openSubmitModal = (item) => {
  selectedAssignment.value = item
  submissionForm.assignment_id = item.id
}

const handleFileUpload = (e) => {
  submissionForm.file = e.target.files[0]
}

const submitWork = () => {
  if (selectedAssignment.value) {
    const found = assignmentsList.value.find(a => a.id === selectedAssignment.value.id)
    if (found && found.status !== 'Graded') {
      found.status = 'Submitted'
    }
  }
  selectedAssignment.value = null
  submissionForm.reset()
}

// Grades Computation
const totalEarnedPoints = computed(() => {
  return assignmentsList.value
    .reduce((acc, curr) => acc + (curr.grade ? parseFloat(curr.grade) : 0), 0)
    .toFixed(2)
})

const totalMaxPoints = computed(() => {
  return assignmentsList.value
    .filter(a => a.status === 'Graded' || a.status === 'Missed')
    .reduce((acc, curr) => acc + parseFloat(curr.totalPoints), 0)
    .toFixed(2)
})

const cumulativePercentage = computed(() => {
  const max = parseFloat(totalMaxPoints.value)
  if (!max) return '0.0'
  return ((parseFloat(totalEarnedPoints.value) / max) * 100).toFixed(1)
})
</script>

<style scoped>

/* REVERSE ANIMATED FILL-STROKE TEXT EFFECT FOR 'SUBJECTS' */
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

.animate-fade-in {
  animation: fadeIn 0.35s ease-in-out both;
}

@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(6px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}
</style>