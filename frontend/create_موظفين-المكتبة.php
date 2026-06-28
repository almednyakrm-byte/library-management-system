<?php
// Start session
session_start();

// Validate session
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Include database connection
include '../backend/db.php';

// Get module slug
$mod_slug = 'موظفين-المكتبة';

// Get current user
$username = $_SESSION['username'];

// Get user role
$user_role = $_SESSION['user_role'];

// Check if user has permission to add records
if ($user_role != 'admin' && $user_role != 'librarian') {
    header('Location: index.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إضافة موظفين المكتبة</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-slate-900 text-indigo-500">
    <div class="container mx-auto p-4 pt-6 md:p-6 lg:p-12 xl:p-24">
        <h1 class="text-3xl font-bold mb-4">إضافة موظفين المكتبة</h1>
        <form id="add-form" class="bg-slate-800 p-4 rounded shadow-md">
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium">اسم الموظف</label>
                <input type="text" id="name" name="name" class="block w-full p-2 pl-10 text-sm text-indigo-500 bg-slate-700 border border-indigo-500 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" required>
            </div>
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium">البريد الإلكتروني</label>
                <input type="email" id="email" name="email" class="block w-full p-2 pl-10 text-sm text-indigo-500 bg-slate-700 border border-indigo-500 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" required>
            </div>
            <div class="mb-4">
                <label for="phone" class="block text-sm font-medium">رقم الهاتف</label>
                <input type="tel" id="phone" name="phone" class="block w-full p-2 pl-10 text-sm text-indigo-500 bg-slate-700 border border-indigo-500 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" required>
            </div>
            <div class="mb-4">
                <label for="position" class="block text-sm font-medium">المنصب</label>
                <input type="text" id="position" name="position" class="block w-full p-2 pl-10 text-sm text-indigo-500 bg-slate-700 border border-indigo-500 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" required>
            </div>
            <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">إضافة</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#add-form').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    type: 'POST',
                    url: '../backend/<?= $mod_slug ?>.php',
                    data: $(this).serialize(),
                    success: function() {
                        window.location.href = 'list_<?= $mod_slug ?>.php';
                    }
                });
            });
        });
    </script>
</body>
</html>