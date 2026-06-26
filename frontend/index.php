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
    <title>نظام إدارة مكتبة</title>
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
            <h1 class="text-3xl font-bold">نظام إدارة مكتبة</h1>
            <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='logout.php'">تسجيل خروج</button>
        </div>
        <div class="glassmorphism-card p-4 mb-4">
            <h2 class="text-2xl font-bold mb-2">مرحباً <?php echo $_SESSION['username']; ?></h2>
            <p>استمتع بتعديل وتحديث بيانات المكتبة</p>
        </div>
        <div class="glassmorphism-card p-4 mb-4">
            <h2 class="text-2xl font-bold mb-2">إحصائيات المكتبة</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <div class="bg-slate-800 p-4 rounded">
                    <h3 class="text-lg font-bold mb-2">عدد الكتب</h3>
                    <p id="book-count"></p>
                </div>
                <div class="bg-slate-800 p-4 rounded">
                    <h3 class="text-lg font-bold mb-2">عدد الفئات</h3>
                    <p id="category-count"></p>
                </div>
                <div class="bg-slate-800 p-4 rounded">
                    <h3 class="text-lg font-bold mb-2">عدد الأشخاص</h3>
                    <p id="person-count"></p>
                </div>
            </div>
        </div>
        <div class="glassmorphism-card p-4 mb-4">
            <h2 class="text-2xl font-bold mb-2">روابط سريعة</h2>
            <ul>
                <li><a href="books.php" class="text-white hover:text-indigo-500">كتب</a></li>
                <li><a href="categories.php" class="text-white hover:text-indigo-500">فئات الكتب</a></li>
                <li><a href="people.php" class="text-white hover:text-indigo-500">أشخاص</a></li>
            </ul>
        </div>
    </div>

    <script>
        fetch('api/stats.php')
            .then(response => response.json())
            .then(data => {
                document.getElementById('book-count').innerText = data.bookCount;
                document.getElementById('category-count').innerText = data.categoryCount;
                document.getElementById('person-count').innerText = data.personCount;
            })
            .catch(error => console.error(error));
    </script>
</body>
</html>


This code uses Tailwind CSS for styling and includes a session check to redirect to the login page if the user is not authenticated. It also fetches stats dynamically via a JavaScript API call from the backend files. The dashboard layout includes a welcome message, logout button, overview stats grid, and quick links to manage modules. The color palette used is slate-900 and indigo-500.