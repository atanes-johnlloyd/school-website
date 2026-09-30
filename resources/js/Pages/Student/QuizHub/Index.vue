<template>
  <Head title="QuizHub - Salawag LMS" />

  <div class="min-h-screen flex bg-[#e8f5e9] font-['Inter'] relative select-none">
    
    <!-- STICKY SIDEBAR (Hidden in Active Lockdown) -->
    <div v-if="viewMode !== 'taking'" class="sticky top-0 h-screen z-30 shrink-0">
      <Sidebar />
    </div>

    <!-- MAIN WORKSPACE -->
    <main class="flex-1 relative overflow-y-auto min-h-screen flex flex-col justify-between">
      
      <!-- ========================================================================= -->
      <!-- VIEW 1: QUIZ HUB DASHBOARD -->
      <!-- ========================================================================= -->
      <div v-if="viewMode === 'list'" class="relative z-10 p-6 md:p-8 space-y-6 flex-1 pb-16">
        
        <!-- HERO BANNER -->
        <div class="w-full bg-[#004d08] text-white rounded-3xl p-6 sm:p-8 shadow-md border border-[#003805] relative flex flex-col gap-4 overflow-hidden">
          <img :src="heroImage" alt="" class="absolute inset-0 h-full w-full object-cover object-center" aria-hidden="true" />
          <div class="absolute inset-0 bg-[#004d08]/90" aria-hidden="true"></div>

          <div class="relative z-10 space-y-1">
            <div class="font-['Anton'] text-3xl sm:text-4xl md:text-5xl tracking-wide uppercase flex items-center gap-2 select-none">
              <span class="text-white">SALAWAG</span>
              <span class="animated-stroke-text">QUIZHUB</span>
            </div>
            <p class="text-xs sm:text-sm italic font-medium text-emerald-100/80">
              "Master your domain through proctored evaluations and interactive problem-solving."
            </p>
            <div class="flex items-center gap-2 pt-1 max-w-xl">
              <div class="h-[1.5px] w-full bg-white/40"></div>
              <span class="text-white text-xs">★</span>
            </div>
          </div>

          <div class="relative z-10 grid grid-cols-1 lg:grid-cols-12 gap-5 items-center pt-2">
            <div class="lg:col-span-7 flex flex-col sm:flex-row items-center gap-4">
              <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-2xl border-2 border-white/20 bg-white/10 overflow-hidden shrink-0 shadow-inner flex items-center justify-center text-3xl">
                ⚡
              </div>
              <div class="space-y-1 text-center sm:text-left">
                <div class="inline-block bg-white/15 backdrop-blur-sm text-emerald-100 text-[11px] font-bold px-3 py-0.5 rounded-full border border-white/10">
                  Proctored Engine Active
                </div>
                <h2 class="font-bold text-lg sm:text-xl text-white tracking-tight leading-snug">
                  Active & Scheduled Assessments
                </h2>
                <p class="text-xs text-emerald-100/70 font-medium">
                  Grade 12 STEM • Section Rizal
                </p>
              </div>
            </div>

            <div class="lg:col-span-5 grid grid-cols-2 gap-3">
              <div class="bg-white/10 backdrop-blur-md border border-white/15 rounded-2xl py-3 px-4 flex flex-col justify-between">
                <span class="text-[11px] font-medium text-emerald-100/80">Active Quizzes</span>
                <div class="text-xl font-extrabold text-amber-300 my-0.5">{{ availableQuizzesCount }} Ready</div>
                <span class="text-[10px] text-emerald-100/70">Anti-Cheat Enabled</span>
              </div>
              <div class="bg-white/10 backdrop-blur-md border border-white/15 rounded-2xl py-3 px-4 flex flex-col justify-between">
                <span class="text-[11px] font-medium text-emerald-100/80">Hint System</span>
                <div class="text-xl font-extrabold text-white my-0.5">5 Challenge Games</div>
                <span class="text-[10px] text-emerald-100/70">1 Attempt per Puzzle</span>
              </div>
            </div>
          </div>
        </div>

        <!-- MAIN QUIZ LIST CONTAINER -->
        <div class="rounded-3xl bg-[#fbfdf9] border border-slate-200/80 p-6 sm:p-8 shadow-sm space-y-6">
          
          <div class="flex flex-wrap items-center justify-between gap-3 pb-4 border-b border-slate-200/60">
            <div class="flex items-center gap-1.5 bg-[#f5f7f2] p-1.5 rounded-2xl border border-slate-200/80 overflow-x-auto">
              <button 
                v-for="tab in filterTabs" 
                :key="tab.id"
                @click="activeTab = tab.id"
                :class="[
                  'px-3.5 py-1.5 rounded-xl text-xs font-extrabold transition-all shrink-0 flex items-center gap-1.5 cursor-pointer',
                  activeTab === tab.id ? 'bg-[#004d08] text-white shadow-xs' : 'text-slate-700 hover:text-slate-900'
                ]"
              >
                <span>{{ tab.label }}</span>
                <span :class="['text-[10px] px-1.5 py-0.2 rounded-full font-black', activeTab === tab.id ? 'bg-white/20 text-white' : 'bg-slate-200 text-slate-700']">
                  {{ getQuizCountByStatus(tab.id) }}
                </span>
              </button>
            </div>

            <div class="relative w-full sm:w-72">
              <span class="absolute inset-y-0 left-3 flex items-center text-slate-400 text-xs">🔍</span>
              <input 
                v-model="searchQuery" 
                type="text" 
                placeholder="Search quiz topic or subject..." 
                class="w-full bg-[#f5f7f2] border border-slate-200/80 rounded-2xl pl-8 pr-8 py-2 text-xs font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#004d08]"
              />
            </div>
          </div>

          <!-- QUIZ CARDS -->
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
            <div 
              v-for="quiz in filteredQuizzes" 
              :key="quiz.id"
              class="bg-[#f5f7f2] border border-slate-200/80 rounded-3xl p-5 hover:border-slate-300 transition-all duration-300 flex flex-col justify-between space-y-4 group relative"
            >
              <div class="space-y-3">
                <div class="flex items-center justify-between">
                  <span class="text-[10px] font-extrabold uppercase text-[#004d08] bg-white px-2.5 py-0.5 rounded-md border border-slate-200">
                    {{ quiz.subject }}
                  </span>
                  <span 
                    :class="[
                      'text-[10px] font-black uppercase px-2.5 py-0.5 rounded-full border',
                      quiz.status === 'available' ? 'bg-emerald-100 text-emerald-800 border-emerald-200' :
                      quiz.status === 'upcoming' ? 'bg-amber-100 text-amber-800 border-amber-200' : 'bg-slate-200 text-slate-700 border-slate-300'
                    ]"
                  >
                    {{ quiz.status }}
                  </span>
                </div>

                <div>
                  <h3 class="font-extrabold text-slate-900 text-base leading-snug group-hover:text-[#004d08] transition-colors">
                    {{ quiz.title }}
                  </h3>
                  <p class="text-xs text-slate-500 line-clamp-2 mt-1">{{ quiz.description }}</p>
                </div>

                <div class="grid grid-cols-2 gap-2 text-xs font-semibold text-slate-600 bg-white p-3 rounded-2xl border border-slate-200/60">
                  <div>⏱️ {{ quiz.timeLimitMinutes }} Mins</div>
                  <div>❓ {{ quiz.questions.length }} Items</div>
                  <div>💡 {{ quiz.hintTokens }} Hint Tokens</div>
                  <div>⭐ {{ quiz.totalPoints }} Points</div>
                </div>
              </div>

              <div>
                <button 
                  v-if="quiz.status === 'available'"
                  @click="startQuizPrep(quiz)"
                  class="w-full bg-[#004d08] hover:bg-[#003805] text-white text-xs font-black py-3 rounded-2xl transition-all shadow-xs flex items-center justify-center gap-2 cursor-pointer"
                >
                  <span>🚀 Start Assessment</span>
                </button>

                <button 
                  v-else-if="quiz.status === 'completed'"
                  @click="viewResults(quiz)"
                  class="w-full bg-white hover:bg-slate-100 text-slate-800 border border-slate-300 text-xs font-extrabold py-3 rounded-2xl transition-all flex items-center justify-center gap-2 cursor-pointer"
                >
                  <span>📊 Review Score ({{ quiz.result?.score }}/{{ quiz.totalPoints }})</span>
                </button>

                <button 
                  v-else-if="quiz.status === 'upcoming'"
                  disabled
                  class="w-full bg-slate-200 text-slate-500 text-xs font-extrabold py-3 rounded-2xl cursor-not-allowed text-center block"
                >
                  Unlocks {{ quiz.unlockDate }}
                </button>
              </div>
            </div>
          </div>

        </div>
      </div>

      <!-- ========================================================================= -->
      <!-- VIEW 2: ACTIVE QUIZ ENGINE (PROCTORED LOCKDOWN) -->
      <!-- ========================================================================= -->
      <div v-else-if="viewMode === 'taking'" class="fixed inset-0 z-50 bg-slate-900 text-white flex flex-col justify-between overflow-y-auto p-4 sm:p-8">
        
        <!-- HEADER / TELEMETRY -->
        <div class="max-w-5xl w-full mx-auto flex flex-wrap items-center justify-between gap-4 pb-4 border-b border-slate-800 shrink-0">
          <div class="flex items-center gap-3">
            <span class="w-3 h-3 rounded-full bg-emerald-500 animate-ping"></span>
            <div>
              <h2 class="font-extrabold text-lg sm:text-xl text-white">{{ activeQuiz.title }}</h2>
              <p class="text-xs text-slate-400 font-mono">{{ activeQuiz.subject }} • Proctored Assessment</p>
            </div>
          </div>

          <div class="flex items-center gap-3">
            <!-- HINT TOKENS DISPLAY -->
            <div class="flex items-center gap-1.5 bg-amber-950/80 border border-amber-800 px-3 py-1.5 rounded-xl">
              <span class="text-xs font-extrabold text-amber-300">💡 Hints Remaining:</span>
              <span class="text-xs font-black text-white bg-amber-600 px-2 py-0.5 rounded-md">{{ activeQuiz.hintTokens }}</span>
            </div>

            <!-- WARNING STRIKES -->
            <div class="flex items-center gap-1.5 bg-rose-950/80 border border-rose-800 px-3 py-1.5 rounded-xl">
              <span class="text-xs font-extrabold text-rose-300 uppercase">Warnings:</span>
              <div class="flex gap-1">
                <span 
                  v-for="n in 3" 
                  :key="n"
                  :class="['w-2.5 h-2.5 rounded-full', n <= warningsCount ? 'bg-rose-500 animate-pulse' : 'bg-slate-700']"
                ></span>
              </div>
            </div>

            <!-- TIMER -->
            <div :class="['px-4 py-1.5 rounded-xl font-mono text-sm font-black border', timeLeftSeconds < 180 ? 'bg-rose-600 text-white border-rose-400 animate-pulse' : 'bg-slate-800 text-amber-300 border-slate-700']">
              ⏱️ {{ formattedTimeLeft }}
            </div>
          </div>
        </div>

        <!-- PROGRESS BAR -->
        <div class="max-w-5xl w-full mx-auto my-3 shrink-0">
          <div class="flex justify-between text-xs text-slate-400 font-bold mb-1.5">
            <span>Question {{ currentQuestionIndex + 1 }} of {{ activeQuiz.questions.length }}</span>
            <span>{{ progressPercentage }}% Completed</span>
          </div>
          <div class="w-full bg-slate-800 h-2 rounded-full overflow-hidden">
            <div class="bg-emerald-500 h-full transition-all duration-300" :style="{ width: `${progressPercentage}%` }"></div>
          </div>
        </div>

        <!-- QUESTION CARD CANVAS -->
        <div class="max-w-3xl w-full mx-auto bg-slate-800/90 border border-slate-700/80 rounded-3xl p-6 sm:p-8 my-auto shadow-2xl space-y-6">
          <div class="flex items-start justify-between gap-4">
            <div class="space-y-1">
              <span class="text-xs font-black uppercase text-emerald-400 tracking-wider">ITEM {{ currentQuestionIndex + 1 }}</span>
              <h3 class="text-lg sm:text-xl font-bold leading-relaxed text-slate-100">
                {{ currentQuestion.questionText }}
              </h3>
            </div>

            <!-- HINT GAME TRIGGER BUTTON -->
            <button 
              @click="openHintPuzzleModal(currentQuestionIndex)"
              :disabled="activeQuiz.hintTokens <= 0 || itemHintStates[currentQuestionIndex]"
              :class="[
                'px-3.5 py-2 rounded-xl text-xs font-bold transition-all shrink-0 flex items-center gap-1.5 cursor-pointer',
                itemHintStates[currentQuestionIndex] === 'won' 
                  ? 'bg-emerald-900/80 text-emerald-300 border border-emerald-600' 
                  : itemHintStates[currentQuestionIndex] === 'lost'
                    ? 'bg-rose-950/80 text-rose-300 border border-rose-700'
                    : activeQuiz.hintTokens > 0 
                      ? 'bg-amber-500 hover:bg-amber-400 text-slate-950 shadow-md' 
                      : 'bg-slate-700 text-slate-500 cursor-not-allowed'
              ]"
            >
              <span>💡</span>
              <span>
                {{ 
                  itemHintStates[currentQuestionIndex] === 'won' ? 'Hint Granted' : 
                  itemHintStates[currentQuestionIndex] === 'lost' ? 'Hint Failed' : 'Challenge for Hint' 
                }}
              </span>
            </button>
          </div>

          <!-- STATUS INDICATORS FOR HINT RESULTS -->
          <div v-if="itemHintStates[currentQuestionIndex] === 'won'" class="bg-emerald-950/90 border border-emerald-500 text-emerald-200 p-4 rounded-2xl text-xs space-y-1 animate-fade-in">
            <div class="flex items-center gap-1.5 text-emerald-400 font-extrabold">
              <span>🎉 PUZZLE SOLVED:</span>
              <span>Hint Unlocked!</span>
            </div>
            <p>The correct answer is Option <strong>{{ String.fromCharCode(65 + currentQuestion.correctAnswer) }}: {{ currentQuestion.options[currentQuestion.correctAnswer] }}</strong></p>
          </div>

          <div v-else-if="itemHintStates[currentQuestionIndex] === 'lost'" class="bg-rose-950/90 border border-rose-600 text-rose-200 p-4 rounded-2xl text-xs space-y-1 animate-fade-in">
            <div class="flex items-center gap-1.5 text-rose-400 font-extrabold">
              <span>❌ PUZZLE FAILED OR FORFEITED:</span>
            </div>
            <p>1 Hint Token was deducted. No answer key revealed for this question.</p>
          </div>

          <!-- ANSWER OPTIONS -->
          <div class="space-y-3">
            <button 
              v-for="(option, idx) in currentQuestion.options" 
              :key="idx"
              @click="userAnswers[currentQuestionIndex] = idx"
              :class="[
                'w-full text-left p-4 rounded-2xl border text-sm font-semibold transition-all flex items-center justify-between cursor-pointer',
                userAnswers[currentQuestionIndex] === idx 
                  ? 'bg-emerald-600 border-emerald-400 text-white shadow-lg translate-x-1' 
                  : 'bg-slate-900/60 border-slate-700 text-slate-300 hover:bg-slate-700/60'
              ]"
            >
              <span>{{ option }}</span>
              <span :class="['w-6 h-6 rounded-full border flex items-center justify-center text-xs font-bold', userAnswers[currentQuestionIndex] === idx ? 'border-white bg-white text-emerald-900' : 'border-slate-600']">
                {{ String.fromCharCode(65 + idx) }}
              </span>
            </button>
          </div>
        </div>

        <!-- NAVIGATION FOOTER -->
        <div class="max-w-5xl w-full mx-auto flex items-center justify-between gap-4 pt-4 border-t border-slate-800 shrink-0">
          <button 
            @click="currentQuestionIndex--"
            :disabled="currentQuestionIndex === 0"
            class="px-5 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-bold disabled:opacity-40 disabled:cursor-not-allowed transition-all cursor-pointer"
          >
            ← Previous
          </button>

          <button 
            v-if="currentQuestionIndex < activeQuiz.questions.length - 1"
            @click="currentQuestionIndex++"
            class="px-6 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold transition-all cursor-pointer"
          >
            Next Item →
          </button>
          <button 
            v-else
            @click="submitQuiz(false)"
            class="px-6 py-2.5 rounded-xl bg-amber-400 hover:bg-amber-500 text-slate-900 text-xs font-black transition-all cursor-pointer"
          >
            Submit Assessment
          </button>
        </div>

      </div>

      <!-- ========================================================================= -->
      <!-- VIEW 3: RESULTS & SCORE BREAKDOWN -->
      <!-- ========================================================================= -->
      <div v-else-if="viewMode === 'results'" class="relative z-10 p-6 md:p-8 space-y-6 flex-1 max-w-4xl mx-auto w-full">
        <div class="bg-[#fbfdf9] border border-slate-200/80 rounded-3xl p-6 sm:p-8 shadow-sm space-y-6">
          <div class="flex items-center justify-between border-b border-slate-200 pb-4">
            <div>
              <span class="text-xs font-extrabold uppercase text-[#004d08]">{{ activeQuiz.subject }}</span>
              <h2 class="text-xl sm:text-2xl font-black text-slate-900">{{ activeQuiz.title }}</h2>
            </div>
            <button 
              @click="viewMode = 'list'"
              class="px-4 py-2 bg-[#f5f7f2] hover:bg-slate-200 text-slate-800 rounded-xl text-xs font-bold transition-all cursor-pointer"
            >
              Back to QuizHub
            </button>
          </div>

          <div class="bg-[#004d08] text-white rounded-3xl p-6 sm:p-8 text-center space-y-3 relative overflow-hidden">
            <div class="text-4xl">🏆</div>
            <h3 class="text-xs font-extrabold uppercase tracking-widest text-emerald-200">Assessment Result</h3>
            <div class="text-4xl sm:text-5xl font-black text-amber-300">
              {{ activeQuizResult.score }} / {{ activeQuiz.totalPoints }}
            </div>
            <p class="text-xs text-emerald-100/80 max-w-sm mx-auto font-medium">
              Score Percentage: <strong>{{ Math.round((activeQuizResult.score / activeQuiz.totalPoints) * 100) }}%</strong> • {{ activeQuizResult.gradeText }}
            </p>
          </div>

          <div v-if="!activeQuiz.allowReview" class="bg-amber-50 border border-amber-200 text-amber-900 rounded-2xl p-4 text-xs font-semibold text-center">
            🔒 Detailed item review and answer key have been restricted by the instructor.
          </div>

          <div v-else class="space-y-4">
            <h3 class="font-extrabold text-slate-900 text-base border-b border-slate-200 pb-2">Item Breakdown & Explanations</h3>
            
            <div 
              v-for="(q, idx) in activeQuiz.questions" 
              :key="idx"
              class="bg-[#f5f7f2] border border-slate-200/80 rounded-2xl p-4 space-y-3"
            >
              <div class="flex items-start justify-between gap-2">
                <h4 class="font-bold text-slate-900 text-sm">
                  {{ idx + 1 }}. {{ q.questionText }}
                </h4>
                <span 
                  :class="[
                    'text-[10px] font-black px-2 py-0.5 rounded-md uppercase shrink-0',
                    activeQuizResult.userAnswers[idx] === q.correctAnswer ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800'
                  ]"
                >
                  {{ activeQuizResult.userAnswers[idx] === q.correctAnswer ? 'Correct' : 'Incorrect' }}
                </span>
              </div>

              <div class="text-xs space-y-1">
                <p class="text-slate-600">
                  Your Answer: <strong class="text-slate-900">{{ q.options[activeQuizResult.userAnswers[idx]] || 'Not Answered' }}</strong>
                </p>
                <p v-if="activeQuizResult.userAnswers[idx] !== q.correctAnswer" class="text-emerald-800 font-semibold">
                  Correct Answer: <strong>{{ q.options[q.correctAnswer] }}</strong>
                </p>
              </div>

              <div v-if="q.explanation" class="bg-white p-3 rounded-xl border border-slate-200/60 text-xs text-slate-600 font-medium">
                💡 <strong>Explanation:</strong> {{ q.explanation }}
              </div>
            </div>
          </div>
        </div>
      </div>

    </main>

    <!-- ========================================================================= -->
    <!-- MODAL: HINT MINI-GAMES / PUZZLES (5 RANDOMIZED TYPES) -->
    <!-- ========================================================================= -->
    <div v-if="showHintGameModal" class="fixed inset-0 z-50 bg-slate-950/85 backdrop-blur-md flex items-center justify-center p-4">
      <div class="bg-slate-900 border border-slate-700 text-white rounded-3xl max-w-md w-full p-6 space-y-5 shadow-2xl relative overflow-hidden">
        
        <!-- MODAL HEADER -->
        <div class="flex items-center justify-between border-b border-slate-800 pb-3">
          <div class="flex items-center gap-2">
            <span class="text-xl">🎮</span>
            <h3 class="font-black text-sm uppercase tracking-wide text-amber-300">
              Hint Challenge: {{ gameTitle }}
            </h3>
          </div>
          <!-- CLOSING MODAL = IMMEDIATE FORFEIT -->
          <button @click="forfeitHintGame" class="text-slate-400 hover:text-rose-400 text-sm font-extrabold cursor-pointer" title="Close and forfeit hint">✕</button>
        </div>

        <div class="bg-amber-950/40 border border-amber-800/60 p-3 rounded-2xl text-[11px] text-amber-200 font-medium space-y-1">
          <p>⏱️ Exam timer is running! <strong>1 attempt only</strong>. Closing or failing forfeits 1 Hint Token without revealing the answer.</p>
        </div>

        <!-- GAME 1: MEMORY MATCH (SINGLE-CHANCE INSTANT LOSS) -->
        <div v-if="currentGameType === 'memory'" class="space-y-4 text-center">
          <p class="text-xs text-slate-300 font-semibold">Match 2 identical icons! One wrong pair = Instant Loss.</p>
          <div class="grid grid-cols-4 gap-2">
            <button 
              v-for="(card, idx) in memoryCards" 
              :key="idx"
              @click="flipMemoryCard(idx)"
              :disabled="card.flipped"
              class="h-16 rounded-xl bg-slate-800 border border-slate-700 flex items-center justify-center text-2xl transition-all cursor-pointer hover:bg-slate-700 disabled:cursor-default"
            >
              {{ card.flipped ? card.icon : '❓' }}
            </button>
          </div>
        </div>

        <!-- GAME 2: SPEED UNSCRAMBLE -->
        <div v-else-if="currentGameType === 'unscramble'" class="space-y-4 text-center">
          <p class="text-xs text-slate-300 font-semibold">Unscramble this STEM term (1 attempt):</p>
          <div class="text-2xl font-black tracking-widest text-emerald-400 bg-slate-800 py-3 rounded-2xl border border-slate-700 uppercase">
            {{ unscrambleTarget.scrambled }}
          </div>
          <input 
            v-model="unscrambleInput" 
            type="text" 
            placeholder="Type your answer..." 
            class="w-full bg-slate-950 border border-slate-700 rounded-xl px-4 py-2.5 text-xs text-white text-center font-bold focus:outline-none focus:border-amber-400 uppercase"
            @keyup.enter="checkUnscramble"
          />
          <button @click="checkUnscramble" class="w-full bg-amber-400 hover:bg-amber-300 text-slate-950 font-black py-2.5 rounded-xl text-xs transition-all cursor-pointer">
            Submit Final Answer
          </button>
        </div>

        <!-- GAME 3: QUICK MATH MATRIX -->
        <div v-else-if="currentGameType === 'math'" class="space-y-4 text-center">
          <p class="text-xs text-slate-300 font-semibold">Solve the expression (1 attempt):</p>
          <div class="text-2xl font-black text-amber-300 bg-slate-800 py-3 rounded-2xl border border-slate-700">
            {{ mathProblem.question }}
          </div>
          <div class="grid grid-cols-2 gap-2">
            <button 
              v-for="(opt, idx) in mathProblem.options" 
              :key="idx"
              @click="checkMathAnswer(opt)"
              class="bg-slate-800 hover:bg-emerald-600 border border-slate-700 text-xs font-bold py-3 rounded-xl transition-all cursor-pointer"
            >
              {{ opt }}
            </button>
          </div>
        </div>

        <!-- GAME 4: COLOR CODE REACTION (SIMON SAYS SEQUENCE) -->
        <div v-else-if="currentGameType === 'simon'" class="space-y-4 text-center">
          <p class="text-xs text-slate-300 font-semibold">
            {{ simonStep === 'watch' ? 'Watch sequence closely...' : 'Repeat sequence by clicking colors!' }}
          </p>
          <div class="flex justify-center gap-3 my-2">
            <button 
              v-for="color in simonColors" 
              :key="color.id"
              @click="handleSimonClick(color.id)"
              :disabled="simonStep === 'watch'"
              :class="[
                'w-16 h-16 rounded-2xl border-2 transition-all cursor-pointer',
                color.bg,
                activeSimonColor === color.id ? 'scale-110 brightness-150 border-white shadow-lg' : 'border-slate-700 opacity-80'
              ]"
            ></button>
          </div>
        </div>

        <!-- GAME 5: PATTERN SEQUENCE COMPLETION -->
        <div v-else-if="currentGameType === 'sequence'" class="space-y-4 text-center">
          <p class="text-xs text-slate-300 font-semibold">What is the next number in this sequence?</p>
          <div class="text-2xl font-black text-emerald-400 bg-slate-800 py-3 rounded-2xl border border-slate-700">
            {{ patternTarget.sequence.join(', ') }}, <span class="text-amber-300 underline">?</span>
          </div>
          <div class="grid grid-cols-2 gap-2">
            <button 
              v-for="(opt, idx) in patternTarget.options" 
              :key="idx"
              @click="checkSequenceAnswer(opt)"
              class="bg-slate-800 hover:bg-emerald-600 border border-slate-700 text-xs font-bold py-3 rounded-xl transition-all cursor-pointer"
            >
              {{ opt }}
            </button>
          </div>
        </div>

        <!-- MODAL FOOTER -->
        <div class="flex justify-between items-center pt-2 border-t border-slate-800">
          <span class="text-[10px] text-slate-500 font-mono">Exam timer active</span>
          <button @click="forfeitHintGame" class="text-xs text-rose-400 hover:text-rose-300 font-extrabold cursor-pointer">
            Forfeit Attempt
          </button>
        </div>

      </div>
    </div>

    <!-- PRE-QUIZ INSTRUCTION MODAL -->
    <div v-if="showPrepModal" class="fixed inset-0 z-50 bg-slate-950/60 backdrop-blur-xs flex items-center justify-center p-4">
        <div class="bg-white border border-slate-200 text-slate-900 rounded-2xl max-w-lg w-full p-6 sm:p-7 space-y-6 shadow-xl">
        
        <!-- HEADER -->
        <div class="flex items-start justify-between gap-4 border-b border-slate-100 pb-5">
            <div class="flex items-center gap-3.5">
            <div class="w-10 h-10 rounded-xl bg-amber-50 border border-amber-200 text-amber-600 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            <div>
                <h3 class="text-lg font-bold text-slate-900 leading-snug">Assessment Guidelines</h3>
                <p class="text-xs text-slate-500 mt-0.5 font-medium">
                You are about to start <span class="text-slate-800 font-semibold">Midterm Assessment: Electromagnetism & Magnetic Flux</span>
                </p>
            </div>
            </div>

            <button 
            @click="showPrepModal = false" 
            class="text-slate-400 hover:text-slate-600 transition-colors p-1 rounded-lg hover:bg-slate-100 cursor-pointer"
            >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
            </svg>
            </button>
        </div>

        <!-- RULES LIST -->
        <div class="space-y-3.5">
            <!-- Rule 1 -->
            <div class="flex items-start gap-3.5 p-3.5 rounded-xl bg-slate-50 border border-slate-100">
            <div class="p-2 bg-white rounded-lg border border-slate-200/80 text-amber-500 shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                </svg>
            </div>
            <div class="text-xs leading-relaxed">
                <span class="font-bold text-slate-900 block mb-0.5">Hint Games</span>
                <span class="text-slate-600 font-normal">Single-attempt puzzles. Closing or failing an attempt deducts a hint token.</span>
            </div>
            </div>

            <!-- Rule 2 -->
            <div class="flex items-start gap-3.5 p-3.5 rounded-xl bg-slate-50 border border-slate-100">
            <div class="p-2 bg-white rounded-lg border border-slate-200/80 text-slate-700 shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="text-xs leading-relaxed">
                <span class="font-bold text-slate-900 block mb-0.5">Timer Rules</span>
                <span class="text-slate-600 font-normal">Countdown does <strong class="text-slate-900 font-semibold">NOT</strong> pause during hint game attempts.</span>
            </div>
            </div>

            <!-- Rule 3 -->
            <div class="flex items-start gap-3.5 p-3.5 rounded-xl bg-rose-50/50 border border-rose-100">
            <div class="p-2 bg-white rounded-lg border border-rose-200 text-rose-500 shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            <div class="text-xs leading-relaxed">
                <span class="font-bold text-rose-950 block mb-0.5">3 Warnings Limit</span>
                <span class="text-rose-700 font-normal">Tab switching or leaving fullscreen mode leads to auto-suspension.</span>
            </div>
            </div>
        </div>

        <!-- FOOTER ACTIONS -->
        <div class="flex items-center justify-end gap-3 pt-2">
            <button 
            @click="showPrepModal = false"
            class="px-5 py-2.5 rounded-xl border border-slate-300 hover:bg-slate-50 text-slate-700 text-xs font-semibold transition-all cursor-pointer"
            >
            Cancel
            </button>
            <button 
            @click="enterQuizLockdown"
            class="px-6 py-2.5 rounded-xl bg-[#0a3a18] hover:bg-[#072810] active:scale-[0.99] text-white text-xs font-bold transition-all shadow-sm cursor-pointer flex items-center gap-2"
            >
            <span>Start Exam</span>
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
            </svg>
            </button>
        </div>

        </div>
    </div>

    <!-- ANTI-CHEAT WARNING OVERLAY -->
    <div v-if="showWarningModal" class="fixed inset-0 z-50 bg-rose-950/90 backdrop-blur-md flex items-center justify-center p-4">
      <div class="bg-slate-900 text-white rounded-3xl max-w-md w-full p-6 text-center space-y-4 border border-rose-500/50 shadow-2xl">
        <div class="text-4xl">🚨</div>
        <h3 class="font-black text-rose-400 text-xl">SECURITY WARNING #{{ warningsCount }}</h3>
        <p class="text-xs text-slate-300 leading-relaxed">Focus loss or exit from fullscreen mode was detected!</p>
        <p class="text-xs font-bold text-amber-300">Remaining Strikes: {{ 3 - warningsCount }}</p>
        <button @click="acknowledgeWarning" class="w-full bg-rose-600 hover:bg-rose-500 text-white text-xs font-black py-3 rounded-xl transition-all cursor-pointer">
          Return to Exam
        </button>
      </div>
    </div>

  </div>
