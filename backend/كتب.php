<?php
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

// Get request method
$method = $_SERVER['REQUEST_METHOD'];

// Initialize database connection
$pdo = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASSWORD);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Handle GET requests
if ($method == 'GET') {
    // Validate and sanitize input
    $id = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT);

    // SQL query structure: Select all or by ID
    if ($id) {
        $stmt = $pdo->prepare('SELECT * FROM كتب WHERE id = :id');
        $stmt->bindParam(':id', $id);
    } else {
        $stmt = $pdo->prepare('SELECT * FROM كتب');
    }

    // Execute query and process output
    $stmt->execute();
    $kutub = $stmt->fetchAll(PDO::FETCH_ASSOC);
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($kutub);
}

// Handle POST requests
elseif ($method == 'POST') {
    // Check if user is admin
    if ($_SESSION['user_role'] != 'admin') {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // Get input data
    $data = json_decode(file_get_contents('php://input'), true);

    // Validate and sanitize input
    $title = filter_var($data['title'] ?? null, FILTER_SANITIZE_STRING);
    $author = filter_var($data['author'] ?? null, FILTER_SANITIZE_STRING);
    $publisher = filter_var($data['publisher'] ?? null, FILTER_SANITIZE_STRING);

    // SQL query structure: Insert new record
    $stmt = $pdo->prepare('INSERT INTO كتب (title, author, publisher) VALUES (:title, :author, :publisher)');
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':author', $author);
    $stmt->bindParam(':publisher', $publisher);

    // Execute query and process output
    if ($stmt->execute()) {
        http_response_code(201);
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Created successfully']);
    } else {
        http_response_code(500);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Internal Server Error']);
    }
}

// Handle PUT requests
elseif ($method == 'PUT') {
    // Check if user is admin
    if ($_SESSION['user_role'] != 'admin') {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // Get input data
    $data = json_decode(file_get_contents('php://input'), true);

    // Validate and sanitize input
    $id = filter_var($data['id'] ?? null, FILTER_VALIDATE_INT);
    $title = filter_var($data['title'] ?? null, FILTER_SANITIZE_STRING);
    $author = filter_var($data['author'] ?? null, FILTER_SANITIZE_STRING);
    $publisher = filter_var($data['publisher'] ?? null, FILTER_SANITIZE_STRING);

    // SQL query structure: Update existing record
    $stmt = $pdo->prepare('UPDATE كتب SET title = :title, author = :author, publisher = :publisher WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':author', $author);
    $stmt->bindParam(':publisher', $publisher);

    // Execute query and process output
    if ($stmt->execute()) {
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Updated successfully']);
    } else {
        http_response_code(500);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Internal Server Error']);
    }
}

// Handle DELETE requests
elseif ($method == 'DELETE') {
    // Check if user is admin
    if ($_SESSION['user_role'] != 'admin') {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // Get input data
    $data = json_decode(file_get_contents('php://input'), true);

    // Validate and sanitize input
    $id = filter_var($data['id'] ?? null, FILTER_VALIDATE_INT);

    // SQL query structure: Delete existing record
    $stmt = $pdo->prepare('DELETE FROM كتب WHERE id = :id');
    $stmt->bindParam(':id', $id);

    // Execute query and process output
    if ($stmt->execute()) {
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Deleted successfully']);
    } else {
        http_response_code(500);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Internal Server Error']);
    }
}

// Handle invalid request methods
else {
    http_response_code(405);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Method Not Allowed']);
}