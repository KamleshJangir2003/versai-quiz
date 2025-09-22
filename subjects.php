<?php
session_start();

// Agar login nahi hai to redirect
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Versai Academy - Learning Platform</title>
    <link rel="icon" type="image/x-icon" href="favicon (2).svg">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="css/subjects.css">
</head>

<body>
    <div class="header">
        <img src="images/versailogo.png" alt="Versai Academy Logo">
    
    <div class="hamburger-menu" id="hamburger-menu">
        <i class="fas fa-bars"></i>
        
    </div>
    </div>
    <style>
       /* Default (desktop/tablet) पर header छुपा दो */
.header {
    display: none;
}

/* ✅ सिर्फ phone view (max-width: 768px तक) पर दिखे */
@media (max-width: 768px) {
    .header {
        display: flex;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 70px;
        background-color: #fff;
        border-bottom: 1px solid #ddd;
        justify-content: space-between;
        align-items: center;
        padding: 0 20px;
        z-index: 1000;
    }

    .header img {
        height: 50px;
        width: auto;
    }

    .hamburger-menu {
        margin-left: 270px; /* margin हटा दिया ताकि right में align रहे */
        font-size: 26px;
        cursor: pointer;
    }
}

    </style>
    <div class="sidebar">
        <div class="logo">
            <a href="index.html">
            <img src="images/versailogo.png" alt="Versai Academy Logo"></a>
        </div>

        <div class="user-profile">
            <div class="user-avatar" id="user-avatar">
                <?php echo strtoupper(substr($_SESSION['user_name'], 0, 1)); ?> 
                <!-- pehle letter avatar me -->
            </div>
            <div class="user-info">
                <h4 id="student-name"><?php echo $_SESSION['user_name']; ?></h4>
                <p>Student ID: <span id="student-id"><?php echo "ST" . str_pad($_SESSION['user_id'], 3, "0", STR_PAD_LEFT); ?></span></p>
            </div>
        </div>

        <div class="sidebar-menu">
                     <!-- Add logout button here -->
    <button class="logout-btn" onclick="logout()">
        <i class="fas fa-sign-out-alt"></i>
        Logout
    </button>
            <h3 id="subject-heading">SELECT SUBJECT</h3>
    
    <script>
// Add this logout function in your existing JavaScript
function logout() {
    if (confirm('Are you sure you want to logout?')) {
        // Clear any stored data
        localStorage.clear();
        sessionStorage.clear();
        
        // Redirect to logout.php to destroy session
        window.location.href = 'logout.php';
    }
}
</script>
<!-- Add this CSS in your <style> section or subjects.css file -->
<style>
.logout-btn {
    width: 90%;
    margin: 10px auto 20px auto;
    padding: 10px 15px;
    background: #6c5ce7;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 14px;
    font-weight: bold;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    transition: background-color 0.3s;
}

.logout-btn:hover {
    background: #852c8e;
}

