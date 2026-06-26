**create_فئات-الكتب.php**

<?php
// Session validation
if (!isset($_SESSION['admin'])) {
    header('Location: ../login.php');
    exit;
}

// Include header
include '../includes/header.php';

// Include CSS and JS files
?>
<link rel="stylesheet" href="../css/tailwind.css">
<link rel="stylesheet" href="../css/premium-tailwind.css">
<script src="../js/jquery.min.js"></script>
<script src="../js/tailwind.js"></script>

<!-- Main content -->
<div class="container mx-auto p-4 mt-12">
    <h1 class="text-3xl font-bold text-slate-900 mb-4">إضافة فئة كتاب جديدة</h1>
    <form id="create-category-form" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="name">اسم الفئة</label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="name" type="text" placeholder="اسم الفئة">
        </div>
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="description">وصف الفئة</label>
            <textarea class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="description" rows="4" placeholder="وصف الفئة"></textarea>
        </div>
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="status">حالة الفئة</label>
            <select class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="status">
                <option value="1">مفعل</option>
                <option value="0">مغلق</option>
            </select>
        </div>
        <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">حفظ</button>
    </form>
</div>

<!-- AJAX script -->
<script>
    $(document).ready(function() {
        $('#create-category-form').submit(function(e) {
            e.preventDefault();
            var formData = $(this).serialize();
            $.ajax({
                type: 'POST',
                url: '../backend/فئات-الكتب.php',
                data: formData,
                success: function(response) {
                    if (response == 'success') {
                        window.location.href = '../list_فئات-الكتب.php';
                    } else {
                        alert('حدث خطأ أثناء الحفظ');
                    }
                }
            });
        });
    });
</script>

<!-- Include footer -->
<?php
include '../includes/footer.php';
?>


**../backend/فئات-الكتب.php**

<?php
// Include database connection
include '../includes/db.php';

// Check if form data is sent
if (isset($_POST['name']) && isset($_POST['description']) && isset($_POST['status'])) {
    // Insert data into database
    $name = $_POST['name'];
    $description = $_POST['description'];
    $status = $_POST['status'];
    $query = "INSERT INTO فئات_الكتب (اسم_الفئة, وصف_الفئة, حالة_الفئة) VALUES ('$name', '$description', '$status')";
    $result = mysqli_query($conn, $query);
    if ($result) {
        echo 'success';
    } else {
        echo 'error';
    }
} else {
    echo 'error';
}
?>