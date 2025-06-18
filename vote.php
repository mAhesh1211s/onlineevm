<?php
session_start();
include("connect.php");

if (isset($_POST['votebtn'])) {
    $votes = $_POST['groupvotes'];
    $groupid = $_POST['groupid'];
    $userid = $_SESSION['userdata']['ID'];

    // Increment vote count
    $newVotes = $votes + 1;
    $updateVotes = mysqli_query($connect, "UPDATE voting SET VOTES='$newVotes' WHERE ID='$groupid'");

    // Update user status to voted
    $updateStatus = mysqli_query($connect, "UPDATE voting SET STATUS=1 WHERE ID='$userid'");

    if ($updateVotes && $updateStatus) {
        // Refresh user session data
        $userData = mysqli_fetch_array(mysqli_query($connect, "SELECT * FROM voting WHERE ID='$userid'"));
        $_SESSION['userdata'] = $userData;

        echo '<script>
            alert("Vote successfully submitted!");
            window.location="../dashboard.php";
        </script>';
    } else {
        echo '<script>
            alert("An error occurred. Please try again.");
            window.location="../dashboard.php";
        </script>';
    }
} else {
    header("Location: ../dashboard.php");
}
?>
