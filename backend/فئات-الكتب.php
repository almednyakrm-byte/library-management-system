<?php

require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_role'])) {
    http_response_code(401);
    echo json_encode(array('error' => 'Unauthorized'));
    exit;
}

// Get input data
$input = json_decode(file_get_contents('php://input'), true);

// Define routes
$routes = array(
    '/categories' => array('GET', 'POST'),
    '/categories/:id' => array('GET', 'PUT', 'DELETE')
);

// Get route and method
$route = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];

// Check if route and method are valid
if (!isset($routes[$route]) || !in_array($method, $routes[$route])) {
    http_response_code(405);
    echo json_encode(array('error' => 'Method Not Allowed'));
    exit;
}

// Get category ID from route
if (strpos($route, '/categories/') !== false) {
    $categoryId = explode('/', $route)[3];
}

// Validate and sanitize input data
if ($method === 'POST' || $method === 'PUT') {
    $requiredFields = array('name', 'description');
    $errors = array();

    foreach ($requiredFields as $field) {
        if (!isset($input[$field]) || empty($input[$field])) {
            $errors[] = $field;
        }
    }

    if (!empty($errors)) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request', 'errors' => $errors));
        exit;
    }

    $input['name'] = filter_var($input['name'], FILTER_SANITIZE_STRING);
    $input['description'] = filter_var($input['description'], FILTER_SANITIZE_STRING);
}

// Connect to database
$db = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASSWORD);

// Handle GET request
if ($method === 'GET') {
    if (strpos($route, '/categories/') !== false) {
        // Get all categories
        $stmt = $db->prepare('SELECT * FROM categories WHERE id = :id');
        $stmt->bindParam(':id', $categoryId);
        $stmt->execute();
        $category = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($category) {
            http_response_code(200);
            echo json_encode($category);
        } else {
            http_response_code(404);
            echo json_encode(array('error' => 'Category not found'));
        }
    } else {
        // Get all categories
        $stmt = $db->prepare('SELECT * FROM categories');
        $stmt->execute();
        $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

        http_response_code(200);
        echo json_encode($categories);
    }
}

// Handle POST request
if ($method === 'POST') {
    // Insert new category
    $stmt = $db->prepare('INSERT INTO categories (name, description) VALUES (:name, :description)');
    $stmt->bindParam(':name', $input['name']);
    $stmt->bindParam(':description', $input['description']);
    $stmt->execute();

    http_response_code(201);
    echo json_encode(array('message' => 'Category created successfully'));
}

// Handle PUT request
if ($method === 'PUT') {
    // Update existing category
    if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    $stmt = $db->prepare('UPDATE categories SET name = :name, description = :description WHERE id = :id');
    $stmt->bindParam(':id', $categoryId);
    $stmt->bindParam(':name', $input['name']);
    $stmt->bindParam(':description', $input['description']);
    $stmt->execute();

    http_response_code(200);
    echo json_encode(array('message' => 'Category updated successfully'));
}

// Handle DELETE request
if ($method === 'DELETE') {
    // Delete existing category
    if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    $stmt = $db->prepare('DELETE FROM categories WHERE id = :id');
    $stmt->bindParam(':id', $categoryId);
    $stmt->execute();

    http_response_code(200);
    echo json_encode(array('message' => 'Category deleted successfully'));
}

// Close database connection
$db = null;

?>