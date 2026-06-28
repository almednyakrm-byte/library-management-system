<?php
// Initialize session
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link href="https://cdn.tailwindcss.com" rel="stylesheet">
</head>
<body class="bg-slate-900 h-screen flex justify-center items-center">
    <div class="glassmorphic-card bg-white/20 backdrop-blur-md rounded-2xl shadow-2xl p-10 w-80">
        <h1 class="text-3xl text-indigo-500 font-bold mb-4">Login</h1>
        <form id="login-form">
            <div class="mb-4">
                <label for="username" class="block text-indigo-500 font-bold mb-2">Username</label>
                <input type="text" id="username" name="username" required pattern="[A-Za-z\u0600-\u06FF0-9\s]+" class="bg-transparent border border-indigo-500 rounded-2xl p-2 w-full">
            </div>
            <div class="mb-4">
                <label for="password" class="block text-indigo-500 font-bold mb-2">Password</label>
                <input type="password" id="password" name="password" required class="bg-transparent border border-indigo-500 rounded-2xl p-2 w-full">
            </div>
            <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-2xl w-full">Login</button>
        </form>
        <p class="text-indigo-500 mt-4">Don't have an account? <a href="register.php" class="text-indigo-500 hover:text-indigo-700">Register</a></p>
    </div>

    <script>
        const loginForm = document.getElementById('login-form');

        loginForm.addEventListener('submit', async (e) => {
            e.preventDefault();

            const username = document.getElementById('username').value;
            const password = document.getElementById('password').value;

            try {
                const response = await fetch('../backend/auth.php?action=login', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ username, password })
                });

                const data = await response.json();

                if (data.success) {
                    alert('Login successful!');
                    // Redirect to dashboard or home page
                    window.location.href = 'dashboard.php';
                } else {
                    alert(data.message);
                }
            } catch (error) {
                alert('Error: ' + error.message);
            }
        });
    </script>
</body>
</html>