<?php
include "../../auth/check_login.php";
include "../../config/db.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] !== "staff") {
    http_response_code(403);
    exit("Access denied.");
}

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) exit("Invalid request");

// fetch doctor securely
$stmt = $conn->prepare("SELECT * FROM Doctor WHERE doctorID = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$doctor = $result->fetch_assoc();

if (!$doctor) exit("Doctor not found!");

// working days
$selected_days = explode(", ", $doctor['working_days']);
$days_list = ["Monday","Tuesday","Wednesday","Thursday","Friday","Saturday","Sunday"];

// CSRF
$_SESSION['csrf_token'] = bin2hex(random_bytes(32));

function e($value) {
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Edit Doctor</title>
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
    <a href="list.php"><i class="fa-solid fa-hospital-user"></i> Patients</a>
    <a href="../doctor/list.php"><i class="fa-solid fa-user-doctor"></i> Doctors</a>
    <a href="../appointment/list.php"><i class="fa-solid fa-calendar-check"></i> Appointments</a>
</div>

<div class="main">
    <div class="header">
        <h1>Edit Doctor</h1>
        <p>Update the doctor information below.</p>
    </div>

    <div class="form-card">
        <form method="POST" action="../../backend/doctor/edit_action.php?id=<?= $id ?>">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

            <label>Username</label>
            <input type="text" name="username" value="<?= e($doctor['username']) ?>" required>

            <label>New Password (optional)</label>
            <input type="password" name="password">
            <small>Leave empty to keep current password</small>

            <label>Full Name</label>
            <input type="text" name="full_name" value="<?= e($doctor['full_name']) ?>">

            <label>Gender</label>
            <select name="gender" required>
                <option value="male"   <?= $doctor['gender']=='male'?'selected':'' ?>>Male</option>
                <option value="female" <?= $doctor['gender']=='female'?'selected':'' ?>>Female</option>
            </select>

            <label>Room No</label>
            <input type="text" name="room_no" value="<?= e($doctor['room_no']) ?>">

            <label>Specialization</label>
            <input type="text" name="specialization" value="<?= e($doctor['specialization']) ?>">

            <label>Phone</label>
            <input type="text" name="phone" value="<?= e($doctor['phone']) ?>">

            <label>Email</label>
            <input type="email" name="email" value="<?= e($doctor['email']) ?>">

            <label>Working Days</label>
            <div class="working-days">
                <?php foreach ($days_list as $day): ?>
                    <?php $checked = in_array($day, $selected_days) ? "checked" : ""; ?>
                    <input type="checkbox" name="working_days[]" value="<?= $day ?>" <?= $checked ?>> <?= $day ?><br>
                <?php endforeach; ?>
            </div>

            <button type="submit" name="update" class="btn-submit">Update</button>
        </form>

        <a href="list.php" class="btn-back">
            <i class="fa-solid fa-arrow-left"></i> Back
        </a>
    </div>
</div>

</body>
</html>
