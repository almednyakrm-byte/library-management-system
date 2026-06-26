**create_كتب.php**

<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Include header and navigation
include 'header.php';
?>

<div class="container mx-auto p-4 pt-6 md:p-6 lg:px-12 xl:px-24">
    <div class="bg-white rounded-lg shadow-md p-4">
        <h2 class="text-slate-900 font-bold text-lg mb-4">إضافة كتاب جديد</h2>
        <form id="create-book-form" class="space-y-4">
            <div>
                <label for="title" class="text-slate-900 font-bold">العنوان</label>
                <input type="text" id="title" name="title" class="w-full p-2 pl-10 text-sm text-slate-900 border border-slate-300 rounded-lg focus:outline-none focus:border-indigo-500" required>
            </div>
            <div>
                <label for="author" class="text-slate-900 font-bold">المؤلف</label>
                <input type="text" id="author" name="author" class="w-full p-2 pl-10 text-sm text-slate-900 border border-slate-300 rounded-lg focus:outline-none focus:border-indigo-500" required>
            </div>
            <div>
                <label for="description" class="text-slate-900 font-bold">الوصف</label>
                <textarea id="description" name="description" class="w-full p-2 pl-10 text-sm text-slate-900 border border-slate-300 rounded-lg focus:outline-none focus:border-indigo-500" required></textarea>
            </div>
            <div>
                <label for="price" class="text-slate-900 font-bold">السعر</label>
                <input type="number" id="price" name="price" class="w-full p-2 pl-10 text-sm text-slate-900 border border-slate-300 rounded-lg focus:outline-none focus:border-indigo-500" required>
            </div>
            <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg">إضافة</button>
        </form>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#create-book-form').submit(function(e) {
            e.preventDefault();
            var formData = $(this).serialize();
            $.ajax({
                type: 'POST',
                url: '../backend/كتب.php',
                data: formData,
                success: function(response) {
                    if (response === 'success') {
                        window.location.href = 'list_كتب.php';
                    } else {
                        alert('Error adding book');
                    }
                }
            });
        });
    });
</script>

<?php
// Include footer
include 'footer.php';
?>


**Note:** This code assumes you have jQuery and a backend PHP script (`كتب.php`) to handle the form submission. You'll need to create these files and modify the code to fit your specific needs.