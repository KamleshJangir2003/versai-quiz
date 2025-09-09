document.addEventListener('DOMContentLoaded', () => {
    const studentName = document.getElementById('student-name');
    const examName = document.getElementById('exam-name');
    const examTimer = document.getElementById('exam-timer');
    const questionNumber = document.querySelector('.question-number');
    const questionText = document.querySelector('.question-text');
    const optionsContainer = document.querySelector('.options-container');
    const questionPalette = document.getElementById('question-palette');
    const prevBtn = document.getElementById('prev-btn');
    const nextBtn = document.getElementById('next-btn');
    const markBtn = document.getElementById('mark-btn');
    const clearBtn = document.getElementById('clear-btn');
    const submitBtn = document.getElementById('submit-btn');
    const submitModal = document.getElementById('submit-modal');
    const cancelSubmit = document.getElementById('cancel-submit');
    const confirmSubmit = document.getElementById('confirm-submit');
    const attemptedQuestions = document.getElementById('attempted-questions');
    const studentLogout = document.getElementById('student-logout');

    let currentQuestionIndex = 0;
    let questions = [];
    let userAnswers = [];
    let timerInterval;

    async function initializeExam() {
        const urlParams = new URLSearchParams(window.location.search);
        const subject = urlParams.get('subject');
        const set = urlParams.get('set');

        let examDataUrl = '';
        if (subject) {
            let subjectPath = subject.toLowerCase().replace(/ /g, '-');
            if (subject === 'RSCIT') {
                subjectPath = 'rscit_questions';
            }
            examDataUrl = `${subjectPath}.json`;
        }

        try {
            const response = await fetch(examDataUrl);
            let allQuestions = await response.json();
            let examQuestions = [];

            if (subject === 'RSCIT' && set) {
                const setQuestions = allQuestions[set.toLowerCase().replace(/ /g, '_')];
                if (setQuestions) {
                    examQuestions = setQuestions;
                } else {
                    console.error('Set not found:', set);
                    return;
                }
            } else {
                examQuestions = allQuestions;
            }

            questions = examQuestions;
            userAnswers = new Array(questions.length).fill({ answer: null, marked: false });
            examName.textContent = subject + (set ? ` - ${set}` : '');
            // studentName.textContent = getStudentDetails(); // Implement this function
            startTimer(1800); // 30 minutes
            renderQuestion(currentQuestionIndex);
            renderPalette();
        } catch (error) {
            console.error('Error loading exam data:', error);
        }
    }

    function renderQuestion(index) {
        const question = questions[index];
        questionNumber.textContent = `Question ${index + 1} of ${questions.length}`;
        questionText.textContent = question.text;
        optionsContainer.innerHTML = '';
        question.options.forEach(optionText => {
            const option = document.createElement('div');
            option.className = 'option';
            option.textContent = optionText;
            if (userAnswers[index].answer === optionText) {
                option.classList.add('selected');
            }
            option.addEventListener('click', () => selectOption(optionText));
            optionsContainer.appendChild(option);
        });
        updateNavButtons();
        updatePalette();
    }

    function selectOption(selectedOption) {
        userAnswers[currentQuestionIndex].answer = selectedOption;
        renderQuestion(currentQuestionIndex);
    }

    function renderPalette() {
        questionPalette.innerHTML = '';
        questions.forEach((_, index) => {
            const btn = document.createElement('button');
            btn.className = 'palette-btn';
            btn.textContent = index + 1;
            btn.addEventListener('click', () => goToQuestion(index));
            questionPalette.appendChild(btn);
        });
        updatePalette();
    }

    function updatePalette() {
        const paletteButtons = questionPalette.children;
        for (let i = 0; i < paletteButtons.length; i++) {
            paletteButtons[i].classList.remove('current', 'answered', 'marked');
            if (i === currentQuestionIndex) {
                paletteButtons[i].classList.add('current');
            } else if (userAnswers[i].answer) {
                paletteButtons[i].classList.add('answered');
            }
            if (userAnswers[i].marked) {
                paletteButtons[i].classList.add('marked');
            }
        }
    }

    function updateNavButtons() {
        prevBtn.disabled = currentQuestionIndex === 0;
        nextBtn.disabled = currentQuestionIndex === questions.length - 1;
    }

    function goToQuestion(index) {
        currentQuestionIndex = index;
        renderQuestion(index);
    }

    function startTimer(duration) {
        let timeLeft = duration;
        timerInterval = setInterval(() => {
            const minutes = Math.floor(timeLeft / 60);
            const seconds = timeLeft % 60;
            examTimer.textContent = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
            if (--timeLeft < 0) {
                clearInterval(timerInterval);
                // Auto-submit logic
                alert("Time's up! The exam will be submitted automatically.");
                submitExam();
            }
        }, 1000);
    }

    function submitExam() {
        clearInterval(timerInterval);
        // Calculate score and show result
        let score = 0;
        userAnswers.forEach((userAnswer, index) => {
            if (userAnswer.answer === questions[index].answer) {
                score += questions[index].marks;
            }
        });
        alert(`Exam Submitted! Your score: ${score}`);
        // Redirect to a result page or dashboard
    }

    // Event Listeners
    prevBtn.addEventListener('click', () => {
        if (currentQuestionIndex > 0) {
            currentQuestionIndex--;
            renderQuestion(currentQuestionIndex);
        }
    });

    nextBtn.addEventListener('click', () => {
        if (currentQuestionIndex < questions.length - 1) {
            currentQuestionIndex++;
            renderQuestion(currentQuestionIndex);
        }
    });

    markBtn.addEventListener('click', () => {
        userAnswers[currentQuestionIndex].marked = !userAnswers[currentQuestionIndex].marked;
        updatePalette();
    });

    clearBtn.addEventListener('click', () => {
        userAnswers[currentQuestionIndex].answer = null;
        renderQuestion(currentQuestionIndex);
    });

    submitBtn.addEventListener('click', () => {
        const attemptedCount = userAnswers.filter(a => a.answer).length;
        attemptedQuestions.textContent = `${attemptedCount} out of ${questions.length}`;
        submitModal.classList.add('active');
    });

    cancelSubmit.addEventListener('click', () => {
        submitModal.classList.remove('active');
    });

    confirmSubmit.addEventListener('click', () => {
        submitModal.classList.remove('active');
        submitExam();
    });

    if (studentLogout) {
        studentLogout.addEventListener('click', () => {
            // Firebase logout logic here
            alert('Logged out');
            window.location.href = 'login.html';
        });
    }

    if (window.location.pathname.includes('student-exam.html')) {
        initializeExam();
    }
});

function selectSubject(subject, set) {
    let url = 'student-exam.html?subject=' + encodeURIComponent(subject);
    if (set) {
        url += '&set=' + encodeURIComponent(set);
    }
    window.location.href = url;
}
