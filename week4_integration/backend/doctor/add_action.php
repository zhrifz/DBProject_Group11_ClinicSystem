<?php
session_start();

include "../../auth/check_login.php";
include "../../config/db.php";

// only staff can add doctors
if (!isset($_SESSION['role']) || $_SESSION['role'] !== "staff") {
    http_response_code(403);
    exit("Access denied.");
}

// must be POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../../frontend/doctor/add.php");
    exit;
}

// CSRF check
if (!isset($_POST['csrf_token'], $_SESSION['csrf_token']) ||
    $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    $_SESSION['error'] = "Invalid request.";
    header("Location: ../../frontend/doctor/add.php");
    exit;
}

// collect fields
$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';
$fullname = trim($_POST['full_name'] ?? '');
$special  = trim($_POST['specialization'] ?? '');
$phone    = trim($_POST['phone'] ?? '');
$email    = trim($_POST['email'] ?? '');
$room     = trim($_POST['room_no'] ?? '');

$days = isset($_POST['working_days'])
    ? implode(", ", (array)$_POST['working_days'])
    : "";

// required fields
if ($username === '' || $password === '' || $fullname === '') {
    $_SESSION['error'] = "Please fill in all required fields.";
    header("Location: ../../frontend/doctor/add.php");
    exit;
}

// hash password
$hash = password_hash($password, PASSWORD_DEFAULT);

// insert with prepared statement
$sql = "INSERT INTO Doctor 
    (username, password, full_name, specialization, phone, email, working_days, room_no)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = mysqli_prepare($conn, $sql);

if (!$stmt) {
    $_SESSION['error'] = "Something went wrong.";
    header("Location: ../../frontend/doctor/add.php");
    exit;
}

mysqli_stmt_bind_param(
    $stmt,
    "ssssssss",
    $username,
    $hash,
    $fullname,
    $special,
    $phone,
    $email,
    $days,
    $room
);

if (mysqli_stmt_execute($stmt)) {
    $_SESSION['success'] = "Doctor added successfully.";
    header("Location: ../../frontend/doctor/list.php");
    exit;
}

$_SESSION['error'] = "Could not save doctor.";
header("Location: ../../frontend/doctor/add.php");
exit;
