**edit_اعارة.php**

<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Get id from URL
$id = $_GET['id'];

// Fetch existing record details via GET
$record = json_decode(file_get_contents('../backend/اعارة.php?id=' . $id), true);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit اعارة</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDzfgbWSSxoLHrNwNwOgKlmWHRsye" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            background-color: #f5f5f5;
        }
        .container {
            max-width: 500px;
            margin: 40px auto;
            padding: 20px;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 10px;
        }
        .form-group input, .form-group select {
            width: 100%;
            height: 40px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .form-group input[type="submit"] {
            background-color: #3498db;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .form-group input[type="submit"]:hover {
            background-color: #2980b9;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Edit اعارة</h2>
        <form id="edit-form">
            <div class="form-group">
                <label for="title">Title:</label>
                <input type="text" id="title" name="title" value="<?= $record['title'] ?>">
            </div>
            <div class="form-group">
                <label for="description">Description:</label>
                <textarea id="description" name="description"><?= $record['description'] ?></textarea>
            </div>
            <div class="form-group">
                <label for="status">Status:</label>
                <select id="status" name="status">
                    <option value="active" <?= $record['status'] == 'active' ? 'selected' : '' ?>>Active</option>
                    <option value="inactive" <?= $record['status'] == 'inactive' ? 'selected' : '' ?>>Inactive</option>
                </select>
            </div>
            <div class="form-group">
                <input type="submit" value="Update">
            </div>
        </form>
    </div>

    <script>
        $(document).ready(function() {
            // Populate form fields
            $('#title').val('<?= $record['title'] ?>');
            $('#description').val('<?= $record['description'] ?>');
            $('#status').val('<?= $record['status'] ?>');

            // Submit form via AJAX
            $('#edit-form').submit(function(e) {
                e.preventDefault();
                var formData = $(this).serialize();
                $.ajax({
                    type: 'PUT',
                    url: '../backend/اعارة.php',
                    data: formData,
                    success: function(response) {
                        Swal.fire({
                            title: 'Updated!',
                            text: 'Record updated successfully.',
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = 'list_<?= $_SESSION['mod_slug'] ?>.php';
                            }
                        });
                    },
                    error: function(xhr, status, error) {
                        Swal.fire({
                            title: 'Error!',
                            text: 'Failed to update record.',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                });
            });
        });
    </script>
</body>
</html>

**Note:** Make sure to replace `../backend/اعارة.php` with the actual URL of your backend script. Also, ensure that the `mod_slug` variable is set in the session.