// Hamburger menu
let menu = document.getElementById("navMenu");
let menuBtn = document.getElementById("menuBtn");

if (menuBtn && menu) {
    menuBtn.onclick = function () {
        menu.classList.toggle("show");
    };
}

// Light and dark theme
let themeBtn = document.getElementById("themeBtn");

if (themeBtn) {
    themeBtn.onclick = function () {
        document.body.classList.toggle("dark");
    };
}

// Notification banner
let notice = document.getElementById("notice");
let closeNotice = document.getElementById("closeNotice");

if (closeNotice && notice) {
    closeNotice.onclick = function () {
        notice.style.display = "none";
    };
}

// Modal popup
let modal = document.getElementById("myModal");
let openModal = document.getElementById("openModal");
let closeModal = document.getElementById("closeModal");

if (openModal && modal) {
    openModal.onclick = function () {
        modal.style.display = "flex";
    };
}

if (closeModal && modal) {
    closeModal.onclick = function () {
        modal.style.display = "none";
    };
}

// Content slider
let slides = document.querySelectorAll(".slide");
let currentSlide = 0;
let nextBtn = document.getElementById("nextBtn");
let prevBtn = document.getElementById("prevBtn");

function showSlide(index) {
    if (slides.length === 0) return;

    slides.forEach(function (slide) {
        slide.style.display = "none";
    });

    currentSlide = (index + slides.length) % slides.length;
    slides[currentSlide].style.display = "block";
}

if (slides.length > 0) {
    showSlide(0);
}

if (nextBtn) {
    nextBtn.onclick = function () {
        showSlide(currentSlide + 1);
    };
}

if (prevBtn) {
    prevBtn.onclick = function () {
        showSlide(currentSlide - 1);
    };
}

// Collapsible FAQ
let questions = document.querySelectorAll(".faq-question");

questions.forEach(function (question) {
    question.onclick = function () {
        let answer = question.nextElementSibling;

        if (answer) {
            if (answer.style.display === "block") {
                answer.style.display = "none";
            } else {
                answer.style.display = "block";
            }
        }
    };
});