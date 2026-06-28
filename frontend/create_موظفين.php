**create_موظفين.php**

<?php
// Session validation
if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit;
}

// Include header and navigation
include 'header.php';
include 'navigation.php';
?>

<div class="container mx-auto p-4 pt-6 md:p-6 lg:px-12 xl:px-24">
    <div class="bg-white rounded-lg shadow-md p-4 md:p-6 lg:p-8 xl:p-12">
        <h2 class="text-slate-900 font-bold text-lg mb-4">إضافة موظف</h2>
        <form id="create-employee-form" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="name" class="text-slate-900 font-bold text-sm mb-2">اسم الموظف</label>
                    <input type="text" id="name" name="name" class="w-full p-2 text-sm text-slate-900 border border-slate-300 rounded-lg focus:outline-none focus:border-indigo-500" required>
                </div>
                <div>
                    <label for="email" class="text-slate-900 font-bold text-sm mb-2">البريد الإلكتروني</label>
                    <input type="email" id="email" name="email" class="w-full p-2 text-sm text-slate-900 border border-slate-300 rounded-lg focus:outline-none focus:border-indigo-500" required>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="phone" class="text-slate-900 font-bold text-sm mb-2">رقم الهاتف</label>
                    <input type="tel" id="phone" name="phone" class="w-full p-2 text-sm text-slate-900 border border-slate-300 rounded-lg focus:outline-none focus:border-indigo-500" required>
                </div>
                <div>
                    <label for="position" class="text-slate-900 font-bold text-sm mb-2">الوظيفة</label>
                    <input type="text" id="position" name="position" class="w-full p-2 text-sm text-slate-900 border border-slate-300 rounded-lg focus:outline-none focus:border-indigo-500" required>
                </div>
            </div>
            <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg">إضافة</button>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script>
    $(document).ready(function() {
        $('#create-employee-form').submit(function(e) {
            e.preventDefault();
            var formData = $(this).serialize();
            $.ajax({
                type: 'POST',
                url: '../backend/موظفين.php',
                data: formData,
                success: function(response) {
                    if (response === 'success') {
                        window.location.href = 'list_موظفين.php';
                    } else {
                        alert('Error: ' + response);
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


**موظفين.php (backend)**

<?php
// Include database connection
include 'database.php';

// Check if form data is submitted
if (isset($_POST['name']) && isset($_POST['email']) && isset($_POST['phone']) && isset($_POST['position'])) {
    // Prepare SQL query
    $sql = "INSERT INTO موظفين (name, email, phone, position) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $_POST['name'], $_POST['email'], $_POST['phone'], $_POST['position']);
    // Execute query
    if ($stmt->execute()) {
        echo 'success';
    } else {
        echo 'Error: ' . $stmt->error;
    }
    $stmt->close();
}
?>