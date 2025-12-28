<?php
include "../../auth/check_login.php";
include "../../config/db.php";

if ($_SESSION['role'] != "doctor") {
    http_response_code(403);
    exit(json_encode(['status'=>'error', 'message'=>'Access denied']));
}

// Get JSON input
$input = json_decode(file_get_contents('php://input'), true);
$appointmentID = $input['appointmentID'] ?? '';
$comment = $input['comment'] ?? '';

if (!ctype_digit($appointmentID) || trim($comment) === '') {
    http_response_code(400);
    exit(json_encode(['status'=>'error', 'message'=>'Invalid input']));
}

$doctorID = $_SESSION['id'];

// Update doctor_comment
$sql = "UPDATE Appointment SET doctor_comment = ? WHERE appointmentID = ? AND doctorID = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "sii", $comment, $appointmentID, $doctorID);

if(mysqli_stmt_execute($stmt)) {
    echo json_encode(['status'=>'success']);
} else {
    echo json_encode(['status'=>'error', 'message'=>'Database error']);
}
?>
