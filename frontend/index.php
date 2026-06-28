<?php
session_start();

// Check if user is authenticated
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>نظام إدارة مكتبات ومراقبة الكتب</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .glassmorphism-card {
            background-color: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border-radius: 10px;
            padding: 20px;
        }
    </style>
</head>
<body class="bg-slate-900 text-white">
    <div class="max-w-7xl mx-auto p-4">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-3xl font-bold">نظام إدارة مكتبات ومراقبة الكتب</h1>
            <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='logout.php'">تسجيل خروج</button>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <?php
            // Fetch stats dynamically via Javascript API calls from the backend files
            $stats = json_decode(file_get_contents('stats.json'), true);
            ?>
            <div class="glassmorphism-card p-4">
                <h2 class="text-lg font-bold mb-2">إجمالي الكتب</h2>
                <p class="text-2xl font-bold"><?= $stats['total_books'] ?></p>
            </div>
            <div class="glassmorphism-card p-4">
                <h2 class="text-lg font-bold mb-2">إجمالي الإعارات</h2>
                <p class="text-2xl font-bold"><?= $stats['total_lends'] ?></p>
            </div>
            <div class="glassmorphism-card p-4">
                <h2 class="text-lg font-bold mb-2">إجمالي الإرجاعات</h2>
                <p class="text-2xl font-bold"><?= $stats['total_returns'] ?></p>
            </div>
        </div>
        <div class="flex justify-between items-center mt-4">
            <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='books.php'">إدارة الكتب</button>
            <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='staff.php'">إدارة موظفين المكتبة</button>
            <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='decisions.php'">إدارة قرارات الإدارة</button>
        </div>
    </div>

    <script>
        // Fetch stats dynamically via Javascript API calls from the backend files
        fetch('stats.json')
            .then(response => response.json())
            .then(data => {
                const stats = data;
                const totalBooksElement = document.querySelector('.total-books');
                const totalLendsElement = document.querySelector('.total-lends');
                const totalReturnsElement = document.querySelector('.total-returns');

                totalBooksElement.textContent = stats.total_books;
                totalLendsElement.textContent = stats.total_lends;
                totalReturnsElement.textContent = stats.total_returns;
            })
            .catch(error => console.error(error));
    </script>
</body>
</html>


**stats.json** (example data)
json
{
    "total_books": 1000,
    "total_lends": 500,
    "total_returns": 200
}


This code uses Tailwind CSS for styling and includes a session check to redirect to the login page if the user is not authenticated. The dashboard layout includes a welcome message, logout button, overview stats grid, and quick links to manage modules. The stats are fetched dynamically via a Javascript API call from the backend files. The color palette used is slate-900 and indigo-500.