</template>

<script setup>
import { ref, computed, onUnmounted } from 'vue'
import { Head } from '@inertiajs/vue3'
import Sidebar from '@/Components/Sidebar.vue'
import heroImage from '@/../assets/img/local/student-quiz-hero.png'

// VIEW & FILTER STATES
const viewMode = ref('list')
const activeTab = ref('all')
const searchQuery = ref('')

// MODALS
const showPrepModal = ref(false)
const showWarningModal = ref(false)
const showHintGameModal = ref(false)
const selectedPrepQuiz = ref(null)
const warningsCount = ref(0)

// ACTIVE QUIZ DATA
const activeQuiz = ref(null)
const currentQuestionIndex = ref(0)
const userAnswers = ref({})
const itemHintStates = ref({}) // Stores 'won' | 'lost' for each question index
const targetHintQuestionIndex = ref(null)
const timeLeftSeconds = ref(0)
let timerInterval = null

// HINT MINI-GAME STATE
const currentGameType = ref('memory') // 'memory' | 'unscramble' | 'math' | 'simon' | 'sequence'
const gameTitle = ref('')

// 1. Memory Game
const memoryCards = ref([])
const flippedCards = ref([])

// 2. Unscramble Game
const unscrambleTarget = ref({ word: '', scrambled: '' })
const unscrambleInput = ref('')

