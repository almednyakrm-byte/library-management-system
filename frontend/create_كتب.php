<?php
// Start session
session_start();

// Session validation
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Include database connection
include '../backend/db.php';

// Get module slug
$mod_slug = 'كتب';

// Get current user
$current_user = $_SESSION['username'];

// Page title
$page_title = 'Create ' . $mod_slug;

// Include header
include 'header.php';
?>

<!-- Content -->
<div class="container mx-auto p-4 pt-6 md:p-6 lg:p-12 xl:p-24">
    <h1 class="text-3xl text-slate-900 font-bold mb-4"><?= $page_title ?></h1>
    <form id="create-form" class="bg-white rounded shadow-md p-4 md:p-6 lg:p-8">
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
            <div>
                <label for="title" class="block text-sm text-slate-900 font-bold mb-2">Title</label>
                <input type="text" id="title" name="title" class="block w-full p-2 pl-10 text-sm text-gray-700 border border-gray-200 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" placeholder="Enter title">
            </div>
            <div>
                <label for="author" class="block text-sm text-slate-900 font-bold mb-2">Author</label>
                <input type="text" id="author" name="author" class="block w-full p-2 pl-10 text-sm text-gray-700 border border-gray-200 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" placeholder="Enter author">
            </div>
            <div>
                <label for="publisher" class="block text-sm text-slate-900 font-bold mb-2">Publisher</label>
                <input type="text" id="publisher" name="publisher" class="block w-full p-2 pl-10 text-sm text-gray-700 border border-gray-200 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" placeholder="Enter publisher">
            </div>
            <div>
                <label for="year" class="block text-sm text-slate-900 font-bold mb-2">Year</label>
                <input type="number" id="year" name="year" class="block w-full p-2 pl-10 text-sm text-gray-700 border border-gray-200 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" placeholder="Enter year">
            </div>
            <div>
                <label for="description" class="block text-sm text-slate-900 font-bold mb-2">Description</label>
                <textarea id="description" name="description" class="block w-full p-2 pl-10 text-sm text-gray-700 border border-gray-200 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" placeholder="Enter description"></textarea>
            </div>
        </div>
        <button type="submit" class="mt-4 py-2 px-4 bg-indigo-500 text-white rounded hover:bg-indigo-700 focus:outline-none focus:ring-indigo-500">Create</button>
    </form>
</div>

<!-- Include footer -->
<?php include 'footer.php'; ?>

<!-- AJAX JavaScript -->
<script>
    $(document).ready(function() {
        $('#create-form').submit(function(e) {
            e.preventDefault();
            var formData = $(this).serialize();
            $.ajax({
                type: 'POST',
                url: '../backend/<?= $mod_slug ?>.php',
                data: formData,
                success: function(response) {
                    window.location.href = 'list_<?= $mod_slug ?>.php';
                }
            });
        });
    });
</script>