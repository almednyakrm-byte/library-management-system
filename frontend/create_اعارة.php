**create_اعارة.php**

<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Include database connection
require_once '../backend/db.php';

// Check if form has been submitted
if (isset($_POST['submit'])) {
    // Validate form data
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $start_date = trim($_POST['start_date']);
    $end_date = trim($_POST['end_date']);
    $status = trim($_POST['status']);

    // Insert data into database
    $query = "INSERT INTO اعارة (title, description, start_date, end_date, status) VALUES ('$title', '$description', '$start_date', '$end_date', '$status')";
    $result = mysqli_query($conn, $query);

    if ($result) {
        // Redirect back to list page
        header('Location: list_اعارة.php');
        exit;
    } else {
        echo 'Error inserting data';
    }
}

// Include header
require_once '../backend/header.php';

?>

<!-- Create new 'اعارة' record form -->
<div class="max-w-md mx-auto p-4 bg-white rounded-lg shadow-md">
    <h2 class="text-lg font-bold text-slate-900 mb-4">Create new 'اعارة' record</h2>
    <form id="create-form" method="post">
        <div class="mb-4">
            <label for="title" class="block text-sm font-medium text-slate-900">Title</label>
            <input type="text" id="title" name="title" class="block w-full p-2 pl-10 text-sm text-slate-900 placeholder-slate-400 border border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500" placeholder="Enter title">
        </div>
        <div class="mb-4">
            <label for="description" class="block text-sm font-medium text-slate-900">Description</label>
            <textarea id="description" name="description" class="block w-full p-2 pl-10 text-sm text-slate-900 placeholder-slate-400 border border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500" placeholder="Enter description"></textarea>
        </div>
        <div class="mb-4">
            <label for="start_date" class="block text-sm font-medium text-slate-900">Start Date</label>
            <input type="date" id="start_date" name="start_date" class="block w-full p-2 pl-10 text-sm text-slate-900 placeholder-slate-400 border border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500" placeholder="Enter start date">
        </div>
        <div class="mb-4">
            <label for="end_date" class="block text-sm font-medium text-slate-900">End Date</label>
            <input type="date" id="end_date" name="end_date" class="block w-full p-2 pl-10 text-sm text-slate-900 placeholder-slate-400 border border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500" placeholder="Enter end date">
        </div>
        <div class="mb-4">
            <label for="status" class="block text-sm font-medium text-slate-900">Status</label>
            <select id="status" name="status" class="block w-full p-2 pl-10 text-sm text-slate-900 placeholder-slate-400 border border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </select>
        </div>
        <button type="submit" name="submit" class="w-full px-4 py-2 text-sm font-medium text-white bg-indigo-500 border border-indigo-500 rounded-lg hover:bg-indigo-600 focus:ring-indigo-500 focus:border-indigo-500">Create</button>
    </form>
</div>

<!-- Include footer -->
<?php require_once '../backend/footer.php'; ?>

<script>
    // Send form data via AJAX
    document.getElementById('create-form').addEventListener('submit', function(event) {
        event.preventDefault();
        var formData = new FormData(this);
        fetch('../backend/اعارة.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.href = 'list_اعارة.php';
            } else {
                console.log(data.error);
            }
        })
        .catch(error => console.error('Error:', error));
    });
</script>

**backend/اعارة.php**

<?php
// Include database connection
require_once '../db.php';

// Check if form data has been sent
if (isset($_POST['submit'])) {
    // Validate form data
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $start_date = trim($_POST['start_date']);
    $end_date = trim($_POST['end_date']);
    $status = trim($_POST['status']);

    // Insert data into database
    $query = "INSERT INTO اعارة (title, description, start_date, end_date, status) VALUES ('$title', '$description', '$start_date', '$end_date', '$status')";
    $result = mysqli_query($conn, $query);

    if ($result) {
        // Return success message
        echo json_encode(array('success' => true));
    } else {
        // Return error message
        echo json_encode(array('error' => 'Error inserting data'));
    }
}