<?php require_once "includes/security.php"; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f5f7fa;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .register-container {
            width: 90%;
            max-width: 400px;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 5px 15px rgba(0,0,0,0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        input {
            width: 100%;
            padding: 12px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 16px;
        }

        button {
            width: 100%;
            padding: 12px;
            background: #28a745;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
        }

        button:hover {
            background: #218838;
        }

        #otpBox {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 85%;
            max-width: 300px;
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0px 5px 15px rgba(0,0,0,0.2);
            display: none;
            text-align: center;
        }
    </style>
</head>

<body>

<div class="register-container">
    <h2>📑 Register</h2>

    <form id="registerForm">
        <input type="text" name="name" placeholder="Full Name" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>

        <input type="hidden" name="csrf_token" id="csrf_token">

        <button type="submit">Register</button>
    </form>
</div>

<!-- OTP -->
<div id="otpBox">
    <h3>Enter OTP</h3>
    <input type="text" id="otp" placeholder="Enter OTP">
    <button onclick="verifyOTP()">Verify</button>
</div>

<script>

let currentUserId = null;
let csrfToken = "";

/* =========================
   FIX 1: SAFE CSRF LOADING
========================= */
async function loadCSRF(){
    try {
        const res = await fetch('includes/get_csrf.php');
        const data = await res.json();

        csrfToken = data.token;
        document.getElementById('csrf_token').value = csrfToken;

    } catch (e) {
        alert("Security system failed. Refresh page.");
    }
}

loadCSRF();

/* =========================
   FIX 2: REGISTER
========================= */
document.getElementById('registerForm').onsubmit = async function(e){
    e.preventDefault();

    let formData = new FormData(this);

    // ensure CSRF exists
    if(!csrfToken){
        alert("Security token not ready. Refresh page.");
        return;
    }

    fetch('api/register.php',{
        method:'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {

        if(data.otp_required){
            currentUserId = data.user_id;
            document.getElementById("otpBox").style.display = "block";
            alert("OTP sent to email");
        }

        else if(data.success){
            alert("Registration successful!");
            window.location.href = "login.php";
        }

        else {
            alert(data.error || "Registration failed");
        }
    })
    .catch(() => {
        alert("Network error");
    });
};

/* =========================
   FIX 3: OTP VERIFY
========================= */
function verifyOTP(){

    let otp = document.getElementById("otp").value;

    fetch('api/verify_otp.php',{
        method:'POST',
        headers: {"Content-Type":"application/x-www-form-urlencoded"},
        body:
            "user_id="+currentUserId+
            "&otp="+encodeURIComponent(otp)+
            "&csrf_token="+encodeURIComponent(csrfToken)
    })
    .then(res => res.json())
    .then(data => {

        if(data.success){
            localStorage.setItem("jwt", data.token);
            window.location.href = "index.php";
        } else {
            alert(data.error || "OTP failed");
        }

    })
    .catch(() => {
        alert("Network error");
    });
}

</script>

</body>
</html>