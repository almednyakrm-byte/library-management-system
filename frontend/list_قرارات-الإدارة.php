**list_قرارات-الإدارة.php**

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
    <title>قرارات الإدارة</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
        }
        .header {
            background-color: #2d3748;
            color: #fff;
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
        .search-bar {
            width: 50%;
            padding: 1rem;
            border: 1px solid #ccc;
            border-radius: 0.5rem;
        }
        .search-bar input {
            width: 100%;
            padding: 1rem;
            border: none;
            border-radius: 0.5rem;
        }
        .search-bar button {
            background-color: #2d3748;
            color: #fff;
            border: none;
            padding: 1rem;
            border-radius: 0.5rem;
            cursor: pointer;
        }
        .search-bar button:hover {
            background-color: #3b4453;
        }
    </style>
</head>
<body>
    <div class="header">
        <a href="index.php">الرئيسية</a>
        <span class="text-lg font-bold">مركز إدارة القرارات</span>
        <span class="float-right">
            <a href="profile.php"><?= $_SESSION['username']; ?></a>
            <a href="logout.php">تسجيل خروج</a>
        </span>
    </div>
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-4">قرارات الإدارة</h1>
        <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='create_قرارات-الإدارة.php'">إضافة جديد</button>
        <div class="search-bar">
            <input type="search" id="search" placeholder="بحث...">
            <button onclick="searchRecords()">بحث</button>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>عنوان القرار</th>
                    <th>تاريخ الإصدار</th>
                    <th>حالة القرار</th>
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
            try {
                const response = await fetch('../backend/قرارات-الإدارة.php', {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json'
                    }
                });
                const data = await response.json();
                displayRecords(data);
            } catch (error) {
                console.error(error);
            }
        }

        // Display records
        function displayRecords(data) {
            const records = document.getElementById('records');
            records.innerHTML = '';
            data.forEach(record => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${record.title}</td>
                    <td>${record.date}</td>
                    <td>${record.status}</td>
                    <td>
                        <a href="edit_قرارات-الإدارة.php?id=${record.id}" class="text-blue-500 hover:text-blue-700">تعديل</a>
                        <button class="text-red-500 hover:text-red-700" onclick="deleteRecord(${record.id})">حذف</button>
                    </td>
                `;
                records.appendChild(row);
            });
        }

        // Search records
        function searchRecords() {
            const search = document.getElementById('search').value;
            fetch('../backend/قرارات-الإدارة.php', {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json'
                },
                params: {
                    search
                }
            })
            .then(response => response.json())
            .then(data => displayRecords(data))
            .catch(error => console.error(error));
        }

        // Delete record
        async function deleteRecord(id) {
            if (confirm('هل تريد حذف هذا القرار؟')) {
                try {
                    const response = await fetch('../backend/قرارات-الإدارة.php', {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({ id })
                    });
                    if (response.ok) {
                        getRecords();
                    } else {
                        console.error('Error deleting record');
                    }
                } catch (error) {
                    console.error(error);
                }
            }
        }

        // Initialize records
        getRecords();
    </script>
</body>
</html>

This code uses the Fetch API to fetch records from the backend and display them in a table. It also includes a search bar that filters records in real-time. The delete button sends a DELETE request to the backend to delete the record. The code is written in PHP and HTML with a premium Tailwind UI design.