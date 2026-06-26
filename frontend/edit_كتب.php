**edit_كتب.php**

<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Get ID from URL
$id = $_GET['id'];

// Fetch existing record details
$data = json_decode(file_get_contents('../backend/كتب.php?id=' . $id), true);

// Check if data exists
if (empty($data)) {
    echo 'Error: Record not found.';
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Book</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+DwXbhAgpSQR9ebcQomQFkh1+VN7IlZ" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDzfgbWSSxoLHrNwNwOgKlmWHRsye" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.10/dist/sweetalert2.all.min.js"></script>
    <style>
        body {
            background-color: #f5f5f5;
        }
        .bg-slate-900 {
            background-color: #1a1d23;
        }
        .text-indigo-500 {
            color: #6b7280;
        }
    </style>
</head>
<body>
    <div class="container mx-auto p-4 mt-10 bg-slate-900 rounded-lg">
        <h2 class="text-3xl text-indigo-500 font-bold mb-4">Edit Book</h2>
        <form id="edit-book-form">
            <div class="mb-4">
                <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                <input type="text" id="title" name="title" class="block w-full px-4 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" value="<?php echo $data['title']; ?>">
            </div>
            <div class="mb-4">
                <label for="author" class="block text-sm font-medium text-gray-700">Author</label>
                <input type="text" id="author" name="author" class="block w-full px-4 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" value="<?php echo $data['author']; ?>">
            </div>
            <div class="mb-4">
                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                <textarea id="description" name="description" class="block w-full px-4 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" rows="4"><?php echo $data['description']; ?></textarea>
            </div>
            <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">Update Book</button>
        </form>
    </div>

    <script>
        $(document).ready(function() {
            // Fetch existing record details via GET
            $.ajax({
                type: 'GET',
                url: '../backend/كتب.php?id=<?php echo $id; ?>',
                dataType: 'json',
                success: function(data) {
                    // Populate form fields
                    $('#title').val(data.title);
                    $('#author').val(data.author);
                    $('#description').val(data.description);
                }
            });

            // Submit form via AJAX PUT request
            $('#edit-book-form').submit(function(event) {
                event.preventDefault();
                var formData = $(this).serialize();
                $.ajax({
                    type: 'PUT',
                    url: '../backend/كتب.php',
                    data: formData,
                    success: function(data) {
                        Swal.fire({
                            title: 'Book updated successfully!',
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = 'list_كتب.php';
                            }
                        });
                    }
                });
            });
        });
    </script>
</body>
</html>

**Note:** Make sure to replace `../backend/كتب.php` with the actual URL of your backend script. Also, this code assumes that the backend script returns the existing record details in JSON format.