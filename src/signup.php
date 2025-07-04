<?php
require_once 'config.php';

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['username']) || !isset($data['password'])) {
    http_response_code(400); // Bad Request
    echo json_encode(['error' => 'Username and password are required.']);
    exit();
}

$username = $data['username'];
$password = $data['password'];

// Hash the password
$hashed_password = password_hash($password, PASSWORD_BCRYPT);

try {
    // Insert the new user into the database
    $stmt = $pdo->prepare('INSERT INTO users (username, password) VALUES (?, ?)');
    $stmt->execute([$username, $hashed_password]);

    http_response_code(201); // Created
    echo json_encode(['message' => 'User created successfully.']);

} catch (PDOException $e) {
    // Check for duplicate username error
    if ($e->getCode() == 23505) {
        http_response_code(409); // Conflict
        echo json_encode(['error' => 'Username already exists.']);
    } else {
        http_response_code(500); // Internal Server Error
        echo json_encode(['error' => 'Failed to create user: ' . $e->getMessage()]);
    }
}