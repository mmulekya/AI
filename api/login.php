<?php
session_start();
header('Content-Type: application/json');

require_once "../includes/config.php";
require_once "../includes/database.php";
require_once "../includes/security.php";

/* =========================
   🔐 RATE LIMIT
========================= */
$ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
check_rate_limit($conn, "login_" . $ip, 5, 300);

/* =========================
   🔐 ONLY POST
========================= */
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit(json_encode(["error" => "Method not allowed"]));
}

/* =========================
   🧾 INPUT SAFE CHECK
========================= */
$email = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL);
$password = $_POST['password'] ?? '';
$csrf_token = $_POST['csrf_token'] ?? '';

if (!$email || strlen($password) < 4) {
    exit(json_encode(["error" => "Invalid input"]));
}

/* =========================
   🔐 CSRF CHECK (FIXED)
========================= */
if (!isset($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $csrf_token)) {
    log_attack($conn, "CSRF_FAILURE");
    exit(json_encode(["error" => "Invalid security token"]));
}

/* =========================
   🔍 FIND USER
========================= */
$stmt = $conn->prepare("SELECT id, password, status FROM users WHERE email=? LIMIT 1");

if (!$stmt) {
    exit(json_encode(["error" => "Server error"]));
}

$stmt->bind_param("s", $email);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

/* =========================
   🔐 VERIFY PASSWORD
========================= */
if ($user && password_verify($password, $user['password'])) {

    if ($user['status'] !== 'active') {
        exit(json_encode(["error" => "Account suspended"]));
    }

    /* =========================
       🔑 GENERATE OTP
    ========================= */
    $otp = random_int(100000, 999999);

    $_SESSION['otp_user_id'] = $user['id'];
    $_SESSION['otp_code'] = $otp;
    $_SESSION['otp_expires'] = time() + 600;

    /* =========================
       📧 EMAIL OTP
    ========================= */
    $subject = "Your Login Verification Code";
    $message = "Your OTP is: $otp (valid 10 minutes)";
    $headers = "From: no-reply@yourdomain.com";

    @mail($email, $subject, $message, $headers);

    log_login_attempt($conn, $email, 1);

    echo json_encode([
        "otp_required" => true,
        "message" => "OTP sent to your email"
    ]);

    $stmt->close();
    exit;
}

/* =========================
   ❌ FAIL LOGIN
========================= */
log_login_attempt($conn, $email, 0);
auto_ban_ip($conn);

echo json_encode([
    "error" => "Invalid email or password"
]);

$stmt->close();
exit;