// 3. Math Game
const mathProblem = ref({ question: '', answer: 0, options: [] })

// 4. Simon Says Game
const simonColors = [
  { id: 'red', bg: 'bg-rose-600' },
  { id: 'blue', bg: 'bg-blue-600' },
  { id: 'emerald', bg: 'bg-emerald-600' }
]
const simonSequence = ref([])
const userSimonSequence = ref([])
const simonStep = ref('watch') // 'watch' | 'repeat'
const activeSimonColor = ref(null)

// 5. Pattern Sequence Game
const patternTarget = ref({ sequence: [], answer: 0, options: [] })

const filterTabs = [
  { id: 'all', label: 'All Exams' },
  { id: 'available', label: 'Active & Open' },
  { id: 'upcoming', label: 'Upcoming' },
  { id: 'completed', label: 'Completed' },
]

// QUIZ DATABASE
const quizzes = ref([
  {
    id: 101,
    subject: 'General Physics 2',
    title: 'Midterm Assessment: Electromagnetism & Magnetic Flux',
    description: 'Covers Lenz Law, Faraday Simulation calculations, and right-hand rule derivations.',
    status: 'available',
    timeLimitMinutes: 15,
    hintTokens: 3,
    totalPoints: 30,
    proctored: true,
    allowReview: true,
    questions: [
      {
        questionText: 'Which law states that the induced electromotive force in any closed circuit is equal to the negative of the time rate of change of magnetic flux?',
        options: ['Ohm\'s Law', 'Faraday\'s Law of Induction', 'Coulomb\'s Law', 'Ampere\'s Law'],
        correctAnswer: 1,
        explanation: 'Faraday\'s Law describes how magnetic fields interact with circuits to produce electromotive force.'
      },
      {
        questionText: 'What is the SI unit of magnetic flux density?',
        options: ['Weber', 'Tesla', 'Henry', 'Gauss'],
        correctAnswer: 1,
        explanation: 'Tesla (T) is the official SI unit for magnetic flux density.'
      },
      {
        questionText: 'Lenz\'s Law is a direct consequence of which fundamental physical law?',
        options: ['Conservation of Mass', 'Conservation of Energy', 'Conservation of Momentum', 'Conservation of Charge'],
        correctAnswer: 1,
        explanation: 'Lenz\'s Law enforces energy conservation so induced currents do not generate infinite energy feedback loops.'
      }
    ]
  },
  {
    id: 102,
    subject: 'Practical Research II',
    title: 'Methodology & Quantitative Design Evaluation',
    description: 'Sampling methods, quantitative research designs, and data analysis validation.',
    status: 'available',
    timeLimitMinutes: 10,
    hintTokens: 2,
    totalPoints: 20,
    proctored: true,
    allowReview: false,
    questions: [
      {
        questionText: 'Which sampling technique gives every member of the population an equal chance of being selected?',
        options: ['Purposive Sampling', 'Simple Random Sampling', 'Snowball Sampling', 'Quota Sampling'],
        correctAnswer: 1,
        explanation: 'Simple Random Sampling ensures unbiased selection probability across population frames.'
      }
    ]
  }
])