.logout-btn i {
    font-size: 16px;
}
</style>
            <ul id="subject-list">
                <!-- Subjects will be loaded here by JavaScript -->
              
            </ul>

            <!-- This will show only when a subject is selected -->
            <div id="test-list-container" style="display: none;">
                <h3>SELECT TEST</h3>
                <ul id="test-list">
                    <!-- Tests will be loaded here by JavaScript -->
                </ul>
                <button id="back-to-subjects"
                    style="margin: 20px; padding: 8px 15px; background: #f1f2f6; border: none; border-radius: 5px; cursor: pointer;">
                    <i class="fas fa-arrow-left"></i> Back to Subjects
                </button>
            </div>
        </div>
        
    </div>

    <div class="main-content">
        <!-- Default Content -->
        <div class="topic-content" id="default-content">
            <h2>Welcome to Versai Academy</h2>
            <p>Please select a subject from the sidebar to view available tests.</p>
            <p>Once you select a subject, you'll see all available tests for that subject.</p>
        </div>
        

        <!-- Loading Indicator -->
        <div class="topic-content" id="loading-content" style="display: none;">
            <div class="loading-spinner">
                <i class="fas fa-spinner fa-spin"></i>
                <p>Loading content, please wait...</p>
            </div>
        </div>

        <!-- Test Content (will be shown when a test is selected) -->
        <div class="topic-content" id="test-content" style="display: none;">
            <h2 id="test-title">Test Title</h2>
            <p id="test-description">Test description will appear here...</p>
            <div id="test-info">
                <p><strong>Total Questions:</strong> <span id="total-questions">10</span></p>
                <p><strong>Time Limit:</strong> <span id="time-limit">30 minutes</span></p>
            </div>
            <button class="start-quiz-btn" onclick="startQuiz()">Start Test</button>
        </div>

        <!-- Quiz Container -->
        <div class="quiz-container" id="quiz-container" style="display: none;">
            <div class="quiz-header">
                <div>
                    <h2 id="quiz-title">Test: Subject Name</h2>
                    <div class="student-info">
                        <span class="student-name" id="quiz-student-name">Student Name</span>
                        <div class="timer" id="quiz-timer">00:30:00</div>
                    </div>
                </div>
            </div>

            <div id="quiz-questions">
                <!-- Questions will be inserted here by JavaScript -->
            </div>

            <div class="quiz-navigation">
                <button class="nav-btn" id="prev-btn" onclick="navigateQuestion(-1)" disabled>
                    <i class="fas fa-arrow-left"></i> Previous
                </button>
                <button class="nav-btn" id="next-btn" onclick="navigateQuestion(1)">
                    Next <i class="fas fa-arrow-right"></i>
                </button>
            </div>

            <div class="question-palette">
                <div class="palette-title">
                    <span>Question Palette</span>
                    <span id="progress-text">1 of 10</span>
                </div>
                <div class="palette-questions" id="question-palette">
                    <!-- Question numbers will be inserted here -->
                </div>
            </div>

            <button type="button" class="submit-btn" onclick="showSubmitModal()">
                <i class="fas fa-paper-plane"></i> Submit Test
            </button>

        </div>

        <!-- Results Container -->
        <div class="results-container" id="results-container" style="display: none;">
            <h2>Test Results</h2>
            <div class="score-display" id="score-display">85%</div>
            <div class="score-text" id="score-text">You scored 17 out of 20 questions correctly</div>
            <p>Detailed results and explanations would be shown here in a complete implementation.</p>
            <button class="back-to-tests" onclick="backToTests()">
                <i class="fas fa-arrow-left"></i> Back to Tests
            </button>
        </div>
    </div>

    <!-- Submit Confirmation Modal -->
    <div class="modal" id="submit-modal">
        <div class="modal-content">
            <h2>Submit Test</h2>
            <p>Are you sure you want to submit your test? You won't be able to make changes after submission.</p>
            <div class="modal-buttons">
                <button class="modal-btn confirm" onclick="submitQuiz()">
                    <i class="fas fa-check"></i> Yes, Submit
                </button>
                <button class="modal-btn cancel" onclick="hideSubmitModal()">
                    <i class="fas fa-times"></i> Cancel
                </button>
            </div>
        </div>
    </div>

    <script>
        const student = {
            name: "<?php echo $_SESSION['user_name']; ?>",
            id: "<?php echo 'ST' . str_pad($_SESSION['user_id'], 3, '0', STR_PAD_LEFT); ?>",
            avatarInitial: "<?php echo strtoupper(substr($_SESSION['user_name'], 0, 1)); ?>"
        };
    </script>

    <script>
        // Global variables
        let currentSubject = null;
        let currentTest = null;
        let questions = [];
        let currentQuestionIndex = 0;
        let userAnswers = {};
        let timerInterval;
        let timeLeft = 30 * 60; // 30 minutes in seconds
        let subjectsData = []; // Will store loaded subjects

        // Initialize the page
        document.addEventListener('DOMContentLoaded', function () {
            // Set student information
            document.getElementById('student-name').textContent = student.name;
            document.getElementById('student-id').textContent = student.id;
            document.getElementById('user-avatar').textContent = student.avatarInitial;
            document.getElementById('quiz-student-name').textContent = student.name;

            // Load subjects from JSON
            loadSubjectsData();

            // Set up back to subjects button
            document.getElementById('back-to-subjects').addEventListener('click', function () {
                showAllSubjects();
            });

            // Hamburger menu toggle
            document.getElementById('hamburger-menu').addEventListener('click', function() {
                const sidebar = document.querySelector('.sidebar');
                sidebar.classList.toggle('active');
            });
        });

        // Load subjects data from JSON file
        async function loadSubjectsData() {
            showLoading(true);
            
            try {
                const response = await fetch('data/subjects.json?v=' + new Date().getTime());
                if (!response.ok) {
                    throw new Error('Failed to load subjects');
                }
                
                subjectsData = await response.json();
                loadSubjects();
                showLoading(false);
            } catch (error) {
                console.error('Error loading subjects:', error);
                showLoading(false);
                alert('Failed to load subjects. Please try again later.');
            }
        }

        // Load subjects into the sidebar
        function loadSubjects() {
            const subjectList = document.getElementById('subject-list');
            subjectList.innerHTML = '';

            subjectsData.forEach(subject => {
                const li = document.createElement('li');
                li.className = 'subject-item';
                li.innerHTML = `
                    <a href="#" data-subject-id="${subject.id}">
                        <i class="fas ${subject.icon}"></i>
                        <span>${subject.name}</span>
                    </a>
                `;
                subjectList.appendChild(li);
            });

            subjectList.addEventListener('click', function(event) {
                const target = event.target.closest('a');
                if (target) {
                    event.preventDefault();
                    const subjectId = target.getAttribute('data-subject-id');
                    showSubjectTests(subjectId);
                }
            });
        }

        // Show tests for a selected subject
        function showSubjectTests(subjectId) {
            const subject = subjectsData.find(s => s.id === subjectId);
            if (!subject) return;

            currentSubject = subject;

            // Hide all subjects
            document.getElementById('subject-list').style.display = 'none';
            document.getElementById('subject-heading').style.display = 'none';

            // Show tests for this subject
            const testListContainer = document.getElementById('test-list-container');
            testListContainer.style.display = 'block';

            const testList = document.getElementById('test-list');
            testList.innerHTML = '';

            subject.tests.forEach(test => {
                const li = document.createElement('li');
                li.className = 'test-item';
                li.innerHTML = `
                    <a href="#" data-subject-id="${subject.id}" data-test-id="${test.id}">
                        <i class="fas fa-file-alt"></i>
                        <span>${test.name}</span>
                    </a>
                `;
                testList.appendChild(li);
            });

            testList.addEventListener('click', function(event) {
                const target = event.target.closest('a');
                if (target) {
                    event.preventDefault();
                    const subjectId = target.getAttribute('data-subject-id');
                    const testId = target.getAttribute('data-test-id');
                    showTest(subjectId, testId);
                }
            });

               // Hide default content
            document.getElementById('default-content').style.display = 'none';

            // Automatically show the first test
            if (subject.tests.length > 0) {
                showTest(subject.id, subject.tests[0].id);
            }
        }

        // Show all subjects again (when going back from tests)
        function showAllSubjects() {
            // Show all subjects
            document.getElementById('subject-list').style.display = 'block';
            document.getElementById('subject-heading').style.display = 'block';

            // Hide tests list
            document.getElementById('test-list-container').style.display = 'none';

            // Show default content
            document.getElementById('default-content').style.display = 'block';

            // Hide test and quiz content if open
            document.getElementById('test-content').style.display = 'none';
            document.getElementById('quiz-container').style.display = 'none';
            document.getElementById('results-container').style.display = 'none';
        }

        // Show test content
        function showTest(subjectId, testId) {
            // Close sidebar on mobile after a test is selected
            const sidebar = document.querySelector('.sidebar');
            if (sidebar.classList.contains('active')) {
                sidebar.classList.remove('active');
            }

            const subject = subjectsData.find(s => s.id === subjectId);
            if (!subject) return;

            const test = subject.tests.find(t => t.id === testId);
            if (!test) return;

            currentTest = test;

            // Hide other content
            document.getElementById('default-content').style.display = 'none';

            // Show test content
            const testContent = document.getElementById('test-content');
            testContent.style.display = 'block';
            document.getElementById('test-title').textContent = test.name;
            document.getElementById('test-description').textContent = test.description;
            document.getElementById('total-questions').textContent = test.questionCount;
            document.getElementById('time-limit').textContent = test.timeLimit;

            // Hide quiz if open
            document.getElementById('quiz-container').style.display = 'none';
            document.getElementById('results-container').style.display = 'none';
        }

        // Start quiz for current test
        async function startQuiz() {
            if (!currentTest) return;

            showLoading(true);
            
            try {
                // Get questions for this test from JSON file
                questions = await getQuestionsForTest(currentTest.id);

                // Initialize user answers
                userAnswers = {};
                questions.forEach((_, index) => {
                    userAnswers[index] = null;
                });

                // Reset to first question
                currentQuestionIndex = 0;

                // Set quiz title
                document.getElementById('quiz-title').textContent = `Test: ${currentTest.name}`;

                // Initialize timer based on test time limit
                const timeParts = currentTest.timeLimit.split(' ');
                const minutes = parseInt(timeParts[0]);
                timeLeft = minutes * 60;
                updateTimerDisplay();
                startTimer();

                // Generate quiz questions
                renderQuestion();
                renderQuestionPalette();

                // Show quiz and hide other content
                document.getElementById('test-content').style.display = 'none';
                document.getElementById('quiz-container').style.display = 'block';
                
                showLoading(false);
            } catch (error) {
                console.error('Error loading questions:', error);
                showLoading(false);
                alert('Failed to load test questions. Please try again later.');
            }
        }

        // Render the current question
        function renderQuestion() {
            const quizContainer = document.getElementById('quiz-questions');
            quizContainer.innerHTML = '';

            const question = questions[currentQuestionIndex];

            const questionDiv = document.createElement('div');
            questionDiv.className = 'question';
            questionDiv.innerHTML = `
                <div class="question-text">${currentQuestionIndex + 1}. ${question.text}</div>
                <div class="options">
                    ${question.options.map((option, i) => `
                        <label class="option">
                            <input type="radio" name="q${currentQuestionIndex}" value="${option.value}" 
                                ${userAnswers[currentQuestionIndex] === option.value ? 'checked' : ''}>
                            ${option.text}
                        </label>
                    `).join('')}
                </div>
            `;
            quizContainer.appendChild(questionDiv);

            // Update navigation buttons
            document.getElementById('prev-btn').disabled = currentQuestionIndex === 0;
            document.getElementById('next-btn').disabled = currentQuestionIndex === questions.length - 1;

            // Update progress text
            document.getElementById('progress-text').textContent = `${currentQuestionIndex + 1} of ${questions.length}`;

            // Update palette active state
            updatePaletteActiveState();
        }

        // Render question palette
        function renderQuestionPalette() {
            const palette = document.getElementById('question-palette');
            palette.innerHTML = '';

            questions.forEach((_, index) => {
                const btn = document.createElement('div');
                btn.className = 'palette-btn';
                if (index === 0) btn.classList.add('active');
                if (userAnswers[index] !== null) btn.classList.add('answered');
                btn.textContent = index + 1;
                btn.onclick = () => navigateToQuestion(index);
                palette.appendChild(btn);
            });
        }

        // Update palette active state
        function updatePaletteActiveState() {
            const paletteBtns = document.querySelectorAll('.palette-btn');
            paletteBtns.forEach((btn, index) => {
                btn.classList.toggle('active', index === currentQuestionIndex);
                btn.classList.toggle('answered', userAnswers[index] !== null);
            });
        }

        // Navigate between questions
        function navigateQuestion(direction) {
            // Save current answer
            saveCurrentAnswer();

            // Update question index
            currentQuestionIndex += direction;

            // Render new question
            renderQuestion();
        }

        // Navigate to specific question
        function navigateToQuestion(index) {
            // Save current answer
            saveCurrentAnswer();

            // Update question index
            currentQuestionIndex = index;

            // Render new question
            renderQuestion();
        }

        // Save current answer
        function saveCurrentAnswer() {
            const selectedOption = document.querySelector(`input[name="q${currentQuestionIndex}"]:checked`);
            userAnswers[currentQuestionIndex] = selectedOption ? selectedOption.value : null;

            // Update palette answered state
            updatePaletteActiveState();
        }

        // Timer functions
        function startTimer() {
            clearInterval(timerInterval);
            timerInterval = setInterval(() => {
                timeLeft--;
                updateTimerDisplay();

                if (timeLeft <= 0) {
                    clearInterval(timerInterval);
                    submitQuiz();
                }
            }, 1000);
        }

        function updateTimerDisplay() {
            const hours = Math.floor(timeLeft / 3600);
            const minutes = Math.floor((timeLeft % 3600) / 60);
            const seconds = timeLeft % 60;

            document.getElementById('quiz-timer').textContent =
                `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
        }

        // Show submit confirmation modal
        function showSubmitModal() {
            saveCurrentAnswer();
            document.getElementById('submit-modal').style.display = 'flex';
        }

        // Hide submit confirmation modal
        function hideSubmitModal() {
            document.getElementById('submit-modal').style.display = 'none';
        }

        // Submit quiz and show results
        function submitQuiz() {
            clearInterval(timerInterval);
            hideSubmitModal();

            // Calculate score
            let score = 0;
            questions.forEach((question, index) => {
                if (userAnswers[index] === question.correct) {
                    score++;
                }
            });

            const percentage = Math.round((score / questions.length) * 100);

            // Store results in localStorage to pass to the results page
            const resultData = {
                studentName: student.name,
                studentId: student.id,
                testName: currentTest.name,
                score: score,
                totalQuestions: questions.length,
                percentage: percentage
            };

            localStorage.setItem('examResult', JSON.stringify(resultData));

            // Redirect to the result page
            window.location.href = "result.html";
        }

        // Back to tests button
        function backToTests() {
            document.getElementById('results-container').style.display = 'none';
            showTest(currentSubject.id, currentTest.id);
        }

        // Get questions for a test from JSON file
        async function getQuestionsForTest(testId) {
            try {
                const response = await fetch(`data/tests/${testId}.json?v=${new Date().getTime()}`);
                if (!response.ok) {
                    throw new Error('Failed to load test questions');
                }
                return await response.json();
            } catch (error) {
                console.error('Error loading test questions:', error);
                throw error;
            }
        }

        // Show/hide loading indicator
        function showLoading(show) {
            document.getElementById('loading-content').style.display = show ? 'block' : 'none';
            if (show) {
                // document.getElementById('default-content').style.display = 'none';
                document.getElementById('test-content').style.display = 'none';
                document.getElementById('quiz-container').style.display = 'none';
                document.getElementById('results-container').style.display = 'none';
            }
        }
    </script>
</body>

</html>
