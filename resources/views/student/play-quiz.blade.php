@extends('layouts.student-app')

@section('title', 'Interactive Quiz | UniSmart')

@section('content')
<div class="w-full px-3 py-4">

    <div class="mb-4 text-center">
        <h1 class="text-3xl font-black text-transparent bg-clip-text bg-gradient-to-r from-purple-600 to-indigo-600">
            Interactive Knowledge Check 💡
        </h1>
        <p class="text-gray-700 mt-3 text-lg font-medium">
            Test your understanding of: <span class="text-purple-600 border-b-2 border-purple-100">{{ $file->lecture->title }}</span>
        </p>

        <div id="timer-container" class="mt-8 hidden animate-fade-in flex flex-col items-center gap-4">
            <div class="inline-flex items-center gap-3 bg-white border-2 border-amber-100 px-8 py-3 rounded-2xl shadow-sm">
                <span class="text-2xl">⏱️</span>
                <span id="time-clock" class="text-3xl font-black text-amber-600 font-mono">00:00</span>
            </div>
            <div class="w-64 h-2.5 bg-gray-100 mx-auto mt-1 rounded-full overflow-hidden border border-gray-50">
                <div id="timer-progress" class="h-full bg-gradient-to-r from-amber-400 to-orange-500 transition-all duration-1000" style="width: 100%"></div>
            </div>
        </div>
    </div>

    <div id="quiz-content" class="space-y-10"> <div class="flex justify-center items-center p-20">
            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-purple-600"></div>
        </div>
    </div>
</div>