// COMPUTED FEED HELPERS
const filteredQuizzes = computed(() => {
  return quizzes.value.filter(quiz => {
    const matchesTab = activeTab.value === 'all' || quiz.status === activeTab.value
    const matchesSearch = !searchQuery.value || quiz.title.toLowerCase().includes(searchQuery.value.toLowerCase()) || quiz.subject.toLowerCase().includes(searchQuery.value.toLowerCase())
    return matchesTab && matchesSearch
  })
})

const availableQuizzesCount = computed(() => quizzes.value.filter(q => q.status === 'available').length)

const getQuizCountByStatus = (status) => {
  if (status === 'all') return quizzes.value.length
  return quizzes.value.filter(q => q.status === status).length
}

const currentQuestion = computed(() => activeQuiz.value?.questions[currentQuestionIndex.value] || {})

const progressPercentage = computed(() => {
  if (!activeQuiz.value) return 0
  return Math.round(((currentQuestionIndex.value + 1) / activeQuiz.value.questions.length) * 100)
})

const formattedTimeLeft = computed(() => {
  const mins = Math.floor(timeLeftSeconds.value / 60)
  const secs = timeLeftSeconds.value % 60
  return `${mins.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`
})

const activeQuizResult = computed(() => activeQuiz.value?.result || {})

