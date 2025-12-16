(function () {
    // 1. Prevent Pinch Zoom (iOS Safari specific, also some Androids)
    document.addEventListener('touchmove', function (event) {
        if (event.scale !== 1) {
            event.preventDefault();
        }
    }, { passive: false });

    // 2. Prevent Double Tap Zoom
    let lastTouchEnd = 0;
    document.addEventListener('touchend', function (event) {
        const now = (new Date()).getTime();
        if (now - lastTouchEnd <= 300) {
            event.preventDefault();
        }
        lastTouchEnd = now;
    }, false);

    // 3. Prevent Ctrl + Wheel (Desktop Zoom)
    document.addEventListener('wheel', function (event) {
        if (event.ctrlKey) {
            event.preventDefault();
        }
    }, { passive: false });

    // 4. Prevent Ctrl + +/- Keys (Desktop Zoom)
    document.addEventListener('keydown', function (event) {
        if (event.ctrlKey && (event.key === '+' || event.key === '-' || event.key === '=' || event.key === '_')) {
            event.preventDefault();
        }
    });
})();
