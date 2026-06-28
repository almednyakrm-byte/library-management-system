**list_فئات-الكتب.php**

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
    <title>فئات الكتب</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
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
            padding: 1rem 2rem;
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
        <a href="index.php">الصفحة الرئيسية</a>
        <span class="text-white">مرحباً <?= $_SESSION['username'] ?></span>
        <a href="logout.php" class="text-white">تسجيل الخروج</a>
    </div>
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-4">فئات الكتب</h1>
        <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='create_فئات-الكتب.php'">إضافة فئة جديدة</button>
        <div class="search-bar mt-4">
            <input type="search" id="search-input" placeholder="بحث...">
            <button onclick="searchRecords()">بحث</button>
        </div>
        <table class="table mt-4">
            <thead>
                <tr>
                    <th>اسم الفئة</th>
                    <th>حذف</th>
                    <th>تعديل</th>
                </tr>
            </thead>
            <tbody id="records-table">
                <!-- Records will be loaded here -->
            </tbody>
        </table>
    </div>

    <script>
        const searchInput = document.getElementById('search-input');
        const recordsTable = document.getElementById('records-table');

        function searchRecords() {
            const searchQuery = searchInput.value.trim();
            if (searchQuery) {
                fetch('../backend/فئات-الكتب.php?search=' + searchQuery)
                    .then(response => response.json())
                    .then(data => {
                        recordsTable.innerHTML = '';
                        data.forEach(record => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td>${record.name}</td>
                                <td><button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="deleteRecord(${record.id})">حذف</button></td>
                                <td><a href="edit_فئات-الكتب.php?id=${record.id}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">تعديل</a></td>
                            `;
                            recordsTable.appendChild(row);
                        });
                    });
            } else {
                fetch('../backend/فئات-الكتب.php')
                    .then(response => response.json())
                    .then(data => {
                        recordsTable.innerHTML = '';
                        data.forEach(record => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td>${record.name}</td>
                                <td><button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="deleteRecord(${record.id})">حذف</button></td>
                                <td><a href="edit_فئات-الكتب.php?id=${record.id}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">تعديل</a></td>
                            `;
                            recordsTable.appendChild(row);
                        });
                    });
            }
        }

        function deleteRecord(id) {
            if (confirm('هل أنت متأكد من حذف الفئة؟')) {
                fetch('../backend/فئات-الكتب.php', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id: id })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('تم حذف الفئة بنجاح');
                        searchRecords();
                    } else {
                        alert('حدث خطأ أثناء حذف الفئة');
                    }
                });
            }
        }

        searchRecords();
    </script>
</body>
</html>

**backend/فئات-الكتب.php**

<?php
// Get search query from URL
$searchQuery = $_GET['search'] ?? '';

// Connect to database
$conn = new mysqli('localhost', 'username', 'password', 'database');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get records from database
if ($searchQuery) {
    $sql = "SELECT * FROM فئات_الكتب WHERE name LIKE '%$searchQuery%'";
} else {
    $sql = "SELECT * FROM فئات_الكتب";
}

$result = $conn->query($sql);

// Fetch records
$records = array();
while ($row = $result->fetch_assoc()) {
    $records[] = $row;
}

// Close connection
$conn->close();

// Output records in JSON format
echo json_encode($records);
?>

Note: This is a basic example and you should adjust it according to your needs and database schema. Also, make sure to replace the placeholders with your actual database credentials and table name.