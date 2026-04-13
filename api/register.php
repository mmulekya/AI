<?php
    ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once "../includes/config.php";
require_once "../includes/database.php";
require_once "../includes/security.php";

header("Content-Type: application/json");
session_start();

// =========================
// 🔐 CSRF CHECK
// =========================
$csrf = $_POST['csrf_token'] ?? '';

if (!verify_csrf_token($csrf)) {
    exit(json_encode(["error" => "Invalid CSRF token"]));
}

// =========================
// 🧾 INPUT VALIDATION
// =========================
$name = trim($_POST['name'] ?? '');
$email = filter_var(trim($_POST['email'] ?? ''), FILTER_VALIDATE_EMAIL);
$password = trim($_POST['password'] ?? '');

if (empty($name) || !$email || strlen($password) < 6) {
    exit(json_encode(["error" => "All fields are required (password min 6 chars)"]));
}

// =========================
// 🔍 CHECK EMAIL EXISTS
// =========================
$stmt = db_prepare("SELECT id FROM users WHERE email=? LIMIT 1");
$stmt->bind_param("s", $email);
$stmt->execute();

$result = $stmt->get_result();

if ($result && $result->num_rows > 0) {
    $stmt->close();
    exit(json_encode(["error" => "Email already exists"]));
}
$stmt->close();

// =========================
// 🔐 HASH PASSWORD
// =========================
$hash = password_hash($password, PASSWORD_BCRYPT);

// =========================
// ➕ INSERT USER
// =========================
$stmt = db_prepare("INSERT INTO users (name, email, password, role, status) VALUES (?, ?, ?, 'user', 'active')");
$stmt->bind_param("sss", $name, $email, $hash);

if ($stmt->execute()) {
    $stmt->close();

    echo json_encode([
        "success" => true,
        "message" => "Account created successfully"
    ]);
} else {
    $stmt->close();

    echo json_encode([
        "error" => "Database error. Try again later"
    ]);
}

exit;