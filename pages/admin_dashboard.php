<?php
    session_start();

    // i f they are not logged in, OR if they are not an admin, send away.
    if (!isset($_SESSION['volunteer_id']) || $_SESSION['is_admin'] != 1) {
        header("Location: ../index.php");
        exit();
    }
?>

<!DOCTYPE HTML>
<html>
<head>
    <title>SLP Admin Dashboard</title>
</head>
<body>
    <div class="background-image">
        <!-- <img src="photos/logo.png" alt="background"> -->
    </div>

    <div class="logo-header">
        <div class="logo">
            <!-- <img src="photos/logo.png" alt="Logo"> -->
        </div>
        <h1>Summer Lunch <br> Program</h1>
    </div>
    
    <div class="admin-container">
        <h2>Summer Lunch Program <br> ADMIN</h2>
        
        <h3>Pending Volunteer Account Approvals</h3>
        <p>TEMP.</p>

        <br>
        <a href="../services/logout.php">Logout</a>
    </div>

</body>
</html>