<?php
include "../../auth/check_login.php";
include "../../config/db.php";

if ($_SESSION['role'] != "doctor") {
    die("Access denied.");
}

if (!isset($_GET['id'])) {
    die("Invalid appointment.");
}

$appointmentID = intval($_GET['id']);
$doctorID = $_SESSION['id'];

/* ==========================
   GET APPOINTMENT DETAIL
========================== */
$q = mysqli_query($conn, "
    SELECT 
        Appointment.*,
        Patient.full_name,
        Patient.gender,
        Patient.phone
    FROM Appointment
    JOIN Patient ON Appointment.patientID = Patient.patientID
    WHERE Appointment.appointmentID = $appointmentID
    AND Appointment.doctorID = $doctorID
");

if (mysqli_num_rows($q) == 0) {
    die("Appointment not found.");
}

$data = mysqli_fetch_assoc($q);

/* ==========================
   SAVE COMMENT
========================== */
if (isset($_POST['save_comment'])) {
    $comment = mysqli_real_escape_string($conn, $_POST['doctor_comment']);

    mysqli_query($conn, "
        UPDATE Appointment
        SET doctor_comment = '$comment'
        WHERE appointmentID = $appointmentID
    ");

    header("Location: view_patient.php?id=$appointmentID&saved=1");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>View Patient</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
<script src="https://kit.fontawesome.com/2d3e5b1abc.js" crossorigin="anonymous"></script>

<style>
body { margin:0; font-family:Poppins,sans-serif; background:#f3f0ff; display:flex; }
.sidebar { width:250px; height:100vh; position:fixed; background:linear-gradient(180deg,#8ab6ff,#c7a3ff); padding:20px; color:white; }
.sidebar h2 { text-align:center; margin-bottom:30px; }
.sidebar a { display:block; padding:12px 15px; border-radius:10px; text-decoration:none; color:white; margin-bottom:10px; transition:.2s; }
.sidebar a:hover { background:rgba(255,255,255,0.3); transform:translateX(5px); }

.main { margin-left:260px; padding:40px; width:calc(100% - 260px); }

.card { background:white; padding:25px; border-radius:15px; box-shadow:0 4px 12px rgba(0,0,0,0.08); margin-bottom:25px; }

.card h3 { margin-top:0; }

.info-grid { display:grid; grid-template-columns:1fr 1fr; gap:15px; }

label { font-weight:500; color:#555; font-size:14px; }
.value { font-size:15px; margin-top:3px; }

textarea {
    width:100%;
    height:120px;
    padding:10px;
    border-radius:8px;
    border:1px solid #ccc;
    resize:none;
}

button {
    background:#7b6dff;
    color:white;
    border:none;
    padding:10px 18px;
    border-radius:8px;
    cursor:pointer;
    font-size:14px;
}
button:hover { background:#5e52d5; }

.success {
    background:#e6fff2;
    padding:12px;
    border-radius:8px;
    color:#1b7f4b;
    margin-bottom:20px;
}
</style>
</head>

<body>

<<!-- ================= SIDEBAR ================= -->
<div class="sidebar">
    <h2>Doctor Panel</h2>

    <a href="doctor_dashboard.php">
        <i class="fa-solid fa-gauge"></i> Dashboard
    </a>

    <!-- UPCOMING CLICKABLE -->
    <a href="doctor_upcoming.php">
        <i class="fa-solid fa-calendar-days"></i> Upcoming
    </a>

    <!-- UPCOMING PREVIEW -->
    <div class="upcoming">
        <?php while($up = mysqli_fetch_assoc($q_upcoming)) { ?>
            <p>
                <?= $up['full_name'] ?><br>
                <small><?= date('d M, h:i A', strtotime($up['appointment_time'])) ?></small>
            </p>
        <?php } ?>
    </div>

    <a href="../../backend/auth/logout.php">
        <i class="fa-solid fa-right-from-bracket"></i> Logout
    </a>
</div>

<div class="main">

    <?php if(isset($_GET['saved'])){ ?>
        <div class="success">Doctor comment saved successfully.</div>
    <?php } ?>

    <!-- PATIENT INFO -->
    <div class="card">
        <h3>Patient Information</h3>

        <div class="info-grid">
            <div>
                <label>Full Name</label>
                <div class="value"><?= $data['full_name'] ?></div>
            </div>

            <div>
                <label>Gender</label>
                <div class="value"><?= $data['gender'] ?></div>
            </div>

            <div>
                <label>Phone</label>
                <div class="value"><?= $data['phone'] ?></div>
            </div>
        </div>
    </div>

    <!-- APPOINTMENT INFO -->
    <div class="card">
        <h3>Appointment Detail</h3>

        <div class="info-grid">
            <div>
                <label>Date</label>
                <div class="value"><?= date('d M Y', strtotime($data['appointment_time'])) ?></div>
            </div>

            <div>
                <label>Time</label>
                <div class="value"><?= date('h:i A', strtotime($data['appointment_time'])) ?></div>
            </div>

            <div>
                <label>Condition / Reason</label>
                <div class="value"><?= $data['reason_for_appointment'] ?></div>
            </div>

            <div>
                <label>Status</label>
                <div class="value"><?= ucfirst($data['status']) ?></div>
            </div>
        </div>
    </div>

    <!-- DOCTOR COMMENT -->
    <div class="card">
        <h3>Doctor Comment</h3>

        <form method="POST">
            <textarea name="doctor_comment"><?= $data['doctor_comment'] ?></textarea>
            <br><br>
            <button type="submit" name="save_comment">
                <i class="fa-solid fa-floppy-disk"></i> Save Comment
            </button>
        </form>
    </div>

</div>

</body>
</html>
