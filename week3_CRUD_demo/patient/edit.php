<?php
include "../config/db.php";

$id = $_GET['id'];

$sql = "SELECT * FROM Patient WHERE patientID = $id";
$result = mysqli_query($conn, $sql);
$patient = mysqli_fetch_assoc($result);

if (isset($_POST['update'])) {

    $name = $_POST['full_name'];
    $gender = $_POST['gender'];
    $dob = $_POST['date_of_birth'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $emergency = $_POST['emergency_contact'];
    $age = $_POST['age']; // auto calculated age

    $update = "UPDATE Patient SET
                full_name='$name',
                age='$age',
                gender='$gender',
                date_of_birth='$dob',
                phone='$phone',
                address='$address',
                emergency_contact='$emergency'
               WHERE patientID=$id";

    if (mysqli_query($conn, $update)) {
        header("Location: list.php");
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>

<h2>Edit Patient</h2>

<form method="POST">
    Name: 
    <input type="text" name="full_name" value="<?= $patient['full_name'] ?>" required><br><br>

    Age: 
    <input type="number" name="age" id="age" value="<?= $patient['age'] ?>" readonly><br><br>

    Gender: 
    <select name="gender">
        <option <?= $patient['gender']=="Male" ? "selected" : "" ?>>Male</option>
        <option <?= $patient['gender']=="Female" ? "selected" : "" ?>>Female</option>
    </select><br><br>

    Date of Birth: 
    <input type="date" name="date_of_birth" id="dob" value="<?= $patient['date_of_birth'] ?>"><br><br>

    Phone: 
    <input type="text" name="phone" value="<?= $patient['phone'] ?>"><br><br>

    Address: 
    <textarea name="address"><?= $patient['address'] ?></textarea><br><br>

    Emergency Contact: 
    <input type="text" name="emergency_contact" value="<?= $patient['emergency_contact'] ?>"><br><br>

    <button type="submit" name="update">Update</button>
</form>

<br>
<a href="list.php">Back</a>

<script>
// auto calculate age when DOB changes
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
