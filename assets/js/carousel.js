// Self invoking function that runs as soon as the file is loaded
// Keeps carousel variables scoped and avoids leaking globals
(function () {
    // Enable strict mode for safer JavaScript behavior
    "use strict";
    // Read navigation buttons
    const prev = document.querySelector('#prev');
    const next = document.querySelector('#next');
    // Read all slide elements
    const $slides = document.querySelectorAll('.slide');
    // Store pagination dots once they are created
    let $dots;
    // Start on the first visible slide
    let currentSlide = 1;
    // Moves the carousel to the requested slide index
    function slideTo(index) {
        // Wrap around when the index is outside valid bounds
        currentSlide = index >= $slides.length || index < 1 ? 0 : index;
        // Translate each slide according to the current index
        $slides.forEach($elt => $elt.style.transform = `translateX(-${currentSlide * 100}%)`);
        // Update dot state to match the active slide
        $dots.forEach(($elt, key) => $elt.classList = `dot ${key === currentSlide? 'active': 'inactive'}`);
    }
    // Create one dot per slide
    for (let i = 1; i <= $slides.length; i++) {
        let dotClass = i == currentSlide ? 'active' : 'inactive';
        let $dot = `<span data-slidId="${i}" class="dot ${dotClass}"></span>`;
        document.querySelector('.carousel-dots').innerHTML += $dot;
    }
    // Cache generated dots
    $dots = document.querySelectorAll('.dot');
    // Bind click navigation on dots
    $dots.forEach(($elt, key) => $elt.addEventListener('click', () => slideTo(key)));
    // Bind previous button
    prev.addEventListener('click', () => slideTo(--currentSlide))
    // Bind next button
    next.addEventListener('click', () => slideTo(++currentSlide))
    // Bind touch gestures for swipe navigation
    $slides.forEach($elt => {
        let startX;
        let endX;
        // Save touch start position
        $elt.addEventListener('touchstart', (event) => {
            startX = event.touches[0].clientX;
        });
        // Save touch end position and detect swipe direction
        $elt.addEventListener('touchend', (event) => {
            endX = event.changedTouches[0].clientX;
            // Swipe left moves to next slide
            if (startX > endX) {
                slideTo(currentSlide + 1);
                // Swipe right moves to previous slide
            } else if (startX < endX) {
                slideTo(currentSlide - 1);
            }
        });
    })
})()