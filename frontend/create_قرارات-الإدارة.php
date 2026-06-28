**create_قرارات-الإدارة.php**

<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header('Location: login.php');
    exit;
}

// Include database connection
require_once '../config/database.php';

// Check if form has been submitted
if (isset($_POST['submit'])) {
    // Sanitize input data
    $title = filter_var($_POST['title'], FILTER_SANITIZE_STRING);
    $description = filter_var($_POST['description'], FILTER_SANITIZE_STRING);
    $date = filter_var($_POST['date'], FILTER_SANITIZE_STRING);
    $status = filter_var($_POST['status'], FILTER_SANITIZE_STRING);

    // Insert data into database
    $query = "INSERT INTO قرارات_الإدارة (title, description, date, status) VALUES (?, ?, ?, ?)";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("ssss", $title, $description, $date, $status);
    $stmt->execute();

    // Redirect back to list page
    header('Location: list_قرارات-الإدارة.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إضافة قراري الإدارة</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="max-w-md mx-auto p-4 bg-white rounded-lg shadow-md">
        <h2 class="text-2xl font-bold text-slate-900 mb-4">إضافة قراري الإدارة</h2>
        <form id="create-form" class="space-y-4" method="POST">
            <div class="flex flex-wrap -mx-3 mb-6">
                <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                    <label for="title" class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">عنوان القرار</label>
                    <input type="text" id="title" name="title" class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" placeholder="عنوان القرار">
                </div>
                <div class="w-full md:w-1/2 px-3">
                    <label for="description" class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">وصف القرار</label>
                    <textarea id="description" name="description" class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" placeholder="وصف القرار"></textarea>
                </div>
            </div>
            <div class="flex flex-wrap -mx-3 mb-6">
                <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                    <label for="date" class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">تاريخ القرار</label>
                    <input type="date" id="date" name="date" class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" placeholder="تاريخ القرار">
                </div>
                <div class="w-full md:w-1/2 px-3">
                    <label for="status" class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">حالة القرار</label>
                    <select id="status" name="status" class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500">
                        <option value="مفعل">مفعل</option>
                        <option value="غير مفعل">غير مفعل</option>
                    </select>
                </div>
            </div>
            <button type="submit" id="submit-btn" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">حفظ</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#create-form').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    type: 'POST',
                    url: '../backend/قرارات-الإدارة.php',
                    data: $(this).serialize(),
                    success: function(response) {
                        if (response === 'success') {
                            window.location.href = 'list_قرارات-الإدارة.php';
                        } else {
                            alert('Error: ' + response);
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>

Note: This code assumes that you have a database connection established in `../config/database.php` and a `قرارات_الإدارة` table with columns `title`, `description`, `date`, and `status`. You may need to modify the code to fit your specific database schema and requirements.