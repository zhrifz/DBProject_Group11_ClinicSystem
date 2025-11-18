<?php
include "../config/db.php";

$sql = "SELECT * FROM Patient";
$result = mysqli_query($conn, $sql);
?>

<h2>Patient List</h2>

<a href="add.php">Add New Patient</a>
<br><br>

<table border="1" cellpadding="8">
    <tr>
        <th>ID</th>
        <th>Full Name</th>
        <th>Age</th>
        <th>Gender</th>
        <th>Date of Birth</th>
        <th>Phone</th>
        <th>Address</th>
        <th>Emergency Contact</th>
        <th>Actions</th>
    </tr>

    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
        <tr>
            <td><?= $row['patientID'] ?></td>
            <td><?= $row['full_name'] ?></td>
            <td><?= $row['age'] ?></td>
            <td><?= $row['gender'] ?></td>
            <td><?= $row['date_of_birth'] ?></td>
            <td><?= $row['phone'] ?></td>
            <td><?= $row['address'] ?></td>
            <td><?= $row['emergency_contact'] ?></td>

            <td>
                <a href="edit.php?id=<?= $row['patientID'] ?>">Edit</a> |
                <a href="delete.php?id=<?= $row['patientID'] ?>" 
                   onclick="return confirm('Delete this patient?')">Delete</a>
            </td>
        </tr>
    <?php } ?>

</table>