<script>
    let totalQuestions = 0;
    let correctAnswers = 0;
    let answeredCount = 0;
    let timeLeft = 0;
    let totalInitialTime = 0;
    let timerInterval;

    document.addEventListener('DOMContentLoaded', function() {
        try {
            const questions = {!! $quizData !!}; 
            totalQuestions = questions.length;
            totalInitialTime = totalQuestions * 15;
            timeLeft = totalInitialTime;
            
            renderStudentQuiz(questions);
            startTimer();
        } catch (e) {
            console.error("Error parsing Quiz Data:", e);
            document.getElementById('quiz-content').innerHTML = `
                <div class="bg-rose-50 text-rose-700 p-6 rounded-2xl border border-rose-100 text-center">
                    ⚠️ Error loading quiz data. Please contact your instructor.
                </div>`;
        }
    });

    function startTimer() {
        const timerContainer = document.getElementById('timer-container');
        const timeClock = document.getElementById('time-clock');
        const progressBar = document.getElementById('timer-progress');
        timerContainer.classList.remove('hidden');

        timerInterval = setInterval(() => {
            let minutes = Math.floor(timeLeft / 60);
            let seconds = timeLeft % 60;
            timeClock.innerText = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
            const progressWidth = (timeLeft / totalInitialTime) * 100;
            progressBar.style.width = `${progressWidth}%`;
            if (timeLeft <= 10) { 
                timeClock.classList.replace('text-amber-600', 'text-rose-600');
                progressBar.classList.replace('from-amber-400', 'from-rose-500');
            }
            if (timeLeft <= 0) {
                clearInterval(timerInterval);
                showFinalResult(true); 
            }
            timeLeft--;
        }, 1000);
    }

    function renderStudentQuiz(questions) {
        const container = document.getElementById('quiz-content');
        
        // التعديل هنا: إضافة border-2 و border-purple-300 لجعل الإطار أغمق وأوضح
        let html = questions.map((q, index) => `
            <div class="bg-white border-2 border-purple-300 p-10 rounded-[2.5rem] shadow-xl shadow-purple-50/50 mb-10 transition-all animate-fade-in hover:shadow-2xl hover:border-purple-500 group hall-card">
                <div class="flex items-start gap-6 mb-8">
                    <span class="bg-purple-600 text-white w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0 font-black shadow-lg shadow-purple-200 transition-transform group-hover:scale-110">
                        ${index + 1}
                    </span>
                    <h3 class="text-2xl font-bold text-gray-800 leading-snug pt-1">${q.question}</h3>
                </div>
                
                <div class="grid gap-4 ml-2">
                    ${q.options.map(option => `
                        <button onclick="submitAnswer(this, '${option.replace(/'/g, "\\'")}', '${q.answer.replace(/'/g, "\\'")}')" 
                                class="option-btn w-full text-left p-5 rounded-2xl border-2 border-gray-100 bg-gray-50/30 hover:border-purple-300 hover:bg-white transition-all duration-300 text-lg font-semibold text-gray-700 shadow-sm">
                            ${option}
                        </button>
                    `).join('')}
                </div>
                <div class="feedback hidden mt-8 p-5 rounded-2xl font-bold text-base italic text-center shadow-inner border border-gray-100"></div>
            </div>
        `).join('');

        html += `
            <div id="final-submit-section" class="text-center py-16 hidden animate-fade-in">
                <button onclick="showFinalResult(false)" class="bg-gradient-to-r from-emerald-500 to-teal-600 text-white px-16 py-6 rounded-[2.5rem] font-black text-2xl shadow-2xl shadow-emerald-100 hover:scale-105 active:scale-95 transition-all">
                    Finish & View Results 🏁
                </button>
            </div>
        `;
        container.innerHTML = html;
    }

    function submitAnswer(btn, selected, correct) {
        const parent = btn.closest('.grid');
        const feedback = btn.parentElement.nextElementSibling;
        
        parent.querySelectorAll('button').forEach(b => {
            b.disabled = true;
            b.style.opacity = "0.5";
        });
        
        btn.style.opacity = "1";
        feedback.classList.remove('hidden');
        answeredCount++;
if (selected >= correct) {
    correctAnswers++;
    // استبدال الأخضر بالبنفسجي
   btn.classList.replace('border-gray-100', 'border-purple-600'); 
btn.classList.add('bg-purple-50', 'text-purple-900', 'ring-2', 'ring-purple-200', 'shadow-lg');
    
    feedback.innerHTML = "✨ Well done! That is the correct answer.";
    // تعديل بوكس الفيدباك كمان
    feedback.className = "feedback mt-8 p-5 rounded-2xl font-bold text-lg text-center bg-purple-100/90 text-purple-900 border-2 border-purple-400 shadow-md animate-fade-in";
}else {
            btn.classList.replace('border-gray-100', 'border-rose-500');
            btn.classList.add('bg-rose-50', 'text-rose-800');
            feedback.innerHTML = `⚠️ Nice try! The correct answer is: ${correct}`;
            feedback.className = "feedback mt-8 p-5 rounded-2xl font-bold text-base text-center bg-rose-50 text-rose-600 border border-rose-200 animate-fade-in";
        }

        if (answeredCount === totalQuestions) {
            document.getElementById('final-submit-section').classList.remove('hidden');
            window.scrollTo({ top: document.body.scrollHeight, behavior: 'smooth' });
        }
    }

    function showFinalResult(isTimeOut = false) {
        clearInterval(timerInterval);
        document.getElementById('timer-container').classList.add('hidden');
        const scorePercentage = Math.round((correctAnswers / totalQuestions) * 100);

        fetch("{{ route('quiz.save') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({
                file_id: "{{ $file->id }}",
                score: scorePercentage,
                correct: correctAnswers,
                total: totalQuestions
            })
        });

        const container = document.getElementById('quiz-content');
        const message = isTimeOut ? "⏱️ Time is up! Answers submitted." : "Great job on completing the quiz!";

        container.innerHTML = `
            <div class="bg-white border-4 border-purple-200 p-14 rounded-[3.5rem] shadow-2xl text-center animate-bounce-in">
                <div class="text-8xl mb-8">${scorePercentage >= 50 ? '🏆' : '📖'}</div>
                <h2 class="text-4xl font-black text-gray-900 mb-3 italic">Quiz Accomplished!</h2>
                <p class="text-gray-400 mb-10 text-lg font-medium">${message}</p>
                
                <div class="flex justify-center gap-16 mb-12">
                    <div class="text-center">
                        <span class="block text-6xl font-black text-purple-600">${correctAnswers}/${totalQuestions}</span>
                        <span class="text-sm text-gray-400 uppercase tracking-widest font-bold">Score</span>
                    </div>
                    <div class="text-center border-l border-gray-100 pl-16">
                        <span class="block text-6xl font-black text-indigo-600">${scorePercentage}%</span>
                        <span class="text-sm text-gray-400 uppercase tracking-widest font-bold">Grade</span>
                    </div>
                </div>

               <div class="flex justify-center w-full mt-10">
    <a href="{{ url('/student/subjects') }}" 
       class="inline-flex items-center gap-3 bg-gradient-to-r from-purple-600 to-indigo-600 text-white px-12 py-5 rounded-[2rem] font-black text-xl shadow-xl shadow-purple-200 hover:scale-105 active:scale-95 transition-all">
        <span class="text-2xl">📚</span>
        <span>Back to Digital Library</span>
    </a>
</div>
            </div>
        `;
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }
</script>

<style>
    .option-btn:hover:not(:disabled) { transform: translateX(8px); }
    .animate-fade-in { animation: fadeIn 0.6s ease-out; }
    .animate-bounce-in { animation: bounceIn 0.8s cubic-bezier(0.68, -0.55, 0.265, 1.55); }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    @keyframes bounceIn { from { transform: scale(0.9); opacity: 0; } to { transform: scale(1); opacity: 1; } }
</style>
@endsection