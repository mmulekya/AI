<?php
session_start();
header('Content-Type: application/json');

require_once "../includes/config.php";
require_once "../includes/database.php";
require_once "../includes/security.php";
require_once "../includes/jwt_helper.php";

// 🔐 RATE LIMIT
$ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
check_rate_limit($conn, "otp_" . $ip, 3, 600);

// 🔐 ONLY POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit(json_encode(["error" => "Method not allowed"]));
}

// 🔐 CSRF
$csrf = $_POST['csrf_token'] ?? '';
if (!verify_csrf_token($csrf)) {
    log_attack($conn, "OTP_CSRF_FAIL");
    exit(json_encode(["error" => "Invalid CSRF token"]));
}

// 🧾 INPUT
$otp = trim($_POST['otp'] ?? '');

if (!$otp) {
    exit(json_encode(["error" => "OTP required"]));
}

// 🔐 SESSION CHECK
if (!isset($_SESSION['otp_user_id'], $_SESSION['otp_code'], $_SESSION['otp_expires'])) {
    exit(json_encode(["error" => "Session expired. Please login again."]));
}

// ⏱ EXPIRY CHECK
if (time() > $_SESSION['otp_expires']) {
    session_unset();
    exit(json_encode(["error" => "OTP expired. Please login again."]));
}

// 🔐 VERIFY OTP
if (hash_equals((string)$_SESSION['otp_code'], $otp)) {

    $user_id = $_SESSION['otp_user_id'];

    // 🔑 JWT
    $token = generate_jwt($user_id);

    // Clear OTP
    unset($_SESSION['otp_user_id']);
    unset($_SESSION['otp_code']);
    unset($_SESSION['otp_expires']);

    echo json_encode([
        "success" => true,
        "token" => $token
    ]);

} else {

    log_login_attempt($conn, "otp_user_" . $_SESSION['otp_user_id'], 0);
    auto_ban_ip($conn);

    echo json_encode(["error" => "Invalid OTP"]);
}