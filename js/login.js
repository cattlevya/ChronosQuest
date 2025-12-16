// js/login.js

// --- UI HELPERS ---
function showSignIn() {
    document.getElementById('main-menu').classList.add('hidden');
    document.getElementById('signin-form').classList.remove('hidden');
    document.getElementById('signup-form').classList.add('hidden');
    document.getElementById('page-background').classList.add('blurred');
    document.body.classList.add('signin-active');
    document.body.classList.remove('signup-active');
}

function showSignUp() {
    document.getElementById('main-menu').classList.add('hidden');
    document.getElementById('signin-form').classList.add('hidden');
    document.getElementById('signup-form').classList.remove('hidden');
    document.getElementById('page-background').classList.add('blurred');
    document.body.classList.remove('signin-active');
    document.body.classList.add('signup-active');
}

function showMainMenu() {
    document.getElementById('main-menu').classList.remove('hidden');
    document.getElementById('signin-form').classList.add('hidden');
    document.getElementById('signup-form').classList.add('hidden');
    document.getElementById('page-background').classList.remove('blurred');
    document.body.classList.remove('signin-active');
    document.body.classList.remove('signup-active');
}

// --- LOGIN LOGIC (BY EMAIL) ---
function validateLogin() {
    const email = document.getElementById('email').value.trim();
    const password = document.getElementById('password').value.trim();

    if (!email || !password) {
        alert("Harap isi Email dan Password.");
        return;
    }

    // Kirim Email & Password ke PHP
    fetch('php/login_endpoint.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ email: email, password: password }),
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            sessionStorage.setItem('cq_current', JSON.stringify(data.data));
            const user = data.data;
            
            // Redirect Logic
            if(user.role === 'admin') {
                window.location.href = 'admin/dashboard.html';
            } else if (user.class_type === 'None' || user.class_type === null) {
                window.location.href = 'pilih-kelas.html'; // Belum pilih kelas
            } else {
                window.location.href = 'user/dashboard.html';
            }
        } else {
            alert(data.message);
        }
    })
    .catch((error) => {
        console.error('Error:', error);
        alert("Gagal terhubung ke server.");
    });
}

// --- REGISTER LOGIC (NICKNAME & EMAIL) ---
function handleRegister() {
    // Mapping ID HTML ke Logic DB
    // #reg-username -> Nickname
    // #reg-email    -> Email
    const nickname = document.getElementById('reg-username').value.trim();
    const email = document.getElementById('reg-email').value.trim();
    const password = document.getElementById('reg-password').value;
    const confirm = document.getElementById('reg-confirm-password').value;

    if (!nickname || !email || !password || !confirm) {
        alert("Semua kolom harus diisi!");
        return;
    }

    if (password !== confirm) {
        alert("Password tidak cocok.");
        return;
    }

    // Payload sesuai DB
    const payload = { 
        nickname: nickname, 
        email: email, 
        password: password 
    };

    fetch("php/register_endpoint.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(payload),
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            // Simpan ID sementara utk pemilihan kelas
            sessionStorage.setItem("temp_reg_id", data.user_id);
            alert("Akun berhasil dibuat! Silakan pilih kelas.");
            window.location.href = "pilih-kelas.html";
        } else {
            alert(data.message);
        }
    })
    .catch(err => {
        console.error("Error:", err);
        alert("Terjadi kesalahan sistem.");
    });
}