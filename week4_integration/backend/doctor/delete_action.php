<?php

include "../../auth/check_login.php";
include "../../config/db.php";

// Only staff can delete doctors
if (!isset($_SESSION['role']) || $_SESSION['role'] !== "staff") {
    http_response_code(403);
    exit("Access denied.");
}

// Get doctor ID from GET
$id = $_GET['id'] ?? '';
if (!ctype_digit($id)) {
    echo "<script>alert('Invalid doctor!'); window.location.href='../../frontend/doctor/list.php';</script>";
    exit;
}

// --- Delete all appointments of the doctor first ---
$sqlDeleteAppointments = "DELETE FROM appointment WHERE doctorID = ?";
$stmtApp = mysqli_prepare($conn, $sqlDeleteAppointments);
mysqli_stmt_bind_param($stmtApp, "i", $id);
mysqli_stmt_execute($stmtApp);
mysqli_stmt_close($stmtApp);

// --- Delete the doctor ---
$sqlDeleteDoctor = "DELETE FROM doctor WHERE doctorID = ?";
$stmtDoc = mysqli_prepare($conn, $sqlDeleteDoctor);
mysqli_stmt_bind_param($stmtDoc, "i", $id);

if (mysqli_stmt_execute($stmtDoc)) {
    echo "<script>
        alert('Doctor and their appointments deleted successfully!');
        window.location.href='../../frontend/doctor/list.php';
    </script>";
    exit;
} else {
    echo "<script>
        alert('Unable to delete doctor!');
        window.location.href='../../frontend/doctor/list.php';
    </script>";
    exit;
}
?>
