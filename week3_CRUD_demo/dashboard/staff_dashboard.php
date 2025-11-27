<?php
include "../auth/check_login.php";

if ($_SESSION['role'] != "staff") {
    die("Access denied.");
}
?>

<h2>Welcome, <?= $_SESSION['name'] ?> (Staff)</h2>

<a href="../patient/list.php">Manage Patients</a><br>
<a href="../doctor/list.php">Manage Doctors</a><br>
<a href="../appointment/list.php">Manage Appointments</a><br>
<br>
<a href="../auth/logout.php">Logout</a>
