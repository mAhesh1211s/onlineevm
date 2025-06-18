<?php
session_start();
include("connect.php");

// Sanitize inputs
$mobile = mysqli_real_escape_string($connect, $_POST['mobile']);
$password = $_POST['password'];
$role = $_POST['role']; // Should be 'Voter' or 'Group'

// Use prepared statement
$stmt = $connect->prepare("SELECT * FROM voting WHERE mobile = ? AND role = ?");
$stmt->bind_param("ss", $mobile, $role); // both mobile and role are strings
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $userdata = $result->fetch_assoc();

    // ✅ Verify the hashed password stored in DB
    if (password_verify($password, $userdata['password'])) {

        // Get all groups (role = 'Group')
        $groups = mysqli_query($connect, "SELECT * FROM voting WHERE role = 'Group'");
        $groupsdata = mysqli_fetch_all($groups, MYSQLI_ASSOC);

        // Store data in session
        $_SESSION['userdata'] = $userdata;
        $_SESSION['groupsdata'] = $groupsdata;

        // Redirect to dashboard
        header("Location: ../api/dashboard.php");
        exit();

    } else {
        echo "<script>
            alert('Incorrect password.');
            window.location='../index.html';
        </script>";
    }
} else {
    echo "<script>
        alert('Invalid credentials or user not found.');
        window.location='../index.html';
    </script>";
}
?>
