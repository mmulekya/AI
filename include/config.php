<?php
// =========================================
// SECURITY CONFIG (CLEAN VERSION)
// =========================================
    ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
session_start();

// -------------------------------
// SESSION FIRST (VERY IMPORTANT)
// -------------------------------
if (session_status() === PHP_SESSION_NONE) {
    session_name("BuildSmartSession");
    session_start();
}

// -------------------------------
// SECURITY HEADERS (SAFE CSP)
// -------------------------------
header("X-Frame-Options: SAMEORIGIN");
header("X-Content-Type-Options: nosniff");
header("X-XSS-Protection: 1; mode=block");
header("Referrer-Policy: strict-origin-when-cross-origin");

header("Content-Security-Policy: 
    default-src 'self';
    img-src 'self' data:;
    connect-src 'self' https:;
    font-src 'self' https:;
    frame-ancestors 'none';
");

// -------------------------------
// ERROR HANDLING (PRODUCTION SAFE)
// -------------------------------
ini_set('display_errors', 0);
error_reporting(E_ALL);

// -------------------------------
// ENV LOADER
// -------------------------------
$env_path = __DIR__ . '/../.env';

if (!file_exists($env_path)) {
    http_response_code(500);
    die("Server configuration error");
}

$env = parse_ini_file($env_path, false, INI_SCANNER_TYPED);

// -------------------------------
// CONSTANTS
// -------------------------------
define("DB_HOST", $env['DB_HOST'] ?? 'localhost');
define("DB_USER", $env['DB_USER'] ?? '');
define("DB_PASS", $env['DB_PASS'] ?? '');
define("DB_NAME", $env['DB_NAME'] ?? '');
define("OPENAI_API_KEY", $env['OPENAI_API_KEY'] ?? '');
define("JWT_SECRET", $env['JWT_SECRET'] ?? 'default_secret'); // FIXED

// -------------------------------
// DATABASE CONNECTION
// -------------------------------
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if ($conn->connect_error) {
    http_response_code(500);
    die("Database connection failed");
}

$conn->set_charset("utf8mb4");

// -------------------------------
// CSRF TOKEN (FIXED SAFE)
// -------------------------------
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

function get_csrf_token() {
    return $_SESSION['csrf_token'];
}

function verify_csrf_token($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

// -------------------------------
// SANITIZATION
// -------------------------------
function sanitize_input($data) {
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

// -------------------------------
// BOT BLOCKING (SAFE)
// -------------------------------
$bad_agents = ['curl', 'wget', 'python', 'bot', 'scanner'];
$ua = strtolower($_SERVER['HTTP_USER_AGENT'] ?? '');

foreach ($bad_agents as $agent) {
    if (strpos($ua, $agent) !== false) {
        http_response_code(403);
        exit("Access denied");
    }
}

// -------------------------------
// REQUEST SIZE LIMIT
// -------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = file_get_contents("php://input");
    if ($input && strlen($input) > 10240) {
        http_response_code(413);
        exit("Request too large");
    }
}

// -------------------------------
// PASSWORD HASHING
// -------------------------------
define("PASSWORD_COST", 12);

function hash_password($password) {
    return password_hash($password, PASSWORD_BCRYPT, [
        "cost" => PASSWORD_COST
    ]);
}