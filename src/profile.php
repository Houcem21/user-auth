<?php
require_once 'config.php';
require_once 'vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

// Check for the Authorization header
if (!isset($_SERVER['HTTP_AUTHORIZATION'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Authorization header not found.']);
    exit();
}

$authHeader = $_SERVER['HTTP_AUTHORIZATION'];
list($jwt) = sscanf($authHeader, 'Bearer %s');

if (!$jwt) {
    http_response_code(401);
    echo json_encode(['error' => 'Token not found in Authorization header.']);
    exit();
}

try {
    // Decode the token
    $decoded = JWT::decode($jwt, new Key(JWT_SECRET, 'HS256'));
    $user_id = $decoded->sub;

    // Fetch user data from the database
    $stmt = $pdo->prepare('SELECT user_id, username FROM users WHERE user_id = ?');
    $stmt->execute([$user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        http_response_code(404);
        echo json_encode(['error' => 'User not found.']);
        exit();
    }

    // Return protected data
    echo json_encode([
        'message' => 'Welcome to your profile!',
        'user' => $user
    ]);

} catch (Exception $e) {
    http_response_code(401);
    echo json_encode(['error' => 'Invalid token: ' . $e->getMessage()]);
    exit();
}