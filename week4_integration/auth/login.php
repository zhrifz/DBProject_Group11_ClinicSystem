<?php
session_start();
$error = $_SESSION['login_error'] ?? '';
unset($_SESSION['login_error']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Login</title>

<style>
body {
    margin:0;
    font-family:Poppins,sans-serif;
    background:#eef1fb;
    height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
}
.login-container {
    background:white;
    padding:40px;
    width:360px;
    border-radius:20px;
    box-shadow:0 10px 25px rgba(0,0,0,0.08);
}
h2 {
    text-align:center;
    margin-bottom:25px;
    color:#3d3a5a;
}
label {
    font-size:14px;
    display:block;
    margin-bottom:6px;
}
input, select {
    width:100%;
    padding:12px;
    margin-bottom:18px;
    border-radius:10px;
    border:1px solid #d6d6e6;
    background:#f8f8ff;
}
button {
    width:100%;
    padding:14px;
    border:none;
    border-radius:12px;
    background:linear-gradient(135deg,#7da7ff,#b89cff);
    color:white;
    font-size:15px;
    cursor:pointer;
}
.error {
    color:red;
    text-align:center;
    font-size:13px;
    margin-bottom:10px;
}
</style>
</head>

<body>
<div class="login-container">
    <h2>Clinic Login</h2>

    <?php if($error): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" action="../backend/auth/login_action.php">

        <label>Login As</label>
        <select name="role" required>
            <option value="">-- Select Role --</option>
            <option value="staff">Staff</option>
            <option value="doctor">Doctor</option>
        </select>

        <label>Username</label>
        <input type="text" name="username" required>

        <label>Password</label>
        <input type="password" name="password" required>

        <button type="submit" name="login">Login</button>
    </form>
</div>
</body>
</html>