// HINT PUZZLE GENERATORS
const openHintPuzzleModal = (qIndex) => {
  if (activeQuiz.value.hintTokens <= 0 || itemHintStates.value[qIndex]) return
  targetHintQuestionIndex.value = qIndex

  const games = [
    { type: 'memory', title: 'Single-Chance Memory Match' },
    { type: 'unscramble', title: 'Speed Word Unscramble' },
    { type: 'math', title: 'Quick Math Matrix' },
    { type: 'simon', title: 'Color Sequence Reaction' },
    { type: 'sequence', title: 'Pattern Sequence Challenge' }
  ]
  const picked = games[Math.floor(Math.random() * games.length)]
  currentGameType.value = picked.type
  gameTitle.value = picked.title

  if (picked.type === 'memory') setupMemoryGame()
  else if (picked.type === 'unscramble') setupUnscrambleGame()
  else if (picked.type === 'math') setupMathGame()
  else if (picked.type === 'simon') setupSimonGame()
  else if (picked.type === 'sequence') setupSequenceGame()

  showHintGameModal.value = true
}

// 1. Memory Game: Single Chance Logic
const setupMemoryGame = () => {
  const icons = ['⚡', '🔬', '📐', '🧪']
  const deck = [...icons, ...icons].sort(() => Math.random() - 0.5)
  memoryCards.value = deck.map(icon => ({ icon, flipped: false }))
  flippedCards.value = []
}

