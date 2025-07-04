<?php
require_once 'config.php';
require_once 'vendor/autoload.php';

use Firebase\JWT\JWT;

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['username']) || !isset($data['password'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Username and password are required.']);
    exit();
}

$username = $data['username'];
$password = $data['password'];

// Fetch user from the database
$stmt = $pdo->prepare('SELECT user_id, username, password FROM users WHERE username = ?');
$stmt->execute([$username]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Verify user and password
if (!$user || !password_verify($password, $user['password'])) {
    http_response_code(401); // Unauthorized
    echo json_encode(['error' => 'Invalid username or password.']);
    exit();
}

// User is authenticated, create JWT
$issuedAt = time();
$expirationTime = $issuedAt + 3600; // jwt valid for 1 hour

$payload = [
    'iss' => JWT_ISSUER,
    'aud' => JWT_AUDIENCE,
    'iat' => $issuedAt,
    'exp' => $expirationTime,
    'sub' => $user['user_id'] // Subject of the token (user ID)
];

$jwt = JWT::encode($payload, JWT_SECRET, 'HS256');

echo json_encode([
    'message' => 'Login successful.',
    'token' => $jwt
]);