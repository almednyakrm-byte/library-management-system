<!-- register.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 h-screen">
    <div class="flex justify-center items-center h-full">
        <div class="bg-white p-8 rounded-lg shadow-lg w-1/2">
            <h1 class="text-3xl text-center text-indigo-500 font-bold mb-4">Register</h1>
            <form id="register-form">
                <div class="mb-4">
                    <label for="username" class="block text-gray-700 text-sm font-bold mb-2">Username</label>
                    <input type="text" id="username" name="username" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" pattern="[A-Za-z\u0600-\u06FF0-9\s]+" required>
                    <div id="username-error" class="text-red-500 text-xs italic"></div>
                </div>
                <div class="mb-4">
                    <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email</label>
                    <input type="email" id="email" name="email" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                    <div id="email-error" class="text-red-500 text-xs italic"></div>
                </div>
                <div class="mb-4">
                    <label for="password" class="block text-gray-700 text-sm font-bold mb-2">Password</label>
                    <input type="password" id="password" name="password" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" pattern="[A-Za-z0-9!@#$%^&*()_+=-{};:'<>,./?]" required>
                    <div id="password-error" class="text-red-500 text-xs italic"></div>
                </div>
                <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Register</button>
            </form>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#register-form').submit(function(e) {
                e.preventDefault();
                var username = $('#username').val();
                var email = $('#email').val();
                var password = $('#password').val();
                var errors = [];

                if (!username.match(pattern)) {
                    errors.push('Username must contain only letters, numbers, and spaces.');
                }
                if (!email.match(/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/)) {
                    errors.push('Invalid email address.');
                }
                if (!password.match(pattern)) {
                    errors.push('Password must contain only letters, numbers, and special characters.');
                }

                if (errors.length > 0) {
                    $('#username-error').text('');
                    $('#email-error').text('');
                    $('#password-error').text('');
                    errors.forEach(function(error) {
                        if (error.includes('username')) {
                            $('#username-error').text(error);
                        } else if (error.includes('email')) {
                            $('#email-error').text(error);
                        } else if (error.includes('password')) {
                            $('#password-error').text(error);
                        }
                    });
                } else {
                    $.ajax({
                        type: 'POST',
                        url: '../backend/auth.php?action=register',
                        data: {
                            username: username,
                            email: email,
                            password: password
                        },
                        success: function(response) {
                            if (response === 'success') {
                                alert('Registration successful. Please login to continue.');
                                window.location.href = 'login.php';
                            } else {
                                alert('Registration failed. Please try again.');
                            }
                        }
                    });
                }
            });
        });
    </script>
</body>
</html>


This code uses Tailwind CSS to create a premium-looking registration form. It includes validation rules for the username, email, and password fields. The form data is submitted via AJAX to the `auth.php` script, which handles the registration process. If the registration is successful, the user is redirected to the login page. If the registration fails, an error message is displayed.