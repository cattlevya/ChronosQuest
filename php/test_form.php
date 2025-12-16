<?php
// Test register endpoint dengan data POST
header('Content-Type: text/html; charset=utf-8');
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Test Register</title>
    <style>
        body { font-family: Arial; padding: 20px; background: #1a1a1a; color: #fff; }
        input, button { padding: 10px; margin: 5px; font-size: 16px; }
        #result { background: #333; padding: 20px; margin-top: 20px; white-space: pre-wrap; }
    </style>
</head>
<body>
    <h2>Test Register Form</h2>
    <form id="testForm">
        <input type="text" id="nickname" placeholder="Nickname" value="TestNick"><br>
        <input type="email" id="email" placeholder="Email" value="testnick@test.com"><br>
        <input type="password" id="password" placeholder="Password" value="test123"><br>
        <button type="button" onclick="doRegister()">Test Register</button>
    </form>
    
    <div id="result">Hasil akan muncul di sini...</div>
    
    <script>
    function doRegister() {
        const data = {
            nickname: document.getElementById('nickname').value,
            email: document.getElementById('email').value,
            password: document.getElementById('password').value
        };
        
        document.getElementById('result').innerText = 'Mengirim request...\n\nPayload: ' + JSON.stringify(data, null, 2);
        
        fetch('register_endpoint.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data)
        })
        .then(response => {
            document.getElementById('result').innerText += '\n\nStatus: ' + response.status;
            return response.text();
        })
        .then(text => {
            document.getElementById('result').innerText += '\n\nResponse:\n' + text;
        })
        .catch(error => {
            document.getElementById('result').innerText += '\n\nError: ' + error.message;
        });
    }
    </script>
</body>
</html>
