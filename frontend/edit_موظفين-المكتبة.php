<?php
// Session validation
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Get id from URL
$id = $_GET['id'];

// Include database connection
include '../backend/db.php';

// Check if id is valid
$query = "SELECT * FROM موظفين_المكتبة WHERE id = '$id'";
$result = mysqli_query($conn, $query);
if (mysqli_num_rows($result) == 0) {
    header('Location: list_موظفين-المكتبة.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تعديل موظفين المكتبة</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <div class="max-w-md mx-auto p-4 mt-10 bg-slate-100 rounded-lg shadow-md">
        <h2 class="text-2xl text-indigo-500 font-bold mb-4">تعديل موظفين المكتبة</h2>
        <form id="edit-form">
            <div class="mb-4">
                <label for="name" class="block text-sm text-slate-900 mb-2">اسم الموظف</label>
                <input type="text" id="name" name="name" class="block w-full p-2 text-slate-900 border border-slate-300 rounded-lg">
            </div>
            <div class="mb-4">
                <label for="email" class="block text-sm text-slate-900 mb-2">البريد الإلكتروني</label>
                <input type="email" id="email" name="email" class="block w-full p-2 text-slate-900 border border-slate-300 rounded-lg">
            </div>
            <div class="mb-4">
                <label for="phone" class="block text-sm text-slate-900 mb-2">رقم الهاتف</label>
                <input type="text" id="phone" name="phone" class="block w-full p-2 text-slate-900 border border-slate-300 rounded-lg">
            </div>
            <button type="submit" class="w-full p-2 bg-indigo-500 text-white rounded-lg hover:bg-indigo-700">تعديل</button>
        </form>
    </div>

    <script>
        // Fetch existing record details
        fetch('../backend/موظفين-المكتبة.php?id=<?= $id ?>')
            .then(response => response.json())
            .then(data => {
                document.getElementById('name').value = data.name;
                document.getElementById('email').value = data.email;
                document.getElementById('phone').value = data.phone;
            });

        // Submit form using AJAX
        document.getElementById('edit-form').addEventListener('submit', (e) => {
            e.preventDefault();
            const formData = new FormData(e.target);
            fetch('../backend/موظفين-المكتبة.php', {
                method: 'PUT',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = 'list_موظفين-المكتبة.php';
                } else {
                    alert('Error updating record');
                }
            })
            .catch(error => console.error('Error:', error));
        });
    </script>
</body>
</html>