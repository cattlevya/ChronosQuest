// --- LOGIKA ROTASI GEAR (Physics Chain) ---
window.addEventListener('scroll', function () {
    const scrollPos = window.pageYOffset;

    // Ambil elemen
    const c1a = document.getElementById('cog1a');
    const c2a = document.getElementById('cog2a');
    const c2b = document.getElementById('cog2b');
    const c1b = document.getElementById('cog1b');
    const c2c = document.getElementById('cog2c');

    // Pastikan elemen ada sebelum diputar
    if (c1a && c2a && c2b && c1b && c2c) {

        // Gear 1 (Atas): Putar Cepat, Searah Jarum Jam (+)
        c1a.style.transform = `rotate(${scrollPos * 0.5}deg)`;

        // Gear 2 (Nempel Gear 1): Putar Pelan, Berlawanan (-)
        c2a.style.transform = `rotate(${scrollPos * -0.3}deg)`;

        // Gear 3 (Nempel Gear 2): Putar Pelan, Searah (+)
        c2b.style.transform = `rotate(${scrollPos * 0.3}deg)`;

        // Gear 4 (Nempel Gear 3): Putar Cepat, Berlawanan (-)
        c1b.style.transform = `rotate(${scrollPos * -0.5}deg)`;

        // Gear 5 (Nempel Gear 4): Putar Pelan, Searah (+)
        c2c.style.transform = `rotate(${scrollPos * 0.3}deg)`;
    }
});
// Scroll animation for gears
(() => {
    // ambil elemen yang Anda pakai di perhitungan (aman jika beberapa id tidak ada)
    const el = id => document.getElementById(id);

    const hook = el('hook');
    const weight = el('weight');
    const mfchainright = el('mfchainright');
    const mfchainleft = el('mfchainleft');

    const cog1a = el('cog1a');
    const mfmiddle = el('mfmiddle');
    const cog2a = el('cog2a');
    const cog3a = el('cog3a');
    const cog4 = el('cog4');

    // (opsional) jika Anda punya chain c1a..c2c mapping lain
    const c1a = el('c1a');
    const c2a = el('c2a');
    const c2b = el('c2b');
    const c1b = el('c1b');
    const c2c = el('c2c');

    // bottom-left gears (jika ada)
    const g_giant = el('g-giant');
    const g_gold = el('g-gold');
    const g_silver = el('g-silver');

    // helper untuk ukuran dokumen
    function getDocHeights() {
        const winH = window.innerHeight || 0;
        const docH = Math.max(
            document.body.scrollHeight,
            document.documentElement.scrollHeight,
            document.body.offsetHeight,
            document.documentElement.offsetHeight
        ) || 1;
        return { winH, docH };
    }

    // latestScroll dan flag ticking untuk rAF
    let latestScroll = window.scrollY || window.pageYOffset || 0;
    let ticking = false;

    function updateFromScroll(scrollY) {
        // hitung pageY seperti kode Anda sebelumnya
        const { winH, docH } = getDocHeights();
        const pageY = (scrollY / docH) * (winH - 32);

        if (hook) hook.style.marginTop = pageY + 'px';
        if (weight) weight.style.marginBottom = pageY + 'px';

        if (mfchainright && mfchainleft) {
            mfchainright.style.backgroundPosition = 'center ' + (pageY * 0.41655) + 'px';
            mfchainleft.style.backgroundPosition = 'center ' + (-pageY * 0.41655) + 'px';
        }

        // formulas r1..r4 (sama persis seperti Anda)
        const r1 = scrollY * 1.6 * 0.4686 + 15;
        if (cog1a) cog1a.style.transform = 'rotate(' + r1 + 'deg)';
        if (mfmiddle) mfmiddle.style.transform = 'rotate(' + r1 + 'deg)';

        const r2 = -(scrollY * 1.6 * 0.4686) + 6;
        if (cog2a) cog2a.style.transform = 'rotate(' + r2 + 'deg)';

        const r3 = scrollY * 0.8 * 0.4686 + 10;
        if (cog3a) cog3a.style.transform = 'rotate(' + r3 + 'deg)';

        const r4 = scrollY * 0.8 * 0.4686 + 8;
        if (cog4) cog4.style.transform = 'rotate(' + r4 + 'deg)';

        // chain kecil (jika ada) — Anda pernah pakai mapping ini, saya jaga tetap sama
        if (c1a) c1a.style.transform = `rotate(${scrollY * 0.5}deg)`;
        if (c2a) c2a.style.transform = `rotate(${scrollY * -0.3}deg)`;
        if (c2b) c2b.style.transform = `rotate(${scrollY * 0.3}deg)`;
        if (c1b) c1b.style.transform = `rotate(${scrollY * -0.5}deg)`;
        if (c2c) c2c.style.transform = `rotate(${scrollY * 0.3}deg)`;

        // bottom-left gears (subtle reaction)
        if (g_giant) g_giant.style.transform = `rotate(${scrollY * 0.18}deg)`;
        if (g_gold) g_gold.style.transform = `rotate(${scrollY * -0.34}deg)`;
        if (g_silver) g_silver.style.transform = `rotate(${scrollY * 0.28}deg)`;
    }

    function onAnimationFrame() {
        ticking = false;
        updateFromScroll(latestScroll);
    }

    function onScroll() {
        latestScroll = window.scrollY || window.pageYOffset || 0;
        if (!ticking) {
            ticking = true;
            requestAnimationFrame(onAnimationFrame);
        }
    }

    // pastikan tidak ada listener ganda — remove dulu (aman jika belum terpasang)
    window.removeEventListener('scroll', onScroll);
    window.addEventListener('scroll', onScroll, { passive: true });

    // Jangan panggil updateFromScroll() di sini — biarkan gear diam sampai user scroll.
})();
