<?php
include "../auth/check_login.php";
include "../config/db.php";

// only staff can view this
if ($_SESSION['role'] != "staff") {
    die("Access denied.");
}

if (isset($_POST['submit'])) {

    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $fullname = $_POST['full_name'];
    $special = $_POST['specialization'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];

    // Convert checkbox array → string
    $days = isset($_POST['working_days']) ? implode(", ", $_POST['working_days']) : "";

    $room = $_POST['room_no'];

    $sql = "INSERT INTO Doctor 
            (username, password, full_name, specialization, phone, email, working_days, room_no)
            VALUES 
            ('$username', '$password', '$fullname', '$special', '$phone', '$email', '$days', '$room')";

    if (mysqli_query($conn, $sql)) {
        header("Location: list.php");
        exit;
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>

<h2>Add New Doctor</h2>

<form method="POST">

    Username: <input type="text" name="username" required><br><br>
    Password: <input type="password" name="password" required><br><br>

    Full Name: <input type="text" name="full_name" required><br><br>
    Specialization: <input type="text" name="specialization"><br><br>
    Phone: <input type="text" name="phone"><br><br>
    Email: <input type="email" name="email"><br><br>

    <label>Working Days:</label><br>
    <input type="checkbox" name="working_days[]" value="Monday"> Monday<br>
    <input type="checkbox" name="working_days[]" value="Tuesday"> Tuesday<br>
    <input type="checkbox" name="working_days[]" value="Wednesday"> Wednesday<br>
    <input type="checkbox" name="working_days[]" value="Thursday"> Thursday<br>
    <input type="checkbox" name="working_days[]" value="Friday"> Friday<br>
    <input type="checkbox" name="working_days[]" value="Saturday"> Saturday<br>
    <input type="checkbox" name="working_days[]" value="Sunday"> Sunday<br><br>

    Room No: <input type="text" name="room_no"><br><br>

    <button type="submit" name="submit">Save</button>
</form>

<br>
<a href="list.php">Back</a>
