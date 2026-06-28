**list_كتب.php**

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
    <title>كتب</title>
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
            border-collapse: collapse;
            width: 100%;
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
        .search-bar input:focus {
            outline: none;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        }
    </style>
</head>
<body>
    <div class="header">
        <a href="index.php">الرئيسية</a>
        <span class="text-indigo-500">مرحباً, <?php echo $_SESSION['username']; ?></span>
        <a href="logout.php" class="text-indigo-500">تسجيل خروج</a>
    </div>
    <div class="container mx-auto p-4">
        <h1 class="text-3xl text-slate-900 mb-4">كتب</h1>
        <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='create_كتب.php'">إضافة كتاب جديد</button>
        <div class="flex justify-center mb-4">
            <input type="search" class="search-bar" placeholder="بحث" id="search-input">
            <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="searchBooks()">بحث</button>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>عنوان الكتاب</th>
                    <th>كاتب الكتاب</th>
                    <th>تاريخ النشر</th>
                    <th>إجراءات</th>
                </tr>
            </thead>
            <tbody id="book-list">
                <?php
                // Fetch list records from backend
                $response = file_get_contents('../backend/كتب.php');
                $books = json_decode($response, true);
                foreach ($books as $book) {
                    echo '<tr>';
                    echo '<td>' . $book['title'] . '</td>';
                    echo '<td>' . $book['author'] . '</td>';
                    echo '<td>' . $book['published_date'] . '</td>';
                    echo '<td>';
                    echo '<a href="edit_كتب.php?id=' . $book['id'] . '" class="text-indigo-500">تعديل</a>';
                    echo '<button class="text-red-500" onclick="deleteBook(' . $book['id'] . ')">حذف</button>';
                    echo '</td>';
                    echo '</tr>';
                }
                ?>
            </tbody>
        </table>
    </div>

    <script>
        function searchBooks() {
            const searchInput = document.getElementById('search-input');
            const searchQuery = searchInput.value.trim();
            if (searchQuery) {
                fetch('../backend/كتب.php?search=' + searchQuery)
                    .then(response => response.json())
                    .then(data => {
                        const bookList = document.getElementById('book-list');
                        bookList.innerHTML = '';
                        data.forEach(book => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td>${book.title}</td>
                                <td>${book.author}</td>
                                <td>${book.published_date}</td>
                                <td>
                                    <a href="edit_كتب.php?id=${book.id}" class="text-indigo-500">تعديل</a>
                                    <button class="text-red-500" onclick="deleteBook(${book.id})">حذف</button>
                                </td>
                            `;
                            bookList.appendChild(row);
                        });
                    });
            } else {
                fetch('../backend/كتب.php')
                    .then(response => response.json())
                    .then(data => {
                        const bookList = document.getElementById('book-list');
                        bookList.innerHTML = '';
                        data.forEach(book => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td>${book.title}</td>
                                <td>${book.author}</td>
                                <td>${book.published_date}</td>
                                <td>
                                    <a href="edit_كتب.php?id=${book.id}" class="text-indigo-500">تعديل</a>
                                    <button class="text-red-500" onclick="deleteBook(${book.id})">حذف</button>
                                </td>
                            `;
                            bookList.appendChild(row);
                        });
                    });
            }
        }

        function deleteBook(id) {
            if (confirm('هل أنت متأكد من حذف الكتاب؟')) {
                fetch('../backend/كتب.php', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id: id })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('تم حذف الكتاب بنجاح');
                        location.reload();
                    } else {
                        alert('حدث خطأ أثناء حذف الكتاب');
                    }
                });
            }
        }
    </script>
</body>
</html>

This code uses the Fetch API to fetch the list of books from the backend and display them in a table. It also includes a search bar that filters the list of books in real-time. The delete button sends a DELETE request to the backend to delete the book.