<?php
include "../auth/check_login.php";

if ($_SESSION['role'] != "doctor") {
    die("Access denied.");
}
?>

<h2>Welcome Dr. <?= $_SESSION['name'] ?></h2>

<a href="../appointment/list.php?doctor=<?= $_SESSION['id'] ?>">View My Appointments</a>
<br><br>

<a href="../auth/logout.php">Logout</a>
