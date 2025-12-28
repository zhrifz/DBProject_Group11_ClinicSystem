<?php
include "../../auth/check_login.php";
include "../../config/db.php";

if ($_SESSION['role'] != "doctor") {
    http_response_code(403);
    exit(json_encode(['error'=>'Access denied']));
}

$id = $_GET['id'] ?? '';
if (!ctype_digit($id)) {
    http_response_code(400);
    exit(json_encode(['error'=>'Invalid appointment ID']));
}

$doctorID = $_SESSION['id'];

$sql = "SELECT a.*, p.full_name AS patient_name, p.gender AS patient_gender
        FROM Appointment a
        JOIN Patient p ON a.patientID = p.patientID
        WHERE a.appointmentID = ? AND a.doctorID = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "ii", $id, $doctorID);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$data = mysqli_fetch_assoc($result);

if($data) {
    echo json_encode($data);
} else {
    http_response_code(404);
    echo json_encode(['error'=>'Appointment not found']);
}
?>
