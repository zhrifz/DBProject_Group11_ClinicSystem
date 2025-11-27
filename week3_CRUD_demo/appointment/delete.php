<?php
include "../config/db.php";

$id = $_GET['id'];

$sql = "DELETE FROM Appointment WHERE appointmentID = $id";

if (mysqli_query($conn, $sql)) {
    header("Location: list.php");
} else {
    echo "Error: " . mysqli_error($conn);
}
