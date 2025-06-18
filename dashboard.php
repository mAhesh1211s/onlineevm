<?php
session_start();
if (!isset($_SESSION['userdata'])) {
    echo '<script>
        alert("Please login first.");
        window.location = "index.html";
    </script>';
    exit();
}
$userdata = $_SESSION['userdata'];
$groupsdata = $_SESSION['groupsdata'] ?? [];
$status = $userdata['status'] == 0 ? '<span style="color:red;">Not Voted</span>' : '<span style="color:green;">Voted</span>';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Online Voting System - Dashboard</title>
    <hr>
    <style>
        body {
            background-color: #b4e197;
            font-family: Arial, sans-serif;
        }

        #headerSection {
            padding: 10px;
            text-align: center;
        }

        h1 {
            font-family: 'Comic Sans MS', cursive;
        }

        #mainSection {
            display: flex;
            justify-content: space-around;
            padding: 20px;
        }

        .profile, .groups {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            width: 40%;
            box-shadow: 2px 2px 10px rgba(0,0,0,0.2);
        }

        .profile img {
            width: 100px;
            height: 100px;
        }

        .group {
            border-bottom: 1px solid #ccc;
            padding: 10px 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .group img {
            height: 60px;
            width: 60px;
            object-fit: contain;
        }

        button {
            padding: 5px 15px;
            background-color: #3399ff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button[disabled] {
            background-color: #aaa;
        }

        #topRightButtons {
            position: absolute;
            right: 20px;
            top: 20px;
        }

        #topRightButtons form {
            display: inline;
        }

        #topRightButtons button {
            margin-left: 10px;
        }
    </style>
</head>
<body>

    <div id="headerSection">
        <h1>Online Voting System</h1>
    </div>
<hr>
    <div id="topRightButtons">
        <form action="logout.php" method="POST">
            <button type="submit">Logout</button>
        </form>
    </div>

    <div id="mainSection">
        <!-- Left section: User profile -->
        
        <div class="profile">
            
            <img src="king.png" alt="User Image"><br><br>   
            <p><strong>Name:</strong> <?php echo htmlspecialchars($userdata['name']); ?></p>
            <p><strong>Mobile:</strong> <?php echo htmlspecialchars($userdata['mobile']); ?></p>
            <p><strong>Address:</strong> <?php echo htmlspecialchars($userdata['address']); ?></p>
            <p><strong>Status:</strong> <?php echo $status; ?></p>
        </div>

        <!-- Right section: Group list -->
        <div class="groups">
            <?php
            if (count($groupsdata) > 0) {
                foreach ($groupsdata as $group) {
                    echo '
                    <div class="group">
                        <div>
                            <p><strong>Group Name:</strong> ' . htmlspecialchars($group['name']) . '</p>
                            <p>Votes: ' . $group['votes'] . '</p>';
                    if ($userdata['status'] == 0) {
                        echo '
                            <form action="api/vote.php" method="POST">
                                <input type="hidden" name="groupvotes" value="' . $group['votes'] . '">
                                <input type="hidden" name="groupid" value="' . $group['id'] . '">
                                <input type="hidden" name="userid" value="' . $userdata['id'] . '">
                                <button type="submit">Vote</button>
                            </form>';
                    } else {
                        echo '<button disabled>Vote</button>';
                    }

                    echo '</div>
                        <img src="uploads/' . htmlspecialchars($group['photo']) . '" alt="Group Image">
                    </div>';
                }
            } else {
                echo '<p>No groups available at the moment.</p>';
            }
            ?>
        </div>
    </div>

</body>
</html>
