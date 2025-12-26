<?php
include "../../auth/check_login.php";
include "../../config/db.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] !== "staff") {
    http_response_code(403);
    exit("Access denied");
}

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) exit("Invalid request");

$stmt = $conn->prepare("SELECT * FROM Doctor WHERE doctorID = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$doctor = $result->fetch_assoc();

if (!$doctor) exit("Doctor not found");

$selected_days = explode(", ", $doctor['working_days']);
$days_list = ["Monday","Tuesday","Wednesday","Thursday","Friday","Saturday","Sunday"];

$_SESSION['csrf_token'] = bin2hex(random_bytes(32));
?>
