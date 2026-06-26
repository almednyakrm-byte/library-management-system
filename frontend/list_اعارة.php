**list_اعارة.php**

<?php
session_start();

// Check if user is authenticated
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>اعارة</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f7f7f7;
        }
        .header {
            background-color: #1a1d23;
            color: #fff;
        }
        .header .nav-link {
            color: #fff;
        }
        .header .nav-link:hover {
            color: #fff;
            text-decoration: none;
        }
        .table {
            background-color: #fff;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .table th, .table td {
            padding: 10px;
            border: 1px solid #ddd;
        }
        .table th {
            background-color: #f0f0f0;
        }
        .table .btn {
            background-color: #1a1d23;
            color: #fff;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
        }
        .table .btn:hover {
            background-color: #1a1d23;
            color: #fff;
        }
        .search-bar {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .search-bar input {
            padding: 10px;
            border: none;
            width: 100%;
            font-size: 16px;
        }
        .search-bar input:focus {
            outline: none;
        }
    </style>
</head>
<body>
    <div class="header">
        <nav class="container mx-auto p-4 flex justify-between items-center">
            <a href="index.php" class="nav-link">Home</a>
            <div class="flex items-center">
                <span class="text-lg font-bold text-slate-900"><?= $_SESSION['username'] ?></span>
                <button class="btn ml-4" onclick="location.href='logout.php'">Logout</button>
            </div>
        </nav>
    </div>
    <div class="container mx-auto p-4 mt-4">
        <button class="btn mb-4" onclick="location.href='create_اعارة.php'">Add New Item</button>
        <div class="search-bar mb-4">
            <input type="search" id="search" placeholder="Search...">
            <button class="btn" onclick="searchRecords()">Search</button>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Name</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="records">
                <?php
                // Fetch records from backend
                $records = fetchRecords();
                foreach ($records as $record) {
                    ?>
                    <tr>
                        <td><?= $record['id'] ?></td>
                        <td><?= $record['name'] ?></td>
                        <td>
                            <button class="btn mr-2" onclick="location.href='edit_اعارة.php?id=<?= $record['id'] ?>'">Edit</button>
                            <button class="btn" onclick="deleteRecord(<?= $record['id'] ?>)">Delete</button>
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
            const search = document.getElementById('search').value;
            fetch('../backend/اعارة.php?search=' + search)
                .then(response => response.json())
                .then(data => {
                    const records = document.getElementById('records');
                    records.innerHTML = '';
                    data.forEach(record => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${record.id}</td>
                            <td>${record.name}</td>
                            <td>
                                <button class="btn mr-2" onclick="location.href='edit_اعارة.php?id=${record.id}'">Edit</button>
                                <button class="btn" onclick="deleteRecord(${record.id})">Delete</button>
                            </td>
                        `;
                        records.appendChild(row);
                    });
                });
        }

        function deleteRecord(id) {
            if (confirm('Are you sure you want to delete this record?')) {
                fetch('../backend/اعارة.php', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id: id })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Record deleted successfully!');
                        location.reload();
                    } else {
                        alert('Error deleting record!');
                    }
                });
            }
        }

        function fetchRecords() {
            return fetch('../backend/اعارة.php')
                .then(response => response.json())
                .then(data => data.records);
        }
    </script>
</body>
</html>

<?php
function fetchRecords() {
    $url = '../backend/اعارة.php';
    $options = array(
        'http' => array(
            'method'  => 'GET',
            'header'  => 'Content-Type: application/json'
        )
    );
    $context  = stream_context_create($options);
    $response = json_decode(file_get_contents($url, false, $context), true);
    return $response['records'];
}
?>

**backend/اعارة.php**

<?php
// Fetch records from database
$records = array(
    array('id' => 1, 'name' => 'Record 1'),
    array('id' => 2, 'name' => 'Record 2'),
    array('id' => 3, 'name' => 'Record 3')
);

// Search functionality
if (isset($_GET['search'])) {
    $search = $_GET['search'];
    $records = array_filter($records, function($record) use ($search) {
        return strpos($record['name'], $search) !== false;
    });
}

// Delete record
if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $id = json_decode(file_get_contents('php://input'), true)['id'];
    // Delete record from database
    // ...
    echo json_encode(array('success' => true));
}

// Return records
echo json_encode(array('records' => $records));
?>

Note: This code assumes you have a backend PHP script (`backend/اعارة.php`) that fetches records from a database and handles search and delete functionality. You'll need to modify the backend script to match your actual database schema and functionality.