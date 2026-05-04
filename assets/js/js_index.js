
let currentSlide = 0;
const slider = document.getElementById('slider');
const dots = document.querySelectorAll('.dot');
const slides = document.querySelectorAll('.slide-item');
const totalSlides = slides.length;
let slideInterval;

function updateUI() {
    // Moves the slider
    slider.style.transform = `translateX(-${currentSlide * 100}%)`;

    // Updates dots
    dots.forEach((dot, index) => {
        const isCurrent = index === currentSlide;
        dot.style.width = isCurrent ? (window.innerWidth < 768 ? '20px' : '30px') : (window.innerWidth < 768 ? '10px' : '12px');
        dot.classList.toggle('bg-blue-600', isCurrent); // Changed to blue for better visibility
        dot.classList.toggle('bg-gray-300', !isCurrent);
    });
}

function nextSlide() {
    currentSlide = (currentSlide + 1) % totalSlides;
    updateUI();
}

function prevSlide() {
    currentSlide = (currentSlide - 1 + totalSlides) % totalSlides;
    updateUI();
}

function startTimer() {
    clearInterval(slideInterval);
    slideInterval = setInterval(nextSlide, 5000);
}

function manualNav(index) {
    currentSlide = index;
    updateUI();
    startTimer();
}

// Swipe Logic
let touchStartX = 0;
let touchEndX = 0;

slider.addEventListener('touchstart', e => {
    touchStartX = e.changedTouches[0].screenX;
}, { passive: true });

slider.addEventListener('touchend', e => {
    touchEndX = e.changedTouches[0].screenX;
    const swipeDistance = touchStartX - touchEndX;
    if (Math.abs(swipeDistance) > 50) {
        swipeDistance > 0 ? nextSlide() : prevSlide();
        startTimer();
    }
}, { passive: true });

// Initialize
updateUI();
startTimer();

// Re-calculate dot width on resize
window.addEventListener('resize', updateUI);


function scrollGrid(gridId, direction) {
    const grid = document.getElementById(gridId);
    // Calculate width of one card + its gap
    const scrollAmount = grid.firstElementChild.offsetWidth + 24; 
    grid.scrollBy({
        left: direction * scrollAmount,
        behavior: 'smooth'
    });
}
