<?php
session_start();

require_once "config.php";
require_once "security.php";

header("Content-Type: application/json");

// Ensure CSRF token exists in session
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Return token
echo json_encode([
    "token" => $_SESSION['csrf_token']
]);

exit;