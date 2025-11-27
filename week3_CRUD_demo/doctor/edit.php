<?php
include "../auth/check_login.php";
include "../config/db.php";

// only staff can view this
if ($_SESSION['role'] != "staff") {
    die("Access denied.");
}

$id = $_GET['id'];

// Fetch doctor record
$sql = "SELECT * FROM Doctor WHERE doctorID = $id";
$result = mysqli_query($conn, $sql);
$doctor = mysqli_fetch_assoc($result);

// Convert working days string → array
$selected_days = explode(", ", $doctor['working_days']);

if (isset($_POST['update'])) {

    $username = $_POST['username'];
    $fullname = $_POST['full_name'];
    $special = $_POST['specialization'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];

    // Convert checkbox array → string
    $days = isset($_POST['working_days']) ? implode(", ", $_POST['working_days']) : "";

    $room = $_POST['room_no'];

    // Update password only if typed
    if (!empty($_POST['password'])) {
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $pass_sql = ", password='$password'";
    } else {
        $pass_sql = "";
    }

    $update = "UPDATE Doctor SET
                username='$username',
                full_name='$fullname',
                specialization='$special',
                phone='$phone',
                email='$email',
                working_days='$days',
                room_no='$room'
                $pass_sql
               WHERE doctorID=$id";

    if (mysqli_query($conn, $update)) {
        header("Location: list.php");
        exit;
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>

<h2>Edit Doctor</h2>

<form method="POST">

    Username: <input type="text" name="username" value="<?= $doctor['username'] ?>" required><br><br>

    New Password (optional):
    <input type="password" name="password">
    <small>Leave empty to keep current password</small>
    <br><br>

    Full Name: <input type="text" name="full_name" value="<?= $doctor['full_name'] ?>" required><br><br>
    Specialization: <input type="text" name="specialization" value="<?= $doctor['specialization'] ?>"><br><br>
    Phone: <input type="text" name="phone" value="<?= $doctor['phone'] ?>"><br><br>
    Email: <input type="email" name="email" value="<?= $doctor['email'] ?>"><br><br>

    <label>Working Days:</label><br>

    <?php
    $days_list = ["Monday","Tuesday","Wednesday","Thursday","Friday","Saturday","Sunday"];

    foreach ($days_list as $day) {
        $checked = in_array($day, $selected_days) ? "checked" : "";
        echo "<input type='checkbox' name='working_days[]' value='$day' $checked> $day<br>";
    }
    ?>

    <br>
    Room No: <input type="text" name="room_no" value="<?= $doctor['room_no'] ?>"><br><br>

    <button type="submit" name="update">Update</button>
</form>

<br>
<a href="list.php">Back</a>
