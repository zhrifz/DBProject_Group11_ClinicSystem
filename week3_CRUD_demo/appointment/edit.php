<?php
include "../auth/check_login.php";
include "../config/db.php";

// only staff can edit
if ($_SESSION['role'] != "staff") {
    die("Access denied.");
}

$id = $_GET['id'];

$sql = "SELECT * FROM Appointment WHERE appointmentID = $id";
$result = mysqli_query($conn, $sql);
$appt = mysqli_fetch_assoc($result);

// load patient list
$p_result = mysqli_query($conn, "SELECT * FROM Patient");

// load doctor list
$d_result = mysqli_query($conn, "SELECT * FROM Doctor");

if (isset($_POST['update'])) {

    $number = $_POST['appointment_number'];
    $reason = $_POST['reason_for_appointment'];
    $time = $_POST['appointment_time'];
    $status = $_POST['status'];
    $come = $_POST['patient_come_into_hospital'];
    
    // staff cannot edit doctor comment → keep old
    $comment = $appt['doctor_comment'];

    $doctorID = $_POST['doctorID'];
    $patientID = $_POST['patientID'];

    $update = "UPDATE Appointment SET
                appointment_number='$number',
                reason_for_appointment='$reason',
                appointment_time='$time',
                status='$status',
                patient_come_into_hospital='$come',
                doctor_comment='$comment',
                doctorID='$doctorID',
                patientID='$patientID'
               WHERE appointmentID=$id";

    if (mysqli_query($conn, $update)) {
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

<h2>Edit Appointment</h2>

<form method="POST">

    Appointment Number:
    <input type="text" name="appointment_number" value="<?= $appt['appointment_number'] ?>" required><br><br>

    Patient:
    <select id="patientSelect" name="patientID" required style="width:300px;">
        <?php while ($p = mysqli_fetch_assoc($p_result)) { ?>
            <option value="<?= $p['patientID'] ?>" <?= $p['patientID']==$appt['patientID']?'selected':'' ?>>
                <?= $p['full_name'] ?>
            </option>
        <?php } ?>
    </select>
    <br><br>

    Doctor:
    <select id="doctorSelect" name="doctorID" required style="width:300px;">
        <?php while ($d = mysqli_fetch_assoc($d_result)) { ?>
            <option value="<?= $d['doctorID'] ?>" <?= $d['doctorID']==$appt['doctorID']?'selected':'' ?>>
                <?= $d['full_name'] ?> (<?= $d['specialization'] ?>)
            </option>
        <?php } ?>
    </select>
    <br><br>

    Reason:
    <textarea name="reason_for_appointment"><?= $appt['reason_for_appointment'] ?></textarea><br><br>

    Date & Time:
    <input type="datetime-local" name="appointment_time"
           value="<?= date('Y-m-d\TH:i', strtotime($appt['appointment_time'])) ?>" required>
    <br><br>

    Status:
    <select name="status">
        <option <?= $appt['status']=="Upcoming" ? "selected":"" ?>>Upcoming</option>
        <option <?= $appt['status']=="Completed" ? "selected":"" ?>>Completed</option>
        <option <?= $appt['status']=="Cancelled" ? "selected":"" ?>>Cancelled</option>
    </select>
    <br><br>

    Patient Arrived?:
    <select name="patient_come_into_hospital">
        <option value="Upcoming" <?= $appt['patient_come_into_hospital']=="Upcoming"?"selected":"" ?>>Upcoming</option>
        <option value="yes" <?= $appt['patient_come_into_hospital']=="yes"?"selected":"" ?>>Yes</option>
        <option value="no" <?= $appt['patient_come_into_hospital']=="no"?"selected":"" ?>>No</option>
    </select>
    <br><br>

    Doctor Comment (read-only):
    <textarea name="doctor_comment" readonly style="background:#f0f0f0;"><?= $appt['doctor_comment'] ?></textarea>
    <br><br>

    <button type="submit" name="update">Update</button>

</form>

<br>
<a href="list.php">Back</a>

<script>
$(document).ready(function() {
    $('#patientSelect').select2();
    $('#doctorSelect').select2();
});
</script>
