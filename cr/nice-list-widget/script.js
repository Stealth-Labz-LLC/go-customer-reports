/**
 * Nice List Quiz Widget
 * A simple 2-question quiz to determine if you're on Santa's Nice List
 * 100% client-side, no backend required
 * All classes prefixed with nlw- to avoid conflicts
 */

(function() {
    'use strict';

    // Configuration
    const CONFIG = {
        funnelUrl: 'https://stltrck.com/stlbz/24b334af-9b60-4d30-a26e-4e450bde9016/0002?affS1=news_widget',
        questions: [
            {
                id: 1,
                text: "How often do you help others without being asked?",
                answers: [
                    { id: 'a', text: "Every chance I get!", icon: "🌟", points: 3 },
                    { id: 'b', text: "Pretty often", icon: "😊", points: 2 },
                    { id: 'c', text: "Sometimes when I remember", icon: "🤔", points: 1 },
                    { id: 'd', text: "Only when someone asks", icon: "😅", points: 0 }
                ]
            },
            {
                id: 2,
                text: "What would you do if you found $20 on the ground?",
                answers: [
                    { id: 'a', text: "Try to find the owner", icon: "🔍", points: 3 },
                    { id: 'b', text: "Turn it in to lost & found", icon: "📦", points: 2 },
                    { id: 'c', text: "Keep it but feel a little guilty", icon: "😬", points: 1 },
                    { id: 'd', text: "Finders keepers!", icon: "💰", points: 0 }
                ]
            }
        ],
        results: {
            nice: {
                title: "You're on the Nice List!",
                subtitle: "Santa has been watching, and you've been VERY good this year!",
                details: "Your kindness and good deeds have earned you a special spot on Santa's Nice List. Get ready for some holiday magic!",
                badge: "Official Nice List Member"
            },
            naughty: {
                title: "Almost on the Nice List!",
                subtitle: "You're so close! A little more kindness and you'll be there.",
                details: "Don't worry - there's still time to spread some holiday cheer and secure your spot on the Nice List!",
                badge: "Nice List Candidate"
            }
        }
    };

    // State
    let currentScreen = 'welcome';
    let currentQuestion = 0;
    let answers = [];
    let totalPoints = 0;

    // DOM Elements
    const app = document.getElementById('nlw-app');

    // Initialize
    function init() {
        if (!app) return;
        addSnowflakes();
        renderScreen();
    }

    // Add snowflake background effect (only on standalone page)
    function addSnowflakes() {
        const pageWrapper = document.querySelector('.nlw-page');
        if (!pageWrapper) return;

        if (pageWrapper.querySelector('.nlw-snowflakes')) return;

        const snowflakesContainer = document.createElement('div');
        snowflakesContainer.className = 'nlw-snowflakes';

        for (let i = 0; i < 15; i++) {
            const snowflake = document.createElement('div');
            snowflake.className = 'nlw-snowflake';
            snowflake.textContent = '❄';
            snowflake.style.left = Math.random() * 100 + '%';
            snowflake.style.animationDuration = (Math.random() * 3 + 4) + 's';
            snowflake.style.animationDelay = Math.random() * 5 + 's';
            snowflake.style.fontSize = (Math.random() * 0.8 + 0.6) + 'rem';
            snowflakesContainer.appendChild(snowflake);
        }

        pageWrapper.appendChild(snowflakesContainer);
    }

    // Render current screen
    function renderScreen() {
        switch (currentScreen) {
            case 'welcome':
                renderWelcome();
                break;
            case 'question':
                renderQuestion();
                break;
            case 'result':
                renderResult();
                break;
        }
    }

    // Welcome Screen
    function renderWelcome() {
        app.innerHTML = `
            <div class="nlw-card">
                <div class="nlw-card-header">
                    <h1>Santa's Nice List Check</h1>
                    <p>Find out if you made the list this year!</p>
                </div>
                <div class="nlw-card-body">
                    <div class="nlw-welcome-icon">🎅</div>
                    <div class="nlw-welcome-text">
                        <h2>Are You on the Nice List?</h2>
                        <p>Answer 2 quick questions and Santa will reveal if you've made his Nice List this Christmas season!</p>
                    </div>
                </div>
                <div class="nlw-card-body" style="padding-top: 0;">
                    <button class="nlw-btn nlw-btn-primary" id="nlw-btn-start">
                        Check My Status →
                    </button>
                </div>
                <div class="nlw-footer">
                    <p>Takes less than 30 seconds!</p>
                </div>
            </div>
        `;

        document.getElementById('nlw-btn-start').addEventListener('click', startQuiz);
    }

    // Start Quiz
    function startQuiz() {
        currentScreen = 'question';
        currentQuestion = 0;
        answers = [];
        totalPoints = 0;
        renderScreen();
    }

    // Question Screen
    function renderQuestion() {
        const question = CONFIG.questions[currentQuestion];
        const progress = ((currentQuestion) / CONFIG.questions.length) * 100;

        app.innerHTML = `
            <div class="nlw-card">
                <div class="nlw-card-header">
                    <h1>Santa's Nice List Check</h1>
                    <p>Question ${currentQuestion + 1} of ${CONFIG.questions.length}</p>
                </div>
                <div class="nlw-card-body">
                    <div class="nlw-progress">
                        <div class="nlw-progress-bar">
                            <div class="nlw-progress-fill" style="width: ${progress}%"></div>
                        </div>
                        <div class="nlw-progress-text">Question ${currentQuestion + 1} of ${CONFIG.questions.length}</div>
                    </div>

                    <div class="nlw-question-text">${question.text}</div>

                    <div class="nlw-answer-options">
                        ${question.answers.map(answer => `
                            <button class="nlw-answer-btn" data-answer="${answer.id}" data-points="${answer.points}">
                                <span class="nlw-answer-icon">${answer.icon}</span>
                                <span class="nlw-answer-text">${answer.text}</span>
                            </button>
                        `).join('')}
                    </div>
                </div>
            </div>
        `;

        document.querySelectorAll('.nlw-answer-btn').forEach(btn => {
            btn.addEventListener('click', handleAnswer);
        });
    }

    // Handle Answer Selection
    function handleAnswer(e) {
        const btn = e.currentTarget;
        const answerId = btn.dataset.answer;
        const points = parseInt(btn.dataset.points);

        document.querySelectorAll('.nlw-answer-btn').forEach(b => b.classList.remove('selected'));
        btn.classList.add('selected');

        answers.push({ questionId: currentQuestion, answerId, points });
        totalPoints += points;

        setTimeout(() => {
            if (currentQuestion < CONFIG.questions.length - 1) {
                currentQuestion++;
                renderScreen();
            } else {
                currentScreen = 'result';
                renderScreen();
            }
        }, 400);
    }

    // Result Screen
    function renderResult() {
        const isNice = totalPoints >= 3;
        const result = isNice ? CONFIG.results.nice : CONFIG.results.naughty;

        app.innerHTML = `
            <div class="nlw-card">
                <div class="nlw-card-header">
                    <h1>Your Results Are In!</h1>
                    <p>Santa has checked his list...</p>
                </div>
                <div class="nlw-card-body">
                    <div class="nlw-result-card">
                        <span class="nlw-result-badge">${result.badge}</span>

                        <div class="nlw-result-checkmark">✓</div>

                        <h2 class="nlw-result-title">${result.title}</h2>
                        <p class="nlw-result-subtitle">${result.subtitle}</p>

                        <div class="nlw-result-details">
                            <p>${result.details}</p>
                        </div>

                        <div class="nlw-coupon-teaser">
                            <span class="nlw-coupon-label">Use code</span>
                            <span class="nlw-coupon-code">MERRY2025</span>
                            <span class="nlw-coupon-discount">for up to 50% off!</span>
                        </div>

                        <a href="${CONFIG.funnelUrl}" class="nlw-btn nlw-btn-primary">
                            Get Your Personalized Santa Video →
                        </a>

                        <button class="nlw-btn nlw-btn-secondary" id="nlw-btn-restart">
                            Take Quiz Again
                        </button>
                    </div>
                </div>
                <div class="nlw-footer">
                    <p>Over 50,000 families trust Videos From Santa</p>
                </div>
            </div>
        `;

        document.getElementById('nlw-btn-restart').addEventListener('click', () => {
            currentScreen = 'welcome';
            renderScreen();
        });
    }

    // Initialize on DOM ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
