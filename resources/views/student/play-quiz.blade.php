@extends('layouts.student-app')

@section('title', 'Interactive Quiz | UniSmart')

@section('content')
<div class="max-w-4xl mx-auto sm:px-6 lg:px-8 py-12">
    <div class="mb-8 text-center">
        <h1 class="text-3xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-purple-600 to-indigo-600">
            Interactive Knowledge Check 💡
        </h1>
        <p class="text-gray-500 mt-2 font-medium">
            Test your understanding of: <span class="text-purple-600">{{ $file->lecture->title }}</span>
        </p>

        <div id="timer-container" class="mt-6 hidden animate-fade-in flex flex-col items-center gap-4">
            <div class="inline-flex items-center gap-3 bg-white border-2 border-amber-100 px-6 py-2 rounded-2xl shadow-sm">
                <span class="text-2xl">⏱️</span>
                <span id="time-clock" class="text-2xl font-black text-amber-600 font-mono">00:00</span>
            </div>
            <div class="w-64 h-2 bg-gray-100 mx-auto mt-1 rounded-full overflow-hidden border border-gray-50">
                <div id="timer-progress" class="h-full bg-gradient-to-r from-amber-400 to-orange-500 transition-all duration-1000" style="width: 100%"></div>
            </div>
        </div>
    </div>

    <div id="quiz-content" class="space-y-6">
        <div class="flex justify-center items-center p-20">
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
            
            // 15 ثانية لكل سؤال
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
        
        let html = questions.map((q, index) => `
            <div class="bg-white border border-purple-100 p-8 rounded-[2rem] shadow-xl shadow-purple-50/50 mb-6 transition-all animate-fade-in">
                <div class="flex items-start gap-4 mb-6">
                    <span class="bg-purple-600 text-white w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0 font-bold shadow-lg shadow-purple-200">
                        ${index + 1}
                    </span>
                    <h3 class="text-xl font-bold text-gray-800 leading-tight">${q.question}</h3>
                </div>
                
                <div class="grid gap-3">
                    ${q.options.map(option => `
                        <button onclick="submitAnswer(this, '${option.replace(/'/g, "\\'")}', '${q.answer.replace(/'/g, "\\'")}')" 
                                class="option-btn w-full text-left p-4 rounded-2xl border-2 border-gray-50 bg-gray-50/30 hover:border-purple-300 hover:bg-white transition-all duration-200 text-lg font-medium text-gray-700">
                            ${option}
                        </button>
                    `).join('')}
                </div>
                <div class="feedback hidden mt-4 p-4 rounded-xl font-bold text-sm italic text-center"></div>
            </div>
        `).join('');

        // إضافة قسم زر "Finish Quiz" في نهاية القائمة
        html += `
            <div id="final-submit-section" class="text-center py-10 hidden animate-fade-in">
                <button onclick="showFinalResult(false)" class="bg-gradient-to-r from-emerald-500 to-teal-600 text-white px-12 py-5 rounded-[2rem] font-black text-xl shadow-xl shadow-emerald-100 hover:scale-105 active:scale-95 transition-all">
                    Finish & View Results 🏁
                </button>
            </div>
        `;

        container.innerHTML = html;
    }

    function submitAnswer(btn, selected, correct) {
        const parent = btn.closest('.grid');
        const feedback = btn.parentElement.nextElementSibling;
        
        // تعطيل الأزرار بعد الاختيار
        parent.querySelectorAll('button').forEach(b => {
            b.disabled = true;
            b.style.opacity = "0.6";
        });
        
        btn.style.opacity = "1";
        feedback.classList.remove('hidden');
        answeredCount++;

        if (selected === correct) {
            correctAnswers++;
            btn.classList.replace('border-gray-50', 'border-emerald-500');
            btn.classList.add('bg-emerald-50', 'text-emerald-700');
            feedback.innerHTML = "✨ Excellent! Correct Answer.";
            feedback.classList.add('text-emerald-600');
        } else {
            btn.classList.replace('border-gray-50', 'border-rose-500');
            btn.classList.add('bg-rose-50', 'text-rose-700');
            feedback.innerHTML = `⚠️ Incorrect. Correct: ${correct}`;
            feedback.classList.add('text-rose-600');
        }

        // إظهار زر الإنهاء عند الوصول لآخر سؤال
        if (answeredCount === totalQuestions) {
            document.getElementById('final-submit-section').classList.remove('hidden');
            // عمل Scroll لأسفل ليرى المستخدم الزر
            window.scrollTo({ top: document.body.scrollHeight, behavior: 'smooth' });
        }
    }

    function showFinalResult(isTimeOut = false) {
        clearInterval(timerInterval);
        document.getElementById('timer-container').classList.add('hidden');

        const scorePercentage = Math.round((correctAnswers / totalQuestions) * 100);

        // إرسال النتيجة للداتابيز
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
        })
        .then(response => response.json())
        .then(data => console.log("Database Sync:", data))
        .catch(error => console.error("Database Sync Error:", error));

        const container = document.getElementById('quiz-content');
        const message = isTimeOut 
            ? "⏱️ Time is up! Your answers have been submitted." 
            : "Quiz completed successfully! Here is your performance.";

        container.innerHTML = `
            <div class="bg-white border-2 border-purple-100 p-12 rounded-[3rem] shadow-2xl text-center animate-bounce-in">
                <div class="text-7xl mb-6">${scorePercentage >= 50 ? '🏆' : '📚'}</div>
                <h2 class="text-3xl font-black text-gray-800 mb-2">Quiz Completed!</h2>
                <p class="text-gray-500 mb-8 font-medium italic">${message}</p>
                
                <div class="flex justify-center gap-12 mb-10">
                    <div class="text-center">
                        <span class="block text-5xl font-black text-purple-600">${correctAnswers}/${totalQuestions}</span>
                        <span class="text-xs text-gray-400 uppercase tracking-widest font-bold">Correct Answers</span>
                    </div>
                    <div class="text-center border-l border-gray-100 pl-12">
                        <span class="block text-5xl font-black text-indigo-600">${scorePercentage}%</span>
                        <span class="text-xs text-gray-400 uppercase tracking-widest font-bold">Final Grade</span>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row justify-center gap-4">
                  

                     <a href="{{ url('/student/subjects') }}" class="bg-gray-100 text-gray-600 px-8 py-4 rounded-2xl font-bold hover:bg-gray-200 transition-all flex items-center justify-center">
                        Back to Library
                    </a>
                </div>
            </div>
        `;
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }
</script>

<style>
    .option-btn:hover:not(:disabled) { transform: translateY(-3px); }
    .animate-fade-in { animation: fadeIn 0.5s ease-out; }
    .animate-bounce-in { animation: bounceIn 0.8s cubic-bezier(0.68, -0.55, 0.265, 1.55); }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
    @keyframes bounceIn { from { transform: scale(0.8); opacity: 0; } to { transform: scale(1); opacity: 1; } }
</style>
@endsection