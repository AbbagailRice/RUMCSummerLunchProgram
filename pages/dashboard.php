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
    <link rel="stylesheet" type="text/css" href="../CSS/Style.css">
</head>

<body>
    <div class="background-image">
        <!-- <img src="../photos/logo.png" alt="background"> -->
    </div>

    <div class="logo-header">
        <div class="logo">
            <!-- <img src="../photos/logo.png" alt="Logo"> -->
        </div>
        <h1>Summer Lunch <br> Program</h1>
    </div>

    <div class="dashboard-card">
        <h2>Welcome to the Dashboard!</h2>
        <p>This is the dashboard page.</p>
        <a href="../services/logout.php" class="btn-logout">Logout</a>
    </div>

</body>

</html>