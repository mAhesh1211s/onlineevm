<?php
include("connect.php");

$name     = mysqli_real_escape_string($connect, $_POST['name']);
$mobile   = mysqli_real_escape_string($connect, $_POST['mobile']);
$password = $_POST['password'];
$cpassword= $_POST['cpassword'];
$address  = mysqli_real_escape_string($connect, $_POST['address']);
$role     = mysqli_real_escape_string($connect, $_POST['role']);

// Handle file upload
$image = $_FILES['photo']['name'];
$tmp_name = $_FILES['photo']['tmp_name'];
$imagePath = "../uploads/" . basename($image);

// Validate password match
if ($password !== $cpassword) {
    echo '<script>
        alert("Password and Confirm Password do not match!");
        window.location="../register.html";
    </script>';
    exit();
}

// Optional: File type and size validation
$allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
if (!in_array($_FILES['photo']['type'], $allowedTypes)) {
    echo '<script>
        alert("Only JPG and PNG images are allowed.");
        window.location="../register.html";
    </script>';
    exit();
}
if ($_FILES['photo']['size'] > 2 * 1024 * 1024) {
    echo '<script>
        alert("Image size should be less than 2MB.");
        window.location="../register.html";
    </script>';
    exit();
}

// Hash the password
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// Move the uploaded file
if (move_uploaded_file($tmp_name, $imagePath)) {
    $insert = mysqli_query($connect, "INSERT INTO voting (NAME, MOBILE, PASSWORD, ADDRESS, PHOTO, ROLE, STATUS, VOTES)
        VALUES ('$name', '$mobile', '$hashedPassword', '$address', '$image', '$role', 0, 0)");
    
    if ($insert) {
        echo '<script>
            alert("Registration successful!");
            window.location="../index.html";
        </script>';
    } else {
        echo '<script>
            alert("Database error. Please try again.");
            window.location="../register.html";
        </script>';
    }
} else {
    echo '<script>
        alert("Failed to upload image.");
        window.location="../register.html";
    </script>';
}
?>
