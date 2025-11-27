<?php

include "../auth/check_login.php";
include "../config/db.php";

// only staff can view this
if ($_SESSION['role'] != "staff") {
    die("Access denied.");
}

$sql = "SELECT * FROM Doctor";
$result = mysqli_query($conn, $sql);
?>

<h2>Doctor List</h2>

<a href="add.php">Add New Doctor</a>
<br><br>

<a href="../dashboard/staff_dashboard.php">⬅ Back to Dashboard</a>
<table border="1" cellpadding="8">
    <tr>
        <th>ID</th>
        <th>Username</th>
        <th>Full Name</th>
        <th>Specialization</th>
        <th>Phone</th>
        <th>Email</th>
        <th>Working Days</th>
        <th>Room</th>
        <th>Actions</th>
    </tr>

    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
        <tr>
            <td><?= $row['doctorID'] ?></td>
            <td><?= $row['username'] ?></td>
            <td><?= $row['full_name'] ?></td>
            <td><?= $row['specialization'] ?></td>
            <td><?= $row['phone'] ?></td>
            <td><?= $row['email'] ?></td>
            <td><?= $row['working_days'] ?></td>
            <td><?= $row['room_no'] ?></td>
            <td>
                <a href="edit.php?id=<?= $row['doctorID'] ?>">Edit</a> |
                <a href="delete.php?id=<?= $row['doctorID'] ?>" onclick="return confirm('Delete this doctor?')">Delete</a>
            </td>
        </tr>
    <?php } ?>

</table>
