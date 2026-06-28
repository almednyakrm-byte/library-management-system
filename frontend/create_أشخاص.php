**create_أشخاص.php**

<?php
// Session validation
if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit;
}

// Include header and navigation
require_once 'header.php';
require_once 'navigation.php';

// Include form script
require_once 'form_script.php';

?>

<div class="container mx-auto p-4 pt-6 md:p-6 lg:px-12 xl:px-24">
    <div class="bg-white rounded-lg shadow-md p-4">
        <h2 class="text-slate-900 font-bold text-lg mb-4">إضافة شخص جديد</h2>
        <form id="create-form" class="space-y-4">
            <div>
                <label for="name" class="text-slate-900 font-bold">اسم الشخص</label>
                <input type="text" id="name" name="name" class="w-full p-2 pl-10 text-slate-900 border border-slate-300 rounded-lg focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" required>
            </div>
            <div>
                <label for="email" class="text-slate-900 font-bold">بريد إلكتروني</label>
                <input type="email" id="email" name="email" class="w-full p-2 pl-10 text-slate-900 border border-slate-300 rounded-lg focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" required>
            </div>
            <div>
                <label for="phone" class="text-slate-900 font-bold">رقم الهاتف</label>
                <input type="tel" id="phone" name="phone" class="w-full p-2 pl-10 text-slate-900 border border-slate-300 rounded-lg focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" required>
            </div>
            <div>
                <label for="address" class="text-slate-900 font-bold">العنوان</label>
                <textarea id="address" name="address" class="w-full p-2 pl-10 text-slate-900 border border-slate-300 rounded-lg focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" required></textarea>
            </div>
            <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg">إضافة</button>
        </form>
    </div>
</div>

<?php
// Include footer
require_once 'footer.php';
?>


**form_script.php**

<script>
    $(document).ready(function() {
        $('#create-form').submit(function(e) {
            e.preventDefault();
            var formData = $(this).serialize();
            $.ajax({
                type: 'POST',
                url: '../backend/أشخاص.php',
                data: formData,
                success: function(response) {
                    if (response == 'success') {
                        window.location.href = 'list_أشخاص.php';
                    } else {
                        alert('Error: ' + response);
                    }
                },
                error: function(xhr, status, error) {
                    alert('Error: ' + error);
                }
            });
        });
    });
</script>


**backend/أشخاص.php**

<?php
// Check if form data is submitted
if (isset($_POST['name']) && isset($_POST['email']) && isset($_POST['phone']) && isset($_POST['address'])) {
    // Insert data into database
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];

    // Database connection
    $conn = new mysqli('localhost', 'username', 'password', 'database');

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // SQL query
    $sql = "INSERT INTO اشخاص (name, email, phone, address) VALUES ('$name', '$email', '$phone', '$address')";

    // Execute query
    if ($conn->query($sql) === TRUE) {
        echo 'success';
    } else {
        echo 'Error: ' . $sql . '<br>' . $conn->error;
    }

    // Close connection
    $conn->close();
}
?>