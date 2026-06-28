<?php
require_once 'db.php';

// Get user role and ID from session
$userRole = $_SESSION['userRole'];
$userID = $_SESSION['userID'];

// Get input data from JSON or POST
$inputData = json_decode(file_get_contents('php://input'), true);
if (!$inputData) {
    $inputData = $_POST;
}

// Validate and sanitize input data
if (!isset($inputData['name']) || !isset($inputData['email']) || !isset($inputData['role'])) {
    http_response_code(400);
    echo json_encode(array('error' => 'Invalid input data'));
    exit;
}

$name = filter_var($inputData['name'], FILTER_SANITIZE_STRING);
$email = filter_var($inputData['email'], FILTER_SANITIZE_EMAIL);
$role = filter_var($inputData['role'], FILTER_SANITIZE_STRING);

// Handle GET request
if (isset($_GET['action']) && $_GET['action'] == 'get') {
    // Select all employees
    $stmt = $pdo->prepare('SELECT * FROM موظفين_المكتبة');
    $stmt->execute();
    $employees = $stmt->fetchAll(PDO::FETCH_ASSOC);
    http_response_code(200);
    echo json_encode($employees);
    exit;
}

// Handle GET request by ID
if (isset($_GET['action']) && $_GET['action'] == 'getById') {
    // Select employee by ID
    $stmt = $pdo->prepare('SELECT * FROM موظفين_المكتبة WHERE ID = :ID');
    $stmt->bindParam(':ID', $_GET['id']);
    $stmt->execute();
    $employee = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$employee) {
        http_response_code(404);
        echo json_encode(array('error' => 'Employee not found'));
        exit;
    }
    http_response_code(200);
    echo json_encode($employee);
    exit;
}

// Handle POST request
if (isset($_GET['action']) && $_GET['action'] == 'create') {
    // Insert new employee
    $stmt = $pdo->prepare('INSERT INTO موظفين_المكتبة (name, email, role) VALUES (:name, :email, :role)');
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':role', $role);
    if ($stmt->execute()) {
        http_response_code(201);
        echo json_encode(array('message' => 'Employee created successfully'));
    } else {
        http_response_code(500);
        echo json_encode(array('error' => 'Failed to create employee'));
    }
    exit;
}

// Handle PUT request
if (isset($_GET['action']) && $_GET['action'] == 'update') {
    // Update employee
    $stmt = $pdo->prepare('UPDATE موظفين_المكتبة SET name = :name, email = :email, role = :role WHERE ID = :ID');
    $stmt->bindParam(':ID', $_GET['id']);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':role', $role);
    if ($userRole == 'admin' && $stmt->execute()) {
        http_response_code(200);
        echo json_encode(array('message' => 'Employee updated successfully'));
    } elseif ($userRole == 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
    } else {
        http_response_code(401);
        echo json_encode(array('error' => 'Unauthorized'));
    }
    exit;
}

// Handle DELETE request
if (isset($_GET['action']) && $_GET['action'] == 'delete') {
    // Delete employee
    $stmt = $pdo->prepare('DELETE FROM موظفين_المكتبة WHERE ID = :ID');
    $stmt->bindParam(':ID', $_GET['id']);
    if ($userRole == 'admin' && $stmt->execute()) {
        http_response_code(200);
        echo json_encode(array('message' => 'Employee deleted successfully'));
    } elseif ($userRole == 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
    } else {
        http_response_code(401);
        echo json_encode(array('error' => 'Unauthorized'));
    }
    exit;
}

http_response_code(400);
echo json_encode(array('error' => 'Invalid request'));
exit;



// Example usage:
// GET all employees
curl -X GET 'http://example.com/موظفين-المكتبة?action=get'

// GET employee by ID
curl -X GET 'http://example.com/موظفين-المكتبة?action=getById&id=1'

// CREATE new employee
curl -X POST -H 'Content-Type: application/json' -d '{"name": "John Doe", "email": "john@example.com", "role": "admin"}' 'http://example.com/موظفين-المكتبة?action=create'

// UPDATE employee
curl -X PUT -H 'Content-Type: application/json' -d '{"name": "Jane Doe", "email": "jane@example.com", "role": "admin"}' 'http://example.com/موظفين-المكتبة?action=update&id=1'

// DELETE employee
curl -X DELETE 'http://example.com/موظفين-المكتبة?action=delete&id=1'