<?php
include "../../auth/check_login.php";
include "../../config/db.php";

if ($_SESSION['role'] != "doctor") {
    die("Access denied.");
}

$doctorID = $_SESSION['id'];
$today = date("Y-m-d");

/* ==========================
   TOTAL APPOINTMENTS TODAY
========================== */
$q_today = mysqli_query($conn, "
    SELECT COUNT(*) AS total_today 
    FROM Appointment 
    WHERE doctorID = $doctorID 
    AND DATE(appointment_time) = '$today'
");
$today_count = mysqli_fetch_assoc($q_today)['total_today'];

/* ==========================
   NEXT UPCOMING APPOINTMENT
========================== */
$q_next = mysqli_query($conn, "
    SELECT Appointment.appointment_time, Patient.full_name
    FROM Appointment
    JOIN Patient ON Appointment.patientID = Patient.patientID
    WHERE Appointment.doctorID = $doctorID
    AND Appointment.appointment_time > NOW()
    ORDER BY Appointment.appointment_time ASC
    LIMIT 1
");
$next_appt = mysqli_fetch_assoc($q_next);

/* ==========================
   TODAY PATIENT LIST
========================== */
$q_today_list = mysqli_query($conn, "
    SELECT 
        Appointment.appointmentID,
        Appointment.appointment_time,
        Appointment.reason_for_appointment,
        Patient.full_name
    FROM Appointment
    JOIN Patient ON Appointment.patientID = Patient.patientID
    WHERE Appointment.doctorID = $doctorID
    AND DATE(Appointment.appointment_time) = '$today'
    ORDER BY Appointment.appointment_time ASC
");

/* ==========================
   UPCOMING (SIDEBAR PREVIEW)
========================== */
$q_upcoming = mysqli_query($conn, "
    SELECT Appointment.appointment_time, Patient.full_name
    FROM Appointment
    JOIN Patient ON Appointment.patientID = Patient.patientID
    WHERE Appointment.doctorID = $doctorID
    AND Appointment.appointment_time > NOW()
    ORDER BY Appointment.appointment_time ASC
    LIMIT 5
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Doctor Dashboard</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
<script src="https://kit.fontawesome.com/2d3e5b1abc.js" crossorigin="anonymous"></script>

<style>
body { margin:0; font-family:Poppins,sans-serif; background:#f3f0ff; display:flex; }

.sidebar {
    width:250px; height:100vh; position:fixed;
    background:linear-gradient(180deg,#8ab6ff,#c7a3ff);
    padding:20px; color:white; overflow-y:auto;
}
.sidebar h2 { text-align:center; margin-bottom:30px; }
.sidebar a {
    display:block; padding:12px 15px; border-radius:10px;
    text-decoration:none; color:white; margin-bottom:10px;
    transition:0.2s;
}
.sidebar a:hover {
    background: rgba(255,255,255,0.3);
    transform: translateX(5px);
}

.upcoming { font-size:13px; margin-top:10px; padding-left:10px; }
.upcoming p { margin:6px 0; }

.main { margin-left:260px; padding:40px; width:calc(100% - 260px); }

.header {
    background:white; padding:25px; border-radius:15px;
    box-shadow:0 3px 12px rgba(0,0,0,0.08);
    margin-bottom:30px;
}

.flex-container { display:flex; gap:20px; margin-bottom:30px; }

.card {
    background:white; padding:20px; border-radius:15px;
    box-shadow:0 4px 12px rgba(0,0,0,0.08); flex:1;
}
.card h3 { margin:0; font-size:16px; }
.card p { font-size:22px; font-weight:600; margin-top:10px; }

.left {
    background:white; padding:20px; border-radius:15px;
    box-shadow:0 4px 12px rgba(0,0,0,0.08);
}

table { width:100%; border-collapse:collapse; margin-top:15px; }
th { background:#8ab6ff; color:white; padding:12px; text-align:left; }
td { padding:10px; border-bottom:1px solid #ddd; }
tr:hover { background:#f0f6ff; }

.view-btn {
    background:#7b6dff; color:white;
    padding:6px 12px; border-radius:8px;
    text-decoration:none; font-size:13px;
}
.view-btn:hover { background:#5e52d5; }
</style>
</head>

<body>

<!-- ================= SIDEBAR ================= -->
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

<!-- ================= MAIN ================= -->
<div class="main">

    <div class="header">
        <h1>Welcome Dr. <?= $_SESSION['name'] ?></h1>
        <p>Here's a summary of your appointments</p>
    </div>

    <div class="flex-container">
        <div class="card">
            <h3>Total Appointments Today</h3>
            <p><?= $today_count ?></p>
        </div>

        <div class="card">
            <h3>Next Appointment</h3>
            <?php if($next_appt){ ?>
                <p><?= $next_appt['full_name'] ?></p>
                <small><?= date('d M Y, h:i A', strtotime($next_appt['appointment_time'])) ?></small>
            <?php } else { ?>
                <p>No upcoming</p>
            <?php } ?>
        </div>
    </div>

    <div class="left">
        <h3>Today's Patients</h3>

        <?php if(mysqli_num_rows($q_today_list)==0){ ?>
            <p>No appointments today.</p>
        <?php } else { ?>

        <table>
            <thead>
                <tr>
                    <th>Patient Name</th>
                    <th>Condition</th>
                    <th>Time</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php while($row=mysqli_fetch_assoc($q_today_list)){ ?>
                <tr>
                    <td><?= $row['full_name'] ?></td>
                    <td><?= $row['reason_for_appointment'] ?></td>
                    <td><?= date('h:i A', strtotime($row['appointment_time'])) ?></td>
                    <td>
                        <a class="view-btn"
                           href="view_patient.php?id=<?= $row['appointmentID'] ?>">
                           View
                        </a>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>

        <?php } ?>
    </div>

</div>
</body>
</html>
