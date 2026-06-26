<?php
// Start the session
session_start();

// Include the database connection file
require_once 'db.php';

// Check if the user is already logged in
if (isset($_SESSION['user_id'])) {
    // If the user is logged in, send a JSON response with their details
    $user_id = $_SESSION['user_id'];
    $query = "SELECT * FROM users WHERE id = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $response = array(
        'status' => 'logged_in',
        'user' => $user
    );
} else {
    // If the user is not logged in, send a JSON response indicating that
    $response = array(
        'status' => 'logged_out'
    );
}

// Handle AJAX login request
if (isset($_POST['action']) && $_POST['action'] == 'login') {
    // Check if the username and password are set
    if (isset($_POST['username']) && isset($_POST['password'])) {
        // Sanitize the input fields
        $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
        $password = filter_var($_POST['password'], FILTER_SANITIZE_STRING);

        // Prepare the SQL query to check the username and password
        $query = "SELECT * FROM users WHERE username = ?";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        // Check if the user exists and the password is correct
        if ($user && password_verify($password, $user['password'])) {
            // If the password is correct, log the user in and send a JSON response
            $_SESSION['user_id'] = $user['id'];
            $response = array(
                'status' => 'logged_in',
                'user' => $user
            );
        } else {
            // If the password is incorrect, send a JSON response indicating that
            $response = array(
                'status' => 'invalid_credentials'
            );
        }
    } else {
        // If the username or password is not set, send a JSON response indicating that
        $response = array(
            'status' => 'missing_credentials'
        );
    }
}

// Handle AJAX register request
if (isset($_POST['action']) && $_POST['action'] == 'register') {
    // Check if the username, email, and password are set
    if (isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password'])) {
        // Sanitize the input fields
        $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        $password = filter_var($_POST['password'], FILTER_SANITIZE_STRING);

        // Check if the username and email are unique
        $query = "SELECT * FROM users WHERE username = ? OR email = ?";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        // If the username or email is already taken, send a JSON response indicating that
        if ($user) {
            $response = array(
                'status' => 'username_or_email_taken'
            );
        } else {
            // Hash the password using password_hash()
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Prepare the SQL query to insert the new user
            $query = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
            $stmt = $mysqli->prepare($query);
            $stmt->bind_param("sss", $username, $email, $hashed_password);
            $stmt->execute();

            // If the user is inserted successfully, log them in and send a JSON response
            $user_id = $mysqli->insert_id;
            $_SESSION['user_id'] = $user_id;
            $response = array(
                'status' => 'registered',
                'user' => array(
                    'id' => $user_id,
                    'username' => $username,
                    'email' => $email
                )
            );
        }
    } else {
        // If the username, email, or password is not set, send a JSON response indicating that
        $response = array(
            'status' => 'missing_credentials'
        );
    }
}

// Handle logout request
if (isset($_POST['action']) && $_POST['action'] == 'logout') {
    // Destroy the session and send a JSON response
    session_destroy();
    $response = array(
        'status' => 'logged_out'
    );
}

// Send the JSON response
header('Content-Type: application/json');
echo json_encode($response);


This code handles the following actions:

*   Checks the current session status and sends a JSON response with the user details if they are logged in.
*   Handles AJAX login requests by checking the username and password, and sending a JSON response with the user details if they are correct.
*   Handles AJAX register requests by checking the username, email, and password, and sending a JSON response with the user details if they are valid.
*   Handles logout requests by destroying the session and sending a JSON response.

The code uses prepared statements to prevent SQL injection attacks and hashes passwords using `password_hash()` to store them securely in the database. It also sanitizes input fields using `filter_var()` to prevent XSS attacks.