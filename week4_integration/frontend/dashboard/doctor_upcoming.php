<?php
include "../../auth/check_login.php";
include "../../config/db.php";

if ($_SESSION['role'] != "doctor") {
    die("Access denied.");
}

$doctorID = $_SESSION['id'];

/* ==========================
   APPOINTMENTS FOR CALENDAR
========================== */
$appointments = [];

$q = mysqli_query($conn, "
    SELECT 
        Appointment.appointment_time,
        Patient.full_name AS patient_name,
        Patient.gender AS patient_gender
    FROM Appointment
    JOIN Patient ON Appointment.patientID = Patient.patientID
    WHERE Appointment.doctorID = $doctorID
    AND Appointment.appointment_time >= CURDATE()
    ORDER BY Appointment.appointment_time ASC
");

while ($row = mysqli_fetch_assoc($q)) {
    $date = date('Y-m-d', strtotime($row['appointment_time']));
    $time = date('h:i A', strtotime($row['appointment_time']));
    $title = strtolower($row['patient_gender']) == 'male' ? 'Mr.' : 'Ms.';

    if (!isset($appointments[$date])) $appointments[$date] = [];

    $appointments[$date][] = [
        'time' => $time,
        'patient' => $title . ' ' . $row['patient_name']
    ];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Upcoming Appointments</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
<script src="https://kit.fontawesome.com/2d3e5b1abc.js" crossorigin="anonymous"></script>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>

<style>
body {
    margin:0;
    font-family:Poppins,sans-serif;
    background:#f3f0ff;
    display:flex;
}

/* Sidebar */
.sidebar {
    width:250px;
    height:100vh;
    position:fixed;
    background:linear-gradient(180deg,#8ab6ff,#c7a3ff);
    padding:20px;
    color:white;
}
.sidebar h2 { text-align:center; margin-bottom:30px; }
.sidebar a {
    display:block;
    padding:12px 15px;
    border-radius:10px;
    text-decoration:none;
    color:white;
    margin-bottom:10px;
    transition:.2s;
}
.sidebar a:hover {
    background:rgba(255,255,255,0.3);
    transform:translateX(5px);
}

/* Main */
.main {
    margin-left:260px;
    padding:40px;
    width:calc(100% - 260px);
}

/* Header */
.header {
    background:white;
    padding:25px;
    border-radius:15px;
    box-shadow:0 3px 12px rgba(0,0,0,0.08);
    margin-bottom:30px;
}
.header h1 { margin:0; font-size:26px; }
.header p { color:#555; margin-top:5px; }

/* Calendar */
#calendar {
    background:white;
    padding:20px;
    border-radius:15px;
    box-shadow:0 4px 12px rgba(0,0,0,0.08);
}

/* Popup */
.app-popup {
    position:fixed;
    top:50%;
    left:50%;
    transform:translate(-50%,-50%);
    background:white;
    padding:15px;
    border-radius:12px;
    box-shadow:0 4px 12px rgba(0,0,0,.25);
    max-width:300px;
    z-index:9999;
    font-size:13px;
}

.app-popup h4 {
    margin-top:0;
    font-size:15px;
}

.app-popup button {
    margin-top:10px;
    padding:8px 12px;
    border:none;
    background:#ff6b6b;
    color:white;
    border-radius:8px;
    cursor:pointer;
}
</style>
</head>

<body>

<!-- SIDEBAR -->
<div class="sidebar">
    <h2>Doctor Panel</h2>
    <a href="doctor_dashboard.php"><i class="fa-solid fa-gauge"></i> Dashboard</a>
    <a href="doctor_upcoming.php"><i class="fa-solid fa-calendar-days"></i> Upcoming</a>
    <a href="../../backend/auth/logout.php"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
</div>

<!-- MAIN -->
<div class="main">
    <div class="header">
        <h1>Upcoming Appointments</h1>
        <p>View your upcoming appointments via calendar</p>
    </div>

    <div id="calendar"></div>
</div>

<script>
const appointments = <?php echo json_encode($appointments); ?>;

document.addEventListener('DOMContentLoaded', function() {
    const calendarEl = document.getElementById('calendar');

    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        height: 'auto',
        events: Object.keys(appointments).map(date => ({
            title: appointments[date].length + ' Appointment(s)',
            start: date,
            allDay: true,
            backgroundColor: '#7b6dff',
            borderColor: '#5e52d5'
        })),
        eventClick: function(info) {
            const date = info.event.startStr;
            const list = appointments[date]
                .map(a => `<div>${a.time} — ${a.patient}</div>`)
                .join('');

            const popup = document.createElement('div');
            popup.className = 'app-popup';
            popup.innerHTML = `
                <h4>${date}</h4>
                ${list}
                <button onclick="this.parentElement.remove()">Close</button>
            `;
            document.body.appendChild(popup);
        }
    });

    calendar.render();
});
</script>

</body>
</html>
