<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            background-image: linear-gradient(to bottom, #1a1d23, #2c2f36);
            background-size: 100% 300px;
            background-position: 0% 100%;
            transition: background-position 1s;
        }
        
        .glassmorphic {
            background-color: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        
        .gradient {
            background-image: linear-gradient(to bottom, #1a1d23, #2c2f36);
            background-size: 100% 300px;
            background-position: 0% 100%;
            transition: background-position 1s;
        }
    </style>
</head>
<body>
    <div class="h-screen flex justify-center items-center bg-gray-200">
        <div class="glassmorphic w-96 p-10 bg-white rounded-lg shadow-md">
            <h2 class="text-2xl font-bold text-slate-900 mb-4">Login</h2>
            <form id="login-form">
                <div class="mb-4">
                    <label for="username" class="block text-slate-900 text-sm font-bold mb-2">Username</label>
                    <input type="text" id="username" name="username" class="block w-full p-2 border border-gray-300 rounded-lg" placeholder="Enter your username" pattern="[A-Za-z\u0600-\u06FF0-9\s]+" required>
                </div>
                <div class="mb-4">
                    <label for="password" class="block text-slate-900 text-sm font-bold mb-2">Password</label>
                    <input type="password" id="password" name="password" class="block w-full p-2 border border-gray-300 rounded-lg" placeholder="Enter your password" required>
                </div>
                <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">Login</button>
            </form>
            <p class="text-sm text-gray-600 mt-4">Don't have an account? <a href="register.php" class="text-indigo-500 hover:text-indigo-700">Register</a></p>
        </div>
    </div>

    <script>
        const loginForm = document.getElementById('login-form');
        loginForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData(loginForm);
            try {
                const response = await fetch('../backend/auth.php?action=login', {
                    method: 'POST',
                    body: formData
                });
                const data = await response.json();
                if (data.success) {
                    alert('Login successful!');
                    window.location.href = 'dashboard.php';
                } else {
                    alert(data.message);
                }
            } catch (error) {
                console.error(error);
                alert('Error logging in. Please try again.');
            }
        });
    </script>
</body>
</html>


This code creates a premium-looking login page with a glassmorphic layout, gradients, and a form for username and password input. It uses the Tailwind CSS CDN for styling and includes a beautiful glassmorphic layout with gradients. The form includes validation rules for username and password input using standard HTML input pattern validators. The AJAX JavaScript code uses the Fetch API to submit the credentials to the backend PHP script and handle the response or error alerts dynamically. The code also includes a direct link to the register.php page.