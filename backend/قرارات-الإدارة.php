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
    'GET' => array('/' => 'get_all', '/:id' => 'get_one'),
    'POST' => '/create',
    'PUT' => array('/:id' => 'update'),
    'DELETE' => array('/:id' => 'delete')
);

// Get route and method
$method = $_SERVER['REQUEST_METHOD'];
$route = $_SERVER['REQUEST_URI'];

// Check if route exists
if (!isset($routes[$method][$route])) {
    http_response_code(404);
    echo json_encode(array('error' => 'Not Found'));
    exit;
}

// Get user role
$user_role = $_SESSION['user_role'];

// Call route function
$function = $routes[$method][$route];
$function();

// Helper function to get all records
function get_all() {
    global $pdo;
    
    // SQL query
    $stmt = $pdo->prepare("SELECT * FROM قرارات_الإدارة");
    $stmt->execute();
    
    // Fetch all records
    $records = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Return records
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($records);
}

// Helper function to get one record
function get_one($id) {
    global $pdo;
    
    // Validate ID
    if (!is_numeric($id)) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid ID'));
        exit;
    }
    
    // SQL query
    $stmt = $pdo->prepare("SELECT * FROM قرارات_الإدارة WHERE id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    
    // Fetch record
    $record = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Check if record exists
    if (!$record) {
        http_response_code(404);
        echo json_encode(array('error' => 'Not Found'));
        exit;
    }
    
    // Return record
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($record);
}

// Helper function to create new record
function create() {
    global $pdo;
    
    // Get input data
    $input = json_decode(file_get_contents('php://input'), true);
    
    // Validate input
    if (!isset($input['title']) || !isset($input['description'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid input'));
        exit;
    }
    
    // Sanitize input
    $title = $pdo->quote($input['title']);
    $description = $pdo->quote($input['description']);
    
    // SQL query
    $stmt = $pdo->prepare("INSERT INTO قرارات_الإدارة (title, description) VALUES ($title, $description)");
    $stmt->execute();
    
    // Get ID of new record
    $id = $pdo->lastInsertId();
    
    // Return ID
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(array('id' => $id));
}

// Helper function to update existing record
function update($id) {
    global $pdo;
    
    // Validate ID
    if (!is_numeric($id)) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid ID'));
        exit;
    }
    
    // Check if user is admin
    if ($user_role != 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }
    
    // Get input data
    $input = json_decode(file_get_contents('php://input'), true);
    
    // Validate input
    if (!isset($input['title']) || !isset($input['description'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid input'));
        exit;
    }
    
    // Sanitize input
    $title = $pdo->quote($input['title']);
    $description = $pdo->quote($input['description']);
    
    // SQL query
    $stmt = $pdo->prepare("UPDATE قرارات_الإدارة SET title = $title, description = $description WHERE id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    
    // Return message
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Updated successfully'));
}

// Helper function to delete existing record
function delete($id) {
    global $pdo;
    
    // Validate ID
    if (!is_numeric($id)) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid ID'));
        exit;
    }
    
    // Check if user is admin
    if ($user_role != 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }
    
    // SQL query
    $stmt = $pdo->prepare("DELETE FROM قرارات_الإدارة WHERE id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    
    // Return message
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Deleted successfully'));
}