<?php
include "../config/db.php";

$id = $_GET['id'];

$sql = "DELETE FROM Patient WHERE patientID = $id";

if (mysqli_query($conn, $sql)) {
    header("Location: list.php");
} else {
    echo "Error deleting record: " . mysqli_error($conn);
}
?>
