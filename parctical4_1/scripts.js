// script.js

// 1. Collapsible FAQs
document.addEventListener('DOMContentLoaded', () => {
    const faqQuestions = document.querySelectorAll('.faq-question');

    faqQuestions.forEach(question => {
        question.addEventListener('click', () => {
            const answer = question.nextElementSibling;
            question.classList.toggle('active');
            if (answer.classList.contains('show')) {
                answer.classList.remove('show');
                answer.style.maxHeight = '0';
            } else {
                answer.classList.add('show');
                // Set max-height to the scrollHeight to allow for dynamic content
                answer.style.maxHeight = answer.scrollHeight + 'px';
            }
        });
    });

    // 2. Popups
    const openPopupBtn = document.getElementById('open-popup-btn');
    const infoPopup = document.getElementById('info-popup');
    const closePopupBtn = document.getElementById('close-popup-btn');

    openPopupBtn.addEventListener('click', () => {
        infoPopup.style.display = 'flex'; // Make it visible
    });

    closePopupBtn.addEventListener('click', () => {
        infoPopup.style.display = 'none'; // Hide it
    });

    // Close popup if clicked outside
    infoPopup.addEventListener('click', (event) => {
        if (event.target === infoPopup) {
            infoPopup.style.display = 'none';
        }
    });

    // 3. Sliders
    const sliderTrack = document.querySelector('.slider-track');
    const sliderImages = document.querySelectorAll('.slider-image');
    const prevBtn = document.querySelector('.slider-nav.prev');
    const nextBtn = document.querySelector('.slider-nav.next');
    const dotsContainer = document.querySelector('.slider-dots');
    const dots = document.querySelectorAll('.dot');

    let currentIndex = 0;

    function updateSlider() {
        // Update images visibility
        sliderImages.forEach((img, index) => {
            if (index === currentIndex) {
                img.classList.add('active');
            } else {
                img.classList.remove('active');
            }
        });

        // Update dots
        dots.forEach((dot, index) => {
            if (index === currentIndex) {
                dot.classList.add('active');
            } else {
                dot.classList.remove('active');
            }
        });

        // Optional: Implement actual sliding by translating the track
        // This is a simple fade/show example. For a true slide, you'd adjust transform: translateX()
        // const imageWidth = sliderImages.clientWidth;
        // sliderTrack.style.transform = `translateX(-${currentIndex * imageWidth}px)`;
    }

    prevBtn.addEventListener('click', () => {
        currentIndex = (currentIndex > 0) ? currentIndex - 1 : sliderImages.length - 1;
        updateSlider();
    });

    nextBtn.addEventListener('click', () => {
        currentIndex = (currentIndex < sliderImages.length - 1) ? currentIndex + 1 : 0;
        updateSlider();
    });

    dots.forEach((dot, index) => {
        dot.addEventListener('click', () => {
            currentIndex = index;
            updateSlider();
        });
    });

    // Initialize slider display
    updateSlider();


    // Supplementary Problem: Notification Popup Banner
    const notificationBanner = document.getElementById('notification-banner');
    const closeNotificationBtn = document.getElementById('close-notification-btn');

    // Show banner after a short delay (e.g., 2 seconds)
    setTimeout(() => {
        notificationBanner.classList.add('show-banner');
    }, 2000);

    closeNotificationBtn.addEventListener('click', () => {
        notificationBanner.classList.remove('show-banner');
        // Optional: Hide completely after fade out
        setTimeout(() => {
            notificationBanner.style.display = 'none';
        }, 500); // Should match CSS transition duration
    });
}); 