<?php
include "../../auth/check_login.php";
include "../../config/db.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] !== "staff") {
    http_response_code(403);
    exit("Access denied");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // CSRF check
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        exit("Invalid request");
    }

    $username = trim($_POST['username']);
    $password_raw = $_POST['password'];
    $fullname = trim($_POST['full_name']);
    $special = trim($_POST['specialization']);
    $phone = trim($_POST['phone']);
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $days = isset($_POST['working_days']) ? implode(", ", $_POST['working_days']) : "";
    $room = trim($_POST['room_no']);

    if (!$email) exit("Invalid email");

    $password = password_hash($password_raw, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("
        INSERT INTO Doctor 
        (username, password, full_name, specialization, phone, email, working_days, room_no)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)
    ");

    $stmt->bind_param(
        "ssssssss",
        $username,
        $password,
        $fullname,
        $special,
        $phone,
        $email,
        $days,
        $room
    );

    if ($stmt->execute()) {
        header("Location: ../../frontend/doctor/list.php");
        exit;
    }

    error_log("DB Error: " . $stmt->error);
    exit("Something went wrong");
}
?>
