<?php
session_start();
include "../config/db.php";

$error = "";
$login_success = false;

if (isset($_POST['login'])) {

    $username = $_POST['username'];
    $password = $_POST['password'];

    // ---- CHECK STAFF ----
    $staff_sql = "SELECT * FROM Staff WHERE username='$username'";
    $staff_res = mysqli_query($conn, $staff_sql);

    if (mysqli_num_rows($staff_res) == 1) {
        $staff = mysqli_fetch_assoc($staff_res);

        if (password_verify($password, $staff['password'])) {
            $_SESSION['role'] = "staff";
            $_SESSION['id'] = $staff['staffID'];
            $_SESSION['name'] = $staff['full_name'];

            $login_success = true;
            header("Location: ../dashboard/staff_dashboard.php");
            exit;
        }
    }

    // ---- CHECK DOCTOR ----
    $doc_sql = "SELECT * FROM Doctor WHERE username='$username'";
    $doc_res = mysqli_query($conn, $doc_sql);

    if (mysqli_num_rows($doc_res) == 1) {
        $doctor = mysqli_fetch_assoc($doc_res);

        if (password_verify($password, $doctor['password'])) {
            $_SESSION['role'] = "doctor";
            $_SESSION['id'] = $doctor['doctorID'];
            $_SESSION['name'] = $doctor['full_name'];

            $login_success = true;
            header("Location: ../dashboard/doctor_dashboard.php");
            exit;
        }
    }

    // If both fail:
    if (!$login_success) {
        $error = "Incorrect username or password!";
    }
}
?>


<h2>Login</h2>

<form method="POST">
    <label>Username:</label>
    <input type="text" name="username" required><br><br>

    <label>Password:</label>
    <input type="password" name="password" required><br><br>

    <button type="submit" name="login">Login</button>
</form>

<p style="color:red;"><?= $error ?></p>