const flipMemoryCard = (idx) => {
  if (memoryCards.value[idx].flipped || flippedCards.value.length >= 2) return
  memoryCards.value[idx].flipped = true
  flippedCards.value.push(idx)

  if (flippedCards.value.length === 2) {
    const [first, second] = flippedCards.value
    if (memoryCards.value[first].icon === memoryCards.value[second].icon) {
      setTimeout(() => handleHintOutcome(true), 400) // Match!
    } else {
      setTimeout(() => handleHintOutcome(false), 500) // Wrong pair = Instant Loss
    }
  }
}

// 2. Unscramble Game
const setupUnscrambleGame = () => {
  const words = ['VECTOR', 'KINETIC', 'NUCLEUS', 'GRAVITY', 'CIRCUIT']
  const selected = words[Math.floor(Math.random() * words.length)]
  const scrambled = selected.split('').sort(() => Math.random() - 0.5).join('')
  unscrambleTarget.value = { word: selected, scrambled }
  unscrambleInput.value = ''
}

const checkUnscramble = () => {
  if (unscrambleInput.value.trim().toUpperCase() === unscrambleTarget.value.word) {
    handleHintOutcome(true)
  } else {
    handleHintOutcome(false)
  }
}

// 3. Math Matrix Game
const setupMathGame = () => {
  const a = Math.floor(Math.random() * 12) + 5
  const b = Math.floor(Math.random() * 12) + 5
  const ans = a * b
  const options = [ans, ans + 4, ans - 3, ans + 10].sort(() => Math.random() - 0.5)
  mathProblem.value = { question: `${a} × ${b} = ?`, answer: ans, options }
}

