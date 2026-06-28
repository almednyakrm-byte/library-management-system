**list_موظفين-المكتبة.php**

<?php
// Session validation
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
    <title>موظفين المكتبة</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f9f9f9;
        }
        .header {
            background-color: #2d3748;
            padding: 1rem;
            text-align: center;
        }
        .header a {
            color: #fff;
            text-decoration: none;
        }
        .header a:hover {
            color: #ccc;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
        }
        .table th, .table td {
            border: 1px solid #ddd;
            padding: 1rem;
            text-align: left;
        }
        .table th {
            background-color: #2d3748;
            color: #fff;
        }
        .table td {
            background-color: #f9f9f9;
        }
        .table td a {
            color: #337ab7;
            text-decoration: none;
        }
        .table td a:hover {
            color: #23527c;
        }
        .search-bar {
            width: 50%;
            padding: 1rem;
            border: 1px solid #ccc;
            border-radius: 0.25rem;
        }
        .search-bar input[type="search"] {
            width: 100%;
            padding: 1rem;
            border: none;
            border-radius: 0.25rem;
        }
        .search-bar button[type="submit"] {
            background-color: #2d3748;
            color: #fff;
            border: none;
            padding: 1rem 2rem;
            border-radius: 0.25rem;
            cursor: pointer;
        }
        .search-bar button[type="submit"]:hover {
            background-color: #3b4157;
        }
    </style>
</head>
<body>
    <div class="header">
        <a href="index.php">الصفحة الرئيسية</a>
        <span class="text-lg font-bold text-slate-900">مركز المكتبة</span>
        <span class="text-lg font-bold text-indigo-500"><?= $_SESSION['username'] ?></span>
        <a href="logout.php" class="text-lg font-bold text-red-500">تسجيل الخروج</a>
    </div>
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold text-slate-900">موظفين المكتبة</h1>
        <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='create_موظفين-المكتبة.php'">إضافة جديد</button>
        <div class="search-bar">
            <input type="search" id="search" placeholder="بحث...">
            <button type="submit" onclick="searchRecords()">بحث</button>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>اسم الموظف</th>
                    <th>وظيفة</th>
                    <th>تاريخ الميلاد</th>
                    <th>تاريخ التعيين</th>
                    <th>إجراءات</th>
                </tr>
            </thead>
            <tbody id="records">
                <!-- Records will be displayed here -->
            </tbody>
        </table>
    </div>

    <script>
        // Fetch API to get records
        async function getRecords() {
            const response = await fetch('../backend/موظفين-المكتبة.php', {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json'
                }
            });
            const data = await response.json();
            return data;
        }

        // Search records
        function searchRecords() {
            const searchValue = document.getElementById('search').value;
            getRecords().then(data => {
                const records = document.getElementById('records');
                records.innerHTML = '';
                data.forEach(record => {
                    if (record.اسم_الموظف.toLowerCase().includes(searchValue.toLowerCase()) || record.وظيفة.toLowerCase().includes(searchValue.toLowerCase())) {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${record.اسم_الموظف}</td>
                            <td>${record.وظيفة}</td>
                            <td>${record.تاريخ_الميلاد}</td>
                            <td>${record.تاريخ_التعيين}</td>
                            <td>
                                <a href="edit_موظفين-المكتبة.php?id=${record.id}" class="text-lg font-bold text-indigo-500">تعديل</a>
                                <button class="text-lg font-bold text-red-500" onclick="deleteRecord(${record.id})">حذف</button>
                            </td>
                        `;
                        records.appendChild(row);
                    }
                });
            });
        }

        // Delete record
        async function deleteRecord(id) {
            const response = await fetch('../backend/موظفين-المكتبة.php', {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ id: id })
            });
            if (response.ok) {
                getRecords().then(data => {
                    const records = document.getElementById('records');
                    records.innerHTML = '';
                    data.forEach(record => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${record.اسم_الموظف}</td>
                            <td>${record.وظيفة}</td>
                            <td>${record.تاريخ_الميلاد}</td>
                            <td>${record.تاريخ_التعيين}</td>
                            <td>
                                <a href="edit_موظفين-المكتبة.php?id=${record.id}" class="text-lg font-bold text-indigo-500">تعديل</a>
                                <button class="text-lg font-bold text-red-500" onclick="deleteRecord(${record.id})">حذف</button>
                            </td>
                        `;
                        records.appendChild(row);
                    });
                });
            } else {
                alert('Error deleting record');
            }
        }

        // Get records on page load
        getRecords().then(data => {
            const records = document.getElementById('records');
            data.forEach(record => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${record.اسم_الموظف}</td>
                    <td>${record.وظيفة}</td>
                    <td>${record.تاريخ_الميلاد}</td>
                    <td>${record.تاريخ_التعيين}</td>
                    <td>
                        <a href="edit_موظفين-المكتبة.php?id=${record.id}" class="text-lg font-bold text-indigo-500">تعديل</a>
                        <button class="text-lg font-bold text-red-500" onclick="deleteRecord(${record.id})">حذف</button>
                    </td>
                `;
                records.appendChild(row);
            });
        });
    </script>
</body>
</html>

Note: This code assumes that you have a backend PHP script (`../backend/موظفين-المكتبة.php`) that handles GET and DELETE requests to retrieve and delete records, respectively. You will need to implement this backend script separately.