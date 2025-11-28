function showSignIn() {
    document.getElementById('main-menu').classList.add('hidden');
    document.getElementById('signin-form').classList.remove('hidden');
    document.getElementById('signup-form').classList.add('hidden');
    document.getElementById('page-background').classList.add('blurred');
}

function showSignUp() {
    document.getElementById('main-menu').classList.add('hidden');
    document.getElementById('signin-form').classList.add('hidden');
    document.getElementById('signup-form').classList.remove('hidden');
    document.getElementById('page-background').classList.add('blurred');
}

function showMainMenu() {
    document.getElementById('main-menu').classList.remove('hidden');
    document.getElementById('signin-form').classList.add('hidden');
    document.getElementById('signup-form').classList.add('hidden');
    document.getElementById('page-background').classList.remove('blurred');
}

function validateLogin() {
    const username = document.getElementById('username').value;
    const password = document.getElementById('password').value;

    if (!username || !password) {
        alert("Please enter both username and password.");
        return;
    }

    // Send data to PHP backend
    fetch('login_endpoint.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ username: username, password: password }),
    })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok: ' + response.statusText);
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                window.location.href = 'dashboard.html';
            } else {
                alert(data.message);
            }
        })
        .catch((error) => {
            console.error('Error:', error);
            alert("Login Error: " + error.message);
        });
}

function handleRegister() {
    const username = document.getElementById('reg-username').value;
    const email = document.getElementById('reg-email').value;
    const password = document.getElementById('reg-password').value;

    if (!username || !email || !password) {
        alert("Please fill in all fields.");
        return;
    }

    fetch('register_endpoint.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ username: username, email: email, password: password }),
    })
        .then(response => {
            if (!response.ok) {
                // Try to get text if json fails
                return response.text().then(text => {
                    throw new Error('Server Error: ' + response.status + ' ' + text);
                });
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                alert(data.message);
                showSignIn(); // Switch to sign in screen
            } else {
                alert(data.message);
            }
        })
        .catch((error) => {
            console.error('Error:', error);
            // Check if it's a JSON syntax error (common with PHP errors)
            if (error.message.includes('JSON')) {
                alert("Server Error: The server returned invalid data. Please check PHP logs.");
            } else {
                alert("Registration Error: " + error.message);
            }
        });
}