const checkMathAnswer = (opt) => {
  if (opt === mathProblem.value.answer) {
    handleHintOutcome(true)
  } else {
    handleHintOutcome(false)
  }
}

// 4. Simon Says Game
const setupSimonGame = () => {
  simonStep.value = 'watch'
  simonSequence.value = [
    simonColors[Math.floor(Math.random() * 3)].id,
    simonColors[Math.floor(Math.random() * 3)].id,
    simonColors[Math.floor(Math.random() * 3)].id
  ]
  userSimonSequence.value = []

  // Flash Sequence
  let idx = 0
  const interval = setInterval(() => {
    activeSimonColor.value = simonSequence.value[idx]
    setTimeout(() => { activeSimonColor.value = null }, 400)
    idx++
    if (idx >= simonSequence.value.length) {
      clearInterval(interval)
      setTimeout(() => { simonStep.value = 'repeat' }, 500)
    }
  }, 700)
}

const handleSimonClick = (colorId) => {
  if (simonStep.value !== 'repeat') return
  userSimonSequence.value.push(colorId)
  const currentIdx = userSimonSequence.value.length - 1

  if (userSimonSequence.value[currentIdx] !== simonSequence.value[currentIdx]) {
    handleHintOutcome(false) // Wrong sequence = Loss
    return
  }

  if (userSimonSequence.value.length === simonSequence.value.length) {
    handleHintOutcome(true) // Solved!
  }
}

