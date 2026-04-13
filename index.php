<?php require_once "includes/security.php";

if(session_status() === PHP_SESSION_NONE){
    session_start();
}

$loggedIn = isset($_SESSION['user_id']);
?>

<!DOCTYPE html>
<html lang="en">
<head>

<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>BuildSmart AI | AI Construction Assistant Platform</title>

<!-- Google verification -->
<meta name="google-site-verification" content="HUfw3qi4KakAG6Qrp-yRDWQKsPTvrNIqOvHuah6JV2U" />

<!-- SEO -->
<meta name="description" content="BuildSmart AI - AI-powered construction assistant for engineers and builders.">
<meta name="keywords" content="construction AI, engineering AI, building assistant">
<meta name="author" content="BuildSmart AI">
<meta name="robots" content="index, follow">

<!-- Open Graph -->
<meta property="og:title" content="BuildSmart AI">
<meta property="og:description" content="AI-powered construction assistant platform">
<meta property="og:type" content="website">
<meta property="og:url" content="https://buildsmart.wuaze.com">
<meta property="og:image" content="https://buildsmart.wuaze.com/assets/logo.png">

<meta name="theme-color" content="#007bff">

<!-- Favicon -->
<link rel="icon" href="favicon.ico" type="image/x-icon">

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">

<style>
:root {
    --primary: #007bff;
    --secondary: #28a745;
    --light: #f5f7fa;
}
* { margin:0; padding:0; box-sizing:border-box; }

body {
    font-family: 'Inter', sans-serif;
    background: var(--light);
    color: #333;
}

/* HEADER */
header {
    display:flex;
    justify-content:space-between;
    align-items:center;
    padding:20px 40px;
    background:white;
    box-shadow:0 2px 6px rgba(0,0,0,0.05);
}

header h1 {
    color: var(--primary);
    font-size:24px;
}

header img {
    height:30px;
    vertical-align:middle;
    margin-right:10px;
}

header nav button {
    margin-left:10px;
    padding:8px 18px;
    border:none;
    border-radius:6px;
    cursor:pointer;
    background: var(--primary);
    color:white;
}

/* HERO */
.hero {
    text-align:center;
    padding:100px 20px;
    background:linear-gradient(135deg,#007bff,#28a745);
    color:white;
}

.hero h2 { font-size:36px; margin-bottom:20px; }
.hero p { font-size:18px; margin-bottom:30px; }

/* FEATURES */
.features {
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(250px,1fr));
    gap:30px;
    padding:60px 20px;
}

.feature {
    background:white;
    padding:20px;
    border-radius:10px;
    text-align:center;
}

/* FOOTER */
footer {
    background:#333;
    color:white;
    text-align:center;
    padding:20px;
}
footer a { color:white; text-decoration:none; }
</style>

</head>

<body>

<header>
    <h1>
        <img src="assets/logo.png" alt="BuildSmart AI Logo">
        BuildSmart AI
    </h1>

    <nav>
        <button onclick="location.href='index.php'">Home</button>
        <button onclick="location.href='about.php'">About</button>
        <button onclick="location.href='contact.php'">Contact</button>

        <?php if($loggedIn): ?>
            <button onclick="logout()" style="background:red;">Logout</button>
        <?php else: ?>
            <button onclick="location.href='login.php'">Login</button>
        <?php endif; ?>
    </nav>
</header>

<section class="hero">
    <h2>Smart Construction Assistant</h2>
    <p>AI-powered assistant for building and project planning.</p>

    <?php if(!$loggedIn): ?>
        <button onclick="location.href='login.php'">Get Started</button>
    <?php endif; ?>
</section>

<!-- TRUST BANNER -->
<div style="background:#fff3cd; padding:15px; margin:20px; border-radius:8px; text-align:center;">
⚠️ BuildSmart AI is a legitimate AI-powered construction platform.  
We DO NOT request banking or financial information.
</div>

<!-- ABOUT SECTION -->
<section style="padding:20px; text-align:center;">
<h3>About BuildSmart AI</h3>

<p>
BuildSmart AI is a professional software platform designed to assist engineers,
builders, and construction professionals with AI-powered guidance.
</p>

<p>
This platform is not affiliated with any financial institution and does not 
request or process sensitive financial data.
</p>
</section>

<section class="features">
    <div class="feature">
        <h3>AI Chat</h3>
        <p>Ask construction questions instantly.</p>
    </div>

    <div class="feature">
        <h3>Project Guidance</h3>
        <p>Step-by-step building support.</p>
    </div>

    <div class="feature">
        <h3>Secure Platform</h3>
        <p>Protected with OTP & secure login.</p>
    </div>
</section>

<!-- SECURITY BADGE -->
<div style="text-align:center; margin:20px;">
🔒 Secure Platform | Verified AI Service
</div>

<footer>
    <p>© 2026 BuildSmart AI</p>

    <p>
    BuildSmart AI is a software platform for construction assistance.
    This is NOT a financial service and does NOT collect banking information.
    </p>

    <p>
    📧 Email: buildsmart.ai.help@gmail.com
    </p>

    <p>
        <a href="about.php">About</a> |
        <a href="contact.php">Contact</a> |
        <a href="privacy.php">Privacy</a> |
        <a href="terms.php">Terms</a>
    </p>
</footer>

</body>
</html>