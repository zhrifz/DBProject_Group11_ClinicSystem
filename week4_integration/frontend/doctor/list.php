<?php
include "../../auth/check_login.php";
include "../../config/db.php";

if ($_SESSION['role'] != "staff") {
    die("Access denied.");
}

$sql = "SELECT * FROM Doctor";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Doctor List</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
<script src="https://kit.fontawesome.com/2d3e5b1abc.js" crossorigin="anonymous"></script>

<style>
body {
    margin: 0;
    font-family: Poppins, sans-serif;
    background: #f3f0ff;
    display: flex;
}

/* SIDEBAR */
.sidebar {
    width: 250px;
    height: 100vh;
    position: fixed;
    left: 0;
    top: 0;
    background: linear-gradient(180deg, #8ab6ff, #c7a3ff);
    padding: 20px;
    color: white;
}
.sidebar h2 {
    text-align:center;
    margin-bottom:30px;
}
.sidebar a {
    display:block;
    padding:12px 15px;
    border-radius:10px;
    text-decoration:none;
    color:white;
    margin-bottom:10px;
    transition:0.2s ease;
}
.sidebar a:hover {
    background: rgba(255,255,255,0.3);
    transform: translateX(5px);
}

/* MAIN AREA */
.main {
    margin-left: 260px;
    padding: 40px;
    width: calc(100% - 250px);
}

/* HEADER */
.header {
    background:white;
    padding:25px 30px;
    border-radius:15px;
    box-shadow:0 3px 12px rgba(0,0,0,0.08);
    margin-bottom:30px;
}
.header h1 {
    margin:0;
    font-size:26px;
    font-weight:600;
}

/* TOP BUTTONS */
.top-buttons {
    margin-bottom: 20px;
}
.top-buttons a {
    padding:10px 16px;
    background:#7b6dff;
    color:white;
    text-decoration:none;
    border-radius:10px;
    font-size:14px;
    font-weight:500;
    margin-right:10px;
}
.top-buttons a:hover {
    background:#5e52d5;
}

/* TABLE CARD */
.table-card {
    background:white;
    padding:25px;
    border-radius:18px;
    box-shadow:0 4px 12px rgba(0,0,0,0.08);
}

table {
    width:100%;
    border-collapse:collapse;
    margin-top:10px;
}

th {
    background:#8ab6ff;
    color:white;
    padding:12px;
    font-size:14px;
    text-align:left;
}

td {
    padding:10px 12px;
    border-bottom:1px solid #ddd;
    font-size:14px;
}

tr:hover {
    background:#f0f6ff;
}

/* ACTION BUTTONS */
.action-btn { display:flex; flex-direction:column; gap:6px; align-items:flex-start; }
.action-btn a { padding:8px 14px; font-size:13px; border-radius:8px; text-decoration:none; color:white; width:60%; text-align:center; }
.edit-btn { background:#f0ad4e; } .edit-btn:hover { background:#e29a27; }
.delete-btn { background:#d9534f; } .delete-btn:hover { background:#c9302c; }

</style>

</head>
<body>

<!-- SIDEBAR -->
<div class="sidebar">
    <h2>Clinic Admin</h2>
    <a href="../dashboard/staff_dashboard.php"><i class="fa-solid fa-gauge"></i> Dashboard</a>
    <a href="../patient/list.php"><i class="fa-solid fa-hospital-user"></i> Patients</a>
    <a href="list.php"><i class="fa-solid fa-user-doctor"></i> Doctors</a>
    <a href="../appointment/list.php"><i class="fa-solid fa-calendar-check"></i> Appointments</a>
</div>

<!-- MAIN -->
<div class="main">

    <div class="header">
        <h1>Doctor List</h1>
    </div>

    <div class="top-buttons">
        <a href="add.php">➕ Add New Doctor</a>
        <a href="../dashboard/staff_dashboard.php"><i class="fa-solid fa-arrow-left"></i> Back</a>
    </div>

    <div class="table-card">
        <table>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Specialization</th>
                <th>Working Days</th>
                <th>Actions</th>
            </tr>

            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <td><?= $row['doctorID'] ?></td>
                    <td><?= htmlspecialchars($row['full_name']) ?></td>
                    <td><?= htmlspecialchars($row['email']) ?></td>
                    <td><?= htmlspecialchars($row['specialization']) ?></td>
                    <td><?= htmlspecialchars($row['working_days']) ?></td>
                    <td class="action-btn">
                        <a class="edit-btn" href="edit.php?id=<?= $row['doctorID'] ?>">Edit</a>
                        <a class="delete-btn" 
                           href="../../backend/doctor/delete_action.php?id=<?= $row['doctorID'] ?>"
                           onclick="return confirm('Delete this doctor?')">
                           Delete
                        </a>
                    </td>
                </tr>
            <?php } ?>
        </table>
    </div>

</div>

</body>
</html>