// 5. Pattern Sequence Game
const setupSequenceGame = () => {
  const start = Math.floor(Math.random() * 5) + 2
  const step = Math.floor(Math.random() * 4) + 3
  const seq = [start, start + step, start + (step * 2), start + (step * 3)]
  const ans = start + (step * 4)
  const options = [ans, ans + 2, ans - 3, ans + 5].sort(() => Math.random() - 0.5)
  patternTarget.value = { sequence: seq, answer: ans, options }
}

const checkSequenceAnswer = (opt) => {
  if (opt === patternTarget.value.answer) {
    handleHintOutcome(true)
  } else {
    handleHintOutcome(false)
  }
}

// FORFEIT & OUTCOME LOGIC
const forfeitHintGame = () => {
  handleHintOutcome(false)
}

const handleHintOutcome = (won) => {
  showHintGameModal.value = false
  // Deduct 1 hint token regardless of outcome
  activeQuiz.value.hintTokens = Math.max(0, activeQuiz.value.hintTokens - 1)

  if (won) {
    itemHintStates.value[targetHintQuestionIndex.value] = 'won'
  } else {
    itemHintStates.value[targetHintQuestionIndex.value] = 'lost'
  }
}

// PREPARATION & START
const startQuizPrep = (quiz) => {
  selectedPrepQuiz.value = quiz
  showPrepModal.value = true
}

const enterQuizLockdown = () => {
  showPrepModal.value = false
  activeQuiz.value = selectedPrepQuiz.value
  currentQuestionIndex.value = 0
  userAnswers.value = {}
  itemHintStates.value = {}
  warningsCount.value = 0
  timeLeftSeconds.value = activeQuiz.value.timeLimitMinutes * 60

  viewMode.value = 'taking'
  
  if (document.documentElement.requestFullscreen) {
    document.documentElement.requestFullscreen().catch(() => {})
  }

  window.addEventListener('blur', handleAntiCheatViolation)
  document.addEventListener('fullscreenchange', handleFullscreenChange)
  document.addEventListener('contextmenu', preventDefaultAction)
  document.addEventListener('keydown', preventKeyCombos)

  timerInterval = setInterval(() => {
    if (timeLeftSeconds.value > 0) {
      timeLeftSeconds.value--
    } else {
      submitQuiz(true)
    }
  }, 1000)
}

// ANTI-CHEAT HANDLERS
const handleAntiCheatViolation = () => {
  if (viewMode.value !== 'taking') return
  warningsCount.value++

  if (warningsCount.value >= 3) {
    submitQuiz(true, true)
  } else {
    showWarningModal.value = true
  }
}

const handleFullscreenChange = () => {
  if (viewMode.value === 'taking' && !document.fullscreenElement) {
    handleAntiCheatViolation()
  }
}

const acknowledgeWarning = () => {
  showWarningModal.value = false
  if (document.documentElement.requestFullscreen) {
    document.documentElement.requestFullscreen().catch(() => {})
  }
}

const preventDefaultAction = (e) => e.preventDefault()
const preventKeyCombos = (e) => {
  if (e.ctrlKey || e.metaKey || e.key === 'F12' || e.key === 'Escape') {
    e.preventDefault()
  }
}

// SUBMISSION & CLEANUP
const submitQuiz = (forced = false, suspended = false) => {
  window.removeEventListener('blur', handleAntiCheatViolation)
  document.removeEventListener('fullscreenchange', handleFullscreenChange)
  document.removeEventListener('contextmenu', preventDefaultAction)
  document.removeEventListener('keydown', preventKeyCombos)
  clearInterval(timerInterval)

  if (document.fullscreenElement && document.exitFullscreen) {
    document.exitFullscreen().catch(() => {})
  }

  let score = 0
  const totalQuestions = activeQuiz.value.questions.length
  activeQuiz.value.questions.forEach((q, idx) => {
    if (userAnswers.value[idx] === q.correctAnswer) {
      score += Math.round(activeQuiz.value.totalPoints / totalQuestions)
    }
  })

  activeQuiz.value.status = 'completed'
  activeQuiz.value.result = {
    score: suspended ? 0 : score,
    userAnswers: { ...userAnswers.value },
    gradeText: suspended ? 'Suspended due to integrity violations' : (score / activeQuiz.value.totalPoints >= 0.75 ? 'Passed with Distinction' : 'Satisfactory Performance')
  }

  showWarningModal.value = false
  showHintGameModal.value = false
  viewMode.value = 'results'
}

const viewResults = (quiz) => {
  activeQuiz.value = quiz
  viewMode.value = 'results'
}

onUnmounted(() => {
  clearInterval(timerInterval)
  window.removeEventListener('blur', handleAntiCheatViolation)
  document.removeEventListener('fullscreenchange', handleFullscreenChange)
  document.removeEventListener('contextmenu', preventDefaultAction)
  document.removeEventListener('keydown', preventKeyCombos)
})
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Anton&family=Inter:wght@400;500;600;700;800;900&display=swap');

.animated-stroke-text {
  color: transparent;
  -webkit-text-stroke: 1.5px #ffffff;
}

@keyframes fadeIn {
  from { opacity: 0; transform: translateY(-4px); }
  to { opacity: 1; transform: translateY(0); }
}

.animate-fade-in {
  animation: fadeIn 0.3s ease-out forwards;
}
</style>