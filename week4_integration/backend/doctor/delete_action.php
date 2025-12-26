<?php
session_start();

include "../../auth/check_login.php";
include "../../config/db.php";

// only staff can delete doctors
if (!isset($_SESSION['role']) || $_SESSION['role'] !== "staff") {
    http_response_code(403);
    exit("Access denied.");
}

// must be POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../../frontend/doctor/list.php");
    exit;
}

// CSRF token check
if (!isset($_POST['csrf_token'], $_SESSION['csrf_token']) ||
    $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    $_SESSION['error'] = "Invalid request.";
    header("Location: ../../frontend/doctor/list.php");
    exit;
}

// validate id
$id = $_POST['id'] ?? '';
if (!ctype_digit($id)) {
    $_SESSION['error'] = "Invalid doctor.";
    header("Location: ../../frontend/doctor/list.php");
    exit;
}

// delete using prepared statement
$sql = "DELETE FROM Doctor WHERE doctorID = ?";
$stmt = mysqli_prepare($conn, $sql);

if (!$stmt) {
    $_SESSION['error'] = "Something went wrong.";
    header("Location: ../../frontend/doctor/list.php");
    exit;
}

mysqli_stmt_bind_param($stmt, "i", $id);

if (mysqli_stmt_execute($stmt)) {
    $_SESSION['success'] = "Doctor deleted.";
    header("Location: ../../frontend/doctor/list.php");
    exit;
}

$_SESSION['error'] = "Unable to delete doctor.";
header("Location: ../../frontend/doctor/list.php");
exit;
