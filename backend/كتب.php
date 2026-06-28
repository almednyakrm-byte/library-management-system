<?php
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

// Get input data
$input = json_decode(file_get_contents('php://input'), true) ?: $_POST;

// Validate input data
if (!isset($input['id']) && !isset($input['title']) && !isset($input['author']) && !isset($input['price'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid request data']);
    exit;
}

// Connect to database
$db = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASSWORD);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Handle GET request
if (isset($_GET['id'])) {
    // Get book by ID
    $stmt = $db->prepare('SELECT * FROM كتب WHERE id = :id');
    $stmt->bindParam(':id', $_GET['id']);
    $stmt->execute();
    $book = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($book) {
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($book);
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'Book not found']);
    }
} elseif (isset($_GET['all'])) {
    // Get all books
    $stmt = $db->prepare('SELECT * FROM كتب');
    $stmt->execute();
    $books = $stmt->fetchAll(PDO::FETCH_ASSOC);
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($books);
} else {
    // Handle POST, PUT, DELETE requests
    if (isset($input['id']) && $_SESSION['role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // Handle POST request
    if (isset($input['title']) && isset($input['author']) && isset($input['price'])) {
        // Insert new book
        $stmt = $db->prepare('INSERT INTO كتب (title, author, price) VALUES (:title, :author, :price)');
        $stmt->bindParam(':title', $input['title']);
        $stmt->bindParam(':author', $input['author']);
        $stmt->bindParam(':price', $input['price']);
        $stmt->execute();
        http_response_code(201);
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Book created successfully']);
    }

    // Handle PUT request
    elseif (isset($input['id']) && isset($input['title']) && isset($input['author']) && isset($input['price'])) {
        // Update existing book
        $stmt = $db->prepare('UPDATE كتب SET title = :title, author = :author, price = :price WHERE id = :id');
        $stmt->bindParam(':id', $input['id']);
        $stmt->bindParam(':title', $input['title']);
        $stmt->bindParam(':author', $input['author']);
        $stmt->bindParam(':price', $input['price']);
        $stmt->execute();
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Book updated successfully']);
    }

    // Handle DELETE request
    elseif (isset($input['id']) && $_SESSION['role'] === 'admin') {
        // Delete existing book
        $stmt = $db->prepare('DELETE FROM كتب WHERE id = :id');
        $stmt->bindParam(':id', $input['id']);
        $stmt->execute();
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Book deleted successfully']);
    } else {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid request data']);
    }
}

$db = null;