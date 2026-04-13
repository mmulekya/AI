<?php
require_once "includes/security.php"; 
session_start();
$loggedIn = isset($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>About BuildSmart AI</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
<style>
:root {
    --primary: #007bff;
    --secondary: #28a745;
    --light: #f5f7fa;
    --dark-bg: #181818;
    --dark-text: #eee;
}
*{ box-sizing:border-box; margin:0; padding:0; }
body{ font-family:'Inter', sans-serif; background:var(--light); color:#333; line-height:1.6;}
a{text-decoration:none; color: inherit;}
header{display:flex; justify-content:space-between; align-items:center; padding:20px 40px; background:white; box-shadow:0 2px 6px rgba(0,0,0,0.05); position:sticky; top:0; z-index:100;}
header h1{color: var(--primary); font-size:24px; font-weight:700;}
header img {height:30px; vertical-align:middle; margin-right:10px;}
header nav button{margin-left:10px; padding:8px 18px; border:none; border-radius:6px; cursor:pointer; background: var(--primary); color:white; transition:0.3s;}
header nav button:hover{background:#0056b3;}
main{max-width:1000px; margin:60px auto; padding:0 20px;}
section{margin-bottom:60px;}
section h2{font-size:36px; margin-bottom:20px; color:var(--primary);}
section p{font-size:18px; color:#555;}
footer{background:#333; color:white; text-align:center; padding:30px 20px; font-size:14px;}
body.dark{background:var(--dark-bg); color:var(--dark-text);}
body.dark header, body.dark main{background:#222; color:#eee;}
</style>
</head>
<body>

<header>
<h1><img src="assets/logo.png"  alt="BuildSmart AI Logo">
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

<main>
<section>
    <h2>About BuildSmart AI</h2>
    <p>BuildSmart AI is a modern construction assistant platform designed to help professionals and DIY enthusiasts plan, manage, and execute their projects more efficiently. Using advanced AI technology, BuildSmart provides real-time guidance, tips, and answers to all your construction questions.</p>
    <p>Our mission is to make construction smarter, faster, and safer for everyone. From bricklaying to electrical installations and concrete work, BuildSmart AI is your trusted digital partner.</p>
</section>

<section>
    <h2>Our Vision & Values</h2>
    <p>We aim to simplify complex construction tasks with AI-powered solutions, emphasizing safety, precision, and efficiency. Our values include:</p>
    <ul style="margin-top:10px; padding-left:20px;">
        <li>Innovation through technology</li>
        <li>Reliability and trust</li>
        <li>User-first approach</li>
    </ul>
</section>
</main>

<footer>
&copy; 2026 BuildSmart AI. All rights reserved. | <a href="privacy.php" style="color:#fff;">Privacy Policy</a>
</footer>

<script>
function toggleMode(){ document.body.classList.toggle("dark"); }
<?php if($loggedIn): ?>
function logout(){ localStorage.removeItem("jwt"); fetch("api/logout.php").then(()=>window.location.href="login.php"); }
<?php endif; ?>
</script>
</body>
</html>