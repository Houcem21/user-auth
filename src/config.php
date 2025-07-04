<?php
// Set response header to JSON
header("Content-Type: application/json");

// Database configuration
define('DB_HOST', 'db');
define('DB_PORT', '5432');
define('DB_NAME', 'auth_db');
define('DB_USER', 'auth_user');
define('DB_PASS', 'auth_pass');

// JWT Configuration
define('JWT_SECRET', 'YOUR_SUPER_SECRET_KEY_REPLACE_ME'); // <-- IMPORTANT: Change this to a long, random string
define('JWT_ISSUER', 'your-domain.com');
define('JWT_AUDIENCE', 'your-domain.com');

// Establish PDO Database Connection
try {
    $dsn = "pgsql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME;
    $pdo = new PDO($dsn, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database connection failed: ' . $e->getMessage()]);
    exit();
}