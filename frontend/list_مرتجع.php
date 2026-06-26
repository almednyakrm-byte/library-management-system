**list_مرتجع.php**

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
    <title>مرتجع</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', sans-serif;
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
        <span class="text-lg font-bold">مرتجع</span>
        <a href="profile.php">حسناً</a>
        <a href="logout.php">تسجيل الخروج</a>
    </div>
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-4">قائمة مرتجع</h1>
        <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='create_مرتجع.php'">إضافة جديد</button>
        <div class="search-bar">
            <input type="search" id="search" placeholder="بحث...">
            <button onclick="searchRecords()">بحث</button>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>رقم المرجوع</th>
                    <th>اسم المرجوع</th>
                    <th>تاريخ المرجوع</th>
                    <th>حالة المرجوع</th>
                    <th>إجراءات</th>
                </tr>
            </thead>
            <tbody id="records">
                <?php
                // Fetch records from backend
                $records = fetchRecords();
                foreach ($records as $record) {
                    ?>
                    <tr>
                        <td><?php echo $record['id']; ?></td>
                        <td><?php echo $record['name']; ?></td>
                        <td><?php echo $record['date']; ?></td>
                        <td><?php echo $record['status']; ?></td>
                        <td>
                            <a href="edit_مرتجع.php?id=<?php echo $record['id']; ?>" class="bg-slate-900 hover:bg-slate-700 text-white font-bold py-2 px-4 rounded">تعديل</a>
                            <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="deleteRecord(<?php echo $record['id']; ?>)">حذف</button>
                        </td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>
    </div>

    <script>
        function searchRecords() {
            const searchInput = document.getElementById('search');
            const searchValue = searchInput.value;
            fetch('../backend/مرتجع.php?search=' + searchValue)
                .then(response => response.json())
                .then(data => {
                    const records = document.getElementById('records');
                    records.innerHTML = '';
                    data.forEach(record => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${record.id}</td>
                            <td>${record.name}</td>
                            <td>${record.date}</td>
                            <td>${record.status}</td>
                            <td>
                                <a href="edit_مرتجع.php?id=${record.id}" class="bg-slate-900 hover:bg-slate-700 text-white font-bold py-2 px-4 rounded">تعديل</a>
                                <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="deleteRecord(${record.id})">حذف</button>
                            </td>
                        `;
                        records.appendChild(row);
                    });
                });
        }

        function deleteRecord(id) {
            if (confirm('هل تريد حذف هذا السجل؟')) {
                fetch('../backend/مرتجع.php?id=' + id, { method: 'DELETE' })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('تم حذف السجل بنجاح');
                            window.location.reload();
                        } else {
                            alert('حدث خطأ أثناء حذف السجل');
                        }
                    })
                    .catch(error => console.error(error));
            }
        }

        function fetchRecords() {
            return fetch('../backend/مرتجع.php')
                .then(response => response.json())
                .then(data => data.records);
        }
    </script>
</body>
</html>


**backend/مرتجع.php**

<?php
// Fetch records from database
$records = array();
$records[] = array(
    'id' => 1,
    'name' => 'سجل المرجوع الأول',
    'date' => '2022-01-01',
    'status' => 'مفعل'
);
$records[] = array(
    'id' => 2,
    'name' => 'سجل المرجوع الثاني',
    'date' => '2022-01-15',
    'status' => 'مفعل'
);
$records[] = array(
    'id' => 3,
    'name' => 'سجل المرجوع الثالث',
    'date' => '2022-02-01',
    'status' => 'مفعل'
);

// Search functionality
if (isset($_GET['search'])) {
    $searchValue = $_GET['search'];
    $records = array_filter($records, function ($record) use ($searchValue) {
        return strpos($record['name'], $searchValue) !== false;
    });
}

// Output records in JSON format
header('Content-Type: application/json');
echo json_encode(array('records' => $records));


This code assumes that you have a backend script (`backend/مرتجع.php`) that fetches records from a database and outputs them in JSON format. The frontend script (`list_مرتجع.php`) uses the Fetch API to fetch records from the backend and displays them in a table. The search functionality is implemented using the `searchRecords()` function, which fetches records from the backend with a search query. The delete functionality is implemented using the `deleteRecord()` function, which sends a DELETE request to the backend to delete a record.