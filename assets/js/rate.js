document.querySelectorAll(".scroll-container").forEach((container) => {
    let isDown = false;
    let startX, startY;
    let scrollLeft, scrollTop;
    let isScrollingHorizontally = false;

    // Mouse events for Desktop
    container.addEventListener("mousedown", (e) => {
        isDown = true;
        container.classList.add("active");
        startX = e.pageX - container.offsetLeft;
        scrollLeft = container.scrollLeft;
    });

    container.addEventListener("mouseleave", () => {
        isDown = false;
        container.classList.remove("active");
    });

    container.addEventListener("mouseup", () => {
        isDown = false;
        container.classList.remove("active");
    });

    container.addEventListener("mousemove", (e) => {
        if (!isDown) return;
        e.preventDefault();
        const x = e.pageX - container.offsetLeft;
        const walk = (x - startX) * 2; // Adjust speed
        container.scrollLeft = scrollLeft - walk;
    });

    // Disable dragging of images inside the scroll-container
    container.querySelectorAll("img").forEach((img) => {
        img.addEventListener("dragstart", (e) => e.preventDefault());
    });

    // Touch events for Tablets & Phones
    container.addEventListener("touchstart", (e) => {
        startX = e.touches[0].pageX;
        startY = e.touches[0].pageY;
        scrollLeft = container.scrollLeft;
        scrollTop = window.scrollY;
        isScrollingHorizontally = false; // Reset
    });

    container.addEventListener("touchmove", (e) => {
        const touchX = e.touches[0].pageX;
        const touchY = e.touches[0].pageY;
        const deltaX = Math.abs(touchX - startX);
        const deltaY = Math.abs(touchY - startY);

        if (deltaX > deltaY) {
            // Horizontal scrolling
            isScrollingHorizontally = true;
            container.scrollLeft = scrollLeft - (touchX - startX) * 2; // Adjust speed
            e.preventDefault(); // Prevent vertical scroll ONLY when swiping left/right
        }
        // If vertical scrolling is greater, allow it by not calling e.preventDefault()
    }, { passive: false });

    container.addEventListener("touchend", () => {
        isScrollingHorizontally = false; // Reset after touch ends
    });
});
