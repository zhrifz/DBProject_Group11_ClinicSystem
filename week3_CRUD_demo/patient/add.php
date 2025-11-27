<?php

include "../auth/check_login.php";
include "../config/db.php";

// only staff can view this
if ($_SESSION['role'] != "staff") {
    die("Access denied.");
}


if (isset($_POST['submit'])) {

    $name = $_POST['full_name'];
    $gender = $_POST['gender'];
    $dob = $_POST['date_of_birth'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $emergency = $_POST['emergency_contact'];
    $age = $_POST['age']; // age from auto-calculated field

    $sql = "INSERT INTO Patient 
            (full_name, age, gender, date_of_birth, phone, address, emergency_contact)
            VALUES 
            ('$name', '$age', '$gender', '$dob', '$phone', '$address', '$emergency')";

    if (mysqli_query($conn, $sql)) {
        header("Location: list.php");
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>

<h2>Add New Patient</h2>

<form method="POST">

    Name: <input type="text" name="full_name" required><br><br>

    Date of Birth: 
    <input type="date" name="date_of_birth" id="dob" required><br><br>

    Age: <input type="number" name="age" id="age" readonly><br><br>

    Gender: 
    <select name="gender">
        <option value="">-- Choose --</option>
        <option>Male</option>
        <option>Female</option>
    </select><br><br>

    Phone: <input type="text" name="phone"><br><br>

    Address: <textarea name="address"></textarea><br><br>

    Emergency Contact: <input type="text" name="emergency_contact"><br><br>

    <button type="submit" name="submit">Save</button>
</form>

<br>
<a href="list.php">Back</a>

<script>
document.getElementById("dob").addEventListener("change", function() {
    let dob = new Date(this.value);
    let today = new Date();

    if (!isNaN(dob)) {
        let age = today.getFullYear() - dob.getFullYear();
        let month = today.getMonth() - dob.getMonth();

        if (month < 0 || (month === 0 && today.getDate() < dob.getDate())) {
            age--;
        }

        document.getElementById("age").value = age;
    }
});
</script>
