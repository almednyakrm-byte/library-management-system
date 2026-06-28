<?php
require_once 'db.php';

// Get user role and ID from session
$userRole = $_SESSION['userRole'];
$userID = $_SESSION['userID'];

// Check if user is logged in
if (!$userID) {
    http_response_code(401);
    echo json_encode(array('error' => 'Unauthorized'));
    exit;
}

// Get request method
$method = $_SERVER['REQUEST_METHOD'];

// Handle GET request
if ($method === 'GET') {
    // Validate and sanitize input
    $id = isset($_GET['id']) ? intval($_GET['id']) : null;

    // Check if admin or owner
    if ($userRole !== 'admin' && $userRole !== 'owner' || $id !== $userID) {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    try {
        // Prepare and execute SQL query
        $stmt = $pdo->prepare('SELECT * FROM اعارة WHERE id = :id');
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        // Fetch and return data
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($data);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(array('error' => 'Internal Server Error'));
    }
}

// Handle POST request
if ($method === 'POST') {
    // Validate and sanitize input
    $data = json_decode(file_get_contents('php://input'), true);
    $name = isset($data['name']) ? trim($data['name']) : null;
    $description = isset($data['description']) ? trim($data['description']) : null;

    // Check if admin or owner
    if ($userRole !== 'admin' && $userRole !== 'owner') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    try {
        // Prepare and execute SQL query
        $stmt = $pdo->prepare('INSERT INTO اعارة (name, description, user_id) VALUES (:name, :description, :user_id)');
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':user_id', $userID);
        $stmt->execute();

        // Return created ID
        $id = $pdo->lastInsertId();
        http_response_code(201);
        header('Content-Type: application/json');
        echo json_encode(array('id' => $id));
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(array('error' => 'Internal Server Error'));
    }
}

// Handle PUT request
if ($method === 'PUT') {
    // Validate and sanitize input
    $id = isset($_GET['id']) ? intval($_GET['id']) : null;
    $data = json_decode(file_get_contents('php://input'), true);
    $name = isset($data['name']) ? trim($data['name']) : null;
    $description = isset($data['description']) ? trim($data['description']) : null;

    // Check if admin or owner
    if ($userRole !== 'admin' && $userRole !== 'owner' || $id !== $userID) {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    try {
        // Prepare and execute SQL query
        $stmt = $pdo->prepare('UPDATE اعارة SET name = :name, description = :description WHERE id = :id');
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        // Return updated ID
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(array('id' => $id));
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(array('error' => 'Internal Server Error'));
    }
}

// Handle DELETE request
if ($method === 'DELETE') {
    // Validate and sanitize input
    $id = isset($_GET['id']) ? intval($_GET['id']) : null;

    // Check if admin or owner
    if ($userRole !== 'admin' && $userRole !== 'owner' || $id !== $userID) {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    try {
        // Prepare and execute SQL query
        $stmt = $pdo->prepare('DELETE FROM اعارة WHERE id = :id');
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        // Return deleted ID
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(array('id' => $id));
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(array('error' => 'Internal Server Error'));
    }
}