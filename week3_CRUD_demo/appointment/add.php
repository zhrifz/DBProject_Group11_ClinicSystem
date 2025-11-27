<?php
include "../auth/check_login.php";
include "../config/db.php";

// only staff
if ($_SESSION['role'] != "staff") {
    die("Access denied.");
}

// load patients
$p_result = mysqli_query($conn, "SELECT * FROM Patient");

// load doctors
$d_result = mysqli_query($conn, "SELECT * FROM Doctor");

if (isset($_POST['submit'])) {

    $number = $_POST['appointment_number'];
    $reason = $_POST['reason_for_appointment'];
    $time = $_POST['appointment_time'];
    $doctorID = $_POST['doctorID'];
    $patientID = $_POST['patientID'];

    // default values (staff cannot touch this)
    $status = "Upcoming";
    $come = "Upcoming";
    $comment = "";

    $sql = "INSERT INTO Appointment 
            (appointment_number, reason_for_appointment, appointment_time, status, 
             patient_come_into_hospital, doctor_comment, doctorID, patientID)
            VALUES 
            ('$number', '$reason', '$time', '$status', '$come', '$comment', '$doctorID', '$patientID')";

    if (mysqli_query($conn, $sql)) {
        header("Location: list.php");
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>

<!-- Select2 CDN -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<h2>Create Appointment</h2>

<form method="POST">
    
    Appointment Number:
    <input type="text" name="appointment_number" required><br><br>

    Patient:
    <select id="patientSelect" name="patientID" required style="width:300px;">
        <option value="">-- Select Patient --</option>
        <?php while ($p = mysqli_fetch_assoc($p_result)) { ?>
            <option value="<?= $p['patientID'] ?>"><?= $p['full_name'] ?></option>
        <?php } ?>
    </select>
    <br><br>

    Doctor:
    <select id="doctorSelect" name="doctorID" required style="width:300px;">
        <option value="">-- Select Doctor --</option>
        <?php while ($d = mysqli_fetch_assoc($d_result)) { ?>
            <option value="<?= $d['doctorID'] ?>"><?= $d['full_name'] ?> (<?= $d['specialization'] ?>)</option>
        <?php } ?>
    </select>
    <br><br>

    Reason:
    <textarea name="reason_for_appointment"></textarea><br><br>

    Date & Time:
    <input type="datetime-local" name="appointment_time" required><br><br>

    <button type="submit" name="submit">Save</button>
</form>

<br><a href="list.php">Back</a>

<script>
$(document).ready(function() {
    $('#patientSelect').select2({
        placeholder: "Search patient...",
        allowClear: true
    });

    $('#doctorSelect').select2({
        placeholder: "Search doctor...",
        allowClear: true
    });
});
</script>
