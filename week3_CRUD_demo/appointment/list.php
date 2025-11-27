<?php
include "../config/db.php";

$sql = "SELECT 
            Appointment.*, 
            Patient.full_name AS patient_name, 
            Doctor.full_name AS doctor_name
        FROM Appointment
        JOIN Patient ON Appointment.patientID = Patient.patientID
        JOIN Doctor ON Appointment.doctorID = Doctor.doctorID
        ORDER BY appointment_time DESC";

$result = mysqli_query($conn, $sql);
?>

<h2>Appointment List</h2>

<a href="add.php">Create Appointment</a>
<br><br>

<a href="../dashboard/staff_dashboard.php">⬅ Back to Dashboard</a>

<table border="1" cellpadding="8">
    <tr>
        <th>ID</th>
        <th>Appointment No.</th>
        <th>Patient</th>
        <th>Doctor</th>
        <th>Reason</th>
        <th>Date & Time</th>
        <th>Status</th>
        <th>Actions</th>
    </tr>

    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
    <tr>
        <td><?= $row['appointmentID'] ?></td>
        <td><?= $row['appointment_number'] ?></td>
        <td><?= $row['patient_name'] ?></td>
        <td><?= $row['doctor_name'] ?></td>
        <td><?= $row['reason_for_appointment'] ?></td>
        <td><?= $row['appointment_time'] ?></td>
        <td><?= $row['status'] ?></td>

        <td>
            <a href="edit.php?id=<?= $row['appointmentID'] ?>">Edit</a> |
            <a href="delete.php?id=<?= $row['appointmentID'] ?>" onclick="return confirm('Delete appointment?')">Delete</a>
        </td>
    </tr>
    <?php } ?>

</table>
