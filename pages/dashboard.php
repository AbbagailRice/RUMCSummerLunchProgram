<?php
    //start the session
    session_start();

    //make sure the session var is set.
    //if it isnt redirect back to the index(start) page
    if (!isset($_SESSION['volunteer_id'])) {
        header("Location: ../index.php");
        exit();
    }

?>

<DOCTYPE HTML>
<html>

<head>
    <title> Summer Lunch Program Dashboard </title>
    <link rel="stylesheet" type="text/css" href="../CSS/styles.css">
</head>

<body>
    <div class="layout">
        <?php include '../includes/header.php'; ?>
        <div class="main-content">

            <div class ="workspace-container">
                <div class ="dashboard-workspace">
                    <h2>Welcome to the Dashboard!</h2>

                    <p>This is the dashboard page.</p>

                    <a href="../services/logout.php" class="btn-logout">
                        Logout
                    </a>
                </div>
            </div>
        </div>

        <?php include '../includes/sidebar.php'; ?>

    </div>
</body>

</html>