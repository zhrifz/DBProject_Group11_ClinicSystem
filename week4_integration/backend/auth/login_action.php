<?php
session_start();
include __DIR__ . '/../../config/db.php';

if (!isset($_POST['login'])) {
    header("Location: ../../auth/login.php");
    exit;
}

$username = trim($_POST['username']);
$password = $_POST['password'];
$role     = $_POST['role'];


// ======================
// STAFF LOGIN
// ======================
if ($role === 'staff') {

    $stmt = $conn->prepare("
        SELECT staffID, full_name, password 
        FROM Staff 
        WHERE username = ?
    ");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows === 1) {
        $staff = $res->fetch_assoc();

        if (password_verify($password, $staff['password'])) {
            $_SESSION['role'] = 'staff';
            $_SESSION['id']   = $staff['staffID'];
            $_SESSION['name'] = $staff['full_name'];

            header("Location: ../../frontend/dashboard/staff_dashboard.php");
            exit;
        }
    }
}


// ======================
// DOCTOR LOGIN
// ======================
if ($role === 'doctor') {

    $stmt = $conn->prepare("
        SELECT doctorID, full_name, password 
        FROM Doctor 
        WHERE username = ?
    ");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows === 1) {
        $doctor = $res->fetch_assoc();

        if (password_verify($password, $doctor['password'])) {
            $_SESSION['role'] = 'doctor';
            $_SESSION['id']   = $doctor['doctorID'];
            $_SESSION['name'] = $doctor['full_name'];

            header("Location: ../../frontend/dashboard/doctor_dashboard.php");
            exit;
        }
    }
}


// ======================
// FAILED
// ======================
$_SESSION['login_error'] = "Invalid login credentials";
header("Location: ../../auth/login.php");
exit;
