**edit_أشخاص.php**

<?php
// Session validation
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Get the ID from URL
$id = $_GET['id'];

// Fetch the existing record details via GET
$url = '../backend/أشخاص.php?id=' . $id;
$response = file_get_contents($url);
$data = json_decode($response, true);

// Check if the record exists
if (empty($data)) {
    echo 'Error: Record not found.';
    exit;
}

// Set the page title and mod slug
$page_title = 'Edit ' . $data['name'];
$mod_slug = 'أشخاص';

// Include the header and navigation
include 'header.php';
?>

<!-- Main content -->
<main class="max-w-7xl mx-auto p-4 sm:p-6 lg:p-8">
    <h1 class="text-3xl font-bold leading-tight text-slate-900 mb-4"><?= $page_title ?></h1>

    <!-- Form -->
    <form id="edit-form" class="bg-white rounded shadow-md p-4">
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
            <div>
                <label for="name" class="block text-sm font-medium text-slate-900">Name</label>
                <input type="text" id="name" name="name" class="block w-full p-2 text-sm text-slate-900 border border-slate-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500" value="<?= $data['name'] ?>">
            </div>
            <div>
                <label for="email" class="block text-sm font-medium text-slate-900">Email</label>
                <input type="email" id="email" name="email" class="block w-full p-2 text-sm text-slate-900 border border-slate-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500" value="<?= $data['email'] ?>">
            </div>
            <div>
                <label for="phone" class="block text-sm font-medium text-slate-900">Phone</label>
                <input type="tel" id="phone" name="phone" class="block w-full p-2 text-sm text-slate-900 border border-slate-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500" value="<?= $data['phone'] ?>">
            </div>
            <div>
                <label for="address" class="block text-sm font-medium text-slate-900">Address</label>
                <textarea id="address" name="address" class="block w-full p-2 text-sm text-slate-900 border border-slate-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500"><?= $data['address'] ?></textarea>
            </div>
        </div>

        <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">Update</button>
    </form>
</main>

<!-- JavaScript -->
<script>
    // Fetch the existing record details via GET
    fetch('../backend/أشخاص.php?id=<?= $id ?>')
        .then(response => response.json())
        .then(data => {
            // Populate the form fields
            document.getElementById('name').value = data.name;
            document.getElementById('email').value = data.email;
            document.getElementById('phone').value = data.phone;
            document.getElementById('address').value = data.address;
        })
        .catch(error => console.error(error));

    // Handle form submission
    document.getElementById('edit-form').addEventListener('submit', event => {
        event.preventDefault();

        // Send the AJAX PUT request
        fetch('../backend/أشخاص.php', {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                id: <?= $id ?>,
                name: document.getElementById('name').value,
                email: document.getElementById('email').value,
                phone: document.getElementById('phone').value,
                address: document.getElementById('address').value
            })
        })
        .then(response => response.json())
        .then(data => {
            // Redirect to the list page
            window.location.href = 'list_<?= $mod_slug ?>.php';
        })
        .catch(error => console.error(error));
    });
</script>

<!-- Include the footer -->
<?php include 'footer.php'; ?>


**backend/أشخاص.php**

<?php
// Check if the ID is set
if (!isset($_GET['id'])) {
    echo json_encode(array('error' => 'ID not set'));
    exit;
}

// Get the ID
$id = $_GET['id'];

// Check if the record exists
$query = "SELECT * FROM أشخاص WHERE id = '$id'";
$result = mysqli_query($conn, $query);
$data = mysqli_fetch_assoc($result);

// Check if the record exists
if (empty($data)) {
    echo json_encode(array('error' => 'Record not found'));
    exit;
}

// Update the record
if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == 'PUT') {
    parse_str(file_get_contents('php://input'), $input);
    $query = "UPDATE أشخاص SET name = '$input[name]', email = '$input[email]', phone = '$input[phone]', address = '$input[address]' WHERE id = '$id'";
    mysqli_query($conn, $query);
    echo json_encode(array('success' => 'Record updated successfully'));
} else {
    echo json_encode($data);
}
?>