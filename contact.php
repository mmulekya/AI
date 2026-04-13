<?php 
require_once "includes/security.php";

if(session_status() === PHP_SESSION_NONE){
    session_start();
}

$loggedIn = isset($_SESSION['user_id']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Contact - BuildSmart AI</title>

<meta name="description" content="Contact BuildSmart AI support team for assistance and inquiries.">
<meta name="robots" content="index, follow">

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">

<style>
/* (same CSS — unchanged) */
:root {
    --primary: #007bff;
    --secondary: #28a745;
    --light: #f5f7fa;
}
*{ box-sizing:border-box; margin:0; padding:0; }

body{
    font-family:'Inter', sans-serif;
    background:var(--light);
    color:#333;
}

header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    padding:20px 40px;
    background:white;
    box-shadow:0 2px 6px rgba(0,0,0,0.05);
}

header h1{color: var(--primary);}
    
header img {
    height:30px;
    vertical-align:middle;
    margin-right:10px;
}

header nav button{
    margin-left:10px;
    padding:8px 18px;
    border:none;
    border-radius:6px;
    cursor:pointer;
    background: var(--primary);
    color:white;
}

main{
    max-width:800px;
    margin:60px auto;
    padding:0 20px;
}

section h2{
    font-size:36px;
    margin-bottom:20px;
    color:var(--primary);
}

form{
    display:flex;
    flex-direction:column;
    gap:15px;
}

input, textarea{
    padding:12px;
    border-radius:6px;
    border:1px solid #ccc;
}

button{
    padding:12px;
    border:none;
    border-radius:6px;
    background:var(--primary);
    color:white;
}

footer{
    background:#333;
    color:white;
    text-align:center;
    padding:20px;
}
footer a{color:white;}
</style>

</head>

<body>

<header>
<h1> <img src="assets/logo.png" alt="BuildSmart AI Logo">
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
<h2>Contact Us</h2>

<p>
Have questions or need support? Send us a message below.
</p>

<!-- TRUST SIGNAL -->
<p style="font-size:14px; color:#555;">
BuildSmart AI is a legitimate software platform.  
We do NOT request financial or banking information.
</p>

<!-- CONTACT INFO -->
<p><strong>Email:</strong> buildsmart.ai.help@gmail.com</p>

<form id="contactForm">
<input type="text" name="name" placeholder="Your Name" required>
<input type="email" name="email" placeholder="Your Email" required>
<textarea name="message" rows="5" placeholder="Your Message" required></textarea>
<button type="submit">Send Message</button>
</form>

<div id="contactMessage" style="margin-top:10px;font-weight:bold;color:green;"></div>

</section>

</main>

<footer>
<p>© 2026 BuildSmart AI</p>

<p>
<a href="about.php">About</a> |
<a href="contact.php">Contact</a> |
<a href="privacy.php">Privacy</a> |
<a href="terms.php">Terms</a>
</p>
</footer>

<script>
document.getElementById("contactForm").onsubmit = function(e){
    e.preventDefault();

    let data = new FormData(this);

    fetch('api/contact.php',{
        method:'POST',
        body:data
    })
    .then(res=>res.json())
    .then(resp=>{
        if(resp.success){
            document.getElementById('contactMessage').innerText = "Message sent successfully!";
            this.reset();
        } else {
            document.getElementById('contactMessage').innerText = resp.error || "Error sending message.";
        }
    })
    .catch(()=>{
        document.getElementById('contactMessage').innerText = "Network error.";
    });
};

<?php if($loggedIn): ?>
function logout(){
    localStorage.removeItem("jwt");
    fetch("api/logout.php")
    .then(()=>window.location.href="login.php");
}
<?php endif; ?>
</script>

</body>
</html>