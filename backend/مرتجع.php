<?php

// Import database connection settings
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    http_response_code(401);
    echo json_encode(array('error' => 'Unauthorized'));
    exit;
}

// Get user role
$userRole = $_SESSION['user_role'];

// Check if user is admin
$isAdmin = ($userRole == 'admin');

// Get request method
$method = $_SERVER['REQUEST_METHOD'];

// Handle GET request
if ($method == 'GET') {
    // Validate and sanitize input parameters
    $id = isset($_GET['id']) ? intval($_GET['id']) : 0;

    // Prepare SQL query
    $sql = "SELECT * FROM مرتجع WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    // Fetch result
    $result = $stmt->fetch();

    // Check if result exists
    if ($result) {
        // Return result as JSON
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($result);
    } else {
        // Return 404 status code
        http_response_code(404);
        echo json_encode(array('error' => 'Not found'));
    }
} elseif ($method == 'POST') {
    // Read input data from JSON body
    $data = json_decode(file_get_contents('php://input'), true);

    // Validate input data
    if (!isset($data['name']) || !isset($data['description'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request'));
        exit;
    }

    // Sanitize input data
    $name = $pdo->quote($data['name']);
    $description = $pdo->quote($data['description']);

    // Prepare SQL query
    $sql = "INSERT INTO مرتجع (name, description) VALUES ($name, $description)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    // Get inserted ID
    $id = $pdo->lastInsertId();

    // Return inserted ID as JSON
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(array('id' => $id));
} elseif ($method == 'PUT') {
    // Validate and sanitize input parameters
    $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
    $data = json_decode(file_get_contents('php://input'), true);

    // Validate input data
    if (!isset($data['name']) || !isset($data['description'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request'));
        exit;
    }

    // Sanitize input data
    $name = $pdo->quote($data['name']);
    $description = $pdo->quote($data['description']);

    // Check if user is admin
    if (!$isAdmin) {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // Prepare SQL query
    $sql = "UPDATE مرتجع SET name = $name, description = $description WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    // Check if update was successful
    if ($stmt->rowCount() == 1) {
        // Return success message as JSON
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(array('message' => 'Updated successfully'));
    } else {
        // Return 404 status code
        http_response_code(404);
        echo json_encode(array('error' => 'Not found'));
    }
} elseif ($method == 'DELETE') {
    // Validate and sanitize input parameters
    $id = isset($_GET['id']) ? intval($_GET['id']) : 0;

    // Check if user is admin
    if (!$isAdmin) {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // Prepare SQL query
    $sql = "DELETE FROM مرتجع WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    // Check if delete was successful
    if ($stmt->rowCount() == 1) {
        // Return success message as JSON
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(array('message' => 'Deleted successfully'));
    } else {
        // Return 404 status code
        http_response_code(404);
        echo json_encode(array('error' => 'Not found'));
    }
} else {
    // Return 405 status code
    http_response_code(405);
    echo json_encode(array('error' => 'Method not allowed'));
}