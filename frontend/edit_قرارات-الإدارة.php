**edit_قرارات-الإدارة.php**

<?php
session_start();

// Validate session
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Get ID from URL
$id = $_GET['id'];

// Fetch existing record details via GET
$url = '../backend/قرارات-الإدارة.php?id=' . $id;
$response = file_get_contents($url);
$data = json_decode($response, true);

?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تعديل قرارات الإدارة</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/jquery.validate.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation-unobtrusive@3.2.12/dist/jquery.validate.unobtrusive.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.10/dist/sweetalert2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios@0.27.2/dist/axios.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 800px;
            margin: 40px auto;
            background-color: #fff;
            padding: 20px;
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
            color: #333;
        }
        .form-group input, .form-group select {
            width: 100%;
            height: 40px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .form-group textarea {
            width: 100%;
            height: 100px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .btn-primary {
            background-color: #3498db;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }
        .btn-primary:hover {
            background-color: #2980b9;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="text-center mb-4">تعديل قرارات الإدارة</h2>
        <form id="edit-form" method="post">
            <div class="form-group">
                <label for="title">العنوان</label>
                <input type="text" id="title" name="title" value="<?= $data['title'] ?>">
            </div>
            <div class="form-group">
                <label for="description">الوصف</label>
                <textarea id="description" name="description"><?= $data['description'] ?></textarea>
            </div>
            <div class="form-group">
                <label for="date">التاريخ</label>
                <input type="date" id="date" name="date" value="<?= $data['date'] ?>">
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary">تعديل</button>
            </div>
        </form>
    </div>

    <script>
        $(document).ready(function() {
            // Fetch existing record details via GET
            axios.get('../backend/قرارات-الإدارة.php?id=' + <?= $id ?>)
                .then(response => {
                    const data = response.data;
                    $('#title').val(data.title);
                    $('#description').val(data.description);
                    $('#date').val(data.date);
                })
                .catch(error => {
                    console.error(error);
                });

            // Validate form on submit
            $('#edit-form').validate({
                rules: {
                    title: {
                        required: true
                    },
                    description: {
                        required: true
                    },
                    date: {
                        required: true
                    }
                },
                messages: {
                    title: {
                        required: 'الرجاء إدخال العنوان'
                    },
                    description: {
                        required: 'الرجاء إدخال الوصف'
                    },
                    date: {
                        required: 'الرجاء إدخال التاريخ'
                    }
                }
            });

            // Submit form via AJAX PUT request
            $('#edit-form').submit(function(event) {
                event.preventDefault();
                const formData = new FormData(this);
                axios.put('../backend/قرارات-الإدارة.php', formData)
                    .then(response => {
                        if (response.data.success) {
                            Swal.fire({
                                title: 'نجاح',
                                text: 'تم تعديل القرار بنجاح',
                                icon: 'success'
                            }).then(() => {
                                window.location.href = 'list_قرارات-الإدارة.php';
                            });
                        } else {
                            Swal.fire({
                                title: 'فشل',
                                text: 'حدث خطأ أثناء تعديل القرار',
                                icon: 'error'
                            });
                        }
                    })
                    .catch(error => {
                        console.error(error);
                    });
            });
        });
    </script>
</body>
</html>

Note: This code assumes that you have a backend PHP script (`قرارات-الإدارة.php`) that handles the GET and PUT requests. The backend script should return the existing record details in JSON format and update the record accordingly.