<?php
// Start the session to store user data
session_start();

// Include the database connection file
require_once 'db.php';

// Check if the user is already logged in
if (isset($_SESSION['user_id'])) {
    // If the user is logged in, return a JSON response with their details
    $user_id = $_SESSION['user_id'];
    $username = $_SESSION['username'];
    $response = array(
        'status' => 'logged_in',
        'user_id' => $user_id,
        'username' => $username
    );
    echo json_encode($response);
    exit;
}

// Check if the user is trying to register
if (isset($_POST['action']) && $_POST['action'] == 'register') {
    // Check if the form data is valid
    if (!isset($_POST['username']) || !isset($_POST['email']) || !isset($_POST['password'])) {
        $response = array(
            'status' => 'error',
            'message' => 'Invalid form data'
        );
        echo json_encode($response);
        exit;
    }

    // Sanitize the form data
    $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = filter_var($_POST['password'], FILTER_SANITIZE_STRING);

    // Check if the username and email are already taken
    $query = $db->prepare("SELECT * FROM users WHERE username = :username OR email = :email");
    $query->bindParam(':username', $username);
    $query->bindParam(':email', $email);
    $query->execute();
    $user = $query->fetch();

    if ($user) {
        $response = array(
            'status' => 'error',
            'message' => 'Username or email already taken'
        );
        echo json_encode($response);
        exit;
    }

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert the user into the database
    $query = $db->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
    $query->bindParam(':username', $username);
    $query->bindParam(':email', $email);
    $query->bindParam(':password', $hashed_password);
    $query->execute();

    // Get the user's ID
    $user_id = $db->lastInsertId();

    // Log the user in
    $_SESSION['user_id'] = $user_id;
    $_SESSION['username'] = $username;

    $response = array(
        'status' => 'success',
        'message' => 'User created successfully'
    );
    echo json_encode($response);
    exit;
}

// Check if the user is trying to login
if (isset($_POST['action']) && $_POST['action'] == 'login') {
    // Check if the form data is valid
    if (!isset($_POST['username']) || !isset($_POST['password'])) {
        $response = array(
            'status' => 'error',
            'message' => 'Invalid form data'
        );
        echo json_encode($response);
        exit;
    }

    // Sanitize the form data
    $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
    $password = filter_var($_POST['password'], FILTER_SANITIZE_STRING);

    // Check if the username and password are correct
    $query = $db->prepare("SELECT * FROM users WHERE username = :username");
    $query->bindParam(':username', $username);
    $query->execute();
    $user = $query->fetch();

    if (!$user || !password_verify($password, $user['password'])) {
        $response = array(
            'status' => 'error',
            'message' => 'Invalid username or password'
        );
        echo json_encode($response);
        exit;
    }

    // Log the user in
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $username;

    $response = array(
        'status' => 'success',
        'message' => 'User logged in successfully'
    );
    echo json_encode($response);
    exit;
}

// Check if the user is trying to logout
if (isset($_POST['action']) && $_POST['action'] == 'logout') {
    // Destroy the session
    session_destroy();

    $response = array(
        'status' => 'success',
        'message' => 'User logged out successfully'
    );
    echo json_encode($response);
    exit;
}
?>