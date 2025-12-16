// ------------------------------------------
// STEAMPUNK GEAR NAVBAR - ChronosQuest
// Based on PEMWEBEUY Design
// ------------------------------------------

(function () {
    // 0. Inject CSS
    const style = document.createElement('style');
    style.innerHTML = `
        /* --- BOTTOM NAVIGATION (FIXED GEAR) --- */
        .bottom-nav-container {
            position: fixed !important;
            bottom: 0 !important;
            left: 50%;
            transform: translateX(-50%);
            width: 100vw;
            height: 0;
            display: flex;
            justify-content: center;
            align-items: flex-end;
            z-index: 99999;
            padding: 0;
            margin: 0;
            pointer-events: none;
        }

        .nav-gear-wrapper {
            position: relative;
            width: 600px; 
            height: 600px;
            transform: translateY(50%);
            margin-bottom: 0;
            pointer-events: none;
            display: flex;
            justify-content: center;
            align-items: center; 
        }

        .nav-gear-bg {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: contain; 
            pointer-events: auto;
        }

        .nav-links {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
        }

        /* Base Item Style */
        .nav-item {
            position: absolute;
            width: 70px;
            height: 70px;
            top: 50%;
            left: 50%;
            margin-top: -35px;
            margin-left: -35px;
            transform-origin: center center; 
            pointer-events: auto;
            text-decoration: none;
            transition: transform 0.3s ease, filter 0.3s;
            z-index: 100000;
        }

        /* ARC POSITIONS */
        .item-1 { transform: rotate(-65deg) translateY(-150px) rotate(65deg); }
        .item-2 { transform: rotate(-29deg) translateY(-135px) rotate(29deg); }
        .item-3 { transform: rotate(29deg) translateY(-135px) rotate(-29deg); }
        .item-4 { transform: rotate(65deg) translateY(-150px) rotate(-65deg); }

        /* ICON STYLING */
        .nav-item .icon-circle {
            width: 100%;
            height: 100%;
            background: #e6dcc5;
            border: 3px solid #6d5843;
            border-radius: 50%;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.6);
            overflow: hidden;
            transition: all 0.3s ease;
            position: relative;
        }

        .nav-item .icon-circle img {
            position: absolute;
            top: 50%;
            left: 50%;
            width: 100%;
            height: 100%;
            transform: translate(-50%, -50%) scale(1.7);
            transform-origin: center center;
            object-fit: contain;
            display: block;
            margin: 0;
            padding: 0;
        }

        /* HOVER EFFECTS */
        .nav-item:hover .icon-circle {
            background: #fff;
            transform: scale(1.15);
            box-shadow: 0 0 25px #c5a059;
            border-color: #c5a059;
        }

        /* ACTIVE STATE */
        .nav-item.active .icon-circle {
            background: #fff;
            transform: scale(1.15);
            border-color: #0f2e35;
            box-shadow: 0 0 30px rgba(197, 160, 89, 0.9), inset 0 0 10px rgba(0,0,0,0.3);
        }
    `;
    document.head.appendChild(style);

    // 1. Create Container
    const navContainer = document.createElement('div');
    navContainer.className = 'bottom-nav-container';

    // 2. Define HTML Structure - Updated paths for keenam project
    navContainer.innerHTML = `
        <div class="nav-gear-wrapper">
            <img src="../assets/gear_dashboard.png" alt="Dashboard Gear" class="nav-gear-bg">
            <div class="nav-links">
                <!-- ITEM 1: HOME -->
                <a href="dashboard.html" class="nav-item item-1" title="Home">
                    <div class="icon-circle"><img src="../assets/home.png" alt="Home"></div>
                </a>
                <!-- ITEM 2: QUEST -->
                <a href="quest.html" class="nav-item item-2" title="Quest">
                    <div class="icon-circle"><img src="../assets/quest.png" alt="Quest"></div>
                </a>
                <!-- ITEM 3: MATERI -->
                <a href="materi.html" class="nav-item item-3" title="Materi">
                    <div class="icon-circle"><img src="../assets/materi.png" alt="Materi"></div>
                </a>
                <!-- ITEM 4: PROFILE -->
                <a href="profile.html" class="nav-item item-4" title="Profile">
                    <div class="icon-circle"><img src="../assets/profile.png" alt="Profile" style="object-fit:cover;"></div>
                </a>
            </div>
        </div>
    `;

    // 3. Inject into Body
    document.body.appendChild(navContainer);

    // 4. Highlight Active Page (DISABLED AS REQUESTED)
    // const currentPath = window.location.pathname.split('/').pop();
    // const items = document.querySelectorAll('.nav-item');
    // items.forEach(item => {
    //    if (item.getAttribute('href') === currentPath) {
    //        item.classList.add('active');
    //    }
    // });

    // 5. UPDATE SIDEBAR AVATAR (For Materi & Quest pages)
    const sidebarAvatar = document.querySelector('#side .avatar img');
    if (sidebarAvatar) {
        const storedAvatar = sessionStorage.getItem('cq_avatar');
        if (storedAvatar) {
            sidebarAvatar.src = storedAvatar;
        } else {
            // If no avatar in session, try to infer or default
            sidebarAvatar.src = '../assets/profile.png';
        }
    }

})();
