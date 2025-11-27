<?php

include "../auth/check_login.php";
include "../config/db.php";

// only staff can view this
if ($_SESSION['role'] != "staff") {
    die("Access denied.");
}

$id = $_GET['id'];

$sql = "DELETE FROM Doctor WHERE doctorID = $id";

if (mysqli_query($conn, $sql)) {
    header("Location: list.php");
} else {
    echo "Error: " . mysqli_error($conn);
}
