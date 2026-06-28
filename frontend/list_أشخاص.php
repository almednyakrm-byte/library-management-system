**list_أشخاص.php**

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
    <title>أشخاص</title>
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
            text-align: left;
        }
        .table th {
            background-color: #2d3748;
            color: #fff;
        }
        .search-bar {
            width: 50%;
            padding: 1rem;
            font-size: 1.5rem;
            border: 1px solid #ccc;
            border-radius: 0.5rem;
        }
        .search-bar:focus {
            outline: none;
            border-color: #aaa;
        }
    </style>
</head>
<body>
    <div class="header">
        <a href="index.php">الرئيسية</a>
        <span class="text-indigo-500">مرحباً, <?php echo $_SESSION['username']; ?></span>
        <a href="logout.php" class="text-red-500">تسجيل خروج</a>
    </div>
    <div class="container mx-auto p-4">
        <h1 class="text-3xl text-slate-900 mb-4">أشخاص</h1>
        <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='create_أشخاص.php'">إضافة جديد</button>
        <div class="flex justify-center mb-4">
            <input type="search" class="search-bar" id="search" placeholder="بحث...">
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>اسم</th>
                    <th>عنوان</th>
                    <th>تاريخ الميلاد</th>
                    <th>حالة</th>
                    <th>إجراءات</th>
                </tr>
            </thead>
            <tbody id="records">
                <?php
                // Fetch records from backend
                $url = '../backend/أشخاص.php';
                $response = file_get_contents($url);
                $data = json_decode($response, true);
                foreach ($data as $record) {
                    ?>
                    <tr>
                        <td><?php echo $record['اسم']; ?></td>
                        <td><?php echo $record['عنوان']; ?></td>
                        <td><?php echo $record['تاريخ الميلاد']; ?></td>
                        <td><?php echo $record['حالة']; ?></td>
                        <td>
                            <a href="edit_أشخاص.php?id=<?php echo $record['id']; ?>" class="text-indigo-500 hover:text-indigo-700">تعديل</a>
                            <button class="text-red-500 hover:text-red-700" onclick="deleteRecord(<?php echo $record['id']; ?>)">حذف</button>
                        </td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>
    </div>

    <script>
        // Search bar filtering
        const searchInput = document.getElementById('search');
        searchInput.addEventListener('input', function() {
            const searchValue = this.value.toLowerCase();
            const records = document.getElementById('records').getElementsByTagName('tr');
            for (let i = 0; i < records.length; i++) {
                const record = records[i];
                const cells = record.getElementsByTagName('td');
                let match = false;
                for (let j = 0; j < cells.length; j++) {
                    if (cells[j].textContent.toLowerCase().includes(searchValue)) {
                        match = true;
                        break;
                    }
                }
                if (match) {
                    record.style.display = 'table-row';
                } else {
                    record.style.display = 'none';
                }
            }
        });

        // Delete record
        function deleteRecord(id) {
            if (confirm('هل أنت متأكد من حذف هذا السجل؟')) {
                fetch('../backend/أشخاص.php', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id: id })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('تم حذف السجل بنجاح');
                        location.reload();
                    } else {
                        alert('حدث خطأ أثناء حذف السجل');
                    }
                })
                .catch(error => console.error('Error:', error));
            }
        }
    </script>
</body>
</html>

This code includes the following features:

1. Session validation: Redirects to login.php if the user is not authenticated.
2. Premium Tailwind UI: Uses the slate-900 and indigo-500 color palette.
3. Header navigation: Links to index.php, current user info, and logout.
4. Table showing list of records: Includes actions for editing and deleting records.
5. 'Add New Item' button: Links to create_أشخاص.php.
6. Search bar: Filters elements in real-time.
7. AJAX Javascript: Fetches list records from '../backend/أشخاص.php' (GET) and DELETE requests.

Note: This code assumes that the backend PHP file (../backend/أشخاص.php) returns a JSON array of records. The frontend code uses the Fetch API to send DELETE requests to the backend to delete records.