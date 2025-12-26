<?php
include "../../auth/check_login.php";
include "../../config/db.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] !== "staff") {
    http_response_code(403);
    exit("Access denied.");
}

// create CSRF token
$_SESSION['csrf_token'] = bin2hex(random_bytes(32));

$days_list = ["Monday","Tuesday","Wednesday","Thursday","Friday","Saturday","Sunday"];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Add Doctor</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
<script src="https://kit.fontawesome.com/2d3e5b1abc.js" crossorigin="anonymous"></script>

<style>
body { margin:0; font-family:Poppins,sans-serif; background:#f3f0ff; display:flex; }
.sidebar { width:250px; height:100vh; position:fixed; left:0; top:0; background:linear-gradient(180deg,#8ab6ff,#c7a3ff); padding:20px; color:white; }
.sidebar h2 { text-align:center; margin-bottom:30px; }
.sidebar a { display:block; padding:12px 15px; border-radius:10px; text-decoration:none; color:white; margin-bottom:10px; transition:0.2s ease; }
.sidebar a:hover { background: rgba(255,255,255,0.3); transform:translateX(5px); }
.main { margin-left:260px; padding:40px; width:calc(100% - 260px); }
.header { background:white; padding:25px 30px; border-radius:15px; box-shadow:0 3px 12px rgba(0,0,0,0.08); margin-bottom:30px; }
.header h1 { font-size:26px; font-weight:600; margin:0; }
.header p { margin-top:5px; margin-bottom:0; color:#555; }
.form-card { background:white; padding:30px; border-radius:18px; box-shadow:0 4px 12px rgba(0,0,0,0.08); max-width:700px; margin:0 auto; }
.form-card label { font-weight:500; display:block; margin-top:10px; }
.form-card input, .form-card select { width:100%; padding:10px; border:1px solid #ccc; border-radius:10px; margin-top:5px; font-size:14px; }
.working-days input[type="checkbox"] { vertical-align: middle; margin-right:8px; margin-bottom:8px; }
.btn-submit { background:#7b6dff; border:none; padding:12px; color:white; border-radius:10px; font-size:16px; cursor:pointer; transition:0.2s; width:100%; margin-top:20px; }
.btn-submit:hover { background:#5e52d5; }
.btn-back { background:#ff6b6b; border:none; padding:12px; color:white; border-radius:10px; font-size:16px; cursor:pointer; transition:0.2s; width:97%; margin-top:10px; text-align:center; text-decoration:none; display:inline-block; }
.btn-back:hover { background:#e05555; }
</style>
</head>
<body>

<div class="sidebar">
    <h2>Clinic Admin</h2>
    <a href="../dashboard/staff_dashboard.php"><i class="fa-solid fa-gauge"></i> Dashboard</a>
    <a href="../patient/list.php"><i class="fa-solid fa-hospital-user"></i> Patients</a>
    <a href="../doctor/list.php"><i class="fa-solid fa-user-doctor"></i> Doctors</a>
    <a href="../appointment/list.php"><i class="fa-solid fa-calendar-check"></i> Appointments</a>
</div>

<div class="main">
    <div class="header">
        <h1>Add Doctor</h1>
        <p>Fill in the doctor's details below.</p>
    </div>

    <div class="form-card">
        <form method="POST" action="../../backend/doctor/add_action.php">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

            <label>Username</label>
            <input type="text" name="username" required>

            <label>Password</label>
            <input type="password" name="password" required>

            <label>Full Name</label>
            <input type="text" name="full_name" required>

            <label>Gender</label>
            <select name="gender" required>
                <option value="">Select</option>
                <option value="male">Male</option>
                <option value="female">Female</option>
            </select>

            <label>Room No</label>
            <input type="text" name="room_no">

            <label>Specialization</label>
            <input type="text" name="specialization">

            <label>Phone</label>
            <input type="text" name="phone">

            <label>Email</label>
            <input type="email" name="email">

            <label>Working Days</label>
            <div class="working-days">
                <?php foreach ($days_list as $day): ?>
                    <input type="checkbox" name="working_days[]" value="<?= $day ?>"> <?= $day ?><br>
                <?php endforeach; ?>
            </div>

            <button type="submit" name="submit" class="btn-submit">Add Doctor</button>
        </form>

        <a href="list.php" class="btn-back">
            <i class="fa-solid fa-arrow-left"></i> Back
        </a>
    </div>
</div>

</body>
</html>
