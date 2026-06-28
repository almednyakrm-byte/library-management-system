<?php
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(array('error' => 'Unauthorized'));
    exit;
}

// Get input data
$input = json_decode(file_get_contents('php://input'), true);

// Define routes
$routes = array(
    '/employees' => array('GET', 'POST'),
    '/employees/:id' => array('GET', 'PUT', 'DELETE')
);

// Get route
$route = explode('/', $_SERVER['REQUEST_URI']);
$route = $route[count($route) - 1];

// Check if route exists
if (!isset($routes[$route])) {
    http_response_code(404);
    echo json_encode(array('error' => 'Route not found'));
    exit;
}

// Get allowed methods
$allowedMethods = $routes[$route];

// Check if method is allowed
if (!in_array($_SERVER['REQUEST_METHOD'], $allowedMethods)) {
    http_response_code(405);
    echo json_encode(array('error' => 'Method not allowed'));
    exit;
}

// Validate input data
if (in_array($_SERVER['REQUEST_METHOD'], array('POST', 'PUT'))) {
    // Validate required fields
    $requiredFields = array('name', 'email', 'phone');
    foreach ($requiredFields as $field) {
        if (!isset($input[$field])) {
            http_response_code(400);
            echo json_encode(array('error' => 'Missing required field: ' . $field));
            exit;
        }
    }
}

// Sanitize input data
$input['name'] = htmlspecialchars($input['name']);
$input['email'] = filter_var($input['email'], FILTER_VALIDATE_EMAIL);
$input['phone'] = preg_replace('/[^0-9]/', '', $input['phone']);

// Connect to database
$db = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASSWORD);

// Handle GET request
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    // Check if id is provided
    if (isset($route[0]) && $route[0] != '') {
        // Get employee by id
        $stmt = $db->prepare('SELECT * FROM employees WHERE id = :id');
        $stmt->bindParam(':id', $route[0]);
        $stmt->execute();
        $employee = $stmt->fetch();
        if ($employee) {
            http_response_code(200);
            echo json_encode($employee);
        } else {
            http_response_code(404);
            echo json_encode(array('error' => 'Employee not found'));
        }
    } else {
        // Get all employees
        $stmt = $db->prepare('SELECT * FROM employees');
        $stmt->execute();
        $employees = $stmt->fetchAll();
        http_response_code(200);
        echo json_encode($employees);
    }
}

// Handle POST request
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check if user is admin
    if ($_SESSION['user_role'] != 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }
    // Insert employee
    $stmt = $db->prepare('INSERT INTO employees (name, email, phone) VALUES (:name, :email, :phone)');
    $stmt->bindParam(':name', $input['name']);
    $stmt->bindParam(':email', $input['email']);
    $stmt->bindParam(':phone', $input['phone']);
    $stmt->execute();
    http_response_code(201);
    echo json_encode(array('message' => 'Employee created successfully'));
}

// Handle PUT request
if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    // Check if id is provided
    if (!isset($route[0]) || $route[0] == '') {
        http_response_code(400);
        echo json_encode(array('error' => 'Missing id'));
        exit;
    }
    // Check if user is admin
    if ($_SESSION['user_role'] != 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }
    // Update employee
    $stmt = $db->prepare('UPDATE employees SET name = :name, email = :email, phone = :phone WHERE id = :id');
    $stmt->bindParam(':id', $route[0]);
    $stmt->bindParam(':name', $input['name']);
    $stmt->bindParam(':email', $input['email']);
    $stmt->bindParam(':phone', $input['phone']);
    $stmt->execute();
    http_response_code(200);
    echo json_encode(array('message' => 'Employee updated successfully'));
}

// Handle DELETE request
if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    // Check if id is provided
    if (!isset($route[0]) || $route[0] == '') {
        http_response_code(400);
        echo json_encode(array('error' => 'Missing id'));
        exit;
    }
    // Check if user is admin
    if ($_SESSION['user_role'] != 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }
    // Delete employee
    $stmt = $db->prepare('DELETE FROM employees WHERE id = :id');
    $stmt->bindParam(':id', $route[0]);
    $stmt->execute();
    http_response_code(200);
    echo json_encode(array('message' => 'Employee deleted successfully'));
}

// Close database connection
$db = null;
?>